<?php
$url = "";
if(isset($_GET['dari']))
	$url .= "dari=".$_GET['dari']."&";
if(isset($_GET['sampai']))
	$url .= "sampai=".$_GET['sampai']."&";
if(isset($_GET['divisi']))
    $url .= "divisi=".$_GET['divisi']."&";
if(isset($_GET['produk']))
	$url .= "produk=".$_GET['produk']."&";
?>
<hr style="margin-top: 10%">
<div class="header-karyawan">
    <a href="scoreproduk?<?=$url?>" class="btn btn-primary"><span class="fa fa-caret-left"></span> Kembali</a>

    <div class="row">
        <div class="col-md-4 col-lg-3 col-sm-6">Nama Karyawan</div>
        <div class="col-md-8 col-lg-9 col-sm-6"><strong><?=$this->mdlaporan->get_karyawans($divisi, $id)?></strong></div>
    </div>
    <div class="row">
        <div class="col-md-4 col-lg-3 col-sm-6">Divisi</div>
        <div class="col-md-8 col-lg-9 col-sm-6"><strong><?=$this->mdlaporan->better_division($divisi)?></strong></div>
    </div>
    <div class="row">
        <div class="col-md-4 col-lg-3 col-sm-6">Periode</div>
        <div class="col-md-8 col-lg-9 col-sm-6"><span class="label label-success"><?php
        echo $this->def->indo_date($_GET['dari'],"half") . " s/d " . $this->def->indo_date($_GET['sampai'],"half");
        ?></span></div>
    </div>
</div>

<div class="table-data">
	<div class="trh">
		<div class="th">No</div>
		<div class="th">Tanggal</div>
		<div class="th">Nama Barang</div>
		<div class="th">Jumlah</div>
		<div class="th">Skor</div>
	</div>
	<?php
    $produk = '';
    if(isset($_GET['produk'])){
        $produk = $_GET['produk'];
    }
    $list = $this->mdlaporan->detail_score($id, $divisi, $_GET['dari'], $_GET['sampai'], $produk);
	$no = 1;
    $final_score = 0;
    $total = 0;
	foreach($list as $row){
		$nm_ = $this->mdlaporan->better_code($row['kd_barang']);
		$tgl = $this->def->indo_date($row['tgl'],"half");
		echo "
		<div class='tr'>
			<div class='td'>$no</div>
			<div class='td'>$tgl</div>
			<div class='td'>$nm_</div>
			<div class='td'>$row[jml]</div>
			<div class='td' align='right'>$row[skor]</div>
		</div>
		";
		$no++;
        $final_score += $row['skor'];
        $total += $row['jml'];
	}
	?>	
	<div class="tr">
		<div class="td"></div>
		<div class="td"></div>
        <div class="td" align="right"><strong>Total: </strong></div>
        <div class="td" align="left"><strong><?= $total ?></strong></div>
		<div class="td" align="right">
			<strong><?=$final_score?></strong>
		</div>
	</div>
</div>

<a href="scoreproduk?<?=$url?>" class="btn btn-primary"><span class="fa fa-caret-left"></span> Kembali</a>