<?php
	$form_attr = array(
	    "id"            => "form_proses_batch", 
	    "name"          => "form_proses_batch", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    $hidden = array(
        "command"   => "proses_batch"
    );

    echo form_open(base_url()."keuangan/proses_pembayaran_reimburse/save", $form_attr, $hidden);

    $msg = translate("Apakah anda yakin akan memproses permintaan biaya yang sudah terpilih ini?",$this->session->userdata("language"));

    $data_bank = $this->bank_m->get_by(array('is_active' => 1));

	$bank_option = array();

	foreach ($data_bank as $bank) 
	{
		$bank_option[$bank->id] = $bank->nob.' A/C No.'. $bank->acc_number;
	}

	$biaya_option = array(
		''	=> translate('Pilih', $this->session->userdata('language')).'...'
	);

	$biaya = $this->biaya_m->get_by(array('is_active' => 1));

	foreach ($biaya as $row) {
		$biaya_option[$row->id] = $row->nama;
	}


    $form_option_payment = '<div class="form-group">
					    	<div class="col-md-12">
					    			<select class="form-control payment_type" name="payment_type" id="payment_type]">
									  <option value="1">Cek</option>
									  <option value="3">Transfer</option>
									</select>
					    		
					     	</div>
					    </div>
					    <div class="form-group">
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-addon">Rp.
									</span>
									<input type="number" min="0" class="form-control text-right col-md-2 payment_cek_ID_0 payment_cek" id="nominal" name="nominal" value="0">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">';
	$form_option_payment .=	 form_dropdown('bank_id', $bank_option, '','id="bank_id" class="form-control"');		
	$form_option_payment .=	'</div>
						</div>
						
						<div id="section_1" class="hidden">
							<div class="form-group">
								<div class="col-md-12">
									<input type="text"class="form-control text-right col-md-2 payment_cek_ID_0 payment_cek" id="bank_no_cek" name="bank_no_cek" placeholder="Nomor Cek">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<input type="text"class="form-control text-right col-md-2 payment_penerimacek_ID_0 payment_cek" id="bank_penerima_cek" name="bank_penerima_cek" placeholder="Penerima">
								</div>
							</div>
							
					    </div>
					  
					    <div id="section_3" class="hidden">
					    	<div class="form-group">
								<div class="col-md-12">
									<div class="input-group"><input type="text" name="bank_pegawai_name" value="" id="bank_name_paymen_0" class="form-control" placeholder="Bank"><span class="input-group-btn"><input type="hidden" name="bank_pegawai_id" value="5" id="bank_id_paymen_0" class="form-control"><a class="btn btn-primary search-bank" data-status-row="item_row_add" title="" data-original-title="Pilih Bank"><i class="fa fa-search"></i></a></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<input type="text" min="0" class="form-control text-right col-md-2 payment_tt_ID_0 payment_tt" id="bank_pegawai_nomor" name="bank_pegawai_nomor" placeholder="No. Rekening">
								</div>
							</div>
					    </div>

					    
						<div class="form-group">
							<label class="col-md-12">Tanggal Buat :</label>
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="tanggal_payment_0" name="tanggal" value="'.date('d M Y').'" readonly>
									<span class="input-group-btn">
										<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12">Tanggal Jatuh Tempo :</label>
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="tanggal_payment_0" name="tanggal_jatuh_tempo" value="'.date('d M Y').'" readonly>
									<span class="input-group-btn">
										<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>';
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-money font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Pembayaran Reimburse", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a id="confirm_save" class="btn btn-circle  btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
			<i class="fa fa-check"></i>
			<?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-9">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<?=translate("Daftar Reimburse", $this->session->userdata("language"))?></span>
						</div>
					</div>
					<div class="portlet-body">
						<div class="form-body">	
							<div class="form-group">
								<label class="col-md-3"><?=translate("Pengaju", $this->session->userdata("language"))?> : </label>
								<div class="col-md-8">
									<?php
										$user = $this->user_m->get_by(array('is_active' => 1));

										$user_option = array(
											''			=> translate('Pilih', $this->session->userdata('language')).'..',
										);
										foreach ($user as $usr) {
											$user_option[$usr->id] = $usr->nama;
										}
										echo form_dropdown('user_id', $user_option, '','id="user_id" class="form-control select2" required');
									?>
								</div>
							</div>
							<table class="table table-striped table-bordered table-hover" id="table_proses_batch">
								<thead>
									<tr>
										<th class="text-center" width="2%"><?=translate("Tgl", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="2%"><?=translate("Biaya", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="5%"><?=translate("Image", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="2%"><?=translate("No. Bon", $this->session->userdata("language"))?></th>
										<th class="text-center" width="2%"><?=translate("Nominal", $this->session->userdata("language"))?></th>
										<th class="text-center" width="4%"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
										<th class="text-center" width="2%"><?=translate("Pilih", $this->session->userdata("language"))?></th>
									</tr>
								</thead>
								<tbody>
								
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="portlet light bordered" id="section-payment">
	 				<div class="portlet-title">
	 					<div class="caption">
	 						<?=translate("Jenis Bayar", $this->session->userdata("language"))?>
	 					</div>
	 				</div>
	 				<div class="portlet-body">
		 				<input type="hidden" id="tpl-form-payment" value="<?=htmlentities($form_option_payment)?>">

						<ul class="list-unstyled">
				        </ul>
				        <div class="form-group">
							<label class="col-md-12"><?=translate("Terbilang", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<label id="th_terbilang"><b># Rupiah #<b></label>
							</div>
						</div> 
	                	<div class="form-group">
							<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<textarea class="form-control" id="keterangan" name="keterangan" value="" rows="3"></textarea>	
							</div>
						</div>  
	                </div>
				</div><!-- end of section-payment -->
			</div>
		</div>
	</div>
</div>
<?=form_close()?>
<div id="popover_bank_pegawai_content" class="row" style="display:none;">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_bank">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('Bank', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Atas Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. Rekening', $this->session->userdata('language'))?></div></th>
                    <th width="1%"><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>