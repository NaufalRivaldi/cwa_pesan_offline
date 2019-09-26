<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//LIST OF CONTROLLER
/*
* index()
*/

class Scoreboard extends CI_Controller {

	public function index(){
		$this->load->model("mdlaporan");
		if(!$this->def->cek_login()){
			redirect("");
		}
		$data['title'] = "Scoreboard Penjualan";
		$data['menu'] = 5;
		$data['submenu'] =51;

		$this->load->view("header",$data);
		$this->load->view("scoreboard");
		$this->load->view("footer");
	}

	public function detail($divisi=null, $id=null){
		$this->load->model("mdlaporan");
		if(!$this->def->cek_login()){
			redirect("");
		}
		$data['title'] = "Detail Scoreboard Penjualan";
		$data['menu'] = 5;
		$data['submenu'] =51;
		$data['divisi'] = $divisi;
		$data['id'] = $id;

		$data['proc'] = true;
		if(empty($divisi) or empty($id)){
			$data['proc'] = false;
		}

		$this->load->view("header",$data);
		$this->load->view("detail");
		$this->load->view("footer");
	}
}