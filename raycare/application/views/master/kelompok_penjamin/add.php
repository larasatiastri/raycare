<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("INFORMASI KELOMPOK PENJAMIN", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_kelompok_penjamin", 
			    "name"          => "form_add_kelompok_penjamin", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    $hidden = array(
		        "command"   => "add"
		    );
		    echo form_open(base_url()."master/kelompok_penjamin/save", $form_attr, $hidden);

		    
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
					<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$kode_cabang = array(
								"name"			=> "nama",
								"id"			=> "nama",
								"autofocus"		=> true,
								"class"			=> "form-control", 
								"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($kode_cabang);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("URL", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$nama_cabang = array(
								"name"			=> "url",
								"id"			=> "url",
								"class"			=> "form-control", 
								"placeholder"	=> translate("URL", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($nama_cabang);
						?>
					</div>
				</div>
			 
			</div>
			<?php $msg = translate("Apakah anda yakin akan menambah kelompok penjamin ini?",$this->session->userdata("language"));?>
			<div class="form-actions fluid">	
				<div class="col-md-offset-2 col-md-9">
    				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                    
    				<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
                    <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
    			</div>		
			</div>
		<?=form_close()?>
	</div>
</div>

