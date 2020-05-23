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
				font-size: 9px;
			}
			table#tarif_tindakan td{
				font-size: 9px;
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
				<b>BERITA ACARA HASIL VERIFIKASI KLAIM PROGRAM BPJS DENGAN POLA TARIF INA CBG’s</b>				
			</div>

			<br>
			<div id="isi_surat">	
				<table id="tarif_tindakan" width="100%" style="border: 0.5px solid #BFBFBF;">
					<thead>
						
						<tr class="even">
							<th width="2%">No</th>
							<th width="9%">Nama Rumah Sakit</th>
							<th width="10%">Bulan Pelayanan</th>
							<th width="9%">Tanggal Pengajuan Klaim RS</th>
							<th width="9%">Jenis Pelayanan</th>
							<th width="9%">Jumlah Kasus</th>
							<th width="5%">Hari Rawat</th>
							<th width="9%">Jumlah Biaya Riil RS (Rp)</th>
							<th width="9%">Jumlah Pengajuan RS (Tarif INA CBG’s) (Rp)</th>
							<th width="10%">Jumlah Hasil Verifikasi (RP)</th>
							<th width="10%">Selisih (RP)</th>
							<th width="10%">KETERANGAN</th>
						</tr>
					</thead>
					<tbody>
						<tr class="odd" >
							<td width="2%" class="no" >1</td>
							<td width="9%" >&nbsp;&nbsp;Klinik Raycare</td>
							<td width="10%" style="text-align:center;" ><?=date('M-Y', strtotime($data_proses['periode_tindakan']))?></td>
							<td width="9%" >&nbsp;&nbsp;<?=date('d-M-Y', strtotime($data_proses['tanggal']))?></td>
							<td width="9%" style="text-align:right;">RJTL/HD</td>
							<td width="9%" style="text-align:right;"><?=$data_proses['jumlah_tindakan_verif']?></td>
							<td width="5%" style="text-align:right;">0</td>
							<td width="9%" style="text-align:right;"><?=formattanparupiah($data_proses['jumlah_tarif_riil'])?></td>
							<td width="9%" style="text-align:right;"><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></td>
							<td width="10%" style="text-align:right;"><?=formattanparupiah($data_proses['jumlah_tarif_ina_verif'])?></td>
							<td width="10%" style="text-align:right;"><?=formattanparupiah($data_proses['jumlah_tarif_ina']-$data_proses['jumlah_tarif_ina_verif'])?></td>
							<td width="10%" >&nbsp;&nbsp;<?=$data_proses['keterangan']?></td>
						</tr>
						<tr class="even" >
							<td width="2%" style="color:#FFFFFF;">No</td>
							<td width="9%" style="color:#FFFFFF;">TGL. PELAYANAN</td>
							<td width="10%" style="color:#FFFFFF;">NAMA</td>
							<td width="9%" style="color:#FFFFFF;">NO. MR</td>
							<td width="9%" style="color:#FFFFFF;">NO SKP/SJP</td>
							<td width="9%" style="color:#FFFFFF;">INA-CBG</td>
							<td width="5%" style="color:#FFFFFF;">TARIF</td>
							<td width="9%" style="color:#FFFFFF;">AMHP</td>
							<td width="9%" style="color:#FFFFFF;">BIAYA LAINNYA</td>
							<td width="10%" style="color:#FFFFFF;">TOTAL</td>
							<td width="10%" style="color:#FFFFFF;">KETERANGAN</td>
							<td width="10%" style="color:#FFFFFF;">KETERANGAN</td>
						</tr>
						<tr class="odd" >
							<td width="2%" style="color:#EFEFEF;">No</td>
							<td width="9%" style="color:#EFEFEF;">TGL. PELAYANAN</td>
							<td width="10%" style="color:#EFEFEF;">NAMA</td>
							<td width="9%" style="color:#EFEFEF;">NO. MR</td>
							<td width="9%" style="color:#EFEFEF;">NO SKP/SJP</td>
							<td width="9%" style="color:#EFEFEF;">INA-CBG</td>
							<td width="5%" style="color:#EFEFEF;">TARIF</td>
							<td width="9%" style="color:#EFEFEF;">AMHP</td>
							<td width="9%" style="color:#EFEFEF;">BIAYA LAINNYA</td>
							<td width="10%" style="color:#EFEFEF;">TOTAL</td>
							<td width="10%" style="color:#EFEFEF;">KETERANGAN</td>
							<td width="10%" style="color:#EFEFEF;">KETERANGAN</td>
						</tr>
						<tr class="even" >
							<td width="2%" style="color:#FFFFFF;">No</td>
							<td width="9%" style="color:#FFFFFF;">TGL. PELAYANAN</td>
							<td width="10%" style="color:#FFFFFF;">NAMA</td>
							<td width="9%" style="color:#FFFFFF;">NO. MR</td>
							<td width="9%" style="color:#FFFFFF;">NO SKP/SJP</td>
							<td width="9%" style="color:#FFFFFF;">INA-CBG</td>
							<td width="5%" style="color:#FFFFFF;">TARIF</td>
							<td width="9%" style="color:#FFFFFF;">AMHP</td>
							<td width="9%" style="color:#FFFFFF;">BIAYA LAINNYA</td>
							<td width="10%" style="color:#FFFFFF;">TOTAL</td>
							<td width="10%" style="color:#FFFFFF;">KETERANGAN</td>
							<td width="10%" style="color:#FFFFFF;">KETERANGAN</td>
						</tr>
						<tr class="odd" >
							<td width="2%" style="color:#EFEFEF;">No</td>
							<td width="9%" style="color:#EFEFEF;">TGL. PELAYANAN</td>
							<td width="10%" style="color:#EFEFEF;">NAMA</td>
							<td width="9%" style="color:#EFEFEF;">NO. MR</td>
							<td width="9%" style="color:#EFEFEF;">NO SKP/SJP</td>
							<td width="9%" style="color:#EFEFEF;">INA-CBG</td>
							<td width="5%" style="color:#EFEFEF;">TARIF</td>
							<td width="9%" style="color:#EFEFEF;">AMHP</td>
							<td width="9%" style="color:#EFEFEF;">BIAYA LAINNYA</td>
							<td width="10%" style="color:#EFEFEF;">TOTAL</td>
							<td width="10%" style="color:#EFEFEF;">KETERANGAN</td>
							<td width="10%" style="color:#EFEFEF;">KETERANGAN</td>
						</tr>
						<tr class="even" >
							<td width="2%" style="color:#FFFFFF;">No</td>
							<td width="9%" style="color:#FFFFFF;">TGL. PELAYANAN</td>
							<td width="10%" style="color:#FFFFFF;">NAMA</td>
							<td width="9%" style="color:#FFFFFF;">NO. MR</td>
							<td width="9%" style="color:#FFFFFF;">NO SKP/SJP</td>
							<td width="9%" style="color:#FFFFFF;">INA-CBG</td>
							<td width="5%" style="color:#FFFFFF;">TARIF</td>
							<td width="9%" style="color:#FFFFFF;">AMHP</td>
							<td width="9%" style="color:#FFFFFF;">BIAYA LAINNYA</td>
							<td width="10%" style="color:#FFFFFF;">TOTAL</td>
							<td width="10%" style="color:#FFFFFF;">KETERANGAN</td>
							<td width="10%" style="color:#FFFFFF;">KETERANGAN</td>
						</tr>
						<tr class="odd" >
							<td width="2%" style="color:#EFEFEF;">No</td>
							<td width="9%" style="color:#EFEFEF;">TGL. PELAYANAN</td>
							<td width="10%" style="color:#EFEFEF;">NAMA</td>
							<td width="9%" style="color:#EFEFEF;">NO. MR</td>
							<td width="9%" style="color:#EFEFEF;">NO SKP/SJP</td>
							<td width="9%" style="color:#EFEFEF;">INA-CBG</td>
							<td width="5%" style="color:#EFEFEF;">TARIF</td>
							<td width="9%" style="color:#EFEFEF;">AMHP</td>
							<td width="9%" style="color:#EFEFEF;">BIAYA LAINNYA</td>
							<td width="10%" style="color:#EFEFEF;">TOTAL</td>
							<td width="10%" style="color:#EFEFEF;">KETERANGAN</td>
							<td width="10%" style="color:#EFEFEF;">KETERANGAN</td>
						</tr>

					</tbody>
					<tfoot>
						<tr class="even">
							<th width="52%" colspan="7">TOTAL</th>
							<th width="9%" style="text-align:right; padding-right:3px;"><?=formattanparupiah($data_proses['jumlah_tarif_riil'])?></th>
							<th width="9%" style="text-align:right; padding-right:3px;"><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></th>
							<th width="10%" style="text-align:right; padding-right:3px;"><?=formattanparupiah($data_proses['jumlah_tarif_ina_verif'])?></th>
							<th width="10%" style="text-align:right; padding-right:3px;"><?=formattanparupiah($data_proses['jumlah_tarif_ina']-$data_proses['jumlah_tarif_ina_verif'])?></th>
							<th width="10%"></th>
						</tr>
						<tr class="odd">
							<th width="52%" colspan="7" style="text-align:right; padding-right:3px;">Terbilang</th>
							<th width="48%" colspan="5" style="text-align:left;">&nbsp;&nbsp;#<?=terbilang($data_proses['jumlah_tarif_ina_verif'])?> Rupiah #</th>
							
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
