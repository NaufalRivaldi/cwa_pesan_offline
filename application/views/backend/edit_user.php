<form action="backend/action/update/<?= $this->def->character($user->username) ?>" class="add form-horizontal col-md-6" method="post">
	<fieldset>
		<legend>Edit User</legend>
		<div class="form-group">
			<label for="usrnm" class="col-md-2 control-label">
				Name
			</label>
			<div class="col-md-10">
				<input type="text" class="form-control" id="usrnm" name="username" value="<?= $user->name ?>">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-10 col-md-offset-2">
				<button type="submit" class="btn btn-primary" name="btn-send">
					Update User
				</button>
			</div>
		</div>


	</fieldset>
</form>