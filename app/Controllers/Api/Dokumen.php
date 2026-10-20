<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\DokumenModel;

class Dokumen extends ResourceController
{
    protected $modelName = 'App\Models\DokumenModel';
    protected $format    = 'json';
    private $rand_length = 4;
	private $same_check = 0;

    public function __construct()
    {
        $this->m_pages = new DokumenModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function getById($id)
	{
        $result = $this->m_pages->find($id);
	
        return $this->unikit->output(200, $result);
	}

    public function getAll()
	{
        $result['data'] = $this->m_pages->findAll();
	
        return $this->unikit->output(200, $result);
	}

    public function save($id){
        helper('basic');
        $old = $id === 'new' ? null : $this->m_pages->find($id);
        if ($id !== 'new' && !$old) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Dokumen tidak ditemukan.']);
        }
        if (!$this->validateData($this->request->getPost(), ['judul' => 'required|max_length[255]'])) {
            return $this->response->setStatusCode(422)->setJSON(['message' => $this->validator->getErrors()]);
        }
        $data = [
            'judul' => $this->request->getPost('judul'),
            'slug' => $old['slug'] ?? slugs($this->request->getPost('judul')) . '-' . bin2hex(random_bytes(6)),
        ];
        $directory = ROOTPATH . 'public/uploads';
        $file = $this->request->getFile('gambar');
        $newName = null;
        try {
            if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                \App\Libraries\UploadStorage::validate($file, true);
                $newName = \App\Libraries\UploadStorage::store($file, $directory);
                $data['file'] = $newName;
            } elseif (!$old) {
                throw new \InvalidArgumentException('File PDF wajib diunggah.');
            }
            $saved = $id === 'new' ? $this->m_pages->insert($data) : $this->m_pages->update($id, $data);
            if (!$saved) {
                throw new \RuntimeException('Dokumen gagal disimpan.');
            }
        } catch (\Throwable $e) {
            \App\Libraries\UploadStorage::remove($newName, $directory);
            return $this->response->setStatusCode($e instanceof \InvalidArgumentException ? 422 : 500)
                ->setJSON(['message' => $e instanceof \InvalidArgumentException ? $e->getMessage() : 'Dokumen gagal disimpan.']);
        }
        // Old filenames come only from the database and are deleted after a successful save.
        if ($newName && $old) {
            \App\Libraries\UploadStorage::remove($old['file'], $directory);
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
    
        if ($this->m_pages->delete($id)) {
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
