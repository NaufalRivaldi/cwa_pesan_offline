<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdlaporan extends CI_Model {


	function build_kriteria($kriteria){
		if(is_array($kriteria)){
			$arr = array();
			foreach($kriteria as $k){
				array_push($arr,$this->run_krit($k));
			}
			$ret = implode(" OR ", $arr);
			return $ret;
		}
		else
			return $this->run_krit($kriteria);
	}

	function run_krit($item){
		return "kd_barang LIKE ".$this->db->escape("$item%");
	}


	public function ex($tg_a,$tg_b,$divisi="",$kriteria=null){
		$indos_1 = $this->def->indo_date($tg_a);
		$indos_2 = $this->def->indo_date($tg_b);

		$indo_1 = date("Ymd",strtotime($tg_a));
		$indo_2 = date("Ymd",strtotime($tg_b));

		if($tg_a == $tg_b){
			$out = $divisi." ".$indo_1;
			$outs = $indos_1;
		}
		else{
			$out = $divisi." $indo_1 - $indo_2";
			$outs = "$indos_1 - $indos_2";
		}


		//PHP Excel Properties
		include "ExcelClass/PHPExcel.php";
		$ex = new PHPExcel();
		//set Document Properties
		$ex->getProperties()->setCreator("Christian Rosandhy")
							->setLastModifiedBy("Christian Rosandhy")
							->setTitle("Skor Produk Unggulan");

		$adaa = "";
		if($divisi <> "")
			$adaa = "Divisi $divisi ";

		$ex ->setActiveSheetIndex(0)
			->setCellValue("A1","Laporan Skor Penjualan Produk Unggulan")
			->setCellValue("A2",$adaa."per tanggal $outs")
			->setCellValue("A3","No")
			->setCellValue("B3","Nama Karyawan")
			->setCellValue("C3","Kode Sales")
			->setCellValue("D3","Divisi")
			->setCellValue("E3","Alamat")
			->setCellValue("F3","Telepon")
			->setCellValue("G3","Skor");

		$addDv = "";
		if($divisi <> "")
			$addDv = "divisi = ".$this->db->escape($divisi)." AND ";

		$query = $this->db->query("SELECT * FROM tb_karyawan WHERE $addDv stat <> 0");

		$c = 5;
		$no = 1;
		foreach($query->result_array() as $row){

			$kr = "";
			if($kriteria <> "" and !is_null($kriteria)){
				$kr = $this->build_kriteria($kriteria);
			}

			$nilai = $this->get_score($row['kd_sales'],$row['divisi'],$tg_a,$tg_b,$kr);

			$ex ->setActiveSheetIndex(0)
				->setCellValue("A$c",$no)
				->setCellValue("B$c",$row['nama'])
				->setCellValue("C$c",$row['kd_sales'])
				->setCellValue("D$c",$row['divisi'])
				->setCellValue("E$c",$row['alamat'])
				->setCellValue("F$c",$row['telp'])
				->setCellValue("G$c",$nilai);
			$no++;
			$c++;
		}

		$ex->getActiveSheet()->setTitle("Skor Produk Unggulan");
		$ex->setActiveSheetIndex(0);
		//Redirecting to Save
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="SkorPU '.$out.' .xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($ex, 'Excel2007');
		$objWriter->save('php://output');	
	}


	function get_karyawan($id){
		$query = $this->db->query("SELECT * FROM tb_karyawan WHERE id = ".$this->db->escape($id));
		return $query->result_array();
	}

	function get_karyawans($divisi, $kd_sales){
		$query = $this->db->query("SELECT * FROM tb_karyawan WHERE divisi = ".$this->db->escape($divisi)." AND kd_sales = ".$this->db->escape($kd_sales));
		return $query->row_array()['nama'];
	}



	function create_detail($kd_sales,$divisi, $tga, $tgb, $krit=""){

		$kt = "";
		if($krit <> "")
			$kt = "AND (" . $this->build_kriteria($krit) .") ";

		$query = $this->db->query("SELECT * FROM tb_history_jual WHERE kd_sales = ".$this->db->escape($kd_sales)." AND divisi = ".$this->db->escape($divisi)." AND tgl BETWEEN ".$this->db->escape($tga)." AND ". $this->db->escape($tgb)." AND skor > 0 $kt");
		return $query->result_array();
	}

	function get_nama_barang($kd_barang){
		$q = $this->db->query("SELECT nm_barang FROM tb_penjualan WHERE kd_barang = ".$this->db->escape($kd_barang));
		return $q->row_array();
	}

	public function get_rule(){
		$this->db->where("stat",1);
		$this->db->select("id, rule_name");
		$query = $this->db->get("tb_kriteria");
		return $query->result_array();
	}

	public function get_rule_list($tgl_a, $tgl_b, $filter){

		$krit = array();
		if(count($filter) > 0){
			foreach($filter as $f){
				$krit[] = $f;
			}
		}
		else{
			$krit = array($filter);
		}


		$this->db->where("tgl BETWEEN '$tgl_a' AND '$tgl_b'");
		$n = 0;

		$group = "(";
		$artmp = array();
		foreach($krit as $kr){
			array_push($artmp,"kd_barang LIKE '%$kr%'");
		}
		$imp = implode(" OR ",$artmp);
		$group .= $imp;
		$group .=")";
		

		$this->db->where($group);
		$query = $this->db->get("tb_history_jual");
		return $query->result_array();
	}



	public function get_divisi(){
		$sql = "SELECT DISTINCT divisi FROM tb_karyawan ORDER BY divisi";
		$run = $this->db->query($sql);
		$arr = array();
		foreach($run->result_array() as $row){
			$arr[] = $row['divisi'];
		}
		
		return $arr;
	}

	public function detail_score($kd_sales, $divisi, $tgl_a, $tgl_b, $produk){
		// if($produk != ''){
		// 	$produk = "AND kd_barang IN 
		// 	(SELECT kdbr FROM tb_kode_barang WHERE nmbr LIKE '%".$produk."%')";
		// }

		// $sql = "
		// SELECT
		// 	kd_sales, tgl, divisi, kd_barang, SUM(jml) AS jml, SUM(skor) AS skor
		// FROM `tb_history_jual` 
		// WHERE 
		// 	kd_sales = ".intval($kd_sales)." AND divisi = ".$this->db->escape($divisi)."
		// 	AND tgl BETWEEN ".$this->db->escape($tgl_a)." AND ".$this->db->escape($tgl_b)."
		// 	$produk
		// GROUP BY kd_barang, tgl
		// ORDER BY kd_barang, tgl
		// ";

		// $run = $this->db->query($sql);
		// return $run->result_array();
		
		$this->db->select('kd_sales, tgl, divisi, kd_barang, SUM(jml) AS jml, SUM(skor) AS skor');
		$this->db->where('tgl >=', $tgl_a);
		$this->db->where('tgl <=', $tgl_b);
		$this->db->where('kd_sales', $kd_sales);
		$this->db->where('divisi', $divisi);

		$arr = array();
		if($produk != ''){
			$sql = $this->db->query("SELECT kdbr FROM tb_kode_barang WHERE nmbr LIKE '%$produk%'");
			foreach($sql->result_array() as $row){
				$arr[] = $row['kdbr'];
			}
			$this->db->where_in('kd_barang', $arr);
		}
		$this->db->group_by(array("kd_barang", "tgl"));
		$this->db->order_by('tgl', 'DESC');
		return $this->db->get('tb_history_jual')->result_array();
	}

	public function total_penjualan($tgl_awal, $tgl_akhir, $divisi){
		if(empty($divisi)){
			// $sql = "SELECT SUM(a.jml*a.brt) AS ttl_jml, a.divisi FROM tb_history_jual a LEFT JOIN tb_kode_barang b ON a.kd_barang = b.kdbr WHERE a.tgl BETWEEN '$tgl_awal' AND '$tgl_akhir' GROUP BY(a.divisi) ORDER BY(ttl_jml) DESC";

			$this->db->select('SUM(jml * brt) AS ttl_jml, divisi');
			$this->db->from('tb_history_jual');
			$this->db->where('tb_history_jual.tgl >=', $tgl_awal);
			$this->db->where('tb_history_jual.tgl <=', $tgl_akhir);
			$this->db->group_by('divisi');
			$this->db->order_by('ttl_jml', 'desc');
		}else{
			// $sql = "SELECT SUM(a.jml*a.brt) AS ttl_jml, a.divisi FROM tb_history_jual a LEFT JOIN tb_kode_barang b ON a.kd_barang = b.kdbr WHERE a.tgl BETWEEN '$tgl_awal' AND '$tgl_akhir' AND a.divisi LIKE '$divisi' GROUP BY(a.divisi)";

			$this->db->select('SUM(jml * brt) AS ttl_jml, divisi');
			$this->db->from('tb_history_jual');
			$this->db->where('tgl >=', $tgl_awal);
			$this->db->where('tgl <=', $tgl_akhir);
			$this->db->like('divisi', $divisi);
			$this->db->group_by('divisi');
		}
		

		return $this->db->get()->result_array();
	}

	public function total_penjualan_detail($tgl_awal, $tgl_akhir, $divisi){
		$this->db->select('tb_history_jual.divisi, SUM(tb_history_jual.jml) AS jml, SUM(tb_history_jual.jml * tb_history_jual.brt) AS total, tb_history_jual.kd_barang, tb_kode_barang.mrbr');
		$this->db->from('tb_history_jual');
		$this->db->join('tb_kode_barang', 'tb_history_jual.kd_barang = tb_kode_barang.kdbr');
		$this->db->where('tb_history_jual.tgl >=', $tgl_awal);
		$this->db->where('tb_history_jual.tgl <=', $tgl_akhir);
		$this->db->like('tb_history_jual.divisi', $divisi);
		$this->db->group_by('tb_kode_barang.mrbr');

		return $this->db->get()->result_array();
	}

	public function get_score($tgl_a, $tgl_b, $divisi){
		$ifand = "";
		if(strlen($divisi) > 0){
			$ifand = "AND a.divisi = ".$this->db->escape($divisi);
		}

		$sql = "
		SELECT 
			a.kd_sales, a.divisi, SUM(a.skor) AS total_skor 
		FROM tb_history_jual a 
		WHERE a.tgl BETWEEN ".$this->db->escape($tgl_a)." AND ".$this->db->escape($tgl_b)."
		$ifand
		GROUP BY a.divisi, a.kd_sales
		ORDER BY SUM(skor) DESC
		";

		$run = $this->db->query($sql);
		return $run->result_array();
	}
	
	public function get_score_produk($tgl_a, $tgl_b, $divisi, $produk){
		$arr = array();
		$sql = $this->db->query("SELECT kdbr FROM tb_kode_barang WHERE nmbr LIKE '%$produk%'");
		foreach($sql->result_array() as $row){
			$arr[] = $row['kdbr'];
		}
		
		$this->db->select('kd_sales, divisi, SUM(skor) AS total_skor');
		$this->db->where('tgl >=', $tgl_a);
		$this->db->where('tgl <=', $tgl_b);

		if(strlen($divisi) > 0){
			$this->db->where('divisi', $divisi);
		}

		$this->db->where_in('kd_barang', $arr);
		$this->db->group_by(array("divisi", "kd_sales"));
		$this->db->order_by('SUM(skor)', 'DESC');
		return $this->db->get('tb_history_jual')->result_array();
	}

	public function get_max_score($tgl_a, $tgl_b, $divisi){
		$ifand = "";
		if(strlen($divisi) > 0){
			$ifand = "AND a.divisi = ".$this->db->escape($divisi);
		}
		$sql = "
		SELECT SUM(a.skor) AS total_skor FROM tb_history_jual a
		WHERE a.tgl BETWEEN ".$this->db->escape($tgl_a)." AND ".$this->db->escape($tgl_b)."
		$ifand
		GROUP BY a.divisi, a.kd_sales
		ORDER BY SUM(a.skor) DESC
		LIMIT 1
		";

		$run = $this->db->query($sql);
		$row = $run->row_array();
		return $row['total_skor'];
	}

	public function karyawan_array(){
		$sql = $this->db->query("SELECT * FROM tb_karyawan WHERE stat = 1");
		$arr = array();
		foreach($sql->result_array() as $row){
			$arr[$row['divisi']][$row['kd_sales']] = $row['nama'];
		}
		return $arr;
	}

	public function better_division($input){
		$list = array(
			"CW1" => "Citra Warna 1 Imam Bonjol",
			"CW2" => "Citra Warna 2 Imam Bonjol",
			"CW3" => "Citra Warna 3 Buluh Indah",
			"CW4" => "Citra Warna 4 Canggu",
			"CW5" => "Citra Warna 5 Teuku Umar Barat",
			"CW6" => "Citra Warna 6 Sunset Road",
			"CW7" => "Citra Warna 7 Gatot Subroto",
			"CW8" => "Citra Warna 8 Ubud",
			"CW9" => "Citra Warna 9 Mumbul Nusa Dua",
			"CA0" => "Citra Warna 10 Mahendradatha",
			"CA1" => "Citra Warna 11 Semabaung Gianyar",
			"CA2" => "Citra Warna 12 Kediri Tabanan",
			"CA3" => "Citra Warna 13 Panjer",
			"CA4" => "Citra Warna 14 Dalung",
			"CA5" => "Citra Warna 15 Singaraja",
			"CA6" => "Citra Warna 16 Tibubeneng",
			"CA7" => "Citra Warna 17 WR. Supratman",
			"CA8" => "Citra Warna 18 Waturenggong",
			"CA9" => "Citra Warna 19 Ahmad Yani",
			"CL1" => "Citra Warna Lombok 1",
			"CS1" => "Citra Warna Makassar"
		);

		if(isset($list[$input]))
			return $list[$input];
		return false;
	}

	public function better_code($kd_barang){
		$sql = $this->db->query("SELECT nm_barang FROM tb_kode WHERE kd_barang = ".$this->db->escape($kd_barang));
		if($sql->num_rows() == 0)
			return $kd_barang;
		else{
			$row = $sql->row_array();
			return $row['nm_barang'];
		}

	}

	public function better_tgl(){
		$sql = "SELECT DISTINCT tgl FROM tb_history_jual ORDER BY tgl DESC LIMIT 1";
		$run = $this->db->query($sql);
		$row = $run->row_array();
		return $row['tgl'];
	}

}