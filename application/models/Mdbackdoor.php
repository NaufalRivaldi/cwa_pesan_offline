<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdbackdoor extends CI_Model {

	var $harduser = "admincwa";
	var $hardpass = "makan3xtidur1x";


	function cek_login(){
		if(isset($_SESSION['backdoor_token'])){
			$token = $_SESSION['backdoor_token'];
			if($token == sha1($this->hardpass))
				return true;
		}
		return false;
	}

	function login($username, $password){
		if($username == $harduser and $password == $hardpass){
			return true;
		
		} else {
			return false;
		}
	}

	function get_statistic($tgl=null, $limit=0){
		if($tgl == null){
			$skrg = date("Y-m-d");
		}
		else{
			$skrg = date("Y-m-d",strtotime($tgl));
		}
		if($limit == 0){
			$then = substr($skrg,0,-2)."01";
		}
		else{
			$thenn = strtotime($skrg) - ($limit * 24 * 60 * 60);
			$then = date("Y-m-d",$thenn);
		}
		$limit_query = "DATE(tgl) BETWEEN '$then' AND '$skrg'";

		$sql = "SELECT COUNT(id) as hit, DATE(tgl) AS tgl from tb_log where header = 'RECORD_STEP' AND $limit_query GROUP BY DATE(tgl)";
//		echo $sql;
		$run = $this->db->query($sql);
		return $run->result_array();
	}

	function update_karyawan($karyawan){
		//hapus karyawan lama
		$del = $this->db->query("DELETE FROM tb_karyawan");
		//masukin data karyawan baru
		$sql = "INSERT INTO tb_karyawan VALUES ";
		foreach($karyawan as $k){
			$sql .= "(null, ".$this->db->escape($k['kd_sales']).", ".$this->db->escape($k['nama']).", '', '', ".$this->db->escape($k['divisi']).", '', 1), ";
		}
		$sql = substr($sql,0,-2);
		$run = $this->db->query($sql);
		return true;
	}

	function update_skor($skor, $addQuery){
		$delete = $this->db->query("DELETE FROM tb_history_jual WHERE tgl BETWEEN $addQuery");
		//artinya, data di tanggal sekian sudah kosong dan siap diisi ulang

		$ins = "INSERT INTO tb_history_jual VALUES ";
		foreach($skor as $sk){
			$ins .= "(null, ".$this->db->escape($sk['kd_sales']).", ".$this->db->escape($sk['tgl']).", ".$this->db->escape($sk['divisi']).", ".$this->db->escape($sk['kd_barang']).", ".$this->db->escape($sk['jml']).", ".$this->db->escape($sk['skor']).", ".$this->db->escape($sk['brt'])."), ";
		}
		$ins = substr($ins, 0, -2);
		$run = $this->db->query($ins);
		return true;
	}


	function record_score($addQuery){
		$sql = "
		SELECT 
		a.kd_sales, a.divisi, SUM(a.skor) AS total_skor, a.tgl
		FROM tb_history_jual a 
		WHERE a.tgl BETWEEN $addQuery
		GROUP BY a.divisi, a.kd_sales, a.tgl
		ORDER BY SUM(skor) DESC
		";
		
		$cek = $this->db->query($sql);
		if($cek->num_rows() > 0){
		    //hapus data lama
		    $del = $this->db->query("DELETE FROM tb_record_score WHERE tgl BETWEEN ".$addQuery);
		}
		$sql = "INSERT INTO tb_record_score VALUES ";
		foreach($cek->result_array() as $row){
			//save ke tb temp
			$sql .= "(NULL, ".$this->db->escape($row['tgl']).", ".$this->db->escape($row['kd_sales']).", ".$this->db->escape($row['divisi']).", $row[total_skor]), ";
		}
		$sql = substr($sql,0,-2);
		$this->db->query($sql);
	}

}