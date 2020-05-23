mb.app.titip_terima_uang = mb.app.titip_terima_uang || {};
mb.app.titip_terima_uang.view = mb.app.titip_terima_uang.view || {};

// mb.app.titip_terima_uang.view namespace
(function(o){

    var $form = $('#form_titip_terima_uang');
    var $tableTitipUang = $('#table_titip_uang');
    var $tableTerimaUang = $('#table_terima_uang');
    var $tableHistory = $('#table_history');

    var handleDataTable = function(){
            oTable = $tableTitipUang.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_titip_uang/' + $('input#date').val(),
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
                ]
        });
        
        $('#table_titip_uang_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_titip_uang_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
                
    };

    var handleDataTable2 = function(){
             oTable2 = $tableTerimaUang.dataTable({
               'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_terima_uang/' + $('input#date_terima').val(),
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
                ]
            }); 
        
        $('#table_terima_uang_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_terima_uang_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
          
    };

    var handleDataTable3 = function(){
             oTable3 = $tableHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_history',
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
                ]
            }); 
        
        $('#table_history_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_history_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
          
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

                oTable.api().ajax.url(baseAppUrl + 'listing_titip_uang/'+ filter_date).load();
                oTable2.api().ajax.url(baseAppUrl + 'listing_terima_uang/'+ date_terima).load();


            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    
    o.init = function(){
        $form.validate();
        baseAppUrl = mb.baseUrl() + 'reservasi/titip_terima_uang/';
        handleDeleteRow();
        handleDataTable2();
        handleDataTable3();
        handleDataTable();
        handleConfirmSave();
        handleDatePickers();
    };

}(mb.app.titip_terima_uang.view));


// initialize  mb.app.titip_terima_uang.view
$(function(){
    mb.app.titip_terima_uang.view.init();

});