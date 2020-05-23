<html>
	<body>
		<?php 
			$cabang_id      = $data_sehat['cabang_id'];
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

			if (file_exists(FCPATH.config_item('site_logo_real')) && is_file(FCPATH.config_item('site_logo_real'))) 
	        {
	            $image_header = base_url().config_item('site_logo_real');
	        }
	        else 
	        {
	            $image_header = base_url()."assets/mb/global/image/logo/logo-real.png";
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
					<span><b>Follow &amp; Visit</b></span><br>
					<span>fb : <?=$cabang_fb[0]['url']?></span><br>
					<span>twitter <?=$cabang_twitter[0]['url']?></span><br>
					<span><?=$cabang_website[0]['url']?></span><br>
				</div>
			</div>
		</div>
		<div id="body_surat">
			<div id="title">
				<b>SURAT KETERANGAN SEHAT</b>
			</div>
			<div id="no_surat"><span style="border-top:2px solid #000;">NO:<?=$data_sehat['no_surat']?></span>
				
			</div>
			<br>
			<br>
			
			<div id="isi_surat">
				Yang bertanda tangan dibawah ini adalah dokter Klinik Hemodialisis Raycare, cabang Kalideres Jakarta Barat, menerangkan bahwa :
				<br>
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
							<td width="20%">Jenis Kelamin</td>
							<td width="2%">: </td>
							<td width="65%"><?=($pasien['gender']=='L')?'Laki - laki':'Perempuan';?></td>
						</tr>
						<tr>
							<td width="20%">Pekerjaan</td>
							<td width="2%">: </td>
							<td width="65%"><?=$nama_pekerjaan?></td>
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
					</table>
				<br>
				<br>
				<span id="paragraf_surat">Setelah melalui pemeriksaan, dinyatakan <b>SEHAT</b>
				<br>
				Demikian Surat Keterangan Sehat ini dibuat untuk dapat dipergunakan seperlunya.</span>
				<br>
				<br>
				<span style="border-bottom:1px solid #000;">Keterangan :</span>
				<br>
				<table border=0 width="100%" style="border: 0px solid black">
					<tr>
						<td width="20%">Tinggi Badan</td>
						<td width="2%">: </td>
						<td width="30%"><?=$data_sehat['tinggi_badan']?> Cm</td>
						<td width="10%"></td>
						<td width="20%">Tek Darah</td>
						<td width="2%">: </td>
						<td width="30%"><?=$data_sehat['td_atas'].' / '.$data_sehat['td_bawah']?> mmHg</td>
					</tr>
					<tr>
						<td width="20%">Berat Badan</td>
						<td width="2%">: </td>
						<td width="30%"><?=$data_sehat['berat_badan']?> Kg</td>
						<td width="10%"></td>
						<td width="20%">Gol Darah</td>
						<td width="2%">: </td>
						<td width="30%"><?=$nama_gol_darah?></td>
					</tr>
				</table>
			</div>
			<br>
			<br>
			<div id="tanggal_surat">
				Jakarta, <?=date('d M Y', strtotime($data_sehat['created_date']));?>
			</div>
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