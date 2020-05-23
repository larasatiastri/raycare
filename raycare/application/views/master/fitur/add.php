<?php
			$form_attr = array(
			    "id"            => "form_add_fitur", 
			    "name"          => "form_add_fitur", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."master/fitur/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"> </i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Fitur", $this->session->userdata("language"))?></span>
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
				<label class="control-label col-md-2"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-4">
					<?php
						$nama = array(
							"id"			=> "nama",
							"name"			=> "nama",
							"autofocus"			=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
							"required"		=> "required"
						);
						echo form_input($nama);
					?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("Path", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-4">
					<?php
						$path = array(
							"id"			=> "path",
							"name"			=> "path",
							"autofocus"			=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Path", $this->session->userdata("language")), 
							"required"		=> "required"
						);
						echo form_input($path);
					?>
				</div>
			</div>	


		</div>
		<?php $msg = translate("Apakah anda yakin akan menyimpan data fitur  ini?",$this->session->userdata("language"));?>
		<?php $msg_proses = translate("Sedang diproses",$this->session->userdata("language"));?>
		<div class="form-actions right">	
			<a class="btn default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a id="confirm_save" class="btn  btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$msg_proses?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>	
		</div>
	</div>
</div>
<?=form_close()?>



