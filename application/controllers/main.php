<?php

class Main extends MY_Controller {

	function Main()
	{
		parent::MY_Controller();
	}
	
	function index()
	{
		## Set the title
		$this->data["page_title"] = "";
		
		## Load the default view
		$this->load->view('main/index', $this->data);
	}
	
	function install()
	{
		$this->data["page_title"] = "Codeigniter 2 Extended Installation";
		$this->load->view('main/install', $this->data);
	}
	
	function ci2_mods_adds()
	{
		$this->data["page_title"] = "Codeigniter 2 Extended Modifications/Additions";
		$this->load->view('main/ci2_mods_adds', $this->data);
	}
	
	function ci_and_phake()
	{
		$this->data["page_title"] = "Codeigniter & Phake";
		$this->load->view('main/ci_and_phake', $this->data);
	}
	
	function ci_and_capistrano()
	{
		$this->data["page_title"] = "Codeigniter & Capistrano";
		$this->load->view('main/ci_and_capistrano', $this->data);
	}
	
	function ci2_extended_roadmap()
	{
		$this->data["page_title"] = "Codeigniter 2 Extended Roadmap";
		$this->load->view('main/ci2_extended_roadmap', $this->data);
	}
	
}

/* End of file main.php */
/* Location: ./system/application/controllers/main.php */