<?php
	$form_attr = array(
	    "id"            => "form_ubah_password", 
	    "name"          => "form_ubah_password", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "ubah_password"
    );

    echo form_open(base_url()."pengaturan/ubah_password/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Ubah Password", $this->session->userdata("language"))?></span>
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
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("Password Lama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-3">
					<?php
						$old_password = array(
							"id"          => "old_password",
							"name"        => "old_password",
							"type"        => "password",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("Password Lama", $this->session->userdata("language")), 
							"required"    => "required",
						);
						echo form_input($old_password);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("Password Baru", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-3">
					<?php
						$password = array(
							"id"          => "password",
							"name"        => "password",
							"type"        => "password",
							"class"       => "form-control", 
							"placeholder" => translate("Password Baru", $this->session->userdata("language")), 
							"required"    => "required"
						);
						echo form_input($password);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("Konfirmasi Password", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-3">
					<?php
						$password_confirm = array(
							"id"          => "password_confirm",
							"name"        => "password_confirm",
							"type"        => "password",
							"class"       => "form-control", 
							"placeholder" => translate("Konfirmasi Password", $this->session->userdata("language")), 
							"required"    => "required"
						);
						echo form_input($password_confirm);
					?>
				</div>
			</div>
		</div>
		<?php $msg = translate("Apakah anda yakin akan mengubah password ini?",$this->session->userdata("language"));?>
		<div class="form-actions fluid">	
			<div class="col-md-offset-1 col-md-9">
				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				<a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
	            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>		
		</div>
	</div>	
</div>
<?=form_close()?>



