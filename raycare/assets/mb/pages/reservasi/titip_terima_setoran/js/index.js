mb.app.titip_terima_setoran = mb.app.titip_terima_setoran || {};
mb.app.titip_terima_setoran.view = mb.app.titip_terima_setoran.view || {};

// mb.app.titip_terima_setoran.view namespace
(function(o){

    var 
        $form                   = $('#form_titip_terima_setoran'),
        $tableTitipsetoran      = $('#table_titip_setoran'),
        $tableTerimasetoran     = $('#table_terima_setoran'),
        $tableKasirBiaya        = $('#table_kasir_biaya'),
        $tableInvoice           = $('#table_pembayaran_pasien'),
        theadFilterTemplate     = $('#thead-filter-template').text(),
        $popoverPasienContent   = $('#popover_pasien_content'),
        tplItemRow              = $.validator.format($('#tpl_item_row').text()),
        $lastPopoverItem        = null
        ;


    var initForm = function () {


        var $btnSearchUser  = $('.pilih-user', $form);
        handleBtnSearchUser($btnSearchUser);

    }

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

    

    var handleDataTable = function(){
            oTable = $tableTitipsetoran.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_titip_setoran/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        
        $('#table_titip_setoran_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_titip_setoran_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
        $(theadFilterTemplate).appendTo($('thead', $tableTitipsetoran));

        $tableTitipsetoran.on('draw.dt', function (){
           var $btnSearchUser  = $('.pilih-user', this);
        handleBtnSearchUser($btnSearchUser);

       $('a[name="pilih_user"]', this).click(function(){
 
            var $anchor = $(this),
                  id    = $anchor.data('id');
             
                $tableKasirBiaya.api().ajax.url(baseAppUrl + 'listing_kasir_biaya/' + id).load();
                $tableInvoice.api().ajax.url(baseAppUrl + 'listing_pembayaran_pasien/' + id).load();
        });                

                
        } );
        

        $('#status').on( 'change', function () {
                // alert('klik');
            // var tanggal  =  $('input#date').val();
                // alert($(this).val());

                oTable.api().ajax.url(baseAppUrl + 'listing_titip_setoran/' + $(this).val()).load();

            }); 
                 
    };

    var handleDataTable2 = function(){
             oTable2 = $tableTerimasetoran.dataTable({
               'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_terima_setoran',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },   
                ]
            }); 
        
        $('#table_terima_setoran_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_terima_setoran_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
          
    };

    var handleKasirBiaya = function(){

        $tableKasirBiaya.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_kasir_biaya/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true }
                ]
        });       
        $('#table_kasir_biaya_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_kasir_biaya_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown


        $tableKasirBiaya.on('draw.dt', function (){
            
        });
    };

    var handleInvoice = function(){

        $tableInvoice.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pembayaran_pasien/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true }
                ]
        });       
        $('#table_pembayaran_pasien_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pembayaran_pasien_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown


        $tableInvoice.on('draw.dt', function (){
            
        });
    };


    var handleDeleteRow = function(){
        $('a[name="check[]"]').click(function(){
                
            var $anchor = $(this),
                  id    = $anchor.data('id');

            bootbox.confirm('Are you sure to finish process this setoran?', function(result) {
                if(result==true) {
                    //location.href = baseAppUrl + 'delete/' +id;
                } 
            });   
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

    var handleModal = function(){
        $('a#modal_ok').click(function() {
            var msg = $(this).data('confirm');

                    $('#btnOK').click();
                    // alert('di klik');
        });
    };

    

     var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'MM yyyy',
                autoclose: true,
                minViewMode : 'months'
            }).on('changeDate', function(ev){

                // alert('a');

                var filter_date   = $('input#date').val();
                var date_terima   = $('input#date_terima').val();

                oTable.api().ajax.url(baseAppUrl + 'listing_titip_setoran/'+ filter_date).load();
                oTable2.api().ajax.url(baseAppUrl + 'listing_terima_setoran/'+ date_terima).load();


            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    
    o.init = function(){
        $form.validate();
        baseAppUrl = mb.baseUrl() + 'reservasi/titip_terima_setoran/';
        initForm();
        handleModal();
        handleDeleteRow();
        handleDataTable2();
        handleDataTable();
        handleKasirBiaya();
        handleInvoice();
        handleConfirmSave();
        handleDatePickers();
    };

}(mb.app.titip_terima_setoran.view));


// initialize  mb.app.titip_terima_setoran.view
$(function(){
    mb.app.titip_terima_setoran.view.init();

});