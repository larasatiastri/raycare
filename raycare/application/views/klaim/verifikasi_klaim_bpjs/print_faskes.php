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
				font-size: 11px;
			}
			table#tarif_tindakan td{
				font-size: 11px;
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
				<b>REKAP FORMULIR PENGAJUAN KLAIM</b>				
			</div>

			<br>
			<div id="isi_surat">	
					<table border=0 width="100%" style="border: 0px solid black; padding-left:30px;">
						<tr>
							<td width="10%">Nama RS</td>
							<td width="2%">: </td>
							<td width="65%"><b>KLINIK RAYCARE (HAEMODIALISA)</b></td>

						</tr>
						<tr>
							<td width="10%">Tingkat Pelayanan</td>
							<td width="2%">: </td>
							<td width="65%"><b>RJTL</b></td>
						</tr>
						<tr>
							<td width="10%">Tgl.Pelayanan</td>
							<td width="2%">: </td>
							<td width="65%"><?=date('01 M Y', strtotime($data_proses['periode_tindakan'])).' s/d '.date('t M Y', strtotime($data_proses['periode_tindakan']))?></td>
						</tr>
							
					</table>
				<br>
				
				<table id="tarif_tindakan" width="100%" style="border: 0.5px solid #BFBFBF;">
					<thead>
						
						<tr class="even">
							<th width="10%">No</th>
							<th width="15%">No. FPK</th>
							<th width="20%">Tgl. FPK</th>
							<th width="10%">Kasus</th>
							<th width="20%">Biaya Riil RS</th>
							<th width="20%">Biaya Setuju</th>
						</tr>
					</thead>
					<tbody>
						<tr class="odd" >
							<td class="no" width="10%">1</td>
							<td width="15%" style="text-align:left; ">&nbsp;&nbsp;<?=$data_proses['no_fpk']?></td>
							<td width="20%" style="text-align:center; "><?=date('d-m-Y', strtotime($data_proses['tanggal_proses']))?></td>
							<td width="10%" style="text-align:right; "><?=$data_proses['jumlah_tindakan_verif']?></td>
							<td width="15%" style="text-align:right; "><?=formatrupiah($data_proses['jumlah_tarif_riil'])?></td>
							<td width="20%" style="text-align:right; "><?=formatrupiah($data_proses['jumlah_tarif_ina_verif'])?></td>
						</tr>
					</tbody>
					<tfoot>
						<tr class="even" >
							<th class="no" width="45%" colspan="3">Total</th>
							<th width="10%" style="text-align:right; "><?=$data_proses['jumlah_tindakan_verif']?></th>
							<th width="15%" style="text-align:right; "><?=formatrupiah($data_proses['jumlah_tarif_riil'])?></th>
							<th width="20%" style="text-align:right; "><?=formatrupiah($data_proses['jumlah_tarif_ina_verif'])?></th>
						</tr>
					</tfoot>
						
				</table>
				<br>
				
				<table width="100%">
					<tbody>
						<tr>
							<td width="33%" style="text-align:center;">
								<table style="text-align:center;">
									
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
								<table style="text-align:center;">
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
										<td>Mengetahui</td>
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
