<?php 
	$form_attr = array(
		"id"            => "form_assesment", 
		"name"          => "form_assesment", 
		"autocomplete"  => "off", 
		"class"         => "form-horizontal",
		"role"			=> "form"
	);
	$hidden = array(
		"command"   => "assesment"
	);
	echo form_open(base_url()."klinik_hd/transaksi_perawat/as", $form_attr, $hidden);

	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	$msg = translate('Apakah anda yakin akan menyimpan assesment ini?', $this->session->userdata('language'));
?>
<div class="portlet" id="assesment">
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Assessment of Patient Hemodialysis", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
				<a class="btn btn-circle btn-default edit_assesment"><i class="fa fa-edit"></i> <?=translate('Edit', $this->session->userdata('language'))?></a>
				<a class="btn btn-circle btn-primary simpan_assesment" data-confirm="<?=$msg?>" data-toggle="modal" style="display:none;"><i class="fa fa-check"></i> <?=translate('Simpan', $this->session->userdata('language'))?></a>
				<button id="simpan" class="hidden"></button>
			</div>
	
		</div>
		<div class="portlet-body form">
		 	
		<!-- BEGIN FORM-->
		 
			<div class="form-body">
				<div class="row">
					<div class="col-md-6">

						<div class="form-group hidden">
							<label class="control-label col-md-3"><?=translate("ID :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<?php
					                $id = array(
					                    "name"			=> "id_tindakan_penaksiran",
					                    "id"			=> "id_tindakan_penaksiran",
										"size"			=> "16",
					                    "class"			=> "form-control", 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['id']
					                );
					                echo form_input($id);

					                $tindakan_id = array(
					                    "name"			=> "id_tindakan",
					                    "id"			=> "id_tindakan",
										"size"			=> "16",
					                    "class"			=> "form-control", 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['tindakan_hd_id']
					                );
					                echo form_input($tindakan_id);
					            ?>	
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Date :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<?php
					                $date = array(
					                    "name"			=> "tanggal_",
					                    "id"			=> "tanggal_",
										"size"			=> "16",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Date", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> date("d F Y", strtotime($form_assesment[0]['tanggal']))
					                );
					                echo form_input($date);
					            ?>	
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Time :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<?php
					                $time = array(
					                    "name"			=> "waktu_",
					                    "id"			=> "waktu_",
										"size"			=> "16",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Time", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['waktu']
					                );
					                echo form_input($time);
					            ?>		
							</div>
						</div>
					
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Alergic :", $this->session->userdata("language"))?></label>
							 
							<div class="col-md-5">
								 	
								 	<?php 
								 		$checked = '';
								 		$checked2 = '';
								 		if ($form_assesment[0]['alergic_food']) {
								 			$checked = 'checked';
								 		}
								 		if ($form_assesment[0]['alergic_medicine']) {
								 			$checked2 = 'checked';
								 		}
								 	?>
									<div class="checkbox-list">
									 	<label class="checkbox-inline">
											<input class="" type="checkbox" id="alergic_medicine" name="alergic_medicine" <?=$checked2?> value="1" disabled>
									 		 Medicine
									 	</label>
									 	<label class="checkbox-inline">
											<input class="" type="checkbox" id="alergic_food" name="alergic_food" <?=$checked?> disabled> Food  
									 	</label>
								 	</div>
								 
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Assessment GCS :", $this->session->userdata("language"))?> </label>
							
							<div class="col-md-6">
								<?php
									$assessment_cgs = array(
					                    "name"			=> "assessment_cgs_",
					                    "id"			=> "assessment_cgs_",
										"rows"			=> 8,
					                    "class"			=> "form-control",
					                    "placeholder"	=> translate("Assessment GCS", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['assessment_cgs']
					                );
				                echo form_textarea($assessment_cgs);
								?>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Medical Diagnose :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<?php
									$medical_diagnose = array(
					                    "name"			=> "medical_diagnose_",
					                    "id"			=> "medical_diagnose_",
										"rows"			=> 8,
					                    "class"			=> "form-control",
					                    "placeholder"	=> translate("Medical Diagnose", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['medical_diagnose']
					                );
				                echo form_textarea($medical_diagnose);
								?>	
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<h4 class="form-section"><?=translate("Dialysis Program", $this->session->userdata("language"))?></h4>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Blood Preasure Pre HD :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
							<?php
								$bp_array = explode('_', $form_assesment[0]['blood_preasure']);
							?>
								<label class="control-label"><?=$bp_array[0]?>/<?=$bp_array[1]?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Time of Dialysis :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
								<?php
					                $time_of_dialysis = array(
					                    "name"			=> "time_of_dialysis_",
					                    "id"			=> "time_of_dialysis_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Time of Dialysis", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['time_of_dialysis']
					                );
					                echo form_input($time_of_dialysis);
					            ?>	
									<span class="input-group-addon">
										<i>&nbsp;Hour(s)&nbsp;</i>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Quick of Blood :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
								<?php
					                $quick_of_blood = array(
					                    "name"			=> "quick_of_blood_",
					                    "id"			=> "quick_of_blood_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Quick of Blood", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=>  $form_assesment[0]['quick_of_blood']
					                );
					                echo form_input($quick_of_blood);
					            ?>
					            	<span class="input-group-addon">
										<i>&nbsp;ml/Hour&nbsp;</i>
									</span>
								</div>		
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Quick of Dialysate :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
								<?php
					                $quick_of_dialysate = array(
					                    "name"			=> "quick_of_dialysate_",
					                    "id"			=> "quick_of_dialysate_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Quick of Dialysate", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['quick_of_dialysis']
					                );
					                echo form_input($quick_of_dialysate);
					            ?>	
						            <span class="input-group-addon">
										<i>&nbsp;ml/Hour&nbsp;</i>
									</span>
								</div>	
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("UF Goal :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
								<?php
					                $uf_goal = array(
					                    "name"			=> "uf_goal_",
					                    "id"			=> "uf_goal_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("UF Goal", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['uf_goal']
					                );
					                echo form_input($uf_goal);
					            ?>		
					            	<span class="input-group-addon">
										<i>&nbsp;Liter(s)&nbsp;</i>
									</span>
								</div>
							</div>

						</div>
						
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Heparin :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								
								<?php 
									
							 		($form_assesment[0]['heparin_reguler']) ? $regular = 'checked' : $regular 	= '';
							 		($form_assesment[0]['heparin_minimal']) ? $minimal = 'checked' : $minimal 	= '';
							 		($form_assesment[0]['heparin_free']) ? $free = 'checked' : $free 	= '';

								?>
								<div class="checkbox-list">
								 	<label class="checkbox-inline">
										<input  type="checkbox" id="regular_" value="1" <?=$regular?> disabled class=""> Regular  
								 	</label>
								 	<label class="checkbox-inline">
										<input type="checkbox" id="minimal_" value="1" <?=$minimal?> disabled class=""> Minimal  
								 	</label>
								 	<label class="checkbox-inline">
										<input type="checkbox" id="free_" value="1" <?=$free?> disabled  class=""> Free  
								 	</label>
							 	</div>  
								 
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Dose :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								
								<?php
					                $dose = array(
					                    "name"			=> "dose_",
					                    "id"			=> "dose_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Dose", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['dose']
					                );
					                echo form_input($dose);
					            ?>		
					            	
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("First :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
								<?php
					                $first = array(
					                    "name"			=> "first_",
					                    "id"			=> "first_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("First", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['first']
					                );
					                echo form_input($first);
					            ?>		
					            	<span class="input-group-addon">
										<i> U </i>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Maintenance :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
								<?php
					                $maintenance = array(
					                    "name"			=> "maintenance_",
					                    "id"			=> "maintenance_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Maintenance", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['maintenance']
					                );
					                echo form_input($maintenance);
					            ?>		
					            	<span class="input-group-addon">
										<i> U / </i>
									</span>
									<input type="text" class="form-control" id="hour_" name="hour_" placeholder="Hour" value="<?=$form_assesment[0]['hours']?>" readonly>
									<span class="input-group-addon">
										<i> Hour </i>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12" style="margin-top: 15px;">
						<h4 class="form-section"><?=translate("Profile Hemodialysis", $this->session->userdata("language"))?></h4>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Machine No. :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<label class="control-label"><?=$form_assesment[0]['machine_no']?></label>
								
									
									<?php
						                $machine_no = array(
						                    "name"			=> "machine_no_",
						                    "id"			=> "machine_no_",
						                    "type"			=> "hidden",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Machine No.", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    "value"			=> $form_assesment[0]['machine_no']
						                );
						                echo form_input($machine_no);
						            ?>		
								
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Type of Dialyzer :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<?php 
									
							 		($form_assesment[0]['dialyzer_new']) ? $new = 'checked' : $new 	= '';
							 		($form_assesment[0]['dialyzer_reuse']) ? $reuse = 'checked' : $reuse 	= '';

								?>
									 
								<div class="radio-list">
									<label class="radio-inline">
									<input type="radio" id="new_" value="1" <?=$new?> name="dializer" disabled class=""> New</label>
									<label class="radio-inline">
									<input type="radio" id="reuse_" value="1" <?=$reuse?> name="dializer" disabled class=""> Reuse </label>
								</div> 
								 
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Dialyzer :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								
								<?php
					                $dialyzer = array(
					                    "name"			=> "dialyzer_",
					                    "id"			=> "dialyzer_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Dialyzer", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['dialyzer']
					                );
					                echo form_input($dialyzer);
					            ?>		
					            	
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Blood Access :", $this->session->userdata("language"))?></label>
							<div class="col-md-8">
								<?php 
									
							 		($form_assesment[0]['ba_avshunt']) ? $av_shunt = 'checked' : $av_shunt 	= '';
							 		($form_assesment[0]['ba_femoral']) ? $femoral = 'checked' : $femoral 	= '';
							 		($form_assesment[0]['ba_catheter']) ? $double_lument = 'checked' : $double_lument 	= '';

								?>

								<div class="checkbox-list">
								 	<label class="checkbox-inline">
										<input type="checkbox" id="av_shunt_" value="1" <?=$av_shunt?> disabled class="" > AV Shunt  
								 	</label>
								 	<label class="checkbox-inline">
										<input type="checkbox" id="femoral_" value="1" <?=$femoral?> disabled class=""> Femoral 
								 	</label>
								 	<label class="checkbox-inline">
										<input type="checkbox" id="double_lument_" value="1" <?=$double_lument?> disabled class=""> Catheter Double Lument
								 	</label>
							 	</div> 
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Type of Dialysate :", $this->session->userdata("language"))?></label>
							<div class="col-md-6">
								<?php 
									
							 		($form_assesment[0]['dialyzer_type']) ? $bicarbonate = 'checked' : $bicarbonate = '';

								?>

								<div class="checkbox-list">
								 	<label class="checkbox-inline">
										<input type="checkbox" id="bicarbonate_" value="1" <?=$bicarbonate?> disabled class="" > Bicarbonate
								 	</label>
							 	</div>  
							</div>
						</div>
					</div>
					
					<div class="col-md-12" style="margin-top: 15px;"></div>
					<div class="col-md-6">
						<h4 class="form-section"><?=translate("Problems Found", $this->session->userdata("language"))?></h4>
						<div class="form-group">									
							<div class="col-md-6">
								 <?php

									$data_problem = array(
										'1'	=> 'Airway Clearance, ineffective',
										'2'	=> 'Fluid balance',
										'3'	=> 'High risk of infection',
										'4'	=> 'Impaired sense of comfort pain',
										'5'	=> 'Disequilibrium Syndrome',
										'6'	=> 'Shock Risk'
									);

									$x=0;
									foreach ($form_problem as $row) 
									{
										$x++;
										$check = '';
										if($row['nilai'] == 1)
										{
											$check = 'checked';
										}

										echo '<div class="checkbox-list">
												 	<label>
														<input type="checkbox" id="problem_'.$x.'" disabled '.$check.' value="'.$x.'" class="pasien_problem" >'.$data_problem[$x].'
												 	</label>
											 	</div>';
									}
								?>
							</div>
						</div>
					</div>


					<div class="col-md-6">
						<h4 class="form-section"><?=translate("Complications During Hemodialysis", $this->session->userdata("language"))?></h4>
						<div class="form-group">									
							<div class="col-md-6">
									<?php
									 
									$data_komplikasi = array(
										'1'	=> 'Bleeding',
										'2'	=> 'Pruritus',
										'3'	=> 'Alergie',
										'4'	=> 'Headache',
										'5'	=> 'Nausea',
										'6'	=> 'Chest Pain',
										'7'	=> 'Hypotension',
										'8'	=> 'Shiver',
										'9'	=> 'Etc'
									);

									$y=0;
									foreach ($form_komplikasi as $row) {

										$y++;
										$check = '';
										if($row['nilai'] == 1)
										{
											$check = 'checked';
										}

										echo '<div class="checkbox-list">
												 	<label>
														<input type="checkbox" id="komplikasi_'.$y.'" '.$check.' disabled value="'.$y.'" class="pasien_komplikasi">'.$data_komplikasi[$y].'
												 	</label>
											 	</div>';
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?=form_close();?>
<!-- END FORM-->