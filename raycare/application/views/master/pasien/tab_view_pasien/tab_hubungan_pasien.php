<div class="portlet light" id="section-hubungan-pasien">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Keluarga Terdekat', $this->session->userdata('language'))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<?php 	
				$get_hubungan_pasien = $this->pasien_hubungan_m->get_by(array('pasien_id' => $form_data['id'], 'is_active' => '1'));
				$data_hubungan_pasien = object_to_array($get_hubungan_pasien);

				$data_negara = $this->region_m->get_by(array('parent' => null));
				$data_negara_array = object_to_array($data_negara);
				
				$data_negara_option = array(
					'' => "Pilih..",
				);
			    foreach ($data_negara_array as $select) {
			        $data_negara_option[$select['id']] = $select['nama'];
			    }

			    $telp_sub = $this->pasien_m->get_data_subjek(2);
				$telp_sub_array = $telp_sub->result_array();
				
				$telp_sub_option = array(
					'' => "Pilih..",

				);
			    foreach ($telp_sub_array as $select) {
			        $telp_sub_option[$select['id']] = $select['nama'];
			    }

				$indexHP = 1;
				
				$nama_pj = '';
				$ktp_pj = '';
				$style = '';
				// print_r($data_hubungan_pasien);
				foreach ($data_hubungan_pasien as $hubungan_pasien) {
					$tipe_hp = '';
					$show_pj = '';
					if ($hubungan_pasien['tipe_hubungan'] == 1) {
						$tipe_hp = '';
						$show_pj = 'hidden';
					}else{
						$tipe_hp = 'hidden';
						$nama_pj = $hubungan_pasien['nama'];
						$ktp_pj = $hubungan_pasien['no_ktp'];
					}

					$get_hp_alamat = $this->pasien_hubungan_alamat_m->get_by(array('pasien_hubungan_id' => $hubungan_pasien['id']));
					$data_hp_alamat = object_to_array($get_hp_alamat);

					// print_r($data_hp_alamat);
					$indexHPAlamat = 1;
					foreach ($data_hp_alamat as $hp_alamat) {

						// die_dump($data_hp_alamat);

						$get_subjek_alamat = $this->subjek_m->get($hp_alamat['subjek_id']);
						$subjek_alamat = object_to_array($get_subjek_alamat);
						
						// print_r($subjek_alamat);
						$telp_sub_option = array(
							'' => "Pilih..",

						);

						$primary = "";
						if ($hp_alamat['is_primary'] == "1") {
							$primary = "checked";
						}
						$rt_rw = explode('/', $hp_alamat['rt_rw']);
						$rt = $rt_rw[0];
						$rw = $rt_rw[1];

						$get_negara = $this->region_m->get_by(array('id' => $hp_alamat['negara_id']));
						$negara_array = object_to_array($get_negara);

						$negara = '';
					    foreach ($negara_array as $select) {
					        $negara = $select['nama'];
					    }

						$get_provinsi = $this->region_m->get_by(array('id' => $hp_alamat['propinsi_id']));
						$provinsi_array = object_to_array($get_provinsi);

						$provinsi = '';
					    foreach ($provinsi_array as $select) {
					        $provinsi = $select['nama'];
					    }

					    $get_kota = $this->region_m->get_by(array('id' => $hp_alamat['kota_id']));
						$kota_array = object_to_array($get_kota);

						$kota = '';
					    foreach ($kota_array as $select) {
					        $kota = $select['nama'];
					    }

					    $get_kecamatan = $this->region_m->get_by(array('id' => $hp_alamat['kecamatan_id']));
						$kecamatan_array = object_to_array($get_kecamatan);

						$kecamatan = '';
					    foreach ($kecamatan_array as $select) {
					        $kecamatan = $select['nama'];
					    }

					    $get_kelurahan = $this->region_m->get_by(array('id' => $hp_alamat['kelurahan_id']));
						$kelurahan_array = object_to_array($get_kelurahan);

						$kelurahan = '';
					    foreach ($kelurahan_array as $select) {
					        $kelurahan = $select['nama'];
					    }

						$form_hp_alamat_edit[] = '
						<div id="'.$indexHP.'_hp_alamat_'.$indexHPAlamat.'">
						<div class="form-group">
						<label class="control-label col-md-3">'.translate("Subjek", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<div class="input-group">
									<label class="control-label">'.$subjek_alamat['nama'].'</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Alamat", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<label class="control-label">'.$hp_alamat['alamat'].'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("RT/RW", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<label class="control-label">'.$rt.'/'.$rw.'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Negara", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<div class="input-group">
									<label class="control-label">'.$negara.'</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<div class="input-group">
									<label class="control-label">'.$provinsi.'</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Kota", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<div class="input-group">
									<label class="control-label">'.$kota.'</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<div class="input-group">
									<label class="control-label">'.$kecamatan.'</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<div class="input-group">
									<label class="control-label">'.$kelurahan.'</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<label class="control-label">'.$hp_alamat['kode_pos'].'</label>
							</div>
						</div>

						<div class="form-group ">
							<label class="control-label col-md-3"></label>
							<div class="col-md-5 '.$indexHP.'_primary_alamat">
								<input type="hidden" disabled name="'.$indexHP.'_hp_alamat['.$indexHPAlamat.'][is_primary_hp_alamat]" id="'.$indexHP.'_primary_hp_alamat_id_'.$indexHPAlamat.'" class="hp_primary_alamat '.$indexHP.'_hp_primary_alamat" value="'.$hp_alamat['is_primary'].'">
								<input type="radio" disabled name="'.$indexHP.'_hp_alamat_is_primary" id="'.$indexHP.'_radio_primary_hp_alamat_id_'.$indexHPAlamat.'" '.$primary.' class="is_primary_hp_alamat" data-row="'.$indexHP.'" data-id="'.$indexHPAlamat.'" style="left: 20px;"> '.translate('Utama', $this->session->userdata('language')).'
							</div>
						</div>
						<hr/>
						</div>
						';
						$indexHPAlamat++;
					}

					// print_r($form_hp_alamat_edit);
					$get_hp_telp = $this->pasien_hubungan_telepon_m->get_by(array('pasien_hubungan_id' => $hubungan_pasien['id']));
					$data_hp_telp = object_to_array($get_hp_telp);

					$indexHPPhone = 1;
					foreach ($data_hp_telp as $hp_telp) {
						$primary = "";
						if ($hp_telp['is_primary'] == "1") {
							$primary = "checked";
						}

						$get_subjek_telp = $this->subjek_m->get($hp_telp['subjek_id']);
						$subjek_telp = object_to_array($get_subjek_telp);

						$form_hp_phone_edit[] = '
						<div id="'.$indexHP.'_hp_telp_'.$indexHPPhone.'">
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Subjek", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<div class="input-group">
									<label class="control-label">'.$subjek_telp['nama'].'</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<label class="control-label">'.$hp_telp['nomor'].'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"></label>
							<div class="col-md-5 '.$indexHP.'_primary_telp"">
								<input type="hidden" name="'.$indexHP.'_hp_phone['.$indexHPPhone.'][is_primary_hp_phone]" id="'.$indexHP.'_primary_hp_phone_id_'.$indexHPPhone.'" class="hp_primary_telp '.$indexHP.'_hp_primary_telp" value="'.$hp_telp['is_primary'].'">
								<input type="radio" disabled name="'.$indexHP.'_hp_phone_is_primary" id="'.$indexHP.'_radio_primary_hp_phone_id_'.$indexHPPhone.'" '.$primary.' class="is_primary_hp_phone" data-row="'.$indexHP.'" data-id="'.$indexHPPhone.'" style="left:20px;"> '.translate('Utama', $this->session->userdata('language')).'
							</div>
						</div>
						<hr/>
						</div>';
					$indexHPPhone++;
					}

					
					
					$form_hubungan_pasien_edit[] = '
					<div id="hubungan_pasien_'.$indexHP.'">
					<div id="group_nama_'.$indexHP.'" >
						<div class="form-group" >
							<label class="control-label col-md-4">'.translate("Nama", $this->session->userdata("language")).' :</label>
							
							<div class="col-md-4 input-group">
								<input type="text" readonly class="form-control send-data" id="hubungan_pasien_nama_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][nama]" value="'.$hubungan_pasien['nama'].'">
								<label class="control-label hidden">'.$hubungan_pasien['nama'].'</label>
								<input type="hidden" class="form-control send-data" id="hubungan_pasien_nama_id_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][id]" value="'.$hubungan_pasien['id'].'">
								<input type="hidden" class="form-control send-data" id="hubungan_pasien_is_delete_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][is_delete]">
								<input type="hidden" class="form-control send-data" id="set_penanggung_jawab_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][set_penanggung_jawab]">
								<span class="input-group-btn">
									<a class="btn btn-primary set_penanggung_jawab hidden" id="set_penanggung_jawab_'.$indexHP.'" data-row="'.$indexHP.'">'.translate("Set Penanggung Jawab", $this->session->userdata("language")).'</a>
									<a class="btn red-intense del_penanggung_jawab hidden" id="del_penanggung_jawab_'.$indexHP.'" data-row="'.$indexHP.'" data-confirm="'.translate("Apa anda yakin ingin menghapus penanggung jawab ini", $this->session->userdata("language")).'">'.translate("Delete Penanggung Jawab", $this->session->userdata("language")).'</a>
									<a class="label label-xs label-success penanggung_jawab '.$show_pj.'" id="penanggung_jawab_'.$indexHP.'" data-row="'.$indexHP.'" style="padding: 4px; border-radius: 0px !important; width: 147px; display: block;">
										'.translate("Penanggung Jawab", $this->session->userdata("language")).'
									</a>
								</span>
								<span class="input-group-btn">
									<a class="btn red-intense del-db-hub-pasien hidden" id="del-db-hub-pasien" title="'.translate('Remove', $this->session->userdata('language')).'" data-confirm="'.translate('Apakah anda yakin ingin menghapus keluarga ini ?', $this->session->userdata('language')).'" data-id="'.$indexHP.'" style="'.$style.'"><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
					</div>
					
					<div id="group_ktp_'.$indexHP.'">
						<div class="form-group" >
							<label class="control-label col-md-4">'.translate("No. KTP", $this->session->userdata("language")).':</label>
							
							<div class="col-md-4 input-group">
								<input type="text" readonly class="form-control send-data" id="hubungan_pasien_ktp_'.$indexHP.'" name="hubungan_pasien['.$indexHP.'][ktp]" value="'.$hubungan_pasien['no_ktp'].'">
							</div>
						</div>
					</div>
					

					<div class="form-group" style="margin-bottom : 30px;">
						<label class="control-label col-md-4">'.translate("Scan KTP", $this->session->userdata("language")).' :</label>
						
						<div class="col-md-4 input-group">
							<input type="hidden" name="hubungan_pasien['.$indexHP.'][url_ktp]" id="hubungan_pasien_url_ktp_'.$indexHP.'" data-id="'.$indexHP.'" class="scan-ktp" value="'.$hubungan_pasien['url_ktp'].'">
							<div id="upload_'.$indexHP.'">
								<div id="drop_'.$indexHP.'">	
									<input type="file" class="hidden" name="upl" id="hubungan_pasien_scan_ktp_'.$indexHP.'" data-url="'.base_url().'upload/upload_photo" multiple />
								</div>

								<ul style="list-style: none; padding-left: 0;">
									<li class="working">
										<div class="thumbnail">
											<a href="'.config_item('site_img_pasien_temp_dir_copy').$hubungan_pasien['url_ktp'].'" target="_blank">
												<img src="'.config_item('site_img_pasien_temp_dir_copy').$hubungan_pasien['url_ktp'].'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
											</a>
										</div>
										<span></span>
									</li>
								</ul>

							</div>
						</div>
					</div>

					<div id="section-hp-alamat">
			            <div class="form-group">
			                <label class="control-label col-md-3"></label>
			                <div class="col-md-6">
				                <div class="portlet">
					                <div class="portlet-title">
					                    <div class="caption">
											<span class="caption-subject font-blue-sharp bold uppercase">'.translate('Alamat', $this->session->userdata('language')).'</span>
										</div>
					                </div>
				                	<div class="portlet-body">
										<div class="form-body">
											';
											foreach ($form_hp_alamat_edit as $row_hp_alamat_edit) {
												$form_hubungan_pasien_edit[] .= $row_hp_alamat_edit;

												// print_r($row_hp_alamat_edit);
											}
											$form_hubungan_pasien_edit[] .= '
											<ul class="list-unstyled hp-alamat">
											</ul>
										</div>
				                	</div>
				                </div>
			                </div>
			            </div>
		        	</div>

		        	<div id="section-hp-phone">
			            <div class="form-group">
			                <label class="control-label col-md-3"></label>
			                <div class="col-md-6">
				                <div class="portlet">
					                <div class="portlet-title">
					                    <div class="caption">
											<span class="caption-subject font-blue-sharp bold uppercase">'.translate('Telepon', $this->session->userdata('language')).'</span>
										</div>
					                </div>
				                	<div class="portlet-body">
										<div class="form-body">
											';
											foreach ($form_hp_phone_edit as $row_hp_phone_edit) {
												$form_hubungan_pasien_edit[] .= $row_hp_phone_edit;
											}
											$form_hubungan_pasien_edit[] .= '
											<ul class="list-unstyled hp-phone">
											</ul>
										</div>
				                	</div>
				                </div>
			                </div>
			            </div>
		        	</div>
		        	</div>';
		        $indexHP++;
		        $form_hp_alamat_edit = array();
		        $form_hp_phone_edit = array();
				}
				
				// echo $indexHPAlamat;
				$form_hubungan_pasien = '
				<div id="hubungan_pasien_{0}">
				<div id="group_nama_{0}" >
					<div class="form-group" >
						<label class="control-label col-md-4">'.translate("Nama", $this->session->userdata("language")).' :</label>
						
						<div class="col-md-4 input-group">
							<input type="text" class="form-control send-data" id="hubungan_pasien_nama_{0}" name="hubungan_pasien[{0}][nama]">
							<input type="hidden" class="form-control send-data" id="set_penanggung_jawab_{0}" name="hubungan_pasien[{0}][set_penanggung_jawab]">
							<span class="input-group-btn">
								<a class="btn btn-primary set_penanggung_jawab" id="set_penanggung_jawab_{0}" data-row="{0}">'.translate("Set Penanggung Jawab", $this->session->userdata("language")).'</a>
								<a class="btn red-intense del_penanggung_jawab hidden" id="del_penanggung_jawab_{0}" data-row="{0}" data-confirm="'.translate("Apa anda yakin ingin menghapus penanggung jawab ini", $this->session->userdata("language")).'">'.translate("Delete Penanggung Jawab", $this->session->userdata("language")).'</a>
								<a class="label label-xs label-success penanggung_jawab hidden" id="penanggung_jawab_{0}" data-row="{0}" style="padding: 4px; border-radius: 0px !important; width: 147px; display: block;">
									'.translate("Penanggung Jawab", $this->session->userdata("language")).'
								</a>
							</span>
							<span class="input-group-btn">
								<a class="btn red-intense del-hub-pasien" id="del-hub-pasien_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				
				<div id="group_ktp_{0}">
					<div class="form-group" >
						<label class="control-label col-md-4">'.translate("No. KTP", $this->session->userdata("language")).':</label>
						
						<div class="col-md-4 input-group">
							<input type="text" class="form-control send-data" id="hubungan_pasien_ktp_{0}" name="hubungan_pasien[{0}][ktp]">
						</div>
					</div>
				</div>
				

				<div class="form-group" style="margin-bottom : 30px;">
					<label class="control-label col-md-4">'.translate("Scan KTP", $this->session->userdata("language")).' :</label>
					
					<div class="col-md-4 input-group">
						<input type="hidden" name="hubungan_pasien[{0}][url_ktp]" id="hubungan_pasien_url_ktp_{0}">
						<div id="upload_{0}">
							<div id="drop_{0}">	
								<input type="file" name="upl" id="hubungan_pasien_scan_ktp_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
							</div>

						<ul>
							<!-- The file uploads will be shown here -->
						</ul>

						</div>
					</div>
				</div>

				<div id="section-hp-alamat">
		            <div class="form-group">
		                <label class="control-label col-md-3"></label>
		                <div class="col-md-6">
			                <div class="portlet">
				                <div class="portlet-title">
				                    <div class="caption">
										<span class="caption-subject font-blue-sharp bold uppercase">'.translate('Alamat', $this->session->userdata('language')).'</span>
									</div>
				                    <div class="actions">
				                        <a  class="btn btn-primary add-hp-alamat">
					                        <i class="fa fa-plus"></i>
					                        <span class="hidden-480"></span>
				                    	</a>
				                	</div>
				                </div>
			                	<div class="portlet-body">
									<div class="form-body">
										<ul class="list-unstyled hp-alamat">
										</ul>
									</div>
			                	</div>
			                </div>
		                </div>
		            </div>
	        	</div>

	        	<div id="section-hp-phone">
		            <div class="form-group">
		                <label class="control-label col-md-3"></label>
		                <div class="col-md-6">
			                <div class="portlet">
				                <div class="portlet-title">
				                    <div class="caption">
										<span class="caption-subject font-blue-sharp bold uppercase">'.translate('Telepon', $this->session->userdata('language')).'</span>
									</div>
				                    <div class="actions">
				                        <a  class="btn btn-primary add-hp-phone">
					                        <i class="fa fa-plus"></i>
					                        <span class="hidden-480"></span>
				                    	</a>
				                	</div>
				                </div>
			                	<div class="portlet-body">
									<div class="form-body">
										<ul class="list-unstyled hp-phone">
										</ul>
									</div>
			                	</div>
			                </div>
		                </div>
		            </div>
	        	</div>
	        	</div>'
				;
			

			$form_hp_alamat = '<div class="form-group">
			<label class="control-label col-md-3">'.translate("Subjek", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<div class="input-group">
						'.form_dropdown('hp_alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_hp_alamat_{0}\" class=\"select2me form-control input-sx subjek_alamat hp_subjek\" required ").'
						<input type="text" id="input_subjek_hp_alamat_{0}" class="form-control hidden send-data input-hp-subjek">
						<input type="hidden" id="hp_alamat_id_{0}" name="hp_alamat[{0}][hp_alamat_id]" class="form-control send-data hp_alamat_id" placeholder="RT">
						<input type="hidden" id="hp_alamat_is_delete{0}" name="hp_alamat[{0}][is_delete]" class="form-control send-data hp_alamat_is_delete" placeholder="'.translate('Is Delete', $this->session->userdata('language')).'">
						
						<span class="input-group-btn">
							<a class="btn btn-xs blue-chambray edit-subjek" id="btn_edit_subjek_hp_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs red-intense del-this delete-subjek" id="btn_delete_subjek_hp_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs green-haze hidden save-subjek" id="btn_save_subjek_hp_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs yellow hidden cancel-subjek" id="btn_cancel_subjek_hp_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("Alamat", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<textarea id="hp_alamat_{0}" required name="hp_alamat[{0}][alamat]" class="form-control send-data hp_alamat" rows="3" placeholder="Alamat Lengkap"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("RT/RW", $this->session->userdata("language")).' :</label>
				<div class="col-md-2">
					<input type="text" id="rt_{0}" name="hp_alamat[{0}][rt]" class="form-control send-data hp_rt" placeholder="RT">
				</div>
				<div class="col-md-2">
					<input type="text" id="rw_{0}" name="hp_alamat[{0}][rw]" class="form-control send-data hp_rw" placeholder="RW">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("Negara", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<div class="input-group">
						'.form_dropdown('hp_alamat[{0}][negara]', $data_negara_option, '', "id=\"hp_negara_{0}\" class=\"form-control input-sx hp_negara send-data\"").'
						<input type="text" id="input_hp_negara_{0}" class="form-control hidden input-negara send-data">
						<span class="input-group-btn">
							<a class="btn btn-xs blue-chambray edit-negara" id="btn_edit_hp_negara_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs green-haze hidden save-negara" id="btn_save_hp_negara_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs yellow hidden cancel-negara" id="btn_cancel_hp_negara_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<div class="input-group">
						'.form_dropdown('hp_alamat[{0}][provinsi]', $region_option, '', "id=\"hp_provinsi_{0}\" class=\"form-control input-sx hp_provinsi send-data\"").'
						<input type="text" id="input_hp_provinsi_{0}" class="form-control hidden input-provinsi send-data">
						<span class="input-group-btn">
							<a class="btn btn-xs blue-chambray edit-provinsi" id="btn_edit_hp_provinsi_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs green-haze save-provinsi hidden" id="btn_save_hp_provinsi_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs yellow hidden cancel-provinsi" id="btn_cancel_hp_provinsi_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("Kota", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<div class="input-group">
						'.form_dropdown('hp_alamat[{0}][kota]', $region_option, '', "id=\"hp_kota_{0}\" class=\"form-control input-sx hp_kota send-data\"").'
						<input type="text" id="input_hp_kota_{0}" class="form-control hidden input-kota send-data">
						<span class="input-group-btn">
							<a class="btn btn-xs blue-chambray edit-kota" id="btn_edit_hp_kota_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs green-haze hidden save-kota" id="btn_save_hp_kota_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs yellow hidden cancel-kota" id="btn_cancel_hp_kota_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<div class="input-group">
						'.form_dropdown('hp_alamat[{0}][kecamatan]', $region_option, '', "id=\"hp_kecamatan_{0}\" class=\"form-control input-sx hp_kecamatan send-data\"").'
						<input type="text" id="input_hp_kecamatan_{0}" class="form-control hidden input-kecamatan send-data">
						<span class="input-group-btn">
							<a class="btn btn-xs blue-chambray edit-kecamatan" id="btn_edit_hp_kecamatan_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs green-haze hidden save-kecamatan" id="btn_save_hp_kecamatan_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs yellow hidden cancel-kecamatan" id="btn_cancel_hp_kecamatan_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<div class="input-group">
						'.form_dropdown('hp_alamat[{0}][kelurahan]', $region_option, '', "id=\"hp_kelurahan_{0}\" class=\"form-control input-sx hp_kelurahan send-data\"").'
						<input type="text" id="input_hp_kelurahan_{0}" class="form-control hidden send-data input-kelurahan">
						<span class="input-group-btn">
							<a class="btn btn-xs blue-chambray edit-kelurahan" id="btn_edit_hp_kelurahan_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs green-haze hidden save-kelurahan" id="btn_save_hp_kelurahan_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs yellow hidden cancel-kelurahan" id="btn_cancel_hp_kelurahan_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<input type="text" name="hp_alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control send-data hp_kode_pos" placeholder="Kode Pos">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3"></label>
				<div class="col-md-5">
					<input type="text" name="hp_alamat[{0}][is_primary_hp_alamat]" id="primary_hp_alamat_id_{0}" class="hp_primary_alamat">
					<input type="radio" name="hp_alamat_is_primary" id="radio_primary_hp_alamat_id_{0}" class="is_primary_hp_alamat"> '.translate('Utama', $this->session->userdata('language')).'
				</div>
			</div>';

			$form_hp_phone = '
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("Subjek", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<div class="input-group">
						'.form_dropdown('hp_phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_hp_telp_{0}\" class=\"form-control input-sx hp_subjek_telp\" required ").'
						<input type="text" id="input_subjek_hp_telp_{0}" class="form-control hidden input-subjek-telp">
						<input type="hidden" id="hp_telp_id_{0}" name="hp_phone[{0}][hp_phone_id]" class="form-control send-data hp_telp_id" placeholder="RT">
						<input type="hidden" id="hp_telp_is_delete_{0}" name="hp_phone[{0}][is_delete]" class="form-control send-data hp_telp_is_delete" placeholder="'.translate('Is Delete', $this->session->userdata('language')).'">
									
						<span class="input-group-btn">
							<a class="btn btn-xs blue-chambray edit-subjek-telp" id="btn_edit_subjek_hp_telp_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs red-intense del-this delete-subjek-telp" id="btn_delete_subjek_hp_telp_{0}" style="height:20px; width:20px;" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs green-haze hidden save-subjek-telp" id="btn_save_subjek_hp_telp_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
							<a class="btn btn-xs yellow hidden cancel-subjek-telp" id="btn_cancel_subjek_hp_telp_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
				<div class="col-md-5">
					<input class="form-control input-sm hp_no_telp" required id="nomer_{0}" name="hp_phone[{0}][number]" placeholder="Nomor Telepon">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"></label>
				<div class="col-md-5">
					<input type="hidden" name="hp_phone[{0}][is_primary_hp_phone]" id="primary_hp_phone_id_{0}" class="hp_primary_telp">
					<input type="radio" name="hp_phone_is_primary" id="radio_primary_hp_phone_id_{0}" class="is_primary_hp_phone"> '.translate('Utama', $this->session->userdata('language')).'
				</div>
			</div>';
		?>

		<input type="hidden" id="tpl-form-hubungan-pasien" value="<?=htmlentities($form_hubungan_pasien)?>">
		<input type="hidden" id="tpl-form-hp-alamat" value="<?=htmlentities($form_hp_alamat)?>">
		<input type="hidden" id="tpl-form-hp-phone" value="<?=htmlentities($form_hp_phone)?>">
		<input type="hidden" id="counterHP" value="<?=$indexHP?>">
		
		<?php foreach ($form_hubungan_pasien_edit as $row):?>
            
            <?=$row?>
            
        <?php endforeach;?>

		<div class="form-body">
			<ul class="list-unstyled hubungan-pasien">
			</ul>
		</div>

		
	</div>
	
</div>

<div class="portlet light" id="section-penanggung-jawab">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Penanggung Jawab', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions hidden">
			<a class="btn btn-primary add-penanggung-jawab">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                    <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>										
		</div>
	</div>
	
	<div class="portlet-body">
		<?php 
				$form_penanggung_jawab = '
				<div id="penanggung_jawab_{0}">
				<div class="form-group" id="group_nama_{0}">
					<label class="control-label col-md-4">'.translate("Nama", $this->session->userdata("language")).' :</label>
					
					<div class="col-md-4 input-group">
						<input type="text" class="form-control send-data" id="penanggung_jawab_nama_{0}" name="penanggung_jawab[{0}][nama]">
						<input type="hidden" class="form-control send-data" id="set_penanggung_jawab_{0}" name="penanggung_jawab[{0}][set_penanggung_jawab]">
						<span class="input-group-btn hidden">
							<a class="btn btn-primary set_penanggung_jawab" id="set_penanggung_jawab_{0}" data-row="{0}">'.translate("Set Penanggung Jawab", $this->session->userdata("language")).'</a>
							<span class="label label-xs label-success penanggung_jawab hidden" id="penanggung_jawab_{0}" data-row="{0}" style="padding: 4px; border-radius: 0px !important; width: 147px; display: block;">
								'.translate("Penanggung Jawab", $this->session->userdata("language")).'
							</span>
						</span>
						<span class="input-group-btn hidden">
							<a class="btn red-intense del-hub-pasien" id="del-hub-pasien" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>

				<div class="form-group" id="group_ktp_{0}">
					<label class="control-label col-md-4">'.translate("No. KTP", $this->session->userdata("language")).':</label>
					
					<div class="col-md-4 input-group">
						<input type="text" class="form-control send-data" id="penanggung_jawab_ktp_{0}" name="penanggung_jawab[{0}][ktp]">
					</div>
				</div>

				<div class="form-group" style="margin-bottom : 30px;">
					<label class="control-label col-md-4">'.translate("Scan KTP", $this->session->userdata("language")).' :</label>
					
					<div class="col-md-4 input-group">
						<input type="hidden" name="penanggung_jawab[{0}][url_ktp]" id="penanggung_jawab_url_ktp_{0}">
						<div id="upload_pj_{0}">
							<div id="drop_pj_{0}">	
								<input type="file" name="upl" id="penanggung_jawab_scan_ktp_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
							</div>

						<ul>
							<!-- The file uploads will be shown here -->
						</ul>

						</div>
					</div>
				</div>

				<div id="section-pj-alamat">
		            <div class="form-group">
		                <label class="control-label col-md-3"></label>
		                <div class="col-md-6">
			                <div class="portlet">
				                <div class="portlet-title">
				                    <div class="caption">
										<span class="caption-subject font-blue-sharp bold uppercase">'.translate('Alamat', $this->session->userdata('language')).'</span>
									</div>
				                    <div class="actions">
				                        <a  class="btn btn-primary add-pj-alamat">
					                        <i class="fa fa-plus"></i>
					                        <span class="hidden-480"></span>
				                    	</a>
				                	</div>
				                </div>
			                	<div class="portlet-body">
									<div class="form-body">
										<ul class="list-unstyled pj-alamat">
										</ul>
									</div>
			                	</div>
			                </div>
		                </div>
		            </div>
	        	</div>

	        	<div id="section-pj-phone">
		            <div class="form-group">
		                <label class="control-label col-md-3"></label>
		                <div class="col-md-6">
			                <div class="portlet">
				                <div class="portlet-title">
				                    <div class="caption">
										<span class="caption-subject font-blue-sharp bold uppercase">'.translate('Telepon', $this->session->userdata('language')).'</span>
									</div>
				                    <div class="actions">
				                        <a  class="btn btn-primary add-pj-phone">
					                        <i class="fa fa-plus"></i>
					                        <span class="hidden-480"></span>
				                    	</a>
				                	</div>
				                </div>
			                	<div class="portlet-body">
									<div class="form-body">
										<ul class="list-unstyled pj-phone">
										</ul>
									</div>
			                	</div>
			                </div>
		                </div>
		            </div>
	        	</div>
	        	</div>'
				;

				$form_pj_alamat = '<div class="form-group">
				<label class="control-label col-md-3">'.translate("Subjek", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<div class="input-group">
							'.form_dropdown('pj_alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_pj_alamat_{0}\" class=\"select2me form-control input-sx subjek_alamat\" required ").'
							<input type="text" id="input_subjek_pj_alamat_{0}" class="form-control hidden send-data">
						
							<span class="input-group-btn">
								<a class="btn btn-xs blue-chambray" id="btn_edit_subjek_pj_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs red-intense del-this" id="btn_delete_subjek_pj_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs green-haze hidden" id="btn_save_subjek_pj_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs yellow hidden" id="btn_cancel_subjek_pj_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("Alamat", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<textarea id="pj_alamat_{0}" required name="pj_alamat[{0}][alamat]" class="form-control send-data" rows="3" placeholder="Alamat Lengkap"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("RT/RW", $this->session->userdata("language")).' :</label>
					<div class="col-md-2">
						<input type="text" id="rt_{0}" name="pj_alamat[{0}][rt]" class="form-control send-data" placeholder="RT">
					</div>
					<div class="col-md-2">
						<input type="text" id="rw_{0}" name="pj_alamat[{0}][rw]" class="form-control send-data" placeholder="RW">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("Negara", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<div class="input-group">
							'.form_dropdown('pj_alamat[{0}][negara]', $data_negara_option, '', "id=\"pj_negara_{0}\" class=\"form-control input-sx pj_negara send-data\"").'
							<input type="text" id="input_pj_negara_{0}" class="form-control hidden send-data">
							<span class="input-group-btn">
								<a class="btn btn-xs blue-chambray" id="btn_edit_pj_negara_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs green-haze hidden" id="btn_save_pj_negara_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs yellow hidden" id="btn_cancel_pj_negara_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<div class="input-group">
							'.form_dropdown('pj_alamat[{0}][provinsi]', $region_option, '', "id=\"pj_provinsi_{0}\" class=\"form-control input-sx pj_provinsi send-data\"").'
							<input type="text" id="input_pj_provinsi_{0}" class="form-control hidden send-data">
							<span class="input-group-btn">
								<a class="btn btn-xs blue-chambray" id="btn_edit_pj_provinsi_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs green-haze hidden" id="btn_save_pj_provinsi_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs yellow hidden" id="btn_cancel_pj_provinsi_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("Kota", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<div class="input-group">
							'.form_dropdown('pj_alamat[{0}][kota]', $region_option, '', "id=\"pj_kota_{0}\" class=\"form-control input-sx pj_kota send-data\"").'
							<input type="text" id="input_pj_kota_{0}" class="form-control hidden send-data">
							<span class="input-group-btn">
								<a class="btn btn-xs blue-chambray" id="btn_edit_pj_kota_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs green-haze hidden" id="btn_save_pj_kota_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs yellow hidden" id="btn_cancel_pj_kota_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<div class="input-group">
							'.form_dropdown('pj_alamat[{0}][kecamatan]', $region_option, '', "id=\"pj_kecamatan_{0}\" class=\"form-control input-sx pj_kecamatan send-data\"").'
							<input type="text" id="input_pj_kecamatan_{0}" class="form-control hidden send-data">
							<span class="input-group-btn">
								<a class="btn btn-xs blue-chambray" id="btn_edit_pj_kecamatan_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs green-haze hidden" id="btn_save_pj_kecamatan_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs yellow hidden" id="btn_cancel_pj_kecamatan_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<div class="input-group">
							'.form_dropdown('pj_alamat[{0}][kelurahan]', $region_option, '', "id=\"pj_kelurahan_{0}\" class=\"form-control input-sx pj_kelurahan send-data\"").'
							<input type="text" id="input_pj_kelurahan_{0}" class="form-control hidden send-data">
							<span class="input-group-btn">
								<a class="btn btn-xs blue-chambray" id="btn_edit_pj_kelurahan_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs green-haze hidden" id="btn_save_pj_kelurahan_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs yellow hidden" id="btn_cancel_pj_kelurahan_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<input type="text" name="pj_alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control send-data" placeholder="Kode Pos">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"></label>
					<div class="col-md-5">
						<input type="hidden" name="pj_alamat[{0}][is_primary_pj_alamat]" id="primary_pj_alamat_id_{0}">
						<input type="radio" name="pj_alamat_is_primary" id="radio_primary_pj_alamat_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
					</div>
				</div>';

				$form_pj_phone = '
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("Subjek", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<div class="input-group">
							'.form_dropdown('pj_phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_pj_telp_{0}\" class=\"form-control input-sx\" required ").'
							<input type="text" id="input_subjek_pj_telp_{0}" class="form-control hidden">
							<span class="input-group-btn">
								<a class="btn btn-xs blue-chambray" id="btn_edit_subjek_pj_telp_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs red-intense del-this" id="btn_delete_subjek_pj_telp_{0}" style="height:20px; width:20px;" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs green-haze hidden" id="btn_save_subjek_pj_telp_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs yellow hidden" id="btn_cancel_subjek_pj_telp_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
					<div class="col-md-5">
						<input class="form-control input-sm" required id="nomer_{0}" name="pj_phone[{0}][number]" placeholder="Nomor Telepon">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"></label>
					<div class="col-md-5">
						<input type="hidden" name="pj_phone[{0}][is_primary_pj_phone]" id="primary_pj_phone_id_{0}">
						<input type="radio" name="pj_phone_is_primary" id="radio_primary_pj_phone_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
					</div>
				</div>';
			?>

		<div class="form-body" id="ul_penanggung_jawab">
			<ul class="list-unstyled" id="ul_penanggung_jawab">
				<li class="fieldset" id="li_penanggung_jawab">
					<div class="form-group">
						<label class="control-label col-md-4">Nama :</label>
						
						<div class="col-md-4 input-group">
							<input type="text" class="form-control send-data" name="penanggung_jawab_edit[2][nama]" value="<?=$nama_pj?>" readonly="readonly" aria-invalid="false">
							<input type="hidden" class="form-control send-data" name="penanggung_jawab_edit[2][set_penanggung_jawab]" readonly="readonly" value="1">
						</div>
					</div>
				
					<div class="form-group">
						<label class="control-label col-md-4">No. KTP:</label>
						
						<div class="col-md-4 input-group">
							<input type="text" class="form-control send-data" name="penanggung_jawab_edit[2][ktp]" value="<?=$ktp_pj?>" readonly="readonly">
						</div>
					</div>
				</li>
			</ul>
		</div>

	
		<input type="hidden" id="tpl-form-penanggung-jawab" value="<?=htmlentities($form_penanggung_jawab)?>">
		<input type="hidden" id="tpl-form-pj-alamat" value="<?=htmlentities($form_pj_alamat)?>">
		<input type="hidden" id="tpl-form-pj-phone" value="<?=htmlentities($form_pj_phone)?>">
		<input type="hidden" id="check_penanggung_jawab" value="0">
		<div class="form-body">
			<ul class="list-unstyled penanggung-jawab">
			
			</ul>
		</div>
	</div>
</div>