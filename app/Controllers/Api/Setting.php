<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\UserModel;
use App\Models\DataModel;
use App\Models\PenghuniModel;
use App\Models\UsahaModel;

class Setting extends ResourceController
{
    public function __construct()
    {
        $this->datamodel = new DataModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function kelolaSetting(){
        $logo_satker = $this->request->getFile('logo_satker');
        $gambar_survey = $this->request->getFile('gambar_survey');
        $gambar_ikm = $this->request->getFile('gambar_ikm');
        $gambar_ipak = $this->request->getFile('gambar_ipak');
        $data = [
            "nama_satker" => $this->request->getVar('nama_satker'),
            "alamat_satker" => $this->request->getVar('alamat_satker'),
            "email_satker" => $this->request->getVar('email_satker'),
            "phone_satker" => $this->request->getVar('phone_satker'),
            "call_center" => $this->request->getVar('call_center'),
            "text_call_center" => $this->request->getVar('text_call_center'),
            "url_facebook" => $this->request->getVar('url_facebook'),
            "url_twitter" => $this->request->getVar('url_twitter'),
            "url_youtube" => $this->request->getVar('url_youtube'),
            "url_instagram" => $this->request->getVar('url_instagram'),
            "running_text" => $this->request->getVar('running_text'),
            "motto" => $this->request->getVar('motto'),
            // "email_delegasi" => $this->request->getVar('email_delegasi'),
            // "url_jadwal_sidang" => $this->request->getVar('url_jadwal_sidang'),
            // "url_survey" => $this->request->getVar('url_survey'),
            // "info_pelayanan" => $this->request->getVar('info_pelayanan'),
        ];

        $uploads = compact('logo_satker', 'gambar_survey', 'gambar_ikm', 'gambar_ipak');
        $stored = [];
        $directory = WRITEPATH . 'uploads';
        try {
            // Validate the whole request before moving any file.
            foreach ($uploads as $file) {
                if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                    \App\Libraries\UploadStorage::validate($file);
                }
            }
            foreach ($uploads as $key => $file) {
                if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                    $stored[] = $data[$key] = \App\Libraries\UploadStorage::store($file, $directory);
                }
            }
            $db = \Config\Database::connect();
            $old = $db->table('dt_setting')->where('id', 1)->get()->getRowArray();
            $saved = $old ? $db->table('dt_setting')->where('id', 1)->update($data)
                : $db->table('dt_setting')->insert(array_merge($data, ['id' => 1]));
            if (!$saved) {
                throw new \RuntimeException('Pengaturan gagal disimpan.');
            }
        } catch (\Throwable $e) {
            foreach ($stored as $name) {
                \App\Libraries\UploadStorage::remove($name, $directory);
            }
            return $this->response->setStatusCode($e instanceof \InvalidArgumentException ? 422 : 500)
                ->setJSON(['message' => $e instanceof \InvalidArgumentException ? $e->getMessage() : 'Upload gagal.']);
        }

        foreach ($uploads as $key => $file) {
            if (isset($data[$key]) && in_array($data[$key], $stored, true) && !empty($old[$key])) {
                \App\Libraries\UploadStorage::remove($old[$key], $directory);
            }
        }
        return $this->response->setJSON(['id' => 1, 'message' => 'Data berhasil disimpan.']);
    }
}
