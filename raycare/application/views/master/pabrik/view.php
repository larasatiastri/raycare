<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-search font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('View Pabrik', $this->session->userdata('language'))?></span>
		</div>
	</div>

	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_view_pabrik", 
			    "name"          => "form_view_pabrik", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "view",
		        "id"		=> $pk_value,

		    );

		    echo form_open(base_url()."master/pabrik/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');
		?>

		<div class="form-body">
			<div class="row">
				<div class="col-md-6">
					<div class="portlet light bordered">
						<div class="portlet-title">
						<div class="caption">
								<span class="caption-subject"><?=translate('Informasi', $this->session->userdata('language'))?></span>
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
									<label class="control-label col-md-4"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
									<div class="col-md-4">
										
										<label class="control-label"><?=$form_data['kode']?></label>

									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-8">
										
										<label class="control-label"><?=$form_data['nama']?></label>

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
							</div>
						</div>	
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="portlet light bordered" id="section-cp">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate('Contact Person', $this->session->userdata('language'))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<?php

							$get_pabrik_contact_person = $this->pabrik_contact_person_m->get_data_cp_pabrik($form_data['id']);
							$records = $get_pabrik_contact_person->result_array();
							// die_dump($records);
							$i=0;
							foreach ($records as $key => $data) {

								$form_cp_edit[] = '
									<div id="contact_person_'.$i.'">
										<div class="form-group">
											<label class="control-label col-md-4">'.translate("Nama", $this->session->userdata("language")).' :</label>
											<div class="col-md-5">
												<label for="subjek" class="control-label">'.$data['nama'].'</label>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
											<div class="col-md-5">
												<label for="subjek" class="control-label">'.$data['nomor'].'</label>
											</div>
										</div>
									</div>'
								;
									$i++;
								}
								
								//die_dump($alamat_sub_option);
								
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

							<input type="hidden" id="tpl-form-cp" >
							<div class="form-body">
								<ul class="list-unstyled">
								</ul>
							</div>
						</div>
					</div>
				</div>
				
					
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="portlet light bordered" id="section-alamat">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate('Alamat', $this->session->userdata('language'))?></span>
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
								
								$get_pabrik_alamat = $this->pabrik_alamat_m->get_data($form_data['id']);
								$records = $get_pabrik_alamat->result_array();
								// die_dump($records);
								
								$i=0;
								foreach ($records as $key => $data) {
									
									$rt_rw = explode('/', $data['rt_rw']);
									$rt = $rt_rw[0];
									$rw = $rt_rw[1];

									$data_negara = $this->region_m->get_by(array('id' => $data['negara_id'],  'parent' => null));
									$data_negara_array = object_to_array($data_negara);
									
									$negara = "";

								    foreach ($data_negara_array as $select) {
								        $negara = $select['nama'];
								    }

									$data_provinsi = $this->region_m->get_by(array('parent' => $data['negara_id']));
									$data_provinsi_array = object_to_array($data_provinsi);

									$provinsi = "";

								    foreach ($data_provinsi_array as $select) {
								        $provinsi = $select['nama'];
								    }

								    $data_kota = $this->region_m->get_by(array('parent' => $data['propinsi_id']));
									$data_kota_array = object_to_array($data_kota);

									$kota = "";

								    foreach ($data_kota_array as $select) {
								        $kota = $select['nama'];
								    }

								    $data_kecamatan = $this->region_m->get_by(array('parent' => $data['kota_id']));
									$data_kecamatan_array = object_to_array($data_kecamatan);

									$kecamatan = "";

								    foreach ($data_kecamatan_array as $select) {
								        $kecamatan = $select['nama'];
								    }

								    $data_kelurahan = $this->region_m->get_by(array('parent' => $data['kecamatan_id']));
									$data_kelurahan_array = object_to_array($data_kelurahan);

									$kelurahan = "";

								    foreach ($data_kelurahan_array as $select) {
								        $kelurahan = $select['nama'];
								    }
								
								$form_alamat_edit[] = '
									<div id="alamat_'.$i.'">
										<div class="form-group">
											<label class="control-label col-md-4 hidden">'.translate("Id Alamat", $this->session->userdata("language")).' :</label>
											<div class="col-md-5">
												<input class="form-control input-sm hidden" id="id_'.$i.'" name="alamat['.$i.'][id]" placeholder="Id Alamat" value="'.$data['id'].'">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
												<div class="col-md-5">
													<label for="subjek" class="control-label">'.$data['nama'].'</label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
												<div class="col-md-5">
													<label for="alamat" class="control-label">'.$data['alamat'].'</label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("RT/RW", $this->session->userdata("language")).' :</label>
												<div class="col-md-4">
													<label for="RT" class="control-label">'.$rt.' / '.$rw.' </label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
												<div class="col-md-5">
													<label for="negara" class="control-label">'.$negara.'</label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
												<div class="col-md-5">
													<label for="provinsi" class="control-label">'.$provinsi.'</label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Kota", $this->session->userdata("language")).' :</label>
												<div class="col-md-5">
													<label for="kota" class="control-label">'.$kota.'</label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
												<div class="col-md-5">
													<label for="kecamatan" class="control-label">'.$kecamatan.'</label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
												<div class="col-md-5">
													<label for="kelurahan" class="control-label">'.$kelurahan.'</label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
												<div class="col-md-5">
													<label for="kode_pos" class="control-label">'.$data['kode_pos'].'</label>
												</div>
											</div>
						
									</div>'
									;
									$i++;
								}

								
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

							<input type="hidden" id="tpl-form-alamat">
							<div class="form-body">
								<ul class="list-unstyled">
								</ul>
							</div>
						</div>
					</div>



					
				</div>

				<div class="col-md-6">
					<div class="portlet light bordered" id="section-telepon">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate('Telepon', $this->session->userdata('language'))?></span>
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

							    $get_pabrik_telepon = $this->pabrik_telepon_m->get_data_telp_pabrik($form_data['id']);
								$records = $get_pabrik_telepon->result_array();
								// die_dump($records);
								$i=0;
								foreach ($records as $key => $data) {

									$form_phone_edit[] = '
									<div id="phone_'.$i.'">
									<div class="form-group">
										<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
										
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
										<div class="col-md-5">
											<label for="subjek" class="control-label">'.$data['nama'].'</label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
										<div class="col-md-5"><label for="subjek" class="control-label">'.$data['nomor'].'</label>
										</div>
									</div>
									</div>'
									;
									$i++;
								}
								//die_dump($alamat_sub_option);
								
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
				</div>


		</div>

		<?php $msg = translate("Apakah anda yakin akan membuat data Pabrik ini?",$this->session->userdata("language"));?>
		<div class="form-actions right">	
			

				<a class="btn default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
				
		</div>
		<?=form_close()?>
	</div>
</div>




