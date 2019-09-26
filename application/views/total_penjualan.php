<h3>Total Penjualan Harian</h3>
<hr>
<p>Last update : <b> <?= date('d-m-Y', strtotime($last_update->tgl)) ?> </b></p>

<form class="form-inline" method="GET" action="<?= site_url('total_penjualan') ?>">
  <div class="form-group">
    <label for="exampleInputName2">Dari : </label>
    <input type="date" class="form-control" name="tgl_awal" value="<?= (!empty($_GET['tgl_awal'])) ? $_GET['tgl_awal'] : $last_update->tgl ?>">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail2">Sampai :</label>
    <input type="date" class="form-control" name="tgl_akhir" value="<?= (!empty($_GET['tgl_akhir'])) ? $_GET['tgl_akhir'] : $last_update->tgl ?>">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail2">Cabang :</label>
    <select name="divisi" id="cabang" class="form-control">
        <option value="">Seluruh Cabang</option>
        <?php foreach($divisi as $dv => $nama): ?>
        <option value="<?= $dv ?>"><?= $nama ?></option>
        <?php endforeach ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Cari</button>
</form>
  <br>
  <?php if(!empty($_GET['tgl_awal'])){ ?>
    <a href="<?= site_url('total_penjualan/export_all/'.$_GET['tgl_awal'].'/'.$_GET['tgl_akhir'].'/'.$_GET['divisi']) ?>" class="btn btn-primary">Export Excel</a>  
  <?php } ?>

	<td class="col-md-12">
		<div class="table-data">
      <div class="trh">
        <div class="th">No</div>
        <div class="th">Nama Cabang</div>
        <div class="th">Jumlah Berat</div>
        <div class="th">Action</div>
      </div>	
      <?php
        $total = 0;
        if(!empty($_GET['tgl_awal'])){
          $list = $this->mdlaporan->total_penjualan($_GET['tgl_awal'], $_GET['tgl_akhir'], $_GET['divisi']);
          $no = 1;
          $nama_cabang = "";
          foreach($list as $row){
            foreach($divisi as $dv => $nama){
              if($row['divisi'] == $dv){
                $nama_cabang = $nama;
              }
            }

            $total += $row['ttl_jml'];
            echo "
            <div class ='tr'>
              <div class='td'>$no</div>
              <div class='td'>$nama_cabang</div>
              <div class='td'>".round($row['ttl_jml'], 1)." Kg</div>
              <div class='td'>
                <a href='".site_url('total_penjualan/detail/'.$_GET['tgl_awal'].'/'.$_GET['tgl_akhir'].'/'.$row['divisi'])."' class='btn btn-success'>Lihat</a>
              </div>
            </div>
            ";
            $no++;
          }

          echo "
          <div class ='tr'>
              <div class='td'></div>
              <div class='td'><b>Grand Total</b></div>
              <div class='td'><b>".round($total,1)." Kg</b></div>
              <div class='td'></div>
          </div>
          ";
        }
      ?>
      
    </div>
	</div>
</div>