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
					<input type="hidden" name="email[{0}][is_primary_email]" id="primary_email_id_{0}">
					<label><input type="radio" name="email_is_primary" id="radio_primary_email_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'</label>
				</div>
			</div>';
		?>

		<input type="hidden" id="tpl-form-email" value="<?=htmlentities($form_email)?>">
		<div class="form-body">
			<ul class="list-unstyled">
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_telepon -->
</div> <!-- end of class="portlet light" tab_telepon -->