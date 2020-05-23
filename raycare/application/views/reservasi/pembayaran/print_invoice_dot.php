
<style type="text/css">
  
@charset "UTF-8";
.image {
  width: 236px;
  float: left;
  margin: 10px;
}

body {
  font-size: 12px;
  line-height: 1.4;
  font-family: arial;
}

p {
  margin: 0;
}

.performance-facts {
  /*border: 1px solid black;*/
  margin: 10px;
  float: left;
  width: 236px;
  padding: 0.5rem;
}
.performance-facts table {
  border-collapse: collapse;
}

.performance-facts__title {
  font-weight: bold;
  font-size: 27px;
  margin: 0 0 0.25rem 0;
}

.performance-facts__header {
  /*border-bottom: 10px solid black;*/
  padding: 0 0 0.25rem 0;
  margin: 0 0 0.5rem 0;
}
.performance-facts__header p {
  margin: 0;
}

.performance-facts__table, .performance-facts__table--small, .performance-facts__table--grid {
  width: 100%;
  /*width: 230px;*/
}
.performance-facts__table thead tr th, .performance-facts__table--small thead tr th, .performance-facts__table--grid thead tr th, .performance-facts__table thead tr td, .performance-facts__table--small thead tr td, .performance-facts__table--grid thead tr td {
  border: 0;
}
.performance-facts__table th, .performance-facts__table--small th, .performance-facts__table--grid th, .performance-facts__table td, .performance-facts__table--small td, .performance-facts__table--grid td {
  font-weight: normal;
  text-align: left;
  padding: 0.25rem 0;
  /*border-top: 1px solid black;*/
  /*white-space: nowrap;*/
}
.deskripsi th{
  white-space: normal !important;
}
.performance-facts__table td:last-child, .performance-facts__table--small td:last-child, .performance-facts__table--grid td:last-child {
  text-align: right;
}
.performance-facts__table .blank-cell, .performance-facts__table--small .blank-cell, .performance-facts__table--grid .blank-cell {
  width: 1rem;
  border-top: 0;
}
.performance-facts__table .thick-row th, .performance-facts__table--small .thick-row th, .performance-facts__table--grid .thick-row th, .performance-facts__table .thick-row td, .performance-facts__table--small .thick-row td, .performance-facts__table--grid .thick-row td {
  border-top-width: 5px;
}

.small-info {
  font-size: 10px;
}

.performance-facts__table--small {
  border-bottom: 0px solid #999;
  margin: 0 0 0.5rem 0;
}
.performance-facts__table--small thead tr {
  border-bottom: 0px solid black;
}
.performance-facts__table--small td:last-child {
  text-align: left;
}
.performance-facts__table--small th, .performance-facts__table--small td {
  border: 0;
  padding: 0;
}

.performance-facts__table--grid {
  margin: 0 0 0.5rem 0;
}
.performance-facts__table--grid td:last-child {
  text-align: left;
}
.performance-facts__table--grid td:last-child::before {
  content: "•";
  font-weight: bold;
  margin: 0 0.25rem 0 0;
}

.text-center {
  text-align: center;
}

.thick-end {
  border-bottom: 0px solid black;
  /*border-bottom: 10px solid black;*/
}

.thin-end {
  border-bottom: 0px solid black;
}
table{
  font-size: 12px;
}


</style>
<!-- onload="window.print()" -->
<body onload="window.print()">
  <?php
  $penjamin_pasien = $this->pasien_penjamin_m->get_by(array('pasien_id' => $pasien['id'], 'penjamin_id' => $penjamin_id, 'is_active' => 1), true);
  $no_penjamin = (count($penjamin_pasien) != 0)? $penjamin_pasien->no_kartu : '';

  $jenis_peserta = ($tindakan_hd['jenis_peserta'] != '' && $tindakan_hd['jenis_peserta'] != NULL)?' ('.$tindakan_hd['jenis_peserta'].')':'';


?>


<section class="performance-facts">
  <header class="performance-facts__header">
    <h1 class="performance-facts__title">KLINIK RAYCARE <i style="font-size:16px;">#<?=substr($invoice['no_invoice'],-6);?></i></h1>
     <table width="100%">
      <tr>
        <td>Tanggal</td>
        <td>: <?=date('d M Y', strtotime($invoice['created_date']))?></td>
      </tr>
      <tr>
        <td>Waktu</td>
        <td>: <?=$invoice['waktu_tindakan']?></td>
      </tr>
      <tr>
        <td>Pelayanan</td>
        <td>: Rawat Jalan</td>
      </tr>
      <tr>
        <td>No. RM</td>
        <td>: <?=$pasien['no_member']?></td>
      </tr>
      <tr>
        <td>Pasien</td>
        <td>: <?=$invoice['nama_pasien']?></td>
      </tr>
      <tr>
        <td>Penjamin</td>
        <td>: <?=$invoice['nama_penjamin']?></td>
      </tr>
      <tr>
        <td>No. Penjamin </td>
        <td>: <?=($invoice['penjamin_id'] == 1)?'-':$no_penjamin?></td>
      </tr>
</table> 

  </header>
  <table class="performance-facts__table">
    <thead>
      <tr>
        <th colspan="3" class="small-info">
          Detail Transaksi
        </th>
      </tr>
    </thead>
    <tbody id="detail_invoice_umum_new">
      <tr> 
        <th colspan="2"> <b>KETERANGAN</b> (UNIT)</th> 
        <td> <b>BIAYA</b>(IDR) </td> 
      </tr> 
      <tr class="thick-row"> 
          <td colspan="3" class="small-info"> <b>(Jumlah * Harga)</b> </td> 
      </tr> 
      <tr> <th colspan="3"> <b>Tindakan</b> </th></tr>
      <?php
      $total_invoice = 0;
      foreach ($invoice_detail_tindakan as $detail_tindakan) {
      ?>
        <tr>
          <td class="blank-cell"> </td>
          <th class="deskripsi" style="white-space: normal !important;"><?=$detail_tindakan['nama_tindakan']?> (<?=$detail_tindakan['qty']?>)</th>
          <td ><b><?=(formattanparupiah($detail_tindakan['harga'] * $detail_tindakan['qty']))?></b></td>
        </tr>
        <tr>
          <td class="blank-cell"> </td>
          <th class="deskripsi" style="white-space: normal !important;">Diskon</th>
          <td ><b><?=(formattanparupiah($detail_tindakan['diskon_nominal']))?></b></td>
        </tr>
      <?php
        $total_invoice = $total_invoice + (($detail_tindakan['harga'] * $detail_tindakan['qty']) - $detail_tindakan['diskon_nominal']);
      }
      ?>
       <tr> <th colspan="3"> <b>Obat & Alkes</b> </th></tr>
      <?php
      foreach ($invoice_detail_item as $detail_item) {
      ?>
        <tr>
          <td class="blank-cell"> </td>
          <th class="deskripsi" style="white-space: normal !important;"><?=$detail_item['nama_tindakan']?> (<?=$detail_item['qty']?>)</th>
          <td ><b><?=(formattanparupiah($detail_item['harga'] * $detail_item['qty']))?></b></td>
        </tr>
        <tr>
          <td class="blank-cell"> </td>
          <th class="deskripsi" style="white-space: normal !important;">Diskon</th>
          <td ><b><?=(formattanparupiah($detail_item['diskon_nominal']))?></b></td>
        </tr>
      <?php
        $total_invoice = $total_invoice + (($detail_item['harga'] * $detail_item['qty']) - $detail_item['diskon_nominal']);
      }
      ?>
      <tr class="thick-end"> 
      <th colspan="2"> <b></b> </th>
       <td> </td> 
       </tr>

    </tbody>
  </table>
  
  
  <table class="performance-facts__table">
    
    <tbody>
      <!-- <tr>
        <th colspan="2">Total Transaksi</th>
        
        <td id="total_invoice_umum"><b><?=formatrupiah($total_invoice)?></b></td>
      </tr> -->
<?php
  $akom = $invoice['akomodasi'];

    
  $tr_akom = '<tr>';
if($akom != 0 && $akom != NULL) {
   
    $tr_akom .= '<th colspan="2">Akomodasi</th>';
    $tr_akom .= '<td id="akomodasi_umum"><b>'.formattanparupiah($invoice['akomodasi']).'</b></td>';
    // echo "</tr>";

}
  $tr_akom .= '</tr>';

  echo $tr_akom;

?>
      <!-- <tr>
        <th colspan="2">Akomodasi</th>
        <td id='"akomodasi_umum"'><b><?=formattanparupiah($invoice['akomodasi'])?></b></td>
      </tr> -->
      <tr>
        <th colspan="2">Diskon</th>
        
        <td id="grand_total_umum"><b><?=formattanparupiah($invoice['diskon_nominal'])?></b></td>
      </tr>
      <tr>
        <th colspan="2">Total Tagihan</th>
        
        <td id="grand_total_umum"><b><?=formattanparupiah($total_invoice+$invoice['akomodasi']-$invoice['diskon_nominal'])?></b></td>
      </tr>
      <tr>
        <th colspan="3">Terbilang</th>
      </tr>  
      <tr>
      <th colspan="3" id="terbilang_umum"><b># <?=terbilang($total_invoice+$invoice['akomodasi']-$invoice['diskon_nominal'])?> Rupiah #</b></th>
      </tr>
      
      
    </tbody>
  </table>
  <?php
    $tipe_bayar = 'Tunai';
    if($pembayaran_tipe['tipe_bayar'] == 2 && $pembayaran_tipe['jenis_kartu'] == 1){
      $tipe_bayar = 'Kartu Debit -'.$pembayaran_tipe['no_kartu'];
    } if($pembayaran_tipe['tipe_bayar'] == 2 && $pembayaran_tipe['jenis_kartu'] == 2){
      $tipe_bayar = 'Kartu Kredit -'.$pembayaran_tipe['no_kartu'];;
    }
  ?>

   <table class="performance-facts__table">
    
    <tbody>
      <tr>
        <th colspan="3">PEMBAYARAN</th>
      
      </tr>
      <tr>
        <th colspan="2"><?=$tipe_bayar?> </th>
        <td id="akomodasi_umum"><b><?=formattanparupiah($pembayaran_tipe['rupiah'])?></b></td>
      </tr>
      <tr>
        <th colspan="2">Kembalian</th>
        
        <td id="grand_total_umum"><b><?=formattanparupiah($pembayaran_tipe['jumlah_bayar']-$pembayaran_tipe['rupiah'])?></b></td>
      </tr>

      
      
    </tbody>
  </table> 

<?php
  $user_id = $this->session->userdata('user_id');
  $user = $this->user_m->get_by(array('id' => $user_id), true);

  $cabang_id = $this->session->userdata('cabang_id');

  $alamat_cabang = $this->cabang_alamat_m->get_by(array('cabang_id' => $cabang_id, 'is_primary' => 1, 'is_active' => 1), true);
 $telepon_cabang = $this->cabang_telepon_m->get_by(array('cabang_id' => $cabang_id, 'is_primary' => 1, 'is_active' => 1), true);

?>
<table width="100%">
      <tr>
        <td>WAKTU CETAK</td>
        <td>: <?=date('d M Y/H:i')?> </td>
      </tr>
      <tr>
        <td>DICETAK OLEH</td>
        <td>: <?=$user->nama?></td>
      </tr>
</table> 
<p><b>Note :</b></p>
<ul style="margin-left: -25px;margin-top: 0px;"> 
<li>Harga Diatas Sudah Termasuk PPN</li>
<li>Harap Menghubungi Kami, Jika Ada Ketidaksesuaian Obat Yang Diterima</li>
</ul>

<h4 style="text-align:center;">Terima Kasih Atas Kunjungan Anda</h4>
<p style="text-align:center;"><b>PT. RAYCARE HEALTH SOLUTION</b><br>
<?=$alamat_cabang->alamat?><br>
Telp : <?=$telepon_cabang->nomor?>, Website : www.klinikraycare.com</p>
</section>


</body>

