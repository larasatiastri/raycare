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

<div class="tabbable-custom nav-justified">
	<ul class="nav nav-tabs nav-justified">
		<li class="hidden">
			<a href="#tab_draft_pembelian" data-toggle="tab">
			Draft Pembelian </a>
		</li>
		<li class="active" >
			<a href="#tab_pembelian_baru" data-toggle="tab">
			Pembelian Baru </a>
		</li>
		<li>
			<a href="#tab_pembelian_proses" data-toggle="tab">
			Pembelian Dalam Proses </a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane hidden" id="tab_draft_pembelian">
			<table class="table table-striped table-hover" id="table_draft_permintaan">
				<thead>
					<tr>
						<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="20%"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
		</div>
		<div class="tab-pane active" id="tab_pembelian_baru">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-shopping-cart font-blue-sharp bold"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pembelian Baru", $this->session->userdata("language"))?></span>
					</div>
					<div class="actions">
			            <a href="<?=base_url()?>pembelian/pembelian_alkes/add" class="btn btn-default btn-circle">
			                <i class="fa fa-plus"></i>
			                <span class="hidden-480">
			                     <?=translate("Tambah", $this->session->userdata("language"))?>
			                </span>
			            </a>
			        </div>
				</div>
<!-- 						<div class="portlet-body"> -->
					<div id="thead-filter-template" class="hidden"><?=htmlentities($td_filter)?></div>
					<table class="table table-striped table-hover" id="table_pembelian_baru">
						<thead>
							<tr>
								<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="1%"><?=translate("No PO", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="20%"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="10%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="10%"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="1%"><?=translate("Posisi", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
							</tr>
						</thead>
						<tbody>
						
						</tbody>
					</table>
<!-- 						</div> -->
			</div>
		</div>
		<div class="tab-pane" id="tab_pembelian_proses">
			<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-shopping-cart font-blue-sharp bold"></i>
							<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pembelian Dalam Proses", $this->session->userdata("language"))?></span>
						</div>
						<div class="actions">
				            <a href="<?=base_url()?>pembelian/pembelian_alkes/history" class="btn btn-default btn-circle">
				                <i class="fa fa-undo"></i>
				                <span class="hidden-480">
				                     <?=translate("History", $this->session->userdata("language"))?>
				                </span>
				            </a>
				        </div>
					</div>
				<table class="table table-striped table-hover" id="table_pembelian_proses">
					<thead>
						<tr>
							<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="1%"><?=translate("No PO", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="10%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="10%"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="1%"><?=translate("No.Cetak", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</thead>
					<tbody>
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>	



<div id="popover_item_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-hover" id="table_pilih_cetak">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No Cetak', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('User', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tanggal Cetak', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="popup_modal" role="basic" aria-hidden="true">
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
<div class="modal fade" id="modal_finish_po" role="basic" aria-hidden="true">
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
<div class="modal fade" id="modal_cancel_po" role="basic" aria-hidden="true">
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


