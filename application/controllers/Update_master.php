<?php defined('BASEPATH') or exit('No direct script access allowed'); 

class Update_master extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		if(!$this->def->cek_login()){
			$this->load->view('login');
		} else {
			if(!$this->def->checkUserMaster($this->def->get_current('username'))){
				$this->def->pesan('danger', 'Anda tidak bisa mengakses halaman tersebut', 'home');
			}
		}
		
	}

	public function index(){
		$data['menu'] = 10;
		$data['title'] = "Update Master";
		$data['file'] = $this->db->get('file_master')->result();
		$data['last_update'] = $this->db->get('file_master')->row();
		$this->load->view('header', $data);
		$this->load->view('update_master');
		$this->load->view('footer');
	}

	public function store(){
		$this->load->model('mdmaster');
		$tmp = $_FILES['file']['tmp_name'];
		$name = $_FILES['file']['name'];

		$upload = $this->mdmaster->upload_master($name, $tmp);
		if($upload){
			$name = 'data-master.rar';
			$data['file_name'] = $name;
			$data['tgl'] = date('Y-m-d');

			$search = $this->db->where('file_name', $name)->get('file_master')->row();
			if(!$search){
				$this->db->insert('file_master', $data);
			} else {
				$this->db->where('file_name', $search->file_name)->update('file_master', $data);
			}
			
			$this->def->pesan("success", "Berhasil upload data ", "update_master");
		} else {
			$this->def->pesan("danger", "Upload gagal, coba lagi!", "update_master");
		}
	}
}

?>