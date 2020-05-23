mb.app.proses = mb.app.proses || {};
(function(o){

    var 
        baseAppUrl        = '',
        $form             = $('#form_proses_permintaan_biaya'),
        tplFormParent     = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
        regExpTplUpload   = new RegExp('bon[0]', 'g'),   // 'g' perform global, case-insensitive
        $popoverPOContent = $('#popover_po_content'), 
        $tablePilihPO     = $('#table_pilih_po'),
        $lastPopoverPO    = null,
        uploadCounter     = 0,
        formsUpload = 
        {
            'bon' : 
            {            
                section  : $('#section-bon', $form),
                template : $.validator.format( tplFormParent.replace(regExpTplUpload, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ uploadCounter++; return uploadCounter-1; },
                fieldPrefix : 'bon'
            }   
        };

        

    var initform = function()
    {    
        $.each(formsUpload, function(idx, form){
            var $section           = form.section,
                $fieldsetContainer = $('ul.list-unstyled', $section);

            addFieldsetParent(form,{});

            // handle button add
            $('a.add-upload', form.section).on('click', function(){
                addFieldsetParent(form,{});
            });
             
        }); 

        handleConfirmSave();

        var $btnSearchPO  = $('.pilih-po', $form);
        handleBtnSearchPO($btnSearchPO);

        handlePilihPO();
    }

    var handleBtnSearchPO = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverPO != null) $lastPopoverPO.popover('hide');

            $lastPopoverPO = $btn;

            $popoverPOContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPOContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPOContent ke .page-content
            $popoverPOContent.hide();
            $popoverPOContent.appendTo($('.page-content'));

            $lastPopoverPO = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handlePilihPO = function(){
        $tablePilihPO.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pembelian',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'ppembelian.no_pembelian no_po','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'supplier.nama nama_sup','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pembelian.tanggal_pesan tanggal_pesan','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pembelian.grand_total grand_total','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': false, 'orderable': true }
                ]
        });       
        $('#table_pilih_po_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_po_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $tablePilihPO);
        handlePilihPOSelect( $btnSelects );

        $tablePilihPO.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihPOSelect( $btnSelect );
            
        } );

        $popoverPOContent.hide();        
    };

    var handlePilihPOSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdPO   = $('input[name="id_po"]'),
                $noPO  = $('input[name="no_po"]')
                ;        

                $IdPO.val($(this).data('item').id);
                $noPO.val($(this).data('item').no_po);

            $('.pilih-po', $form).popover('hide');          


            e.preventDefault();
        });     
    };
    
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
        handleUploadifyPajak();
        handleDatePickers();

        $('select[name$="[biaya_id]"]', $newFieldset).select2();

        $('input[name$="[total_bon]"]', $newFieldset).on('change', function(){
            handleCountTotalBon();
        });


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
                        $('input[name$="[total_bon]"]', $fieldset).val(0);
                        $fieldset.hide(); 
                        handleCountTotalBon();       
                    }
                }
            });
        }
        else
        {
            if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
            $fieldset.remove();            
        }
        handleCountTotalBon();
    }


    
    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true,
                format : 'dd M yyyy'
            });

            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true,
                format : 'dd M yyyy'
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }


    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $('#save', $form).click();
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
                    

                    $('input#bon_url_faktur_pajak_'+index).attr('value',filename);
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

    var handleBtnAddBon = function($btn) {
        $btn.click(function() {
            addFieldsetParent();
        });
    };

    var handleCountTotalBon = function() {
        $hargaBon = $('input[name$="[total_bon]"]', $form);

        var total = 0;
        $.each($hargaBon, function(idx,hargaBon) {
            
            var harga = $(this).val();

            if(harga == ""){
                harga = 0;
            }else{
                harga = parseInt(harga);
            }

            total = total + harga;
        });

        $('input#nominal_bon', $form).val(total);
        $('input#nominal_bon_show', $form).val(mb.formatRp(total));

        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "get_terbilang",  
            data:  {nominal:total},  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                
              $('label#terbilang_bon').text(data.terbilang);
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
        });
    };

    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pembayaran_transaksi/';
        initform();
    };
 }(mb.app.proses));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses.init();
});