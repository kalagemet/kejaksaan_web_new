<?php
namespace App\Libraries;

use App\Models\PengaduanModel;
use CodeIgniter\Database\BaseConnection;
use Config\Database;

class PengaduanService
{
    private BaseConnection $db;
    private MasyarakatFiles $files;

    public function __construct(?BaseConnection $db = null, ?MasyarakatFiles $files = null)
    {
        $this->db = $db ?? Database::connect();
        $this->files = $files ?? new MasyarakatFiles();
    }

    public function create(array $user, array $data, $identity, $document, array $evidence = []): int
    {
        $user = $this->db->table('dt_masyarakat')->where('id', $user['id'])->where('aktif', 1)->get()->getRowArray();
        if (!$user) { throw new \DomainException('Akun masyarakat belum terverifikasi.'); }
        if (count($evidence) > 5) { throw new \DomainException('Maksimal 5 berkas bukti pendukung.'); }
        $validation = \Config\Services::validation(null, false);
        $validation->setRules(['alamat' => 'required|max_length[2000]', 'uraian' => 'required|min_length[20]|max_length[3000]']);
        if (!$validation->run($data)) { throw new \DomainException(implode(' ', $validation->getErrors())); }
        $stored = [];
        try {
            $stored[] = $this->files->save($identity, 'identitas');
            $stored[] = $this->files->save($document, 'aduan');
            foreach ($evidence as $file) {
                if ($file->getError() !== UPLOAD_ERR_NO_FILE) { $stored[] = $this->files->save($file, 'bukti'); }
            }
            $this->db->transBegin();
            $now = gmdate('Y-m-d H:i:s');
            $this->db->table('dt_pengaduan')->insert([
                'masyarakat_id' => $user['id'], 'nomor' => 'ADU-' . gmdate('Ymd') . '-' . strtoupper(bin2hex(random_bytes(5))),
                'nama' => $user['nama'], 'nik' => $user['nik'], 'alamat' => $data['alamat'],
                'uraian' => $data['uraian'], 'status' => 'diajukan', 'version' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $id = (int) $this->db->insertID();
            foreach ($stored as $meta) {
                $this->db->table('dt_pengaduan_berkas')->insert(array_merge($meta, ['pengaduan_id' => $id, 'created_at' => $now]));
            }
            $this->db->table('dt_pengaduan_riwayat')->insert([
                'pengaduan_id' => $id, 'status' => 'diajukan', 'tanggapan' => 'Laporan berhasil dikirim dan menunggu penelaahan petugas.', 'created_at' => $now,
            ]);
            if (!$this->db->transStatus()) { throw new \RuntimeException('Penyimpanan gagal.'); }
            $this->db->transCommit();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            foreach ($stored as $meta) { $this->files->remove($meta['nama_file']); }
            if ($e instanceof \DomainException) { throw $e; }
            throw new \DomainException('Pengaduan belum dapat disimpan. Silakan coba kembali.');
        }
    }

    public function detail(int $id, ?int $owner = null): array
    {
        $query = $this->db->table('dt_pengaduan')->where('id', $id);
        if ($owner !== null) { $query->where('masyarakat_id', $owner); }
        $row = $query->get()->getRowArray();
        if (!$row) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); }
        return [
            'laporan' => $row,
            'berkas' => $this->db->table('dt_pengaduan_berkas')->where('pengaduan_id', $id)->orderBy('id')->get()->getResultArray(),
            'riwayat' => $this->db->table('dt_pengaduan_riwayat')->where('pengaduan_id', $id)->orderBy('id')->get()->getResultArray(),
        ];
    }

    public function transition(int $id, int $version, string $status, string $message, string $destination, int $adminId, string $adminName): void
    {
        $row = $this->detail($id)['laporan'];
        if (!in_array($status, PengaduanModel::NEXT[$row['status']] ?? [], true)) { throw new \DomainException('Perubahan status tidak sesuai tahapan pengaduan.'); }
        if (mb_strlen(trim($message)) < 10 || mb_strlen($message) > 3000) { throw new \DomainException('Tanggapan wajib berisi 10 sampai 3000 karakter.'); }
        if ($status === 'dilimpahkan' && (!$destination || mb_strlen($destination) > 255)) { throw new \DomainException('Instansi tujuan pelimpahan wajib diisi, maksimal 255 karakter.'); }
        $this->db->transBegin();
        try {
            $now = gmdate('Y-m-d H:i:s');
            $this->db->table('dt_pengaduan')->where('id', $id)->where('version', $version)->where('status', $row['status'])
                ->set('version', 'version + 1', false)->update(['status' => $status, 'updated_at' => $now]);
            if ($this->db->affectedRows() !== 1) { throw new \DomainException('Laporan telah diperbarui petugas lain. Muat ulang halaman.'); }
            $this->db->table('dt_pengaduan_riwayat')->insert([
                'pengaduan_id' => $id, 'status' => $status, 'tanggapan' => trim($message),
                'tujuan_pelimpahan' => $status === 'dilimpahkan' ? trim($destination) : null,
                'admin_id' => $adminId, 'admin_nama' => mb_substr($adminName, 0, 150), 'created_at' => $now,
            ]);
            if (!$this->db->transStatus()) { throw new \RuntimeException('Penyimpanan tanggapan gagal.'); }
            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            if ($e instanceof \DomainException) { throw $e; }
            throw new \DomainException('Tanggapan belum dapat disimpan.');
        }
    }
}
