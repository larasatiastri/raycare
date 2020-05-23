<?php 
	
	$form_attr = array(
	    "id"            => "form_proses_resep", 
	    "name"          => "form_proses_resep", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
		"command"            => "proses"
    );

    echo form_open(base_url()."apotik/resep_obat/save", $form_attr, $hidden);

    $msg_save = translate('Apakah anda yakin akan memproses data ini?', $this->session->userdata('language'));

    $pasien = $this->pasien_m->get_by(array('id' => $form_data['pasien_id']), true);
    $pasien = object_to_array($pasien);	

    $dokter = $this->user_m->get_by(array('id' => $form_data['dokter_id']), true);
    $dokter = object_to_array($dokter);

?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-history font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Resep Obat", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="<?=base_url()?>apotik/resep_obat/history"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a class="btn btn-primary btn-circle hidden" id="confirm_save_draf" data-confirm="<?=$msg_save?>"><i class="fa fa-file-text-o"></i> <?=translate("Simpan ke Draf", $this->session->userdata("language"))?></a>
			
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<input type="hidden" class="form-control" value="1" id="gudang_id" name="gudang_id">
			<input type="hidden" class="form-control" value="<?=$pk_value?>" id="tindakan_resep_obat_id" name="tindakan_resep_obat_id">
			<input type="hidden" class="form-control" value="<?=$pasien['id']?>" id="pasien_id" name="pasien_id">
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
								<label class="col-md-12"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<label class=""><b><?=$pasien['nama']?></b></label>
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
											<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
										</tr>
									</thead>
									<tbody>
									
										<?php 

											$i = 0;
											foreach ($form_data_item as $item) {
												$data_identitas = $this->tindakan_resep_obat_detail_identitas_m->get_data_item_detail_manual($item['id'])->result_array();
										
												$htmlx = '<tr id="item_row_'.$i.'">
														<td class="text-left">'.$item['keterangan'].'</td>
													</tr>
													<tr>
													<td class="details">
														<table class="table" style="font-size:11px;">
															<thead>
																<tr>
																	<td width="10%">
																		<b>Kode Item</b>
																	</td>
																	<td width="10%">
																		<b>Nama Item</b>
																	</td>
																	<td width="10%">
																		<b>Batch Number</b>
																	</td>
																	<td width="8%">
																		<b>Expire Date</b>
																	</td>
																	<td width="5%">
																		<b>Jumlah</b>
																	</td>
																	<td>
																		<b>Status</b>
																	</td>
																</tr>
															</thead>
															<tbody>';

														foreach ($data_identitas as $identitas) {

															$status = '<span class="label label-sm label-info">Belum Diinput</span>';
															if($identitas['status'] == 2){
																$status = '<span class="label label-sm label-success">Sudah Diinput Perawat</span>';
															}if($identitas['status'] == 3){
																$status = '<span class="label label-sm label-danger">Batal Diinput</span>';
															}
															$htmlx .= '<tr>
																<td>'.$identitas['item_kode'].'</td>
																<td>'.$identitas['item_nama'].'</td>
																<td>'.$identitas['bn_sn_lot'].'</td>
																<td>'.date('d M Y', strtotime($identitas['expire_date'])).'</td>
																<td>'.$identitas['jumlah'].' '.$identitas['satuan'].'</td>
																<td>'.$status.'</td>
															</tr>';	
														}

													$htmlx .= '</tbody>
														</table>
													</td>
													</tr>';

													echo $htmlx;

												$i++;
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