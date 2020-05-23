 <div id="asses2">
 <div class="portlet light">
 <div class="portlet-title">	 
	<div class="caption">
		<?php
			$i = 1;
			$cabang_id = '';
			$transaksi_id = '';
			$tipe = '';
			if(count($data_sejarah))
			{
				foreach ($data_sejarah as $sejarah) 
				{
					echo '<input type="hidden" id="cabang_id_'.$i.'" name="cabang_id_'.$i.'" value="'.$sejarah['cabang_id'].'"><input type="hidden" id="tipe_trans_'.$i.'" name="tipe_trans_'.$i.'" value="'.$sejarah['tipe'].'"><input type="hidden" id="transaksi_id_'.$i.'" name="transaksi_id_'.$i.'" value="'.$sejarah['transaksi_id'].'">';

					$i++;
				}

				$cabang_id = $data_sejarah[0]['cabang_id'];
				$transaksi_id = $data_sejarah[0]['transaksi_id'];
				$tipe = $data_sejarah[0]['tipe'];
			}
		?>
		<input type="hidden" id="last_index" name="last_index" value="<?=$i-1?>">
		<input type="hidden" id="curr_index" name="curr_index" value="1">
		<input type="hidden" id="tipe_trans" name="tipe_trans" value="<?=$tipe?>">
		<input type="hidden" id="tindakanhdid" name="tindakanhdid" value="<?=$transaksi_id?>">
		<input type="hidden" id="cabang_id" name="cabang_id" value="<?=$cabang_id?>">

		<span class="caption-subject font-blue-sharp bold uppercase"><div id="tgglpage"></div></span>
	</div>
	<div class="actions hidden">
            <a id="first1" class="btn btn-sm grey-cascade" ><i class="fa fa-fast-backward"></i></a>
            <a id="prev" class="btn btn-sm grey-cascade"> <i class="fa fa-backward"></i></a>
            <a id="next" class="btn btn-sm grey-cascade" ><i class="fa fa-forward"></i></a>
            <a id="last1" class="btn btn-sm grey-cascade" ><i class="fa fa-fast-forward"></i></a>	 
    </div>
</div>

</div>
 <div class="portlet" id="asses">
 		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Assessment of Patient Hemodialysis", $this->session->userdata("language"))?></span>
				</div>
		
			</div>
		</div>
		<div class="portlet-body form">
			 	
			<!-- BEGIN FORM-->
			 
				<div class="form-body">
					<div class="row">
						<div class="col-md-6 pull-left">

							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Date :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<?php
						                $date = array(
						                    "name"			=> "datesejarah",
						                    "id"			=> "datesejarah",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Date", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> date("d M Y", strtotime($date_value))
						                );
						                echo form_input($date);
						            ?>	
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Time :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<?php
						                $time = array(
						                    "name"			=> "time",
						                    "id"			=> "time",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Time", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $time_value
						                );
						                echo form_input($time);
						            ?>		
								</div>
							</div>
						
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Alergic :", $this->session->userdata("language"))?></label>
								 
								<div class="col-md-6">
									 
										 
										<input type="checkbox" id="medicine2" name="medicine2" value="1"  disabled class="make-switch" > Medicine
										 &nbsp;&nbsp;
										<input type="checkbox" id="food2" value="1" disabled class="make-switch"  value="1" > Food  
									 
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Assessment GCS :", $this->session->userdata("language"))?> </label>
								
								<div class="col-md-6">
									<?php
										$assessment_cgs = array(
						                    "name"			=> "assessment_cgs",
						                    "id"			=> "assessment_cgs",
						                    "cols"			=> 32,
											"rows"			=> 5,
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control",
						                    "placeholder"	=> translate("Assessment GCS", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $assessment_cgs_value
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
						                    "name"			=> "medical_diagnose",
						                    "id"			=> "medical_diagnose",
						                    "cols"			=> 32,
											"rows"			=> 5,
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control",
						                    "placeholder"	=> translate("Medical Diagnose", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $medical_diagnose_value
						                );
					                echo form_textarea($medical_diagnose);
									?>	
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
							<h4 class="form-section"><?=translate("Dialysis Program", $this->session->userdata("language"))?></h3>
						</div>

						<div class="col-md-6 pull-left">

							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Time of Dialysis :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
									<?php
						                $time_of_dialysis = array(
						                    "name"			=> "time_of_dialysis",
						                    "id"			=> "time_of_dialysis",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Time of Dialysis", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $time_of_dialysis_value
						                );
						                echo form_input($time_of_dialysis);
						            ?>	
										<span class="input-group-addon">
											<i> Hour(s) </i>
										</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Quick of Blood :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
									<?php
						                $quick_of_blood = array(
						                    "name"			=> "quick_of_blood",
						                    "id"			=> "quick_of_blood",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Quick of Blood", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $quick_of_blood_value
						                );
						                echo form_input($quick_of_blood);
						            ?>
						            	<span class="input-group-addon">
											<i>ml/Hour</i>
										</span>
									</div>		
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Quick of Dialysate :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
									<?php
						                $quick_of_dialysate = array(
						                    "name"			=> "quick_of_dialysate",
						                    "id"			=> "quick_of_dialysate",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Quick of Dialysate", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $quick_of_dialysis_value
						                );
						                echo form_input($quick_of_dialysate);
						            ?>	
							            <span class="input-group-addon">
											<i>ml/Hour</i>
										</span>
									</div>	
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("UF Goal :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
									<?php
						                $uf_goal = array(
						                    "name"			=> "uf_goal",
						                    "id"			=> "uf_goal",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("UF Goal", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $uf_goal_value
						                );
						                echo form_input($uf_goal);
						            ?>		
						            	<span class="input-group-addon">
											<i> Liter(s) </i>
										</span>
									</div>
								</div>

							</div>
							
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Heparin :", $this->session->userdata("language"))?></label>
									
									<div class="col-md-6">
										 
											 
											<input type="checkbox" id="regular" value="1" disabled class="make-switch"> Regular  
									 		&nbsp;&nbsp;
											<input type="checkbox" id="minimal" value="1" disabled class="make-switch"> Minimal  
											 &nbsp;&nbsp;
											<input type="checkbox" id="free" value="1" disabled  class="make-switch"> Free  
										 
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Dose :", $this->session->userdata("language"))?></label>
									
									<div class="col-md-6">
										
										<?php
							                $dose = array(
							                    "name"			=> "dose",
							                    "id"			=> "dose",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Dose", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $dose_value
							                );
							                echo form_input($dose);
							            ?>		
							            	
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("First :", $this->session->userdata("language"))?></label>
									
									<div class="col-md-6">
										<div class="input-group">
										<?php
							                $first = array(
							                    "name"			=> "first",
							                    "id"			=> "first",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("First", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $first_value
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
									
									<div class="col-md-6">
										<div class="input-group">
										<?php
							                $maintenance = array(
							                    "name"			=> "maintenance",
							                    "id"			=> "maintenance",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Maintenance", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $maintenance_value
							                );
							                echo form_input($maintenance);
							            ?>		
							            	<span class="input-group-addon">
												<i> U / </i>
											</span>
											<input type="text" class="form-control" id="hour" name="hour" placeholder="Hour" value="" readonly>
											<span class="input-group-addon">
												<i> Hour </i>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<h4 class="form-section"><?=translate("Profile Hemodialysis", $this->session->userdata("language"))?></h4>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Machine No. :", $this->session->userdata("language"))?></label>
									
									<div class="col-md-6">
										
										<?php
							                $machine_no = array(
							                    "name"			=> "machine_no",
							                    "id"			=> "machine_no",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Machine No.", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $machine_no_value
							                );
							                echo form_input($machine_no);
							            ?>		
							            	
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Type of Dialyzer :", $this->session->userdata("language"))?></label>
									
									<div class="col-md-6">
									 
											 
											<input type="checkbox" id="new" value="1" disabled class="make-switch" > New 
											 &nbsp;&nbsp;
											<input type="checkbox" id="reuse" value="1" disabled class="make-switch" > Reuse 
										 
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Dialyzer :", $this->session->userdata("language"))?></label>
									
									<div class="col-md-6">
										
										<?php
							                $dialyzer = array(
							                    "name"			=> "dialyzer",
							                    "id"			=> "dialyzer",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Dialyzer", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $dialyzer_value
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
										 
											 
											<input type="checkbox" id="av_shunt" value="1" disabled class="make-switch" > AV Shunt  
											 &nbsp;&nbsp;
											<input type="checkbox" id="femoral" value="1" disabled class="make-switch"> Femoral 
											 &nbsp;&nbsp;
											<input type="checkbox" id="double_lument" value="1" disabled class="make-switch"> Catheter Double Lument 
										 
									</div>
								</div>
							
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Type of Dialysate :", $this->session->userdata("language"))?></label>
									
									<div class="col-md-6">
									 
											 
											<input type="checkbox" id="bicarbonate" value="1" disabled class="make-switch" > Bicarbonate 
										 
									</div>
								</div>
							</div>
							<div class="col-md-12"></div>

							<div class="col-md-6">
								<h4 class="form-section"><?=translate("Problems Found", $this->session->userdata("language"))?></h4>
								<div class="form-group">									
									<div class="col-md-6">
										  
											<?php
												  $x = 1;
												  for ($x=1;$x<=6;$x++) 
												 {
												// 	$checked = '';
												// 	if($problem['is_active'] == 1)
												// 	{
												// 		$checked = 'checked = "checked"';
												// 	}

													echo '<table><tr><td><input type="checkbox" id="prob'.$x.'" disabled  value="'.$x.'" class="make-switch"></td><td style="padding-left:10px"><div id="probname'.$x.'"></div></td></tr></table>';
												//	$i++;
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
												  $y = 1;
												  for ($y=1;$y<=9;$y++) 
												 {
												// 	$checked = '';
												// 	if($problem['is_active'] == 1)
												// 	{
												// 		$checked = 'checked = "checked"';
												// 	}

													echo '<table><tr><td><input type="checkbox" id="comp'.$y.'" disabled   value="'.$y.'" class="make-switch"></td><td style="padding-left:10px"><div id="compname'.$y.'"></div></td></tr></table> ';
												//	$i++;
												 }
											?>
									 
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>
			 

			 </div>
		  
			<!-- END FORM-->
	 

	<div class="portlet">
		<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"> <?=translate("Supervising of Fluid During Hemodialysis", $this->session->userdata("language"))?></span>
			</div>
			 
		</div>
		</div>
		 
		<div class="portlet-body form">
			 
			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
					<div class="row">
						<div class="col-md-6">
							<h4 class="form-section"><?=translate("Intake", $this->session->userdata("language"))?></h4>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Remaining of Priming :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
										<?php
							                $remaining = array(
							                    "name"			=> "remaining_",
							                    "id"			=> "remaining_",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Remaining of Priming", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $remaining_of_priming_value
							                );
							                echo form_input($remaining);
							            ?>		
						            	<span class="input-group-addon">
											<i> cc </i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Wash Out :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
										<?php
							                $washout = array(
							                    "name"			=> "washout_",
							                    "id"			=> "washout_",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Wash Out", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $wash_out_value
							                );
							                // echo form_input($washout);
							            ?>		
						            	<span class="input-group-addon">
											<i> cc </i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Drip of Fluid :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
										<?php
							                $drip_of_fluid = array(
							                    "name"			=> "drip_of_fluid_",
							                    "id"			=> "drip_of_fluid_",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Drip of Fluid", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $drip_of_fluid_value
							                );
							                echo form_input($drip_of_fluid);
							            ?>		
						            	<span class="input-group-addon">
											<i> cc </i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Blood :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
										<?php
							                $blood = array(
							                    "name"			=> "blood_",
							                    "id"			=> "blood_",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Blood", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $blood_value
							                );
							                echo form_input($blood);
							            ?>		
						            	<span class="input-group-addon">
											<i> cc </i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Drink :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
										<?php
							                $drink = array(
							                    "name"			=> "drink_",
							                    "id"			=> "drink_",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Drink", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $drink_value
							                );
							                echo form_input($drink);
							            ?>		
						            	<span class="input-group-addon">
											<i> cc </i>
										</span>
									</div>
								</div>
							</div>

						</div>

						<div class="col-md-6">
							<h4 class="form-section"><?=translate("Output", $this->session->userdata("language"))?></h4>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Vomiting :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
										<?php
							                $vomitting = array(
							                    "name"			=> "vomitting_",
							                    "id"			=> "vomitting_",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Vomiting", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $vomiting_value
							                );
							                echo form_input($vomitting);
							            ?>		
						            	<span class="input-group-addon">
											<i> cc </i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Urinate :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<div class="input-group">
										<?php
							                $urinate = array(
												"name"        => "urinate_",
												"id"          => "urinate_",
												"size"        => "16",
												"maxlength"   => "255",
												"class"       => "form-control", 
												"placeholder" => translate("Urinate", $this->session->userdata("language")), 
												"readonly"    => "readonly",
												// "value"       => $urinate_value
							                );
							                echo form_input($urinate);
							            ?>		
						            	<span class="input-group-addon">
											<i> cc </i>
										</span>
									</div>
								</div>
							</div>
						</div>
					<div class="col-md-12">
						<h4 class="form-section"><?=translate("Blood Transfusion", $this->session->userdata("language"))?></h4>

					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Type :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">									
								<?php
					                $type = array(
					                    "name"			=> "type_",
					                    "id"			=> "type_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Type", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    // "value"			=> $transfusion_type_value
					                );
					                echo form_input($type);
					            ?>						            	
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Quantity :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<div class="input-group">
									<?php
						                $quantity = array(
						                    "name"			=> "quantity_",
						                    "id"			=> "quantity_",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Quantity", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $transfusion_qty_value
						                );
						                echo form_input($quantity);
						            ?>		
					            	<span class="input-group-addon">
										<i> Bag </i>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Blood Type :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">									
								<?php
					                $blood_type = array(
					                    "name"			=> "blood_type_",
					                    "id"			=> "blood_type_",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Blood Type", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    // "value"			=> $transfusion_blood_type_value
					                );
					                echo form_input($blood_type);
					            ?>						            	
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Serial Number :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">									
								<?php
					                $serial_number = array(
					                    "name"			=> "serial_number_",
					                    "id"			=> "serial_number_",
										"rows"			=> "6",
					                    "cols"		=> "8",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Serial Number", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    // "value"			=> $serial_number_value
					                );
					                echo form_textarea($serial_number);
					            ?>						            	
							</div>
						</div>

					</div>

					</div>
				</div>
 
			</div>
			 
		</div>
 </div>

 <div class="portlet">
 	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Observation Dialysis", $this->session->userdata("language"))?></span>
			</div>
			 
		</div>
		</div>
	 
		<div class="portlet-body form">

			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
					<div class="table-container">
						<div class="table-actions-wrapper" >
							<span>
								<?=translate("Search", $this->session->userdata("language"))?>:&nbsp;<input type="text" aria-controls="table_user" class="table-group-action-input form-control input-small input-inline input-sm"></input>			
							</span>
								
						</div>
						<table class="table table-striped table-bordered table-hover" id="table_dialysis">
						<thead>
						<tr role="row" class="heading">
							<th width="10%">
								<center> <?=translate("Waktu", $this->session->userdata("language"))?></center>
							</th>
							<th width="10%">
								<center> <?=translate("BP", $this->session->userdata("language"))?></center>
							</th>
						 
							<th width="10%">
								<center> <?=translate("UFG", $this->session->userdata("language"))?></center>
							</th>
							<th width="10%">
								<center> <?=translate("UFR", $this->session->userdata("language"))?></center>
							</th>
							<th width="10%">
								<center> <?=translate("UFV", $this->session->userdata("language"))?></center>
							</th>
							<th width="10%">
								<center> <?=translate("QB", $this->session->userdata("language"))?></center>
							</th>
							<th class="text-center"><?=translate('TMP', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('VP', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('AP', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Cond', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Temperature', $this->session->userdata('language'))?></th>
						 
							 
							<th width="10%">
								<center> <?=translate("Nama Perawat", $this->session->userdata("language"))?></center>
							</th>
							<th width="10%">
								<center> <?=translate("Keterangan", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Aksi", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Action", $this->session->userdata("language"))?></center>
							</th>
							
						</tr>
						
						</thead>
						<tbody>
						</tbody>
						</table>
					</div>
				</div>

			
			</div>
		</div>
			<!-- END FORM-->
	</div>
 
 

 <div class="portlet">
 	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Examination Support", $this->session->userdata("language"))?></span>
			</div>
			 
		</div>
		</div>
	 
		<div class="portlet-body form">
		 
			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
					<div class="row">
						<div class="col-md-6 pull-left">
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Laboratory :", $this->session->userdata("language"))?> </label>
								
								<div class="col-md-6">
									<?php
										$laboratory = array(
						                    "name"			=> "laboratory_",
						                    "id"			=> "laboratory_",
						                    "cols"			=> 32,
											"rows"			=> 5,
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control",
						                    "placeholder"	=> translate("Laboratory", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $laboratory_value
						                );
					                echo form_textarea($laboratory);
									?>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("ECG :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<?php
										$ecg = array(
						                    "name"			=> "ecg_",
						                    "id"			=> "ecg_",
						                    "cols"			=> 32,
											"rows"			=> 5,
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control",
						                    "placeholder"	=> translate("ECG", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $ecg_value
						                );
					                echo form_textarea($ecg);
									?>	
								</div>
							</div>
						</div>
						<div class="col-md-6">

							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Priming :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<?php
						                $priming = array(
						                    "name"			=> "priming_",
						                    "id"			=> "priming_",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Priming", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $priming_value
						                );
						                echo form_input($priming);
						            ?>	
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Initiation :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<?php
						                $initiation = array(
						                    "name"			=> "initiation_",
						                    "id"			=> "initiation_",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Initiation", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $initiation_value
						                );
						                echo form_input($initiation);
						            ?>		
								</div>
							</div>
						
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate("Termination :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
									<?php
						                $termination = array(
						                    "name"			=> "termination_",
						                    "id"			=> "termination_",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Termination", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    // "value"			=> $termination_value
						                );
						                echo form_input($termination);
						            ?>	
								</div>
							</div>
						</div>
					</div>
				</div>
			 

			</div>
		 
		</div>
			<!-- END FORM-->
	</div>
 
 <!-- +++++++ -->
 <div class="portlet">
 	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Item telah tersimpan", $this->session->userdata("language"))?></span>
			</div>
			 
		</div>
		</div>
		 
		<div class="portlet-body form">

			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
					<div class="table-container">
						<div class="table-actions-wrapper" >
							<span>
								<?=translate("Search", $this->session->userdata("language"))?>:&nbsp;<input type="text" aria-controls="table_user" class="table-group-action-input form-control input-small input-inline input-sm"></input>			
							</span>
								
						</div>
						<table class="table table-striped table-bordered table-hover" id="table_item2">
						<thead>
						<tr role="row" class="heading">
							<th width="10%">
								<center> <?=translate("Nama Item", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Aksi", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Aksi", $this->session->userdata("language"))?></center>
							</th>
							
						</tr>
						
						</thead>
						<tbody>
						</tbody>
						</table>
					</div>
				</div>
			
			</div>
		</div>
			<!-- END FORM-->
	</div>

	<div class="portlet">
		<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("tagihan Paket", $this->session->userdata("language"))?></span>
			</div>
			 
		</div>
		</div>
	 
		<div class="portlet-body form">

			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
					<div class="table-container">
						<div class="table-actions-wrapper" >
							<span>
								<?=translate("Search", $this->session->userdata("language"))?>:&nbsp;<input type="text" aria-controls="table_user" class="table-group-action-input form-control input-small input-inline input-sm"></input>			
							</span>
								
						</div>
						<table class="table table-striped table-bordered table-hover" id="table_invoice3">
						<thead>
						<tr role="row" class="heading">
							<th width="10%">
								<center> <?=translate("Tipe Paket", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Nama Paket", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Aksi", $this->session->userdata("language"))?></center>
							</th>
							
						</tr>
						
						</thead>
						<tbody>
						</tbody>
						</table>
					</div>
				</div>
				 
			</div>
		</div>
			<!-- END FORM-->
	</div>

	<div class="portlet">
		<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("item yang digunakan", $this->session->userdata("language"))?></span>
			</div>
			 
		</div>
		</div>
	 
		<div class="portlet-body form">

			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
					<div class="table-container">
						<div class="table-actions-wrapper" >
							<span>
								<?=translate("Search", $this->session->userdata("language"))?>:&nbsp;<input type="text" aria-controls="table_user" class="table-group-action-input form-control input-small input-inline input-sm"></input>			
							</span>
								
						</div>
						<table class="table table-striped table-bordered table-hover" id="table_provision">
						<thead>
						<tr role="row" class="heading">
							<th width="10%">
								<center> <?=translate("Waktu Pemberian", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Nama Item", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Nama Paket", $this->session->userdata("language"))?></center>
							</th>
							<th width="10%">
								<center> <?=translate("Jumlah Item", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Nama Perawat", $this->session->userdata("language"))?></center>
							</th>
							<th width="15%">
								<center> <?=translate("Nurse", $this->session->userdata("language"))?></center>
							</th>

							
						</tr>
						
						</thead>
						<tbody>
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
			<!-- END FORM-->
	</div>
</div>
 <?php include('edit_observasi_dialisis.php') ?>
<div id="asses3" style="display:none">
 <div class="portlet">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Paket", $this->session->userdata("language"))?></span>
				</div>
		
			</div>
		</div>
		<div class="portlet-body form">
			 
			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
						
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Nomor Transaksi :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<p class="form-control-static" id="tagihantransaksinumber">
									  
								</p>	
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Nama Paket :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<p class="form-control-static" id="tagihanpaketname">
									 
								</p>
							</div>
						</div>
	
				</div>
			</div>
		 
		</div>
			<!-- END FORM-->
	</div>

	<div class="portlet">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Paket", $this->session->userdata("language"))?></span>
				</div>
		
			</div>
		</div>
		<div class="portlet-body form">
			 
			<div class="form-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="table_view_paket">
					<thead>
					<tr role="row" class="heading">
						 
						<th><center><?=translate("Nomor",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Nama",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Jatah",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Digunakan",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Sisa",$this->session->userdata("language"))?></center></th>
							 
							
					</tr>
					</thead>
					<tbody>
					 
					</tbody>
					</table>

					<div class="form-group">						
						<div class="col-md-4">
						<?php
			                $jumlah_item = array(
			                    "name"			=> "jumlah_item",
			                    "id"			=> "jumlah_item",
								"size"			=> "5",
			                    "class"			=> "text",
								"readonly"		=> "readonly",
								"hidden"		=> "hidden",
			                    // "value"			=> $result_count
			                );
							echo form_input($jumlah_item);
						?>
						</div>
					</div>

				</div>
					
			</div>
			<? $msg = translate("Save item?",$this->session->userdata("language"));?>								
			<div class="form-actions fluid">	
				<div class="col-md-offset-3 col-md-9">
					<a class="btn default" id="backbtn2"><?=translate("Back", $this->session->userdata("language"))?></a>
				</div>			
			</div>
		</div>
		 
	</div>
</div>