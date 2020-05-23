<div class="portlet light" id="section-telepon">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Telepon', $this->session->userdata('language'))?></span>
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

		    $get_pasien_telepon = $this->pasien_telepon_m->get_data($form_data['id']);
			$records = $get_pasien_telepon->result_array();
			
			$i=0;
			foreach ($records as $key => $data) {
				$primary = "";
				if ($data['is_primary'] == "1") {
					$primary = "checked";
				}

				$form_phone_edit[] = '
				<div id="phone_'.$i.'">
				<div class="form-group">
					<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						<input class="form-control input-sm hidden" id="id'.$i.'" name="phone['.$i.'][id]" placeholder="Id Telepon" value="'.$data['id'].'">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						<label class="control-label">'.$data['nama'].'</label>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						<label class="control-label">'.$data['nomor'].'</label>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4"></label>
					<div class="col-md-3">
						<input type="hidden" value="'.$data['is_primary'].'" data-id="'.$i.'" name="phone['.$i.'][is_primary_phone]" id="primary_phone_id_'.$i.'">
						<input type="radio" disabled name="phone_is_primary" data-id="'.$i.'" id="radio_primary_phone_id_'.$i.'" '.$primary.'> '.translate("Utama", $this->session->userdata("language")).'
                    </div>
					
				</div>
				<div class="form-group">
					<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						<input class="form-control input-sm hidden" id="is_delete_'.$i.'" name="phone['.$i.'][is_delete]" placeholder="Is Delete">
					</div>
				</div>
				<hr>
				</div>'
				;
				$i++;
			}
			
			$form_phone = '
			<div class="form-group">
					<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
					<div class="col-md-3">
						<input class="form-control input-sm hidden" id="id{0}" name="phone[{0}][id]" placeholder="Id Telepon">
					</div>
				</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
				<div class="col-md-3">
					'.form_dropdown('phone[{0}][subjek]', $telp_sub_option, '', " id=\"subjek_telp_{0}\" class=\"form-control input-sx\" ").'
					<input type="text" id="input_subjek_telp_{0}" class="form-control hidden">
				</div>
				<span class="input-group-btn" style="left:-15px;">
					<a class="btn btn-xs blue-chambray" id="btn_edit_subjek_telp_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
					<a class="btn btn-xs red-intense del-this" id="btn_delete_subjek_telp_{0}" style="height:20px; width:20px;" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" style="margin-left:-6px;"></i></a>
					<a class="btn btn-xs green-haze hidden" id="btn_save_subjek_telp_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>
					<a class="btn btn-xs yellow hidden" id="btn_cancel_subjek_telp_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
				</span>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
				<div class="col-md-3">
					<input class="form-control input-sm" id="nomer_{0}" name="phone[{0}][number]" placeholder="Nomor Telepon">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4"></label>
				<div class="col-md-3">
					<input type="hidden" name="phone[{0}][is_primary_phone]" id="primary_phone_id_{0}">
					<input type="radio" name="phone_is_primary" id="radio_primary_phone_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'

				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
				<div class="col-md-3">
					<input class="form-control input-sm hidden" id="is_delete_{0}" name="phone[{0}][is_delete]" placeholder="Is Delete">
				</div>
			</div>
			';
			

			//die_dump($pasien_telepon_option);
			
		?>
		<div class="form-group">
			<label class="control-label col-md-4 hidden"><?=translate("Phone Counter", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<input type="hidden" id="phone_counter" value="<?=$i?>" >
			</div>
		</div>
		
		<?php foreach ($form_phone_edit as $row):?>
            <?=$row?>
            
        <?php endforeach;?>

		
		<input type="hidden" id="tpl-form-phone" value="<?=htmlentities($form_phone)?>">
		<div class="form-body">
			<ul class="list-unstyled">
			</ul>
		</div>
	</div>
</div>