
<form action="<?= base_url('backend/ultah/store') ?>" enctype="multipart/form-data" method="post">
	<div class="row">
		<div class="col-md-6"><p>Masukan File Ultah</p></div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<input type="file" class="form-control" name="ultah">
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6">
			<input type="submit" class="btn btn-primary">
		</div>
	</div>
</form>