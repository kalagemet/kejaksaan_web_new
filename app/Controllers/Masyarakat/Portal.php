<?php
namespace App\Controllers\Masyarakat;

use App\Libraries\MasyarakatFiles;
use App\Models\PengaduanModel;

class Portal extends PortalController
{
    public function dashboard()
    {
        $user = $this->account();
        return view('masyarakat/dashboard', ['user' => $user,
            'jumlah' => (new PengaduanModel())->where('masyarakat_id', $user['id'])->countAllResults()]);
    }

    public function comingSoon($feature)
    {
        $titles = ['besuk' => 'Pengajuan izin besuk tahanan', 'informasi' => 'Permintaan informasi penanganan perkara / informasi publik'];
        if (!isset($titles[$feature])) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); }
        return view('masyarakat/coming_soon', ['user' => $this->account(), 'title' => $titles[$feature]]);
    }

    public function profile() { return view('masyarakat/profile', ['user' => $this->account()]); }

    public function saveProfile()
    {
        $user = $this->account();
        $files = new MasyarakatFiles();
        $photo = null;
        $data = [];
        foreach (['nama', 'pekerjaan', 'tanggal_lahir', 'alamat'] as $key) { $data[$key] = $this->input($key); }
        $data['tanggal_lahir'] = $data['tanggal_lahir'] ?: null;
        try {
            $file = $this->request->getFile('foto');
            if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                $photo = $files->save($file, 'foto');
                $data['foto'] = $photo['nama_file'];
            }
            service('masyarakatAccounts')->updateProfile((int) $user['id'], $data);
            if ($photo) { $files->remove($user['foto']); }
            return redirect()->to(site_url('masyarakat/profil'))->with('success', 'Profil berhasil diperbarui.');
        } catch (\DomainException $e) {
            if ($photo) { $files->remove($photo['nama_file']); }
            return $this->failForm('masyarakat/profil', $e->getMessage(), $data);
        }
    }

    public function changeContact()
    {
        try {
            $this->limit('contact');
            $challenge = service('masyarakatAccounts')->contactOtp((int) $this->account()['id'], $this->input('jenis'), $this->input('kontak_baru'), $this->rawPassword('password_saat_ini'));
            session()->set(['masyarakat_otp' => $challenge['token'], 'masyarakat_otp_purpose' => $this->input('jenis') === 'email' ? 'email_change' : 'wa_change']);
            return redirect()->to(site_url('masyarakat/verifikasi'))->with($challenge['sent'] ? 'success' : 'error', $challenge['sent']
                ? 'OTP dikirim ke email baru untuk perubahan email, atau ke email terverifikasi saat ini untuk perubahan nomor WA.'
                : 'Email OTP belum dapat dikirim. Kontak lama tetap berlaku. Silakan kirim ulang.');
        } catch (\DomainException $e) { return $this->failForm('masyarakat/profil', $e->getMessage()); }
    }

    public function photo()
    {
        $user = $this->account();
        try { $path = (new MasyarakatFiles())->path((string) $user['foto']); }
        catch (\DomainException $e) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); }
        return $this->response->setHeader('Cache-Control', 'private, no-store')->setHeader('X-Content-Type-Options', 'nosniff')
            ->setContentType(mime_content_type($path))->setBody(file_get_contents($path));
    }

    public function complaints()
    {
        $user = $this->account();
        $model = new PengaduanModel();
        return view('masyarakat/pengaduan_list', ['user' => $user,
            'laporan' => $model->where('masyarakat_id', $user['id'])->orderBy('id', 'DESC')->paginate(15, 'aduan'),
            'pager' => $model->pager]);
    }

    public function createComplaint() { return view('masyarakat/pengaduan_form', ['user' => $this->account()]); }

    public function saveComplaint()
    {
        $data = ['alamat' => $this->input('alamat'), 'uraian' => $this->input('uraian')];
        try {
            $this->limit('complaint', 5, 3600);
            $id = service('pengaduan')->create($this->account(), $data, $this->request->getFile('identitas'),
                $this->request->getFile('dokumen_aduan'), $this->request->getFileMultiple('bukti') ?? []);
            return redirect()->to(site_url('masyarakat/pengaduan/' . $id))->with('success', 'Pengaduan berhasil dikirim. Pantau perkembangan melalui halaman ini.');
        } catch (\DomainException $e) { return $this->failForm('masyarakat/pengaduan/buat', $e->getMessage(), $data); }
    }

    public function complaint($id)
    {
        return view('masyarakat/pengaduan_detail', array_merge(service('pengaduan')->detail((int) $id, (int) $this->account()['id']), ['user' => $this->account(), 'isAdmin' => false]));
    }

    public function download($id, $fileId)
    {
        $data = service('pengaduan')->detail((int) $id, (int) $this->account()['id']);
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
