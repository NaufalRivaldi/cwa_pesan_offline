<h3>Download Penjualan Cabang</h3>
<hr>
<div class="row">
	<div class="col-md-12">
		<table class="table table-sm">
			<thead>
				<tr>
					<th>No</th>
					<th>Tanggal</th>
					<th>Check</th>
				</tr>
			</thead>
			<tbody>
				<?php $no=1; foreach($file as $row) { 
					$dateYesterday = date('Y-m-d', strtotime('-1 day', strtotime($row['tgl'])));
					$tglIndo = date("d-m-Y", strtotime($dateYesterday))
					?>
					<tr>
						<td><?=$no++?></td>
						<td><?= $dateYesterday ?></td>
						<td><a href="<?= base_url('download/list/'. $row['tgl']) ?>" class="btn btn-sm btn-primary">Check</a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>