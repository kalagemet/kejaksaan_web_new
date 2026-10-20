<?php

use App\Controllers\Api\Ulasan;
use App\Models\UlasanModel;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Config\Services;

final class UlasanTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Services::resetSingle('response');
        Services::resetSingle('request');
        Services::resetSingle('validation');
        require_once APPPATH . 'Database/Migrations/2026-10-20-000002_CreateUlasanTable.php';
        (new \App\Database\Migrations\CreateUlasanTable(Database::forge('tests')))->up();
        Database::connect('tests')->table('dt_ulasan')->emptyTable();
    }

    private function input(): array
    {
        return [
            'nama' => 'Pengunjung Website', 'no_hp' => '081234567890',
            'email' => 'pengunjung@example.com', 'isi' => 'Pelayanan sudah baik, mohon informasi lebih lengkap.',
            'rating' => '4',
        ];
    }

    private function submit(array $input)
    {
        $request = Services::request();
        $request->setMethod('post');
        $request->setGlobal('post', $input);
        $controller = new Ulasan();
        $controller->initController($request, Services::response(), Services::logger());
        return $controller->save();
    }

    public function testVisitorCanSubmitWithoutLoggingIn(): void
    {
        session()->remove(['is_login', 'user_id', 'role']);
        $response = $this->submit($this->input());
        $this->assertSame(201, $response->getStatusCode());
        $row = (new UlasanModel())->first();
        $this->assertSame('Pengunjung Website', $row['nama']);
        $this->assertSame('081234567890', $row['no_hp']);
        $this->assertSame('pengunjung@example.com', $row['email']);
        $this->assertSame(4, (int) $row['rating']);
        $this->assertNotEmpty($row['created_at']);
        $json = json_decode($response->getBody(), true);
        $this->assertTrue($json['success']);
        $this->assertArrayNotHasKey('email', $json);
        $this->assertArrayNotHasKey('data', $json);
    }

    public function testPhoneFormattingAndWhitespaceAreNormalized(): void
    {
        $response = $this->submit(array_merge($this->input(), ['nama' => '  Pengunjung  ', 'no_hp' => '+62 (812) 3456-7890']));
        $this->assertSame(201, $response->getStatusCode());
        $row = (new UlasanModel())->first();
        $this->assertSame('Pengunjung', $row['nama']);
        $this->assertSame('+6281234567890', $row['no_hp']);
    }

    /** @dataProvider invalidInput */
    public function testInvalidReviewsAreNotSaved(string $field, $value): void
    {
        $response = $this->submit(array_merge($this->input(), [$field => $value]));
        $this->assertSame(422, $response->getStatusCode());
        $this->assertArrayHasKey($field, json_decode($response->getBody(), true)['errors']);
        $this->assertSame(0, (new UlasanModel())->countAllResults());
    }

    public function invalidInput(): array
    {
        return [
            'empty name' => ['nama', ''], 'array name' => ['nama', ['invalid']],
            'invalid phone' => ['no_hp', 'bukan-nomor'], 'short phone' => ['no_hp', '123'],
            'invalid email' => ['email', 'alamat-salah'], 'missing review' => ['isi', ''],
            'short review' => ['isi', 'baik'], 'long review' => ['isi', str_repeat('a', 3001)],
            'missing rating' => ['rating', null], 'zero rating' => ['rating', '0'],
            'high rating' => ['rating', '6'], 'decimal rating' => ['rating', '4.5'],
            'array rating' => ['rating', ['5']],
        ];
    }

    public function testVisitorCannotChooseIdOrTimestamps(): void
    {
        $response = $this->submit(array_merge($this->input(), ['id' => 999999, 'created_at' => '2000-01-01 00:00:00']));
        $this->assertSame(201, $response->getStatusCode());
        $row = (new UlasanModel())->first();
        $this->assertNotSame(999999, (int) $row['id']);
        $this->assertNotSame('2000-01-01 00:00:00', $row['created_at']);
    }

    public function testAdminListingIsPaginatedNewestFirst(): void
    {
        $model = new UlasanModel();
        for ($i = 1; $i <= 21; $i++) {
            $model->insert(array_merge($this->input(), ['nama' => 'Pengunjung ' . $i]));
        }
        $rows = $model->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->paginate(20, 'ulasan');
        $this->assertCount(20, $rows);
        $this->assertSame('Pengunjung 21', $rows[0]['nama']);
        $this->assertSame(2, $model->pager->getPageCount('ulasan'));
    }

    public function testOnlyAdminRouteCanReadReviews(): void
    {
        $routes = Services::routes();
        require APPPATH . 'Config/Routes.php';
        $this->assertSame('adminonly', $routes->getRoutesOptions('admin/ulasan', 'get')['filter']);
        $this->assertArrayHasKey('api/ulasan/save', $routes->getRoutes('post'));
        $this->assertArrayNotHasKey('api/ulasan/all', $routes->getRoutes('get'));
        $this->assertContains('csrf', config('Filters')->globals['before']);
    }

    public function testMigrationCanRollbackOnIsolatedDatabase(): void
    {
        $connection = Database::connect(array_merge(config('Database')->tests, ['DBPrefix' => 'ulasan_rollback_']), false);
        $migration = new \App\Database\Migrations\CreateUlasanTable(Database::forge($connection));
        $migration->up();
        $this->assertTrue($connection->tableExists('dt_ulasan'));
        $migration->down();
        $this->assertFalse($connection->tableExists('dt_ulasan'));
        $connection->close();
    }

    public function testHomepageFormContainsAllRequiredFieldsAndCsrf(): void
    {
        $html = view('publik/ulasan_form');
        $this->assertStringContainsString('api/ulasan/save', $html);
        foreach (['nama', 'no_hp', 'email', 'isi', 'rating'] as $field) {
            $this->assertStringContainsString('name="' . $field . '"', $html);
        }
        $this->assertStringContainsString('name="' . csrf_token() . '"', $html);
        $this->assertSame(5, substr_count($html, 'type="radio"'));
    }

    public function testAdminViewEscapesVisitorContent(): void
    {
        $model = new UlasanModel();
        $model->insert(array_merge($this->input(), [
            'nama' => '<script>alert("nama")</script>',
            'isi' => '<img src=x onerror="alert(1)"> Kritik dan saran.',
        ]));
        $rows = $model->orderBy('id', 'DESC')->paginate(20, 'ulasan');
        session()->set(['role' => 'admin', 'nama' => 'Admin', 'username' => 'admin@example.com']);
        $html = view('admin/ulasan', ['ulasan' => $rows, 'pager' => $model->pager, '_baseurl' => 'http://example.com/']);
        $this->assertStringNotContainsString('<script>alert("nama")</script>', $html);
        $this->assertStringNotContainsString('<img src=x onerror="alert(1)">', $html);
        $this->assertStringContainsString(esc($rows[0]['nama']), $html);
        $this->assertStringContainsString(esc($rows[0]['isi']), $html);
    }
}
