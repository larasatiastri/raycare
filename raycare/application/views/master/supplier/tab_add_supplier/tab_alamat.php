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
				<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<div class="input-group">
						<input type="text" id="rt_{0}" name="alamat[{0}][rt]" class="form-control" placeholder="RT" required>
						<span class="input-group-addon">/</span>
						<input type="text" id="rw_{0}" name="alamat[{0}][rw]" class="form-control" placeholder="RW" required>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<div class="input-group">
						<input type="text" id="input_kelurahan_{0}" name="alamat[{0}][kelurahan]" class="form-control" readonly required>
						<input type="hidden" id="input_kode_{0}" name="alamat[{0}][kode]" class="form-control">
						<span class="input-group-btn">
							<a class="btn btn-primary search_alamat" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_{0}" title="'.translate('Cari', $this->session->userdata('language')).'" href="'.base_url().'master/supplier/modal_search_alamat/{0}"><i class="fa fa-search"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div id="div_lokasi_{0}" class="hidden">
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :<span class="required">*</span></label>
					<div class="col-md-8">
						<input type="text" id="input_kecamatan_{0}" name="alamat[{0}][kecamatan]" class="form-control" readonly required>					
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :<span class="required">*</span></label>
					<div class="col-md-8">
						
							<input type="text" id="input_kota_{0}" name="alamat[{0}][kota]" class="form-control" readonly required>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :<span class="required">*</span></label>
					<div class="col-md-8">
						
							<input type="text" id="input_provinsi_{0}" name="alamat[{0}][provinsi]" class="form-control" readonly>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :<span class="required">*</span></label>
					<div class="col-md-8">
						
							<input type="text" id="input_negara_{0}" name="alamat[{0}][negara]" class="form-control" readonly required>					
						
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" name="alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control"  placeholder="Kode Pos" required>
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

		<input type="hidden" id="tpl-form-alamat" value="<?=htmlentities($form_alamat)?>">
		<div class="form-body">
			<ul class="list-unstyled">
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_alamat -->
</div> <!-- end of class="portlet light" tab_alamat -->