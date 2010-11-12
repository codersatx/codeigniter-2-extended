<?php

class MY_Controller extends CI_Controller {
	
	var $data = array();
	
	function MY_Controller() {
		parent::CI_Controller();
		$this->is_installed();
	}
	
	function is_installed() {
		## Check to see if database.php file has been created
		$url = explode('/',str_replace('http://','',current_url()));
		if (!read_file('./application/config/database.php') && $url[1] != "install") {
			redirect('/install');
		}
	}
	
}

?>