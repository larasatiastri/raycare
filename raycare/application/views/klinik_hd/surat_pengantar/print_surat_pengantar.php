<html>
	<body>
	<?php
		$nama_poli   = $pengantar['nama_rs_poliklinik'];
		$nama_dokter = $pengantar['nama_dokter'];
		
		$cabang_id   = $pengantar['cabang_id'];
		$cabang      = $this->cabang_m->get($cabang_id);

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
			
			$form_alamat      = $alamat_pasien['alamat'].$rt.$rw;
			$form_kel_alamat  = ($form_kel_alamat != '')? " Kel. ".$form_kel_alamat:'';
			$form_kec_alamat  = ($form_kec_alamat != '')?  " Kec. ".$form_kec_alamat:'';
			$form_kota_alamat = ($form_kota_alamat != '')?  " ".$form_kota_alamat:'';
        }		
	?>
		<div id="header">
			<div class="head">
				<div class="logo-a5">
					<img src="<?=$image_header?>">
					<div class="rs-code">
						<p>RS CODE : <?=$cabang->kode?></p>
					</div>
				</div>
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
				<div class="socmed" width="158px" height="70px" style="">
					<span><b>E.</b> <?=rtrim($data_email,', ')?></span>
					<br>
					<br>
					<span><b>Follow &amp; Visit</b></span><br>
					<span>fb : <?=$cabang_fb[0]['url']?></span><br>
					<span>twitter <?=$cabang_twitter[0]['url']?></span><br>
					<span><?=$cabang_website[0]['url']?></span><br>
				</div>
			</div>
		</div>
		<div id="body_surat">
			<div id="title">
				<b>SURAT PENGANTAR</b>	
			</div>
			<div id="no_surat"><span style="border-top:2px solid #000;">NO:<?=$pengantar['no_surat']?></span>
				
			</div>
			<br>
			<br>
			<div id="tanggal_surat">
				Jakarta, <?=date('d M Y', strtotime($pengantar['tanggal']));?>
			</div>
			<br>
			<br>
			<div id="tujuan_surat">
				Yth. <?=$nama_dokter?>
				<br>
				<?=$nama_poli?>
			</div>
			<br>
			<div id="isi_surat">
				Dengan hormat,
				<br>
				Mohon Penanganan dan Pemeriksaan terhadap pasien :
				<br>
					<table border=0 width="100%" style="border: 0px solid black; padding-left:10px;">
						<tr>
							<td width="20%">Nama</td>
							<td width="2%">: </td>
							<td width="65%"><?=$pasien['nama']?></td>

						</tr>
						<tr>
							<td width="22%">Tempat, Tanggal Lahir</td>
							<td width="2%">: </td>
							<td width="65%"><?=$pasien['tempat_lahir']?>, <?=date('d M Y', strtotime($pasien['tanggal_lahir']))?></td>
						</tr>
						<tr>
							<td width="20%">Alamat</td>
							<td width="2%">: </td>
							<td width="65%"><?=$form_alamat?></td>
						</tr>
						<?php if($form_kel_alamat != '')
						{
							?>
						<tr>
							<td width="22%" colspan="2"></td>
							<td width="65%"><?=$form_kel_alamat.$form_kec_alamat.$form_kota_alamat?></td>
						</tr>
						<?php
						}
						?>
						<tr>
							<td width="20%">Diagnosa</td>
							<td width="2%">: </td>
							<td width="65%">
								<table border=0 width="100%" style="border: 0px solid black">
									<?php
										foreach ($pengantar_diagnosa as $diagnosa) 
										{
									?>
											<tr><td>- <?=$diagnosa['name']?></td></tr>
									<?php
										}
									?>
								</table>
							</td>
						</tr>
					</table>
				<br>
				<span id="paragraf_surat">Nama diatas merupakan pasien Hemodialisa rutin di Klinik "Raycare" 2x seminggu, 
					<?=$pengantar['deskripsi']?>
					<br>
					<br>
					Atas perhatiannya kami ucapkan terimakasih.
				</span>
			</div>
			<br>
			<br>
			<div id="penutup_surat" style="margin-left:345px;width:380px;">
				<br>
				Klinik Hemodialisa Raycare
				<br>
				<br>
				<br>
				<br>
				<?=$dokter['nama']?>
			</div>
			<div style="margin-left:350px;width:380px;font-size:8px;">
				SIP :<?= $dokter['sip']?>
			</div>



		</div>
	</body>
</html>