<div class="portlet light" id="section-email"> <!-- begin of class="portlet light" tab_telepon -->
	<div class="portlet-title">
		<div class="caption">
			<span><?=translate('Email', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions hidden">
			<a class="btn btn-primary add-email">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <!-- <?=translate("Tambah", $this->session->userdata("language"))?> -->
                </span>
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
							<label class="control-label">'.$data['email'].'</label>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4">'.translate("URL", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$data['url'].'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"></label>
						<div class="col-md-8">
							<input type="radio" '.$primary.' disabled name="email_is_primary" id="radio_primary_email_id_'.$i.'" data-id="'.$i.'"> '.translate('Utama', $this->session->userdata('language')).'
						</div>
					</div>
					<hr/>
				<li>';
				$i++;
			}			
		?>
		
		<div class="form-group">
			<label class="control-label col-md-4 hidden"><?=translate("Email Counter", $this->session->userdata("language"))?> :</label>
			<div class="col-md-8">
				<input type="hidden" id="Email_counter" value="<?=$i?>" >
			</div>
		</div>
		
		<div class="form-body">
			<ul class="list-unstyled">
				<?php foreach ($form_email_edit as $row):?>
		            <?=$row?>
		        <?php endforeach;?>
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_telepon -->
</div> <!-- end of class="portlet light" tab_telepon -->