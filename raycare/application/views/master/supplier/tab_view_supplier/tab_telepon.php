<div class="portlet light" id="section-telepon"> <!-- begin of class="portlet light" tab_telepon -->
	<div class="portlet-title">
		<div class="caption">
			<span><?=translate('Telepon', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions hidden">
			<a class="btn btn-primary add-telp">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <!-- <?=translate("Tambah", $this->session->userdata("language"))?> -->
                </span>
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

				$subjek_telp = $this->subjek_m->get($data['subjek_telp_id']);

			    $form_phone_edit[] = '
			    <li id="phone_'.$i.'" class="fieldset">
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$subjek_telp->nama.'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$data['no_telp'].'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"></label>
						<div class="col-md-8">
							<input type="radio" '.$primary.' disabled name="phone_is_primary" id="radio_primary_phone_id_'.$i.'" data-id="'.$i.'"> '.translate('Utama', $this->session->userdata('language')).'
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