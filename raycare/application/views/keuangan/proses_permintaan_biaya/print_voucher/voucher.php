<html>
	<head>
		<style type="text/css">

			body
			{
				font-size: 13px;
				font-family: Arial, Verdana, "Times New Roman";
			}

			#header 
			{
				width: 100%;
				margin: auto;
			}

			#head-table, #head-table-inform{
				width: 100%;
				margin-bottom: 10px;
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
			}
			#logo {
				width: 30%;
			}

			.title-child{
				font-size: 14px;
			}

			
		</style>
	</head>
	<body>
		<div id="header">
			<table id="head-table">
				<tr>
					<td width="30%">
						<?php 
							if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'."cloud/raycare/logo/logo-big.png") && is_file($_SERVER['DOCUMENT_ROOT'].'/'."cloud/raycare/logo/logo-big.png")) 
					        {
					            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."logo/logo-big.png";
					        }
					        else 
					        {
					            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."logo/logo-global.png";
					        }
						?>
						<img id="logo" src="<?=$image_header?>" >
					</td>
					<td id="title">
						<h2>PT. RAYCARE HEALTH SOLUTION</h2>
						<h4>CAB. KALIDERES</h4>
						<p class="title-child">Cash/Bank Spending Voucher</p>
						<p class="title-child">(USD/IDR)</p>
					</td>
				</tr>
			</table>

			<table id="head-table-inform">
				<tr>
					<td width="20%">Number</td>
					<td>: <?=$data_voucher['nomor_voucher']?></td>
				</tr>
				<tr>
					<td>Date</td>
					<td>: </td>
				</tr>

			</table>
		</div>
		<div id="body">

			<table id="body-table">
				<tr>
					<td colspan="2" height="20px">
						<?php

							$get_nama = $this->user_m->get_by(array('id' => $form_data['diproses_oleh']), true);

							$get_created = $this->user_m->get_by(array('id' => $form_data['diminta_oleh_id']), true);

							$get_created_by = $this->user_m->get_by(array('id' => 21), true);
							$tanda_tangan_made_by = config_item('base_dir')."cloud/".config_item('site_dir')."pages/master/user/images/".$get_created_by->username."/".$get_created_by->url_sign;
							$ttd_made = '<br><br><br><br>'.$get_created_by->nama;
							if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'."cloud/".config_item('site_dir')."pages/master/user/images/".$get_created_by->username."/".$get_created_by->url_sign) && is_file($_SERVER['DOCUMENT_ROOT'].'/'."cloud/".config_item('site_dir')."pages/master/user/images/".$get_created_by->username."/".$get_created_by->url_sign)) 
							{
								$ttd_made = '<img style="height:75px;" src="'.$tanda_tangan_made_by.'"><br>'.$get_created_by->nama;
							}

							$get_setuju = $this->user_m->get_by(array('id' => 4), true);
							$tanda_tangan_approved_by = config_item('base_dir')."cloud/".config_item('site_dir')."pages/master/user/images/".$get_setuju->username."/".$get_setuju->url_sign;
							$ttd_approve = '<br><br><br><br><br>'.$get_setuju->nama;
							if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'."cloud/".config_item('site_dir')."pages/master/user/images/".$get_setuju->username."/".$get_setuju->url_sign) && is_file($_SERVER['DOCUMENT_ROOT'].'/'."cloud/".config_item('site_dir')."pages/master/user/images/".$get_setuju->username."/".$get_setuju->url_sign)) 
							{
								$ttd_approve = '<img style="height:75px;" src="'.$tanda_tangan_approved_by.'"><br>'.$get_setuju->nama;
							}
							// die_dump($get_nama);

						?>
						<b>Paid To :</b> <?=$get_created->nama?>
					</td>

					<td id="signature" rowspan="2" style="height:75px;">
						<b>Made by</b><br>
						<?=$ttd_made?>
					</td>


				</tr>
				<tr rowspan="2">
					<td>
						<b>Amount :</b> 
						<br>
						<br><?php
							$nominal = 0;
							if($form_data['nominal_setujui'] <= 1000000){
        						$nominal = formatrupiah($form_data['nominal_setujui']);
        					}else{
        						$permintaan_biaya_bayar = $this->permintaan_biaya_pembayaran_m->get_data($form_data['id'])->result_array();

        						foreach ($permintaan_biaya_bayar as $row_bayar) {
        							$nominal = formatrupiah($row_bayar['total']);
        						}
        					}

        					echo $nominal;

						?>
					</td>
					<td>
						<?php
							$rupiah = $form_data['nominal_setujui']+$form_data['sisa'];
							// die_dump($rupiah);
		        			$terbilang = terbilang($rupiah);
		        			// die_dump($terbilang);
						?>
						<b>Say :</b> 
						<br>
						<br><?=$terbilang?> Rupiah
					</td>
					
				</tr>

				<tr rowspan="3">
					<td rowspan="2">
						<b>Paying with :</b>
						<br>
						<?php

							$permintaan_biaya_id = $form_data['id'];
        					$checked_tunai = '';
        					$checked_cek = '';
        					$checked_giro = '';
        					$checked_tt = '';

        					$nomor_cheque = '';
        					$nomor_giro = '';
        					$nomor_tt = '';

        					if($form_data['nominal_setujui'] <= 1000000){
        						$checked_tunai = 'checked="checked"';
        						$checked_cek = '';
        						$checked_giro = '';
        						$checked_tt = '';
        					}else{
        						$permintaan_biaya_bayar = $this->permintaan_biaya_pembayaran_m->get_data($form_data['id'])->result_array();

        						$found_cheque = false;
        						$found_giro = false;
        						$found_tt = false;

        						foreach ($permintaan_biaya_bayar as $row_bayar) {
        							if($row_bayar['pembayaran_tipe'] == 1){
        								$found_cheque = true;
        								$nomor_cheque .= " No :".$row_bayar['nomor_tipe']." ".formatrupiah($row_bayar['total'])."\n";
        							}if($row_bayar['pembayaran_tipe'] == 2){
        								$found_giro = true;
        								$nomor_giro .= " No :".$row_bayar['nomor_tipe']." ".formatrupiah($row_bayar['total'])."\n";

        							}if($row_bayar['pembayaran_tipe'] == 3){
        								$found_tt = true;
        								$nomor_tt .= " No Rek :".$row_bayar['nomor_tipe']." ".formatrupiah($row_bayar['total'])."\n";

        							}
        						}

        						if($found_cheque == true){
	        						$checked_cek = 'checked="checked"';
        						}if($found_giro == true){
	        						$checked_giro = 'checked="checked"';
        						}if($found_tt == true){
	        						$checked_tt = 'checked="checked"';
        						}


        					}

						?>
						<br><input id="tunai" type="checkbox" <?=$checked_tunai?>> Cash
						<br><input id="cek" type="checkbox" <?=$checked_cek?>> Cheque <?=$nomor_cheque?>
						<br><input id="giro" type="checkbox" <?=$checked_giro?>> Bilyetgiro <?=$nomor_giro?>
						<br><input id="transfer" type="checkbox" <?=$checked_tt?>> Transfer To: <?=$nomor_tt?>
					</td>
					<td rowspan="2" >
						<b>Being :</b> 
						<br>
						<br> <?=$form_data['keperluan']?>
					</td>
					<td id="signature" style="height:120px;">
						<b>Approved by</b><br>
						<?=$ttd_approve?>
					</td>
					
				</tr>
				<tr rowspan="3">
					<td id="signature" style="height:120px;">
						<b>Received by</b>
						
					</td>
				</tr>
			</table>
			
		</div>
		<div id="footer">
			<p style="font-size:9px;">Print by : <?=$this->session->userdata('nama_lengkap');?> | Print date : <?=date('d M Y, H:i')?> WIB</p>
		</div>
	</body>
</html>