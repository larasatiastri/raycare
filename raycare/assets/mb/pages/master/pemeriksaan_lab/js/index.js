mb.app.pemeriksaan_lab = mb.app.pemeriksaan_lab || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tablePemeriksaan   = $('#table_pemeriksaan_lab');
 
    var handleDataTable = function() 
    { 
        oTable = $tablePemeriksaan.dataTable({
            'processing' : true,
            'serverSide' : true,
            'language'   : mb.DTLanguage(),
            'ajax'       : {
                              'url'   : baseAppUrl + 'listing',
                              'type'  : 'POST',
                            },      
            'pageLength' : 10,
            'lengthMenu' : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'      : [[1, 'asc']],
            'columns'    : [ //{ 'visible' : false, 'searchable': false, 'orderable': true },
                            { 'visible' : true, 'name' : 'kategori_pemeriksaan_lab.tipe tipe', 'searchable': true, 'orderable': true },
                            { 'visible' : true, 'name' : 'kategori_pemeriksaan_lab.kode kode', 'searchable': true, 'orderable': true },
                            { 'visible' : true, 'name' : 'kategori_pemeriksaan_lab.nama nama', 'searchable': true, 'orderable': true },
                            { 'visible' : true, 'name' : 'kategori_pemeriksaan_lab.harga harga', 'searchable': true, 'orderable': true },
                            { 'visible' : true, 'name' : 'kategori_pemeriksaan_lab.satuan satuan', 'searchable': true, 'orderable': true },
                            { 'visible' : true, 'name' : 'kategori_pemeriksaan_lab.id id', 'searchable': false, 'orderable': false },
                          ]
        }); 

        // $tablePemeriksaan.on('draw.dt', function() {
        //   // body...
        //     $('a[name="delete[]"]', this).click(function(){
        //       var id = $(this).data('id'),
        //           msg = $(this).data('confirm');

        //       handleDeleteRow(id,msg);
        //     });
        // });

    }

    var handleDeleteRow = function(id,msg){
      bootbox.confirm(msg, function(result) {
        if(result==true) {
          location.href = baseAppUrl + 'delete/'+id;                       
        } 
      });
    }
    var initForm = function(){
         handleDataTable();
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/pemeriksaan_lab/';
        initForm();
    };
 }(mb.app.pemeriksaan_lab));


// initialize  mb.app.home.table
$(function(){
    mb.app.pemeriksaan_lab.init();
});