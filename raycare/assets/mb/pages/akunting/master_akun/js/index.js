mb.app.master_akun = mb.app.master_akun || {};
(function(o){

    var 
        baseAppUrl          = '',
        $tableMasterAkun  = $('#table_master_akun');

    var handleDataTable = function() 
    {
    	oTableMasterAkun = $tableMasterAkun.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing/0',
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
                { 'visible' : true, 'searchable': true, 'orderable': false }
            ]
        });

        $tableMasterAkun.on('draw.dt', function (){
            // action for delete locker
            $('a[name="suspend[]"]', this).click(function(){
                var id    = $(this).data('id'),
                    msg    = $(this).data('confirm');

                handleSuspend(id, msg);
            }); 

            $('a[name="un_suspend[]"]', this).click(function(){
                var id    = $(this).data('id'),
                    msg    = $(this).data('confirm');

                handleUnSuspend(id, msg);
            }); 

        } );

    }

    var handleSuspend = function(id,msg){

        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'suspend/' +id;
            } 
        });
    }

    var handleUnSuspend = function(id,msg){

        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'un_suspend/' +id;
            } 
        });
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'akunting/master_akun/';
        handleDataTable();
    };
 }(mb.app.master_akun));


// initialize  mb.app.home.table
$(function(){
    mb.app.master_akun.init();
});