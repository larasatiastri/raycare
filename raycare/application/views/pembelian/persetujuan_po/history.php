<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-history font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Persetujuan PO", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a class="btn default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover" id="table_pembelian">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("No PO", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="20%"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
</div>
