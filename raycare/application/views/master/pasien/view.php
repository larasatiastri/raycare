<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_view_pasien", 
			    "name"          => "form_view_pasien", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "view",
		        "id"		=> $pk_value
		    );

		    echo form_open(base_url()."master/pasien/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');

			// $get_hp_telp = $this->pasien_hubungan_telepon_m->get_by(array('pasien_hubungan_id' => '18'));
			// $get_hp_telp = $this->pasien_hubungan_telepon_m->get_by(array('pasien_hubungan_id' => $hubungan_pasien['id']));
			// $data_hp_telp = object_to_array($get_hp_telp);
		?>	

		<div class="form-body">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Umum", $this->session->userdata("language"))?></span>
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
							<label class="control-label col-md-2"><?=translate("Keterangan Daftar", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-2">
								<?php
									$cabang = $this->cabang_m->get_by(array('id' => $form_data['cabang_id']));
									// die(dump($this->db->last_query()));
									$cabang_option = array(
									    '' => translate('Pilih..', $this->session->userdata('language'))
									);

									$cabang_nama = '';
									foreach ($cabang as $data)
									{
									    // $cabang_option[$data->id] = $data->nama;
									    $cabang_nama = $data->nama;
									}
									// echo form_dropdown('cabang_id', $cabang_option, $form_data['cabang_id'], "id=\"cabang_id\" class=\"form-control\"");
								?>
								<label class="control-label"><?=$cabang_nama?></label>
							</div>
							<div class="col-md-2">
								<label class="control-label"><?=$form_data['no_member']?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-4">
								<label class="control-label"><?=$form_data['nama']?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Tempat & Tanggal Lahir", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-2">
								<label class="control-label"><?=$form_data['tempat_lahir']?></label>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">
									<?=date('d F Y', strtotime($form_data['tanggal_lahir']))?>
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Jenis Kelamin", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-4">
								<?php
									$gender = "";
									if ($form_data['gender'] == 'L') 
									{
										$gender = "Laki-Laki";
									}elseif($form_data['gender'] == 'P'){
										
										$gender = "Perempuan";
									}
                            	?>

		                        <label class="control-label"><?=$gender?></label>
		                    </div>
							
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Agama", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-2">
								<?php
									$agama = $this->info_umum_m->get_by(array('id' => $form_data['agama_id']));
									$agama_array = object_to_array($agama);
									
									$agama = '';
								    foreach ($agama_array as $select) {
								        $agama = $select['nama'];
								    }
								?>

								<label class="control-label"><?=$agama?></label>
							</div>
							
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Golongan Darah", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-2">
								<?php
									$golongan_darah = $this->info_umum_m->get_by(array('id' => $form_data['golongan_darah_id']));
									$golongan_darah_array = object_to_array($golongan_darah);
									
									$golongan_darah = '';
								    foreach ($golongan_darah_array as $select) {
								        $golongan_darah = $select['nama'];
								    }
								?>

								<label class="control-label"><?=$golongan_darah?></label>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Cara Masuk", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-2">
								<?php
									$cara_masuk = $this->info_umum_m->get_by(array('id' => $form_data['cara_masuk_id']));
									$cara_masuk_array = object_to_array($cara_masuk);
									
									// die_dump($this->db->last_query());
									$cara_masuk = '';
								    foreach ($cara_masuk_array as $select) {
								        $cara_masuk = $select['nama'];
								    }

								?>
								<label class="control-label"><?=$cara_masuk?></label>

							</div>
							
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Pendidikan", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-2">
								<?php
									$pendidikan = $this->info_umum_m->get_by(array('id' => $form_data['pendidikan_id']));
									$pendidikan_array = object_to_array($pendidikan);
									
									$pendidikan = '';
								    foreach ($pendidikan_array as $select) {
								        $pendidikan = $select['nama'];
								    }

								?>
								<label class="control-label"><?=$pendidikan?></label>

							</div>
							
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Pekerjaan", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-2">
								<?php
									$pekerjaan = $this->info_umum_m->get_by(array('id' => $form_data['pekerjaan_id']));
									$pekerjaan_array = object_to_array($pekerjaan);
									
									$pekerjaan = '';
								    foreach ($pekerjaan_array as $select) {
								        $pekerjaan = $select['nama'];
								    }

								?>
								<label class="control-label"><?=$pekerjaan?></label>

							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate('Foto', $this->session->userdata('language'))?> :</label>
							<div class="col-md-2">
								<?php
									$url =	explode('/', $form_data['url_photo']);
									// echo $url[1];
								?>
								<input type="hidden" name="url" id="url" value="<?=$url[1]?>">
								<div id="upload">
									<div id="drop">	
										<input type="file" class="hidden" name="upl" id="upl" data-url="http://localhost/raycarecore/upload/upload_photo" multiple="">
									</div>

									<ul class="ul-img" style="list-style: none; padding-left: 0px;">
										<li class="working">
											<div class="thumbnail">
												<a href="<?=config_item('site_img_pasien_temp_dir_copy').$form_data['url_photo']?>" target="_blank">
													<img src="<?=config_item('site_img_pasien_temp_dir_copy').$form_data['url_photo']?>" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
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
			</div>

			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Pendukung", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body">
					
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#telepon" data-toggle="tab">
							<?=translate('Telepon', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#alamat" data-toggle="tab">
							<?=translate('Alamat', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#surat_kelayakan_data_anggota" data-toggle="tab">
							<?=translate('Surat Kelayakan Data Anggota', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#informasi_dokumen" data-toggle="tab">
							<?=translate('Informasi Dokumen', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#informasi_lain" data-toggle="tab">
							<?=translate('Informasi Lain', $this->session->userdata('language'))?> </a>
						</li>
						<li>
							<a href="#hubungan_pasien" data-toggle="tab">
							<?=translate('Hubungan Pasien', $this->session->userdata('language'))?> </a>
						</li>

					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="telepon">
							<?php include('tab_view_pasien/tab_telepon.php') ?>
						</div>
						<div class="tab-pane" id="alamat">
							<?php include('tab_view_pasien/tab_alamat.php') ?>
						</div>
						<div class="tab-pane" id="surat_kelayakan_data_anggota">
							<?php include('tab_view_pasien/tab_surat_kelayakan_data_anggota.php') ?>
						</div>
						<div class="tab-pane" id="informasi_dokumen">
							<?php include('tab_view_pasien/tab_informasi_dokumen.php') ?>
						</div>
						<div class="tab-pane" id="informasi_lain">
							<?php include('tab_view_pasien/tab_informasi_lain.php') ?>
						</div>
						<div class="tab-pane" id="hubungan_pasien">
							<?php include('tab_view_pasien/tab_hubungan_pasien.php') ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php $msg = translate("Apakah anda yakin akan membuat data pasien ini?",$this->session->userdata("language"));?>
		<div class="form-actions fluid">	
			<div class="col-md-offset-1 col-md-9">
				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				<a id="confirm_save" class="btn btn-sm btn-primary hidden" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
		        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>		
		</div>
		<?=form_close()?>
	</div>
</div>

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




