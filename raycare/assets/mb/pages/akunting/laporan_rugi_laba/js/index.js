mb.app.laporan_rugi_laba = mb.app.laporan_rugi_laba || {};
(function(o){

    var 
        baseAppUrl          = '',
        $tablelaporan_rugi_laba  = $('#table_labrug');

    var handleDataTable = function() 
    {
    	oTablelaporan_rugi_laba = $tablelaporan_rugi_laba.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
            ]
        });

    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'akunting/laporan_rugi_laba/';
        handleDataTable();
    };
 }(mb.app.laporan_rugi_laba));


// initialize  mb.app.home.table
$(function(){
    mb.app.laporan_rugi_laba.init();
});