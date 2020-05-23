
		<?php
			$form_attr = array(
			    "id"            => "form_add_pasien", 
			    "name"          => "form_add_pasien", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."master/pasien/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');

			$msg = translate("Apakah anda yakin akan membuat data pasien ini?",$this->session->userdata("language"));
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
												<img src="<?=$img_src?>" alt="Foto tidak ditemukan" class="img-thumbnail img-responsive" style="padding:0px;border:0px;">
											</a>
										</div>
									</li>
								</ul>
								
								<div id="upload" style="text-align:center;">
									<span class="btn-file profile-userbuttons" style="margin:0;">
										<button type="button" class="btn btn-circle btn-primary" style="margin:0;">Pilih Foto</button>
										<!-- <span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>		 -->
										<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_photo" multiple />
									</span>
								</div>	
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
									<li class="active">
										<a href="#">
										<i class="icon-user"></i>
										Profil </a>
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
						<i class="icon-plus font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Registrasi Pasien", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="note note-success note-bordered">
					<p>
						 INFO : Menu untuk input penjamin, penanggung dll akan muncul setelah data profile pasien tersimpan.
					</p>
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
					    <div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Cabang", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<?php
											$cabang = $this->cabang_m->get_by(array('is_active' => '1', 'tipe' => '1'));
											// die(dump($this->db->last_query()));
											$cabang_option = array(
											    '' => translate('Pilih..', $this->session->userdata('language'))
											);

											foreach ($cabang as $data)
											{
											    $cabang_option[$data->id] = $data->nama;
												if($data->parent_id != 0){

													$cabang_parent = $this->cabang_m->get_by(array('id' => $data->parent_id), true);

											    	$cabang_option[$data->id] = $data->nama.' ( cabang dari '.$cabang_parent->nama.' )';

												}
											}
											echo form_dropdown('cabang_id', $cabang_option, $this->session->userdata('cabang_id'), "id=\"cabang_id\" class=\"form-control\" required ");
										?>
									</div>
								</div>
							</div>
							<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<?php
											$fullname = array(
												"id"			=> "nama_lengkap",
												"name"			=> "nama_lengkap",
												"autofocus"			=> true,
												"class"			=> "form-control", 
												"placeholder"	=> translate("Nama Lengkap", $this->session->userdata("language")), 
												"value"			=> $flash_form_data['nama_lengkap'],
												"help"			=> $flash_form_data['nama_lengkap'],
												"required"		=> "required", 
											);
											echo form_input($fullname);
										?>
									</div>
								</div>
					    	</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Tempat & Tanggal Lahir", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-6">
										<?php
											$tempat_lahir = array(
												"id"			=> "tempat_lahir",
												"name"			=> "tempat_lahir",
												"class"			=> "form-control", 
												"placeholder"	=> translate("Tempat Lahir", $this->session->userdata("language")), 
												"value"			=> $flash_form_data['tempat_lahir'],
												"help"			=> $flash_form_data['tempat_lahir'],
												"required"		=> "required", 
											);
											echo form_input($tempat_lahir);
										?>
									</div>
									
									<div class="col-md-6">
										<div class="input-group date" id="tanggal_lahir">
											<input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required readonly >
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>

							</div>
							<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Jenis Kelamin", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<div class="radio-list">
				                            <label class="radio-inline">
				                                <div class="radio-inline" style="padding-left:0px !important;">
				                                    <span class="">
				                                        <input type="radio" name="jenis_kelamin"  checked value="L" id="jk-laki" required>
				                                    </span>
				                                </div> 

				                                <span>Laki-Laki</span> 
				                            </label>
				                            <label class="radio-inline">
				                                <div class="radio-inline"  >
				                                    <span class="">
				                                        <input type="radio" name="jenis_kelamin"  value="P" id="jk-perempuan" required>
				                                    </span>
				                                </div> 
				                                <span>Perempuan</span> 
				                            </label>
				                        </div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Tanggal Daftar", $this->session->userdata("language"))?> :<span class="required">*</span></label>

									<div class="col-md-12">
										<div class="input-group date" id="tanggal_daftar">
											<input type="text" class="form-control" id="tanggal_daftar" name="tanggal_daftar" required value="<?=date('d-M-Y')?>" readonly >
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Agama", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<?php
											$agama = $this->info_umum_m->get_by(array('tipe' => 1));
											$agama_array = object_to_array($agama);
											
											$agama_option = array(
												'' => translate("Pilih", $this->session->userdata('user_id')),
												'-'	=> translate("Tidak Diketahui", $this->session->userdata('user_id'))

											);
										    foreach ($agama_array as $select) {
										        $agama_option[$select['id']] = $select['nama'];
										    }

											echo form_dropdown('agama', $agama_option, '', "id=\"agama\" class=\"form-control\"");
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Golongan Darah", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<?php
											$golongan_darah = $this->info_umum_m->get_by(array('tipe' => 2));
											$golongan_darah_array = object_to_array($golongan_darah);
											
											$golongan_darah_option = array(
												'' => translate("Pilih", $this->session->userdata('user_id')),
												'-'	=> translate("Tidak Diketahui", $this->session->userdata('user_id'))

											);
										    foreach ($golongan_darah_array as $select) {
										        $golongan_darah_option[$select['id']] = $select['nama'];
										    }

											echo form_dropdown('golongan_darah', $golongan_darah_option, '', "id=\"golongan_darah\" class=\"form-control\"");
										?>
									</div>
									
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Cara Masuk", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<?php
											$cara_masuk = $this->info_umum_m->get_by(array('tipe' => 5));
											$cara_masuk_array = object_to_array($cara_masuk);
											
											// die_dump($this->db->last_query());
											$cara_masuk_option = array(
												'' => "Pilih..",

											);
										    foreach ($cara_masuk_array as $select) {
										        $cara_masuk_option[$select['id']] = $select['nama'];
										    }

											echo form_dropdown('cara_masuk', $cara_masuk_option, '', "id=\"cara_masuk\" class=\"form-control\"");
										?>
									</div>
									
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Pendidikan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<?php
											$pendidikan = $this->info_umum_m->get_by(array('tipe' => 3));
											$pendidikan_array = object_to_array($pendidikan);
											
											$pendidikan_option = array(
												'' => "Pilih..",

											);
										    foreach ($pendidikan_array as $select) {
										        $pendidikan_option[$select['id']] = $select['nama'];
										    }

											echo form_dropdown('pendidikan', $pendidikan_option, '', "id=\"pendidikan\" class=\"form-control\"");
										?>
									</div>
									
								</div>

					    	</div>
					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Pekerjaan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<?php
											$pekerjaan = $this->info_umum_m->get_by(array('tipe' => 4));
											$pekerjaan_array = object_to_array($pekerjaan);
											
											$pekerjaan_option = array(
												'' => "Pilih..",

											);
										    foreach ($pekerjaan_array as $select) {
										        $pekerjaan_option[$select['id']] = $select['nama'];
										    }

											echo form_dropdown('pekerjaan', $pekerjaan_option, '', "id=\"pekerjaan\" class=\"form-control\"");
										?>
									</div>
								</div>
					    	</div>
					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-6 bold"><?=translate('KTP', $this->session->userdata('language'))?> :</label>
									<label class="col-md-6 bold"><?=translate('Tgl. Kadaluarsa', $this->session->userdata('language'))?> :</label>
									<div class="col-md-6">
										<input type="text" name="no_ktp" id="no_ktp" class="form-control" placeholder="No. KTP" required>
									</div>
									<div class="col-md-6">
										<div class="input-group date" id="tanggal_kadaluarsa">
											<input type="text" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" required value="<?=date('d-M-Y')?>" readonly >
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>
					    	</div>

					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate('Kartu BPJS', $this->session->userdata('language'))?> :</label>
									<div class="col-md-6">
										<input type="text" name="no_bpjs" id="no_bpjs" class="form-control" placeholder="No. Kartu BPJS">
									</div>
									<div class="col-md-6">
										<input type="hidden" name="url_bpjs" id="url_bpjs" >
										<div id="upload">
											<span class="btn default btn-file">
												<span class="fileinput-new"><?=translate('Upload Kartu BPJS', $this->session->userdata('language'))?></span>		
												<input type="file" name="upl" id="upl_bpjs" data-url="<?=base_url()?>upload/upload_photo" multiple />
											</span>

										<ul class="ul-img img-bpjs">
											<!-- The file uploads will be shown here -->
										</ul>

										</div>
									</div>
								</div>
					    	</div>

					    	<div class="col-md-6">
					    		<div class="form-group">
					    			<div class="col-md-12">
										<input type="hidden" name="url_ktp" id="url_ktp"  required>
										<div id="upload">
											<span class="btn default btn-file">
												<span class="fileinput-new"><?=translate('Upload KTP', $this->session->userdata('language'))?></span>		
												<input type="file" name="upl" id="upl_ktp" data-url="<?=base_url()?>upload/upload_photo" multiple />
											</span>

										<ul class="ul-img img-ktp">
											<!-- The file uploads will be shown here -->
										</ul>

										</div>
									</div>
					    		</div>
					    	</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="portlet light" id="section-alamat">
									<div class="portlet-title">
										<div class="caption">
											<?=translate('Alamat', $this->session->userdata('language'))?>
										</div>
										<div class="actions">
											<a class="btn btn-circle btn-icon-only btn-default add-alamat">
								                <i class="fa fa-plus"></i>
								            </a>										
										</div>
									</div>
								<div class="portlet-body">

									<div class="form-group">
										<label class="control-label col-md-4 hidden"><?=translate("Counter", $this->session->userdata("language"))?> :</label>
										<div class="col-md-8">
											<input type="hidden" id="counter" value="1">	
										</div>
									</div>
										<?php
											// $telp_sub = $this->cabang_m->get_data_sub_telp();
											// $telp_sub_option = $telp_sub->result_array();
											$alamat_sub = $this->pasien_m->get_data_subjek(1);
											$alamat_sub_array = $alamat_sub->result_array();
											
											$alamat_sub_option = array(
												'' => "Pilih..",

											);
										    foreach ($alamat_sub_array as $select) {
										        $alamat_sub_option[$select['id']] = $select['nama'];
										    }


											$data_negara = $this->region_m->get_by(array('parent' => null));
											$data_negara_array = object_to_array($data_negara);
											
											$data_negara_option = array(
												'' => "Pilih..",
											);
										    foreach ($data_negara_array as $select) {
										        $data_negara_option[$select['id']] = $select['nama'];
										    }

											$region_option = array(
												'' => "Pilih..",
											);
										    // foreach ($telp_sub_option as $select) {
										    //     $sub_option[$select['id']] = $select['nama'];
										    // }
											//die_dump($alamat_sub_option);
											$form_alamat = '
												
											<div class="form-group">
											<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :<span class="required">*</span></label>
												<div class="col-md-8">
													<div class="input-group">
														'.form_dropdown('alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_alamat_{0}\" class=\"select2me form-control subjek_alamat\" required ").'
														<input type="text" id="input_subjek_alamat_{0}" class="form-control hidden">
													
														<span class="input-group-btn">
															<a class="btn blue-chambray" id="btn_edit_subjek_alamat_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
															<a class="btn red-intense del-this" id="btn_delete_subjek_alamat_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
															<a class="btn btn-primary hidden" id="btn_save_subjek_alamat_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
															<a class="btn yellow hidden" id="btn_cancel_subjek_alamat_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Alamat Lengkap", $this->session->userdata("language")).' :<span class="required">*</span></label>
												<div class="col-md-8">
													<textarea id="alamat_{0}" name="alamat[{0}][alamat]" class="form-control" rows="3" placeholder="'.translate("Alamat Lengkap", $this->session->userdata("language")).'" required></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<div class="input-group">
														<input type="text" id="rt_{0}" name="alamat[{0}][rt]" class="form-control" placeholder="RT">
														<span class="input-group-addon">/</span>
														<input type="text" id="rw_{0}" name="alamat[{0}][rw]" class="form-control" placeholder="RW">
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<div class="input-group">
														<input type="text" id="input_kelurahan_{0}" name="alamat[{0}][kelurahan]" class="form-control" readonly>
														<input type="hidden" id="input_kode_{0}" name="alamat[{0}][kode]" class="form-control">
														<span class="input-group-btn">
															<a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_{0}" title="'.translate('Cari', $this->session->userdata('language')).'" href="<?=base_url()?>master/pasien/modal/search_kelurahan"><i class="fa fa-search"></i></a>
														</span>
													</div>
												</div>
											</div>
											<div id="div_lokasi" class="hidden">
												<div class="form-group">
													<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
													<div class="col-md-8">
														<input type="text" id="input_kecamatan_{0}" name="alamat[{0}][kecamatan]" class="form-control" readonly>					
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :</label>
													<div class="col-md-8">
														
															<input type="text" id="input_kota_{0}" name="alamat[{0}][kota]" class="form-control" readonly>					
														
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
													<div class="col-md-8">
														
															<input type="text" id="input_provinsi_{0}" name="alamat[{0}][provinsi]" class="form-control" readonly>					
														
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
													<div class="col-md-8">
														
															<input type="text" id="input_negara_{0}" name="alamat[{0}][negara]" class="form-control" readonly>					
														
													</div>
												</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
													<div class="col-md-8">
														<input type="text" name="alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control"  placeholder="Kode Pos">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4"></label>
													<div class="col-md-8">
														<input type="hidden" name="alamat[{0}][is_primary_alamat]" id="primary_alamat_id_{0}">
														<input type="radio" name="alamat_is_primary" id="radio_primary_alamat_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
													</div>
												</div>
											';
										?>

										<input type="hidden" id="tpl-form-alamat" value="<?=htmlentities($form_alamat)?>">
										<div class="form-body">
											<ul class="list-unstyled">
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="portlet light" id="section-telepon">
	<div class="portlet-title">
		<div class="caption">
			<!-- <span class="caption-subject font-blue-sharp bold uppercase"></span> -->
			<?=translate('Telepon', $this->session->userdata('language'))?>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-icon-only btn-default add-phone">
                <i class="fa fa-plus"></i>
            </a>										
		</div>
	</div>
	<div class="portlet-body">
		<?php
			$telp_sub = $this->pasien_m->get_data_subjek(2);
			$telp_sub_array = $telp_sub->result_array();
			
			$telp_sub_option = array(
				'' => "Pilih..",

			);
		    foreach ($telp_sub_array as $select) {
		        $telp_sub_option[$select['id']] = $select['nama'];
		    }
			//die_dump($alamat_sub_option);
			$form_phone = '
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<div class="input-group">
						'.form_dropdown('phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_telp_{0}\" class=\"form-control\" required ").'
						<input type="text" id="input_subjek_telp_{0}" class="form-control hidden">
						<span class="input-group-btn">
							<a class="btn blue-chambray" id="btn_edit_subjek_telp_{0}"  title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
							<a class="btn red-intense del-this" id="btn_delete_subjek_telp_{0}"  title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
							<a class="btn btn-primary hidden" id="btn_save_subjek_telp_{0}"  title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
							<a class="btn yellow hidden" id="btn_cancel_subjek_telp_{0}"  title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<input class="form-control" id="nomer_{0}" name="phone[{0}][number]" placeholder="Nomor Telepon" required>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4"></label>
				<div class="col-md-8">
					<input type="hidden" name="phone[{0}][is_primary_phone]" id="primary_phone_id_{0}">
					<input type="radio" name="phone_is_primary" id="radio_primary_phone_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
				</div>
			</div>';
		?>

		<input type="hidden" id="tpl-form-phone" value="<?=htmlentities($form_phone)?>">
		<div class="form-body">
			<ul class="list-unstyled">
			</ul>
		</div>
	</div>
</div>
							</div>
							
						</div>
<div class="form-actions right">
						<a class="btn default" href="javascript:history.go(-1)">
							<i class="fa fa-chevron-left"></i>
							<?=translate("Kembali", $this->session->userdata("language"))?>
						</a>
						<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
							<i class="glyphicon glyphicon-floppy-disk"></i>
							<?=translate("Simpan & Lanjutkan", $this->session->userdata("language"))?>
						</a>
		        		<button type="submit" id="save" class="btn default hidden" >
		        			<?=translate("Simpan", $this->session->userdata("language"))?>
		        		</button>
					</div>	
					</div>
				</div>
					
					
				</div>

			</div>
		</div>



		<?=form_close()?>


<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row" class="heading">
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

<div class="modal fade" id="modal_alamat" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="modal_faskes" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="modal_faskes_1" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>




