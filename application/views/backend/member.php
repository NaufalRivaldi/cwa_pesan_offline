<h3>Update Member</h3>
<?php if($last_update->last_updated == null) { 
	$last_update = '0000-00-00';
}
	?>
<p>Last Update : <?= $last_update->last_updated ?> </p>
<?php 
$update = explode(' ', $last_update->last_updated);
if($update[0] != date('Y-m-d')){
	echo "<p style='color:red'><b>Anda belum mengupdate member hari ini, harap melakukan update sekarang juga</b> </p>";
}

 ?>



<form action="<?= base_url('backend/member/importMember') ?>" method="post" enctype="multipart/form-data">
	<input type="file" class="form-control" required name="file_member"> 
	<br>
	<input type="submit" class="btn btn-primary">
</form>
<hr>
<br>
<table class="table table-sm table-striped" id="myTable">
	<thead>
		<tr>
			<th>No</th>
			<th>No Member</th>
			<th>Nama</th>
			<th>Registrasi</th>
			<th>Point</th>
			<th>Details</th>
		</tr>
	</thead>
	<tbody>
		<?php $no =1;
		foreach($member as $rows) : 
			if(strlen($rows->kdmember) == 5){
				$nomb = '000'.$rows->kdmember;

			} else {
				$nomb = '00'.$rows->kdmember;
			}
		?>
		<tr>
			<td><?= $no++ ?></td>
			<td><?= $nomb ?></td>
			<td><?= $rows->nm_member ?></td>
			<td><?= $rows->lokasi_daftar ?></td>
			<td><?= $rows->point ?></td>
			<td><a href="<?= base_url('backend/member?kd_member='.$rows->kdmember) ?>" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a></td>
		</tr>
		<?php endforeach ?>
	</tbody>
	
</table>
<br><br>
<!-- Modal -->

<?php 
if(isset($_GET['kd_member'])){
	$kode = $_GET['kd_member'];
	$data = $this->db->where('kdmember', $kode)->get('member')->row();
}

 ?>
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		    <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Data Member </h5>
		        		        	        
		    </div>
		    <div class="modal-body">
		        <table class="table table-striped">
		        	<tr>
		        		<td>No Member </td>
		        		<td>: </td>
		        		<td><?= $data->kdmember ?></td>
		        	</tr>
		        	<tr>
		        		<td>Nama </td>
		        		<td>: </td>
		        		<td><?= $data->nm_member ?></td>
		        	</tr>
		        	<tr>
		        		<td>Alamat </td>
		        		<td>: </td>
		        		<td><?= $data->almt_member ?></td>
		        	</tr>
		        	<tr>
		        		<td>Telp </td>
		        		<td>: </td>
		        		<td><?= $data->telp ?></td>
		        	</tr>
		        	<tr>
		        		<td>Kitas </td>
		        		<td>: </td>
		        		<td><?= $data->no_kitas ?></td>
		        	</tr>
		        	<tr>
		        		<td>Lokasi Daftar </td>
		        		<td>: </td>
		        		<td><?= $data->lokasi_daftar ?></td>
		        	</tr>
		        	<tr>
		        		<td>Tgl Daftar </td>
		        		<td>: </td>
		        		<td><?= $data->tgl_daftar ?></td>
		        	</tr>
		        </table>
		    </div>
		    <div class="modal-footer">
		    	<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
		    </div>
		    
	    </div>
  	</div>
</div>
