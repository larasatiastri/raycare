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
				<b>BERITA ACARA HASIL VERIFIKASI KLAIM BPJS KESEHATAN</b>				
			</div>
			<div id="title">
				<b>BULAN PELAYANAN <?=strtoupper(date('F Y', strtotime($data_proses['periode_tindakan'])))?></b>				
			</div>
			<br>
			<div id="isi_surat">
				Yang bertandatangan dibawah ini :
				<br>
				<br>
				
					<table border=0 width="100%" style="border: 0px solid black; padding-left:30px;">
						<tr>
							<td width="10%">Nama</td>
							<td width="2%">: </td>
							<td width="65%"><?=$bpjs_1->nama?></td>

						</tr>
						<tr>
							<td width="10%">Jabatan</td>
							<td width="2%">: </td>
							<td width="65%">Ketua Koordinator Verifikasi Klaim BPJS Kesehatan</td>
						</tr>
						<tr>
							<td height="10px"></td>
						</tr>

						<tr>
							<td width="10%">Nama</td>
							<td width="2%">: </td>
							<td width="65%"><?=$bpjs_2->nama?></td>

						</tr>
						<tr>
							<td width="10%">Jabatan</td>
							<td width="2%">: </td>
							<td width="65%">Petugas Verifikator Klaim BPJS bulan Pelayanan <?=date('F Y', strtotime($data_proses['periode_tindakan']))?></td>
						</tr>
						
							
					</table>
				<br>
				
				<span id="paragraf_surat">Telah melakukan rekapitulasi Umpan Balik hasil verifikasi Klaim BPJS Kesehatan bulan pelayanan <?=date('F Y', strtotime($data_proses['periode_tindakan']))?> Klinik Raycare dengan tarif kelas Rumah Sakit d berdasarkan Perjanjian Kerjasama antara Klinik Raycare dengan BPJS Cabang Jakarta Barat Nomor <?=$data_proses['no_surat_perjanjian']?> tentang Pelayanan Kesehatan Bagi Peserta BPJS Kesehatan.
				<br>
				<br>
				Dengan rincian laporan selisih pengajuan dan verifikasi sebagai berikut :
				</span>
				<br>
				<table id="tarif_tindakan" width="100%" style="border: 0.5px solid #BFBFBF;">
					<thead>
						
						<tr class="even">
							<th width="10%"></th>
							<th width="15%">Jumlah Pasien</th>
							<th width="20%">Tarif</th>
							<th width="10%">AHMP</th>
							<th width="15%">Biaya Lainnya</th>
							<th width="20%">Total</th>
						</tr>
					</thead>
					<tbody>
						<tr class="odd">
							<td colspan="6">&nbsp;&nbsp;Pengajuan</td>
						</tr>
						<tr class="even" >
							<td width="20%">&nbsp;&nbsp;RJTL</td>
							<td width="15%" style="text-align:right; "><?=$data_proses['jumlah_tindakan']?></td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></td>
							<td width="10%" style="text-align:right; "><?=formattanparupiah($data_proses['ahmp'])?></td>
							<td width="15%" style="text-align:right; "><?=formattanparupiah($data_proses['biaya_lain'])?></td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></td>
							
						</tr>
						<tr class="odd" >
							<td width="20%">&nbsp;&nbsp;RITL</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							<td width="10%" style="text-align:right; ">0</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							
						</tr>
						<tr class="even" >
							<td width="10%" >&nbsp;&nbsp;Subtotal</td>
							<td width="15%" style="text-align:right;"><?=$data_proses['jumlah_tindakan']?></td>
							<td width="20%" style="text-align:right;"><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></td>
							<td width="10%" style="text-align:right;">0</td>
							<td width="15%" style="text-align:right;">0</td>
							<td width="20%" style="text-align:right;"><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></td>
							
						</tr>
						<tr class="odd" >
							<td width="10%" style="color:#EFEFEF;">&nbsp;&nbsp;Subtotal</td>
							<td width="15%" style="text-align:right;color:#EFEFEF;"><?=$data_proses['jumlah_tindakan']?></td>
							<td width="20%" style="text-align:right;color:#EFEFEF;"><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></td>
							<td width="10%" style="text-align:right;color:#EFEFEF;">-</td>
							<td width="15%" style="text-align:right;color:#EFEFEF;">-</td>
							<td width="20%" style="text-align:right;color:#EFEFEF;"><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></td>
							
						</tr>

						<tr class="even">
							<td colspan="6">&nbsp;&nbsp;Hasil Verifikasi</td>
						</tr>
						<tr class="odd" >
							<td width="20%">&nbsp;&nbsp;RJTL</td>
							<td width="15%" style="text-align:right; "><?=$data_proses['jumlah_tindakan_verif']?></td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina_verif'])?></td>
							<td width="10%" style="text-align:right; "><?=formattanparupiah($data_proses['ahmp_verif'])?></td>
							<td width="15%" style="text-align:right; "><?=formattanparupiah($data_proses['biaya_lain_verif'])?></td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina_verif'])?></td>
							
						</tr>
						<tr class="even" >
							<td width="20%">&nbsp;&nbsp;RITL</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							<td width="10%" style="text-align:right; ">0</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							
						</tr>
						<tr class="odd" >
							<td width="20%">&nbsp;&nbsp;Subtotal</td>
							<td width="15%" style="text-align:right; "><?=$data_proses['jumlah_tindakan_verif']?></td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina_verif'])?></td>
							<td width="10%" style="text-align:right; ">0</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina_verif'])?></td>
							
						</tr>
						
					</tbody>
						
				</table>
				<br>
				<span id="paragraf_surat" >Dibulatkan menjadi <?=formatrupiah($data_proses['jumlah_tarif_ina_verif'])?></span><br>
				<br>
				<span id="paragraf_surat">Terbilang : “<?=terbilang($data_proses['jumlah_tarif_ina_verif'])?> Rupiah”
				</span>
				<br>
				<table id="tarif_tindakan" width="100%" style="border: 0.5px solid #BFBFBF;">
					<thead>
						
						<tr class="even">
							<th width="10%">Selisih</th>
							<th width="15%">Jumlah Pasien</th>
							<th width="20%">Tarif</th>
							<th width="10%">AHMP</th>
							<th width="15%">Biaya Lainnya</th>
							<th width="20%">Total</th>
						</tr>
					</thead>
					<tbody>
						<tr class="odd">
							<td colspan="6">&nbsp;&nbsp;RJTL</td>
						</tr>
						<tr class="even" >
							<td width="20%">&nbsp;&nbsp;Tidak Layak Administrasi</td>
							<td width="15%" style="text-align:right; "><?=$data_proses['jumlah_tindakan']-$data_proses['jumlah_tindakan_verif']?></td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina'] - $data_proses['jumlah_tarif_ina_verif'])?></td>
							<td width="10%" style="text-align:right; "><?=formattanparupiah($data_proses['ahmp'] - $data_proses['ahmp_verif'])?></td>
							<td width="15%" style="text-align:right; "><?=formattanparupiah($data_proses['biaya_lain'] - $data_proses['biaya_lain_verif'])?></td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina'] - $data_proses['jumlah_tarif_ina_verif'])?></td>
							
						</tr>
						<tr class="odd" >
							<td width="20%">&nbsp;&nbsp;Tidak Layak Software</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							<td width="10%" style="text-align:right; ">0</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							
						</tr>
						<tr class="even" >
							<td width="20%">&nbsp;&nbsp;Subtotal</td>
							<td width="15%" style="text-align:right; "><?=$data_proses['jumlah_tindakan']-$data_proses['jumlah_tindakan_verif']?></td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina'] - $data_proses['jumlah_tarif_ina_verif'])?></td>
							<td width="10%" style="text-align:right; "><?=formattanparupiah($data_proses['ahmp'] - $data_proses['ahmp_verif'])?></td>
							<td width="15%" style="text-align:right; "><?=formattanparupiah($data_proses['biaya_lain'] - $data_proses['biaya_lain_verif'])?></td>
							<td width="20%" style="text-align:right; "><?=formattanparupiah($data_proses['jumlah_tarif_ina'] - $data_proses['jumlah_tarif_ina_verif'])?></td>
							
						</tr>
						<tr class="odd" >
							<td width="10%" style="color:#EFEFEF;">&nbsp;&nbsp;Subtotal</td>
							<td width="15%" style="text-align:right;color:#EFEFEF;"><?=$data_proses['jumlah_tindakan']?></td>
							<td width="20%" style="text-align:right;color:#EFEFEF;"><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></td>
							<td width="10%" style="text-align:right;color:#EFEFEF;">-</td>
							<td width="15%" style="text-align:right;color:#EFEFEF;">-</td>
							<td width="20%" style="text-align:right;color:#EFEFEF;"><?=formattanparupiah($data_proses['jumlah_tarif_ina'])?></td>
							
						</tr>

						<tr class="even">
							<td colspan="6">&nbsp;&nbsp;RITL</td>
						</tr>
						<tr class="odd" >
							<td width="20%">&nbsp;&nbsp;Tidak Layak Administrasi</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							<td width="10%" style="text-align:right; ">0</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							
						</tr>
						<tr class="even" >
							<td width="20%">&nbsp;&nbsp;Tidak Layak Software</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							<td width="10%" style="text-align:right; ">0</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							
						</tr>
						<tr class="odd" >
							<td width="20%">&nbsp;&nbsp;Subtotal</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							<td width="10%" style="text-align:right; ">0</td>
							<td width="15%" style="text-align:right; ">0</td>
							<td width="20%" style="text-align:right; ">0</td>
							
						</tr>
						
					</tbody>
					
								
				</table>
				<br>
				<span id="paragraf_surat" >Adapun rincian pasien yang tidak layak administrasi RJTL terlampir.<br>Demikian Umpan Balik Verifikasi Klaim BPJS ini dibuat untuk digunakan sebagaimana mestinya. Atas perhatiannya kami sampaikan, terima kasih.</span>
				<br>
				<table width="100%">
					
					<tbody>
						<tr>
							<td width="50%" style="text-align:center;">
								<table style="text-align:center;">
									<tr>
										<td>Mengetahui,</td>
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
										<td height="20px"></td>
									</tr>
									
									<tr>
										<td style="text-align:center;"><?=$bpjs_1->nama?></td>
									</tr>
								</table>
							</td>
							<td width="50%" style="text-align:center;">
								<table style="text-align:center;">
									<tr>
										<td>Jakarta, <?=date('d F Y', strtotime($data_proses['tanggal']))?></td>
									</tr>
									<tr>
										<td>Petugas Verfikator Klaim BPJS</td>
									</tr>
									
									<tr>
										<td height="10px"></td>
									</tr>
									
									<tr>
										<td height="10px"></td>
									</tr>
									<tr>
										<td height="20px"></td>
									</tr>

									<tr>
										<td style="text-align:center;"><?=$bpjs_2->nama?></td>
									</tr>

								</table>
							</td>
						</tr>
						<tr>
							<td width="50%" colspan="2" style="text-align:center;">
								<table style="text-align:center;">
									<tr>
										<td>Diterima Oleh,</td>
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
										<td height="20px"></td>
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
