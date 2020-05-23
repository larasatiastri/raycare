<div class="portlet light bordered" id="section-hubungan-pasien">
	<div class="portlet-title">
		<div class="caption">
			<?=translate('Keluarga Terdekat', $this->session->userdata('language'))?>
		</div>
		<div class="actions">
			<a class="btn btn-icon-only btn-circle btn-default add-hubungan-pasien">
                <i class="fa fa-plus"></i>
            </a>										
		</div>
	</div>
	<div class="portlet-body">
		<?php 
				$form_hubungan_pasien = '
				<div id="hubungan_pasien_{0}">
				<div id="group_nama_{0}" >
					<div class="form-group" >
						<label class="control-label col-md-4">'.translate("Nama", $this->session->userdata("language")).' :<span class="required">*</span></label>
						
						<div class="col-md-8">
							<div class="input-group">
								<input type="text" class="form-control send-data" id="hubungan_pasien_nama_{0}" name="hubungan_pasien[{0}][nama]" required>
								<input type="hidden" class="form-control send-data" id="set_penanggung_jawab_{0}" name="hubungan_pasien[{0}][set_penanggung_jawab]">
								<span class="input-group-btn">
									<a class="btn btn-primary set_penanggung_jawab" id="set_penanggung_jawab_{0}" data-row="{0}">'.translate("Set PJ", $this->session->userdata("language")).'</a>
									<a class="btn red-intense del_penanggung_jawab hidden" id="del_penanggung_jawab_{0}" data-row="{0}" data-confirm="'.translate("Apa anda yakin ingin menghapus penanggung jawab ini", $this->session->userdata("language")).'">'.translate("Delete Penanggung Jawab", $this->session->userdata("language")).'</a>
									<a class="btn btn-success penanggung_jawab hidden" id="penanggung_jawab_{0}" data-row="{0}">
										'.translate("Penanggung Jawab", $this->session->userdata("language")).'
									</a>
									<a class="btn red-intense del-hub-pasien" id="del-hub-pasien" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
								</span>
							</div>	
						</div>
					</div>
				</div>
				
				<div id="group_ktp_{0}">
					<div class="form-group" >
						<label class="control-label col-md-4">'.translate("No. KTP", $this->session->userdata("language")).' :<span class="required">*</span></label>
						
						<div class="col-md-8">
							<input type="text" class="form-control send-data" id="hubungan_pasien_ktp_{0}" name="hubungan_pasien[{0}][ktp]" required>
						</div>
					</div>
				</div>
				

				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Scan KTP", $this->session->userdata("language")).' :</label>
					
					<div class="col-md-8">
						<input type="hidden" name="hubungan_pasien[{0}][url_ktp]" id="hubungan_pasien_url_ktp_{0}">
						<div id="upload_{0}">
							<div id="drop_{0}">	
								<span class="btn default btn-file">
									<span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>	
									<input type="file" name="upl" id="hubungan_pasien_scan_ktp_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
								</span>
							</div>

						<ul class="ul-img">
							<!-- The file uploads will be shown here -->
						</ul>

						</div>
					</div>
				</div>

				<div id="section-hp-alamat">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="portlet">
				                <div class="portlet-title">
				                    <div class="caption">
										'.translate('Alamat', $this->session->userdata('language')).'
									</div>
				                    <div class="actions">
				                        <a  class="btn btn-circle btn-icon-only btn-default add-hp-alamat">
					                        <i class="fa fa-plus"></i>
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
		                <div class="col-md-12">
			                <div class="portlet">
				                <div class="portlet-title">
				                    <div class="caption">
										'.translate('Telepon', $this->session->userdata('language')).'
									</div>
				                    <div class="actions">
				                        <a class="btn btn-icon-only btn-circle btn-default add-hp-phone">
					                        <i class="fa fa-plus"></i>
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
			<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div class="input-group">
						'.form_dropdown('hp_alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_hp_alamat_{0}\" class=\"select2me form-control subjek_alamat hp_subjek\" required ").'
						<input type="text" id="input_subjek_hp_alamat_{0}" class="form-control hidden send-data input-hp-subjek">
					
						<span class="input-group-btn">
							<a class="btn blue-chambray edit-subjek" id="btn_edit_subjek_hp_alamat_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
							<a class="btn red-intense del-this delete-subjek" id="btn_delete_subjek_hp_alamat_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
							<a class="btn btn-primary hidden save-subjek" id="btn_save_subjek_hp_alamat_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
							<a class="btn yellow hidden cancel-subjek" id="btn_cancel_subjek_hp_alamat_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<textarea id="hp_alamat_{0}" required name="hp_alamat[{0}][alamat]" class="form-control send-data hp_alamat" rows="3" placeholder="Alamat Lengkap"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("RT / RW", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div class="input-group">
						<input type="text" id="rt_{0}" name="hp_alamat[{0}][rt]" class="form-control send-data hp_rt" placeholder="RT">
						<span class="input-group-addon">/</span>
						<input type="text" id="rw_{0}" name="hp_alamat[{0}][rw]" class="form-control send-data hp_rw" placeholder="RW">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div class="input-group">
						<input type="text" id="input_hp_kelurahan_{0}" name="hp_alamat[{0}][kelurahan]" class="form-control" readonly>
						<input type="hidden" id="input_hp_kode_{0}" name="hp_alamat[{0}][kode]" class="form-control hp_kode">
						<span class="input-group-btn">
							<a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_{0}" title="'.translate('Cari', $this->session->userdata('language')).'" href="<?=base_url()?>master/pasien/modal/search_kelurahan"><i class="fa fa-search"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div id="div_hp_lokasi" class="hidden">
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input type="text" id="input_hp_kecamatan_{0}" name="hp_alamat[{0}][kecamatan]" class="form-control" readonly>					
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_hp_kota_{0}" name="hp_alamat[{0}][kota]" class="form-control" readonly>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_hp_provinsi_{0}" name="hp_alamat[{0}][provinsi]" class="form-control" readonly>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_hp_negara_{0}" name="hp_alamat[{0}][negara]" class="form-control" readonly>					
						
					</div>
				</div>
				</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<input type="text" name="hp_alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control send-data hp_kode_pos" placeholder="Kode Pos">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-4"></label>
				<div class="col-md-8">
					<input type="hidden" name="hp_alamat[{0}][is_primary_hp_alamat]" id="primary_hp_alamat_id_{0}" class="hp_primary_alamat">
					<input type="radio" name="hp_alamat_is_primary" id="radio_primary_hp_alamat_id_{0}" class="is_primary_hp_alamat"> '.translate('Utama', $this->session->userdata('language')).'
				</div>
			</div>';

			$form_hp_phone = '
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div class="input-group">
						'.form_dropdown('hp_phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_hp_telp_{0}\" class=\"form-control hp_subjek_telp\" required ").'
						<input type="text" id="input_subjek_hp_telp_{0}" class="form-control hidden input-subjek-telp">
						<span class="input-group-btn">
							<a class="btn blue-chambray edit-subjek-telp" id="btn_edit_subjek_hp_telp_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
							<a class="btn red-intense del-this delete-subjek-telp" id="btn_delete_subjek_hp_telp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
							<a class="btn btn-primary hidden save-subjek-telp" id="btn_save_subjek_hp_telp_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
							<a class="btn yellow hidden cancel-subjek-telp" id="btn_cancel_subjek_hp_telp_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<input class="form-control hp_no_telp" required id="nomer_{0}" name="hp_phone[{0}][number]" placeholder="Nomor Telepon">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4"></label>
				<div class="col-md-8">
					<input type="hidden" name="hp_phone[{0}][is_primary_hp_phone]" id="primary_hp_phone_id_{0}" class="hp_primary_telp">
					<input type="radio" name="hp_phone_is_primary" id="radio_primary_hp_phone_id_{0}" class="is_primary_hp_phone"> '.translate('Utama', $this->session->userdata('language')).'
				</div>
			</div>';
		?>

		<input type="hidden" id="tpl-form-hubungan-pasien" value="<?=htmlentities($form_hubungan_pasien)?>">
		<input type="hidden" id="tpl-form-hp-alamat" value="<?=htmlentities($form_hp_alamat)?>">
		<input type="hidden" id="tpl-form-hp-phone" value="<?=htmlentities($form_hp_phone)?>">

		<div class="form-body">
			<ul class="list-unstyled hubungan-pasien">
			</ul>
		</div>

		
	</div>
	
</div>

<div class="portlet light bordered" id="section-penanggung-jawab">
	<div class="portlet-title">
		<div class="caption">
			<?=translate('Penanggung Jawab', $this->session->userdata('language'))?>
		</div>
		<div class="actions hidden">
			<a class="btn btn-circle btn-default btn-icon-only add-penanggung-jawab">
                <i class="fa fa-plus"></i>
            </a>										
		</div>
	</div>
	
	<div class="portlet-body">
		<?php 
				$form_penanggung_jawab = '
				<div id="penanggung_jawab_{0}">
				<div class="form-group" id="group_nama_{0}">
					<label class="control-label col-md-4">'.translate("Nama", $this->session->userdata("language")).' :<span class="required">*</span></label>
					
					<div class="col-md-8 input-group">
						<input type="text" class="form-control send-data" id="penanggung_jawab_nama_{0}" name="penanggung_jawab[{0}][nama]" required>
						<input type="hidden" class="form-control send-data" id="set_penanggung_jawab_{0}" name="penanggung_jawab[{0}][set_penanggung_jawab]">
						<span class="input-group-btn hidden">
							<a class="btn btn-primary set_penanggung_jawab" id="set_penanggung_jawab_{0}" data-row="{0}">'.translate("Set Penanggung Jawab", $this->session->userdata("language")).'</a>
							<span class="label label-success penanggung_jawab hidden" id="penanggung_jawab_{0}" data-row="{0}">
								'.translate("Penanggung Jawab", $this->session->userdata("language")).'
							</span>
						</span>
						<span class="input-group-btn hidden">
							<a class="btn red-intense del-hub-pasien" id="del-hub-pasien" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>

				<div class="form-group" id="group_ktp_{0}">
					<label class="control-label col-md-4">'.translate("No. KTP", $this->session->userdata("language")).':<span class="required">*</span></label>
					
					<div class="col-md-8 input-group">
						<input type="text" class="form-control send-data" id="penanggung_jawab_ktp_{0}" name="penanggung_jawab[{0}][ktp]" required>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Scan KTP", $this->session->userdata("language")).' :</label>
					
					<div class="col-md-8 input-group">
						<input type="hidden" name="penanggung_jawab[{0}][url_ktp]" id="penanggung_jawab_url_ktp_{0}">
						<div id="upload_pj_{0}">
							<div id="drop_pj_{0}">	
								<span class="btn default btn-file">
									<span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>	
									<input type="file" name="upl" id="penanggung_jawab_scan_ktp_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
								</span>
							</div>

						<ul class="ul-img">
							<!-- The file uploads will be shown here -->
						</ul>

						</div>
					</div>
				</div>

				<div id="section-pj-alamat">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="portlet">
				                <div class="portlet-title">
				                    <div class="caption">
										'.translate('Alamat', $this->session->userdata('language')).'
									</div>
				                    <div class="actions">
				                        <a  class="btn btn-default btn-circle btn-icon-only add-pj-alamat">
					                        <i class="fa fa-plus"></i>
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
		                <div class="col-md-12">
			                <div class="portlet">
				                <div class="portlet-title">
				                    <div class="caption">
										'.translate('Telepon', $this->session->userdata('language')).'
									</div>
				                    <div class="actions">
				                        <a  class="btn btn-default btn-circle btn-icon-only add-pj-phone">
					                        <i class="fa fa-plus"></i>
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
				<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :<span class="required">*</span></label>
					<div class="col-md-8">
						<div class="input-group">
							'.form_dropdown('pj_alamat[{0}][subjek]', $alamat_sub_option, '', "id=\"subjek_pj_alamat_{0}\" class=\"select2me form-control subjek_alamat\" required ").'
							<input type="text" id="input_subjek_pj_alamat_{0}" class="form-control hidden send-data">
						
							<span class="input-group-btn">
								<a class="btn blue-chambray" id="btn_edit_subjek_pj_alamat_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
								<a class="btn red-intense del-this" id="btn_delete_subjek_pj_alamat_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
								<a class="btn btn-primary hidden" id="btn_save_subjek_pj_alamat_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
								<a class="btn yellow hidden" id="btn_cancel_subjek_pj_alamat_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :<span class="required">*</span></label>
					<div class="col-md-8">
						<textarea id="pj_alamat_{0}" name="pj_alamat[{0}][alamat]" class="form-control send-data" rows="3" placeholder="Alamat Lengkap" required></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("RT/RW", $this->session->userdata("language")).' :</label>
					<div class="col-md-2">
						<input type="text" id="rt_{0}" name="pj_alamat[{0}][rt]" class="form-control send-data" placeholder="RT">
					</div>
					<div class="col-md-2">
						<input type="text" id="rw_{0}" name="pj_alamat[{0}][rw]" class="form-control send-data" placeholder="RW">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<div class="input-group">
							<input type="text" id="input_pj_kelurahan_{0}" name="pj_alamat[{0}][kelurahan]" class="form-control" readonly>
							<input type="hidden" id="input_pj_kode_{0}" name="pj_alamat[{0}][kode]" class="form-control">
							<span class="input-group-btn">
								<a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan_{0}" title="'.translate('Cari', $this->session->userdata('language')).'" href="<?=base_url()?>master/pasien/modal/search_kelurahan"><i class="fa fa-search"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div id="div_pj_lokasi" class="hidden">
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input type="text" id="input_pj_kecamatan_{0}" name="pj_alamat[{0}][kecamatan]" class="form-control" readonly>					
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kota/Kabupaten", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_pj_kota_{0}" name="pj_alamat[{0}][kota]" class="form-control" readonly>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_pj_provinsi_{0}" name="pj_alamat[{0}][provinsi]" class="form-control" readonly>					
						
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						
							<input type="text" id="input_pj_negara_{0}" name="pj_alamat[{0}][negara]" class="form-control" readonly>					
						
					</div>
				</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input type="text" name="pj_alamat[{0}][kode_pos]" id="kode_pos_{0}" class="form-control send-data" placeholder="Kode Pos">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4"></label>
					<div class="col-md-8">
						<input type="hidden" name="pj_alamat[{0}][is_primary_pj_alamat]" id="primary_pj_alamat_id_{0}">
						<input type="radio" name="pj_alamat_is_primary" id="radio_primary_pj_alamat_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
					</div>
				</div>';

				$form_pj_phone = '
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :<span class="required">*</span></label>
					<div class="col-md-8">
						<div class="input-group">
							'.form_dropdown('pj_phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_pj_telp_{0}\" class=\"form-control\" required ").'
							<input type="text" id="input_subjek_pj_telp_{0}" class="form-control hidden">
							<span class="input-group-btn">
								<a class="btn blue-chambray" id="btn_edit_subjek_pj_telp_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
								<a class="btn red-intense del-this" id="btn_delete_subjek_pj_telp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
								<a class="btn btn-primary hidden" id="btn_save_subjek_pj_telp_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
								<a class="btn yellow hidden" id="btn_cancel_subjek_pj_telp_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :<span class="required">*</span></label>
					<div class="col-md-8">
						<input class="form-control" id="nomer_{0}" name="pj_phone[{0}][number]" placeholder="Nomor Telepon" required>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4"></label>
					<div class="col-md-8">
						<input type="hidden" name="pj_phone[{0}][is_primary_pj_phone]" id="primary_pj_phone_id_{0}">
						<input type="radio" name="pj_phone_is_primary" id="radio_primary_pj_phone_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
					</div>
				</div>';
			?>

		<div class="form-body hidden" id="ul_penanggung_jawab">
			<ul class="list-unstyled" id="ul_penanggung_jawab">
				<li class="fieldset" id="li_penanggung_jawab"></li>
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