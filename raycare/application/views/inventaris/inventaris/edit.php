<?php
			$form_attr = array(
			    "id"            => "form_edit_inventaris", 
			    "name"          => "form_edit_inventaris", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "edit",
		        "id"		=> $pk_value,
		        "created_by" => $form_data['created_by']
		    );

		    echo form_open(base_url()."inventaris/inventaris/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			if($form_data['url_foto'] != '')
		    {
		        if (file_exists(FCPATH.config_item('site_img_inv_dir').$form_data['id'].'/'.$form_data['url_foto']) && is_file(FCPATH.config_item('site_img_inv_dir').$form_data['id'].'/'.$form_data['url_foto'])) 
		        {
		            $image_inventaris = base_url().config_item('site_img_inv_dir').$form_data['id'].'/'.$form_data['url_foto'];
		        	$file_img = $form_data['url_foto'];
		        }
		        else
		        {
		            $image_inventaris = base_url().config_item('site_img_inv_dir').'global/global.png';
		            $file_img = 'global.png';
		        }
		    }
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil font-blue-sharp"> </i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Inventaris", $this->session->userdata("language"))?></span>
			<span class="caption-helper"><label class="control-label "><?=$form_data["no_inventaris"]?></label></span>
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
				<label class="control-label col-md-3"><?=translate("Merk", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-6">
					<?php
						$merk = array(
							"id"			=> "merk",
							"name"			=> "merk",
							"autofocus"			=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Merk", $this->session->userdata("language")), 
							"required"		=> "required",
							"value"		=> $form_data["merk"]
						);
						echo form_input($merk);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Tipe", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-6">
					<?php
						$tipe = array(
							"id"			=> "tipe",
							"name"			=> "tipe",
							"autofocus"			=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Tipe", $this->session->userdata("language")), 
							"required"		=> "required",
							"value"		=> $form_data["tipe"]
						);
						echo form_input($tipe);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Serial Number", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-6">
					<?php
						$serial_number = array(
							"id"			=> "serial_number",
							"name"			=> "serial_number",
							"autofocus"			=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Serial Number", $this->session->userdata("language")), 
							"required"		=> "required",
							"value"		=> $form_data["serial_number"]
						);
						echo form_input($serial_number);
					?>
				</div>
			</div>
			<div class="form-group">
                <label class="control-label col-md-3"><?=translate("Tanggal Pembelian", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                <div class="col-md-6">
                    <div class="input-group date" id="tanggal_pembelian">
                        <input type="text" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" value="<?=date('d M Y', strtotime($form_data["tanggal_pembelian"]))?>" required readonly >
                        <span class="input-group-btn">
                            <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>

                </div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3"><?=translate("Pembeli", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
				<div class="col-md-6">
					<?php
						$users = $this->user_m->get_by(array('is_active' => 1));

						$user_option = array();

						foreach ($users as $user) {
							$user_option[$user->id] = $user->nama;
						}

						echo form_dropdown('pembeli_id', $user_option, $form_data['pembeli'], 'id="pembeli_id" class="form-control"');
					?>
				</div>
			</div>
            <div class="form-group">
				<label class="control-label col-md-3"><?=translate("Garansi", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-6">
					<?php
						$garansi = array(
							"id"			=> "garansi",
							"name"			=> "garansi",
							"autofocus"			=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Garansi", $this->session->userdata("language")), 
							"required"		=> "required",
							"value"		=> $form_data["garansi"]
						);
						echo form_input($garansi);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Ip Address", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-6">
					<?php
						$ip_address = array(
							"id"			=> "ip_address",
							"name"			=> "ip_address",
							"autofocus"			=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Ip Address", $this->session->userdata("language")), 
							"required"		=> "required",
							"value"		=> $form_data["ip_address"]
						);
						echo form_input($ip_address);
					?>
				</div>
			</div>
			<div class="form-group">
                <label class="control-label col-md-3"><?=translate("Tanggal Serah Terima", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                <div class="col-md-6">
                    <div class="input-group date" id="tanggal_serah_terima">
                        <input type="text" class="form-control" id="tanggal_serah_terima" name="tanggal_serah_terima" value="<?=date('d M Y', strtotime($form_data["tanggal_serah_terima"]))?>" required readonly >
                        <span class="input-group-btn">
                            <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>

                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3"><?=translate("Tanggal Kembali", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                <div class="col-md-6">
                    <div class="input-group date" id="tanggal_kembali">
                        <input type="text" class="form-control" id="tanggal_kembali" name="tanggal_kembali" value="<?=date('d M Y', strtotime($form_data["tanggal_kembali"]))?>" required readonly >
                        <span class="input-group-btn">
                            <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>

                </div>
            </div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Pengguna", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
				<div class="col-md-6">
					<?php
						$users = $this->user_m->get_by(array('is_active' => 1));

						$user_option = array();

						foreach ($users as $user) {
							$user_option[$user->id] = $user->nama;
						}

						echo form_dropdown('user_id', $user_option, $form_data['pengguna'], 'id="user_id" class="form-control"');
					?>
				</div>
			</div>
			<div class="form-group">
                <label class="control-label col-md-3"><?=translate("Jadwal Maintenance", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                <div class="col-md-6">
                    <div class="input-group date" id="jadwal_maintenance">
                        <input type="text" class="form-control" id="jadwal_maintenance" name="jadwal_maintenance" value="<?=date('d M Y',strtotime($form_data["jadwal_maintenance"]))?>" required readonly >
                        <span class="input-group-btn">
                            <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>

                </div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3"><?=translate('Foto', $this->session->userdata('language'))?> :</label>
				<div class="col-md-6">
					<input type="hidden" name="url" id="url" value="<?=$file_img?>">
					<div id="upload">
						<span class="btn default btn-file">
							<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>		
							<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_photo" multiple />
						</span>

					<ul class="ul-img">
							<li class="working">
							<div class="thumbnail">
							<a class="fancybox-button" title="<?=$file_img?>" href="<?=$image_inventaris?>" data-rel="fancybox-button"><img src="<?=$image_inventaris?>" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>
							</div>
							<span></span></li>
						</ul>

					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
				
				<div class="col-md-6">
					<?php
						$keterangan = array(
							"id"			=> "keterangan",
							"name"			=> "keterangan",
							"rows"			=> 5, 
							"autofocus"		=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
							"value"			=> $form_data['keterangan'],
							"help"			=> $flash_form_data['keterangan'],
						);
						echo form_textarea($keterangan);				
					?>
				</div>
				
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
				<?php $user_buat = $this->user_m->get($form_data['created_by'])?>
					<label class="control-label"><strong><?=$user_buat->nama?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Divisi", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
				<?php $divisi_buat = $this->divisi_m->get($form_data['divisi_id'])?>
					<label class="control-label"><strong><?=$divisi_buat->nama?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Tanggal Dibuat", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=date('d M Y, H:i', strtotime($form_data["created_date"]))?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Diubah Oleh", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
				<?php 	if($form_data['modified_by'] != NULL){
							$user_ubah = $this->user_m->get($form_data['modified_by']);
							$ubah = $user_ubah->nama;
							$tgl_ubah = date('d M Y, H:i', strtotime($form_data['modified_date']));
					  	}else{
					  		$ubah = '-';
					  		$tgl_ubah = '-';
					  	}
						?>
					<label class="control-label"><strong><?=$ubah?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Tanggal Ubah", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=$tgl_ubah?></strong></label>
				</div>
			</div>


		</div>
		<?php $msg = translate("Apakah anda yakin akan menyimpan data inventaris ini?",$this->session->userdata("language"));?>
		<?php $msg_proses = translate("Sedang diproses",$this->session->userdata("language"));?>
		<div class="form-actions right">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>inventaris/inventaris">
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



