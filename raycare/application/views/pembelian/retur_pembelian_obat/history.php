<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-history font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Retur Pembelian", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a class="btn default" href="<?=base_url()?>pembelian/retur_pembelian"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>

        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_retur_pembelian_obat">
		<thead>
		<tr>
			<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Nama Supplier", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("No. Retur", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("No. Surat Jalan", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Jenis Retur", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Total", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Apoteker", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div>
</div>