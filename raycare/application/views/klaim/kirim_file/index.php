<?php
	$form_attr = array(
	    "id"            => "form_kirim_file", 
	    "name"          => "form_kirim_file", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."klaim/kirim_file_txt/send_mail", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$msg = translate("Apakah anda yakin akan mengirim email beserta lampiran file txt ini?",$this->session->userdata("language"));
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Kirim File TXT", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
				<i class="fa fa-send"></i>
				<?=translate("Kirim Email", $this->session->userdata("language"))?>
			</a>
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
							<label class="col-md-12"><?=translate("Upload File TXT", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-6">
								<div class="input-group" >
									<input type="hidden" name="url" id="url">
										<div id="upload">
											<span class="btn default btn-file">
												<span class="fileinput-new"><?=translate('Pilih File', $this->session->userdata('language'))?></span>		
												<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_file_txt" multiple />
											</span>
										</div>
									<span class="input-group-btn">
										<a class="btn green" id="generate">
										<i class="fa fa-check"></i> Generate </a>
									</span>
								</div>
								<div class="help-block" id="text_filename">
									
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Kirim Ke", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
								<?php
									$kirim_ke = array(
										"id"			=> "kirim_ke",
										"name"			=> "kirim_ke",
										"class"			=> "form-control", 
										"placeholder"	=> translate("Kirim Ke", $this->session->userdata("language")), 
										"value"			=> $flash_form_data['kirim_ke'],
										"help"			=> $flash_form_data['kirim_ke'],
										"required"		=> "required", 
										"value"			=> "nurenka86@gmail.com",
									);
									echo form_input($kirim_ke);
								?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("CC", $this->session->userdata("language"))?> : </label>
							
							<div class="col-md-12">
								<?php
									$cc = array(
										"id"			=> "cc",
										"name"			=> "cc",
										"class"			=> "form-control", 
										"placeholder"	=> translate("CC", $this->session->userdata("language")), 
										"value"			=> $flash_form_data['cc'],
										"help"			=> $flash_form_data['cc']
									);
									echo form_input($cc);
								?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Subject", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
								<?php
									$subject = array(
										"id"			=> "subject",
										"name"			=> "subject",
										"class"			=> "form-control", 
										"placeholder"	=> translate("Subject", $this->session->userdata("language")), 
										"value"			=> $flash_form_data['subject'],
										"help"			=> $flash_form_data['subject'],
										"required"		=> "required", 
									);
									echo form_input($subject);
								?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Isi Email", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
								<?php
									$isi_email = array(
										"id"			=> "isi_email",
										"name"			=> "isi_email",
										"class"			=> "form-control", 
										"placeholder"	=> translate("Isi Email", $this->session->userdata("language")), 
										"rows"			=> 10,
										"value"			=> $flash_form_data['isi_email'],
										"help"			=> $flash_form_data['isi_email'],
										"required"		=> "required", 
									);
									echo form_textarea($isi_email);
								?>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<?=translate("Data Tidak Sesuai", $this->session->userdata("language"))?></span>
						</div>
						<div class="actions">
							<a class="btn btn-circle btn-default" id="refresh">
								<i class="fa fa-undo"></i>
								<?=translate("Refresh", $this->session->userdata("language"))?>
							</a>
							<a id="update_inacbg" class="btn btn-circle btn-primary" >
								<i class="fa fa-recycle"></i>
								<?=translate("Update TXT", $this->session->userdata("language"))?>
							</a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="table_kirim_file">
							<thead>
								<tr>
									<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("No. Rekmed", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Nama Pasien", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("No. SEP", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("No. Penjamin (INACBG)", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("No. Penjamin (SEP)", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Check", $this->session->userdata("language"))?></th>
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
<?=form_close()?>







