<?php defined('BASEPATH') or exit('No direct script access allowed!');

class Karyawan extends CI_Controller 
{
	public function __construct(){
		parent::__construct();
		$this->load->model('mdbackdoor');
		$this->load->model('mdkaryawan');
		if(!$this->mdbackdoor->cek_login()){
			$this->load->view("backend/login");
		}
	}
	public function index(){
		$data['menu'] = 4;
		$data['title'] = "Data Karyawan";
		$data['karyawan'] = $this->db->order_by('nama_lengkap', 'asc')->where('stat', 1)->get('tb_det_karyawan')->result_array();
		
		$data['lahir'] = $this->db->like('ttl', date('d-m'))->get('tb_det_karyawan')->result_array();
		
		$teks = "Selamat Ulang Tahun <b>";
		foreach ($data['lahir'] as $karyawan) {
			$lahir = explode(",", $karyawan['ttl']);
			$tgl = $lahir[1];
			$newTgl = explode("-", $tgl);
			$tglBulan= $newTgl[0]."-".$newTgl[1];
			$teks = $teks ." ". $karyawan['nama_lengkap'].", ";
			//echo "Selamat ulang tahun ". $karyawan['nama_lengkap'];		
		}
		$teksUltah = rtrim($teks, ", ");
		$data['ultah'] = $teksUltah. "</b>. Wish you all the best!";
		$this->load->view("backend/header",$data);
		$this->load->view("backend/karyawan");
		$this->load->view("backend/footer");
	}

	public function create(){
		$data['menu'] = 4;
		$data['title'] = "Add Karyawan";
		$data['karyawan'] = $this->mdkaryawan->getDefaultValues();
		$this->load->view("backend/header",$data);
		$this->load->view("backend/create_karyawan");
		$this->load->view("backend/footer");
	}

	public function store(){
		$data = $this->input->post();
		$insert = $this->db->insert('tb_det_karyawan', $data);
		$this->def->pesan("success","Berhasil menginsert data karyawan","backend/karyawan");
	}

	public function edit($id){
		$data['menu'] = 4;
		$data['title'] = "Add Karyawan";
		$data['karyawan'] = $this->db->where('id', $id)->get('tb_det_karyawan')->row_array();
		$this->load->view("backend/header",$data);
		$this->load->view("backend/create_karyawan");
		$this->load->view("backend/footer");
	}

	public function update($id){
		$data = $this->input->post();
		$update = $this->db->where('id', $id)->update('tb_det_karyawan', $data);
		$this->def->pesan("success","Berhasil mengupdate data karyawan","backend/karyawan");
	}

	public function delete($id){
		$delete = $this->db->set('stat', 0)->where('id', $id)->update('tb_det_karyawan');
		$this->def->pesan("success","Berhasil menghapus data karyawan","backend/karyawan");
	}
}

 ?>