mb.app.history_penerimaan_barang = mb.app.history_penerimaan_barang || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form   = $('#form_index'),
        $table1 = $('#table_history_penerimaan_barang');

    var handleDataTable = function() 
    {
        $table1.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_history',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'filter'                : true,
            'paginate'              : true,
            'info'                  : false,
            'pagingType'            : 'full_numbers',
            'columns'               : [
                { 'visible' : false, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                ]
        });
        $table1.on('draw.dt', function (){
            $('.btn', this).tooltip();
               
        });
    }

    
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/penerimaan_barang/';
        handleDataTable();

    };
 }(mb.app.history_penerimaan_barang));


// initialize  mb.app.home.table
$(function(){
    mb.app.history_penerimaan_barang.init();
});