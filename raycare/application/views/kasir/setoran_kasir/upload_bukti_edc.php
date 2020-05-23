<?php

	$msg = translate('Anda yakin akan menambahkan bukti edc ini?', $this->session->userdata('language'));
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
?>
<form id="form_upload_edc" name="form_upload_edc" role="form" class="form-horizontal" autocomplete="off">
	<input type="hidden" id="pembayaran_id" name="pembayaran_id" required="required" class="form-control" value="<?=$data_bayar_tipe['pembayaran_id']?>"> 
	<input type="hidden" id="pembayaran_tipe_id" name="pembayaran_tipe_id" required="required" class="form-control" value="<?=$data_bayar_tipe['id']?>"> 
	
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       

	<div class="modal-body" id="section-upload">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?=$data_bayar['nama_pasien']?> / <?= date('d M Y', strtotime($data_bayar['tanggal']))?>
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
	               	<div class="form-body" >
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Upload Bukti EDC", $this->session->userdata("language"))?> :<span class="required">*</span></label>
							<div class="col-md-8">
								<input type="hidden" required name="upload[0][url]" id="url_bukti_edc_0" value="<?=$data_bayar_tipe['url_bukti_edc']?>">
								<div id="upload_0">
									<span class="btn default btn-file">
										<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>		
										<input type="file" class="upl_invoice" name="upl" id="upl_0" data-url="<?=base_url()?>upload/upload_photo" multiple />
									</span>

								<ul class="ul-img">
							<?php
								if($data_bayar_tipe['url_bukti_edc'] != NULL || $data_bayar_tipe['url_bukti_edc'] != ''){
									?>
									<li class="working">
										<div class="thumbnail">
											<a class="fancybox-button" title="<?=$data_bayar_tipe['pembayaran_id'].'/'.$data_bayar_tipe['id'].'/'.$data_bayar_tipe['url_bukti_edc']?>" href="<?=base_url()?>assets/mb/pages/reservasi/pembayaran/images/<?=$data_bayar_tipe['pembayaran_id'].'/'.$data_bayar_tipe['id'].'/'.$data_bayar_tipe['url_bukti_edc']?>" data-rel="fancybox-button">
											<img src="<?=base_url()?>assets/mb/pages/reservasi/pembayaran/images/<?=$data_bayar_tipe['pembayaran_id'].'/'.$data_bayar_tipe['id'].'/'.$data_bayar_tipe['url_bukti_edc']?>" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a>
										</div>
										<span></span>
									</li>
									<?php
								}
							?>
								</ul>

								</div>
							</div>
						</div>
					</div>
			        
			        
	   			</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
		<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
		<button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	var 
		baseAppUrl      = mb.baseUrl() + 'kasir/setoran_kasir/',
		$form           = $('#form_upload_edc'),
		tplFormParent   = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
		regExpTplUpload = new RegExp('upload[0]', 'g'),   // 'g' perform global, case-insensitive
		uploadCounter   = 0,
       	pembayaran_tipe_id = $('input#pembayaran_tipe_id').val();
       
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

	$form_edc  = $('#form_upload_edc');
	baseAppUrlInv  = mb.baseUrl() + 'kasir/setoran_kasir/';
	handleConfirmSave();
	handleUploadify();
	handleFancybox();
});	

function handleConfirmSave() {
    $('a#confirm_save', $form_edc).click(function() {
        if (! $form_edc.valid()) return;

        var i = 0;
        var msg = $(this).data('confirm');
        bootbox.confirm(msg, function(result) {
            Metronic.blockUI({boxed: true});
            if (result==true) {
                i = parseInt(i) + 1;
                $('a#confirm_save', $form_edc).attr('disabled','disabled');
                if(i === 1)
                {
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrlInv + 'save_upload_edc',
                        data     : $form_edc.serialize(),
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                           if(results.success === true)
                           {
                                mb.showToast('success',results.msg,'Sukses');
                                $('a#confirm_save', $form_edc).removeAttr('disabled');
                                $('a#close', $form_edc).click();
                           }
                           else
                           {
                                mb.showToast('error',results.msg,'Gagal');
                           }
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });               
                }
            }
        });
    });
};

function handleUploadify()
{
	$('.upl_invoice').each(function(index)
	{
		var ul = $('#upload_'+index+' ul.ul-img');
   
	    // Initialize the jQuery File Upload plugin
	    $('#upl_'+index).fileupload({

	        // This element will accept file drag/drop uploading
	        dropZone: $('#drop'),
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
	            

	            $('input#url_bukti_edc_'+index).attr('value',filename);
	            // Add the HTML to the UL element
	            ul.html(tpl);
	            handleFancybox();
	            // data.context = tpl.appendTo(ul);

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