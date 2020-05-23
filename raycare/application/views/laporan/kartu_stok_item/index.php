<?php
	$form_attr = array(
	    "id"            => "form_kartu_stok_item", 
	    "name"          => "form_kartu_stok_item", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Grafik Arus Keluar Masuk Barang", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a id="cetak_csv" class="btn btn-circle btn-primary hidden" target="_blank" href="<?=base_url()?>laporan/arus_barang/cetak_csv">
				<i class="fa fa-file-excel-o"></i>
				<?=translate("Download CSV", $this->session->userdata("language"))?>
			</a>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-12">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<?=translate("Filter", $this->session->userdata("language"))?>
						</div>
					</div>
					<div class="portlet-body">
						<div class="form-body">
							<div class="form-group">
								<label class="col-md-3"><?=translate("Tanggal", $this->session->userdata("language"))?> : </label>
								<label class="col-md-3"><?=translate("Gudang", $this->session->userdata("language"))?> : </label>
								<label class="col-md-3"><?=translate("Kategori", $this->session->userdata("language"))?> : </label>
								<label class="col-md-3"><?=translate("Sub Kategori", $this->session->userdata("language"))?> : </label>
								
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<div id="reportrange" class="btn default">
										<i class="fa fa-calendar"></i>
										&nbsp; <span>
										</span>
										<b class="fa fa-angle-down"></b>
									</div>
									<input type="hidden" class="form-control" id="tgl_awal" name="tgl_awal"></input>
									<input type="hidden" class="form-control" id="tgl_akhir" name="tgl_akhir"></input>
								</div>
								<div class="col-md-3">
									<?php
										$option_gudang = array(
						            	);
						            	$data_gudang = $this->gudang_m->get_by(array('is_active' => 1));
						            	foreach ($data_gudang as $row) {
						            		$option_gudang[$row->id] = $row->nama;
						            	}
						            	echo form_dropdown('gudang_id', $option_gudang, '', 'id="gudang_id" class="form-control"');
									?>
								</div>
								<div class="col-md-3">
									<?php
										$kategori_id =  $this->item_kategori_m->get_by(array('is_active' => 1));
										$kategori = object_to_array($kategori_id);
										// die_dump($gudang);
										$sub_kategori = array(
											0 => "Semua.."
										);
										foreach ($kategori as $data_kategori) {
											$sub_kategori[$data_kategori['id']] = $data_kategori['nama'];
										}
										//die_dump($alamat_sub_option);
										
										echo form_dropdown('item_kategori', $sub_kategori, '', "id=\"tipe_kategori\" class=\"form-control warehouse\"");
									?>		
								</div>
								<div class="col-md-3">
									<?php
										$sub_option = array(
											0 => "Semua.."
										);

																		
										echo form_dropdown('item_sub_kategori', $sub_option, '', "id=\"tipe_sub_kategori\" class=\"form-control warehouse\" ");
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-9"><?=translate("Item", $this->session->userdata("language"))?> : </label>
								
								<label class="col-md-3"></label>
								
							</div>
							<div class="form-group">
								
								<div class="col-md-9">
									<?php
										
										$item = $this->item_m->get_by(array('is_active' => '1'));
										// die(dump($this->db->last_query()));
										$item_option = array();
										foreach ($item as $row) {
											$item_option[$row->id] = $row->nama;
										}

										echo form_dropdown('item_id', $item_option, '', 'id="item_id" class="form-control select2" multiple');
									?>
								</div>
								<div class="col-md-3">
									<a class="btn btn-circle btn-default btn-block" id="refresh">
										<i class="fa fa-search"></i>
										<?=translate("Cari", $this->session->userdata("language"))?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject uppercase">
						      <?=translate("Tabel Arus Masuk Keluar Barang", $this->session->userdata("language"))?>
						    </span>
						</div>
					</div>
					<div class="portlet-body">
					<style>
.container-fix {
  overflow-y: auto;
  height: 300px;
}
/*th {
  height: 0;
  line-height: 0;
  padding-top: 0;
  padding-bottom: 0;
  color: transparent;
  border: none;
  white-space: nowrap;
}
th{
  position: fixed;
  background: transparent;
  color: #fff;
  padding: 9px 25px;
  top: 50;
  margin-left: -25px;
  line-height: normal;
  border-left: 1px solid #800;
}
*/

					</style>
						<div class="container-fix">
						<table class="table table-striped table-bordered table-hover table-header-fixed" id="table_kartu_stok">
							<thead>
								<tr style="background-color:#2462AC;color:#fff;">
									<th class="text-center" width="1%" rowspan="3" style="vertical-align: middle;"><?=translate("No", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="2%" rowspan="3" style="vertical-align: middle;"><?=translate("Tgl", $this->session->userdata("language"))?> </th>
									<th class="text-center" rowspan="3" width="1%" style="vertical-align: middle;"><?=translate("Kode", $this->session->userdata("language"))?></th>
									<th class="text-center" rowspan="3" style="vertical-align: middle;"><?=translate("Nama", $this->session->userdata("language"))?></th>				
									<th class="text-center" rowspan="3" width="1%" style="vertical-align: middle;"><?=translate("Satuan", $this->session->userdata("language"))?></th>				
									<th class="text-center" rowspan="3" width="1%" style="vertical-align: middle;"><?=translate("BN", $this->session->userdata("language"))?></th>				
									<th class="text-center" colspan="5" style="border-bottom:1px solid #fff;"><?=translate("Masuk", $this->session->userdata("language"))?></th>				
									<th class="text-center" colspan="5" style="border-bottom:1px solid #fff;"><?=translate("Keluar", $this->session->userdata("language"))?></th>				
									<th class="text-center" colspan="5" style="border-bottom:1px solid #fff;"><?=translate("Stok", $this->session->userdata("language"))?></th>				
								</tr>
								<tr style="background-color:#2462AC;color:#fff;">
									<th class="text-center" colspan="5" id="grand_total_masuk" style="border-bottom:1px solid #fff;"><b>Rp. 0,- </b></th>			
									<th class="text-center" colspan="5" id="grand_total_keluar" style="border-bottom:1px solid #fff;"><b>Rp. 0,- </b></th>			
									<th class="text-center" colspan="5" id="grand_total_stok" style="border-bottom:1px solid #fff;"><b>Rp. 0,- </b></th>			
								</tr>
								<tr style="background-color:#2462AC;color:#fff;">
									<th class="text-center" width="1%" style="vertical-align: middle;"><?=translate("Jml", $this->session->userdata("language"))?> </th>
									<th class="text-center inline-button-table" colspan="2" width="5%"><?=translate("Harga /Unit", $this->session->userdata("language"))?></th>
									<th class="text-center inline-button-table" colspan="2" width="5%" style="vertical-align: middle;"><?=translate("Total Harga", $this->session->userdata("language"))?></th>				
									<th class="text-center" width="1%" style="vertical-align: middle;"><?=translate("Jml", $this->session->userdata("language"))?> </th>
									<th class="text-center inline-button-table" colspan="2" width="5%"><?=translate("Harga /Unit", $this->session->userdata("language"))?></th>
									<th class="text-center inline-button-table" colspan="2" width="5%" style="vertical-align: middle;"><?=translate("Total Harga", $this->session->userdata("language"))?></th>

									<th class="text-center" width="1%" style="vertical-align: middle;"><?=translate("Jml", $this->session->userdata("language"))?> </th>
									<th class="text-center inline-button-table" colspan="2" width="5%"><?=translate("Harga /Unit", $this->session->userdata("language"))?></th>
									<th class="text-center inline-button-table" colspan="2" width="5%" style="vertical-align: middle;"><?=translate("Total Harga", $this->session->userdata("language"))?></th>				
								</tr>
							</thead>

							<tbody id="table_kartu_stok_item">

							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>
							
		</div>
	</div>
</div>
<div class="modal fade bs-modal-lg" id="modal_detail" role="basic" aria-hidden="true">
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

<?=form_close()?>
