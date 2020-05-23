mb.app.edit_transaksi = mb.app.edit_transaksi || {};
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
                { 'name':'tindakan_hd.berat_awal berat_awal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.berat_akhir berat_akhir','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.berat_akhir berat_akhir', 'visible' : true, 'searchable': false, 'orderable': false },
                ],
             
        });

        $('a.btn',$("#table_cabang3")).tooltip();     
    };


    var handleRefresh = function() {
      
      $('a#refresh_history').click(function(){
        oTableHistori.api().ajax.url(baseAppUrl + 'listing_histori').load();
      });

    };


  



    


   
    o.init = function(){
       
        baseAppUrl = mb.baseUrl() + 'klinik_hd/edit_transaksi/';
        handleHistori();
        handleRefresh();

    };
 }(mb.app.edit_transaksi));


// initialize  mb.app.home.table
$(function(){
    mb.app.edit_transaksi.init();
});