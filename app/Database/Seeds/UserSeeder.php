<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;
class UserSeeder extends Seeder
{
    public function run()
    {
        $email = getenv('ADMIN_EMAIL');
        $password = getenv('ADMIN_PASSWORD');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !is_string($password) || strlen($password) < 12) {
            throw new \RuntimeException('Isi ADMIN_EMAIL dan ADMIN_PASSWORD (minimal 12 karakter).');
        }
        if ($this->db->table('user')->where('email', $email)->countAllResults()) {
            throw new \RuntimeException('Email sudah terdaftar; seeder tidak menimpa akun.');
        }
        $this->db->table('user')->insert([
            'email' => $email, 'nama_lengkap' => 'Administrator',
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'admin', 'status' => '1', 'instansi' => '00',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
