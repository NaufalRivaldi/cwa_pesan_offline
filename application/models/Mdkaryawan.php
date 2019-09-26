<?php 
defined('BASEPATH') or exit('No direct script access Allowed!');
 
class Mdkaryawan Extends CI_Model
{
	public function getDefaultValues(){
		return [ 
			'nama_lengkap' => '',
			'ttl' => '',
			'devisi' => '',
			
		];
	}
}

 ?>