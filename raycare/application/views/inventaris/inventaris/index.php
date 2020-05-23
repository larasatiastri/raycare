<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-list font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Inventaris", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">

            <a href="<?=base_url()?>inventaris/inventaris/add" class="btn btn-circle btn-default"> <i class="fa fa-plus"></i> <span class="hidden-480"> <?=translate("Tambah", $this->session->userdata("language"))?></span> </a>

        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_inventaris">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("No. Inventaris", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Merk", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tipe", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Serial Number", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tgl Pembelian", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Garansi", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jadwal Maintenance", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Pengguna", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Penanggung Jawab", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>