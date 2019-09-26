<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {
	function index(){
		if(isset($_SESSION['token']))
			unset($_SESSION['token']);
		if(isset($_SESSION['sudah_muncul']))
			unset($_SESSION['sudah_muncul']);
		if(isset($_SESSION['notif']))
			unset($_SESSION['notif']);


		$this->def->pesan("success","Anda sudah logout dari sistem","");
	}
}