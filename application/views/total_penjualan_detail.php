<h3>Total Penjualan <?= $divisi ?></h3>
<hr>

<p>
    <a href="<?= site_url('total_penjualan?tgl_awal='.$this->uri->segment(3).'&tgl_akhir='.$this->uri->segment(4).'&divisi=') ?>" class="btn btn-primary">< Kembali</a> | 
   <a href="<?= site_url('total_penjualan/export/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5)) ?>" class="btn btn-primary">Export Excel</a>
</p>

<p>
    <b>Tanggal Penjualan : </b>
    <span class="label label-success"><?php
	echo $this->def->indo_date($this->uri->segment(3),"half") . " s/d " . $this->def->indo_date($this->uri->segment(4),"half");
	?></span>
</p>

	<td class="col-md-12">
		<div class="table-data">
      <div class="trh">
        <div class="th">No</div>
        <div class="th">Nama Barang</div>
        <div class="th">Jumlah Terjual</div>
        <div class="th">Total Berat</div>
      </div>	
      <?php
        $no = 1;
        $total = 0;
        $nama_barang = "";
        foreach($list as $row){
            $nm_barang = $this->db->where('kd_merk', $row['mrbr'])->get('tb_nama_barang')->row();
            if(!empty($nm_barang)){
                $nama_barang = $nm_barang->rule_name;
            }else{
                $nm_barang = $this->db->where('kd_barang', $row['kd_barang'])->get('tb_nama_barang')->row();
                if(!empty($nm_barang)){
                    $nama_barang = $nm_barang->rule_name;
                }
            }
            echo "
            <div class ='tr'>
                <div class='td'>$no</div>
                <div class='td'>$nama_barang</div>
                <div class='td'>".$row['jml']."</div>
                <div class='td'>".$row['total']." Kg</div>
            </div>
            ";
            $no++;
            $total += $row['total'];
        }
      ?>
        <div class ='tr'>
            <div class='td'></div>
            <div class='td'></div>
            <div class='td'><b>Grand Total</b></div>
            <div class='td'><b><?= $total ?> Kg</b></div>
        </div>
    </div>
	</div>
</div>