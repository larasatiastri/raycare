<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Pencairan Klaim BPJS", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
           <div class="actions">
				<a href="<?=base_url()?>keuangan/proses_pencairan_klaim" class="btn btn-circle btn-default"> <i class="fa fa-chevron-left"></i> 
	           		<span class="hidden-480">
	                     <?=translate("Kembali", $this->session->userdata("language"))?>
	                </span>
	            </a>
			</div>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_proses_klaim_history">
			<thead>
				<tr>
					<th class="text-center"><?=translate("Periode", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No. Surat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tarif INA CBGs", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tindakan", $this->session->userdata("language"))?><br>(<?=translate("Verifikasi", $this->session->userdata("language"))?>)</th>
					<th class="text-center"><?=translate("Jumlah Tarif INA CBGs", $this->session->userdata("language"))?><br>(<?=translate("Verifikasi", $this->session->userdata("language"))?>)</th>
					<th class="text-center"><?=translate("Biaya Admin", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Total Diterima", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal Dibuat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal Diterima", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
