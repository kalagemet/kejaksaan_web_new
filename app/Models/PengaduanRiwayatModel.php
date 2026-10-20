<?php
namespace App\Models;
use CodeIgniter\Model;
class PengaduanRiwayatModel extends Model
{
    protected $table = 'dt_pengaduan_riwayat';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['pengaduan_id', 'status', 'tanggapan', 'tujuan_pelimpahan', 'admin_id', 'admin_nama', 'created_at'];
}
