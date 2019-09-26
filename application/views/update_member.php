<h3>Update Data Member</h3>
<hr>
<p>Last update : <b> <?= date('d-m-Y', strtotime($last_update->tgl)) ?> </b></p>

<?php if($this->def->get_current('username') == "it@cwabali.com"): ?>
<div class="row">
	<div class="col-md-6">
		<form action="update_member/store" method="post" enctype="multipart/form-data">
			<br>
			<input type="file" name="file1" class="form-control" required>
			<input type="file" name="file2" class="form-control" required>
			<br>
			<input type="submit" class="btn btn-success">
		</form>
	</div>
</div>
<?php endif ?>

<div class="row">
	<div class="col-md-12">
		<table class="table table-sm">
			<thead>
				<tr>
					<th>No</th>
					<th>File</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php $no=1; foreach($file as $row) : ?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= $row->file_name ?></td>
					<td><a href="<?= base_url('upload_dbf/'.$row->file_name) ?>" class="btn btn-primary btn-sm">Download</a></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>