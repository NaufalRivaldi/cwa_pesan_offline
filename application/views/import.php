<h3>Import Penjualan pada tanggal kemarin (<?= date('d-m-Y'); ?>)</h3>
<hr>
<div class="row">
	<form action="import/store" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<input type="hidden" value="<?= $this->def->get_current('username') ?>" name="username">
			<input type="hidden" value="<?= date('Y-m-d'); ?>" name="tgl">
			<label for="" class="col-sm-12 control-label">
				File
			</label>
			<div class="col-sm-6">
				<input type="file" name="attach" class="form-control">
			</div>
			<div class="col-md-12">
				<br>
				<input type="submit" class="btn btn-primary" value="Simpan" >
			</div>
		</div>
		
	</form>
</div>


<div class="row">
	<div class="col-md-10">
		<br>
		<?= $keterangan ?>
	</div>
	
</div>