<?php defined('BASEPATH') or exit('No direct script access allowed!');

class Kirim extends CI_Controller
{
	public function index(){
		if($this->def->cek_login()){
			$data['title'] = "Kirim Data ke Pusat";
			$data['menu'] = 7;
			$data['kirim'] = $this->db->where('username', $this->def->get_current('username'))->order_by('nama', 'desc')->get('kirim_pusat')->result();
			$this->load->view("header",$data);
			$this->load->view("kirim-data");
			$this->load->view("footer");
		}
		else{
			$this->load->view('login');
		}
	}

	public function upload(){
		$this->load->model('mdkirim');
		$data = $this->input->post();
		$file = explode('.', $_FILES['file']['name']);
		$data['tanggal'] = date('Y-m-d');
		$data['nama'] = $file[0];
		$data['format'] = $file[1];
		$file_name = $_FILES['file']['name'];
		$tmp = $_FILES['file']['tmp_name'];
		$data['username'] = $this->def->get_current('username');

		$uploads = $this->mdkirim->do_upload($file_name, $tmp);
		if($uploads){
			//kalo upload sukses masukan ke db
			
			$data['file_name'] = $file_name;
			$cek = $this->db->where('file_name', $file_name)->get('kirim_pusat')->row_array();
			
			if(!$cek){
				$this->db->insert('kirim_pusat', $data);
			} else {
				$this->db->where('file_name', $file_name)->update('kirim_pusat', $data);
			}

			$this->def->pesan("success", "Data berhasil diupload ", "kirim");
		} 

	}
}

 ?>