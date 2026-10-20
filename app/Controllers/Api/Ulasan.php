<?php

namespace App\Controllers\Api;

use App\Models\UlasanModel;
use CodeIgniter\RESTful\ResourceController;

class Ulasan extends ResourceController
{
    protected $modelName = UlasanModel::class;
    protected $format = 'json';

    public function save()
    {
        $data = [];
        foreach (['nama', 'no_hp', 'email', 'isi', 'rating'] as $field) {
            $value = $this->request->getPost($field);
            $data[$field] = is_string($value) ? trim($value) : $value;
        }
        if (is_string($data['no_hp'])) {
            $data['no_hp'] = str_replace([' ', '-', '(', ')'], '', $data['no_hp']);
        }
        // A visitor cannot set timestamps, IDs, or any other database fields.
        try {
            if (!$this->model->insert($data)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false, 'message' => 'Periksa kembali data ulasan Anda.',
                    'errors' => $this->model->errors(),
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Ulasan pengunjung gagal disimpan.');
            return $this->response->setStatusCode(503)->setJSON([
                'success' => false, 'message' => 'Ulasan belum dapat disimpan. Silakan coba lagi nanti.',
            ]);
        }
        return $this->response->setStatusCode(201)->setJSON([
            'success' => true, 'message' => 'Terima kasih! Rating dan masukan Anda berhasil dikirim.',
        ]);
    }
}
