<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\BeritaModel;

class Berita extends ResourceController
{
    protected $modelName = 'App\Models\PagesModel';
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
        $slug = $this->request->getVar('judul');
        $data = [
            'judul' => $this->request->getVar('judul'),
            'isi' => $this->request->getVar('isi'),
            'jenis' => $this->request->getVar('jenis'),
            'slug' => slugs($slug) . "-" . getRandomStr()
        ];
        $file = $this->request->getFile('gambar');

        if ($file !== null && $file->isValid()) {
            $newName = 'berita_' . $file->getRandomName();

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

        if ($id == 'new') {
            // $data['created_at'] = date('Y-m-d H:i:s');
            $result = $this->m_pages->insert($data);
        } else {
            if ($file !== null && $file->isValid()) { // Only unlink if a new valid file is uploaded
                $old = $this->m_pages->find($id);
                if ($old && $old['gambar'] !== null) {
                    $oldFilePath = ROOTPATH . 'public/uploads/' . $old['gambar'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            }

            $result = $this->m_pages->update($id, $data);
        }

        if ($result) {
            return $this->unikit->output(200);
        } else {
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
        // Mengambil file gambar yang diunggah
        $file = $this->request->getFile('upload');

        // Memeriksa apakah file ada dan valid
        if ($file->isValid() && !$file->hasMoved()) {
            // Tentukan path untuk menyimpan file di folder writable
            $originalName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $file->getName()); // Membersihkan nama file
            // $uploadPath = WRITEPATH . '/uploads'; // Menyimpan di folder writable/uploads
            $uploadPath = ROOTPATH . 'public/uploads/media'; // Menyimpan di folder writable/uploads

            // Pastikan folder tujuan ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Memeriksa tipe MIME file
            $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'error' => 'Invalid file type. Only PNG, JPG, and GIF are allowed.'
                ]);
            }

            // Handle duplicate file names with timestamp instead of counter
            $newName = $originalName;
            $fileInfo = pathinfo($originalName);

            // Check if the file already exists
            if (file_exists($uploadPath . DIRECTORY_SEPARATOR . $newName)) {
                // Generate new name with timestamp if file already exists
                $timestamp = time(); // Get the current timestamp
                $newName = $fileInfo['filename'] . "_$timestamp." . $fileInfo['extension'];
            }

            // Pindahkan file ke folder tujuan
            if ($file->move($uploadPath, $newName)) {
                // Kembalikan URL gambar setelah berhasil diunggah
                return $this->response->setJSON([
                    'uploaded' => true,
                    'url' => base_url('uploads/media/' . $newName) // Mengembalikan URL yang benar
                ]);
            } else {
                return $this->response->setJSON([
                    'error' => 'File upload failed.'
                ]);
            }
        }

        $errorCode = $file->getError();

        // Get the error message based on the error code
        $errorMessage = $file->getErrorString();

        return $this->response->setJSON([
            'error' => "File is invalid. Error Code: $errorCode, Message: $errorMessage"
        ]);
    }




}