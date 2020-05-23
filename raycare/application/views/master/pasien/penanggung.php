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
			$alamat_sub = $this->pasien_m->get_data_subjek(1);
			$alamat_sub_array = $alamat_sub->result_array();

			$alamat_sub_option = array(
				'' => "Pilih..",

			);
		    foreach ($alamat_sub_array as $select) {
		        $alamat_sub_option[$select['id']] = $select['nama'];
		    }

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
							<img src="<?=$img_src?>" alt="Foto tidak ditemukan"  class="img-thumbnail img-responsive" style="padding:0px;border:0px;">
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
				<li class="active">
					<a href="<?=base_url()?>master/pasien/penanggung/<?=$pk_value?>">
					<i class="icon-user"></i>
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
	<div class="portlet">
		<div class="portlet light" id="section-hubungan-pasien">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-users font-blue-sharp"></i>
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penanggung Pasien", $this->session->userdata("language"))?></span>
				</div>
							</div>
			<div class="portlet-body form">
				<?php 	
					$get_hubungan_pasien = $this->pasien_hubungan_m->get_by(array('pasien_id' => $form_data['id'], 'is_active' => '1'));
					$data_hubungan_pasien = object_to_array($get_hubungan_pasien);

					$data_negara = $this->region_m->get_by(array('parent' => null));
					$data_negara_array = object_to_array($data_negara);
					
					$data_negara_option = array(
						'' => "Pilih..",
					);
				    foreach ($data_negara_array as $select) {
				        $data_negara_option[$select['id']] = $select['nama'];
				    }

				    $telp_sub = $this->pasien_m->get_data_subjek(2);
					$telp_sub_array = $telp_sub->result_array();
					
					$telp_sub_option = array(
						'' => "Pilih..",

					);
				    foreach ($telp_sub_array as $select) {
				        $telp_sub_option[$select['id']] = $select['nama'];
				    }

					$indexHP = 1;
					
					$nama_pj = '';
					$ktp_pj = '';
					$style = '';
					// print_r($data_hubungan_pasien);
					if(count($data_hubungan_pasien) == 0)
					{
						$form_hubungan_pasien_edit = array();
					}
					foreach ($data_hubungan_pasien as $hubungan_pasien) {
						$tipe_hp = '';
						$show_pj = '';
						$set_penanggung_jawab ='';
						if ($hubungan_pasien['tipe_hubungan'] == 1) {
							$tipe_hp = '';
							$show_pj = 'hidden';
							$set_penanggung_jawab = '0';
						}else{
							$tipe_hp = 'hidden';
							$nama_pj = $hubungan_pasien['nama'];
							$ktp_pj = $hubungan_pasien['no_ktp'];
							$set_penanggung_jawab = '1';
						}

						$get_hp_alamat = $this->pasien_hubungan_alamat_m->get_by(array('pasien_hubungan_id' => $hubungan_pasien['id']));
						$data_hp_alamat = object_to_array($get_hp_alamat);

						// print_r($data_hp_alamat);
						$indexHPAlamat = 1;
						foreach ($data_hp_alamat as $hp_alamat) {

							// die_dump($data_hp_alamat);
							$primary = "";
							if ($hp_alamat['is_primary'] == "1") {
								$primary = "checked";
							}
							$rt_rw = explode('_', $hp_alamat['rt_rw']);
							$rt = $rt_rw[0];
							$rw = $rt_rw[1];

							$data_provinsi = $this->region_m->get_by(array('parent' => $hp_alamat['negara_id']));
							$data_provinsi_array = object_to_array($data_provinsi);

							$data_provinsi_option = array(
							'' => "Pilih..",
							);
						    foreach ($data_provinsi_array as $select) {
						        $data_provinsi_option[$select['id']] = $select['nama'];
						    }

						    $data_kota = $this->region_m->get_by(array('parent' => $hp_alamat['propinsi_id']));
							$data_kota_array = object_to_array($data_kota);

							$data_kota_option = array(
							'' => "Pilih..",
							);
						    foreach ($data_kota_array as $select) {
						        $data_kota_option[$select['id']] = $select['nama'];
						    }

						    $data_kecamatan = $this->region_m->get_by(array('parent' => $hp_alamat['kota_id']));
							$data_kecamatan_array = object_to_array($data_kecamatan);

							$data_kecamatan_option = array(
							'' => "Pilih..",
							);
						    foreach ($data_kecamatan_array as $select) {
						        $data_kecamatan_option[$select['id']] = $select['nama'];
						    }

						    $data_kelurahan = $this->region_m->get_by(array('parent' => $hp_alamat['kecamatan_id']));
							$data_kelurahan_array = object_to_array($data_kelurahan);

							$data_kelurahan_option = array(
							'' => "Pilih..",
							);
						    foreach ($data_kelurahan_array as $select) {
						        $data_kelurahan_option[$select['id']] = $select['nama'];
						    }

						    $hidden = 'hidden';
						    $nama_kelurahan = '';
						    $nama_kecamatan = '';
						    $nama_kotkab = '';
						    $nama_propinsi = '';
						    $data_lokasi = array();
						    if($hp_alamat['kode_lokasi'] != NULL || $hp_alamat['kode_lokasi'] != '')
						    {
						    	
						    	$hidden = '';

						    	$data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $hp_alamat['kode_lokasi']), true);
						    	$data_lokasi = object_to_array($data_lokasi);
						    	$nama_kelurahan = $data_lokasi['nama_kelurahan'];
							    $nama_kecamatan = $data_lokasi['nama_kecamatan'];
							    $nama_kotkab = $data_lokasi['nama_kabupatenkota'];
							    $nama_propinsi = $data_lokasi['nama_propinsi'];

						    }

							$form_hp_alamat_edit[] = '
							<div id="'.$indexHP.'_hp_alamat_'.$indexHPAlamat.'" style="border:1px solid #eee; padding:12px;background-color:#eee;border-radius:10px;margin-top:10px;">
							<div class="form-group">
							<label class="col-md-12 bold">'.translate("Subjek", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<div class="input-group">
										'.form_dropdown($indexHP.'_hp_alamat['.$indexHPAlamat.'][subjek]', $alamat_sub_option, $hp_alamat['subjek_id'], 'id="'.$indexHP.'_subjek_hp_alamat_'.$indexHPAlamat.'" class="select2me form-control input-sx subjek_alamat hp_subjek" data-id required ').'
										<input type="text" id="'.$indexHP.'_input_subjek_hp_alamat_'.$indexHPAlamat.'" class="form-control hidden send-data input-hp-subjek">
										<input type="hidden" id="'.$indexHP.'_hp_alamat_id_'.$indexHPAlamat.'" name="'.$indexHP.'_hp_alamat['.$indexHPAlamat.'][hp_alamat_id]" class="form-control send-data hp_rt" placeholder="RT" value="'.$hp_alamat['id'].'">
										<input type="hidden" id="'.$indexHP.'_hp_alamat_is_delete_'.$indexHPAlamat.'" name="'.$indexHP.'_hp_alamat['.$indexHPAlamat.'][is_delete]" class="form-control send-data hp_rt" placeholder="'.translate('Is Delete', $this->session->userdata('language')).'">
										
										<span class="input-group-btn">
											<a class="btn blue-chambray edit-subjek"  id="'.$indexHP.'_btn_edit_subjek_hp_alamat_'.$indexHPAlamat.'" data-id="'.$indexHPAlamat.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" ></i></a>
											<a class="btn btn-primary hidden" id="'.$indexHP.'_btn_save_subjek_hp_alamat_'.$indexHPAlamat.'" data-id="'.$indexHPAlamat.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" ></i></a>
											<a class="btn yellow hidden" id="'.$indexHP.'_btn_cancel_subjek_hp_alamat_'.$indexHPAlamat.'" data-id="'.$indexHPAlamat.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" ></i></a>
											<a class="btn red-intense del-db-hp-alamat" id="'.$indexHP.'_btn_delete_subjek_hp_alamat_'.$indexHPAlamat.'" data-id="'.$indexHPAlamat.'" title="'.translate('Remove', $this->session->userdata('language')).'" data-confirm="'.translate('Apakah anda yakin ingin menghapus alamat ini ?', $this->session->userdata('language')).'"><i class="fa fa-times" ></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Alamat", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<textarea id="'.$indexHP.'_hp_alamat_'.$indexHPAlamat.'" required name="'.$indexHP.'_hp_alamat['.$indexHPAlamat.'][alamat]" class="form-control send-data hp_alamat" rows="3" placeholder="Alamat Lengkap">'.$hp_alamat['alamat'].'</textarea>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-12 bold">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											<div class="input-group">
												<input type="text" id="'.$indexHP.'_rt_'.$indexHPAlamat.'" name="'.$indexHP.'_hp_alamat['.$indexHPAlamat.'][rt]" class="form-control send-data hp_rt" placeholder="RT" value="'.$rt.'">
												<span class="input-group-addon">/</span>
												<input type="text" id="'.$indexHP.'_rw_'.$indexHPAlamat.'" name="'.$indexHP.'_hp_alamat['.$indexHPAlamat.'][rw]" class="form-control send-data hp_rw" placeholder="RW" value="'.$rw.'">
											</div>
										</div>
									</div>

								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-12 bold">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											<div class="input-group">
												<input type="text" id="input_hp_kelurahan_'.$indexHPAlamat.'" name="hp_alamat['.$indexHPAlamat.'][kelurahan]"  value="'.$nama_kelurahan.'" class="form-control" readonly>
												<input type="hidden" id="input_hp_kode_'.$indexHPAlamat.'" name="'.$indexHP.'_hp_alamat['.$indexHPAlamat.'][kode]" value="'.$hp_alamat['kode_lokasi'].'" class="form-control hp_kode">
												<span class="input-group-btn">
													<a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_'.$indexHPAlamat.'" title="'.translate('Cari', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/search_kelurahan/hub_pasien/'.$indexHPAlamat.'"><i class="fa fa-search"></i></a>
												</span>
											</div>
										</div>
									</div>

								</div>
							</div>
						<div id="div_hp_lokasi" class="'.$hidden.'">


							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-12 bold">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											<input type="text" id="input_hp_kecamatan_'.$indexHPAlamat.'" name="hp_alamat['.$indexHPAlamat.'][kecamatan]" value="'.$nama_kecamatan.'" class="form-control" readonly>					
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-12 bold">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											
												<input type="text" id="input_hp_kota_'.$indexHPAlamat.'" name="hp_alamat['.$indexHPAlamat.'][kota]" class="form-control" value="'.$nama_kotkab.'" readonly>					
											
										</div>
									</div>

								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-12 bold">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											
												<input type="text" id="input_hp_provinsi_'.$indexHPAlamat.'" name="hp_alamat['.$indexHPAlamat.'][provinsi]" value="'.$nama_propinsi.'" class="form-control" readonly>					
											
										</div>
									</div>

								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-12 bold">'.translate("Negara", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											
												<input type="text" id="input_hp_negara_'.$indexHPAlamat.'" name="hp_alamat['.$indexHPAlamat.'][negara]" class="form-control" readonly value="Indonesia">					
											
										</div>
									</div>

								</div>
							</div>
						</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-12 bold">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											<input type="text" name="'.$indexHP.'_hp_alamat['.$indexHPAlamat.'][kode_pos]" id="'.$indexHP.'_kode_pos_'.$indexHPAlamat.'" class="form-control send-data hp_kode_pos" placeholder="Kode Pos" value="'.$hp_alamat['kode_pos'].'">
										</div>
									</div>
									<div class="form-group ">
										<label class="control-label col-md-12"></label>
										<div class="col-md-12" '.$indexHP.'_primary_alamat">
											<input type="hidden" name="'.$indexHP.'_hp_alamat['.$indexHPAlamat.'][is_primary_hp_alamat]" id="'.$indexHP.'_primary_hp_alamat_id_'.$indexHPAlamat.'" class="hp_primary_alamat '.$indexHP.'_hp_primary_alamat" value="'.$hp_alamat['is_primary'].'">
											<input type="radio" name="'.$indexHP.'_hp_alamat_is_primary" id="'.$indexHP.'_radio_primary_hp_alamat_id_'.$indexHPAlamat.'" '.$primary.' class="is_primary_hp_alamat" data-row="'.$indexHP.'" data-id="'.$indexHPAlamat.'" style="left: 20px;"> '.translate('Utama', $this->session->userdata('language')).'
										</div>
									</div>
								</div>
							</div>
							

							
							</div>
							';
							$indexHPAlamat++;
						}

						// print_r($form_hp_alamat_edit);
						$get_hp_telp = $this->pasien_hubungan_telepon_m->get_by(array('pasien_hubungan_id' => $hubungan_pasien['id']));
						$data_hp_telp = object_to_array($get_hp_telp);

						if(count($data_hp_telp) == 0)
						{
							$form_hp_phone_edit = array();
						}

						$indexHPPhone = 1;
						foreach ($data_hp_telp as $hp_telp) {
							$primary = "";
							if ($hp_telp['is_primary'] == "1") {
								$primary = "checked";
							}

							$form_hp_phone_edit[] = '
							<div id="'.$indexHP.'_hp_telp_'.$indexHPPhone.'" style="border:1px solid #eee; padding:12px;background-color:#eee;border-radius:10px;margin-top:10px;">
							<div class="row">
								<div class="col-md-7">
								<div class="col-md-12">
										<input class="form-control hp_no_telp" required id="nomer_'.$indexHPPhone.'" name="'.$indexHP.'_hp_phone['.$indexHPPhone.'][number]" placeholder="Nomor Telepon" value="'.$hp_telp['nomor'].'">
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group">
										<div class="col-md-12">
											<div class="input-group">
												'.form_dropdown($indexHP.'_hp_phone['.$indexHPPhone.'][subjek]', $telp_sub_option, $hp_telp['subjek_id'], 'id="'.$indexHP.'_subjek_hp_telp_'.$indexHPPhone.'" class="form-control input-sx hp_subjek_telp" required ').'
												<input type="text" id="'.$indexHP.'_input_subjek_hp_telp_'.$indexHPPhone.'" class="form-control hidden input-subjek-telp">
												<input type="hidden" id="'.$indexHP.'_hp_telp_id_'.$indexHPPhone.'" name="'.$indexHP.'_hp_phone['.$indexHPPhone.'][hp_phone_id]" class="form-control send-data hp_rt" placeholder="RT" value="'.$hp_telp['id'].'">
												<input type="hidden" id="'.$indexHP.'_hp_telp_is_delete_'.$indexHPPhone.'" name="'.$indexHP.'_hp_phone['.$indexHPPhone.'][is_delete]" class="form-control send-data hp_rt" placeholder="'.translate('Is Delete', $this->session->userdata('language')).'">
												
												<span class="input-group-btn">
													<a class="btn blue-chambray edit-subjek-telp" id="'.$indexHP.'_btn_edit_subjek_hp_telp_'.$indexHPPhone.'" data-id="'.$indexHPPhone.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" ></i></a>
													<a class="btn btn-primary hidden save-subjek-telp" id="'.$indexHP.'_btn_save_subjek_hp_telp_'.$indexHPPhone.'" data-id="'.$indexHPPhone.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" ></i></a>
													<a class="btn yellow hidden cancel-subjek-telp" id="'.$indexHP.'_btn_cancel_subjek_hp_telp_'.$indexHPPhone.'" data-id="'.$indexHPPhone.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" ></i></a>
													<a class="btn red-intense del-db-hp-telp delete-subjek-telp" id="'.$indexHP.'_btn_delete_subjek_hp_telp_'.$indexHPPhone.'" data-id="'.$indexHPPhone.'" title="'.translate('Remove', $this->session->userdata('language')).'" data-confirm="'.translate('Apakah anda yakin ingin menghapus telepon ini ?', $this->session->userdata('language')).'"><i class="fa fa-times" ></i></a>

												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<div class="col-md-12 '.$indexHP.'_primary_telp"">
											<input type="hidden" name="'.$indexHP.'_hp_phone['.$indexHPPhone.'][is_primary_hp_phone]" id="'.$indexHP.'_primary_hp_phone_id_'.$indexHPPhone.'" class="hp_primary_telp '.$indexHP.'_hp_primary_telp" value="'.$hp_telp['is_primary'].'">
											<input type="radio" name="'.$indexHP.'_hp_phone_is_primary" id="'.$indexHP.'_radio_primary_hp_phone_id_'.$indexHPPhone.'" '.$primary.' class="is_primary_hp_phone" data-row="'.$indexHP.'" data-id="'.$indexHPPhone.'" style="left:20px;"> '.translate('Utama', $this->session->userdata('language')).'
										</div>
									</div>

								</div>
							</div>
							
						</div>';
						$indexHPPhone++;
						}

						$image_kerabat = config_item('site_img_pasien_temp_dir_copy').'doc_global/document.png';
						if(file_exists($_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien_temp_dir_copy').$form_data['no_member'].'/penanggung/'.$hubungan_pasien['url_ktp']) && is_file($_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien_temp_dir_copy').$form_data['no_member'].'/penanggung/'.$hubungan_pasien['url_ktp']))
						{
							$image_kerabat = config_item('site_img_pasien_temp_dir_copy').$form_data['no_member'].'/penanggung/'.$hubungan_pasien['url_ktp'];
						}


						
						$form_hubungan_pasien_edit[] = '
						<div id="hubungan_pasien_'.$indexHP.'" style="padding:12px;border:4px double #2462AC;border-radius:10px;margin-top:10px;">
						<div class="row">
							<div class="col-md-4">
								<div id="group_nama_'.$indexHP.'">

									<div class="form-group" >
										<label class="col-md-12 bold">'.translate("Nama", $this->session->userdata("language")).' :</label>
										<div class="col-md-12 ">
										<div class="input-group">
											<input type="text" class="form-control send-data" id="hubungan_pasien_nama_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][nama]" value="'.$hubungan_pasien['nama'].'" placeholder="Nama Penanggung Jawab">
											<input type="hidden" class="form-control send-data" id="hubungan_pasien_nama_id_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][id]" value="'.$hubungan_pasien['id'].'">
											<input type="hidden" class="form-control send-data" id="hubungan_pasien_is_delete_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][is_delete]">
											<input type="hidden" class="form-control send-data" id="set_penanggung_jawab_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][set_penanggung_jawab]" value="'.$set_penanggung_jawab.'">
											<span class="input-group-btn">
												<a class="btn btn-primary set_penanggung_jawab '.$tipe_hp.'" id="set_penanggung_jawab_'.$indexHP.'" data-row="'.$indexHP.'">'.translate("Set Penanggung Jawab", $this->session->userdata("language")).'</a>
												<a class="btn red-intense del_penanggung_jawab hidden" id="del_penanggung_jawab_'.$indexHP.'" data-row="'.$indexHP.'" data-confirm="'.translate("Apa anda yakin ingin menghapus penanggung jawab ini", $this->session->userdata("language")).'">'.translate("Hapus PJ", $this->session->userdata("language")).'</a>
												<a class="btn btn-success penanggung_jawab '.$show_pj.'" id="penanggung_jawab_'.$indexHP.'" data-row="'.$indexHP.'" >
													'.translate("Penanggung Jawab", $this->session->userdata("language")).'
												</a>
												<a class="btn red-intense del-db-hub-pasien" id="del-db-hub-pasien" title="'.translate('Remove', $this->session->userdata('language')).'" data-confirm="'.translate('Apakah anda yakin ingin menghapus keluarga ini ?', $this->session->userdata('language')).'" data-id="'.$indexHP.'" style="'.$style.'"><i class="fa fa-times"></i></a>
											</span>
										</div>
										</div>
									</div>
								</div>

							</div>
							<div class="col-md-4">
								<div id="group_ktp_'.$indexHP.'">
									<div class="form-group" >
										<label class="col-md-12 bold">'.translate("No. KTP", $this->session->userdata("language")).':</label>
										
										<div class="col-md-12">
											<input type="text" class="form-control send-data" id="hubungan_pasien_ktp_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][ktp]" value="'.$hubungan_pasien['no_ktp'].'" placeholder="No. KTP">

										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-12 bold">'.translate("Scan KTP", $this->session->userdata("language")).' :</label>
									<div class="col-md-12 ">
									<div class="input-group">
										<input type="hidden" name="hubungan_pasien['.$indexHP.'][url_ktp]" id="hubungan_pasien_url_ktp_'.$indexHP.'" data-id="'.$indexHP.'" class="scan-ktp" value="'.$hubungan_pasien['url_ktp'].'">
											<div id="upload_'.$indexHP.'">
												<div id="drop_'.$indexHP.'">
													<span class="btn default btn-file">
														<span class="fileinput-new">Pilih Foto</span>		
														<input type="file" name="upl" id="hubungan_pasien_scan_ktp_'.$indexHP.'" data-url="'.base_url().'upload/upload_photo" multiple />
													</span>
													<span class="help-block">
														Pastikan format gambarnya JPG / PNG						
													</span>
												</div>
											</div>

											<ul class="ul-img">
												<li class="working">
													<div class="thumbnail">
														<a href="'.$image_kerabat.'" target="_blank">
															<img src="'.$image_kerabat.'" alt="Foto tidak ditemukan" class="img-thumbnail" style="max-width:50px; max-height:50px;">
														</a>
													</div>
													<span></span>
												</li>
											</ul>

										</div>
									</div>
									
								</div>

							</div>

						</div>
												
						
						
						<div class="row">
							<div class="col-md-6">
								<div id="section-hp-alamat">
						            <div class="form-group">
						                <div class="col-md-12">
							                <div class="portlet light">
								                <div class="portlet-title">
								                    <div class="caption">
														'.translate('Alamat', $this->session->userdata('language')).'</span>
													</div>
								                </div>
							                	<div class="portlet-body form">
													<div class="form-body" style="margin-top:10px;">
														';
														foreach ($form_hp_alamat_edit as $row_hp_alamat_edit) {
															$form_hubungan_pasien_edit[] .= $row_hp_alamat_edit;

															// print_r($row_hp_alamat_edit);
														}
														$form_hubungan_pasien_edit[] .= '
														<ul class="list-unstyled hp-alamat">
														</ul>
													</div>
													<div class="form-actions" style="padding:10px 0px !important;">
								                        <a  class="btn btn-primary add-hp-alamat" data-row="'.$indexHP.'" data-counter="'.$indexHPAlamat.'">
									                        <i class="fa fa-plus"></i>
									                        '.translate("Tambah Alamat", $this->session->userdata("language")).'
								                    	</a>
								                	</div>

							                	</div>
							                </div>
						                </div>
						            </div>
					        	</div>

							</div>
							<div class="col-md-6">
								<div id="section-hp-phone">
						            <div class="form-group">
						                <div class="col-md-12">
							                <div class="portlet">
								                <div class="portlet-title">
								                    <div class="caption">
														'.translate('Telepon', $this->session->userdata('language')).'
													</div>
								                </div>
							                	<div class="portlet-body form">
													<div class="form-body" style="margin-top:10px;">
														';
														foreach ($form_hp_phone_edit as $row_hp_phone_edit) {
															$form_hubungan_pasien_edit[] .= $row_hp_phone_edit;
														}
														$form_hubungan_pasien_edit[] .= '
														<ul class="list-unstyled hp-phone">
														</ul>
													</div>
													<div class="form-actions" style="padding:10px 0px !important;">
								                        <a  class="btn btn-primary add-hp-phone" data-row="'.$indexHP.'" data-counter="'.$indexHPPhone.'">
									                        <i class="fa fa-plus"></i>
									                        '.translate("Tambah Telepon", $this->session->userdata("language")).'

								                    	</a>
								                	</div>

							                	</div>
							                </div>
						                </div>
						            </div>
					        	</div>

							</div>
						</div>
						
						
			        				        	</div>';
			        $indexHP++;
			        $form_hp_alamat_edit = array();
			        $form_hp_phone_edit = array();
					}
					
					// echo $indexHPAlamat;
					$form_hubungan_pasien = '
					<div id="hubungan_pasien_{0}" >
					<div id="group_nama_{0}" >
						<div class="form-group" >
							<label class="col-md-12 bold">'.translate("Nama", $this->session->userdata("language")).' :</label>
							
							<div class="col-md-12 input-group">
								<input type="text" class="form-control send-data" id="hubungan_pasien_nama_{0}" name="hubungan_pasien[{0}][nama]">
								<input type="hidden" class="form-control send-data" id="set_penanggung_jawab_{0}" name="hubungan_pasien[{0}][set_penanggung_jawab]">
								<span class="input-group-btn">
									<a class="btn btn-primary set_penanggung_jawab" id="set_penanggung_jawab_{0}" data-row="{0}">'.translate("Set Penanggung Jawab", $this->session->userdata("language")).'</a>
									<a class="btn red-intense del_penanggung_jawab hidden" id="del_penanggung_jawab_{0}" data-row="{0}" data-confirm="'.translate("Apa anda yakin ingin menghapus penanggung jawab ini", $this->session->userdata("language")).'">'.translate("Hapus PJ", $this->session->userdata("language")).'</a>
									<a class="label label-success penanggung_jawab hidden" id="penanggung_jawab_{0}" data-row="{0}" >
										'.translate("Penanggung Jawab", $this->session->userdata("language")).'
									</a>
									<a class="btn red-intense del-hub-pasien" id="del-hub-pasien_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>

								</span>
							</div>
						</div>
					</div>
					
					<div id="group_ktp_{0}">
						<div class="form-group" >
							<label class="control-label col-md-4">'.translate("No. KTP", $this->session->userdata("language")).':</label>
							
							<div class="col-md-8 input-group">
								<input type="text" class="form-control send-data" id="hubungan_pasien_ktp_{0}" name="hubungan_pasien[{0}][ktp]" placeholder="No. KTP">
							</div>
						</div>
					</div>
					

					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Scan KTP", $this->session->userdata("language")).' :</label>
						
						<div class="col-md-8 input-group">
							<input type="hidden" name="hubungan_pasien[{0}][url_ktp]" id="hubungan_pasien_url_ktp_{0}">
							<div id="upload_{0}">
								<div id="drop_{0}">	
									<span class="btn default btn-file">
										<span class="fileinput-new">Pilih Foto</span>		
										<input type="file" name="upl" id="hubungan_pasien_scan_ktp_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
									</span>

								</div>

							<ul class="ul-img">
								<!-- The file uploads will be shown here -->
							</ul>

							</div>
						</div>
					</div>

					<div id="section-hp-alamat">
			            <div class="form-group">
			                <div class="col-md-12">
				                <div class="portlet">
					                <div class="portlet-title">
					                    <div class="caption">
											'.translate('Alamat', $this->session->userdata('language')).'
										</div>
					                    <div class="actions">
					                        <a  class="btn btn-circle btn-default btn-icon-only add-hp-alamat">
						                        <i class="fa fa-plus"></i>
					                    	</a>
					                	</div>
					                </div>
				                	<div class="portlet-body">
										<div class="form-body">
											<ul class="list-unstyled hp-alamat">
											</ul>
										</div>
				                	</div>
				                </div>
			                </div>
			            </div>
		        	</div>

		        	<div id="section-hp-phone">
			            <div class="form-group">
			                <div class="col-md-12">
				                <div class="portlet">
					                <div class="portlet-title">
					                    <div class="caption">
											'.translate('Telepon', $this->session->userdata('language')).'
										</div>
					                    <div class="actions">
					                        <a  class="btn btn-circle btn-icon-only btn-default add-hp-phone">
						                        <i class="fa fa-plus"></i>
					                    	</a>
					                	</div>
					                </div>
				                	<div class="portlet-body">
										<div class="form-body">
											<ul class="list-unstyled hp-phone">
											</ul>
										</div>
				                	</div>
				                </div>
			                </div>
			            </div>
		        	</div>
		        	</div>'
					;
				

				$form_hp_alamat = '
				<div  style="border:1px solid #eee; padding:12px;background-color:#eee;border-radius:10px;">
					<div class="form-group">
						<label class="col-md-12 bold">'.translate("Subjek", $this->session->userdata("language")).' :</label>
						<div class="col-md-12">
							<div class="input-group">
								'.form_dropdown('hp_alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_hp_alamat_{0}\" class=\"select2me form-control input-sx subjek_alamat hp_subjek\" required ").'
								<input type="text" id="input_subjek_hp_alamat_{0}" class="form-control hidden send-data input-hp-subjek">
								<input type="hidden" id="hp_alamat_id_{0}" name="hp_alamat[{0}][hp_alamat_id]" class="form-control send-data hp_alamat_id" placeholder="RT">
								<input type="hidden" id="hp_alamat_is_delete{0}" name="hp_alamat[{0}][is_delete]" class="form-control send-data hp_alamat_is_delete" placeholder="'.translate('Is Delete', $this->session->userdata('language')).'">
								
								<span class="input-group-btn">
									<a class="btn blue-chambray edit-subjek" id="btn_edit_subjek_hp_alamat_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-primary hidden save-subjek" id="btn_save_subjek_hp_alamat_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow hidden cancel-subjek" id="btn_cancel_subjek_hp_alamat_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
									<a class="btn red-intense del-this delete-subjek" id="btn_delete_subjek_hp_alamat_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>

								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12 bold">'.translate("Alamat", $this->session->userdata("language")).' :</label>
						<div class="col-md-12">
							<textarea id="hp_alamat_{0}" required name="hp_alamat[{0}][alamat]" class="form-control send-data hp_alamat" rows="3" placeholder="Alamat Lengkap"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("RT/RW", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<div class="input-group">
										<input type="text" id="rt_{0}" name="hp_alamat[{0}][rt]" class="form-control send-data hp_rt" placeholder="RT">
										<span class="input-group-addon">/</span>
										<input type="text" id="rw_{0}" name="hp_alamat[{0}][rw]" class="form-control send-data hp_rw" placeholder="RW">
									</div>


								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<div class="input-group">
										<input type="text" id="input_hp_kelurahan_{0}" name="hp_alamat[{0}][kelurahan]" class="form-control" readonly>
										<input type="hidden" id="input_hp_kode_{0}" name="hp_alamat[{0}][kode]" class="form-control hp_kode">
										<span class="input-group-btn">
											<a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_{0}" title="'.translate('Cari', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/search_kelurahan/hub_pasien/{0}"><i class="fa fa-search"></i></a>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="div_hp_lokasi" class="hidden">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<input type="text" id="input_hp_kecamatan_{0}" name="hp_alamat[{0}][kecamatan]" class="form-control" readonly>					
								</div>
							</div>

						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									
										<input type="text" id="input_hp_kota_{0}" name="hp_alamat[{0}][kota]" class="form-control" readonly>					
									
								</div>
							</div>

						</div>

					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									
										<input type="text" id="input_hp_provinsi_{0}" name="hp_alamat[{0}][provinsi]" class="form-control" readonly>					
									
								</div>
							</div>

						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Negara", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									
										<input type="text" id="input_hp_negara_{0}" name="hp_alamat[{0}][negara]" class="form-control" readonly>					
									
								</div>
							</div>

						</div>

					</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<input type="text" name="hp_alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control send-data hp_kode_pos" placeholder="Kode Pos">
								</div>
							</div>
							<div class="form-group">

								<div class="col-md-12">
									<input hidden type="text" name="hp_alamat[{0}][is_primary_hp_alamat]" id="primary_hp_alamat_id_{0}" class="hp_primary_alamat">
									<input type="radio" name="hp_alamat_is_primary" id="radio_primary_hp_alamat_id_{0}" class="is_primary_hp_alamat"> '.translate('Utama', $this->session->userdata('language')).'
								</div>
							</div>

						</div>
					</div>




				</div>
								
														
				

				';

				$form_hp_phone = '
				<div style="border:1px solid #eee; padding:12px;background-color:#eee;border-radius:10px;">

					<div class="row">
						<div class="col-md-7">
							<input class="form-control hp_no_telp" required id="nomer_{0}" name="hp_phone[{0}][number]" placeholder="Nomor Telepon">

						</div>
						<div class="col-md-5">
							<div class="input-group">
								'.form_dropdown('hp_phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_hp_telp_{0}\" class=\"form-control input-sx hp_subjek_telp\" required ").'
								<input type="text" id="input_subjek_hp_telp_{0}" class="form-control hidden input-subjek-telp">
								<input type="hidden" id="hp_telp_id_{0}" name="hp_phone[{0}][hp_phone_id]" class="form-control send-data hp_telp_id" placeholder="RT">
								<input type="hidden" id="hp_telp_is_delete_{0}" name="hp_phone[{0}][is_delete]" class="form-control send-data hp_telp_is_delete" placeholder="'.translate('Is Delete', $this->session->userdata('language')).'">
											
								<span class="input-group-btn">
									<a class="btn blue-chambray edit-subjek-telp" id="btn_edit_subjek_hp_telp_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-primary hidden save-subjek-telp" id="btn_save_subjek_hp_telp_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow hidden cancel-subjek-telp" id="btn_cancel_subjek_hp_telp_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
									<a class="btn red-intense del-this delete-subjek-telp" id="btn_delete_subjek_hp_telp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12"></label>
								<div class="col-md-12">
									<input type="hidden" name="hp_phone[{0}][is_primary_hp_phone]" id="primary_hp_phone_id_{0}" class="hp_primary_telp">
									<input type="radio" name="hp_phone_is_primary" id="radio_primary_hp_phone_id_{0}" class="is_primary_hp_phone"> '.translate('Utama', $this->session->userdata('language')).'
								</div>
							</div>
						</div>
					</div>


				</div>

				

				';
			?>

				<input type="hidden" id="tpl-form-hubungan-pasien" value="<?=htmlentities($form_hubungan_pasien)?>">
				<input type="hidden" id="tpl-form-hp-alamat" value="<?=htmlentities($form_hp_alamat)?>">
				<input type="hidden" id="tpl-form-hp-phone" value="<?=htmlentities($form_hp_phone)?>">
				<input type="hidden" id="counterHP" value="<?=$indexHP?>">
				
				<?php 
					if($form_hubungan_pasien_edit){
					foreach ($form_hubungan_pasien_edit as $row):?>
		            
		            <?=$row?>
		            
		        <?php endforeach;}?>

				<div class="form-body"  style="margin-top:10px;">
					<ul class="list-unstyled hubungan-pasien">
					</ul>
				</div>

				<div class="form-actions" style="padding:10px 0px !important;">
					<a class="btn btn-primary add-hubungan-pasien">
		                <i class="fa fa-plus"></i>
		                <?=translate("Tambah Penanggung", $this->session->userdata("language"))?>

		            </a>										
				</div>



			</div>
		</div>
	</div>

	<div class="portlet light" id="section-penanggung-jawab">
		<div class="portlet-title">
			<div class="caption">
				<?=translate('Penanggung Jawab', $this->session->userdata('language'))?>
			</div>
			<div class="actions hidden">
				<a class="btn btn-circle btn-icon-only btn-default add-penanggung-jawab">
	                <i class="fa fa-plus"></i>
	            </a>										
			</div>
		</div>
		
		<div class="portlet-body form">
			<?php 
					$form_penanggung_jawab = '
					<div id="penanggung_jawab_{0}">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group" id="group_nama_{0}">
								<label class="control-label col-md-4">'.translate("Nama", $this->session->userdata("language")).' :</label>
								
								<div class="col-md-8 input-group">
									<input type="text" class="form-control send-data" id="penanggung_jawab_nama_{0}" name="penanggung_jawab[{0}][nama]">
									<input type="hidden" class="form-control send-data" id="set_penanggung_jawab_{0}" name="penanggung_jawab[{0}][set_penanggung_jawab]">
									<span class="input-group-btn hidden">
										<a class="btn btn-primary set_penanggung_jawab" id="set_penanggung_jawab_{0}" data-row="{0}">'.translate("Set Penanggung Jawab", $this->session->userdata("language")).'</a>
										<span class="label label-success penanggung_jawab hidden" id="penanggung_jawab_{0}" data-row="{0}" >
											'.translate("Penanggung Jawab", $this->session->userdata("language")).'
										</span>
									</span>
									<span class="input-group-btn hidden">
										<a class="btn red-intense del-hub-pasien" id="del-hub-pasien" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>

						</div>

						<div class="col-md-4">
							<div class="form-group" id="group_ktp_{0}">
								<label class="control-label col-md-4">'.translate("No. KTP", $this->session->userdata("language")).':</label>
								
								<div class="col-md-8 input-group">
									<input type="text" class="form-control send-data" id="penanggung_jawab_ktp_{0}" name="penanggung_jawab[{0}][ktp]">
								</div>
							</div>

						</div>
					</div>

					
					
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Scan KTP", $this->session->userdata("language")).' :</label>
						
						<div class="col-md-8 input-group">
							<input type="hidden" name="penanggung_jawab[{0}][url_ktp]" id="penanggung_jawab_url_ktp_{0}">
							<div id="upload_pj_{0}">
								<div id="drop_pj_{0}">	
									<input type="file" name="upl" id="penanggung_jawab_scan_ktp_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
								</div>

							<ul class="ul-img">
								<!-- The file uploads will be shown here -->
							</ul>

							</div>
						</div>
					</div>

					<div id="section-pj-alamat">
			                <div class="col-md-12">
				                <div class="portlet">
					                <div class="portlet-title">
					                    <div class="caption">
											'.translate('Alamat', $this->session->userdata('language')).'
										</div>
					                    <div class="actions">
					                        <a  class="btn btn-circle btn-icon-only btn-default add-pj-alamat">
						                        <i class="fa fa-plus"></i>
					                    	</a>
					                	</div>
					                </div>
				                	<div class="portlet-body">
										<div class="form-body">
											<ul class="list-unstyled pj-alamat">
											</ul>
										</div>
				                	</div>
				                </div>
			                </div>
			            </div>
		        	</div>

		        	<div id="section-pj-phone">
			            <div class="form-group">
			                <div class="col-md-6">
				                <div class="portlet">
					                <div class="portlet-title">
					                    <div class="caption">
											'.translate('Telepon', $this->session->userdata('language')).'
										</div>
					                    <div class="actions">
					                        <a  class="btn btn-circle btn-icon-only btn-default add-pj-phone">
						                        <i class="fa fa-plus"></i>
					                    	</a>
					                	</div>
					                </div>
				                	<div class="portlet-body">
										<div class="form-body">
											<ul class="list-unstyled pj-phone">
											</ul>
										</div>
				                	</div>
				                </div>
			                </div>
			            </div>
		        	</div>
		        	</div>'
					;

					$form_pj_alamat = '<div class="form-group">
					<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div class="input-group">
								'.form_dropdown('pj_alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_pj_alamat_{0}\" class=\"select2me form-control input-sx subjek_alamat\" required ").'
								<input type="text" id="input_subjek_pj_alamat_{0}" class="form-control hidden send-data">
							
								<span class="input-group-btn">
									<a class="btn blue-chambray" id="btn_edit_subjek_pj_alamat_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn red-intense del-this" id="btn_delete_subjek_pj_alamat_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
									<a class="btn btn-primary hidden" id="btn_save_subjek_pj_alamat_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow hidden" id="btn_cancel_subjek_pj_alamat_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<textarea id="pj_alamat_{0}" required name="pj_alamat[{0}][alamat]" class="form-control send-data" rows="3" placeholder="Alamat Lengkap"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div class="input-group">
								<input type="text" id="rt_{0}" name="pj_alamat[{0}][rt]" class="form-control send-data" placeholder="RT">
								<span class="input-group-addon">/</span>
								<input type="text" id="rw_{0}" name="pj_alamat[{0}][rw]" class="form-control send-data" placeholder="RW">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div class="input-group">
								'.form_dropdown('pj_alamat[{0}][negara]', $data_negara_option, '', "id=\"pj_negara_{0}\" class=\"form-control input-sx pj_negara send-data\"").'
								<input type="text" id="input_pj_negara_{0}" class="form-control hidden send-data">
								<span class="input-group-btn">
									<a class="btn blue-chambray" id="btn_edit_pj_negara_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-primary hidden" id="btn_save_pj_negara_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow hidden" id="btn_cancel_pj_negara_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div class="input-group">
								'.form_dropdown('pj_alamat[{0}][provinsi]', $region_option, '', "id=\"pj_provinsi_{0}\" class=\"form-control input-sx pj_provinsi send-data\"").'
								<input type="text" id="input_pj_provinsi_{0}" class="form-control hidden send-data">
								<span class="input-group-btn">
									<a class="btn blue-chambray" id="btn_edit_pj_provinsi_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-primary hidden" id="btn_save_pj_provinsi_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow hidden" id="btn_cancel_pj_provinsi_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kota", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div class="input-group">
								'.form_dropdown('pj_alamat[{0}][kota]', $region_option, '', "id=\"pj_kota_{0}\" class=\"form-control input-sx pj_kota send-data\"").'
								<input type="text" id="input_pj_kota_{0}" class="form-control hidden send-data">
								<span class="input-group-btn">
									<a class="btn blue-chambray" id="btn_edit_pj_kota_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-primary hidden" id="btn_save_pj_kota_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow hidden" id="btn_cancel_pj_kota_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div class="input-group">
								'.form_dropdown('pj_alamat[{0}][kecamatan]', $region_option, '', "id=\"pj_kecamatan_{0}\" class=\"form-control input-sx pj_kecamatan send-data\"").'
								<input type="text" id="input_pj_kecamatan_{0}" class="form-control hidden send-data">
								<span class="input-group-btn">
									<a class="btn blue-chambray" id="btn_edit_pj_kecamatan_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-primary hidden" id="btn_save_pj_kecamatan_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow hidden" id="btn_cancel_pj_kecamatan_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div class="input-group">
								'.form_dropdown('pj_alamat[{0}][kelurahan]', $region_option, '', "id=\"pj_kelurahan_{0}\" class=\"form-control input-sx pj_kelurahan send-data\"").'
								<input type="text" id="input_pj_kelurahan_{0}" class="form-control hidden send-data">
								<span class="input-group-btn">
									<a class="btn blue-chambray" id="btn_edit_pj_kelurahan_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-primary hidden" id="btn_save_pj_kelurahan_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow hidden" id="btn_cancel_pj_kelurahan_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<input type="text" name="pj_alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control send-data" placeholder="Kode Pos">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"></label>
						<div class="col-md-8">
							<input type="hidden" name="pj_alamat[{0}][is_primary_pj_alamat]" id="primary_pj_alamat_id_{0}">
							<input type="radio" name="pj_alamat_is_primary" id="radio_primary_pj_alamat_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
						</div>
					</div>';

					$form_pj_phone = '
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div class="input-group">
								'.form_dropdown('pj_phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_pj_telp_{0}\" class=\"form-control input-sx\" required ").'
								<input type="text" id="input_subjek_pj_telp_{0}" class="form-control hidden">
								<span class="input-group-btn">
									<a class="btn blue-chambray" id="btn_edit_subjek_pj_telp_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn red-intense del-this" id="btn_delete_subjek_pj_telp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
									<a class="btn btn-primary hidden" id="btn_save_subjek_pj_telp_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow hidden" id="btn_cancel_subjek_pj_telp_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<input class="form-control" required id="nomer_{0}" name="pj_phone[{0}][number]" placeholder="Nomor Telepon">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"></label>
						<div class="col-md-8">
							<input type="hidden" name="pj_phone[{0}][is_primary_pj_phone]" id="primary_pj_phone_id_{0}">
							<input type="radio" name="pj_phone_is_primary" id="radio_primary_pj_phone_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
						</div>
					</div>';
				?>

			<div class="form-body" id="ul_penanggung_jawab">
				<ul class="list-unstyled" id="ul_penanggung_jawab">
					<li class="fieldset" id="li_penanggung_jawab">
					<div class="portlet-body form">

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-12 bold">Nama :</label>
									
									<div class="col-md-12">
										<div class="input-group">
											
										
										<input type="text" class="form-control send-data" name="penanggung_jawab_edit[2][nama]" value="<?=$nama_pj?>" readonly="readonly" aria-invalid="false">
										<input type="hidden" class="form-control send-data" name="penanggung_jawab_edit[2][set_penanggung_jawab]" readonly="readonly" value="1">
										<span class="input-group-btn">
											
											<!-- <a class="btn red-intense del_penanggung_jawab" id="del_penanggung_jawab_2" data-row="2" data-confirm="Apa anda yakin ingin menghapus penanggung jawab ini">Hapus PJ</a> -->
											<a class="btn red-intense del_penanggung_jawab" id="del_penanggung_jawab" data-confirm="<?=translate('Apa anda yakin ingin menghapus penanggung jawab ini ?', $this->session->userdata('language'))?>"><?=translate('Hapus PJ', $this->session->userdata('language'))?></a>
											
										</span>
										<span class="input-group-btn hidden">
											<a class="btn red-intense del-hub-pasien" id="del-hub-pasien" title="Remove"><i class="fa fa-times"></i></a>
										</span>
										</div>
									</div>
								</div>

							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-12 bold">No. KTP :</label>
									
									<div class="col-md-12">

										<input type="text" class="form-control send-data" name="penanggung_jawab_edit[2][ktp]" value="<?=$ktp_pj?>" readonly="readonly">
									</div>
								</div>

							</div>
						</div>
						</div>			
					</li>
				</ul>
			</div>

		
			<input type="hidden" id="tpl-form-penanggung-jawab" value="<?=htmlentities($form_penanggung_jawab)?>">
			<input type="hidden" id="tpl-form-pj-alamat" value="<?=htmlentities($form_pj_alamat)?>">
			<input type="hidden" id="tpl-form-pj-phone" value="<?=htmlentities($form_pj_phone)?>">
			<input type="hidden" id="check_penanggung_jawab" value="0">
			<div class="form-body">
				<ul class="list-unstyled penanggung-jawab">
				
				</ul>
			</div>
			<div class="form-actions right">
				<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
					<i class="fa fa-chevron-left"></i>
					<?=translate("Kembali", $this->session->userdata("language"))?>
				</a>
				<a id="confirm_save_penanggung" class="btn btn-circle btn-primary" data-confirm="<?=$msg?>" data-toggle="modal">
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
