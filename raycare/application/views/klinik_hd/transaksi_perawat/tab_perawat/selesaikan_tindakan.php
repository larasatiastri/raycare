<?php
	$form_attr = array(
		"id"			=> "form_selesaikan_tindakan", 
		"name"			=> "form_selesaikan_tindakan", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
	);
	echo form_open(base_url()."klinik_hd/transaksi_perawat/save_selesaikan", $form_attr,$hidden);

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

	($form_tindakan_hd_penaksiran_view['alergic_medicine']) ? $check_med    = 'checked="checked"' : $check_med = '';
	($form_tindakan_hd_penaksiran_view['alergic_food']) ? $check_food       = 'checked="checked"' : $check_food = '';
	($form_tindakan_hd_penaksiran_view['heparin_reguler']) ? $check_reg     = 'checked="checked"' : $check_reg = '';
	($form_tindakan_hd_penaksiran_view['heparin_minimal']) ? $check_min     = 'checked="checked"' : $check_min = '';
	($form_tindakan_hd_penaksiran_view['heparin_free']) ? $check_free       = 'checked="checked"' : $check_free = '';
	($form_tindakan_hd_penaksiran_view['dialyzer_new']) ? $check_new        = 'checked="checked"' : $check_new = '';
	($form_tindakan_hd_penaksiran_view['dialyzer_reuse']) ? $check_reuse    = 'checked="checked"' : $check_reuse = '';
	($form_tindakan_hd_penaksiran_view['ba_avshunt']) ? $check_av           = 'checked="checked"' : $check_av = '';
	($form_tindakan_hd_penaksiran_view['ba_femoral']) ? $check_femoral      = 'checked="checked"' : $check_femoral = '';
	($form_tindakan_hd_penaksiran_view['ba_catheter']) ? $check_catheter    = 'checked="checked"' : $check_catheter = '';
	($form_tindakan_hd_penaksiran_view['dialyzer_type']) ? $check_dialysate = 'checked="checked"' : $check_dialysate = '';

?>
<div class="row">
	<div class="col-md-9">
		<div class="portlet light bordered" style="border:2px solid #666 !important;">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Medical Record of Patient Hemodialysis", $this->session->userdata("language"))?></span>
				</div>
			</div>
	<?php
	if($data_item_resep != 0){
		?>
		<div class="note note-danger note-bordered">
		<p>
			<b> PERINGATAN: </b>Terdapat satu atau beberapa obat/vitamin dalam resep yang belum diinput! 
		</p>
	</div>
		<?php
	}
	?>
							<input class="form-control hidden" id="item_resep" name="item_resep" value="<?=$data_item_resep?>">
	<input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$form_tindakan['id']?>">
	<input class="form-control hidden" id="tindakan_hd_penaksiran_id" name="tindakan_hd_penaksiran_id" value="<?=$form_assesment[0]['id']?>">
	<input class="form-control hidden" id="bed_id" name="bed_id" value="<?=$pk_value?>">
	<input class="form-control hidden" id="pasien_id" name="pasien_id" value="<?=$form_pasien['id']?>">

	<div class="portlet-body">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-md-3 col-sm-12 bold"><?=translate("Date", $this->session->userdata("language"))?> :</label>
					<label class="col-md-9 col-sm-12"><?=date("d F Y", strtotime($form_assesment[0]['tanggal']))?></label>
					

				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-12 bold"><?=translate("Alergic", $this->session->userdata("language"))?> :</label>
					<label class="col-md-9 col-sm-12"><label class="checkbox-inline" style="padding-left:0px">
					<input type="checkbox" id="medicine" value="1" disabled <?=$check_med?>> Medicine </label>
												<label class="checkbox-inline" style="padding-left:0px">
													<input type="checkbox" id="food" value="1" disabled <?=$check_food?>> Food </label>	</label>
					

				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-12 bold"><?=translate("Assesment GCS", $this->session->userdata("language"))?> :</label>
					<label class="col-md-9 col-sm-12"><?=preg_replace('/(\r\n|\n|\r|\f)/U', "<br />", $form_tindakan_hd_penaksiran_view['assessment_cgs'])?>
</label>
					

				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="col-md-3 col-sm-12 bold"><?=translate("Time", $this->session->userdata("language"))?> :</label>
					<label class="col-md-9 col-sm-12"><?=$form_tindakan_hd_penaksiran_view['waktu']?></label>
					

				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-12 bold"><?=translate("Medical Diagnose", $this->session->userdata("language"))?> :</label>
					<label class="col-md-9 col-sm-12"><?=preg_replace('/(\r\n|\n|\r|\f)/U', "<hr color='black' style='margin-top:0;margin-bottom:5px'>&nbsp;", $form_tindakan_hd_penaksiran_view['medical_diagnose'])?></label>
					

				</div>
			</div>
			<div class="col-md-4" style="background-color:#eee;padding:10px;border-radius:2px;">
				<div class="form-group">
					<label class="col-md-4 col-sm-12 bold"><?=translate("Name", $this->session->userdata("language"))?> :</label>
					<label class="col-md-8 col-sm-12 blue"><b><?=$form_pasien['nama']?></b></label>
					

				</div>
				<div class="form-group">
					<label class="col-md-4 col-sm-12 bold"><?=translate("No. Medrec", $this->session->userdata("language"))?> :</label>
					<label class="col-md-8 col-sm-12"><?=$form_pasien['no_member']?></label>
					

				</div>
				<div class="form-group">
					<label class="col-md-4 col-sm-12 bold"><?=translate("Place / Born Date", $this->session->userdata("language"))?> :</label>
					<label class="col-md-8 col-sm-12"><?=$form_pasien['tempat_lahir']?> / <?=date('d M Y',strtotime($form_pasien['tanggal_lahir']))?></label>
					

				</div>
				<div class="form-group">
					<label class="col-md-4 col-sm-12 bold"><?=translate("Age", $this->session->userdata("language"))?> :</label>
					<label class="col-md-8 col-sm-12"><?php 
													$umur = date_diff(date_create($form_pasien['tanggal_lahir']), date_create('today'))->y;

													if ($umur < 1) {
														$umur = translate('Dibawah 1 tahun', $this->session->userdata('language'));
													}

													echo $umur;
												?> </label>
					

				</div>
				<div class="form-group">
					<label class="col-md-4 col-sm-12 bold"><?=translate("Dry Weight", $this->session->userdata("language"))?> :</label>
					<label class="col-md-8 col-sm-12"><?=$form_pasien['berat_badan_kering']?></label>
					

				</div>
			</div>
		</div>

		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?=translate("Dialysis Program", $this->session->userdata("language"))?>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 col-sm-12 bold"><?=translate("Time of Dialysis", $this->session->userdata("language"))?> :</label>
							<label class="col-md-9 col-sm-12"><?=$form_tindakan_hd_penaksiran_view['time_of_dialysis']?> Hours</label>
							

						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-12 bold"><?=translate("Quick of Blood", $this->session->userdata("language"))?> :</label>
							<label class="col-md-9 col-sm-12"><?=$form_tindakan_hd_penaksiran_view['quick_of_blood']?> ml/Hours</label>
							

						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-12 bold"><?=translate("Quick of Dialysate", $this->session->userdata("language"))?> :</label>
							<label class="col-md-9 col-sm-12"><?=$form_tindakan_hd_penaksiran_view['quick_of_dialysis']?> ml/Hours</label>
							

						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-12 bold"><?=translate("UF Goal", $this->session->userdata("language"))?> :</label>
							<label class="col-md-9 col-sm-12"><?=$form_tindakan_hd_penaksiran_view['uf_goal']?> L</label>
							

						</div>

					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-4 col-sm-12 bold"><?=translate("Heparin", $this->session->userdata("language"))?> :</label>
							<label class="col-md-8 col-sm-12">
<label class="checkbox" style="padding-left:0px"><input type="checkbox" id="regular" value="1" disabled <?=$check_reg?>> Regular</label>
							<label class="checkbox" style="padding-left:0px"><input type="checkbox" id="minimal" value="1" disabled <?=$check_min?>> Minimal</label>
							<label class="checkbox" style="padding-left:0px"><input type="checkbox" id="free" value="1" disabled <?=$check_free?>> Free</label>
							</label>

							
							

						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-4 col-sm-12 bold"><?=translate("Dose", $this->session->userdata("language"))?> :</label>
							<label class="col-md-8 col-sm-12"><?=$form_tindakan_hd_penaksiran_view['dose']?></label>
						</div>
						<div class="form-group">
							<label class="col-md-4 col-sm-12 bold"><?=translate("First", $this->session->userdata("language"))?> :</label>
							<label class="col-md-8 col-sm-12"><?=$form_tindakan_hd_penaksiran_view['first']?> U</label>
						</div>
						<div class="form-group">
							<label class="col-md-4 col-sm-12 bold"><?=translate("Maintenance", $this->session->userdata("language"))?> :</label>
							<label class="col-md-8 col-sm-12"><?=$form_tindakan_hd_penaksiran_view['maintenance']?> U/ <?=$form_tindakan_hd_penaksiran_view['hours']?> Hours</label>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?=translate("Profile Hemodialysis", $this->session->userdata("language"))?>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-md-4 col-sm-12 bold"><?=translate("Machine No.", $this->session->userdata("language"))?></label>
							<label class="col-md-8 col-sm-12">: <?=$form_tindakan_hd_penaksiran_view['machine_no']?></label>
						</div>
						<div class="form-group">
							<label class="col-md-4 col-sm-12 bold"><?=translate("Blood Access", $this->session->userdata("language"))?></label>
							<label class="col-md-8 col-sm-12">
<div class="checkbox-list" style="float:left;">
										<label class="checkbox">
										<input type="checkbox" id="av_shunt" value="1" disabled <?=$check_av?>> AV Shunt </label>
										<label class="checkbox">
										<input type="checkbox" id="femoral" value="1" disabled <?=$check_femoral?>> Femoral </label>
										<label class="checkbox">
										<input type="checkbox" id="double_lument" value="1" disabled <?=$check_catheter?>> Catheter Double Lument </label>
									</div>							</label>

							
							

						</div>

					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-md-4 col-sm-12 bold"><?=translate("Type of Dialyzer", $this->session->userdata("language"))?></label>
							<label class="col-md-8 col-sm-12">
<div class="checkbox-list">
										<label class="checkbox-inline">
										<input type="checkbox" id="new" value="1" disabled <?=$check_new?>> New </label>
										<label class="checkbox-inline">
										<input type="checkbox" id="reuse" value="1" disabled <?=$check_reuse?>> Reuse </label>
									</div>
						</label>

							
							

						</div>
						<div class="form-group">
							<label class="col-md-4 col-sm-12 bold"><?=translate("Type of Dialysate", $this->session->userdata("language"))?></label>
							<label class="col-md-8 col-sm-12">
<div class="checkbox-list">
										<label class="checkbox-inline">
											<input type="checkbox" id="bicarbonate" value="1" disabled <?=$check_dialysate?>> Bicarbonate 
										</label>
									</div>
						</label>

							
							

						</div>

					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-md-4 col-sm-12 bold"><?=translate("Dialyzer", $this->session->userdata("language"))?></label>
							<label class="col-md-8 col-sm-12">: <?=$form_tindakan_hd_penaksiran_view['dialyzer']?></label>
						</div>

					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-12 col-sm-12 bold"><?=translate("Problem Found", $this->session->userdata("language"))?> :</label>
							<label class="col-md-12 col-sm-12">
<div class="checkbox-list">
										<?php
											$i = 1;
											for ($x=0;$x<=5;$x++) 
											{
												$checked = '';
												if($pasien_problem_view[$x]['nilai'] == 1)
												{
													$checked = 'checked = "checked"';
												}

												echo '<label ><input type="checkbox" disabled id="problem_'.$pasien_problem_view[$x]['problem_id'].'" name="problem_'.$pasien_problem_view[$x]['problem_id'].'" value="'.$pasien_problem_view[$x]['problem_id'].'" '.$checked.'> '.$problem_name[$i].'</label><br>';
												$i++;
											}
										?>
									</div>
						</label>

							
							

						</div>

					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-12 col-sm-12 bold"><?=translate("Complication During Hemodialysis", $this->session->userdata("language"))?> :</label>
							<label class="col-md-6 col-sm-12">
<div class="checkbox-list">
										<?php
											$i = 1;
											for ($x=0;$x<=5;$x++)
											{
												$checked = '';
												if($pasien_komplikasi_view[$x]['nilai'] == 1)
												{
													$checked = 'checked = "checked"';
												}

												echo '<label ><input type="checkbox" disabled id="complication_'.$pasien_komplikasi_view[$x]['complication_id'].'" name="complication_'.$pasien_komplikasi_view[$x]['complication_id'].'" value="'.$pasien_komplikasi_view[$x]['complication_id'].'" '.$checked.'> '.$complication_name[$i].'</label><br>';
												$i++;
											}
										?>
									</div>
						</label>
						<label class="col-md-6 col-sm-12">
<div class="checkbox-list">
										<?php
											$i = 7;
											for ($x=6;$x<=8;$x++)
											{
												$checked = '';
												if($pasien_komplikasi_view[$x]['nilai'] == 1)
												{
													$checked = 'checked = "checked"';
												}

												echo '<label ><input type="checkbox" disabled id="complication_'.$pasien_komplikasi_view[$x]['complication_id'].'" name="complication_'.$pasien_komplikasi_view[$x]['complication_id'].'" value="'.$pasien_komplikasi_view[$x]['complication_id'].'" '.$checked.'> '.$complication_name[$i].'</label><br>';
												$i++;
											}

											for($y=1;$y<=3;$y++)
											{
												echo '<label ><input type="hidden" disabled id="" name="" value=""> </label><br>';
											}
										?>
										


									</div>						</label>


							
							

						</div>

					</div>
				</div>
			</div>

		</div>

			<form>
			<table width="100%" border="0" class="text" >

			
				<tr>
					<td>					</td>
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
											<td style="border-bottom: 0.5px solid #bfbfbf" width="80px" ><?=$form_tindakan_hd_penaksiran_view['remaining_of_priming']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>2.</td>
											<td>Wash Out</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran_view['wash_out']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>3.</td>
											<td>Drip of fluid</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran_view['drip_of_fluid']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>4.</td>
											<td>Blood</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran_view['blood']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>5.</td>
											<td>Drink</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran_view['drink']?></td>
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
											<td style="border-bottom: 0.5px solid #bfbfbf" width="80px" ><?=$form_tindakan_hd_penaksiran_view['vomiting']?></td>
											<td>cc</td>
										</tr>
										<tr>
											<td>2.</td>
											<td>Urinate</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf"><?=$form_tindakan_hd_penaksiran_view['urinate']?></td>
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
											<td style="border-bottom: 0.5px solid #bfbfbf" width="80px" ><?=$form_tindakan_hd_penaksiran_view['transfusion_type']?></td>
											<td></td>
										</tr>
										<tr>
											<td>2.</td>
											<td>Quantity</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran_view['transfusion_qty']?></td>
											<td>Bag</td>
										</tr>
										<tr>
											<td>3.</td>
											<td>Blood Type</td>
											<td>:</td>
											<td style="border-bottom: 0.5px solid #bfbfbf" ><?=$form_tindakan_hd_penaksiran_view['transfusion_blood_type']?></td>
											<td></td>
										</tr>
										<tr>
											<td valign="top">4.</td>
											<td valign="top">Serial No</td>
											<td valign="top">:</td>
											<td valign="top"><?=preg_replace('/(\r\n|\n|\r|\f)/U', "<br />", $form_tindakan_hd_penaksiran_view['serial_number'])?></td>
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
					<th class="text-center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=translate('TMP', $this->session->userdata('language'))?></th>
					<th class="text-center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=translate('VP', $this->session->userdata('language'))?></th>
					<th class="text-center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=translate('AP', $this->session->userdata('language'))?></th>
					<th class="text-center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=translate('Cond', $this->session->userdata('language'))?></th>
					<th class="text-center" width="70px" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;"><?=translate('Temperature', $this->session->userdata('language'))?></th>
					<td align="center" style="border-bottom: 0.5px solid #bfbfbf;border-right: 0.5px solid #bfbfbf; font-size: 10px;">Nurse</td>
					<td align="center"  width="200px" style="border-bottom: 0.5px solid #bfbfbf; font-size: 10px;">Explanation</td>
				</tr>
				<?foreach($form_observasi_view as $observasi){

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
							if($form_medicine_view != '0')
							{
								foreach($form_medicine_view as $medicine)
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
								 
								<?=preg_replace('/(\r\n|\n|\r|\f)/U', "<hr color=#bfbfbf style=margin-top:0;margin-bottom:5>&nbsp;", $form_tindakan_hd_penaksiran_view['laboratory'])?><hr color="#bfbfbf" style="margin-top:0;margin-bottom:0">
							 
							</td>
						</tr>
						 
						<tr>
							<td class="text2" style="font-size: 10px;">ECG :</td>
						</tr>
						<tr>
							<td style="font-size: 10px;"><?=preg_replace('/(\r\n|\n|\r|\f)/U', "<hr color=#bfbfbf style=margin-top:0;margin-bottom:5>&nbsp;", $form_tindakan_hd_penaksiran_view['ecg'])?> <hr color="#bfbfbf" style="margin-top:0;margin-bottom:0"></td>
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
				<td width="20%" style="font-size:10px;"><?=$form_tindakan_hd_penaksiran_view['priming']?></td>
				<td width="12%" style="font-size:10px;">Initiation</td>
				<td width="1%" style="font-size:10px;">:</td>
				<td width="20%" style="font-size:10px;"><?=$form_tindakan_hd_penaksiran_view['initiation']?></td>
				<td width="12%" style="font-size:10px;">Termination</td>
				<td width="1%" style="font-size:10px;">:</td>
				<td width="20%" style="font-size:10px;"><?=$form_tindakan_hd_penaksiran_view['termination']?></td>
			</tr>
		
		</table>

		</form>
	</div>





			
		</div>
	</div>
	<div class="col-md-3">
		<div class="portlet box yellow" style="padding-bottom: 0px !important;">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Selesai Tindakan", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body">
				<div class="row">
				<div class="col-md-12">
					<div class="survey_pertanyaan">
						<!-- <div class="form-group"> -->
								<?php 
									$survey = $this->pertanyaan_surey_m->get_by(array('tipe' => 1, 'poliklinik_id' => 1, 'is_active' => 1));
									// die_dump($survey);
									
									$i = 1;
									foreach ($survey as $data_survey) 
									{
										echo '<div class="form-group">';
										echo '<label class="col-md-6 bold">';
										echo $data_survey->pertanyaan.' :</label>';
										echo '<input class="form-control hidden" id="survey_id_'.$i.'" name="pertanyaan['.$i.'][survey_id]" value="'.$data_survey->id.'">';
										echo '<input class="form-control hidden" id="nilai_'.$i.'" name="pertanyaan['.$i.'][nilai]">';


										echo '<div class="col-md-6">
												<div class="radio-list">';
										for ($z = $data_survey->range_awal ; $z <= $data_survey->range_akhir ; $z++) 
										{ 
											if ($z == 0) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="0"> ';
												echo translate('Sangat Buruk', $this->session->userdata('language'));
												echo '</label>';
											}
											if ($z == 1) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="1"> ';
												echo translate('Buruk', $this->session->userdata('language'));
												echo '</label>';
											}
											if ($z == 2) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="2"> ';
												echo translate('Biasa', $this->session->userdata('language'));
												echo '</label>';
											}
											if ($z == 3) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="3"> ';
												echo translate('Baik', $this->session->userdata('language'));
												echo '</label>';
											}
											if ($z == 4) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="4"> ';
												echo translate('Sangat Baik', $this->session->userdata('language'));
												echo '</label>';
											}

										}

										echo '</div>';
										echo '</div>';
										echo '</div>';

										$i++;
									}
								?>
							
						<!-- </div>	 -->
					</div>
					</div>
					<div class="col-md-12">
					<div class="form-group">
						<label class="col-md-6 bold"><?=translate('Berat Badan Awal', $this->session->userdata('language'))?> :</label>
						<div class="col-md-6">
							<label class="control-label"><?=$form_tindakan['berat_awal']?> Kg</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-6 bold"><?=translate('Berat Badan Kering', $this->session->userdata('language'))?> :</label>
						<div class="col-md-6">
							<label class="control-label"><?=$form_pasien['berat_badan_kering']?> Kg</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-6 bold"><?=translate('Berat Badan Akhir', $this->session->userdata('language'))?> :</label>
						<div class="col-md-6">
							<div class="input-group">
								<input class="form-control" id="berat_akhir" name="berat_akhir" required>
								<span class="input-group-addon">
									&nbsp;Kg&nbsp;
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-6 bold"><?=translate('Gunakan Dializer Untuk Tindakan Berikutnya', $this->session->userdata('language'))?> :</label>
						<div class="col-md-6">
							<div class="radio-list">
								<label class="radio-inline">
								<input type="radio" name="reuse_dializer" id="ya" value="1" checked> <?=translate('Ya', $this->session->userdata('language'))?></label>
								<label class="radio-inline">
								<input type="radio" name="reuse_dializer" id="tidak" value="0"> <?=translate('Tidak', $this->session->userdata('language'))?> </label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-6 bold"><?=translate('Keluhan', $this->session->userdata('language'))?> :</label>
						<div class="col-md-6">
							<textarea class="form-control" name="keluhan_selesai" rows="5"></textarea>
						</div>
					</div>	
					<div class="form-group hidden">
						<label class="control-label col-md-6"><?=translate('Assesment', $this->session->userdata('language'))?> :</label>
						<div class="col-md-6">
							<textarea class="form-control" readonly name="assessment_cgs_selesai" rows="5"><?=$form_assesment[0]['assessment_cgs']?></textarea>
						</div>
					</div>	
				</div>	
				</div>	

					
				</div>
			</div>
	</div>
</div>

		<div class="form-actions hidden">
			<button class="btn btn-primary" id="simpan_selesai_tindakan">Simpan</button>
		</div>
	</div>		
</div>