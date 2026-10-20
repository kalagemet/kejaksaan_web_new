<?php

use App\Controllers\Api\User;
use App\Filters\AdminOnly;
use App\Libraries\Access;
use App\Libraries\GoogleOAuth;
use App\Libraries\Unikit;
use App\Libraries\UploadStorage;
use App\Models\UserModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Config\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

final class SecurityRegressionTest extends CIUnitTestCase
{
    private array $oauthEnvironment = [];

    protected function setUp(): void
    {
        parent::setUp();
        Services::resetSingle('response');
        Services::resetSingle('request');
        Services::resetSingle('validation');
        foreach (['GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET'] as $key) {
            $this->oauthEnvironment[$key] = getenv($key);
        }
        require_once APPPATH . 'Database/Migrations/2026-10-20-000001_AlignUserSchema.php';
        (new \App\Database\Migrations\AlignUserSchema(Database::forge('tests')))->up();
        Database::connect('tests')->table('user')->emptyTable();
        session()->remove(['is_login', 'user_id', 'role', 'status', 'google_oauth']);
    }

    protected function tearDown(): void
    {
        foreach ($this->oauthEnvironment as $key => $value) {
            putenv($value === false ? $key : "$key=$value");
        }
        parent::tearDown();
    }

    private function controller(array $post = []): User
    {
        $request = Services::request();
        $request->setGlobal('post', $post);
        $controller = new User();
        $controller->initController($request, Services::response(), Services::logger());
        return $controller;
    }

    public function testRegistrationNeverChangesExistingCredentials(): void
    {
        foreach (['0', '1'] as $status) {
            $model = $this->getMockBuilder(UserModel::class)->onlyMethods(['first', 'insert', 'update'])->getMock();
            $model->method('first')->willReturn(['user_id' => 1, 'status' => $status]);
            $model->expects($this->never())->method('insert');
            $model->expects($this->never())->method('update');
            $validation = $this->getMockBuilder(Unikit::class)->onlyMethods(['validation'])->getMock();
            $controller = $this->controller([
                'nama' => 'Attacker', 'email' => 'admin@example.com',
                'password' => 'replacement123', 'password_confirmation' => 'replacement123',
            ]);
            $controller->userModel = $model;
            $controller->unikit = $validation;
            $this->assertSame(409, $controller->doRegister()->getStatusCode());
        }
    }

    public function testBrowserSuppliedOAuthIdentityIsRejected(): void
    {
        $response = $this->controller(['id' => 'forged', 'email' => 'admin@example.com'])->oauth_check_user();
        $this->assertSame(410, $response->getStatusCode());
        $this->assertFalse((bool) session('is_login'));
    }

    public function testOnlyActiveDatabaseAdministratorCanAccessCms(): void
    {
        $model = new UserModel();
        foreach ([['user', '1', false], ['admin', '0', false], ['admin', '1', true]] as [$role, $status, $allowed]) {
            $id = $model->insert([
                'email' => bin2hex(random_bytes(4)) . '@example.com', 'password' => 'unused',
                'role' => $role, 'status' => $status,
            ]);
            session()->set(['is_login' => true, 'user_id' => $id, 'role' => 'admin']);
            $result = (new AdminOnly())->before(Services::request());
            if ($allowed) {
                $this->assertNull($result);
            } else {
                $this->assertSame(302, $result->getStatusCode());
                $this->assertSame(site_url(), $result->getHeaderLine('Location'));
            }
        }
    }

    public function testUnauthorizedAdminApiStillReturnsForbiddenJson(): void
    {
        $request=Services::request();
        $request->getUri()->setPath('/api/setting');
        $response=(new AdminOnly())->before($request);
        $this->assertSame(403,$response->getStatusCode());
        $this->assertStringContainsString('Akses administrator',$response->getBody());
    }

    public function testAuthenticatedAdministratorSkipsAdminLogin(): void
    {
        $id=(new UserModel())->insert(['email'=>'admin@example.com','password'=>'unused','role'=>'admin','status'=>'1']);
        session()->set(['is_login'=>true,'user_id'=>$id,'role'=>'user']);
        $response=(new \App\Controllers\Utils())->login();
        $this->assertSame(site_url('admin'),$response->getHeaderLine('Location'));
    }

    public function testInactiveAccountCannotLogIn(): void
    {
        (new UserModel())->insert([
            'email' => 'inactive@example.com', 'password' => password_hash('test-password', PASSWORD_DEFAULT),
            'status' => '0', 'role' => 'admin',
        ]);
        $this->assertFalse((new Access())->login('inactive@example.com', 'test-password')['status']);
        $this->assertFalse((bool) session('is_login'));
    }

    public function testOAuthStateIsOneUseAndMustMatch(): void
    {
        session()->set('google_oauth', ['state' => 'expected', 'created' => time()]);
        try {
            (new GoogleOAuth())->identity('code', 'forged', 'http://example.com/callback');
            $this->fail('Invalid state was accepted.');
        } catch (RuntimeException $e) {
            $this->assertNull(session('google_oauth'));
        }
    }

    public function testOAuthExchangesCodeBeforeReadingVerifiedIdentity(): void
    {
        putenv('GOOGLE_CLIENT_ID=test-client');
        putenv('GOOGLE_CLIENT_SECRET=test-secret');
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'server-token'])),
            new Response(200, [], json_encode(['sub' => 'google-subject', 'email' => 'verified@example.com', 'email_verified' => true])),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);
        session()->set('google_oauth', ['state' => 'expected', 'created' => time()]);
        $profile = (new GoogleOAuth($client))->identity('code', 'expected', 'http://example.com/callback');
        $this->assertSame('google-subject', $profile['sub']);
        $this->assertSame('Bearer server-token', $mock->getLastRequest()->getHeaderLine('Authorization'));
        $this->assertNull(session('google_oauth'));
    }

    public function testUploadRejectsExecutableExtensionEvenWithImageMime(): void
    {
        $file = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()
            ->onlyMethods(['isValid', 'hasMoved', 'getSize', 'getMimeType', 'getClientExtension'])->getMock();
        $file->method('isValid')->willReturn(true);
        $file->method('hasMoved')->willReturn(false);
        $file->method('getSize')->willReturn(100);
        $file->method('getMimeType')->willReturn('image/jpeg');
        $file->method('getClientExtension')->willReturn('php');
        $this->expectException(InvalidArgumentException::class);
        UploadStorage::validate($file);
    }

    public function testFileRemovalCannotEscapeUploadDirectory(): void
    {
        $root = WRITEPATH . 'cache/upload-test-' . bin2hex(random_bytes(5));
        mkdir($root . '/uploads', 0775, true);
        file_put_contents($root . '/outside.txt', 'keep');
        UploadStorage::remove('../outside.txt', $root . '/uploads');
        $this->assertFileExists($root . '/outside.txt');
        unlink($root . '/outside.txt');
        rmdir($root . '/uploads');
        rmdir($root);
    }

    public function testUserMigrationCanRunTwiceWithoutChangingAccounts(): void
    {
        $model = new UserModel();
        $id = $model->insert(['email' => 'preserve@example.com', 'password' => 'existing', 'role' => 'admin', 'status' => '1']);
        (new \App\Database\Migrations\AlignUserSchema(Database::forge('tests')))->up();
        $this->assertSame('existing', $model->find($id)['password']);
        $this->assertSame('admin', $model->find($id)['role']);
    }

    public function testEditingNewsPreservesPublishedSlug(): void
    {
        $request = Services::request();
        $request->setGlobal('post', ['judul' => 'Judul diperbarui', 'isi' => 'Isi berita', 'jenis' => 'berita']);
        $model = $this->getMockBuilder(\App\Models\BeritaModel::class)->onlyMethods(['find', 'update'])->getMock();
        $model->method('find')->willReturn(['id' => 7, 'slug' => 'url-publik-lama', 'gambar' => null]);
        $model->expects($this->once())->method('update')->with(7, $this->callback(function ($data) {
            return $data['slug'] === 'url-publik-lama' && $data['judul'] === 'Judul diperbarui';
        }))->willReturn(true);
        $controller = new \App\Controllers\Api\Berita();
        $controller->initController($request, Services::response(), Services::logger());
        $controller->m_pages = $model;
        $this->assertSame(200, $controller->save(7)->getStatusCode());
    }

    public function testDocumentEditIgnoresClientSuppliedOldFile(): void
    {
        $request = Services::request();
        $request->setGlobal('post', ['judul' => 'Dokumen', 'file_lama' => '../../outside.php']);
        $model = $this->getMockBuilder(\App\Models\DokumenModel::class)->onlyMethods(['find', 'update'])->getMock();
        $model->method('find')->willReturn(['id' => 8, 'slug' => 'dokumen-lama', 'file' => 'keep.pdf']);
        $model->expects($this->once())->method('update')->with(8, $this->callback(function ($data) {
            return !array_key_exists('file', $data) && $data['slug'] === 'dokumen-lama';
        }))->willReturn(true);
        $controller = new \App\Controllers\Api\Dokumen();
        $controller->initController($request, Services::response(), Services::logger());
        $controller->m_pages = $model;
        $this->assertSame(200, $controller->save(8)->getStatusCode());
    }

    public function testCsrfRejectsUploadWithoutToken(): void
    {
        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', []);
        $security = new \CodeIgniter\Security\Security(new \Config\Security());
        $this->expectException(\CodeIgniter\Security\Exceptions\SecurityException::class);
        $security->verify($request);
    }

    public function testCsrfAcceptsEditorUploadHeader(): void
    {
        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', []);
        $security = new \CodeIgniter\Security\Security(new \Config\Security());
        $request->setHeader('X-CSRF-TOKEN', $security->getHash());
        $this->assertSame($security, $security->verify($request));
    }

    public function testEveryExplicitRoutePointsToExistingMethod(): void
    {
        $routes = Services::routes();
        require APPPATH . 'Config/Routes.php';
        $this->assertNotEmpty($routes->getRoutes('get'));
        foreach (['get', 'post', 'delete'] as $verb) {
            foreach ($routes->getRoutes($verb) as $path => $handler) {
                if (!is_string($handler) || strpos($handler, '::') === false) {
                    continue;
                }
                [$class, $method] = explode('::', $handler, 2);
                $method = explode('/', $method)[0];
                $this->assertTrue(method_exists($class, $method), "$verb $path points to missing $class::$method");
            }
        }
    }

    public function testLegacyMigrationsAndUserMappingWorkTogether(): void
    {
        require_once APPPATH . 'Database/Migrations/2023-12-11-062204_CreateUserTable.php';
        require_once APPPATH . 'Database/Migrations/2023-12-11-064905_UpdateTabelUser.php';
        $connection = Database::connect(array_merge(config('Database')->tests, ['DBPrefix' => 'isolated_']), false);
        $forge = Database::forge($connection);
        (new \App\Database\Migrations\CreateUserTable($forge))->up();
        (new \App\Database\Migrations\UpdateTabelUser($forge))->up();
        $connection->table('users')->insert([
            'id' => 12, 'email' => 'legacy@example.com', 'username' => 'Legacy', 'password' => 'existing-hash',
        ]);
        (new \App\Database\Migrations\AlignUserSchema($forge))->up();
        $user = $connection->table('user')->where('user_id', 12)->get()->getRowArray();
        $this->assertSame('existing-hash', $user['password']);
        $this->assertSame('Legacy', $user['nama_lengkap']);
        $this->assertSame('0', $user['status']);
        $this->assertSame('user', $user['role']);
        $connection->close();
    }

    public function testPermohonanListIsLimitedToSessionUser(): void
    {
        $forge = Database::forge('tests');
        $forge->addField([
            'user_id' => ['type' => 'INT'], 'subyek' => ['type' => 'TEXT'],
            'tgl_pertanyaan' => ['type' => 'DATETIME'],
        ]);
        $forge->createTable('permohonan', true);
        $db = Database::connect('tests');
        $db->table('permohonan')->emptyTable();
        $db->table('permohonan')->insertBatch([
            ['user_id' => 1, 'subyek' => 'Milik pengguna pertama', 'tgl_pertanyaan' => '2026-10-20 10:00:00'],
            ['user_id' => 2, 'subyek' => 'Rahasia pengguna kedua', 'tgl_pertanyaan' => '2026-10-20 10:00:00'],
        ]);
        session()->set('user_id', 1);
        $response = $this->controller()->getPermohonanByUser();
        $rows = json_decode($response->getBody(), true)['data'];
        $this->assertCount(1, $rows);
        $this->assertSame('Milik pengguna pertama', $rows[0]['subyek']);
    }

    public function testEmployeeSaveUsesEmployeeFieldsAndModel(): void
    {
        $forge = Database::forge('tests');
        $forge->addField(['jenis_alias' => ['type' => 'VARCHAR', 'constraint' => 64]]);
        $forge->createTable('ms_jenis_pegawai', true);
        $db = Database::connect('tests');
        $db->table('ms_jenis_pegawai')->emptyTable();
        $db->table('ms_jenis_pegawai')->insert(['jenis_alias' => 'jaksa']);
        $request = Services::request();
        $request->setGlobal('post', ['nama' => 'Pegawai', 'jenis' => 'jaksa', 'urutan' => '3', 'role' => 'admin']);
        $model = $this->getMockBuilder(\App\Models\PegawaiModel::class)->onlyMethods(['find', 'update'])->getMock();
        $model->method('find')->willReturn(['id' => 10, 'foto' => null]);
        $model->expects($this->once())->method('update')->with(10, $this->callback(function ($data) {
            return $data['nama'] === 'Pegawai' && $data['urutan'] === 3 && !isset($data['role']);
        }))->willReturn(true);
        $controller = new \App\Controllers\Api\Pegawai();
        $controller->initController($request, Services::response(), Services::logger());
        $controller->m_pegawai = $model;
        $this->assertSame(200, $controller->save(10)->getStatusCode());
    }

    public function testMissingUploadIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        UploadStorage::validate(null);
    }
}
