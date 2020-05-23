<?php
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
    $msg = translate("Apakah anda yakin akan mengubah dokumen ini?",$this->session->userdata("language"));

    $pasien = object_to_array($this->pasien_m->get($pasien_dokumen['pasien_id']));
    $dokumen = object_to_array($this->dokumen_m->get($pasien_dokumen['dokumen_id']));
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title caption-subject font-blue-sharp bold uppercase"><?=translate('Dokumen Pasien', $this->session->userdata('language'))?></h4>
</div>
<form id="modal_ubah_dokumen" name="modal_ubah_dokumen" role="form" class="form-horizontal" autocomplete="off">
<div class="modal-body">
	<div class="portlet light">
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
                <input type="hidden" id="command" name="command" required="required" class="form-control" value="edit">                       
                <input type="hidden" id="pasien_dokumen_id" name="pasien_dokumen_id" required="required" class="form-control" value="<?=$pasien_dokumen['id']?>">                       
                <div class="form-group">
                    <label class="control-label col-md-4"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <label class="control-label" id="nama3" name="nama3" ><?=$pasien['nama']?></label>
                            <input type="hidden" id="pasien_id" name="pasien_id" required="required" class="form-control" value="<?=$pasien['id']?>">                       
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-md-4"><?=translate("Nama Dokumen", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <label class="control-label" id="nama_dokumen" name="nama_dokumen" ><?=$dokumen['nama']?></label>
                        <input type="hidden" id="dokumen_id" name="dokumen_id" required="required" class="form-control" value="<?=$pasien_dokumen['dokumen_id']?>">
                            
                        </div>
					</div>
		        </div>
		        <div id="dokumen_detail">
		        	
		        </div>		       
		         
   			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a id="confirm_save" class="btn btn-primary" id="close" data-dismiss="modal"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';
	$form = $('#modal_ubah_dokumen');
    handleValidation();
	handleDetailDokumen();
});
function handleValidation() {
    var error1   = $('.alert-danger', $form);
    var success1 = $('.alert-success', $form);

    $form.validate({
        // class has-error disisipkan di form element dengan class col-*
        errorPlacement: function(error, element) {
            error.appendTo(element.closest('[class^="col"]'));
        },
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        // rules: {
        // buat rulenya di input tag
        // },
        invalidHandler: function (event, validator) { //display error alert on form submit              
            success1.hide();
            error1.show();
            Metronic.scrollTo(error1, -200);
        },

        highlight: function (element) { // hightlight error inputs
            $(element).closest('[class^="col"]').addClass('has-error');
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control group
        }

        
    });       
}

function handleDetailDokumen(){
	
    var dok_id = $('input#dokumen_id', $form).val();
    var pasien_dok_id = $('input#pasien_dokumen_id', $form).val();
	var pasien_id = $('input#pasien_id', $form).val();
	
	$.ajax
    ({ 
        type: 'POST',
        url: baseAppUrl +  "show_dokumen_detail_edit",  
        data:  {dok_id:dok_id,pasien_dok_id:pasien_dok_id, pasien_id:pasien_id},  
        success:function(result)          //on recieve of reply
        { 
           $('div#dokumen_detail', $form).html(result);

           $('input[type=checkbox]', $form).uniform();
           $('input[type=radio]', $form).uniform();

           handleFancybox();
        
        }   
   });
}

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