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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("REKAP PENJUALAN", $this->session->userdata("language"))?>
				PERIODE <?=date('d M Y', strtotime($tgl_awal)).' s/d '.date('d M Y', strtotime($tgl_akhir))?>
			</span>
		</div>
		
	</div>
	<div class="portlet-body">
		<table class="table table-condensed table-striped table-hover" id="table_rekap" style="border:1px;">
			<thead>
				<tr role="row">
					<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
					<th class="text-center inline-button-table" width="13%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="30%"><?=translate("Item", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="5%"><?=translate("BN", $this->session->userdata("language"))?> </th>
					<th class="text-center inline-button-table" width="13%"><?=translate("ED", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Harga", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("SubTotal", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Diskon", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="5%"><?=translate("PPN", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="8%"><?=translate("Total", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="8%"><?=translate("HPP", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="8%"><?=translate("Total HPP", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("No. Penjualan", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Konsumen", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>
            <tbody  border="1">

				<?php
						$i=1;
						$total_item = 0;
						$total_harga = 0;
						$total_disc = 0;
						$total_sub = 0;
						$total_ppn = 0;
						$grand_total = 0;
						$total_hpp = 0;
						$grand_total_hpp = 0;
						foreach ($data_laporan as $key => $laporan):

							// $inv_history = $this->inventory_history_m->get_by(array('transaksi_id' => $laporan['penjualan_obat_id']), true);


							if($laporan['tipe_item'] == 3 && in_array($laporan['item_id'], config_item('dialyzer_id_array')) === false){

								$tgl_resep = date('Y-m-d', strtotime($laporan['tanggal']));
								$data_resep = $this->penjualan_obat_detail_m->get_resep_pasien($laporan['pasien_id'], $tgl_resep)->result_array();


								$resep_id = '';
								foreach ($data_resep as $key => $resep_ps) {
									$resep_id .= $resep_ps['id'].'-';
								}

								$data_resep_identitas = $this->penjualan_obat_detail_m->get_resep_pasien_identitas($resep_id, $laporan['item_id'])->row(0);					

								// die(dump($this->db->last_query()));			

								$satuan = $data_resep_identitas->nama;
								$bn = $data_resep_identitas->bn_sn_lot;
								$ed = date('d M Y', strtotime($data_resep_identitas->expire_date));
								$edd = date('Y-m-d', strtotime($data_resep_identitas->expire_date));

								
							}elseif($laporan['tipe_item'] == 3 && in_array($laporan['item_id'], config_item('dialyzer_id_array'))){

								$tgl_resep = date('Y-m-d', strtotime($laporan['tanggal']));
								$data_resep = $this->penjualan_obat_detail_m->get_resep_dialyzer($laporan['pasien_id'], $tgl_resep)->row(0);

								$satuan = 'Pcs';
								$bn = $data_resep->bn_sn_lot;
								$ed = date('d M Y', strtotime($data_resep->expired_date));
								$edd = date('Y-m-d', strtotime($data_resep->expired_date));

								
							}else{
								$satuan = $laporan['satuan'];
								$bn = $laporan['bn_sn_lot'];
								$ed = date('d M Y', strtotime($laporan['expire_date']));
								$edd = date('Y-m-d', strtotime($laporan['expire_date']));
							}

							$inv_history_detail = $this->inventory_history_detail_m->get_by(array('item_id' => $laporan['item_id'], 'bn_sn_lot' => $bn, 'date(expire_date)' => $edd ), true);

							$pembelian_detail = $this->pembelian_detail_m->get_by(array('id' => $inv_history_detail->pembelian_detail_id), true);

							$harga_beli = $pembelian_detail->harga_beli_primary;

							$diskon_satuan = ($laporan['diskon_nominal'] / $laporan['jumlah']);

							$konsumen = $laporan['nama_pasien'];

					        if(preg_match("/raycare/i", $konsumen)){
            
								$harga_asli = $laporan['harga_jual'];

								$sub_total = $laporan['jumlah'] * $harga_asli;

								$sub_total = $sub_total - $laporan['diskon_nominal'];

								$ppn = 0;
							}else{
								$harga_asli = (($laporan['harga_jual'] - $diskon_satuan) / 1.1) + $diskon_satuan;

								$sub_total = $laporan['jumlah'] * $harga_asli;

								$sub_total = $sub_total - $laporan['diskon_nominal'];

								$ppn = (10 / 100) * $sub_total;
							}

							$harga_total = $sub_total + $ppn;

					?>
							<tr>
								<td><?=$i?></td>
								<td><div class="inline-button-table"><?=date('d M Y', strtotime($laporan['tanggal']))?></div></td>
								<td><div class="text-left" style="margin-left: 5px;"><?=$laporan['nama']?></div></td>
								<td><div class="text-left"><?=$bn?></div></td>
								<td class="inline-button-table"><?=$ed?></td>
								<td class="inline-button-table"><?=$laporan['jumlah'].' '.$satuan?></td>
								<td class="text-right"><?=formattanparupiah($harga_asli)?></td>
								<td class="text-right"><?=formattanparupiah($sub_total)?></td>
								<td class="text-right"><?=formattanparupiah($laporan['diskon_nominal'])?></td>
								<td class="text-right"><?=formattanparupiah($ppn)?></td>
								<td class="text-right"><?=formattanparupiah($harga_total)?></td>
								<td class="text-right"><?=formattanparupiah($harga_beli)?></td>
								<td class="text-right"><?=formattanparupiah($harga_beli * $laporan['jumlah'])?></td>
								<td class="inline-button-table"><?=$laporan['no_penjualan']?></td>
								<td class="inline-button-table"><?=$laporan['nama_pasien']?></td>
							</tr>
					<?php
						$total_item = $total_item + $laporan['jumlah'];
						$total_harga = $total_harga + $harga_asli;
						$total_sub = $total_sub + $sub_total;
						$total_disc = $total_disc + $laporan['diskon_nominal'];
						$total_ppn = $total_ppn + $ppn;
						$grand_total = $grand_total + ($harga_total);
						$total_hpp = $total_hpp + ($harga_beli);
						$grand_total_hpp = $grand_total_hpp + ($harga_beli * $laporan['jumlah']);
						$i++;
						endforeach;
					?>
				
			</tbody>
			<tfoot>
				<tr>
					<th colspan="5"> TOTAL </th>
					<th class="text-left"> <?=$total_item?> </th>
					<th>  </th>
					<th class="text-right"> <?=formattanparupiah($total_sub)?> </th>
					<th class="text-right"> <?=formattanparupiah($total_disc)?> </th>
					<th class="text-right"> <?=formattanparupiah($total_ppn)?> </th>
					<th class="text-right"> <?=formattanparupiah($grand_total)?> </th>
					<th> </th>
					<th class="text-right"> <?=formattanparupiah($grand_total_hpp)?> </th>
					<th colspan="2">  </th>
				</tr>
			</tfoot>
		</table>
	</div>
</div><!-- akhir dari portlet -->
</body>
</html>