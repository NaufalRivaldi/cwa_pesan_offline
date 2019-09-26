
<!-- <div class="row">
	<div class="jumbotron">
		<div class="row">
			<div class="col-md-12">
				<p>Update Poin Member</p>
			</div>
		</div>
		<form action="backend/point/generate" method="post">
			<div class="row">
				<div class="col-md-6">
					<input type="text" name="start_date" class="form-control" id="datepicker" placeholder="Start Date" required autocomplete="off">
				</div>
				<div class="col-md-6">
					<input type="text" name="end_date" class="form-control" id="datepickers" placeholder="End Date" required autocomplete="off">
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<button type="submit" class="btn btn-block btn-primary">Generate!</button>
				</div>
			</div>
		</form>
	</div>
</div> -->


<div class="row">
	<h3>Penjualan Member Cabang</h3>
	<div class="col-md-12">
		<table class="table table-striped">
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
					$tglIndo = date("d-m-Y", strtotime($row['tgl']))
					?>
					<tr>
						<td><?=$no++?></td>
						<td><?= $tglIndo ?></td>
						<td><a href="<?= base_url('backend/point/check/'. $row['tgl']) ?>" class="btn btn-sm btn-primary">Check</a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<br><br>