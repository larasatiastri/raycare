<div class="portlet light" id="section-alamat">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Alamat', $this->session->userdata('language'))?></span>
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
					<div id="alamat_'.$i.'">
						<div class="form-group">
							<label class="control-label col-md-4 hidden">'.translate("Id Alamat", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
								<input class="form-control hidden" id="id_'.$i.'" name="alamat['.$i.'][id]" placeholder="Id Alamat" value="'.$data['id'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
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
								<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									<textarea id="alamat_'.$i.'" name="alamat['.$i.'][alamat]" class="form-control" rows="3" placeholder="Alamat Lengkap">'.$data['alamat'].'</textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									<div class="input-group">
										<input type="text" id="rt_'.$i.'" name="alamat['.$i.'][rt]" class="form-control" value="'.$rt.'">
										<span class="input-group-addon">/</span>
										<input type="text" id="rt_'.$i.'" name="alamat['.$i.'][rw]" class="form-control" value="'.$rw.'">
									</div>

								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									<div class="input-group">
										<input type="text" id="input_kelurahan_'.$i.'" name="alamat['.$i.'][kelurahan]" value="'.$nama_kelurahan.'" class="form-control"  readonly>
										<input type="hidden" id="input_kode_'.$i.'" name="alamat['.$i.'][kode]" value="'.$data['kode_lokasi'].'" class="form-control">
										<span class="input-group-btn">
											<a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_'.$i.'" title="'.translate('Cari', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/search_kelurahan/pasien/'.$i.'"><i class="fa fa-search"></i></a>
										</span>
									</div>
								</div>
							</div>

								
			<div id="div_lokasi" class="'.$hidden.'">
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input type="text" id="input_kecamatan_'.$i.'" name="alamat['.$i.'][kecamatan]" value="'.$nama_kecamatan.'" class="form-control" readonly>					
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_kota_'.$i.'" name="alamat['.$i.'][kota]" value="'.$nama_kotkab.'" class="form-control" readonly>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_provinsi_'.$i.'" name="alamat['.$i.'][provinsi]" value="'.$nama_propinsi.'" class="form-control" readonly>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_negara_'.$i.'" name="alamat['.$i.'][negara]" value="Indonesia" class="form-control" readonly>					
						
					</div>
				</div>
				</div>
			
	
			
							<div class="form-group">
								<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
				
										'.form_dropdown('alamat_['.$i.'][negara]', $data_negara_option, $data['negara_id'], "id=\"negara_".$i."\" data-id=\"$i\" class=\"form-control input-sx negara\" disabled").'
										
									
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									
										'.form_dropdown('alamat_['.$i.'][provinsi]', $data_provinsi_option, $data['propinsi_id'], "id=\"provinsi_$i\" data-id=\"$i\" class=\"form-control input-sx provinsi\" disabled").'
										
									
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4">'.translate("Kota", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									
										'.form_dropdown('alama_['.$i.'][kota]', $data_kota_option, $data['kota_id'], "id=\"kota_$i\" data-id=\"$i\" class=\"form-control input-sx kota\" disabled").'
										
									
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									
										'.form_dropdown('alamat_['.$i.'][kecamatan]', $data_kecamatan_option, $data['kecamatan_id'], "id=\"kecamatan_$i\" data-id=\"$i\" class=\"form-control input-sx kecamatan\" disabled").'
										
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									
										'.form_dropdown('alamat_['.$i.'][kelurahan]', $data_kelurahan_option, $data['kelurahan_id'], "id=\"kelurahan_$i\" data-id=\"$i\" class=\"form-control input-sx kelurahan\" disabled").'
										
										
									
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
								<div class="col-md-8">
									<input type="text" name="alamat['.$i.'][kode_pos]" id="kode_pos_'.$i.'" class="form-control" placeholder="Kode Pos" value="'.$data['kode_pos'].'">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4"></label>
								<div class="col-md-4 primary_alamat">
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
					<hr>
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
				<div class="form-group">
					<label class="control-label col-md-4 hidden">'.translate("Id Alamat", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input class="form-control hidden" id="id_{0}" name="alamat[{0}][id]" placeholder="Id Alamat">
					</div>
				</div>
				<div class="form-group">
				<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
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
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<textarea id="alamat_{0}" name="alamat[{0}][alamat]" class="form-control" rows="3" placeholder="Alamat Lengkap"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<div class="input-group">
							<input type="text" id="rt_{0}" name="alamat[{0}][rt]" class="form-control">
							<span class="input-group-addon">/</span>
							<input type="text" id="rt_{0}" name="alamat[{0}][rw]" class="form-control">
						</div>
 
					</div>
				</div>
				<div class="form-group">
				<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div class="input-group">
						<input type="text" id="input_kelurahan_{0}" name="alamat[{0}][kelurahan]" class="form-control" readonly>
						<input type="hidden" id="input_kode_{0}" name="alamat[{0}][kode]" class="form-control">
						<span class="input-group-btn">
							<a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_{0}" title="'.translate('Cari', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/search_kelurahan/pasien/{0}"><i class="fa fa-search"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div id="div_lokasi" class="hidden">
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input type="text" id="input_kecamatan_{0}" name="alamat[{0}][kecamatan]" class="form-control" readonly>					
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_kota_{0}" name="alamat[{0}][kota]" class="form-control" readonly>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_provinsi_{0}" name="alamat[{0}][provinsi]" class="form-control" readonly>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_negara_{0}" name="alamat[{0}][negara]" class="form-control" readonly>					
						
					</div>
				</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input type="text" name="alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control" placeholder="Kode Pos">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4"></label>
					<div class="col-md-4 primary_alamat">
						<input type="hidden" name="alamat[{0}][is_primary_alamat]" id="primary_alamat_id_{0}">
						<input type="radio" name="alamat_is_primary" id="radio_primary_alamat_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'

					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input class="form-control hidden" id="is_delete_{0}" name="alamat[{0}][is_delete]" placeholder="Is Delete">
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
		<div class="form-body">
			<ul class="list-unstyled">
			</ul>
		</div>
	</div>
</div>