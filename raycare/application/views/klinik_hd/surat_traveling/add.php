<?php
	$form_attr = array(
	    "id"            => "form_add_surat_traveling", 
	    "name"          => "form_add_surat_traveling", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."klinik_hd/surat_traveling/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('CREATE REFERAL OF DIALYSIS PATIENT', $this->session->userdata('language'))?></span>
		</div>
		
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="portlet light bordered">
				
				<div class="portlet-body form">

				<?php

					$id_pasien = '';
					$no_rekmed = '';
					$nama_pasien = '';
					$umur_sex_pasien = '';
					$gender = '';
					if($pasien_id != null)
					{
						$id_pasien = $pasien_id;
						$data_pasien = $this->pasien_m->get($id_pasien);
						$no_rekmed = $data_pasien->no_member;
						$nama_pasien = $data_pasien->nama;
						$umur_sex_pasien = date_diff(date_create($data_pasien->tanggal_lahir), date_create('today'))->y.' y.o ';
						$gender = ($data_pasien->gender == 'L')?'Male':'Female';
					}

				?>
				<div class="form-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Medrec No", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span> </label>
								<div class="col-md-12">
									<div class="input-group">
										<input class="form-control" id="no_rekmed" name="no_rekmed" value="<?=$no_rekmed?>" placeholder="<?=translate("Medrec No", $this->session->userdata("language"))?>" required>
										<span class="input-group-btn">
											<a class="btn grey-cascade pilih-pasien" title="<?translate('Select Pasien', $this->session->userdata('language'))?>">
												<i class="fa fa-search"></i>
											</a>
										</span>
									</div>
									<input class="form-control hidden" id="id_ref_pasien" name="id_ref_pasien" value="<?=$id_pasien?>" required placeholder="<?=translate("ID Referensi Pasien", $this->session->userdata("language"))?>">
								</div>	
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Patient", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<input class="form-control" id="nama_ref_pasien" name="nama_ref_pasien" value="<?=$nama_pasien?>" readonly  required placeholder="<?=translate("Patient", $this->session->userdata("language"))?>">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Age / Sex", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
										<input class="form-control" id="umur_sex_pasien" name="umur_sex_pasien" value="<?=$umur_sex_pasien.$gender?>" readonly  required placeholder="<?=translate("Age / Sex", $this->session->userdata("language"))?>">
								</div>	
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Date of letter", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<div class="input-group date" id="tanggal_surat">
										<input type="text" class="form-control" id="tanggal_surat" name="tanggal_surat" value="<?=date('d M Y')?>" readonly required>
										<span class="input-group-btn">
											<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Date of inition of maintenance haemodialysis in Raycare ", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								<div class="col-md-12">
									<div class="input-group date" id="date_of_inition">
										<input type="text" class="form-control" id="date_of_inition" name="date_of_inition" readonly required>
										<span class="input-group-btn">
											<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Reason of creating referal of dialysis patient", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								<div class="col-md-12">
									<div class="radio-list">
										<label class="radio-inline">
										<input type="radio" name="alasan" id="pindah" value="1" checked> Move Away </label>
										<label class="radio-inline">
										<input type="radio" name="alasan" id="traveling" value="2"> Traveling </label>
										<label class="radio-inline">
										<input type="radio" name="alasan" id="patient" value="3"> Being Our Patient </label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4" id="move_for_reason">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Reason", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								
								<div class="col-md-12">
									<textarea class="form-control" id="alasan_pindah" name="alasan_pindah" value=""  required="required" placeholder="<?=translate("Alasan Pindah", $this->session->userdata("language"))?>"></textarea>

								</div>
							</div>
						</div>
						<div class="col-md-4" id="move_for_type">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Type", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								
								<div class="col-md-12">
									<?php
										$option_pindah = array(
											''		=> 'Pilih..',
											'1'		=> 'Internal',
											'2'		=> 'External',
										);

										echo form_dropdown('tipe_pindah', $option_pindah, '','id="tipe_pindah" class="form-control" required');	
									?>
								</div>
							</div>
						</div>
						<div class="col-md-4 hidden" id="travel_for">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Traveling For", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								<div class="col-md-4">
									<input type="number" name="lama_traveling" id="lama_traveling" class="form-control" min="1" value="1">	
								</div>
								<div class="col-md-8">
								<?php
									$options = array(
										'1' => 'Hari',
										'2' => 'Minggu',
										'3' => 'Bulan',
									);	
									echo form_dropdown('jenis_lama', $options, '','id="jenis_lama" class="form-control"');							
								?>
								</div>
							</div>
						</div>
						<div class="col-md-4 hidden" id="travel_for_reason">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Reason", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								
								<div class="col-md-12">
								<?php
									$options_alasan = array(
										'1' => 'Liburan',
										'2' => 'Dirawat',
										'3' => 'Dinas Luar Kota',
									);	
									echo form_dropdown('alasan_traveling', $options_alasan, '','id="alasan_traveling" class="form-control"');	
								?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group" id="tujuan_external">
								<label class="col-md-12 bold"><?=translate("Hospital Destination", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								<div class="col-md-12">
									<input name="rs_tujuan" id="rs_tujuan" class="form-control" required>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 hidden" id="tujuan_internal">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Hospital Destination", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								<div class="col-md-12">
									<?php
										$cabang = $this->cabang_m->get_by(array('tipe' => 1, 'is_active' => 1, 'id != ' => $this->session->userdata('cabang_id')));

										$options_internal = array(
											'' => 'Pilih..',
										);	

										foreach ($cabang as $key => $cbg) {
											$options_internal[$cbg->id] = $cbg->nama;
										}

										echo form_dropdown('rs_tujuan_internal', $options_internal, '','id="rs_tujuan_internal" class="form-control" required');	
									?>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Vascular Access", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<div class="checkbox-list">
									 	<label class="checkbox-inline">
											<input class="" type="checkbox" id="cdl" name="cdl" value="1">
									 		<?=translate("Catheter Double Lumen", $this->session->userdata("language"))?>
									 	</label>
									 	<label class="checkbox-inline">
											<input class="" type="checkbox" id="av_shunt" name="av_shunt" value="1">
											<?=translate("AV Shut - Fistula", $this->session->userdata("language"))?>
									 	</label>
									 	<label class="checkbox-inline">
											<input class="" type="checkbox" id="femoral" name="femoral" value="1">
											<?=translate("Femoralis", $this->session->userdata("language"))?>
									 	</label>
								 	</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 bold"><?=translate("Frequency of hemodialysis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-2">
									<div class="input-group">
									<input class="form-control" id="freq_hemo" name="freq_hemo" readonly></input>
									<span class="input-group-addon">
										<i>&nbsp;/ Week&nbsp;</i>
									</span>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-12">
									<div class="checkbox-list">
									 	<label class="checkbox-inline">
											<input class="check_hari" type="checkbox" id="monday" name="monday" value="1">
									 		<?=translate("Monday", $this->session->userdata("language"))?>
									 	</label>
									 	<label class="checkbox-inline">
											<input class="check_hari" type="checkbox" id="tuesday" name="tuesday" value="1">
											<?=translate("Tuesday", $this->session->userdata("language"))?>
									 	</label>
									 	<label class="checkbox-inline">
											<input class="check_hari" type="checkbox" id="wednesday" name="wednesday" value="1">
											<?=translate("Wednesday", $this->session->userdata("language"))?>
									 	</label>
									 	<label class="checkbox-inline">
											<input class="check_hari" type="checkbox" id="thursday" name="thursday" value="1">
									 		<?=translate("Thursday", $this->session->userdata("language"))?>
									 	</label>
									 	<label class="checkbox-inline">
											<input class="check_hari" type="checkbox" id="friday" name="friday" value="1">
											<?=translate("Friday", $this->session->userdata("language"))?>
									 	</label>
									 	<label class="checkbox-inline">
											<input class="check_hari" type="checkbox" id="saturday" name="saturday" value="1">
											<?=translate("Saturday", $this->session->userdata("language"))?>
									 	</label>
									 	<label class="checkbox-inline">
											<input class="check_hari" type="checkbox" id="sunday" name="sunday" value="1">
											<?=translate("Sunday", $this->session->userdata("language"))?>
									 	</label>
								 	</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							 <div class="form-group">
								<label class="col-md-12 bold"><?=translate("Body weight increase in between hemodialysis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Min", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<div class="input-group">
									<input class="form-control" id="min" name="min" ></input>
									<span class="input-group-addon">
										<i>&nbsp;Kg&nbsp;</i>
									</span>
									</div>	
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Max", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>		
								<div class="col-md-12">
									<div class="input-group">
										<input class="form-control" id="max" name="max" ></input>
										<span class="input-group-addon">
											<i>&nbsp;Kg&nbsp;</i>
										</span>
									</div>
								</div>					
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Dry Weight", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								<div class="col-md-12">
									<div class="input-group">
									<input class="form-control" id="dry" name="dry" ></input>
									<span class="input-group-addon">
										<i>&nbsp;Kg&nbsp;</i>
									</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Recent laboratory data", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("UR", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<div class="input-group">
									<input class="form-control" id="ur" name="ur" ></input>
									<span class="input-group-addon">
										<i>&nbsp;mg/dL&nbsp;</i>
									</span>
									</div>	
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("CR", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<div class="input-group">
									<input class="form-control" id="cr" name="cr" ></input>
									<span class="input-group-addon">
										<i>&nbsp;md/dL&nbsp;</i>
									</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("HB", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								<div class="col-md-12">
									<div class="input-group">
									<input class="form-control" id="hb" name="hb" ></input>
									<span class="input-group-addon">
										<i>&nbsp;g/dL&nbsp;</i>
									</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("HbsAg", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
										<input class="form-control" id="hbsag" name="hbsag" value="">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("anti HCV", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
										<input class="form-control" id="hcv" name="hcv" value="">
								</div>
							</div>

						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("anti HIV", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
										<input class="form-control" id="hiv" name="hiv" value="">
								</div>	
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Blood group", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<input class="form-control" id="blood_group" name="blood_group" value="">
								</div>	
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Total Heparin dose", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<div class="input-group">
									<input class="form-control" id="total_heparin_dose" name="total_heparin_dose" ></input>
									<span class="input-group-addon">
										<i>&nbsp;/U&nbsp;</i>
									</span>
									</div>	
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Initial", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<div class="input-group">
									<input class="form-control" id="initial" name="initial" ></input>
									<span class="input-group-addon">
										<i>&nbsp;/U&nbsp;</i>
									</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-md-4 bold"><?=translate("Hourly", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>	
								<div class="col-md-12">
									<div class="input-group">
									<input class="form-control" id="hourly" name="hourly" ></input>
									<span class="input-group-addon">
										<i>&nbsp;/U&nbsp;</i>
									</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Blood Flow (QB)", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<input class="form-control" id="blood_flow" name="blood_flow" value="">
								</div>	
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Medication", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<input class="form-control" id="medication" name="medication" value="">
								</div>	
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Complication on Hemodialysis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<input class="form-control" id="complication" name="complication" value="">
								</div>	
							</div>
						</div>
					</div>
			
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-12"><?=translate("For more information please contact phone No : +62 21 29430783", $this->session->userdata("language"))?> </label>
							</div>
						</div>
					</div>	
				</div>
		</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		<div class="col-md-4">
			<div class="portlet light bordered">
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Remarks", $this->session->userdata("language"))?> : </label>
							<div class="col-md-12">
								<textarea class="form-control" id="remarks" name="remarks" rows="14" placeholder="<?=translate("Remarks", $this->session->userdata("language"))?>"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
		</div><!-- end of <div class="col-md-8"> -->
	</div><!-- end of <div class="row"> -->
		
	</div>
	<?php $msg = translate("Apakah anda yakin akan membuat surat traveling ini?",$this->session->userdata("language"));?>
		<div class="form-actions right">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>klinik_hd/surat_traveling/history"><i class="fa fa-history"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
</div>


<?=form_close()?>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. RM', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tempat, Tanggal Lahir', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 




