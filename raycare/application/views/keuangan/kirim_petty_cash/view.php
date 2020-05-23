<?php

	$form_attr = array(
		"id"			=> "form_edit_kirim_saldo", 
		"name"			=> "form_edit_kirim_saldo", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "edit",
        "id"    => $pk_value
	);


	echo form_open("", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Apa kamu yakin ingin menambahkan titip setoran ini ?',$this->session->userdata('language'));
	$submit_text        = translate('Simpan', $this->session->userdata('language'));
	$reset_text         = translate('Reset', $this->session->userdata('language'));
	$back_text          = translate('Kembali', $this->session->userdata('language'));


?>	
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Kirim Petty Cash', $this->session->userdata('language'))?></span>
    </div>
</div>
<div class="modal-body">
	<div class="portlet light">	
		<div class="portlet-body">
			<div class="form-group">
                <label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
				<label class="col-md-12"><?=date('d-M-Y', strtotime($setoran['tanggal']))?></label>
			</div>
			<div class="form-group">
                <label class="col-md-12"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
                <label class="col-md-12"><?=$setoran['subjek']?></label>
            </div>
            <div class="form-group">
                <label class="col-md-12"><?=translate("No. Cek", $this->session->userdata("language"))?> :</label>
                <label class="col-md-12"><?=$setoran['no_cek']?></label>
            </div>
			
			<div class="form-group">
                <label class="col-md-12"><?=translate("Jumlah Kirim", $this->session->userdata("language"))?> :</label>     
				<label class="col-md-12"><?=formatrupiah($setoran['total_setor'])?></label>		
				
			</div>
			<div class="form-group">
				<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
				<label class="col-md-12" id="terbilang"><?='#'.terbilang($setoran['total_setor']).' Rupiah #'?></label>
			</div>
			<div class="form-group">
				<label class="col-md-12"><?=translate("Pengeluaran Bank", $this->session->userdata("language"))?> :</label>		
					<?php
						$nob = '-';
                        if($setoran['bank_id'] != 0){
                            $banks = $this->bank_m->get($setoran['bank_id']);
                            $nob = $banks->nob.' a/n '.$banks->acc_name.' - '.$banks->acc_number;
                        }

						
					?>
                <label class="col-md-12"><?=$nob?></label>

			</div>
			<div class="form-group">
				<label class="col-md-12"><?=translate("Bukti Cek", $this->session->userdata("language"))?> <span>:</span></label>
				<div class="col-md-12">
					<input type="hidden" name="url_bukti_setor" id="url_bukti_setor" value="<?=$setoran['url_bukti_setor']?>">
					<div id="upload">
                        <ul class="ul-img">
                        <li class="working">
                            <div class="thumbnail">
                                <a class="fancybox-button" title="<?=$setoran['url_bukti_setor']?>" href="<?=base_url().'assets/mb/pages/keuangan/kirim_petty_cash/images/'.$pk_value.'/'.$setoran['url_bukti_setor']?>" data-rel="fancybox-button"><img src="<?=base_url().'assets/mb/pages/keuangan/kirim_petty_cash/images/'.$pk_value.'/'.$setoran['url_bukti_setor']?>" alt="Smiley face" class="img-thumbnail" ></a>
                            </div>
                        </li>
                        </ul>
					</div>
				</div>
			</div>

			
		</div>
	</div>									
</div>
<?php
	$confirm = translate('Anda yakin akan mengirim petty cash ini?', $this->session->userdata('language'));
?>
<div class="modal-footer">
    <a class="btn default" id="close" data-dismiss="modal"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>						
<?=form_close();?>

<script type="text/javascript">
$(document).ready(function(){
    handleFancybox();

});


function handleFancybox() {
    if (!jQuery.fancybox) {
        return;
    }

    if ($(".fancybox-button").size() > 0) {
        $(".fancybox-button").fancybox({
            groupAttr: 'data-rel',
            prevEffect: 'none',
            nextEffect: 'none',
            closeBtn: true,
            helpers: {
                title: {
                    type: 'inside'
                }
            }
        });
    }
};

</script>
