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

			.title{
				font-size: 14px;
				text-align: center;

			}

			.title p{
				margin-left: -200px;

			}

			.term{
				font-size: 12px;
				font-style: italic;
			}
			
			.label {
			    font-size: 12px;
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
				padding-right: 10px;
				background-color: #FFF;
				float: right;
				text-align: center;
			}

			#tujuan_surat{
				text-align: justify;
			}

			#isi_surat{
				text-align: justify;
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
		</style>
	</head>
	<body>
	<?php
		$nama_dokter    = $data_traveling['kepada'];
		$cabang_id      = $data_traveling['cabang_id'];
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
   
		$gender = ($pasien['gender'] == 'P')?'Female':'Male';

		$vas_access = str_split($data_traveling['vascular_access']);
		$frequency = str_split($data_traveling['frequency_of_hemodialysis']);

		$check_cdl = ($vas_access[0] == '1')?'checked="checked"':'';
		$check_av_shunt = ($vas_access[1] == '1')?'checked="checked"':'';
		$check_femoralis = ($vas_access[2] == '1')?'checked="checked"':'';

		$check_mon = ($frequency[0] == '1')?'checked="checked"':'';
		$check_tue = ($frequency[1] == '1')?'checked="checked"':'';
		$check_wed = ($frequency[2] == '1')?'checked="checked"':'';
		$check_thu = ($frequency[3] == '1')?'checked="checked"':'';
		$check_fri = ($frequency[4] == '1')?'checked="checked"':'';
		$check_sat = ($frequency[5] == '1')?'checked="checked"':'';
		$check_sun = ($frequency[6] == '1')?'checked="checked"':'';

		$day = str_split($data_traveling['frequency_of_hemodialysis']);

		$count_1 = 0;
		foreach ($day as $row_day) 
		{
			if($row_day == '1')
			{
				$count_1 = $count_1+1;
			}
		}
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
		<div id="body_surat">
			<div id="title">
				<b style="border-bottom:2px solid #000;">REFERAL OF DIALYSIS PATIENT</b>
			</div>
			<div id="no_surat"><span>NO:<?=$data_traveling['no_surat']?></span>
			</div>
			<br>
			<br>
			<div id="tanggal_surat">
				Jakarta, <?=date('d M Y', strtotime($data_traveling['tanggal_surat']));?>
			</div>
			<br>
			
			<div id="isi_surat">
				<table border=0 width="100%">
					<tr>
						<td width="33%">Name</td>
						<td width="1%">:</td>
						<td width="66%"><?=$pasien['nama']?></td>

					</tr>
					<tr>
						<td width="33%">Age / Sex</td>
						<td width="1%">: </td>
						<td width="66%"><?=$umur_pasien.$gender?></td>
					</tr>
					<tr>
						<td width="33%">Nationality</td>
						<td width="1%">: </td>
						<td width="66%">WNI</td>
					</tr>
				</table>
				<br/>
				<table border=0 width="100%">
					
					<tr>
						<td width="33%">Date inition of maintenance hemodialysis in Raycare</td>
						<td width="1%">:</td>
						<td width="66%"><?=date('d M Y', strtotime($data_traveling['date_of_inition']))?></td>
					</tr>
				</table>
				<br/>
				<table border=0 width="100%">
					<tr>
						<td width="100%">Vascular access :</td>
					</tr>
					<tr>
						<td width="100%">
							<div class="checkbox-list">
							 	<label class="checkbox-inline" style="color:green;">
									<input class="" type="checkbox" id="cdl" name="cdl" value="1" <?=$check_cdl?>>	
							 		<?=translate("Catheter Double Lumen", $this->session->userdata("language"))?>
							 	</label>
							 	<label class="checkbox-inline">
									<input class="" type="checkbox" id="av_shunt" name="av_shunt" value="1" <?=$check_av_shunt?>>
									<?=translate("AV Shut - Fistula", $this->session->userdata("language"))?>
							 	</label>
							 	<label class="checkbox-inline">
									<input class="" type="checkbox" id="femoral" name="femoral" value="1" <?=$check_femoralis?>>
									<?=translate("Femoralis", $this->session->userdata("language"))?>
							 	</label>
						 	</div>
						</td>
					</tr>
				</table>
				<br/>
				<table border=0 width="100%">
					<tr>
						<td width="33%">Frequency of hemodialysis</td>
						<td width="1%">:</td>
						<td width="66%"><?=$count_1?> / week</td>
					</tr>
				</table>

				<table border=0 width="100%">
					<tr>
						<td>
							<div class="checkbox-list">
							 	<label class="checkbox-inline">
									<input class="" type="checkbox" id="cdl" name="cdl" value="1" <?=$check_mon?>>
							 		<?=translate("Mon", $this->session->userdata("language"))?>
							 	</label>
							 	<label class="checkbox-inline">
									<input class="" type="checkbox" id="av_shunt" name="av_shunt" value="1" <?=$check_tue?>>
									<?=translate("Tue", $this->session->userdata("language"))?>
							 	</label>
							 	<label class="checkbox-inline">
									<input class="" type="checkbox" id="femoral" name="femoral" value="1" <?=$check_wed?>>
									<?=translate("Wed", $this->session->userdata("language"))?>
							 	</label>
							 	<label class="checkbox-inline">
									<input class="" type="checkbox" id="cdl" name="cdl" value="1" <?=$check_thu?>>
							 		<?=translate("Thu", $this->session->userdata("language"))?>
							 	</label>
							 	<label class="checkbox-inline">
									<input class="" type="checkbox" id="av_shunt" name="av_shunt" value="1" <?=$check_fri?>>
									<?=translate("Fri", $this->session->userdata("language"))?>
							 	</label>
							 	<label class="checkbox-inline">
									<input class="" type="checkbox" id="femoral" name="femoral" value="1" <?=$check_sat?>>
									<?=translate("Sat", $this->session->userdata("language"))?>
							 	</label>
							 	<label class="checkbox-inline">
									<input class="" type="checkbox" id="femoral" name="femoral" value="1" <?=$check_sun?>>
									<?=translate("Sun", $this->session->userdata("language"))?>
							 	</label>
						 	</div>
						</td>
					</tr>
				</table>
				<br/>
				<table border=0 width="100%">
					<tr>
						<td width="100%">Body weight increase in between hemodialysis</td>
					</tr>
				</table>
				<table border=0 width="100%">
					<tr>
						<td width="33%">Min</td>
						<td width="1%">:</td>
						<td width="66%"><?=$data_traveling['body_weight_min']?> Kg</td>
					</tr>
					<tr>
						<td width="33%">Max</td>
						<td width="1%">:</td>
						<td width="66%"><?=$data_traveling['body_weight_max']?> Kg</td>
					</tr>
					<tr>
						<td width="33%">Dry weight</td>
						<td width="1">:</td>
						<td width="66%"><?=$data_traveling['body_dry_weight']?> Kg</td>
					</tr>
				</table>
				<br/>
				<table border=0 width="100%">
					<tr>
						<td width="100%">Recent laboratory data</td>
					</tr>
				</table>
				<table border=0 width="100%">
					
					<tr>
						<td width="33%">UR</td>
						<td width="1%">:</td>
						<td width="66%"><?=$data_traveling['lab_ur']?> md/dL</td>
					</tr>
					<tr>
						<td width="33%">CR</td>
						<td width="1%">:</td>
						<td width="66%"><?=$data_traveling['lab_cr']?> mg/dL</td>
					</tr>
					<tr>
						<td width="33%">HB</td>
						<td width="1%">:</td>
						<td width="66%"><?=$data_traveling['lab_hb']?> mg/dL</td>
					</tr>
					<tr>
						<td width="33%">HbsAg</td>
						<td width="1">:</td>
						<td width="66%"><?=$data_traveling['lab_hbsag']?></td>
					</tr>
					<tr>
						<td width="33%">anti HCV</td>
						<td width="1%">:</td>
						<td width="66%"><?=$data_traveling['lab_anti_hcv']?></td>
					</tr>
					<tr>
						<td width="33%">anti HIV</td>
						<td width="1%">:</td>
						<td width="66%"><?=$data_traveling['lab_anti_hiv']?></td>
					</tr>
				</table>
				<br/>
				<table border="0" width="100%">
					
					<tr>
						<td width="33%">Blood group</td>
						<td width="1%">:</td>
						<td width="66%" colspan="7"><?=$data_traveling['blood_group']?></td>
					</tr>
					<tr>
						<td width="33%">Total Heparin dose</td>
						<td width="1%">:</td>
						<td width="20%"><?=$data_traveling['total_heparin_dose']?> /U</td>
						<td width="10%">Initial</td>
						<td width="1%">:</td>
						<td width="15%"><?=$data_traveling['initial']?> /U</td>
						<td width="10%">Hourly</td>
						<td width="1%">:</td>
						<td width="10%"><?=$data_traveling['hourly']?> /U</td>
					</tr>
					<tr>
						<td width="33%">Blood Flow (QB)</td>
						<td width="1%">:</td>
						<td width="66%" colspan="7"><?=$data_traveling['blood_flow']?> mg/dL</td>
					</tr>
					<tr>
						<td width="33%">Medication</td>
						<td width="1%">:</td>
						<td width="66%" colspan="7"><?=$data_traveling['medication']?></td>
					</tr>
					<tr>
						<td width="33%">Complication on Hemodialysis</td>
						<td width="1%">:</td>
						<td width="66%" colspan="7"><?=$data_traveling['complication_on_hemodialysis']?></td>
					</tr>
					<tr>
						<td width="100%" colspan="3">For more information please contact phone No : +62 21 29430783</td>
						
					</tr>
				</table>
				<br/>
				<table border=0 width="100%" style="border: 0px solid black">
				<tr>
						<td width="33%">Remarks</td>
						<td width="1%">:</td>
						<td width="66%"><?=$data_traveling['remarks']?></td>
					</tr>
				</table>			
			</div>
			<br>
			<br>
			<div class="signate">
				<div class="signate-left"></div>
				<div class="signate-center"></div>
				<div class="signate-right">
					Klinik Hemodialisa Raycare
					<br>
					<br>
					<br>
					<br>
					<br>
					<?=$dokter['nama']?>
				</div>
				<div style="margin-left:550px;width:380px;font-size:8px;">
					SIP :<?= $dokter['sip']?>
				</div>
			</div>
		</div>
	</body>
</html>
