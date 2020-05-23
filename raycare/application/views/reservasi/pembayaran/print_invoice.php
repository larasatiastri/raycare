
<style type="text/css">
	
@charset "UTF-8";
.image {
  width: 250px;
  float: left;
  margin: 20px;
}

body {
  font-size: small;
  line-height: 1.4;
}

p {
  margin: 0;
}

.performance-facts {
  border: 1px solid black;
  margin: 20px;
  float: left;
  width: 300px;
  padding: 0.5rem;
}
.performance-facts table {
  border-collapse: collapse;
}

.performance-facts__title {
  font-weight: bold;
  font-size: 2rem;
  margin: 0 0 0.25rem 0;
}

.performance-facts__header {
  border-bottom: 10px solid black;
  padding: 0 0 0.25rem 0;
  margin: 0 0 0.5rem 0;
}
.performance-facts__header p {
  margin: 0;
}

.performance-facts__table, .performance-facts__table--small, .performance-facts__table--grid {
  width: 100%;
}
.performance-facts__table thead tr th, .performance-facts__table--small thead tr th, .performance-facts__table--grid thead tr th, .performance-facts__table thead tr td, .performance-facts__table--small thead tr td, .performance-facts__table--grid thead tr td {
  border: 0;
}
.performance-facts__table th, .performance-facts__table--small th, .performance-facts__table--grid th, .performance-facts__table td, .performance-facts__table--small td, .performance-facts__table--grid td {
  font-weight: normal;
  text-align: left;
  padding: 0.25rem 0;
  border-top: 1px solid black;
  white-space: nowrap;
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
  font-size: 0.7rem;
}

.performance-facts__table--small {
  border-bottom: 1px solid #999;
  margin: 0 0 0.5rem 0;
}
.performance-facts__table--small thead tr {
  border-bottom: 1px solid black;
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
  border-bottom: 10px solid black;
}

.thin-end {
  border-bottom: 1px solid black;
}

</style>
<?php
  $penjamin_pasien = $this->pasien_penjamin_m->get_by(array('pasien_id' => $pasien['id'], 'penjamin_id' => $penjamin_id, 'is_active' => 1), true);
  $no_penjamin = (count($penjamin_pasien) != 0)? $penjamin_pasien->no_kartu : '';

  $jenis_peserta = ($tindakan_hd['jenis_peserta'] != '' && $tindakan_hd['jenis_peserta'] != NULL)?' ('.$tindakan_hd['jenis_peserta'].')':'';
?>


<section class="performance-facts">
  <header class="performance-facts__header">
    <h1 class="performance-facts__title">Klinik Raycare <i>#<?=$invoice['no_invoice']?></i></h1>
    <p id="tanggal_invoice">Tanggal : <?=date('d M Y', strtotime($invoice['created_date']))?></p>
    <p id="tanggal_invoice">Waktu : <?=$invoice['waktu_tindakan']?></p>
    <p id="no_rm">No. RM : <?=$pasien['no_member']?></p>
    <p id="nama_pasien">Pasien : <?=$invoice['nama_pasien']?></p>
    <p id="penjamin">Penjamin : <?=$invoice['nama_penjamin']?></p>
    <p id="no_penjamin">No. Penjamin : <?=($invoice['penjamin_id'] == 1)?'-':$no_penjamin?></p>
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
        <th colspan="2"> <b>Deskripsi</b> (Jml)</th> 
        <td> Sub Total </td> 
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
          <td ><b><?=(formatrupiah($detail_tindakan['harga'] * $detail_tindakan['qty']))?></b></td>
        </tr>
        <tr>
          <td class="blank-cell"> </td>
          <th class="deskripsi" style="white-space: normal !important;">Diskon</th>
          <td ><b><?=(formatrupiah($detail_tindakan['diskon_nominal']))?></b></td>
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
          <td ><b><?=(formatrupiah($detail_item['harga'] * $detail_item['qty']))?></b></td>
        </tr>
        <tr>
          <td class="blank-cell"> </td>
          <th class="deskripsi" style="white-space: normal !important;">Diskon</th>
          <td ><b><?=(formatrupiah($detail_item['diskon_nominal']))?></b></td>
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
      <tr>
        <th colspan="2">Total Transaksi</th>
        
        <td id="total_invoice_umum"><b><?=formatrupiah($total_invoice)?></b></td>
      </tr>
      <tr>
        <th colspan="2">Diskon</th>
        
        <td id="akomodasi_umum"><b><?=formatrupiah($invoice['diskon_nominal'])?></b></td>
      </tr>
      <tr>
        <th colspan="2">Akomodasi</th>
        
        <td id="akomodasi_umum"><b><?=formatrupiah($invoice['akomodasi'])?></b></td>
      </tr>
      <tr>
        <th colspan="2">Grand Total</th>
        
        <td id="grand_total_umum"><b><?=formatrupiah($total_invoice+$invoice['akomodasi']-$invoice['diskon_nominal'])?></b></td>
      </tr>
      <tr>
        <th colspan="3">Terbilang</th>
      </tr>  
      <tr>
      <th colspan="3" id="terbilang_umum"><b># <?=terbilang($total_invoice+$invoice['akomodasi']-$invoice['diskon_nominal'])?> Rupiah #</b></th>
      </tr>

      <tr>
        <th colspan="3">Pasien</th>
      </tr>  
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <tr>
        <td colspan="3"><?=$invoice['nama_pasien']?></td>
      </tr> 
      
    </tbody>
  </table>
</section>
