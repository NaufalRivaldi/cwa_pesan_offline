<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistic extends CI_Controller {

	public function index(){
		$this->load->model("mdbackdoor");
		if($this->mdbackdoor->cek_login()){
			$data['menu'] = 2;
			$data['title'] = "Statistic";
			$this->load->view("backend/header",$data);
			$this->load->view("backend/statistic");
			$this->load->view("backend/footer");
		}
		else
			$this->def->pesan("error","Anda harus log in terlebih dahulu","backend");
	}

}