<div class="portlet light" id="section-bank"> <!-- begin of class="portlet light" tab_telepon -->
	<div class="portlet-title">
		<div class="caption">
			<span><?=translate('Bank', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-icon-only btn-default add-bank">
                <i class="fa fa-plus"></i>
            </a>										
		</div>
	</div>
	<div class="portlet-body"> <!-- begin of class="portlet-body" tab_telepon -->
		<?php
			$form_bank = '
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Nama Bank", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div id="subjek_{0}" class="input-group">
						<input type="text" id="bank_nama_{0}" class="form-control" name="bank[{0}][nama]" placeholder="'.translate('Nama Bank', $this->session->userdata('language')).'">
						<span class="input-group-btn">
							<a class="btn red-intense del-this-bank" id="btn_delete_bank{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Cabang Bank", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
						<input type="text" id="bank_cabang_{0}" class="form-control" name="bank[{0}][cabang]" placeholder="'.translate('Cabang Bank', $this->session->userdata('language')).'">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("No. Rekening", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
						<input type="text" id="bank_norek_{0}" class="form-control" name="bank[{0}][norek]" placeholder="'.translate('No. Rekening', $this->session->userdata('language')).'">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Atas Nama", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
						<input type="text" id="bank_atasnama_{0}" class="form-control" name="bank[{0}][atasnama]" placeholder="'.translate('Atas Nama', $this->session->userdata('language')).'">
				</div>
			</div>
			';
		?>

		<input type="hidden" id="tpl-form-bank" value="<?=htmlentities($form_bank)?>">
		<div class="form-body">
			<ul class="list-unstyled">
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_telepon -->
</div> <!-- end of class="portlet light" tab_telepon -->