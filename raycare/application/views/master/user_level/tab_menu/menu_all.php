<div class="portlet light">
<!-- 	<div class="portlet-title">
		<div class="caption">
			<?=translate('Menu User Level', $this->session->userdata('language'))?>
		</div>
	</div> -->
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="control-label col-md-4"><?=translate("Menu Utama", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php

						$option_menu_utama = array(
							'0'	=> translate('Utama', $this->session->userdata('language'))
						);						

						echo form_dropdown('menu_parent_id', $option_menu_utama, '', 'id="menu_parent_id" class="form-control" required="required"');

					?>
				</div>
			</div>
			<div class="form-group">
				<div class="table-scrollable">
					<table class="table table-striped table-bordered table-hover" id="table_menu">
						<thead>
							<tr>
								<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Base URL", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("URL", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Icon Class", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Unik ID", $this->session->userdata("language"))?></th>
								<th class="text-center" width="1%"><?=translate("Urutan", $this->session->userdata("language"))?></th>
								<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
							</tr>
						</thead>

						<tbody id="menu_content">
							<tr>
								<td colspan="7" style="text-align:center;"><span><?=translate('Tidak ada sub menu dalam menu utama yang dipilih', $this->session->userdata('language'))?></span></td>
							</tr>
						</tbody>
					</table>	
				</div>
			</div>		
		</div>	
	</div>
</div>