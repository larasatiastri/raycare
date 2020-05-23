<div class="portlet light">
	<div class="portlet-body form">
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
		
		<style>
			
		</style>
			<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-search font-blue-sharp bold"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penerimaan Masuk Barang", $this->session->userdata("language"))?></span>
					</div>

					<div class="actions">
						<?php $msg = translate("Apakah anda yakin akan membuat data barang datang ini?",$this->session->userdata("language"));?>
						<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
						<!-- <a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a> -->
						<!-- <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button> -->
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="row">
							<div class="col-md-3">
								<div class="portlet light bordered">
									<div class="portlet-title">
										<div class="caption">
											<?=translate("Informasi", $this->session->userdata("language"))?>
										</div>
									</div>
									<div class="portlet-body">
										<div class="form-body">
											<div class="form-group hidden">
												<label class="control-label col-md-4"><?=translate("Supplier Id", $this->session->userdata("language"))?> <span>:</span></label>
												<div class="col-md-4">
													<input type="hidden" id="supplier_id" name="supplier_id" value="<?=$supplier_id?>">
												</div>
											</div>
						
											<div class="form-group">
												<label class="col-md-12"><?=translate("Gudang", $this->session->userdata("language"))?> <span>:</span></label>
												<div class="col-md-12">
													<?php 
														$gudang = $this->gudang_m->get_by(array('id' => $gudang_id), true);
													?>
													<label class="control-label"><strong><?=$gudang->nama?></strong></label>
													<input type="hidden" name="gudang_id" value="<?=$gudang_id?>">
												</div>
											</div>


											<div class="form-group">
										        <label class="col-md-12"><?=translate("Tanggal Datang", $this->session->userdata("language"))?> :</label>
										        <div class="col-md-12">
										            <label class="control-label"><strong><?=$data_tanggal?></strong></label>
										        </div>
										    </div>

											<div class="form-group">
												<label class="col-md-12"><?=translate("No Surat Jalan", $this->session->userdata("language"))?> <span>:</span></label>
												<div class="col-md-12">
													<label class="control-label"><strong><?=$no_surat_jalan?></strong></label>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> <span>:</span></label>
												<div class="col-md-12">
													<label class="control-label"><strong><?=$keterangan_gudang?></strong></label>
												</div>
											</div>

											<div class="form-group">
											<?php 
												$get_supplier = $this->supplier_m->get($supplier_id);
												$supplier = object_to_array($get_supplier);
											?>

											<label class="col-md-12">Supplier :</label>
											<div class="col-md-12">
												<label class="control-label" style="text-align:left;"><strong><?=$supplier['nama']?>&nbsp;"<?=$supplier['kode']?>"</strong></label>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-12">Contact Person :</label>
											<div class="col-md-12">
												<label class="control-label" style="text-align:left;"><strong><?=$supplier['orang_yang_bersangkutan']?></strong></label>
											</div>
										</div>

										<div class="form-group">
											<?php
												$get_supplier_telp = $this->supplier_telp_m->get_by(array('supplier_id' => $supplier_id));
												$supplier_telp = object_to_array($get_supplier_telp);
											?>
											<label class="col-md-12">Telepon :</label>
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
											<label class="col-md-12">Alamat :</label>
											<div class="col-md-12">
												<ul style="list-style:none;padding-left: 0px;">
													<?php
														foreach ($supplier_alamat as $alamat) {
															echo '<li><strong>'.$alamat['alamat'].'</strong></li>';
														}
													?>
												</ul>
											</div>
										</div>
										</div>
									</div>	
								</div>
							</div>

							<div class="col-md-9">
							<div class="portlet light bordered">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Detail Item', $this->session->userdata('language'))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<?php 
										$data_detail = $this->pmb_detail_m->get_data_masuk($pk_value)->result_array();
										// die(dump($this->db->last_query()));
										
									?>
					                <span id="tpl_pembelian_row" class="hidden"><?=htmlentities($pembelian_detail_template)?></span>
					                <input type="hidden" id="identitasCounter" value="1">
									<table class="table table-bordered" id="table_pembelian_detail">
										<thead>
											<tr class="heading">
												<th class="text-center" width="15%"><?=translate("Kode", $this->session->userdata("language"))?></th>
												<th class="text-center" width="20%"><?=translate("Nama", $this->session->userdata("language"))?></th>
												<th class="text-center" width="30%"><?=translate("Jumlah Terima", $this->session->userdata("language"))?></th>
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
														<td>'.rtrim($jml_diterima,', ').'</td>
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