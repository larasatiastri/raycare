<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Antrian Laboratorium", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body">
		<table class="table table-condensed table-striped table-hover" id="table_antrian_lab">
			<thead>
				<tr role="row">
			 		<th class="text-center" width="1%"><?=translate("No.", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="15%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="30%"><?=translate("No. Telp", $this->session->userdata("language"))?> </th>
			 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div><!-- akhir dari portlet -->