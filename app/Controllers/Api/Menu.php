<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\MenuModel;
use App\Models\GrupMenuModel;

class Menu extends ResourceController
{
    protected $modelName = 'App\Models\MenuModel';
    protected $format    = 'json';
    private $rand_length = 4;
	private $same_check = 0;

    public function __construct()
    {
        $this->m_menu = new MenuModel();
        $this->m_grup_menu = new GrupMenuModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function getById($id)
	{
        $result = $this->m_menu->find($id);
	
        return $this->unikit->output(200, $result);
	}

    public function getAll()
	{
        $filter = $this->request->getVar('filter');
        $result['data'] = $this->m_menu->getMenuWithGrup($filter);
        return $this->unikit->output(200, $result);
	}

    public function save($id){
        $grup_menu_id = $this->request->getVar('lokasi') == 'footer' ? null : $this->request->getVar('grup_menu');
        $navbar = $this->request->getVar('navbar');
        $data = [
            'judul' =>  $this->request->getVar('judul'),
            'lokasi' =>  $this->request->getVar('lokasi'),
            'tipe' =>  $this->request->getVar('tipe'),
            'urutan' => $this->m_menu->getLastUrutan($grup_menu_id),
            'grup_menu_id' => $grup_menu_id,
        ];
        if ($this->request->getVar('tipe') == 'halaman') {
            $data['tipe'] = 'internal';
            $data['relasi'] = 'halaman';
            $data['content'] = $this->request->getVar('content');
            $data['link'] = null;
        } else if ($this->request->getVar('tipe') == 'berita') {
            $data['tipe'] = 'internal';
            $data['relasi'] = 'berita';
            $data['content'] = $this->request->getVar('content');
            $data['link'] = null;
        } else {
            $data['link'] = $this->request->getVar('link');
        }
        if ($data['grup_menu_id'] == 'single') {
            $check =  $this->m_grup_menu->where('grup_menu',$data['judul'])->first();
            if ($check == null) {
                $urutan_grup = $this->m_grup_menu->select('max(urutan) as urutan')->where('navbar',$navbar)->first();
                $data_grup_menu = [
                    'grup_menu' =>  $this->request->getVar('judul'),
                    'navbar' =>  $navbar,
                    'is_child' => 0,
                    'urutan' => $urutan_grup !== null ? ($urutan_grup['urutan'] + 1) : 1,
                ];
                
                $insert = $this->m_grup_menu->insert($data_grup_menu);
                $data['grup_menu_id'] = $this->m_grup_menu->insertID(); 
            }else{
                $data['grup_menu_id'] = $check['id']; 
            }
            $data['is_child'] = 0; 
        }
        if($id == 'new'){
            $result = $this->m_menu->insert($data);
        } else {
            $result = $this->m_menu->update($id,$data);
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

        $menu = $this->m_menu->find($id);

        if (!$menu) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }
        if ($menu['is_child'] == 0) {
            if ($this->m_grup_menu->delete($menu['grup_menu_id'])) {
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
        }else{
            if ($this->m_menu->delete($id)) {
                $this->resortOrder($menu['grup_menu_id']);
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
    }

    public function changeOrder($direction, $id)
    {
        $currentMenu = $this->m_menu->find($id);

        if (!$currentMenu) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ]);
        }

        $this->resortOrder($currentMenu['grup_menu_id']);
        $currentMenu = $this->m_menu->find($id);
        $newOrder = ($direction === 'naik') ? $currentMenu['urutan'] - 1 : $currentMenu['urutan'] + 1;
        $otherMenu = $this->m_menu->where('grup_menu_id', $currentMenu['grup_menu_id'])
            ->where('urutan', $newOrder)
            ->first();

        if ($otherMenu) {
            $this->m_menu->update($id, ['urutan' => $newOrder]);
            $this->m_menu->update($otherMenu['id'], ['urutan' => $currentMenu['urutan']]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data berhasil diubah'
        ]);
    }

    private function resortOrder($groupId)
    {
        $menus = $this->m_menu->where('grup_menu_id', $groupId)
            ->orderBy('urutan', 'ASC')
            ->findAll();

        $currentOrder = 1;

        foreach ($menus as $menu) {
            $this->m_menu->update($menu['id'], ['urutan' => $currentOrder]);
            $currentOrder++;
        }
    }
}