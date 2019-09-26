<div class="row">
	<h3>Download Penjualan</h3>
</div>

<div class="row">
	<table class="table table-sm">
		<thead>
			<tr>
				<th>No</th>
				<th>Tgl</th>
				<th>Check</th>
			</tr>
		</thead>
		<tbody>
			<?php $no=1; foreach($files as $row) { ?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $row->nama ?></td>
				<td><a href="finance/check/<?= $row->nama ?>" class="btn btn-primary btn-sm">Check</a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>