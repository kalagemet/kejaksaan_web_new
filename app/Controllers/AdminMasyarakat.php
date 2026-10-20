<?php
namespace App\Controllers;

use App\Models\MasyarakatModel;
use App\Models\PengaduanModel;
use App\Libraries\MasyarakatFiles;

class AdminMasyarakat extends \App\Controllers\Masyarakat\PortalController
{
    public function users()
    {
        $model = new MasyarakatModel();
        return view('admin/masyarakat', ['showHeaderSearch' => false, 'users' => $model->select('id,nama,nik,no_wa,email,aktif,pekerjaan,tanggal_lahir,alamat,created_at,email_verified_at')
            ->orderBy('id', 'DESC')->paginate(20, 'masyarakat'), 'pager' => $model->pager]);
    }

    public function complaints()
    {
        $model = new PengaduanModel();
        $status = $this->request->getGet('status');
        if (is_string($status) && isset(PengaduanModel::STATUS[$status])) { $model->where('status', $status); }
        return view('admin/pengaduan', ['showHeaderSearch' => false, 'laporan' => $model->orderBy('id', 'DESC')->paginate(20, 'aduan'), 'pager' => $model->pager,
            'statusFilter' => is_string($status) ? $status : '']);
    }

    public function detail($id)
    {
        return view('admin/pengaduan_detail', array_merge(service('pengaduan')->detail((int) $id), ['isAdmin' => true, 'showHeaderSearch' => false]));
    }

    public function respond($id)
    {
        try {
            service('pengaduan')->transition((int) $id, (int) $this->input('version'), $this->input('status'),
                $this->input('tanggapan'), $this->input('tujuan_pelimpahan'), (int) session('user_id'), (string) session('nama'));
            return redirect()->to(site_url('admin/pengaduan/' . $id))->with('success', 'Status dan tanggapan berhasil disimpan.');
        } catch (\DomainException $e) {
            return $this->failForm('admin/pengaduan/' . $id, $e->getMessage(), ['tanggapan' => $this->input('tanggapan'), 'tujuan_pelimpahan' => $this->input('tujuan_pelimpahan')]);
        }
    }

    public function download($id, $fileId)
    {
        $data = service('pengaduan')->detail((int) $id);
        foreach ($data['berkas'] as $file) {
            if ((int) $file['id'] === (int) $fileId) {
                try { $path = (new MasyarakatFiles())->path($file['nama_file']); }
                catch (\DomainException $e) { break; }
                return $this->response->download($path, null)->setFileName(str_replace(["\r", "\n", '"'], '_', $file['nama_asli']))
                    ->setHeader('Cache-Control', 'private, no-store')->setHeader('X-Content-Type-Options', 'nosniff');
            }
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
}
