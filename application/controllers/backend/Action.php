<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Action extends CI_Controller {

	public function hide(){
		$email = $_GET['email'];
		$sql = "UPDATE tb_admin SET stat = 9 WHERE username = ".$this->db->escape($email);
		$run = $this->db->query($sql);
		$this->def->pesan("success","Berhasil menghapus user dari sistem","backend/home");
	}

	public function show(){
		$email = $_GET['email'];
		$sql = "UPDATE tb_admin SET stat = 1 WHERE username = ".$this->db->escape($email);
		$run = $this->db->query($sql);
		$this->def->pesan("success","Berhasil mengembalikan user ke sistem","backend/home");
	}

	public function reset(){
		$email = $_GET['email'];
		$new = sha1('123456');
		$sql = "UPDATE tb_admin SET password = '$new' WHERE username = ".$this->db->escape($email);
		$run = $this->db->query($sql);
		$this->def->pesan("success","Berhasil mereset password user \"<strong>$email</strong>\" ke password default (<mark>123456</mark>)","backend/home");
	}


	public function add($type){
		switch($type){
			case "user":

				$username = $_POST['username'];
				$nama = $_POST['nama'];
				$pass = sha1('123456');
				$token = sha1(sha1($username).sha1($nama));

				$sql = "INSERT INTO tb_admin VALUES (".$this->db->escape($username).", ".$this->db->escape($pass).", ".$this->db->escape($nama).", ".$this->db->escape($token).", 1)";
				$run = $this->db->query($sql);
				if($run){
					$this->def->pesan("success","Berhasil menyimpan user baru","backend/home");
				}
				else{
					$this->def->pesan("error","Error : mohon cek kembali inputan apakah sudah benar atau tidak","backend/home");
				}

			break;
		}
	}
	
	public function tes(){
	    $tgl = "2017-05-01";
	    $this->load->model("mdbackdoor");
	    
	    $upl = $this->db->query("SELECT DISTINCT tgl FROM tb_history_jual");
	    foreach($upl->result_array() as $rw){
	        $tgl = $rw['tgl'];
    	    $this->mdbackdoor->record_score($tgl);
    	    echo "Data tanggal $tgl berhasil direcord <br>";
	    }
	    
	}

	public function update($id){
		$user = $id."@cwabali.com";
		$username = $this->input->post('username');
		$this->db->query("UPDATE tb_admin SET name = '$username' WHERE username = '$user'");
		$this->def->pesan("success","Berhasil mengedit user","backend/home");
	}
}