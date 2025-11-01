<?php

namespace App\Libraries;

class ExternalApi
{
    protected $_ci;

    public function __construct()
    {
        $this->_ci = \Config\Services::instance();
        $this->client = \Config\Services::curlrequest();
    }

    public function get_data_perkara(){
        $response = $this->client->request('GET', getenv('URL_API_PERKARA'));
        $body = $response->getBody();
        return json_decode($body,true);
    }

}