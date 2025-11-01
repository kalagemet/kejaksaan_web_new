<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\AgenModel;

class Agen extends ResourceController
{
    protected $modelName = 'App\Models\AgenModel';
    protected $format    = 'json';
    private $rand_length = 4;
	private $same_check = 0;

    public function __construct()
    {
        $this->m_agen = new AgenModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function getById($id)
	{
        $result = $this->m_agen->find($id);
	
        return $this->unikit->output(200, $result);
	}

    public function save($id){
       
        $data = [
            'nama' =>  $this->request->getVar('nama')
        ];
        $file = $this->request->getFile('foto');

        if($file !== null && $file->isValid()){
            $newName = 'agen_' . $file->getRandomName();

            // Move the uploaded file to a temporary location
            $file->move(ROOTPATH . 'public/uploads/temp', $newName);

            $tempFilePath = ROOTPATH . 'public/uploads/temp/' . $newName;
            $finalFilePath = ROOTPATH . 'public/uploads/' . $newName;

            // Load the image
            $image = \Config\Services::image()
                ->withFile($tempFilePath)
                // ->resize(400, 300, false) // Set height to 300px and maintain aspect ratio
                ->save($finalFilePath);

            // Remove the temporary file
            unlink($tempFilePath);

            $data['foto'] = $newName; // Save the new image name
        }

        if($id == 'new'){
            // $data['created_at'] = date('Y-m-d H:i:s');
            $result = $this->m_agen->insert($data);
        } else {
            if ($file !== null && $file->isValid()) { // Only unlink if a new valid file is uploaded
                $old = $this->m_agen->find($id);
                if ($old && $old['foto'] !== null) {
                    $oldFilePath = ROOTPATH . 'public/uploads/' . $old['gambar'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            }
            
            $result = $this->m_agen->update($id,$data);
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
    
        if ($this->m_agen->delete($id)) {
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