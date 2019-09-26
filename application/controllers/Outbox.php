<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outbox extends CI_Controller {

	public function index(){
		if(!$this->def->cek_login()){
			redirect("");
		}

		$data['title'] = "Pesan Keluar";
		$data['menu'] = 2;
		$this->load->model("mail");

		$current = $this->def->get_current("username");
		$data['query'] = $this->mail->get_mail($current);

		$this->load->view("header",$data);
		$this->load->view("outbox",$data);
		$this->load->view("footer");
	}

	public function view($id){
		if(!$this->def->cek_login()){
			redirect("");
		}

		$this->load->model("mail");
		$this->load->model("crud");
		$data['menu'] = 2;
		$data['outbox'] = true;
		$data['id'] = $id;
		$data['row'] = $this->mail->get_message($id);
		$data['attachment'] = $this->mail->get_attachment($id);
		
		$this->load->view("header",$data);
		$this->load->view("single",$data);
		$this->load->view("footer");
		$this->mail->set_read($id);
	}

}