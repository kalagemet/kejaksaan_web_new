<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//

include_once(__DIR__."/BaseTemplate.php");

class Template extends BaseTemplate{
	protected $_ci;
	protected $baseurl;
	
	function __construct() {
		$this->_ci = &get_instance();
		$this->baseurl = base_url();
	}
	
	function display($template,$data=null){
		$data['_baseurl'] = $this->baseurl;
		$data['cekhal'] = $template;
		$data['_content'] = $this->_ci->load->view(''.$template,$data,TRUE);
		
		$arr = $this->parseContent($template, $data);
		$data = array_merge($data, $arr);
		
		$this->_ci->load->view('template/template.php',$data);
	}

}

/* End of file template.php */
/* Location: ./application/libraries/template.php */