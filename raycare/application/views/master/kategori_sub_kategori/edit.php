<?php 
	$form_attr = array(
		"id"            => "form_edit", 
		"name"          => "form_edit", 
		"autocomplete"  => "off", 
		"class"         => "form-horizontal",
		"role"			=> "form"
	);
	$hidden = array(
		"command"   => "edit_kategori",
		"id"		=> $pk
	);
	echo form_open(base_url()."master/kategori_sub_kategori/save", $form_attr, $hidden);

	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('KATEGORI', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions">
			
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

			<div class="form-group" style="margin-top: 10px;">
				<label class="control-label col-md-3"><?=translate('Kode', $this->session->userdata('language'))?><span class="required">*</span> :</label>
				<div class="col-md-2">
					<?php 
						$attr_kode = array(
							"name"			=> "kode",
							"id"			=> "kode",
							"autofocus"		=> true,
							"maxlength"		=> 3,
							"class"			=> "form-control",
							"placeholder"	=> translate('Kode', $this->session->userdata('language')),
							"required"		=> "required",
							"value"			=> $form_data['kode']
						);
						echo form_input($attr_kode);
					 ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate('Nama', $this->session->userdata('language'))?><span class="required">*</span> :</label>
				<div class="col-md-2">
					<?php 
						$attr_nama = array(
							"name"			=> "nama",
							"id"			=> "nama",
							"class"			=> "form-control",
							"placeholder"	=> translate('Nama', $this->session->userdata('language')),
							"required"		=> "required",
							"value"			=> $form_data['nama']
						);
						echo form_input($attr_nama);
					 ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate('Tipe Akun', $this->session->userdata('language'))?> :</label>
				<div class="col-md-2">
					<?php 

						$tipe_akun_option = array(
							""			=> "Pilih...",
							"1"			=> "Persediaan Barang",
							"2"			=> "Harta Lancar",
							"3"			=> "Harta Tetap",
							"4"			=> "Jasa",
						);
						$tipe_akun = $this->akun_kategori_m->get();
						echo form_dropdown("tipe_akun", $tipe_akun_option, $form_data['tipe_akun'], " id=\"tipe akun\" class=\"form-control\" ");
					 ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
				<div class="col-md-3">
					<?php 
						$attr_keterangan = array(
							"name"			=> "keterangan",
							"id"			=> "keterangan",
							"class"			=> "form-control",
							"placeholder"	=> translate('Keterangan', $this->session->userdata('language')),
							"rows"			=> "5",
							"value"			=> $form_data['keterangan']
							// "required"		=> "required"
						);
						echo form_textarea($attr_keterangan);
					 ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Create By", $this->session->userdata("language"))?> :</label>
				<div class="col-md-3">
					<?php 
						$user_create = $this->user_m->get($form_data['created_by']);
					?>
					<label class="control-label"> <?=$user_create->nama?></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Create Date", $this->session->userdata("language"))?> :</label>
				<div class="col-md-3">
					<label class="control-label"><?=date('d F Y H:i:s', strtotime($form_data['created_date']))?></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Modified By", $this->session->userdata("language"))?> :</label>
				<div class="col-md-3">
					<?php 
						if ($form_data['modified_by']) 
						{
							$user_modified = $this->user_m->get($form_data['modified_by']);
							$user_modified = object_to_array($user_modified);
						}
						else {
							$user_modified['nama'] = $form_data['modified_by'];
						}
					?>
					<label class="control-label"><?=$user_modified['nama']?></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Modified Date", $this->session->userdata("language"))?> :</label>
				<div class="col-md-3">
					<?php 
						if ($form_data['modified_date']) 
						{
							$modified_date = date('d F Y H:i:s', strtotime($form_data['modified_date']));
						} else {
							$modified_date = $form_data['modified_date'];
						}
					?>
					<label class="control-label"><?=$modified_date?></label>
					<input type="hidden" name="modified_date" value="<?=$form_data['modified_date']?>">
					<a target="_blank" id="open_new_tab" class="btn btn-sm btn-primary hidden" href="<?=base_url()?>master/kategori_sub_kategori/edit/<?=$pk?>" ><?=translate("Open", $this->session->userdata("language"))?></a>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<?php $msg = translate("Apakah anda yakin akan mengubah kategori ini?",$this->session->userdata("language"));?>
			<div class="col-md-offset-3 col-md-9">
    			<a class="btn default" href="<?=base_url()?>master/kategori_sub_kategori"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				<a class="btn btn-primary" data-confirm="<?=$msg?>" id="confirm_save" data-toggle="modal"><?=translate('Simpan', $this->session->userdata('language'))?></a>
                <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>
		</div>
	</div>
</div>
<?=form_close()?>
