<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Fitur User Level", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <?php
							
				$fitur_tombol = $this->fitur_tombol_m->get_dist_page()->result_array();

				$fitur_tombol_option = array(
				    '' => translate('Pilih Fitur', $this->session->userdata('language'))
				);

				foreach ($fitur_tombol as $fitur)
				{
				    $fitur_tombol_option[$fitur['page']] = $fitur['page'];
				}
				echo form_dropdown('fitur_tombol_id', $fitur_tombol_option,'', "id=\"fitur_tombol_id\" class=\"form-control bs-select\" style=\"width:200px;\"");
			?>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_fitur">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("Tombol", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Batasan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
