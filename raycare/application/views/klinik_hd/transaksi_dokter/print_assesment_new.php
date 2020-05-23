<?php

	$problem_name = array(
		'1'	=> 'Airway Clearance, ineffective',
		'2'	=> 'Fluid balance',
		'3'	=> 'High risk of infection',
		'4'	=> 'Impaired sense of comfort pain',
		'5'	=> 'Disequilibrium Syndrome',
		'6'	=> 'Shock risk'
	);

	$complication_name = array(
		'1'	=> 'Bleeding',
		'2'	=> 'Pruritus',
		'3'	=> 'Dialyzer Alergic',
		'4'	=> 'Headache',
		'5'	=> 'Nausea',
		'6'	=> 'Chest Pain',
		'7'	=> 'Hypotension',
		'8'	=> 'Shiver',
		'9'	=> 'Etc'
	);

	($form_tindakan_hd_penaksiran['alergic_medicine']) ? $check_med    = 'checked="checked"' : $check_med = '';
	($form_tindakan_hd_penaksiran['alergic_food']) ? $check_food       = 'checked="checked"' : $check_food = '';
	($form_tindakan_hd_penaksiran['heparin_reguler']) ? $check_reg     = 'checked="checked"' : $check_reg = '';
	($form_tindakan_hd_penaksiran['heparin_minimal']) ? $check_min     = 'checked="checked"' : $check_min = '';
	($form_tindakan_hd_penaksiran['heparin_free']) ? $check_free       = 'checked="checked"' : $check_free = '';
	($form_tindakan_hd_penaksiran['dialyzer_new']) ? $check_new        = 'checked="checked"' : $check_new = '';
	($form_tindakan_hd_penaksiran['dialyzer_reuse']) ? $check_reuse    = 'checked="checked"' : $check_reuse = '';
	($form_tindakan_hd_penaksiran['ba_avshunt']) ? $check_av           = 'checked="checked"' : $check_av = '';
	($form_tindakan_hd_penaksiran['ba_femoral']) ? $check_femoral      = 'checked="checked"' : $check_femoral = '';
	($form_tindakan_hd_penaksiran['ba_catheter']) ? $check_catheter    = 'checked="checked"' : $check_catheter = '';
	($form_tindakan_hd_penaksiran['dialyzer_type']) ? $check_dialysate = 'checked="checked"' : $check_dialysate = '';

	// die(dump($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real')));
	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real')) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real'))) 
    {
        $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."logo/logo-real.png";
    }
    else 
    {
        $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."logo/logo-real.png";
    }
	// die(dump($image_header));

?>

<html>
<head>
	<style>
			body
			{
				font-size: 10px;
				font-family: Arial;
				text-align: justify;
			}

			table {
				border: none;
				font-size: 10px;
				margin: 0;
				padding: 0;
			}

			table.text{
				font-size: 10pt;
			}
			td.text2{
				font-size: 10pt;
				text-transform: uppercase;
			}
			#header 
			{
				width: 100%;
				border:0px solid green;
				margin-bottom:7px;
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
				font-size: 10px;
				/*margin-left: 30px;*/
				text-decoration:overline;
			}

			#no_surat {
				text-align: center;
				/*margin-left: 30px;*/
				text-decoration:overline;
			}

			.title-child{
				font-size: 10px;
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
				width : 150px;
				height: 70px;
				float:left;
				background-color:#fff;
				padding-right:180px;
			}
			.logo-a4-margin{
				float: left;
				width:55px;
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
		
		<form>
			<table width="100%" border="0" class="text" >
				<tr>
					<td colspan="2">
						<table width="900" border="0">
							<tr><td valign="top" class="text2" colspan="2"><b>Medical Record of Patient Hemodialysis</b></td></tr>
							<tr>
								<td valign="top" >
									<table border=0 width="500" style="border: 0px solid black;">
										
										<tr>
											<td width="60px">Date</td>
											<td width="1px">:</td>
											<td><?=date("d F Y", strtotime($form_tindakan_hd_penaksiran['tanggal']))?></td>
											<td></td>
											<td width="30px">Time</td>
											<td width="1px">:</td>
											<td><?=$form_tindakan_hd_penaksiran['waktu']?></td>
										</tr>
										<tr>
											<td>Alergic</td>
											<td width="1px">:</td>
											<td colspan="5">
												<label class="checkbox-inline">
													<input type="checkbox" id="medicine" value="1" disabled <?=$check_med?>> Medicine </label>
												<label class="checkbox-inline">
													<input type="checkbox" id="food" value="1" disabled <?=$check_food?>> Food </label>	
											</td>
										</tr>
										<tr>
											<td valign="top">Assesment GCS</td>
											<td width="1px" valign="top">:</td>
											<td colspan="2">
												<?=preg_replace('/(\r\n|\n|\r|\f)/U', "<br />", $form_tindakan_hd_penaksiran['assessment_cgs'])?>
											</td>
											<td width="30px" valign="top">Medical Diagnose</td>
											<td width="1px" valign="top">:</td>
											<td valign="top"><?=preg_replace('/(\r\n|\n|\r|\f)/U', "<hr color='black' style='margin-top:0;margin-bottom:5px'>&nbsp;", $form_tindakan_hd_penaksiran['medical_diagnose'])?></td>
										</tr>
										
										
										
									</table>
								</td>
								<td  width="300" align="right">
									<table border=0 width="300" style="border: 0.5px solid #bfbfbf">
										
										<tr>
											<td width="100px" style="padding-left:10px; ">Name</td>

											<td style="padding-left:10px; ">: <b><?=$form_pasien['nama']?></b></td>
										</tr>
										<tr>
											<td width="100px" style="padding-left:10px; ">No.Medrec</td>

											<td style="padding-left:10px; ">: <?=$form_pasien['no_member']?></td>
										</tr>
										<tr>
											<td width="100px" style="padding-left:10px; ">Place / Born Date</td>

											<td style="padding-left:10px; ">: <?=$form_pasien['tempat_lahir']?> / <?=date('d M Y',strtotime($form_pasien['tanggal_lahir']))?></td>
										</tr>
										<tr>
											<td style="padding-left:10px; ">Age</td>
											<td style="padding-left:10px; ">: 
												<?php 
													$umur = date_diff(date_create($form_pasien['tanggal_lahir']), date_create('today'))->y;

													if ($umur < 1) {
														$umur = translate('Dibawah 1 tahun', $this->session->userdata('language'));
													}

													echo $umur;
												?> 
											</td>
										</tr>
										<tr>
											<td style="padding-left:10px; ">Dry Weight</td>
											<td style="padding-left:10px; ">: <?=$form_pasien['berat_badan_kering']?></td>
										</tr>
									</table>
								</td>
							</tr>
							
						</table>
					</td>
				</tr>
				

				<tr>
					<td colspan="2">
						<table width="900" border=0 style="border-bottom: 0.5px solid #bfbfbf">
							<tr>
								<td width="130px" valign="top">Dialysis Program</td>		 
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<table width="300px" border=0 >
							<tr>
								<td width="130px" valign="top">Time of Dialysis</td>
								<td width="5px">:</td>
								<td style="border-bottom: 0.5px solid #bfbfbf" width="50px"><?=$form_tindakan_hd_penaksiran['time_of_dialysis']?></td>
							 	<td width="80px">Hours</td>
							 	<td width="100px"></td>
							 	<td width="90px">Heparin</td>
							 	<td width="1px">:</td>
							 	<td width="100px"><input type="checkbox" id="regular" value="1" disabled <?=$check_reg?>> Regular</td>
							 	<td width="60px">Dose</td>
							 	<td width="1px">:</td>
							 	<td><?=$form_tindakan_hd_penaksiran['dose']?></td>
							 	<td ></td>
							</tr>
							 <tr>
								<td width="130px" valign="top">Quick of Blood</td>
								<td width="5px">:</td>
								<td style="border-bottom: 0.5px solid #bfbfbf"><?=$form_tindakan_hd_penaksiran['quick_of_blood']?></td>
							 	<td width="80px">ml/Hours</td>
							 	<td width="100px"></td>
							 	<td></td>
							 	<td width="1px"></td>
							 	<td width="100px"><input type="checkbox" id="minimal" value="1" disabled <?=$check_min?>> Minimal </td>
							 	<td width="60px">First</td>
							 	<td width="1px">:</td>
							 	<td style="border-bottom: 0.5px solid #bfbfbf" width="40px"> <?=$form_tindakan_hd_penaksiran['first']?></td>
							 	<td>U</td>
							</tr>
							<tr>
								<td width="130px" valign="top">Quick of Dialysate</td>
								<td width="5px">:</td>
								<td style="border-bottom: 0.5px solid #bfbfbf"><?=$form_tindakan_hd_penaksiran['quick_of_dialysis']?></td>
							 	<td width="80px">ml/Hours</td>
							 	<td width="100px"></td>
							 	<td></td>
							 	<td width="1px"></td>
							 	<td width="100px"><input type="checkbox" id="free" value="1" disabled <?=$check_free?>> Free </td>
							 	<td>Maintenance</td>
						 		<td>:</td>
						 		<td style="border-bottom: 0.5px solid #bfbfbf" width="40px"> <?=$form_tindakan_hd_penaksiran['maintenance']?> </td>
						 		<td> U/ <?=$form_tindakan_hd_penaksiran['hours']?> Hours</td>


							</tr>
							<tr>
								<td width="130px" valign="top">UF Goal</td>
								<td width="5px">:</td>
								<td style="border-bottom: 0.5px solid #bfbfbf"> <?=$form_tindakan_hd_penaksiran['uf_goal']?></td>
							 	<td width="80px">L</td>
							 	<td width="100px"></td>
							 	<td></td>
							 	<td width="1px"></td>
							 	<td></td>
							 	<td></td>
							 	<td></td>
							 	<td></td>
							 	<td></td>



							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table width="900" border=0 style="border-bottom: 0.5px solid #bfbfbf" >
							<tr>
								<td  valign="top">Profile Hemodialysis</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td><table width="900" border=0 >
							<tr>
								<td  valign="top"  width="80px">Machine No</td>
								<td  valign="top"  width="1px">:</td>
							 	<td  width="150px"><?=$form_tindakan_hd_penaksiran['machine_no']?></td>
							 	<td  valign="top"  width="100px">Type of Dialyzer :</td>
							 	<td width="200px"> 
							 		<div class="checkbox-list">
										<label class="checkbox-inline">
										<input type="checkbox" id="new" value="1" disabled <?=$check_new?>> New </label>
										<label class="checkbox-inline">
										<input type="checkbox" id="reuse" value="1" disabled <?=$check_reuse?>> Reuse </label>
									</div>
								</td>
								<td  valign="top"  width="60px">Dialyzer :</td>
							 	<td align="left"><?=$form_tindakan_hd_penaksiran['dialyzer']?></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table width="900" border=0 style="border-bottom: 0.5px solid #bfbfbf">
							<tr>
								<td  valign="top"  width="80px">Blood Access</td>
								<td  valign="top"  width="1px">:</td>
							 	<td width="150px">
							 		<div class="checkbox-list">
										<label class="checkbox-inline">
										<input type="checkbox" id="av_shunt" value="1" disabled <?=$check_av?>> AV Shunt </label><br>
										<label class="checkbox-inline">
										<input type="checkbox" id="femoral" value="1" disabled <?=$check_femoral?>> Femoral </label><br>
										<label class="checkbox-inline">
										<input type="checkbox" id="double_lument" value="1" disabled <?=$check_catheter?>> Catheter Double Lument </label>
									</div>
							 	</td>
							 	<td  valign="top"  width="100px">Type of Dialysate :</td>
							 	<td  valign="top" colspan="3"> 
							 		<div class="checkbox-list">
							 			<label class="checkbox-inline">
											<input type="checkbox" id="bicarbonate" value="1" disabled <?=$check_dialysate?>> Bicarbonate 
										</label><br>
									</div>
							 	</td>
							</tr>
						</table>
					</td>
				</tr>
			
				<tr>
					<td><table width="900" border=0 style="border-bottom: 0.5px solid #bfbfbf" >
							<tr>
								<td>Problem Found:</td>
								<td colspan="2">Complication During Hemodialysis:</td>
							</tr>
							<tr>
								<td  valign="top"> 
									<div class="checkbox-list">
										<?php
											$i = 1;
											for ($x=0;$x<=5;$x++) 
											{
												$checked = '';
												if($pasien_problem[$x]['nilai'] == 1)
												{
													$checked = 'checked = "checked"';
												}

												echo '<label ><input type="checkbox" disabled id="problem_'.$pasien_problem[$x]['problem_id'].'" name="problem_'.$pasien_problem[$x]['problem_id'].'" value="'.$pasien_problem[$x]['problem_id'].'" '.$checked.'> '.$problem_name[$i].'</label><br>';
												$i++;
											}
										?>
									</div>
								</td>
							 	<td>
							 		<div class="checkbox-list">
										<?php
											$i = 1;
											for ($x=0;$x<=5;$x++)
											{
												$checked = '';
												if($pasien_komplikasi[$x]['nilai'] == 1)
												{
													$checked = 'checked = "checked"';
												}

												echo '<label ><input type="checkbox" disabled id="complication_'.$pasien_komplikasi[$x]['complication_id'].'" name="complication_'.$pasien_komplikasi[$x]['complication_id'].'" value="'.$pasien_komplikasi[$x]['complication_id'].'" '.$checked.'> '.$complication_name[$i].'</label><br>';
												$i++;
											}
										?>
									</div>
							 	</td>
							 	<td>
							 		<div class="checkbox-list">
										<?php
											$i = 7;
											for ($x=6;$x<=8;$x++)
											{
												$checked = '';
												if($pasien_komplikasi[$x]['nilai'] == 1)
												{
													$checked = 'checked = "checked"';
												}

												echo '<label ><input type="checkbox" disabled id="complication_'.$pasien_komplikasi[$x]['complication_id'].'" name="complication_'.$pasien_komplikasi[$x]['complication_id'].'" value="'.$pasien_komplikasi[$x]['complication_id'].'" '.$checked.'> '.$complication_name[$i].'</label><br>';
												$i++;
											}

											for($y=1;$y<=3;$y++)
											{
												echo '<label ><input type="hidden" disabled id="" name="" value=""> </label><br>';
											}
										?>
										


									</div>
							 	</td>
							</tr>
							 <tr>
								<td colspan="2"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table width="900" border="0" style="border-bottom: 0.5px solid #bfbfbf">
							<tr>
								<td colspan="2">Supervising of fluid during Hemodialysis</td>
							</tr>
							<tr>
								<td colspan="2" style="height:5px"></td>
							</tr>
							<tr>
								<td>Intake</td>
								<td>Output</td>
								<td>Blood Transfusion</td>
							</tr>
							<tr>
								<td valign="top">
									<table>
										<tr>
											<td>1.</td>
											<td>Remain of Priming</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" width="80px" ><?=$form_tindakan_hd_penaksiran['remaining_of_priming']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>2.</td>
											<td>Wash Out</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran['wash_out']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>3.</td>
											<td>Drip of fluid</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran['drip_of_fluid']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>4.</td>
											<td>Blood</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran['blood']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>5.</td>
											<td>Drink</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran['drink']?></td>
											<td>cc</td>
										</tr>
									</table>
								</td>
								<td valign="top">
									<table>
										<tr>
											<td>1.</td>
											<td>Vomitting</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" width="80px" ><?=$form_tindakan_hd_penaksiran['vomiting']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>2.</td>
											<td>Urinate</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf"><?=$form_tindakan_hd_penaksiran['urinate']?></td>
											<td>cc</td>
										</tr>
									 </table>
								</td>
								<td valign="top">
									<table>
										<tr>
											<td>1.</td>
											<td width="80px" >Type</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" width="80px" ><?=$form_tindakan_hd_penaksiran['transfusion_type']?></td>
											<td></td>
										</tr>
										<tr>
											<td>2.</td>
											<td>Quantity</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran['transfusion_qty']?></td>
											<td>Bag</td>
										</tr>
										<tr>
											<td>3.</td>
											<td>Blood Type</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran['transfusion_blood_type']?></td>
											<td></td>
										</tr>
										<tr>
											<td valign="top">4.</td>
											<td valign="top">Serial No</td>
											<td valign="top">:</td>
											<td valign="top"><?=preg_replace('/(\r\n|\n|\r|\f)/U', "<br />", $form_tindakan_hd_penaksiran['serial_number'])?></td>
											<td></td>
										</tr>
										 
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<table id="monitoring_dialysis" width="100%" border="0" cellpadding="0" cellspacing="0" style="border-top: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf;border-top: 0.5px solid #bfbfbf;border-left: 0.5px solid #bfbfbf;" class="text">
				<tr>
					<td colspan="13" align="center" style="border-bottom: 0.5px solid #bfbfbf; font-size: 10px;" class="text2" ><b>MONITORING DIALYSIS</b></td>
				</tr>
				<tr>
					<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">Hours</td>
					<td align="center"  width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">Blood Pressure</td>
					<td align="center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">Quick of Blood</td>
					<td align="center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">UF Goal</td>
					<td align="center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">UFR</td>
					<td align="center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">UFV</td>
					<td align="center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">TMP</td>
					<td align="center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">VP</td>
					<td align="center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">AP</td>
					<td align="center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">Cond</td>
					<td align="center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">Temp</td>
					<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">Nurse</td>
					<td align="center"  width="200px" style="border-bottom: 0.5px solid #bfbfbf; font-size: 10px;">Explanation</td>
				</tr>
				<?foreach($form_observasi as $observasi){

					$user = $this->user_m->get($observasi['user_id']);
				?>

					<tr>
						<td align="center"  style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=date("H:i:s", strtotime($observasi['waktu_pencatatan']))?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['tekanan_darah_1'].'/'.$observasi['tekanan_darah_2']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['qb']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['ufg']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['ufr']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['ufv']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['tmp']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['vp']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['ap']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['cond']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$observasi['temperature']?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=$user->nama?></td>
						<td align="center" style="border-bottom: 0.5px solid #bfbfbf; font-size: 10px;"><?=ucwords($observasi['keterangan'])?></td>
					</tr>
				<?}?>
			</table>
			<br>
			<table width="100%"  style="border-top: 0.5px solid #bfbfbf;border-bottom: 0.5px solid #bfbfbf; font-size: 10px;" cellpadding="0" cellspacing="0" class="text">
			<tr>
				<td width="50%" style="border-right: 0.5px solid #bfbfbf; font-size: 10px;" valign="top">
					<table width="450">
						<tr>
							<td class="text2" style="font-size: 10px;"><b>Medicine</b></td>
						</tr>
						<?php 
							$x=0;
							if($form_medicine != '0')
							{
								foreach($form_medicine as $medicine)
								{
									$checked = '';

									($medicine['is_show_assessment']) ? $checked = 'checked = "checked"' : $checked = '';
							
									echo '<tr><td style="font-size: 10px;"><input type="checkbox" id="cek[]" '.$checked.' > '.$medicine['nama'].'</td> </tr>';
								}						
							}
							else
							{
								echo '<tr><td style="font-size: 10px;"><input type="checkbox" id="cek[]" checked = "checked" > Etc </td> </tr>';
							}
						?>
					</table>
				</td>
				<td width="50%" style="padding-left:5px">
					<table width="450" border="0">
						<tr>
							<td class="text2" style="font-size: 10px;"><b>Examination support</b></td>
						</tr>
						<tr>
							<td class="text2" style="font-size: 10px;">Laboratory :</td>
						</tr>
						<tr>
							<td  valign="top" style="font-size: 10px;">
								 
								<?=preg_replace('/(\r\n|\n|\r|\f)/U', "<hr color=#bfbfbf style=margin-top:0;margin-bottom:5>&nbsp;", $form_tindakan_hd_penaksiran['laboratory'])?><hr color="#bfbfbf" style="margin-top:0;margin-bottom:0">
							 
							</td>
						</tr>
						 
						<tr>
							<td class="text2" style="font-size: 10px;">ECG :</td>
						</tr>
						<tr>
							<td style="font-size: 10px;"><?=preg_replace('/(\r\n|\n|\r|\f)/U', "<hr color=#bfbfbf style=margin-top:0;margin-bottom:5>&nbsp;", $form_tindakan_hd_penaksiran['ecg'])?> <hr color="#bfbfbf" style="margin-top:0;margin-bottom:0"></td>
						</tr>
						 <tr>
							<td height="10px" style="font-size: 10px;"></td>
				 
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="font-size: 10px;"></td>
				 
			</tr>
		</table>
		<br>
		<table width="100%" cellpadding="0" cellspacing="0" class="text">
			<tr>
				<td width="12%" style="font-size:10px;">Priming</td>
				<td width="1%" style="font-size:10px;">:</td>
				<td width="20%" style="font-size:10px;"><?=$form_tindakan_hd_penaksiran['priming']?></td>
				<td width="12%" style="font-size:10px;">Initiation</td>
				<td width="1%" style="font-size:10px;">:</td>
				<td width="20%" style="font-size:10px;"><?=$form_tindakan_hd_penaksiran['initiation']?></td>
				<td width="12%" style="font-size:10px;">Termination</td>
				<td width="1%" style="font-size:10px;">:</td>
				<td width="20%" style="font-size:10px;"><?=$form_tindakan_hd_penaksiran['termination']?></td>
			</tr>
		
		</table>

		</form>
	</body>
</html>
