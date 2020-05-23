<div class="portlet light"> <!-- begin of class="portlet light" verifikasi_pendaftaran -->
	<div class="portlet-title"> <!-- begin of class="portlet-title" verifikasi_pendaftaran -->
		<div class="caption">
			<i class="fa fa-check-square-o font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Verifikasi Pendaftaran", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions"> <!-- begin of class="actions" verifikasi_pendaftaran -->
            <?php
            	//tambahkan data ke tabel fitur_tombol. Field page="user_level", button="add"
            	$user_level_id = $this->session->userdata('level_id');
            	
            	$data = '<a href="'.base_url().'klaim/verifikasi_pendaftaran/history" class="btn btn-circle btn-default"> <i class="fa fa-history"></i> <span class="hidden-480"> '.translate("History", $this->session->userdata("language")).' </span> </a>';
            	echo restriction_button($data, $user_level_id, 'master_verifikasi_pendaftaran','history');
            ?>
        </div> <!-- end of class="actions" verifikasi_pendaftaran -->
	</div> <!-- end of class="portlet-title" verifikasi_pendaftaran -->
	<div class="portlet-body"> <!-- begin of class="portlet-body" verifikasi_pendaftaran -->
		<table class="table table-striped table-bordered table-hover" id="table_verifikasi_pendaftaran">
			<thead>
				<tr>
          <th class="text-center"><?=translate("No. RM", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Nama Pasien", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Tanggal Daftar", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Poliklinik", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("User", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div> <!-- end of class="portlet-body" verifikasi_pendaftaran -->
</div> <!-- end of class="portlet light" verifikasi_pendaftaran -->

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