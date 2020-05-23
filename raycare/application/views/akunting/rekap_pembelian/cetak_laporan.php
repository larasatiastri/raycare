<html>
<head>
	<style>
        body {
                font-family: "Open Sans", sans-serif;

        }
		.text{
			font-size: 8pt;
		}
		.text2{
			font-size: 9pt;
		}
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 4px;
        }

        tr:nth-child(even){background-color: #f2f2f2}

        th {
            background-color: #2462AC;
            color: white;
        }      

        
        #header 
            {
                width: 100%;
                border:0px solid green;
                margin-bottom:7px;
            }
        #body
        {
            width: 100%;
            margin: auto;
        }

        #body-table td{
            height: 75px;
        }

        #body-table{
            border-collapse:collapse;
            color:#333;
            width: 100%;
        }

        #body-table #signature{
            width: 20%;
            text-align: center;
            height: 0px;
        }

        #body-table th, #body-table td{
            vertical-align:top;
            padding:5px 10px;
            border:1px solid #000;
        }

        #title {
            text-transform: uppercase;
            text-align: center;
            font-size: 10px;
            /*margin-left: 30px;*/
            text-decoration:overline;
        }

        #no_surat {
            text-align: center;
            /*margin-left: 30px;*/
            text-decoration:overline;
        }

        .title-child{
            font-size: 10px;
        }

        .head{
            display: block;
            width: 100%;
            margin: auto;
            border:0px solid red;
            margin:0px;
            /*padding-left:10px;*/
        }
        .logo-a4{
            float: left;
            width : 150px;
            height: 70px;
            float:left;
            background-color:#fff;
            padding-right:180px;
        }
        .logo-a4-margin{
            float: left;
            width:55px;
            height: 70px; 
        }
        .logo-a4 img{
            width: 18px !important;
            height: 20px;
        }

        .logo-a4 div{
            margin-left:63px;
            padding:1px;
            background-color:#ed3237;
            border-radius:3px;
        }

        .rs-code p{
            color:#FFF;
            margin:0px;
            padding:0px;
            font-size:8px;
            text-align:center;
        }

	</style>
</head>
	<body>
		<?php 
			if (file_exists($_SERVER['DOCUMENT_ROOT']."cloud/".config_item('site_dir')."logo/logo-default.png") && is_file($_SERVER['DOCUMENT_ROOT']."cloud/".config_item('site_dir')."logo/logo-default.png")) 
	        {
	            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."/logo/logo-default.png";
	        }
	        else 
	        {
	            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."/logo/logo-default.png";
	        }

		?>
        <div id="header">
            <div class="head">
                <div class="logo-a4">
                    <img src="<?=$image_header?>">
                </div>
                
            </div>
        </div>
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption" align="center" style="font-size:16px;text-transform: uppercase;padding-bottom:10px;color:#2462AC;">
			<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("REKAP PEMBELIAN", $this->session->userdata("language"))?>
				PERIODE <?=date('d M Y', strtotime($tgl_awal)).' s/d '.date('d M Y', strtotime($tgl_akhir))?>
			</span>
			
		</div>
		<div class="portlet-body">
			
			<table class="table table-condensed table-striped table-hover" id="table_rekap" style="border:1px;">
				<thead>
					
					<tr>
						<th class="text-center"><?=translate("No", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("No.PMB", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="2%"><?=translate("Tgl.PMB", $this->session->userdata("language"))?> </th>
						
						
						<th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
						
						<th class="text-center" width="30%"><?=translate("Item", $this->session->userdata("language"))?> </th>
						
						<th class="text-center" width="10%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("BN", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("ED", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Harga", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("DiskItem", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("SubTotal", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Diskon", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("PPN", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("PPh", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("BiayaTambahan", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="8%"><?=translate("Total", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("No.PO", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="2%"><?=translate("Tgl.PO", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("JenisBayar", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("SatuanPO", $this->session->userdata("language"))?> </th>
						
						
						
						
					</tr>
				</thead>
				<tbody>

					<?php
						$i=1;

						//die(dump($data_laporan));
						$total_harga_beli = 0;
						$total_diskon_item = 0;
						$total_subtotal = 0;
						$total_diskon_all = 0;
						$total_ppn = 0;
						$total_pph = 0;
						$total_tambahan = 0;
						$total_grand_total = 0;
						
						foreach ($data_laporan as $key => $laporan):

							$tbd = 0;
							$diskon_item = 0;
							$sub_total = 0;
							$diskon_all = 0;
							$ppn = 0;
							$tad = 0;
							$tat = 0;
							$harga_satuan = 0;
							$harga_beli = 0;

							$tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($laporan['tipe_pembayaran'])->result_array();

							$po_detail = $this->pmb_po_detail_m->get_data_item_po($laporan['po_id'])->result_array();

							$jenis_bayar = $tipe_bayar[0]['nama'].' '.$tipe_bayar[0]['lama_tempo'];

							$konversi_pesan = $this->item_m->get_nilai_konversi($laporan['satuan_pesan']);

							$harga_satuan = $laporan['harga_beli'] / $konversi_pesan;

							$konversi_terima = $this->item_m->get_nilai_konversi($laporan['satuan_terima']);

							$harga_beli = $harga_satuan * $konversi_terima;
							$pph23 = 0;
							$biaya_tambahan = $laporan['biaya_tambahan'] / count($po_detail);

							$tbd = $laporan['jumlah_diterima'] * $harga_beli;
							$diskon_item = ($laporan['diskon_item'] / 100) * $tbd;
							$sub_total = $tbd - $diskon_item;
							$diskon_all = ($laporan['diskon'] / 100) * $sub_total;
							$tad = $sub_total - $diskon_all;
							$ppn = ($laporan['pph'] / 100) * $tad;
							
							if($laporan['is_pph'] == 1){
								$pph23 = ($laporan['pph_23']/100) * $tbd;
							}

							$tat = $tad + $ppn - $pph23 + $biaya_tambahan;


							
							

					?>
							<tr>
								<td><?=$i?></td>
								<td><div class="inline-button-table"><?=$laporan['no_pmb']?></td>
								<td><div class="inline-button-table"><?=date('d M Y', strtotime($laporan['tanggal']))?></div></td>
								
								
								<td><div class="inline-button-table"><?=$laporan['nama_supplier']?></td>
								
								<td><div class="inline-button-table text-left"><?=$laporan['nama_item']?></div></td>
								
								
								<td class="inline-button-table"><?=$laporan['jumlah_diterima'].' '.$laporan['nama_satuan_terima']?></td>
								<td class="inline-button-table"><?=$laporan['bn_sn_lot']?></td>
								<td class="inline-button-table"><?=date('d M Y', strtotime($laporan['expire_date']))?></td>
								<td class="text-right"><?=formattanparupiah($harga_beli)?></td>
								<td class="text-right"><?=formattanparupiah($diskon_item)?></td>
								<td class="text-right"><?=formattanparupiah($sub_total)?></td>
								<td class="text-right"><?=formattanparupiah($diskon_all)?></td>
								<td class="text-right"><?=formattanparupiah($ppn)?></td>
								<td class="text-right"><?=formattanparupiah($pph23)?></td>
								<td class="text-right"><?=formattanparupiah($biaya_tambahan)?></td>
								<td class="text-right"><?=formattanparupiah($tat)?></td>
								<td><div class="inline-button-table"><?=$laporan['no_pembelian']?></td>
								<td><div class="inline-button-table"><?=date('d M Y', strtotime($laporan['tanggal_pesan']))?></div></td>
								<td><div class="inline-button-table"><?=$jenis_bayar?></td>
								<td class="inline-button-table"><?=$laporan['nama_satuan']?></td>
								
								
								
							</tr>
					<?php
						
						$total_harga_beli = $total_harga_beli + $harga_beli;
						$total_diskon_item = $total_diskon_item + $diskon_item;
						$total_subtotal = $total_subtotal + $sub_total;
						$total_diskon_all = $total_diskon_all + $diskon_all;
						$total_ppn = $total_ppn + $ppn;
						$total_pph = $total_pph + $pph23;
						$total_tambahan = $total_tambahan + $biaya_tambahan;
						$total_grand_total = $total_grand_total + $tat;
						$i++;
						endforeach;
					?>
					
				</tbody>
				<tfoot>
					<tr>
						<th colspan="9"> Total</th>
						
						<th class="text-right"><?=formattanparupiah($total_diskon_item)?></th>
						<th class="text-right"><?=formattanparupiah($total_subtotal)?></th>
						<th class="text-right"><?=formattanparupiah($total_diskon_all)?></th>
						<th class="text-right"><?=formattanparupiah($total_ppn)?></th>
						<th class="text-right"><?=formattanparupiah($total_pph)?></th>
						<th class="text-right"><?=formattanparupiah($total_tambahan)?></th>
						<th class="text-right"><?=formattanparupiah($total_grand_total)?></th>
						<th colspan="4"> </th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div><!-- akhir dari portlet -->
</body>
</html>