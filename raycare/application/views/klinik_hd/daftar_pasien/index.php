<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-user-follow font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pasien", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">

        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_pasien">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No.Kartu", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tempat, Tanggal Lahir", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Alamat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tempat Daftar", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>

<!-- begin of modal_tolak -->
<div class="modal fade bs-modal-lg" id="modal_surat" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog">
       <div class="modal-content">

       </div>
   </div>
</div>
