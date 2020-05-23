<?php
	$invoice = '';
	if(count($data_invoice) != 0) $invoice = object_to_array($data_invoice);

	$msg = translate('Anda yakin akan menambahkan invoice di tindakan ini?', $this->session->userdata('language'));
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
?>
<form id="form_upload_invoice" name="form_upload_invoice" role="form" class="form-horizontal" autocomplete="off">
	<input type="hidden" id="tindakan_hd_id" name="tindakan_hd_id" required="required" class="form-control" value="<?=$data_tindakan['id']?>"> 
	<input type="hidden" id="no_transaksi" name="no_transaksi" required="required" class="form-control" value="<?=$data_tindakan['no_transaksi']?>"> 
	<input type="hidden" id="pasien_id" name="pasien_id" required="required" class="form-control" value="<?=$data_pasien['id']?>">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       

	<div class="modal-body" id="section-upload">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?=$data_tindakan['no_transaksi']?> / <?=$data_pasien['nama']?>
				</div>
				<div class="actions">
					<a class="btn btn-icon-only btn-default btn-circle add-upload">
						<i class="fa fa-plus"></i>
					</a>
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
	                <?php
					
					$form_upload_invoice = '
					<div class="form-group hidden">
						<label class="control-label col-md-4">'.translate("ID", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<input class="form-control" id="id_invoice{0}" name="upload[{0}][id]">
						</div>
					</div>
					<div class="form-group hidden">
						<label class="control-label col-md-4">'.translate("Active", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<input class="form-control" id="is_active_invoice{0}" name="upload[{0}][is_active]">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("No. Invoice", $this->session->userdata("language")).' :<span class="required">*</span></label>
						<div class="col-md-8">
							<div class="input-group">
								<input class="form-control" required id="no_invoice{0}" name="upload[{0}][no_invoice]" placeholder="No. Invoice">
								<span class="input-group-btn">
									<a class="btn red-intense del-this" id="btn_delete_upload_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Upload Invoice", $this->session->userdata("language")).' :<span class="required">*</span></label>
						<div class="col-md-8">
							<input type="hidden" required name="upload[{0}][url]" id="url_invoice_{0}">
							<div id="upload_{0}">
								<span class="btn default btn-file">
									<span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>		
									<input type="file" class="upl_invoice" name="upl" id="upl_{0}" data-url="'.base_url().'upload_new/upload_photo" multiple />
								</span>

							<ul class="ul-img">
							</ul>

							</div>
						</div>
					</div>
					
					';
				?>

				<input type="hidden" id="tpl-form-upload" value="<?=htmlentities($form_upload_invoice)?>">
				<div class="form-body" >
					<ul class="list-unstyled">
					</ul>
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
		baseAppUrl      = mb.baseUrl() + 'klinik_hd/history_transaksi/',
		$form           = $('#form_upload_invoice'),
		tplFormParent   = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
		regExpTplUpload = new RegExp('upload[0]', 'g'),   // 'g' perform global, case-insensitive
		uploadCounter   = 0,
       	tindakan_hd_id = $('input#tindakan_hd_id').val(),
        formsUpload = 
        {
            'upload' : 
            {            
                section  : $('#section-upload', $form),
                urlData  : function(){ return baseAppUrl + 'get_invoice'; },
                template : $.validator.format( tplFormParent.replace(regExpTplUpload, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ uploadCounter++; return uploadCounter-1; },
                fields   : ['id','no_invoice','url','is_active'],
                fieldPrefix : 'upload'
            }   
        };

	$.each(formsUpload, function(idx, form){
        var $section           = form.section,
            $fieldsetContainer = $('ul.list-unstyled', $section);

        $.ajax({
            type     : 'POST',
            url      : form.urlData(),
            data     : {tindakan_hd_id: tindakan_hd_id},
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success  : function( results ) {
                if (results.success === true) {
                    var rows = results.rows;

                    $.each(rows, function(idx, data){
                        addFieldsetParent(form, data);
                    });
                }
                else
                {
                    addFieldsetParent(form,{});
                }

            },
            complete : function(){
                Metronic.unblockUI();
            }
        });

        // handle button add
        $('a.add-upload', form.section).on('click', function(){
            addFieldsetParent(form,{});
        });
         
    });  

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

	$form_invoice  = $('#form_upload_invoice');
	baseAppUrlInv  = mb.baseUrl() + 'klinik_hd/history_transaksi/';
	handleConfirmSave();

});	

function addFieldsetParent(form,data)
{
    var 
        $section           = form.section,
        $fieldsetContainer = $('ul.list-unstyled', $section),
        counter            = form.counter(),
        $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
        fields             = form.fields,
        prefix             = form.fieldPrefix
    ;

    if(Object.keys(data).length>0){
        for (var i=0; i<fields.length; i++){
            // format: name="emails[_ID_1][subject]"
            $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).val( data[fields[i]] );
            $('a.del-this', $newFieldset).attr('data-id',data[fields[0]]);
            $('ul.ul-img', $newFieldset).html('<li class="working"><div class="thumbnail"><a class="fancybox-button" title="'+data[fields[2]]+'" href="'+mb.baseDir()+'cloud/raycare/pages/klinik_hd/history_transaksi/images/'+data[fields[2]]+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/raycare/pages/klinik_hd/history_transaksi/images/'+data[fields[2]]+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a></div><span></span></li>');
       		handleFancybox();
        }       
    }

    $('a.del-this', $newFieldset).on('click', function(){
        var id = $(this).data('id');
    
        handleDeleteFieldset($(this).parents('.fieldset').eq(0), id);
    });

    handleUploadify();

    //jelasin warna hr pemisah antar fieldset
    $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');
};

function handleDeleteFieldset($fieldset, id)
{
	var 
        $parentUl     = $fieldset.parent(),
        fieldsetCount = $('.fieldset', $parentUl).length,
        hasId         = false ; 

    if(id != undefined)
    {
        var i = 0;
        bootbox.confirm('Anda yakin akan menghapus invoice ini?', function(result) {
            if (result==true) {
                i = parseInt(i) + 1;
                if(i == 1)
                {
                	$('input[name$="[is_active]"]', $fieldset).val(0);
                    $fieldset.hide();        
                }
            }
        });
    }
    else
    {
        if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
        $fieldset.remove();            
    }
}


function handleConfirmSave() {
    $('a#confirm_save', $form_invoice).click(function() {
        if (! $form_invoice.valid()) return;

        var i = 0;
        var msg = $(this).data('confirm');
        bootbox.confirm(msg, function(result) {
            Metronic.blockUI({boxed: true});
            if (result==true) {
                i = parseInt(i) + 1;
                $('a#confirm_save', $form_invoice).attr('disabled','disabled');
                if(i === 1)
                {
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrlInv + 'save_upload',
                        data     : $form_invoice.serialize(),
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                           if(results.success === true)
                           {
                                mb.showMessage('success',results.msg,'Sukses');
                                $('a#confirm_save', $form_invoice).removeAttr('disabled');
                                $('a#close', $form_invoice).click();
                           }
                           else
                           {
                                mb.showMessage('error',results.msg,'Gagal');
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
	                tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
	            }
	            else
	            {
	                tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
	            }
	            

	            $('input#url_invoice_'+index).attr('value',filename);
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