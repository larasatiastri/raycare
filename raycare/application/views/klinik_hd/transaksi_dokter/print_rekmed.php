<html>
	<head>
		<style type="text/css">

			body
			{
				font-size: 11px;
				font-family: Arial;
				text-align: justify;
			}

			

			table {
			    border-collapse: collapse;
			    width: 100%;
			}

			#pasien th, td {
			    padding: 8px;
			    text-align: left;
			    border-bottom: 1px solid #ddd;
			}
			ul {
				list-style-type:none;
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

			.title{
				font-size: 16px;
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
			margin-top: 575px;
			margin-left: 240px;
			height:80px !important;
			position:fixed !important;
			bottom:0 !important;
			right:0 !important;
			opacity: 0.5;
			z-index: -1;
			}
			.stempel-bed{
				width: 70px;
				height: 70px;
				padding-left: 608px;
				/*background-color: yellow;*/
				position: fixed !important;
			}




		</style>
	</head>
	<body>
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
   
		$gender = $pasien['gender'];


		$tekanan_darah = explode('_', $data_tindakan['td']);
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
		
		<div>
			<div align="center" style=" width:100%; height:30px; background-color:#ddd;margin-top:20px;font-size:16px;padding-top:10px;">
				<b>KLINIK UMUM RAYCARE</b>
			</div>

			<div>
				<table id="pasien" border=0 width="100%">
					<tbody>
						<tr>
							<td width="15%" style="vertical-align:top;">Nama Pasien</td>
							<td width="1%"style="vertical-align:top;">:</td>
							<td style="vertical-align:top;"><strong><?=$data_pasien['nama']?></strong></td>
							<td width="3%" style="vertical-align:top;text-align:center;"><b><?=$data_pasien['gender']?></b></td>
						</tr>
						<tr>
							<td width="15%" style="vertical-align:top;">No. RM</td>
							<td width="1%"style="vertical-align:top;">:</td>
							<td colspan="2" style="vertical-align:top;"><strong><?=$data_pasien['no_member']?></strong></td>
						</tr>
						<tr>
							<td width="15%" style="vertical-align:top;">Umur</td>
							<td width="1%"style="vertical-align:top;">:</td>
							<td colspan="2" style="vertical-align:top;"><strong><?=$umur_pasien?></strong></td>
						</tr>
					</tbody>
				</table>
			</div>
			<br>

			<div>
				<table id="dokter" border=1 width="100%" style="border-bottom:1px solid #000 !important;">
					<thead>
						<tr>
							<th width="20%" style="vertical-align:top;text-align:center;border-bottom:1px solid #000 !important;">Tanggal</th>
							<th style="vertical-align:top;text-align:center;border-bottom:1px solid #000 !important;">Catatan Dokter</th>
							<th width="8%" style="vertical-align:top;text-align:center;border-bottom:1px solid #000 !important;">Paraf</th>
						</tr>
					</thead>
					<tbody>
					<tr>
						<td style="vertical-align:top;border-bottom:1px solid #000 !important;">Tgl. <strong><?=date('d M Y', strtotime($data_tindakan['tanggal']))?></strong><br>
							<ul style="list-style-type:none !important;">
								<li>BB = <strong><?=$data_tindakan['bb']?></strong> Kg </li>
								<li>TD = <strong><?=$tekanan_darah[0].'/'.$tekanan_darah[1]?></strong> mmHg </li>
								<li>ND = <strong><?=$data_tindakan['nadi']?></strong> x/mnt </li>
								<li>ND = <strong><?=$data_tindakan['respirasi']?></strong> x/mnt </li>
								<li>S = <strong><?=$data_tindakan['suhu']?></strong> &#8451; </li>
							</ul>
						</td>
						<td  style="border-bottom:1px solid #000 !important;"><b>Diagnosa :</b></br>
							<ol>
								<?php foreach ($data_tindakan_diagnosa as $diagnosa) { ?>
										<li><?=$diagnosa['diagnosa']?></li>
								<?php } ?>
								
							</ol>
							</br>
							</br><b>Tindakan :</b> </br>
							<ol>
								<?php foreach ($data_tindakan_tindakan as $tindakan) { 
										$data_tindakan = $this->tindakan_m->get_by(array('id' => $tindakan['tindakan_id']), true);
									?>
										<li><?=$data_tindakan->nama?></li>
								<?php } ?>
								
							</ol>
							</br>
							</br>
							<?php if($data_tindakan_resep_detail) {?>


							<b>Resep :</b> </br>
							<ol>
								<?php foreach ($data_tindakan_resep_detail as $resep) { 
										$data_item = $this->item_m->get_by(array('id' => $resep['item_id']), true);
										$data_item_satuan = $this->item_satuan_m->get_by(array('id' => $resep['satuan_id']), true);
									?>
										<li><?=$data_item->nama.' '.$resep['jumlah'].' '.$data_item_satuan->nama.' '.$resep['dosis']?></li>
								<?php } ?>
								
							</ol>
							<?php }?>
						</td>
						<td style="border-bottom:1px solid #000 !important;"></td>
					</tr>
					</tbody>
					
				</table>
				

			<div class="signate">
				
			</div>
		</div>
	</body>
</html>
