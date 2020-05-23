mb.app.kirim_petty_cash = mb.app.kirim_petty_cash || {};

// mb.app.kirim_petty_cash namespace
(function(o){

    var 
        $tableKirimSaldo      = $('#table_kirim_petty_cash');

    var handleDataTable = function(){
        oTable = $tableKirimSaldo.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $tableKirimSaldo.on('draw.dt', function (){
              
        });       
    };


    
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/kirim_petty_cash/';
        handleDataTable();
    };

}(mb.app.kirim_petty_cash));


// initialize  mb.app.kirim_petty_cash
$(function(){
    mb.app.kirim_petty_cash.init();

});