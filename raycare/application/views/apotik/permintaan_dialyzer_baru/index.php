<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Permintaan Dialyzer Baru", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>apotik/permintaan_dialyzer_baru/history" class="btn btn-default btn-circle">
                <i class="fa fa-history"></i>
                <span class="hidden-480">
                     <?=translate("History", $this->session->userdata("language"))?>
                </span>
            </a>  
    	</div>
		
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_permintaan_dialyzer_baru">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Cabang", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No. Permintaan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Diminta Oleh", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Waktu Permintaan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Bed", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>



<div class="modal fade bs-modal-lg" id="modal_proses" role="basic" aria-hidden="true">
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