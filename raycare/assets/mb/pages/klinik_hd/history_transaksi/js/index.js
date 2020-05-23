mb.app.history_transaksi = mb.app.history_transaksi || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tableCabang        = $('#table_cabang'),
      $tableCabang2       = $('#table_order_item'),
      $tabletindakan      = $('#table_addperson'),
      $tableItemSearch    = $('#table_item_search'),
      $popoverItemContent = $('#popover_item_content2'), 
      $lastPopoverItem    = null,
      $yy                 = 0,
      theadFilterTemplate = $('#thead-filter-template').text(),
      itemCounter         = 1,
      timestamp           = 0,
      noerror             = true;
 
    var handleDataTable = function() 
    { 
          oTable = $tableCabang.dataTable({
              'processing'            : true,
              'serverSide'            : true,
              'language'              : mb.DTLanguage(),
              'ajax'                  : {
                                          'url'   : baseAppUrl + 'listing',
                                          'type'  : 'POST',
                                        },      
              'pageLength'            : 10,
              'stateSave'             :true,
              'pagingType'            :'full_numbers',
              'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
              'order'                 : [[1, 'asc']],
              'columns'               : [ //{ 'visible' : false, 'searchable': false, 'orderable': true },
                                          { 'visible' : true, 'searchable': true, 'orderable': true },
                                          { 'visible' : true, 'searchable': true, 'orderable': true },
                                          { 'visible' : true, 'searchable': true, 'orderable': true },
                                          { 'visible' : false, 'searchable': true, 'orderable': true },
                                          { 'visible' : false, 'searchable': true, 'orderable': true },
                                          { 'visible' : false, 'searchable': true, 'orderable': true },
                                          { 'visible' : false, 'searchable': true, 'orderable': true },
                                          { 'visible' : false, 'searchable': true, 'orderable': true },
                                          { 'visible' : true, 'searchable': true, 'orderable': true },
                                          
                                          { 'visible' : true, 'searchable': false, 'orderable': false },
                                        ],
               
          });
          // $('#table_cabang_wrapper .dataTables_length select').select2(); // modify table per page dropdown


          $tableCabang.on('draw.dt', function (){
            $('.search-item', this).tooltip();       
          });
          // $popoverItemContent.hide();
    }

     var handleTransaksiDiproses = function(){
     
      oTableTransaksiDiproses=$("#table_cabang2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_transaksi_diproses',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name':'tindakan_hd.id id',' visible' : false, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.no_transaksi no_transaksi',' visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.nama pasien_nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.tempat_lahir tempat_lahir','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'f.alamat alamat','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.status hd_status','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.status hd_status','visible' : true, 'searchable': false, 'orderable': false },
                 
                ],
             
        });

       $("#table_cabang2").on('draw.dt', function (){
          $('.search-item', this).tooltip();

        });
         
     
    };

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
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'tindakan_hd.id id','visible' : false, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.no_transaksi no_transaksi',' visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'user.nama nama1','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'r.nama nama3','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.berat_awal berat_awal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.berat_akhir berat_akhir','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.berat_akhir berat_akhir', 'visible' : true, 'searchable': false, 'orderable': false },
                ],
             
        });

        $('a.btn',$("#table_cabang3")).tooltip();

        // $("#table_cabang3").on("click", "tr", function() {
        //   var iPos = oTableHistori.fnGetPosition( this );
        //   var aData = oTableHistori.fnGetData( iPos );
        //   var iId = aData[0];

        //   // alert(iId);
        //   location.href = baseAppUrl + 'detail_history/'+iId;

        // });

 
         
     
    };


  



    


   
    o.init = function(){
       
        baseAppUrl = mb.baseUrl() + 'klinik_hd/history_transaksi/';
        handleHistori();
       

    };
 }(mb.app.history_transaksi));


// initialize  mb.app.home.table
$(function(){
    mb.app.history_transaksi.init();
});