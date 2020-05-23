<div class="portlet light" id="section-alamat"><!-- begin of class="portlet light" tab_alamat -->
	<div class="portlet-title">
		<div class="caption">
			<span><?=translate('Alamat', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-icon-only btn-default add-alamat">
                <i class="fa fa-plus"></i>
            </a>										
		</div>
	</div>
	<div class="portlet-body"> <!-- begin of class="portlet-body" tab_alamat -->

			<div class="form-group">
				<label class="control-label col-md-4 hidden"><?=translate("Counter", $this->session->userdata("language"))?> :</label>
				<div class="col-md-5">
					<input type="hidden" id="counter" value="1">	
				</div>
			</div>

		<?php
			$alamat_sub = $this->supplier_m->get_data_subjek(1);
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
		    
		    $get_data_supplier_alamat = $this->supplier_alamat_m->get_by(array('supplier_id' => $form_data['id'], 'is_active' => 1));
		    $data_supplier_alamat = object_to_array($get_data_supplier_alamat);
		    // die_dump($this->db->last_query());
		    $form_alamat_edit = array();
		    $i = 1;
		    foreach ($data_supplier_alamat as $data) {
		    	// Begin of alamat form < //
		    	$is_primary_alamat = '';
		    	if ($data['is_primary'] == 1) {
		    		$is_primary_alamat = 'checked';
		    	}

		    	$rt_rw = explode('/', $data['rt_rw']);
				$rt = $rt_rw[0];
				$rw = $rt_rw[1];

				$kelurahan = '';
				$kecamatan = '';
				$kotakab = '';
				$provinsi = '';


				$data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $data['kode_lokasi']));
				if(count($data_lokasi)){
					$data_lokasi = object_to_array($data_lokasi);

					$kelurahan = $data_lokasi[0]['nama_kelurahan'];
					$kecamatan = $data_lokasi[0]['nama_kecamatan'];
					$kotakab = $data_lokasi[0]['nama_kabupatenkota'];
					$provinsi = $data_lokasi[0]['nama_propinsi'];
				}

				$form_alamat_edit[] = '
				<li id="alamat_'.$i.'" class="fieldset">
					<div class="form-group">
					<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div id="subjek_alamat_'.$i.'" class="input-group">
								'.form_dropdown('alamat['.$i.'][subjek]', $alamat_sub_option, $data['subjek_alamat_id'], 'id="subjek_alamat_'.$i.'" class="form-control input-sx subjek_alamat" required ').'
							
								<span class="input-group-btn">
									<a class="btn blue-chambray edit-subjek" id="btn_edit_subjek_alamat_'.$i.'" data-id="'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn red-intense del-db" id="btn_delete_subjek_alamat_'.$i.'" data-id="'.$i.'" title="'.translate('Remove', $this->session->userdata('language')).'" data-confirm="'.translate('Apakah anda yakin ingin menghapus alamat ini ?', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
								</span>
							</div>

							<div id="subjek_alamat_hidden_'.$i.'" class="input-group hidden">
								<input type="text" id="input_subjek_alamat_'.$i.'" class="form-control">
								<span class="input-group-btn">
									<a class="btn green-haze" id="btn_save_subjek_alamat_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow" id="btn_cancel_subjek_alamat_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :<span class="required">*</span></label>
						<div class="col-md-8">
							<textarea id="alamat_'.$i.'" required name="alamat['.$i.'][alamat]" class="form-control" rows="3" placeholder="'.translate('Alamat Lengkap', $this->session->userdata('language')).'">'.$data['alamat'].'</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("RT/RW", $this->session->userdata("language")).' :<span class="required">*</span></label>
						<div class="col-md-8">
							<div class="input-group">
								<input type="text" id="rt_'.$i.'" name="alamat['.$i.'][rt]" class="form-control" placeholder="'.translate('RT', $this->session->userdata('language')).'" value="'.$rt.'" required>
								<span class="input-group-addon">/</span>
								<input type="text" id="rw_'.$i.'" name="alamat['.$i.'][rw]" class="form-control" placeholder="'.translate('RW', $this->session->userdata('language')).'" value="'.$rw.'" required>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :<span class="required">*</span></label>
						<div class="col-md-8">
							<div class="input-group">
								<input type="text" id="input_kelurahan_'.$i.'" name="alamat['.$i.'][kelurahan]" class="form-control" readonly value="'.$kelurahan.'" required>
								<input type="hidden" id="input_kode_'.$i.'" name="alamat['.$i.'][kode]" class="form-control" value="'.$data['kode_lokasi'].'" required>
								<span class="input-group-btn">
									<a class="btn btn-primary search_alamat" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_'.$i.'" title="'.translate('Cari', $this->session->userdata('language')).'" href="'.base_url().'master/supplier/modal_search_alamat/'.$i.'"><i class="fa fa-search"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div id="div_lokasi_'.$i.'">
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :<span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" id="input_kecamatan_'.$i.'" name="alamat['.$i.'][kecamatan]" class="form-control" readonly value="'.$kecamatan.'">					
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :<span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" id="input_kota_'.$i.'" name="alamat['.$i.'][kota]" class="form-control" readonly value="'.$kotakab.'" required>					
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :<span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" id="input_provinsi_'.$i.'" name="alamat['.$i.'][provinsi]" class="form-control" readonly value="'.$provinsi.'" required>					
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
								<input type="text" id="input_negara_'.$i.'" name="alamat['.$i.'][negara]" class="form-control" readonly value="Indonesia" required>					
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :<span class="required">*</span></label>
						<div class="col-md-8">
							<input type="text" name="alamat['.$i.'][kode_pos]" id="kode_pos_'.$i.'" class="form-control" placeholder="'.translate('Kode Pos', $this->session->userdata('language')).'" value="'.$data['kode_pos'].'" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"></label>
						<div class="col-md-8">
							<input type="hidden" name="alamat['.$i.'][supplier_alamat_id]" id="supplier_alamat_id_'.$i.'" value="'.$data['id'].'">
							<input type="hidden" name="alamat['.$i.'][is_delete]" id="is_delete_alamat_'.$i.'">
							<input type="hidden" name="alamat['.$i.'][is_primary_alamat]" id="primary_alamat_id_'.$i.'" value="'.$data['is_primary'].'">
							<label><input type="radio" '.$is_primary_alamat.' name="alamat_is_primary" id="radio_primary_alamat_id_'.$i.'" data-id="'.$i.'"> '.translate('Utama', $this->session->userdata('language')).'</label>
						</div>
					</div>
					<hr/>
				</li>';
					// End of alamat form > //
				$i++;
		    }
		    // Begin of alamat form < //
			$form_alamat = '
			<div class="form-group">
			<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<div class="input-group">
						'.form_dropdown('alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_alamat_{0}\" class=\"select2me form-control subjek_alamat\" required ").'
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
				<label class="control-label col-md-4">'.translate("Alamat Lengkap", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<textarea id="alamat_{0}" name="alamat[{0}][alamat]" class="form-control" rows="3" placeholder="'.translate("Alamat Lengkap", $this->session->userdata("language")).'" required></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div class="input-group">
						<input type="text" id="rt_{0}" name="alamat[{0}][rt]" class="form-control" placeholder="RT">
						<span class="input-group-addon">/</span>
						<input type="text" id="rw_{0}" name="alamat[{0}][rw]" class="form-control" placeholder="RW">
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
							<a class="btn btn-primary search_alamat" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_{0}" title="'.translate('Cari', $this->session->userdata('language')).'" href="'.base_url().'master/supplier/modal_search_alamat/{0}"><i class="fa fa-search"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div id="div_lokasi_{0}" class="hidden">
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
					<input type="text" name="alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control"  placeholder="Kode Pos">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4"></label>
				<div class="col-md-8">
					<input type="hidden" name="alamat[{0}][is_primary_alamat]" id="primary_alamat_id_{0}">
					<label><input type="radio" name="alamat_is_primary" id="radio_primary_alamat_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'</label>
				</div>
			</div>
			';

			// End of alamat form > //
		?>

		<div class="form-group">
			<label class="control-label col-md-4 hidden"><?=translate("Alamat Counter", $this->session->userdata("language"))?> :</label>
			<div class="col-md-8">
				<input type="hidden" id="alamat_counter" value="<?=$i?>" >
			</div>
		</div>

		<input type="hidden" id="tpl-form-alamat" value="<?=htmlentities($form_alamat)?>">
		<div class="form-body">
			<ul class="list-unstyled">
				<?php foreach ($form_alamat_edit as $row):?>
		            <?=$row?>
		        <?php endforeach;?>
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_alamat -->
</div> <!-- end of class="portlet light" tab_alamat -->