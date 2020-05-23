<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_add_pengajuan_pembayaran_kasbon", 
		"name"			=> "form_add_pengajuan_pembayaran_kasbon", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."keuangan/pengajuan_pembayaran_kasbon/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

    $user_level_id = $this->session->userdata('level_id');
    $user_id = $this->session->userdata('user_id');
    $nama_user = $this->session->userdata('nama_lengkap');
    // die_dump($user_level_id);

    $btn_del_bon = '<div class="text-center"><button class="btn red-intense del-this-bon" title="Hapus Bon"><i class="fa fa-times"></i></button></div>';

	$item_cols_bon = array(// style="width:156px;
		'bon_upload' => '<div class="input-group">
									<input id="bon_url_{0}" name="bon[{0}][url]" class="form-control" readonly>
									<span class="input-group-btn" id="upload_{0}">
	                                <span class="btn default btn-file">
	                                    <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
	                                    <input type="file" name="upl" id="pdf_file_{0}" data-url="'.base_url().'upload/upload_pdf" multiple />
	                                </span>
	                                </span>
	                            </div>',
		'action'           => $btn_del_bon,
	);

	$item_row_template_bon =  '<tr id="item_row_bon_{0}" ><td>' . implode('</td><td>', $item_cols_bon) . '</td></tr>';
?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("PENGAJUAN PEMBAYARAN KASBON", $this->session->userdata("language"))?></span>
		</div>

		<?php
			$confirm_save       = translate('Anda yakin untuk menambahkan permintaan biaya ini?',$this->session->userdata('language'));
			$submit_text        = translate('Simpan', $this->session->userdata('language'));
			$reset_text         = translate('Reset', $this->session->userdata('language'));
			$back_text          = translate('Kembali', $this->session->userdata('language'));
		?>
		<div class="actions">    
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
		</div>
		<div class="form-wizard">
			<div class="row">
				<div class="col-md-3">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?=date('d M Y')?>"readonly >
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						 
						<div class="form-group">
							<div class="col-md-12">
									<input class="form-control hidden" id="nomer_{0}" name="user_level_id" value="<?=$user_level_id?>">
									<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="<?=$user_id?>" required placeholder="ID Referensi Pasien">
									<input class="form-control input-sm hidden" id="nomer_{0}" name="cabang_id" value="<?=$flash_form_data["cabang_id"]?>" placeholder="Kasir Titip Uang ID">
									<input class="form-control" id="nomer_{0}" name="nama_ref_user" value="<?=$nama_user?>" placeholder="Diminta Oleh" required readonly>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<input type="text" class="form-control" id="subjek" name="subjek" placeholder="Subjek" required>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<input type="text" class="form-control" id="no_cek" name="no_cek" placeholder="No. Cek" required>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-addon">
										Rp.
									</span>
									<input class="form-control" type="number" id="nominal" name="nominal" placeholder="Nominal" required>
									
								</div>
								<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?></label>
							<label class="col-md-12" id="terbilang"></label>

						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Kirim dari Bank", $this->session->userdata("language"))?> :</label>		
							<div class="col-md-12">
								<?php
									$banks = $this->bank_m->get_by(array('is_active' => 1));

									$bank_option = array(
										'' => translate('Pilih', $this->session->userdata('language')).'...'
									);

									foreach ($banks as $bank) {
										$bank_option[$bank->id] = $bank->nob.' a/n '.$bank->acc_name.' - '.$bank->acc_number;
									}

									echo form_dropdown('bank_id', $bank_option, '', 'id="bank_id" class="form-control" required');
								?>
							</div>
						</div>

					</div>
					
				</div>
				<div class="col-md-9" id="section-Keterangan">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Kasbon", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-body">
						    <div class="portlet-body">
								<table class="table table-condensed table-striped table-bordered table-hover" id="tabel_kasbon">
	                                <thead>
	                                    <tr>
	                                        <th class="text-center" width="1%"><?=translate("Pilih", $this->session->userdata("language"))?> </th>
	                                        <th class="text-center" width="15%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="10%"><?=translate("Tipe", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="20%"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="15%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Keperluan", $this->session->userdata("language"))?> </th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                  
	                                    <!-- <?//=$item_row?> -->
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
<div class="modal fade bs-modal-lg" id="modal_detail" role="basic" aria-hidden="true">
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


