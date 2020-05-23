<html>
	<head>
		<style type="text/css">

			body
			{
				font-size: 12px;
				font-family: Calibri;
				text-align: justify;
			}

			table {
				border: none;
				font-size: 12px;
				margin: 0;
				padding: 0;
			}

			table, td, th {
			    border: 0px solid green;
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

			#body_surat{
				margin-left: 10px;
				padding-right: 50px;
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

        $bank = $this->bank_m->get($data_kwitansi['bank_id']);
        $bank = object_to_array($bank);

		$cross_cash  = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$cross_check = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$cross_trf   = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$no_check    = '____________________';
		$no_trans    = '____________________';

        if($data_kwitansi['tipe_bayar'] == 1)
        {
        	$cross_cash = '&nbsp;X&nbsp;';
        }
        if($data_kwitansi['tipe_bayar'] == 2)
        {
        	$cross_check = '&nbsp;X&nbsp;';
        	$no_check = $data_kwitansi['no_check_transfer'];
        	$no_trans = '';
        }
        if($data_kwitansi['tipe_bayar'] == 3)
        {
        	$cross_trf = '&nbsp;X&nbsp;';
        	$no_check = '';
        	$no_trans = $data_kwitansi['no_check_transfer'];
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
		<div id="body_surat">
			<div id="title">
				<b>SURAT PERNYATAAN</b>				
			</div>
			<div id="no_surat"><span style="border-top:2px solid #000;">NO:<?=$data_proses['no_surat']?></span>
				
			</div>
			<br>
			<br>
			<div id="isi_surat">
				Dengan ini saya yang bertandatangan dibawah ini :
				<br>
				<br>
				
					<table border=0 width="100%" style="border: 0px solid black; padding-left:30px;">
						<tr>
							<td width="25%">Nama</td>
							<td width="2%">: </td>
							<td width="65%"><?=$user->nama?></td>

						</tr>
						<tr>
							<td width="25%">Jabatan</td>
							<td width="2%">: </td>
							<td width="65%">Direktur Klinik</td>
						</tr>
						<tr>
							<td width="25%">Bidang Usaha</td>
							<td width="2%">: </td>
							<td width="65%">Klinik Hemodialisis</td>
						</tr>
					
						<tr>
							<td width="25%">Alamat</td>
							<td width="2%">: </td>
							<td width="65%">Jl. Peta Selatan Blok 6</td>
						</tr>
						<tr>
							<td width="27%" colspan="2"></td>
							<td width="65%">Kalideres, Jakarta Barat 11840 - Indonesia</td>
						</tr>
							
					</table>
				<br>
				
				<span id="paragraf_surat">Menyatakan bahwa pembayaran klaim BPJS untuk bulan pelayanan <?=date('F Y', strtotime($data_proses['periode_tindakan']))?> berdasarkan berita acara hasil verifikasi BPJS Kesehatan adalah sebagai berikut :
				</span>
				<br>
				<br>
				<table border=0 width="100%" style="border: 0px solid black; padding-left:30px;">
					<tr>
						<td width="40%"><ul><li>Jumlah Kasus KJS Rawat Jalan HD</li></ul></td>
						<td width="2%">: </td>
						<td width="30%"><?=$data_proses['jumlah_tindakan_verif']?></td>

					</tr>
					<tr>
						<td width="32%" colspan="2"></td>
						<td width="65%">[ <?=terbilang($data_proses['jumlah_tindakan_verif'])?> ]</td>
					</tr>
					<tr>
						<td width="40%"><ul><li>Jumlah Klaim</li></ul></td>
						<td width="2%">: </td>
						<td width="30%"><?=formatrupiah($data_proses['jumlah_tarif_ina_verif'])?></td>
					</tr>
					<tr>
						<td width="32%" colspan="2"></td>
						<td width="65%">[ <?=terbilang($data_proses['jumlah_tarif_ina_verif'])?> Rupiah ]</td>
					</tr>	
				</table>
				<br>
				<br>
				<span id="paragraf_surat">Demikian surat pernyataan ini kami buat dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.
				</span>
				<br>
				<br>

			</div>
			<div id="penutup_surat">
				Jakarta, <?=date('d F Y', strtotime($data_proses['tanggal']))?>
				<br>
				Klinik Raycare
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<u><?=$user->nama?></u><br>
				Direktur	
			</div>
			
		</div>
		
	</body>
</html>
