<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table            = 'dt_pegawai';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','nama','nip','jenis','jabatan','tempat_lahir','tgl_lahir','pangkat','golongan','jenis_kelamin','agama','foto'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getPegawaiWithJenis()
    {
        return $this->select('dt_pegawai.*, id_jenis, ms_jenis_pegawai.jenis_nama')
                    ->join('ms_jenis_pegawai', 'ms_jenis_pegawai.jenis_alias = dt_pegawai.jenis')
                    ->orderBy('ms_jenis_pegawai.id_jenis', 'asc')
                    ->findAll();
    }

    public function getPegawaiByJenis($jenis)
    {
        return $this->select('dt_pegawai.*, id_jenis, ms_jenis_pegawai.jenis_nama')
                    ->join('ms_jenis_pegawai', 'ms_jenis_pegawai.jenis_alias = dt_pegawai.jenis')
                    ->where('dt_pegawai.jenis',$jenis)
                    ->orderBy('urutan', 'asc')
                    ->findAll();
    }
   
}
