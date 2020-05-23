<?php
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
    $msg = translate("Anda yakin akan membatalkan tindakan ini?",$this->session->userdata("language"));
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title caption-subject font-blue-sharp bold uppercase"><?=translate('Keterangan Batal Tindakan', $this->session->userdata('language'))?></h4>
</div>
<form id="modal_keterangan_tolak" name="modal_keterangan_tolak" role="form" class="form-horizontal" autocomplete="off">
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
                <input type="hidden" id="tindakan_hd_id" name="tindakan_hd_id" required="required" class="form-control" value="<?=$id?>">                       
                <div class="form-group">
                    <label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                    <div class="col-md-6">
                        
                            <textarea class="form-control" name="keterangan_tolak" id="keterangan_tolak" value="<?=$keterangan_tolak?>" rows="6" required><?=$keterangan_tolak?></textarea>

                    </div>
                </div>       
		         
   			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="reset" class="btn btn-default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></button>
	<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
	<button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
    $form = $('#modal_keterangan_tolak');
    handleValidation();
    handleConfirmSave();
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

function handleConfirmSave(){
   $('a#confirm_save', $form).click(function() {
        if (! $form.valid()) return;
        var i = 0;
        var msg = $(this).data('confirm');
        bootbox.confirm(msg, function(result) {
            if (result==true) {
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                i = parseInt(i) + 1;
                $('a#confirm_save', $form).attr('disabled','disabled');
                if(i === 1)
                {
                    $.ajax
                    ({ 
                        type: 'POST',
                        url: baseAppUrl +  "hapus_tindakan",  
                        data:  $form.serialize(),
                        dataType : 'json',  
                        success:function(result)          //on recieve of reply
                        { 
                          if(result.success == true)
                          {
                            $('a.btn-reload-antrian').click();
                          }
                        },
                        complete:function()
                        {
                            $('button#close', $form).click();
                            Metronic.unblockUI();
                        }  
                   });
                }
            }
        });
    });
}
</script>