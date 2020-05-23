<?php
	$form_attr = array(
	    "id"            => "form_barang_datang_farmasi", 
	    "name"          => "form_barang_datang_farmasi", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "proses"
    );


    echo form_open(base_url()."gudang/barang_datang_farmasi/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	// $draft_id = $this->draft_pmb_m->get_last_id()->result_array();

	$pembelian_id = $this->draft_pmb_po_m->get_by(array('draft_pmb_id' => $draft_pmb_id));
	$pembelian_id = object_to_array($pembelian_id);
	// die_dump($pembelian_id);
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cube font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penerimaan Masuk Barang", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-3">
				<div class="portlet box blue-sharp">
					<div class="portlet-title" style="margin-bottom: 0px !important;">
	                    <div class="caption">
	                        <span class="caption-subject">Informasi</span>
	                    </div>
	                </div>
	                <div class="portlet-body form">
		                <div class="form-body">
							<div class="alert alert-danger display-hide">
						        <button class="close" data-close="alert"></button>
						        <?=$form_alert_danger?>
						    </div>

						    <div class="alert alert-success display-hide">
						        <button class="close" data-close="alert"></button>
						        <?=$form_alert_success?>
						    </div>
							<div class="form-group hidden">
								<label class="control-label text-left col-md-12"><?=translate("Supplier Id", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="text" id="draft_id" name="draft_id" value="<?=$draft_pmb_id?>">
									<input type="hidden" id="supplier_id" name="supplier_id" value="<?=$supplier_id?>">
									<input type="hidden" id="is_pmb" name="is_pmb" value="">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Gudang", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<?php 

									$option_gudang = array(
				            			''  => translate('Pilih Gudang', $this->session->userdata('language')).'...',
			            			);
				            		$data_gudang = $this->gudang_m->get_by(array('cabang_klinik' => $this->session->userdata('cabang_id'), 'is_active' => 1));
				            		foreach ($data_gudang as $row) {
				            			$option_gudang[$row->id] = $row->nama;
				            		}
				            			echo form_dropdown('gudang_id', $option_gudang, $gudang_id, 'id="gudang_id" class="form-control input-sm"');
								?>
									<label class="control-label"><?=$gudang->nama?></label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("No.PO", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<ul class="list-unstyled">
										<?php 
											$i = 1;
											$id = '';
											foreach ($pembelian_id as $data) {
													$id .= $data['po_id']."','";

												$get_no_po = $this->pembelian_m->get_by(array('id' => $data['po_id']), true);
												// die_dump($get_no_po);
												echo '<li>'.$get_no_po->no_pembelian.'</li>';
												echo '<input id="no_po" name="po['.$i.'][po_id]" type="hidden" value="'.$get_no_po->id.'">';
											$i++;
											}
										?>
									</ul>
								</div>
							</div>
							<div class="form-group" hidden>
								<label class="col-md-12"><?=translate("ID PMB", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="text" class="form-control" id="pmb_id" name="pmb_id" value="<?=$id?>">
								</div>
							</div>


							<div class="form-group">
						        <label class="col-md-12 bold"><?=translate("Tanggal Datang", $this->session->userdata("language"))?><span class="required">*</span>:</label>
						        
						        <div class="col-md-12">
						            <div class="input-group date" id="tanggal">
						                <input type="text" class="form-control" id="tanggal" name="tanggal" readonly required value="<?=date('d F Y')?>">
						                <span class="input-group-btn">
						                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
						                </span>
						            </div>
						        </div>
						    </div>

							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("No Surat Jalan", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
								<div class="col-md-12">
									<input type="text" class="form-control" id="surat_jalan" name="surat_jalan" required placeholder="<?=translate("No Surat Jalan", $this->session->userdata("language"))?>">
								</div>
							</div>

							<div class="form-group" hidden>
								<label class="col-md-12"><?=translate("No Faktur", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="text" class="form-control" id="no_faktur" name="no_faktur" placeholder="<?=translate("No Faktur", $this->session->userdata("language"))?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Upload Surat Jalan", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="text" class="form-control" placeholder="Nama File" name="url_faktur" id="url_faktur" required readonly>
									<div id="upload">
										<span class="btn default btn-file">
											<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>	
											<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload_new/upload_photo" />
										</span>

										<ul class="ul-img">
										<!-- The file uploads will be shown here -->
										</ul>

									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<textarea name="keterangan" id="" cols="40" rows="5" placeholder="<?=translate("Keterangan", $this->session->userdata("language"))?>"></textarea>
								</div>
							</div>
						</div>

	                </div>
	                <div class="portlet-title" style="margin-bottom: 0px !important;">
	                    <div class="caption">
	                        <span class="caption-subject">Supplier</span>
	                    </div>
	                </div>
	                <div class="portlet-body form">
		                <div class="form-body">
		                	<div class="form-group">
								<?php 
									$get_supplier = $this->supplier_m->get($supplier_id);
									$supplier = object_to_array($get_supplier);
								?>

								<label class="control-label col-md-12"></label>
								<div class="col-md-12">
									<label class="control-label"><strong><?=$supplier['nama']?>&nbsp;"<?=$supplier['kode']?>"</strong></label>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-12"></label>
								<div class="col-md-12">
									<label class="control-label"><strong><?=$supplier['orang_yang_bersangkutan']?></strong></label>
								</div>
							</div>

							<div class="form-group">
								<?php
									$get_supplier_telp = $this->supplier_telp_m->get_by(array('supplier_id' => $supplier_id));
									$supplier_telp = object_to_array($get_supplier_telp);
								?>
								<label class="control-label col-md-12"></label>
								<div class="col-md-12">
									<ul style="list-style:none; padding-left: 0px; margin-bottom:0px;">
										<?php
											foreach ($supplier_telp as $telp) {
												$get_subjek_telp = $this->subjek_m->get($telp['subjek_telp_id']);
												$subjek_telp = object_to_array($get_subjek_telp);

												echo '<li style="padding-top: 3px;"><b>'.$subjek_telp['nama'].'</b>.'.$telp['no_telp'].'</li>';
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
								<label class="control-label col-md-12"></label>
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
				<div class="portlet box blue-sharp">
					<div class="portlet-title" style="margin-bottom: 0px !important;">
						<div class="caption">
							<span class="caption-subject"><?=translate("Daftar Item", $this->session->userdata("language"))?></span>
						</div>						
					</div>
					<div class="portlet-body form">
					<div class="form-body">

						<?php 

							$array_po_id = array();

							foreach ($pembelian_id as $id_pembelian) {

								if ($id_pembelian != "") {
									$array_po_id[] = $id_pembelian['po_id'];
								}
							}


							// die_dump($array_po_id);
							$url_po_id = urlencode(base64_encode(serialize($array_po_id)));
							$id = '';
							foreach ($pembelian_id as $po) {
								$id .= $po['po_id']."','";
							}
							$id = rtrim($id,"','");
								$get_data_po = $this->pembelian_detail_m->get_data_pembelian_detail($id)->result_array();

									// die_dump($this->db->last_query());
								
																
								$i = 1;
								$z = 0;
								$jumlah_total_masuk = 0;
								foreach ($get_data_po as $data_po) {
									$get_draft_detail_id = $this->draft_pmb_detail_m->get_by(array('draft_pmb_id' => $draft_pmb_id));
									// die_dump($this->db->last_query());
									$draft_detail_id = object_to_array($get_draft_detail_id);

									$jumlah = $this->draft_pmb_detail_actual_m->get_data($draft_detail_id[$z]['draft_pmb_detail_id']);
									// die_dump($jumlah);
									if($jumlah[0]['jumlah'] != null)
									{
										$jumlah_total_masuk = $jumlah[0]['jumlah'];
									}
									else
									{
										$jumlah_total_masuk = 0;
									}

									$get_id_detail = $this->pembelian_detail_m->get_data_detail($id, $data_po['item_id'])->result_array();
									$get_data_pesan = $this->pembelian_detail_m->get_data_item($id, $data_po['item_id'])->result_array();

									$jml_diterima = '0';
									$po_id = '';
									foreach ($get_id_detail as $id_detail) {
										$po_id .= $id_detail['id']."','";

										$diterima_detail = $this->pmb_po_detail_m->get_data_diterima($id_detail['id']);
										// die(dump($this->db->last_query()));
							            if(count($diterima_detail)){
							                $jml_diterima = '';
							                foreach ($diterima_detail as $diterima) {
							                   $jml_diterima .= $diterima['jumlah'].' '.$diterima['nama_satuan'].', '; 
							                }

							                $jml_diterima = rtrim($jml_diterima,', ');
							            }
									}
									$po_id = rtrim($po_id,"','");


									$data_pesan = '';
									foreach ($get_data_pesan as $row_pesan) {
										$data_pesan .= $row_pesan['jumlah_disetujui'].' '.$row_pesan['satuan_nama'].', ';
									}
									$data_pesan = rtrim($data_pesan,', ');

									$get_satuan_primary = $this->item_satuan_m->get($data_po['primary']);
									$get_jumlah_pesan_lain = $this->pembelian_detail_m->get_data_jumlah_pesan_supplier_lain($supplier_id, $data_po['item_id'], $data_po['item_satuan_id'])->result_array();
									$get_jumlah_pesan_terima = $this->pmb_m->get_jumlah_terima($supplier_id, $data_po['item_id'], $data_po['item_satuan_id'], $id)->result_array();
									// die_dump($jumlah_pesan_terima);
									$get_jumlah_pesan_terima_supplier_lain = $this->pmb_m->get_jumlah_terima_supplier_lain($supplier_id, $data_po['item_id'], $data_po['item_satuan_id'])->result_array();

									$cek_is_identitas = $this->item_m->get($data_po['item_id']);

									$is_identitas = object_to_array($cek_is_identitas);

									$jumlah_pesan_lain = 0;
									$jumlah_pesan_terima = 0;
									$jumlah_pesan_terima_supplier_lain = 0;
									
									if (!empty($get_jumlah_pesan_lain)) {
										$jumlah_pesan_lain = $get_jumlah_pesan_lain[0]['jumlah_pesan'];
									}

									if (!empty($get_jumlah_pesan_terima)) {
										$jumlah_pesan_terima = $jumlah_pesan_terima[0]['jumlah_diterima'];
									}

									if (!empty($get_jumlah_pesan_terima_supplier_lain)) {
										$jumlah_pesan_terima_supplier_lain = $jumlah_pesan_terima_supplier_lain[0]['jumlah_diterima'];
									}


									$data_pembelian_detail[] = '
										<tr id="item_row_'.$i.'" class="table_item">
											<td class="text-left">
												<label id="item_kode" name="items['.$i.'][item_kode]" class="control-label">'.$data_po['item_kode'].'</label>
												<input type="hidden" id="item_id_'.$i.'" name="items['.$i.'][item_id]" value="'.$data_po['item_id'].'">
												<input type="hidden" id="item_satuan_id_'.$i.'" name="items['.$i.'][item_satuan_id]" value="'.$data_po['item_satuan_id'].'">
												<input type="hidden" id="item_po_'.$i.'" name="items['.$i.'][po]" value="'.$data_po['pembelian_id'].'">
												<input type="hidden" id="item_harga_beli_'.$i.'" name="items['.$i.'][harga_beli]" value="'.$data_po['harga_beli'].'">
											</td>
											
											<td>
												<label id="item_nama" name="items['.$i.'][item_nama]" class="control-label">'.$data_po['item_nama'].'</label>
												<div id="simpan_identitas" class="simpan_identitas hidden"></div>
											</td>
											
											<td class="text-left">
												<a class="jumlah_pesan" data-toggle="modal" data-target="#popup_modal_jumlah_pesan" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_pesan/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.$url_po_id.'" >
													<span name="items['.$i.'][jumlah_pesan]">'.$data_pesan.'</span>
												</a>
											</td>
											
											<td class="text-left">
												<a class="jumlah_terima" data-toggle="modal" data-target="#popup_modal_jumlah_terima" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_terima/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.$url_po_id.'">
													<span name="items['.$i.'][jumlah_diterima]">'.$jml_diterima.'</span>
												</a>
											</td>
											
											<td class="text-left">
												<label id="jumlah_belum_diterima" name="items['.$i.'][jumlah_belum_diterima]" class="control-label">'
													.$data_po['jumlah_belum_diterima'].'&nbsp;<label name="items['.$i.'][satuan_nama]">'.$get_satuan_primary->nama.'</label>
												</label>
											</td>
											

											<td class="identitas">
												<div class="input-group">
													<input type="number" readonly id="jumlah_masuk_'.$i.'" name="items['.$i.'][jumlah_masuk]" data-id="'.$i.'" class="form-control text-right" min="0" value="'.$jumlah_total_masuk.'">
													<span class="input-group-addon">
														<label class="control-label" id="satuan_jumlah_masuk_'.$i.'" name="items['.$i.'][satuan_jumlah_masuk]">'.$get_satuan_primary->nama.'</label>
													</span>
												</div>	
											</td>
											
											<td class="text-center inline-button-table">
												<a class="btn grey-cararra pemisahan_item" data-target="#modal_add_terima_barang" data-toggle="modal" href="'.base_url().'gudang/barang_datang_farmasi/add_terima_barang/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.intval($data_po['jumlah_pesan']).'/item_row_'.$i.'/'.$draft_detail_id[$z]['draft_pmb_detail_id'].'/'.$draft_pmb_id.'/'.$supplier_id.'/'.$gudang_id.'/'.$data_po['id'].'"><i class="fa fa-download font-blue-steel"></i></a>
												<a class="btn grey-cararra del-detail-item" data-row="'.$i.'" data-confirm="'.translate("Apakah anda yakin ingin menghapus item ini ?", $this->session->userdata("language")).'"><i class="fa fa-times font-red-intense"></i></a>
											</td>
										</tr>
									';
									$i++;
									$z++;
								}

								// die_dump($jumlah);

								$pembelian_detail = '<td class="text-center">
												<label id="item_kode" name="items[{0}][item_kode]" class="control-label"></label>
												<input type="hidden" id="item_id_{0}" name="items[{0}][item_id]">
												<input type="hidden" id="item_satuan_id_{0}" name="items[{0}][item_satuan_id]">
												<input type="hidden" id="item_po_{0}" name="items[{0}][po]">
												<input type="hidden" id="item_harga_beli_{0}" name="items[{0}][harga_beli]">
											</td>
											
											<td>
												<label id="item_nama" name="items[{0}][item_nama]" class="control-label"></label>
												<div id="simpan_identitas" class="simpan_identitas hidden"></div>
											</td>
											
											<td class="text-center jumlah_pesan">
												<label id="jumlah_pesan" class="control-label">
													<a class="jumlah_pesan" name="items[{0}][jumlah_pesan]" data-toggle="modal" data-target="#popup_modal_jumlah_pesan" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_pesan/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.$url_po_id.'" style="cursor:pointer;color:#333;">
														<span name="items[{0}][jumlah_pesan]"><b>'.intval(0).'</b></span>
													</a>&nbsp;<span name="items[{0}][satuan_nama]">Satuan</span>
												</label>
											</td>
											
											<td class="text-center jumlah_terima">
												<label id="jumlah_terima" class="control-label">
													<a class="jumlah_terima" name="items[{0}][jumlah_diterima]" data-toggle="modal" data-target="#popup_modal_jumlah_terima" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_terima/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.$url_po_id.'" style="cursor:pointer;color:#333;">
														<span name="items[{0}][jumlah_diterima]"><b>'.intval(0).'</b></span>
													</a>&nbsp;<span name="items[{0}][satuan_nama]">Satuan</span>
												</label>

												<label id="jumlah_diterima" class="control-label hidden">
													<a style="cursor:pointer;color:#333;">
														<b>0</b></a>'.' / '.'<a style="cursor:pointer;color:#333;"><b>0</b>
													</a>
													'.'&nbsp;<span name="items[{0}][satuan_nama]">Satuan</span>
												</label>
											</td>
											
											<td class="text-center jumlah_belum_diterima">
												<label id="jumlah_belum_diterima" name="items[{0}][jumlah_belum_diterima]" class="control-label">
												0&nbsp;<label name="items[{0}][satuan_nama]">Satuan</label>
												</label>
											</td>
											

											<td class="identitas">
												
											</td>
											
											<td class="text-center inline-button-table">
												<a class="btn blue-chambray pemisahan_item" href="'.base_url().'gudang/barang_datang_farmasi/add_terima_barang/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.intval($data_po['jumlah_pesan']).'/item_row_'.$i.'/'.$draft_detail_id[0]['draft_pmb_detail_id'].'/'.$draft_pmb_id.'/'.$supplier_id.'/'.$gudang_id.'/'.$data_po['id'].'"><i class="fa fa-chain-broken"></i></a>
												<a class="btn red-intense del-detail-item" data-row="{0}" data-confirm="'.translate("Apakah anda yakin ingin menghapus item ini ?", $this->session->userdata("language")).'"><i class="fa fa-times"></i></a>
											</td>';
		                    		
		                    	$pembelian_detail_template =  '<tr id="item_row_{0}" class="table_item">'.$pembelian_detail.'</tr>';
							

							// die_dump($this->db->last_query());
						?>
		                <span id="tpl_pembelian_row" class="hidden"><?=htmlentities($pembelian_detail_template)?></span>
		                <input type="hidden" id="identitasCounter" value="1">
						<input type="hidden" id="pembelianCounter" value="<?=$i?>">
						<input type="hidden" id="url_po_id" value="<?=$url_po_id?>">
						<table class="table table-striped table-hover table-condensed" id="table_pembelian_detail">
							<thead>
								<tr>
									<th class="text-center" width="1%"><?=translate("Kode", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
									<th class="text-center" width="10%"><?=translate("Jumlah PO", $this->session->userdata("language"))?></th>
				                    <th class="text-center inline-button-table" width="10%"><?=translate("Sudah Terima", $this->session->userdata("language"))?></th>
				                    <th class="text-center" width="10%"><?=translate("Sisa PO", $this->session->userdata("language"))?></th>
				                    <th class="text-center" width="13%"><?=translate("Jumlah Masuk", $this->session->userdata("language"))?></th>
				                    <th class="text-center" width="1%" ><?=translate("Aksi", $this->session->userdata("language"))?></th>
								</tr>
							</thead>
									
							<tbody>
								<?php 
									foreach ($data_pembelian_detail as $row) {
										echo $row;
									}
								?>
							</tbody>
						</table>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<div class="form-actions right">
			<?php $msg = translate("Apakah anda yakin akan membuat data barang datang ini?",$this->session->userdata("language"));?>
			<a class="btn default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="glyphicon glyphicon-floppy-disk"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
			<button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			<a id="confirm_save_draft" class="btn btn-sm btn-primary btn-circle hidden" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan Draft", $this->session->userdata("language"))?></a>
			<button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>
</div>	

			

<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_pesan" role="basic" aria-hidden="true">
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

<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_pesan_supplier_lain" role="basic" aria-hidden="true">
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

<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_terima" role="basic" aria-hidden="true">
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

<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_terima_supplier_lain" role="basic" aria-hidden="true">
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

<div class="modal fade bs-modal-lg" id="popup_modal_identitas" role="basic" aria-hidden="true">
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

<div class="modal fade" id="popup_modal_pemisahan_item" role="basic" aria-hidden="true">
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

<div class="modal fade bs-modal-lg" id="modal_add_terima_barang" role="basic" aria-hidden="true">
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
<?=form_close()?>