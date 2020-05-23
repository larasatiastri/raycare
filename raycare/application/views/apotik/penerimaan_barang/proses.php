<?php 
	
	$form_attr = array(
	    "id"            => "form_proses_terima", 
	    "name"          => "form_proses_terima", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
		"command"      => "proses",
    );

    echo form_open(base_url()."apotik/penerimaan_barang/terima" , $form_attr, $hidden);

?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-plus-circle font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Penerimaan Barang", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<?php
				$confirm_save       = translate('Anda yakin akan menerima semua item pengiriman ini ?',$this->session->userdata('language'));
				$confirm_reject     = translate('Anda yakin akan menolak semua item pengiriman ini ?',$this->session->userdata('language'));
				$submit_text        = translate('Proses', $this->session->userdata('language'));
				$reset_text         = translate('Reject', $this->session->userdata('language'));
				$back_text          = translate('Kembali', $this->session->userdata('language'));
			?>
			<a class="btn btn-default btn-circle" href="javascript:history.go(-1);">
				<i class="fa fa-reply"></i> 
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<!-- <button type="button" id="reject" class="btn btn-primary hidden" ><?=$reset_text?></button> -->
	        <a id="confirm_reject" class="btn btn-circle red-intense hidden" href="<?=base_url()?>apotik/penerimaan_barang/tolak/<?=$form_data_pengiriman['id']?>" data-confirm="<?=$confirm_reject?>" data-toggle="modal" data-target="#popup_modal">
	        	<i class="fa fa-times"></i>
	        	<!-- <i class="fa fa-floppy-o"></i> -->
				<?=translate("Tolak", $this->session->userdata("language"))?>
	        </a>
	        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
	        <a id="confirm_save" class="btn btn-circle btn-primary" data-confirm="<?=$confirm_save?>" data-toggle="modal">
	        	<i class="fa fa-check"></i>
				<?=translate("Terima", $this->session->userdata("language"))?>
	        </a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<!-- <input type="hidden" class="form-control" value="<?=$gudang_id?>" id="gudang_id" name="gudang_id"> -->
			<div class="row"> 
				<div class="col-md-3"> <!-- Informasi Penjualan -->
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Informasi", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-12"><?=translate("No. Penjualan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<label class=""> <?=$form_data['no_penjualan']?> </label>
                            			<input type="hidden" id="pengiriman_id" name="pengiriman_id"  value="<?=$form_data_pengiriman['id']?>" required="required" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-12"><?=translate("No. Surat Jalan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<label class=""> <?=$form_data_pengiriman['no_surat_jalan']?> </label>
										<input type="hidden" id="no_surat_jalan" name="no_surat_jalan"  value="<?=$form_data_pengiriman['no_surat_jalan']?>" />

									</div>
								</div>
								<div class="form-group">
									<label class="col-md-12"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<label class=""> <?=date('d F Y', strtotime($form_data['tanggal_pesan']))?> </label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-12"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<label class=""> <?=date('d F Y', strtotime($form_data['tanggal_kirim']))?> </label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-12"><?=translate("Upload Surat Jalan", $this->session->userdata("language"))?> <span>:</span></label>
									<div class="col-md-12">
										<input type="hidden" name="url_faktur" id="url_faktur">
										<div id="upload">
											<span class="btn default btn-file">
												<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>	
												<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_photo" />
											</span>

											<ul class="ul-img">
											<!-- The file uploads will be shown here -->
											</ul>

										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<label class="" style="text-align: left;"> <?=$form_data['keterangan']?> </label>
									</div> 
								</div>
							</div>
						</div>
					</div>
				</div> <!-- End of Informasi Penjualan -->
				<div class="col-md-9">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Item", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="form-body">
								<table class="table table-bordered table-hover table-striped" id="table_item">
									<thead>
										<tr>
											<th class="text-center" width="10%"><?=translate("Kode Item", $this->session->userdata("language"))?></th>
											<th class="text-center" width="40%"><?=translate("Nama Item", $this->session->userdata("language"))?></th>
											<th class="text-center" width="10%"><?=translate("Jumlah Yang Dikirim", $this->session->userdata("language"))?></th>
											<th class="text-center" width="10%"><?=translate("Batch Number", $this->session->userdata("language"))?></th>
											<th class="text-center" width="10%"><?=translate("Expire Date", $this->session->userdata("language"))?></th>
										</tr>
									</thead>
									<tbody>
									

										<?php 

											$i = 0;
											foreach ($form_data_item as $item) 
											{	

												echo '<tr>
														<td class="text-center"><input class="form-control hidden" id="pengiriman_detail_id" value="'.$item['id'].'" name="items['.$i.'][pengiriman_detail_id]">'.$item['item_kode'].'</td>
														<td class="text-left">'.$item['item_nama'].' <div id="modal_identitas_temp"></div> </td>
														<td class="text-center"><a href="'.base_url().'apotik/penerimaan_barang/modal_detail/'.$item['pengiriman_id'].'/'.$item['item_id'].'" data-toggle="modal" data-target="#popup_modal_detail">'.$item['jumlah_kirim'].' '.$item['satuan'].' / '.$item['jumlah_konversi'].' '.$item['satuan_primary'].'</a></td>	
														<td class="text-left">'.$item['bn_sn_lot'].' </td>
														<td class="text-left">'.date('d M Y', strtotime($item['expire_date'])).' </td>
													</tr>';

												$i++;
											}

											// UTK BOX PAKET
											$z = $i;
											foreach ($form_data_box as $item) 
											{
												$penjualan_detail = $this->pengiriman_detail_m->get_by(array('penjualan_id' => $penjualan_id, 'box_paket_id' => $item['box_paket_id']), true);
												$penjualan_detail = object_to_array($penjualan_detail);
												// die_dump($penjualan_detail);

												$box    = $this->box_paket_m->get($item['box_paket_id']);

												echo '<tr>
														<td class="text-center"></td>
														<td class="text-left">'.$box->nama.'</td>
														<td class="text-center">'.$penjualan_detail['jumlah_paket'].'</td>
														<td class="text-center">
															<input type="number" min="0" max="'.$penjualan_detail['jumlah_paket'].'" class="form-control text-right" name="items['.$z.'][jumlah_kirim]" id="jumlah_kirim_'.$z.' ">
														</td>
														<td class="text-center"> </td>
													</tr>';

												$z++;
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
			<div class="row">
				<div class="col-md-3">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Tujuan", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="form-body">

								<div class="form-group">
									<label class="col-md-12"><?=translate("Nama Customer", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<label class=""> 
											<?php 

												if ($form_data['tipe_customer'] == 1) {
													$customer = $this->cabang_m->get($form_data_pengiriman['customer_id']);
												} else {
													$customer = $this->penjualan_customer_m->get($form_data['customer_id']);
												}
												
												echo $customer->nama .' ['.$customer->kode.']';
											?>
										</label>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<label class=""> 
											<?php 

												$alamat = $this->cabang_alamat_m->get_alamat_lengkap($form_data_pengiriman['customer_id']);
												// die_dump($alamat);

												$rt = '';
												$rw = '';
												if ($alamat[0]['rt_rw'] != NULL || $alamat[0]['rt_rw'] != '') {
													
													$rt_rw = $alamat[0]['rt_rw'];

													$pisah_rt_rw = explode('/', $rt_rw);
													// die_dump($pisah_rt_rw);
													$rt = 'RT '.$pisah_rt_rw[0];
													$rw = 'RW '.$pisah_rt_rw[1];
												}

												echo $alamat[0]['alamat'].' '.$rt.' '.$rw.', '.$alamat[0]['nama_kelurahan'].', '.$alamat[0]['nama_kecamatan'].', '.$alamat[0]['nama_kabupatenkota'].' '.$alamat[0]['kode_pos'] ;
											?>
										</label>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-12"><?=translate('Kontak', $this->session->userdata('language'))?> :</label>
									<div class="col-md-12">
										<label class="">
											<?php 

												if ($form_data['tipe_customer'] == 1) 
												{
													$cabang         = $this->cabang_m->get($form_data['customer_id']);
													$cabang_telepon = $this->cabang_telepon_m->get_by(array('is_primary' => 1, 'is_active' => 1, 'cabang_id' => $cabang->id));
													
													echo $cabang->penanggung_jawab.' ('.$cabang_telepon[0]->nomor.')';
												}
												else {
													
													$customer         = $this->penerima_customer_m->get($form_data['customer_id']);
													$customer_telepon = $this->customer_telepon_m->get_by(array('is_primary' => 1, 'is_active' => 1, 'customer_id' => $customer->id));
													
													echo $customer->orang_bersangkutan.' ('.$customer_telepon[0]->nomor.')';
												}
											?>
										</label>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>

			
	</div>
</div>

<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true">
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
<div class="modal fade bs-modal-lg" id="popup_modal_detail" role="basic" aria-hidden="true">
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