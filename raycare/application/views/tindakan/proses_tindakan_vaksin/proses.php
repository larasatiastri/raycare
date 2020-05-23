<?php 
	
	$form_attr = array(
	    "id"            => "form_proses_tindakan_vaksin", 
	    "name"          => "form_proses_tindakan_vaksin", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
		"command"            => "proses"
    );

    echo form_open(base_url()."tindakan/tindakan_vaksin/save", $form_attr, $hidden);

    $msg_save = translate('Apakah anda yakin akan memproses data ini?', $this->session->userdata('language'));

    $pasien = $this->pasien_m->get_by(array('id' => $form_data['pasien_id']), true);
    $pasien = object_to_array($pasien);	

    $pasien_alamat = $this->pasien_alamat_m->get_by(array('pasien_id' => $form_data['pasien_id'], 'is_primary' => 1), true);
    $pasien_alamat =  object_to_array($pasien_alamat);

    $vaksin = $this->master_vaksin_m->get_by(array('id' => $form_data['master_vaksin_id']), true);
    $vaksin = object_to_array($vaksin);

    $dokter = $this->user_m->get_by(array('id' => $form_data['dokter_id']), true);
    $dokter = object_to_array($dokter);

    $perawat = $this->user_m->get_by(array('id' => $form_data['perawat_id']), true);
    $perawat = object_to_array($perawat);

    $cabang = $this->cabang_m->get($form_data['cabang_id']);

?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Tindakan Vaksin", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="<?=base_url()?>tindakan/tindakan_vaksin"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a class="btn btn-primary btn-circle hidden" id="confirm_save_draf" data-confirm="<?=$msg_save?>"><i class="fa fa-file-text-o"></i> <?=translate("Simpan ke Draf", $this->session->userdata("language"))?></a>
			<a class="btn btn-primary btn-circle" id="confirm_save" data-confirm="<?=$msg_save?>"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
			<button class="btn hidden" id="save"></button>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<input type="hidden" class="form-control" value="<?=$form_data['cabang_id']?>" id="cabang_id" name="cabang_id">
			<input type="hidden" class="form-control" value="<?=$form_data['tipe_pasien']?>" id="tipe_pasien" name="tipe_pasien">
			<input type="hidden" class="form-control" value="<?=$pk_value?>" id="tindakan_vaksin_id" name="tindakan_vaksin_id">
			<input type="hidden" class="form-control" value="<?=$pasien['id']?>" id="pasien_id" name="pasien_id">
			<input type="hidden" class="form-control" value="<?=$pasien['nama']?>" id="pasien_nama" name="pasien_nama">
			<input type="hidden" class="form-control" value="<?=$pasien_alamat['alamat']?>" id="pasien_alamat" name="pasien_alamat">
			<div class="row">
				<div class="col-md-3">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Informasi", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="form-group">
								<label class="col-md-12"><?=translate("Cabang", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<label class=""><b> <?=$cabang->nama?></b> </label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<label class=""><b> <?=date('d M Y', strtotime($form_data['tanggal']))?></b> </label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Jenis Vaksin", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<label class=""><b> <?=$vaksin['nama']?></b> </label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<label class=""><b> <?=$pasien['nama']?></b> </label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<label class=""><b> <?=$dokter['nama']?></b> </label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Perawat", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<label class=""><b> <?=$perawat['nama']?></b> </label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Daftar Item", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body form">

							<div class="table-scrollable">
								<table class="table table-bordered table-hover table-striped responsive" id="table_item">
									<thead>
										<tr>
											<th class="text-center" width="10%"><?=translate("Kode Item", $this->session->userdata("language"))?></th>
											<th class="text-center"><?=translate("Nama Item", $this->session->userdata("language"))?></th>
											<th class="text-center" width="10%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
											<th class="text-center" width="10%"><?=translate("Jumlah Kirim", $this->session->userdata("language"))?></th>
											<th class="text-center" width="8%"><?=translate("Harga", $this->session->userdata("language"))?></th>
											<th class="text-center" width="8%"><?=translate("Sub Total", $this->session->userdata("language"))?></th>
										</tr>
									</thead>
									<tbody>
									
										<?php 

											$i = 0;
											$grand_total = 0;
											foreach ($form_data_item as $item) {

												$item_satuan = $this->item_satuan_m->get_available_stok($item['item_id'])->result_array();

												$satuan_option = array();
												foreach ($item_satuan as $row) {
													$satuan_option[$row['id']] = $row['nama'];
												}

												$harga_item = $this->item_harga_m->get_harga_item_satuan($item['item_id'],$item['item_satuan_id'])->row(0);
												// die(dump($this->db->last_query()));

												// Set aksi jika identitas dan jika bukan identitas
												$input_jumlah = '<div class="input-group"><div style="width:80px;"><input type="number" min="0" class="form-control text-right" value="0" name="items['.$i.'][jumlah_kirim]" id="jumlah_kirim_'.$i.'"></div><span class="input-group-addon">
															'.form_dropdown('items['.$i.'][item_satuan_id_kirim]', $satuan_option, '', 'id="id_satuan_item_'.$i.'" class="form_control"').'
														</span></div>';
												if ($item['is_identitas'] == 1) {
													$input_jumlah = '<div class="input-group">
																<input readonly type="number" min="0" max="'.$item['qty'].'" class="form-control text-right" value="0" name="items['.$i.'][jumlah_kirim]" id="jumlah_kirim_'.$i.'">
																<input type="hidden" value="'.$item['qty'].'" class="form-control text-right" name="items['.$i.'][jumlah_pesan]" id="jumlah_pesan_'.$i.'">
																<span class="input-group-btn"><button type="button" data-toggle="modal" data-target="#popup_modal_jumlah_keluar" href="'.base_url().'tindakan/tindakan_vaksin/add_identitas/item_row_'.$i.'/'.$item['item_id'].'/'.$form_data['cabang_id'].'" class="btn blue-chambray add-identitas" name="item['.$i.'][identitas]" title="Tambah Jumlah"><i class="fa fa-info"></i></button></span>
															</div>';
												}

												echo '<tr id="item_row_'.$i.'" >
														<td class="text-center"><input class="form-control hidden" id="tindakan_resep_obat_detail_id" value="'.$item['id'].'" name="items['.$i.'][tindakan_resep_obat_detail_id]"><input class="form-control hidden" id="item_id" value="'.$item['item_id'].'" name="items['.$i.'][item_id]"><input class="form-control hidden" id="satuan_id" value="'.$item['item_satuan_id'].'" name="items['.$i.'][item_satuan_id]">'.$item['item_kode'].'<div id="identitas_row" class="hidden"></td>
														<td class="text-left">'.$item['item_nama'].' <div id="modal_identitas_temp"></div> </td>
														<td class="text-center">'.$item['qty'].' '.$item['satuan'].'</td>
														<td class="text-center">
															'.$input_jumlah.'
														</td>
														<td class="text-right"><input type="hidden" value="'.$harga_item->harga.'" class="form-control" name="items['.$i.'][harga_jual]" id="harga_jual_'.$i.'">
															'.formatrupiah($harga_item->harga).'
														</td><td class="text-right"><input type="hidden" value="'.$item['qty'] * $harga_item->harga.'" class="form-control" name="items['.$i.'][sub_total]" id="sub_total_'.$i.'">
															'.formatrupiah($item['qty'] * $harga_item->harga).'
														</td>
														
													</tr>';

												$grand_total = $grand_total + ($item['qty'] * $harga_item->harga);

												$i++;
											}
										?>
										
									</tbody>
									<tfoot>
										<tr>
											<th colspan="4" class="text-right" width="10%"><?=translate("Grand Total", $this->session->userdata("language"))?></th>
											<th colspan="2" class="text-right" width="10%"><?=formatrupiah($grand_total)?></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="modal fade" id="modal_konversi" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_keluar" role="basic" aria-hidden="true">
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


<?=form_close();?>