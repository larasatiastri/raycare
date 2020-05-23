<div class="portlet light">
	 <div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Transaksi Sedang Diproses", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="portlet-body">
			<table class="table table-condensed table-striped table-bordered table-hover" id="table_tindakan">
			<thead>
				<tr role="row">
					<th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="12%"><?=translate("No.Transaksi", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Alamat", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="15%"><?=translate("Dokter", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>

				</tr>
			</thead>
			<tbody>
				
			</tbody>
			</table>
		</div>
	</div>
</div>

