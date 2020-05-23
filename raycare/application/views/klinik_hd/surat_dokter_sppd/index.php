<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-note font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Surat Pengantar HD 3x", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>klinik_hd/surat_dokter_sppd/index" class="btn green">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover" id="table_pasien">
		<thead>
		<tr>
			<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Dokter Buat", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Diagnosa", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Alasan", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div>
</div>