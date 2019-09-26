<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->def->cek_login()){
			$this->load->view('login');
		} else {
			$level = $this->def->get_current('level');
			if($level!="sa"){
				$this->def->pesan("error", "Anda tidak memiliki hak akses ke halaman tersebut", 'import');
			}
		}
	}

	public function index()
	{
		$data['title'] = "Download Penjualan";
		$data['menu'] = 7;
		$data['file'] = $this->db->group_by('tgl')->order_by('tgl', 'desc')->get('attach_penjualan_member')->result_array();

		$this->load->view("header",$data);
		$this->load->view('download');
		$this->load->view("footer");
	
	}

	public function list($tgl){
		$data['title'] = "List Cabang";

		$data['cabang'] = $this->db->where('tgl', $tgl)->get('attach_penjualan_member')->result_array();

		$data['menu'] = 7;
		$this->load->view("header",$data);
		$this->load->view('list_cabang');
		$this->load->view("footer");
	}

}
