<?php
namespace App\Controllers\Masyarakat;

use App\Libraries\MasyarakatFiles;

class Auth extends PortalController
{
    public function login() { return $this->actor() ? redirect()->to(site_url('masyarakat')) : view('masyarakat/login'); }
    public function register() { return $this->actor() ? redirect()->to(site_url('masyarakat')) : view('masyarakat/register'); }
    public function forgot() { return view('masyarakat/forgot'); }

    public function doLogin()
    {
        if ($this->actor()) { return redirect()->to(site_url('masyarakat')); }
        try {
            $this->limit('login', 10, 300);
            $this->validateCaptcha('login');
            $user = service('masyarakatAccounts')->authenticate($this->input('nik'), $this->rawPassword());
            if (!(int) $user['aktif']) {
                $challenge = service('masyarakatAccounts')->registrationOtp((int) $user['id']);
                return $this->challenge($challenge, 'register');
            }
            session()->regenerate(true);
            session()->set(['masyarakat_id' => (int) $user['id'], 'masyarakat_version' => (int) $user['session_version']]);
            return redirect()->to(site_url('masyarakat'));
        } catch (\DomainException $e) {
            return $this->failForm('masyarakat/login', $e->getMessage(), ['nik' => $this->input('nik')]);
        }
    }

    public function doRegister()
    {
        if ($this->actor()) { return redirect()->to(site_url('masyarakat')); }
        $data = [];
        foreach (['nama', 'nik', 'no_wa', 'email', 'pekerjaan', 'tanggal_lahir', 'alamat'] as $key) { $data[$key] = $this->input($key); }
        $data['tanggal_lahir'] = $data['tanggal_lahir'] ?: null;
        $photo = null;
        $created = false;
        $files = new MasyarakatFiles();
        try {
            $this->limit('register');
            $this->validateCaptcha('register');
            $data['password'] = $this->rawPassword();
            if ($data['password'] !== $this->rawPassword('konfirmasi_password')) { throw new \DomainException('Konfirmasi password tidak sama.'); }
            $file = $this->request->getFile('foto');
            if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                $photo = $files->save($file, 'foto');
                $data['foto'] = $photo['nama_file'];
            }
            $user = service('masyarakatAccounts')->register($data);
            $created = true;
            return $this->challenge(service('masyarakatAccounts')->registrationOtp((int) $user['id']), 'register');
        } catch (\DomainException $e) {
            if (!$created && $photo) { $files->remove($photo['nama_file']); }
            if ($created) { return $this->failForm('masyarakat/login', 'Akun telah dibuat. Masuk dengan NIK dan password untuk melanjutkan aktivasi. ' . $e->getMessage()); }
            return $this->failForm('masyarakat/daftar', $e->getMessage(), $data);
        }
    }

    public function doForgot()
    {
        try {
            $this->limit('forgot');
            $challenge = service('masyarakatAccounts')->resetOtp($this->input('nik'), $this->input('email'));
            session()->set(['masyarakat_otp' => $challenge['token'], 'masyarakat_otp_purpose' => 'reset']);
            return redirect()->to(site_url('masyarakat/verifikasi'))->with('success', 'Jika NIK dan email cocok dengan akun aktif, kode OTP telah dikirim ke email Anda.');
        } catch (\DomainException $e) { return $this->failForm('masyarakat/lupa-password', $e->getMessage()); }
    }

    public function captcha(string $purpose)
    {
        if (!in_array($purpose, ['login', 'register'], true)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $builder = new \Gregwar\Captcha\CaptchaBuilder(null, new \Gregwar\Captcha\PhraseBuilder(5, '0123456789'));
        $builder->build();
        session()->set('masyarakat_captcha_' . $purpose, ['code' => $builder->getPhrase(), 'expires' => time() + 300]);
        return $this->response->setContentType('image/jpeg')->setBody($builder->get());
    }

    private function validateCaptcha(string $purpose): void
    {
        $challenge = session('masyarakat_captcha_' . $purpose);
        session()->remove('masyarakat_captcha_' . $purpose);
        if (!is_array($challenge) || ($challenge['expires'] ?? 0) < time()
            || !is_string($challenge['code'] ?? null) || $challenge['code'] === ''
            || !hash_equals($challenge['code'], $this->input('captcha_input'))) {
            throw new \DomainException('CAPTCHA tidak sesuai atau telah kedaluwarsa. Silakan coba kembali.');
        }
    }

    public function verify()
    {
        if (!session('masyarakat_otp')) { return redirect()->to(site_url('masyarakat/login')); }
        return view('masyarakat/verify', ['purpose' => session('masyarakat_otp_purpose')]);
    }

    private function actor(): ?int
    {
        if (!session('masyarakat_id')) { return null; }
        try {
            $user = $this->account();
            return (int) $user['session_version'] === (int) session('masyarakat_version') ? (int) $user['id'] : null;
        } catch (\DomainException $e) { return null; }
    }

    public function doVerify()
    {
        try {
            if (session('masyarakat_otp_purpose') === 'reset' && $this->rawPassword('password_baru') !== $this->rawPassword('konfirmasi_password')) {
                throw new \DomainException('Konfirmasi password baru tidak sama.');
            }
            $purpose = service('masyarakatAccounts')->verify((string) session('masyarakat_otp'), $this->input('otp'), $this->rawPassword('password_baru'), $this->actor());
            session()->remove(['masyarakat_otp', 'masyarakat_otp_purpose']);
            if ($purpose === 'reset') { session()->remove(['masyarakat_id', 'masyarakat_version']); }
            return redirect()->to(site_url(in_array($purpose, ['email_change', 'wa_change'], true) ? 'masyarakat/profil' : 'masyarakat/login'))
                ->with('success', $purpose === 'reset' ? 'Password berhasil diganti. Silakan masuk kembali.' : 'Verifikasi berhasil. Data akun telah diperbarui.');
        } catch (\DomainException $e) { return $this->failForm('masyarakat/verifikasi', $e->getMessage()); }
    }

    public function resend()
    {
        try {
            $this->limit('resend');
            $challenge = service('masyarakatAccounts')->resend((string) session('masyarakat_otp'), $this->actor());
            return $this->challenge($challenge, (string) session('masyarakat_otp_purpose'));
        } catch (\DomainException $e) {
            if (session('masyarakat_otp_purpose') === 'reset') {
                return redirect()->to(site_url('masyarakat/verifikasi'))->with('success', 'Jika permintaan valid, kode telah dikirim. Tunggu 60 detik sebelum mengirim ulang.');
            }
            return $this->failForm('masyarakat/verifikasi', $e->getMessage());
        }
    }

    private function challenge(array $challenge, string $purpose)
    {
        session()->set(['masyarakat_otp' => $challenge['token'], 'masyarakat_otp_purpose' => $purpose]);
        return redirect()->to(site_url('masyarakat/verifikasi'))->with($challenge['sent'] ? 'success' : 'error', $challenge['sent']
            ? 'Kode OTP telah dikirim melalui email. Kode berlaku 10 menit.'
            : 'Email OTP belum dapat dikirim. Akun/data Anda tetap tersimpan. Silakan kirim ulang atau hubungi pengelola.');
    }

    public function logout()
    {
        session()->remove(['masyarakat_id', 'masyarakat_version', 'masyarakat_otp', 'masyarakat_otp_purpose']);
        session()->regenerate(true);
        return redirect()->to(site_url('masyarakat/login'))->with('success', 'Anda telah keluar.');
    }
}
