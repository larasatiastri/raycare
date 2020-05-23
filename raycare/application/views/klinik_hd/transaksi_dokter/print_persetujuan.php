<html>
	<head>
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
	<link href="<?=base_url()?>assets/mb/global/css/jquery.signature.css" rel="stylesheet">

		<style type="text/css">

			body
			{
				font-size: 11px;
				font-family: Arial;
				text-align: justify;
			}
			.container{
				width: 800px;
			}

			table {
				border: none;
				font-size: 11px;
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
				border:0px solid #000;
			}

			#title {
				text-transform: uppercase;
				text-align: center;
				font-size: 13px;
				/*margin-left: 30px;*/
				/*text-decoration:overline;*/
			}

			#no_surat {
				text-align: center;
				/*margin-left: 30px;*/
				text-decoration:overline;
			}

			.title-child{
				font-size: 13px;
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
				width: 180px !important;
				/*height: 20px;*/
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

			.title{
				font-size: 13px;
				text-align: center;

			}

			.title p{
				margin-left: -200px;

			}

			.term{
				font-size: 11px;
				font-style: italic;
			}
			
			.label {
			    font-size: 11px;
			    font-family: Arial, Helvetica, sans-serif !important;
			    padding: 1px 4px 1px 4px;
			}

			#paragraf_surat{
				text-align: justify;
			}

			#tanggal_surat{
				text-align: right;
			}

			#penutup_surat{
				margin-left: 1000px;
				text-align: right;
			}

			.signate-right{
				width: 152px;
				height: 100px;
				background-color: #FFF;
				float: right;
				text-align: center;
			}

			#tujuan_surat{
				text-align: justify;
			}

			#isi_surat{
				text-align: justify;
				margin-left: 10px;
			}

			.line1 {
		        width: 112px;
		        height: 27px;
		        border-bottom: 1px solid red;
		        -webkit-transform:
		            translateY(-20px)
		            translateX(5px)
		            rotate(27deg); 
		        position: absolute;
		        /* top: -20px; */
		    }
		    .line2 {
		        width: 112px;
		        height: 27px;
		        border-bottom: 1px solid green;
		        -webkit-transform:
		            translateY(20px)
		            translateX(5px)
		            rotate(-26deg);
		        position: absolute;
		        top: -33px;
		        left: -13px;
		    }

		   .stempel{
			width:200px !important;
			margin-right: -200px !important;
			padding:1px 1px 1px 1px;
			/*margin-top: 575px;*/
			margin-left: 240px;
			height:8px !important;
			position:relative !important;
			bottom:-300 !important;
			right:0 !important;
			opacity: 0.5;
			z-index: -1;
			}
			.stempel-bed{
				width: 70px;
				height: 70px;
				padding-left: 608px;
				/*background-color: yellow;*/
				position: relative !important;
			}

			.bed{
				color: red;
				font-size: 66px;
				width:70px !important;
				/*margin-right: -200px !important;*/
				padding:1px 1px 1px 1px;
				/*margin-top: 75px;
				margin-left: 40px;
*/				height:70px !important;
				position:relative !important;
				bottom:0 !important;
				right:0 !important;
				opacity: 0.5;
				z-index: -1;
				/*border:8px solid red;
				border-radius: 50%;*/
			}

			.kbw-signature { width: 250px; height: 150px; border: 1px;}

		</style>
	</head>
	<body>
	<div class="container">
	<?php
		$cabang_id      = $this->session->userdata('cabang_id');
		$cabang         = $this->cabang_m->get($cabang_id);
		
		$cabang_alamat  = $this->cabang_alamat_m->get_by(array('cabang_id' => $cabang_id, 'is_primary' => 1, 'is_active' => 1));
		$cabang_alamat  = object_to_array($cabang_alamat);
		$cabang_telepon = $this->cabang_telepon_m->get_by(array('cabang_id' => $cabang_id,'is_active' => 1, 'subjek_id' => 8));
		$cabang_telepon = object_to_array($cabang_telepon);
		$cabang_fax     = $this->cabang_telepon_m->get_by(array('cabang_id' => $cabang_id,'is_active' => 1, 'subjek_id' => 9));
		$cabang_fax     = object_to_array($cabang_fax);
		$cabang_email   = $this->cabang_sosmed_m->get_by(array('tipe' => 1,'cabang_id' => $cabang_id,'is_active' => 1));
		$cabang_email   = object_to_array($cabang_email);
		$cabang_fb      = $this->cabang_sosmed_m->get_by(array('tipe' => 3,'cabang_id' => $cabang_id,'is_active' => 1));
		$cabang_fb      = object_to_array($cabang_fb);
		$cabang_twitter = $this->cabang_sosmed_m->get_by(array('tipe' => 4,'cabang_id' => $cabang_id,'is_active' => 1));
		$cabang_twitter = object_to_array($cabang_twitter);
		$cabang_website = $this->cabang_sosmed_m->get_by(array('tipe' => 2,'cabang_id' => $cabang_id,'is_active' => 1));
		$cabang_website = object_to_array($cabang_website);

		$data_email = '';
		foreach ($cabang_email as $email) 
		{
			$data_email .= $email['url'].', ';
		}
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real')) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real'))) 
        {
            $image_header = config_item('base_dir').config_item('site_logo_real');
        }
        else 
        {
            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."logo/logo-real.png";
        }

        if($alamat_pasien != '')
        {
        	$rt = '';
			$rw = '';
			if ($alamat_pasien['rt_rw'] != NULL) 
			{
				$rt_rw = explode('_', $alamat_pasien['rt_rw']);
				$rt = " RT. ".$rt_rw[0];
				$rw = " RW. ".$rt_rw[1];
			}
			
			$form_alamat = $alamat_pasien['alamat'].$rt.$rw;
			$form_kel_alamat = ($form_kel_alamat != '')? " Kel. ".$form_kel_alamat:'';
			$form_kec_alamat = ($form_kec_alamat != '')?  " Kec. ".$form_kec_alamat:'';
			$form_kota_alamat = ($form_kota_alamat != '')?  " ".$form_kota_alamat:'';
        }
   
		$gender = $pasien['gender'];
	?>
		<div id="header">
			<div class="head">
				<div class="logo-a4">
					<img src="<?=$image_header?>">
					<div class="rs-code">
						<p>RS CODE : <?=$cabang->kode?></p>
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
		<br>
		<?php 
	        $stempel = config_item('base_dir')."cloud/".config_item('site_dir')."/logo/approved.png";
	        ?>
	        <div class="stempel-bed">
	        	<div class="bed" align="center"><b><?=$assesment['machine_no']?></b></div>
	        </div>
	    
		<div class="stempel"><img src="<?=$stempel?>" style="height:160px;"></div>	

		<div id="body_surat">
			<div id="title">
				<b>FORMULIR PERNYATAAN PERSETUJUAN TINDAKAN MEDIS</b>
			</div>

			<br>
			<div id="isi_surat">
			<table border=0 width="100%">
				<tr>
					<td width="100%" colspan="7">Yang bertanda tangan dibawah ini :</td>
				</tr>
				<tr>
					<td width="15%" style="vertical-align:top;">Nama</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="35%" style="vertical-align:top;"><?=$pj['nama']?></td>
					<td width="1%" style="vertical-align:top;"></td>
					<td width="15%" style="vertical-align:top;">No. KTP</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="27%" style="vertical-align:top;"><?=$pj['no_ktp']?></td>
				</tr>
				<tr>
					<td width="15%" style="vertical-align:top;">Alamat</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="35%" style="vertical-align:top;"><?=ucwords(strtolower($pj_alamat['alamat'])).' '.ucwords(strtolower($form_kel_alamat_pj)).' '.ucwords(strtolower($form_kec_alamat_pj)).' '.ucwords(strtolower($form_kota_alamat_pj))?></td>
					<td width="1%" style="vertical-align:top;"></td>
					<td width="15%" style="vertical-align:top;">Telepon</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="27%" style="vertical-align:top;"><?=$pj_telepon['nomor']?></td>
				</tr>
				<tr>
					<td width="57%" colspan="4"></td>
					<td width="15%" style="vertical-align:top;">Status</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="27%" style="vertical-align:top;"><?=$status?></td>
				</tr>
				<tr>
					<td width="100%" colspan="7" height="7px"></td>
				</tr>
				<tr>
					<td width="100%" colspan="7">Dengan ini menyatakan sesungguhnya bahwa saya telah memberikan :</td>
				</tr>
				<tr>
					<td width="100%" colspan="7" height="7px"></td>
				</tr>
				<tr>
					<td width="100%" colspan="7" style="text-align:center; font-size:14px;"><b>"P E R S E T U J U A N"</b></td>
				</tr>
				<tr>
					<td width="100%" colspan="7" height="7px"></td>
				</tr>
				<tr>
					<td width="100%" colspan="7">Untuk melakukan tindakan medis berupa : Haemodialisis (Terapi Ginjal)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*dengan Anestesi Umum / Lokal</td>
				</tr>
				<tr>
					<td width="100%" colspan="7">Terhadap pasien dengan data sebagai berikut :</td>
				</tr>
				<tr>
					<td width="15%" style="vertical-align:top;">Nama Lengkap</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="35%" style="vertical-align:top;"><?=$pasien['nama']?></td>
					<td width="1%" style="vertical-align:top;"></td>
					<td width="15%" style="vertical-align:top;">Umur</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="27%" style="vertical-align:top;"><?=$umur_pasien.',&nbsp;'.$gender?></td>
				</tr>
				<tr>
					<td width="15%" style="vertical-align:top;">No. Rekam Medis</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="35%" style="vertical-align:top;"><?=$pasien['no_member']?></td>
					<td width="1%" style="vertical-align:top;"></td>
					<td width="15%" style="vertical-align:top;">Alamat</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="27%" style="vertical-align:top;"><?=ucwords(strtolower($alamat_pasien['alamat'])).' '.ucwords(strtolower($form_kel_alamat)).' '.ucwords(strtolower($form_kec_alamat)).' '.ucwords(strtolower($form_kota_alamat))?></td>
				</tr>
				<tr>
					<td width="15%" style="vertical-align:top;">No. BPJS</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="35%" style="vertical-align:top;"><?=$pasien_penjamin['no_kartu']?></td>
					<td width="1%" style="vertical-align:top;"></td>
					<td width="15%" style="vertical-align:top;">Telepon</td>
					<td width="1%" style="vertical-align:top;">:</td>
					<td width="27%" style="vertical-align:top;"><?=$telepon_pasien['nomor']?></td>
				</tr>
				<tr>
					<td width="100%" colspan="7" height="7px"></td>
				</tr>
				<tr>
					<td width="100%" colspan="7">Untuk ini saya menyatakan pula :</td>
				</tr>
				
			</table>
			<table border=0 width="100%">
				<tr>
					<td width="2%" style="vertical-align:top;">a.</td>
					<td width="2%" style="vertical-align:top;"></td>
					<td width="90%" colspan="5" style="text-align:justify;">Bahwa telah dijelaskan kepada saya mengenai diagnosis, tata cara, tujuan resiko dan komplikasi yang mungkin timbul selama dan setelah tindakan medis tsb, alternatif tindakan lain dan resikonya serta prognosa tindakan yang dilakukan.</td>
				</tr>
				<tr>
					<td width="2%" style="vertical-align:top;">b.</td>
					<td width="2%" style="vertical-align:top;"></td>
					<td width="90%" colspan="5" style="text-align:justify;">Bahwa apa yang telah diterangkan oleh dokter kepada saya telah saya pahami / mengerti sepenuhnya dan kepada saya telah diberikan kesempatan untuk bertanya.</td>
				</tr>
				<tr>
					<td width="2%" style="vertical-align:top;">c.</td>
					<td width="2%" style="vertical-align:top;"></td>
					<td width="90%" colspan="5" style="text-align:justify;">Bahwa tindakan medis tersebut juga menggunakan obat - obat dan bahan - bahan kontras yang diperlukan guna memperlancar tindakan.</td>
				</tr>
				<tr>
					<td width="2%" style="vertical-align:top;">d.</td>
					<td width="2%" style="vertical-align:top;"></td>
					<td width="90%" colspan="5" style="text-align:justify;">Bahwa saya juga memberikan persetujuan untuk pemberian Anestesi umum dan lokal untuk dapat dilakukan tindakan lokal.</td>
				</tr>
				<tr>
					<td width="2%" style="vertical-align:top;">e.</td>
					<td width="2%" style="vertical-align:top;"></td>
					<td width="90%" colspan="5" style="text-align:justify;">Bahwa saya juga memberikan persetujuan untuk tindakan / prosedur tambahan dan pengobatan lebih lanjut, apabila pada waktu tindakan medis pertama diperlukan demi keselamatan jiwa.</td>
				</tr>
				<tr>
					<td width="2%" style="vertical-align:top;">f.</td>
					<td width="2%" style="vertical-align:top;"></td>
					<td width="90%" colspan="5" style="text-align:justify;">Bahwa walaupun tindakan medis diatas telah dilaksanakan dengan penanganan yang Profesional, tetap ada kemungkinan untuk tidak memberikan hasil seperti yang diharapkan.</td>
				</tr>
				<tr>
					<td width="2%" style="vertical-align:top;">g.</td>
					<td width="2%" style="vertical-align:top;"></td>
					<td width="90%" colspan="5" style="text-align:justify;">Bahwa setiap penggunaan obat / bahan kimia dapat menimbulkan reaksi alergi yang tidak dapat diduga sebelumnya bersifat reaksi ringan sampai berat / mengancam jiwa.</td>
				</tr>
				<tr>
					<td width="2%" style="vertical-align:top;">h.</td>
					<td width="2%" style="vertical-align:top;"></td>
					<td width="90%" colspan="5" style="text-align:justify;">Bahwa setiap penggunaan obat berupa vitamin merupakan suatu obat tambahan, yang dalam penggunaanya atas permintaan pasien sendiri serta tanpa paksaan dari pihak manapun.</td>
				</tr>
				<tr>
					<td width="100%" colspan="7" height="5px"></td>
				</tr>
				<tr>
					<td width="100%" colspan="7" style="text-align:justify;">Demikian pernyataan ini saya buat dengan penuh kesadaran dan tanpa paksaan serta tidak akan menuntut Dokter dan atau klinik serta petugas kesehatan lainnya baik secara perdata maupun pidana.</td>
				</tr>
				<tr>
					<td width="100%" colspan="7" height="5px"></td>
				</tr>
			</table>
			
				<div style="float:left; width: 220px; height:50px; border:0px solid #000;">

					<table border="1" width="100%" style="float:left;border:1px solid #000 !important;margin-bottom:20px;">
						<?php
							$bp = explode('_', $assesment['blood_preasure']);
						?>
						<tr>
							<td><strong>BB pre HD</strong></td>
							<td>:</td>
							<td><?=$tindakan_hd['berat_awal']?> Kg</td>
						</tr>
						<tr>
							<td><strong>UFG</strong></td>
							<td>:</td>
							<td><?=$assesment['uf_goal']?> Liter(s)</td>
						</tr>
						<tr>
							<td><strong>QB</strong></td>
							<td>:</td>
							<td><?=$assesment['quick_of_blood']?> ml/Hour</td>
						</tr>
						<tr>
							<td><strong>TD pre HD</strong></td>
							<td>:</td>
							<td><?=$bp[0].' / '.$bp[1]?></td>
						</tr>
					</table>
					</br>
					<br>
			
					<table border="1" width="100%" style="border:1px solid #000 !important;margin-top:10px;">
						<tr>
							<td colspan="3" align="center"><b>PERSETUJUAN TRANSFUSI</b></td>
						</tr>
						<tr>
							<td>Golongan Darah</td>
							<td>:</td>
							<td>......................................</td>
						</tr>
						<tr>
							<td>Serial Number</td>
							<td>:</td>
							<td>1......................................</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>2......................................</td>
						</tr>
						<tr>
							<td>Ukuran</td>
							<td>:</td>
							<td>1......................................</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>2......................................</td>
						</tr>
						<tr>
							<td>Exp Date</td>
							<td>:</td>
							<td>1......................................</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>2......................................</td>
						</tr>
						<tr>
							<td align="center"><br><br><br><br>( <?=$pasien['nama']?> )<br>Pasien</td>
							<td></td>
							<td align="center"><br><br><br><br>(....................)<br>Perawat</td>
						</tr>

					</table>
					<div style="font-size:10px; font-style:italic;"><b>* :</b>&nbsp;Jika dibutuhkan anestesi</div>




				</div>
				<div style="float:left; width: 472px; height:50px; border:0px solid #000; margin-left:10px;">
						<?php
							$title = 'Pasien';
							if($status !== 'Pasien')
							{
								if($status === 'Suami Pasien' || $status === 'Istri Pasien' || $status === 'Anak Pasien' || $status === 'Ayah Pasien' || $status === 'Ibu Pasien')
								{
									$title = 'Keluarga';
								}
								else
								{
									$title = 'Wali';
								}
							}
						?>
					<div style="float:left; width:472px;text-align:right;"><b>Jakarta, Hari : </b><?=getdayname(date('D', strtotime($tindakan_hd['tanggal'])))?><b> Tgl:</b> <?=date('d M Y', strtotime($tindakan_hd['tanggal']))?> <b>Jam :</b> <?=date('H.i', strtotime($tindakan_hd['tanggal']))?><b> WIB</b></div>
					<div style="float:left; width:236px;height:220px;text-align:center;padding:10px 0 0 0;border-bottom: 2px dotted #989898;">Disetujui oleh<br>( <?=$title?> )<br><div id="sig_setuju"></div><textarea name="signature_setuju" id="signature_setuju" rows="5" cols="50" readonly hidden value="<?=$tindakan_hd['ttd_setuju']?>"><?=$tindakan_hd['ttd_setuju']?></textarea><br>( <?= $pj['nama']?> )</div>
					<div style="float:left; width:148px;height:220px;text-align:center;padding:10px 0 0 88px;border-bottom: 2px dotted #989898;">Saksi I<br>(Keluarga Pasien)<br><div id="sig_saksi"></div><textarea name="signature_saksi" id="signature_saksi" rows="5" cols="50" readonly hidden value="<?=$tindakan_hd['ttd_saksi']?>"><?=$tindakan_hd['ttd_saksi']?></textarea><br>Tanda Tangan & Nama Jelas</div>
					<div style="float:left; width:236px;height:220px;text-align:center;padding:10px 0 0 0;border-bottom: 2px dotted #989898;">Dokter Yang Melakukan Tindakan Medis/<br>Dokter yang memberikan penjelasan<br><div id="sig_dokter_medis"></div><textarea name="signature_dokter_medis" id="signature_dokter_medis" rows="5" cols="50" readonly hidden value="<?=$tindakan_hd['ttd_dokter_medis']?>"><?=$tindakan_hd['ttd_dokter_medis']?></textarea><br>( <?=$dokter['nama']?> )</div>
					<div style="float:left; width:148px;height:220px;text-align:center;padding:10px 0 0 88px;border-bottom: 2px dotted #989898;">Saksi II<br>(Petugas Kesehatan)<br><div id="sig_saksi2"></div><textarea name="signature_saksi2" id="signature_saksi2" rows="5" cols="50" readonly hidden value="<?=$tindakan_hd['ttd_saksi2']?>"><?=$tindakan_hd['ttd_saksi2']?></textarea><br>Tanda Tangan & Nama Jelas</div>
					<div style="float:left; width:236px;height:220px;text-align:center;padding:10px 0 0 0;">Dokter Yang Melakukan Anestesi*<br><div id="sig_dokter_anastesi"></div><textarea name="signature_dokter_anastesi" id="signature_dokter_anastesi" rows="5" cols="50" readonly hidden value="<?=$tindakan_hd['ttd_dokter_anastesi']?>"><?=$tindakan_hd['ttd_dokter_anastesi']?></textarea><br>Tanda Tangan & Nama Jelas</div>
					
				</div>
			</div>
			

			<div class="signate">
				
			</div>
		</div>
		</div>
	</body>
</html>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="<?=base_url()?>assets/mb/global/js/signature/jquery.signature.min.js"></script>
<script>
$(document).ready(function(){

	//alert('test');
	$('#sig_setuju').signature();
    $('#sig_setuju').signature('draw', $('textarea#signature_setuju').val()); 

    $('#sig_saksi').signature();
    $('#sig_saksi').signature('draw', $('textarea#signature_saksi').val()); 

    $('#sig_dokter_medis').signature();
    $('#sig_dokter_medis').signature('draw', $('textarea#signature_dokter_medis').val()); 

    $('#sig_saksi2').signature();
    $('#sig_saksi2').signature('draw', $('textarea#signature_saksi2').val());

    $('#sig_dokter_anastesi').signature();
    $('#sig_dokter_anastesi').signature('draw', $('textarea#signature_dokter_anastesi').val()); 
});
</script>