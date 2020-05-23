<?php
			$form_attr = array(
			    "id"            => "form_barang_datang", 
			    "name"          => "form_barang_datang", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "proses"
		    );

		    echo form_open(base_url()."gudang/barang_datang/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');

			// die_dump($form_data);
			$data_tanggal      = '';
			$no_surat_jalan    = '';
			$no_faktur         = '';
			$keterangan_gudang ='';
			// $id                = '';

			if($form_data['tanggal'] != null)
			{
				$data_tanggal = date("d F Y", strtotime($form_data['tanggal']));
			}
			if($form_data['no_surat_jalan'] != null)
			{
				$no_surat_jalan = $form_data['no_surat_jalan'];
			}
			if($form_data['no_faktur'] != null)
			{
				$no_faktur = $form_data['no_faktur'];
			}
			if($form_data['keterangan_gudang'] != null)
			{
				$keterangan_gudang = $form_data['keterangan_gudang'];
			}


		?>
		
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-cube font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penerimaan Barang", $this->session->userdata("language"))?></span>
					</div>

					<div class="actions">
						<?php $msg = translate("Apakah anda yakin akan membuat data barang datang ini?",$this->session->userdata("language"));?>
						<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
						<!-- <a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a> -->
						<!-- <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button> -->
					</div>
				</div>
				<div class="portlet-body form">
					<div class="row">
						<div class="col-md-3">
							<div class="portlet light bordered">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="form-group hidden">
										<label class="control-label col-md-12"><?=translate("Supplier Id", $this->session->userdata("language"))?> <span>:</span></label>
										<div class="col-md-12">
											<input type="hidden" id="supplier_id" name="supplier_id" value="<?=$supplier_id?>">
										</div>
									</div>
				
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Gudang", $this->session->userdata("language"))?> <span>:</span></label>
										<?php 
												$gudang = $this->gudang_m->get($gudang_id);
											?>
										<label class="col-md-12"><?=$gudang->nama?></label>
										<input type="hidden" name="gudang_id" value="<?=$gudang_id?>">
									</div>

									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("No.PO", $this->session->userdata("language"))?> <span>:</span></label>
										<div class="col-md-12">
											<ul class="list-unstyled" style="margin-bottom:0;">
												<?php 
													$i = 1;
													foreach ($pembelian_id as $data) {
														$get_no_po = $this->pembelian_m->get_by(array('id' => $data),true);
														echo '<li>'.$get_no_po->no_pembelian.'</li>';
														echo '<input id="no_po" name="po['.$i.'][po_id]" type="hidden" value="'.$get_no_po->id.'">';
													$i++;
													}
												?>
											</ul>
										</div>
									</div>

									<div class="form-group">
								        <label class="col-md-12 bold"><?=translate("Tanggal Datang", $this->session->userdata("language"))?> :</label>
								        <label class="col-md-12"><?=$data_tanggal?></label>
								    </div>

									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("No Surat Jalan", $this->session->userdata("language"))?> <span>:</span></label>
										<label class="col-md-12"><?=$no_surat_jalan?></label>
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Surat Jalan", $this->session->userdata("language"))?> <span>:</span></label>
										<div class="col-md-12">
											<input type="hidden" name="url_surat_jalan" id="url_surat_jalan" value="<?=$form_data['url_surat_jalan']?>">
											<div id="upload">

												<ul class="ul-img">
												<li class="working"><div class="thumbnail"><a class="fancybox-button" title="<?=$form_data['url_surat_jalan']?>" href="<?=config_item('base_dir')?>cloud/<?=config_item('site_dir')?>pages/gudang/barang_datang/images/<?=$pk_value?>/<?=$form_data['url_surat_jalan']?>" data-rel="fancybox-button"><img src="<?=config_item('base_dir')?>cloud/<?=config_item('site_dir')?>pages/gudang/barang_datang/images/<?=$pk_value?>/<?=$form_data['url_surat_jalan']?>" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a></div><span></span></li>
												</ul>

											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> <span>:</span></label>
										<label class="col-md-12"><?=$keterangan_gudang?></label>
									</div>

									<div class="form-group">
										<?php 
											$get_supplier = $this->supplier_m->get($supplier_id);
											$supplier = object_to_array($get_supplier);
										?>

										<label class="col-md-12 bold"><?=translate("Supplier", $this->session->userdata("language"))?> <span>:</span></label>
										<label class="col-md-12" style="text-align:left;"><?=$supplier['nama']?>&nbsp;"<?=$supplier['kode']?>"</label>
									</div>

									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Pihak Supplier", $this->session->userdata("language"))?> <span>:</span></label>
										<div class="col-md-5">
											<label class="control-label" style="text-align:left;"><?=$supplier['orang_yang_bersangkutan']?></label>
										</div>
									</div>

									<div class="form-group">
										<?php
											$get_supplier_telp = $this->supplier_telp_m->get_by(array('supplier_id' => $supplier_id));
											$supplier_telp = object_to_array($get_supplier_telp);
										?>
										<label class="col-md-12 bold"><?=translate("Kontak", $this->session->userdata("language"))?> <span>:</span></label>
										<div class="col-md-12">
											<ul style="list-style:none; padding-left: 0px; margin-bottom:0px;">
												<?php
													foreach ($supplier_telp as $telp) {
														$get_subjek_telp = $this->subjek_m->get($telp['subjek_telp_id']);
														$subjek_telp = object_to_array($get_subjek_telp);

														echo '<li style="padding-top: 3px;">'.$subjek_telp['nama'].'.'.$telp['no_telp'].'</li>';
													}
												?>
											</ul>

										</div>
									</div>

									<div class="form-group">
										<?php
											$get_supplier_alamat = $this->supplier_alamat_m->get_by(array('supplier_id' => $supplier_id));
											$supplier_alamat = object_to_array($get_supplier_alamat);
										?>
										<label class="col-md-12 bold"><?=translate("Alamat", $this->session->userdata("language"))?> <span>:</span></label>
										<div class="col-md-12">
											<ul style="list-style:none;padding-left: 0px;">
												<?php
													foreach ($supplier_alamat as $alamat) {
														echo '<li>'.$alamat['alamat'].'</li>';
													}
												?>
											</ul>
										</div>
									</div>
								</div>
							</div>


						</div>
						<div class="col-md-9">
							<div class="portlet light bordered">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject"><?=translate('Detail Item', $this->session->userdata('language'))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<?php 
										$data_detail = $this->pmb_detail_m->get_data_masuk($pk_value)->result_array();
										// die(dump($this->db->last_query()));
										
									?>
					                <span id="tpl_pembelian_row" class="hidden"><?=htmlentities($pembelian_detail_template)?></span>
					                <input type="hidden" id="identitasCounter" value="1">
									<input type="hidden" id="pembelianCounter" value="<?=$i?>">
									<table class="table table-striped table-hover table-condensed" id="table_pembelian_detail">
										<thead>
											<tr class="">
												<th class="text-center" width="15%"><?=translate("Kode", $this->session->userdata("language"))?></th>
												<th class="text-center" width="20%"><?=translate("Nama", $this->session->userdata("language"))?></th>
												<th class="text-center" width="30%"><?=translate("Jumlah Terima", $this->session->userdata("language"))?></th>
												<th class="text-center" width="30%"><?=translate("Batch Number", $this->session->userdata("language"))?></th>
												<th class="text-center inline-button-table" width="30%"><?=translate("Expire Date", $this->session->userdata("language"))?></th>
											</tr>
										</thead>
												
										<tbody>
											<?php 
												foreach ($data_detail as $row) {

													$jml_diterima = '0';
													$diterima = $this->pmb_detail_m->get_data_masuk_item($pk_value, $row['item_id'])->result_array();
													if(count($diterima)){
														$jml_diterima = '';
														foreach ($diterima as $row_terima) {
															$jml_diterima .= $row_terima['jumlah'].' '.$row_terima['nama_item_satuan'].', ';
														}
													}
													echo '<tr>
														<td>'.$row['kode_item'].'</td>
														<td>'.$row['nama_item'].'</td>
														<td>'.$row['jumlah'].' '.$row['nama_item_satuan'].'</td>
														<td>'.$row['bn_sn_lot'].'</td>
														<td>'.$row['expire_date'].'</td>
														</tr>';
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	


			
	</div>


<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_pesan" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
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

<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_pesan_supplier_lain" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
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

<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_terima" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
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

<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_terima_supplier_lain" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" >
       <div class="modal-content">

       </div>
   </div>
</div>

<div class="modal fade bs-modal-lg" id="popup_modal_identitas" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" style="width:1100px !important;">
       <div class="modal-content">

       </div>
   </div>
</div>

<div class="modal fade bs-modal-lg" id="popup_modal_identitas_view" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" style="width:1100px !important;">
       <div class="modal-content">

       </div>
   </div>
</div>

<div class="modal fade" id="popup_modal_pemisahan_item" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog" >
       <div class="modal-content">

       </div>
   </div>
</div>
<?=form_close()?>