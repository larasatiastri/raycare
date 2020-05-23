<?php 
	$form_attr = array(
		"id"            => "form_tambah", 
		"name"          => "form_tambah", 
		"autocomplete"  => "off", 
		"class"         => "form-horizontal",
		"role"			=> "form"
	);
	$hidden = array(
		"command"   => "add_kategori"
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
							"required"		=> "required"
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
							"required"		=> "required"
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
						echo form_dropdown("tipe_akun", $tipe_akun_option, "", " id=\"tipe akun\" class=\"form-control\" ");
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
							"rows"			=> "5" 								
							// "required"		=> "required"
						);
						echo form_textarea($attr_keterangan);
					 ?>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<?php $msg = translate("Apakah anda yakin akan membuat kategori ini?",$this->session->userdata("language"));?>
			<div class="col-md-offset-3 col-md-9">
    			<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                <!-- <button type="reset" class="btn default" ><?=translate("Reset", $this->session->userdata("language"))?></button> -->
				<a class="btn btn-primary" data-confirm="<?=$msg?>" id="confirm_save" data-toggle="modal"><?=translate('Simpan', $this->session->userdata('language'))?></a>
                <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>
		</div>
	</div>
</div>
<?=form_close()?>
