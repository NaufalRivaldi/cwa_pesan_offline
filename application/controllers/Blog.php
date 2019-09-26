<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//LIST OF CONTROLLER
/*
* index()
*/

class Blog extends CI_Controller {

	public function index()
	{
		if(!$this->def->cek_login()){
			redirect("");
		}
		$data['title'] = "Blog";
		$data['menu'] = 2;
		$data['submenu'] = 21;

		$this->load->view("header",$data);

		$this->load->model("table");

		$this->table->query("SELECT * FROM tb_blog");
		$this->table->where("stat <> 9");
		$this->table->order("ORDER BY tgl_update DESC");
		$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->table->page($current_page);
		$data['query'] = $this->table->run();
		$data['page'] = $current_page;
		$ttq = $this->db->query("SELECT * FROM tb_blog WHERE stat <> 9");
		$data['totalquery'] = $ttq->num_rows();

		$this->load->view("blog",$data);

		$this->load->view("footer");

	}

	public function add(){
		if(!$this->def->cek_login()){
			redirect("");
		}

		$data['title'] = "Add Blog Data";
		$data['menu'] = 2;
		$data['submenu'] = 22;

		$this->load->view("header",$data);
		$this->load->view("footer");

	}

	public function add_proses(){

	}


}