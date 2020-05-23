mb.app.pengajuan_pembayaran_kasbon = mb.app.pengajuan_pembayaran_kasbon || {};
mb.app.pengajuan_pembayaran_kasbon.add = mb.app.pengajuan_pembayaran_kasbon.add || {};


(function(o){
    
    var 
        baseAppUrl          = '',
        $form               = $('#form_add_pengajuan_pembayaran_kasbon'),
        $tableKasbon        = $('#tabel_kasbon'),
        tplFormParent       = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
        regExpTplUpload     = new RegExp('bon[0]', 'g'),   // 'g' perform global, case-insensitive
        uploadCounter       = 0,
        $popoverItemContent = $('#popover_item_content'), 
        $lastPopoverItem    = null,        
        $tablePilihPo       = $('#table_pilih_po'),        
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

    var initForm = function(){

        var $btnSearchPo  = $('a.pilih-po', $tableKasbon);
        
        $.each($btnSearchPo, function(){
            var rowId  = $(this).closest('tr').prop('id');
  
            $(this).popover({ 
                html : true,
                container : '.page-content',
                placement : 'bottom',
                content: '<input type="hidden" name="rowItemId"/>'

            }).on("show.bs.popover", function(){

                var $popContainer = $(this).data('bs.popover').tip();

                $popContainer.css({minWidth: '1150px', maxWidth: '1150px'});

                if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

                $lastPopoverItem = $(this);

                $popoverItemContent.show();

            }).on('shown.bs.popover', function(){

                var 
                    $popContainer = $(this).data('bs.popover').tip(),
                    $popcontent   = $popContainer.find('.popover-content')
                    ;

                // record rowId di popcontent
                $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
                
                // pindahkan $popoverItemContent ke .popover-conter
                $popContainer.find('.popover-content').append($popoverItemContent);

            }).on('hide.bs.popover', function(){
                //pindahkan kembali $popoverPasienContent ke .page-content
                $popoverItemContent.hide();
                $popoverItemContent.appendTo($('.page-content'));

                $lastPopoverItem = null;

            }).on('hidden.bs.popover', function(){
                // console.log('hidden.bs.popover')
            }).on('click', function(e){
                e.preventDefault();
            });
        });

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
        handleUploadifySisa();
        handleDataTablePembelian();

        var $inputTotal = $('input[name$="[total]"]', $tableKasbon);
        $inputTotal.on('change', function(){
            handleSisaKasbon();
        });


    };

    function addFieldsetParent(form,data)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul.list-unstyled', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
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

    function handleUploadifySisa()
    {

        var ul = $('#upload ul.ul-img-setor');
   
        // Initialize the jQuery File Upload plugin
        $('#upl').fileupload({

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
                

                $('input#url_bukti_setor').attr('value',filename);
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
    }

    var handleFancybox = function() {
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

    var handleDataTablePembelian = function() 
    {
        oTablePo = $tablePilihPo.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pembelian' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        
        $('#table_pilih_supplier_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_supplier_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePilihPo.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihPoSelect( $btnSelect );  
            
        });

        $popoverItemContent.hide();
    }

    var handlePilihPoSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop = $(this).parents('.popover').eq(0),
                rowId      = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableKasbon),
                $IdPo      = $('input[name$="[id_po]"]',$row),
                $NoPo      = $('input[name$="[no_po]"]',$row);


            $IdPo.val($(this).data('item').id);
            $NoPo.val($(this).data('item').no_po);
                    
            $('.pilih-po').popover('hide');          

            e.preventDefault();
        });     
    };

    var handleBtnSearchPo = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
  
        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1150px', maxWidth: '1150px'});

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverItemContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleSisaKasbon = function(){
        var nominal = parseInt($('input#nominal').val()),
            grand_total = 0,
            $total_real = $('input[name$="[total]"]', $tableKasbon);

        $.each($total_real, function(idx, total){
            grand_total = grand_total + (parseInt($(this).val()));
        });

        $('label#label_total_biaya').text(mb.formatRp(grand_total));
        grand_total_sisa = nominal - grand_total;
        $('input#nominal_sisa').val(grand_total_sisa);
        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "get_terbilang",  
            data:  {nominal:grand_total_sisa},  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                
              $('label#terbilang_sisa').text(data.terbilang);
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
        });
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pengajuan_pembayaran_kasbon/';
        initForm();
        handleSisaKasbon();

    };

}(mb.app.pengajuan_pembayaran_kasbon.add));

$(function(){    
    mb.app.pengajuan_pembayaran_kasbon.add.init();
});