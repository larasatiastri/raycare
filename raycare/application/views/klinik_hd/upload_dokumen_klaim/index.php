<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-note font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Upload Dokumen Penunjang Klaim", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
		<a class="btn btn-default" id="btn_refresh_table"><i class="fa fa-recycle"></i>Reload</a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover" id="table_os_tindakan_hd">
		<thead>
		<tr>
			<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Tgl.Tindakan", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
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