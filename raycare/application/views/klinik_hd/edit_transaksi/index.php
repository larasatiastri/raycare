<div class="portlet light">
<div class="portlet-title">
	<div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan HD Pasien", $this->session->userdata("language"))?></span>
	</div>

	<div class="actions">
		<a class="btn btn-circle btn-primary" id="btn-add" href="<?=base_url()?>klinik_hd/edit_transaksi/add">
			<i class="fa fa-plus"></i>
		</a>
	</div>
	 
</div>
<div class="portlet-body">	 
	<table class="table table-condensed table-striped table-bordered table-hover" id="table_cabang3">
		<thead>
			<tr role="row">
				<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No.Transaksi", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No. Pasien", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Nama Pasien", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Nama Dokter", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("BB pre HD", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("BB post HD", $this->session->userdata("language"))?> </th>
		 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		 	 
			</tr>
		</thead>
		<tbody>
			 
		</tbody>
	</table>
</div>
</div>


<div class="modal fade bs-modal-lg" id="modal_add" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>
<div class="modal fade bs-modal-lg" id="popup_konfirm_password" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>
