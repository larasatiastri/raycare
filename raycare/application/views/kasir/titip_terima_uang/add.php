<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Titip Uang", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_titip_uang", 
			    "name"          => "form_add_titip_uang", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."kasir/titip_terima_uang/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');
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
				<label class="control-label col-md-4"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
				<div class="col-md-2">
					<div class="input-group date" id="tanggal">
						<input type="text" class="form-control" id="tanggal" name="tanggal" readonly >
						<span class="input-group-btn">
							<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
						</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-4"><?=translate("Diterima Oleh", $this->session->userdata("language"))?> :</label>
				
				<div class="col-md-2">
					<input class="form-control input-sm" id="nomer_{0}" name="nama_ref_user" value="<?=$flash_form_data["nama_ref_user"]?>" placeholder="Diterima Oleh" required>
					<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="<?=$flash_form_data["id_ref_pasien"]?>" placeholder="ID Referensi Pasien" required>
					<input class="form-control input-sm hidden" id="nomer_{0}" name="tipe_user" required>
				</div>
				<span class="input-group-btn" style="left:-15px;">
					<a class="btn btn-xs btn-primary pilih-user" style="height:20px;" title="<?=translate('Pilih User', $this->session->userdata('language'))?>">
						<i class="fa fa-search"></i>
					</a>
				</span>
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-4"><?=translate("Rupiah", $this->session->userdata("language"))?> :</label>
				<div class="col-md-2">
					<?php
						$rupiah = array(
							"name"			=> "rupiah",
							"id"			=> "rupiah",
							"autofocus"			=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Rupiah", $this->session->userdata("language")), 
							"required"		=> "required"
						);
						echo form_input($rupiah);
					?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-4"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
				<div class="col-md-2">
					<?php
						$subjek = array(
							"name"			=> "subjek",
							"id"			=> "subjek",
							"autofocus"		=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Subjek", $this->session->userdata("language")), 
							"required"		=> "required"
						);
						echo form_input($subjek);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
				<div class="col-md-2">
					<?php
						$keterangan = array(
							"name"			=> "keterangan",
							"id"			=> "keterangan",
							"class"			=> "form-control",
							"rows"			=> 6, 
							"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
						);
						echo form_textarea($keterangan);
					?>
				</div>
			</div>

			
</div>

		<?php $msg = translate("Apakah anda yakin akan menambah Titip Uang ?",$this->session->userdata("language"));?>
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

<div id="popover_pasien_content" class="row" style="display:none">
	<div class="portlet-body form">
			<div class="form-body">
				<ul class="nav nav-tabs">
					<li  class="active">
						<a href="#user" data-toggle="tab">
							<?=translate('User', $this->session->userdata('language'))?> </a>
					</li>
					<li>
						<a href="#gudang_orang" data-toggle="tab">
							<?=translate('Gudang Orang', $this->session->userdata('language'))?> </a>
					</li>
				</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="user" >
					<?php include('tab_data_user/user.php') ?>
				</div>
				<div class="tab-pane" id="gudang_orang">
					<?php include('tab_data_user/gudang_orang.php') ?>
				</div>
			</div>
		</div>
	</div>	
</div>





