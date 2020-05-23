<?php
	$form_attr = array(
	    "id"            => "form_edit_pasien", 
	    "name"          => "form_edit_pasien", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "edit",
        "id"		=> $pk_value
    );

    echo form_open("#", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$msg = translate("Apakah anda yakin akan mengubah penjamin pasien ini?",$this->session->userdata("language"));
?>	
<!-- BEGIN PROFILE SIDEBAR -->
					<div class="profile-sidebar" style="width: 250px;">
						<!-- PORTLET MAIN -->
						<div class="portlet light profile-sidebar-portlet" style="padding-left:0px !important;padding-right:0px !important;">
						<div class="patient-padding-picture"></div>
							<div class="form-body">






								<!-- SIDEBAR USERPIC -->
								<input type="hidden" name="tanggal" id="tanggal" value="<?=date('M Y')?>" >
								<input type="hidden" name="url" id="url" value="<?=$form_data['url_photo']?>" >
								<input type="hidden" name="pegawai_id" id="pegawai_id" value="<?=$pk_value?>" >
								<div id="upload" class="profile-userpic" style="text-align:center">
								<?php

											$url_photo = 'global.png';
											$img_src = config_item('site_img_pasien_temp_dir_copy').'global/global.png';

											if($form_data['url_photo'] != '' && $form_data['url_photo'] != 'global.png' || $form_data['url_photo'] != NULL && $form_data['url_photo'] != 'global.png')
											{
												$url_photo = $form_data['url_photo'];
												$img_src = config_item('site_img_pasien_temp_dir_copy').$form_data['url_photo'];
												if($form_data['url_photo'] != 'global/global.png')
												{
													$img_src = config_item('site_img_pasien_temp_dir_copy').$form_data['no_member'].'/foto/'.$form_data['url_photo'];													
												}
											}
										?>								<!-- <a class="fancybox-button" title="<?=$form_data['url_photo']?>" href="<?=$img_src?>" data-rel="fancybox-button">
									<img src="<?=$img_src?>" alt="Smiley face" class="img-responsive img-thumbnail">
								</a>
 -->								<!-- <img src="<?=$img_src?>" class="img-responsive" alt="<?=$form_data['url_photo']?>"> -->
								<ul class="ul-img">
									<li class="working">
										<div class="thumbnail" style="border:0px !important;">
											<a class="fancybox-button" title="<?=$form_data['url_photo']?>" href="<?=$img_src?>" data-rel="fancybox-button">
												<img src="<?=$img_src?>" alt="Smiley face" class="img-thumbnail img-responsive" style="padding:0px;border:0px;">
											</a>
										</div>
									</li>
								</ul>
								
								
							</div>
							<!-- END SIDEBAR USERPIC -->
							</div>
							
							<!-- SIDEBAR USER TITLE -->
							<div class="profile-usertitle">
								<div class="profile-usertitle-name">
									 <?=$form_data["nama"]?>								
								</div>
								<div class="profile-usertitle-job">
										<?=$form_data['no_member']?>
								</div>
							</div>
							<!-- END SIDEBAR USER TITLE -->
							
							<!-- SIDEBAR MENU -->
							<div class="profile-usermenu">
								<ul class="nav">
									<li class="">
										<a href="<?=base_url()?>master/pasien/edit/<?=$pk_value?>">
										<i class="icon-user"></i>
										Profil </a>
									</li>
									<li class="">
										<a href="<?=base_url()?>master/pasien/kelayakan_anggota/<?=$pk_value?>">
										<i class="icon-briefcase"></i>
										Kelayakan Anggota </a>
									</li>
									<li class="active">
										<a href="<?=base_url()?>master/pasien/penjamin_pasien/<?=$pk_value?>">
										<i class="fa fa-list-alt"></i>
										Penjamin </a>
									</li>
									<li class="">
										<a href="<?=base_url()?>master/pasien/penanggung/<?=$pk_value?>">
										<i class="icon-check"></i>
										Penanggung </a>
									</li>
									<li class="">
										<a href="<?=base_url()?>master/pasien/dokumen_pasien/<?=$pk_value?>">
										<i class="icon-docs"></i>
										Dokumen </a>
									</li>

									<li>
										<a href="<?=base_url()?>master/pasien/info_lain_pasien/<?=$pk_value?>">
										<i class="icon-info"></i>
										Info Lain </a>
									</li>
									
								</ul>
							</div>
							<!-- END MENU -->
						</div>

						<!-- END PORTLET MAIN -->
					</div>
					<!-- END BEGIN PROFILE SIDEBAR -->
					<!-- BEGIN PROFILE CONTENT -->
					<div class="profile-content">



	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-list-alt font-blue-sharp"></i>
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penjamin Pasien", $this->session->userdata("language"))?></span>
			</div>
		</div>
		<div class="portlet-body form">

															  
				<?php

					$array_check = array();
					$array_enable = array();
					$array_nomor_kartu = array();
					foreach ($data_penjamin as $penjamin) 
					{
						$array_check[$penjamin['id']] = '';
						$array_enable[$penjamin['id']] = '';		
						$array_nomor_kartu[$penjamin['id']] = '';

						if($penjamin['id'] === '1'){
							$array_enable[$penjamin['id']] = 'readonly';
						}

						foreach ($data_penjamin_pasien as $penjamin_pasien) {

							
							if($penjamin['id'] === $penjamin_pasien['penjamin_id']){
								$array_check[$penjamin['id']] = 'checked'; 
								$array_nomor_kartu[$penjamin['id']] = $penjamin_pasien['no_kartu']; 
							}
						}

						$checkbox_penjamin =  '<div class="row"><div class="col-md-6 bold"><div class="checkbox-list">
							 	<label class="bold">
									<input type="checkbox" id="penjamin_id_'.$penjamin['id'].'" '.$array_check[$penjamin['id']].' '.$array_enable[$penjamin['id']].' name="penjamin['.$penjamin['id'].'][penjamin_id]" value="'.$penjamin['id'].'" class="check_penjamin" >'.$penjamin['nama'].'
							 	</label>
						 	</div></div>';

						$checkbox_penjamin .= '<div class="col-md-6"><div class="form-group">
						<div class="col-md-12"><input type="text" class="form-control" name="penjamin['.$penjamin['id'].'][nomor_kartu]" id="penjamin_nomor_kartu_'.$penjamin['id'].'" '.$array_enable[$penjamin['id']].' value="'.$array_nomor_kartu[$penjamin['id']].'" placeholder="No. Kartu"></div>
									</div></div></div>';

						echo $checkbox_penjamin;
					}

				?>

			<div class="form-actions right">
				<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
					<i class="fa fa-chevron-left"></i>
					<?=translate("Kembali", $this->session->userdata("language"))?>
				</a>
				<a id="confirm_save_penjamin" class="btn btn-circle btn-primary" data-confirm="<?=$msg?>" data-toggle="modal">
					<i class="glyphicon glyphicon-floppy-disk"></i>
					<?=translate("Simpan", $this->session->userdata("language"))?>
				</a>
				<button type="submit" id="save" class="btn default hidden" >
					<?=translate("Simpan", $this->session->userdata("language"))?>
				</button>
			</div>
		</div>
	</div>
<?=form_close()?>
