<?php defined('BASEPATH') or exit('No direct script access allowed!');

class Mdkirim extends CI_Model
{
	public function do_upload($fileName, $tmp){
		$format = ['xlsx', 'pdf', 'XLS', 'jpg', 'png', 'docx', 'csv'];
	
		$ext = explode('.', $fileName);
		if(in_array($ext[1], $format)){
			$this->def->pesan('danger', 'Format data yang diupload salah!', 'kirim');
			return false;
		}

		$loc = 'kirim_pusat/'.$fileName;
		move_uploaded_file($tmp, $loc);
		return true;
	}
}

 ?>