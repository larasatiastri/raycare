<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="fa fa-list font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Dokumen PO", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>akunting/daftar_faktur_pajak/history" class="btn btn-circle btn-default">
                <i class="fa fa-history"></i>
                <span class="hidden-480">
                     <?=translate("History", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div> 
	<!--end of <div class="portlet-title">-->
	<div class="portlet-body form">
		<table class="table table-striped table-bordered table-hover" id="table_daftar_faktur_pajak">
		<thead>
		<tr class="heading">
			<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("No. PO", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="5%"><?=translate("Invoice", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("No. Invoice", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Tanggal Invoice", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Total Invoice", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="5%"><?=translate("Faktur Pajak", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div> <!--end of <div class="portlet-body form">-->
</div> <!--end of <div class="portlet light">-->