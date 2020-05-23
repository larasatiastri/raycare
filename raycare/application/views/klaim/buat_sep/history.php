<?php

	$form_attr = array(
	    "id"            => "form_index_sep", 
	    "name"          => "form_index_sep", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
		    echo form_open(base_url()."klaim/buat_sep/save", $form_attr);
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Buat SEP Tindakan", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
           <a href="javascript:history.go(-1)" class="btn btn-circle btn-default">
           		<i class="fa fa-chevron-left"></i>
                <span class="hidden-480">
                     <?=translate("Kembali", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_buat_sep_history">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("No. Tindakan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No. SEP", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal SEP", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No. RM", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Nama Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Dokter", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="ajax_notes1" role="basic" aria-hidden="true">
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
