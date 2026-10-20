<?php
namespace App\Models;

use CodeIgniter\Model;

class PengaduanModel extends Model
{
    protected $table = 'dt_pengaduan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['masyarakat_id', 'nomor', 'nama', 'nik', 'alamat', 'uraian', 'status', 'version'];
    protected $useTimestamps = true;

    public const STATUS = [
        'diajukan' => 'Laporan diajukan', 'penelaahan' => 'Penelaahan',
        'puldata_buket' => 'Pengumpulan data dan bahan keterangan', 'tindak_lanjut' => 'Tindak lanjut',
        'ditolak' => 'Ditolak', 'penyelidikan' => 'Ditindaklanjuti ke penyelidikan', 'dilimpahkan' => 'Dilimpahkan',
    ];
    public const NEXT = [
        'diajukan' => ['penelaahan'],
        'penelaahan' => ['puldata_buket', 'ditolak', 'dilimpahkan'],
        'puldata_buket' => ['tindak_lanjut', 'ditolak', 'dilimpahkan'],
        'tindak_lanjut' => ['ditolak', 'penyelidikan', 'dilimpahkan'],
        'ditolak' => [], 'penyelidikan' => [], 'dilimpahkan' => [],
    ];
}
