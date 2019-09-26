<?php 
if(isset($outbox)){
?>
<a href="outbox" class="btn btn-default"><span class="fa fa-caret-left"></span> Kembali ke Pesan Keluar</a>
<?php
}
elseif(isset($trash)){
?>
<a href="trash" class="btn btn-default"><span class="fa fa-caret-left"></span> Kembali ke Tempat Sampah</a>
<?php
}
else{
?>
<a href="home" class="btn btn-default"><span class="fa fa-caret-left"></span> Kembali ke Pesan Masuk</a>
<?php
}
?>

<br>
<br>
<div class="box">
	<div class="row">
		<div class="col-sm-6">
			<table align="left" cellpadding=2>
				<tr>
					<td><strong>From</strong></td>
					<td> : </td>
					<td><a href="home/compose/?to=<?=$row['username']?>" class="label label-primary"><?=$row['username']?></a></td>
				</tr>
				<tr>
					<td><strong>To</strong></td>
					<td> : </td>
					<td>
					<?php
					$list = $this->mail->get_receiver($row['id']);
					$nlist = 0;
					foreach($list as $r){
						$nlist++;
						echo "<a href='home/compose/?to=$r[username]' class='label label-default'>$r[username]</a> ";
					}
					?>
					</td>
				</tr>
				<tr>
					<td><strong>Subject</strong></td>
					<td> : </td>
					<td><?=$row['subject']?></td>
				</tr>
			</table>
		</div>
		<div class="col-sm-6" align="right">
			<?=$this->def->indo_date($row['tgl'])?>
		</div>
	</div>
	<div class="message">
		<?php
		//cek lampiran
		if($attachment){
		?>
		<div class="files-holder">
			<strong>Lampiran : </strong>
			<div>
			<?php
			foreach($attachment as $file){
				echo "<a href='upload/$file[location]' class='label label-success' title='Klik untuk mendownload $file[nmfile]' download='$file[nmfile]'>$file[nmfile]</a> ";
			}
			?>
			</div>
		</div>
		<?php
		}
		echo "<div class='isi'>".$row['message']."</div>";
		?>
	</div>

<?php 
if(!isset($trash)){
?>
	<div class="tbar">
		<a href="home/forward/<?=$id?>" class="btn btn-info"><span class="fa fa-share"></span> Forward</a>

		<a href="home/delete/<?=$id?>" class="btn btn-danger"><span class="fa fa-trash"></span> Hapus Pesan</a>
	</div>
<?php
}
else{
?>
	<div class="tbar">
		<a href="trash/restore/<?=$id?>" class="btn btn-success"><span class="fa fa-refresh"></span> Kembalikan ke tempat semula</a>
		<a href="trash/delete/<?=$id?>" class="btn btn-danger"><span class="fa fa-trash"></span> Hapus Permanen</a>
	</div>
<?php
}


if(isset($outbox)){
	//pembuatan laporan
	$total = $nlist;
	$read = $this->mail->read_control($row['id'],1);
	$del1 = $this->mail->read_control($row['id'],8);
	$del2 = $this->mail->read_control($row['id'],9);
	$delete = $del1 + $del2;
	$notread = $total - ($read + $del1 + $del2);

	echo "
	<span>
		<b>Report</b>
		<br>
		Total penerima : $total. <span class='label label-success'>Sudah dibaca : $read</span> <span class='label label-danger'>Dihapus : $delete</span> <span class='label label-warning'>Belum dibaca : $notread</span>
	</span>
	";
}


if(isset($inbox)){
?>	
	<button class="quick-reply-toggle" class="button btn-sm">Balas Pesan Ini (Quick Reply)</button>
	


	<?php
	$rc = "";
	$receiver = $this->mail->get_receiver($row['id']);
	foreach($receiver as $rec){
		$rc .= $rec['username'].", ";
	}
	$rc = substr($rc, 0, -2);

	$subj = "Re : $row[subject]";
	$isi = "";
	// $isi = "<br><blockquote>
	// <b>---Reply to $row[username]---</b>
	// <br>
	// Tanggal : ".$this->def->indo_date($row['tgl'])."
	// <br>
	// Untuk : $rc
	// <br>
	// <br>
	// $row[message]
	// </blockquote>";
	?>
	<form action="home/send" method="post" class="quick-reply form-horizontal" enctype="multipart/form-data">
		<?=$this->def->echo_token();?>
		<?php echo "<input type='hidden' name='reply_to' value='$id'>";?>
		<div class="form-group">
			<label for="tjn" class="col-sm-2 control-label">
				Tujuan
			</label>
			<div class="col-sm-10">
				<select data-placeholder="Pilih email tujuan..." class="chosen-select" multiple style="width:350px;" tabindex="4" name="tujuan">
				<?php
				$def = $row['username'];
				$list = $this->mail->list_email();
				if(isset($_SESSION['tujuan'])){
					$chk = $_SESSION['tujuan'];
					unset($_SESSION['tujuan']);
				}
				else
					$chk = array();

				foreach($list as $r){
					if(($row['username'] == $r['username']) or (in_array($r['username'], $chk)))
						$addq = " selected ";
					else
						$addq = "";
					echo "<option value=\"$r[username]\" $addq>$r[username]</option>";
				}
				?>
			</select>
			</div>
		</div>
		<div class="form-group">
			<label for="subj" class="col-sm-2 control-label">
				Subject
			</label>
			<div class="col-sm-10">
				<input id="subj" type="text" name="subject" class="form-control" value="<?=$this->crud->echo_sess('subject', $subj)?>" maxlength="200">
			</div>
		</div>
		<div class="form-group">
			<label for="msg" class="col-sm-2 control-label">
				Isi Pesan
			</label>
			<div class="col-sm-10">
				<textarea name="msg" id="msg" class="ckeditor"><?=$this->crud->echo_sess("msg",$isi)?></textarea>
				<script src="js/ckeditor/ckeditor.js"></script>
				<script>
					CKEDITOR.replace();
				</script>
			</div>
		</div>

		<div class="form-group">
			<label for="file" class="col-sm-2 control-label">
				Attachment
			</label>
			<div class="col-sm-10">
				<input id="file" type="file" name="file[]" multiple="multiple" class="form-control" maxlength="200">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10 col-sm-push-2">
				<button type="submit" class="button btn-lg"><span class="fa fa-send"></span> Kirim</button>
			</div>
		</div>
	</form>
<?php
}
?>
</div>