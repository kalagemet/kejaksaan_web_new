<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\GrupMenuModel;

class GrupMenu extends ResourceController
{
    protected $modelName = 'App\Models\GrupMenuModel';
    protected $format    = 'json';
    private $rand_length = 4;
	private $same_check = 0;

    public function __construct()
    {
        $this->m_grup_menu = new GrupMenuModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function getById($id)
	{
        $result = $this->m_grup_menu->find($id);
	
        return $this->unikit->output(200, $result);
	}

    public function getAll()
	{
        $result['data'] = $this->m_grup_menu->getChildMenu();
        return $this->unikit->output(200, $result);
	}

    public function save($id){
       
        $data = [
            'grup_menu' =>  $this->request->getVar('grup_menu'),
            'navbar' =>  $this->request->getVar('navbar'),
        ];
        if($id == 'new'){
            $result = $this->m_grup_menu->insert($data);
        } else {
            $result = $this->m_grup_menu->update($id,$data);
        }
        
        if($result){
            return $this->unikit->output(200);
        }else{
            return $this->unikit->output(400, $result['message']);
        } 
    }

    public function delete($id = null)
    {
        $this->request->getMethod(true) === 'DELETE' or $this->request->getMethod(true) === 'POST' or exit('Method Not Allowed');
        if ($id === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID is required to delete a record.'
            ]);
        }
    
        if ($this->m_grup_menu->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data gagal dihapus'
            ]);
        }
    }

    public function changeOrder($direction, $id)
    {
        $currentMenu = $this->m_grup_menu->find($id);

        if (!$currentMenu) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ]);
        }

        $this->resortOrder($currentMenu['navbar']);
        $currentMenu = $this->m_grup_menu->find($id);
        $newOrder = ($direction === 'naik') ? $currentMenu['urutan'] - 1 : $currentMenu['urutan'] + 1;
        $otherMenu = $this->m_grup_menu->where('navbar', $currentMenu['navbar'])
            ->where('urutan', $newOrder)
            ->first();

        if ($otherMenu) {
            $this->m_grup_menu->update($id, ['urutan' => $newOrder]);
            $this->m_grup_menu->update($otherMenu['id'], ['urutan' => $currentMenu['urutan']]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data berhasil diubah'
        ]);
    }

    private function resortOrder($navbar)
    {
        $menus = $this->m_grup_menu->where('navbar', $navbar)
            ->orderBy('urutan', 'ASC')
            ->findAll();

        $currentOrder = 1;

        foreach ($menus as $menu) {
            $this->m_grup_menu->update($menu['id'], ['urutan' => $currentOrder]);
            $currentOrder++;
        }
    }
}