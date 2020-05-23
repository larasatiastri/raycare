	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Kode PPH 23", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
				
				<a href="<?=base_url()?>akunting/proses_pph_23/history" class="btn default"> <i class="fa fa-history"></i> <span class="hidden-480"><?=translate("Histori", $this->session->userdata("language"))?></span> </a>
			
	        </div>
		</div>
		<div class="portlet-body">
			<table class="table table-condensed table-striped table-hover" id="table_pph_23">
				<thead>
					<tr role="row">
						<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("No.Pengajuan", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="15%"><?=translate("No. PO", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="30%"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
						<th class="text-center"width="10%"><?=translate("Status", $this->session->userdata("language"))?> </th>
						<th class="text-center"width="20%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
				 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div><!-- akhir dari portlet -->