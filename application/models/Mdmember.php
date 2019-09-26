<?php defined('BASEPATH') or exit('No direct script access allowed!');

class Mdmember extends CI_Model
{
	public function dateFormat($date){
		$month = array(
			'Jan' => '01',
			'Feb' => '02',
			'Mar' => '03',
			'Apr' => '04',
			'May' => '05',
			'Jun' => '06',
			'Jul' => '07',
			'Aug' => '08',
			'Sep' => '09',
			'Oct' => '10',
			'Nov' => '11',
			'Dec' => '12'
		);

		$split = explode('-', $date);
		return '20'.$split[2]. '-' . $month[$split[1]] . '-'. $split[0];
	}

	public function uploadPenjualan($filename, $tmp){
		$ext = explode('.', $filename);
		if($ext[1] <> 'xlsx'){
			$this->def->pesan("danger", "Upload data gagal, hanya dapat mengupload format xlsx ", "import");
			return false;
		}
		$loc = 'upload_cabang/'. $filename;
		move_uploaded_file($tmp, $loc);
		return $filename;
	}

	//import penjualan member cabang
	public function importExcel($files){
		require_once('phpexcel/excel_reader2.php');
		require_once('phpexcel/SpreadsheetReader.php');
		try {
			$reader = new SpreadsheetReader($files);
		} catch(Exception $E){
			echo $E->getMessage();
			die();
		}


		$query = "INSERT INTO penjualan_member VALUES ";
		foreach($reader as $key => $row){
			if($row[0] == 'nmor' ){
				//kalau kolomnya beda otomatis data salah
				if($row[2] != 'kdlg'){
					//hapus dulu data dr tb attach_penjualan_member
					$path = explode('/', $files);
					$this->db->where('file', end($path))->delete('attach_penjualan_member');
					unlink($files);
					$this->def->pesan("danger", "Upload data gagal, data yang anda upload bukan penjualan member", 'import');
					
				}
				continue;
			} 	
			// kalau data sudah ada update;
			$nmor = $this->db->where('nmor', $row[0])->where('nmbr', $row[5])->get('penjualan_member')->row_array();
			if($nmor != 0){
				$action = 0;
				$update = "UPDATE penjualan_member SET nmor = '$row[0]', 
							tggl = '".$this->dateFormat($row[1])."',
							kdmember = '$row[2]',
							kdbr = '$row[4]',
							nmbr = '".str_replace("'", ' ', $row[5])."',
							juml = '$row[6]',
							hrga = '$row[7]',
							ttal = '$row[8]',
							kdmr = '$row[9]',
							kdkm = '$row[11]',
							kdjn = '$row[12]',
							kdgd = '$row[13]',
							kdsl = '$row[14]'
							where id = '$nmor[id]'
							
				";
			} else {
				$action = 1;
				$query .= "('', '".$row[0]. "','" .$this->dateFormat($row[1]). "','". $row[2] . "','". $row[4] ."','". $row[5] ."','". $row[6] ."','". $row[7] ."','". $row[8] ."','". $row[9] ."','". $row[11] ."','". $row[12] ."','". $row[13] ."','". $row[14] ."'),";
				
			}
			
			

		}

		if($action == 1){
			$query = substr($query, 0, -1);
			$run = $this->db->query($query.";");
		} else {
			$this->db->query($update);
		}

		

		//delete data tidak diperlukan 
		$delete = $this->db->like('nmbr', 'TINTER')->delete('penjualan_member');
		
	}

	public function uploadMember($filename, $tmp){
		$ext = explode('.', $filename);
		if($ext[1] <> 'xlsx'){
			$this->def->pesan("danger", "Upload data gagal, hanya dapat mengupload format xlsx ", "backend/member");
			return false;
		}
		$loc = 'upload_member/'. $filename;
		move_uploaded_file($tmp, $loc);
		return $filename;

		
	}

	public function insertExcelMember($file){
		require_once('phpexcel/excel_reader2.php');
		require_once('phpexcel/SpreadsheetReader.php');

		try {
			$reader = new SpreadsheetReader($file);
		} catch(Exception $E){
			echo $E->getMessage();
			die();
		}

		//kosongkang table member dulu sebelum insert
		$truncate = $this->db->query('TRUNCATE member');

		//kalau sudah kosong baru insert lagi, jadi update baru dia.. biar ga kedouble an
		$query = "INSERT INTO member VALUES ";
		foreach($reader as $data){
			//skip baris pertama
			if($data[0] == 'nomb'){
				continue;
			}
			//format tanggal null
			if($data[6] == '  -   -'){
				$dates = null;
				$lastUpdate = null;
			} else {
				$dates = $this->dateFormat($data[6]);
				$lastUpdate = date('Y-m-d H:i:s');
			}

			$query .= "('','".$data[0]. "',". "'" .$data[1] ."',". "'" .$data[2] ."',". "'" .$data[3] ."',". "'" .$data[4] ."',". "'" .$data[5] ."',". "'" .$dates ."',". "'" .$data[7] ."',". "'" .$lastUpdate ."'),";
		} 

		$query = substr($query, 0, -1);
		$this->db->query($query);
	}

	public function generatePossibility(){
		//kosongkan table dulu
		$del = $this->db->query("TRUNCATE kemungkinan");
		$date = date('Y-m-d');

		//get all data member & poinnya
		$data = $this->db->get('member')->result();
		$ins = "INSERT INTO kemungkinan VALUES ";
		foreach($data as $row){
			if($row->total_point != 0) {
				for($i=1; $i<=$row->total_point; $i++){
					$ins .= "('', '$row->kdmember', '$date'),";
				}
			}
		}

		$ins = substr($ins, 0, -1);

		
		$run = $this->db->query($ins.";");
	}

	public function upload_dbf($name, $tmp){
		$ext = explode('.', $name);
		if($ext[1] <> 'dbf'){
			$this->def->pesan("danger", "Upload data gagal, hanya dapat mengupload format dbf ", "update_member");
			return false;
		}
		$loc = 'upload_dbf/'. $name;
		move_uploaded_file($tmp, $loc);
		return true;
	}
}

 ?>