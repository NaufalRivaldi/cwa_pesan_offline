<?php
$tgl = date('d-m-Y');
header("Content-type: application/vnd-ms-excel");

header("Content-Disposition: attachment; filename=$tgl-$title-$divisi.xls");

header("Pragma: no-cache");

header("Expires: 0");

?>

<h3>Total Penjualan <?= $divisi ?></h3>
<hr>
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
            <tr>
                <td>$no</td>
                <td>$nama_barang</td>
                <td>".$row['jml']."</td>
                <td>".$row['total']." Kg</td>
            </tr>
            ";
            $no++;
            $total += $row['total'];
        }

        foreach($list2 as $row){
            if(empty($row['mrbr'])){
                echo "
                <tr>
                    <td>$no</td>
                    <td>$row[kd_barang]</td>
                    <td>".$row['jml']."</td>
                    <td>".$row['total']." Kg</td>
                </tr>
                ";
                $no++;
                $total += $row['total'];
            }
        }
      ?>
        <tr>
            <td></td>
            <td></td>
            <td><b>Grand Total</b></td>
            <td><b><?= $total ?> Kg</b></td>
        </tr>
    </tbody>
</table>