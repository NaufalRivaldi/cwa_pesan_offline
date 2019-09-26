<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ___ extends CI_Controller {

	public function index()
	{
		if($this->def->cek_login()){
			$data['title'] = "___";
			$data['menu'] = 0;
			$data['submenu'] = 0;
			$this->load->view("header",$data);

			$this->load->view("footer");
		}
		else{
			$this->load->view('login');
		}
	}

}
