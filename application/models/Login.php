<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Model {
	var $username;

	function process(){
		$username = $_POST['username'];
		$password = $_POST['password'];

		if($this->validate($username,$password)){
			$this->username = $username;
			$this->logged_action();
            echo "
			<script>
				window.location = '';
			</script>
			";
		}
		else{
			$this->def->pesan("error","Username atau password yang anda masukkan salah","home");
		}
	}

	function validate($username,$password){
		$cek = $this->db->query("SELECT * FROM tb_admin WHERE username = ".$this->db->escape($username)." AND stat = 1");
		if($cek->num_rows()	== 1){
			$data = $cek->row_array();
			$pass = sha1($password);

			if($data['password'] <> $pass)
				return false;
			else
				return true;
		}
		else
			return false;
	}

	function logged_action(){
		//create token and session
		$_SESSION['token'] = sha1(rand(1,11000));
		$upd = $this->db->query("UPDATE tb_admin SET token = ".$this->db->escape($_SESSION['token'])." WHERE username = ".$this->db->escape($this->username));
		return true;
	}
}