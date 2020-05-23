<div class="portlet light" style="margin-top : 20px;">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_history">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Dokter", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 550px !important;"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
