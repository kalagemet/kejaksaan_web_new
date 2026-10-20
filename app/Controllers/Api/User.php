<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\UserModel;
use App\Models\DataModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class User extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';
    private $rand_length = 4;
    private $same_check = 0;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->datamodel = new DataModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function doLogin()
    {
        $input = $this->request->getPost('captcha_input');
        $storedCaptcha = session()->get('captcha_word');

        if (is_string($input) && is_string($storedCaptcha) && $storedCaptcha !== '' && hash_equals($storedCaptcha, $input)) {
            session()->remove('captcha_word');
            $username = $this->request->getVar('email');
            $password = $this->request->getVar('password');
            $result = $this->access->login($username, $password);
            if ($result['status'] == true) {
                return $this->response->setJSON(['url_redirect' => site_url(session('role') === 'admin' ? 'admin' : 'user')]);
            } else {
                return $this->unikit->output(400, $result['message']);
            }
        } else {
            return $this->unikit->output(400, "CAPTCHA verification failed!");
        }

    }

    public function doRegister()
    {
        $nama = $this->request->getVar('nama');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $password_confirmation = $this->request->getVar('password_confirmation');

        //password harus sama
        if ($password != $password_confirmation) {
            return $this->unikit->output(400, ['message' => 'Password yang anda masukan tidak cocok']);
        }

        //validasi input
        $validation[] = ['email', 'Email', 'required|valid_email'];
        $validation[] = ['password', 'Password', 'required|min_length[8]'];
        $validation[] = ['nama', 'Nama', 'required'];
        $this->unikit->validation($validation);

        //generate token
        $token = bin2hex(random_bytes(32));

        //cek email sudah ada
        $check = $this->userModel->where('email', $email)->first();
        // Never replace credentials through registration, including inactive accounts.
        if ($check) {
            return $this->response->setStatusCode(409)->setJSON(['message' => 'Email sudah terdaftar. Silakan login atau hubungi administrator.']);
        }
        $input = [
            'token' => $token, 'nama_lengkap' => $nama, 'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'status' => '0', 'role' => 'user',
        ];
        if (!$this->userModel->insert($input)) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Registrasi gagal.']);
        }

        //isi email
        $message = view('email/verify', ['url' => site_url('user/verifikasi_akun?token=' . $token)]);

        //kirim email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = getenv('email.SMTPHost');
            $mail->Port = (int) getenv('email.SMTPPort');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('email.SMTPUser');
            $mail->Password = getenv('email.SMTPPass');
            $mail->SMTPSecure = (string) getenv('email.SMTPCrypto');

            $mail->setFrom(getenv('email.SMTPUser'), 'Halo JPN');
            $mail->addAddress($email, 'User');
            $mail->isHTML(true);

            $mail->Subject = 'Verifikasi Email - Halo JPN';
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            // Roll back the pending registration so delivery failure does not lock out retry.
            $this->userModel->delete($this->userModel->getInsertID());
            log_message('error', 'Pengiriman email verifikasi gagal.');
            return $this->response->setStatusCode(503)->setJSON(['message' => 'Email verifikasi belum dapat dikirim. Silakan coba lagi.']);
        }
        return $this->response->setJSON(['message' => 'Silakan cek email anda untuk verifikasi.']);
    }

    public function getKejari($id)
    {
        $where = ['inst_parent' => $id];
        $result = $this->datamodel->select_result_dataArray('instansi', $where, 'ins_satkerkd, inst_nama, alias');

        return $this->unikit->output(200, $result);
    }

    public function getUserProfil()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user');
        $builder->select('nama_lengkap, nik, no_hp, instansi');
        $builder->where('user_id', $_SESSION['user_id']);
        $query = $builder->get();
        $result = $query->getRowArray();

        return $this->unikit->output(200, $result);
    }

    public function submit_pelayanan_hukum()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'subyek' => 'required|min_length[10]',
            // 'masalah' => 'required|min_length[50]',
            'cat_id' => 'required',
        ]);

        $user = $this->datamodel->select_row_data('user', ['user_id' => $_SESSION['user_id']]);

        $data = [
            'subyek' => $this->request->getVar('subyek'),
            'pertanyaan' => $this->request->getVar('masalah'),
            'tgl_pertanyaan' => date("Y-m-d H:i:s"),
            'no_laporan' => date("Y") . '-' . get_random_string($this->rand_length),
            'cat_id' => $this->request->getVar('cat_id'),
            'instansi' => $user->instansi,
            'public' => $this->request->getVar('public'),
            'user_id' => $_SESSION['user_id']
        ];

        if (!$validation->run($data)) {
            $err = $validation->getErrors();
            return $this->unikit->output(400, [
                'status' => 400,
                'message' => $err
            ]);
        } else {

            $result = $this->datamodel->insert_data('permohonan', $data);
            if ($result) {
                return $this->unikit->output(200);
            } else {
                return $this->unikit->output(400, $result['message']);
            }
        }
    }

    public function update_profil()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'nama_lengkap' => 'required',
            'nik' => 'required|min_length[16]',
            'no_hp' => 'required',
        ]);

        // print_r($this->request->getVar('tahun'));

        $data = [
            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
            'nik' => $this->request->getVar('nik'),
            'no_hp' => $this->request->getVar('no_hp'),
            'alamat' => $this->request->getVar('alamat'),
            'instansi' => $this->request->getVar('pilihKejari')
        ];

        if (!$validation->run($data)) {
            $err = $validation->getErrors();
            return $this->unikit->output(400, [
                'status' => 400,
                'message' => $err
            ]);
        } else {

            $result = $this->datamodel->insert_data('user', $data, 'user_id', $_SESSION['user_id']);
            if ($result) {
                return $this->unikit->output(200);
            } else {
                return $this->unikit->output(400, $result['message']);
            }
        }
    }

    public function oauth_check_user()
    {
        return $this->response->setStatusCode(410)->setJSON([
            'message' => 'Gunakan login Google melalui halaman login. Identitas dari browser tidak diterima.',
        ]);
    }

    public function getPermohonanByUser()
    {
        $rows = \Config\Database::connect()->table('permohonan')
            ->where('user_id', session('user_id'))->orderBy('tgl_pertanyaan', 'DESC')->get()->getResultArray();
        return $this->response->setJSON(['data' => $rows]);
    }

    public function ubah_password()
    {
        helper(['form']);
        $session = session();
        $userModel = new UserModel();
        $user = $userModel->find($session->get('user_id'));

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/profil')->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);

        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->to('/admin/profil')->withInput()->with('errors', ['Password lama salah']);

        }

        // dd($this->validator->getErrors(), $currentPassword, $user['password'], password_verify($currentPassword, $user['password']));

        $userModel->update($user['user_id'], ['password' => $newPassword]);
        return redirect()->to('/admin/profil')->with('success', 'Password berhasil diperbarui!');

    }

}
