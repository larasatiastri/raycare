<?php

	$form_attr = array(
		"id"			=> "form_add_bon", 
		"name"			=> "form_add_bon", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "upload_cek",
	);
	echo form_open(base_url()."keuangan/pengajuan_pembayaran_kasbon/save", $form_attr,$hidden);
?>	
<div class="modal-header">
</div>
<div class="modal-body">

</div>

<div class="modal-footer">
    <a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" id="btn_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
<?=form_close();?>

<script type="text/javascript">
$(document).ready(function() {
	var 
		baseAppUrl      = '',
		$form           = $('#form_add_bon'),
		$tableKasbon    = $('#tabel_kasbon'),
		tplFormParent   = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
		regExpTplUpload = new RegExp('bon[0]', 'g'),   // 'g' perform global, case-insensitive
		uploadCounter   = 0,
		formsUpload     = 
        {
            'bon' : 
            {            
                section  : $('#section-bon', $form),
                template : $.validator.format( tplFormParent.replace(regExpTplUpload, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ uploadCounter++; return uploadCounter-1; },
                fieldPrefix : 'bon'
            }   
        };

      	$.each(formsUpload, function(idx, form){
            var $section           = form.section,
                $fieldsetContainer = $('ul.list-unstyled', $section);

            addFieldsetParent(form,{});

            // handle button add
            $('a.add-upload', form.section).on('click', function(){
                addFieldsetParent(form,{});
            });
             
        }); 

        handleBtnOk();
});

function handleBtnOk(){
    $('a#btn_ok').click(function(){

        var kasbon_id = $('input#kasbon_id').val();
        $tr_kasbon = $('tr#item_row_'+kasbon_id);

        $('div#container_bon', $tr_kasbon).html($('div#list_containt').html());
        $('a#close').click();
    });
}

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


    $('a.del-this', $newFieldset).on('click', function(){
        var id = $(this).data('id');
    
        handleDeleteFieldset($(this).parents('.fieldset').eq(0), id);
    });

    handleUploadify();
    handleDatePickers();

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

function handleUploadify()
{
    $form           = $('#form_add_bon');

    $('input.upl_invoice', $form).each(function(index)
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
                

                $('input#bon_url_'+index).attr('value',filename);
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

function handleDatePickers() {

    if (jQuery().datepicker) {
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            orientation: "left",
            autoclose: true,
            format : 'dd M yyyy'
        }).on('changeDate', function(){
            $('div.datepicker-dropdown').hide();
        });
        $('body').removeClass("modal-open");;

        $('.date').datepicker({
            rtl: Metronic.isRTL(),
            orientation: "left",
            autoclose: true,
            format : 'dd M yyyy'
        }).on('changeDate', function(){
            $('div.datepicker-dropdown').hide();
        });
        $('body').removeClass("modal-open");;
        //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }
}
</script>