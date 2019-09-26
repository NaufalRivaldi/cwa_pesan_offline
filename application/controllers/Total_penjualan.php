<?php defined('BASEPATH') or exit('No direct script access allowed'); 

class Total_penjualan extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		// if(!$this->def->cek_login()){
		// 	$this->load->view('login');
		// } else {
		// 	if(!$this->def->is_PU($this->def->get_current('username'))){
		// 		$this->def->pesan('danger', 'Anda tidak bisa mengakses halaman tersebut', 'home');
		// 	}
		// }
		
	}

	public function index(){
		$this->load->model("mdlaporan");
		$data['menu'] = 12;
		$data['title'] = "Total Penjualan";
		$data['divisi'] = array(
			"CW1" => "Citra Warna 1",
			"CW2" => "Citra Warna 2",
			"CW3" => "Citra Warna 3",
			"CW4" => "Citra Warna 4",
			"CW5" => "Citra Warna 5",
			"CW6" => "Citra Warna 6",
			"CW7" => "Citra Warna 7",
			"CW8" => "Citra Warna 8",
			"CW9" => "Citra Warna 9",
			"CA0" => "Citra Warna 10",
			"CA1" => "Citra Warna 11",
			"CA2" => "Citra Warna 12",
			"CA3" => "Citra Warna 13",
			"CA4" => "Citra Warna 14",
			"CA5" => "Citra Warna 15",
			"CA6" => "Citra Warna 16",
			"CA7" => "Citra Warna 17",
			"CA8" => "Citra Warna 18",
			"CA9" => "Citra Warna 19",
			"CB0" => "Citra Warna 20",
			"CL1" => "Citra Warna Lombok 1",
			"CS1" => "Citra Warna Makassar 1"
		);

		$data['last_update'] = $this->db->order_by('tgl', 'desc')->limit(1)->get('tb_history_jual')->row();
		$this->load->view('header', $data);
		$this->load->view('total_penjualan');
		$this->load->view('footer');
	}

	public function detail($tgl_awal, $tgl_akhir, $divisi){
		$this->load->model("mdlaporan");
		$data['menu'] = 12;
		$data['title'] = "Total Penjualan Cabang";
		$data['divisi'] = "";
		$data['divisi'] = array(
			"CW1" => "Citra Warna 1",
			"CW2" => "Citra Warna 2",
			"CW3" => "Citra Warna 3",
			"CW4" => "Citra Warna 4",
			"CW5" => "Citra Warna 5",
			"CW6" => "Citra Warna 6",
			"CW7" => "Citra Warna 7",
			"CW8" => "Citra Warna 8",
			"CW9" => "Citra Warna 9",
			"CA0" => "Citra Warna 10",
			"CA1" => "Citra Warna 11",
			"CA2" => "Citra Warna 12",
			"CA3" => "Citra Warna 13",
			"CA4" => "Citra Warna 14",
			"CA5" => "Citra Warna 15",
			"CA6" => "Citra Warna 16",
			"CA7" => "Citra Warna 17",
			"CA8" => "Citra Warna 18",
			"CA9" => "Citra Warna 19",
			"CB0" => "Citra Warna 20",
			"CL1" => "Citra Warna Lombok 1",
			"CS1" => "Citra Warna Makassar 1"
		);

		// Select Divisi
		foreach($data['divisi'] as $a => $b){
			if($divisi == $a){
				$data['divisi'] = $b;
			}
		}

		$data['list'] = $this->mdlaporan->total_penjualan_detail($tgl_awal, $tgl_akhir, $divisi);
		$this->load->view('header', $data);
		$this->load->view('total_penjualan_detail');
		$this->load->view('footer');
	}

	public function export($tgl_awal, $tgl_akhir, $divisi){
		// asdasdasd
		$this->load->model("mdlaporan");
		$data['menu'] = 12;
		$data['title'] = "Export Data";
		$data['divisi'] = "";
		$data['divisi'] = array(
			"CW1" => "Citra Warna 1",
			"CW2" => "Citra Warna 2",
			"CW3" => "Citra Warna 3",
			"CW4" => "Citra Warna 4",
			"CW5" => "Citra Warna 5",
			"CW6" => "Citra Warna 6",
			"CW7" => "Citra Warna 7",
			"CW8" => "Citra Warna 8",
			"CW9" => "Citra Warna 9",
			"CA0" => "Citra Warna 10",
			"CA1" => "Citra Warna 11",
			"CA2" => "Citra Warna 12",
			"CA3" => "Citra Warna 13",
			"CA4" => "Citra Warna 14",
			"CA5" => "Citra Warna 15",
			"CA6" => "Citra Warna 16",
			"CA7" => "Citra Warna 17",
			"CA8" => "Citra Warna 18",
			"CA9" => "Citra Warna 19",
			"CB0" => "Citra Warna 20",
			"CL1" => "Citra Warna Lombok 1",
			"CS1" => "Citra Warna Makassar 1"
		);

		// Select Divisi
		foreach($data['divisi'] as $a => $b){
			if($divisi == $a){
				$data['divisi'] = $b;
			}
		}

		$data['list'] = $this->mdlaporan->total_penjualan_detail($tgl_awal, $tgl_akhir, $divisi);
		$this->load->view('export-excel', $data);
	}

	public function export_all($tgl_awal, $tgl_akhir, $divisi=null){
		$this->load->model("mdlaporan");
		$data['menu'] = 12;
		$data['title'] = "Export Data All";
		$data['divisi'] = "";
		$data['divisi'] = array(
			"CW1" => "Citra Warna 1",
			"CW2" => "Citra Warna 2",
			"CW3" => "Citra Warna 3",
			"CW4" => "Citra Warna 4",
			"CW5" => "Citra Warna 5",
			"CW6" => "Citra Warna 6",
			"CW7" => "Citra Warna 7",
			"CW8" => "Citra Warna 8",
			"CW9" => "Citra Warna 9",
			"CA0" => "Citra Warna 10",
			"CA1" => "Citra Warna 11",
			"CA2" => "Citra Warna 12",
			"CA3" => "Citra Warna 13",
			"CA4" => "Citra Warna 14",
			"CA5" => "Citra Warna 15",
			"CA6" => "Citra Warna 16",
			"CA7" => "Citra Warna 17",
			"CA8" => "Citra Warna 18",
			"CA9" => "Citra Warna 19",
			"CL1" => "Citra Warna Lombok 1",
			"CS1" => "Citra Warna Makassar 1"
		);

		$data['tgl_awal'] = $tgl_awal;
		$data['tgl_akhir'] = $tgl_akhir;

		$this->load->view('export-excel-all', $data);
	}
}

?>