<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_add_permintaan_kasbon", 
		"name"			=> "form_add_permintaan_kasbon", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."keuangan/permintaan_kasbon/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

    $user_level_id = $this->session->userdata('level_id');
    $user_id = $this->session->userdata('user_id');
    $nama_user = $this->session->userdata('nama_lengkap');
  
?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Permintaan Kasbon", $this->session->userdata("language"))?></span>
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

			<div class="row">
				<div class="col-md-3">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-group hidden">
							<label class="col-md-12"><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?=date('d M Y')?>"readonly >
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						 
						<div class="form-group hidden">
							<label class="col-md-12"><?=translate('Pengguna', $this->session->userdata('language'))?> :</label>
							<div class="col-md-12">
									<input class="form-control hidden" id="nomer_{0}" name="user_level_id" value="<?=$user_level_id?>">
									<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="<?=$user_id?>" required placeholder="ID Referensi Pasien">
									<input class="form-control input-sm hidden" id="nomer_{0}" name="cabang_id" value="<?=$flash_form_data["cabang_id"]?>" placeholder="Kasir Titip Uang ID">
									<input class="form-control" id="nomer_{0}" name="nama_ref_user" value="<?=$nama_user?>" placeholder="Diminta Oleh" required readonly>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-12"><?=translate('Tipe', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"> Kasbon </label>
							<div class="col-md-12 hidden">
								<div class="radio-list">
									<label class="radio-inline hidden">
										<input type="radio" id="kasbon" value="1" data-type="1" name="tipe" class="form-control" checked> Kas
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
							<div class="col-md-12">
								<input class="form-control" type="text" id="nominal_show" name="nominal_show" placeholder="Nominal" readonly>
								<input class="form-control" type="hidden" id="nominal" name="nominal" placeholder="Nominal" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?></label>
							<label class="col-md-12" id="terbilang"></label>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('Keperluan', $this->session->userdata('language'))?></label>
							<div class="col-md-12">
								<?php
									$keperluan = array(
										"name"        => "keperluan",
										"id"          => "keperluan",
										"class"       => "form-control",
										"required"	  => "required",
										"rows"        => 10, 
										"placeholder" => translate("Keperluan", $this->session->userdata("language")),
										
									);
									echo form_textarea($keperluan);
								?>
							</div>
						</div>
						
					</div>
				</div>
				<div class="col-md-9" id="section-biaya">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Biaya", $this->session->userdata("language"))?></span>
							</div>
							
						</div>
						<div class="form-body">
						    <div class="portlet-body">

								<div class="form-body">
									<div class="table-responsive">
			                            <table class="table table-condensed table-striped table-bordered table-hover" id="table_tambah_biaya">
			                                <thead>
			                                    <tr role="row">
			                                        <th class="text-center" width="10%"><?=translate("Biaya", $this->session->userdata('language'))?></th> 
			                                        <th class="text-center" width="8%"><?=translate("Kode", $this->session->userdata('language'))?></th> 
			                                        <th class="text-center" width="20%"><?=translate("Item", $this->session->userdata('language'))?></th>
			                                        <th class="text-center" width="5%"><?=translate("Jumlah", $this->session->userdata('language'))?></th>
			                                        <th class="text-center" width="10%"><?=translate("Satuan", $this->session->userdata('language'))?></th>
			                                        <th class="text-center" width="20%"><?=translate("Harga", $this->session->userdata('language'))?></th>
			                                        <th class="text-center" width="20%"><?=translate("Sub Total", $this->session->userdata('language'))?></th>
			                                    </tr>
			                                </thead>
			                                <tbody>
			                                	<?php
			                                		foreach ($data_item as $key => $detail) {
			                                			$item = $this->item_m->get_by(array('id' => $detail), true);
														$item = object_to_array($item);

														$item_satuan = $this->item_satuan_m->get_by(array('id' => $data_item_satuan[$key]), true);
														$item_satuan = object_to_array($item_satuan);
			                                	?>
			                                		<tr>
			                                			<td><div class="text-left"><input type="hidden" name="biaya[<?=$key?>][biaya_id]" value="">Biaya Pembelian</div></td>
			                                			<td><input type="hidden" name="biaya[<?=$key?>][item_id]" value="<?=$item['id']?>"><?=$item['kode']?></td>
			                                			<td><?=$item['nama']?></td>
			                                			<td><input type="number" name="biaya[<?=$key?>][jumlah]" value="<?=$data_jumlah[$key]?>" class="form-control" min="0" max="<?=$data_jumlah[$key]?>" id="biaya_jumlah_<?=$key?>" data-idx="<?=$key?>"> </td>
			                                			<td><input type="hidden" name="biaya[<?=$key?>][item_satuan_id]" value="<?=$item_satuan['id']?>"><?=$item_satuan['nama']?></td>
			                                			<td><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;IDR&nbsp;</span><input type="number" name="biaya[<?=$key?>][harga]" id="biaya_harga_<?=$key?>" value="0" data-idx="<?=$key?>" class="form-control" required></div></td>
			                                			<td><div class="text-right"><label class="control-label" name="items[<?=$key?>][item_sub_total]" id="biaya_label_sub_total_<?=$key?>" ><?=formatrupiah(1*0)?></label></div><input type="hidden" name="biaya[<?=$key?>][sub_total]" id="biaya_sub_total_<?=$key?>" value="0" class="form-control nominal_biaya"></td>
			                                		</tr>
			                                	<?php
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

				
				<?php
					$confirm_save       = translate('Anda yakin untuk menambahkan permintaan kasbon ini?',$this->session->userdata('language'));
					$submit_text        = translate('Simpan', $this->session->userdata('language'));
					$reset_text         = translate('Reset', $this->session->userdata('language'));
					$back_text          = translate('Kembali', $this->session->userdata('language'));
				?>
				<div class="form-actions right">    
			        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
			        	<i class="fa fa-chevron-left"></i>
			        	<?=$back_text?>
			        </a>
			        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
			        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
			        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
			        	<i class="fa fa-check"></i>
			        	<?=$submit_text?>
			        </a>
				</div>
			</div>
		</div>
	</div>
</div>


