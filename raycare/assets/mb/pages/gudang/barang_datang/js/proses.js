mb.app.gudang = mb.app.gudang || {};
(function(o){

    var 
        baseAppUrl                 = '',
        $form                      = $('#form_barang_datang')
        $tableJumlahPesan          = $('#table_jumlah_pesan'),
        $tablePembelianDetail      = $('#table_pembelian_detail'),
        $popoverJumlahPesanContent = $('#popover_jumlah_pesan'), 
        tplPembelian               = $.validator.format( $('#tpl_pembelian_row').text()),
        pembelianCounter           = 1,
        $lastPopoverJumlahPesan    = null;

    var initform = function()
    {
        // addPembelianRow();      
    }

    var handleDatePicker = function()
    {
         if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
                autoclose: true
            })
            // $('body').removeClass("modal-open");
        }
    }

    var handleBtnJumlahPesan = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // alert($btn.data('id'));

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverJumlahPesan != null) $lastPopoverJumlahPesan.popover('hide');

            $lastPopoverJumlahPesan = $btn;

            $popoverJumlahPesanContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverJumlahPesanContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverJumlahPesanContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverJumlahPesanContent.hide();
            $popoverJumlahPesanContent.appendTo($('.page-content'));

            $lastPopoverJumlahPesan = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleJumlahMasuk = function(){
        $('input[name$="[jumlah_masuk]"]').on('change keyup', function(){
            // alert($(this).data('id'));
            var id = $(this).data('id');
            $('input#jumlah_masuk_' + id).val($(this).val());
            $('input#jumlah_masuk_' + id).attr('value',$(this).val());

            $('input#jumlah_masuk_awal_' + id).val($(this).val());
            $('input#jumlah_masuk_awal_' + id).attr('value',$(this).val());

            row_id = 'item_row_'+id;
            $row        = $('tr#'+row_id, $('#table_pembelian_detail'));

            $('a.pemisahan_item', $row).attr('href', baseAppUrl + 'modal_pemisahan_item/' + $('input#item_id_'+id).val() + '/' + $('input#item_satuan_id_'+id).val() + '/' + $(this).val() + '/item_row_' +id);

        });
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            is_save = 1;
            $('input#is_pmb').attr('value','1');
            $.each($('tr.table_item'), function(){
                is_identitas = $('input[name$="[is_identitas]"]', $(this)).val();
                total_identitas = $('input[name$="[total_identitas]"]', $(this)).val()
                jumlah_masuk = $('input[name$="[jumlah_masuk]"]', $(this)).val()
                if (is_identitas == '1') {
                    if (total_identitas != jumlah_masuk) {
                        $(this).addClass('alert-danger');
                        $('input[name$="[jumlah_masuk]"]', $(this)).focus();
                        is_save = 0;
                    }
                };
            });

            if (is_save == 1) {
                if (! $form.valid()) return;
                var i = 0;
                var msg = $(this).data('confirm');
                var proses = $(this).data('proses');
                bootbox.confirm(msg, function(result) {
                    Metronic.blockUI({boxed: true, message: proses});
                    if (result==true) {
                        i = parseInt(i) + 1;
                        $('a#confirm_save', $form).attr('disabled','disabled');
                        if(i === 1)
                        {
                          $('#save', $form).click();
                        }
                    }else{
                        Metronic.unblockUI();
                    }
                });
            };
            
        });
    };

    var handleConfirmSaveDraft = function(){
        $('a#confirm_save_draft', $form).click(function() {
            is_save = 1;
            $.each($('tr.table_item'), function(){
                is_identitas = $('input[name$="[is_identitas]"]', $(this)).val();
                total_identitas = $('input[name$="[total_identitas]"]', $(this)).val()
                jumlah_masuk = $('input[name$="[jumlah_masuk]"]', $(this)).val()
                if (is_identitas == '1') {
                    if (total_identitas != jumlah_masuk) {
                        $(this).addClass('alert-danger');
                        $('input[name$="[jumlah_masuk]"]', $(this)).focus();
                        is_save = 0;
                    }
                };
            });

            if (is_save == 1) {
                if (! $form.valid()) return;
                var i = 0;
                var msg = $(this).data('confirm');
                var proses = $(this).data('proses');
                bootbox.confirm(msg, function(result) {
                    Metronic.blockUI({boxed: true, message: proses});
                    if (result==true) {
                        i = parseInt(i) + 1;
                        $('a#confirm_save_draft', $form).attr('disabled','disabled');
                        if(i === 1)
                        {
                          $('#save', $form).click();
                        }
                    }else{
                        Metronic.unblockUI();
                    }
                });
            };
            
        });
    };


    var handleBtnDelete = function(){
        $('a.del-detail-item').on('click', function(e){
            
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    var rowId = $(this).data('row');
                    // alert('a');
                    var $row     = $('#item_row_'+rowId, $('table#table_pembelian_detail'));    
                    
                        if($('tbody>tr', $('table#table_pembelian_detail')).length >= 0){
                            $row.remove();
                        }

                    e.preventDefault();
                }
            });
                
            
        });
    };

    var handleUploadify = function()
    {
        var ul = $('#upload ul');

       
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
                
                $('input#url_faktur').attr('value',filename);
                // // Add the HTML to the UL element
                ul.html(tpl);
                // data.context = tpl.appendTo(ul);
                
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

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'gudang/barang_datang/';
        // handleDataTableInfoItem();
        // handleDataTableHistoryPecah();
        initform();
        handleBtnDelete();
        handleJumlahMasuk();
        handleDatePicker();
        handleConfirmSave();
        handleConfirmSaveDraft();
        handleUploadify();
    };
 }(mb.app.gudang));


// initialize  mb.app.home.table
$(function(){
    mb.app.gudang.init();
});