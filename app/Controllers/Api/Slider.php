<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\UserModel;
use App\Models\DataModel;
use App\Models\SlideshowModel;

class Slider extends ResourceController
{
    protected $modelName = 'App\Models\SlideshowModel';
    protected $format    = 'json';
    private $rand_length = 4;
	private $same_check = 0;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->datamodel = new DataModel();
        $this->m_slideshow = new SlideshowModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
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
        $directory = ROOTPATH . 'public/uploads';
        $old = $id === 'new' ? null : $this->m_slideshow->find($id);
        if ($id !== 'new' && !$old) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Data tidak ditemukan.']);
        }
        $file = $this->request->getFile('gambar');
        $newName = null;
        try {
            if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                \App\Libraries\UploadStorage::validate($file);
                $newName = \App\Libraries\UploadStorage::store($file, $directory . '/temp');
                \Config\Services::image()->withFile($directory . '/temp/' . $newName)
                    ->save($directory . '/' . $newName);
                \App\Libraries\UploadStorage::remove($newName, $directory . '/temp');
                $data['gambar'] = $newName;
            }
            $saved = $id === 'new' ? $this->m_slideshow->insert($data) : $this->m_slideshow->update($id, $data);
            if (!$saved) {
                throw new \RuntimeException('Data gagal disimpan.');
            }
        } catch (\Throwable $e) {
            \App\Libraries\UploadStorage::remove($newName, $directory);
            \App\Libraries\UploadStorage::remove($newName, $directory . '/temp');
            return $this->response->setStatusCode($e instanceof \InvalidArgumentException ? 422 : 500)
                ->setJSON(['message' => $e instanceof \InvalidArgumentException ? $e->getMessage() : 'Data gagal disimpan.']);
        }
        if ($newName && $old) {
            \App\Libraries\UploadStorage::remove($old['gambar'], $directory);
        }
        return $this->response->setJSON(['success' => true]);
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