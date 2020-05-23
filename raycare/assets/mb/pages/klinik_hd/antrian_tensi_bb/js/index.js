mb.app.antrian_tensi = mb.app.antrian_tensi || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tableAntrian        = $('#table_antrian_tensi');
      $tableAntrianHistory        = $('#table_antrian_tensi_history');

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
                $('div#counter_panggil').text($('input#nama_pasien').val() +'| ' +i+' Kali');

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
          oTable = $tableAntrian.dataTable({
              'processing'            : true,
              'serverSide'            : true,
              'language'              : mb.DTLanguage(),
              'ajax'                  : {
                                          'url'   : baseAppUrl + 'listing',
                                          'type'  : 'POST',
                                        },      
              'pageLength'            : 10,
              'paginate'            : false,
              'info'            : false,
              'filter'            : false,
              'stateSave'             :true,
              'pagingType'            :'full_numbers',
              'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
              'order'                 : [[1, 'asc']],
              'columns'               : [ 
                                          { 'name' : 'pasien.id id','visible' : true, 'searchable': false, 'orderable': true },
                                          { 'name' : 'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
                                          { 'name' : 'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                                          { 'name' : 'pasien.tempat_lahir tempat_lahir','visible' : true, 'searchable': false, 'orderable': true },
                                          { 'name' : 'pendaftaran_tindakan.berat_badan berat_badan','visible' : true, 'searchable': true, 'orderable': true },                                              
                                          { 'name' : 'pendaftaran_tindakan.tekanan_darah tekanan_darah','visible' : true, 'searchable': true, 'orderable': true },                                              
                                          { 'name' : 'pasien.tempat_lahir tempat_lahir', 'visible' : true, 'searchable': false, 'orderable': false },
                                        ],
               
          });

          var tableColumnToggler = $('#sample_4_column_toggler');

          oTable.fnSetColumnVis(1, false);
          oTable.fnSetColumnVis(3, false);
          
          $('input[type="checkbox"]', tableColumnToggler).change(function () {
              /* Get the DataTables object again - this is not a recreation, just a get of the object */
              var iCol = parseInt($(this).attr("data-column"));
              var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
              oTable.fnSetColumnVis(iCol, (bVis ? false : true));
          });

          $tableAntrian.on('draw.dt', function (){
               $('a.proses', this).click(function(){
                  var index = $(this).data('index'),
                      id = $(this).data('id'),
                      shift = $(this).data('shift'),
                      dokter_id = $(this).data('dokter_id'),
                      bb = $('input#bb_'+index).val(),
                      ta = $('input#tensi_atas_'+index).val(),
                      tb = $('input#tensi_bwh_'+index).val();

                  if( isNumeric(bb) == true && isNumeric(ta) == true && isNumeric(tb) == true){
                      $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'save',
                        data     : {id:id, bb:bb, ta:ta, tb:tb, shift:shift, dokter_id:dokter_id},
                        dataType : 'json',
                        success  : function( results ) {
                            
                            if(results.success == true){
                                mb.showToast('success',results.msg,'Berhasil');
                                oTable.api().ajax.url(baseAppUrl + 'listing').load();

                            }
                            if(results.success == false){
                                mb.showToast('error',results.msg,'Gagal');
                                oTable.api().ajax.url(baseAppUrl + 'listing').load();
                            }

                            
                        }
                    });
                  }else{
                    bootbox.alert('Gunakan tanda titik untuk mengisi bilangan desimal');
                    $('input#bb_'+index).focus();
                  }
              });
          });
          // $popoverItemContent.hide();
    }

    function isNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }

    var handleDataTableHistory = function() 
    { 
        oTableHistory = $tableAntrianHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                                        'url'   : baseAppUrl + 'listing_history',
                                        'type'  : 'POST',
                                      },      
            'pageLength'            : 10,
            'paginate'            : false,
            'info'            : false,
            'filter'            : false,
            'stateSave'             :true,
            'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [ 
                                        { 'name' : 'pasien.id id','visible' : true, 'searchable': false, 'orderable': true },
                                        { 'name' : 'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
                                        { 'name' : 'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                                        { 'name' : 'pasien.tempat_lahir tempat_lahir','visible' : true, 'searchable': false, 'orderable': true },
                                        { 'name' : 'pendaftaran_tindakan.berat_badan berat_badan','visible' : true, 'searchable': true, 'orderable': true },                                              
                                        { 'name' : 'pendaftaran_tindakan.tekanan_darah tekanan_darah','visible' : true, 'searchable': true, 'orderable': true },  
                                      ],
             
        });

        var tableColumnToggler = $('#combo_history');

        oTableHistory.fnSetColumnVis(1, false);
        oTableHistory.fnSetColumnVis(3, false);
        
        $('input[type="checkbox"]', tableColumnToggler).change(function () {
            /* Get the DataTables object again - this is not a recreation, just a get of the object */
            var iCol = parseInt($(this).attr("data-column"));
            var bVis = oTableHistory.fnSettings().aoColumns[iCol].bVisible;
            oTableHistory.fnSetColumnVis(iCol, (bVis ? false : true));
        });
    }


     
    o.init = function(){
       
        baseAppUrl = mb.baseUrl() + 'klinik_hd/antrian_tensi_bb/';
        handleTombolAntrian();
        handleDataTable();   
        handleDataTableHistory();  
    };
 }(mb.app.antrian_tensi));


// initialize  mb.app.home.table
$(function(){
    mb.app.antrian_tensi.init();
});