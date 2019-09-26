<div class="row">
	
	<h3>Kirim data harian ke pusat</h3>
</div>
<br>
<div class="row">
	<form action="kirim/upload" method="post" enctype="multipart/form-data">
		<div class="col-md-6">
			<input type="file" class="form-control" id="myForm" name="file" required>
		</div>
		<input type="submit" class="btn btn-primary" value="Kirim">
	</form>
	
</div>
<hr>
<div class="row">
	<?php if($kirim){ ?>
	<h4>Data yang sudah anda kirim : </h4>
	<table class="table table-sm">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama File</th>
				<th>Pengirim</th>
				<th>Download</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 1; foreach($kirim as $row):
			?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $row->nama. '.'. $row->format ?></td>
				<td><?= $row->username ?></td>
				<td><a href="<?= base_url('kirim_pusat/'. $row->file_name) ?>" class="btn btn-sm btn-success">Download</a></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php } else {
		echo "Tidak ada data";
	} ?>
</div>
