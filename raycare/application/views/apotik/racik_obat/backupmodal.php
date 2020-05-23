<div class="modal fade form-horizontal" id="ajax_notes" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        	<!-- <form action="" class="form-horizontal"> -->
	        	<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Identitas Item", $this->session->userdata("language"))?></h4>
				</div>
				<div class="modal-body">
					<div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate('I', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<input type="text" name="i" value="1" id="i" class="form-control">	
						</div>
					</div>
					<div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate('Item Id', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<input type="text" name="item_id" value="" id="item_id" class="form-control" autofocus="1">	
						</div>
					</div>
					<div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate('Item Satuan Id', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<input type="text" name="item_satuan_id" value="" id="item_satuan_id" class="form-control" autofocus="1">	
						</div>
					</div>
					<div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate('Identitas Ditambahkan', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<input type="text" name="identitas_tambah" value="" id="identitas_tambah" class="form-control" autofocus="1">	
						</div>
					</div>
					<div class="form-group" style="margin-bottom : 20px;">
						<label class="control-label col-md-3"><?=translate('Identitas', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<?php 
								$identitas_option = array(
									'' => 'Pilih..'
								);
								
								echo form_dropdown('identitas', $identitas_option, "", "id=\"identitas\" class=\"form-control\""); 
							?>
						</div>
						<div class="col-md-2" style="margin-left : -30px !important;">
							<a id="tambah_identitas" class="btn btn-primary hidden"><?=translate('Tambah', $this->session->userdata('language'))?></a>
							<a id="tambah_fieldset" class="btn btn-primary"><?=translate('Tambah', $this->session->userdata('language'))?></a>
						</div>
					</div>

					<?php			                
					$form_identitas = '
		                <div id="show_identitas_{0}" class="show_identitas">
		   					
		                </div>';
			        ?>

			        <div id="form_identitas"></div>
			        <input type="hidden" id="tpl-form-identitas" value="<?=htmlentities($form_identitas)?>">
			        <div class="form-body">
			            <ul class="list-unstyled">
			            </ul>
			        </div>
				</div>
			<!-- </form> -->
			<div class="modal-footer">
				<a type="button" id="modal_close" class="btn default hidden" data-dismiss="modal"><?=translate('Close', $this->session->userdata('language'))?></a>
				<a id="modal_cancel" class="btn default" data-dismiss="modal"><?=translate('Cancel', $this->session->userdata('language'))?></a>
				<a id="modal_ok" class="btn btn-primary"><?=translate('OK', $this->session->userdata('language'))?></a>
			</div>
        </div>
    </div>
</div>
