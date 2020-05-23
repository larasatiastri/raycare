<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-money font-blue-sharp"></i> 
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Pembayaran Reimburse", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			
			<a class="btn btn-default btn-circle" href="<?=base_url()?>keuangan/proses_pembayaran_reimburse">
				<i class="fa fa-chevron-left"></i> 
				<?=translate('Kembali', $this->session->userdata('language'))?>
			</a>
			
		</div>
	</div>
	
	<div class="portlet-body">

		<table class="table table-striped table-hover" id="table_pembayaran_transaksi_reimburse_history">
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
