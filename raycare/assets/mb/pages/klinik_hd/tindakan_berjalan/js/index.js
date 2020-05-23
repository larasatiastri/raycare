mb.app.tindakan_berjalan = mb.app.tindakan_berjalan || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tableTindakan        = $('#table_tindakan');
 
    var handleDataTable = function() 
    { 
        oTable = $tableTindakan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                                        'url'   : baseAppUrl + 'listing_transaksi_diproses',
                                        'type'  : 'POST',
                                      },      
            'pageLength'            : 25,
            'stateSave'             : true,
            'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [ 
                                        { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                                        { 'name':'tindakan_hd.no_transaksi no_transaksi',' visible' : true, 'searchable': true, 'orderable': true },
                                        { 'name':'pasien_alamat.alamat alamat','visible' : true, 'searchable': true, 'orderable': true },
                                        { 'name':'user.nama nama_dokter','visible' : true, 'searchable': true, 'orderable': true },
                                        { 'name':'tindakan_hd.status status','visible' : true, 'searchable': true, 'orderable': true },
                                        { 'visible' : true, 'searchable': false, 'orderable': false },
                                      ],
             
        });
    }
	   
    o.init = function(){
    	 
        baseAppUrl = mb.baseUrl() + 'klinik_hd/tindakan_berjalan/';
        handleDataTable();  
    };
 }(mb.app.tindakan_berjalan));


// initialize  mb.app.home.table
$(function(){
    mb.app.tindakan_berjalan.init();
});