<?php defined('BASEPATH') or exit('No direct script access allowed'); 

class Update_member extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		if(!$this->def->cek_login()){
			$this->load->view('login');
		} else {
			if(!$this->def->checkUser($this->def->get_current('username'))){
				$this->def->pesan('danger', 'Anda tidak bisa mengakses halaman tersebut', 'home');
			}
		}
		
	}

	public function index(){
		$data['menu'] = 9;
		$data['title'] = "Update Member";
		$data['file'] = $this->db->get('file_dbf')->result();
		$data['last_update'] = $this->db->get('file_dbf')->row();
		$this->load->view('header', $data);
		$this->load->view('update_member');
		$this->load->view('footer');
	}

	public function store(){
		$this->load->model('mdmember');
		$tmp1 = $_FILES['file1']['tmp_name'];
		$name1 = $_FILES['file1']['name'];

		$tmp2 = $_FILES['file2']['tmp_name'];
		$name2 = $_FILES['file2']['name'];

		if($name1 == $name2){
			$this->def->pesan("danger", "Harap mengupload 2 file yang berbeda", "update_member");
		} else {
			$upload1 = $this->mdmember->upload_dbf($name1, $tmp1);
			$upload2 = $this->mdmember->upload_dbf($name2, $tmp2);
			if($upload1 && $upload2){
				$data1['file_name'] = $name1;
				$data1['tgl'] = date('Y-m-d');

				$data2['file_name'] = $name2;
				$data2['tgl'] = date('Y-m-d');

				$search1 = $this->db->where('file_name', $name1)->get('file_dbf')->row();
				if(!$search1){
					$this->db->insert('file_dbf', $data1);
				} else {
					$this->db->where('file_name', $search1->file_name)->update('file_dbf', $data1);
				}

				$search2 = $this->db->where('file_name', $name2)->get('file_dbf')->row();
				if(!$search2){
					$this->db->insert('file_dbf', $data2);
				} else {
					$this->db->where('file_name', $search2->file_name)->update('file_dbf', $data2);
				}
				
				$this->def->pesan("success", "Berhasil upload data ", "update_member");
			} else {
				$this->def->pesan("danger", "Upload gagal, coba lagi!", "update_member");
			}
		}

		

		
	}
}

?>