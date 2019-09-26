<?php defined('BASEPATH') or exit('No direct script access allowed!');

class Point extends CI_Controller
{
	public function index(){
		$this->load->model("mdbackdoor");
		if(!$this->mdbackdoor->cek_login()){
			$this->load->view("backend/login");
		}
		else{
			$data['menu'] = 6;
			$data['member'] = $this->db->get('member')->result();
			$data['last_update'] = $this->db->get('member')->row();
			$data['file'] = $this->db->group_by('tgl')->order_by('tgl', 'desc')->get('attach_penjualan_member')->result_array();
			$data['title'] = "Manage Point Member";
			$this->load->view("backend/header",$data);
			$this->load->view("backend/point");
			$this->load->view("backend/footer");
		}
	}

	public function generate(){
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

		//sekarang select tabel yang tanggal transaksinya seperti diatas
		$select = $this->db->query("SELECT sum(ttal) as total, nmor, tggl, kdmember FROM penjualan_member WHERE tggl BETWEEN '$start_date' AND '$end_date' group by nmor")->result();

		if($select) {
			$insert = "INSERT INTO score_member VALUES ";
		
			foreach($select as $data){
				//hitung point yang di dapat
				$point = floor($data->total / 100000);
				//cek data if it exist just update it 
				$cek_score = $this->db->where('nmor', $data->nmor)->get('score_member')->row_array();
				if($cek_score != 0){
					$update = "UPDATE score_member SET 
						kdmember = '$data->kdmember', 
						nmor = '$data->nmor',
						total = '$data->total',
						poin = '$point'
						WHERE nmor = '$data->nmor' ";
					$stat = 0;
				} else {
					$insert .= "('','".$data->kdmember. "',". "'" .$data->nmor ."',". "'" .$data->total ."',". "'" .$point ."'),";
					$stat = 1;
				}
				
			}
			
			if($stat == 1){
				$insert = substr($insert, 0, -1);
				$this->db->query($insert);
			} else {
				$this->db->query($update);
			}

			$this->def->pesan("success", "Update score member berhasil!", "backend/point");
		} else {
			$this->def->pesan("danger", "Update gagal, karena tidak ada data pada tanggal tersebut!", "backend/point");
		}
		

	}

	public function check($tgl){
		$data['title'] = "Cabang Pengirim";
		$data['menu'] = 6;
		$data['cabang'] = $this->db->where('tgl', $tgl)->get('attach_penjualan_member')->result_array();
		
		$this->load->view("backend/header",$data);
		$this->load->view("backend/check_point");
		$this->load->view("backend/footer");
	}
}


 ?>