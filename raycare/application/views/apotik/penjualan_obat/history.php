<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-history font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Penjualan Obat", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a class="btn default" href="<?=base_url()?>apotik/penjualan_obat"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>

        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_penjualan_obat">
		<thead>
		<tr>
			<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("No. Jual", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Nama Pasien", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Alamat Pasien", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Grand Total", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Kasir", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div>
</div>