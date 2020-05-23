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

  		$bpjs_1 = $this->petugas_bpjs_m->get($data_proses['penerima_id']);
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
		<div id="body_surat">
			<div id="title">
				<b>BERITA ACARA PENYERAHAN KLAIM BPJS KESEHATAN</b>				
			</div>
			<div id="title">
				<b>BULAN PELAYANAN <?=strtoupper(date('F Y', strtotime($data_proses['periode_tindakan'])))?></b>				
			</div>
			<br>
			<br>
			<div id="isi_surat">
				Yang bertandatangan dibawah ini :
				<br>
				<br>
				
					<table border=0 width="100%" style="border: 0px solid black; padding-left:30px;">
						<tr>
							<td width="10%">Nama</td>
							<td width="2%">: </td>
							<td width="65%"><?=$user->nama?></td>

						</tr>
						<tr>
							<td width="10%">Jabatan</td>
							<td width="2%">: </td>
							<td width="65%">Direktur Klinik Raycare</td>
						</tr>
						
							
					</table>
				<br>
				
				<span id="paragraf_surat">Menyerahkan tagihan beserta berkas pendukung klaim program BPJS Kesehatan bulan pelayanan <?=date('F Y', strtotime($data_proses['periode_tindakan']))?> Klinik Raycare dengan tarif kelas Rumah Sakit D berdasarkan Perjanjian Kerjasama antara Klinik Raycare dengan BPJS Cabang Jakarta Barat Nomor <?=$data_proses['no_surat_perjanjian']?> tentang Pelayanan Kesehatan Bagi Peserta BPJS Kesehatan untuk selanjutanya agar dapat dilakukan verifikasi oleh BPJS Kesehatan Cabang Jakarta Barat.
				<br>
				<br>
				<br>
				Dengan rincian klaim sebagai berikut :
				</span>
				<br>
				<br>
				<table id="tarif_tindakan" width="100%" style="border: 0.5px solid #BFBFBF;">
					<thead>
						<tr class="even">
							<th colspan="7">TAGIHAN YANG DIAJUKAN RS</th>
						</tr>
						<tr class="odd">
							<th width="10%"></th>
							<th width="15%">Jumlah Pasien</th>
							<th width="20%">Tarif</th>
							<th width="10%">AHMP</th>
							<th width="15%">Biaya Lainnya</th>
							<th width="20%">Total</th>
							<th width="10%">Ket</th>
						</tr>
					</thead>
					<tbody>
						<tr class="even">
							<td colspan="7">&nbsp;&nbsp;Pengajuan</td>
						</tr>
						<tr class="odd" >
							<td width="10%">&nbsp;&nbsp;RJTL</td>
							<td width="15%" style="text-align:center; "><?=$data_proses['jumlah_tindakan']?></td>
							<td width="20%" style="text-align:center; "><?=formatrupiah($data_proses['jumlah_tarif_ina'])?></td>
							<td width="10%" style="text-align:center; ">-</td>
							<td width="15%" style="text-align:center; ">-</td>
							<td width="20%" style="text-align:center; "><?=formatrupiah($data_proses['jumlah_tarif_ina'])?></td>
							<td width="10%" style="text-align:center; "></td>
						</tr>
						<tr class="even" >
							<td width="10%">&nbsp;&nbsp;RITL</td>
							<td width="15%" style="text-align:center; ">-</td>
							<td width="20%" style="text-align:center; ">-</td>
							<td width="10%" style="text-align:center; ">-</td>
							<td width="15%" style="text-align:center; ">-</td>
							<td width="20%" style="text-align:center; ">-</td>
							<td width="10%" style="text-align:center; "></td>
						</tr>
						<tr class="odd" >
							<td width="10%">&nbsp;&nbsp;Subtotal</td>
							<td width="15%" style="text-align:center; "><?=$data_proses['jumlah_tindakan']?></td>
							<td width="20%" style="text-align:center; "><?=formatrupiah($data_proses['jumlah_tarif_ina'])?></td>
							<td width="10%" style="text-align:center; ">-</td>
							<td width="15%" style="text-align:center; ">-</td>
							<td width="20%" style="text-align:center; "><?=formatrupiah($data_proses['jumlah_tarif_ina'])?></td>
							<td width="10%" style="text-align:center; "></td>
						</tr>
						
					</tbody>
					
								
				</table>
				<br>
				<span id="paragraf_surat" style="font-size: 9px;">RJTL : Rawat Jalan Tingkat Lanjut.</span><br>
				<br>
				<span id="paragraf_surat">Demikian Berita Acara ini dibuat untuk digunakan sebagaimana mestinya. Atas perhatiannya kami sampaikan terima kasih.
				</span>
				<br>
				<br>
				<table width="100%">
					
					<tbody>
						<tr>
							<td width="50%" style="text-align:center;">
								<table style="text-align:center;">
									<tr>
										<td>Tanggal Diterima <?=date('d F Y', strtotime($data_proses['tanggal']))?></td>
									</tr>
									<tr>
										<td>Yang Menerima</td>
									</tr>
									<tr>
										<td>Ka. Unit Manajemen Pelayanan Rujukan</td>
									</tr>
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="10px">
											
										</td>
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
										<td height="40px"></td>
									</tr>
									
									<tr>
										<td style="text-align:center;"><?=$bpjs_1->nama?></td>
									</tr>
								</table>
							</td>
							<td width="50%" style="text-align:center;">
								<table style="text-align:center;">
									<tr>
										<td>Tanggal Diserahkan <?=date('d F Y', strtotime($data_proses['tanggal']))?></td>
									</tr>
									<tr>
										<td>Yang Menyerahkan</td>
									</tr>
									<tr>
										<td>Direktur Klinik Raycare</td>
									</tr>
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="10px">
											
										</td>
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
										<td height="40px"></td>
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
