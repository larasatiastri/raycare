<html>
	<head>
		<style type="text/css">

			body
			{
				font-size: 12px;
				font-family: Arial;
				text-align: justify;
			}

			table {
				border: none;
				font-size: 12px;
				margin: 0;
				padding: 0;
			    border-collapse:collapse; 
			    line-height: 15px;
			}

			table#tarif_tindakan{
				font-size: 10px;
			}
			table#tarif_tindakan td{
				font-size: 10px;
				padding-right: 3px;
				border: 0.5px solid #BFBFBF;
			}
		
			table#tarif_tindakan th{
				border: 0.5px solid #BFBFBF;
			}
		
			tr.odd{
				background-color: #EFEFEF;
			}

			td.no{
				text-align: center;
			}
			
			#header 
			{
				width: 100%;
				border:0px solid green;
				margin-bottom:15px;
			}

			#head-table, #head-table-inform{
				width: 100%;
				margin-bottom: 1px;
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
				font-size: 16px;
				/*margin-left: 30px;*/
				text-decoration:overline;
			}

			#no_surat {
				text-align: center;
				/*margin-left: 30px;*/
				text-decoration:overline;
			}

			div#tujuan_surat br {
   				line-height:22px;
			}

			.title-child{
				font-size: 14px;
			}

			.head{
				display: block;
				width: 100%;
				margin: auto;
				border:0px solid red;
				/*padding-left:10px;*/
			}
			.logo-a4{
				float: left;
				width : 185px;
				height: 70px;
				float:left;
				background-color:#fff;
				padding-right:80px;
			}
			.logo-a4-margin{
				float: left;
				width:20px;
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

			.address {
				width: 158px;
				height: 70px;
				float:left;
				border-left:1px solid #2462AC;
				padding-left:10px;
				padding-right:10px;

			}

			.address span{
				font-size:8px; color:#2462AC;
			}

			.socmed {
				width: 158px;
				height: 70px;
				float:left;
				background-color:#FFF;
				border-left:1px solid #2462AC;
				padding-left:10px;
			}

			.socmed span{
				font-size:8px;
				color:#2462AC;
			}

		</style>
	</head>
	<body>
	<?php
		
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real_bpjs')) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real_bpjs'))) 
        {
            $image_header = config_item('base_dir').config_item('site_logo_real_bpjs');
        }
        else 
        {
            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."logo/logo-bpjs-kesehatan.png";
        }


  		$user = $this->user_m->get($data_proses['user_id']);

  		$bpjs_1 = $this->petugas_bpjs_m->get($data_proses['penerima_id']);
  		$bpjs_2 = $this->petugas_bpjs_m->get($data_proses['verif_id']);
  		$bpjs_3 = $this->petugas_bpjs_m->get_by(array('jabatan'=>1, 'is_active'=>1), true);
	?>
		<div id="header">
			<div class="head">
				<div class="logo-a4">
					<img src="<?=$image_header?>">
				</div>
			</div>
		</div>
		<div id="body_surat">
			<div id="title">
				<b>KLAIM TIDAK LAYAK ADMINISTRASI RJTL BPJS Kesehatan KLINIK RAYCARE</b>				
			</div>
			<div id="title">
				<b>BULAN PELAYANAN <?=date('F Y', strtotime($data_proses['periode_tindakan']))?></b>				
			</div>

			<br>
			<div id="isi_surat">	
				<table id="tarif_tindakan" width="100%" style="border: 0.5px solid #BFBFBF;">
					<thead>
						
						<tr class="even">
							<th width="2%">No</th>
							<th width="9%">TGL. PELAYANAN</th>
							<th width="10%">NAMA</th>
							<th width="9%">NO. MR</th>
							<th width="9%">NO SKP/SJP</th>
							<th width="9%">INA-CBG</th>
							<th width="10%">TARIF</th>
							<th width="9%">AMHP</th>
							<th width="9%">BIAYA LAINNYA</th>
							<th width="10%">TOTAL</th>
							<th width="10%">KETERANGAN</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$color = 'odd';
							$i = 1;
							$total_tarif = 0;
							$total_ahmp = 0;
							$total_biaya_lain = 0;
							$total_total = 0;
							foreach ($data_tidak_layak as $tidak_layak) 
							{
								if($i%2 == 0) $color = 'even';
						?>
							<tr class="<?=$color?>" >
								<td width="2%" class="no"><?=$i?></td>
								<td width="9%"><?=date('d-m-Y', strtotime($tidak_layak['tanggal_tindakan']))?></td>
								<td width="10%"><?=$tidak_layak['nama']?></td>
								<td width="9%"><?=$tidak_layak['no_member']?></td>
								<td width="9%"><?=$tidak_layak['no_skp']?></td>
								<td width="9%"><?=$tidak_layak['ina_cbg']?></td>
								<td width="10%" style="text-align:right;"><?=($tidak_layak['tarif'] != '')?formattanparupiah($tidak_layak['tarif']):formattanparupiah(0)?></td>
								<td width="9%" style="text-align:right;"><?=($tidak_layak['ahmp'] != '')?formattanparupiah($tidak_layak['ahmp']):formattanparupiah(0)?></td>
								<td width="9%" style="text-align:right;"><?=($tidak_layak['biaya_lain'] != '')?formattanparupiah($tidak_layak['biaya_lain']):formattanparupiah(0)?></td>
								<td width="10%" style="text-align:right;"><?=($tidak_layak['total'] != '')?formattanparupiah($tidak_layak['total']):formattanparupiah(0)?></td>
								<td width="10%"><?=$tidak_layak['keterangan']?></td>
							</tr>
						
						<?php
								$total_tarif      = $total_tarif + $tidak_layak['tarif'];
								$total_ahmp       = $total_ahmp + $tidak_layak['ahmp'];
								$total_biaya_lain = $total_biaya_lain + $tidak_layak['biaya_lain'];
								$total_total      = $total_total + $tidak_layak['total'];

								$i++;
							}

							for($x=1;$x<=(7-count($data_tidak_layak));$x++)
							{
								$class = 'even';
								$color = 'color:#FFFFFF;';

								if($x%2 == 0)
								{
									$class = 'odd';
									$color = 'color:#EFEFEF;';
								}
						?>
						<tr class="<?=$class?>" >
							<td width="2%" style="<?=$color?>">No</td>
							<td width="9%" style="<?=$color?>">TGL. PELAYANAN</td>
							<td width="10%" style="<?=$color?>">NAMA</td>
							<td width="9%" style="<?=$color?>">NO. MR</td>
							<td width="9%" style="<?=$color?>">NO SKP/SJP</td>
							<td width="9%" style="<?=$color?>">INA-CBG</td>
							<td width="10%" style="<?=$color?>">TARIF</td>
							<td width="9%" style="<?=$color?>">AMHP</td>
							<td width="9%" style="<?=$color?>">BIAYA LAINNYA</td>
							<td width="10%" style="<?=$color?>">TOTAL</td>
							<td width="10%" style="<?=$color?>">KETERANGAN</td>
						</tr>
						<?php

							}
						?>

					</tbody>
					<tfoot>
						<tr class="even">
							<th width="52%" colspan="6">TOTAL</th>
							<th width="10%" style="text-align:right;"><?=formattanparupiah($total_tarif)?></th>
							<th width="9%" style="text-align:right;"><?=formattanparupiah($total_ahmp)?></th>
							<th width="9%" style="text-align:right;"><?=formattanparupiah($total_biaya_lain)?></th>
							<th width="10%" style="text-align:right;"><?=formattanparupiah($total_total)?></th>
							<th width="10%" ></th>
							
						</tr>
					</tfoot>
						
				</table>
				<br>
				
				<table width="100%">
					<tbody>
						<tr>
							<td width="33%" style="text-align:center;">
								<table style="text-align:center; color: #FFF;">
									
									<tr rowspan="2">
										<td valign="bottom">Menyetujui</td>
									</tr>
									
									<tr>
										<td height="10px"></td>
									</tr>
									
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="50px"></td>
									</tr>
									
									<tr>
										<td style="text-align:center;"><?=$user->nama?></td>
									</tr>
								</table>
							</td>
							<td width="33%" style="text-align:center;">
								<table style="text-align:center; color: #FFF;">
									<tr>
										<td>Mengetahui</td>
									</tr>
									<tr>
										<td>Kepala Cabang BPJS Jakarta Barat</td>
									</tr>
									
									<tr>
										<td height="10px"></td>
									</tr>
									
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="50px"></td>
									</tr>
									
									<tr>
										<td style="text-align:center;"><?=$bpjs_3->nama?></td>
									</tr>
								</table>
							</td>
							<td width="33%" style="text-align:center;">
								<table style="text-align:center;">
									<tr>
										<td>Jakarta, <?=date('d F Y', strtotime($data_proses['tanggal']))?></td>
									</tr>
									<tr>
										<td>Verifikator BPJS</td>
									</tr>
									
									<tr>
										<td height="10px"></td>
									</tr>
									
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="50px"></td>
									</tr>

									<tr>
										<td style="text-align:center;"><?=$bpjs_2->nama?></td>
									</tr>

								</table>
							</td>
							
						</tr>
					</tbody>	
				</table>
			</div>
		</div>
		
	</body>
</html>
