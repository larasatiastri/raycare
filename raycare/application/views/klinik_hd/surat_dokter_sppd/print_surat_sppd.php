<html>
	
	<body>
		<?php
			$cabang_id = $this->session->userdata('cabang_id');
			$cabang    = $this->cabang_m->get($cabang_id);

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

	        $tanda_tangan_issued = config_item('base_dir')."cloud/".config_item('site_dir')."logo/stempel.png";
			$user = $this->user_m->get($dokter_setuju['id']);
			$membuat = '';
			if(count($user) != 0){
				$membuat = $user->nama;
				$tanda_tangan_issued = config_item('base_dir')."cloud/".config_item('site_dir')."pages/master/user/images/".$user->username."/".$user->url_sign;
			}else{
				$membuat = 'Nico';
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
					<span><b>P.</b> <?=$cabang_telepon[0]['nomor']?></span>
					<br>
					<span><b>E.</b> <?=rtrim($data_email,', ')?></span>
					<!-- <br>
					<span><b>F.</b> <?=$cabang_fax[0]['nomor']?></span> -->
				</div>
				<div class="socmed" width="158px" height="70px" style="">
					<span><b>Follow &amp; Visit</b></span><br>
					<span>fb : <?=$cabang_fb[0]['url']?></span><br>
					<span>twitter <?=$cabang_twitter[0]['url']?></span><br>
					<span><?=$cabang_website[0]['url']?></span><br>
				</div>
			</div>
		</div>
		<div id="body_surat">

			<br>
			<br>
			<div id="isi_surat">
				Dengan hormat,
				<br>
				<br>
				Mohon penanganan tindakan Hemodialisis terhadap pasien :
					<table border=0 width="100%" style="border: 0px solid black; padding-left:10px;">
						<tr>
							<td width="20%">Nama</td>
							<td width="2%">: </td>
							<td width="65%"><?=$pasien['nama']?></td>

						</tr>
						<tr>
							<td width="20%">Umur</td>
							<td width="2%">: </td>
							<td width="65%"><?=$umur_pasien?></td>
						</tr>
						<tr>
							<td width="20%">Alamat</td>
							<td width="2%">: </td>
							<td width="65%"><?=$alamat_pasien['alamat']?></td>
						</tr>
						<tr>
							<td width="22%" colspan="2"></td>
							<td width="65%"><?=$form_kel_alamat.$form_kec_alamat.$form_kota_alamat?></td>
						</tr>	
						<tr>
							<td width="20%">Diagnosis</td>
							<td width="2%">: </td>
							<td width="65%"><?=$data_surat_sppd['diagnosa1']?></td>
						</tr>	
						<?php
							if($data_surat_sppd['diagnosa2'] != '' && $data_surat_sppd['diagnosa2'] != NULL && $data_surat_sppd['diagnosa2'] != '-' ){
							?>
						<tr>
							<td width="20%">Diagnosis Tambahan</td>
							<td width="2%">: </td>
							<td width="65%"><?=$data_surat_sppd['diagnosa2']?></td>
						</tr>
							<?php
							}
						?>
					</table>
				<br>
				<br>
				<span id="paragraf_surat">Pasien merupakan Hemodialisis rutin 2x seminggu, dan dibutuhkan tindakan tambahan hemodialisis 3x seminggu untuk pasien karena&nbsp;
				<?=$data_surat_sppd['alasan']?>
				<br>
				
				</span>
			</div>
			<br>
			<br>
			<div>
				Jakarta, <?=date('d M Y', strtotime($data_surat_sppd['tanggal']))?>
				<br>
				Hormat kami,
				<br>
				<br>
				<img style="height:75px;" src="<?=$tanda_tangan_issued?>">
				<br>
				<?=$dokter_setuju['nama']?>
			</div>
			<div >
				(SIP :<?= $dokter_setuju['sip']?>)
			</div>
		</div>
	</body>
</html>