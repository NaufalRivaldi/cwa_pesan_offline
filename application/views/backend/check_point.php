<?php
$tgl1 = $this->uri->segment(4);// pendefinisian tanggal awal
$tgl2 = date('Y-m-d', strtotime('-1 days', strtotime($tgl1))); //operasi pengurangan tanggal sebanyak 1 hari

$format1 = date("d-m-Y", strtotime($tgl1));
$format2 = date("d-m-Y", strtotime($tgl2))

?>

<h4>Data Penjualan Cabang yang diimport pada tanggal <b><?= $format1 ?></b></h4>
<hr>
<div class="row">
	<div class="col-md-12">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>No.</th>
					<th>Cabang</th>
					<th>Nama File</th>
					<th>Penjualan</th>
				</tr>
			</thead>
			<tbody>
				<?php $no=1; foreach($cabang as $row) { 
					$cabang = explode("@", $row['username']);
					?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= strtoupper($cabang[0]) ?></td>
					<td><?= $row['file'] ?></td>
					<td><a href="<?= base_url('upload_cabang/'. $row['file']) ?>" class="btn btn-sm btn-success">Download</a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>