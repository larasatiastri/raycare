<?php

	$form_attr = array(
		"id"			=> "form_add_titip_setoran", 
		"name"			=> "form_add_titip_setoran", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."reservasi/titip_terima_setoran/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');


?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Titip Setoran", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body form">
		<div class="portlet-body form">	
			<div class="form-wizard">
				<div class="form-body">
					<div class="alert alert-danger display-hide">
				        <button class="close" data-close="alert"></button>
				        <?=$form_alert_danger?>
				    </div>
				    <div class="alert alert-success display-hide">
				        <button class="close" data-close="alert"></button>
				        <?=$form_alert_success?>
				    </div>

					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-2">
							<div class="input-group date" id="tanggal" >
								<input type="text" class="form-control" id="tanggal" name="tanggal"  readonly >
								<span class="input-group-btn">
									<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
								<!-- value="<?=date('d-M-Y')?>" -->
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Diterima Oleh", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-2">
								<input class="form-control input-sm" id="nomer_{0}" name="nama_ref_user" value="<?=$flash_form_data["nama_ref_user"]?>" placeholder="Diterima Oleh" required>
								<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="<?=$flash_form_data["id_ref_pasien"]?>" placeholder="ID Referensi Pasien">
								<input class="form-control input-sm hidden" id="nomer_{0}" name="tipe_user" value="<?=$flash_form_data["tipe_user"]?>" placeholder="Tipe User">
								<input class="form-control input-sm hidden" id="nomer_{0}" name="kasir_titip_uang_id" value="<?=$flash_form_data["kasir_titip_uang_id"]?>" placeholder="Kasir Titip Uang ID">
							</div>
							<span class="input-group-btn" style="left:-15px;">
								<a class="btn btn-xs btn-primary pilih-user" style="height:20px;" title="<?=translate('Pilih User', $this->session->userdata('language'))?>">
									<i class="fa fa-search"></i>
								</a>
							</span>
						</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Rupiah :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$rupiah = array(
									"name"			=> "rupiah",
									"id"			=> "rupiah",
									"class"			=> "form-control", 
									"placeholder"	=> translate("Rupiah", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($rupiah);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Subjek :", $this->session->userdata("language"))?></label>
						<div class="col-md-2">
							<?php
								$subjek = array(
									"name"			=> "subjek",
									"id"			=> "subjek",
									"class"			=> "form-control", 
									"placeholder"	=> translate("Subjek", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($subjek);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
						<div class="col-md-4">
							
							<?php
								$keterangan = array(
									"name"			=> "keterangan",
									"id"			=> "keterangan",
									"class"			=> "form-control",
									"rows"			=> 6, 
									"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
								);
								echo form_textarea($keterangan);
							?>
							<!-- <textarea rows="6" class="form-control" id="description" name="description"></textarea> -->
						</div>
					</div>

				    <div class="row">
						<div class="col-md-12">
	                        <div class="portlet">
	                            <div class="portlet-title">
	                            	<div class="caption">
					                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Detail Setoran Biaya", $this->session->userdata("language"))?></span>
					                </div>
	                            </div>
	                            <div class="portlet-body">
	                                <!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
	                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_detail_setoran_biaya">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th width="45%"><div class="text-center"><?=translate("Keterangan", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Rupiah", $this->session->userdata('language'))?></div></th>
	                                                <th width="15%"><div class="text-center"><?=translate("Tanggal", $this->session->userdata('language'))?></div></th>
													<th class="table-checkbox"><div class="text-center"><input type="checkbox" class="group-checkable text-center" data-set="#table_add_detail_setoran_biaya .checkboxes"/></div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          
	                                            <!-- <?//=$item_row?> -->
	                                        </tbody>
	                                        <tfoot>
	                                        	<tr role="row" class="heading">
								    				<td align="right"><b>Total</b></td>
								    				<td><input type="hidden" name="total_setoran_biaya" ><div class="text-right bold total_biaya"><b>Rp. 0,-</b></div></td>
								    				<td colspan="2" ><div class="text-center bold total_hari"><b>5 Hari</b></div></td>
								    			</tr>
	                                        </tfoot>
	                                    </table>
	                                </div>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="col-md-12">
	                        <div class="portlet">
	                            <div class="portlet-title">
	                            	<div class="caption">
					                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Detail Setoran Invoice", $this->session->userdata("language"))?></span>
					                </div>
	                            </div>
	                            <div class="portlet-body">
	                                <!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
	                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_detail_setoran_invoice">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th width="45%"><div class="text-center"><?=translate("Nomor Invoice", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Rupiah", $this->session->userdata('language'))?></div></th>
	                                                <th width="15%"><div class="text-center"><?=translate("Tanggal", $this->session->userdata('language'))?></div></th>
													<th class="table-checkbox"><div class="text-center"><input type="checkbox" class="group-checkable text-center" data-set="#table_add_detail_setoran_invoice .checkboxes"/></div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          
	                                            <!-- <?//=$item_row?> -->
	                                        </tbody>
	                                        <tfoot>
	                                        	<tr role="row" class="heading">
								    				<td align="right"><b>Total</b></td>
								    				<td><input type="hidden" name="total_setoran_invoice" ><div class="text-right bold total_invoice"><b>Rp. 0,-</b></div></td>
								    				<td colspan="2" ><input type="hidden" name="tanggal_invoice" ><div class="text-center bold total_hari_invoice"><b>5 Hari</b></div></td>
								    			</tr>
	                                        </tfoot>

	                                    </table>
	                                </div>
	                            </div>
	                        </div>
	                    </div>

	                </div>
				</div>
				<?php
					$confirm_save       = translate('Apa kamu yakin ingin menambahkan titip setoran ini ?',$this->session->userdata('language'));
					$submit_text        = translate('Simpan', $this->session->userdata('language'));
					$reset_text         = translate('Reset', $this->session->userdata('language'));
					$back_text          = translate('Kembali', $this->session->userdata('language'));
				?>
				<div class="form-actions fluid">    
				    <div class="col-md-offset-1 col-md-9">
				        
				        <a class="btn default" href="javascript:history.go(-1)"><?=$back_text?></a>
				        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
				        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
				        <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=$submit_text?></a>
				        
				    </div>          
				</div>
			</div>
		</div>

	</div>			
</div>									
</div>
							
<?=form_close();?>
<?php $this->load->view('reservasi/titip_terima_setoran/pilih_user.php'); ?> 