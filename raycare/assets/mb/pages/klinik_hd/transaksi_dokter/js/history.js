mb.app.cabang = mb.app.cabang || {};
(function(o){

    var 
      baseAppUrl          = '';
 
  var handleHistori = function(){
     
    oTableHistori=$("#table_cabang3").dataTable({
        'processing'            : true,
        'serverSide'            : true,
        'language'              : mb.DTLanguage(),
        'ajax'                  : {
            'url' : baseAppUrl + 'listing_histori',
            'type' : 'POST',
        },          
        'pageLength'            : 10,
         // 'stateSave'             :true,
          'pagingType'            :'full_numbers',
        'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
        'order'                 : [[1, 'asc']],
        'columns'               : [
            
            { 'name':'tindakan_hd.no_transaksi id','visible' : false, 'searchable': false, 'orderable': false },
            { 'name':'tindakan_hd.no_transaksi no_transaksi',' visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'tindakan_hd.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'user.nama nama1','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'tindakan_hd.berat_awal berat_awal','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'tindakan_hd.berat_akhir berat_akhir','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'tindakan_hd.berat_akhir berat_akhir', 'visible' : true, 'searchable': false, 'orderable': false }
             
            ],
         
    });

    oTableHistoriUmum=$("#table_tindakan_umum").dataTable({
        'processing'            : true,
        'serverSide'            : true,
        'language'              : mb.DTLanguage(),
        'ajax'                  : {
            'url' : baseAppUrl + 'listing_histori_umum',
            'type' : 'POST',
        },          
        'pageLength'            : 10,
         // 'stateSave'             :true,
          'pagingType'            :'full_numbers',
        'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
        'order'                 : [[1, 'asc']],
        'columns'               : [
            
            { 'name':'tindakan_umum.id id','visible' : false, 'searchable': false, 'orderable': false },
            { 'name':'tindakan_umum.nomor_tindakan nomor_tindakan',' visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'tindakan_umum.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'user.nama nama1','visible' : true, 'searchable': true, 'orderable': true },
            { 'name':'tindakan_umum.berat_akhir berat_akhir', 'visible' : true, 'searchable': false, 'orderable': false }
             
            ],
             
        });

    };

  

   
    o.init = function(){
         
        baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
        handleHistori();
        

    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});