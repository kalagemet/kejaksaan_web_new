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
        $directory = ROOTPATH . 'public/uploads';
        $old = $id === 'new' ? null : $this->m_agen->find($id);
        if ($id !== 'new' && !$old) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Data tidak ditemukan.']);
        }
        $file = $this->request->getFile('foto');
        $newName = null;
        try {
            if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                \App\Libraries\UploadStorage::validate($file);
                $newName = \App\Libraries\UploadStorage::store($file, $directory . '/temp');
                \Config\Services::image()->withFile($directory . '/temp/' . $newName)
                    ->save($directory . '/' . $newName);
                \App\Libraries\UploadStorage::remove($newName, $directory . '/temp');
                $data['foto'] = $newName;
            }
            $saved = $id === 'new' ? $this->m_agen->insert($data) : $this->m_agen->update($id, $data);
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
            \App\Libraries\UploadStorage::remove($old['foto'], $directory);
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