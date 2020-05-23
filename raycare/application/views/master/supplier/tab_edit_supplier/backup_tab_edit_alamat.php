        $this->load->model('master/info_alamat_m');
<div class="portlet light" id="section-telepon"> <!-- begin of class="portlet light" tab_telepon -->
	<div class="portlet-title">
		<div class="caption">
			<span><?=translate('Telepon', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-icon-only btn-default add-telp">
                <i class="fa fa-plus"></i>
            </a>										
		</div>
	</div>
	<div class="portlet-body"> <!-- begin of class="portlet-body" tab_telepon -->
		<?php
			$telp_sub = $this->supplier_m->get_data_subjek(2);
			$telp_sub_array = $telp_sub->result_array();
			
			$telp_sub_option = array(
				'' => "Pilih..",

			);
		    foreach ($telp_sub_array as $select) {
		        $telp_sub_option[$select['id']] = $select['nama'];
		    }
			
			$get_data_supplier_telepon = $this->supplier_telepon_m->get_by(array('supplier_id' => $form_data['id'], 'is_active' => 1));
			$data_supplier_telepon = object_to_array($get_data_supplier_telepon);
			$i = 1;
			foreach ($data_supplier_telepon as $data) {
				$primary = '';
				if ($data['is_primary'] == 1) {
					$primary = 'checked';
				}

			    $form_phone_edit[] = '
			    <li id="phone_'.$i.'" class="fieldset">
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div id="subjek_'.$i.'" class="input-group">
								'.form_dropdown('phone['.$i.'][subjek]', $telp_sub_option, $data['subjek_telp_id'], 'id="subjek_telp_'.$i.'" class="form-control input-sx" required ').'
								<span class="input-group-btn">
									<a class="btn blue-chambray edit-phone" id="btn_edit_subjek_telp_'.$i.'" data-id="'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
									<a class="btn red-intense del-db" id="btn_delete_subjek_telp_'.$i.'" data-id="'.$i.'" title="'.translate('Remove', $this->session->userdata('language')).'" data-confirm="'.translate('Apakah anda yakin ingin menghapus telepon ini ?', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
								</span>
							</div>
							<div id="subjek_hidden_'.$i.'" class="input-group hidden">
								<input type="text" id="input_subjek_telp_'.$i.'" class="form-control">
								<span class="input-group-btn">
									<a class="btn green-haze" id="btn_save_subjek_telp_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
									<a class="btn yellow" id="btn_cancel_subjek_telp_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<input class="form-control input-sm" required id="nomer_'.$i.'" name="phone['.$i.'][number]" placeholder="'.translate('Nomor Telepon', $this->session->userdata('language')).'" value="'.$data['no_telp'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"></label>
						<div class="col-md-8">
							<input type="hidden" name="phone['.$i.'][supplier_telp_id]" id="supplier_telp_id_'.$i.'" value="'.$data['id'].'">
							<input type="hidden" name="phone['.$i.'][is_primary_phone]" id="primary_phone_id_'.$i.'" value="'.$data['is_primary'].'">
							<input type="hidden" name="phone['.$i.'][is_delete]" id="is_delete_phone_'.$i.'">
							<input type="radio" '.$primary.' name="phone_is_primary" id="radio_primary_phone_id_'.$i.'" data-id="'.$i.'"> '.translate('Utama', $this->session->userdata('language')).'
						</div>
					</div>
					<hr/>
				<li>';
				$i++;
			}			

			$form_phone = '
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div id="subjek_{0}" class="input-group">
						'.form_dropdown('phone[{0}][subjek]', $telp_sub_option, '', "id=\"subjek_telp_{0}\" class=\"form-control input-sx\" required ").'
						<span class="input-group-btn">
							<a class="btn blue-chambray" id="btn_edit_subjek_telp_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil"></i></a>
							<a class="btn red-intense del-this" id="btn_delete_subjek_telp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
						</span>
					</div>
					<div id="subjek_hidden_{0}" class="input-group hidden">
						<input type="text" id="input_subjek_telp_{0}" class="form-control">
						<span class="input-group-btn">
							<a class="btn green-haze" id="btn_save_subjek_telp_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check"></i></a>
							<a class="btn yellow" id="btn_cancel_subjek_telp_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<input class="form-control input-sm" required id="nomer_{0}" name="phone[{0}][number]" placeholder="Nomor Telepon">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4"></label>
				<div class="col-md-8">
					<input type="hidden" name="phone[{0}][supplier_telp_id]" id="supplier_telp_id_{0}">
					<input type="hidden" name="phone[{0}][is_primary_phone]" id="primary_phone_id_{0}">
					<input type="hidden" name="phone[{0}][is_delete]" id="is_delete_phone_{0}">
					<input type="radio" name="phone_is_primary" id="radio_primary_phone_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
				</div>
			</div>';
		?>
		
		<div class="form-group">
			<label class="control-label col-md-4 hidden"><?=translate("Phone Counter", $this->session->userdata("language"))?> :</label>
			<div class="col-md-8">
				<input type="hidden" id="phone_counter" value="<?=$i?>" >
			</div>
		</div>
		
		

		<input type="hidden" id="tpl-form-phone" value="<?=htmlentities($form_phone)?>">
		<div class="form-body">
			<ul class="list-unstyled">
				<?php foreach ($form_phone_edit as $row):?>
		            <?=$row?>
		        <?php endforeach;?>
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_telepon -->
</div> <!-- end of class="portlet light" tab_telepon -->