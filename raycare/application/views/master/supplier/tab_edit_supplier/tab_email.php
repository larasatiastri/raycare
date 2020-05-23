<div class="portlet light" id="section-email"> <!-- begin of class="portlet light" tab_telepon -->
	<div class="portlet-title">
		<div class="caption">
			<span><?=translate('Email', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-icon-only btn-default add-email">
                <i class="fa fa-plus"></i>
            </a>										
		</div>
	</div>
	<div class="portlet-body"> <!-- begin of class="portlet-body" tab_telepon -->
		<?php
			
			$get_data_supplier_email = $this->supplier_email_m->get_by(array('supplier_id' => $form_data['id'], 'is_active' => 1));
			$data_supplier_email = object_to_array($get_data_supplier_email);
			$i = 1;
			foreach ($data_supplier_email as $data) {
				$primary = '';
				if ($data['is_primary'] == 1) {
					$primary = 'checked';
				}

			    $form_email_edit[] = '
			    <li id="email_'.$i.'" class="fieldset">
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Email", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<div id="subjek_{0}" class="input-group">
								<input type="text" id="email_'.$i.'" class="form-control" name="email['.$i.'][email]" value="'.$data['email'].'" placeholder="'.translate('Email', $this->session->userdata('language')).'">
								<span class="input-group-btn">
									<a class="btn red-intense del-this" id="btn_delete_subjek_telp_'.$i.'" data-confirm="'.translate('Anda yakin akan menghapus email ini?', $this->session->userdata('language')).'" data-index="'.$i.'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"></label>
						<div class="col-md-8">
							<input type="hidden" name="email['.$i.'][supplier_email_id]" id="supplier_email_id_'.$i.'" value="'.$data['id'].'">
							<input type="hidden" name="email['.$i.'][is_primary_email]" id="primary_email_id_'.$i.'" value="'.$data['is_primary'].'">
							<input type="hidden" name="email['.$i.'][is_delete]" id="is_delete_email_'.$i.'">
							<label><input type="radio" '.$primary.' name="email_is_primary" id="radio_primary_email_id_'.$i.'" data-id="'.$i.'"> '.translate('Utama', $this->session->userdata('language')).'</label>
						</div>
					</div>
					<hr/>
				<li>';
				$i++;
			}			

			$form_email = '
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Email", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div id="subjek_{0}" class="input-group">
						<input type="text" id="email_{0}" class="form-control" name="email[{0}][email]" placeholder="'.translate('Email', $this->session->userdata('language')).'">
						<span class="input-group-btn">
							<a class="btn red-intense del-this" id="btn_delete_subjek_telp_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4"></label>
				<div class="col-md-8">
					<input type="hidden" name="email[{0}][supplier_email_id]" id="supplier_email_id_{0}">
					<input type="hidden" name="email[{0}][is_primary_email]" id="primary_email_id_{0}">
					<input type="hidden" name="email[{0}][is_delete]" id="is_delete_email_{0}">
					<label><input type="radio" name="email_is_primary" id="radio_primary_email_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'</label>
				</div>
			</div>';
		?>
		
		<div class="form-group">
			<label class="control-label col-md-4 hidden"><?=translate("Email Counter", $this->session->userdata("language"))?> :</label>
			<div class="col-md-8">
				<input type="hidden" id="email_counter" value="<?=$i?>" >
			</div>
		</div>
		
		

		<input type="hidden" id="tpl-form-email" value="<?=htmlentities($form_email)?>">
		<div class="form-body">
			<ul class="list-unstyled">
				<?php
				if(count($data_supplier_email) != 0)
				{
					foreach ($form_email_edit as $row)
					{
						echo $row;
					}
				} 
				?>
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_telepon -->
</div> <!-- end of class="portlet light" tab_telepon -->