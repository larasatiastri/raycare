<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">
		<span class="caption-subject font-blue-sharp bold uppercase judul"><?=translate("Lantai ", $this->session->userdata("language"))?><?=$form_data[0]['lantai']?> </span>
		<span class="caption-helper"><label class="control-label"><?=$form_data[0]['bed_nama'].' [ '.$form_data[0]['bed_kode'].' ]'?></label></span>
	</h4>
</div>
<div class="modal-body">
	<form class="form-horizontal">
	<div class="row">
		<div class="col-md-6">
			<div class="embed-responsive embed-responsive-4by3" style="border: 2px solid #cecece;">
			  	<iframe class="embed-responsive-item" src=""></iframe>
			</div>
			<!-- <img src="img.jpg" class="img-thumbnail" width="100%" ></img> -->
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="control-label col-md-6"><?=translate('No. Transaksi', $this->session->userdata('language'))?> :</label>
				<div class="col-md-6">
					<input class="form-control hidden" id="id" value="<?=$bed_id?>">
					<label class="control-label no_transaksi" style="text-align:left;"><?=$form_data[0]['no_transaksi']?></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-6"><?=translate('Tanggal Registrasi', $this->session->userdata('language'))?> :</label>
				<div class="col-md-6">
					<label class="control-label no_transaksi"><?=date('d M Y', strtotime($form_data[0]['tanggal_registrasi']))?></label>
				</div>
			</div>			
			<div class="form-group">
				<label class="control-label col-md-6"><?=translate('Jam Mulai', $this->session->userdata('language'))?> :</label>
				<div class="col-md-6">
					<input class="form-control input-small hidden" id="bed_detail" name="bed_detail">
					<label class="control-label waktu_mulai"><?=$form_data[0]['jam_mulai']?></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-6"><?=translate('Pasien', $this->session->userdata('language'))?> :</label>
				<div class="col-md-6">
					<label class="control-label pasien" style="text-align:left;"><?=$form_data[0]['pasien']?></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-6"><?=translate('Usia', $this->session->userdata('language'))?> :</label>
				<div class="col-md-6">
					<label class="control-label pasien" style="text-align:left;">
					<?php 
						echo date_diff(date_create($form_data[0]['tanggal_lahir']), date_create('today'))->y;
					 ?>
					 tahun
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-6"><?=translate('Dokter', $this->session->userdata('language'))?> :</label>
				<div class="col-md-6">
					<label class="control-label dokter" style="text-align:left;"><?=$form_data[0]['dokter']?></label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-6"><?=translate('Perawat Proses', $this->session->userdata('language'))?> :</label>
				<div class="col-md-6">
					<label class="control-label perawat" style="text-align:left;"><?=$form_data[0]['perawat']?></label>
				</div>
			</div>
			<div class="form-group">
				<b><span class="control-label col-md-6 font-red"><?=translate('Sedang Dibuka', $this->session->userdata('language'))?> :</span></b>

					<b><span class="control-label col-md-6 perawat_open" style="text-align:left;color:red;"><?=$form_data[0]['perawat_open']?></span></b>
			</div>

		</div>
		<?php
			$url = array();
            if ($form_data[0]['url_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$form_data[0]['no_member'].'/foto/'.$form_data[0]['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$form_data[0]['no_member'].'/foto/'.$form_data[0]['url_photo'])) 
                {
                    $img_url = base_url().config_item('site_img_pasien').$form_data[0]['no_member'].'/foto/'.$form_data[0]['url_photo'];
                }
                else
                {
                    $img_url = base_url().config_item('site_img_pasien').'global/global.png';
                }
            } else {

                $img_url = base_url().config_item('site_img_pasien').'global/global.png';
            }

		?>
		<div class="col-md-2">
			<div class="img">
				<img class="img-thumbnail" src="<?=$img_url?>">
			</div>
		</div>
	</div>
	</form>
</div>
<div class="modal-footer">
	<a class="btn default" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
</div>