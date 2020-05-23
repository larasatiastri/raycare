<div class="portlet light" id="section-pembayaran"> <!-- begin of class="portlet light" tab_tipe_pembayaran -->
	<div class="portlet-title">
		<div class="caption">
			<span><?=translate('Tipe Pembayaran', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-icon-only btn-default add-pembayaran">
                <i class="fa fa-plus"></i>
            </a>										
		</div>
	</div>
	<div class="portlet-body"> <!-- begin of class="portlet-body" tab_tipe_pembayaran -->
		<?php
			
			$data_tipe_bayar = $this->master_tipe_bayar_m->get_by(array('is_active' => 1));
			$tipe_bayar_option = array(
				'' => "Pilih..",
			);

		    foreach ($data_tipe_bayar as $select) {
		        $tipe_bayar_option[$select->id] = $select->nama;
		    }

			$form_pembayaran = '<div class="form-group">
				<label class="control-label col-md-4">'.translate("Tipe Pembayaran", $this->session->userdata("language")).' :</label>
				<div class="col-md-6">
					<div id="subjek_{0}" class="input-group">
						'.form_dropdown('pembayaran[{0}][tipe_bayar]', $tipe_bayar_option, '', "id=\"tipe_bayar_{0}\" class=\"form-control input-sx tipe_pembayaran\" required ").'
						<span class="input-group-btn">
							<a class="btn red-intense del-this" id="btn_delete_tipe_bayar_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group tempo" id=" lama_tempo_{0}" style="display: none;">
				<label class="control-label col-md-4">'.translate("Lama Tempo", $this->session->userdata("language")).' :</label>
				<div class="col-md-3">
					<div class="input-group">
						<input class="form-control input-sm" id="tempo_{0}" name="pembayaran[{0}][tempo]" placeholder="'.translate('Lama Tempo', $this->session->userdata('language')).'">
						<div class="input-group-addon">
							&nbsp;'.translate('Hari', $this->session->userdata('language')).'&nbsp;
						</div>
					</div>
				</div>
			</div>';
		?>

		<input type="hidden" id="tpl-form-pembayaran" value="<?=htmlentities($form_pembayaran)?>">
		<div class="form-body">
			<ul class="list-unstyled">
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_tipe_pembayaran -->
</div> <!-- end of class="portlet light" tab_tipe_pembayaran -->