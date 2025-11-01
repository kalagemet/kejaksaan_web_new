<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    // protected $allowedFields    = ['id_oauth','email', 'password', 'role', 'token', 'status', 'created_at', 'updated_at'];
    protected $allowedFields    = ['email', 'password', 'role','google_id','nama_lengkap','status','token'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

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

    public function getUser(){

        $ins = session()->get('instansi');

        $builder = $this->table('user');
        $builder->select('nama_lengkap, email, instansi, i.ins_satkerkd, i.inst_nama, alamat, no_hp, last_login, user_id, role, status');
        $builder->join('instansi i', 'user.instansi = i.ins_satkerkd');
        if($ins != '00'){
            $builder->like('instansi', $ins, 'after');
        }
        $query   = $builder->get();
            
        return $query->getResultArray();
                      
       } 

       public function insert_last_login($id)
	{
        $input = ['last_login' => date("Y-m-d H:i:s")];
		$builder = $this->db->table('user');
            $builder->where('user_id', $id);
            $builder->update($input);
	}

    
}
