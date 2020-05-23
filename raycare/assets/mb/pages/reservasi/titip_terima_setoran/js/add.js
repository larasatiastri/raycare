mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl              = '',
        $form                   = $('#form_add_titip_setoran'),
        $tableAddAccount        = $('#table_add_account', $form),
        $tableAccountSearch     = $('#table_account_search'),
        $tableInformation       = $('#table_information'),
        $popoverPasienContent   = $('#popover_pasien_content'), 
        $tablePilihUser         = $('#table_pilih_user'),
        $tablePilihGudangOrang  = $('#table_pilih_gudang_orang'),
        $tableKasirBiaya        = $('#table_add_detail_setoran_biaya'),
        $tableInvoice           = $('#table_add_detail_setoran_invoice'),
        $lastPopoverItem        = null,
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        tplItemAccRow           = $.validator.format( $('#tpl_item_acc_row').text() ),
        itemCounter             = 0
        ;

       var initForm = function(){
      

        var 
            $btnSearchAccount = $('.search-account', $tableAddAccount),
            $btnDeletes = $('.del-this', $tableAddAccount);


        var $btnSearchUser  = $('.pilih-user', $form);
        handleBtnSearchUser($btnSearchUser);
        
        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        // tambah 1 row kosong pertama
        addItemAccRow();
        addItemRow();
        $('.row_plus', $tableAddAccount).hide();

    };
    

    var addItemRow = function(){

        var numRow = $('tbody tr', $tableAddAccount).length;
        
        var 
            $rowContainer         = $('tbody', $tableAddAccount),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchAccount     = $('.search-account', $newItemRow)
            ;

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
        handleDatePickers();
       
    };

    var handleTambahRowPelengkap = function(){
        $('a#add_biaya', $form).click(function() {
            addItemRow();
        });
    };

    var addItemAccRow = function(){

        var numRow = $('tbody tr', $tableAddAccount).length;
        var 
            $rowContainer         = $('tbody', $tableAddAccount),
            $newItemRow           = $(tplItemAccRow(itemCounter++)).appendTo( $rowContainer ),
            $btnAddAccount  = $('.add_row', $newItemRow)
            ;

        // handle delete btn
        $('.del-this-plus', $newItemRow).on('click', function(e){
            $('input[name$="[var]"]', $newItemRow).val('');
            e.preventDefault();
        });
        // handle button search item
        // handleAddAcc($btnAddAccount);

    };
     
     var handleBtnSearchSales = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'right',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

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
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableItems = function(){
        $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_account',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableItemSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handleAccountSelect( $btnSelect );
            
        } );

        $popoverItemContent.hide();        
    };

    var handleBtnSearchUser = function($btn){
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

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverPasienContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPasienContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverPasienContent.hide();
            $popoverPasienContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

     var handlePilihGudangOrang = function(){
        $tablePilihGudangOrang.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_gudang_orang_terima_uang',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#ttable_pilih_user_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#ttable_pilih_user_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select-gudang-orang', $tablePilihGudangOrang);
        handlePilihGudangOrangSelect( $btnSelects );

        $tablePilihGudangOrang.on('draw.dt', function (){
            var $btnSelect = $('a.select-gudang-orang', this);
            handlePilihGudangOrangSelect( $btnSelect );
            
        } );

        $popoverPasienContent.hide();        
    };

    var handlePilihUser = function(){
        $tablePilihUser.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_user_terima_uang',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#ttable_pilih_user_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#ttable_pilih_user_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $tablePilihUser);
        handlePilihUserSelect( $btnSelects );

        $tablePilihUser.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihUserSelect( $btnSelect );
            
        } );

        $popoverPasienContent.hide();        
    };

     var handlePilihGudangOrangSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefGudangOrang   = $('input[name="nama_ref_user"]'),
                $IdRefUser   = $('input[name="id_ref_pasien"]'),
                $kasir_titip_uang_id   = $('input[name="kasir_titip_uang_id"]'),
                $tipe_user   = $('input[name="tipe_user"]'),
                $itemCodeEl = null,
                $itemNameEl = null
                ;        


            $('.pilih-user', $form).popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $kasir_titip_uang_id.val($(this).data('item').kasir_titip_uang_id);
            $IdRefUser.val($(this).data('item').user_id);
            $namaRefGudangOrang.val($(this).data('item').nama_gudang_orang);
            $tipe_user.val($(this).data('item').tipe_user);

            // alert($itemIdEl.val($(this).data('item').id));


            e.preventDefault();
        });     
    };

    var handlePilihUserSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefUser   = $('input[name="nama_ref_user"]'),
                $IdRefUser   = $('input[name="id_ref_pasien"]'),
                $kasir_titip_uang_id   = $('input[name="kasir_titip_uang_id"]'),
                $tipe_user   = $('input[name="tipe_user"]'),
                $itemCodeEl = null,
                $itemNameEl = null
                ;        


            $('.pilih-user', $form).popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdRefUser.val($(this).data('item').id_user);
            $kasir_titip_uang_id.val($(this).data('item').kasir_titip_uang_id);
            $namaRefUser.val($(this).data('item').nama_user);
            $tipe_user.val($(this).data('item').tipe_user);

            // alert($itemIdEl.val($(this).data('item').id));


            e.preventDefault();
        });     
    };

    var handleBtnDelete = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableAddAccount);

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableAddAccount).length == 0){
                addItemRow();
            }
            e.preventDefault();
        });
    };

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

 

    var handleDatePickers = function () {
        // var time = new Date($('#waktu_selesai').val());
        if (jQuery().datetimepicker) {
            $('.date', $form).datetimepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
                // update : time

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

    jQuery('#table_add_detail_setoran_biaya .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }                    
        });
        jQuery.uniform.update(set);
    });

    jQuery('#table_add_detail_setoran_invoice .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }                    
        });
        jQuery.uniform.update(set);
    });

    var handleKasirBiaya = function(){

        $tableKasirBiaya.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_add_detail_setoran_biaya',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#table_add_detail_setoran_biaya_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_add_detail_setoran_biaya_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown


        $tableKasirBiaya.on('draw.dt', function (){
            
            var total_setoran_biaya =  $('input[name="total_setoran_biaya"]', this).val();
            var tanggal_biaya =  $('input[id="tanggal"]', this).val();
            // alert(tanggal_biaya);
            var total_biaya = 0;
                var $biaya = $('input[name$="[rupiah]"]', $tableKasirBiaya);
                // alert($biaya);
                $.each($biaya, function(){
                    total_biaya = total_biaya + parseInt($(this).val());
                });
                $('div.total_biaya').text(mb.formatRp(parseInt(total_biaya)));

            var count = 0;
            var $tanggal_biaya =  $('input[name$="[tanggal]"]', $tableKasirBiaya);
            var last_tanggal = '';
            $.each($tanggal_biaya, function(idx, tgl){
                var tanggal = $(this).val();

                if (tanggal != last_tanggal) 
                {
                    count = count + 1;
                }

                last_tanggal = tanggal;
                
            });
            $('div.total_hari').text(parseInt(count) + ' Hari');
            // alert(count);
            
        });
    };

    var handleInvoice = function(){

        $tableInvoice.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_add_detail_setoran_invoice',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#table_add_detail_setoran_invoice_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_add_detail_setoran_invoice_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown


        $tableInvoice.on('draw.dt', function (){
            
            var total_setoran_invoice =  $('input[name="total_setoran_invoice"]', this).val();

            var total_invoice = 0;
            var $invoice = $('input[name$="[bayar]"]', $tableInvoice);
            // alert($invoice);
                $.each($invoice, function(){
                    total_invoice = total_invoice + parseInt($(this).val());
                });
                $('div.total_invoice').text(mb.formatRp(parseInt(total_invoice)));

            var count = 0;
            var $tanggal_invoice =  $('input[name$="[tanggal]"]', $tableInvoice);
            var last_tanggal = '';
            $.each($tanggal_invoice, function(idx, tgl){
                var tanggal = $(this).val();

                if (tanggal != last_tanggal) 
                {
                    count = count + 1;
                }

                last_tanggal = tanggal;
                
            });
            $('div.total_hari_invoice').text(parseInt(count) + ' Hari');
            // alert(count);
        });
    };

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'reservasi/titip_terima_setoran/';
        handleValidation();
        initForm();
        handleDatePickers();
        handleConfirmSave();
        handlePilihUser();
        handleKasirBiaya();
        handleInvoice();
        handlePilihGudangOrang();
        handleTambahRowPelengkap();
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});