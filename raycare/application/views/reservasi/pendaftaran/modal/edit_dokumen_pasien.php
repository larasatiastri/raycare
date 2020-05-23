<?php
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
    $msg = translate("Apakah anda yakin akan mengubah dokumen ini?",$this->session->userdata("language"));

    $pasien = object_to_array($this->pasien_m->get($pasien_dokumen['pasien_id']));
    $dokumen = object_to_array($this->dokumen_m->get($pasien_dokumen['dokumen_id']));
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title caption-subject font-blue-sharp bold uppercase"><?=translate('Edit Dokumen Pasien', $this->session->userdata('language'))?></h4>
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
                    <label class="control-label col-md-4"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <label class="control-label" id="nama3" name="nama3" ><?=$pasien['nama']?></label>
                            <input type="hidden" id="pasien_id" name="pasien_id" required="required" class="form-control" value="<?=$pasien['id']?>">                       
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-md-4"><?=translate("Nama Dokumen", $this->session->userdata("language"))?> :<span class="required">*</span></label>
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
	<button type="reset" class="btn btn-default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></button>
	<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
	<button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	baseAppUrl = mb.baseUrl() + 'reservasi/pendaftaran_tindakan/';
	$form = $('#modal_ubah_dokumen');
    handleValidation();
	handleDetailDokumen();
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

           handleDatePickers();

           $('.uploadbutton').each(function(index){
           		handleUploadifyDokumen(index);
           });

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

function handleUploadifyDokumen(index)
{
    var ul = $('#upload_dokumen_'+index+' ul.ul-img');

 
    // Initialize the jQuery File Upload plugin
    $('#upload_'+index).fileupload({

        // This element will accept file drag/drop uploading
       
        dataType: 'json',
        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            tpl = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },
        done: function(e, data){
            console.log(data);
            var filename = data.result.filename;
            var filename = filename.replace(/ /g,"_");
            var filetype = data.files[0].type;
            
            if(filetype == 'image/jpeg' || filetype == 'image/png' || filetype == 'image/gif')
            {
                tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
            }
            else
            {
                tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
            }
            $('input[name$="[value]"]', $('#upload_dokumen_'+index)).attr('value',filename);
            // Add the HTML to the UL element
            ul.html(tpl);
            handleFancybox();
            Metronic.unblockUI();
            // data.context = tpl.appendTo(ul);

        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            Metronic.blockUI({boxed: true});
        },

        fail:function(e, data){
            // Something has gone wrong!
            bootbox.alert('File Tidak Dapat Diupload');
            Metronic.unblockUI();
        }
    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }    
}

function handleDatePickers() {
    if (jQuery().datepicker) {
        $('.date', $form).datepicker({
            rtl: Metronic.isRTL(),
            format : 'dd-M-yyyy',
           
        }).on('changeDate', function(ev){
        	$('.datepicker-dropdown').hide();
        });
        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        $('.date').on('click', function(){
            if ($('#ajax_notes').is(":visible") && $('body').hasClass("modal-open") == false) {
                $('body').addClass("modal-open");
            }
        });
    }
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
                        url: baseAppUrl +  "save_dokumen",  
                        data:  $form.serialize(),
                        dataType : 'json',  
                        success:function(result)          //on recieve of reply
                        { 
                          if(result.success == true)
                          {
                            $('a#refresh_upload').click();
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