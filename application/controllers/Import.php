<?php 
defined("BASEPATH") or exit('No direct script access allowed!');

class Import extends CI_Controller 
{
	public function __construct(){
		parent::__construct();
		if(!$this->def->cek_login()){
			$this->load->view('login');
		}
	}
	public function index()
	{

		$user = $this->def->get_current('username');
		$date = date('Y-m-d');
		
		$uploadStat = $this->def->cek_upload_cabang($user, $date);
		if($uploadStat){
			$data['keterangan'] = "";
		} else {
			$data['keterangan'] = "<b style='color:red'>Anda belum mengupload data penjualan member, harap untuk mengupload sekarang! </b>";
		}

		$data['title'] = "Import Penjualan";
		$data['menu'] = 6;
		$this->load->view("header",$data);
		$this->load->view("import");
		$this->load->view("footer");
		
	}

	public function store(){
		$this->load->model('crud');
		$this->load->model('Mdmember');
		$user = $this->def->get_current('username');
		$users = explode('@', $user);
		$date = date('Y-m-d',strtotime("-1 days"));
		$convertDate = str_replace('-', '', $date);

		$nmFile = explode(".", $_FILES['attach']['name']);
		$tmpName = $_FILES['attach']['tmp_name'];

		//rename file
		$newFileName = 'Penjualan Member '.strtoupper($users[0]). '_'. date('Ymd') .'.' . end($nmFile);

		//uplaod file excel
		$upload = $this->Mdmember->uploadPenjualan($newFileName, $tmpName);
		if($upload){
			$files = explode('.', $newFileName);
		
				$data = $this->input->post();
				$data['file'] = $newFileName;
				//insert ke tabel attach penjualan untuk file yg diupload
				$ins = $this->db->insert('attach_penjualan_member', $data);
				//file isi excel insert ke table penjualan member
				$insert = $this->Mdmember->importExcel('upload_cabang/'.$newFileName);
				$this->def->pesan("success", "Berhasil upload data $files[0] ", "import");
			
		} else {
			$this->def->pesan("error", "Data gagal diupload ", "import");
		}

		
	}
}

 ?>