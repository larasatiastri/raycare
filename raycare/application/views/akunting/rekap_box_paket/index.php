<?php
	$form_attr = array(
	    "id"            => "form_rekap_box_paket", 
	    "name"          => "form_rekap_box_paket", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."akunting/rekap_box_paket", $form_attr, $hidden);
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
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("REKAP BOX PAKET", $this->session->userdata("language"))?></span>
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
						<div class="col-md-3">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Status", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<?php
										$status_array = array(
										    0 => translate('Semua', $this->session->userdata('language')),
										    3 => translate('Digunakan', $this->session->userdata('language')),
										    4 => translate('Dikembalikan', $this->session->userdata('language')),
										);
										echo form_dropdown('status', $status_array, $status, "id=\"status\" class=\"form-control select2\" required ");
									?>
								</div>
							</div>
						</div>
						<div class="col-md-4">
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
					</div>
				</div>
			</div>
			<table class="table table-condensed table-striped table-hover" id="table_rekap" style="border:1px;">
				<thead>
					
					<tr>
						<th class="text-center"><?=translate("No", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("Tgl.Tindakan", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("Kode Box", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="30%"><?=translate("Item", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("BN", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("ED", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("H.Beli", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="3%"><?=translate("Status", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="5%"><?=translate("Tgl.Buat", $this->session->userdata("language"))?> </th>
							
					</tr>
				</thead>
				<tbody>

					<?php
						$i=1;

						// die(dump($data_laporan));
						$total_harga_beli = 0;
						foreach ($data_laporan as $key => $laporan):
							
							$harga_beli = 0;
							$harga_beli = $laporan['harga_beli'];

							$status = "";
							if($laporan['status'] == 3){
								$status = '<div class="text-left"><span class="label label-md label-danger">Digunakan</span></div>';

							}if($laporan['status'] == 4){
								$status = '<div class="text-left"><span class="label label-md label-warning">Dikembalikan</span></div>';

							}

					?>
							<tr>
								<td><?=$i?></td>
								<td><div class="inline-button-table"><?=date('d M Y', strtotime($laporan['tanggal_tindakan']))?></div></td>
								<td><div class="inline-button-table"><?=$laporan['kode_box_paket']?></td>
								
								<td><div class="inline-button-table"><?=$laporan['nama']?></td>						
								<td class="inline-button-table"><?=$laporan['jumlah'].' '.$laporan['satuan']?></td>
								<td class="inline-button-table"><?=$laporan['bn_sn_lot']?></td>
								<td class="inline-button-table"><?=date('d M Y', strtotime($laporan['expire_date']))?></td>
								<td class="text-right"><?=formattanparupiah($harga_beli)?></td>
								
								<td><div class="inline-button-table inline-button-table"><?=$status?></td>
								<td class="inline-button-table"><?=$laporan['nama_pasien']?></td>
								<td><div class="inline-button-table"><?=date('d M Y', strtotime($laporan['tanggal_buat']))?></div></td>
								
							</tr>
					<?php
						
						$total_harga_beli = $total_harga_beli + $harga_beli;
						
						$i++;
						endforeach;
					?>
					
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2"> Total Box Paket</th>
						
						<th class="text-right"><?=count($data_laporan_group)?></th>
						<th class="text-right" colspan="4"> Total Harga Beli</th>
						<th class="text-right"><?=formattanparupiah($total_harga_beli)?></th>
						
						<th colspan="3"> </th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div><!-- akhir dari portlet -->
<?=form_close();?>