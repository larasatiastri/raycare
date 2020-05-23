<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-check font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Persetujuan PO", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>pembelian/persetujuan_po/history" class="btn default">
                <i class="fa fa-history"></i>
                <span class="hidden-480">
                     <?=translate("History", $this->session->userdata("language"))?>
                </span>
            </a>
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
					<th class="text-center" width="8%"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Posisi", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
</div>
