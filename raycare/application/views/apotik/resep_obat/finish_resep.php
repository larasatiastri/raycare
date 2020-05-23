<?php 
	
	$form_attr = array(
	    "id"            => "form_proses_resep", 
	    "name"          => "form_proses_resep", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
		"command"            => "finish"
    );

    echo form_open(base_url()."apotik/resep_obat/save", $form_attr, $hidden);

    $msg_save = translate('Apakah anda yakin akan menyelesaikan resep ini?', $this->session->userdata('language'));

    $pasien = $this->pasien_m->get_by(array('id' => $form_data['pasien_id']), true);
    $pasien = object_to_array($pasien);	

    $dokter = $this->user_m->get_by(array('id' => $form_data['dokter_id']), true);
    $dokter = object_to_array($dokter);

    $cabang = $this->cabang_m->get($form_data['cabang_id']);

?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-check font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Finish Resep Obat", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="<?=base_url()?>apotik/resep_obat"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>

			<a class="btn btn-primary btn-circle hidden" id="confirm_save_draf" data-confirm="<?=$msg_save?>"><i class="fa fa-file-text-o"></i> <?=translate("Simpan ke Draf", $this->session->userdata("language"))?></a>
			<a class="btn btn-primary btn-circle" id="confirm_save" data-confirm="<?=$msg_save?>"><i class="fa fa-check"></i> <?=translate("Selesai", $this->session->userdata("language"))?></a>
			<button class="btn hidden" id="save"></button>
			
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<input type="hidden" class="form-control" value="<?=$form_data['cabang_id']?>" id="cabang_id" name="cabang_id">
			<input type="hidden" class="form-control" value="<?=$pk_value?>" id="tindakan_resep_obat_id" name="tindakan_resep_obat_id">
			<input type="hidden" class="form-control" value="<?=$pasien['id']?>" id="pasien_id" name="pasien_id">
			<input type="hidden" class="form-control" value="<?=$form_data['tindakan_id']?>" id="tindakan_id" name="tindakan_id">
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
								<label class="col-md-12"><?=translate("No. Resep", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<label class=""><b> <?=$form_data['nomor_resep']?></b> </label>
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
								<label class="col-md-12"><?=translate("Tanggal Resep", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<label class=""><b> <?=date('d M Y', strtotime($form_data['created_date']))?></b> </label>
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
										</tr>
									</thead>
									<tbody>
									
										<?php 

											$j = 0;
											foreach ($form_data_item as $item) {
												$data_identitas = $this->tindakan_resep_obat_detail_identitas_m->get_data_item_detail($item['id'])->result_array();
										
												$htmlx = '<tr id="item_row_'.$j.'">
														<td class="text-center"><input class="form-control hidden" id="tindakan_resep_obat_detail_id" value="'.$item['id'].'" name="items['.$i.'][tindakan_resep_obat_detail_id]"><input class="form-control hidden" id="item_id" value="'.$item['item_id'].'" name="items['.$i.'][item_id]"><input class="form-control hidden" id="satuan_id" value="'.$item['satuan_id'].'" name="items['.$i.'][item_satuan_id]">'.$item['item_kode'].'<div id="identitas_row" class="hidden"></td>
														<td class="text-left">'.$item['item_nama'].' <div id="modal_identitas_temp"></div> </td>
														<td class="text-center">'.$item['jumlah'].' '.$item['satuan'].'</td>
													</tr>
													<tr>
													<td class="details" colspan="3">
														<table class="table" style="font-size:11px;" id="table_identitas_detail">
															<thead>
																<tr class="parent" id="item_detail_row_'.$j.'">
																	<td width="10%">
																		<b>Batch Number</b>
																	</td>
																	<td width="8%">
																		<b>Expire Date</b>
																	</td>
																	<td width="5%">
																		<b>Jumlah</b>
																	</td>
																	<td width="10%">
																		<b>Status</b>
																	</td>
																	<td id="pj_column" class="hidden">
																		<b>Ditanggung Oleh</b>
																	</td>
																	<td width="1%">
																		<b>Gunakan</b>
																	</td>
																	<td width="1%">
																		<b>Batalkan</b>
																	</td>
																	<td width="1%">
																		<b>Bawa Pulang</b>
																	</td>
																</tr>
															</thead>
															<tbody>';

														$i = 0;
														foreach ($data_identitas as $identitas) {

															$status = '<span class="label label-sm label-info">Belum Diinput</span>';
															$rbgunakan = '<label class="radio-inline">
																			<input type="radio" name="identitas['.$j.']['.$i.'][aksi]" data-row="'.$j.'" id="identitas_aksi_'.$i.'" value="2"> </label>';
															$rbbatalkan = '<label class="radio-inline">
																			<input type="radio" name="identitas['.$j.']['.$i.'][aksi]"  data-row="'.$j.'" id="identitas_aksi_'.$i.'" value="3"> </label>';
															$rbbawa = '<label class="radio-inline">
																			<input type="radio" name="identitas['.$j.']['.$i.'][aksi]"  data-row="'.$j.'" id="identitas_aksi_'.$i.'" value="4"> </label>';
															if($identitas['status'] == 2){
																$status = '<span class="label label-sm label-success">Sudah Diinput Perawat</span>';
																$rbgunakan = '<label class="radio-inline">
																			<input type="radio" name="identitas['.$j.']['.$i.'][aksi]"  data-row="'.$j.'" id="identitas_aksi_'.$i.'" value="2" disabled> </label>';
																$rbbatalkan = '<label class="radio-inline">
																			<input type="radio" name="identitas['.$j.']['.$i.'][aksi]"  data-row="'.$j.'" id="identitas_aksi_'.$i.'" value="3"> </label>';
																$rbbawa = '<label class="radio-inline">
																			<input type="radio" name="identitas['.$j.']['.$i.'][aksi]"  data-row="'.$j.'" id="identitas_aksi_'.$i.'" value="4" disabled> </label>';
															}if($identitas['status'] == 3){
																$status = '<span class="label label-sm label-danger">Batal Diinput</span>';
																$rbgunakan = '<label class="radio-inline">
																			<input type="radio" name="identitas['.$j.']['.$i.'][aksi]"  data-row="'.$j.'" id="identitas_aksi_'.$i.'" value="2"> </label>';
																$rbbatalkan = '<label class="radio-inline">
																			<input type="radio" name="identitas['.$j.']['.$i.'][aksi]"  data-row="'.$j.'" id="identitas_aksi_'.$i.'" value="3" disabled> </label>';
																$rbbawa = '<label class="radio-inline">
																			<input type="radio" name="identitas['.$j.']['.$i.'][aksi]"  data-row="'.$j.'" id="identitas_aksi_'.$i.'" value="4"> </label>';
															}
															$htmlx .= '<tr class="fieldset">
																<td><input type="hidden" name="identitas['.$j.']['.$i.'][id]" id="identitas_id_'.$i.'" value="'.$identitas['id'].'" ><input type="hidden" name="identitas['.$j.']['.$i.'][bn_sn_lot]" id="identitas_bn_sn_lot_'.$i.'" value="'.$identitas['bn_sn_lot'].'" ><input type="hidden" name="identitas['.$j.']['.$i.'][item_id]" id="identitas_item_id_'.$i.'" value="'.$identitas['item_id'].'" >'.$identitas['bn_sn_lot'].'</td>
																<td><input type="hidden" name="identitas['.$j.']['.$i.'][expire_date]" id="identitas_expire_date_'.$i.'" value="'.$identitas['expire_date'].'" >'.date('d M Y', strtotime($identitas['expire_date'])).'</td>
																<td><input type="hidden" name="identitas['.$j.']['.$i.'][jumlah]" id="identitas_id_'.$i.'" value="'.$identitas['jumlah'].'" ><input type="hidden" name="identitas['.$j.']['.$i.'][harga]" id="identitas_id_'.$i.'" value="'.$identitas['harga'].'" ><input type="hidden" name="identitas['.$j.']['.$i.'][item_satuan_id]" id="identitas_id_'.$i.'" value="'.$identitas['satuan_id'].'" >'.$identitas['jumlah'].' '.$item['satuan'].'</td>
																
																<td>'.$status.'</td>
																<td id="field_pj" class="hidden"><input type="text" name="identitas['.$j.']['.$i.'][pj]" id="identitas_pj_'.$i.'" class="form-control"></td>
																<td>'.$rbgunakan.'</td>
																<td>'.$rbbatalkan.'</td>
																<td>'.$rbbawa.'</td>
															</tr>';	
															$i++;
														}

													$htmlx .= '</tbody>
														</table>
													</td>
													</tr>';

													echo $htmlx;

												$j++;
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