<?php
			$form_attr = array(
			    "id"            => "form_view_inventaris", 
			    "name"          => "form_view_inventaris", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "view"
		    );

		    echo form_open(base_url()."inventaris/inventaris/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

			if($form_data['url_foto'] != '')
		    {
		        if (file_exists(FCPATH.config_item('site_img_inv_dir').$form_data['id'].'/'.$form_data['url_foto']) && is_file(FCPATH.config_item('site_img_inv_dir').$form_data['id'].'/'.$form_data['url_foto'])) 
		        {
		            $image_inventaris = config_item('base_dir').config_item('site_img_inv_dir').$form_data['id'].'/'.$form_data['url_foto'];
		        	$file_img = $form_data['url_foto'];
		        }
		        else
		        {
		            $image_inventaris = config_item('base_dir').config_item('site_img_inv_dir').'global/global.png';
		            $file_img = 'global.png';
		        }
		    }
			

?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-search font-blue-sharp"> </i>
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
				<label class="control-label col-md-3"><?=translate("No. Inventaris", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=$form_data["no_inventaris"]?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Merk", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=$form_data["merk"]?></strong></label>
				</div>
				
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=$form_data["tipe"]?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Serial Number", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=$form_data["serial_number"]?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Tanggal Pembelian", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=date('d M Y',strtotime($form_data["tanggal_pembelian"]))?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Pembeli", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<?php $user_pembeli = $this->user_m->get($form_data['pembeli'])?>
					<label class="control-label"><strong><?=$user_pembeli->nama?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Garansi", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=$form_data["garansi"]?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Alamat IP", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=$form_data["ip_address"]?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Tanggal Serah Terima", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=date('d M Y',strtotime($form_data["tanggal_serah_terima"]))?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Tanggal Kembali", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=date('d M Y',strtotime($form_data["tanggal_kembali"]))?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Pengguna", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<?=$user_pengguna = $this->user_m->get($form_data['pengguna'])?>
					<label class="control-label"><strong><?=$user_pengguna->nama?></strong></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Jadwal Maintenance", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<label class="control-label"><strong><?=date('d M Y',strtotime($form_data["jadwal_maintenance"]))?></strong></label>
				</div>
			</div>
			
            <div class="form-group">
				<label class="control-label col-md-3"><?=translate('Foto', $this->session->userdata('language'))?> :</label>
				<div class="col-md-6">
					<div id="upload">
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
					<label class="control-label"><strong><?=($form_data["keterangan"] != '')?$form_data["keterangan"]:'-'?></strong></label>
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
		</div>
	</div>
</div>
<?=form_close()?>



