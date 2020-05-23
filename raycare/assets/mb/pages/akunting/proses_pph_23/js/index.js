mb.app.antrian_tensi = mb.app.antrian_tensi || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tablePPH        = $('#table_pph_23');
      $tablePPHHistory        = $('#table_pph_23_history');

    var handleDataTable = function() 
    { 
          oTable = $tablePPH.dataTable({
              'processing'            : true,
              'serverSide'            : true,
              'language'              : mb.DTLanguage(),
              'ajax'                  : {
                                          'url'   : baseAppUrl + 'listing/1',
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
                                          { 'name' : 'pembelian_pph_23.id id','visible' : true, 'searchable': false, 'orderable': true },
                                          { 'name' : 'pembelian_pph_23.nomor_pph nomor_pph','visible' : true, 'searchable': true, 'orderable': true },
                                          { 'name' : 'pembelian_pph_23.nomor_pembelian nomor_pembelian','visible' : true, 'searchable': true, 'orderable': true },
                                          { 'name' : 'pembelian_pph_23.pph_23_nominal pph_23_nominal','visible' : true, 'searchable': false, 'orderable': true },
                                          { 'name' : 'pembelian_pph_23.status status','visible' : true, 'searchable': true, 'orderable': true },                                              
                                          { 'name' : 'pembelian_pph_23.kode_pajak kode_pajak','visible' : true, 'searchable': true, 'orderable': true },                                              
                                          { 'name' : 'pembelian_pph_23.id id', 'visible' : true, 'searchable': false, 'orderable': false },
                                        ],
               
          });

          

          $tablePPH.on('draw.dt', function (){
               $('a.edit', this).click(function(){
                  var $anchor = $(this),
                      index    = $anchor.data('index');

                  $('div#action_edit_'+index).addClass('hidden');
                  $('div#action_save_cancel_'+index).removeClass('hidden');

                  $('label#label_kode_'+index).addClass('hidden');
                  $('input#kode_'+index).removeClass('hidden');

                  $('input#kode_'+index).focus();
                });

                $('a.batal', this).click(function(){
                  var $anchor = $(this),
                      index    = $anchor.data('index');

                  $('div#action_edit_'+index).removeClass('hidden');
                  $('div#action_save_cancel_'+index).addClass('hidden');

                  $('label#label_kode_'+index).removeClass('hidden');
                  $('input#kode_'+index).addClass('hidden');
                  
                });

                $('a.simpan', this).click(function(){
                  var $anchor = $(this),
                      index   = $anchor.data('index'),
                      msg   = $anchor.data('msg');

                  var i = 0;

                  var id = $('input#id_pph_'+index).val(),
                    po_id = $('input#id_po_'+index).val(),
                    kode = $('input#kode_'+index).val();

                    bootbox.confirm(msg, function(result) {
                        if (result==true) {
                            i = parseInt(i) + 1;
                            if(i == 1)
                            {
                                $.ajax({
                                type     : 'POST',
                                url      : baseAppUrl + 'save',
                                data     : {id:id, po_id:po_id, kode:kode},
                                dataType : 'json',
                                success  : function( results ) {
                                    
                                    if(results.success == true){
                                        mb.showToast('success',results.msg,'Berhasil');
                                        oTable.api().ajax.url(baseAppUrl + 'listing/1').load();

                                    }
                                    if(results.success == false){
                                        mb.showToast('error',results.msg,'Gagal');
                                        oTable.api().ajax.url(baseAppUrl + 'listing/1').load();
                                    }

                                    
                                }
                            });     
                            }
                        }
                    });
              });
          });
          // $popoverItemContent.hide();
    }


    var handleDataTableHistory = function() 
    { 
        oTableHistory = $tablePPHHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                                        'url'   : baseAppUrl + 'listing/2',
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
                                          { 'name' : 'pembelian_pph_23.id id','visible' : true, 'searchable': false, 'orderable': true },
                                          { 'name' : 'pembelian_pph_23.nomor_pph nomor_pph','visible' : true, 'searchable': true, 'orderable': true },
                                          { 'name' : 'pembelian_pph_23.nomor_pembelian nomor_pembelian','visible' : true, 'searchable': true, 'orderable': true },
                                          { 'name' : 'pembelian_pph_23.pph_23_nominal pph_23_nominal','visible' : true, 'searchable': false, 'orderable': true },
                                          { 'name' : 'pembelian_pph_23.status status','visible' : true, 'searchable': true, 'orderable': true },                                              
                                          { 'name' : 'pembelian_pph_23.kode_pajak kode_pajak','visible' : true, 'searchable': true, 'orderable': true },                                              
                                          { 'name' : 'pembelian_pph_23.id id', 'visible' : false, 'searchable': false, 'orderable': false },
                                      ],
             
        });

      
    }


     
    o.init = function(){
       
        baseAppUrl = mb.baseUrl() + 'akunting/proses_pph_23/';
        handleDataTable();   
        handleDataTableHistory();  
    };
 }(mb.app.antrian_tensi));


// initialize  mb.app.home.table
$(function(){
    mb.app.antrian_tensi.init();
});