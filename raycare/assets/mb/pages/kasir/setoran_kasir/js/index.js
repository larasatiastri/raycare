mb.app.setoran_kasir = mb.app.setoran_kasir || {};
(function(o){

    var 
        baseAppUrl             = '',
        $tableSetoranKasir        = $('#table_setoran_kasir');

    var handleDataTable = function() 
    {

        oTable = $tableSetoranKasir.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing',
                'type' : 'POST',
            },          
            
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
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableSetoranKasir.on('draw.dt', function (){
            $('.btn', this).tooltip();
            
        } );
    }
    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'kasir/setoran_kasir/';
        handleDataTable();
    };
 }(mb.app.setoran_kasir));


// initialize  mb.app.home.table
$(function(){
    mb.app.setoran_kasir.init();
});