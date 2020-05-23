
		<?php
			$form_attr = array(
			    "id"            => "form_add_item", 
			    "name"          => "form_add_item", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."master/item/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');
		?>


			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-search font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Detail Histori", $this->session->userdata("language"))?></span>
					</div>
					<div class="actions">
						<a class="btn btn-circle btn-default" href="<?=base_url()?>klinik_hd/transaksi_dokter/history/">
						<i class="fa fa-chevron-left"></i>
							<?=translate("Kembali", $this->session->userdata("language"))?>
						</a>

						<?php
							$data_pasien_prev = $this->tindakan_hd_m->get_data_hd_pasien($id, $pasien_id, "prev")->result_array();
							$data_pasien_next = $this->tindakan_hd_m->get_data_hd_pasien($id, $pasien_id, "next")->result_array();
							// die_dump($data_pasien_prev);
							$disable_prev = "disabled";
							$url_prev = "#";

							if (!empty($data_pasien_prev)) {
								$disable_prev = "";
								$url_prev = base_url().'klinik_hd/transaksi_dokter/detail_history/'.$data_pasien_prev[0]['id'].'/'.$pasien_id;
							}

							$disable_next = "disabled";
							$url_next = "#";
							if (!empty($data_pasien_next)) {
								$disable_next = "";
								$url_next = base_url().'klinik_hd/transaksi_dokter/detail_history/'.$data_pasien_next[0]['id'].'/'.$pasien_id;
							}
						?>
						<a class="btn btn-circle btn-default btn-icon-only" <?=$disable_prev?> href="<?=$url_prev?>">
							<i class="fa fa-fast-backward"></i>
						</a>
						<a class="btn btn-circle btn-default btn-icon-only" <?=$disable_next?> href="<?=$url_next?>">
							<i class="fa fa-fast-forward"></i>
						</a>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="alert alert-danger display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_danger?>
					    </div>
					    <div class="alert alert-success display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_success?>
					    </div>
					    <input type="hidden" id="tindakanid" value="<?=$form_data_kiri[0]['tindakan_id']?>" >
					    <input type="hidden" id="tindakanhdid" value="<?=$form_data_kiri[0]['tindakan_id']?>" >
					    <input type="hidden" id="tggl" value="<?=$form_data_kiri[0]['tanggal']?>" >
					     <input type="hidden" id="pasienid" value="<?=$pasien_id?>" >
					     <input type="hidden" id="observasiid" name="observasiid" >
					      <input type="hidden" id="id" name="id"value="<?=$id?>"  >

					    <div class="row">
					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="control-label col-md-3"><?=translate("No.Transaksi", $this->session->userdata("language"))?> :</label>
									<div class="col-md-9">
									 	<label class="control-label"><strong><?=$form_data_kiri[0]['no_transaksi']?></strong></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=($form_data_kiri[0]['tanggal']!= NULL || $form_data_kiri[0]['tanggal']!='')?date('d M Y',strtotime($form_data_kiri[0]['tanggal'])):'-'?></strong></label>
										</div>
								</div>

					    		<div class="form-group">
									<label class="control-label col-md-3"><?=translate("No.Pasien", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=$form_data_kiri[0]['no_member']?></strong></label>
										</div>
									 
								</div>

								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=$form_data_kiri[0]['nama']?></strong></label>
										</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Dialisis Terakhir", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=($form_data_kiri[0]['tanggal_terakhir']!= NULL || $form_data_kiri[0]['tanggal_terakhir']!='')?date('d M Y',strtotime($form_data_kiri[0]['tanggal'])):'-'?></strong></label>
										</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=$form_data_kiri[0]['keterangan']?></strong></label>
										</div>
								</div>

								
					    	</div>

					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=$form_data_kanan[0]['nama']?></strong></label>
										</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Berat Awal", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=$form_data_kanan[0]['berat_awal']?> Kg</strong></label>
										</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Berat Akhir", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=$form_data_kanan[0]['berat_akhir']?> Kg</strong></label>
										</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Rujukan", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=$form_data_kanan[0]['nama_poli']?></strong></label>
										</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("No.Bed", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=$form_data_kanan[0]['kode']?></strong></label>
										</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Klaim", $this->session->userdata("language"))?> :</label>
								 
										<div class="col-md-9">
									 		<label class="control-label"><strong><?=$form_data_kanan[0]['nama_penjamin']?></strong></label>
										</div>
								</div>
					    	</div>
					    </div>
					</div>
				</div>	
	
			<div class="portlet light">
				 
				<div class="portlet-body">
					<ul class="nav nav-tabs">
						<li  class="active">
							<a href="#satuan" data-toggle="tab">
							<?=translate('Assessment of patient hemodialysis', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#penjualan" data-toggle="tab">
							<?=translate('Supervising of fluid during hemodialysis', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#pembelian" data-toggle="tab">
							<?=translate('Observation dialysis', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#spesifikasi" data-toggle="tab">
							<?=translate('Examination support', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#gambar" data-toggle="tab">
							<?=translate('Item', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#identitas" data-toggle="tab">
							<?=translate('Paket', $this->session->userdata('language'))?> </a>
						</li>
					 
						<li>
							<a href="#resep" data-toggle="tab">
							<?=translate('Resep', $this->session->userdata('language'))?> </a>
						</li>

						<li>
							<a href="#data_pasien" data-toggle="tab">
							<?=translate('Data Pasien', $this->session->userdata('language'))?> </a>
						</li>

					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="satuan">
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
						<div class="col-md-6">

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

						<div class="col-md-6">

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
						</div>
						<div class="tab-pane" id="penjualan">
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
							                    "name"			=> "remaining",
							                    "id"			=> "remaining",
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
							                    "name"			=> "washout",
							                    "id"			=> "washout",
												"size"			=> "16",
							                    "maxlength"		=> "255",
							                    "class"			=> "form-control", 
							                    "placeholder"	=> translate("Wash Out", $this->session->userdata("language")), 
							                    "readonly"		=> "readonly",
							                    // "value"			=> $wash_out_value
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
								<label class="control-label col-md-3"><?=translate("Drip of Fluid :", $this->session->userdata("language"))?></label>
								
								<div class="col-md-6">
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
							                    "name"			=> "blood",
							                    "id"			=> "blood",
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
							                    "name"			=> "drink",
							                    "id"			=> "drink",
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
							                    "name"			=> "vomitting",
							                    "id"			=> "vomitting",
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
												"name"        => "urinate",
												"id"          => "urinate",
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
					                    "name"			=> "type",
					                    "id"			=> "type",
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
						                    "name"			=> "quantity",
						                    "id"			=> "quantity",
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
					                    "name"			=> "blood_type",
					                    "id"			=> "blood_type",
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
					                    "name"			=> "serial_number",
					                    "id"			=> "serial_number",
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
</div>
						<div class="tab-pane" id="pembelian">

							<div class="portlet">
 								<div class="portlet light">
									<div class="portlet-title">
										<div class="caption">
												<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Observation Dialysis", $this->session->userdata("language"))?></span>
										</div>
			 
									</div>
								</div>
	 
								<div class="portlet-body form">

			 
								<div class="form-wizard">
									<div class="form-body">
										<div class="table-container">
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
													<th width="10%">
														<center> <?=translate("Nama Perawat", $this->session->userdata("language"))?></center>
													</th>
													<th width="10%">
														<center> <?=translate("Keterangan", $this->session->userdata("language"))?></center>
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

 
					</div>
 
					
				</div>
<!-- ==== -->

<!--  ==== -->


						<div class="tab-pane" id="spesifikasi">
							 <div class="portlet">
 	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Examination Support", $this->session->userdata("language"))?></span>
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
						                    "name"			=> "ecg",
						                    "id"			=> "ecg",
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
						                    "name"			=> "priming",
						                    "id"			=> "priming",
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
						                    "name"			=> "initiation",
						                    "id"			=> "initiation",
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
						                    "name"			=> "termination",
						                    "id"			=> "termination",
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
		</div>
			<!-- END FORM-->
	</div>
						</div>
						<div class="tab-pane" id="gambar">
							 <div class="portlet">
 	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Item telah tersimpan", $this->session->userdata("language"))?></span>
			</div>
			 
		</div>
		 
		<div class="portlet-body form">

			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
					<div class="table-container">
						<table class="table table-striped table-bordered table-hover" id="table_item2">
						<thead>
						<tr role="row" class="heading">
							<th width="10%">
								<center> <?=translate("Nama Item", $this->session->userdata("language"))?></center>
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
	</div>

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
							<th width="10%">
								<center> <?=translate("Batch Number", $this->session->userdata("language"))?></center>
							</th>
							<th width="10%">
								<center> <?=translate("Expire Date", $this->session->userdata("language"))?></center>
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
						<div class="tab-pane" id="identitas">
							<div id="asses2">
							<div class="portlet">
		<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Paket", $this->session->userdata("language"))?></span>
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
	</div>
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
						</div>
					 
						<div class="tab-pane" id="resep">
							<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("resep", $this->session->userdata("language"))?></span>
				</div>
		
			</div>
		</div>
							<ul class="nav nav-tabs">
							 <li class="active">
								<a href="#resepobat" data-toggle="tab">
									<?=translate('Resep Obat', $this->session->userdata('language'))?> </a>
							</li>
							<li>
								<a href="#resepracikan" data-toggle="tab">
								<?=translate('Resep Manual', $this->session->userdata('language'))?> </a>
							</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="resepobat">
									<div class="portlet-body">
										<table class="table table-striped table-bordered table-hover" id="table_resep_obat">
										<thead>

										<tr class="heading">
											<th class="text-center" style="width : 250px !important;"><?=translate("Nama Item", $this->session->userdata("language"))?></th>
											<th class="text-center" style="width : 250px !important;"><?=translate("Tipe", $this->session->userdata("language"))?></th>
			                    			<th class="text-center" style="width : 250px !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
			                    			<th class="text-center" style="width : 250px !important;"><?=translate("Dosis", $this->session->userdata("language"))?></th>
										</tr>
										</thead>

										<tbody>
										</tbody>
						 
										</table>
									</div>
								</div>
								<div class="tab-pane" id="resepracikan">
									<div class="portlet-body">
										<table class="table table-striped table-bordered table-hover" id="table_resep_racikan">
										<thead>

										<tr class="heading">
												<th class="text-center" style="width : 250px !important;"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
								 		</tr>
										</thead>

										<tbody>
										</tbody>
						 
										</table>
									</div>
								</div>
								</div>
							</div>

							<div class="tab-pane" id="data_pasien">
								<div class="portlet light">
									<div class="portlet-body form">
										<div class="row">
											<div class="col-md-6">
												<div class="portlet light">
													<div class="portlet-title">
														<div class="caption">
															<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Umum", $this->session->userdata("language"))?></span>
														</div>
													</div>
													<div class="row">
														<div class="col-md-9">
															<div class="portlet-body form">
																<div class="form-body">
																	<div class="form-group hidden">
																		<label class="control-label col-md-5"><?=translate("ID", $this->session->userdata("language"))?> :</label>
																		
																		<div class="col-md-7">
																			<input class="form-control" value="<?=$pasien_id?>" id="id_pasien" name="id_pasien">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-5"><?=translate("No. Pasien", $this->session->userdata("language"))?> :</label>
																		
																		<div class="col-md-7">
																			 <label class="control-label"><?=$form_pasien['no_member']?></label>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-5"><?=translate("Keterangan Daftar", $this->session->userdata("language"))?> :</label>
																		
																		<div class="col-md-7">
																			 <label class="control-label"><?=$form_pasien['keterangan']?></label>
																		</div>
																	</div>
									 								<div class="form-group">
																		<label class="control-label col-md-5"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> :</label>
																		
																		<div class="col-md-7">
																			 <label class="control-label"><?=$form_pasien['nama']?></label>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-5"><?=translate("Tempat, Tanggal Lahir", $this->session->userdata("language"))?> :</label>
																		
																		<div class="col-md-7">
																			<label class="control-label"><?=$form_pasien['tempat_lahir']?></label>,  <label class="control-label"><?=date('d M Y',strtotime($form_pasien['tanggal_lahir']))?></label>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-5"><?=translate("Agama", $this->session->userdata("language"))?> :</label>
																		
																		<div class="col-md-7">
																			<?php 
																				$get_agama = $this->info_umum_m->get($form_pasien['agama_id']);
																				$get_agama = object_to_array($get_agama);

																				$agama = "";

																				if (!empty($get_agama)) {
																					$agama = $get_agama['nama'];
																				}
																			?>
																			<label class="control-label"><?=$agama?></label>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-5"><?=translate("Golongan Darah", $this->session->userdata("language"))?> :</label>
																		
																		<div class="col-md-7">
																			<?php 
																				$get_goldar = $this->info_umum_m->get($form_pasien['golongan_darah_id']);
																				$get_goldar = object_to_array($get_goldar);

																				$goldar = "";
																				if (!empty($get_goldar)) {
																					$goldar = $get_goldar['nama'];
																				}
																			?>
																			<label class="control-label"><?=$goldar?></label>
																		</div>
																	</div>
																</div>	
															</div>
														</div>
														<?php
															$url = array();
												            if ($form_pasien['url_photo'] != '') 
												            {
												                if (file_exists(FCPATH.config_item('site_img_pasien').$form_pasien['no_member'].'/foto/'.$form_pasien['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$form_pasien['no_member'].'/foto/'.$form_pasien['url_photo'])) 
												                {
												                    $img_url = base_url().config_item('site_img_pasien').$form_pasien['no_member'].'/foto/'.$form_pasien['url_photo'];
												                }
												                else
												                {
												                    $img_url = base_url().config_item('site_img_pasien').'global/global.png';
												                }
												            } else {

												                $img_url = base_url().config_item('site_img_pasien').'global/global.png';
												            }

												            $img_url = base_url().config_item('site_img_pasien').'global/global.png';

														?>
														<div class="col-md-3">
															<div class="col-md-3">
																<img class="img-thumbnail" src="<?=$img_url?>" style="max-width:100px">
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="portlet light" id="section-telepon">
													 <div class="portlet-title">
														<div class="caption">
															<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Telepon", $this->session->userdata("language"))?></span>
														</div>
													</div>
													<div class="portlet-body form">
														<div class="form-body">
															<div class="alert alert-danger display-hide">
														        <button class="close" data-close="alert"></button>
														        <?=$form_alert_danger?>
														    </div>
														    <div class="alert alert-success display-hide">
														        <button class="close" data-close="alert"></button>
														        <?=$form_alert_success?>
														    </div>
														    <?php
																$pasien_telepon = $this->pasien_telepon_m->get_by(array('pasien_id' => $pasien_id));
																$pasien_telepon = object_to_array($pasien_telepon);
																// die_dump($pasien_telpon);
																foreach ($pasien_telepon as $telepon) {
																	$subjek_telp = $this->subjek_m->get($telepon['subjek_id']);
																	$subjek_telp = object_to_array($subjek_telp);

																	$telp_sub = "";
																	$telp_nomor = "";

																	if (!empty($subjek_telp)) {
																		$telp_sub = $subjek_telp['nama'];
																		$telp_nomor = $telepon['nomor'];
																	}
															?>	 
															<div class="form-group">
																
																<label class="control-label col-md-3"><?=$telp_sub?> : </label>
																
																<div class="col-md-4">
																	 <label class="control-label"><?=$telp_nomor?></label>
																</div>
															</div>

															<?php } ?>
														</div>	
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="portlet light" id="section-alamat">
													<div class="portlet-title">
														<div class="caption">
															<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Data Surat Kelayakan Anggota', $this->session->userdata('language'))?></span>
														</div>
														 
													</div>
													<div class="portlet-body">
														<div class="form-body form">
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Kode Cabang", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4">
																	 <label class="control-label"><?=$form_pasien['ref_kode_cabang']?></label>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Kode Rumah Sakit Rujukan", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4">
																	 <label class="control-label"><?=$form_pasien['ref_kode_rs_rujukan']?></label>
																</div>
															</div>
							 								<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4">
																	 <label class="control-label"><?=($form_pasien['ref_tanggal_rujukan'] != NULL || $form_pasien['ref_tanggal_rujukan'] != '')?date('d M Y',strtotime($form_pasien['ref_tanggal_rujukan'])):'-'?></label>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Nomor Rujukan", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4"> 
																	  <label class="control-label"><?=$form_pasien['ref_nomor_rujukan']?></label>
																</div>
															</div>
														</div>	
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="portlet light" id="section-alamat">
													<div class="portlet-title">
														<div class="caption">
															<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Alamat', $this->session->userdata('language'))?></span>
														</div>
														 
													</div>
													<div class="portlet-body">
														<div class="form-body form">

															<?php
																$pasien_alamat = $this->pasien_alamat_m->get_by(array('pasien_id' => $pasien_id));
																$pasien_alamat = object_to_array($pasien_alamat);

																// die_dump($pasien_alamat);
																$kelurahan = "";
																$kecamatan = "";
																$kota = "";
																$provinsi = "";
																if (!empty($pasien_alamat)) {
																	foreach ($pasien_alamat as $alamat) {
																		$get_subjek_alamat = $this->subjek_m->get($alamat['subjek_id']);
																		$get_subjek_alamat = object_to_array($get_subjek_alamat);

																		$subjek_alamat = "";

																		if (!empty($get_subjek_alamat)) {
																			$subjek_alamat = $get_subjek_alamat['nama'];
																		}

																		$rt_rw = explode('_', $alamat['rt_rw']);

																		$region = $this->info_alamat_m->get_by(array('lokasi_kode' => $alamat['kode_lokasi']));
																		$region = object_to_array($region);

																		if (!empty($region)) {
																			$kelurahan = $region[0]['nama_kelurahan'];
																			$kecamatan = $region[0]['nama_kecamatan'];
																			$kota = $region[0]['nama_kabupatenkota'];
																			$provinsi = $region[0]['nama_propinsi'];
																		}
																	
																
																	// die_dump($this->db->last_query());
															?>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4">
																	 <label class="control-label"><?=$subjek_alamat?></label>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-7">
																	 <label class="control-label"><?=$alamat['alamat']?></label>
																</div>
															</div>
							 								<div class="form-group">
																<label class="control-label col-md-4"><?=translate("RT / RW", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4">
																	 <label class="control-label"><?=$rt_rw[0].'/'.$rt_rw[1]?></label>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Kelurahan / Desa", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4"> 
																	  <label class="control-label"><?=$kelurahan?></label>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Kecamatan", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4"> 
																	  <label class="control-label"><?=$kecamatan?></label>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Kota", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4"> 
																	  <label class="control-label"><?=$kota?></label>
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Provinsi", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4"> 
																	  <label class="control-label"><?=$provinsi?></label>
																</div>
															</div>
															<?php }} ?>
														</div>	
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="portlet light" id="section-alamat">
													<div class="portlet-title">
														<div class="caption">
															<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Informasi Lain', $this->session->userdata('language'))?></span>
														</div>
														 
													</div>
													<div class="portlet-body">
														<div class="form-body form">
															
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Dokter Pengirim", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4">
																	 <label class="control-label"><?=$form_pasien['dokter_pengirim']?></label>
																</div>
															</div>
															 
															<?php 
																$form_penyakit = $this->pasien_penyakit_m->get_penyakit($pasien_id)->result();
																$form_penyakit = object_to_array($form_penyakit);

															?>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Penyakit Bawaan", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4"> 
																	<? foreach($form_penyakit as $row){?>
																	  <label class="control-label"><?if($row['tipe']==1){?><?=$row['nama']?>,<?}?></label>
																	<?}?>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Penyakit Penyebab", $this->session->userdata("language"))?> :</label>
																
																<div class="col-md-4">
																	<? foreach($form_penyakit as $row){?>
																	  <label class="control-label"><?if($row['tipe']==2){?><?=$row['nama']?>,<?}?></label>
																	<?}?>
																</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						</div>

					</div>
				</div>
			</div>			
		</div>
		</div>
			<?=form_close()?>










