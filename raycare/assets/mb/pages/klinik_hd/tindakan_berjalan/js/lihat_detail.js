mb.app.tindakan_berjalan = mb.app.tindakan_berjalan || {};
mb.app.tindakan_berjalan.view = mb.app.tindakan_berjalan.view || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tableTindakan        = $('#table_provision');
 
    var handleDataTable = function() 
    { 
        var tindakan_hd_id = $('input#tindakanid').val();

        oTable = $tableTindakan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                                        'url'   : baseAppUrl + 'listing_item_telah_digunakan/' + tindakan_hd_id,
                                        'type'  : 'POST',
                                      },      
            'pageLength'            : 25,
            'stateSave'             :true,
            'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [ 
                                        { 'name': 'tindakan_hd_item.waktu waktu','visible' : true, 'searchable': true, 'orderable': true },
                                        { 'name': 'item.nama item_nama','visible' : true, 'searchable': true, 'orderable': true },
                                        { 'name': 'sum(tindakan_hd_item.jumlah) jumlah','visible' : true, 'searchable': false, 'orderable': true },
                                        { 'name': 'user.nama user_nama','visible' : true, 'searchable': true, 'orderable': true },
                                      ],
             
        });
    }
	   
    o.init = function(){
    	 
        baseAppUrl = mb.baseUrl() + 'klinik_hd/tindakan_berjalan/';
        handleDataTable();  
    };
 }(mb.app.tindakan_berjalan.view));


// initialize  mb.app.home.table
$(function(){
    mb.app.tindakan_berjalan.view.init();
});