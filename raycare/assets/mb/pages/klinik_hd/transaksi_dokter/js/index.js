mb.app.cabang = mb.app.cabang || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tableCabang        = $('#table_cabang'),
      $tableDitolak        = $('#table_ditolak');

    var handleTombolAntrian = function(){

        var i = 0;

        $('a#tombol_panggil').click(function(){

          i = parseInt(i)+1;

          $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_antrian',
            data     : {antrian_id : $('input#antrian_id').val(), counter: i},
            dataType : 'json',
            success  : function( results ) {

              if(results.success == true){
                $('div#counter_panggil').text($('input#nama_pasien').val() +' | '+i+' Kali');

                if(i == 3){
                  $('div#div_lewat').removeClass('hidden');
                  $('div#div_tindak').removeClass('hidden');
                  i = 0;
                }
              }
              
                       
            },
            complete : function (transport) {
                
            }
          });

            
        });

        $('a#tombol_tindak').click(function(){
            i = 0;

            $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'tindak_antrian',
            data     : {antrian_id : $('input#antrian_id').val(), counter: i},
            dataType : 'json',
            success  : function( results ) {

              if(results.success == true){
                $('div#counter_panggil').text('');

                $('div#div_lewat').addClass('hidden');

                location.reload();
              }else{
                  mb.showToast('error','Pasien Belum Dipanggil','Gagal');
                  oTable.api().ajax.url(baseAppUrl + 'listing').load();
              }
              
                       
            },
            complete : function (transport) {
                
            }
          });

            
            
        });

        $('a#tombol_lewat').click(function(){
            i = 0;

            $.ajax({
              type     : 'POST',
              url      : baseAppUrl + 'lewati_antrian',
              data     : {antrian_id : $('input#antrian_id').val(), counter: i},
              dataType : 'json',
              success  : function( results ) {

                if(results.success == true){
                  $('div#counter_panggil').text('');

                  $('div#div_lewat').addClass('hidden');
                  $('div#div_tindak').removeClass('hidden');
                  location.reload();
                }else{
                    mb.showToast('error','Pasien Belum Dipanggil','Gagal');
                    oTable.api().ajax.url(baseAppUrl + 'listing').load();
                }
                
                         
              },
              complete : function (transport) {
                  
              }
            }); 
        });
    }

 
    var handleDataTable = function() 
    { 
          oTable = $tableCabang.dataTable({
              'processing'            : true,
              'serverSide'            : true,
              "scrollX"               : "100%",
              "scrollCollapse"        : true,
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
              'columns'               : [ 
                                          { 'name' : 'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                                          { 'name' : 'poliklinik.nama nama_poli','visible' : true, 'searchable': true, 'orderable': true },
                                          { 'name' : 'pasien.tempat_lahir tempat_lahir','visible' : true, 'searchable': true, 'orderable': true },
                                          { 'name' : 'pasien.tempat_lahir tempat_lahir','visible' : true, 'searchable': false, 'orderable': true },
                                          { 'name' : 'pendaftaran_tindakan.created_date created_date','visible' : true, 'searchable': true, 'orderable': true },                                              
                                          { 'name' : 'pasien.tempat_lahir tempat_lahir', 'visible' : true, 'searchable': false, 'orderable': false },
                                        ],
               
          });
          new $.fn.dataTable.FixedColumns( oTable );
          // $('#table_cabang_wrapper .dataTables_length select').select2(); // modify table per page dropdown


          $tableCabang.on('draw.dt', function (){
          $('.search-item', this).tooltip();
             $('a[name="del[]"]', this).click(function(e){
                var id = $(this).data('id'),
                    confirm = $(this).data('confirm');

                    handleDeleteRowPendaftaran(id,confirm);

             });      
          });
          // $popoverItemContent.hide();
    }

    var handleDataTableDitolak = function() 
    { 
      oTableDitolak = $tableDitolak.dataTable({
        'processing'            : true,
        'serverSide'            : true,
        'language'              : mb.DTLanguage(),
        'ajax'                  : {
                                    'url'   : baseAppUrl + 'listing_ditolak',
                                    'type'  : 'POST',
                                  },      
        'pageLength'            : 10,
        'stateSave'             :true,
        'filter'                : false,
        'info'                  : false,
        'paginate'              : false,
        'pagingType'            :'full_numbers',
        'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
        'order'                 : [[1, 'asc']],
        'columns'               : [ 
                                    { 'visible' : true, 'searchable': true, 'orderable': false },
                                    { 'visible' : true, 'searchable': true, 'orderable': false },
                                    { 'visible' : true, 'searchable': true, 'orderable': false },
                                    { 'visible' : true, 'searchable': true, 'orderable': false },                                      
                                  ],
           
      });
    }

    var handleDeleteRowPendaftaran = function(id,msg){

        bootbox.confirm(msg, function(result) {
          if(result==true) {
            location.href = baseAppUrl +'cancel_tindakan/'+id;
          }
        });  
    };

     
    o.init = function(){
       
        baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
        handleDataTable(); 
        handleDataTableDitolak();   
        handleTombolAntrian();    
    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});