mb.app.pembayaran_masuk = mb.app.pembayaran_masuk || {};
mb.app.pembayaran_masuk.proses = mb.app.pembayaran_masuk.proses || {};
(function(o){

    var 
        baseAppUrl                 = '',
        $form                      = $('#form_proses_invoice')
        $tableInvoice              = $('#table_invoice'),
        $lastPopoverItemBayar      = null;

    var initform = function()
    {
        handleDatePicker();      
    }

    var handleDatePicker = function()
    {
         if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
            }).on('changeDate', function(){
                var tanggal = $('input#tanggal', $form).val(),
                    shift = $('input#tipe', $form).val();
                    
                oTable.api().ajax.url(baseAppUrl + 'listing_invoice' + '/' +tanggal+'/'+shift).load();
            });
        }
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            
            if (! $form.valid()) return;
            
            var i = 0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                Metronic.blockUI({boxed: true, message: "Sedang diproses..."});
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
                    tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                }
                else
                {
                    tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                }
                
                $('input#url_bukti_setor').attr('value',filename);
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

    var handleDataTableInvoice = function() {

        var tanggal = $('input#tanggal', $form).val(),
            shift = $('input#tipe', $form).val();

        oTable = $tableInvoice.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_invoice_view/'+tanggal+'/'+shift,
                'type' : 'POST',
            },          
            'filter'                : false,
            'info'                  : false,
            'paginate'                  : false,
            'pageLength'            : 25,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableInvoice.on('draw.dt', function (){
            $('.btn', this).tooltip();
            
            $totalBayar = $('input[name$="[total_bayar]"]' , this);
            
            total = 0;
            $.each($totalBayar, function(idx, totalBayar){
                var harga = parseInt($(this).val());

                    total = total + harga;
            });

            $('input#total_setor', $form).val(mb.formatTanpaRp(total));
            // $('input#total', $form).val(total);
            // $('input#total', $form).attr('value',total);

        });

    };

    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pembayaran_masuk/';
        initform();
        handleDataTableInvoice();
        handleConfirmSave();
        handleUploadify();
    };
 }(mb.app.pembayaran_masuk.proses));


// initialize  mb.app.home.table
$(function(){
    mb.app.pembayaran_masuk.proses.init();
});