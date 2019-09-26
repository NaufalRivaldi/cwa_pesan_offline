<?php
defined("BASEPATH") or exit();

function query($sql){
	$ci =& get_instance();
	$qry = $ci->db->query($sql);
	return $qry;
}

function quote($txt){
	$ci =& get_instance();
	return $ci->db->escape($txt);
}

Class Laporan extends CI_Controller{

	public function index(){

//		$bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : date("n");
		$bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : 5; //sementara
		$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date("Y");
		$divisi = isset($_GET['divisi']) ? $_GET['divisi'] : 'CW1';
		$realtotal = 0;


		$def = $selec = "";
		$sel['CW1'] = $sel['CW2'] = $sel['CW3'] = $sel['CW4'] = $sel['CW5'] = $sel['CW6'] = $sel['CW7'] = $sel['CW8'] = $sel['CW9'] = $sel['CA0'] = "";

		if(isset($_GET['divisi'])){
			$def = $_GET['divisi'];
			$sel[$_GET['divisi']] = "selected";
		}
		if(isset($_GET['detail']))
			$selec = "checked";

		$s = $_SERVER['QUERY_STRING'];

		echo "
		<form action='' method='get'>
			<strong>Divisi</strong>
			<select name='divisi' onchange='this.form.submit()'>
				<option value='CW1' ".$sel['CW1'].">CW1</option>
				<option value='CW2' ".$sel['CW2'].">CW2</option>
				<option value='CW3' ".$sel['CW3'].">CW3</option>
				<option value='CW4' ".$sel['CW4'].">CW4</option>
				<option value='CW5' ".$sel['CW5'].">CW5</option>
				<option value='CW6' ".$sel['CW6'].">CW6</option>
				<option value='CW7' ".$sel['CW7'].">CW7</option>
				<option value='CW8' ".$sel['CW8'].">CW8</option>
				<option value='CW9' ".$sel['CW9'].">CW9</option>
				<option value='CA0' ".$sel['CA0'].">CA0</option>
			</select>

			<br>
			<label for='dtl'>
				<input $selec type='checkbox' name='detail' value='1' id='dtl' onchange='this.form.submit()'>
				Tampilkan Data Penjualan Secara Detail
			</label>
		</form>
		";
		echo "
		<a href='laporan/export/?$s'>Export Data Ini</a>
		";

		echo "
		<table class='data table'>
		";

		if(!isset($_GET['detail'])){
			echo "<tr>
			<th>Nama Kriteria</th>
			<th>Jumlah</th>
			<th>Skor</th>
			</tr>";
		}

		$kriteria = query("SELECT * FROM tb_kriteria WHERE stat = 1");
		foreach($kriteria->result_array() as $row){
			$nilai = 0;
			$pcs = 0;
			$kd_barang = $row['kd_barang'];
			$kd_merk = $row['kd_merk'];
			$kd_golongan = $row['kd_golongan'];
			$kd_satuan = $row['kd_satuan'];
			$kd_jenis = $row['kd_jenis'];

			//get list kode barang sesuai kriteria
			if(!empty($kd_barang)){
				$where = " WHERE kdbr = ".quote($kd_barang);
			}
			else{
				$where = "WHERE ";
				$km = array();
				if(!empty($kd_merk)){
					$km[] = "mrbr = ".quote($kd_merk);
				}
				if(!empty($kd_golongan)){
					$km[] = "glbr = ".quote($kd_golongan);
				}
				if(!empty($kd_satuan)){
					$km[] = "kmbr = ".quote($kd_satuan);
				}
				if(!empty($kd_jenis)){
					$km[] = "jnbr = ".quote($kd_jenis);
				}
				$imp = implode(" AND ",$km);
				$where = $where.$imp;
			}

			

			$builded = "SELECT * FROM tb_kode_barang $where";
			$cek = query($builded);


			if(!isset($_GET['detail'])){
				echo "
				<tr>
					<td>$row[rule_name]</td>
				";
			}
			else{
				echo "
				<tr>
					<th colspan=6>$row[rule_name]</th>
				</tr>
				";
			}

			if(isset($_GET['detail'])){
				echo "
				<tr>
					<th>Kode Sales</th>
					<th>Divisi</th>
					<th>Tanggal</th>
					<th>Kode Barang</th>
					<th>Jumlah</th>
					<th>Skor</th>
				</tr>";
			}


			//dari query diatas akan menghasilkan data tabel kode barang yg akan diambil
			$list = array();
			foreach($cek->result_array() as $r){
				$kdbr = $r['kdbr'];
				$list[] = quote($kdbr);
			}



			//sekarang tangkap data dari tb_history_jual
			if(count($list) > 0){
				$imp = "IN(".implode(",",$list).")";

				$adddiv = "";
				if(strlen($divisi) > 0){
					$adddiv = " AND divisi = ".quote($divisi);
				}

				$sqlget = "SELECT id, kd_sales, divisi, tgl, kd_barang, SUM(jml) AS jumlah, SUM(skor) AS total_skor FROM tb_history_jual WHERE kd_barang $imp AND MONTH(tgl) = $bulan AND YEAR(tgl) = $tahun $adddiv GROUP BY kd_barang, divisi, tgl";
				$tangkap = query($sqlget);

/*
				 echo "
				 <tr>
				 	<th colspan=6>SQL : <mark>$sqlget</mark></th>
				 </tr>
				 ";
*/

				foreach($tangkap->result_array() as $rt){
					if(isset($_GET['detail'])){
						echo "
						<tr>
							<td>(-)</td>
							<td>$rt[divisi]</td>
							<td>$rt[tgl]</td>
							<td>$rt[kd_barang]</td>
							<td>$rt[jumlah]</td>
							<td>$rt[total_skor]</td>
						</tr>
						";
					}
					$nilai += $rt['total_skor'];
					$pcs += $rt['jumlah'];

				}


			}


			if(!isset($_GET['detail'])){
				echo "
					<td>$pcs</td>
					<td><b>$nilai</b></td>
				</tr>
				";

				$realtotal += $nilai;
			}
			else{
				echo "
				<tr>
					<td colspan=4></td>
					<td><strong>$pcs</strong></td>
					<td><strong>$nilai</strong></td>
				</tr>
				<tr>
					<td colspan=6></td>
				</tr>
				";

			}

		}

		if(!isset($_GET['detail'])){
			echo "<tr>
			<td></td>
			<td></td>
			<td><mark><strong>$realtotal</strong></mark></td>
			</tr>";
		}

	}























	public function export(){

//		$bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : date("n");
		$bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : 5; //sementara
		$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date("Y");
		$divisi = isset($_GET['divisi']) ? $_GET['divisi'] : 'CW1';
		$realtotal = 0;

		$filename = "export-$tahun-$bulan-$divisi";

		$def = $sel = "";
		if(isset($_GET['divisi']))
			$def = $_GET['divisi'];
		if(isset($_GET['detail']))
			$sel = "checked";

		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);


		echo "
		<table>
		";

		if(!isset($_GET['detail'])){
			echo "<tr>
			<th>Nama Kriteria</th>
			<th>Jumlah</th>
			<th>Skor</th>
			</tr>";
		}

		$kriteria = query("SELECT * FROM tb_kriteria WHERE stat = 1");
		foreach($kriteria->result_array() as $row){
			$nilai = 0;
			$pcs = 0;
			$kd_barang = $row['kd_barang'];
			$kd_merk = $row['kd_merk'];
			$kd_golongan = $row['kd_golongan'];
			$kd_satuan = $row['kd_satuan'];
			$kd_jenis = $row['kd_jenis'];

			//get list kode barang sesuai kriteria
			if(!empty($kd_barang)){
				$where = " WHERE kdbr = ".quote($kd_barang);
			}
			else{
				$where = "WHERE ";
				$km = array();
				if(!empty($kd_merk)){
					$km[] = "mrbr = ".quote($kd_merk);
				}
				if(!empty($kd_golongan)){
					$km[] = "glbr = ".quote($kd_golongan);
				}
				if(!empty($kd_satuan)){
					$km[] = "kmbr = ".quote($kd_satuan);
				}
				if(!empty($kd_jenis)){
					$km[] = "jnbr = ".quote($kd_jenis);
				}
				$imp = implode(" AND ",$km);
				$where = $where.$imp;
			}

			

			$builded = "SELECT * FROM tb_kode_barang $where";
			$cek = query($builded);


			if(!isset($_GET['detail'])){
				echo "
				<tr>
					<td>$row[rule_name]</td>
				";
			}
			else{
				echo "
				<tr>
					<th colspan=6>$row[rule_name]</th>
				</tr>
				";
			}

			if(isset($_GET['detail'])){
				echo "
				<tr>
					<th>Kode Sales</th>
					<th>Divisi</th>
					<th>Tanggal</th>
					<th>Kode Barang</th>
					<th>Jumlah</th>
					<th>Skor</th>
				</tr>";
			}


			//dari query diatas akan menghasilkan data tabel kode barang yg akan diambil
			$list = array();
			foreach($cek->result_array() as $r){
				$kdbr = $r['kdbr'];
				$list[] = quote($kdbr);
			}



			//sekarang tangkap data dari tb_history_jual
			if(count($list) > 0){
				$imp = "IN(".implode(",",$list).")";

				$adddiv = "";
				if(strlen($divisi) > 0){
					$adddiv = " AND divisi = ".quote($divisi);
				}

				$sqlget = "SELECT id, kd_sales, divisi, tgl, kd_barang, SUM(jml) AS jumlah, SUM(skor) AS total_skor FROM tb_history_jual WHERE kd_barang $imp AND MONTH(tgl) = $bulan AND YEAR(tgl) = $tahun $adddiv GROUP BY kd_barang, divisi, tgl";
				$tangkap = query($sqlget);


				// echo "
				// <tr>
				// 	<th colspan=6>SQL : <mark>$sqlget</mark></th>
				// </tr>
				// ";


				foreach($tangkap->result_array() as $rt){
					if(isset($_GET['detail'])){
						echo "
						<tr>
							<td>(-)</td>
							<td>$rt[divisi]</td>
							<td>$rt[tgl]</td>
							<td>$rt[kd_barang]</td>
							<td>$rt[jumlah]</td>
							<td>$rt[total_skor]</td>
						</tr>
						";
					}
					$nilai += $rt['total_skor'];
					$pcs += $rt['jumlah'];

				}


			}


			if(!isset($_GET['detail'])){
				echo "
					<td>$pcs</td>
					<td><b>$nilai</b></td>
				</tr>
				";

				$realtotal += $nilai;
			}
			else{
				echo "
				<tr>
					<td colspan=4></td>
					<td><strong>$pcs</strong></td>
					<td><strong>$nilai</strong></td>
				</tr>
				<tr>
					<td colspan=6></td>
				</tr>
				";

			}

		}

		if(!isset($_GET['detail'])){
			echo "<tr>
			<td></td>
			<td></td>
			<td><strong>$realtotal</strong></td>
			</tr>";
		}

	}

}