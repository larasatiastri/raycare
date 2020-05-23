mb.app.antrian_cek_lab = mb.app.antrian_cek_lab || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tableAntrian        = $('#table_antrian_lab');

    var handleDataTable = function() 
    { 
          oTable = $tableAntrian.dataTable({
            'processing'  : true,
            'serverSide'  : true,
            'language'    : mb.DTLanguage(),
            'ajax'        : {
                              'url'   : baseAppUrl + 'listing',
                              'type'  : 'POST',
                            },      
            'pageLength'  : 10,
            'paginate'    : false,
            'info'        : false,
            'filter'      : false,
            'stateSave'   : true,
            'pagingType'  :'full_numbers',
            'lengthMenu'  : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'       : [[1, 'asc']],
            'columns'     : [ 
                              { 'name' : 'pendaftaran_tindakan.id id', 'visible' : true, 'searchable': false, 'orderable': false },
                              { 'name' : 'pendaftaran_tindakan.created_date tanggal','visible' : true, 'searchable': true, 'orderable': true },                                              
                              { 'name' : 'pendaftaran_tindakan.nama_pasien nama_pasien','visible' : true, 'searchable': true, 'orderable': true },                                              
                              { 'name' : 'pendaftaran_tindakan.no_telp no_telp','visible' : true, 'searchable': true, 'orderable': true },                                              
                              { 'name' : 'pendaftaran_tindakan.id id', 'visible' : true, 'searchable': false, 'orderable': false },
                            ],
               
          });
          // $popoverItemContent.hide();
    }
     
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'laboratorium/antrian_cek_lab/';
        handleDataTable();   
    };
 }(mb.app.antrian_cek_lab));


// initialize  mb.app.home.table
$(function(){
    mb.app.antrian_cek_lab.init();
});