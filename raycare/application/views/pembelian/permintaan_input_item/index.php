<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cubes font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Permintaan Input Item", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_input_item">
			<thead>
				<tr>
					<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jenis", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
