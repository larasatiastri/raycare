<?php
	$form_attr = array(
	    "id"            => "form_rekap_pembelian", 
	    "name"          => "form_rekap_pembelian", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."akunting/rekap_pembelian", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$msg = translate("Apakah anda yakin akan mengolah data absensi ini?",$this->session->userdata("language"));
?>
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("REKAP PEMBELIAN", $this->session->userdata("language"))?></span>
			</div>
			
		</div>
		<div class="portlet-body">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject uppercase">
							<?=translate("Filter", $this->session->userdata("language"))?>
						</span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Periode", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<div class="input-group">
										<div class="input-group date">
											<input type="text" class="form-control" id="tgl_awal" name="tgl_awal" required readonly value="<?=date('d-M-Y', strtotime($tgl_awal))?>" >
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>
										<span class="input-group-addon">
											<i> s/d </i>
										</span>
										<div class="input-group date">
											<input type="text" class="form-control" id="tgl_akhir" name="tgl_akhir" required readonly value="<?=date('d-M-Y', strtotime($tgl_akhir))?>">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<?php
										$data_supplier = $this->supplier_m->get_by(array('is_active' => 1));
										$data_supplier = object_to_array($data_supplier);
										
										$supplier_option = array(
										    0 => translate('Semua', $this->session->userdata('language'))
										);

										foreach ($data_supplier as $key => $supplier)
										{
										   $supplier_option[$supplier['id']] = $supplier['nama'];
										}
										echo form_dropdown('supplier_id', $supplier_option, $supplier_id, "id=\"supplier_id\" class=\"form-control select2\" required ");
									?>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Item", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<?php
										
										$item = $this->item_m->get_by(array('is_active' => '1'));
										// die(dump($this->db->last_query()));
										$item_option = array();
										foreach ($item as $row) {
											$item_option[$row->id] = $row->nama;
										}

										echo form_dropdown('item_id', $item_option, $isian_item, 'id="item_id" class="form-control select2" multiple');
									?>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							
							<div class="form-group">
								<label class="col-md-12 bold" style="color: white;">...............</label>
								<div class="col-md-12">
									<a class="btn btn-primary btn-block" id="refresh">
										<i class="fa fa-search"></i>
										<?=translate("Cari", $this->session->userdata("language"))?>
									</a>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							
							<div class="form-group">
								<label class="col-md-12 bold" style="color: white;">...............</label>
								<div class="col-md-12">
									<a class="btn btn-success btn-block" id="cetak">
										<i class="fa fa-print"></i>
										<?=translate("Cetak", $this->session->userdata("language"))?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<table class="table table-condensed table-striped table-hover" id="table_rekap" style="border:1px;">
				<thead>
					
					<tr>
						<th class="text-center"><?=translate("No", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("No.PMB", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="2%"><?=translate("Tgl.PMB", $this->session->userdata("language"))?> </th>
						
						
						<th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
						
						<th class="text-center" width="30%"><?=translate("Item", $this->session->userdata("language"))?> </th>
						
						<th class="text-center" width="10%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("BN", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("ED", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Harga", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("DiskItem", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("SubTotal", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Diskon", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("PPN", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("PPh", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("BiayaTambahan", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="8%"><?=translate("Total", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("No.PO", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="2%"><?=translate("Tgl.PO", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("JenisBayar", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("SatuanPO", $this->session->userdata("language"))?> </th>
						
						
						
						
					</tr>
				</thead>
				<tbody>

					<?php
						$i=1;

						//die(dump($data_laporan));
						$total_harga_beli = 0;
						$total_diskon_item = 0;
						$total_subtotal = 0;
						$total_diskon_all = 0;
						$total_ppn = 0;
						$total_pph = 0;
						$total_tambahan = 0;
						$total_grand_total = 0;
						
						foreach ($data_laporan as $key => $laporan):

							$tbd = 0;
							$diskon_item = 0;
							$sub_total = 0;
							$diskon_all = 0;
							$ppn = 0;
							$tad = 0;
							$tat = 0;
							$harga_satuan = 0;
							$harga_beli = 0;

							$tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($laporan['tipe_pembayaran'])->result_array();

							$po_detail = $this->pmb_po_detail_m->get_data_item_po($laporan['po_id'])->result_array();

							$jenis_bayar = $tipe_bayar[0]['nama'].' '.$tipe_bayar[0]['lama_tempo'];

							$konversi_pesan = $this->item_m->get_nilai_konversi($laporan['satuan_pesan']);

							$harga_satuan = $laporan['harga_beli'] / $konversi_pesan;

							$konversi_terima = $this->item_m->get_nilai_konversi($laporan['satuan_terima']);

							$harga_beli = $harga_satuan * $konversi_terima;
							$pph23 = 0;
							$biaya_tambahan = $laporan['biaya_tambahan'] / count($po_detail);

							$tbd = $laporan['jumlah_diterima'] * $harga_beli;
							$diskon_item = ($laporan['diskon_item'] / 100) * $tbd;
							$sub_total = $tbd - $diskon_item;
							$diskon_all = ($laporan['diskon'] / 100) * $sub_total;
							$tad = $sub_total - $diskon_all;
							$ppn = ($laporan['pph'] / 100) * $tad;
							
							if($laporan['is_pph'] == 1){
								$pph23 = ($laporan['pph_23']/100) * $tbd;
							}

							$tat = $tad + $ppn - $pph23 + $biaya_tambahan;


							
							

					?>
							<tr>
								<td><?=$i?></td>
								<td><div class="inline-button-table"><?=$laporan['no_pmb']?></td>
								<td><div class="inline-button-table"><?=date('d M Y', strtotime($laporan['tanggal']))?></div></td>
								
								
								<td><div class="inline-button-table"><?=$laporan['nama_supplier']?></td>
								
								<td><div class="inline-button-table text-left"><?=$laporan['nama_item']?></div></td>
								
								
								<td class="inline-button-table"><?=$laporan['jumlah_diterima'].' '.$laporan['nama_satuan_terima']?></td>
								<td class="inline-button-table"><?=$laporan['bn_sn_lot']?></td>
								<td class="inline-button-table"><?=date('d M Y', strtotime($laporan['expire_date']))?></td>
								<td class="text-right"><?=formattanparupiah($harga_beli)?></td>
								<td class="text-right"><?=formattanparupiah($diskon_item)?></td>
								<td class="text-right"><?=formattanparupiah($sub_total)?></td>
								<td class="text-right"><?=formattanparupiah($diskon_all)?></td>
								<td class="text-right"><?=formattanparupiah($ppn)?></td>
								<td class="text-right"><?=formattanparupiah($pph23)?></td>
								<td class="text-right"><?=formattanparupiah($biaya_tambahan)?></td>
								<td class="text-right"><?=formattanparupiah($tat)?></td>
								<td><div class="inline-button-table"><?=$laporan['no_pembelian']?></td>
								<td><div class="inline-button-table"><?=date('d M Y', strtotime($laporan['tanggal_pesan']))?></div></td>
								<td><div class="inline-button-table"><?=$jenis_bayar?></td>
								<td class="inline-button-table"><?=$laporan['nama_satuan']?></td>
								
								
								
							</tr>
					<?php
						
						$total_harga_beli = $total_harga_beli + $harga_beli;
						$total_diskon_item = $total_diskon_item + $diskon_item;
						$total_subtotal = $total_subtotal + $sub_total;
						$total_diskon_all = $total_diskon_all + $diskon_all;
						$total_ppn = $total_ppn + $ppn;
						$total_pph = $total_pph + $pph23;
						$total_tambahan = $total_tambahan + $biaya_tambahan;
						$total_grand_total = $total_grand_total + $tat;
						$i++;
						endforeach;
					?>
					
				</tbody>
				<tfoot>
					<tr>
						<th colspan="9"> Total</th>
						
						<th class="text-right"><?=formattanparupiah($total_diskon_item)?></th>
						<th class="text-right"><?=formattanparupiah($total_subtotal)?></th>
						<th class="text-right"><?=formattanparupiah($total_diskon_all)?></th>
						<th class="text-right"><?=formattanparupiah($total_ppn)?></th>
						<th class="text-right"><?=formattanparupiah($total_pph)?></th>
						<th class="text-right"><?=formattanparupiah($total_tambahan)?></th>
						<th class="text-right"><?=formattanparupiah($total_grand_total)?></th>
						<th colspan="4"> </th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div><!-- akhir dari portlet -->
<?=form_close();?>