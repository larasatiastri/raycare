<?php
	$form_attr = array(
	    "id"            => "form_buat_kwitansi", 
	    "name"          => "form_buat_kwitansi", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "buat_kwitansi"
    );

    echo form_open(base_url()."klaim/proses_klaim/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$data_bank = $this->bank_m->get_data()->result_array();


	$bank_option = array();

	foreach ($data_bank as $bank) 
	{
		$bank_option[$bank['id']] = $bank['nob'].' A/C No.'. $bank['acc_number'];
	}

	$tipe_options = array(
		1		=> 'Cash',
		2		=> 'Check',
		3		=> 'Transfer'
	);

?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Proses Klaim BPJS', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat kwitansi untuk klaim bpjs ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<input type="hidden" class="form-control" id="proses_id" name="proses_id" value="<?=$data_klaim['id']?>"></input>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("No Kwitansi", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-7">
								<input class="form-control" name="no_kwitansi" id="no_kwitansi" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Jumlah Terima", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-7">
								<input class="form-control" name="jumlah_terima" id="jumlah_terima" value="<?=$data_klaim['jumlah_tarif_ina_verif']?>" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Tipe Pembayaran", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-7">
								<?php
									echo form_dropdown('tipe_bayar', $tipe_options, '', 'id="tipe_bayar" class="form-control"');
								?>
							</div>	
						</div>
						<div class="form-group hidden" id="group_nomor">
							<label class="control-label col-md-4" id="label_nomor"><?=translate("No Check", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-7">
								<input class="form-control" type="text" name="no_check_transfer" id="no_check_transfer"></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Bank", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-7">
								<?php
									echo form_dropdown('bank_id', $bank_option, '', 'id="bank_id" class="form-control"');
								?>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Atas Nama", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-7">
								<input class="form-control" name="dibayar_ke" id="dibayar_ke" value="PT. Raycare Health Solution"></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Diterima Dari", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-7">
								<input class="form-control" name="diterima_dari" id="diterima_dari" value="BPJS Kesehatan" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Uang Pembayaran", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-7">
								<input class="form-control" name="deskripsi" id="deskripsi" value="Klaim Program BPJS Kesehatan Bulan Pelayanan <?=date('F Y', strtotime($data_klaim['periode_tindakan']))?>" required></input>
							</div>	
						</div>
						
						
					</div>
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		
	</div><!-- end of <div class="row"> -->

	</div>
</div>


<?=form_close()?>





