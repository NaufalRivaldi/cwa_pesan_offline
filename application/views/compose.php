<form action="home/send" class="form-horizontal new-message" method="post" enctype="multipart/form-data">
	<?=$this->def->echo_token();?>
	<div class="form-group">
		<label for="tjn" class="col-sm-2 control-label">
			Tujuan
		</label>
		<div class="col-sm-10">
			<select data-placeholder="Pilih email tujuan..." class="chosen-select" multiple="true" style="width:350px;" tabindex="4" name="tujuan[]">
				<?php
				$def = $id_admin;
				$list = $this->mail->list_email();
				if(isset($_SESSION['tujuan'])){
					$chk = $_SESSION['tujuan'];
					unset($_SESSION['tujuan']);
				}
				else
					$chk = array();
				foreach($list as $row){
					$a = in_array($row['username'], $chk);
					$b = $row['username'] === $def;

					if($a or $b){
						$sel = "selected $a $b";
					}
					else
						$sel = "";

					echo "<option value=\"$row[username]\" $sel>$row[username]</option>";
				}
				?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="subj" class="col-sm-2 control-label">
			Subject
		</label>
		<div class="col-sm-10">
			<?php
			if(isset($fwd_subject))
				$fwd_subj = $fwd_subject;
			else
				$fwd_subj = "";
			?>
			<input id="subj" type="text" name="subject" class="form-control" value="<?=$this->crud->echo_sess("subject", $fwd_subj)?>" maxlength="200">
		</div>
	</div>
	<div class="form-group">
		<label for="msg" class="col-sm-2 control-label">
			Isi Pesan
		</label>
		<div class="col-sm-10">
			<?php
			if(isset($fwd_message))
				$fwd = $fwd_message;
			else
				$fwd = "";
			?>
			<textarea name="msg" id="msg" class="ckeditor"><?=$this->crud->echo_sess("msg",$fwd)?></textarea>
			<script src="js/ckeditor/ckeditor.js"></script>
			<script>
				CKEDITOR.replace();
			</script>
		</div>
	</div>

	<div class="form-group">
		<label for="file" class="col-sm-2 control-label">
			Attachment (max 5MB)
		</label>
		<div class="col-sm-10">
			<input id="file" type="file" name="file[]" multiple="multiple" class="form-control" maxlength="200">
			
			<?php if(isset($forward)) : ?>
			<input type="hidden" name="forward_from" value="<?=$id_pesan?>">
				<?php
				$cek_attc = "SELECT * FROM tb_attachment WHERE id_inbox = ".$this->db->escape($id_pesan);
				$rcek = $this->db->query($cek_attc);
				foreach($rcek->result_array() as $dt){
					echo "<span class='label label-success'>$dt[nmfile]</span> ";
				}
				?>
			<?php endif; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-10 col-sm-push-2">
			<button type="submit" class="button btn-lg"><span class="fa fa-send"></span> Kirim</button>
		</div>
	</div>
</form>

