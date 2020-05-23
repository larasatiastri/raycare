mb.app.setoran_kasir = mb.app.setoran_kasir || {};
mb.app.setoran_kasir.add = mb.app.setoran_kasir.add || {};
(function(o){

    var 
        baseAppUrl                 = '',
        $form                      = $('#form_add_setoran')
        $tableInvoice              = $('#table_invoice'),
        $tableInvoiceNon             = $('#table_invoice_non_jadwal'),
        $tableInvoiceEdc              = $('#table_invoice_edc'),
        $tableInvoiceEdcNon              = $('#table_invoice_edc_non'),
        $tableHutangPasien         = $('#table_invoice_hutang'),
        $lastPopoverItemBayar      = null;  

    var initform = function()
    {
        handleDatePicker();  
        handleValidation();    
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

            // // ajax form submit
            // submitHandler: function (form) {
            //     success1.show();
            //     error1.hide();
            //     // ajax form submit
            // }
        });
        
    }

    var handleDatePicker = function()
    {
         if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
            }).on('changeDate', function(){

                var tipe = $('input[name="jenis_bayar"]:checked', $form).val(),
                    tanggal = $('input#tanggal', $form).val(),
                    shift = $('select#tipe', $form).val(),
                    bank_id = $('select#bank_id', $form).val();

                if(tipe == 1){
                    oTable.api().ajax.url(baseAppUrl + 'listing_invoice' + '/' +tipe+'/'+tanggal+'/'+shift).load();  
                    oTableNon.api().ajax.url(baseAppUrl + 'listing_invoice_non' + '/' +tipe+'/'+tanggal+'/'+shift).load();  
                    oTableEdc.api().ajax.url(baseAppUrl + 'listing_invoice_edc/0/0/0/0').load();
                    oTableEdcNon.api().ajax.url(baseAppUrl + 'listing_invoice_edc_non/0/0/0/0').load();
                }else{
                    oTable.api().ajax.url(baseAppUrl + 'listing_invoice/0/0/0').load();  
                    oTableNon.api().ajax.url(baseAppUrl + 'listing_invoice_non/0/0/0').load();  
                    oTableEdc.api().ajax.url(baseAppUrl + 'listing_invoice_edc/' +bank_id + '/' +tipe+'/'+tanggal+'/'+shift).load();
                    oTableEdcNon.api().ajax.url(baseAppUrl + 'listing_invoice_edc_non/' +bank_id + '/' +tipe+'/'+tanggal+'/'+shift).load();
                }
            });
        }
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            
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
                
                $('input#url_bukti_setor').attr('value',filename);
                // // Add the HTML to the UL element
                ul.html(tpl);
                // data.context = tpl.appendTo(ul);
                
                handleFancybox();
                Metronic.unblockUI('#upload');

                    // data.context = tpl.appendTo(ul);

            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                Metronic.blockUI({boxed: true, target: '#upload'});
            },


            fail:function(e, data){
                // Something has gone wrong!
                bootbox.alert('File Tidak Dapat Diupload');
                Metronic.unblockUI('#upload');
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

        var tipe = $('input[name="jenis_bayar"]:checked', $form).val(),
            tanggal = $('input#tanggal', $form).val(),
            shift = $('select#tipe', $form).val();

        oTable = $tableInvoice.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_invoice/'+tipe+'/'+tanggal+'/'+shift,
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
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableInvoice.on('draw.dt', function (){
            $('.btn', this).tooltip();
            
            handleCountTotal(); 
        });

    };

    var handleDataTableInvoiceNon = function() {

        var tipe = $('input[name="jenis_bayar"]:checked', $form).val(),
            tanggal = $('input#tanggal', $form).val(),
            shift = $('select#tipe', $form).val();

        oTableNon = $tableInvoiceNon.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_invoice_non/'+tipe+'/'+tanggal+'/'+shift,
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
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableInvoiceNon.on('draw.dt', function (){
            $('.btn', this).tooltip();
            
            handleCountTotal();    
        });

    };

    var handleCountTotal = function() {
        $totalBayar = $('input[name$="[total_bayar]"]' , $tableInvoice);
            
        total = 0;
        $.each($totalBayar, function(idx, totalBayar){
            var harga = parseInt($(this).val());

                total = total + harga;
        });

        $totalBayarNon = $('input[name$="[total_bayar_non]"]' , $tableInvoiceNon);

        $.each($totalBayarNon, function(idx, totalBayarNon){
            var harga = parseInt($(this).val());

                total = total + harga;
        });

        $('input#total_setor', $form).val(mb.formatTanpaRp(total));
        $('input#total', $form).val(total);
        $('input#total', $form).attr('value',total);

    };

    var handleDataTableInvoiceEdc = function() {

        var tipe = $('input[name="jenis_bayar"]:checked', $form).val(),
            tanggal = $('input#tanggal', $form).val(),
            bank_id = $('select#bank_id', $form).val(),
            shift = $('select#tipe', $form).val();

        oTableEdc = $tableInvoiceEdc.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_invoice_edc/'+bank_id+'/'+tipe+'/'+tanggal+'/'+shift,
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
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableInvoiceEdc.on('draw.dt', function (){
            $('.btn', this).tooltip();
            
            handleCountTotalEdc();

            
        });

    };

    var handleDataTableInvoiceEdcNon = function() {

        var tipe = $('input[name="jenis_bayar"]:checked', $form).val(),
            tanggal = $('input#tanggal', $form).val(),
            bank_id = $('select#bank_id', $form).val(),
            shift = $('select#tipe', $form).val();

        oTableEdcNon = $tableInvoiceEdcNon.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_invoice_edc_non/'+bank_id+'/'+tipe+'/'+tanggal+'/'+shift,
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
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableInvoiceEdcNon.on('draw.dt', function (){
            $('.btn', this).tooltip();
            
            handleCountTotalEdc();

            
        });

    };

    var handleCountTotalEdc = function() {
        $totalBayar = $('input[name$="[total_bayar]"]' , $tableInvoiceEdc);
            
        total = 0;
        $.each($totalBayar, function(idx, totalBayar){
            var harga = parseInt($(this).val());

                total = total + harga;
        });

        $totalBayarNon = $('input[name$="[total_bayar_non]"]' , $tableInvoiceEdcNon);

        $.each($totalBayarNon, function(idx, totalBayarNon){
            var harga = parseInt($(this).val());

                total = total + harga;
        });

        $('input#total_setor', $form).val(mb.formatTanpaRp(total));
        $('input#total', $form).val(total);
        $('input#total', $form).attr('value',total);

    };

    var handleSelectShift = function() {
        $('input[name="jenis_bayar"]', $form).on('change', function(){
            var value = $(this).val(), 
                tanggal = $('input#tanggal', $form).val(),
                shift = $('select#tipe', $form).val(),
                bank_id = $('select#bank_id', $form).val();

            if(value == 1){
                $('div#setor_tunai').removeClass('hidden');
                $('div#setor_edc').addClass('hidden');
                $('div#detail_tunai').removeClass('hidden');
                $('div#detail_tunai_non').removeClass('hidden');
                $('div#detail_edc').addClass('hidden');
                $('div#detail_edc_non').addClass('hidden');
                $('select#mesin_edc_id').removeAttr('required');
                $('input#nomor_bukti_setor').attr('required','required');
                $('input#upl').attr('required','required');
                oTable.api().ajax.url(baseAppUrl + 'listing_invoice' + '/' +value+'/'+tanggal+'/'+shift).load();
                oTableNon.api().ajax.url(baseAppUrl + 'listing_invoice_non' + '/' +value+'/'+tanggal+'/'+shift).load();
                oTableEdc.api().ajax.url(baseAppUrl + 'listing_invoice_edc/0/0/0/0').load();
                oTableEdcNon.api().ajax.url(baseAppUrl + 'listing_invoice_edc_non/0/0/0/0').load();

            }if(value == 2){
                $('div#setor_tunai').addClass('hidden');
                $('div#setor_edc').removeClass('hidden');
                $('div#detail_tunai').addClass('hidden');
                $('div#detail_tunai_non').addClass('hidden');
                $('div#detail_edc').removeClass('hidden');
                $('div#detail_edc_non').removeClass('hidden');
                $('select#mesin_edc_id').attr('required','required');
                $('input#nomor_bukti_setor').removeAttr('required');
                $('input#upl').removeAttr('required');
                oTable.api().ajax.url(baseAppUrl + 'listing_invoice/0/0/0').load();  
                oTableNon.api().ajax.url(baseAppUrl + 'listing_invoice_non/0/0/0').load();  
                oTableEdc.api().ajax.url(baseAppUrl + 'listing_invoice_edc/' +bank_id + '/' +value+'/'+tanggal+'/'+shift).load();
                oTableEdcNon.api().ajax.url(baseAppUrl + 'listing_invoice_edc_non/' +bank_id + '/' +value+'/'+tanggal+'/'+shift).load();
                

            }

        });

        $('select#tipe', $form).on('change', function(){
           
            var tipe = $('input[name="jenis_bayar"]:checked', $form).val(),
                tanggal = $('input#tanggal', $form).val(),
                bank_id = $('select#bank_id', $form).val(),
                shift = $(this).val(); 

            if(tipe == 1){
                oTable.api().ajax.url(baseAppUrl + 'listing_invoice' + '/' +tipe+'/'+tanggal+'/'+shift).load();   
                oTableNon.api().ajax.url(baseAppUrl + 'listing_invoice_non' + '/' +tipe+'/'+tanggal+'/'+shift).load();   
                oTableEdc.api().ajax.url(baseAppUrl + 'listing_invoice_edc/0/0/0/0').load();
                oTableEdcNon.api().ajax.url(baseAppUrl + 'listing_invoice_edc_non/0/0/0/0').load();
            }if(tipe == 2){
                oTable.api().ajax.url(baseAppUrl + 'listing_invoice/0/0/0').load();  
                oTableNon.api().ajax.url(baseAppUrl + 'listing_invoice_non/0/0/0').load();  
                oTableEdc.api().ajax.url(baseAppUrl + 'listing_invoice_edc/' +bank_id + '/' +tipe+'/'+tanggal+'/'+shift).load();
                oTableEdcNon.api().ajax.url(baseAppUrl + 'listing_invoice_edc_non/' +bank_id + '/' +tipe+'/'+tanggal+'/'+shift).load();
                
            }
        });

        $('select#bank_id', $form).on('change', function(){
            var tipe = $('input[name="jenis_bayar"]:checked', $form).val(),
                tanggal = $('input#tanggal', $form).val(),
                shift = $('select#tipe', $form).val(); 
                bank_id = $(this).val();

            if(tipe == 1){
                // oTable.api().ajax.url(baseAppUrl + 'listing_invoice' + '/' +tipe+'/'+tanggal+'/'+shift).load();   
                // oTableNon.api().ajax.url(baseAppUrl + 'listing_invoice_non' + '/' +tipe+'/'+tanggal+'/'+shift).load();   
                // oTableEdc.api().ajax.url(baseAppUrl + 'listing_invoice_edc/0/0/0/0').load();
                // oTableEdcNon.api().ajax.url(baseAppUrl + 'listing_invoice_edc_non/0/0/0/0').load();
            }if(tipe == 2){
                oTable.api().ajax.url(baseAppUrl + 'listing_invoice/0/0/0').load();  
                oTableNon.api().ajax.url(baseAppUrl + 'listing_invoice_non/0/0/0').load();  
                oTableEdc.api().ajax.url(baseAppUrl + 'listing_invoice_edc/' +bank_id + '/' +tipe+'/'+tanggal+'/'+shift).load();
                oTableEdcNon.api().ajax.url(baseAppUrl + 'listing_invoice_edc_non/' +bank_id + '/' +tipe+'/'+tanggal+'/'+shift).load();

                $.ajax
                ({ 
 
                    type: 'POST',
                    url: baseAppUrl +  "get_mesin_edc",  
                    data:  {bank_id: bank_id},  
                    dataType : 'json',
                    success:function(data)          //on recieve of reply
                    { 
                       if(data.success == true){
                        var row = data.data_mesin,
                            label = '';

                         $.each(row, function(idx, row) {
                             label = label + '<label>'+row['nama']+'</label></br>';
                         });

                         $('div#data_mesin').html(label);
                       }
                    
                    }
           
               });
                
            }
        });
    }

    var handleDataTableHutang = function() 
    {

        oTableHutang = $tableHutangPasien.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_belum_bayar'+ '/0/0/0',
                'type' : 'POST',
            },  

            'paginate'  : false,
            'info'  : false,
            'filter'  : false,
            'pageLength'            : 25,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableHutangPasien.on('draw.dt', function (){
            $('.btn', this).tooltip();
            
        } );
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'kasir/setoran_kasir/';
        initform();
        handleDataTableInvoice();
        handleDataTableInvoiceNon();
        handleDataTableInvoiceEdc();
        handleDataTableInvoiceEdcNon();
        handleDataTableHutang();
        handleConfirmSave();
        handleUploadify();
        handleSelectShift();
    };
 }(mb.app.setoran_kasir.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.setoran_kasir.add.init();
});