mb.app.tindakan_cek_lab = mb.app.tindakan_cek_lab || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tableTindakanLab        = $('#table_tindakan_lab'),
      $tableTindakanLabHistory        = $('#table_tindakan_lab_history');

    var handleDataTable = function() 
    { 
          oTable = $tableTindakanLab.dataTable({
            'processing'  : true,
            'serverSide'  : true,
            'language'    : mb.DTLanguage(),
            'ajax'        : {
                              'url'   : baseAppUrl + 'listing/1',
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
                              { 'name' : 'tindakan_lab.no_pemeriksaan no_pemeriksaan', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.tanggal tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.tipe_pasien tipe_pasien', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.nama_pasien nama_pasien', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.tanggal_lahir tanggal_lahir', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.no_telp_pasien no_telp_pasien', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.nama_dokter nama_dokter', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.status status', 'visible' : true, 'searchable': true, 'orderable': false },
                                                                         
                              { 'name' : 'tindakan_lab.id id', 'visible' : true, 'searchable': false, 'orderable': false },
                            ],
               
          });
        oTableHistory = $tableTindakanLabHistory.dataTable({
            'processing'  : true,
            'serverSide'  : true,
            'language'    : mb.DTLanguage(),
            'ajax'        : {
                              'url'   : baseAppUrl + 'listing/2',
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
                              { 'name' : 'tindakan_lab.no_pemeriksaan no_pemeriksaan', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.tanggal tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.tipe_pasien tipe_pasien', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.nama_pasien nama_pasien', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.tanggal_lahir tanggal_lahir', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.no_telp_pasien no_telp_pasien', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.nama_dokter nama_dokter', 'visible' : true, 'searchable': true, 'orderable': false },
                              { 'name' : 'tindakan_lab.status status', 'visible' : true, 'searchable': true, 'orderable': false },
                                                                         
                              { 'name' : 'tindakan_lab.id id', 'visible' : true, 'searchable': false, 'orderable': false },
                            ],
               
          });
         $tableTindakanLab.on('draw.dt', function (){
          
          $('a[name="delete[]"]', this).click(function(){
              var $anchor = $(this),
                    id    = $anchor.data('id');
                    msg    = $anchor.data('confirm');

              handleDeleteRow(id,msg);
          });
        });
    }
    
    var handleDeleteRow = function(id,msg){

      bootbox.confirm(msg, function(result) {
        if(result==true) {
          location.href = baseAppUrl + 'delete/' +id;
        } 
      });
    
    };
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'laboratorium/tindakan_cek_lab/';
        handleDataTable();   
    };
 }(mb.app.tindakan_cek_lab));


// initialize  mb.app.home.table
$(function(){
    mb.app.tindakan_cek_lab.init();
});