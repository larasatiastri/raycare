<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Klaim Pasien", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>master/pasien/add_claim/<?=$id_pasien?>" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<input type="hidden" id="id_pasien" value="<?=$id_pasien?>">
		<table class="table table-striped table-bordered table-hover" id="table_claim">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("No Kartu", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Aktif", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
