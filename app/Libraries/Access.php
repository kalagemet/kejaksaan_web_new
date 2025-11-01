<?php

namespace App\Libraries;
use App\Models\UserModel;

class Access
{
    protected $_ci;

    public function __construct()
    {
        $this->_ci = \Config\Services::instance();
        $this->session = \Config\Services::session();
        $this->userModel = new UserModel();
    }

    public function login($username, $password)
    {
        $user = $this->userModel->where('email', $username)->first();
        if ($user) {
            if (password_verify($password, $user['password'])) {

                $nama = $user['nama_lengkap'];

                $this->session->set('nama', $nama);
                $this->session->set('user_id', $user['user_id']);
                $this->session->set('username', $user['email']);
                $this->session->set('instansi', $user['instansi']);
                $this->session->set('role', $user['role']);
                $this->session->set('status', $user['status']);
                $this->session->set('is_login', true);

                $this->userModel->insert_last_login($user['user_id']);
                return ['status' => true, 'data' => $user];
            } else {
                return ['status' => false, 'message' => 'Password / Username Salah'];
            }
        } else {
            return ['status' => false, 'message' => 'Username yang anda masukan tidak terdaftar'];
        }
    }

    public function login_google_oauth($id_oauth)
    {
        $data = $this->userModel->where('google_id', $id_oauth)->first();

        $this->session->set('user_id', $data['user_id']);
        $this->session->set('email', $data['email']);
        $this->session->set('username', $data['email']);
        $this->session->set('nama', $data['nama_lengkap']);
        $this->session->set('role', $data['role']);
        $this->session->set('status', $data['status']);
        $this->session->set('is_login', true);
        return ['status' => true, 'data' => $data];
    }

    public function logout()
    {
        $this->session->destroy();
    }
}