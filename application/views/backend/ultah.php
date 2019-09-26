
<h2>Data Karyawan Ultah Bulan <?= date('m') ?></h2>
<a class="btn btn-primary toggle_trigger" href="<?= base_url('backend/ultah/create') ?>">
	<span class="fa fa-plus"></span>
	Update Ultah
</a>
<br><br>
<table class="table data table-striped">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Lengkap</th>
				<th>TTL</th>
				<th>Devisi</th>
			</tr>
		</thead>
		<tbody>
			<?php $no=1; foreach($karyawan as $row) {?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= $row['nama_lengkap'] ?></td>
					<td><?= $row['ttl'] ?></td>
					<td><?= ucwords(strtolower($row['devisi'])) ?></td>
					<td>
						<a href="<?= base_url('backend/karyawan/edit/'. $row['id']) ?>" class="btn btn-primary btn-sm"><i class="fa fa-cog"></i></a>
						<a href="<?= base_url('backend/karyawan/delete/'. $row['id']) ?>" class="btn btn-danger delete-button btn-sm" onclick="return confirm('Yakin akan menghapus data? Data yang sudah terhapus tidak dapat dikembalikan')"><i class="fa fa-trash"></i></a>	
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>