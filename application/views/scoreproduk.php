<h2>Score Produk Unggulan</h2>
<?php
$last = $this->def->get_setting("last_update");
$tgl_form = date("Y-m-d", strtotime($this->mdlaporan->better_tgl()));
?>
<div>
	Last update : <strong><?=$this->def->indo_date($last)?></strong>
</div>

<form action="" method="get" class="form-horizontal well">
	<div class="row">
		<div class="col-sm-4">
			<label for="dari">Dari Tanggal</label>
			<input type="date" id="dari" name="dari" class="form-control" value="<?php if(isset($_GET['dari'])){echo $_GET['dari'];}else{echo $tgl_form;}?>">
		</div>
		<div class="col-sm-4">
			<label for="sampai">Sampai Tanggal</label>
			<input type="date" id="sampai" name="sampai" value="<?php if(isset($_GET['sampai'])){echo $_GET['sampai'];}else{echo $tgl_form;}?>" class="form-control">
		</div>
		<div class="col-sm-4">
			<label for="dvs">Divisi</label>
			<select name="divisi" id="dvs" class="form-control">
				<option value="">- Semua Divisi -</option>
				<?php
				$list_divisi = $this->mdlaporan->get_divisi();
				$dvv = '0x';
				if(isset($_GET['divisi'])){
					$dvv = $_GET['divisi'];
				}

				foreach($list_divisi as $ls){
					$sel = "";
					if($ls == $dvv){
						$sel = "selected";
					}
					echo "<option $sel value='$ls'>$ls</option>";
				}
				?>
			</select>
		</div>
        <div class="col-sm-4">
            <label for="produk">Produk</label>
			<select name="produk" id="produk" class="form-control" required>
				<option value="">- Pilih Produk -</option>
				<option value="paladin" <?= (isset($_GET['produk'])) ? ($_GET['produk'] == 'paladin') ? 'selected' : '' : '' ?>>Paladin</option>
			</select>
        </div>
	</div>
	<div>
		<label for="gp_divisi">
			<input type="checkbox" value="1" name="group" id="gp_divisi" <?php 
			if(isset($_GET['group'])){
				echo "checked";
			}
			else{
				if(!isset($_GET['dari']) and !isset($_GET['sampai']))
					echo "checked";
			}
			?>>
			Gabungkan skor per divisi
		</label>
	</div>
	<div align="center" style="padding-top:1em;">
		<button class="btn btn-lg">Proses</button>
	</div>
</form>
<br><br>
<h3>
	<?= (isset($_GET['produk'])) ? 'Produk : '.strtoupper($_GET['produk']) : '' ?>
</h3>
<?php
if(isset($_GET['dari']) and isset($_GET['sampai'])){

	$dari = date("Y-m-d",strtotime($_GET['dari']));
    $sampai = date("Y-m-d",strtotime($_GET['sampai']));
    $produk = $_GET['produk'];

	if(strtotime($dari) > strtotime($sampai)){
		//gw juga bingung,, intinya dituker aja
		$temp = $dari;
		$dari = $sampai;
		$sampai = $temp;
	}

	$divisi = isset($_GET['divisi']) ? $_GET['divisi'] : "";

	
	$karyawan = $this->mdlaporan->karyawan_array();
	$score_list = $this->mdlaporan->get_score_produk($dari, $sampai, $divisi, $produk);
	$max_score = $this->mdlaporan->get_max_score($dari, $sampai, $divisi);

	if(!isset($_GET['group'])){
		echo "
		<table class='table data'>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Divisi</th>
				<th>Skor</th>
			</tr>
		</thead>
		<tbody>
		";
	}
	else{
		echo "
		<table class='table data'>
		<thead>
			<tr>
				<th>No</th>
				<th>Divisi</th>
				<th>Skor</th>
			</tr>
		</thead>
		<tbody>
		";
	}

	$no = 1;
	foreach($score_list as $row){
		if(isset($karyawan[$row['divisi']][$row['kd_sales']])){
				//kalau karyawannya ada
				$nama = $karyawan[$row['divisi']][$row['kd_sales']];

				$width = ceil(($row['total_skor'] / $max_score) * 100)."%";
				if($width > 75)
					$cl = "info";
				else if($width > 50)
					$cl = "success";
				else if($width > 25)
					$cl = "warning";
				else
					$cl = "danger";


				if(!isset($_GET['group'])){
					$url = "scoreproduk/detail/$row[divisi]/$row[kd_sales]?dari=$dari&sampai=$sampai&divisi=$divisi&produk=$produk";
					echo "
					<tr>
						<td><a href='$url' class='block full-row'></a>$no</td>
						<td><a href='$url' class='block full-row'></a>$nama ($row[kd_sales])</td>
						<td><a href='$url' class='block full-row'></a>".$this->mdlaporan->better_division($row['divisi'])."</td>
						<td>
							<a href='$url' class='block full-row'></a>
							<strong>".number_format($row['total_skor'])."</strong>
							<div class=\"progress\">
							<div class=\"progress-bar progress-bar-$cl progress-bar-striped active\" role=\"progressbar\"
							aria-valuenow=\"40\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:$width\">
							</div>
							</div>					
						</td>
					</tr>
					";

					$sum = isset($sum) ? $sum + $row['total_skor'] : $row['total_skor'];
				}
				else{
					
					//kumpulin skor per array
					$group_skor[$row['divisi']]['skor'] = isset($group_skor[$row['divisi']]['skor']) ? $group_skor[$row['divisi']]['skor'] + $row['total_skor'] : $row['total_skor'];
					
				}

				$no++;
			
		}
	
	}

	if(isset($_GET['group'])){
		$no = 1;
		$max = 0;
		if(isset($group_skor)){
    		arsort($group_skor);
    		$max = max($group_skor);

    		foreach($group_skor as $key=>$val){
    			//width management
    			$fscore = ceil(($val['skor'] / $max['skor']) * 100);
    			$w = strval($fscore)."%";
    			if($fscore > 75)
    				$cl = "info";
    			else if($fscore > 50)
    				$cl = "success";
    			else if($fscore > 25)
    				$cl = "warning";
    			else
    				$cl = "danger";
    
    			$url = "scoreproduk?dari=$_GET[dari]&sampai=$_GET[sampai]&divisi=$key&produk=$produk";
    			echo "
    			<tr>
    				<td><a href='$url' class='block full-row'></a>$no</td>
    				<td><a href='$url' class='block full-row'></a>".$this->mdlaporan->better_division($key)."</td>
    				<td>
    					<a href='$url' class='block full-row'></a>
    						<strong>".number_format($val['skor'])."</strong>
    						<div class=\"progress\">
    						  <div class=\"progress-bar progress-bar-$cl progress-bar-striped active\" role=\"progressbar\"
    						  aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:$w\">
    						  </div>
    						</div>
    					</a>
    				</td>
    			</tr>
    			";
    			$no++;
    		}
		}
		else{
		    echo "
		    <tr>
		        <td colspan=3>Data scoreboard pada tanggal tersebut belum dapat ditampilkan. Mohon pilih periode tanggal yang lain.</td>
		    </tr>
		    ";
		}
    		


	}

	echo "</tbody>";

	if(!isset($_GET['group'])){
		echo "
		<tfoot>
		<tr>
			<td></div>
			<td></div>
			<td align='right'>Total Skor : </div>
			<td><strong>".number_format($sum)."</strong></div>
		</tr>
		</tfoot>
		";
	}

	echo "
	</tbody>
	</div>
	";

}
?>
