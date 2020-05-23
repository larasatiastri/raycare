<?php
	$form_attr = array(
	    "id"            => "form_edit_spesialis", 
	    "name"          => "form_edit_spesialis", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
	);

	$hidden = array(
	    "command"   => "edit",
	    "id"		=> $pk_value,
	);

	echo form_open(base_url()."master/spesialis/save", $form_attr, $hidden);
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));


	//die_dump($records);
	
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-green-sharp bold uppercase"><?=translate("Spesialis", $this->session->userdata("language"))?></span>
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
					<div class="col-md-3">
						<?php
							$nama = array(
								"id"			=> "nama",
								"name"			=> "nama",
								"autofocus"			=> true,
								"class"			=> "form-control", 
								"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
								"required"		=> "required",
								"value"			=> $form_data['nama']
							);
							echo form_input($nama);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?>:</label>
					<div class="col-md-3">
						<?php
							$keterangan = array(
								"id"			=> "keterangan",
								"name"			=> "keterangan",
								"autofocus"		=> true,
								"class"			=> "form-control", 
								"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
								"rows"			=> 4,
								"value"			=> $form_data['keterangan']
							);
							echo form_textarea($keterangan);
						?>
					</div>
				</div>

			</div>
	</div>
	
	
	<div class="portlet-body form">

		<?php $msg = translate("Apakah anda yakin akan merubah spesialis ini?",$this->session->userdata("language"));?>
		<div class="form-actions fluid">	
			<div class="col-md-offset-1 col-md-9">
				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				<a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
	            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>		
		</div>
		<?=form_close()?>
	</div>
	
</div>



