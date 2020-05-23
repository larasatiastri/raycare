<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp bold"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pemeriksaan Lab", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_pemeriksaan_lab", 
			    "name"          => "form_add_pemeriksaan_lab", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    $hidden = array(
		        "command"   => "edit",
		        "id"	    => $pk_value
		    );
		    echo form_open(base_url()."master/pemeriksaan_lab/save", $form_attr, $hidden);

			
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
					<label class="control-label col-md-3"><?=translate("Kategori", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$data_kategori = $this->kategori_pemeriksaan_lab_m->get_by(array('is_active' => 1));

							$option_kategori = array(
								'' => 'Pilih...'
							);

							foreach ($data_kategori as $key => $kategori) {
								$option_kategori[$kategori->id] = $kategori->tipe.' - '.$kategori->nama;
							}
							echo form_dropdown('kategori_id', $option_kategori,$form_data['kategori_pemeriksaan_id'],'id="kategori_id" class="form-control"');
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Kode", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$kode = array(
								"name"			=> "kode",
								"id"			=> "kode",
								"autofocus"		=> true,
								"class"			=> "form-control", 
								"placeholder"	=> translate("Kode", $this->session->userdata("language")), 
								"required"		=> "required",
								"value"			=> $form_data['kode']
							);
							echo form_input($kode);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$nama = array(
								"name"			=> "nama",
								"id"			=> "nama",
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
					<label class="control-label col-md-3"><?=translate("Satuan", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<?php
							$satuan = array(
								"name"			=> "satuan",
								"id"			=> "satuan",
								"class"			=> "form-control",
								"placeholder"	=> translate("Satuan", $this->session->userdata("language")), 
							);
							echo form_input($satuan);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Harga", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$harga = array(
								"name"			=> "harga",
								"id"			=> "harga",
								"class"			=> "form-control", 
								"value"			=> $form_data['satuan'],
								"placeholder"	=> translate("Harga", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($harga);
						?>
					</div>
				</div>
				
				  
			</div>
			<?php $msg = translate("Apakah anda yakin akan mengubah pemeriksaan lab ini?",$this->session->userdata("language"));?>
			<div class="form-actions fluid">	
				<div class="col-md-offset-3">
    				<a class="btn default" href="javascript:history.go(-1)">
    					<i class="glyphicon glyphicon-chevron-left"></i>
    					<?=translate("Kembali", $this->session->userdata("language"))?>
    				</a>
                    <button type="reset" class="btn default" >
                    	<i class="fa fa-eraser"></i>
                    	<?=translate("Reset", $this->session->userdata("language"))?>
                    </button>
    				<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
    					<i class="glyphicon glyphicon-floppy-disk"></i>
    					<?=translate("Simpan", $this->session->userdata("language"))?>
    				</a>
                    <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
    			</div>		
			</div>
		<?=form_close()?>
	</div>
</div>