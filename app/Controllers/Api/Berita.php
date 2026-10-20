<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\BeritaModel;

class Berita extends ResourceController
{
    protected $modelName = 'App\Models\BeritaModel';
    protected $format = 'json';
    private $rand_length = 4;
    private $same_check = 0;

    public function __construct()
    {
        $this->m_pages = new BeritaModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function getById($id)
    {
        $result = $this->m_pages->find($id);

        return $this->unikit->output(200, $result);
    }

    public function getAll($id)
    {
        $result['data'] = $this->m_pages->findByJenis($id);

        return $this->unikit->output(200, $result);
    }

    public function save($id)
    {
        helper('basic');
        $old = $id === 'new' ? null : $this->m_pages->find($id);
        if ($id !== 'new' && !$old) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Berita tidak ditemukan.']);
        }
        if (!$this->validateData($this->request->getPost(), [
            'judul' => 'required|max_length[255]', 'isi' => 'required',
            'jenis' => 'required|in_list[berita,pengumuman,siaran_pers]',
        ])) {
            return $this->response->setStatusCode(422)->setJSON(['message' => $this->validator->getErrors()]);
        }
        $data = [
            'judul' => $this->request->getPost('judul'), 'isi' => $this->request->getPost('isi'),
            'jenis' => $this->request->getPost('jenis'),
            'slug' => $old['slug'] ?? slugs($this->request->getPost('judul')) . '-' . bin2hex(random_bytes(6)),
        ];
        $file = $this->request->getFile('gambar');
        $newName = null;
        $directory = ROOTPATH . 'public/uploads';
        try {
            if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                \App\Libraries\UploadStorage::validate($file);
                $newName = \App\Libraries\UploadStorage::store($file, $directory . '/temp');
                $source = $directory . '/temp/' . $newName;
                \Config\Services::image()->withFile($source)->resize(800, 600, true)->save($directory . '/' . $newName);
                \Config\Services::image()->withFile($source)->resize(150, 150, true)->save($directory . '/thumbnail_' . $newName);
                \App\Libraries\UploadStorage::remove($newName, $directory . '/temp');
                $data['gambar'] = $newName;
            }
            $saved = $id === 'new' ? $this->m_pages->insert($data) : $this->m_pages->update($id, $data);
            if (!$saved) {
                throw new \RuntimeException('Berita gagal disimpan.');
            }
        } catch (\Throwable $e) {
            if ($newName) {
                foreach ([$newName, 'thumbnail_' . $newName] as $name) {
                    \App\Libraries\UploadStorage::remove($name, $directory);
                }
                \App\Libraries\UploadStorage::remove($newName, $directory . '/temp');
            }
            return $this->response->setStatusCode($e instanceof \InvalidArgumentException ? 422 : 500)
                ->setJSON(['message' => $e instanceof \InvalidArgumentException ? $e->getMessage() : 'Berita gagal disimpan.']);
        }
        if ($newName && $old) {
            \App\Libraries\UploadStorage::remove($old['gambar'], $directory);
            \App\Libraries\UploadStorage::remove('thumbnail_' . $old['gambar'], $directory);
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

    public function imageUpload()
    {
        $file = $this->request->getFile('upload');
        try {
            \App\Libraries\UploadStorage::validate($file);
            $name = \App\Libraries\UploadStorage::store($file, ROOTPATH . 'public/uploads/media');
            return $this->response->setJSON(['uploaded' => true, 'url' => base_url('uploads/media/' . $name)]);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode(422)->setJSON(['error' => ['message' => $e->getMessage()]]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => ['message' => 'Upload gagal.']]);
        }
    }
}
