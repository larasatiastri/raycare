
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

		    echo form_open(base_url()."master/pasien/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');

			$msg = translate("Apakah anda yakin akan mengubah profile pasien ini?",$this->session->userdata("language"));

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
						<i class="icon-user font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Data Pasien", $this->session->userdata("language"))?></span>
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

					    <div class="row">
					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate("No. Rekam Medis", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<label class="control-label"><?=$form_data['no_member']?></label>
										<input type="hidden" name="no_member" id="no_member" value="<?=$form_data['no_member']?>">
									</div>
								</div>
					    	</div>
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
											echo form_dropdown('cabang_id', $cabang_option, $form_data['cabang_id'], "id=\"cabang_id\" class=\"form-control\" required ");
										?>
									</div>
								</div>
					    	</div>
					    </div>
					    <div class="row">
					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<?php
											$fullname = array(
												"id"			=> "nama_lengkap",
												"name"			=> "nama_lengkap",
												"class"			=> "form-control", 
												"placeholder"	=> translate("Nama Lengkap", $this->session->userdata("language")), 
												"value"			=> $form_data['nama'],
												
											);
											echo form_input($fullname);
										?>
									</div>
								</div>
					    	</div>
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
												"value"			=> $form_data['tempat_lahir'],
												
											);
											echo form_input($tempat_lahir);
										?>
									</div>
									
									<div class="col-md-6">

										<div class="input-group date" id="tanggal_lahir">
											<input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?=date('d-M-Y', strtotime($form_data['tanggal_lahir']))?>" readonly >
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>
					    	</div>
					    </div>
					    <div class="row">
					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Jenis Kelamin", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-12">
										<?php
											$checked_laki = "";
											$checked_perempuan = "";
											if ($form_data['gender'] == 'L') 
											{
												$checked_laki = "checked";
											}elseif($form_data['gender'] == 'P'){
												
												$checked_perempuan = "checked";
											}
		                            	?>

				                        <div class="radio-list">
				                            <label class="radio-inline">
				                                <div class="radio-inline" style="padding-left:0px !important;">
				                                    <span class="">
				                                        <input type="radio" name="jenis_kelamin"  <?=$checked_laki?> value="L" id="jk-laki">
				                                    </span>
				                                </div> 

				                                <span>Laki-Laki</span> 
				                            </label>
				                            <label class="radio-inline">
				                                <div class="radio-inline"  >
				                                    <span class="">
				                                        <input type="radio" name="jenis_kelamin" <?=$checked_perempuan?> value="P" id="jk-perempuan">
				                                    </span>
				                                </div> 
				                                <span>Perempuan</span> 
				                            </label>
				                        </div>
				                    </div>
									
								</div>
					    	</div>
					    	<div class="col-md-2">
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

											echo form_dropdown('golongan_darah', $golongan_darah_option, $form_data['golongan_darah_id'], "id=\"golongan_darah\" class=\"form-control\"");
										?>
									</div>
									
								</div>
					    	</div>
					    	<div class="col-md-4">
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

											echo form_dropdown('agama', $agama_option, $form_data['agama_id'], "id=\"agama\" class=\"form-control\"");
										?>
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
											<input type="text" class="form-control" id="tanggal_daftar" name="tanggal_daftar" required value="<?=date('d-M-Y', strtotime($form_data['tanggal_daftar']))?>" readonly >
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>
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

											echo form_dropdown('cara_masuk', $cara_masuk_option, $form_data['cara_masuk_id'], "id=\"cara_masuk\" class=\"form-control\"");
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

											echo form_dropdown('pendidikan', $pendidikan_option, $form_data['pendidikan_id'], "id=\"pendidikan\" class=\"form-control\"");
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

											echo form_dropdown('pekerjaan', $pekerjaan_option, $form_data['pekerjaan_id'], "id=\"pekerjaan\" class=\"form-control\"");
										?>
									</div>
								</div>
					    	</div>
					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate('KTP', $this->session->userdata('language'))?> :</label>
									<div class="col-md-12">
										<input type="text" name="no_ktp" id="no_ktp" class="form-control" placeholder="No. KTP" required value="<?=$form_data['no_ktp']?>">
									</div>
									
								</div>
					    	</div>

					    	<div class="col-md-6">
					    		<div class="form-group">
									<label class="col-md-12 bold"><?=translate('Kartu BPJS', $this->session->userdata('language'))?> :</label>
									<div class="col-md-12">
										<input type="text" name="no_bpjs" id="no_bpjs" class="form-control" placeholder="No. Kartu BPJS" value="<?=$form_data['no_bpjs']?>">
									</div>
									
								</div>
					    	</div>

					    </div>
						<div class="row">
							<div class="col-md-6">
								
								<div class="portlet light" id="section-alamat">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Alamat', $this->session->userdata('language'))?></span>
		</div>
	</div>
	<div class="portlet-body form">

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
		    
		    $get_pasien_alamat = $this->pasien_alamat_m->get_data($form_data['id']);
			$records = $get_pasien_alamat->result_array();
			if(count($records))
			{

				$i=0;
				foreach ($records as $key => $data) {
					$primary = "";
					if ($data['is_primary'] == "1") {
						$primary = "checked";
					}
					$rt_rw = explode('_', $data['rt_rw']);
					$rt = $rt_rw[0];
					$rw = $rt_rw[1];

					$data_provinsi = $this->region_m->get_by(array('parent' => $data['negara_id']));
					$data_provinsi_array = object_to_array($data_provinsi);

					$data_provinsi_option = array(
					'' => "Pilih..",
					);
				    foreach ($data_provinsi_array as $select) {
				        $data_provinsi_option[$select['id']] = $select['nama'];
				    }

				    $data_kota = $this->region_m->get_by(array('parent' => $data['propinsi_id']));
					$data_kota_array = object_to_array($data_kota);

					$data_kota_option = array(
					'' => "Pilih..",
					);
				    foreach ($data_kota_array as $select) {
				        $data_kota_option[$select['id']] = $select['nama'];
				    }

				    $data_kecamatan = $this->region_m->get_by(array('parent' => $data['kota_id']));
					$data_kecamatan_array = object_to_array($data_kecamatan);

					$data_kecamatan_option = array(
					'' => "Pilih..",
					);
				    foreach ($data_kecamatan_array as $select) {
				        $data_kecamatan_option[$select['id']] = $select['nama'];
				    }

				    $data_kelurahan = $this->region_m->get_by(array('parent' => $data['kecamatan_id']));
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
				    if($data['kode_lokasi'] != NULL || $data['kode_lokasi'] != '')
				    {
				    	
				    	$hidden = '';

				    	$data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $data['kode_lokasi']), true);
				    	$data_lokasi = object_to_array($data_lokasi);
				    	$nama_kelurahan = $data_lokasi['nama_kelurahan'];
					    $nama_kecamatan = $data_lokasi['nama_kecamatan'];
					    $nama_kotkab = $data_lokasi['nama_kabupatenkota'];
					    $nama_propinsi = $data_lokasi['nama_propinsi'];

				    }

					$form_alamat_edit[] = '
					<div id="alamat_'.$i.'" style="border:1px solid #fff; padding:12px;background-color:#eee;border-radius:10px;">
						<div class="form-group">
							<label class="control-label col-md-4 hidden">'.translate("Id Alamat", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
								<input class="form-control hidden" id="id_'.$i.'" name="alamat['.$i.'][id]" placeholder="Id Alamat" value="'.$data['id'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12 bold">'.translate("Subjek", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<div class="input-group">
										'.form_dropdown('alamat['.$i.'][subjek]', $alamat_sub_option, $data['subjek_id'], "id=\"subjek_alamat_$i\" class=\"select2me form-control input-sx subjek_alamat\"").'
										<input type="text" id="input_subjek_alamat_'.$i.'" class="form-control hidden">
										<span class="input-group-btn">
											<a class="btn blue-chambray edit-subjek" data-id="'.$i.'" id="btn_edit_subjek_alamat_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
											<a class="btn red-intense del-db" data-id="'.$i.'" id="btn_delete_subjek_alamat_'.$i.'" data-confirm="'.translate('Apakah anda yakin ingin menghapus alamat ini ?', $this->session->userdata('language')).'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
											<a class="btn btn-primary hidden save-subjek" data-id="'.$i.'" id="btn_save_subjek_alamat_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
											<a class="btn yellow hidden cancel-subjek" data-id="'.$i.'" id="btn_cancel_subjek_alamat_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Alamat", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<textarea id="alamat_'.$i.'" name="alamat['.$i.'][alamat]" class="form-control" rows="3" placeholder="Alamat Lengkap">'.$data['alamat'].'</textarea>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-12 bold">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											<div class="input-group">
												<input type="text" id="rt_'.$i.'" name="alamat['.$i.'][rt]" class="form-control" value="'.$rt.'">
												<span class="input-group-addon">/</span>
												<input type="text" id="rt_'.$i.'" name="alamat['.$i.'][rw]" class="form-control" value="'.$rw.'">
											</div>

										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-12 bold">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											<div class="input-group">
												<input type="text" id="input_kelurahan_'.$i.'" name="alamat['.$i.'][kelurahan]" value="'.$nama_kelurahan.'" class="form-control"  readonly>
												<input type="hidden" id="input_kode_'.$i.'" name="alamat['.$i.'][kode]" value="'.$data['kode_lokasi'].'" class="form-control">
												<span class="input-group-btn">
													<a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_'.$i.'" title="'.translate('Cari', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/search_kelurahan/pasien/'.$i.'"><i class="fa fa-search"></i></a>
												</span>
											</div>
										</div>
									</div>

								</div>
							</div>
							
							
								
			<div id="div_lokasi" class="'.$hidden.'">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-12 bold">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<input type="text" id="input_kecamatan_'.$i.'" name="alamat['.$i.'][kecamatan]" value="'.$nama_kecamatan.'" class="form-control" readonly>					
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-12 bold">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<input type="text" id="input_kota_'.$i.'" name="alamat['.$i.'][kota]" value="'.$nama_kotkab.'" class="form-control" readonly>					
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-12 bold">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<input type="text" id="input_provinsi_'.$i.'" name="alamat['.$i.'][provinsi]" value="'.$nama_propinsi.'" class="form-control" readonly>					
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-12 bold">'.translate("Negara", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<input type="text" id="input_negara_'.$i.'" name="alamat['.$i.'][negara]" value="Indonesia" class="form-control" readonly>					
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
									<input type="text" name="alamat['.$i.'][kode_pos]" id="kode_pos_'.$i.'" class="form-control" placeholder="Kode Pos" value="'.$data['kode_pos'].'">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-12"></label>
								<div class="col-md-12 primary_alamat">
									<input type="hidden" value="'.$data['is_primary'].'" name="alamat['.$i.'][is_primary_alamat]" id="primary_alamat_id_'.$i.'">
									<input type="radio" data-id="'.$i.'" name="alamat_is_primary" '.$primary.' id="radio_primary_alamat_id_'.$i.'"> '.translate('Utama', $this->session->userdata('language')).'
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									<input class="form-control hidden" id="is_delete_alamat_'.$i.'" name="alamat['.$i.'][is_delete]" placeholder="Is Delete">
								</div>
							</div>

					</div>
				</div>

			
	
			
							
					
					</div>'
					;
					$i++;
				}
			}
			else
			{
				$i = 0;
				$form_alamat_edit = array();
			}


			$form_alamat = '
			<div style="border:1px solid #ddd; padding:12px;background-color:#eee;border-radius:10px;">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label col-md-4 hidden">'.translate("Id Alamat", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
								<input class="form-control hidden" id="id_{0}" name="alamat[{0}][id]" placeholder="Id Alamat">
							</div>
						</div>
						<div class="form-group">
						<label class="col-md-12 bold">'.translate("Subjek", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<div class="input-group">
									'.form_dropdown('alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_alamat_{0}\" class=\"form-control input-sx subjek_alamat\"").'
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
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label class="col-md-12 bold">'.translate("Alamat", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<textarea id="alamat_{0}" name="alamat[{0}][alamat]" class="form-control" rows="3" placeholder="Alamat Lengkap"></textarea>
							</div>
						</div>

					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-12 bold">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<div class="input-group">
									<input type="text" id="rt_{0}" name="alamat[{0}][rt]" class="form-control" placeholder="RT">
									<span class="input-group-addon">/</span>
									<input type="text" id="rt_{0}" name="alamat[{0}][rw]" class="form-control" placeholder="RW">
								</div>
		 
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-12 bold">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<div class="input-group">
									<input type="text" id="input_kelurahan_{0}" name="alamat[{0}][kelurahan]" class="form-control" placeholder="Kelurahan / Desa" readonly>
									<input type="hidden" id="input_kode_{0}" name="alamat[{0}][kode]" class="form-control" placeholder="Kelurahan / Desa">
									<span class="input-group-btn">
										<a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_{0}" title="'.translate('Cari', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/search_kelurahan/pasien/{0}"><i class="fa fa-search"></i></a>
									</span>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="row">
					<div id="div_lokasi" class="hidden">
						
						
						
					

						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<input type="text" id="input_kecamatan_{0}" name="alamat[{0}][kecamatan]" class="form-control" readonly>					
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
										<input type="text" id="input_kota_{0}" name="alamat[{0}][kota]" class="form-control" readonly>
								</div>
							</div>

						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									
										<input type="text" id="input_provinsi_{0}" name="alamat[{0}][provinsi]" class="form-control" readonly>					
									
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold">'.translate("Negara", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									
										<input type="text" id="input_negara_{0}" name="alamat[{0}][negara]" class="form-control" readonly>					
									
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
								<input type="text" name="alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control" placeholder="Kode Pos">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"></label>
							<div class="col-md-12 primary_alamat">
								<input type="hidden" name="alamat[{0}][is_primary_alamat]" id="primary_alamat_id_{0}">
								<input type="radio" name="alamat_is_primary" id="radio_primary_alamat_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'

							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<input class="form-control hidden" id="is_delete_{0}" name="alamat[{0}][is_delete]" placeholder="Is Delete">
							</div>
						</div>

					</div>

				</div>

				
			</div>
				
				
				
								';
		?>
		
		<div class="form-group">
			<label class="control-label col-md-4 hidden"><?=translate("Alamat Counter", $this->session->userdata("language"))?> :</label>
			<div class="col-md-8">
				<input type="hidden" id="alamat_counter" value="<?=$i?>" >
			</div>
		</div>

		<?php foreach ($form_alamat_edit as $row):?>
            <?=$row?>
            
        <?php endforeach;?>

		<input type="hidden" id="tpl-form-alamat" value="<?=htmlentities($form_alamat)?>">
		<div class="form-body" style="padding:0px !important;">
			<ul class="list-unstyled">
			</ul>
		</div>

		<div class="form-actions" style="padding:10px 0px !important;">
			<a class="btn btn-primary add-alamat">
                <i class="fa fa-plus"></i>
                <?=translate("Tambah Alamat", $this->session->userdata("language"))?>
            </a>										
		</div>
	</div>
</div>


								

								

								

								

								
								
								
								
								
								

							
							</div>
							<div class="col-md-6">
								<div class="portlet light" id="section-telepon">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Telepon', $this->session->userdata('language'))?></span>
				</div>

			</div>
			<div class="portlet-body form">
				<?php
					$telp_sub = $this->pasien_m->get_data_subjek(2);
					$telp_sub_array = $telp_sub->result_array();
					
					$telp_sub_option = array(
						'' => "Pilih..",

					);
				    foreach ($telp_sub_array as $select) {
				        $telp_sub_option[$select['id']] = $select['nama'];
				    }

				    $get_pasien_telepon = $this->pasien_telepon_m->get_data($form_data['id']);
					$records = $get_pasien_telepon->result_array();

					if(count($records))
					{


						$i=0;
						foreach ($records as $key => $data) {
							$primary = "";
							if ($data['is_primary'] == "1") {
								$primary = "checked";
							}

							$form_phone_edit[] = '
							<div id="phone_'.$i.'" style="border:1px solid #eee; padding:12px;background-color:#eee;border-radius:10px;">
							<div class="form-group">
								<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									<input class="form-control hidden" id="id'.$i.'" name="phone['.$i.'][id]" placeholder="Id Telepon" value="'.$data['id'].'">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-7">
									<input class="form-control" id="nomer_'.$i.'" name="phone['.$i.'][number]" placeholder="Nomor Telepon" value="'.$data['nomor'].'">
								</div>
								<div class="col-md-5">
									<div class="input-group">
									'.form_dropdown('phone['.$i.'][subjek]', $telp_sub_option, $data['subjek_id'], "id=\"subjek_telp_$i\" class=\"form-control\"").'
									<input type="text" id="input_subjek_telp_'.$i.'" class="form-control hidden">
									<span class="input-group-btn">
										<a class="btn blue-chambray edit" data-id="'.$i.'" id="btn_edit_subjek_telp_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-3px;"></i></a>
										<a class="btn red-intense del-db" data-id="'.$i.'" id="btn_delete_subjek_telp_'.$i.'" data-confirm="'.translate('Apakah anda yakin ingin menghapus telepon ini ?', $this->session->userdata('language')).'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" style="margin-left:-3px;"></i></a>
										<a class="btn btn-primary hidden save" data-id="'.$i.'" id="btn_save_subjek_telp_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-3px;"></i></a>
										<a class="btn yellow hidden cancel" data-id="'.$i.'" id="btn_cancel_subjek_telp_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-3px;"></i></a>
									</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<input type="hidden" value="'.$data['is_primary'].'" data-id="'.$i.'" name="phone['.$i.'][is_primary_phone]" id="primary_phone_id_'.$i.'">
									<input type="radio" name="phone_is_primary" data-id="'.$i.'" id="radio_primary_phone_id_'.$i.'" '.$primary.'> '.translate("Utama", $this->session->userdata("language")).'
			                    </div>
								
							</div>
							<div class="form-group">
								<label class="col-md-12 hidden bold">'.translate("Deleted", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<input class="form-control hidden" id="is_delete_'.$i.'" name="phone['.$i.'][is_delete]" placeholder="Is Delete">
								</div>
							</div>
						
							</div>'
							;
							$i++;
						}
					}
					else
					{
						$i = 0;
						$form_phone_edit = array();
					}
					
					$form_phone = '
					<div  style="border:1px solid #eee; padding:12px;background-color:#eee;border-radius:10px;">
						<div class="form-group">
								<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									<input class="form-control hidden" id="id{0}" name="phone[{0}][id]" placeholder="Id Telepon">
								</div>
							</div>
						<div class="form-group">
						<div class="col-md-7">
								<input class="form-control" id="nomer_{0}" name="phone[{0}][number]" placeholder="Nomor Telepon">
							</div>

							<div class="col-md-5">
								<div class="input-group">
									'.form_dropdown('phone[{0}][subjek]', $telp_sub_option, '', " id=\"subjek_telp_{0}\" class=\"form-control\" ").'
									<input type="text" id="input_subjek_telp_{0}" class="form-control hidden">
									<span class="input-group-btn">
										<a class="btn blue-chambray" id="btn_edit_subjek_telp_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" ></i></a>
										<a class="btn red-intense del-this" id="btn_delete_subjek_telp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" ></i></a>
										<a class="btn btn-primary hidden" id="btn_save_subjek_telp_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" ></i></a>
										<a class="btn yellow hidden" id="btn_cancel_subjek_telp_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" ></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"></label>
							<div class="col-md-12">
								<input type="hidden" name="phone[{0}][is_primary_phone]" id="primary_phone_id_{0}">
								<input type="radio" name="phone_is_primary" id="radio_primary_phone_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'

							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
							<div class="col-md-12">
								<input class="form-control hidden" id="is_delete_{0}" name="phone[{0}][is_delete]" placeholder="Is Delete">
							</div>
						</div>

					</div>
					
					';
					

					//die_dump($pasien_telepon_option);
					
				?>
				<div class="form-group">
					<label class="control-label col-md-4 hidden"><?=translate("Phone Counter", $this->session->userdata("language"))?> :</label>
					<div class="col-md-8">
						<input type="hidden" id="phone_counter" value="<?=$i?>" >
					</div>
				</div>
				
				<?php foreach ($form_phone_edit as $row):?>
		            <?=$row?>
		            
		        <?php endforeach;?>

				
				<input type="hidden" id="tpl-form-phone" value="<?=htmlentities($form_phone)?>">
				<div class="form-body" style="padding:0px !important;">
					<ul class="list-unstyled">
					</ul>
				</div>

				<div class="form-actions" style="padding:10px 0px !important;">
					<a class="btn btn-primary add-phone">
		                <i class="fa fa-plus"></i>
		                <?=translate("Tambah Telepon", $this->session->userdata("language"))?>
		            </a>										
				</div>
			</div>
		</div>
							</div>
						</div>

						
				</div>
					<div class="form-actions right">
						<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
							<i class="fa fa-chevron-left"></i>
							<?=translate("Kembali", $this->session->userdata("language"))?>
						</a>
						<a id="confirm_save" class="btn btn-circle btn-primary" data-confirm="<?=$msg?>" data-toggle="modal">
							<i class="glyphicon glyphicon-floppy-disk"></i>
							<?=translate("Simpan", $this->session->userdata("language"))?>
						</a>
		        		<button type="submit" id="save" class="btn default hidden" >
		        			<?=translate("Simpan", $this->session->userdata("language"))?>
		        		</button>
					</div>
				</div>	
			</div>

		</div><!-- END PROFILE CONTENT -->



		
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




