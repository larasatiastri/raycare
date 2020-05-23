<div class="portlet light" id="section-telepon">
	<div class="portlet-title">
		<div class="caption">
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

		    $get_pasien_telepon = $this->pasien_telepon_m->get_data($form_data['id']);
			$records = $get_pasien_telepon->result_array();

			if(count($records))
			{


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
						<div class="col-md-8">
							<input class="form-control hidden" id="id'.$i.'" name="phone['.$i.'][id]" placeholder="Id Telepon" value="'.$data['id'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div class="input-group">
							'.form_dropdown('phone['.$i.'][subjek]', $telp_sub_option, $data['subjek_id'], "id=\"subjek_telp_$i\" class=\"form-control\"").'
							<input type="text" id="input_subjek_telp_'.$i.'" class="form-control hidden">
							<span class="input-group-btn">
								<a class="btn blue-chambray edit" data-id="'.$i.'" id="btn_edit_subjek_telp_'.$i.'" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-3px;"></i></a>
								<a class="btn red-intense del-db" data-id="'.$i.'" id="btn_delete_subjek_telp_'.$i.'" data-confirm="'.translate('Apakah anda yakin ingin menghapus telepon ini ?', $this->session->userdata('language')).'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" style="margin-left:-3px;"></i></a>
								<a class="btn btn-primary hidden save" data-id="'.$i.'" id="btn_save_subjek_telp_'.$i.'" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-3px;"></i></a>
								<a class="btn yellow hidden cancel" data-id="'.$i.'" id="btn_cancel_subjek_telp_'.$i.'" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-3px;"></i></a>
							</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<input class="form-control" id="nomer_'.$i.'" name="phone['.$i.'][number]" placeholder="Nomor Telepon" value="'.$data['nomor'].'">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"></label>
						<div class="col-md-8">
							<input type="hidden" value="'.$data['is_primary'].'" data-id="'.$i.'" name="phone['.$i.'][is_primary_phone]" id="primary_phone_id_'.$i.'">
							<input type="radio" name="phone_is_primary" data-id="'.$i.'" id="radio_primary_phone_id_'.$i.'" '.$primary.'> '.translate("Utama", $this->session->userdata("language")).'
	                    </div>
						
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<input class="form-control hidden" id="is_delete_'.$i.'" name="phone['.$i.'][is_delete]" placeholder="Is Delete">
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
				$form_phone_edit = array();
			}
			
			$form_phone = '
			<div class="form-group">
					<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
					<div class="col-md-8">
						<input class="form-control hidden" id="id{0}" name="phone[{0}][id]" placeholder="Id Telepon">
					</div>
				</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div class="input-group">
						'.form_dropdown('phone[{0}][subjek]', $telp_sub_option, '', " id=\"subjek_telp_{0}\" class=\"form-control\" ").'
						<input type="text" id="input_subjek_telp_{0}" class="form-control hidden">
						<span class="input-group-btn">
							<a class="btn blue-chambray" id="btn_edit_subjek_telp_{0}" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" ></i></a>
							<a class="btn red-intense del-this" id="btn_delete_subjek_telp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times" ></i></a>
							<a class="btn btn-primary hidden" id="btn_save_subjek_telp_{0}" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" ></i></a>
							<a class="btn yellow hidden" id="btn_cancel_subjek_telp_{0}" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" ></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<input class="form-control" id="nomer_{0}" name="phone[{0}][number]" placeholder="Nomor Telepon">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4"></label>
				<div class="col-md-8">
					<input type="hidden" name="phone[{0}][is_primary_phone]" id="primary_phone_id_{0}">
					<input type="radio" name="phone_is_primary" id="radio_primary_phone_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'

				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<input class="form-control hidden" id="is_delete_{0}" name="phone[{0}][is_delete]" placeholder="Is Delete">
				</div>
			</div>
			';
			

			//die_dump($pasien_telepon_option);
			
		?>
		<div class="form-group">
			<label class="control-label col-md-4 hidden"><?=translate("Phone Counter", $this->session->userdata("language"))?> :</label>
			<div class="col-md-8">
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