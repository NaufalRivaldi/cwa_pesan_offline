<h2>Ubah Password Akun</h2>
<br>
<form action="setting/save" method="post" class="form-horizontal">
	<?=$this->def->echo_token();?>
	<div class="form-group">
		<label for="old_pass" class="col-sm-2 control-label">
			Password Lama
		</label>
		<div class="col-sm-10">
			<input type="password" name="old_pass" class="form-control" id="old_pass" value="<?php if(isset($firsttime)){echo "123456";}?>">
		</div>
	</div>
	<div class="form-group">
		<label for="newpass" class="col-sm-2 control-label">
			Password Baru
		</label>
		<div class="col-sm-10">
			<input type="password" name="new_pass" class="form-control" id="newpass">			
		</div>
	</div>
	<div class="form-group">
		<label for="newpass2" class="col-sm-2 control-label">
			Password Baru (Lagi)
		</label>
		<div class="col-sm-10">
			<input type="password" name="new_pass2" class="form-control" id="newpass2">			
		</div>
	</div>
	<div class="col-sm-10 col-sm-push-2">
		<button>
			<span class="fa fa-save"></span> Simpan
		</button>
	</div>
</form>