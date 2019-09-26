<h2>Upload Scoreboard Penjualan</h2>

<p>
	Last Update : <strong><?=$this->def->indo_date($this->def->get_setting("last_update"))?></strong>
</p>
<form action="backend/scoreboard/process" method="post" enctype="multipart/form-data">
	<input type="file" name="file" accept=".cwa" class="form-control">
	<button class="btn btn-primary" name="btn">Proses</button>
</form>

