<?php

use App\Libraries\MasyarakatAccounts;
use App\Models\MasyarakatModel;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Config\Services;
use Tests\Support\Libraries\TestOtpDelivery;

final class MasyarakatTest extends CIUnitTestCase
{
    protected $db;
    private MasyarakatAccounts $accounts;
    private TestOtpDelivery $delivery;
    private int $sequence = 0;

    protected function setUp(): void
    {
        parent::setUp();
        require_once SUPPORTPATH . 'Libraries/TestOtpDelivery.php';
        require_once APPPATH . 'Database/Migrations/2026-10-20-000003_CreateLayananMasyarakat.php';
        $this->db = Database::connect('tests');
        if (!$this->db->tableExists('dt_masyarakat')) {
            (new \App\Database\Migrations\CreateLayananMasyarakat(Database::forge('tests')))->up();
        }
        foreach (['dt_pengaduan_riwayat','dt_pengaduan_berkas','dt_pengaduan','dt_masyarakat_otp','dt_masyarakat'] as $table) { $this->db->table($table)->emptyTable(); }
        $this->delivery = new TestOtpDelivery();
        $this->accounts = new MasyarakatAccounts($this->db, $this->delivery);
        Services::injectMock('masyarakatAccounts', $this->accounts);
        $throttler = $this->getMockBuilder(\CodeIgniter\Throttle\Throttler::class)->disableOriginalConstructor()->onlyMethods(['check'])->getMock();
        $throttler->method('check')->willReturn(true);
        Services::injectMock('throttler', $throttler);
        session()->remove(['masyarakat_id','masyarakat_version','masyarakat_otp','masyarakat_otp_purpose','masyarakat_captcha_login','masyarakat_captcha_register']);
        Services::resetSingle('response');
        Services::resetSingle('request');
        Services::resetSingle('validation');
    }

    protected function tearDown(): void
    {
        Services::resetSingle('masyarakatAccounts');
        Services::resetSingle('throttler');
        parent::tearDown();
    }

    private function authController(array $post): \App\Controllers\Masyarakat\Auth
    {
        Services::resetSingle('request'); Services::resetSingle('response');
        $request=Services::request(); $request->setMethod('post'); $request->setGlobal('post',$post);
        $controller=new \App\Controllers\Masyarakat\Auth();
        $controller->initController($request,Services::response(),Services::logger());
        return $controller;
    }

    private function user(array $changes = []): array
    {
        $n = ++$this->sequence;
        return $this->accounts->register(array_merge([
            'nama'=>'Masyarakat ' . $n, 'nik'=>sprintf('330000000000%04d',$n),
            'email'=>'citizen'.$n.'@example.com', 'no_wa'=>'0812345'.sprintf('%05d',$n),
            'password'=>'password-aman-123', 'pekerjaan'=>null, 'tanggal_lahir'=>null, 'alamat'=>null,
        ],$changes));
    }

    private function active(): array
    {
        $user = $this->user();
        $challenge = $this->accounts->registrationOtp((int)$user['id']);
        $this->accounts->verify($challenge['token'],$this->delivery->code());
        return (new MasyarakatModel())->find($user['id']);
    }

    public function testRegisterRequiredDataAndActivateWithEmailOtp(): void
    {
        $user = $this->user(['email'=>'CITIZEN1@EXAMPLE.COM']);
        $this->assertSame(0,(int)$user['aktif']);
        $this->assertTrue(password_verify('password-aman-123',$user['password']));
        $this->assertSame('citizen1@example.com',$user['email']);
        $this->assertSame('6281234500001',$user['no_wa']);
        $challenge = $this->accounts->registrationOtp((int)$user['id']);
        $row = $this->db->table('dt_masyarakat_otp')->where('token',$challenge['token'])->get()->getRowArray();
        $this->assertNotSame($this->delivery->code(),$row['code_hash']);
        $this->assertTrue(password_verify($this->delivery->code(),$row['code_hash']));
        $this->assertSame('register',$this->accounts->verify($challenge['token'],$this->delivery->code()));
        $active = $this->accounts->active((int)$user['id']);
        $this->assertNotEmpty($active['email_verified_at']);
        $this->assertNull($active['wa_verified_at']);
    }

    /** @dataProvider duplicateFields */
    public function testDuplicateNikEmailAndWaAreRejected(string $field): void
    {
        $first = $this->user();
        $this->expectException(DomainException::class);
        $this->user([$field=>$first[$field]]);
    }
    public function duplicateFields(): array { return [['nik'],['email'],['no_wa']]; }

    public function testNikMustContainExactlySixteenDigits(): void
    {
        $this->expectException(DomainException::class);
        $this->user(['nik'=>'123']);
    }

    public function testOtpCanOnlyBeUsedOnce(): void
    {
        $user = $this->user();
        $challenge = $this->accounts->registrationOtp((int)$user['id']);
        $code = $this->delivery->code();
        $this->accounts->verify($challenge['token'],$code);
        $this->expectException(DomainException::class);
        $this->accounts->verify($challenge['token'],$code);
    }

    public function testOtpAttemptLimitSurvivesWrongCodes(): void
    {
        $user = $this->user();
        $challenge = $this->accounts->registrationOtp((int)$user['id']);
        for ($i=0;$i<5;$i++) {
            try { $this->accounts->verify($challenge['token'],'000000'); $this->fail('Wrong code accepted.'); }
            catch (DomainException $e) { $this->assertSame('Kode OTP tidak sesuai.',$e->getMessage()); }
        }
        $this->expectException(DomainException::class);
        $this->accounts->verify($challenge['token'],$this->delivery->code());
    }

    public function testExpiredOtpIsRejected(): void
    {
        $user=$this->user(); $challenge=$this->accounts->registrationOtp((int)$user['id']);
        $this->db->table('dt_masyarakat_otp')->where('token',$challenge['token'])->update(['expires_at'=>'2000-01-01 00:00:00']);
        $this->expectException(DomainException::class);
        $this->accounts->verify($challenge['token'],$this->delivery->code());
    }

    public function testUnsentOtpCannotActivateAccount(): void
    {
        $user=$this->user(); $this->delivery->fail=true;
        $challenge=$this->accounts->registrationOtp((int)$user['id']);
        $this->assertFalse($challenge['sent']);
        $this->assertSame(0,(int)(new MasyarakatModel())->find($user['id'])['aktif']);
        $this->expectException(DomainException::class);
        $this->accounts->verify($challenge['token'],'000000');
    }

    public function testResendHasCooldownAndInvalidatesPreviousCode(): void
    {
        $user=$this->user(); $first=$this->accounts->registrationOtp((int)$user['id']);
        try { $this->accounts->resend($first['token']); $this->fail('Cooldown bypassed.'); }
        catch (DomainException $e) { $this->assertStringContainsString('60 detik',$e->getMessage()); }
        $this->db->table('dt_masyarakat_otp')->where('token',$first['token'])->update(['created_at'=>gmdate('Y-m-d H:i:s',time()-61)]);
        $next=$this->accounts->resend($first['token']);
        $this->assertNotSame($first['token'],$next['token']);
        $this->assertNotEmpty($this->db->table('dt_masyarakat_otp')->where('token',$first['token'])->get()->getRowArray()['used_at']);
    }

    public function testResetChangesPasswordAndRevokesExistingSessions(): void
    {
        $user=$this->active();
        session()->set(['masyarakat_id'=>$user['id'],'masyarakat_version'=>$user['session_version']]);
        $challenge=$this->accounts->resetOtp($user['nik'],$user['email']);
        $this->assertSame('reset',$this->accounts->verify($challenge['token'],$this->delivery->code(),'password-baru-456'));
        $updated=$this->accounts->authenticate($user['nik'],'password-baru-456');
        $this->assertSame((int)$user['session_version']+1,(int)$updated['session_version']);
        $this->assertNotNull((new \App\Filters\MasyarakatOnly())->before(Services::request()));
    }

    public function testUnknownResetDoesNotSendEmail(): void
    {
        $challenge=$this->accounts->resetOtp('1234567890123456','unknown@example.com');
        $this->assertSame(64,strlen($challenge['token']));
        $this->assertCount(0,$this->delivery->messages);
    }

    public function testContactChangesOnlyAfterOtpAndAreBoundToOwner(): void
    {
        $user=$this->active();
        $challenge=$this->accounts->contactOtp((int)$user['id'],'email','new@example.com','password-aman-123');
        $code=$this->delivery->code();
        $this->assertSame($user['email'],$this->accounts->active((int)$user['id'])['email']);
        $this->assertSame('new@example.com',$this->delivery->messages[count($this->delivery->messages)-1]['destination']);
        try { $this->accounts->verify($challenge['token'],$code,null,999999); $this->fail('Wrong owner accepted.'); }
        catch (DomainException $e) { $this->assertStringContainsString('tidak valid',$e->getMessage()); }
        $this->accounts->verify($challenge['token'],$code,null,(int)$user['id']);
        $this->assertSame('new@example.com',$this->accounts->active((int)$user['id'])['email']);
    }

    public function testWaChangeIsConfirmedUsingCurrentEmail(): void
    {
        $user=$this->active();
        $challenge=$this->accounts->contactOtp((int)$user['id'],'no_wa','081299999999','password-aman-123');
        $this->assertSame($user['email'],$this->delivery->messages[count($this->delivery->messages)-1]['destination']);
        $this->accounts->verify($challenge['token'],$this->delivery->code(),null,(int)$user['id']);
        $updated=$this->accounts->active((int)$user['id']);
        $this->assertSame('6281299999999',$updated['no_wa']);
        $this->assertNull($updated['wa_verified_at']);
    }

    public function testDuplicateContactIsRecheckedAtOtpCompletion(): void
    {
        $user=$this->active();
        $challenge=$this->accounts->contactOtp((int)$user['id'],'email','reserved@example.com','password-aman-123');
        $code=$this->delivery->code();
        $this->user(['email'=>'reserved@example.com']);
        $this->expectException(DomainException::class);
        $this->accounts->verify($challenge['token'],$code,null,(int)$user['id']);
    }

    public function testProfileCannotChangeNikOrContactsWithoutOtp(): void
    {
        $user=$this->active();
        $this->accounts->updateProfile((int)$user['id'],['nama'=>'Nama diperbarui','nik'=>'9999999999999999','email'=>'forged@example.com','aktif'=>0]);
        $updated=$this->accounts->active((int)$user['id']);
        $this->assertSame($user['nik'],$updated['nik']);
        $this->assertSame($user['email'],$updated['email']);
        $this->assertSame('Nama diperbarui',$updated['nama']);
    }

    public function testOldEmailResetChallengeIsRevokedAfterEmailChange(): void
    {
        $user=$this->active();
        $reset=$this->accounts->resetOtp($user['nik'],$user['email']); $oldCode=$this->delivery->code();
        $contact=$this->accounts->contactOtp((int)$user['id'],'email','new@example.com','password-aman-123');
        $this->accounts->verify($contact['token'],$this->delivery->code(),null,(int)$user['id']);
        $this->expectException(DomainException::class);
        $this->accounts->verify($reset['token'],$oldCode,'new-password-456');
    }

    public function testProtectedRoutesRequireCorrectAccountType(): void
    {
        $routes=Services::routes(); require APPPATH.'Config/Routes.php';
        foreach (['masyarakat','masyarakat/profil','masyarakat/pengaduan'] as $path) {
            $this->assertSame('masyarakat',$routes->getRoutesOptions($path,'get')['filter']);
        }
        foreach (['admin/masyarakat','admin/pengaduan'] as $path) {
            $this->assertSame('adminonly',$routes->getRoutesOptions($path,'get')['filter']);
        }
        $this->assertNotNull((new \App\Filters\MasyarakatOnly())->before(Services::request()));
    }

    public function testRegistrationOtpAndNikLoginControllerFlow(): void
    {
        session()->set('masyarakat_captcha_register', ['code'=>'12345','expires'=>time()+300]);
        $response=$this->authController([
            'nama'=>'Pelapor baru','nik'=>'3300000000009876','no_wa'=>'081299998888','email'=>'newuser@example.com',
            'password'=>'password-aman-123','konfirmasi_password'=>'password-aman-123','captcha_input'=>'12345',
        ])->doRegister();
        $this->assertStringEndsWith('/masyarakat/verifikasi',$response->getHeaderLine('Location'));
        $this->assertNotEmpty(session('masyarakat_otp'));
        $this->assertNull(session('masyarakat_id'));
        $response=$this->authController(['otp'=>$this->delivery->code()])->doVerify();
        $this->assertStringEndsWith('/masyarakat/login',$response->getHeaderLine('Location'));
        session()->set('masyarakat_captcha_login', ['code'=>'54321','expires'=>time()+300]);
        $response=$this->authController(['nik'=>'3300000000009876','password'=>'password-aman-123','captcha_input'=>'54321'])->doLogin();
        $this->assertStringEndsWith('/masyarakat',$response->getHeaderLine('Location'));
        $this->assertNotEmpty(session('masyarakat_id'));
        $this->assertNull((new \App\Filters\MasyarakatOnly())->before(Services::request()));
    }

    public function testCitizenLogoutDoesNotDestroyAdministratorSession(): void
    {
        $user=$this->active();
        session()->set(['masyarakat_id'=>$user['id'],'masyarakat_version'=>1,'is_login'=>true,'user_id'=>99]);
        $this->authController([])->logout();
        $this->assertNull(session('masyarakat_id'));
        $this->assertSame(99,session('user_id'));
        session()->remove(['is_login','user_id']);
    }

    public function testWrongLoginDoesNotFlashPassword(): void
    {
        $this->authController(['nik'=>'123','password'=>'private-password'])->doLogin();
        $values=session()->getFlashdata('form_values');
        $this->assertArrayNotHasKey('password',$values);
        $this->assertSame('123',$values['nik']);
    }

    public function testCitizenLoginRequiresCaptchaBeforeAuthenticating(): void
    {
        $user=$this->active();
        foreach ([null, ['code'=>'12345','expires'=>time()-1], ['code'=>'54321','expires'=>time()+300]] as $challenge) {
            session()->set('masyarakat_captcha_login', $challenge);
            $response=$this->authController(['nik'=>$user['nik'],'password'=>'password-aman-123','captcha_input'=>'12345'])->doLogin();
            $this->assertStringEndsWith('/masyarakat/login',$response->getHeaderLine('Location'));
            $this->assertNull(session('masyarakat_id'));
            $this->assertNull(session('masyarakat_captcha_login'));
            $this->assertStringContainsString('CAPTCHA',session()->getFlashdata('error'));
        }
    }

    public function testRegistrationWithoutCaptchaDoesNotCreateAccount(): void
    {
        session()->remove('masyarakat_captcha_register');
        $before=(new MasyarakatModel())->countAllResults();
        $response=$this->authController(['nama'=>'Contoh','nik'=>'3300000000009876','no_wa'=>'081299998888','email'=>'new@example.com','password'=>'password-aman-123','konfirmasi_password'=>'password-aman-123'])->doRegister();
        $this->assertStringEndsWith('/masyarakat/daftar',$response->getHeaderLine('Location'));
        $this->assertSame($before,(new MasyarakatModel())->countAllResults());
    }

    public function testCaptchaImageUsesSeparateSessionFromAdminAndOtherForms(): void
    {
        session()->set('captcha_word','admin-code');
        $response=$this->authController([])->captcha('login');
        $login=session('masyarakat_captcha_login');
        $this->assertMatchesRegularExpression('/^[0-9]{5}$/',$login['code']);
        $this->assertGreaterThan(time(),$login['expires']);
        $this->assertStringStartsWith('image/jpeg',$response->getHeaderLine('Content-Type'));
        $this->assertSame("\xFF\xD8",substr($response->getBody(),0,2));
        $this->authController([])->captcha('register');
        $this->assertSame($login,session('masyarakat_captcha_login'));
        $this->assertSame('admin-code',session('captcha_word'));
        session()->remove('captcha_word');
    }

    public function testRevokedCitizenSessionCannotSkipLogin(): void
    {
        $user=$this->active();
        session()->set(['masyarakat_id'=>$user['id'],'masyarakat_version'=>$user['session_version']+1]);
        $response=(new \App\Filters\MasyarakatOnly())->before(Services::request());
        $this->assertStringEndsWith('/masyarakat/login',$response->getHeaderLine('Location'));
        $this->assertNull(session('masyarakat_id'));
    }

    public function testLoggedInCitizenCannotOpenOrSubmitLoginAndRegistration(): void
    {
        $user=$this->active();
        session()->set(['masyarakat_id'=>$user['id'],'masyarakat_version'=>$user['session_version']]);
        foreach (['login','register','doLogin','doRegister'] as $method) {
            $response=$this->authController([])->$method();
            $this->assertStringEndsWith('/masyarakat',$response->getHeaderLine('Location'));
        }
    }

    public function testGuestAndAdministratorNeedCitizenLogin(): void
    {
        session()->set(['is_login'=>true,'user_id'=>99,'role'=>'admin']);
        $response=(new \App\Filters\MasyarakatOnly())->before(Services::request());
        $this->assertStringEndsWith('/masyarakat/login',$response->getHeaderLine('Location'));
        $this->assertStringContainsString('Anda harus login',session()->getFlashdata('error'));
        session()->remove(['is_login','user_id','role']);
    }

    public function testPrivateDownloadRoutesAlwaysRequireTheirAccountFilter(): void
    {
        $routes=Services::routes(); require APPPATH.'Config/Routes.php';
        $found=0;
        foreach ($routes->getRoutes('get') as $path=>$handler) {
            if (strpos($path,'/berkas/')===false || strpos($handler,'::download')===false) { continue; }
            $this->assertSame(strpos($path,'admin/')===0?'adminonly':'masyarakat',$routes->getRoutesOptions($path,'get')['filter']);
            $found++;
        }
        $this->assertSame(2,$found);
    }
}
