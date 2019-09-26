<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scoreboard extends CI_Controller {

	public function index(){
		$this->load->model("mdbackdoor");
		if($this->mdbackdoor->cek_login()){
			$data['menu'] = 3;
			$data['title'] = "Scoreboard Penjualan";
			$this->load->view("backend/header",$data);
			$this->load->view("backend/scoreboard");
			$this->load->view("backend/footer");
		}
		else
			$this->def->pesan("error","Anda harus log in terlebih dahulu","backend");
	}

	public function process(){
		$this->load->model("mdbackdoor");
		$error = 0;
		if(isset($_POST['btn'])){
			$file = $_FILES['file'];
			if($file['error'] > 0){
				$error = 1;
			}
			$fname = $file['name'];
			$exf = explode(".",$fname);
			$n = count($exf);

			if(strtolower($exf[$n-1]) <> "cwa"){
				$error = 1;
			}
		}
		else{
			$error = 1;
		}


		if($error > 0){
			$this->def->pesan("error","Mohon upload file yang tepat untuk memproses data tersebut","backend/scoreboard");
		}
		else{
			$text = file_get_contents($file['tmp_name']);
			move_uploaded_file($file['tmp_name'],"backup/".$file['name']);
			$array = json_decode($text,true);

			$tgl = $array['tgl'];
			$karyawan = $array['karyawan'];
			$skor = $array['skor'];
			$addQuery = $array['query'];

			if($this->mdbackdoor->update_karyawan($karyawan)){
				if($this->mdbackdoor->update_skor($skor, $addQuery)){
					//upd
					$this->mdbackdoor->record_score($addQuery);
					
					$this->db->query("UPDATE tb_setting SET value = ".$this->db->escape($tgl)." WHERE param = 'last_update'");
					$this->def->pesan("success","Berhasil mengupdate data scoreboard penjualan","backend/scoreboard");
				}
			}


		}
	}

}