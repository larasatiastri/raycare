<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-note font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Reuse Dialyzer", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover" id="table_dialyzer">
		<thead>
		<tr>
			<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Dialyzer", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Index", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("BN", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Volume (%)", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Tgl. Terakhir Pakai", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div>
</div>