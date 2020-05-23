<?php
	$form_attr = array(
	    "id"            => "form_add_pengurangan_modal", 
	    "name"          => "form_add_pengurangan_modal", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."keuangan/pengurangan_modal/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"> </i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pengurangan Modal", $this->session->userdata("language"))?></span>
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

			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("Nominal", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-4">
					<div class="input-group">
						<span class="input-group-addon">
							IDR.
						</span>
						<input class="form-control nominal" id="nominal" name="nominal" required>
					</div>
					<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate('Terbilang', $this->session->userdata('language'))?>:</label>
				<label class="col-md-4" id="terbilang"></label>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate('Keperluan', $this->session->userdata('language'))?><span class="required">*</span>:</label>
				<div class="col-md-4">
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
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate('No.Rek Tujuan', $this->session->userdata('language'))?> <span class="required">*</span>:</label>
				<div class="col-md-4">
					<input class="form-control" type="text" id="no_rek_tujuan" name="no_rek_tujuan" placeholder="No. Rekening Tujuan" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate('Bank Tujuan', $this->session->userdata('language'))?> <span class="required">*</span>:</label>
				<div class="col-md-4">
					<input class="form-control" type="text" id="bank_tujuan" name="bank_tujuan" placeholder="Bank Tujuan" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate('A/N Bank Tujuan', $this->session->userdata('language'))?> <span class="required">*</span>:</label>
				<div class="col-md-4">
					<input class="form-control" type="text" id="a_n_bank_tujuan" name="a_n_bank_tujuan" placeholder="A/N Bank Tujuan" >
				</div>
			</div>
			

		</div>
		<?php $msg = translate("Apakah anda yakin akan menyimpan data pengurangan modal ini?",$this->session->userdata("language"));?>
		<?php $msg_proses = translate("Sedang diproses",$this->session->userdata("language"));?>
		<div class="form-actions right">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$msg_proses?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>	
		</div>
	</div>
</div>
<?=form_close()?>



