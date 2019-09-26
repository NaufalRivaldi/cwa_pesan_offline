
	<h2>Manage User</h2>
	<a class="btn btn-primary toggle_trigger">
		<span class="fa fa-plus"></span>
		Tambah User
	</a>
	
	<div class="toggle_target">
	<form action="backend/action/add/user" class="add form-horizontal col-md-6" method="post">
		<fieldset>
			<legend>Tambah User Baru</legend>
			<div class="form-group">
				<label for="usrnm" class="col-md-2 control-label">
					Username
				</label>
				<div class="col-md-10">
					<input type="text" class="form-control" id="usrnm" name="username">
				</div>
			</div>
			<div class="form-group">
				<label for="nmm" class="col-md-2 control-label">
					Nama
				</label>
				<div class="col-md-10">
					<input type="text" class="form-control" id="nmm" name="nama">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-10 col-md-offset-2">
					<button type="submit" class="btn btn-primary" name="btn-send">
						Tambah User
					</button>
				</div>
			</div>


		</fieldset>
	</form>
	</div>



	<table class="table data table-striped">
		<thead>
			<tr>
				<th>Username</th>
				<th>Name</th>
				<th>Stat</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$sql = $this->db->query("SELECT * FROM tb_admin");
		foreach($sql->result_array() as $row){
			if($row['stat'] == 1){
				$action = "<a href='backend/action/hide?email=$row[username]' class='btn btn-danger delete-button'>Hapus User</a>";
				$stat = "<span class='label label-success'>Aktif</span>";
				$trclass = '';
			}
			else{
				$action = "<a href='backend/action/show?email=$row[username]' class='btn btn-success'>Kembalikan User</a>";
				$stat = "<span class='label label-danger'>Nonaktif</span>";
				$trclass = 'trhide';
			}
			$exp = explode("@", $row['username']);
			echo "
			<tr class='$trclass'>
				<td>$row[username]</td>
				<td>$row[name]</td>
				<td>$stat</td>
				<td>
					<a href='backend/home/edit/$exp[0]' class='btn btn-primary'>Edit User</a>
					$action
					<a href='backend/action/reset?email=$row[username]' class='btn btn-primary'>Reset Password</a>
				</td>
			</tr>
			";
		}
		?>
		</tbody>
	</table>
