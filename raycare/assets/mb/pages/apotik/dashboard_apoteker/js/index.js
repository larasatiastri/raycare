mb.app.dashboard_apoteker = mb.app.dashboard_apoteker || {};
(function(o){

    var 
        baseAppUrl          = '',
        $tableExpired  = $('#table_expired'),
        $tableBuffer 	= $('#table_buffer'),
        $tablePembelianProses = $('#table_pembelian_proses');

    var handleDataTable = function() 
    {
    	$tableExpired.dataTable();

        $tableBuffer.dataTable();

        $tablePembelianProses.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_proses/1' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });


        
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/dashboard_apoteker/';
        handleDataTable();
    };
 }(mb.app.dashboard_apoteker));


// initialize  mb.app.home.table
$(function(){
    mb.app.dashboard_apoteker.init();
});