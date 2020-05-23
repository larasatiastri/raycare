<?php
	$form_attr = array(
	    "id"            => "form_add_cabang", 
	    "name"          => "form_add_cabang", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."master/cabang/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$msg = translate("Apakah anda yakin akan membuat cabang ini?",$this->session->userdata("language"));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Cabang", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				<i class="fa fa-backward"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
    		<button type="submit" id="save" class="btn default hidden" >
    			<?=translate("Simpan", $this->session->userdata("language"))?>
    		</button>
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
						<label class="control-label col-md-2"><?=translate("Tipe", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
						<div class="col-md-2">
							<?php
								$sub_option = array('' => translate("Pilih", $this->session->userdata('language')).'..',
													'1' => translate("Rumah Sakit", $this->session->userdata('language')),
													'2' => translate("Distributor", $this->session->userdata('language')),
													'3' => translate("Produksi", $this->session->userdata('language'))
													);
							    
								//die_dump($alamat_sub_option);
								
								echo form_dropdown('tipe', $sub_option, '', "id=\"tipe\" class=\"form-control \" required=\"required\"");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("Kode Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
						<div class="col-md-2">
							<?php
								$kode_cabang = array(
									"name"			=> "kode",
									"id"			=> "kode",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Kode Cabang", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($kode_cabang);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("Nama Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
						<div class="col-md-3">
							<?php
								$nama_cabang = array(
									"name"			=> "nama",
									"id"			=> "nama",
									"class"			=> "form-control", 
									"placeholder"	=> translate("Nama Cabang", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($nama_cabang);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("URL Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
						<div class="col-md-3">
							<?php
								$url_cabang = array(
									"name"			=> "url",
									"id"			=> "url",
									"class"			=> "form-control", 
									"placeholder"	=> translate("URL Cabang", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($url_cabang);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
						<div class="col-md-3">
							<?php
								$keterangan = array(
									"name"			=> "keterangan",
									"id"			=> "keterangan",
									"class"			=> "form-control", 
									"placeholder"	=> translate("Keterangan", $this->session->userdata("language")),
									"rows"			=> "4" 								
								);
								echo form_textarea($keterangan);
							?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="portlet" id="section-add-alamat">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Alamat', $this->session->userdata('language'))?></span>
								</div>
							</div>
							<div class="portlet-body">
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat Subjek", $this->session->userdata("language"))?> :<span class="required">*</span></label>
									<div class="col-md-6">
										<div class="input-group">
											<?php
												$tipe = 1;
												$alamat_sub = $this->cabang_m->get_data_sub($tipe);
												$alamat_sub_option = $alamat_sub->result_array();
												$sub_option = array('' => "Pilih..");
											    foreach ($alamat_sub_option as $select) {
											        $sub_option[$select['id']] = $select['nama'];
											    }
												//die_dump($alamat_sub_option);
												
												echo form_dropdown('sub_alamat', $sub_option, '', "id=\"subjek_alamat\" class=\"form-control warehouse\" required=\"required\" ");
											?>
											<input type="text" id="input_subjek_alamat" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_subjek_alamat" title="<?=translate('Edit', $this->session->userdata('language'))?>"><i class="fa fa-pencil" ></i></a>
												<a class="btn green-haze hidden" id="btn_save_subjek_alamat" title="<?=translate('Save', $this->session->userdata('language'))?>"><i class="fa fa-check" ></i></a>
												<a class="btn yellow hidden" id="btn_cancel_subjek_alamat" title="<?=translate('Cancel', $this->session->userdata('language'))?>"><i class="fa fa-undo" ></i></a>
											</span>
										</div>	
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :<span class="required">*</span></label>
									<div class="col-md-6">
										<?php
											$alamat = array(
												"name"        => "alamat",
												"id"          => "alamat",
												"autofocus"   => true,
												"class"       => "form-control", 
												"required"    => "required", 
												"placeholder" => translate("Alamat", $this->session->userdata("language")), 
												"rows"        => "4"
											);
											echo form_textarea($alamat);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("RT / RW", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" id="rt" name="rt" class="form-control" placeholder="RT">
											<span class="input-group-addon">/</span>
											<input type="text" id="rw" name="rw" class="form-control" placeholder="RW">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kelurahan / Desa", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<div class="input-group">
			                                <input type="text" id="input_kelurahan" class="form-control" disabled="disabled">
			                                <input type="hidden" id="input_kode" class="form-control">
										<span class="input-group-btn">
											<a class="btn btn-primary search_kelurahan" id="btn_cari_kelurahan" data-toggle="modal" data-target="#modal_alamat" title="<?=translate('Cari', $this->session->userdata('language'))?>" href="<?=base_url()?>master/cabang/search_kelurahan"><i class="fa fa-search"></i></a>
										</span>
										</div>
									</div>
								</div>
								<div id="div_lokasi" class="hidden">
									<div class="form-group">
										<label class="control-label col-md-4"><?=translate("Kecamatan", $this->session->userdata("language"))?> :</label>
										<div class="col-md-6">
			                                <input type="text" id="input_kecamatan" class="form-control" disabled="disabled">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4"><?=translate("Kota / Kabupaten", $this->session->userdata("language"))?> :</label>
										<div class="col-md-6">
			                                <input type="text" id="input_kota" class="form-control" disabled="disabled">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4"><?=translate("Provinsi", $this->session->userdata("language"))?> :</label>
										<div class="col-md-6">
			                                <input type="text" id="input_provinsi" class="form-control" disabled="disabled">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4"><?=translate("Negara", $this->session->userdata("language"))?> :</label>
										<div class="col-md-6">
			                                <input type="text" id="input_negara" class="form-control" disabled="disabled">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kode Pos", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											$negara = array(
												"name"			=> "kode_pos",
												"id"			=> "kode_pos",
												"autofocus"			=> true,
												"class"			=> "form-control", 
												"placeholder"	=> translate("Kode Pos", $this->session->userdata("language"))
											);
											echo form_input($negara);
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="portlet" id="section-telepon">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Telepon', $this->session->userdata('language'))?></span>
								</div>
								<div class="actions">
									<a class="btn btn-primary add-phone">
										<i class="fa fa-plus"></i> 
										<?=translate('Tambah', $this->session->userdata('language'))?>
									</a>										
								</div>
							</div>
							<div class="portlet-body">
								<?php
									$telp_sub = $this->cabang_m->get_data_sub_telp();
									$telp_sub_option = $telp_sub->result_array();
									$sub_option = array('' => "Pilih..");
								    foreach ($telp_sub_option as $select) {
								        $sub_option[$select['id']] = $select['nama'];
								    }
									//die_dump($alamat_sub_option);
									$form_phone = '<div class="form-group">
									<label class="control-label col-md-4">'.translate("Telepon Subjek", $this->session->userdata("language")).' :<span class="required">*</span></label>
									<div class="col-md-4">
										<div class="input-group">
											'.form_dropdown('phone[{0}][subjek]', $sub_option, '', "id=\"subjek_telepon_{0}\" class=\"form-control\" required=\"required\" ").'
											<input type="text" id="input_subjek_telepon_{0}" class="form-control hidden">
										<span class="input-group-btn">
											<a class="btn blue-chambray" id="btn_edit_subjek_telepon_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
											<a class="btn red-intense del-this" id="btn_delete_subjek_telepon_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
											<a class="btn green-haze hidden" id="btn_save_subjek_telepon_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
											<a class="btn yellow hidden" id="btn_cancel_subjek_telepon_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
										</span>
										</div>
									</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :<span class="required">*</span></label>
										<div class="col-md-4">
											<input class="form-control" id="nomer_0" name="phone[{0}][number]" placeholder="Nomor Telepon" required>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4"></label>
										<div class="col-md-4">										
	                                        <input type="hidden" id="primary_id_{0}" name="phone[{0}][is_primary]" >
	                                        <div class="radio-list">
												<label class="radio-inline">
												<input type="radio" name="phone_is_primary" data-id="{0}" id="primary_phone_{0}" value="1">'.translate('Utama', $this->session->userdata('language')).'</label>
											</div>		                             
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
				<div class="row hidden" id="tipe_rumah_sakit">
				<div class="col-md-12">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Poliklinik & Dokter", $this->session->userdata("language"))?></span>
							</div>
							<div class="actions">
									<a class="btn btn-primary add-poliklinik-dokter">
										<i class="fa fa-plus"></i> 
										<?=translate('Tambah', $this->session->userdata('language'))?>
									</a>										
								</div>
						</div>
						<?php
						   	$btn_del    = '<div class="text-center"><button type="button" class="btn btn-sx red del-this" title="Delete Phone"><i class="fa fa-times"></i></button></div>';
						    $poliklinik = array(
						        '1' => '',
						        '2' => 'Fax',
						        '3' => 'Home',
						    );
						    $poliklinik_sub = $this->cabang_m->get_data_poliklinik();
							$poliklinik_sub_option = $poliklinik_sub->result_array();
							$sub_option = array('' => "Pilih Subjek..");
						    foreach ($poliklinik_sub_option as $select) {
						        $sub_option[$select['id']] = $select['nama'];
						    }

						    $dokter_sub = $this->cabang_m->get_data_dokter();
						    $dokter_sub_option = $dokter_sub->result_array();
						    $options = array();
						    foreach ($dokter_sub_option as $key) {
						    	$options[$key['id']] = $key['dokter'];
						    }

						    $perawat_sub = $this->cabang_m->get_data_perawat();
						    $perawat_sub_option = $perawat_sub->result_array();
						    $perawat_options = array();
						    foreach ($perawat_sub_option as $key) {
						    	$perawat_options[$key['id']] = $key['perawat'];
						    }

                            $time_range = '<div class="col-md-8">
			                                <div class="input-group input-large" id="defaultrange">
			                                	<label class="control-label col-md-4">'.translate("Jam Buka", $this->session->userdata("language")).' :</label>
			                                    <div class="input-group">
													<input type="text" class="form-control timepicker timepicker-no-seconds" id="jam_buka_{0}" name="poliklinik[{0}][jam_buka]">
													<span class="input-group-btn">
														<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
													</span>
												</div>
												<label class="control-label col-md-4">'.translate("Jam Tutup", $this->session->userdata("language")).' :</label>
												<div class="input-group">
													<input type="text" class="form-control timepicker timepicker-no-seconds" id="jam_tutup_{0}" name="poliklinik[{0}][jam_tutup]">
													<span class="input-group-btn">
														<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
													</span>
												</div>
			                                </div>
			                            </div>';

						    // item row column
						    $item_cols = array(
						        'poliklinik_subject' => form_dropdown('poliklinik[{0}][subjek]', $sub_option, '', "id=\"poliklinik_subjek_{0}\" class=\"form-control\" "),
						        'poliklinik_waktu'  => $time_range,
						        'dokter' => form_dropdown('poliklinik[{0}][dokter][]', $options, '', "id=\"dokter_{0}\" class=\"form-control phone_sub\" multiple=\"multiple\" "),
						        'perawat' => form_dropdown('poliklinik[{0}][perawat][]', $perawat_options, '', "id=\"perawat_{0}\" class=\"form-control phone\" multiple=\"multiple\" "),
						        'action'        => $btn_del,
						    );

						    // gabungkan $item_cols jadi string table row
						    $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						?>
						<div class="portlet-body">
							<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
							<table class="table table-striped table-bordered table-hover table-condensed" id="table_poliklinik_dokter">
							<thead>
							<tr>
								<th class="text-center"><?=translate("Poliklinik", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Waktu Kerja", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Dokter", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Perawat", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
							</tr>
							</thead>
							<tbody>
							
							</tbody>
							</table>
						</div>
					</div>
				</div>
				</div>
			
			</div>
		<?=form_close()?>
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