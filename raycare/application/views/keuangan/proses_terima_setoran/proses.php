<?php

	$form_attr = array(
		"id"			=> "form_proses_titip_setoran", 
		"name"			=> "form_proses_titip_setoran", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "proses",
		"id"		=> $pk_value
	);


	echo form_open(base_url()."keuangan/proses_terima_setoran/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Anda yakin akan menerima setoran ini?',$this->session->userdata('language'));
	$submit_text        = translate('Terima Setoran & Kirim Petty Cash', $this->session->userdata('language'));
	$reset_text         = translate('Reset', $this->session->userdata('language'));
	$back_text          = translate('Kembali', $this->session->userdata('language'));


?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Terima Setoran", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=$back_text?></a>
	        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
	        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
	        <a id="confirm_save" class="btn btn-circle btn-primary hidden" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><i class="fa fa-check"></i> <?=$submit_text?></a>
	        <a id="save_modal" class="btn btn-circle btn-primary" href="<?=base_url()?>keuangan/proses_terima_setoran/modal_verif/<?=$pk_value?>" data-toggle="modal" data-target="#popup_modal_verif"><i class="fa fa-check"></i> <?=$submit_text?></a>
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
					    		<div class="portlet box blue-sharp">
					    			<div class="portlet-title" style="margin-bottom: 0px !important;">
										<div class="caption">
											<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
										</div>
					    			</div>
									<div class="portlet-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-12 bold"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
													<label class="col-md-12"><?=date('d M Y', strtotime($data_setoran['tanggal']))?></label>
													<input type="hidden" name="tanggal" id="tanggal" value="<?=$data_setoran['tanggal']?>" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-12 bold"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?> :</label>		
													<label class="col-md-12"><?=formatrupiah($data_setoran['rupiah_bon'])?></label>		
												</div>
												
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-12 bold"><?=translate("Sisa Saldo", $this->session->userdata("language"))?> :</label>		
													<label class="col-md-12"><?=formatrupiah($data_setoran['rupiah'])?></label>	
													<input type="hidden" name="rupiah" id="rupiah" value="<?=$data_setoran['rupiah']?>" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-12 bold"><?=translate("Setor Ke Bank", $this->session->userdata("language"))?> :</label>		
														<?php
															$nob = '-';
															if($data_setoran['bank_id'] != 0){
																$banks = $this->bank_m->get_by(array('id' => $data_setoran['bank_id']), true);
																$nob = $banks->nob;
															}
														?>
													<label class="col-md-12"><?=$nob?></label>	
													<input type="hidden" name="bank_id" id="bank_id" value="<?=$data_setoran['bank_id']?>" class="form-control">
												</div>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-12 bold"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
											<label class="col-md-12" id="terbilang"><?='#'.terbilang($data_setoran['rupiah']).' Rupiah #'?></label>
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
														<a class="btn btn-primary pilih-user" title="<?=translate('Pilih User', $this->session->userdata('language'))?>">
															<i class="fa fa-search"></i>
														</a>
													</span>
												</div>
												
											</div>
										</div>
										
										<div class="form-group hidden">
											<label class="col-md-12"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?> :</label>		
											<label class="col-md-12"><b><?=$data_setoran['rupiah_bon']?></b></label>		
										</div>
										
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Bukti Setor", $this->session->userdata("language"))?> <span>:</span></label>
											<div class="col-md-12">
												<input type="hidden" name="url_bukti_setor" id="url_bukti_setor" value="<?=$data_setoran['url_bukti_setor']?>">
												<div id="upload">
													<ul class="ul-img">
													<li class="working">
														<div class="thumbnail">
															<a class="fancybox-button" title="<?=$data_setoran['url_bukti_setor']?>" href="<?=config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/titip_terima_setoran/images/'.$pk_value.'/'.$data_setoran['url_bukti_setor']?>" data-rel="fancybox-button"><img src="<?=config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/titip_terima_setoran/images/'.$pk_value.'/'.$data_setoran['url_bukti_setor']?>" alt="Smiley face" class="img-thumbnail" ></a>
														</div>
													</li>
													</ul>

												</div>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12"><?=$data_setoran['subjek']?></label>
											<input type="hidden" name="subjek_" id="subjek_" value="<?=$data_setoran['subjek']?>" class="form-control">
											
										</div>

										<div class="form-group">
											<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12"><?=($data_setoran['keterangan'] != '')?$data_setoran['keterangan']:'-'?></label>
										</div>
									</div>
					    		</div>
					    	</div>
					    	<div class="col-md-7">
					    		<div class="portlet box blue-sharp">
					    			<div class="portlet-title" style="margin-bottom: 0px !important;">
					    				<div class="caption">
											<span class="caption-subject"><?=translate("Daftar Biaya", $this->session->userdata("language"))?></span>
										</div>
					    			</div>
					    			<div class="portlet-body">
			                            <table class="table table-condensed table-striped table-hover" id="table_add_detail_setoran_biaya">
			                                <thead>
			                                    <tr>
			                                        <th class="text-center" width="1%"><?=translate("Nomor", $this->session->userdata("language"))?> </th>
			                                        <th class="text-center" width="1%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
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
					    	<div class="col-md-2">
					    		<div class="portlet box blue-madison">
									<div class="portlet-title" style="margin-bottom: 0px !important;">
										<div class="caption">
											<span class="caption-subject"><?=translate("Kirim Petty Cash", $this->session->userdata("language"))?></span>
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
							            <div class="form-group">
							                <label class="col-md-12 bold"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
							                <div class="col-md-12">
							                    <?php
							                        $subjek = array(
							                            "name"          => "subjek",
							                            "id"            => "subjek",
							                            "class"         => "form-control", 
							                            "placeholder"   => translate("Subjek", $this->session->userdata("language")), 
							                            "required"      => "required"
							                        );
							                        echo form_input($subjek);
							                    ?>
							                </div>
							            </div>

							            <div class="form-group">
							                <label class="col-md-12 bold"><?=translate("Jenis Bayar", $this->session->userdata("language"))?> :</label>
							                <div class="col-md-12">
							                    <?php
								                    $jenis_bayar_option = array(
								                    	''		=> 'Pilih..',
								                    	'1'		=> 'Cek',
								                    	'2'		=> 'Transfer',
								                    	'3'		=> 'Tunai',
								                    );

								                    echo form_dropdown('jenis_bayar', $jenis_bayar_option,'','id="jenis_bayar" class="form-control" required');
							                    ?>
							                </div>
							            </div>

										<div class="form-group hidden" id="div_cek">
							                <label class="col-md-12 bold"><?=translate("No. Cek", $this->session->userdata("language"))?> :</label>      
							                <div class="col-md-12">
							                    <?php
							                        $no_cek = array(
							                            "name"          => "no_cek",
							                            "id"            => "no_cek",
							                            "class"         => "form-control", 
							                            "placeholder"   => translate("No. Cek", $this->session->userdata("language"))
							                        );
							                        echo form_input($no_cek);
							                    ?>
							                </div>
							            </div>

							            <div class="hidden" id="div_transfer">
								            <div class="form-group ">
								                <label class="col-md-12 bold"><?=translate("Bank Tujuan", $this->session->userdata("language"))?> :</label>      
								                <div class="col-md-12">
								                    <?php
								                        $bank_tujuan = array(
								                            "name"          => "bank_tujuan",
								                            "id"            => "bank_tujuan",
								                            "class"         => "form-control", 
								                            "placeholder"   => translate("Bank Tujuan", $this->session->userdata("language"))
								                        );
								                        echo form_input($bank_tujuan);
								                    ?>
								                </div>
								            </div>
								            <div class="form-group">
								                <label class="col-md-12 bold"><?=translate("No.Rek Tujuan", $this->session->userdata("language"))?> :</label>      
								                <div class="col-md-12">
								                    <?php
								                        $norek_tujuan = array(
								                            "name"          => "norek_tujuan",
								                            "id"            => "norek_tujuan",
								                            "class"         => "form-control", 
								                            "placeholder"   => translate("No. Rek Tujuan", $this->session->userdata("language"))
								                        );
								                        echo form_input($norek_tujuan);
								                    ?>
								                </div>
								            </div>

								            <div class="form-group">
								                <label class="col-md-12 bold"><?=translate("Atas Nama", $this->session->userdata("language"))?> :</label>      
								                <div class="col-md-12">
								                    <?php
								                        $atas_nama = array(
								                            "name"          => "atas_nama",
								                            "id"            => "atas_nama",
								                            "class"         => "form-control", 
								                            "placeholder"   => translate("Atas Nama", $this->session->userdata("language"))
								                        );
								                        echo form_input($atas_nama);
								                    ?>
								                </div>
								            </div>
							            </div>
							            

							            <div class="form-group">
											<label class="col-md-12 bold"><?=translate("Kirim dari Bank", $this->session->userdata("language"))?> :</label>		
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
											<label class="col-md-12 bold"><?=translate("Nominal", $this->session->userdata("language"))?> :</label>		
											<div class="col-md-12">
							                    <div class="input-group">
							                        <span class="input-group-addon">
							                            IDR.
							                        </span>
							    					<?php
							    						$nominal = array(
							    							"name"			=> "nominal",
							    							"id"			=> "nominal",
							    							"class"			=> "form-control", 
							    							"placeholder"	=> translate("Nominal", $this->session->userdata("language")), 
							    							"required"		=> "required"
							    						);
							    						echo form_input($nominal);
							    					?>
							                    </div>
							    					<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12 bold"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
											<label class="col-md-12" id="terbilang_nominal"></label>
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
<div class="modal fade bs-modal-sm" id="popup_modal_verif" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">
       </div>
   </div>
</div>
<?=form_close();?>