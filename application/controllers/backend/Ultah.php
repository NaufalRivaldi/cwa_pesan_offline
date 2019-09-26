<?php defined('BASEPATH') or exit('No direct script access allowed!');

class Ultah extends CI_Controller 
{
	
	public function index(){
		$this->load->model('mdbackdoor');
		if(!$this->mdbackdoor->cek_login()){
			$this->load->view('login');
		} else {	
			$data['menu'] = 4;
			$data['title'] = "Data Karyawan Ultah";
			$data['karyawan'] = $this->db->order_by('nama_lengkap', 'asc')->get('ultah')->result_array();
			$this->load->view("backend/header",$data);
			$this->load->view("backend/ultah");
			$this->load->view("backend/footer");	
		}
	}

	public function create(){
		$data['menu'] = 4;
		$data['title'] = "Import Ultah";
		$this->load->view("backend/header",$data);
		$this->load->view("backend/create_karyawan");
		$this->load->view("backend/footer");
	}

	public function store(){
		$this->load->model('mdultah');
		$fileName = $_FILES['ultah']['name'];
		$tmp = $_FILES['ultah']['tmp_name'];
		$file = $this->mdultah->upload_ultah($fileName, $tmp);
		
		if($file){
			$import = $this->mdultah->import_ultah('ultah/'.$fileName);
			$this->def->pesan('success', "Data ultah berhasil diupdate", 'backend/ultah');
		} else {
			$this->def->pesan('danger', "Data ultah gagal terupdate", 'backend/ultah');
		}
	}

	public function edit($id){
		$data['menu'] = 4;
		$data['title'] = "Add Karyawan";
		$data['karyawan'] = $this->db->where('id', $id)->get('ultah')->row_array();
		$this->load->view("backend/header",$data);
		$this->load->view("backend/create_karyawan");
		$this->load->view("backend/footer");
	}

	public function update($id){
		$data = $this->input->post();
		$update = $this->db->where('id', $id)->update('ultah', $data);
		$this->def->pesan("success","Berhasil mengupdate data karyawan","backend/karyawan");
	}

	public function delete($id){
		$delete = $this->db->set('stat', 0)->where('id', $id)->update('ultah');
		$this->def->pesan("success","Berhasil menghapus data karyawan","backend/karyawan");
	}
}

 ?>