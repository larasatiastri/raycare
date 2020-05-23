 
	<div class="portlet light">
		<div class="portlet-title">
			 <div class="caption">
			 	<?=translate("Daftar Dokumen", $this->session->userdata("language"))?>
			 </div>
			<div class="actions">
	            <a id="adddoc" data-target="#modal_add_dokumen" href="<?=base_url()?>klinik_hd/transaksi_dokter/add_dokumen/<?=$pasien_id?>" data-toggle="modal" class="btn btn-circle btn-icon-only btn-default">
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
						 <th class="text-center"><?=translate("Status", $this->session->userdata("language"))?> </th>
				 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
 
	</div>

 
 

 
 