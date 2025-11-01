<?php

namespace App\Controllers;
use App\Libraries\Unikit;
use App\Models\DataModel;

class User extends BaseController
{
    public function __construct() {
        $this->unikit = new Unikit();
        $this->dataModel = new DataModel();
    }

    public function index(){
        $data['data'] = $this->m_setting->find(1);
        return view('admin/setting',$data);
        // return view('user/dashboard');
    }

    public function profil(){
        $whereUser = ['user_id' => session()->get('user_id')];
        $data['profil'] = $this->dataModel->select_row_data('user', $whereUser);
        $where = [
            'inst_parent' => '0',
            'klas' => '1'
        ];
        $data['kejati'] = $this->dataModel->select_result_data('instansi', $where);
        return view('user/profil', $data);
    }

    public function pelayanan_hukum(){
        $data['kategori'] = $this->permohonanModel->getMsKategori();
        $data['list'] = $this->permohonanModel->getPermohonanByUser();
        return view('user/pelayanan_hukum', $data);
    }

    public function histori(){
        return view('user/histori');
    }

    public function detail_permohonan($id){
        $data['permohonan'] = $this->permohonanModel->getByID($id);
        $data['list'] = $this->permohonanModel->getPermohonanByUser();
        return view('user/detail_permohonan', $data);
    }
    
}

