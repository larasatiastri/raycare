<div class="portlet light" id="section-telepon">
	<div class="portlet-title">
		<div class="caption">
			<!-- <span class="caption-subject font-blue-sharp bold uppercase"></span> -->
			<?=translate('Telepon', $this->session->userdata('language'))?>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-icon-only btn-default add-phone">
                <i class="fa fa-plus"></i>
            </a>										
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
				<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<div class="input-group">
						'.form_dropdown('phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_telp_{0}\" class=\"form-control\" required ").'
						<input type="text" id="input_subjek_telp_{0}" class="form-control hidden">
						<span class="input-group-btn">
							<a class="btn blue-chambray" id="btn_edit_subjek_telp_{0}"  title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
							<a class="btn red-intense del-this" id="btn_delete_subjek_telp_{0}"  title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
							<a class="btn btn-primary hidden" id="btn_save_subjek_telp_{0}"  title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
							<a class="btn yellow hidden" id="btn_cancel_subjek_telp_{0}"  title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :<span class="required">*</span></label>
				<div class="col-md-8">
					<input class="form-control" id="nomer_{0}" name="phone[{0}][number]" placeholder="Nomor Telepon" required>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4"></label>
				<div class="col-md-8">
					<input type="hidden" name="phone[{0}][is_primary_phone]" id="primary_phone_id_{0}">
					<input type="radio" name="phone_is_primary" id="radio_primary_phone_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
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