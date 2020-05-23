<?php
	$td_filter = '<tr role="row" class="filter"><td><div class="text-center"></div></td> <td><div class="text-center"></div></td>  <td><div class="text-center"></div></td><td><div class="text-center"></div></td> <td><div class="text-center"></div></td> <td><div class="text-center"> <select name="pembelian_baru_status" id="pembelian_baru_status" class="form-control form-filter input-sx"> <option value="">'. translate("Semua", $this->session->userdata("language")).'</option> <option value="0">'. translate("Menunggu Persetujuan", $this->session->userdata("language")).'</option> <option value="1">'. translate("Proses Persetujuan", $this->session->userdata("language")).'</option> <option value="2">'. translate("Disetujui", $this->session->userdata("language")).'</option> <option value="5">'. translate("Ditolak", $this->session->userdata("language")).'</option></select></div></td><td><div class="text-center"></div></td>';
			
	$form_attr = array(
	    "id"            => "form_index_pembelian", 
	    "name"          => "form_index_pembelian", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-history font-blue-sharp bold"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Pembelian", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>pembelian/pembelian_obat" class="btn btn-default btn-circle">
                <i class="fa fa-chevron-left"></i>
                <span class="hidden-480">
                     <?=translate("Kembali", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<table class="table table-striped table-bordered table-hover" id="table_pembelian_history">
		<thead>
			<tr>
				<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("No PO", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="20%"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("No.Cetak", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</thead>
		<tbody>
		
		</tbody>
	</table>
</div>
<div class="modal fade" id="modal_send_po" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        </div>
    </div>
</div>

