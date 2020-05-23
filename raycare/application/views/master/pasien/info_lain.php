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
			<input type="hidden" name="no_member" id="no_member" value="<?=$form_data['no_member']?>" >
			<input type="hidden" name="pasien_id" id="pasien_id" value="<?=$pk_value?>" >
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
							<img src="<?=$img_src?>" alt="Foto tidak ditemukan" class="img-thumbnail img-responsive" style="padding:0px;border:0px;">
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
				<li class="">
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

				<li class="active">
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
				<i class="icon-info font-blue-sharp"></i>
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Lain", $this->session->userdata("language"))?></span>

			</div>
		</div>
		<div class="portlet-body form">

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate("Dokter Pengirim", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-12">
							<?php
								$dokter_pengirim = array(
									"id"			=> "dokter_pengirim",
									"name"			=> "dokter_pengirim",
									"class"			=> "form-control", 
									"placeholder"	=> translate("Dokter Pengirim", $this->session->userdata("language")), 
									"value"			=> $form_data['dokter_pengirim'],
									"help"			=> $flash_form_data['dokter_pengirim'],
								);
								echo form_input($dokter_pengirim);
							?>
						</div>
					</div>

				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate("Referensi Dari Pasien Lain", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-12">
							<div class="input-group">
								<?php

									$get_ref_pasien = $this->pasien_m->get_by(array('id' => $form_data['pasien_id']));
									$data_ref_pasien = object_to_array($get_ref_pasien);
									$ref_pasien = '';
									
									foreach ($data_ref_pasien as $data) {
										$ref_pasien = $data['nama'];
									}

									$nama_ref_pasien = array(
										"id"			=> "nama_ref_pasien",
										"name"			=> "nama_ref_pasien",
										"class"			=> "form-control", 
										"placeholder"	=> translate("Referensi Pasien", $this->session->userdata("language")), 
										"value"			=> $ref_pasien,
										"help"			=> $flash_form_data['nama_ref_pasien'],
									);

									$id_ref_pasien = array(
										"id"			=> "id_ref_pasien",
										"name"			=> "id_ref_pasien",
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("ID Referensi Pasien", $this->session->userdata("language")), 
										"value"			=> $form_data['pasien_id'],
										"help"			=> $flash_form_data['id_ref_pasien'],
									);
									echo form_input($nama_ref_pasien);
									echo form_input($id_ref_pasien);
								?>
								<span class="input-group-btn">
									<a class="btn grey-cascade pilih-pasien" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
										<i class="fa fa-search"></i>
										<span>&nbsp;Pilih Pasien</span>
									</a>
								</span>
							</div>
							
							
						</div>
						
					</div>

				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate("Penyakit Bawaan", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-12">
							<?php
								$pasien_penyakit = $this->pasien_penyakit_m->get_by
													(
														array(
															'pasien_id' => $form_data['id'],
															'is_active' => '1'
														)
													);
								$data_pasien_penyakit = object_to_array($pasien_penyakit);

								$pasien_penyakit_id = array(
									''	=> '',

									);

								foreach ($data_pasien_penyakit as $data) {
									$pasien_penyakit_id[$data['penyakit_id']] = $data['penyakit_id'] ;
								}


								$penyakit_bawaan = $this->penyakit_m->get_by(array('tipe' => 1));
								$penyakit_bawaan_array = object_to_array($penyakit_bawaan);
								
								$penyakit_bawaan_option = array(
									''	=> '',
								);

								foreach ($penyakit_bawaan_array as $select) {
							        $penyakit_bawaan_option[$select['id']] = $select['nama'];
							    }

								echo form_dropdown('penyakit_bawaan[]', $penyakit_bawaan_option, $pasien_penyakit_id, "id=\"multi_select_penyakit_bawaan\" class=\"multi-select\" multiple=\"multiple\"");
									
							?>
						</div>
						
					</div>

				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate("Penyakit Penyebab", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-12">
							<?php
								$pasien_penyakit = $this->pasien_penyakit_m->get_by
													(
														array(
															'pasien_id' => $form_data['id'],
															'is_active' => '1'
														)
													);
								$data_pasien_penyakit = object_to_array($pasien_penyakit);

								$pasien_penyakit_id = array(
									''	=> '',
									
									);

								foreach ($data_pasien_penyakit as $data) {
									$pasien_penyakit_id[$data['penyakit_id']] = $data['penyakit_id'] ;
								}

								$penyakit_penyebab = $this->penyakit_m->get_by(array('tipe' => 2));
								$penyakit_penyebab_array = object_to_array($penyakit_penyebab);
								
								$penyakit_penyebab_option = array(
									''	=> '',
								);

							    foreach ($penyakit_penyebab_array as $select) {
							        $penyakit_penyebab_option[$select['id']] = $select['nama'];
							    }
								echo form_dropdown('penyakit_penyebab[]', $penyakit_penyebab_option, $pasien_penyakit_id, "id=\"multi_select_penyakit_penyebab\" class=\"multi-select\" multiple=\"multiple\"");
									
							?>
						</div>
						
					</div>

				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-12">
							<?php
								$keterangan = array(
									"id"			=> "keterangan",
									"name"			=> "keterangan",
									"rows"			=> 5, 
									"class"			=> "form-control", 
									"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
									"value"			=> $form_data['keterangan'],
									"help"			=> $flash_form_data['keterangan'],
								);
								echo form_textarea($keterangan);				
							?>
						</div>
						
					</div>
				</div>
			</div>

			
			
			
			
			

			<div class="form-actions right">
				<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
					<i class="fa fa-chevron-left"></i>
					<?=translate("Kembali", $this->session->userdata("language"))?>
				</a>
				<a id="confirm_save_info_lain" class="btn btn-circle btn-primary" data-confirm="<?=$msg?>" data-toggle="modal">
					<i class="glyphicon glyphicon-floppy-disk"></i>
					<?=translate("Simpan", $this->session->userdata("language"))?>
				</a>
        		<button type="submit" id="save" class="btn default hidden" >
        			<?=translate("Simpan", $this->session->userdata("language"))?>
        		</button>
			</div>
		</div>
	</div>
</div>
<?=form_close()?>
<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tempat, Tanggal Lahir', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

