<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\PagesModel;

class Pages extends ResourceController
{
    protected $modelName = 'App\Models\PagesModel';
    protected $format    = 'json';
    private $rand_length = 4;
	private $same_check = 0;

    public function __construct()
    {
        $this->m_pages = new PagesModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function getById($id)
	{
        $result = $this->m_pages->find($id);
	
        return $this->unikit->output(200, $result);
	}

    public function getAll()
	{
        $result['data'] = $this->m_pages->orderBy('featured','desc')->findAll();
	
        return $this->unikit->output(200, $result);
	}

    public function save($id){

        $slug = $this->request->getVar('judul');

       
        $data = [
            'judul' =>  $this->request->getVar('judul'),
            'isi' =>  $this->request->getVar('isi'),
            'slug' => slugs($slug)
        ];

        if($id == 'new'){
            // $data['created_at'] = date('Y-m-d H:i:s');
            $result = $this->m_pages->insert($data);
        } else {
            
            $result = $this->m_pages->update($id,$data);
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
    
        if ($this->m_pages->delete($id)) {
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

    public function featured($id)
    {
        $page = $this->m_pages->find($id);
        $count = $this->m_pages->where('featured >',0)->countAllResults();
        
        if ($count >= 6) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Maksimal featured hanya 6 data'
            ]);
        }else{
            $data = [
                'featured'=>!$page['featured']
            ];
            
            $result = $this->m_pages->update($id,$data);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }
    }


}