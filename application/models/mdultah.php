<?php 
defined('BASEPATH') or exit('No direct script access Allowed!');
 
class Mdultah Extends CI_Model
{

	
	public function convert($date){
	    $month = [
	        'Januari' => '01',
	        'Februari' => '02',
	        'Maret' => '03',
	        'April' => '04',
	        'Mei' => '05',
	        "Juni" => '06',
	        'Juli' => '07',
	        'Agustus' => '08',
	        'September' => '09',
	        'Oktober' => '10',
	        'November' => '11',
	        'Desember' => '12'
	        ];
	        
	    $teks = explode(",",$date);
	    $tgl = explode(" ", $teks[1]);
	    $result = $teks[0].", ".$tgl[3] ."-". $month[$tgl[2]] ."-". $tgl[1];
	    if($result) {
	        return $result;
	    } else {
	        return false;
	    }
	    
	}

	public function upload_ultah($fileName, $tmp){
		$ext = explode('.', $fileName);
		if($ext[1] <> 'xlsx'){
			
			return false;
		} else {

		$loc = 'ultah/'. $fileName;
		move_uploaded_file($tmp, $loc);
		return true;
		}
	}

	public function import_ultah($file){
		require_once('phpexcel/excel_reader2.php');
		require_once('phpexcel/SpreadsheetReader.php');

		try {
			$reader = new SpreadsheetReader($file);
		} catch(Exception $E){
			echo $E->getMessage();
			die();
		}


		$kosongkan = $this->db->query("TRUNCATE ultah");

		$query = "INSERT INTO ultah VALUES ";
		foreach($reader as $data){
			$query .= "('','".$data[0]. "',". "'" .$data[1] ."',". "'" .$data[2] ."'),";
		}

		$query = substr($query, 0,-1);
		$this->db->query($query);

		$this->db->where("nama_lengkap", '')->delete('ultah');
		$this->db->where("nama_lengkap", 'NAMA')->delete('ultah');
	}
}

 ?>