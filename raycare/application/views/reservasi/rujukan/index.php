<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Rujukan", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>reservasi/rujukan/history" class="btn default">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("History", $this->session->userdata("language"))?>
                </span>
            </a>
			&nbsp;
            <a href="<?=base_url()?>reservasi/rujukan/add" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-hover table-light" id="table_rujukan">
			<thead>
				<tr class="uppercase">
					<th class="fit"></th>
					<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Poliklinik Asal", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Poliklinik Tujuan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal Di Rujuk", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
				
			</tbody>
		</table>

	</div>
</div>
