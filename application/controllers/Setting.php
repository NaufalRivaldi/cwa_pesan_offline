<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {
	public function index(){
		if(!$this->def->cek_login()){
			redirect("");
		}

		$data['title'] = "Pengaturan";
		$data['menu'] = 4;

		$current = $this->def->get_current("username");

		$this->load->view("header",$data);
		$this->load->view("setting");
		$this->load->view("footer");
	}

	public function save(){
		if(!$this->def->cek_login()){
			redirect("");
		}

		if(isset($_POST[$_SESSION['form-token']])){
			$old_pass = $_POST['old_pass'];
			$new_pass = $_POST['new_pass'];
			$new_pass2 = $_POST['new_pass2'];

			//cek old password dulu
			$current = $this->def->get_current();
			$pass = $this->def->get_admin($current,"password");
			$old_hashed = sha1($old_pass);
			if($pass <> $old_hashed){
				$this->def->pesan("error","Password lama yang anda masukkan tidak tepat.. Mohon dicoba kembali","setting");
			}
			else{
				//filter new pass
				if($new_pass <> $new_pass2){
					$this->def->pesan("error","Password baru yang Anda masukkan tidak cocok.. Silakan dicoba kembali","setting");
				}
				else if(strlen($new_pass) < 6){
					$this->def->pesan("error","Password yang Anda masukkan tidak aman.. Mohon gunakan password baru yang lebih aman (minimal 6 karakter)","setting");
				}

				//yaudalah gapapa
				$new_hashed = sha1($new_pass);
				$upd = $this->db->simple_query("UPDATE tb_admin SET password = ".$this->db->escape($new_hashed)." WHERE username = ".$this->db->escape($current));
				if($upd){
					$this->def->pesan("success","Berhasil menyimpan password baru. Mulai sekarang gunakan password baru tersebut untuk Log In.","setting");
				}
				else{
					$this->def->pesan("error","Kesalahan query. Password gagal disimpan","setting");
				}
			}


		}
	}
}