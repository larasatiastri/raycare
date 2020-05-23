<?php
	$form_attr = array(
	    "id"            => "form_edit_cabang", 
	    "name"          => "form_edit_cabang", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "edit"
    );

    echo form_open(base_url()."master/cabang/save", $form_attr, $hidden);

    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	// die_dump($form_data_alamat);
	if(count($form_data_alamat))
	{
		if($form_data_alamat[0]->rt_rw != '/')
		{
			$rt_rw = explode("/", $form_data_alamat[0]->rt_rw);
			$rt = $rt_rw[0];
			$rw = $rt_rw[1];
		}
		$id_alamat    = $form_data_alamat[0]->id;
		$alamat       = $form_data_alamat[0]->alamat;
		$id_subjek    = $form_data_alamat[0]->subjek_id;
		$kode_lokasi    = $form_data_alamat[0]->kode_lokasi;
		$kode_pos = $form_data_alamat[0]->kode_pos;
	}
	else
	{
		$rt           = '';
		$rw           = '';		
		$id_alamat    = 0;
		$alamat       = '';
		$id_subjek    = 0;
		$kode_lokasi    = '';
		$kode_pos = '';
	}

	$data_alamat = $this->info_alamat_m->get_by(array('lokasi_kode' => $kode_lokasi));
	$lokasi = object_to_array($data_alamat);
	// die_dump($data_alamat);

	$hidden = 'hidden';
	if($form_data['tipe'] == 1)
	{
		$hidden = '';
	}

	$msg = translate("Apakah anda yakin akan mengubah cabang ini?",$this->session->userdata("language"));

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
			<button type="reset" class="btn btn-circle btn-default">
				<i class="fa fa-refresh"></i>
				<?=translate('Reset', $this->session->userdata('language'))?>
			</button>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
				<i class="fa fa-floppy-o"></i>
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
						<label class="control-label col-md-2"><?=translate("Tipe", $this->session->userdata("language"))?> :<span class="required">*</span></label>
						<div class="col-md-2">
							<?php
								$sub_option = array('' => translate("Pilih..", $this->session->userdata('language')).'...',
													'1' => translate("Rumah Sakit", $this->session->userdata('language')),
													'2' => translate("Distributor", $this->session->userdata('language')),
													'3' => translate("Produksi", $this->session->userdata('language'))
													);
							    
								//die_dump($alamat_sub_option);
								
								echo form_dropdown('tipe', $sub_option, $form_data['tipe'], "id=\"tipe\" class=\"form-control \" required=\"required\"");
							?>
						</div>
					</div>
					<div class="form-group">
						<input class="hidden" id="id" value="<?=$form_data['id']?>" name="id">
						<input class="hidden" id="id_alamat" value="<?=$id_alamat;?>" name="id_alamat">
						<label class="control-label col-md-2"><?=translate("Kode Cabang", $this->session->userdata("language"))?> :<span class="required">*</span></label>
						<div class="col-md-2">
							<?php
								$kode_cabang = array(
									"name"			=> "kode",
									"id"			=> "kode",
									"class"			=> "form-control", 
									"placeholder"	=> translate("Kode Cabang", $this->session->userdata("language")), 
									"required"		=> "required",
									"value"			=> $form_data['kode']
								);
								echo form_input($kode_cabang);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("Nama Cabang", $this->session->userdata("language"))?> :<span class="required">*</span></label>
						<div class="col-md-3">
							<?php
								$nama_cabang = array(
									"name"			=> "nama",
									"id"			=> "nama",
									"class"			=> "form-control", 
									"placeholder"	=> translate("Nama Cabang", $this->session->userdata("language")), 
									"required"		=> "required",
									"value"			=> $form_data['nama']
								);
								echo form_input($nama_cabang);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("URL Cabang", $this->session->userdata("language"))?> :<span class="required">*</span></label>
						<div class="col-md-3">
							<?php
								$url_cabang = array(
									"name"			=> "url",
									"id"			=> "url",
									"class"			=> "form-control", 
									"placeholder"	=> translate("URL Cabang", $this->session->userdata("language")), 
									"required"		=> "required",
									"value"			=> $form_data['url']
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
									"rows"			=> "4",
									"value"			=> $form_data['keterangan'] 								
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
												
												echo form_dropdown('sub_alamat', $sub_option, $id_subjek, "id=\"subjek_alamat\" class=\"form-control select2me\" required=\"required\" ");
											?>
											<input type="text" id="input_subjek_alamat" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_subjek_alamat" title="<?=translate('Edit', $this->session->userdata('language'))?>"><i class="fa fa-pencil"></i></a>
												<a class="btn green-haze hidden" id="btn_save_subjek_alamat" title="<?=translate('Save', $this->session->userdata('language'))?>"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_subjek_alamat" title="<?=translate('Cancel', $this->session->userdata('language'))?>"><i class="fa fa-undo"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :<span class="required">*</span></label>
									<div class="col-md-6">
										<?php
											$alamat = array(
												"name"			=> "alamat",
												"id"			=> "alamat",
												"autofocus"			=> true,
												"class"			=> "form-control", 
												"placeholder"	=> translate("Alamat", $this->session->userdata("language")), 
												"required"		=> "required",
												"rows"			=> "4",
												"value"			=> $alamat
											);
											echo form_textarea($alamat);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("RT / RW", $this->session->userdata("language"))?> :</label>
									<div class="col-md-2">
										<?php
											$rt = array(
												"name"			=> "rt",
												"id"			=> "rt",
												"autofocus"			=> true,
												"class"			=> "form-control", 
												"placeholder"	=> translate("RT", $this->session->userdata("language")), 
												"required"		=> "required",
												"value"			=> $rt
											);
											echo form_input($rt);
										?>
									</div>
									<div class="col-md-2">
										<?php
											$rw = array(
												"name"			=> "rw",
												"id"			=> "rw",
												"autofocus"			=> true,
												"class"			=> "form-control", 
												"placeholder"	=> translate("RW", $this->session->userdata("language")), 
												"required"		=> "required",
												"value"			=> $rw
											);
											echo form_input($rw);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kelurahan / Desa", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<div class="input-group">
			                                <input type="text" id="input_kelurahan" class="form-control" disabled="disabled" value="<?=$lokasi[0]['nama_kelurahan']?>">
			                                <input type="" id="input_kode" class="form-control" value="<?=$lokasi[0]['lokasi_kode']?>">
										<span class="input-group-btn">
											<a class="btn btn-primary search_kelurahan" id="btn_cari_kelurahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_{0}" title="<?=translate('Cari', $this->session->userdata('language'))?>" href="<?=base_url()?>master/cabang/search_kelurahan"><i class="fa fa-search"></i></a>
										</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kecamatan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
		                                <input type="text" id="input_kecamatan" class="form-control" disabled="disabled" value="<?=$lokasi[0]['nama_kecamatan']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kota / Kabupaten", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
		                                <input type="text" id="input_kota" class="form-control" disabled="disabled" value="<?=$lokasi[0]['nama_kabupatenkota']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Provinsi", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
		                                <input type="text" id="input_provinsi" class="form-control" disabled="disabled" value="<?=$lokasi[0]['nama_propinsi']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Negara", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
		                                <input type="text" id="input_negara" class="form-control" disabled="disabled" value="Indonesia">
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
												"placeholder"	=> translate("Kode Pos", $this->session->userdata("language")), 
												"value"			=> $kode_pos
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
									$form_phone_edit = array();
									$telp_sub = $this->cabang_m->get_data_sub_telp();
									$telp_sub_option = $telp_sub->result_array();
									$sub_option = array('' => "Pilih Subjek...");
								    foreach ($telp_sub_option as $select) {
								        $sub_option[$select['id']] = $select['nama'];
								    }

								    $records = $customer_phone->result_array();
									//die_dump($records);
									$i = 0;
									foreach ($records as $key=>$data) {

										$primary = "";
										$value = "";
										if ($data['is_prim'] == 1) {
											$primary = "checked";
											$value = 'value="1"';
										}
										$i++;
									//die_dump($alamat_sub_option);
									$form_phone_edit[] = '
									<div id="phone_'.$i.'">
									<div class="form-group">
										<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
										<div class="col-md-5">
											<input class="form-control hidden" id="id'.$i.'" name="phone['.$i.'][id]" placeholder="Id Telepon" value="'.$data['id'].'">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :<span class="required">*</span></label>
										<div class="col-md-5">
											<div class="input-group">
												'.form_dropdown('phone['.$i.'][subjek]', $sub_option, $data['subjek_id'], "id=\"subjek_telepon_$i\" class=\"form-control select2me\" required=\"required\" ").'
												<input type="text" id="input_subjek_telepon_'.$i.'" class="form-control hidden">
												<span class="input-group-btn">
													<a class="btn blue-chambray edit" data-id="'.$i.'" id="btn_edit_subjek_telepon_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
													<a class="btn red-intense del-db" data-id="'.$i.'" id="btn_delete_subjek_telepon_'.$i.'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
													<a class="btn green-haze hidden save" data-id="'.$i.'" id="btn_save_subjek_telepon_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
													<a class="btn yellow hidden cancel" data-id="'.$i.'" id="btn_cancel_subjek_telepon_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :<span class="required">*</span></label>
										<div class="col-md-5">
											<input class="form-control" id="nomer_'.$i.'" name="phone['.$i.'][number]" placeholder="Nomor Telepon" value="'.$data['nomor'].'" required>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4"></label>
										<div class="col-md-5">										
	                                        <input type="hidden" id="primary_id_'.$i.'" name="phone['.$i.'][is_primary]" '.$value.' >
	                                        <div class="radio-list">
												<label class="radio-inline">
												<input type="radio" name="phone_is_primary" id="primary_phone_'.$i.'" data-id="'.$i.'" value="1" '.$primary.'>'.translate('Utama', $this->session->userdata('language')).'</label>
											</div>
		                                    
				                        </div>
										
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
										<div class="col-md-5">
											<input class="form-control hidden" id="is_delete_'.$i.'" name="phone['.$i.'][is_delete]" placeholder="Is Delete">
										</div>
									</div>
									<hr>
									</div>';
									$i++;
								}
								echo '<input class="form-control hidden" value='.$i.' id="i_phone">';
								$form_phone = '
								<div class="form-group">
										<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
										<div class="col-md-5">
											<input class="form-control hidden" id="id{0}" name="phone[{0}][id]" placeholder="Id Telepon">
										</div>
									</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
									<div class="col-md-5">
										<div class="input-group" id="group_edit_{0}">
											'.form_dropdown('phone[{0}][subjek]', $sub_option, '', " id=\"subjek_telepon_{0}\" class=\"form-control select2me\" ").'
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_subjek_telepon_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
												<a class="btn red-intense del-this" id="btn_delete_subjek_telepon_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
											</span>
										</div>
										<div class="input-group hidden" id="group_remove_{0}">
											<input type="text" id="input_subjek_telepon_{0}" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn green-haze hidden" id="btn_save_subjek_telepon_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_subjek_telepon_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
											
											</span>
										
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' : </label>
									<div class="col-md-5">
										<input class="form-control" id="nomer_{0}" name="phone[{0}][number]" placeholder="Nomor Telepon">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"></label>
									<div class="col-md-5">										
                                        <input type="hidden" id="primary_id_{0}" name="phone[{0}][is_primary]" >
                                        <div class="radio-list">
											<label class="radio-inline">
											<input type="radio" name="phone_is_primary" data-id="{0}" id="primary_phone_{0}" value="1">'.translate('Utama', $this->session->userdata('language')).'</label>
										</div>		                             
			                        </div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
									<div class="col-md-5">
										<input class="form-control hidden" id="is_delete_{0}" name="phone[{0}][is_delete]" placeholder="Is Delete">
									</div>
								</div>
								';
								?>
								<?php foreach ($form_phone_edit as $row):?>
	                                <?=$row?>
	                            <?php endforeach;?>
								<input type="hidden" id="tpl-form-phone" value="<?=htmlentities($form_phone)?>">
									<div class="form-body">
										<ul class="list-unstyled">
										</ul>
									</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row <?=$hidden?>" id="tipe_rumah_sakit">
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
						   	$btn_del    = '<div class="text-center"><button type="button" class="btn red del-this" title="Delete Poliklinik"><i class="fa fa-times"></i></button></div>';
						   	$btn_del_db    = '<div class="text-center"><button type="button" class="btn red del-poli-db" title="Delete Poliklinik"><i class="fa fa-times"></i></button></div>';
						    $poliklinik = array(
						        '1' => '',
						        '2' => 'Fax',
						        '3' => 'Home',
						    );
						    $poliklinik_sub = $this->cabang_m->get_data_poliklinik();
							$poliklinik_sub_option = $poliklinik_sub->result_array();
							$sub_option = array('' => "Pilih..");
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

			                $id = ' <div class="col-md-5">
											 	<input type="hidden" class="form-control " id="id{0}" name="poliklinik[{0}][id]" placeholder="Id ">
											</div>';
							 $is_delete = '<div class="form-group">
													<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
													<div class="col-md-5">
														<input type="hidden"class="form-control " id="is_delete_{0}" name="poliklinik[{0}][is_deleted]" placeholder="Is Delete">
													</div>
												</div>';

						    $item_cols = array(
								'poliklinik_subject' => form_dropdown('poliklinik[{0}][subjek]', $sub_option, '', "id=\"poliklinik_subject_{0}\" class=\"form-control\"").$id.$is_delete,
								'poliklinik_waktu'   => $time_range,
								'dokter'             => form_dropdown('poliklinik[{0}][dokter][]', $options, '', "id=\"dokter_{0}\" class=\"form-control	phone_sub\" multiple=\"multiple\""),
								'perawat'            => form_dropdown('poliklinik[{0}][perawat][]', $perawat_options, '', "id=\"perawat_{0}\" class=\"form-control phone\" multiple=\"multiple\""),
								'action'             => $btn_del,
						    );

						    $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

						    //die_dump($records);
						    	$i=0;
						    $records = object_to_array($form_data_poliklinik);
			                if(empty($records)){
			                	$get_row = array();
							}
							else{
								foreach ($records as $key=>$data) {
									
                            		$time_range = '<div class="col-md-8">
			                                <div class="input-group input-large" id="defaultrange">
			                                	<label class="control-label col-md-4">'.translate("Jam Buka", $this->session->userdata("language")).' :</label>
			                                    <div class="input-group">
													<input type="text" class="form-control timepicker timepicker-no-seconds" id="jam_buka_'.$i.'" name="poliklinik['.$i.'][jam_buka]" value="'.$data['jam_buka'].'">
													<span class="input-group-btn">
														<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
													</span>
												</div>
												<label class="control-label col-md-4">'.translate("Jam Tutup", $this->session->userdata("language")).' :</label>
												<div class="input-group">
													<input type="text" class="form-control timepicker timepicker-no-seconds" id="jam_tutup_'.$i.'" name="poliklinik['.$i.'][jam_tutup]" value="'.date('h:i:s A',strtotime($data['jam_tutup'])).'">
													<span class="input-group-btn">
														<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
													</span>
												</div>
			                                </div>
			                            </div>';

			                        $is_delete = '<div class="form-group">
													<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
													<div class="col-md-5">
														<input type="hidden" class="form-control " id="is_delete_'.$i.'" name="poliklinik['.$i.'][is_deleted]" placeholder="Is Delete">
													</div>
												</div>';
									$id = ' <div class="col-md-5">
											 	<input type="hidden" class="form-control " id="id'.$i.'" name="poliklinik['.$i.'][id]" placeholder="Id Telepon" value="'.$data['id'].'">
											</div>';

			                        $dokter = $this->cabang_poliklinik_dokter_m->get_by(array('cabang_poliklinik_id' => $data['id']));
			                        $rec_dokter = object_to_array($dokter);
			                        $array_dokter = array();
			                        foreach ($rec_dokter as $doktor) {
			                        	$array_dokter[$doktor['id']] = $doktor['dokter_id'];
			                        }

			                        $perawat = $this->cabang_poliklinik_perawat_m->get_by(array('cabang_poliklinik_id' => $data['id']));
			                        $rec_perawat = object_to_array($perawat);
			                        $array_perawat = array();
			                        foreach ($rec_perawat as $suster) {
			                        	$array_perawat[$suster['id']] = $suster['perawat_id'];
			                        }
			                        //die_dump($dokter);
						    		// item row column
								    $item_cols = array(
										'poliklinik_subject' => form_dropdown('poliklinik[{0}][subjek]', $sub_option, $data['poliklinik_id'], "id=\"poliklinik_subject_{0}\" class=\"form-control\"").$is_delete.$id,
										'poliklinik_waktu'   => $time_range,
										'dokter'             => form_dropdown('poliklinik[{0}][dokter][]', $options, $array_dokter, "id=\"dokter_{0}\" class=\"form-control	phone_sub\" multiple=\"multiple\""),
										'perawat'            => form_dropdown('poliklinik[{0}][perawat][]', $perawat_options, $array_perawat, "id=\"perawat_{0}\" class=\"form-control phone\" multiple=\"multiple\""),
										'action'             => $btn_del_db,
								    );

								  $item_rows =  '<tr id="item_row_'.$i.'"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
								  $get_row[] = str_replace('{0}', "{$key}", $item_rows);
								  $i++;   
								}
								
							}
								echo '<input class="form-control hidden" value='.$i.' id="tbl_counter">';

						    // gabungkan $item_cols jadi string table row
						   

						?>
						<div class="portlet-body">
							<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-hover table-condensed" id="table_poliklinik_dokter">
								<thead>
								<tr>
									<th class="text-center" width="15%"><?=translate("Poliklinik", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="10%"><?=translate("Waktu Kerja", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="20%"><?=translate("Dokter", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="15%"><?=translate("Perawat", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
								</tr>
								</thead>
								<tbody>
									 <?php foreach ($get_row as $row):?>
	                                    <?=$row?>
	                                <?php endforeach;?>
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