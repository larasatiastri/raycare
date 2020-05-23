<div class="portlet light">
	<div class="portlet-body form">
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

			$draft_id = $this->draft_pmb_m->get_last_id()->result_array();

			$pembelian_id = $this->draft_pmb_po_m->get_by(array('draft_pmb_id' => $draft_pmb_id));
			$pembelian_id = object_to_array($pembelian_id);
			// die_dump($pembelian_id);
		?>
		
		<style>
			
		</style>
		<div class="form-body">
			<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penerimaan Masuk Barang", $this->session->userdata("language"))?></span>
					</div>

					<div class="actions">
						<?php $msg = translate("Apakah anda yakin akan membuat data barang datang ini?",$this->session->userdata("language"));?>
						<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
						<a id="confirm_save" class="btn btn-sm btn-primary btn-circle" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
						<button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
						<a id="confirm_save_draft" class="btn btn-sm btn-primary btn-circle" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan Draft", $this->session->userdata("language"))?></a>
						<button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<input type="hidden" id="is_pmb" name="is_pmb" value="">
									<label class="control-label col-md-3"><?=translate("Informasi", $this->session->userdata("language"))?> <span>:</span></label>
								</div>
								
								<div class="form-group hidden">
									<label class="control-label col-md-4"><?=translate("Supplier Id", $this->session->userdata("language"))?> <span>:</span></label>
									<div class="col-md-4">
										<input type="text" id="draft_id" name="draft_id" value="<?=$draft_pmb_id?>">
										<input type="hidden" id="supplier_id" name="supplier_id" value="<?=$supplier_id?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Gudang", $this->session->userdata("language"))?> <span>:</span></label>
									<div class="col-md-4">
										<?php 
											$gudang = $this->gudang_m->get($gudang_id);
										?>
										<label class="control-label"><?=$gudang->nama?></label>
										<input type="hidden" name="gudang_id" value="<?=$gudang_id?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("No.PO", $this->session->userdata("language"))?> <span>:</span></label>
									<div class="col-md-4">
										<ul class="list-unstyled" style="margin-bottom:0;">
											<?php 
												$i = 1;
												$id = '';
												foreach ($pembelian_id as $data) {
          											$id .= $data['po_id'].',';

													$get_no_po = $this->pembelian_m->get($data['po_id']);
													echo '<li>'.$get_no_po->no_pembelian.'</li>';
													echo '<input id="no_po" name="po['.$i.'][po_id]" type="hidden" value="'.$get_no_po->id.'">';
												$i++;
												}
											?>
										</ul>
									</div>
								</div>

								<div class="form-group">
							        <label class="control-label col-md-4"><?=translate("Tanggal Datang", $this->session->userdata("language"))?> :</label>
							        
							        <div class="col-md-4">
							            <div class="input-group date" id="tanggal">
							                <input type="text" class="form-control" id="tanggal" name="tanggal" readonly value="<?=date('d F Y')?>">
							                <span class="input-group-btn">
							                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
							                </span>
							            </div>
							        </div>
							    </div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("No Surat Jalan", $this->session->userdata("language"))?> <span>:</span></label>
									<?php
										$get_draft_pmb = $this->draft_pmb_m->get_by(array('draft_pmb_id' => $draft_pmb_id));
										$draft_pmb = object_to_array($get_draft_pmb);
										// die_dump($draft_pmb);
									?>
									<div class="col-md-4">
										<input type="text" class="form-control" id="surat_jalan" value="<?=$draft_pmb[0]['no_surat_jalan']?>" name="surat_jalan" placeholder="<?=translate("No Surat Jalan", $this->session->userdata("language"))?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("No Faktur", $this->session->userdata("language"))?> <span>:</span></label>
									<div class="col-md-4">
										<input type="text" class="form-control" id="no_faktur" value="<?=$draft_pmb[0]['no_faktur']?>" name="no_faktur" placeholder="<?=translate("No Faktur", $this->session->userdata("language"))?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> <span>:</span></label>
									<div class="col-md-4">
										<textarea name="keterangan" id="" cols="40" rows="5" placeholder="<?=translate("Keterangan", $this->session->userdata("language"))?>"><?=$draft_pmb[0]['keterangan_gudang']?></textarea>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"><?=translate("Supplier", $this->session->userdata("language"))?> <span>:</span></label>
								</div>

								<div class="form-group">
									<?php 
										$get_supplier = $this->supplier_m->get($supplier_id);
										$supplier = object_to_array($get_supplier);
									?>

									<label class="control-label col-md-1"></label>
									<div class="col-md-5">
										<label class="control-label" style="text-align:left;"><?=$supplier['nama']?>&nbsp;"<?=$supplier['kode']?>"</label>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-1"></label>
									<div class="col-md-5">
										<label class="control-label" style="text-align:left;"><?=$supplier['orang_yang_bersangkutan']?></label>
									</div>
								</div>

								<div class="form-group">
									<?php
										$get_supplier_telp = $this->supplier_telp_m->get_by(array('supplier_id' => $supplier_id));
										$supplier_telp = object_to_array($get_supplier_telp);
									?>
									<label class="control-label col-md-1"></label>
									<div class="col-md-5">
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
									<label class="control-label col-md-1"></label>
									<div class="col-md-5">
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
				</div>
			</div>	
		</div>

		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Detail Item', $this->session->userdata('language'))?></span>
				</div>
			</div>
			<div class="portlet-body">
				
				<?php 

					$array_po_id = array();

					foreach ($pembelian_id as $id_pembelian) {

						if ($id_pembelian != "") {
							$array_po_id[] = $id_pembelian['po_id'];
						}
					}


					$url_po_id = urlencode(base64_encode(serialize($array_po_id)));
					// die_dump($url_po_id);
					$id = '';
					foreach ($pembelian_id as $po) {
						$id .= $po['po_id'].',';
					}
						$get_data_po = $this->pembelian_detail_m->get_data_pembelian_detail($id)->result_array();
						
						// die_dump($get_data_po);
						
						$i = 1;
						$z = 0;
						$jumlah_total_masuk = 0;
						foreach ($get_data_po as $data_po) {
							$get_draft_detail_id = $this->draft_pmb_detail_m->get_by(array('draft_pmb_id' => $draft_pmb_id));
							$draft_detail_id = object_to_array($get_draft_detail_id);
							// die_dump($draft_detail_id[1]);

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

							$get_satuan_primary = $this->item_satuan_m->get($data_po['primary']);
							// die_dump($get_satuan_primary);
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
									<td class="text-center">
										<label id="item_kode" name="items['.$i.'][item_kode]" class="control-label">'.$data_po['item_kode'].'</label>
										<input type="hidden" id="item_id_'.$i.'" name="items['.$i.'][item_id]" value="'.$data_po['item_id'].'">
										<input type="hidden" id="item_satuan_id_'.$i.'" name="items['.$i.'][item_satuan_id]" value="'.$data_po['item_satuan_id'].'">
										<input type="hidden" id="item_po_'.$i.'" name="items['.$i.'][po]" value="'.$data_po['id'].'">
										<input type="hidden" id="item_harga_beli_'.$i.'" name="items['.$i.'][harga_beli]" value="'.$data_po['harga_beli'].'">
									</td>
									
									<td>
										<label id="item_nama" name="items['.$i.'][item_nama]" class="control-label">'.$data_po['item_nama'].'</label>
										<div id="simpan_identitas" class="simpan_identitas hidden"></div>
									</td>
									
									<td class="text-center">
										<span id="jumlah_pesan" class="control-label label bg-blue">
											<a class="jumlah_pesan" data-toggle="modal" data-target="#popup_modal_jumlah_pesan" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_pesan/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.$url_po_id.'" style="cursor:pointer;color:#fff;">
												<span name="items['.$i.'][jumlah_pesan]"><b>'.intval($data_po['jumlah_pesan']).'</b></span>
											</a>
											'.' / '.'
											<a class="jumlah_pesan" name="items['.$i.'][jumlah_pesan_lain]" data-toggle="modal" data-target="#popup_modal_jumlah_pesan_supplier_lain" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_pesan_supplier_lain/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'" style="cursor:pointer;color:#fff;">
												<span name="items['.$i.'][jumlah_pesan_lain]"><b>'.intval($jumlah_pesan_lain).'</b></span>
											</a>
											'.'&nbsp;<label name="items['.$i.'][satuan_nama]">'.$data_po['satuan_nama'].'</label>
										</span>
									</td>
									
									<td class="text-center">
										<span id="jumlah_terima" class="control-label label bg-blue">
											<a class="jumlah_terima" data-toggle="modal" data-target="#popup_modal_jumlah_terima" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_terima/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.$url_po_id.'" style="cursor:pointer;color:#fff;">
												<span name="items['.$i.'][jumlah_diterima]"><b>'.intval($jumlah_pesan_terima).'</b></span>
											</a>
											'.' / '.'
											<a class="jumlah_terima" data-toggle="modal" data-target="#popup_modal_jumlah_terima_supplier_lain" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_terima_supplier_lain/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'" style="cursor:pointer;color:#fff;">
												<span name="items['.$i.'][jumlah_diterima_lain]"><b>'.intval($jumlah_pesan_terima_supplier_lain).'</b></span>
											</a>
											'.'&nbsp;<label name="items['.$i.'][satuan_nama]">'.$data_po['satuan_nama'].'</label>
										</span>

										<span id="jumlah_diterima" class="control-label label bg-blue hidden">
											<a style="cursor:pointer;color:#fff;">
												<b>'.$data_po['jumlah_diterima'].'</b></a>'.' / '.'<a style="cursor:pointer;color:#fff;"><b>0</b>
											</a>
											'.'&nbsp;<label name="items['.$i.'][satuan_nama_jumlah_masuk]">'.$data_po['satuan_nama'].'</label>
										</span>
									</td>
									
									<td class="text-center">
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
										<a class="btn blue-chambray pemisahan_item" href="'.base_url().'gudang/barang_datang_farmasi/add_terima_barang/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.intval($data_po['jumlah_pesan']).'/item_row_'.$i.'/'.$draft_detail_id[$z]['draft_pmb_detail_id'].'/'.$draft_pmb_id.'/'.$supplier_id.'/'.$gudang_id.'"><i class="fa fa-edit"></i></a>
										<a class="btn red-intense del-detail-item" data-row="'.$i.'" data-confirm="'.translate("Apakah anda yakin ingin menghapus item ini ?", $this->session->userdata("language")).'"><i class="fa fa-times"></i></a>
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
											</a>
											'.' / '.'
											<a class="jumlah_pesan" name="items[{0}][jumlah_pesan_lain]" data-toggle="modal" data-target="#popup_modal_jumlah_pesan_supplier_lain" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_pesan_supplier_lain/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'" style="cursor:pointer;color:#333;">
												<span name="items[{0}][jumlah_pesan_lain]"><b>'.intval(0).'</b></span>
											</a>
											'.'&nbsp;<span name="items[{0}][satuan_nama]">Satuan</span>
										</label>
									</td>
									
									<td class="text-center jumlah_terima">
										<label id="jumlah_terima" class="control-label">
											<a class="jumlah_terima" name="items[{0}][jumlah_diterima]" data-toggle="modal" data-target="#popup_modal_jumlah_terima" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_terima/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.$url_po_id.'" style="cursor:pointer;color:#333;">
												<span name="items[{0}][jumlah_diterima]"><b>'.intval(0).'</b></span>
											</a>
											'.' / '.'
											<a class="jumlah_terima" name="items[{0}][jumlah_diterima_lain]" data-toggle="modal" data-target="#popup_modal_jumlah_terima_supplier_lain" href="'.base_url().'gudang/barang_datang_farmasi/modal_jumlah_terima_supplier_lain/'.$supplier_id.'/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'" style="cursor:pointer;color:#333;">
												<span name="items[{0}][jumlah_diterima_lain]"><b>'.intval(0).'</b></span>
											</a>
											'.'&nbsp;<span name="items[{0}][satuan_nama]">Satuan</span>
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
										<a class="btn blue-chambray pemisahan_item" href="'.base_url().'gudang/barang_datang_farmasi/add_terima_barang/'.$data_po['item_id'].'/'.$data_po['item_satuan_id'].'/'.intval($data_po['jumlah_pesan']).'/item_row_'.$i.'/'.$draft_detail_id[0]['draft_pmb_detail_id'].'/'.$draft_pmb_id.'/'.$supplier_id.'/'.$gudang_id.'"><i class="fa fa-chain-broken"></i></a>
										<a class="btn red-intense del-detail-item" data-row="{0}" data-confirm="'.translate("Apakah anda yakin ingin menghapus item ini ?", $this->session->userdata("language")).'"><i class="fa fa-times"></i></a>
									</td>';
                    		
                    	$pembelian_detail_template =  '<tr id="item_row_{0}" class="table_item">'.$pembelian_detail.'</tr>';
					

					// die_dump($this->db->last_query());
				?>
                <span id="tpl_pembelian_row" class="hidden"><?=htmlentities($pembelian_detail_template)?></span>
                <input type="hidden" id="identitasCounter" value="1">
				<input type="hidden" id="pembelianCounter" value="<?=$i?>">
				<input type="hidden" id="url_po_id" value="<?=$url_po_id?>">
				<table class="table table-bordered" id="table_pembelian_detail">
					<thead>
						<tr class="heading">
							<th class="text-center" width="15%"><?=translate("Kode", $this->session->userdata("language"))?></th>
							<th class="text-center" width="32%"><?=translate("Nama", $this->session->userdata("language"))?></th>
							<th class="text-center" width="10%"><?=translate("Jumlah PO", $this->session->userdata("language"))?></th>
		                    <th class="text-center" width="10%"><?=translate("Sudah Terima", $this->session->userdata("language"))?></th>
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