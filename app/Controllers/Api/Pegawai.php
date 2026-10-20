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
        return $this->response->setJSON(['data' => $this->m_pegawai->getPegawaiWithJenis()]);
    }

    public function getById($id)
	{
        $result = $this->m_pegawai->find($id);
	
        return $this->unikit->output(200, $result);
	}

    public function save($id){
       
        if (!$this->validateData($this->request->getPost(), [
            'nama' => 'required|max_length[255]', 'jenis' => 'required|is_not_unique[ms_jenis_pegawai.jenis_alias]',
            'urutan' => 'permit_empty|is_natural',
            'tgl_lahir' => 'permit_empty|valid_date[Y-m-d]',
        ])) {
            return $this->response->setStatusCode(422)->setJSON(['message' => $this->validator->getErrors()]);
        }
        $data = [];
        foreach (['nama', 'nip', 'jenis', 'jabatan', 'tempat_lahir', 'tgl_lahir', 'pangkat', 'golongan', 'jenis_kelamin', 'agama', 'urutan'] as $key) {
            if ($this->request->getPost($key) !== null) {
                $data[$key] = $this->request->getPost($key);
            }
        }
        if (isset($data['tgl_lahir']) && $data['tgl_lahir'] === '') {
            $data['tgl_lahir'] = null;
        }
        $data['urutan'] = (int) ($data['urutan'] ?? 0);
        $directory = ROOTPATH . 'public/uploads';
        $old = $id === 'new' ? null : $this->m_pegawai->find($id);
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
            $saved = $id === 'new' ? $this->m_pegawai->insert($data) : $this->m_pegawai->update($id, $data);
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
    
        if ($this->m_pegawai->delete($id)) {
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
