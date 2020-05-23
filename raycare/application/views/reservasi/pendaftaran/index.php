<?php
	$form_attr = array(
	    "id"            => "form_pendaftaran_tindakan", 
	    "name"          => "form_pendaftaran_tindakan", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."reservasi/pendaftaran_tindakan/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');

	$alamat_sub = $this->pasien_m->get_data_subjek(1);
    $alamat_sub_array = $alamat_sub->result_array();
    
    $alamat_sub_option = array(
        '' => "Pilih..",

    );
    foreach ($alamat_sub_array as $select) {
        $alamat_sub_option[$select['id']] = $select['nama'];
    }

    $telp_sub = $this->pasien_m->get_data_subjek(2);
    $telp_sub_array = $telp_sub->result_array();
    
    $telp_sub_option = array(
        '' => "Pilih..",

    );
    foreach ($telp_sub_array as $select) {
        $telp_sub_option[$select['id']] = $select['nama'];
    }

    $shift_aktif = 0;
    if(date('H:i:s') > '06:30:00' &&  date('H:i:s') <= '11:30:00'){
        $shift_aktif = 1;
    }if(date('H:i:s') > '11:30:01' &&  date('H:i:s') <= '18:30:00'){
        $shift_aktif = 2;
    }if(date('H:i:s') > '18:30:01' &&  date('H:i:s') <= '23:30:00'){
        $shift_aktif = 3;
    }

    $shift = array(
        '' => 'Pilih..',
        '1' => 'Shift 1 "06:30 - 12:30"',
        '2' => 'Shift 2 "12:00 - 18:00"',
        '3' => 'Shift 3 "17:30 - 23:30"',

    );

    // echo date('Y-m-d H:i:s');
?>
<!-- BEGIN PROFILE SIDEBAR -->
<div class="profile-sidebar" style="width: 250px;">
	<!-- PORTLET MAIN -->
	<div class="portlet light profile-sidebar-portlet">
		<div class="patient-padding-picture"></div>
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
		<!-- SIDEBAR USERPIC -->
		<!--<div class="profile-userpic">
			<img id="side_img_pasien" src="<?=base_url().config_item('site_img_pasien')?>global/global_medium.png" class="img-responsive" alt="Foto tidak ditemukan">
		</div>-->
		<!-- END SIDEBAR USERPIC -->
		<!-- SIDEBAR USER TITLE -->
		<div class="profile-usertitle">
			<div class="profile-usertitle-name" id="side_nama_pasien">
				 Pasien
			</div>
			<div class="profile-usertitle-job" id="side_umur_pasien">
				 --- Tahun
			</div>
		</div>
		<!-- END SIDEBAR USER TITLE -->

	</div>
	<!-- END PORTLET MAIN -->
	<!-- PORTLET MAIN -->
	<div class="portlet light">
		<!-- STAT -->
		<div class="row list-separated profile-stat">
			<div class="col-md-4 col-sm-4 col-xs-6">
				<div class="uppercase profile-stat-title" id="side_transaksi_pasien">
					 ---
				</div>
				<div class="uppercase profile-stat-text">
					 Transaksi
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-6">
				<div class="uppercase profile-stat-title" id="side_tagihan_pasien">
					 ---
				</div>
				<div class="uppercase profile-stat-text">
					 Tagihan
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-6">
				<div class="uppercase profile-stat-title" id="side_upload_pasien">
					 ---
				</div>
				<div class="uppercase profile-stat-text">
					 Uploads
				</div>
			</div>
		</div>
		<!-- END STAT -->
		<div class="tentang_pasien" style="display: none;">
			<h4 class="profile-desc-title" id="side_tentang_pasien">Tentang Pasien</h4>
			<span class="profile-desc-text" id="side_keterangan_pasien"> 
				<div class="form-group">
					<div class="col-md-12">
						<label class="control-label col-md-12 bold" style="text-align: left;">Tanggal Registrasi : </label>
						<label class="side_tgl_registrasi col-md-12"></label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label class="control-label col-md-12 bold" style="text-align: left;">Alamat :</label>
						<label class="side_alamat col-md-12"></label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label class="control-label col-md-12 bold" style="text-align: left;">Gender : </label>
						<label class="side_gender col-md-12"></label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label class="control-label col-md-12 bold" style="text-align: left;">Tempat, Tgl Lahir : </label>
						<label class="side_ttl col-md-12"></label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label class="control-label col-md-12 bold" style="text-align: left;">Telepon : </label>
						<label class="side_tlp col-md-12"></label>
					</div>
				</div>
				
				
			</span>

		</div>
	</div>
	<!-- END PORTLET MAIN -->
	<div class="portlet box blue-chambray">
		<div class="portlet-title" style="margin-bottom: 0px !important;">
			<div class="caption antrian-title">
				<?=translate("Antrian", $this->session->userdata("language"))?>
			</div>
		</div>
		<div class="portlet-body">
			<div class="row" id="ant2">	
				<div class="col-md-12">
					<table class="table table-striped table-hover table-condensed" id="table_antrian">
					<thead>
					<tr>
						<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Antrian", $this->session->userdata("language"))?> </th>
				 
					</tr>
					</thead>
					<tbody>
			
					</tbody>
					</table>
		 		</div>
			</div><!-- end of <div class="row" id="ant2"> -->
		</div>
			
	</div><!-- end of <div class="portlet light bordered"> -->
</div>
<!-- END BEGIN PROFILE SIDEBAR -->
<!-- BEGIN PROFILE CONTENT -->
<div class="profile-content">
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-cursor font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pendaftaran Rawat Jalan", $this->session->userdata("language"))?></span>
						<!-- <span class="caption-helper"><?php echo '<label class="control-label current_time">'.date('d M Y').'</label>'; ?></span> -->
						<!-- <span class="caption-helper" style="color:red;">Peringatan : Resepsionis diwajibkan input jadwal menggunakan program, Terimakasih.</span> -->
					</div>
				</div>
				<input type="hidden" id="cabangid" name="cabangid" value="<?=$this->session->userdata('cabang_id')?>">
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
					    	<div class="col-md-7">
					    		<div class="portlet box blue-sharp">
						 				<div class="portlet-title" style="margin-bottom: 0px !important;">
						 					<div class="caption">
						 						<?=translate("Form Input", $this->session->userdata("language"))?>
						 					</div>
						 				</div>
						 				
						 				<div class="portlet-body">
						 				<div class="note note-success note-bordered">
											<p>
								 				INFO : Untuk pencarian data pasien, bisa dilakukan dengan cara menginput No. RM / No. KTP / No. BPJS (Sesuai dengan jenis kartu yang dipilih), kemudian tekan enter
											</p>
										</div>
							 				<div class="row">
							 				<div class="col-md-6">
							 						<div class="form-group">
														<label class="col-md-12 bold"><?=translate("Jenis Kartu", $this->session->userdata("language"))?> :</label>
														<div class="col-md-12">
															<div class="input-group">
                                                                <div class="input-group-btn">
                                                                    <button id="button_jenis_kartu" type="button" class="btn green dropdown-toggle" data-toggle="dropdown" aria-expanded="false">No. RM
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a class="list_jenis_kartu" data-id="1" data-text="No. RM" > No. RM </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="list_jenis_kartu" data-id="2" data-text="No. KTP"> No. KTP </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="list_jenis_kartu" data-id="3" data-text="No. BPJS"> No. BPJS </a>
                                                                        </li>

                                                                    </ul>
                                                                </div>
                                                                <!-- /btn-group -->
                                                                <input id="no_member" name="no_member" class="form-control" placeholder="Isi No. Rekam Medis" type="text">
                                                                <input id="tipe_kartu" name="tipe_kartu" class="form-control" type="hidden" value="1">
                                                            </div>
														</div>
													</div>
							 					</div>
							 					<div class="col-md-6 hidden">
							 						<div class="form-group">
														<label class="col-md-12 bold"><?=translate("No. Rekam Medik", $this->session->userdata("language"))?> :</label>
														<div class="col-md-12">													
															<div class="input-group">
															<?php
																

																$id_ref_pasien = array(
																	"id"			=> "id_pasien",
																	"name"			=> "id_pasien",
																	"autofocus"			=> true,
																	"class"			=> "form-control hidden", 
																	"placeholder"	=> translate("ID Referensi Pasien", $this->session->userdata("language")), 
																	"value"			=> $flash_form_data['id_ref_pasien'],
																	"help"			=> $flash_form_data['id_ref_pasien'],
																	"required"		=>"required"
																);
																echo form_input($id_ref_pasien);
															?>
																<span class="input-group-btn">
																	<a class="btn btn-primary pilih-pasien" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
																		<i class="fa fa-search"></i>
																	</a>
																</span>
															</div>
														</div>
													</div>
							 					</div>
							 					<div class="col-md-6">
							 						<div class="form-group">
														<label class="col-md-12 bold"><?=translate("Kode Poli", $this->session->userdata("language"))?> :</label>
														<div class="col-md-12">
															<div class="input-group">
																<?php
																	$cabang_id = $this->session->userdata('cabang_id');
																	$poliklinik = $this->poliklinik_m->getdataall($cabang_id);
																	$poliklinik_sub_option = $poliklinik->result_array();
																	$sub_option = array('' => "Pilih..");
																    foreach ($poliklinik_sub_option as $select) {
																        $sub_option[$select['id']] = $select['nama'];
																    }

																	echo form_dropdown('poliklinik', $sub_option, 1, "id=\"poliklinik_sub\" class=\"form-control\" required=\"required\" ");
																?>
																<span class="input-group-btn btn-group-status">
																	<a title="<?=translate("", $this->session->userdata("language"))?>" id="button_status" class="btn default"><i id="icon_status" class="fa "></i></a>
																</span>
															</div>
														</div>
													</div>
							 					</div>
							 				</div>
							 				<div class="row">
							 					<div class="col-md-6">
							 						<div class="form-group">
														<label class="col-md-12 bold"><?=translate("Shift", $this->session->userdata("language"))?> :</label>
														<div class="col-md-12">
															<?php
																echo form_dropdown('shift', $shift, $shift_aktif, "id=\"shift\" class=\"form-control\" required=\"required\" ");
															?>
														</div>
													</div>
							 					</div>
							 					<div class="col-md-6">
							 						<div class="form-group">
														<label class="col-md-12 bold"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
														<div class="col-md-12">
															<?php
																$sub = array();
																echo form_dropdown('dokter', $sub, '', "id=\"dokter\" class=\"form-control\" required=\"required\" ");
															?>
														</div>
													</div>
							 					</div>
							 				</div>
							 				<div class="row">
							 					<div class="col-md-6">
							 						<div class="form-group" id="ant">
														<label class="col-md-12 bold"><?=translate("No. Antrian", $this->session->userdata("language"))?> :</label>
														<div class="col-md-12">
															 <label class="control-label" id="noantrian">0</label>
															 <input type="hidden" id="noantrianval" name="noantrianval">
														</div>
													</div>
							 					</div>
							 					<div class="col-md-6">
							 						<div class="form-group">
									 					<label class="col-md-12 bold"><?=translate("Penanggung Jawab", $this->session->userdata("language"))?> :</label>
														<?php
															$tipe_pj_option = array(
																'1'	=> translate('Diri Sendiri', $this->session->userdata('language'))
															);
														?>
														<div class="col-md-12">
															<div class="input-group">
															<?php
																echo form_dropdown('tipe_pj_daftar', $tipe_pj_option, '1', 'id="tipe_pj_daftar" class="form-control"');
															?>
															<input type="hidden" class="form-control" name="penanggungjawab_id" id="penanggungjawab_id" ></input>
															
															<span class="input-group-btn btn-group-pj">
																<a title="<?=translate("Tambah PJ", $this->session->userdata("language"))?>" href="<?=base_url()?>reservasi/pendaftaran_tindakan/add_pj" id="add_pj" data-toggle="modal" data-target="#modal_pj" class="btn btn-primary" disabled><i id="icon_status" class="fa fa-plus"></i></a>
															</span>
															</div>
														</div>
								 					</div>
							 					</div>
							 					<div class="col-md-6">
							 						<div class="form-group hidden" id="nama_pj">
									                    <label class="col-md-12 bold"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
									                    <div class="col-md-12">
									                        <?php
									                        	$nama_pj_option = array();
																echo form_dropdown('nama_pj', $nama_pj_option, '', 'id="nama_pj" class="form-control"');
															?>
									                    </div>
									                </div>
							 					</div>
							 				</div><!--row-->
						 					
										
										
										
										
					 					
					 					
					 					<div class="hidden" id="div_pj">
					 						<div class="form-group hidden" id="div_alias">
							                    <label class="col-md-12"><?=translate("Alias Penanggungjawab", $this->session->userdata("language"))?> :</label>
							                    <div class="col-md-12">
							                        <input class="form-control" name="alias_pj" id="alias_pj" readonly></input>
							                    </div>
							                </div>
							                <div class="form-group hidden" id="div_nama">
							                    <label class="col-md-12"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
							                    <div class="col-md-12">
							                        <input class="form-control" name="nama" id="nama" readonly></input>
							                    </div>
							                </div>
							                <div class="form-group">
							                    <label class="col-md-12"><?=translate("No. KTP", $this->session->userdata("language"))?> :</label>
							                    <div class="col-md-12">
							                        <input class="form-control" name="no_ktp" id="no_ktp" readonly></input>
							                    </div>
							                </div>
							                
							                <div class="form-group">
							                    <label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
							                    <div class="col-md-12">
							                        <div class="row">
							                           <div class="col-md-12">
							                                <textarea class="form-control" id="alamat" name="alamat" readonly></textarea>
							                           </div>
							                        </div>
							                    </div>
							                </div>
							                
							                <div class="form-group">
							                    <label class="col-md-12"><?=translate("Telepon", $this->session->userdata("language"))?> :</label>
							                    <div class="col-md-12">
							                        <div class="row">
							                        
							                           <div class="col-md-12">
							                                <input class="form-control" name="no_telepon" id="no_telepon" placeholder="<?=translate("No. Telepon", $this->session->userdata("language"))?>" readonly></input>
							                           </div>
							                        </div>
							                    </div>
							                </div>
					 					</div>
						 				</div>
										
					 					
									</div><!-- PENUTUP PORLET FORM INPUT-->

								<div class="portlet box blue-sharp">
						 				<div class="portlet-title" style="margin-bottom: 0px !important;">
						 					<div class="caption">
						 						<?=translate("Penjamin", $this->session->userdata("language"))?>
						 					</div>
						 				</div>
						 				<div class="portlet-body">
						 				<table class="table table-striped table-hover" id="table_klaim1">
											<thead>
												<tr role="row">
													<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
													<th class="text-center"><?=translate("No. Kartu", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="1%"><?=translate("Aktif", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="1%"><?=translate("Penggunaan", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="1%"><?=translate("Pilih", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="1%"><?=translate("Pilih", $this->session->userdata("language"))?> </th>
													 
												</tr>
											</thead>
											<tbody>
												 
											</tbody>
										</table>
						 				</div>
					 				</div><!-- PENUTUP PORTLET PENJAMIN -->	

					 				<div class="portlet box blue-sharp">
						 				<div class="portlet-title" style="margin-bottom: 0px !important;">
						 					<div class="caption">
						 						<?=translate("Dokumen", $this->session->userdata("language"))?>
						 					</div>
						 				</div>
						 				<div class="portlet-body">
						 				<table class="table table-striped table-hover" id="table_cabang4">
											<thead>
												<tr role="row">
													<th class="text-center"><?=translate("Nama Dokumen", $this->session->userdata("language"))?> </th>
													<th class="text-center"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> </th>
													<th class="text-center"><?=translate("Jenis", $this->session->userdata("language"))?> </th>
													 <th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
											 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
											 		<th class="text-center" width="200"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
											 		<th class="text-center" width="200"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
						 				</div>
					 				</div><!-- PENUTUP PORTLET DOKUMEN -->

					    	</div>
					    	<div class="col-md-5">
					    		<div class="portlet light">
									<div class="portlet-title tabbable-line">
										<div class="caption">
											<?=translate("Informasi", $this->session->userdata("language"))?></span>
										</div>
										<ul class="nav nav-tabs">
											<li class="active">
												<a href="#tab_2_1" data-toggle="tab" id="daftar">
												Daftar </a>
											</li>
											<li>
												<a href="#tab_2_2" data-toggle="tab" class="hidden" id="rujukan ">
												Rujukan <span class='badge badge-primary hidden' id="notifrujukan" style='width:15px;height:15px'></span> </a>
											</li>
											
										</ul>
									</div>
									<div class="portlet-body form">
										<div class="tab-content">
											<div class="tab-pane fade active in" id="tab_2_1">
												<div class="portlet-body">														
													<div class="portlet box red">
														<div class="portlet-title" style="margin-bottom: 0px !important;">
															<div class="caption">
																<?=translate("Pasien Tidak Hadir", $this->session->userdata("language"))?>
															</div>
														</div>
														<div class="portlet-body" id="ant2">	
															<div class=" table-scrollable">
																<table class="table table-striped table-hover table-condensed">
																<thead>
																<tr>
																	<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
																	<th class="text-center" width="5%"><?=translate("No. RM", $this->session->userdata("language"))?> </th>
																	<th class="text-center" width="50%"><?=translate("Nama Pasien", $this->session->userdata("language"))?> </th>
																	<th class="text-center" width="40%"><?=translate("Alasan", $this->session->userdata("language"))?> </th>
																</tr>
																</thead>
																<tbody id="table_pasien_belum_datang">
																	
																</tbody>
																<tfoot>
																	<tr>
																		<th colspan="3">Total Jadwal Seharusnya</th>
																		<th id="total_jadwal_seharusnya"></th>
																	</tr>
																	<tr>
																		<th colspan="3">Total Tidak Hadir</th>
																		<th id="total_belum_datang"></th>
																	</tr>
																</tfoot>
																</table>
													 		</div>
														</div><!-- end of <div class="row" id="ant2"> -->	
													</div><!-- end of <div class="portlet light bordered"> -->
														<div class="portlet box blue-madison">
															<div class="portlet-title" style="margin-bottom: 0px !important;">
																<div class="caption">
																	<?=translate("Tindakan Tanpa Jadwal", $this->session->userdata("language"))?>
																</div>
															</div>
															<div class="portlet-body" id="ant2">	
															
																	<table class="table table-striped table-hover table-condensed">
																	<thead>
																	<tr>
																		<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
																		<th class="text-center" width="5%"><?=translate("No. RM", $this->session->userdata("language"))?> </th>
																		<th class="text-center" width="30%"><?=translate("Nama Pasien", $this->session->userdata("language"))?> </th>
																	</tr>
																	</thead>
																	<tbody id="table_pasien_tanpa_jadwal">
																		
																	</tbody>
																	<tfoot>
																		<tr>
																			<th colspan="2">Total Tindakan</th>
																			<th id="total_tindakan"></th>
																		</tr>
																	</tfoot>
																	</table>
														 		
															</div><!-- end of <div class="row" id="ant2"> -->	
														</div><!-- end of <div class="portlet light bordered"> -->
														<div class="portlet light bordered hidden">
															<div class="row">
																<div class= "col-md-8">
																		<div class="form-group">
																			<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
																			<div class="col-md-8">
																				<?php
																					$nama_pasien = array(
																						"id"			=> "nama_pasien",
																						"name"			=> "nama_pasien",
																						"autofocus"			=> true,
																						"class"			=> "form-control", 
																						"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
																						"value"			=> $flash_form_data['nama_pasien'],
																						"help"			=> $flash_form_data['nama_pasien'],
																						"readonly"		=> "readonly",
																					);

																					echo form_input($nama_pasien);
																				?>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
																			<div class="col-md-8">
																				<?php
																					$alamat_pasien = array(
																						"id"			=> "alamat_pasien",
																						"name"			=> "alamat_pasien",
																						"autofocus"			=> true,
																						"class"			=> "form-control", 
																						"placeholder"	=> translate("Alamat", $this->session->userdata("language")), 
																						"value"			=> $flash_form_data['alamat_pasien'],
																						"help"			=> $flash_form_data['alamat_pasien'],
																						"readonly"		=> "readonly",
																					);

																					echo form_input($alamat_pasien);
																				?>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="control-label col-md-4"><?=translate("Gender", $this->session->userdata("language"))?> :</label>
																			<div class="col-md-8">
																				<?php
																					$gender_pasien = array(
																						"id"			=> "gender_pasien",
																						"name"			=> "gender_pasien",
																						"autofocus"			=> true,
																						"class"			=> "form-control", 
																						"placeholder"	=> translate("Gender", $this->session->userdata("language")), 
																						"value"			=> $flash_form_data['gender_pasien'],
																						"help"			=> $flash_form_data['gender_pasien'],
																						"readonly"		=> "readonly",
																					);

																					echo form_input($gender_pasien);
																				?>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="control-label col-md-4"><?=translate("Tempat, Tgl Lahir", $this->session->userdata("language"))?> :</label>
																			<div class="col-md-8">
																				<?php
																					$tgl_lahir_pasien = array(
																						"id"			=> "tgl_lahir_pasien",
																						"name"			=> "tgl_lahir_pasien",
																						"autofocus"			=> true,
																						"class"			=> "form-control", 
																						"placeholder"	=> translate("Tempat, Tgl Lahir", $this->session->userdata("language")), 
																						"value"			=> $flash_form_data['tgl_lahir_pasien'],
																						"help"			=> $flash_form_data['tgl_lahir_pasien'],
																						"readonly"		=> "readonly",
																					);

																					echo form_input($tgl_lahir_pasien);
																				?>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="control-label col-md-4"><?=translate("Umur", $this->session->userdata("language"))?> :</label>
																			<div class="col-md-8">
																				<?php
																					$umur = array(
																						"id"			=> "umur",
																						"name"			=> "umur",
																						"autofocus"			=> true,
																						"class"			=> "form-control", 
																						"placeholder"	=> translate("Umur", $this->session->userdata("language")), 
																						"value"			=> $flash_form_data['umur'],
																						"help"			=> $flash_form_data['umur'],
																						"readonly"		=> "readonly",
																					);

																					echo form_input($umur);
																				?>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="control-label col-md-4"><?=translate("Telepon", $this->session->userdata("language"))?> :</label>
																			<div class="col-md-8">
																				<?php
																					$telepon_pasien = array(
																						"id"			=> "telepon_pasien",
																						"name"			=> "telepon_pasien",
																						"autofocus"			=> true,
																						"class"			=> "form-control", 
																						"placeholder"	=> translate("Telepon", $this->session->userdata("language")), 
																						"value"			=> $flash_form_data['telepon_pasien'],
																						"help"			=> $flash_form_data['telepon_pasien'],
																						"readonly"		=> "readonly",
																					);

																					echo form_input($telepon_pasien);
																				?>
																			</div>
																		</div>
																	</div>

																	<div class="col-md-4">
																		<div class="col-md-3">
																		<table width="100px" border="1" style="border:0px">
																		<tr>
																			<td id="imgpasien" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;width:100px;height:100px;border:0px;"> </td>
																		</tr>
																		</table>
																	</div>
																	</div>
																</div>
															 </div>
															

														</div>



												 
											</div>
											<div class="tab-pane fade" id="tab_2_2">
												<div class="portlet-body">
													 <?php include('tab_pendaftaran_tindakan/rujukan.php') ?>
												</div>
											</div>
											
											

											
										</div>
									</div>
								</div>
					    	</div>
					    </div>

								 
								
				  		<div class="form-actions right">
				            <a class="btn btn-circle btn-default hidden" id="button_warning" data-target="#modal_warning" data-toggle="modal" href="<?=base_url()?>reservasi/pendaftaran_tindakan/modal_warning">
								<i class="fa fa-undo"></i>
								<?=translate('Warning', $this->session->userdata('language'))?>
							</a>
							<a class="btn default history" href="<?=base_url()?>reservasi/pendaftaran_tindakan/history">
								<i class="fa fa-undo"></i>
								<?=translate('History', $this->session->userdata('language'))?>
							</a>
							<a class="btn green tambah-pasien" href="<?=base_url()?>master/pasien/add">
								<i class="fa fa-plus"></i>
								<?=translate('Registrasi Pasien Baru', $this->session->userdata('language'))?>
							</a>

							<?php $msg = translate("Apakah anda yakin akan membuat pendaftaran tindakan ini?",$this->session->userdata("language"));?>
							<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
								<i class="glyphicon glyphicon-floppy-disk"></i>
								<?=translate("Simpan", $this->session->userdata("language"))?>
							</a>
				            <button type="submit" id="save" class="btn default hidden" >			            	
				            	<?=translate("Simpan", $this->session->userdata("language"))?>
				            </button>
						</div>
					<?=form_close()?>
				</div>
				</div>
			</div>
		</div><!-- end of col-md-12 -->
	</div><!-- end of row -->
</div><!-- end of div profile-content -->
<!-- END PROFILE CONTENT -->



<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No Member', $this->session->userdata('language'))?></div></th>
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

<div class="modal fade" id="ajax_notes" role="basic" aria-hidden="true" style="display:none">
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

<div class="modal fade" id="modal_warning"  role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal_pj"  role="basic" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
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


     <div class="modal fade" id="ajax_notes3" role="basic" aria-hidden="true" style="display:none">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title caption-subject font-blue-sharp bold uppercase"><?=translate('Update Dokumen Pasien', $this->session->userdata('language'))?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="alert alert-danger display-hide">
                            <button class="close" data-close="alert"></button>
                            <?=$form_alert_danger?>
                        </div>
                        <div class="alert alert-success display-hide">
                            <button class="close" data-close="alert"></button>
                            <?=$form_alert_success?>
                        </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Nama Pasien", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                         <label class="control-label" id="nama2" name="nama2"></label>
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Jenis Dokumen", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                         <label class="control-label" id="jenisdokumen2" name="jenisdokumen2"></label>
                                        
                                        
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Nama Dokumen", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                         <label class="control-label" id="namadokumen2" name="namadokumen2" ></label>
                                         
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("No.Dokumen", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                         <label class="control-label" id="nodokumen2" name="nodokumen2"></label>
                                   
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Tipe", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                     <div class="input-group">
                                     <label class="control-label" id="kel22" name="kel22"> </label>
                                  </div>
                                </div>
                                 <div class="col-md-12"></div>
                            </div>
                        <div class="style" style="margin-top: 10px;">
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group input-medium-date date date-picker">
                                        <input class="form-control" id="date2" readonly required="required" value="<?=date('d M Y')?>">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn default date-set">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Dokumen", $this->session->userdata("language"))?></label>
                                 
                                <div class="col-md-4">
                                    <input type="file" id="uploaddokumen2" name="uploaddokumen2" class="uploadbutton up-this" value="" required="required" />
                               
                                </div>
                                 <div class="col-md-12"></div>
                                <input type="hidden" id="uploadfilename2" name="uploadfilename2" required="required" />
                            </div> 
                            <div id="uploadchoosen_file_container_12" name="uploadchoosen_file_container_12" style="display:none" >
                                <div class="form-group">
                                    <label class="control-label col-md-4"></label>
                                    <div class="col-md-4">
                                        <label id="uploadchoosen_file_12" name="uploadchoosen_file_12">
                                           
                                        </label>
                                    </div>
                                    <div class="col-md-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>
 
                </div>
                <div class="modal-footer">
                    <div class="form-actions fluid">    
                        <div class="col-md-12">
                             
                            <button type="reset" class="btn default" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></button>
                            <a id="confirm_save3" class="btn btn-primary" href="#" data-confirm="<?=$msg2?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
                            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
                        </div>      
                    </div>
                    <!-- <button type="button" class="btn default" data-dismiss="modal">Simpan</button> -->
                </div>
            </div>
        </div>
    </div>
</form>
 

<form id="modalpic" name="modalpic"    autocomplete="off">
    <?
    	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
      $msg 					  = translate("Apakah anda yakin akan menambah dokumen ini?",$this->session->userdata("language"));
    ?>
    <div class="modal fade" id="ajax_notes2" role="basic" aria-hidden="true" style="display:none">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-body">
                    <div class="row">
        	           
                            <div class="form-group">
        					 	<div id="gambar" align="center"></div>
        			        </div>
        			         
                      
                        </div>
                    </div>
                    <div class="modal-footer">
                    <div class="form-actions fluid">	
    				    <div class="col-md-12">
    				    	 
                            <button type="reset" class="btn default" data-dismiss="modal"><?=translate("Ok", $this->session->userdata("language"))?></button>
                             
        		      	</div>		
                    </div>
                    <!-- <button type="button" class="btn default" data-dismiss="modal">Simpan</button> -->
                </div>
 
                </div>
                 
            </div>

        </div>
   
</form>