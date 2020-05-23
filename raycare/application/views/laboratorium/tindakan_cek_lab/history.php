<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Tindakan Lab", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			
			<a href="<?=base_url()?>laboratorium/tindakan_cek_lab" class="btn default"> <i class="fa fa-chevron-left"></i> <span class="hidden-480"><?=translate("Kembali", $this->session->userdata("language"))?></span> </a>
		
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-condensed table-striped table-hover" id="table_tindakan_lab_history">
			<thead>
				<tr role="row">
			 		<th class="text-center" width="1%"><?=translate("No.", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="2%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="5%"><?=translate("Tipe Pasien", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="15%"><?=translate("Pasien / JK", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="5%"><?=translate("Tgl.Lahir / Umur", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="8%"><?=translate("No. Telp", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Dokter", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
			 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div><!-- akhir dari portlet -->