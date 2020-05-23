<div class="portlet light" id="section-alamat">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Alamat', $this->session->userdata('language'))?></span>
		</div>
	</div>
	<div class="portlet-body">

			<div class="form-group">
				<label class="control-label col-md-4 hidden"><?=translate("Counter", $this->session->userdata("language"))?> :</label>
				<div class="col-md-3">
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
			
			$i=0;
			foreach ($records as $key => $data) {
				$primary = "";
				if ($data['is_primary'] == "1") {
					$primary = "checked";
				}
				$rt_rw = explode('/', $data['rt_rw']);
				$rt = $rt_rw[0];
				$rw = $rt_rw[1];

				$get_negara = $this->region_m->get_by(array('id' => $data['negara_id']));
				$negara_array = object_to_array($get_negara);

				$negara = '';
				foreach ($negara_array as $select) {
			        $negara = $select['nama'];
			    }


				$data_provinsi = $this->region_m->get_by(array('id' => $data['propinsi_id']));
				$data_provinsi_array = object_to_array($data_provinsi);

				$provinsi = '';
			    foreach ($data_provinsi_array as $select) {
			        $provinsi = $select['nama'];
			    }

			    $data_kota = $this->region_m->get_by(array('id' => $data['kota_id']));
				$data_kota_array = object_to_array($data_kota);

				$kota = '';
			    foreach ($data_kota_array as $select) {
			        $kota = $select['nama'];
			    }

			    $data_kecamatan = $this->region_m->get_by(array('id' => $data['kecamatan_id']));
				$data_kecamatan_array = object_to_array($data_kecamatan);

				$kecamatan = '';
			    foreach ($data_kecamatan_array as $select) {
			        $kecamatan = $select['nama'];
			    }

			    $data_kelurahan = $this->region_m->get_by(array('id' => $data['kelurahan_id']));
				$data_kelurahan_array = object_to_array($data_kelurahan);

				$kelurahan = '';

			    foreach ($data_kelurahan_array as $select) {
			        $kelurahan = $select['nama'];
			    }

				$form_alamat_edit[] = '
				<div id="alamat_'.$i.'">
					<div class="form-group">
						<label class="control-label col-md-4 hidden">'.translate("Id Alamat", $this->session->userdata("language")).' :</label>
						<div class="col-md-3">
							<input class="form-control input-sm hidden" id="id_'.$i.'" name="alamat['.$i.'][id]" placeholder="Id Alamat" value="'.$data['id'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<label class="control-label">'.$data['nama'].'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<label class="control-label">'.$data['alamat'].'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("RT/RW", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<label class="control-label">'.$rt.'/'.$rw.'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<label class="control-label">'.$negara.'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<label class="control-label">'.$provinsi.'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Kota", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">	
								<label class="control-label">'.$kota.'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<label class="control-label">'.$kecamatan.'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<label class="control-label">'.$kelurahan.'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<label class="control-label">'.$data['kode_pos'].'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"></label>
							<div class="col-md-4 primary_alamat">
								<input type="hidden" value="'.$data['is_primary'].'" name="alamat['.$i.'][is_primary_alamat]" id="primary_alamat_id_'.$i.'">
								<input type="radio" disabled data-id="'.$i.'" name="alamat_is_primary" '.$primary.' id="radio_primary_alamat_id_'.$i.'" style="left:20px;"> '.translate('Utama', $this->session->userdata('language')).'
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<input class="form-control input-sm hidden" id="is_delete_alamat_'.$i.'" name="alamat['.$i.'][is_delete]" placeholder="Is Delete">
							</div>
						</div>
				<hr>
				</div>'
				;
				$i++;
			}

			$form_alamat = '
				<div class="form-group">
					<label class="control-label col-md-4 hidden">'.translate("Id Alamat", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						<input class="form-control input-sm hidden" id="id_{0}" name="alamat[{0}][id]" placeholder="Id Alamat">
					</div>
				</div>
				<div class="form-group">
				<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						'.form_dropdown('alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_alamat_{0}\" class=\"form-control input-sx subjek_alamat\"").'
						<input type="text" id="input_subjek_alamat_{0}" class="form-control hidden">
					</div>
					<span class="input-group-btn" style="left:-15px;">
						<a class="btn btn-xs blue-chambray" id="btn_edit_subjek_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs red-intense del-this" id="btn_delete_subjek_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs green-haze hidden" id="btn_save_subjek_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs yellow hidden" id="btn_cancel_subjek_alamat_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
					</span>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						<textarea id="alamat_{0}" name="alamat[{0}][alamat]" class="form-control" rows="3" placeholder="Alamat Lengkap"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("RT/RW", $this->session->userdata("language")).' :</label>
					<div class="col-md-2">
						<input type="text" id="rt_{0}" name="alamat[{0}][rt]" class="form-control">
					</div>
					<div class="col-md-1" style="padding-left: 0px !important;
												 padding-right: 0px !important;
												 height: 20px;
												 width: 0.33333333%;">
						<span style="display: table-cell;
									 vertical-align: middle;
									 height: 20px;">/</span>
					</div>
					<div class="col-md-2">
						<input type="text" id="rt_{0}" name="alamat[{0}][rw]" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						'.form_dropdown('alamat[{0}][negara]', $data_negara_option, '', "id=\"negara_{0}\" class=\"form-control input-sx negara\"").'
						<input type="text" id="input_negara_{0}" class="form-control hidden">
					</div>
					<span class="input-group-btn" style="left:-15px;">
						<a class="btn btn-xs blue-chambray" id="btn_edit_negara_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs green-haze hidden" id="btn_save_negara_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs yellow hidden" id="btn_cancel_negara_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
					</span>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						'.form_dropdown('alamat[{0}][provinsi]', $region_option, '', "id=\"provinsi_{0}\" class=\"form-control input-sx provinsi\"").'
						<input type="text" id="input_provinsi_{0}" class="form-control hidden">
					</div>
					<span class="input-group-btn" style="left:-15px;">
						<a class="btn btn-xs blue-chambray" id="btn_edit_provinsi_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs green-haze hidden" id="btn_save_provinsi_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs yellow hidden" id="btn_cancel_provinsi_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
					</span>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kota", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						'.form_dropdown('alamat[{0}][kota]', $region_option, '', "id=\"kota_{0}\" class=\"form-control input-sx kota\"").'
						<input type="text" id="input_kota_{0}" class="form-control hidden">
					</div>
					<span class="input-group-btn" style="left:-15px;">
						<a class="btn btn-xs blue-chambray" id="btn_edit_kota_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs green-haze hidden" id="btn_save_kota_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs yellow hidden" id="btn_cancel_kota_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
					</span>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						'.form_dropdown('alamat[{0}][kecamatan]', $region_option, '', "id=\"kecamatan_{0}\" class=\"form-control input-sx kecamatan\"").'
						<input type="text" id="input_kecamatan_{0}" class="form-control hidden">
					</div>
					<span class="input-group-btn" style="left:-15px;">
						<a class="btn btn-xs blue-chambray" id="btn_edit_kecamatan_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs green-haze hidden" id="btn_save_kecamatan_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs yellow hidden" id="btn_cancel_kecamatan_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
					</span>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						'.form_dropdown('alamat[{0}][kelurahan]', $region_option, '', "id=\"kelurahan_{0}\" class=\"form-control input-sx kelurahan\"").'
						<input type="text" id="input_kelurahan_{0}" class="form-control hidden">
					</div>
					<span class="input-group-btn" style="left:-15px;">
						<a class="btn btn-xs blue-chambray" id="btn_edit_kelurahan_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs green-haze hidden" id="btn_save_kelurahan_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
						<a class="btn btn-xs yellow hidden" id="btn_cancel_kelurahan_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
					</span>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						<input type="text" name="alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control" placeholder="Kode Pos">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4"></label>
					<div class="col-md-4 primary_alamat">
						<input type="hidden" name="alamat[{0}][is_primary_alamat]" id="primary_alamat_id_{0}">
						<input type="radio" name="alamat_is_primary" id="radio_primary_alamat_id_{0}" style="left:20px;"> '.translate('Utama', $this->session->userdata('language')).'

					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						<input class="form-control input-sm hidden" id="is_delete_{0}" name="alamat[{0}][is_delete]" placeholder="Is Delete">
					</div>
				</div>
				';
		?>
		
		<div class="form-group">
			<label class="control-label col-md-4 hidden"><?=translate("Alamat Counter", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
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