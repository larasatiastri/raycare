<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Klaim BPJS", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
           <a href="<?=base_url()?>klaim/proses_klaim/history" class="btn btn-circle btn-default"> <i class="fa fa-history"></i> 
           		<span class="hidden-480">
                     <?=translate("History", $this->session->userdata("language"))?>
                </span>
            </a>
            <a href="<?=base_url()?>klaim/proses_klaim/add" class="btn btn-circle btn-primary"> <i class="fa fa-plus"></i> 
           		<span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_proses_klaim">
			<thead>
				<tr>
					<th class="text-center"><?=translate("Periode", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No. Surat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tindakan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tarif Riil RS ", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tarif INA CBGs", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jumlah Tindakan", $this->session->userdata("language"))?><br>(<?=translate("Verifikasi", $this->session->userdata("language"))?>)</th>
					<th class="text-center"><?=translate("Jumlah Tarif INA CBGs", $this->session->userdata("language"))?><br>(<?=translate("Verifikasi", $this->session->userdata("language"))?>)</th>
					<th class="text-center"><?=translate("Tanggal Dibuat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
