<?php
	$form_attr = array(
		"id"			=> "form_examination", 
		"name"			=> "form_examination", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
	);
	echo form_open(base_url()."#", $form_attr,$hidden);
	
	$msg = translate('Apakah anda yakin akan menyimpan examination ini?', $this->session->userdata('language'));
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Examination Support", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<?php
    			$user_level_id = $this->session->userdata('level_id');
        		
        		$data = '<a class="btn btn-circle btn-default edit_examination"><i class="fa fa-edit"></i> '.translate('Edit', $this->session->userdata('language')).'</a>';
        		echo restriction_button($data, $user_level_id, 'klinik_hd_transaksi_perawat', 'edit_examination');
            ?>
			
			<a class="btn btn-circle btn-primary simpan_examination" style="display:none;" data-toggle="modal" data-confirm="<?=$msg?>"><i class="fa"></i> <?=translate('Simpan', $this->session->userdata('language'))?></a>
		</div>
	</div>
	<div class="portlet-body form">
		
		<!-- BEGIN FORM-->
		<div class="form-wizard">
			<div class="form-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Laboratory :", $this->session->userdata("language"))?> </label>
							
							<div class="col-md-6">
								<?php
									$laboratory = array(
					                    "name"			=> "laboratory",
					                    "id"			=> "laboratory",
										"rows"			=> 4,
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control",
					                    "placeholder"	=> translate("Laboratory", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['laboratory']
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
					                    "name"			=> "ecg",
					                    "id"			=> "ecg",
										"rows"			=> 4,
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control",
					                    "placeholder"	=> translate("ECG", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['ecg']
					                );
				                echo form_textarea($ecg);
								?>	
							</div>
						</div>
					</div>
					<div class="col-md-6">

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Priming :", $this->session->userdata("language"))?></label>
							<div class="col-md-5">
								<div class="input-group">
										<?php
							                $priming = array(
							                    "name"			=> "priming",
							                    "id"			=> "priming",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Priming", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    "value"			=> $form_assesment[0]['priming']
							                );
							                echo form_input($priming);
							            ?>	
									<span class="input-group-btn">
										<a title="Ubah Priming" class="btn btn-primary ubah-priming"><i class="fa fa-edit"></i></a>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Initiation :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<?php
					                $initiation = array(
					                    "name"			=> "initiation",
					                    "id"			=> "initiation",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Initiation", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['initiation']
					                );
					                echo form_input($initiation);
					            ?>		
							</div>
						</div>
					
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Termination :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-5">
								<?php
					                $termination = array(
					                    "name"			=> "termination",
					                    "id"			=> "termination",
										"size"			=> "16",
					                    "maxlength"		=> "255",
					                    "class"			=> "form-control", 
					                    "placeholder"	=> translate("Termination", $this->session->userdata("language")), 
					                    "readonly"		=> "readonly",
					                    "value"			=> $form_assesment[0]['termination']
					                );
					                echo form_input($termination);
					            ?>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?=form_close()?>
	</div>
		<!-- END FORM-->
</div>