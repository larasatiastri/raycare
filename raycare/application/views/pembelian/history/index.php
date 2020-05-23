<?php
	$td_filter = '<tr role="row" class="filter"><td><div class="text-center"></div></td> <td><div class="text-center"></div></td>  <td><div class="text-center"></div></td><td><div class="text-center"></div></td> <td><div class="text-center"></div></td> <td><div class="text-center"> <select name="pembelian_status" id="pembelian_status" class="form-control form-filter input-sx"> <option value="">'. translate("Semua", $this->session->userdata("language")).'</option> <option value="1">'. translate("Diproses", $this->session->userdata("language")).'</option> <option value="2">'. translate("Ditolak", $this->session->userdata("language")).'</option></select></div></td>';

	$td_filter2 = '<tr role="row" class="filter"><td><div class="text-center"></div></td> <td><div class="text-center"></div></td>  <td><div class="text-center"></div></td><td><div class="text-center"></div></td> <td><div class="text-center"></div></td> <td><div class="text-center"> <select name="pembelian_baru_status" id="pembelian_baru_status" class="form-control form-filter input-sx"> <option value="">'. translate("Semua", $this->session->userdata("language")).'</option> <option value="10">'. translate("Kadaluarsa", $this->session->userdata("language")).'</option> <option value="8">'. translate("Dihapus", $this->session->userdata("language")).'</option> <option value="7">'. translate("Diterima", $this->session->userdata("language")).'</option> <option value="6">'. translate("Ditolak", $this->session->userdata("language")).'</option></select></div></td><td><div class="text-center"></div></td>';

?>

	
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-history font-blue-sharp"> </i>
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Pembelian", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
					<a id="load_table"></a>
					<a href="<?=base_url()?>pembelian/history/laporan_pembelian" class="btn default">
			                <i class="fa fa-file"></i>
			                <span class="hidden-480">
			                     <?=translate("Laporan Pembelian", $this->session->userdata("language"))?>
			                </span>
			            </a>
				</div>
			</div>
			<div id="thead-filter-template2" class="hidden"><?=htmlentities($td_filter2)?></div>
			<table class="table table-striped table-hover" id="table_pembelian">
				<thead>
					<tr>
						<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("No PO", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Total", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Dibuat oleh", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
		</div>
	


<div id="popover_item_content" class="row">
    <div class="col-md-12">
    	<ul class="nav nav-tabs">
			<li class="active">
				<a href="#terdaftar" data-toggle="tab">
				<?=translate('Item Yang Terdaftar', $this->session->userdata('language'))?> </a>
			</li>
			<li>
				<a href="#tidak_terdaftar" data-toggle="tab">
				<?=translate('Item Yang Tidak Terdaftar', $this->session->userdata('language'))?> </a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="terdaftar">
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Item Yang Terdaftar", $this->session->userdata("language"))?></span>
						</div>
					</div>
					<div class="portlet-body">
				        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item">
				            <thead>
				                <tr role="row" class="heading">
				                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
				                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
				                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
				                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
				                </tr>
				            </thead>
				            <tbody>
				            </tbody>
				        </table>
				    </div>
				</div>
			</div>
			<div class="tab-pane" id="tidak_terdaftar">
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Item Yang Tidak Terdaftar", $this->session->userdata("language"))?></span>
						</div>
					</div>
					<div class="portlet-body">
				        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item_tidak_terdaftar">
				            <thead>
				                <tr role="row" class="heading">
				                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
				                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
				                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
				                </tr>
				            </thead>
				            <tbody>
				            </tbody>
				        </table>
				    </div>
				</div>
			</div>
		</div>
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
