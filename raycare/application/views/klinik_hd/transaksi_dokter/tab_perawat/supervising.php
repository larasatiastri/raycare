<?php
	$form_attr = array(
		"id"			=> "form_supervising", 
		"name"			=> "form_supervising", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
	);
	echo form_open(base_url()."#", $form_attr,$hidden);

	$msg = translate('Apakah anda yakin akan menyimpan supervising ini?', $this->session->userdata('language'));
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Supervising of Fluid During Hemodialysis", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default edit_supervising"><i class="fa fa-edit"></i> <?=translate('Edit', $this->session->userdata('language'))?></a>
			<a class="btn btn-circle btn-primary simpan_supervising" style="display: none;" data-confirm="<?=$msg?>" data-toggle="model"><i class="fa fa-check"></i> <?=translate('Simpan', $this->session->userdata('language'))?></a>
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
							<label class="control-label col-md-4"><?=translate("Remaining of Priming :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
									<?php
						                $remaining = array(
						                    "name"			=> "remaining",
						                    "id"			=> "remaining",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Remaining of Priming", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    "value"			=> $form_assesment[0]['remaining_of_priming']
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
							<label class="control-label col-md-4"><?=translate("Wash Out :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
									<?php
						                $washout = array(
						                    "name"			=> "washout",
						                    "id"			=> "washout",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Wash Out", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    "value"			=> $form_assesment[0]['wash_out']
						                );
						                echo form_input($washout);
						            ?>		
					            	<span class="input-group-addon">
										<i> cc </i>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Drip of Fluid :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
									<?php
						                $drip_of_fluid = array(
						                    "name"			=> "drip_of_fluid",
						                    "id"			=> "drip_of_fluid",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Drip of Fluid", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    "value"			=> $form_assesment[0]['drip_of_fluid']
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
							<label class="control-label col-md-4"><?=translate("Blood :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
									<?php
						                $blood = array(
						                    "name"			=> "blood",
						                    "id"			=> "blood",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Blood", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    "value"			=> $form_assesment[0]['blood']
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
							<label class="control-label col-md-4"><?=translate("Drink :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<div class="input-group">
									<?php
						                $drink = array(
						                    "name"			=> "drink",
						                    "id"			=> "drink",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Drink", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    "value"			=> $form_assesment[0]['drink']
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
							
							<div class="col-md-5">
								<div class="input-group">
									<?php
						                $vomitting = array(
						                    "name"			=> "vomitting",
						                    "id"			=> "vomitting",
											"size"			=> "16",
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control", 
						                    "placeholder"	=> translate("Vomiting", $this->session->userdata("language")), 
						                    "readonly"		=> "readonly",
						                    "value"			=> $form_assesment[0]['vomiting']
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
							
							<div class="col-md-5">
								<div class="input-group">
									<?php
						                $urinate = array(
											"name"        => "urinate",
											"id"          => "urinate",
											"size"        => "16",
											"maxlength"   => "255",
											"class"       => "form-control", 
											"placeholder" => translate("Urinate", $this->session->userdata("language")), 
											"readonly"    => "readonly",
											"value"       => $form_assesment[0]['urinate']
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
				<div class="col-md-12" style="margin-top:15px;">
					<h4 class="form-section"><?=translate("Blood Transfusion", $this->session->userdata("language"))?></h4>

				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Type :", $this->session->userdata("language"))?></label>
						
						<div class="col-md-5">									
							<?php
				                $type = array(
				                    "name"			=> "type",
				                    "id"			=> "type",
									"size"			=> "16",
				                    "maxlength"		=> "255",
				                    "class"			=> "form-control", 
				                    "placeholder"	=> translate("Type", $this->session->userdata("language")), 
				                    "readonly"		=> "readonly",
				                    "value"			=> $form_assesment[0]['transfusion_type']
				                );
				                echo form_input($type);
				            ?>						            	
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Quantity :", $this->session->userdata("language"))?></label>
						
						<div class="col-md-5">
							<div class="input-group">
								<?php
					                $quantity = array(
					                    "name"			=> "quantity",
					                    "id"			=> "quantity",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Quantity", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['transfusion_qty']
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
						<label class="control-label col-md-4"><?=translate("Blood Type :", $this->session->userdata("language"))?></label>
						
						<div class="col-md-5">									
							<?php
				                $blood_type = array(
				                    "name"			=> "blood_type",
				                    "id"			=> "blood_type",
									"size"			=> "16",
				                    "maxlength"		=> "255",
				                    "class"			=> "form-control", 
				                    "placeholder"	=> translate("Blood Type", $this->session->userdata("language")), 
				                    "readonly"		=> "readonly",
				                    "value"			=> $form_assesment[0]['transfusion_blood_type']
				                );
				                echo form_input($blood_type);
				            ?>						            	
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Serial Number :", $this->session->userdata("language"))?></label>
						
						<div class="col-md-6">									
							<?php
				                $serial_number = array(
				                    "name"			=> "serial_number",
				                    "id"			=> "serial_number",
									"rows"			=> "4",
				                    "class"			=> "form-control", 
				                    "placeholder"	=> translate("Serial Number", $this->session->userdata("language")), 
				                    "readonly"		=> "readonly",
				                    "value"			=> $form_assesment[0]['serial_number']
				                );
				                echo form_textarea($serial_number);
				            ?>						            	
						</div>
					</div>

				</div>

				</div>
			</div>
		</div>
		<?=form_close();?>
	</div>
</div>