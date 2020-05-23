<?php
	$td_filter = '<tr role="row" class="filter"><td><div class="text-center"></div></td> <td><div class="text-center"></div></td>  <td><div class="text-center"></div></td><td><div class="text-center"></div></td> <td><div class="text-center"></div></td> <td><div class="text-center"> <select name="pembelian_status" id="pembelian_status" class="form-control form-filter input-sx"> <option value="">'. translate("Semua", $this->session->userdata("language")).'</option> <option value="1">'. translate("Ditolak", $this->session->userdata("language")).'</option> <option value="2">'. translate("Diproses", $this->session->userdata("language")).'</option></select></div></td>';

?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-building font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Pabrik", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>master/pabrik/add" data-toggle="modal" class="btn green">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_daftar_pabrik">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="15%"><?=translate("Contact Person", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Alamat", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Telepon", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>
			<tbody>
			

			</tbody>
		</table>
	</div>
</div>

<div id="popover_item_content" class="row">
    <div class="col-md-12">
    	<div class="portlet">
			<div class="portlet-body">
		        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item">
		            <thead>
		                <tr role="row" class="heading">
		                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Telepon', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>
		            </tbody>
		        </table>
		    </div>
		</div>
		
    </div>
</div>