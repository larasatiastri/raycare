<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-tag font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Surat Pengantar", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>klinik_hd/surat_pengantar/index" class="btn btn-circle btn-default">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="tabel_surat_pengantar" style=" border-collapse: collapse !important;">
			<thead>
				<tr>
					<th class="text-center"><?=translate("No. Surat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("RS / Poliklinik Tujuan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Dokter Tujuan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
				
			</tbody>
		</table>

	</div>
</div>
