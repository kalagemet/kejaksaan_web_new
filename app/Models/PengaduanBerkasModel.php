<?php
namespace App\Models;
use CodeIgniter\Model;
class PengaduanBerkasModel extends Model
{
    protected $table = 'dt_pengaduan_berkas';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['pengaduan_id', 'jenis', 'nama_asli', 'nama_file', 'mime', 'ukuran', 'created_at'];
}
