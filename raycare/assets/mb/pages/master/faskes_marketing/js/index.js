mb.app.faskes_marketing = mb.app.faskes_marketing || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tableFaskesMarketing        = $('#table_faskes_marketing');
 
    var handleDataTable = function() 
    { 
    	oTable = $tableFaskesMarketing.dataTable({
          'processing'            : true,
    			'serverSide'            : true,
          'pagingType'            : 'full_numbers',
    			'language'              : mb.DTLanguage(),
    			'ajax'              	  : {
                              				'url'   : baseAppUrl + 'listing',
                              				'type'  : 'POST',
                              			},			
    			'pageLength'			      : 10,
    			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
    			'order'                	: [[1, 'asc']],
    			'columns'               : [ 
                              				{ 'name' : 'user.nama nama_marketing', 'visible' : true, 'searchable': true, 'orderable': true },
                              				{ 'name' : 'master_faskes.kode_faskes kode_faskes', 'visible' : true, 'searchable': true, 'orderable': true },
                                      { 'name' : 'master_faskes.nama_faskes nama_faskes', 'visible' : true, 'searchable': true, 'orderable': true },
                                      { 'name' : 'master_faskes.nama_reg nama_reg', 'visible' : true, 'searchable': true, 'orderable': true },
                              				{ 'name' : 'user.nama nama_marketing', 'visible' : true, 'searchable': false, 'orderable': false },
                              		  ],
        
      });
      $tableFaskesMarketing.on('draw.dt', function(){
        $btnDelete = $('a.delete', this);

        $btnDelete.click(function(){
          var id = $(this).data('id'),
              msg = $(this).data('confirm');

            bootbox.confirm(msg, function(result){
                if(result == true){
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'delete_faskes_marketing',
                        data     : {kode_faskes: id},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            if (results.success === true) {
                                oTable.api().ajax.url(baseAppUrl +  'listing').load();

                            }
                            else
                            {
                                
                            }

                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
                    
                }
            });    
        });
      });		  
    }

    // mb.app.home.table properties
    o.init = function(){
      baseAppUrl = mb.baseUrl() + 'master/faskes_marketing/';
      handleDataTable();
    };
 }(mb.app.faskes_marketing));


// initialize  mb.app.home.table
$(function(){
    mb.app.faskes_marketing.init();
});