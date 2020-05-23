<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-money font-blue-sharp"></i> 
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Pembayaran Reimburse", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-primary btn-circle" href="<?=base_url()?>keuangan/proses_pembayaran_reimburse/proses_batch">
				<i class="fa fa-plus"></i> 
				<?=translate('Proses', $this->session->userdata('language'))?>
			</a>
			<a class="btn btn-default btn-circle" href="<?=base_url()?>keuangan/proses_pembayaran_reimburse/history">
				<i class="fa fa-history"></i> 
				<?=translate('History', $this->session->userdata('language'))?>
			</a>
			
		</div>
	</div>
	
	<div class="portlet-body">

		<table class="table table-striped table-hover" id="table_pembayaran_transaksi_reimburse">
			<thead>
			<tr>
				<th class="text-center" width="5%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="8%"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="5%"><?=translate("Tipe", $this->session->userdata("language"))?> </th>
				<th class="text-center" width=""><?=translate("Ref", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Posisi", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="5%"><?=translate("Waktu Akhir", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
			</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade bs-modal-lg" id="modal_proses_kasbon" role="basic" aria-hidden="true">
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
<div class="modal fade bs-modal-lg" id="modal_pencairan_kasbon" role="basic" aria-hidden="true">
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
<div class="modal fade bs-modal-lg" id="modal_revisi" role="basic" aria-hidden="true">
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