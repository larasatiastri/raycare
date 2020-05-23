<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-history font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pendaftaran Belum Ditindak", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			
			<a class="btn default" href="javascript:history.go(-1)">
			<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a class="btn default" href="<?=base_url()?>reservasi/pendaftaran_tindakan/history_all">
			<i class="fa fa-history"></i>
				<?=translate("History", $this->session->userdata("language"))?>
			</a>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-condensed table-striped table-hover" id="table_history">
			<thead>
			<tr>
				<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No. RM", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No. Penjamin", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Asal Rujukan", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No. Rujukan", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Poliklinik", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Dokter", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Front Office", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
			</tr>
			</thead>
			<tbody>			
			</tbody>
		</table>
	</div>
</div>