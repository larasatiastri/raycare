<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-flag font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Data Pasien Pindah", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>master/pasien_pindah/add" class="btn btn-circle btn-default">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_pasien_pindah">
		<thead>
		<tr>
			<th class="text-center"><?=translate("No. RM", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Nama Pasien", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Tanggal Pindah", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Lokasi Tujuan", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div>
</div>



