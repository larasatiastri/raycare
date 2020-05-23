
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

			$msg = translate("Apakah anda yakin akan mengubah kelayakan anggota pasien ini?",$this->session->userdata("language"));

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
									<li class="active">
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
<?php
	$id_faskes = '';
	$kode_faskes = '';
	$nama_faskes = '';
	$reg_faskes = '';
	$nama_marketing_value = '';
	$id_marketing_value = '';

	if($form_data_faskes != ''){
		$id_faskes = $form_data_faskes['id'];
		$kode_faskes = $form_data_faskes['kode_faskes'];
		$nama_faskes = $form_data_faskes['nama_faskes'];
		$reg_faskes = $form_data_faskes['nama_reg'];
	}
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-briefcase font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Kelayakan Anggota", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate("Kode Cabang", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<?php
							$kode_cabang = array(
								"id"			=> "kode_cabang_rujukan",
								"name"			=> "kode_cabang_rujukan",
								"class"			=> "form-control", 
								"readonly"		=> "form-readonly", 
								"placeholder"	=> translate("Kode Cabang", $this->session->userdata("language")), 
								"value"			=> $form_data['ref_kode_cabang'],
							);
							echo form_input($kode_cabang);
						?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate("Asal Faskes Tk.1/Klinik/Puskesmas", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<?php
								$faskes_1 = array(
									"id"			=> "faskes_1",
									"name"			=> "faskes_1",
									"class"			=> "form-control", 
									"readonly"			=> "readonly", 
									"placeholder"	=> translate("Asal Faskes Tk.1/Klinik/Puskesmas", $this->session->userdata("language")), 
									"value"			=> $form_data['faskes_tk_1'],
								);
								echo form_input($faskes_1);

								$id_faskes_1 = array(
									"id"			=> "id_faskes_1",
									"name"			=> "id_faskes_1",
									"class"			=> "form-control", 
									"type"			=> "hidden",
									"value"			=> $form_data['faskes_tk_1_id'],
								);
								echo form_input($id_faskes_1);
							?>
							<span class="input-group-btn">
								<a class="btn btn-primary search_faskes" data-toggle="modal" data-target="#modal_faskes_1" title="<?=translate('Cari', $this->session->userdata('language'))?>" href="<?=base_url()?>master/pasien/search_faskes"><i class="fa fa-search"></i></a>
							</span>
						</div>
						<span class="help-block">
							Isi dengan nama faskes yang tercantum dalam kartu BPJS pasien.
						</span>
					</div>
				</div>

			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate("Asal RS Rujukan/Traveling", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<?php
								$asal_faskes = array(
									"id"			=> "asal_faskes",
									"name"			=> "asal_faskes",
									"class"			=> "form-control", 
									"readonly"			=> "readonly", 
									"placeholder"	=> translate("Asal RS Rujukan/Traveling", $this->session->userdata("language")), 
									"value"			=> $nama_faskes,
								);
								echo form_input($asal_faskes);
							?>
							<span class="input-group-btn">
								<a class="btn btn-primary search_faskes" data-toggle="modal" data-target="#modal_faskes" title="<?=translate('Cari', $this->session->userdata('language'))?>" href="<?=base_url()?>master/pasien/search_faskes_1"><i class="fa fa-search"></i></a>
							</span>
						</div>
						<span class="help-block">
							Isi dengan nama faskes yang tercantum dalam surat rujukan puskesmas.
						</span>
					</div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<div class="form-group faskes">
					<label class="col-md-12 bold"><?=translate("Kode Faskes", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<?php
							$kode_faskes = array(
								"id"			=> "kode_faskes",
								"name"			=> "kode_faskes",
								"class"			=> "form-control", 
								"readonly"			=> "readonly", 
								"placeholder"	=> translate("Kode Faskes", $this->session->userdata("language")), 
								"value"			=> $kode_faskes,
							);
							echo form_input($kode_faskes);

							$id_faskes = array(
								"id"			=> "id_faskes",
								"name"			=> "id_faskes",
								"class"			=> "form-control", 
								"type"			=> "hidden",
								"value"			=> $id_faskes,
							);
							echo form_input($id_faskes);
						?>
					</div>
				</div>

			</div>
			<div class="col-md-4">
				<div class="form-group faskes">
					<label class="col-md-12 bold"><?=translate("Regional", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<?php
							$regional = array(
								"id"			=> "regional",
								"name"			=> "regional",
								"class"			=> "form-control", 
								"readonly"			=> "readonly", 
								"placeholder"	=> translate("Regional", $this->session->userdata("language")), 
								"value"			=> $reg_faskes,
							);
							echo form_input($regional);
						?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group hidden">
					<label class="col-md-12 bold"><?=translate("Kode RS / Puskesmas Rujukan", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<?php
							$kode_rs_rujukan = array(
								"id"			=> "kode_rs_rujukan",
								"name"			=> "kode_rs_rujukan",
								"class"			=> "form-control", 
								"placeholder"	=> translate("Kode RS / Puskesmas Rujukan", $this->session->userdata("language")), 
								"value"			=> $form_data['ref_kode_rs_rujukan'],
							);
							echo form_input($kode_rs_rujukan);
						?>
					</div>
				</div>

			</div>

		</div>

		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate("Tanggal Rujukan RS/Faskes", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group date" id="tanggal_rujukan">
							<input type="text" class="form-control" id="tanggal_rujukan" name="tanggal_rujukan" readonly value="<?=($form_data['ref_tanggal_rujukan'] != NULL && $form_data['ref_tanggal_rujukan'] != '' && $form_data['ref_tanggal_rujukan'] != '0000-00-00 00:00:00')?date('d M Y', strtotime($form_data['ref_tanggal_rujukan'])):''?>">
							<span class="input-group-btn">
								<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
							</span>
						</div>
						<span class="help-block">
							Isi dengan tanggal rujukan dari RS/Faskes yang merujuk langsung ke Klinik Raycare.
						</span>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate("Nomor Rujukan", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<?php
							$nomer_rujukan = array(
								"id"    => "nomer_rujukan",
								"name"  => "nomer_rujukan",
								"class" => "form-control", 
								"type"  => "text",
								"class" => "form-control",
								"value" => $form_data['ref_nomor_rujukan'],
							);
							echo form_input($nomer_rujukan);
						?>
						<span class="help-block">
							Isi dengan nomor rujukan dari Faskes tk 1 yang ada di kartu BPJS. Jika rujukan langsung dari RS maka isi dengan tanda strip(-)
						</span>
					</div>
					
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group faskes">
					<label class="col-md-12 bold"><?=translate("Marketing", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<?php
							$user_marketing = $this->user_m->get($form_data['marketing_id']);

							$marketing_option = array(
								''		=> translate('Pilih', $this->session->userdata('language')).'...'
							);
							$data_marketing = $this->user_m->get_by('user_level_id = 20 AND is_active = 1' );

							foreach ($data_marketing as $row) {
								$marketing_option[$row->id] =  $row->nama;
							}
							echo form_dropdown('id_marketing', $marketing_option, $form_data['marketing_id'], 'id="id_marketing" class="form-control"');

							$nama_marketing = array(
								"id"			=> "nama_marketing",
								"name"			=> "nama_marketing",
								"type"			=> "hidden",
								"value"			=> $user_marketing->nama
							);
							echo form_input($nama_marketing);
							
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
				<a id="confirm_save_kelayakan" class="btn btn-circle btn-primary" data-confirm="<?=$msg?>" data-toggle="modal">
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




