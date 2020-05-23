mb.app.kelompok_penjamin = mb.app.kelompok_penjamin || {};
(function(o){

    var 
      baseAppUrl          = '',
      $tablekelompok_penjamin        = $('#table_kelompok_penjamin'),
      $tablekelompok_penjamin2       = $('#table_order_item'),
      $tabletindakan      = $('#table_addperson'),
      $tableItemSearch    = $('#table_item_search'),
      $popoverItemContent = $('#popover_item_content2'), 
      $lastPopoverItem    = null,
      $yy                 = 0,
      itemCounter         = 1;
 
    var handleDataTable = function() 
    { 
        	oTable = $tablekelompok_penjamin.dataTable({
              'processing'            : true,
        			'serverSide'            : true,
              'stateSave'             : true,
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
                                  				{ 'visible' : true, 'searchable': true, 'orderable': true },
                                  				{ 'visible' : true, 'searchable': true, 'orderable': true },
                                  				{ 'visible' : true, 'searchable': false, 'orderable': false },
                                  		  ],
            
          });
          // $('#table_kelompok_penjamin_wrapper .dataTables_length select').select2(); // modify table per page dropdown
          $tablekelompok_penjamin.on('draw.dt', function (){
              
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
        
        $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "deleteajax",  
                                data:  {id:id},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                     // $('#accept', $form).click();
                                   mb.showMessage(data[0],data[1],data[2]);
                                   oTable.api().ajax.reload();
                      
                                }
                   
                       });
                        // oTable.ajax.url(baseAppUrl + 'listing').load();
                        
      } 
    });
  
  };
    var handleDelete = function(btn){
      $(btn, this).click(function(){
      var $anchor = btn,
                        id    = $anchor.data('id');
                        msg    = $anchor.data('confirm');
                        
                         handleDeleteRow(id,msg,1);
     });

    }

    var handleDeleteRow = function(id,msg,type){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				//location.href = baseAppUrl + 'delete/' +id;

				$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "deleteajax",  
                                data:  {id:id,type:type},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                     // $('#accept', $form).click();

                                   mb.showMessage(data[0],data[1],data[2]);
                                   oTable.api().ajax.reload();
                                 
                                }
                   
                       });
                        // oTable.ajax.url(baseAppUrl + 'listing').load();
                        
			} 
		});
	
	};

	
var initForm = function(){
         handleDataTable();
        var 
            $btnsSearchItem = $('a[name="view[]"]');
           
  
    };

     var addItemRow = function(){
         

        handleBtnSearchItem(1);

       // handleBtnDeleteItem($btnDelete);
       // handleCheck($checkMultiply);
        
    };

    var handleBtnSearchItem = function($btn){
 
     //   var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);
  
        $btn.popover({ 
            html : true,
           // container : '.page-content',
            placement : 'bottom',
           // content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){
           
              var $popContainer = $(this).data('bs.popover').tip();
         
               var maxWidth = 700;

               $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
          //   // $popContainer.css({minWidth: '720px', maxWidth: '720px'});

          if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

           $lastPopoverItem = $btn;
 
          // //  $popoverItemContent.show();
           
        }).on('shown.bs.popover', function(){
  
             var 
                 $popContainer = $(this).data('bs.popover').tip();
            //     $popContent   = $popContainer.find('.popover-content');

            // // record rowId di popcontent
            // $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
            
            
            // // pindahkan $popoverItemContent ke .popover-conter
             $popContainer.find('.popover-content').append($popoverItemContent);
             $popoverItemContent.show();
        }).on('hide.bs.popover', function(){
             
           //  //pindahkan kembali $popoverItemContent ke .page-content
           // $popoverItemContent.hide();
           // $popoverItemContent.appendTo($('.page-content'));

              $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
           
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
          // alert('hi');
          // e.preventDefault();
        });
     
    };   

     var handleDataTable2 = function() 
    {
     
    	
    	  oTable2=$tablekelompok_penjamin2.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_tindakan/',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				//{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
        { 'visible' : false, 'searchable': true, 'orderable': true },
			 
        		]
        });oTable2.api().ajax.url(baseAppUrl + 'listing_tindakan_2/' + $("#pk2").val()).load() ;
        $tablekelompok_penjamin2.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="select[]"]', this).click(function(){

					var $anchor = $(this),
					      id    = $anchor.data('id');
					     
					oTable2.api().ajax.url(baseAppUrl + 'listing_tindakan_2/' + id).load();
					$("#pk2", $('#modaltindakan')).val(id);
					$("#date", $('#modaltindakan')).val('');
					$("#harga", $('#modaltindakan')).val('');
					 
					if(!$(".alert-danger", $('#modaltindakan')).is(":visible")){
						$(".alert-danger", $('#modaltindakan')).hide("");

					}
					 
					//oTable2.api().ajax.reload();
					//handleDeleteRow(id,msg,1);
			});	
			$('a[name="select[]2"]', this).click(function(){

					var $anchor = $(this),
					      id    = $anchor.data('id');
					     
					  oTable3.api().ajax.url(baseAppUrl + 'listing_tindakan_2/' + id).load();
				 
			});	
			 
		} );
    }


    var handleDataTable3 = function() 
    {

    	  oTable3=$tabletindakan.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_tindakan_2',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
				//{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },

				 
        		]
        });
        $tabletindakan.on('draw.dt', function (){
			// $('.btn', this).tooltip();
			// // action for delete locker
			// $('a[name="select[]"]', this).click(function(){
				 
			// 		var $anchor = $(this),
			// 		      id    = $anchor.data('id');
			// 		      msg    = $anchor.data('confirm');

			// 		handleDeleteRow(id,msg,1);
			// });	

			 
		} );
    }
    // mb.app.home.table properties
    o.init = function(){
    	 
        baseAppUrl = mb.baseUrl() + 'master/kelompok_penjamin/';
      handleDataTable();
       // handleDataTable2();
       // handleDataTable3();
       // initForm();

    };
 }(mb.app.kelompok_penjamin));


// initialize  mb.app.home.table
$(function(){
    mb.app.kelompok_penjamin.init();
});