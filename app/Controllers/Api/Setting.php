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

        if ($logo_satker->isValid() && !$logo_satker->hasMoved()) {
            // Tentukan path untuk menyimpan file di folder writable
            $newName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $logo_satker->getName()); // Membersihkan nama file
            $uploadPath = WRITEPATH . 'uploads'; // Menyimpan di folder writable/uploads
    
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            // Memeriksa tipe MIME file
            $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
            if (!in_array($logo_satker->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'error' => 'Invalid file type. Only PNG, JPG, and GIF are allowed.'
                ]);
            }
    
            // Pindahkan file ke folder tujuan
            if ($logo_satker->move($uploadPath, $newName)) {
                // Kembalikan URL gambar setelah berhasil diunggah
                $data['logo_satker'] = $newName;

            } 
        }
        
        if ($gambar_survey->isValid() && !$gambar_survey->hasMoved()) {
            // Tentukan path untuk menyimpan file di folder writable
            $newName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $gambar_survey->getName()); // Membersihkan nama file
            $uploadPath = WRITEPATH . 'uploads'; // Menyimpan di folder writable/uploads
    
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            // Memeriksa tipe MIME file
            $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
            if (!in_array($gambar_survey->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'error' => 'Invalid file type. Only PNG, JPG, and GIF are allowed.'
                ]);
            }
    
            // Pindahkan file ke folder tujuan
            if ($gambar_survey->move($uploadPath, $newName)) {
                // Kembalikan URL gambar setelah berhasil diunggah
                $data['gambar_survey'] = $newName;

            } 
        }
        
        if ($gambar_ikm->isValid() && !$gambar_ikm->hasMoved()) {
            // Tentukan path untuk menyimpan file di folder writable
            $newName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $gambar_ikm->getName()); // Membersihkan nama file
            $uploadPath = WRITEPATH . 'uploads'; // Menyimpan di folder writable/uploads
    
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            // Memeriksa tipe MIME file
            $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
            if (!in_array($gambar_ikm->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'error' => 'Invalid file type. Only PNG, JPG, and GIF are allowed.'
                ]);
            }
    
            // Pindahkan file ke folder tujuan
            if ($gambar_ikm->move($uploadPath, $newName)) {
                // Kembalikan URL gambar setelah berhasil diunggah
                $data['gambar_ikm'] = $newName;

            } 
        }

        if ($gambar_ipak->isValid() && !$gambar_ipak->hasMoved()) {
            // Tentukan path untuk menyimpan file di folder writable
            $newName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $gambar_ipak->getName()); // Membersihkan nama file
            $uploadPath = WRITEPATH . 'uploads'; // Menyimpan di folder writable/uploads
    
            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            // Memeriksa tipe MIME file
            $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
            if (!in_array($gambar_ipak->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'error' => 'Invalid file type. Only PNG, JPG, and GIF are allowed.'
                ]);
            }
    
            // Pindahkan file ke folder tujuan
            if ($gambar_ipak->move($uploadPath, $newName)) {
                // Kembalikan URL gambar setelah berhasil diunggah
                $data['gambar_ipak'] = $newName;

            } 
        }

        $check = $this->datamodel->select_row_data('dt_setting',['id'=>1]);
        
        if($check == null){
            $data['id'] = 1;
            $result = $this->datamodel->insert_data('dt_setting',$data);
        } else {
            $result = $this->datamodel->insert_data('dt_setting',$data,'id', 1);
        }
        
        if($result){
            return $this->unikit->output(200, 
            [
                'id' => $result,
                'message' => 'Data berhasil disimpan'
            ]);
        }else{
            return $this->unikit->output(400, $result['message']);
        } 
    }
}