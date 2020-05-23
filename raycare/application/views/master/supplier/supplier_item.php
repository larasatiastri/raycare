<div class="portlet light"> <!-- begin of class="portlet light" supplier item-->
	<div class="portlet-title"> <!-- begin of class="portlet-title" supplier item-->
		<div class="caption">
			<i class="fa fa-briefcase font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Supplier Item", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions"> <!-- begin of class="actions" supplier item-->
			<a class="btn btn-circle btn-default" href="<?=base_url()?>master/supplier">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
        </div> <!-- end of class="actions" supplier item-->
	</div> <!-- end of class="portlet-title" supplier item-->
	<div class="portlet-body"> <!-- begin of class="portlet-body" supplier item-->
		<input type="hidden" id="supplier_id" value="<?=$supplier_id?>">
		<table class="table table-striped table-bordered table-hover" id="table_supplier_item">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Order", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Harga Saat Ini", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("HET Pusat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Presentasi", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal Efektif", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div> <!-- end of class="portlet-body" supplier item-->
</div> <!-- end of class="portlet light" supplier item-->

<!-- begin of modal harga -->
<div class="modal fade bs-modal-lg" id="popup_modal_harga" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">

       </div>
   </div>
</div>
<!-- end of modal harga -->

<!-- begin of modal order -->
<div class="modal fade bs-modal-lg" id="popup_modal_order" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">

       </div>
   </div>
</div>
<!-- end of modal order -->