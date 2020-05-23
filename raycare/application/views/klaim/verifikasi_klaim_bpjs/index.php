<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Verifikasi Klaim BPJS", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_verifikasi_klaim_bpjs">
			<thead>
				<tr>
					<th class="text-center"><?=translate("Periode", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No. Surat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tindakan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tarif Riil RS ", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tarif INA CBGs", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tindakan", $this->session->userdata("language"))?><br>(<?=translate("Verifikasi", $this->session->userdata("language"))?>)</th>
					<th class="text-center"><?=translate("Jumlah Tarif INA CBGs", $this->session->userdata("language"))?><br>(<?=translate("Verifikasi", $this->session->userdata("language"))?>)</th>
					<th class="text-center"><?=translate("Tanggal Dibuat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
