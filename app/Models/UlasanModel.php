<?php

namespace App\Models;

use CodeIgniter\Model;

class UlasanModel extends Model
{
    protected $table = 'dt_ulasan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['nama', 'no_hp', 'email', 'isi', 'rating'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nama' => 'required|min_length[2]|max_length[100]',
        'no_hp' => 'required|regex_match[/^\+?[0-9]{8,20}$/]',
        'email' => 'required|valid_email|max_length[254]',
        'isi' => 'required|min_length[10]|max_length[3000]',
        'rating' => 'required|in_list[1,2,3,4,5]',
    ];
    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama wajib diisi.', 'min_length' => 'Nama minimal 2 karakter.',
            'max_length' => 'Nama maksimal 100 karakter.',
        ],
        'no_hp' => ['required' => 'Nomor telepon wajib diisi.', 'regex_match' => 'Nomor telepon harus berisi 8–20 digit, boleh diawali +.'],
        'email' => ['required' => 'Email wajib diisi.', 'valid_email' => 'Alamat email tidak valid.', 'max_length' => 'Email terlalu panjang.'],
        'isi' => [
            'required' => 'Saran atau kritik wajib diisi.', 'min_length' => 'Saran atau kritik minimal 10 karakter.',
            'max_length' => 'Saran atau kritik maksimal 3000 karakter.',
        ],
        'rating' => ['required' => 'Pilih rating pelayanan.', 'in_list' => 'Rating harus antara 1 hingga 5 bintang.'],
    ];
    protected $skipValidation = false;
}
