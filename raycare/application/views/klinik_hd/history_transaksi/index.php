<div class="portlet light">
<div class="portlet-title">
	<div class="caption">
        <i class="fa fa-list font-blue-sharp"> </i>
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Histori Transaksi Pasien", $this->session->userdata("language"))?></span>
	</div>
	<div class="actions">
		<a class="btn default" href="javascript:history.go(-1)">
		<i class="fa fa-chevron-left"></i>
			<?=translate("Kembali", $this->session->userdata("language"))?>
		</a>
	</div>
	 
</div>
<div class="portlet-body">	 
	<table class="table table-condensed table-striped table-hover" id="table_cabang3">
		<thead>
			<tr role="row">
				<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No.Transaksi", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No. Pasien", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Nama Pasien", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Nama Dokter", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Resepsionis", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("BB pre HD", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("BB post HD", $this->session->userdata("language"))?> </th>
		 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		 	 
			</tr>
		</thead>
		<tbody>
			 
		</tbody>
	</table>
</div>
</div>
<div class="modal fade" id="modal_upload" role="basic" aria-hidden="true">
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
<div class="modal fade" id="modal_upload_setuju" role="basic" aria-hidden="true">
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
<div class="modal fade" id="modal_upload_sep" role="basic" aria-hidden="true">
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
<div class="modal fade" id="modal_dokumen_pasien" role="basic" aria-hidden="true">
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
