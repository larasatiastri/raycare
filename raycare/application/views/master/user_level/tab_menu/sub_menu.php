<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<?=translate('Sub Menu', $this->session->userdata('language'))?>
		</div>
		<div class="actions">
												
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="control-label col-md-4"><?=translate("Ambil Fitur Dari Cabang", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php

						$cabang = $this->cabang_m->get_by(array('is_active' => 1));

						$option_cabang = array(
							''	=> translate('Pilih', $this->session->userdata('language')).'...'
						);

						foreach ($cabang as $cabang) 
						{
							$option_cabang[$cabang->id] = $cabang->nama;
						}

						echo form_dropdown('fitur_cabang_id', $option_cabang, $this->session->userdata('cabang_id'), 'id="fitur_cabang_id" class="form-control" required="required"');

					?>
				</div>
			</div>
			<div class="form-group">
				<div class="table-scrollable">
					<table class="table table-striped table-bordered table-hover" id="table_fitur">
						<thead>
							<tr>
								<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("URL", $this->session->userdata("language"))?></th>
								<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
							</tr>
						</thead>

						<tbody id="fitur_content">
							<tr>
								<td colspan="3" style="text-align:center;"><span><?=translate('Tidak ada fitur di cabang yang dipilih', $this->session->userdata('language'))?></span></td>
							</tr>
						</tbody>
					</table>	
				</div>		
			</div>		
		</div>	
	</div>
</div>