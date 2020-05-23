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
			    line-height: 20px;
			}

			table#tarif_tindakan td{
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
				margin-bottom:20px;
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
				margin:0px;
				/*padding-left:10px;*/
			}
			.logo-a4{
				float: left;
				width : 165px;
				height: 70px;
				float:left;
				background-color:#fff;
				padding-right:180px;
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
		
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real')) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real'))) 
        {
            $image_header = config_item('base_dir').config_item('site_logo_real');
        }
        else 
        {
            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."logo/logo-real.png";
        }

        $user = $this->user_m->get($data_proses['user_id']);
  
	?>
		<div id="header">
			<div class="head">
				<div class="logo-a4">
					<img src="<?=$image_header?>">
					<div class="rs-code">
						<p>RS CODE : <?=$form_cabang->kode?></p>
					</div>
				</div>
				<div class="logo-a4-margin"></div>
				<div class="address">
					<span><b style="">Address&nbsp;:</b></span>
					<br>
					<span><?=$cabang_alamat[0]['alamat']?></span>
					<br>
					<br>
					<span><b>P.</b> <?=$cabang_telepon[0]['nomor']?></span>
					<br>
					<span><b>F.</b> <?=$cabang_fax[0]['nomor']?></span>
				</div>
				<div class="socmed">
					<span><b>E.</b> <?=rtrim($data_email,', ')?></span>
					<br>
					<span><b>Follow &amp; Visit</b></span><br>
					<span>fb : <?=$cabang_fb[0]['url']?></span><br>
					<span>twitter <?=$cabang_twitter[0]['url']?></span><br>
					<span><?=$cabang_website[0]['url']?></span>
				</div>
			</div>
		</div>
		<div id="body_kwitansi">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="60%" colspan="4"></td>
					<td width="40%" style="text-align: right;">Jakarta, <?=date('d F Y', strtotime($data_proses['tanggal']))?></td>
				</tr>
				<tr>
					<td width="60%" colspan="5" height="15px"></td>
				</tr>
				<tr>
					<td width="25%">Nomor</td>
					<td width="2%">:</td>
					<td width="83%" colspan="3" valign="top" style="text-align: left;"><?=$data_proses['no_surat']?></td>
				</tr>

				<tr>
					<td width="25%">Lampiran</td>
					<td width="2%">:</td>
					<td width="83%" colspan="3" valign="top" style="text-align: left;">1 (satu) berkas</td>
				</tr>
				
				<tr>
					<td width="25%" valign="bottom">Perihal</td>
					<td width="2%" valign="bottom">:</td>
					<td width="83%" colspan="3" valign="bottom" style="text-align: left;">Pengajuan Tagihan Rawat Jalan Program BPJS</td>
				</tr>
				<tr>
					<td width="27%" colspan="2" valign="bottom"></td>
					<td width="83%" colspan="3" valign="bottom" style="text-align: left;">Bulan Pelayanan <?=date('F Y', strtotime($data_proses['periode_tindakan']))?></td>
				</tr>				
			</table>
			<br>
			<br>

			<div id="tujuan_surat">
				Yang Terhormat,
				<br>
				BPJS Kesehatan Cabang Jakarta Barat
				<br>
				di Jakarta
			</div>
			<br>
			<br>
			<div id="isi_surat">
				Bersama ini kami sampaikan rekapitulasi tagihan klaim pasien BPJS beserta file dan berkas pendukung klaim dengan INA-CBGs bulan pelayanan <?=date('F Y', strtotime($data_proses['periode_tindakan']))?>.
				<br>
				<br>
				<table id="tarif_tindakan" width="100%" style="border: 0.5px solid #BFBFBF;">
					<thead>
						<tr class="even">
							<th class="no" width="5%">No</th>
							<th width="25%">Jenis Pelayanan</th>
							<th width="35%">Jumlah Tarif Riil RS</th>
							<th width="35%">Jumlah Biaya (Tarif INA-CBGs)</th>
						</tr>
					</thead>
					<tbody>
						<tr class="odd">
							<td class="no" width="5%">1</td>
							<td width="25%">&nbsp;&nbsp;Rawat Jalan</td>
							<td width="35%" style="text-align:right; padding-right:5px;"><?=formatrupiah($data_proses['jumlah_tarif_riil'])?></td>
							<td width="35%" style="text-align:right; padding-right:5px;"><?=formatrupiah($data_proses['jumlah_tarif_ina'])?></td>
						</tr>
						<tr class="even" >
							<td class="no" width="5%" style="color:#fff;">1</td>
							<td width="25%" style="color:#fff;">Rawat Jalan</td>
							<td width="35%" style="color:#fff;">Jumlah Tarif Riil RS</td>
							<td width="35%" style="color:#fff;">Jumlah Biaya (Tarif INA-CBGs)</td>
						</tr>
						<tr class="odd blank_odd">
							<td class="no" width="5%" style="color:#EFEFEF;">1</td>
							<td width="25%" style="color:#EFEFEF;">Rawat Jalan</td>
							<td width="35%" style="color:#EFEFEF;">Jumlah Tarif Riil RS</td>
							<td width="35%" style="color:#EFEFEF;">Jumlah Biaya (Tarif INA-CBGs)</td>
						</tr>
					</tbody>
					<tfoot>
						<tr class="even">
							<th width="30%" colspan="2" style="text-align:center;">Total</th>
							<th width="35%" style="text-align:right; padding-right:5px;"><?=formatrupiah($data_proses['jumlah_tarif_riil'])?></th>
							<th width="35%" style="text-align:right; padding-right:5px;"><?=formatrupiah($data_proses['jumlah_tarif_ina'])?></th>
						</tr>
					</tfoot>
								
				</table>
				<br>
				<br>
				Terbilang 	: "<?=terbilang($data_proses['jumlah_tarif_ina'])?> Rupiah"
				<br>
				<br>
				Demikian atas kerjasamanya kami ucapkan terimakasih.
				<br>
				<br>
				<br>
				<table width="100%">
					
					<tbody>
						<tr>
							<td class="no" width="10%" colspan="2"></td>
							<td width="35%" style="text-align:right; padding-right:5px;"></td>
							<td width="35%" style="text-align:right; padding-right:5px;">
								<table>
									<tr>
										<td style="text-align:center;">Pengajuan Klaim</td>
									</tr>
									<tr>
										<td style="text-align:center;">Direktur Klinik Raycare</td>
									</tr>
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td style="text-align:center;">
											<table width="100px" border="0">
												<tr>
													<td style="font-size: 6px; text-align:center; color:#fff">Materai</td>
												</tr>
												
												<tr>
													<td style="font-size: 6px; text-align:center; color:#fff">Materai</td>
												</tr>	

											</table>
										</td>
									</tr>
									<tr>
										<td height="10px"></td>
									</tr>
									
									<tr>
										<td style="text-align:center;"><?=$user->nama?></td>
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
