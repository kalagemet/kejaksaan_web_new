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
    public function __construct() {
        $this->unikit = new Unikit();
        $this->access = new Access();
        $this->userModel = new UserModel();
    }

    public function login(){
        if (session()->get('is_login')) {
            // Jika sudah login, langsung redirect ke controller admin
            return redirect()->to('/admin');
        }
        $data['url_oauth'] = $this->url_oauth(); 
        $data['captcha_url'] = base_url('captcha');
        $data['setting'] = $this->dataSetting['setting'];
        return view('utils/login', $data);
    }

    public function logout(){
        $this->access->logout();
        return redirect()->to('/');
    }

    public function register(){
        $data['url_oauth'] = $this->url_oauth();
        return view('utils/register', $data);
    }

    public function url_oauth(){
        $param = http_build_query([
            'client_id' => getenv('GOOGLE_CLIENT_ID'),
            'redirect_uri' => site_url('user/oauth_redirect'),
            'response_type' => 'token',
            'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        ]);

        $url_oauth = "https://accounts.google.com/o/oauth2/v2/auth?".$param;
        return $url_oauth;
    }
    public function redirect_oauth(){
        $data['url'] = getenv('GOOGLE_USERINFO_URL');
        return view('utils/redirect_oauth', $data);
    }

    public function verifikasi_akun(){
        $data = array();

        $token = $this->request->getVar('token');
        $check = $this->userModel->where('token', $token)->first();
        if($check){
            if($check['status'] == "1"){
                $data['message'] = "Akun anda sudah terdaftar dan aktif, silahkan login";
            }else{
                $input['status'] = '1';
                $this->userModel->update($check['user_id'], $input);
                $data['message'] = "Akun anda sudah terdaftar dan aktif, silahkan login";
            }
        }else{
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