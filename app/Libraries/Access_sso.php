<?php

namespace App\Libraries;
use App\Models\UserModel;

class Access_sso
{
    protected $_ci;

    public function __construct()
    {
        $this->_ci = \Config\Services::instance();
        $this->session = \Config\Services::session();

        $this->client_id = getenv('SSO_CLIENT_ID');
        $this->sso_baseurl = getenv('SSO_BASEURL');
    }

    function get_login_url(){
      return $this->sso_baseurl."/user?client_id=".$this->client_id;
    }

    public function set_user_data($data)
    {
        if ($data['instansi'] === '00') {
            $data['instansi'] = 0;
        }

        session()->set('user_id', $data['user_id']);
        session()->set('username', $data['username']);
        session()->set('nama_lengkap', $data['nama_lengkap']);
        session()->set('kode_ins', $data['instansi']);
        session()->set('ins_encode', encode_url($data['instansi']));
        session()->set('namanya', $data['inst_nama']);
        session()->set('jns_ins', $data['klas']);
        session()->set('akses', $data['role']);
        session()->set('is_login', true);

        return true;
    }

    public function url_get_user_data()
    {
        $token = session('token_sso');
        return $this->sso_baseurl . "/api/sso/get_user_info?token=" . $token;
    }



    
}