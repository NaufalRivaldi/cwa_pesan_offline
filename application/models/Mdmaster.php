<?php defined('BASEPATH') or exit('No direct script access allowed!');

class Mdmaster extends CI_Model
{
	public function upload_master($name, $tmp){
		$ext = explode('.', $name);
		if($ext[1] <> 'rar'){
			$this->def->pesan("danger", "Upload data gagal, hanya dapat mengupload format rar atau zip ", "update_master");
			return false;
        }
        $name = "data-master.".$ext[1];
		$loc = 'upload_master/'. $name;
		move_uploaded_file($tmp, $loc);
		return true;
	}
}

 ?>