	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_edit_biaya", 
			    "name"          => "form_edit_biaya", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    $hidden = array(
		        "command"   => "edit",
		        "id"		=> $pk
		    );
		    echo form_open(base_url()."master/biaya/save", $form_attr, $hidden);

			$item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
		?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-pencil font-blue-sharp"> </i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Edit Data Biaya", $this->session->userdata("language"))?></span>
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
		     <input type="hidden" id="pk" name="pk" value="<?=$pk?>">
			<div class="form-group">
				<label class="control-label col-md-4"><?=translate("Pilih Kategori Biaya", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
				<div class="form-group">
				<div class="col-md-4">
					<?php
						$kategori_biaya = $this->m_kategori_biaya->get_by(array('is_active' => 1));

						$kategori_biaya_option = array();

						foreach ($kategori_biaya as $kategori_biaya) {
							$kategori_biaya_option[$kategori_biaya->id] = $kategori_biaya->nama;
						}

						echo form_dropdown('kategori_biaya', $kategori_biaya_option, $form_data['kategori_biaya'], 'id="kategori_biaya" class="form-control"');
					?>
				</div>
			</div>

				<div class="form-group">
				<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-4">
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
			<?php $msg = translate("Apakah anda yakin akan merubah data kategori biaya ini?",$this->session->userdata("language"));?>
		</div>
			<div class="form-actions right">	
			<a class="btn default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
    				<a id="confirm_save" class="btn  btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$msg_proses?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>	
    			</div>		
			</div>
		</div>

		<?=form_close()?>
