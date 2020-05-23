mb.app.proses_po = mb.app.proses_po || {};
(function(o){

    var 
        baseAppUrl           = '',
        $form                = $('#form_proses_po'),
        $popoverBankContent  = $('#popover_bank_supplier_content'), 
        $lastPopoverSuppBank = null,
        $tableBankSupplier   = $('table#table_pilih_bank'),
        id_po                = $('input[name="pembelian_id"]', $form).val(),
        $tableInvoice        = $('#table_invoice'),
        tplFormPayment       = '<li class="fieldset">' + $('#tpl-form-payment', $form).val() + '<hr></li>',
        regExpTpl            = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        paymentCounter       = 0;


    var forms = {
        'payment' : {            
            section  : $('#section-payment', $form),
            template : $.validator.format( tplFormPayment.replace(regExpTpl, '_ID_{0}') ), //ubah ke format template jquery validator
            counter  : function(){ paymentCounter++; return paymentCounter-1; }
        }      
    };
        

    var initform = function()
    {    
        handleConfirmSave();
        handleDatePickers();
        $('select[name$="[biaya_id]"]', $form).select2();

        $('#form_proses_po').on('keyup keypress', function(e) {

            var keyCode = e.keyCode || e.which;
            if(keyCode == 13){
                e.preventDefault();
                return false;
            }
            
        });

              

        $.each(forms, function(idx, form){

            // beri satu fieldset kosong
            addFieldset(form);

        });

        handleDataTableBankSupplier();
        handleChangeTipeBayar();
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
        }
    }

    var addFieldset = function(form){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer)
            ;

        handleDatePickers();
        $('select.payment_type').val(1);
        
        handleSelectSection(1, $newFieldset);

        $('select[name="payment_type"]', $newFieldset).on('change', function(){
            handleSelectSection(this.value, $newFieldset);

            var $btnBankSupplier = $('a.search-bank', $newFieldset);


            $.each($btnBankSupplier, function(idx, btnBankSupplier){
                var rowId  = $(this).closest('tr').prop('id');

                $(this).popover({ 
                    html : true,
                    container : '.page-content',
                    placement : 'bottom',
                    content: '<input type="hidden" name="rowItemId"/>'

                }).on("show.bs.popover", function(){

                    var $popContainer = $(this).data('bs.popover').tip();

                    $popContainer.css({minWidth: '1150px', maxWidth: '1150px'});

                    if ($lastPopoverSuppBank != null) $lastPopoverSuppBank.popover('hide');

                    $lastPopoverSuppBank = $(this);

                    $popoverBankContent.show();

                }).on('shown.bs.popover', function(){

                    var 
                        $popContainer = $(this).data('bs.popover').tip(),
                        $popcontent   = $popContainer.find('.popover-content')
                        ;

                    // record rowId di popcontent
                    $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
                    
                    // pindahkan $popoverBankContent ke .popover-conter
                    $popContainer.find('.popover-content').append($popoverBankContent);

                }).on('hide.bs.popover', function(){
                    //pindahkan kembali $popoverPasienContent ke .page-content
                    $popoverBankContent.hide();
                    $popoverBankContent.appendTo($('.page-content'));

                    $lastPopoverSuppBank = null;

                }).on('hidden.bs.popover', function(){
                    // console.log('hidden.bs.popover')
                }).on('click', function(e){
                    e.preventDefault();

                });
            });  
        });
        


    
        $('input#nominal', $newFieldset). on('change keyup', function(e) {

            var keyCode = e.keyCode || e.which;
            if(keyCode == 13){
                e.preventDefault();
                return false;
            }
            var nominal = $(this).val();

            handleTerbilang(nominal);
        });

        

        

        
        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'silver');
        $('input#last_count').val(counter);

        // calculateTotal();
        // checkedTotal();

    };

    var handleTerbilang = function(nominal){
        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "get_terbilang",  
            data:  {nominal:nominal},  
            dataType : 'json',
            beforeSend : function(){
            },
            success:function(data)          //on recieve of reply
            { 
                
              $('label#th_terbilang').text(data.terbilang);
            
            },
            complete : function(){
            }
        });
    }

    var handleSelectSection = function(value,$fieldset)
    {
        if(value == 1)
        {
            $('div#section_1', $fieldset).removeClass('hidden');
            $('div#section_2', $fieldset).addClass('hidden');
            $('div#section_3', $fieldset).addClass('hidden');
        }
        if(value == 2)
        {
            $('div#section_1', $fieldset).addClass('hidden');
            $('div#section_2', $fieldset).removeClass('hidden');
            $('div#section_3', $fieldset).addClass('hidden');
        }
        if(value == 3)
        {
            $('div#section_3', $fieldset).removeClass('hidden');
            $('div#section_2', $fieldset).addClass('hidden');
            $('div#section_1', $fieldset).addClass('hidden');
        }


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

    var handleBtnSuppBank = function($btn){
        var rowId  = $btn.closest('tr').prop('id');

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1150px', maxWidth: '1150px'});

            if ($lastPopoverSuppBank != null) $lastPopoverSuppBank.popover('hide');

            $lastPopoverSuppBank = $btn;

            $popoverBankContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverBankContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverBankContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverBankContent.hide();
            $popoverBankContent.appendTo($('.page-content'));

            $lastPopoverSuppBank = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();

        });
    };

    var handleDataTableBankSupplier = function()
    {
        oTableBankSupplier = $tableBankSupplier.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_supplier_bank/'+$('input#supplier_id').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'name' : 'supplier_bank.nob nob', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier_bank.acc_name acc_name','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier_bank.acc_number acc_number', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier_bank.acc_number acc_name', 'visible' : true, 'searchable': false, 'orderable': false },               
                ]
        });


        $tableBankSupplier.on('draw.dt', function (){

            var $btnSelect = $('a.select-supplier-bank', this);
            handleSuppBankSelect( $btnSelect );
            
        });

        $popoverBankContent.hide();        
    };

    var handleSuppBankSelect = function($btn){
        $btn.on('click', function(e){
            var 

                $bankSuppId    = $('input[name="bank_supp_id"]', $form);
                $bankSuppName  = $('input[name="bank_supp_name"]', $form);
                $bankSuppNomor = $('input[name="bank_supp_nomor"]', $form);


                $bankSuppId.val($(this).data('item').id);
                $bankSuppName.val($(this).data('item').nob);
                $bankSuppNomor.val($(this).data('item').acc_number);
                $('.search-bank', $form).popover('hide');
        });  
    }

    var handleChangeTipeBayar = function(){

        var $tipe = $('input[name$="[tipe]"]', $('#table_invoice'));

        $.each($tipe, function(idx, tipe){
            index = '';
            tipe = '';
            if ($(this).prop("checked") === true){
                index = $(this).data('index');
                tipe = $(this).data('text');
                $('th#th_jenis_bayar_'+index).text(tipe);
            }


        });

        $('input[name$="[tipe]"]', $('#table_invoice')).on('change', function(){

            var id = $(this).val();
            var $tableInvoice = $('#table_invoice'),
                rowId         = $(this).closest('tr').prop('id'),
                index         = $(this).data('index'),
                tipe          = $(this).data('text'),
                $row          = $('#'+rowId, $tableInvoice); 
             

            if(id == 1){
                $('div#pilih_trf',$row).addClass('hidden');
                $('div#pilih_giro',$row).addClass('hidden');
                $('div#pilih_cek',$row).removeClass('hidden');
                $('th#th_jenis_bayar_'+index).text(tipe);
            }
            if(id == 2){
                $('div#pilih_trf',$row).addClass('hidden');
                $('div#pilih_giro',$row).removeClass('hidden');
                $('div#pilih_cek',$row).addClass('hidden');
                $('th#th_jenis_bayar_'+index).text(tipe);
            }
            if(id == 3){
                $('div#pilih_trf',$row).removeClass('hidden');
                $('div#pilih_giro',$row).addClass('hidden');
                $('div#pilih_cek',$row).addClass('hidden');
                $('th#th_jenis_bayar_'+index).text(tipe);
            }
        });



        $nominal = $('input[name="nominal_biaya"]', $form);
        $nominal_cek = $('input[name="nominal"]', $form);
        $total_invoice = $('input[name$="[total_invoice]"]', $('#table_invoice'));

        $nominal.on('change', function(){
            var $tableInvoice = $('#table_invoice'),
                rowId         = $(this).closest('tr').prop('id'),
                $row          = $('#'+rowId, $tableInvoice); 


            total_biaya = 0;
            $.each($nominal, function(idx, nominal){
                biaya = $(this).val();
                if(biaya == ''){
                    biaya = 0;
                }
                total_biaya = total_biaya + parseInt(biaya);
            });

            grand_total = 0;
            $.each($total_invoice, function(idx, total_invoice){
                invoice = $(this).val();

                if(invoice == ''){
                    invoice = 0;
                }
                grand_total = grand_total + parseInt(invoice);
            });

            nominal_cek = 0;
            $.each($nominal_cek, function(idx, nominal_cek){
                cek = $(this).val();

                if(cek == ''){
                    cek = 0;
                }
                nominal_cek = nominal_cek + parseInt(cek);
            });


            $('th#biaya_tambahan', $('#table_invoice')).text(mb.formatRp(total_biaya));
            $('th#th_total_bayar', $('#table_invoice')).text(mb.formatRp(total_biaya + grand_total));
            $('input#total_bayar', $('#table_invoice')).val(total_biaya + grand_total);
            $('input#total_bayar', $('#table_invoice')).attr('value', total_biaya + grand_total);

            var nominal = total_biaya + nominal_cek;
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
                    
                  $('th#th_terbilang').text('Terbilang: '+data.terbilang);
                  $('label#th_terbilang').text(data.terbilang);
                
                },
                complete : function(){
                  Metronic.unblockUI();
                }
            });

        });

        $.each($total_invoice, function(idx, total_invoice){
            invoice = parseInt($(this).val());

            if(invoice == ''){
                invoice = 0;
            }
            
            $('th#th_total_cek_bayar_'+idx).text(mb.formatRp(invoice));
        });

        $('input[name$="[bank_no_cek]"]',  $('#table_invoice')).on('change', function(){
         var $tableInvoice = $('#table_invoice'),
             rowId         = $(this).closest('tr').prop('id'),
             $row          = $('#'+rowId, $tableInvoice),
             index         = $(this).data('index');

             no_cek = $('input[name$="[bank_no_cek]"]',  $row).val();

             $('th#th_no_cek_bayar_'+index).text(no_cek);
        });

        $('input[name$="[bank_no_giro]"]',  $('#table_invoice')).on('change', function(){
         var $tableInvoice = $('#table_invoice'),
             rowId         = $(this).closest('tr').prop('id'),
             $row          = $('#'+rowId, $tableInvoice),
             index         = $(this).data('index');

             no_giro = $('input[name$="[bank_no_giro]"]',  $row).val();

             $('th#th_no_cek_bayar_'+index).text(no_giro);
        });

        $('input[name$="[bank_supp_nomor]"]',  $('#table_invoice')).on('change', function(){
         var $tableInvoice = $('#table_invoice'),
             rowId         = $(this).closest('tr').prop('id'),
             $row          = $('#'+rowId, $tableInvoice),
             index         = $(this).data('index');

             no_transfer = $('input[name$="[bank_supp_nomor]"]',  $row).val();

             $('th#th_no_cek_bayar_'+index).text(no_transfer);
        });
    }

    var handleBankSelect = function(){
        $selectBank = $('select[name$="[bank_id]"]', $('#table_invoice'));

        $.each($selectBank, function(idx, selectBank){
            var index         = $(this).data('index');
            var bank          = $(this).find(":selected").text();

            $('th#th_bank_bayar_'+index).text(bank);

        });

        $selectBank.on('change', function(){
            var $tableInvoice = $('#table_invoice'),
                rowId         = $(this).closest('tr').prop('id'),
                $row          = $('#'+rowId, $tableInvoice),
                index         = $(this).data('index');

            var bank = $('select[name$="[bank_id]"] option:selected', $row).text();
                $('th#th_bank_bayar_'+index).text(bank);
        });


    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/proses_pembayaran_transaksi/';
        initform();
        handleBankSelect();
    };
 }(mb.app.proses_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses_po.init();
});