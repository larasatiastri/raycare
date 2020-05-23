<?php
	$form_attr = array(
	    "id"            => "form_add_user", 
	    "name"          => "form_add_user", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."master/user/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah User", $this->session->userdata("language"))?></span>
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
			    <!-- <div class="row">
			    	<div class="col-md-12">
			    		<div class="portlet light bordered"> -->
			    			<!-- <div class="portlet-title">
			    				<div class="caption">
			    					<?=translate("Informasi", $this->session->userdata("language"))?>
			    				</div>
			    			</div> -->
							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
								<div class="col-md-8">
									<?php
										$fullname = array(
											"id"			=> "nama",
											"name"			=> "nama",
											"autofocus"			=> true,
											"class"			=> "form-control", 
											"placeholder"	=> translate("Nama Lengkap", $this->session->userdata("language")), 
											"required"		=> "required",
											"value"			=> $flash_form_data['nama'],
											"help"			=> $flash_form_data['nama'],
										);
										echo form_input($fullname);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Username", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
								<div class="col-md-8">
									<?php
										$username = array(
											"id"			=> "username",
											"name"			=> "username",
											"class"			=> "form-control", 
											"placeholder"	=> translate("Username", $this->session->userdata("language")), 
											"required"		=> "required",
											"value"			=> $flash_form_data['username'],
											"help"			=> $flash_form_data['username'],
										);
										echo form_input($username);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Inisial", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
								<div class="col-md-8">
									<?php
										$inisial = array(
											"id"			=> "inisial",
											"name"			=> "inisial",
											"class"			=> "form-control", 
											"placeholder"	=> translate("Inisial", $this->session->userdata("language")), 
											"required"		=> "required",
											"value"			=> $flash_form_data['inisial'],
											"help"			=> $flash_form_data['inisial'],
										);
										echo form_input($inisial);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Kata Kunci", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
								<div class="col-md-8">
									<?php
										$password = array(
											"id"			=> "password",
											"name"			=> "password",
											"type"			=> "password",
											"class"			=> "form-control", 
											"placeholder"	=> translate("Password", $this->session->userdata("language")), 
											"required"		=> "required",
											"value"			=> $flash_form_data['password'],
											"help"			=> $flash_form_data['password'],
										);
										echo form_input($password);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Ulangi Kata Kunci", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
								<div class="col-md-8">
									<?php
										$password_confirm = array(
											"id"			=> "password_confirm",
											"name"			=> "password_confirm",
											"type"			=> "password",
											"class"			=> "form-control", 
											"placeholder"	=> translate("Ulangi Kata Kunci", $this->session->userdata("language")), 
											"required"		=> "required",
											"value"			=> $flash_form_data['password_confirm'],
											"help"			=> $flash_form_data['password_confirm'],
										);
										echo form_input($password_confirm);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("User Level", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
								<div class="col-md-8">
									<?php
										
										$user_level = $this->user_level_m->get_by(array('is_active' => '1'));
										// die(dump($this->db->last_query()));
										$user_level_option = array(
										    '' => translate('Pilih User Level', $this->session->userdata('language'))
										);

										foreach ($user_level as $user)
										{
										    $user_level_option[$user->id] = $user->nama;
										}
										echo form_dropdown('user_level_id', $user_level_option, $flash_form_data['user_level_id'], "id=\"user_level_id\" class=\"form-control\"");
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
								<div class="col-md-8">
									<?php
										
										$cabang = $this->cabang_m->get_by(array('is_active' => '1'));
										// die(dump($this->db->last_query()));
										$cabang_option = array(
										    '' => translate('Pilih Cabang', $this->session->userdata('language'))
										);

										foreach ($cabang as $data)
										{
										    $cabang_option[$data->id] = $data->nama;
										}
										echo form_dropdown('cabang_id', $cabang_option, $flash_form_data['cabang_id'], "id=\"cabang_id\" class=\"form-control\"");
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Bahasa", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
								<div class="col-md-8">
									<?php
										
										$bahasa = $this->bahasa_m->get_by(array('is_active' => '1'));
										
										$bahasa_option = array(
										    '' => translate('Pilih Bahasa', $this->session->userdata('language'))
										);

										foreach ($bahasa as $data)
										{
										    $bahasa_option[$data->kode] = $data->nama;
										}
										echo form_dropdown('bahasa', $bahasa_option, $flash_form_data['bahasa'], "id=\"bahasa\" class=\"form-control\"");
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate('Foto', $this->session->userdata('language'))?> :</label>
								<div class="col-md-8">
								<input type="hidden" name="url" id="url">
									<div id="upload">
										<span class="btn default btn-file">
											<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>	
											<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_photo" />
										</span>

									<ul class="ul-img" id="url_img">
										<!-- The file uploads will be shown here -->
									</ul>

									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4"><?=translate('Tanda Tangan', $this->session->userdata('language'))?> :</label>
								<div class="col-md-8">
								<input type="hidden" name="url_sign" id="url_sign">
									<div id="upload">
										<span class="btn default btn-file">
											<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>	
											<input type="file" name="upl" id="upl_sign" data-url="<?=base_url()?>upload/upload_photo" />
										</span>

									<ul class="ul-img" id="url_ttd">
										<!-- The file uploads will be shown here -->
									</ul>

								</div>
								</div>
							</div>
			    			
			    	<!-- 	</div>
			    	</div>
			    </div> -->
				

			</div>

			<?=form_close()?>
			<?php $msg = translate("Apakah anda yakin akan membuat user ini?",$this->session->userdata("language"));?>
			<?php $msg_processing = translate("Sedang Diproses",$this->session->userdata("language"));?>
			<div class="form-actions right">	
				<a class="btn default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
				<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$msg_processing?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
		        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>
	</div>	
</div>



