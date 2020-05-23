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
			
			$data_pembayaran = $this->supplier_tipe_pembayaran_m->get_by(array('supplier_id' => $pk_value, 'is_active' => 1));
			if(count($data_pembayaran))
			{
				$data_pembayaran = object_to_array($data_pembayaran);

				$i = 1;
				foreach ($data_pembayaran as $bayar) 
				{
					$hidden = 'hidden';

					if($bayar['tipe_bayar_id'] == 3)
					{
						$hidden = '';
					}
					$form_pembayaran_edit[] = '<li id="field_'.$i.'" class="fieldset">
						<div class="form-group hidden">
							<label class="control-label col-md-4">'.translate("ID Pembayaran", $this->session->userdata("language")).' :</label>
							<div class="col-md-6">
								<input type="text" name="pembayaran['.$i.'][id]" id="tipe_id_'.$i.'" value="'.$bayar['id'].'">
							</div>
						</div>
						<div class="form-group hidden">
							<label class="control-label col-md-4">'.translate("Active Pembayaran", $this->session->userdata("language")).' :</label>
							<div class="col-md-6">
								<input type="text" name="pembayaran['.$i.'][is_active]" id="tipe_is_active_'.$i.'" value="1">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Tipe Pembayaran", $this->session->userdata("language")).' :</label>
							<div class="col-md-6">
								<div id="subjek_'.$i.'" class="input-group">
									'.form_dropdown('pembayaran['.$i.'][tipe_bayar]', $tipe_bayar_option, $bayar['tipe_bayar_id'], 'id="tipe_bayar_'.$i.'" class="form-control tipe_pembayaran" data-index="'.$i.'" required').'
									<span class="input-group-btn">
										<a class="btn red-intense del-this-bayar-db" data-index="'.$i.'" data-confirm="'.translate('Anda yakin akan menghapus tipe pembayaran ini?', $this->session->userdata('language')).'" id="btn_delete_tipe_bayar_'.$i.'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group tempo '.$hidden.'" id="lama_tempo_'.$i.'" >
							<label class="control-label col-md-4">'.translate("Lama Tempo", $this->session->userdata("language")).' :</label>
							<div class="col-md-3">
								<div class="input-group">
									<input class="form-control input-sm" id="tempo_'.$i.'" name="pembayaran['.$i.'][tempo]" value="'.$bayar['lama_tempo'].'" placeholder="'.translate('Lama Tempo', $this->session->userdata('language')).'">
									<div class="input-group-addon">
										&nbsp;'.translate('Hari', $this->session->userdata('language')).'&nbsp;
									</div>
								</div>
							</div>
						</div>
						<hr style="border-color: rgb(228, 228, 228);">
					</li>';

					$i++;
				}
			}
			else
			{
				$i = 1;
			}

			$form_pembayaran = '
			<div class="form-group hidden">
				<label class="control-label col-md-4">'.translate("ID Pembayaran", $this->session->userdata("language")).' :</label>
				<div class="col-md-6">
					<input type="text" name="pembayaran[{0}][id]" id="tipe_id_{0}" value="">
				</div>
			</div>
			<div class="form-group hidden">
				<label class="control-label col-md-4">'.translate("Active Pembayaran", $this->session->userdata("language")).' :</label>
				<div class="col-md-6">
					<input type="text" name="pembayaran[{0}][is_active]" id="tipe_is_active_{0}" value="1">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Tipe Pembayaran", $this->session->userdata("language")).' :</label>
				<div class="col-md-6">
					<div id="subjek_{0}" class="input-group">
						'.form_dropdown('pembayaran[{0}][tipe_bayar]', $tipe_bayar_option, '', "id=\"tipe_bayar_{0}\" class=\"form-control tipe_pembayaran\" required ").'
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
			<input type="hidden" id="jml_data_bayar" name="jml_data_bayar" value="<?=$i?>">
			<ul class="list-unstyled">
			<?php
				if(count($data_pembayaran))
				{
					foreach ($form_pembayaran_edit as $row) {
						echo $row;
					}
				}
			?>
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_tipe_pembayaran -->
</div> <!-- end of class="portlet light" tab_tipe_pembayaran -->