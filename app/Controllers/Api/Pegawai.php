<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\PegawaiModel;

class Pegawai extends ResourceController
{
    protected $modelName = 'App\Models\PegawaiModel';
    protected $format    = 'json';
    private $rand_length = 4;
	private $same_check = 0;

    public function __construct()
    {
        $this->m_pegawai = new PegawaiModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function getAll()
	{
        $result['data'] = $this->m_pegawai->getPegawaiWithJenis();
	
        return $this->unikit->output(200, $result);
	}

    public function getById($id)
	{
        $result = $this->m_slideshow->find($id);
	
        return $this->unikit->output(200, $result);
	}

    public function save($id){
       
        $data = [
            'title' =>  $this->request->getVar('title'),
            'subtitle' =>  $this->request->getVar('subtitle'),
            'link' =>  $this->request->getVar('link')
        ];
        $file = $this->request->getFile('gambar');

        if($file !== null && $file->isValid()){
            $newName = 'layanan_' . $file->getRandomName();

            // Move the uploaded file to a temporary location
            $file->move(ROOTPATH . 'public/uploads/temp', $newName);

            $tempFilePath = ROOTPATH . 'public/uploads/temp/' . $newName;
            $finalFilePath = ROOTPATH . 'public/uploads/' . $newName;

            // Load the image
            $image = \Config\Services::image()
                ->withFile($tempFilePath)
                ->resize(400, 300, false) // Set height to 300px and maintain aspect ratio
                ->save($finalFilePath);

            // Remove the temporary file
            unlink($tempFilePath);

            $data['gambar'] = $newName; // Save the new image name
        }

        if($id == 'new'){
            // $data['created_at'] = date('Y-m-d H:i:s');
            $result = $this->m_slideshow->insert($data);
        } else {
            if ($file !== null && $file->isValid()) { // Only unlink if a new valid file is uploaded
                $old = $this->m_slideshow->find($id);
                if ($old && $old['gambar'] !== null) {
                    $oldFilePath = ROOTPATH . 'public/uploads/' . $old['gambar'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            }
            
            $result = $this->m_slideshow->update($id,$data);
        }
        
        if($result){
            return $this->unikit->output(200);
        }else{
            return $this->unikit->output(400, $result['message']);
        } 
    }

    public function delete($id = null)
    {
        $this->request->getMethod(true) === 'DELETE' or $this->request->getMethod(true) === 'POST' or exit('Method Not Allowed');
        if ($id === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID is required to delete a record.'
            ]);
        }
    
        if ($this->m_slideshow->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data gagal dihapus'
            ]);
        }
    }

}