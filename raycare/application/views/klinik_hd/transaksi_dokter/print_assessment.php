<?
	// die_dump($check_med);

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

?>

<html>
<head>
	<style>
		.text{
			font-size: 8pt;
		}
		.text2{
			font-size: 9pt;
		}
	</style>
</head>
	<body>
		<div class="image" style="font-size: 10px;">
			<?php 
				if (file_exists(FCPATH.config_item('site_logo_real')) && is_file(FCPATH.config_item('site_logo_real'))) 
			    {
			        $image_header = base_url().config_item('site_logo_real');
			    }
			    else 
			    {
			        $image_header = base_url()."assets/mb/global/image/logo/logo-real.png";
			    }
			?>
			<img src="<?=$image_header?>" style="width: 200px; float: left; margin-right: 290px;"><!-- <b><?=$form_cabang['nama']?></b> -->
			<!-- <br><?=$alamat_subject['nama'].' : '.$form_cabang_alamat['alamat'].' '.$kota_cabang ?>  -->
			<br>
			<?php 
				// foreach ($form_cabang_telepon as $tlp) 
				// {
    //         		$telpon_subject = $this->subjek_m->get($tlp['subjek_id'])->nama;
    //         		echo $telpon_subject.' : '.$tlp['nomor'].'<br>';
				// }
			?>
			<?php 
				// foreach ($cabang_sosmed as $sosmed) 
				// {
    //         		echo $sosmed['nama_sosmed'].' : '.$sosmed['url'].'<br>';
				// }
			?>
			
					
		</div>
		
		<form>
			<table width="100%" border="0" class="text">
				<tr>
					<td>
						 <!-- <img src="<?=base_url()?>assets/mb/global/image/logo/logo-big.png" style="width: 200px;  border: 1px solid #000;"> -->
					</td>
				</tr>
				<tr>
					<td><hr></td>
				</tr>
				<tr>
					<td>
						<table width="900" border="0">
							<tr>
								<td valign="top" class="text2">Assessment of Patient Hemodialysis</td>
								<td  width="300" align="right">
									<table border=0 width="300" style="border: 1px solid black">
										<tr>
											<td width="100px" style="padding-left:10px; padding-top:10px">Name</td>

											<td style="padding-left:10px; padding-top:10px">: </td>
										</tr>
										<tr>
											<td width="100px" style="padding-left:10px; padding-top:10px">No.Medrec</td>

											<td style="padding-left:10px; padding-top:10px">:</td>
										</tr>
										<tr>
											<td width="100px" style="padding-left:10px; padding-top:10px">Place / Born Date</td>

											<td style="padding-left:10px; padding-top:10px">: </td>
										</tr>
										<tr>
											<td style="padding-left:10px; padding-top:10px">Age</td>
											<td style="padding-left:10px; padding-top:10px">: 
												
											</td>
										</tr>
										<tr>
											<td style="padding-left:10px; padding-top:10px; padding-bottom:10px">Dry Weight</td>
											<td style="padding-left:10px; padding-top:10px">: </td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><table width="900" border=0 style="border-bottom: 1px solid black">
							<tr>
								<td width="30px">Date</td>
								<td width="440px">:</td>
								<td width="30px">Time</td>
								<td>: </td>
							</tr>
							 
						</table>
					</td>
				</tr>
				<tr>
					<td><table width="900" border=0 style="border-bottom: 1px solid black">
							<tr>
								<td width="60px">Alergic</td>
								<td>:
									<label class="checkbox-inline">
										<input type="checkbox" id="medicine" value="1" disabled <?=$check_med?>> Medicine </label>
									<label class="checkbox-inline">
										<input type="checkbox" id="food" value="1" disabled <?=$check_food?>> Food </label>
								</td>
								 
							</tr>
							 
						</table>
					</td>
				</tr>
				 <tr>
					<td><table width="900" border=0 style="border-bottom: 1px solid black">
							<tr>
								<td width="60px">Assesment</td>
								<td>
								</td>
								 
							</tr>
							 
						</table>
					</td>
				</tr>
				 <tr>
					<td><table width="900" style="border-bottom: 1px solid black" >
							<tr>
								<td width="30px" valign="top">GCS</td>
								<td valign="top" width="5px">:</td>
								<td height="40px" valign="top"> 
								</td>
								 
							</tr>
							 
						</table>
					</td>
				</tr>

				<tr>
					<td><table width="900" border=0 cellpadding="0" cellspacing="0">
							<tr>
								 
								<td   valign="top">Medical Diagnose : <hr color="black" style="margin-top:0;margin-bottom:0">
								</td>
								 
							</tr>
							 
						</table>
					</td>
				</tr>

				<tr>
					<td><table width="900" border=0 >
							<tr>
								<td width="130px" valign="top">Dialysis Program</td>
							 
							</tr>
							 
						</table>
					</td>
				</tr>

				<tr>
					<td><table width="900" border=0 >
							<tr>
								<td width="150px" valign="top">Time of Dialysis</td>
								<td width="5px">:</td>
								<td style="border-bottom: 1px solid black" width="100px"></td>
							 	<td>Hours</td>
							</tr>
							 <tr>
								<td width="150px" valign="top">Quick of Blood</td>
								<td width="5px">:</td>
								<td style="border-bottom: 1px solid black"></td>
							 	<td>ml/Hours</td>
							</tr>
							<tr>
								<td width="150px" valign="top">Quick of Dialysate</td>
								<td width="5px">:</td>
								<td style="border-bottom: 1px solid black"></td>
							 	<td>ml/Hours</td>
							</tr>
							<tr>
								<td width="150px" valign="top">UF Goal</td>
								<td width="5px">:</td>
								<td style="border-bottom: 1px solid black"></td>
							 	<td>L</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="50px"> 
					</td>
				</tr>
				<tr>
					<td><table width="900" border=0 >
							<tr>
								<td width="130px" valign="top">Heparin</td>
								<td width="200px">
									<table>
									 	<tr>
									 		<td><input type="checkbox" id="regular" value="1" disabled > Regular</td>
									 		 
									 	</tr>
									 	<tr>
									 		<td><input type="checkbox" id="minimal" value="1" disabled > Minimal </label></td>
									 		 
									 	</tr>
									 	<tr>
									 		<td><input type="checkbox" id="free" value="1" disabled> Free </td>
									 		 
									 	</tr>
									 </table>
								</td>
								<td>
									 <table>
									 	<tr>
									 		<td>Dose</td>
									 		<td>:</td>
									 		<td> </td>
									 		<td>&nbsp;</td>
									 	</tr>
									 	<tr>
									 		<td>First</td>
									 		<td>:</td>
									 		<td style="border-bottom: 1px solid black" width="60px"></td>
									 		<td>U</td>
									 	</tr>
									 	<tr>
									 		<td>Mantainance</td>
									 		<td>:</td>
									 		<td style="border-bottom: 1px solid black" width="60px">  </td>
									 		<td> U/      Hours</td>
									 	</tr>
									 </table>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table width="900" border=0 >
							<tr>
								<td  valign="top">Profile Hemodialysis</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td><table width="900" border=0 >
							<tr>
								<td  valign="top"  width="80px">Machine No :</td>
							 	<td width="80px"><?=$form_tindakan_hd_penaksiran['machine_no']?></td>
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
					<td><table width="900" border=0 >
							<tr>
								<td  valign="top"  width="150px">Blood Access :</td>
							 	<td>
							 		<div class="checkbox-list">
										<label class="checkbox-inline">
										<input type="checkbox" id="av_shunt" value="1" disabled> AV Shunt </label><br>
										<label class="checkbox-inline">
										<input type="checkbox" id="femoral" value="1" disabled > Femoral </label><br>
										<label class="checkbox-inline">
										<input type="checkbox" id="double_lument" value="1" disabled > Catheter Double Lument </label>
									</div>
							 	</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td><table width="900" border=0 >
							<tr>
								<td  valign="top"  width="150px">Type of Dialysate :</td>
							 	<td>
							 		<label class="checkbox-inline">
														<input type="checkbox" id="bicarbonate" value="1" disabled > Bicarbonate </label>
							 	</td>
							</tr>
							 
						</table>
					</td>
				</tr>
			<tr>
				<td> <hr> </td>
			</tr>
				<tr>
					<td><table width="900" border=0 >
							<tr>
								<td>Problem Found:</td>
								<td>Compiication During Hemodialysis:</td>
							</tr>
							<tr>
								<td  valign="top"> 
									<div class="checkbox-list">
										<?php
											$i = 1;
											foreach ($pasien_problem as $problem) 
											{
												$checked = '';
												if($problem['nilai'] == 1)
												{
													$checked = '';
												}

												echo '<label ><input type="checkbox" disabled id="problem_'.$problem['problem_id'].'" name="problem_'.$problem['problem_id'].'" value="'.$problem['problem_id'].'" '.$checked.'> '.$problem_name[$i].'</label><br>';
												$i++;
											}
										?>
									</div>
								</td>
							 	<td>
							 		<div class="checkbox-list">
										<?php
											$i = 1;
											foreach ($pasien_komplikasi as $complication)
											{
												$checked = '';
												if($complication['nilai'] == 1)
												{
													$checked = '';
												}

												echo '<label ><input type="checkbox" disabled id="complication_'.$complication['complication_id'].'" name="complication_'.$complication['complication_id'].'" value="'.$complication['complication_id'].'" '.$checked.'> '.$complication_name[$i].'</label><br>';
												$i++;
											}
										?>
									</div>
							 	</td>
							</tr>
							 <tr>
								<td colspan="2"><hr></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
