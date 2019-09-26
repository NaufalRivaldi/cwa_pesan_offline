<!doctype html>
<html lang="en">
<head>
	<base href="<?=base_url()?>">
	<meta charset="UTF-8">
	<title>CWA Mail <?php if(isset($title)){echo "- $title";}?></title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/simpleFilePreview.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/chosen.min.css">
	<link rel="stylesheet" href="js/DataTable/buttons.dataTables.min.css">
	<link rel="stylesheet" href="js/DataTable/jquery.dataTables.min.css">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="icon" href="img/icon.jpg">
	<!-- datepicker -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>


<header class="side-header">
	<div class="logo">
		<a href=""><img src="img/logo.jpg"></a>
		<h1>CWA Mail</h1>
	</div>
	
	<div style="padding:1em; text-align:center;">
		<a href="home/compose" class="btn btn-success btn-lg">
			<span class="fa fa-envelope"></span> Buat Pesan Baru
		</a>
	</div>

	<nav>
		<ul>
			<li class="<?=$this->def->compare_output($menu,1,"active")?>">
				<a href="home">
				<span class="fa fa-fw fa-inbox"></span>
				<span class="label">Pesan Masuk</span>
				</a>
			</li>
			<li class="<?=$this->def->compare_output($menu,2,"active")?>">
				<a href="outbox">
				<span class="fa fa-fw fa-sign-out"></span>
				<span class="label">Pesan Keluar</span>
				</a>
			</li>
			<li class="<?=$this->def->compare_output($menu,5,"active")?>">
				<a href="scoreboard">
				<span class="fa fa-fw fa-star"></span>
				<span class="label">Scoreboard Penjualan</span>
<!--
				<span class="new">New</span>
-->
				</a>
			</li>
			
			<?php if($this->def->is_spt($this->def->get_current('username'))): ?>
			<li class="<?=$this->def->compare_output($menu,11,"active")?>">
				<a href="scoreproduk">
				<span class="fa fa-fw fa-star"></span>
				<span class="label">Score Produk</span>
<!--
				<span class="new">New</span>
-->
				</a>
			</li>
			<?php endif ?>

			<?php if($this->def->is_PU($this->def->get_current('username'))): ?>
			<li class="<?=$this->def->compare_output($menu,11,"active")?>">
				<a href="total_penjualan">
				<span class="fa fa-fw fa-star"></span>
				<span class="label">Total Penjualan</span>
<!--
				<span class="new">New</span>
-->
				</a>
			</li>
			<?php endif ?>

			<li class="<?=$this->def->compare_output($menu,3,"active")?>">
				<a href="trash">
				<span class="fa fa-fw fa-trash"></span>
				<span class="label">Tempat Sampah</span>
				</a>
			</li>

			<li class="<?=$this->def->compare_output($menu,4,"active")?>">
				<a href="setting">
					<span class="fa fa-fw fa-cog"></span>
					<span class="label">Ubah Password</span>
				</a>
			</li>
			<?php if($this->def->cabang_only($this->def->get_current('username'))): ?>
			<li class="<?=$this->def->compare_output($menu,6,"active")?>">
				<a href="import">
					<span class="fa fa-fw fa-file"></span>
					<span class="label">Penjualan Member</span>
				</a>
			</li>

			<li class="<?=$this->def->compare_output($menu,7,"active")?>">
				<a href="kirim">
					<span class="fa fa-fw fa-database"></span>
					<span class="label">Kirim Data ke Pusat</span>
				</a>
			</li>
			<?php endif ?>
			
			<?php if($this->def->is_finance($this->def->get_current('username'))): ?>
			<li class="<?=$this->def->compare_output($menu,8,"active")?>">
				<a href="finance">
					<span class="fa fa-fw fa-info"></span>
					<span class="label">Menu Finance</span>
				</a>
			</li>
			<?php endif ?>

			<?php if($this->def->is_master($this->def->get_current('username'))): ?>
			<li class="<?=$this->def->compare_output($menu,10,"active")?>">
				<a href="update_master">
					<span class="fa fa-fw fa-cloud-download"></span>
					<span class="label">Update Master</span>
				</a>
			</li>
			<?php endif ?>

		</ul>
	</nav>

	<div class="copyright">
		&copy; 2018;
		Tian X Naufal
	</div>
</header>
	<?php 
	

	 ?>
<main class="side-main">
	<div class="head-info">
		<div class="alleft">
			 <i class="birthday"><?php $this->def->ultah() ?></i>
		</div>
		<div class="alright">
			Hai, <?=$this->def->get_current("name")?>..
			<a href="logout" class="btn btn-danger">
				<span class="fa fa-close"></span>
				Log Out
			</a>
			
		</div>

	</div>
	<div class="container-fluid" style="margin-top:8%;">
	<?=$this->def->msghandling()?>
	<?php $this->def->page_record();?>
	<?php
		if($this->def->first_login()){
			if(!isset($_SESSION['sudah_muncul']))
				$this->def->show_changepass();
		}

		if(isset($news)){
			$this->def->echo_news($news);
		}

			
	?>

	
	