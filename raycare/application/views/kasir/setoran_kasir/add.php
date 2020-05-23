<?php
	$form_attr = array(
	    "id"            => "form_add_setoran", 
	    "name"          => "form_add_setoran", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."kasir/setoran_kasir/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Buat Setoran', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat setoran ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="portlet box blue-sharp">
				<div class="portlet-title" style="margin-bottom: 0px !important;">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="form-group">
						    <label class="col-md-12 bold"><?=translate("Jenis Bayar", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
		                	<div class="col-md-12">
		                  		<div class="radio-list">
									<label class="radio-inline">
									<input type="radio" name="jenis_bayar" id="tunai" value="1" checked> Tunai</label>
									<label class="radio-inline">
									<input type="radio" name="jenis_bayar" id="mesin" value="2"> Mesin EDC </label>
									
								</div>
		                	</div>
		              	</div>
		              	<div class="form-group">
						    <label class="col-md-12 bold"><?=translate("Tanggal Setor", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
		                	<div class="col-md-12">
		                  		<div class="input-group date" id="tanggal">
		                    	<input type="text" class="form-control" id="tanggal_setor" name="tanggal_setor" value="<?=date('d M Y')?>" readonly required>
		                    		<span class="input-group-btn">
		                    			<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
		                    		</span>
		                    	</div>
		                	</div>
		              	</div>

		              	<div class="form-group">
						    <label class="col-md-12 bold"><?=translate("Tanggal Tindakan", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
		                	<div class="col-md-12">
		                  		<div class="input-group date" id="tanggal">
		                    	<input type="text" class="form-control" id="tanggal" name="tanggal" value="<?=date('d M Y')?>" readonly required>
		                    		<span class="input-group-btn">
		                    			<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
		                    		</span>
		                    	</div>
		                	</div>
		              	</div>
						
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Shift", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
							<div class="col-md-12">
								<?php
									$jenis_option = array(
										''			=> translate('Pilih', $this->session->userdata('language')).'..',
										'1'			=> translate('Shift 1', $this->session->userdata('language')),
										'2'			=> translate('Shift 2', $this->session->userdata('language')),
										'3'			=> translate('Shift 3', $this->session->userdata('language')),
										'4'			=> translate('Shift 4', $this->session->userdata('language')),
									);
									echo form_dropdown('tipe', $jenis_option, '','id="tipe" class="form-control" required');
								?>
							</div>
						</div>
						<div class="form-group">
						    <label class="col-md-12 bold"><?=translate("Bank Tujuan", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
		                	<div class="col-md-12">
		                  		<?php
									$bank_option = array(
										''			=> translate('Pilih', $this->session->userdata('language')).'..',
									);

									$banks = $this->bank_m->get_by(array('is_active' => 1));
									foreach ($banks as $bank) {
										$bank_option[$bank->id] = $bank->nob;
									}
									echo form_dropdown('bank_id', $bank_option, '','id="bank_id" class="form-control" required');
								?>
		                	</div>
		              	</div>
		              	<div id="setor_edc" class="hidden">
			              	<div class="form-group">
							    <label class="col-md-12"><?=translate("Mesin EDC", $this->session->userdata("language"))?>:</label>
			                	<div class="col-md-12" id="data_mesin">
			                  		
			                	</div>
			              	</div>
		              	</div>
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Jumlah Setoran", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-addon">
										&nbsp;Rp&nbsp;
									</span>
									<input class="form-control hidden" id="total" name="total">
									<input class="form-control text-right" id="total_setor" name="total_setor" readonly="">
								</div>
							</div>
						</div>
						
						<div id="setor_tunai">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Nomor Bukti Setoran", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
								<div class="col-md-12">
									<input class="form-control" id="nomor_bukti_setor" name="nomor_bukti_setor" required>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Upload Bukti Setor", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="hidden" name="url_bukti_setor" id="url_bukti_setor">
									<div id="upload">
										<span class="btn default btn-file">
											<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>	
											<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload_new/upload_photo" />
										</span>

										<ul class="ul-img">
										<!-- The file uploads will be shown here -->
										</ul>

									</div>
								</div>
							</div>
						</div>

							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<textarea class="form-control" id="keterangan" name="keterangan" rows="6" cols="4"></textarea> 
								</div>
							</div>

					</div>
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		<div class="col-md-9">
			<div class="portlet box blue-sharp" id="detail_tunai">
				<div class="portlet-title" style="margin-bottom: 0px !important;">
					<div class="caption">
						<?=translate('Detail Invoice Tindakan Sesuai Jadwal', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group hidden">
							<label class="control-label col-md-3"><?=translate("No. Account", $this->session->userdata("language"))?> :</label>
							<div class="col-md-8 ">
								<input class="form-control" id="nomor_account" name="nomor_account">
							</div>
						</div>
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-hover" id="table_invoice">
                                <thead>
                                        <th class="text-center" width="1%"><?=translate("No. Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Tanggal Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Tanggal Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width=""><?=translate("Pasien", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Total Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Jumlah Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width=""><?=translate("Keterangan", $this->session->userdata('language'))?></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
			<div class="portlet box blue-madison" id="detail_tunai_non">
				<div class="portlet-title" style="margin-bottom: 0px !important;">
					<div class="caption">
						<?=translate('Detail Invoice Tindakan Diluar Jadwal', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group hidden">
							<label class="control-label col-md-3"><?=translate("No. Account", $this->session->userdata("language"))?> :</label>
							<div class="col-md-8 ">
								<input class="form-control" id="nomor_account" name="nomor_account">
							</div>
						</div>
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-hover" id="table_invoice_non_jadwal">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="1%"><?=translate("No. Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Tanggal Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Tanggal Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width=""><?=translate("Pasien", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Total Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Jumlah Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width=""><?=translate("Keterangan", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
			<!--
			<div class="portlet light bordered" id="detail_tunai_lab">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Detail Invoice Lab', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="table_invoice_lab">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="10%"><?=translate("No. Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Tgl Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Waktu", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Total Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Jumlah Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="8%"><?=translate("Keterangan", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>

			<div class="portlet light bordered" id="detail_tunai_transfusi">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Detail Invoice Transfusi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="table_invoice_trans">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="10%"><?=translate("No. Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Tgl Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Waktu", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Total Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Jumlah Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="8%"><?=translate("Keterangan", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
			<div class="portlet light bordered" id="detail_tunai_apotik">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Detail Invoice Apotik', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="table_invoice_apotik">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="10%"><?=translate("No. Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Tgl Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Waktu", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Total Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Jumlah Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="8%"><?=translate("Keterangan", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
			<div class="portlet box blue-sharp hidden" id="detail_edc">
				<div class="portlet-title" style="margin-bottom: 0px !important;">
					<div class="caption">
						<?=translate('Detail Invoice Tindakan Sesuai Jadwal', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-hover" id="table_invoice_edc">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="10%"><?=translate("No. Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Tgl Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Waktu", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Total Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Jumlah Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="8%"><?=translate("Mesin EDC", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
			<div class="portlet box blue-madison hidden" id="detail_edc_non">
				<div class="portlet-title" style="margin-bottom: 0px !important;">
					<div class="caption">
						<?=translate('Detail Invoice Diluar Jadwal', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-hover" id="table_invoice_edc_non">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="10%"><?=translate("No. Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Tgl Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Waktu", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Total Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Jumlah Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="8%"><?=translate("Mesin EDC", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
			<div class="portlet box red">
				<div class="portlet-title" style="margin-bottom: 0px !important;">
					<div class="caption">
						<?=translate('Detail Invoice Belum Lunas', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group hidden">
							<label class="control-label col-md-3"><?=translate("No. Account", $this->session->userdata("language"))?> :</label>
							<div class="col-md-8 ">
								<input class="form-control" id="nomor_account" name="nomor_account">
							</div>
						</div>
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-hover" id="table_invoice_hutang">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="10%"><?=translate("No. Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Tgl Inv", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Total Invoice", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Jumlah Bayar", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Sisa", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
		</div><!-- end of <div class="col-md-8"> -->
		
	</div><!-- end of <div class="row"> -->

	</div>
</div>

<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true" style="">
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


<?=form_close()?>




