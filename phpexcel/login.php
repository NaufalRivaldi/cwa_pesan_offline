<?php
class login{
	var $token;
	function __construct(){
		$this->token = sha1(rand(1,10000));
	}
	function validate($username, $password){
		global $db;
		$user = $db->quote($username);
		$pass = $db->quote(sha1($password));
		$arr = array();

		$cek = $db->query("SELECT * FROM tb_admin WHERE username = $user AND password = $pass");
		if($cek->rowCount()==1){
			//update ke token baru
			$arr['type'] = "success";
			$token = $this->token;
			$updToken = $db->exec("UPDATE tb_admin SET token = '$token' WHERE username = $user AND password = $pass");

			$arr['pesan'] = "Login Sukses";
			$arr['token'] = $token;
		}
		else{
			$arr['type'] = "error";
			$arr['pesan'] = "Mohon mengisi username dan password dengan benar";
		}
		return $arr;
	}
}