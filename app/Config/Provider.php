<?php

namespace Config;

use Illuminate\Database\Capsule\Manager as Capsule;

// dib
class Provider {
	function __construct() {
		$this->view();
	}

	function view(){
		/* 
		dib: plz provide & declare global scoped data for view 
		at this class 
		*/
		$renderer = Services::renderer();
		$renderer->setData([
			"a" => "a1 provider",
			"b" => 2,
			"isGuest" => true,
			"isAdmin" => false,
			"_baseurl" => base_url(),
		]);
	}
}