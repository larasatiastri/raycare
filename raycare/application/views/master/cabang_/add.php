<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-green-sharp bold uppercase"><?=translate("Informasi Cabang", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_cabang", 
			    "name"          => "form_add_cabang", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."master/cabang/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
		?>
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
					<label class="control-label col-md-2"><?=translate("Kode Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-2">
						<?php
							$kode_cabang = array(
								"name"			=> "kode",
								"id"			=> "kode",
								"autofocus"			=> true,
								"class"			=> "form-control", 
								"placeholder"	=> translate("Kode Cabang", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($kode_cabang);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Nama Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$nama_cabang = array(
								"name"			=> "nama",
								"id"			=> "nama",
								"class"			=> "form-control", 
								"placeholder"	=> translate("Nama Cabang", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($nama_cabang);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Alamat Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$alamat_cabang = array(
								"name"			=> "alamat",
								"id"			=> "alamat",
								"class"			=> "form-control",
								"rows"			=> 5, 
								"placeholder"	=> translate("Alamat Cabang", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_textarea($alamat_cabang);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("No. Telepon 1", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$no_telp1 = array(
								"name"			=> "nomor_telepon1",
								"id"			=> "nomor_telepon1",
								"class"			=> "form-control", 
								"placeholder"	=> translate("No. Telepon 1", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($no_telp1);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("No. Telepon 2", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<?php
							$no_telp2 = array(
								"name"			=> "nomor_telepon2",
								"id"			=> "nomor_telepon2",
								"class"			=> "form-control", 
								"placeholder"	=> translate("No. Telepon 2", $this->session->userdata("language")), 								
							);
							echo form_input($no_telp2);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("No. Fax", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<?php
							$no_fax = array(
								"name"			=> "nomor_fax",
								"id"			=> "nomor_fax",
								"class"			=> "form-control", 
								"placeholder"	=> translate("No. Fax", $this->session->userdata("language")), 								
							);
							echo form_input($no_fax);
						?>
					</div>
				</div>
			</div>
			<?php $msg = translate("Apakah anda yakin akan membuat cabang ini?",$this->session->userdata("language"));?>
			<div class="form-actions fluid">	
				<div class="col-md-offset-1 col-md-9">
    				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                    <button type="reset" class="btn default" ><?=translate("Reset", $this->session->userdata("language"))?></button>
    				<a id="confirm_save" class="btn green-haze" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
                    <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
    			</div>		
			</div>
		<?=form_close()?>
	</div>
</div>
