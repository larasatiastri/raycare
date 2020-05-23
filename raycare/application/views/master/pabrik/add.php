<?php
	$form_attr = array(
	    "id"            => "form_add_pabrik", 
	    "name"          => "form_add_pabrik", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
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
			<i class="icon-plus font-blue-sharp bold"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Pabrik", $this->session->userdata("language"))?></span>
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
								<?=translate("Informasi", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="form-body">
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
												"value"			=> $flash_form_data['kode'],
												"required"		=> "required", 
												"help"			=> $flash_form_data['kode'],
											);
											echo form_input($kode);
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> :<span class="required">*</span></label>
									
									<div class="col-md-8">
										<?php
											$fullname = array(
												"id"			=> "nama_lengkap",
												"name"			=> "nama_lengkap",
												"class"			=> "form-control",
												"rows" 			=> "4",
												"placeholder"	=> translate("Nama Lengkap", $this->session->userdata("language")), 
												"value"			=> $flash_form_data['nama_lengkap'],
												"help"			=> $flash_form_data['nama_lengkap'],
												"required"		=> "required", 
											);
											echo form_textarea($fullname);
										?>
									</div>
								</div>
							</div>
						</div>	
					</div>
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
										<input type="text" id="input_nama_cp_hidden_{0}" class="form-control hidden">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<input class="form-control" id="nomer_{0}" name="cp[{0}][number]" placeholder="Nomor Telepon">
									</div>
								</div>';
							?>

							<input type="hidden" id="tpl-form-cp" value="<?=htmlentities($form_cp)?>">
							<div class="form-body">
								<ul class="list-unstyled">
								</ul>
							</div>
						</div>
					</div><!-- end of <div class="portlet light bordered" id="section-cp"> -->
					<div class="portlet light bordered" id="section-telepon">
						<div class="portlet-title">
							<div class="caption">
								<?=translate('Telepon', $this->session->userdata('language'))?>
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
									<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											'.form_dropdown('phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_telp_{0}\" class=\"form-control\" ").'
											<span class="input-group-btn">
												<a class="btn blue-chambray" id="btn_edit_subjek_telp_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
												<a class="btn red-intense del-this" id="btn_delete_subjek_telp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
											</span>
										</div>
										<div class="input-group">
											<input type="text" id="input_subjek_telp_{0}" class="form-control hidden">
											<span class="input-group-btn">
												<a class="btn btn-primary hidden" id="btn_save_subjek_telp_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
												<a class="btn yellow hidden" id="btn_cancel_subjek_telp_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<input class="form-control" id="nomer_{0}" name="phone[{0}][number]" placeholder="Nomor Telepon">
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
										<textarea id="alamat_{0}"  name="alamat[{0}][alamat]" class="form-control" rows="3" placeholder="'.translate("Alamat Lengkap", $this->session->userdata("language")).'"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
									<div class="col-md-8">
										<div class="input-group">
											<input type="text" id="rt_{0}" name="alamat[{0}][rt]" class="form-control" placeholder="'.translate("RT", $this->session->userdata("language")).'">
											<span class="input-group-addon">/</span>
											<input type="text" id="rw_{0}" name="alamat[{0}][rw]" class="form-control" placeholder="'.translate("RW", $this->session->userdata("language")).'">
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
								</div>';
							?>

							<input type="hidden" id="tpl-form-alamat" value="<?=htmlentities($form_alamat)?>">
							<div class="form-body">
								<ul class="list-unstyled">
								</ul>
							</div>
						</div>
					</div>


				</div><!-- end of <div class="col-md-6"> -->
				
					
			</div>

			<div class="row">
				<div class="col-md-6">



					
				</div>

				<div class="col-md-6">

				</div>


		</div>


		<?=form_close()?>
	</div>
	<?php $msg = translate("Apakah anda yakin akan membuat data Pabrik ini?",$this->session->userdata("language"));?>
		<div class="form-actions right">	
			<a class="btn default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
</div>




