<div id="asses1" style="display:none">
	<div class="portlet">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Observation Dialysis", $this->session->userdata("language"))?></span>
				</div>
		
			</div>
		</div>
		 
		<div class="portlet-body form">
		<input type="hidden" id="username1" name="username1" value="<?=$this->session->userdata("username")?>">
		<input type="hidden" id="userid1" name="userid1" value="<?=$this->session->userdata("user_id")?>">
		<input type="hidden" id="observasiid" name="observasiid" >
			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
					<div class="table-container">
						<div class="table-actions-wrapper" >
							<span>
								<?=translate("Search", $this->session->userdata("language"))?>:&nbsp;<input type="text" aria-controls="table_user" class="table-group-action-input form-control input-small input-inline input-sm"></input>			
							</span>
								
						</div>
						<table class="table table-striped table-bordered table-hover" id="table_dialysis2">
						<thead>
						<tr role="row">
							<th width="10%">
								<center> <?=translate("Time", $this->session->userdata("language"))?></center>
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
							 
							<th width="10%">
								<center> <?=translate("Nurse", $this->session->userdata("language"))?></center>
							</th>
							<th width="10%">
								<center> <?=translate("Note", $this->session->userdata("language"))?></center>
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
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Input Observation Of Dialysis", $this->session->userdata("language"))?></span>
				</div>
		
			</div>
		</div>
		<div class="portlet-body form">
			<!--  ==== -->
				 <?php
					$form_attr = array(
						"id"			=> "form_access2", 
						"name"			=> "form_access2", 
						"autocomplete"	=> "off", 
						"class"			=> "form-horizontal",
					);
					echo form_open(base_url()."#", $form_attr,$hidden);

					$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
					$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
					
					$flash_form_data  = $this->session->flashdata('form_data');
					$flash_form_error = $this->session->flashdata('form_error');
			?>	
			 	<div class="form-wizard">
			
			    
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Time", $this->session->userdata("language"))?></label>
						
						<div class="col-md-2">
							<?php
								$jam = array(
									"name"			=> "jam",
									"id"			=> "jam",
									"class"			=> "form-control", 
									"required"		=> "required",
									// "value"			=> date("H:i", strtotime($waktu_pencatatan_value))
								);
								echo form_input($jam);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Blood Preasure", $this->session->userdata("language"))?></label>
						
						<div class="col-md-1">
							<?php
				                $tda = array(
				                    "name"			=> "tda3",
				                    "id"			=> "tda3",
									"required"		=> "required",
									"size"			=> "5",
									"class"			=> "form-control", 
									"maxlength"		=> "255", 
									// "value"			=> $tda_value
				                );
				                echo form_input($tda);
				            ?>
						</div>

						<div class="col-md-1">
							<?
								$tdb = array(
				                    "name"			=> "tdb3",
									"required"		=> "required",
				                    "id"			=> "tdb3",
									"class"			=> "form-control", 
									"size"			=> "5",
									"maxlength"		=> "255", 
									// "value"			=> $tdb_value
				                );
				                echo form_input($tdb);
				            ?>
						</div>
					</div>
 
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("UFG", $this->session->userdata("language"))?></label>
						
						<div class="col-md-2">
							<?php
								$ufg = array(
									"name"			=> "ufg",
									"id"			=> "ufg",
									"class"			=> "form-control", 
									"required"		=> "required",
									// "value"			=> $ufg_value
								);
								echo form_input($ufg);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("UFR", $this->session->userdata("language"))?></label>
						
						<div class="col-md-2">
							<?php
								$ufr = array(
									"name"			=> "ufr",
									"id"			=> "ufr",
									"class"			=> "form-control", 
									"required"		=> "required",
									// "value"			=> $ufr_value
								);
								echo form_input($ufr);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("UFV", $this->session->userdata("language"))?></label>
						
						<div class="col-md-2">
							<?php
								$ufv = array(
									"name"			=> "ufv",
									"id"			=> "ufv",
									"class"			=> "form-control", 
									"required"		=> "required",
									// "value"			=> $ufv_value
								);
								echo form_input($ufv);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("QB", $this->session->userdata("language"))?></label>
						
						<div class="col-md-2">
							<?php
								$qb = array(
									"name"			=> "qb",
									"id"			=> "qb",
									"class"			=> "form-control", 
									"required"		=> "required",
									// "value"			=> $qb_value
								);
								echo form_input($qb);
							?>
						</div>
					</div>
					 
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Nurse", $this->session->userdata("language"))?></label>
						
						<div class="col-md-2">
							<?php
								$jam = array(
									"name"			=> "nurse",
									"id"			=> "nurse",
									"class"			=> "form-control", 
									"required"		=> "required",
									"disabled"		=> "disabled",
									// "value"			=> $this->session->userdata("username")
								);
								echo form_input($jam);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Notes", $this->session->userdata("language"))?></label>
						
						<div class="col-md-4">
							<?php
								$keterangan = array(
				                    "name"			=> "keterangan",
				                    "id"			=> "keterangan",
									"rows"			=> 5, 
									"cols"			=> 40, 
				                    "class"			=> "form-control",
				                    "placeholder"	=> "Notes",
				                    // "value"			=> $keterangan_value
				                );
								echo form_textarea($keterangan);
							?>
						</div>
					</div>

				</div>
			<!--  ==== -->
			 
			
				  <?
    				 
        			$msg = translate("Apakah anda merubah observasi dialisis ini?",$this->session->userdata("language"));
    			?>							
				<div class="form-actions fluid">	
					<div class="col-md-offset-3 col-md-9">

						<a class="btn btn-primary" id="confirmsave" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
						<button type="submit" id="saveobservasi" class="btn default hidden" ><?=translate("Reset", $this->session->userdata("language"))?></button>
						<a class="btn default" id="backbtn"><?=translate("Kembali", $this->session->userdata("language"))?></a>
					</div>			
				</div>

			</div>
			 <?=form_close();?>
		</div>
			<!-- END FORM-->
 </div>
 