<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\VideoModel;

class Video extends ResourceController
{
    protected $modelName = 'App\Models\SlideshowModel';
    protected $format    = 'json';
    private $rand_length = 4;
	private $same_check = 0;

    public function __construct()
    {
        $this->m_video = new VideoModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function getById($id)
	{
        $result = $this->m_video->find($id);
	
        return $this->unikit->output(200, $result);
	}

    public function save($id){
       
        $data = [
            'link' =>  $this->request->getVar('link')
        ];
        $file = $this->request->getFile('gambar');

        if($id == 'new'){
            // $data['created_at'] = date('Y-m-d H:i:s');
            $result = $this->m_video->insert($data);
        } else {
           
            
            $result = $this->m_video->update($id,$data);
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
    
        if ($this->m_video->delete($id)) {
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