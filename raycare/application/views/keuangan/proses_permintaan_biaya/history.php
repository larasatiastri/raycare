
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-money font-blue-sharp"></i> 
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Pembayaran Kasbon & Rembes", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="<?=base_url()?>keuangan/proses_permintaan_biaya">
				<i class="fa fa-chevron-left"></i> 
				<?=translate('Kembali', $this->session->userdata('language'))?>
			</a>
			
		</div>
	</div>
	
	<div class="portlet-body">

		<table class="table table-striped table-bordered table-hover" id="table_proses_permintaan_biaya_history">
			<thead>
			<tr>
				<th class="text-center" width="10%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Tipe", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Keperluan", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
			</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
</div>