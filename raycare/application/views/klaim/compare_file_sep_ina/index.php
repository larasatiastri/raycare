<?php
	$form_attr = array(
	    "id"            => "form_compare_file_sep_ina", 
	    "name"          => "form_compare_file_sep_ina", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."klaim/compare_file_sep_ina/send_mail", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$msg = translate("Apakah anda yakin telah menyelesaikan perbandingan file ini?",$this->session->userdata("language"));
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Bandingkan File SEP - INACBG", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			
    		<button type="submit" id="save" class="btn default hidden" >
    			<?=translate("Simpan", $this->session->userdata("language"))?>
    		</button>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-3">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<?=translate("Form Input", $this->session->userdata("language"))?></span>
						</div>
					</div>
					<div class="form-body">
						<div class="alert alert-danger display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_danger?>
					    </div>
					    <div class="alert alert-success display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_success?>
					    </div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Periode Tindakan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
				
							<div class="col-md-12">
								<div id="reportrange" class="btn default">
									<i class="fa fa-calendar"></i>
									&nbsp; <span>
									</span>
									<b class="fa fa-angle-down"></b>
								</div>
								<input type="hidden" class="form-control" id="tgl_awal" name="tgl_awal"></input>
								<input type="hidden" class="form-control" id="tgl_akhir" name="tgl_akhir"></input>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Upload File CSV SEP", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-6">
								<div class="input-group" >
									<input type="hidden" name="url_sep" id="url_sep">
										<div id="upload_sep">
											<span class="btn default btn-file">
												<span class="fileinput-new"><?=translate('Pilih File', $this->session->userdata('language'))?></span>		
												<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_file_csv" multiple />
											</span>
										</div>
									
								</div>
								<div class="help-block" id="text_filename_sep">
									
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Upload File CSV INACBG", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-6">
								<div class="input-group" >
									<input type="hidden" name="url_ina" id="url_ina">
										<div id="upload_ina">
											<span class="btn default btn-file">
												<span class="fileinput-new"><?=translate('Pilih File', $this->session->userdata('language'))?></span>		
												<input type="file" name="upl" id="upl_ina" data-url="<?=base_url()?>upload/upload_file_csv" multiple />
											</span>
										</div>
									
								</div>
								<div class="help-block" id="text_filename_ina">
									
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">	
						<a class="btn btn-success" id="btn_compare"><i class="fa fa-recycle"></i> <?=translate("Bandingkan File", $this->session->userdata("language"))?></a>
						<a id="confirm_save" class="btn btn-primary hidden" data-confirm="<?=$msg?>" data-proses="<?=$msg_processing?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
				        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<?=translate("Table Perbandingan", $this->session->userdata("language"))?></span>
						</div>
						<div class="actions">
							
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="table_compare_file_sep_ina">
							<thead>
								<tr>
									<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("SIMRHS", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("SEP / VCLAIM", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("INACBG", $this->session->userdata("language"))?></th>
								</tr>
							</thead>

							<tbody>
									<tr>
										<td>Total Tindakan</td>
										<td id="total_hd">0</td>
										<td id="total_sep">0</td>
										<td id="total_ina">0</td>
									</tr>
									<tr>
										<td>Data Tindakan Double</td>
										<td id="total_hd_double">0</td>
										<td id="total_sep_double">0</td>
										<td id="total_ina_double">0</td>
									</tr>
									<tr>
										<td>Belum Input INACBG</td>
										<td id="total_hd_blm_input">0</td>
										<td id="total_sep_blm_input">0</td>
										<td id="total_ina_blm_input">0</td>
									</tr>
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
		</div>
	</div>	
</div>
<?=form_close()?>







