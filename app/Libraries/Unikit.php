<?php

namespace App\Libraries;

class Unikit //Universal Kit 
{
    protected $_ci;

    public function __construct()
    {
        $this->_ci = \Config\Services::instance();
        $this->validation = \Config\Services::validation();
    }

    // public function display($content, $data = array()) // tidak direkomendasikan
    // {
    //     $data['_include'] = view('template/include', $data);
    //     $data['_content'] = view($content, $data);
    //     echo view('template/template', $data);
    // }

    public function output($code = 200, $data = array()){
        $this->response = \Config\Services::response();
        $this->response->setStatusCode($code)->setJSON($data);
        $this->response->send();
        exit;
    }

    public function validation($rules, $message = array()){
        $list_error = array(
            'required' => '{field} harus diisi',
            'numeric'  => '{field} harus berupa angka'
        ); 

        foreach($message as $key => $value){
            $list_error[$key] = $value;
        }

        $set_rules = array();
        foreach($rules as $key => $value){
            $set_rules[$value[0]] = array(
                'label' => $value[1],
                'rules' => $value[2]
            );

            $set_errors = array();
            $explode = explode('|', $value[2]);
            foreach($explode as $err){
                if(isset($list_error[$err])){
                    $set_errors[$err] = $list_error[$err];
                }
            }

            $set_rules[$value[0]]['errors'] = $set_errors;
            $this->validation->setRules($set_rules);
        }

        $request = \Config\Services::request();
        if (!$this->validation->withRequest($request)->run()) {
            $errors = $this->validation->getErrors();
            if(is_array($errors)){
                $errors = reset($errors);
            }
            $output = array(
                'status'    => 400,
                'message' => $errors,
                'error' => true
            );
            $this->output(400, $output);
        }
    }
}