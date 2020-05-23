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


	echo form_open(base_url()."keuangan/titip_terima_setoran/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Apa kamu yakin ingin menambahkan titip setoran ini ?',$this->session->userdata('language'));
	$submit_text        = translate('Simpan', $this->session->userdata('language'));
	$reset_text         = translate('Reset', $this->session->userdata('language'));
	$back_text          = translate('Kembali', $this->session->userdata('language'));


?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pengajuan Saldo Kas Kecil", $this->session->userdata("language"))?></span>
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
						<div class="portlet-body">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
								
								<div class="col-md-12">
									<div class="input-group date" id="tanggal" >
										<input type="text" class="form-control" id="tanggal" name="tanggal" value="<?=date('d M Y')?>" readonly >
										<span class="input-group-btn">
											<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group hidden">
									<label class="col-md-12 bold"><?=translate("Diterima Oleh", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<div class="input-group">
											<input class="form-control input-sm" id="nomer_{0}" name="nama_ref_user" value="<?=$flash_form_data["nama_ref_user"]?>" placeholder="Diterima Oleh" >
											<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="<?=$flash_form_data["id_ref_pasien"]?>" placeholder="ID Referensi Pasien">
											<input class="form-control input-sm hidden" id="nomer_{0}" name="tipe_user" value="<?=$flash_form_data["tipe_user"]?>" placeholder="Tipe User">
											<input class="form-control input-sm hidden" id="nomer_{0}" name="kasir_titip_uang_id" value="<?=$flash_form_data["kasir_titip_uang_id"]?>" placeholder="Kasir Titip Uang ID">
											<span class="input-group-btn">
												<a class="btn btn-primary pilih-user" style="height:20px;" title="<?=translate('Pilih User', $this->session->userdata('language'))?>">
													<i class="fa fa-search"></i>
												</a>
											</span>
										</div>
										
									</div>
								</div>
							<div class="form-group hidden">
								<label class="col-md-12"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?> :</label>		
								<div class="col-md-12">
									<?php
										$rupiah_bon = array(
											"name"			=> "rupiah_bon",
											"id"			=> "rupiah_bon",
											"class"			=> "form-control", 
											"placeholder"	=> translate("Jumlah Biaya", $this->session->userdata("language")), 
											"readonly"		=> "readonly"
										);
										echo form_input($rupiah_bon);
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?> :</label>		
								<label class="col-md-12" id="jumlah_bon"></label>		

							</div>
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Sisa Saldo", $this->session->userdata("language"))?> :</label>		
								<div class="col-md-12">
									<?php
										$rupiah = array(
											"name"			=> "rupiah",
											"id"			=> "rupiah",
											"class"			=> "form-control", 
											"placeholder"	=> translate("Sisa Saldo", $this->session->userdata("language")), 
											"required"		=> "required"
										);
										echo form_input($rupiah);
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
								<label class="col-md-12" id="terbilang"></label>
							</div>
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Setor Ke Bank", $this->session->userdata("language"))?> :</label>		
								<div class="col-md-12">
									<?php
										$banks = $this->bank_m->get_by(array('is_active' => 1));

										$bank_option = array(
											'' => translate('Pilih', $this->session->userdata('language')).'...'
										);

										foreach ($banks as $bank) {
											$bank_option[$bank->id] = $bank->nob.' a/n '.$bank->acc_name.' - '.$bank->acc_number;
										}

										echo form_dropdown('bank_id', $bank_option, '', 'id="bank_id" class="form-control"');
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Upload Bukti Setor", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="hidden" name="url_bukti_setor" id="url_bukti_setor">
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
								<label class="col-md-12 bold"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
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
								<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
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
								</div>
							</div>
						</div>
		    		</div>
		    	</div>
		    	<div class="col-md-9">
		    		<div class="portlet light bordered">
		    			<div class="portlet-title">
		    				<div class="caption">
								<span class="caption-subject"><?=translate("Daftar Biaya", $this->session->userdata("language"))?></span>
							</div>
		    			</div>
		    			<div class="portlet-body">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_detail_setoran_biaya">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="1%"><?=translate("Nomor", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="1%"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="1%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Keperluan", $this->session->userdata("language"))?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    <!-- <?//=$item_row?> -->
                                </tbody>
                                <tfoot>
                                	
                                </tfoot>
                            </table>
                        </div>
	                </div>
		    	</div>
		    </div>
		</div>	
		<div class="form-actions right">
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=$back_text?></a>
	        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
	        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
	        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><i class="glyphicon glyphicon-floppy-disk"></i> <?=$submit_text?></a>
		</div>		
	</div>									
</div>						
<?=form_close();?>
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