mb.app.proses_po = mb.app.proses_po || {};
(function(o){

    var 
        baseAppUrl      = '',
        $form           = $('#form_proses_po'),
        tplFormParent   = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
        tplFormBiaya   = '<li class="fieldset-biaya">' + $('#tpl-form-biaya', $form).val() + '<hr></li>',
        regExpTplUpload = new RegExp('bon[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplBiaya = new RegExp('biaya[0]', 'g'),   // 'g' perform global, case-insensitive
        uploadCounter   = 0,
        biayaCounter   = 0,
        id_po = $('input[name="pembelian_id"]', $form).val(),
        formsUpload = 
        {
            'bon' : 
            {            
                section  : $('#section-bon', $form),
                template : $.validator.format( tplFormParent.replace(regExpTplUpload, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ uploadCounter++; return uploadCounter-1; },
                fieldPrefix : 'bon'
            }   
        },
        formsBiaya = 
        {
            'biaya' : 
            {            
                section  : $('#section-biaya', $form),
                template : $.validator.format( tplFormBiaya.replace(regExpTplBiaya, '{0}') ), //ubah ke format template jquery validator
                urlData  : function(){ return baseAppUrl + 'get_biaya_tambahan'; },
                counter  : function(){ biayaCounter++; return biayaCounter-1; },
                fields   : ['id','pembelian_id','biaya_id','nominal','is_active'],
                fieldPrefix : 'biaya'
            }   
        };

        

    var initform = function()
    {    
        $.each(formsUpload, function(idx, form){
            var $section           = form.section,
                $fieldsetContainer = $('ul#invoiceList', $section);

            addFieldsetParent(form,{});

            // handle button add
            $('a.add-upload', form.section).on('click', function(){
                addFieldsetParent(form,{});
            });
             
        }); 

        $.each(formsBiaya, function(idx, formBiaya){
            var $section           = formBiaya.section,
                $fieldsetContainer = $('ul#biayaList', $section);

            $.ajax({
                type     : 'POST',
                url      : formBiaya.urlData(),
                data     : {id_po: id_po},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    if (results.success === true) {
                        var rows = results.rows;

                        $.each(rows, function(idx, data){
                            addFieldsetBiaya(formBiaya,data);
                        });

                        handleCountTotalBiaya();
                    }
                    else
                    {
                        addFieldsetBiaya(formBiaya,{});
                    }

                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });

            // handle button add
            $('a.add-biaya', formBiaya.section).on('click', function(){
                addFieldsetBiaya(formBiaya,{});
            });
             
        }); 

        $('#form_proses_po').on('keyup keypress', function(e) {

            var keyCode = e.keyCode || e.which;
            if(keyCode == 13){
                e.preventDefault();
                return false;
            }
            
        });

        handleConfirmSave();
    }
    
    function addFieldsetParent(form,data)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul#invoiceList', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            fields             = form.fields,
            prefix             = form.fieldPrefix
        ;

    
        $('a.del-this', $newFieldset).on('click', function(){
            var id = $(this).data('id');
        
            handleDeleteFieldset($(this).parents('.fieldset').eq(0), id);
        });

        $('input[name$="[total_bon]"]', $newFieldset).on('change', function(){
            handleCountTotal();
        });

        handleUploadify();
        handleDatePickers();
        handleUploadifyPajak();
        

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');
    };

    function addFieldsetBiaya(form,data)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul#biayaList', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            fields             = form.fields,
            prefix             = form.fieldPrefix
        ;

        if(Object.keys(data).length>0){
            for (var i=0; i<fields.length; i++){
                // format: name="emails[_ID_1][subject]"
                $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).val( data[fields[i]] );
                $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).attr( 'value', data[fields[i]] );
                $('a.del-this-biaya', $newFieldset).attr('data-id',data[fields[0]]);
            }       
        }
    
        $('a.del-this-biaya', $newFieldset).on('click', function(){
            var id = $(this).data('id');
        
            handleDeleteFieldsetBiaya($(this).parents('.fieldset-biaya').eq(0), id);
        });

        $('input[name$="[nominal]"]', $newFieldset).on('change', function(){
            handleCountTotalBiaya();
        });

        $('select[name$="[biaya_id]"]', $newFieldset).select2();

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
    function handleDeleteFieldsetBiaya($fieldset, id)
    {
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset-biaya', $parentUl).length,
            hasId         = false ; 

        if(id != undefined)
        {
            var i = 0;
            bootbox.confirm('Anda yakin akan menghapus biaya ini?', function(result) {
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

        handleCountTotalBiaya();
    }
    
    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "right",
                autoclose: true,
                format : 'dd M yyyy'
            });

            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "right",
                autoclose: true,
                format : 'dd M yyyy'
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }


    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;

            var i = 0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses...'});
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                      $('#save', $form).click();
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

    var handleCountTotal = function(){
        var $totalBon = $('input[name$="[total_bon]"]', $form),
            grandTotal = 0;
        
        $.each($totalBon, function(idx, totalbon){
            var total = $(this).val();

            if(total == ''){
                total = 0;
            }if(total != ''){
                total = parseInt(total);
            }

            grandTotal = grandTotal + total;
        });

        $('td#label_total_invoice').text(mb.formatRp(grandTotal));
        $('input#total_invoice').val(grandTotal);

    } 

    var handleCountTotalBiaya = function(){
        var $totalBiaya = $('input[name$="[nominal]"]', $form),
            grandTotal = 0,
            grandTotalPO = parseInt($('input#grand_tot_hidden').val());

        
        $.each($totalBiaya, function(idx, totalbiaya){
            var total = $(this).val();

            if(total == ''){
                total = 0;
            }if(total != ''){
                total = parseInt(total);
            }

            grandTotal = grandTotal + total;
        });

        $('td#biaya_tambahan_po').text(mb.formatRp(grandTotal));
        $('input#biaya_tambah_hidden').val(grandTotal);
        $('input#biaya_tambah_hidden').attr('value',grandTotal);
        $('input#grand_tot_biaya_hidden').val(grandTotal + grandTotalPO);
        $('input#grand_tot_biaya_hidden').attr('value', (grandTotal + grandTotalPO));
        $('td#grand_tot_biaya').text(mb.formatRp(grandTotal + grandTotalPO));

    }

    function handleUploadifyPajak()
    {
        $('.upl_invoice').each(function(index)
        {
            var ul_pajak = $('#upload_pajak_'+index+' ul.ul-img-pajak');
       
            // Initialize the jQuery File Upload plugin
            $('#upl_pajak_'+index).fileupload({

                // This element will accept file drag/drop uploading
                dropZone: $('#drop'),
                dataType: 'json',
                // This function is called when a file is added to the queue;
                // either via the browse button, or via drag/drop:
                add: function (e, data) {

                    tplpajak = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

                    // Initialize the knob plugin
                    tplpajak.find('input').knob();

                    // Listen for clicks on the cancel icon
                    tplpajak.find('span').click(function(){

                        if(tplpajak.hasClass('working')){
                            jqXHR.abort();
                        }

                        tplpajak.fadeOut(function(){
                            tplpajak.remove();
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
                        tplpajak.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                    }
                    else
                    {
                        tplpajak.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                    }
                    

                    $('input#bon_url_pajak_'+index).attr('value',filename);
                    // Add the HTML to the UL element
                    ul_pajak.html(tplpajak);
                    handleFancybox();
                    // data.context = tplpajak.appendTo(ul);

                    Metronic.unblockUI();
                        // data.context = tplpajak.appendTo(ul);

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

    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pembayaran_transaksi/';
        initform();
    };
 }(mb.app.proses_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses_po.init();
});