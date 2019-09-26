<?php defined('BASEPATH') or exit('No direct script access allwed!');

class Finance extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		if(!$this->def->cek_login()){
			$this->load->view('login');
		} else {
			$account = $this->def->get_current('username');
			if(!$this->def->is_finance($account)){
				$this->def->pesan('danger', 'Anda tidak bisa mengakses halaman tersebut', 'home');
			}
		}
	}

	public function index(){
		$data['menu'] = 8;
		$data['files'] = $this->db->query("SELECT * FROM kirim_pusat group by nama order by nama desc")->result();
		$this->load->view('header', $data);
		$this->load->view('finance');
		$this->load->view('footer');
	}

	public function check($param){
		$data['data'] = $this->db->where('nama', $param)->order_by('format', 'asc')->get('kirim_pusat')->result();
		$data['menu'] = 8;
		$this->load->view('header', $data);
		$this->load->view('check_kirim');
		$this->load->view('footer');
	}
}

 ?>