<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Access;
use App\Libraries\Unikit;
use App\Models\UserModel;
use App\Models\DataModel;
use App\Models\PenghuniModel;
use App\Models\UsahaModel;

class Admin extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';
    private $rand_length = 4;
	private $same_check = 0;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->datamodel = new DataModel();
        $this->penghuniModel = new PenghuniModel();
        $this->access = new Access();
        $this->unikit = new Unikit();
    }

    public function getPenghuni()
	{
		$output['data'] =  $data = $this->penghuniModel->getPenghuni();
        $this->unikit->output(200, $output);
	}

    public function getUser()
	{
        $output['data'] =  $data = $this->userModel->getUser();
        return $this->unikit->output(200, $output);
	}

    

    


}