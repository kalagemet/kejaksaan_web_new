<?php

namespace App\Models;

use CodeIgniter\Model;

class DataModel extends Model
{
    protected $table            = 'dt_perkara';
    protected $primaryKey       = 'no_reg_perkara';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

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

    function select_row_data($tabelv, $data, $return = NULL)
	{
        $builder = $this->db->table($tabelv);

		if ($return) {
			$builder->select($return);
		}
		foreach ($data as $key => $value) {
			$builder->where($key, $value);
		}
        
		$row = $builder->get()->getRow();
		if ($return) {
			if (isset($row->{$return})) {
				return $row->{$return};
			}else{
				return false;
			}
		}else{
			return $row;
		}
	}

    function select_row_dataArray($tabelv, $data, $return = NULL)
	{
        $builder = $this->db->table($tabelv);

		if ($return) {
			$builder->select($return);
		}
		foreach ($data as $key => $value) {
			$builder->where($key, $value);
		}
        
		$result = $builder->get()->getRowArray();
		return $result;
		// if ($return) {
		// 	if (isset($row->{$return})) {
		// 		return $row->{$return};
		// 	}else{
		// 		return false;
		// 	}
		// }else{
		// 	return $row;
		// }
	}

    function select_result_data($tabelv, $data, $order = null)
	{

        $builder = $this->db->table($tabelv);

		foreach ($data as $key => $value) {
			$builder->where($key, $value);
		};
		if($order != null){
			$builder->order_by($order, 'desc');
		}
		
	
		$result = $builder->get()->getResult();
		return $result;
	}

    function select_result_dataArray($tabelv, $data, $field=null, $order = null)
	{

        $builder = $this->db->table($tabelv);
		if($field != null){
			$builder->select($field);
		}
		

		foreach ($data as $key => $value) {
			$builder->where($key, $value);
		};
		if($order != null){
			$builder->order_by($order, 'desc');
		}
		
	
		$result = $builder->get()->getResultArray();
		return $result;
	}

    public function insert_data($table, $input, $field = NULL, $id = NULL, $insert_if_not_exist = false)
	{
		if($id == NULL){
            $builder = $this->db->table($table);
            $builder->insert($input);
            $insert_id = $this->db->insertID();

			return (isset($insert_id)) ? $insert_id : FALSE;
		} else {

            $builder = $this->db->table($table);
            $builder->where($field, $id);
            $builder->update($input);

			return ( $builder) ? $id : FALSE;
			// return true;
            
		}
	}
}
