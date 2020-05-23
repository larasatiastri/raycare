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
			}

			table, td, th {
			    border: 0px solid green;
			    border-collapse:collapse; 
			    
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
        	$no_trans    = '____________________';
        }
        if($data_kwitansi['tipe_bayar'] == 3)
        {
        	$cross_trf = '&nbsp;X&nbsp;';
        	$no_check    = '____________________';
        	$no_trans = $data_kwitansi['no_check_transfer'];
        }

        
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
					<td width="40%" style="text-align: right;">KWITANSI No. <b><?=$data_kwitansi['no_kwitansi']?></b></td>
				</tr>
				<tr>
					<td width="60%" colspan="5" height="10px"></td>
				</tr>
				<tr>
					<td width="25%">Tanggal</td>
					<td width="2%">:</td>
					<td width="83%" colspan="3" valign="top" style="text-align: left;"><?=date('d F Y', strtotime($data_proses['tanggal']))?></td>
				</tr>
				<tr>
					<td width="60%" colspan="5" height="15px"></td>
				</tr>
				<tr>
					<td width="25%" valign="top">Jumlah Terima<br><br>Terbilang</td>
					<td width="2%" valign="top">:<br><br>:</td>
					<td width="24%" valign="top" style="text-align: left;"><b><?=formatrupiahnol($data_kwitansi['jumlah_terima'])?></b><br><br>#<?=terbilang($data_kwitansi['jumlah_terima'])?> Rupiah#</td>
					<td width="15%"></td>
					<td width="15px">[<?=$cross_cash?>] Cash <br><br> [<?=$cross_check?>] Check, No. <b><?=$no_check?></b><br><br>[<?=$cross_trf?>] Transfer, No. <b><?=$no_trans?></b></td>
				</tr>
				<tr>
					<td width="60%" colspan="5" height="6px"></td>
				</tr>
				<tr>
					<td width="25%" valign="bottom">Sudah Diterima Dari</td>
					<td width="2%" valign="bottom">:</td>
					<td width="83%" colspan="3" valign="bottom" style="text-align: left;"><b><?=$data_kwitansi['diterima_dari']?></b></td>
				</tr>
				<tr>
					<td width="60%" colspan="5" height="6px"></td>
				</tr>
				<tr>
					<td width="25%" valign="bottom">Uang Pembayaran</td>
					<td width="2%" valign="bottom">:</td>
					<td width="83%" colspan="3" valign="bottom" style="text-align: left;"><b><?=$data_kwitansi['deskripsi']?></b></td>
				</tr>
				<tr>
					<td width="60%" colspan="5" height="6px"></td>
				</tr>
				<tr>
					<td width="25%" valign="top" style="border-bottom: 1px solid #eaeaea; border-right: 1px solid #eaeaea; background-color:#eaeaea"><br></td>
					<td width="2%" valign="top" style="border-bottom: 1px solid #eaeaea; background-color:#eaeaea"></td>
					<td width="83%" colspan="3"  valign="top" style="text-align: right;">Jakarta, <?=date('d F Y', strtotime($data_proses['tanggal']))?></td>
				</tr>
				<tr>
					<td width="25%" valign="top" style="border-bottom: 1px solid #eaeaea; border-right: 1px solid #eaeaea; background-color:#eaeaea; padding-left:5px;">Pembayaran ditransfer ke   :</td>
					<td width="2%" valign="top" style="border-bottom: 1px solid #eaeaea; background-color:#eaeaea"></td>
					<td width="83%" colspan="3" valign="top" style="text-align: left;"></td>
				</tr>
				<tr>
					<td width="25%" valign="top" style="border-bottom: 1px solid #eaeaea; border-right: 1px solid #eaeaea; background-color:#eaeaea; padding-left:5px;"><?=$bank['nob']?></td>
					<td width="2%"  valign="top" style="border-bottom: 1px solid #eaeaea; background-color:#eaeaea"></td>
					<td width="83%" colspan="3"  valign="top" style="text-align: right; padding-right:50px; font-size:5px;">materai</td>
				</tr>
				
				<tr>
					<td width="25%" valign="top" style="border-bottom: 1px solid #eaeaea; border-right: 1px solid #eaeaea; background-color:#eaeaea; padding-left:5px;"> No. Rek : <?=$bank['acc_number']?></td>
					<td width="2%" valign="top" style="border-bottom: 1px solid #eaeaea; background-color:#eaeaea"></td>
					<td width="83%" colspan="3"  valign="top" style="text-align: left;"></td>
				</tr>
				<tr>
					<td width="25%" valign="top" style="border-bottom: 1px solid #eaeaea; border-right: 1px solid #eaeaea; background-color:#eaeaea; padding-left:5px; font-size:11px;">A/N <?=$data_kwitansi['dibayar_ke']?></td>
					<td width="2%" valign="top" style="border-bottom: 1px solid #eaeaea; background-color:#eaeaea"></td>
					<td width="83%"  colspan="3" valign="top" style="text-align: right; padding-right:25px;"></td>
				</tr>
				<tr>
					<td width="25%" valign="top" style="border-bottom: 1px solid #eaeaea; border-right: 1px solid #eaeaea; background-color:#eaeaea; color:#eaeaea; padding-left:5px;">Test</td>
					<td width="2%" valign="top" style="border-bottom: 1px solid #eaeaea; background-color:#eaeaea"></td>
					<td width="83%" colspan="3" valign="top" style="text-align: right; padding-right:25px;"></td>
				</tr>
				<tr>
					<td width="25%" valign="top" style="border-bottom: 1px solid #eaeaea; border-right: 1px solid #eaeaea; background-color:#eaeaea; color:#eaeaea; padding-left:5px;">Test</td>
					<td width="2%" valign="top" style="border-bottom: 1px solid #eaeaea; background-color:#eaeaea"></td>
					<td width="83%" colspan="3" valign="top" style="text-align: right; padding-right:25px;"></td>
				</tr1>
				<tr>
					<td width="25%" valign="top" style="border-bottom: 1px solid #eaeaea; border-right: 1px solid #eaeaea; background-color:#eaeaea; color:#eaeaea; padding-left:5px;">Test</td>
					<td width="2%" valign="top" style="border-bottom: 1px solid #eaeaea; background-color:#eaeaea"></td>
					<td width="83%" colspan="3" valign="top" style="text-align: right; padding-right:25px;"></td>
				</tr1>
				<tr>
					<td width="25%" valign="top" style="border-bottom: 1px solid #eaeaea; border-right: 1px solid #eaeaea; background-color:#eaeaea; color:#eaeaea; padding-left:5px;">Test</td>
					<td width="2%" valign="top" style="border-bottom: 1px solid #eaeaea; background-color:#eaeaea"></td>
					<td width="83%" colspan="3" valign="top" style="text-align: right; padding-right:25px;"></td>
				</tr1>
				
				<tr>
					<td width="25%" valign="top"></td>
					<td width="70%" colspan="3" valign="top"></td>
					<td width="5%"  valign="top" style="text-align: right; padding-right:27px;"><b><u>dr. Denta Pentiharwati</u></b></td>
				</tr>
				<tr>
					<td width="25%" valign="top"></td>
					<td width="70%" colspan="3" valign="top"></td>
					<td width="5%"  valign="top" style="text-align: right; padding-right:29px;"><b>Direktur</b></td>
				</tr>
				
			</table>
		</div>
		
	</body>
</html>
