 
	<div class="portlet light bordered">
		<div class="portlet-title">
			 <div class="caption">
				<span class="caption-subject"><?=translate("Daftar Dokumen", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
	            <a id="adddoc" href="<?=base_url()?>reservasi/pendaftaran_tindakan/add_dokumen/" data-target="#ajax_notes" data-toggle="modal" class="btn btn-circle btn-icon-only btn-default">
	                <i class="fa fa-plus"></i>
	                
	            </a>
	            <a id="refresh_upload" class="btn btn-primary hidden">
	                <i class="fa fa-undo"></i>
	                
	            </a>
	        </div>
		</div>
		<div class="portlet-body">
			<table class="table table-striped table-bordered table-hover" id="table_cabang4">
				<thead>
					<tr role="row">
						<th class="text-center"><?=translate("Nama Dokumen", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Jenis", $this->session->userdata("language"))?> </th>
						 <th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
				 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				 		<th class="text-center" width="200"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				 		<th class="text-center" width="200"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
 
	</div>

 
 

 
 