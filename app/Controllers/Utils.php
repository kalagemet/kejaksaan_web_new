<?php

namespace App\Controllers;
use App\Libraries\Unikit;
use App\Libraries\Access;
use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\SettingModel;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class Utils extends BaseController
{
    public function __construct()
    {
        $this->unikit = new Unikit();
        $this->access = new Access();
        $this->userModel = new UserModel();
    }

    public function login()
    {
        $user = session('is_login') && session('user_id') ? $this->userModel->find(session('user_id')) : null;
        if ($user && (string) $user['status'] === '1') {
            return redirect()->to(site_url($user['role'] === 'admin' ? 'admin' : 'user'));
        }
        $data['url_oauth'] = $this->url_oauth();
        $data['captcha_url'] = base_url('captcha');
        $data['setting'] = $this->dataSetting['setting'];
        return view('utils/login', $data);
    }

    public function logout()
    {
        $this->access->logout();
        return redirect()->to('/');
    }

    public function register()
    {
        $data['url_oauth'] = $this->url_oauth();
        return view('utils/register', $data);
    }

    public function url_oauth()
    {
        return (new \App\Libraries\GoogleOAuth())->authorizationUrl(site_url('user/oauth_redirect'));
    }

    public function redirect_oauth()
    {
        try {
            $profile = (new \App\Libraries\GoogleOAuth())->identity(
                (string) $this->request->getGet('code'), (string) $this->request->getGet('state'),
                site_url('user/oauth_redirect')
            );
            $user = $this->userModel->where('google_id', $profile['sub'])->first();
            if (!$user) {
                // Existing local accounts require explicit linking by an administrator.
                if ($this->userModel->where('email', $profile['email'])->first()) {
                    return redirect()->to('/user/login')->with('error', 'Email sudah terdaftar. Gunakan password akun Anda.');
                }
                $id = $this->userModel->insert([
                    'google_id' => $profile['sub'], 'email' => $profile['email'],
                    'nama_lengkap' => $profile['name'] ?? $profile['email'],
                    'role' => 'user', 'status' => '1',
                    'password' => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
                ]);
                if (!$id) {
                    throw new \RuntimeException('Pembuatan akun gagal.');
                }
            }
            $result = $this->access->login_google_oauth($profile['sub']);
            if (!$result['status']) {
                throw new \RuntimeException('Akun tidak aktif.');
            }
            return redirect()->to(session('role') === 'admin' ? '/admin' : '/user');
        } catch (\Throwable $e) {
            log_message('error', 'Login Google gagal.');
            return redirect()->to('/user/login')->with('error', 'Login Google gagal. Silakan coba kembali.');
        }
    }

    public function verifikasi_akun()
    {
        $data = array();

        $token = $this->request->getVar('token');
        $check = is_string($token) && preg_match('/^[a-f0-9]{64}$/', $token)
            ? $this->userModel->where('token', $token)->first() : null;
        if ($check) {
            if ($check['status'] == "1") {
                $data['message'] = "Akun anda sudah terdaftar dan aktif, silahkan login";
            } else {
                $input['status'] = '1';
                $input['token'] = null;
                $this->userModel->update($check['user_id'], $input);
                $data['message'] = "Akun anda sudah terdaftar dan aktif, silahkan login";
            }
        } else {
            $data['message'] = "URL Tidak valid";
        }

        return view("utils/verifikasi_akun", $data);
    }

    public function generateCaptcha()
    {
        // Membuat PhraseBuilder khusus hanya dengan angka
        $phraseBuilder = new PhraseBuilder(5, '0123456789'); // Panjang 6 karakter, hanya angka

        // Membuat CaptchaBuilder dengan PhraseBuilder khusus
        $builder = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build();

        // Menyimpan CAPTCHA dalam sesi
        session()->set('captcha_word', $builder->getPhrase());

        // Mengembalikan gambar CAPTCHA sebagai respons
        $response = service('response');
        $response->setHeader('Content-Type', 'image/jpeg');
        $builder->output();
        exit;
    }

}
