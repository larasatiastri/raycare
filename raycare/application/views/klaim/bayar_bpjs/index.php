<?php
	$form_attr = array(
	    "id"            => "form_bayar_bpjs", 
	    "name"          => "form_bayar_bpjs", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."klaim/compare_file/send_mail", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$msg = translate("Apakah anda yakin akan membayar iuran bpjs ini?",$this->session->userdata("language"));

	$merchant_id = '327';
    $sharedkey = 'sta911n9reickar3';
    $url = 'http://103.252.51.158:2124/biller/inquiry';

    $hash = SHA1($merchant_id.$sharedkey."8888801261103951");

    $parameter = array(
        "MERCHANT_ID"  => $merchant_id,
        "ORDER_ID"     => "001",
        "CHECKSUMHASH" => $hash,
        "PRODUCT_CODE" => "BPJSKS",
        "SUBSCRIBER_NUMBER" => $array_input['no_va'],
        "PERIOD" => "01",
        "MOBILE_NO" => ""
    );

    $periode_option = array(
    	''		=> 'Pilih Periode...',
    	'01'	=> '01',
    	'02'	=> '02',
    	'03'	=> '03',
    	'04'	=> '04',
    	'05'	=> '05',
    	'06'	=> '06',
    	'07'	=> '07',
    	'08'	=> '08',
    	'09'	=> '09',
    	'10'	=> '10',
    	'11'	=> '11',
    	'12'	=> '12'
   	);

   	$mesin_edc = $this->mesin_edc_m->get_data_full()->result_array();
    $mesin_edc_option = array(
        '' => translate('Pilih', $this->session->userdata('language')) . '..'
    );
    foreach ($mesin_edc as $msn) {
        $mesin_edc_option[$msn['id']] = $msn['nama'].' - '.$msn['nob'];
    }
    
    $jenis_kartu_options = array(
        '0' => translate('Pilih Jenis Kartu', $this->session->userdata('language')) . '..',
        '1' => translate('Kartu Debit', $this->session->userdata('language')),
        '2' => translate('Kartu Kredit', $this->session->userdata('language')),
    );
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Bayar Iuran BPJS", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-6">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<?=translate("Form Input", $this->session->userdata("language"))?></span>
						</div>
					</div>
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
							<label class="col-md-4 control-label" ><?=translate("No. Virtual Account", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
				
							<div class="col-md-8">
								<input type="text" name="no_va" id="no_va" placeholder="No. Virtual Account" class="form-control">

							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label"><?=translate("Periode", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-8">
								<?php
									echo form_dropdown('periode_bayar', $periode_option,'','id="periode" class="form-control"');
								?>
							</div>
						</div>
						<div id="detail_tagihan">
							<div class="form-group">
								<label class="col-md-4 control-label" ><?=translate("Nama Pelanggan", $this->session->userdata("language"))?> : </label>
					
								<div class="col-md-8">
									<input type="hidden" name="order_id" id="order_id" value="" class="form-control">
									<input type="hidden" name="inquiry_id" id="inquiry_id" value="" class="form-control">
									<input type="hidden" name="trx_id" id="trx_id" value="" class="form-control">
									<input type="hidden" name="periode" id="periode" value="" class="form-control">
									<input type="hidden" name="jpa_ref" id="jpa_ref" value="" class="form-control">
									<input type="hidden" name="no_va_keluarga" id="no_va_keluarga" value="" class="form-control">
									<input type="hidden" name="no_va_kepala_keluarga" id="no_va_kepala_keluarga" value="" class="form-control">
									<input type="hidden" name="nama_pelanggan" id="nama_pelanggan" value="" class="form-control">
									<input type="hidden" name="jumlah_peserta" id="jumlah_peserta" value="" class="form-control">
									<input type="hidden" name="jumlah_tagihan" id="jumlah_tagihan" value="" class="form-control">
									<input type="hidden" name="biaya_admin" id="biaya_admin" value="" class="form-control">
									<input type="hidden" name="total_bayar_trx" id="total_bayar_trx" value="" class="form-control">
									<label id="nama_pelanggan"> </label>
								</div>
							</div>	
							<div class="form-group">
								<label class="col-md-4 control-label" ><?=translate("Jumlah Peserta", $this->session->userdata("language"))?> : </label>
					
								<div class="col-md-8">
									<label id="jumlah_peserta"> </label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" ><?=translate("Periode Pembayaran", $this->session->userdata("language"))?> : </label>
					
								<div class="col-md-8">
									<label id="periode_pembayaran"> </label>
								</div>
							</div>	
							<div class="form-group">
								<label class="col-md-4 control-label" ><?=translate("Jumlah Tagihan", $this->session->userdata("language"))?> : </label>
					
								<div class="col-md-8">
									<label id="jumlah_tagihan"> </label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" ><?=translate("Biaya Admin", $this->session->userdata("language"))?> : </label>
					
								<div class="col-md-8">
									<label id="biaya_admin"> </label>
								</div>
							</div>	
							<div class="form-group">
								<label class="col-md-4 control-label" ><?=translate("Total Bayar", $this->session->userdata("language"))?> : </label>
					
								<div class="col-md-8">
									<label id="total_bayar"> </label>
								</div>
							</div>	
						</div>
					</div>
					<div class="form-actions right">
						
						<a id="cek_tagihan" class="btn btn-info">
							<i class="fa fa-dollar"></i>
							<?=translate("Lihat Tagihan", $this->session->userdata("language"))?>
						</a>
						
					</div>
				</div>
			</div>
			<div class="col-md-6">								
				<div class="portlet light bordered" id="section-payment">
	 				<div class="portlet-title">
	 					<div class="caption">
	 						<?=translate("Jenis Bayar", $this->session->userdata("language"))?>
	 					</div>
	 					<div class="actions">
				            
	 					</div>
	 				</div>
	 				<div class="form-body">
						<ul class="list-unstyled">
							<li class="fieldset">
								<div class="form-group">
							    	<div class="col-md-12">
							    			<select class="form-control payment_type" name="payment[_ID_0][payment_type]" id="payment[_ID_0][payment_type]">
											  <option value="1">Tunai</option>
											  <option value="2">Mesin EDC</option>
											</select>
							    		
							     	</div>
							    </div>
							    <div id="section_2" hidden>
							    	<div class="form-group">
										<div class="col-md-12">
		            						<?php echo form_dropdown('payment[_ID_0][mesin_edc_id]', $mesin_edc_option, ' ', "id=\"mesin_edc_id\" class=\"form-control\"")?>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12">
		            						<?php echo form_dropdown('payment[_ID_0][jenis_kartu]', $jenis_kartu_options, ' ', "id=\"jenis_kartu\" class=\"form-control\"")?>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12">
											<input class="form-control col-md-2" id="payment[_ID_0][no_kartu]" name="payment[_ID_0][no_kartu]" placeholder="No. Kartu">
										</div>
									</div>
							    </div>
							    <div id="section_1" hidden>
									<div class="form-group">
										<div class="col-md-12">
											<label class="control-label">Jumlah Bayar :</label>
										</div>
										<div class="col-md-12">
											<div class="input-group">
												<span class="input-group-addon">Rp.
												</span>
												<input type="number" min="0" class="form-control text-right col-md-2 payment_cash_ID_0 payment_cash" id="payment[_ID_0][jumlah_bayar]" name="payment[_ID_0][jumlah_bayar]" value="0">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12">
											<label class="control-label">Total Invoice :</label>
										</div>
										<div class="col-md-12">
											<div class="input-group">
												<span class="input-group-addon">Rp.
												</span>
												<input type="number" min="0" class="form-control text-right col-md-2 payment_cash_ID_0 payment_cash" id="payment[_ID_0][nominal]" name="payment[_ID_0][nominal]" value="0" readonly>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12">
											<label class="control-label">Kembali :</label>
										</div>
										<div class="col-md-12">
											<div class="input-group">
												<span class="input-group-addon">Rp.
												</span>
												<input type="number" min="0" class="form-control text-right col-md-2 payment_cash_ID_0 payment_cash" id="payment[_ID_0][kembali]" name="payment[_ID_0][kembali]" value="0" readonly>
											</div>
										</div>
									</div>
								</div>
							</li>
		                </ul>
		            </div>
		            <div class="form-actions right">
						<?php $msg_po_kasbon = translate("Apakah anda yakin akan membayar tagihan ini?",$this->session->userdata("language"));?>
						
						<a id="bayar_tagihan" class="btn btn-success hidden">
							<i class="fa fa-money"></i>
							<?=translate("Bayar Tagihan", $this->session->userdata("language"))?>
						</a>
						
			            <button type="submit" id="save" class="btn default hidden" >			            	
			            	<?=translate("Simpan", $this->session->userdata("language"))?>
			            </button>
					</div>
				</div><!-- end of section-payment -->
			</div>

		</div>
	</div>	
</div>
<?=form_close()?>







