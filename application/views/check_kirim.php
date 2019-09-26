<div class="row">
	<h3>Download <?= $this->uri->segment(3) ?></h3>
</div>

<div class="row">
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
			<?php $no=1; foreach($data as $row) { ?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $row->file_name ?></td>
				<td><?= $row->username ?></td>
				<td><a href="kirim_pusat/<?= $row->file_name ?>" class="btn btn-success btn-sm">Download</a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>