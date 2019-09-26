<?php
$tgl = date('d-m-Y');
header("Content-type: application/vnd-ms-excel");

header("Content-Disposition: attachment; filename=$tgl-$title.xls");

header("Pragma: no-cache");

header("Expires: 0");
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

?>

<?php foreach($divisi as $a => $b) : ?>

<hr>
<h3>Total Penjualan <?= $a ?></h3>
<p>
    <b>Tanggal Penjualan : </b>
    <?php
	echo $this->def->indo_date($this->uri->segment(3),"half") . " s/d " . $this->def->indo_date($this->uri->segment(4),"half");
	?>
</p>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah Terjual</th>
            <th>Total Berat</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $no = 1;
        $total = 0;
        $nama_barang = "";
        $kd_merk = "";
        $list = $this->mdlaporan->total_penjualan_detail($tgl_awal, $tgl_akhir, $a);
        foreach($list as $row){
            $nm_barang = $this->db->where('kd_merk', $row['mrbr'])->get('tb_nama_barang')->row();
            if(!empty($nm_barang)){
                $nama_barang = $nm_barang->rule_name;
                $kd_merk = $nm_barang->kd_merk;
            }else{
                $nm_barang = $this->db->where('kd_barang', $row['kd_barang'])->get('tb_nama_barang')->row();
                if(!empty($nm_barang)){
                    $nama_barang = $nm_barang->rule_name;
                    $kd_merk = $nm_barang->kd_barang;
                }else{
                    $kd_merk = $row['mrbr'];
                }
            }
            echo "
            <tr>
                <td>$no</td>
                <td>".$kd_merk."</td>
                <td>$nama_barang</td>
                <td>".$row['jml']."</td>
                <td>".round($row['total'],1)." Kg</td>
            </tr>
            ";
            $no++;
            $total += $row['total'];
        }
      ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><b>Grand Total</b></td>
            <td><b><?= $total ?> Kg</b></td>
        </tr>
    </tbody>
</table>

<?php endforeach ?>