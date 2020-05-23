<?php
			$form_attr = array(
			    "id"            => "form_add_mesin_edc", 
			    "name"          => "form_add_mesin_edc", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."master/mesin_edc/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"> </i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Mesin EDC", $this->session->userdata("language"))?></span>
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
				<label class="control-label col-md-2"><?=translate("Bank Tujuan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
				<div class="col-md-4">
					<?php
						$banks = $this->bank_m->get_by(array('is_active' => 1));

						$bank_option = array();

						foreach ($banks as $bank) {
							$bank_option[$bank->id] = $bank->nob.' a/n '.$bank->acc_name.' - '.$bank->acc_number;
						}

						echo form_dropdown('bank_id', $bank_option, '', 'id="bank_id" class="form-control"');
					?>
				</div>
			</div>

		</div>
		<?php $msg = translate("Apakah anda yakin akan menyimpan data mesin edc ini?",$this->session->userdata("language"));?>
		<?php $msg_proses = translate("Sedang diproses",$this->session->userdata("language"));?>
		<div class="form-actions right">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$msg_proses?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>	
		</div>
	</div>
</div>
<?=form_close()?>



