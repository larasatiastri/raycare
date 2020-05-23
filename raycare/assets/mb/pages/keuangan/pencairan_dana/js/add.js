mb.app.add = mb.app.add || {};


(function(o){
    
     var 
        baseAppUrl        = '',
        $form             = $('#form_add_permintaan_biaya'),
        tplFormParent     = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
        regExpTplUpload   = new RegExp('bon[0]', 'g'),   // 'g' perform global, case-insensitive
        $tableTambahBiaya = $('#table_tambah_biaya',$form);
        tplBiayaRow       = $.validator.format($('#tpl_biaya_row',$form).text());
        biayaCounter      = 0;
        uploadCounter     = 0,
        formsUpload       = 
        {
            'bon' : 
            {            
                section  : $('#section-reimburse', $form),
                template : $.validator.format( tplFormParent.replace(regExpTplUpload, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ uploadCounter++; return uploadCounter-1; },
                fieldPrefix : 'bon'
            }   
        };

    var initForm = function(){
        handleValidation();
        handleDatePickers();
        handleConfirmSave();
        
        handleChangeType();

        $.each(formsUpload, function(idx, form){
            var $section           = form.section,
                $fieldsetContainer = $('ul#list_reimburse', $section);

            addFieldsetParent(form,{});

            // handle button add
            $('a.add-upload', form.section).on('click', function(){
                addFieldsetParent(form,{});
            });
             
        }); 

        $('select[name$="[biaya_id]"]', $form).select2();

        addItemRow();
        handleTambahRow();

    };


    function addFieldsetParent(form,data)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul#list_reimburse', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            fields             = form.fields,
            prefix             = form.fieldPrefix
        ;

    
        $('a.del-this', $newFieldset).on('click', function(){
            var id = $(this).data('id');
        
            handleDeleteFieldset($(this).parents('.fieldset').eq(0), id);
        });

        $('input[name$="[nominal_bon]"]', $newFieldset).on('change', function(){
            countTotalBon();
        });
        $('select[name$="[biaya_id]"]', $newFieldset).select2();

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

        countTotalBon();
    }

    function addItemRow()
    {
        if(! isValidLastBiayaRow() ) return;

        var numRow = $('tbody tr', $tableTambahBiaya).length;
        var 
            $rowContainer         = $('tbody', $tableTambahBiaya),
            $newItemRow           = $(tplBiayaRow(biayaCounter++)).appendTo( $rowContainer )
            ;
        $('select[name$="[biaya_id]"]', $newItemRow).focus();

        handleDatePickers();
        $('select[name$="[biaya_id]"]', $newItemRow).select2();
        // handle delete btn
        handleBtnDelete( $('.del-this-biaya', $newItemRow) );
        
        $('input[name$="[nominal]"]', $newItemRow).on('change', function(){
            countTotalBiaya();
        });

    };

     
    function handleTambahRow()
    {
        $('a.add-biaya').click(function() {
            addItemRow();
        });
    };

    function handleBtnDelete($btn)
    {
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableTambahBiaya)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableTambahBiaya).length == 0){
                addItemRow();
            }
            countTotalBiaya();
            e.preventDefault();
        });
    };

    function isValidLastBiayaRow()
    {      
        var 
            $itemBiaya = $('input[name$="[biaya_id]"]', $tableTambahBiaya ),
            itemBiaya    = $itemBiaya.val()           
        
        return (itemBiaya != '');
    };
    
    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            // alert('klik');
            if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $('#save', $form).click();
                }
            });
        });
    };
 

    var handleDatePickers = function () {
        var time = new Date($('#tanggal').val());
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
                autoclose: true,
                update : time

            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }


    var handleValidation = function() {
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

    var handleTerbilang = function(nominal){
        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "get_terbilang",  
            data:  {nominal:nominal},  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                
              $('label#terbilang').text(data.terbilang);
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
        });
    }

    var handleChangeType = function(){

        var $sectionBiaya = $('#section-biaya', $form);
        var $sectionBon = $('#section-reimburse', $form);

        $('input[name="tipe"]', $form).on("change", function(){
            var value = $(this).val();
            if(value == 1){
                $('#section-biaya', $form).removeClass("hidden");
                $('select[name$="[biaya_id]"]', $sectionBiaya).attr('required','required');
                $('input[name$="[tanggal]"]', $sectionBiaya).attr('required','required');
                $('input[name$="[nominal]"]', $sectionBiaya).attr('required','required');
                $('input[name$="[keterangan]"]', $sectionBiaya).attr('required','required');


                $('#section-reimburse', $form).addClass("hidden");
                $('input[name$="[no_bon]"]', $sectionBon).removeAttr('required');
                $('select[name$="[biaya_id]"]', $sectionBon).removeAttr('required');
                $('input[name$="[tanggal]"]', $sectionBon).removeAttr('required');
                $('input[name$="[nominal_bon]"]', $sectionBon).removeAttr('required');
                $('input[name$="[keterangan]"]', $sectionBon).removeAttr('required');
                $('input[name$="[url]"]', $sectionBon).removeAttr('required');

            }if(value == 2){
                $('#section-biaya', $form).addClass("hidden");
                $('select[name$="[biaya_id]"]', $sectionBiaya).removeAttr('required');
                $('input[name$="[tanggal]"]', $sectionBiaya).removeAttr('required');
                $('input[name$="[nominal]"]', $sectionBiaya).removeAttr('required');
                $('input[name$="[keterangan]"]', $sectionBiaya).removeAttr('required');

                $('#section-reimburse', $form).removeClass("hidden");
                $('input[name$="[no_bon]"]', $sectionBon).attr('required','required');
                $('select[name$="[biaya_id]"]', $sectionBon).attr('required','required');
                $('input[name$="[tanggal]"]', $sectionBon).attr('required','required');
                $('input[name$="[nominal_bon]"]', $sectionBon).attr('required','required');
                $('input[name$="[keterangan]"]', $sectionBon).attr('required','required');
                $('input[name$="[url]"]', $sectionBon).attr('required','required');
            }
        });
    }
   
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

    var countTotalBiaya = function(){
        var $inputNominal = $('input.nominal_biaya', $form);
        var total = 0;
        $.each($inputNominal, function(idx, inputNominal){
            var nominal = $(this).val();

            if(nominal == ""){
                nominal = 0;
            }else{
                nominal = parseInt(nominal);
            }

            total = total + nominal;
        }); 

        $('input[name="nominal_show"]', $form).val(mb.formatRp(total));
        $('input[name="nominal"]', $form).val(total);
        handleTerbilang(total);
    }

    var countTotalBon = function(){
        var $inputNominal = $('input.nominal_bon', $form);
        var total = 0;
        $.each($inputNominal, function(idx, inputNominal){
            var nominal = $(this).val();

            if(nominal == ""){
                nominal = 0;
            }else{
                nominal = parseInt(nominal);
            }

            total = total + nominal;
        }); 

        $('input[name="nominal_show"]', $form).val(mb.formatRp(total));
        $('input[name="nominal"]', $form).val(total);
        handleTerbilang(total);
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/permintaan_biaya/';
        initForm();

    };

}(mb.app.add));

$(function(){    
    mb.app.add.init();
});