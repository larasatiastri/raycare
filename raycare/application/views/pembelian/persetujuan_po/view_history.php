<?php
	$form_attr = array(
	    "id"            => "form_view_pmb", 
	    "name"          => "form_view_pmb", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );

    echo form_open(base_url()."pembelian/pembelian/save", $form_attr);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

			// die_dump($form_data_supplier);
	$data_penawaran = $this->pembelian_penawaran_m->get_by(array('pembelian_id' => $pk_value, 'is_active' => 1));
	if($data_penawaran)
	{
		$data_penawaran = object_to_array($data_penawaran);
		$x = 0;
		$item_row_template_penawaran_db = '';
		foreach ($data_penawaran as $penawaran)
		{
			$btn_del_penawaran_db = '<div class="text-center"><button class="btn red-intense del-this-penawaran-db" data-index="'.$x.'" data-confirm="'.translate('Anda yakin akan menghapus penawaran ini?', $this->session->userdata('language')).'" data-id="'.$penawaran['id'].'" title="Hapus Penawaran"><i class="fa fa-times"></i></button></div>';

			$item_cols_penawaran_db = array(// style="width:156px;
				'penawaran_nomor'  => '<input type="hidden" id="penawaran_id_'.$x.'" name="penawaran['.$x.'][id]" class="form-control" value="'.$penawaran['id'].'"><input id="penawaran_nomor_'.$x.'" name="penawaran['.$x.'][nomor]" class="form-control" value="'.$penawaran['nomor_penawaran'].'">',
				'penawaran_upload' => '<div class="input-group">
											<input id="penawaran_url_'.$x.'" name="penawaran['.$x.'][url]" class="form-control" value="'.$penawaran['url'].'" readonly>
											<span class="input-group-btn" id="upload_'.$x.'">
			                                <span class="btn default btn-file">
			                                    <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
			                                    <input type="file" name="upl" id="pdf_file_'.$x.'" data-url="'.base_url().'upload/upload_pdf" multiple />
			                                </span>
			                                </span>
			                            </div>',
				'action'           => '<input type="hidden" id="penawaran_is_active_'.$x.'" name="penawaran['.$x.'][is_active]" value="1" class="form-control">'.$btn_del_penawaran_db,
			);

			$item_row_template_penawaran_db .=  '<tr id="item_row_penawaran_'.$x.'" ><td>' . implode('</td><td>', $item_cols_penawaran_db) . '</td></tr>';

			$x++;
		}
	}
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-search font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("View Persetujuan Pembelian", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
		</div>
	</div>
	<div class="portlet-body form">
			<div class="row">
				<div class="col-md-3">
					<div class="portlet">
						<div class="portlet-body form">
							<div class="tabbable-custom nav-justified">
								<ul class="nav nav-tabs nav-justified">
									<li class="active">
										<a href="#tab_info" data-toggle="tab">
										Informasi </a>
									</li>
									<li>
										<a href="#tab_supplier" data-toggle="tab">
										Supplier </a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab_info">
										<div class="form-group hidden">
											<input type="hidden" id="id_pembelian" value="<?=$pk_value?>">
										</div>
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("No PO", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12" style="text-align: left;"><?=$form_data['no_pembelian']?></label>
										</div>
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12" style="text-align: left;"><?=date('d M Y', strtotime($form_data['tanggal_pesan']))?></label>
										</div>
										<?php
											$lama_tempo = ($tipe_bayar[0]['lama_tempo'] != '')?$tipe_bayar[0]['lama_tempo'].' Hari':'';
										?>
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate('Tipe Pembayaran', $this->session->userdata('language'))?> :</label>
											<label class="col-md-12"><?=$tipe_bayar[0]['nama'].' '.$lama_tempo?></label>
											
										</div>
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12" style="text-align: left;"><?=date('d M Y', strtotime($form_data['tanggal_kadaluarsa']))?></label>
										</div>
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12" style="text-align: left;"><?=$form_data['keterangan']?></label>
										</div>
									</div>
									<div class="tab-pane" id="tab_supplier">
										
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12" style="text-align: left;"><?=$form_data_supplier[0]['nama'].' ['.$form_data_supplier[0]['kode'].']'?></label>
										</div>
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Contact Person", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12" style="text-align: left;"><?=$form_data_supplier[0]['orang_yang_bersangkutan']?></label>
										</div>
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12" style="text-align: left;"><?=$form_data_supplier[0]['alamat']?></label>
										</div>
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Telepon", $this->session->userdata("language"))?> :</label>
											<div class="col-md-12">
												<?php
													$data_telp = $this->pembelian_m->get_data_no_telp($pk_value)->result();
													echo '<ul style="list-style-type: none; text-align: left; padding: 0px; margin:0px;">'; 
						                			foreach ($data_telp as $no_telp)
						                			{
						                				echo '<li>'.$no_telp->no_telp.'</li>';
						                			}
						                			echo '</ul>';
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption"><?=translate("Penawaran", $this->session->userdata("language"))?></div>						
					</div>
					<div class="portlet-body">
						<span id="tpl_penawaran_row" class="hidden"><?=htmlentities($item_row_template_penawaran)?></span>
						<div class="table-scrollable">
						<table class="table table-striped table-bordered table-hover" id="table_penawaran">
							<thead>
								<tr>
									<th class="text-center" width="100%"><?=translate("Penawaran", $this->session->userdata("language"))?> </th>	
								</tr>
							</thead>
							<tbody>
								<?php
									if($data_penawaran)
									{
										$data_penawaran = object_to_array($data_penawaran);
										foreach ($data_penawaran as $penawaran)
										{
											?>
											<tr>
												<td><a target="_blank" href="<?=base_url()?>assets/mb/pages/pembelian/pembelian/doc/penawaran/<?=$pk_value?>/<?=$penawaran['id']?>/<?=$penawaran['url']?>"><?=($penawaran['nomor_penawaran'] != '')?$penawaran['nomor_penawaran']:$penawaran['url']?></a></td>
											</tr>
											<?php
										}
									}
								?>
							</tbody>
						</table>
						</div>
					</div>
				</div>
				</div>
				<div class="col-md-9">
				<div class="table-scrollable">
					<table class="table table-bordered" id="table_detail_pembelian_view">
						<thead>
							<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Syarat Order", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Stok", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Jumlah Pesan", $this->session->userdata("language"))?> </th>
							<th class="text-center" style="width: 10% !important;"><?=translate("Jumlah Setujui", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Harga Sistem", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Diskon", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="1%"><?=translate("Tolak", $this->session->userdata("language"))?> </th>
						</thead>
						<tbody>
							<?php
								$pembelian_detail = $this->persetujuan_po_history_m->get_data_item_view($pk_value, $form_data_supplier[0]['id'])->result_array();
								$data_head = $this->pembelian_m->get_by(array('id' => $pk_value));
								$data_head_detail = object_to_array($data_head);
								
								$user_level_id = $this->session->userdata('level_id');
								$total = 0;
								$i = 0;

								foreach ($pembelian_detail as $detail_item) {

									$disabled = '';
									$style = '';

									if($detail_item['user_level_id'] != $user_level_id)
									{
										$disabled = 'disabled="disabled"';
										$style = 'style="background-color:#EFEFEF;';
									}
									$harga = $this->supplier_harga_item_m->get_harga_edit($detail_item['id_satuan'])->result_array();
									// die(dump($harga));

									$persetujuan = $this->persetujuan_po_history_m->get_data_persetujuan($pk_value, $detail_item['id_detail'])->result_array();
									// die_dump($persetujuan);
									$stok = $this->inventory_m->get_stok($detail_item['id'],$detail_item['id_satuan']);
									if(count($stok) == 0)
									{
										$data_stok = 0;
									}
									else
									{
										$data_stok = $stok[0]['stok'];
									}

									$sub_total = ($detail_item['harga_beli']-($detail_item['harga_beli']*$detail_item['diskon']/100))*$detail_item['jumlah_disetujui'];

									$checked = '';

									if($detail_item['jumlah_disetujui'] == 0){
										$checked = 'checked="checked"';
									}
									$color = '';
									$jumlah = '';
										
										if($detail_item['is_active'] == 1)
										{
											$total = $total+$sub_total;
										}
									
									$info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info[]" class="lihat-persetujuan" data-id="'.$detail_item['id_detail'].'" '.$disabled.'>'.$detail_item['jumlah_disetujui'].'</a>';
											// die_dump($sub_total);
									$max_min = ($detail_item['max_order'] != NULL && $detail_item['min_order'] != NULL)?$detail_item['max_order'].'/'.$detail_item['min_order']:'-';
									echo '<tr id="item_row_'.$i.'" '.$color.' '.$style.'class="data-item">
												<td class="text-center hidden"><input type="hidden" value="'.$detail_item['id'].'">'.$detail_item['id'].'</td>
												<td class="text-center"><input type="hidden" value="'.$detail_item['kode'].'">'.$detail_item['kode'].'</td>
												<td><input type="hidden" name="items['.$i.'][id]" value="'.$detail_item['id'].'">'.$detail_item['nama'].'</td>
												<td class="text-center"><input type="hidden" value="'.$max_min.'">'.$max_min.'</td>
												<td class="text-left"><input type="hidden" name="items[{0}][satuan]" value="'.$detail_item['id_satuan'].'">'.$detail_item['satuan'].'</td>
												<td class="text-left"><input type="hidden" value="'.$data_stok.'">'.$data_stok.'</td>
												<td class="text-left"><input type="hidden" value="'.$detail_item['jumlah_pesan'].'">'.$detail_item['jumlah_pesan'].'</td>
												<td class="text-left">'.$info.'</td>
												<td class="text-right"><input type="hidden" value="'.$detail_item['harga_beli'].'">Rp. '.number_format($detail_item['harga_beli'],0,'','.').',-</td>
												<td class="text-right"><input type="hidden" value="'.$detail_item['diskon'].'">'.$detail_item['diskon'].'%</td>
												<td class="text-right"><input type="hidden" value="'.$sub_total.'">Rp. '.number_format($sub_total,0,'','.').',-</td>
												<td class="text-center"><input class="checkboxes" disabled name="items[$i][checkbox]" id="checkbox_'.$i.'" type="checkbox" '.$checked.'></td>
											</tr>';
									$i++;
								}

								//$grand_total = $total - intval($total*intval($data_head_detail[0]['diskon']/100)) + intval($total*intval($data_head_detail[0]['pph']/100)) + intval($data_head_detail[0]['biaya_tambahan']);
							?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="9" class="text-right bold">Total</td>
								<td colspan="2">
									<div class="input-group col-md-12">
										<span class="input-group-addon">
											&nbsp;Rp&nbsp;
										</span>
										<input class="form-control text-right" readonly value="<?=formattanparupiahstrip($total)?>" id="total" name="total">
									</div>
									<input class="form-control text-right hidden" readonly value="" id="total_hidden" name="total_hidden">
								</td>
							</tr>
							<tr>
								<td colspan="9" class="text-right bold">Diskon(%)</td>
								<td colspan="2">
									<div class="input-group col-md-12">
										<input class="form-control text-right" readonly type="text" value="<?=$data_head_detail[0]['diskon']?>" id="diskon" name="diskon">
										<span class="input-group-addon">
											&nbsp;%&nbsp;
										</span>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="9" class="text-right bold">PPN(%)</td>
								<td colspan="2">
									<div class="input-group col-md-12">
										<input class="form-control text-right" readonly type="text" value="<?=$data_head_detail[0]['pph']?>" id="pph" name="pph">
										<span class="input-group-addon">
											&nbsp;%&nbsp;
										</span>
									</div>
								</td>
							</tr>
							<?php
								$diskon = $total * ($data_head_detail[0]['diskon']/100);
								$tad = $total - $diskon;

								$tat = $tad + ($tad * $data_head_detail[0]['pph']/100);

								$grand_total =  $tat + intval($data_head_detail[0]['biaya_tambahan']);
							?>
							<tr>
								<td colspan="9" class="text-right bold">Biaya Tambahan</td>
								<td colspan="2">
									<div class="input-group col-md-12">
										<span class="input-group-addon">
											&nbsp;Rp&nbsp;
										</span>
										<input class="form-control text-right" readonly value="<?=formattanparupiahstrip($data_head_detail[0]['biaya_tambahan'])?>" id="biaya_tambahan" name="biaya_tambahan">
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="9" class="text-right bold">Grand Total</td>
								<td colspan="2">
									<div class="input-group col-md-12">
										<span class="input-group-addon">
											&nbsp;Rp&nbsp;
										</span>
										<input class="form-control text-right" readonly value="<?=formattanparupiahstrip($grand_total)?>" id="grand_total" name="grand_total">
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
				</div>
				<div class="col-md-9">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject"><?=translate("Detail Pengiriman", $this->session->userdata("language"))?></span>
						</div>
					</div>
					<div class="portlet-body">
						<div class="form-body">
						<?php 
							$data_pengiriman = $this->pembelian_detail_tanggal_kirim_m->get_tanggal_kirim($pk_value);

							$idx = 1;
							foreach ($data_pengiriman as $key => $data_kirim){
						?>
							
								<table class="table table-striped table-bordered table-hover" >
								<thead>
									<tr>
										<th colspan="4"><?=date('d M Y', strtotime($data_kirim['tanggal_kirim']))?></th>
									</tr>
								</thead>
								<tbody>
							<?php
								$data_kirim_detail = $this->pembelian_detail_tanggal_kirim_m->get_tanggal_kirim_detail($pk_value, $data_kirim['tanggal_kirim']);

								$idy = a;
								foreach ($data_kirim_detail as $key => $kirim_detail) {
							?>
								<tr>
								<td><?=$kirim_detail['kode_item']?></td>
								<td><?=$kirim_detail['nama_item']?></td>
								<td><?=$kirim_detail['jumlah_kirim']?></td>
								<td><?=$kirim_detail['nama_satuan']?></td>
								</tr>
							<?php
								$idy++;

								}	
							?>		
								</tbody>
								</table>
					<?php
								$idx++;
							}
						?>
						</div>
					</div>
				</div>
			</div>
			</div>
	</div>
</div>


<div id="popover_item_content" class="row">
    <div class="col-md-12">
    	<div class="portlet">
			<div class="portlet-body">
		        <table class="table table-condensed table-striped table-bordered table-hover" id="table_data_persetujuan">
		            <thead>
		                <tr>
		                    <th><div class="text-center"><?=translate('User Level', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Order', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Status', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Baca', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Dibaca Oleh', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Persetujuan', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Disetujui Oleh', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Jumlah Persetujuan', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Keterangan', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>
		            </tbody>
		        </table>
    		</div>
    	</div>
    </div>
</div>