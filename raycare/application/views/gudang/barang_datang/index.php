<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-shopping-cart font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pembelian Akan Datang", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover table-condensed" id="table_pembelian_datang">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Tgl.Pesan", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("No.PO", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Supplier", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Barang", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Tgl.Datang", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("PJ.Pembelian", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>	
</div>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cube font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penerimaan Barang", $this->session->userdata("language"))?></span>
		</div>

		<div class="actions">
		<a href="<?=base_url()?>gudang/barang_datang/modal_add" id="modal_add" class="btn btn-primary hidden" data-toggle="modal" data-target="#popup_modal_add"><?=translate("Tambah", $this->session->userdata("language"))?></a>

			<div class="btn-group">
	            <?php
	            	$user_level_id = $this->session->userdata('level_id');
	            	$data = '<a id="tambah" class="btn btn-default btn-circle"> <i class="fa fa-plus"></i> <span class="hidden-480"> '.translate("Tambah", $this->session->userdata("language")).'</span> </a>';
	            	
	            	$option_gudang = array(
	            		''  => translate('Pilih Gudang', $this->session->userdata('language')).'...',
	            	);
	            	$data_gudang = $this->gudang_m->get_by(array('cabang_klinik' => $this->session->userdata('cabang_id'), 'is_active' => 1));
	            	foreach ($data_gudang as $row) {
	            		$option_gudang[$row->id] = $row->nama;
	            	}
	            	echo form_dropdown('gudang_id', $option_gudang, '', 'id="gudang_id" class="form-control input-sm"');
	            ?>
				
			</div>
            <?php 
            echo restriction_button($data,$user_level_id,'gudang_barang_datang','add');
            ?>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover" id="table_barang_datang">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("No.PMB", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("No.Surat Jalan", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Tanggal Datang", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>	
</div>



<div class="modal fade bs-modal-lg" id="popup_modal_add" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
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

<div class="modal fade bs-modal-lg" id="popup_modal_add_po" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
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