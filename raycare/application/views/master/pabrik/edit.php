<?php
	$form_attr = array(
	    "id"            => "form_edit_pabrik", 
	    "name"          => "form_edit_pabrik", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "edit",
        'id'		=> $pk_value,
    );

    echo form_open(base_url()."master/pabrik/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-pencil font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Edit Pabrik', $this->session->userdata('language'))?></span>
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
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<?=translate('Informasi', $this->session->userdata('language'))?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Kode", $this->session->userdata("language"))?> :<span class="required">*</span></label>
							<div class="col-md-8">
								<?php
									$kode = array(
										"id"			=> "kode",
										"name"			=> "kode",
										"autofocus"		=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("Kode", $this->session->userdata("language")), 
										"value"			=> $form_data['kode'],
										"required"		=> "required", 
										"help"			=> $flash_form_data['kode'],
									);
									echo form_input($kode);
								?>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> : <span class="required">*</span></label>
							
							<div class="col-md-8">
								<?php
									$fullname = array(
										"id"			=> "nama_lengkap",
										"name"			=> "nama_lengkap",
										"class"			=> "form-control",
										"rows" 			=> "4",
										"placeholder"	=> translate("Nama Lengkap", $this->session->userdata("language")), 
										"value"			=> $form_data['nama'],
										"help"			=> $flash_form_data['nama_lengkap'],
										"required"		=> "required", 
									);
									echo form_textarea($fullname);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Created By", $this->session->userdata("language"))?>:</label>
							<div class="col-md-8">
								<?php $user_create = $this->user_m->get($form_data['created_by']) ?>
								<label class="control-label"> <?=$user_create->nama?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Created Date", $this->session->userdata("language"))?> :</label>
							<div class="col-md-8">
								<label class="control-label"><?=$form_data['created_date']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Modified By", $this->session->userdata("language"))?> :</label>
							<div class="col-md-8">
								<?php 
									if ($form_data['modified_by']) 
									{
										$user_modified = $this->user_m->get($form_data['modified_by']);
										$user_modified = object_to_array($user_modified);
									}
									else {
										$user_modified['nama'] = $form_data['modified_by'];
									}
								?>
								<label class="control-label"><?=$user_modified['nama']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Modified Date", $this->session->userdata("language"))?> :</label>
							<div class="col-md-8">
								<label class="control-label"><?=$form_data['modified_date']?></label>
								<input type="hidden" name="modified_date" value="<?=$form_data['modified_date']?>">
								<a target="_blank" id="open_new_tab" class="btn btn-sm btn-primary hidden" href="<?=base_url()?>master/user_level/edit/<?=$pk_value?>" ><?=translate("Open", $this->session->userdata("language"))?></a>
							</div>
						</div>
					</div><!-- end of <div class="portlet light bordered"> -->
					<div class="portlet light bordered" id="section-cp">
						<div class="portlet-title">
							<div class="caption">
								<?=translate('Contact Person', $this->session->userdata('language'))?>
							</div>
							<div class="actions">
								<a class="btn btn-circle btn-icon-only btn-default add-cp">
					                <i class="fa fa-plus"></i>
					            </a>										
							</div>
						</div>
						<div class="portlet-body">
							<?php
								

								$get_pabrik_contact_person = $this->pabrik_contact_person_m->get_data($form_data['id']);
								$records = $get_pabrik_contact_person->result_array();
								// die_dump($records);

								$i=0;
								foreach ($records as $key => $data) {

									$form_cp_edit[] = '
									<div id="contact_person_'.$i.'">
									<div class="form-group">
										<label class="control-label col-md-4 hidden">'.translate("Id Contact Person", $this->session->userdata("language")).' :</label>
										<div class="col-md-8">
											<input class="form-control hidden" id="id'.$i.'" name="cp['.$i.'][id]" placeholder="Id Contact Person" value="'.$data['id'].'">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Nama", $this->session->userdata("language")).' :</label>
										<div class="col-md-8">
											<div class="input-group">
												<input type="text" id="input_nama_cp_'.$i.'" name="cp['.$i.'][nama_cp]" class="form-control" value="'.$data['nama'].'" placeholder="Nama Contact Person">
												<span class="input-group-btn">
													<a class="btn red-intense del-db-cp" data-id="'.$i.'" id="btn_delete_subjek_cp_'.$i.'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
												</span>
											</div>
											<div class="input-group">
												<input type="text" id="input_nama_cp_'.$i.'" class="form-control hidden">
												<span class="input-group-btn">
													<a class="btn btn-primary hidden" id="btn_save_subjek_cp_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
													<a class="btn yellow hidden" id="btn_cancel_subjek_cp_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
										<div class="col-md-8">
											<input class="form-control" id="nomer_'.$i.'" name="cp['.$i.'][number]" placeholder="Nomor Telepon" value="'.$data['nomor'].'">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
										<div class="col-md-5">
											<input class="form-control hidden" id="is_delete_cp_'.$i.'" name="cp['.$i.'][is_delete_cp]" placeholder="Is Delete">
										</div>
									</div>
									<hr>
									</div>'
									;
									$i++;
								}

								//die_dump($alamat_sub_option);
								$form_cp = '
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Nama", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											<input type="text" id="input_nama_cp_{0}" name="cp[{0}][nama_cp]" class="form-control" placeholder="Nama Contact Person">
											<span class="input-group-btn">
												<a class="btn red-intense del-this" id="btn_delete_subjek_cp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
											</span>
										</div>
										<div class="input-group">
											<input type="text" id="input_nama_cp_hidden_{0}" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn btn-primary hidden" id="btn_save_subjek_cp_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_subjek_cp_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<input class="form-control" id="nomer_{0}" name="cp[{0}][number]" placeholder="Nomor Telepon">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
									<div class="col-md-5">
										<input class="form-control hidden" id="is_delete_cp_{0}" name="cp[{0}][is_delete_cp]" placeholder="Is Delete">
									</div>
								</div>
								';
							?>

							<div class="form-group">
								<label class="control-label col-md-4 hidden"><?=translate("Contact Person Counter", $this->session->userdata("language"))?> :</label>
								<div class="col-md-5">
									<input type="hidden" id="cp_counter" value="<?=$i?>" >
								</div>
							</div>
							
							<?php foreach ($form_cp_edit as $row):?>
                                <?=$row?>
                                
                            <?php endforeach;?>

							<input type="hidden" id="tpl-form-cp" value="<?=htmlentities($form_cp)?>">
							<div class="form-body">
								<ul class="list-unstyled">
								</ul>
							</div>
						</div><!-- end of <div class="portlet-body"> -->
					</div><!-- end of <div class="portlet light bordered" id="section-cp"> -->
					<div class="portlet light bordered" id="section-telepon">
						<div class="portlet-title">
							<div class="caption">
								<?=translate('Telepon', $this->session->userdata('language'))?>
							</div>
							
						</div>
						<div class="portlet-body">
							<?php
								$telp_sub = $this->pabrik_m->get_data_subjek(2);
								$telp_sub_array = $telp_sub->result_array();
								
								$telp_sub_option = array(
									'' => "Pilih..",

								);
							    foreach ($telp_sub_array as $select) {
							        $telp_sub_option[$select['id']] = $select['nama'];
							    }
								//die_dump($alamat_sub_option);

							    $get_pabrik_telepon = $this->pabrik_telepon_m->get_data($form_data['id']);
								$records = $get_pabrik_telepon->result_array();

								// die(dump($records));
								
								$i=0;
								foreach ($records as $key => $data) {

									$form_phone_edit[] = '
									<div id="phone_'.$i.'">
									<div class="form-group">
										<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
										<div class="col-md-5">
											<input class="form-control hidden" id="id'.$i.'" name="phone['.$i.'][id]" placeholder="Id Telepon" value="'.$data['id'].'">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
										<div class="col-md-8">
											<div class="input-group">
												'.form_dropdown('phone['.$i.'][subjek]', $telp_sub_option, $data['subjek_id'], "id=\"subjek_telp_$i\" class=\"form-control\"").'
												<span class="input-group-btn">
													<a class="btn blue-chambray edit" data-id="'.$i.'" id="btn_edit_subjek_telp_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
													<a class="btn red-intense del-db" data-id="'.$i.'" id="btn_delete_subjek_telp_'.$i.'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
												</span>
											</div>
											<div class="input-group">
												<input type="text" id="input_subjek_telp_'.$i.'" class="form-control hidden">
												<span class="input-group-btn">
													<a class="btn btn-primary hidden save" data-id="'.$i.'" id="btn_save_subjek_telp_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
													<a class="btn yellow hidden cancel" data-id="'.$i.'" id="btn_cancel_subjek_telp_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
										<div class="col-md-8">
											<input class="form-control" id="nomer_'.$i.'" name="phone['.$i.'][number]" placeholder="Nomor Telepon" value="'.$data['nomor'].'">
										</div>
									</div>
										
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
										<div class="col-md-8">
											<input class="form-control hidden" id="is_delete_phone_'.$i.'" name="phone['.$i.'][is_delete_phone]" placeholder="Is Delete">
										</div>
									</div>
									<hr>
									</div>'
									;
									$i++;
								} 

							?>

							<div class="form-group">
								<label class="control-label col-md-4 hidden"><?=translate("Phone Counter", $this->session->userdata("language"))?> :</label>
								<div class="col-md-5">
									<input type="hidden" id="phone_counter" value="<?=$i?>" >
								</div>
							</div>
							
							<?php foreach ($form_phone_edit as $row):?>
                                <?=$row?>
                                
                            <?php endforeach;?>

							<input type="hidden" id="tpl-form-phone">
							<div class="form-body">
								<ul class="list-unstyled">
								</ul>
							</div>
						</div>

				</div>
				<div class="col-md-6">
					<div class="portlet light bordered" id="section-alamat">
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
									<div class="col-md-5">
										<input type="hidden" id="counter" value="1">	
									</div>
								</div>
							<?php
								// $telp_sub = $this->cabang_m->get_data_sub_telp();
								// $telp_sub_option = $telp_sub->result_array();
								$alamat_sub = $this->pabrik_m->get_data_subjek(1);
								$alamat_sub_array = $alamat_sub->result_array();
								
								// die_dump($alamat_sub_array);

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
								
								$get_pabrik_alamat = $this->pabrik_alamat_m->get_data($form_data['id']);
								$records = $get_pabrik_alamat->result_array();
								// die_dump($records);
								
								$i=0;
								foreach ($records as $key => $data) {
									
									$rt_rw = explode('/', $data['rt_rw']);
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

									$form_alamat_edit[] = '
									<div id="alamat_'.$i.'">
										<div class="form-group">
											<label class="control-label col-md-4 hidden">'.translate("Id Alamat", $this->session->userdata("language")).' :</label>
											<div class="col-md-5">
												<input class="form-control hidden" id="id_'.$i.'" name="alamat['.$i.'][id]" placeholder="Id Alamat" value="'.$data['id'].'">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<div class="input-group">
														'.form_dropdown('alamat['.$i.'][subjek]', $alamat_sub_option, $data['subjek_id'], "id=\"subjek_alamat_$i\" class=\"select2me form-control subjek_alamat\"").'
														<span class="input-group-btn">
															<a class="btn blue-chambray edit-subjek" data-id="'.$i.'" id="btn_edit_subjek_alamat_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
															<a class="btn red-intense del-db" data-id="'.$i.'" id="btn_delete_subjek_alamat_'.$i.'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
														</span>
													</div>
													<div class="input-group">
														<input type="text" id="input_subjek_alamat_'.$i.'" class="form-control hidden">
														<span class="input-group-btn">
															<a class="btn btn-primary hidden save-subjek" data-id="'.$i.'" id="btn_save_subjek_alamat_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
															<a class="btn yellow hidden cancel-subjek" data-id="'.$i.'" id="btn_cancel_subjek_alamat_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<textarea id="alamat_'.$i.'" name="alamat['.$i.'][alamat]" class="form-control" rows="3" placeholder="Alamat Lengkap">'.$data['alamat'].'</textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<div class="input-group">
														<input type="text" id="rt_'.$i.'" name="alamat['.$i.'][rt]" class="form-control" value="'.$rt.'" placeholder="'.translate("RT", $this->session->userdata("language")).'">
														<span class="input-group-addon">/</span>
														<input type="text" id="rt_'.$i.'" name="alamat['.$i.'][rw]" class="form-control" value="'.$rw.'" placeholder="'.translate("RT", $this->session->userdata("language")).'">
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<div class="input-group">
														'.form_dropdown('alamat['.$i.'][negara]', $data_negara_option, $data['negara_id'], "id=\"negara_$i\" data-id=\"$i\" class=\"form-control negara\"").'
														<span class="input-group-btn">
															<a class="btn blue-chambray edit-negara" data-id="'.$i.'" id="btn_edit_negara_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
														</span>
													</div>
													<div class="input-group">
														<input type="text" id="input_negara_'.$i.'" class="form-control hidden">
														<span class="input-group-btn">
															<a class="btn btn-primary hidden save" data-id="'.$i.'" id="btn_save_negara_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
															<a class="btn yellow hidden cancel" data-id="'.$i.'" id="btn_cancel_negara_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<div class="input-group">
														'.form_dropdown('alamat['.$i.'][provinsi]', $data_provinsi_option, $data['propinsi_id'], "id=\"provinsi_$i\" data-id=\"$i\" class=\"form-control provinsi\"").'
														<span class="input-group-btn">
															<a class="btn blue-chambray edit-provinsi" data-id="'.$i.'" id="btn_edit_provinsi_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
														</span>
													</div>
													<div class="input-group">
														<input type="text" id="input_provinsi_'.$i.'" class="form-control hidden">
														<span class="input-group-btn">
															<a class="btn btn-primary hidden save" data-id="'.$i.'" id="btn_save_provinsi_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
															<a class="btn yellow hidden cancel" data-id="'.$i.'" id="btn_cancel_provinsi_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Kota", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<div class="input-group">
														'.form_dropdown('alamat['.$i.'][kota]', $data_kota_option, $data['kota_id'], "id=\"kota_$i\" data-id=\"$i\" class=\"form-control kota\"").'
														<span class="input-group-btn">
															<a class="btn blue-chambray edit-kota" data-id="'.$i.'" id="btn_edit_kota_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
														</span>
													</div>
													<div class="input-group">
														<input type="text" id="input_kota_'.$i.'" class="form-control hidden">
														<span class="input-group-btn">
															<a class="btn btn-primary hidden save" data-id="'.$i.'" id="btn_save_kota_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
															<a class="btn yellow hidden cancel" data-id="'.$i.'" id="btn_cancel_kota_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<div class="input-group">
														'.form_dropdown('alamat['.$i.'][kecamatan]', $data_kecamatan_option, $data['kecamatan_id'], "id=\"kecamatan_$i\" data-id=\"$i\" class=\"form-control kecamatan\"").'
														<span class="input-group-btn">
															<a class="btn blue-chambray edit-kecamatan" data-id="'.$i.'" id="btn_edit_kecamatan_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
														</span>
													</div>
													<div class="input-group">
														<input type="text" id="input_kecamatan_'.$i.'" class="form-control hidden">
														<span class="input-group-btn">
															<a class="btn btn-primary hidden save" data-id="'.$i.'" id="btn_save_kecamatan_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
															<a class="btn yellow hidden cancel" data-id="'.$i.'" id="btn_cancel_kecamatan_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
												<div class="col-md-8">
													<div class="input-group">
														'.form_dropdown('alamat['.$i.'][kelurahan]', $data_kelurahan_option, $data['kelurahan_id'], "id=\"kelurahan_$i\" data-id=\"$i\" class=\"form-control kelurahan\"").'
														<span class="input-group-btn">
															<a class="btn blue-chambray edit-kelurahan" data-id="'.$i.'" id="btn_edit_kelurahan_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
														</span>
													</div>
													<div class="input-group">
														<input type="text" id="input_kelurahan_'.$i.'" class="form-control hidden">
														<span class="input-group-btn">
															<a class="btn btn-primary hidden save" data-id="'.$i.'" id="btn_save_kelurahan_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
															<a class="btn yellow hidden cancel" data-id="'.$i.'" id="btn_cancel_kelurahan_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
												<div class="col-md-4">
													<input type="text" name="alamat['.$i.'][kode_pos]" id="kode_pos_'.$i.'" class="form-control" placeholder="Kode Pos" value="'.$data['kode_pos'].'">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
												<div class="col-md-5">
													<input class="form-control hidden" id="is_delete_alamat_'.$i.'" name="alamat['.$i.'][is_delete_alamat]" placeholder="Is Delete">
												</div>
											</div>
									<hr>
									</div>'
									;
									$i++;
								}


								$form_alamat = '
								
								<div class="form-group">
								<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											'.form_dropdown('alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_alamat_{0}\" class=\"select2me form-control subjek_alamat\"  ").'
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_subjek_alamat_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
												<a class="btn red-intense del-this" id="btn_delete_subjek_alamat_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
											</span>
										</div>
										<div class="input-group">
											<input type="text" id="input_subjek_alamat_{0}" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn btn-primary hidden" id="btn_save_subjek_alamat_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_subjek_alamat_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<textarea id="alamat_{0}"  name="alamat[{0}][alamat]" class="form-control" rows="3" placeholder="Alamat Lengkap"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											<input type="text" id="rt_{0}" name="alamat[{0}][rt]" class="form-control">
											<span class="input-group-addon">/</span>
											<input type="text" id="rw_{0}" name="alamat[{0}][rw]" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											'.form_dropdown('alamat[{0}][negara]', $data_negara_option, '', "id=\"negara_{0}\" class=\"form-control negara\"").'
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_negara_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
											</span>
										</div>
										<div class="input-group">
											<input type="text" id="input_negara_{0}" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn btn-primary hidden" id="btn_save_negara_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_negara_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											'.form_dropdown('alamat[{0}][provinsi]', $region_option, '', "id=\"provinsi_{0}\" class=\"form-control provinsi\"").'
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_provinsi_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
											</span>
										</div>
										<div class="input-group">
											<input type="text" id="input_provinsi_{0}" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn btn-primary hidden" id="btn_save_provinsi_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_provinsi_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Kota", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											'.form_dropdown('alamat[{0}][kota]', $region_option, '', "id=\"kota_{0}\" class=\"form-control kota\"").'
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_kota_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
											</span>
										</div>
										<div class="input-group">
											<input type="text" id="input_kota_{0}" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn btn-primary hidden" id="btn_save_kota_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_kota_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											'.form_dropdown('alamat[{0}][kecamatan]', $region_option, '', "id=\"kecamatan_{0}\" class=\"form-control kecamatan\"").'
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_kecamatan_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
											</span>
										</div>
										<div class="input-group">
											<input type="text" id="input_kecamatan_{0}" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn btn-primary hidden" id="btn_save_kecamatan_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_kecamatan_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											'.form_dropdown('alamat[{0}][kelurahan]', $region_option, '', "id=\"kelurahan_{0}\" class=\"form-control kelurahan\"").'
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_kelurahan_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
											</span>
										</div>
										<div class="input-group">
											<input type="text" id="input_kelurahan_{0}" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn btn-primary hidden" id="btn_save_kelurahan_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_kelurahan_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
									<div class="col-md-4">
										<input type="text" name="alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control" placeholder="Kode Pos">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
									<div class="col-md-5">
										<input class="form-control hidden" id="is_delete_alamat_{0}" name="alamat[{0}][is_delete_alamat]" placeholder="Is Delete">
									</div>
								</div>
								';
							?>

							<div class="form-group">
								<label class="control-label col-md-4 hidden"><?=translate("Alamat Counter", $this->session->userdata("language"))?> :</label>
								<div class="col-md-5">
									<input type="hidden" id="alamat_counter" value="<?=$i?>" >
								</div>
							</div>

							<?php foreach ($form_alamat_edit as $row):?>
                                <?=$row?>
                                
                            <?php endforeach;?>

							<input type="hidden" id="tpl-form-alamat" value="<?=htmlentities($form_alamat)?>">
							<div class="form-body">
								<ul class="list-unstyled">
								</ul>
							</div>
						</div>
					</div>
					</div>
<!-- </div> -->
				</div>
				
			<!-- </div>	
			</div> -->
		</div><!-- end of <div class="form-body"> -->
		<?=form_close()?>
		<?php $msg = translate("Apakah anda yakin akan membuat data Pabrik ini?",$this->session->userdata("language"));?>
		<div class="form-actions right">	
			<a class="btn default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div><!-- end of <div class="portlet-body form"> -->
</div><!-- end of <div class="portlet light"> -->




