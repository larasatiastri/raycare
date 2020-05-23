<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Identitas Item", $this->session->userdata("language"))?></h4>
</div>
<div class="modal-body">
	<input type="hidden" name="pk" value="<?=$pk?>" id="pk" class="form-control" required="required" autofocus="1" readonly>
	<div class="row">
		<div class="col-md-12">
			<label class="control-label col-md-3"><?=translate('Daftar Identitas', $this->session->userdata('language'))?></label>
			<div class="col-md-6">
				<input type="text" id="a" value="">
			</div>
		</div>
		<div class="col-md-12">
			<?php
                $form_identitas = '
                <div class="form-group">
                	<label class="control-label col-md-3">'.translate('Id Identitas', $this->session->userdata('language')).'</label>
                	<div class="col-md-6">
                		<input type="text" id="identitas_id_{0}" name="identitas[{0}][identitas_id]">
                	</div>
   					
                </div>';
	        ?>

	        <div id="form_identitas"></div>
	        <input type="hidden" id="tpl-form-identitas" value="<?=htmlentities($form_identitas)?>">
	        <div class="form-body">
	            <ul class="list-unstyled">
	            </ul>
	        </div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal"><?=translate('cancel', $this->session->userdata('language'))?></button>
	<button type="button" class="btn btn-primary"><?=translate('OK', $this->session->userdata('language'))?></button>
</div>