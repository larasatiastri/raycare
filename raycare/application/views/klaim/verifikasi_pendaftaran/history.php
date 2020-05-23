<div class="portlet light"> <!-- begin of class="portlet light" history verifikasi_pendaftaran -->
	<div class="portlet-title"> <!-- begin of class="portlet-title" history verifikasi_pendaftaran -->
		<div class="caption">
			<i class="fa fa-check-square-o font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Verifikasi Pendaftaran", $this->session->userdata("language"))?></span>
		</div>
    <div class="actions">
        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
          <i class="fa fa-chevron-left"></i>
          <?=translate("Kembali", $this->session->userdata("language"))?>
        </a>
    </div>
	</div> <!-- end of class="portlet-title" history verifikasi_pendaftaran -->
	<div class="portlet-body"> <!-- begin of class="portlet-body" history verifikasi_pendaftaran -->
		<table class="table table-striped table-bordered table-hover" id="table_history_verifikasi_pendaftaran">
			<thead>
				<tr>
					<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Tanggal Daftar", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Poliklinik", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
          <th class="text-center"><?=translate("User", $this->session->userdata("language"))?></th>
          <th class="text-center"><?=translate("User Verif", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal Verif", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div> <!-- end of class="portlet-body" history verifikasi_pendaftaran -->
</div> <!-- end of class="portlet light" history verifikasi_pendaftaran -->

<!-- begin of modal_proses -->
<div class="modal fade bs-modal-lg" id="popup_modal_proses" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
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
<!-- end of modal_proses -->

<!-- begin of modal_tolak -->
<div class="modal fade bs-modal-lg" id="popup_modal_tolak" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
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
<!-- end of modal_tolak -->