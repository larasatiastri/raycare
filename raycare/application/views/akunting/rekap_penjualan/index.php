<?php
	$form_attr = array(
	    "id"            => "form_rekap_penjualan", 
	    "name"          => "form_rekap_penjualan", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."akunting/rekap_penjualan", $form_attr, $hidden);
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
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("REKAP PENJUALAN", $this->session->userdata("language"))?></span>
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
								<label class="col-md-12 bold"><?=translate("Konsumen", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<?php
										
										$konsumen_option = array(
										    '-' => translate('Semua', $this->session->userdata('language'))
										);

										foreach ($data_laporan as $key => $laporan_jual)
										{
										    $konsumen_option[$laporan_jual['pasien_id'].'-'.$laporan_jual['nama_pasien']] = $laporan_jual['nama_pasien'];
										}
										echo form_dropdown('pasien_id', $konsumen_option, $pasien_id, "id=\"pasien_id\" class=\"form-control select2\" required ");
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
					<tr role="row">
						<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="30%"><?=translate("Item", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("BN", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("ED", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Harga", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("SubTotal", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Diskon", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("PPN", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="8%"><?=translate("Total", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="8%"><?=translate("HPP", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="8%"><?=translate("Total HPP", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("No. Penjualan", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Konsumen", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>

					<?php
						$i=1;
						$total_item = 0;
						$total_harga = 0;
						$total_disc = 0;
						$total_sub = 0;
						$total_ppn = 0;
						$grand_total = 0;
						$total_hpp = 0;
						$grand_total_hpp = 0;
						foreach ($data_laporan as $key => $laporan):

							// $inv_history = $this->inventory_history_m->get_by(array('transaksi_id' => $laporan['penjualan_obat_id']), true);


							if($laporan['tipe_item'] == 3 && in_array($laporan['item_id'], config_item('dialyzer_id_array')) === false){

								$tgl_resep = date('Y-m-d', strtotime($laporan['tanggal']));
								$data_resep = $this->penjualan_obat_detail_m->get_resep_pasien($laporan['pasien_id'], $tgl_resep)->result_array();


								$resep_id = '';
								foreach ($data_resep as $key => $resep_ps) {
									$resep_id .= $resep_ps['id'].'-';
								}

								$data_resep_identitas = $this->penjualan_obat_detail_m->get_resep_pasien_identitas($resep_id, $laporan['item_id'])->row(0);					

								// die(dump($this->db->last_query()));			

								$satuan = $data_resep_identitas->nama;
								$bn = $data_resep_identitas->bn_sn_lot;
								$ed = date('d M Y', strtotime($data_resep_identitas->expire_date));
								$edd = date('Y-m-d', strtotime($data_resep_identitas->expire_date));

								
							}elseif($laporan['tipe_item'] == 3 && in_array($laporan['item_id'], config_item('dialyzer_id_array'))){

								$tgl_resep = date('Y-m-d', strtotime($laporan['tanggal']));
								$data_resep = $this->penjualan_obat_detail_m->get_resep_dialyzer($laporan['pasien_id'], $tgl_resep)->row(0);

								$satuan = 'Pcs';
								$bn = $data_resep->bn_sn_lot;
								$ed = date('d M Y', strtotime($data_resep->expired_date));
								$edd = date('Y-m-d', strtotime($data_resep->expired_date));

								
							}else{
								$satuan = $laporan['satuan'];
								$bn = $laporan['bn_sn_lot'];
								$ed = date('d M Y', strtotime($laporan['expire_date']));
								$edd = date('Y-m-d', strtotime($laporan['expire_date']));
							}

							$inv_history_detail = $this->inventory_history_detail_m->get_by(array('item_id' => $laporan['item_id'], 'bn_sn_lot' => $bn, 'date(expire_date)' => $edd ), true);

							$pembelian_detail = $this->pembelian_detail_m->get_by(array('id' => $inv_history_detail->pembelian_detail_id), true);

							$harga_beli = $pembelian_detail->harga_beli_primary;

							$diskon_satuan = ($laporan['diskon_nominal'] / $laporan['jumlah']);

							$konsumen = $laporan['nama_pasien'];

					        if(preg_match("/raycare/i", $konsumen)){
            
								$harga_asli = $laporan['harga_jual'];

								$sub_total = $laporan['jumlah'] * $harga_asli;

								$sub_total = $sub_total - $laporan['diskon_nominal'];

								$ppn = 0;
							}else{
								$harga_asli = (($laporan['harga_jual'] - $diskon_satuan) / 1.1) + $diskon_satuan;

								$sub_total = $laporan['jumlah'] * $harga_asli;

								$sub_total = $sub_total - $laporan['diskon_nominal'];

								$ppn = (10 / 100) * $sub_total;
							}

							$harga_total = $sub_total + $ppn;

					?>
							<tr>
								<td><?=$i?></td>
								<td><div class="inline-button-table"><?=date('d M Y', strtotime($laporan['tanggal']))?></div></td>
								<td><div class="text-left" style="margin-left: 5px;"><?=$laporan['nama']?></div></td>
								<td><div class="text-left"><?=$bn?></div></td>
								<td class="inline-button-table"><?=$ed?></td>
								<td class="inline-button-table"><?=$laporan['jumlah'].' '.$satuan?></td>
								<td class="text-right"><?=formattanparupiah($harga_asli)?></td>
								<td class="text-right"><?=formattanparupiah($sub_total)?></td>
								<td class="text-right"><?=formattanparupiah($laporan['diskon_nominal'])?></td>
								<td class="text-right"><?=formattanparupiah($ppn)?></td>
								<td class="text-right"><?=formattanparupiah($harga_total)?></td>
								<td class="text-right"><?=formattanparupiah($harga_beli)?></td>
								<td class="text-right"><?=formattanparupiah($harga_beli * $laporan['jumlah'])?></td>
								<td class="inline-button-table"><?=$laporan['no_penjualan']?></td>
								<td class="inline-button-table"><?=$laporan['nama_pasien']?></td>
							</tr>
					<?php
						$total_item = $total_item + $laporan['jumlah'];
						$total_harga = $total_harga + $harga_asli;
						$total_sub = $total_sub + $sub_total;
						$total_disc = $total_disc + $laporan['diskon_nominal'];
						$total_ppn = $total_ppn + $ppn;
						$grand_total = $grand_total + ($harga_total);
						$total_hpp = $total_hpp + ($harga_beli);
						$grand_total_hpp = $grand_total_hpp + ($harga_beli * $laporan['jumlah']);
						$i++;
						endforeach;
					?>
					
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5"> TOTAL </th>
						<th class="text-left"> <?=$total_item?> </th>
						<th>  </th>
						<th class="text-right"> <?=formattanparupiah($total_sub)?> </th>
						<th class="text-right"> <?=formattanparupiah($total_disc)?> </th>
						<th class="text-right"> <?=formattanparupiah($total_ppn)?> </th>
						<th class="text-right"> <?=formattanparupiah($grand_total)?> </th>
						<th> </th>
						<th class="text-right"> <?=formattanparupiah($grand_total_hpp)?> </th>
						<th colspan="2">  </th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div><!-- akhir dari portlet -->
<?=form_close();?>