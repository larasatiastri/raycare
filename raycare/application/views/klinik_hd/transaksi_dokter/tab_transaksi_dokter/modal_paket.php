 
 <link href="<?=base_url()?>assets/metronic/admin/layout3/css/google-font-open-sans-400-300-600-700.css" rel="stylesheet" type="text/css"/>
 <link href="<?=base_url()?>assets/mb/global/css/maestrobyte.css" rel="stylesheet" type="text/css">
 <link href="<?=base_url()?>assets/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css">
 
<style>
.popover{
    z-index:100000000;
}
input[type="number"] {
   width:50px;
}
.loading-message{
   z-index : 200000000; // Could be any value but less than 1000.
}
</style>

<?php if(isset($js_files) && count($js_files)): ?>

<?php foreach ($js_files as $js): ?>

<script type="text/javascript" src="<?=base_url().$js?>"></script>

<?php endforeach; ?>

<?php endif;?>
 
<script>
 
 var 
     baseAppUrl              = '',
     $form = $('#form_observasi_dialisis5');
     
     // $errorTop               = $('.alert-danger', $form),
     // $successTop             = $('.alert-success', $form),
     
     $tableOrderItem55        = $('#table_paket5'),
     
     
     
     $popoverItemContentpaket5566     = $('#popover_item_content_tagpaket5566'), 
     
     
     
     $lastPopoverItem55        = null,
     
     
     tplItemRow55             = $.validator.format( $('#tpl_item_row5').text() ),
     itemCounter55             = 1,
     ccc=0,
     regExpTpl        = new RegExp('_ID_0', 'g')  // 'g' perform global, case-insensitive
    
        
        ;

$(document).ready(function() {
 
 
    baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
   // handleValidation();
    addItemRow55();
	handlePaket55();
	handletagpaket();
	handlebtnnumber();
	handlebtnnumber2();
	handlebtnnumber3();
	handlebtnnumber4();
	handlebtnopsi();
    handleChangeGudang();

	handleViewTagihan5();
	handleSaveTambahMonitoring();
    
    $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getnomortransaksi",  
                                data:  {transaksiid:$("#idtindakanpaket").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    
                                      $("#tagihantransaksinumber2").html(data.id);
                                       
                                     
                                     
                                     
                                }
                   
                       });
 
     $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_paket_item",  
                                data:  {transaksiid:$("#idtindakanpaket").val(),paket_id:$("#idtindakanpaketid").val(),tindakanhdpaketid:$("#tindakanhdpaketid").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                	var y=0;
                                    
                                    $("#table_edit_paket").find("tbody").append(data);

                                    $.each($("#table_edit_paket tbody tr"),function(){
                                    	y++;
                                    		  // handlebtnnumber($('input[name$="[user]"]', this),$('input[name$="[jatah]"]', this));
                                    		  handlebtnnumber($(this),y);
                                    		  handlebtnnumber2($(this),y);
                                    		// alert(idx);

                                    });
                                    
                                    var gudang_id = $('select#pilih_opsi_gudang').val();
                                    handleBtnIdentitas(gudang_id);
                                     
                                     
                                     
                                }
                   
                       });
 				$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_paket_tindakan",  
                                data:  {transaksiid:$("#idtindakanpaket").val(),paket_id:$("#idtindakanpaketid").val(),tindakanhdpaketid:$("#tindakanhdpaketid").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                	var y=0;
                                    
                                    $("#table_edit_tindakan").find("tbody").append(data);

                                    $.each($("#table_edit_tindakan tbody tr"),function(){
                                    	y++;
                                    		  // handlebtnnumber($('input[name$="[user]"]', this),$('input[name$="[jatah]"]', this));
                                    		  handlebtnnumber5($(this),y);
                                    		  handlebtnnumber6($(this),y);
                                    		// alert(idx);

                                    });
                                       
                                     
                                     
                                     
                                }
                   
                       });
 
});
 
 var handleBtnSearchPaket55 = function($btn){
  
       var rowId55  = $btn.closest('tr').prop('id');
        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId5"/>'

        }).on("show.bs.popover", function(){
            
            var $popContainer5 = $(this).data('bs.popover').tip();
         
             var maxWidth = 700;

            $popContainer5.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            

              if ($lastPopoverItem55 != null) $lastPopoverItem55.popover('hide');

              $lastPopoverItem55 = $btn;

        

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer5 = $(this).data('bs.popover').tip();
                $popContent5   = $popContainer5.find('.popover-content');

           
              $('input:hidden[name="rowItemId5"]', $popContent5).val(rowId55);
            
            
            $popContainer5.find('.popover-content').append($popoverItemContentpaket5566);
            $popoverItemContentpaket5566.show();
        }).on('hide.bs.popover', function(){
          
            
            $popoverItemContentpaket5566.hide();
            $popoverItemContentpaket5566.appendTo($('.page-content'));

           $lastPopoverItem55 = null;

        }).on('hidden.bs.popover', function(){
            
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
           
          e.preventDefault();
        });
    };

    var addItemRow55 = function(){
        var numRow = $('tbody tr', $tableOrderItem55).length;
 
        
        var 
            $rowContainer  = $('tbody', $tableOrderItem55),
            $newItemRow    = $(tplItemRow55(itemCounter55++)).appendTo( $rowContainer ),
            $btnDelete     = $('.del-this5', $newItemRow);
            $btnSearchItem     = $('.tambahpakett55', $newItemRow);

		$btnDelete.tooltip();
		 $btnSearchItem.tooltip();
   		handleBtnSearchPaket55($btnSearchItem);
      //  handleBtnSearchItem($btnSearchItem);
       
       handleBtnDeleteItem55($btnDelete);
       
       // handleCheck($checkMultiply);
        
    };

    var handleBtnDeleteItem55 = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem55);

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableOrderItem55).length == 0){
                        addItemRow55();
                    }
            //     }
            // });

            e.preventDefault();
        });

    };

    var handlePaket55 = function(){
  
      oTablePaket55=$("#table_obat5").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_popover2',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 
                ],
            "createdRow"             : function( row, data, dataIndex ) {
                            $('a[name="viewpaket55[]"]', row).click(function(){
                                found=false;
                                 
                               $.each($('input[name$="[idpaket5]"]', $("#table_paket5")),function(idx, value){
                               
                                if($('a[name="viewpaket55[]"]', row).data('item').id == this.value)
                                    {
                                        found=true;
                                       
                                    } 
                                });

                              if(found==false)
                               {
                                            $('input[name$="[namapaket5]"]').last().val($(this).data('item').nama);
                                             $('input[name$="[idpaket5]"]').last().val($(this).data('item').id);
                                             $('input[name$="[harga5]"]').last().val($(this).data('item').harga);
                                           
                                            addItemRow55();
                                }
                                          
                                       $('.tambahpakett55', $("#table_paket5")).popover('hide');          


                                                });
                                            }
             
        });

        
     
    };


var handletagpaket= function(){
	var arr = [];

     $('#confirm_save_tagpaket55').on('click', function(){
          
     
    		$.each($("#table_paket5 tbody tr"),function(idx){

    				 arr.push($('input[name$="[idpaket5]"]', this).val());
    		})

    		 $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "inserttindakanhdpaket",  
                                data:  {paket_id:arr,tindakan_id:$('input#id_tindakan').val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    
                                      
                                       
                                     $("#reloadpakettagihan")[0].click();
                                     
                                     
                                }
                   
                       });
});
        
       // });
}

var handleViewTagihan5= function(){
  
      oTableViewTagihann5=$("#table_view_paket5").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_view_tagihan_paket2/' + $("#idtindakanpaket").val() + '/' + $("#idtindakanpaketid").val() + '/' + $("#tindakanhdpaketid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                {'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                {'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 
                ],
             
        });

  		$("#table_view_paket5").on('draw.dt', function (){
			$('.btn', this).tooltip();
		 
          });
         
     
    };

    var handlebtnnumber = function(btn,sisa)
    {  
        $('input[name$="[user]"]', btn).click(function(){
        	$('div[name$="item['+sisa+'][sisa]"]').html($('input[name$="item['+sisa+'][sisa2]"]').val()-this.value);
        });

       
    };

     var handlebtnnumber2 = function(btn,sisa)
    {  
      
        $('input[name$="item['+sisa+'][user]"]').on('keyup', function(){
  			 $('div[name$="item['+sisa+'][sisa]"]').html($('input[name$="item['+sisa+'][sisa2]"]').val()-this.value);
		});
    };

     var handlebtnnumber3 = function(btn,sisa)
    {  
        $('input[name$="[user2]"]', btn).click(function(){
        	$('div[name$="item['+sisa+'][sisa2]"]').html($('input[name$="item['+sisa+'][sisa3]"]').val()-this.value);
        });

       
    };

     var handlebtnnumber4 = function(btn,sisa)
    {  
      
        $('input[name$="item['+sisa+'][user2]"]').on('keyup', function(){
  			 $('div[name$="item['+sisa+'][sisa2]"]').html($('input[name$="item['+sisa+'][sisa3]"]').val()-this.value);
		});
    };

     var handlebtnnumber5 = function(btn,sisa)
    {  
        $('input[name$="[user4]"]', btn).click(function(){
        	$('div[name$="item['+sisa+'][sisa4]"]').html($('input[name$="item['+sisa+'][sisa5]"]').val()-this.value);
        });

       
    };

     var handlebtnnumber6 = function(btn,sisa)
    {  
      
        $('input[name$="item['+sisa+'][user4]"]').on('keyup', function(){
  			 $('div[name$="item['+sisa+'][sisa4]"]').html($('input[name$="item['+sisa+'][sisa5]"]').val()-this.value);
		});
    };

     var handlebtnnumber7 = function(btn,sisa)
    {  
        $('input[name$="[user5]"]', btn).click(function(){
        	$('div[name$="item['+sisa+'][sisa5]"]').html($('input[name$="item['+sisa+'][sisa6]"]').val()-this.value);
        });

       
    };

     var handlebtnnumber8 = function(btn,sisa)
    {  
      
        $('input[name$="item['+sisa+'][user5]"]').on('keyup', function(){
  			 $('div[name$="item['+sisa+'][sisa5]"]').html($('input[name$="item['+sisa+'][sisa6]"]').val()-this.value);
		});
    };

     var handleSaveTambahMonitoring = function()
    {
        $('a#modal_ok3').click(function() {
 
   		if (! $("#modal_1").valid()) return;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
 					var ItemArray = [];
 					var ItemArray2 = [];
 					if($("#pilih_opsi").val()=='clear' || $("#pilih_opsi").val()=='all')
 					{
 						$.each($("#table_edit_paket tbody tr"),function(){
                        	ItemArray.push({
    							item_id :  $('input[name$="[itemid]"]',this).val(), 
    							jumlah : $('input[name$="[user]"]',this).val(),
    							item_satuan_id:$('input[name$="[satuanid]"]',this).val(),
                                
							});

                      	});

                      	$.each($("#table_edit_tindakan tbody tr"),function(){
                        	ItemArray2.push({
    							tindakan_id :  $('input[name$="[itemid4]"]',this).val(), 
    							jumlah : $('input[name$="[user4]"]',this).val(),
    							 
							});

                      	});
 					}else{
 						$.each($("#table_edit_paket tbody tr"),function(){
                        	ItemArray.push({
    							item_id :  $('input[name$="[itemid]"]',this).val(), 
    							jumlah : $('input[name$="[user]"]',this).val(),
    							item_satuan_id:$('input[name$="[satuanid2]"]',this).val(),
							});
                        });

                        $.each($("#table_edit_tindakan tbody tr"),function(){
                        	ItemArray2.push({
    							tindakan_id :  $('input[name$="[itemid5]"]',this).val(), 
    							jumlah : $('input[name$="[user5]"]',this).val(),
    							 
							});

                      	});
 					}

 					 
					
                    $.ajax
                    ({ 

                        type: 'POST',
                        url: baseAppUrl +  "simpatindakanhditem",  
                        // data:  {data:ItemArray,data2:ItemArray2,jml_item : $('input[name="jml_item"]',$("#table_edit_paket")).val(),transaksiid:$("#idtindakanpaket").val(),paket_id:$("#idtindakanpaketid").val(),tindakanhdpaketid:$("#tindakanhdpaketid").val(),tggl_tindakan:$("#tggl_tindakan").val(),keterangan_tindakan:$("#keterangan_tindakan").val()},  
                       data : $("#modal_1").serialize(),
                        dataType : 'json',
                         beforeSend : function(){
                			Metronic.blockUI({boxed: true, message:'Sedang Diproses..'});
            			},
                        success:function(data)          //on recieve of reply
                        { 
                             mb.showMessage('success','Item dalam paket telah digunakan','Sukses');
                             $('a.reload-table2').click();
                        },
                        complete : function() {
                			Metronic.unblockUI();
            			}
               
                   });
                    $("#reloadpakettagihan2")[0].click();

                }
            });
        });
    };

    var handleChangeGudang = function()
    {
        $('select#pilih_opsi_gudang').on('change', function(){
            var gudang_id = $(this).val();
            handleBtnIdentitas(gudang_id);
        });
    }

     var handlebtnopsi = function()
    {  
        $('#pilih_opsi').on('change', function(){
        	 // alert($("#idtindakanpaket").val());
        	 // alert($("#tindakanhdpaketid").val());
        	 if(this.value=='clear')
        	 {
        	 	 	 $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_paket_item",  
                                data:  {transaksiid:$("#idtindakanpaket").val(),paket_id:$("#idtindakanpaketid").val(),tindakanhdpaketid:$("#tindakanhdpaketid").val()},  
                                dataType : 'json',
                                 beforeSend : function(){
                					Metronic.blockUI({boxed: true , message : 'Sedang Diproses'});
            					},
                                success:function(data)          //on recieve of reply
                                { 
                                	var y=0;
                                    $("#table_edit_paket").find("tbody tr").remove();
                                    $("#table_edit_paket").find("tbody").append(data);

                                    $.each($("#table_edit_paket tbody tr"),function(){
                                    	y++;
                                    		  // handlebtnnumber($('input[name$="[user]"]', this),$('input[name$="[jatah]"]', this));
                                    		  handlebtnnumber($(this),y);
                                    		  handlebtnnumber2($(this),y);
                                    		 

                                    });
                                       
                                      $.each($("#table_edit_paket tbody tr"),function(){
                              			$('input[name$="[user]"]',this).val(0);
                              			$('div[name$="[sisa]"]',this).html($('input[name$="[sisa2]"]',this).val());   

                     				});

                                    var gudang_id = $('select#pilih_opsi_gudang').val();
                                    handleBtnIdentitas(gudang_id);
                                     
                                     
                                },
                                 complete : function() {
                					Metronic.unblockUI();
            					}
                   
                       });

 						$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_paket_tindakan",  
                                data:  {transaksiid:$("#idtindakanpaket").val(),paket_id:$("#idtindakanpaketid").val(),tindakanhdpaketid:$("#tindakanhdpaketid").val()},  
                                dataType : 'json',
                                // beforeSend : function(){
                                //     Metronic.blockUI({boxed: true , message : 'Sedang Diproses'});
                                // },
                                success:function(data)          //on recieve of reply
                                { 
                                	var y=0;
                                    $("#table_edit_tindakan").find("tbody tr").remove();
                                    $("#table_edit_tindakan").find("tbody").append(data);

                                    $.each($("#table_edit_tindakan tbody tr"),function(){
                                    	y++;
                                    		  // handlebtnnumber($('input[name$="[user]"]', this),$('input[name$="[jatah]"]', this));
                                    		  handlebtnnumber5($(this),y);
                                    		  handlebtnnumber6($(this),y);
                                    		 

                                    });
                                       
                                      $.each($("#table_edit_tindakan tbody tr"),function(){
                              			$('input[name$="[user]"]',this).val(0);
                                        if($('input[name$="[sisa5]"]',this).val() > 0)
                                        {
                              			   $('div[name$="[sisa4]"]',this).html($('input[name$="[sisa5]"]',this).val());   
                                        }
                                        else
                                        {
                                            $('div[name$="[sisa4]"]',this).html(0);  
                                        }

                     				});
                                     
                                     
                                },
                 //                 complete : function() {
                	// 				Metronic.unblockUI();
            					// }
                   
                       });
        	 		
        	 }else if(this.value=='all'){
        	 	 	$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_paket_item",  
                                data:  {transaksiid:$("#idtindakanpaket").val(),paket_id:$("#idtindakanpaketid").val(),tindakanhdpaketid:$("#tindakanhdpaketid").val()},  
                                dataType : 'json',
                                  beforeSend : function(){
                                    Metronic.blockUI({boxed: true , message : 'Sedang Diproses'});
                                },
                                success:function(data)          //on recieve of reply
                                { 
                                	var y=0;
                                      $("#table_edit_paket").find("tbody tr").remove();
                                    $("#table_edit_paket").find("tbody").append(data);

                                    $.each($("#table_edit_paket tbody tr"),function(){
                                    	y++;
                                    		  // handlebtnnumber($('input[name$="[user]"]', this),$('input[name$="[jatah]"]', this));
                                    		  handlebtnnumber($(this),y);
                                    		  handlebtnnumber2($(this),y);
                                    		// alert(idx);

                                    });
                                       
                                      $.each($("#table_edit_paket tbody tr"),function(){
                             				$('input[name$="[user]"]',this).val(0);
                             				$('div[name$="[sisa]"]',this).html(0);   

                     				});

                                    var gudang_id = $('select#pilih_opsi_gudang').val();
                                    handleBtnIdentitas(gudang_id);
                                     
                                     
                                },
                                 complete : function() {
                					Metronic.unblockUI();
            					}
                   
                       });

 					$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_paket_tindakan",  
                                data:  {transaksiid:$("#idtindakanpaket").val(),paket_id:$("#idtindakanpaketid").val(),tindakanhdpaketid:$("#tindakanhdpaketid").val()},  
                                dataType : 'json',
                                //  beforeSend : function(){
                                //     Metronic.blockUI({boxed: true , message : 'Sedang Diproses'});
                                // },
                                success:function(data)          //on recieve of reply
                                { 
                                	var y=0;
                                     $("#table_edit_tindakan").find("tbody tr").remove();
                                    $("#table_edit_tindakan").find("tbody").append(data);

                                    $.each($("#table_edit_tindakan tbody tr"),function(){
                                    	y++;
                                    		  // handlebtnnumber($('input[name$="[user]"]', this),$('input[name$="[jatah]"]', this));
                                    		  handlebtnnumber5($(this),y);
                                    		  handlebtnnumber6($(this),y);
                                    		// alert(idx);

                                    });
                                       
                                      $.each($("#table_edit_tindakan tbody tr"),function(){
                             				$('input[name$="[user]"]',this).val($('input[name$="[sisa5]"]',this).val());
                             				$('div[name$="[sisa4]"]',this).html(0);   

                     				});                                     
                                     
                                },
                 //                 complete : function() {
                	// 				Metronic.unblockUI();
            					// }
                   
                       });
        	 	 	
        	 }else{
        	 		$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_paket_item2",  
                                data:  {transaksiid:$("#idtindakanpaket").val(),paket_id:$("#pilih_opsi").val(),tindakanhdpaketid:$("#tindakanhdpaketid").val()},  
                                dataType : 'json',
                                 beforeSend : function(){
                                    Metronic.blockUI({boxed: true , message : 'Sedang Diproses'});
                                },
                                success:function(data)          
                                { 
                                	var z=0;
                                    $("#table_edit_paket").find("tbody tr").remove();
                                    $("#table_edit_paket").find("tbody").append(data);

                                    $.each($("#table_edit_paket tbody tr"),function(){
                                    	z++;
                                    		  
                                    		  handlebtnnumber3($(this),z);
                                    		  handlebtnnumber4($(this),z);
                                    		 

                                    });

                                    $.each($("#table_edit_paket tbody tr"),function(){
                                        if($('input[name$="[sisa3]"]',this).val() > 0)
                                        {
                                            $('input[name$="[user]"]',this).val(0);                                            
                                        }
                                        else
                                        {
                                            $('input[name$="[user]"]',this).val(0);
                                        }
                                        $('div[name$="[sisa2]"]',this).html(0);   

                                    });

                                    var gudang_id = $('select#pilih_opsi_gudang').val();
                                    handleBtnIdentitas(gudang_id);                                     
                                     
                                },
                                 complete : function() {
                					Metronic.unblockUI();
            					}
                   
                       });

 					$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_paket_tindakan2",  
                                data:  {transaksiid:$("#idtindakanpaket").val(),paket_id:$("#pilih_opsi").val(),tindakanhdpaketid:$("#tindakanhdpaketid").val()},  
                                dataType : 'json',
                 //                beforeSend : function(){
                	// 				Metronic.blockUI({boxed: true });
            					// },
                                success:function(data)          
                                { 
                                	var z=0;
                                    $("#table_edit_tindakan").find("tbody tr").remove();
                                    $("#table_edit_tindakan").find("tbody").append(data);

                                    $.each($("#table_edit_tindakan tbody tr"),function(){
                                    	z++;
                                    		  
                                    		  handlebtnnumber7($(this),z);
                                    		  handlebtnnumber8($(this),z);
                                    		 

                                    });

                                    $.each($("#table_edit_tindakan tbody tr"),function(){
                                        
                                        if($('input[name$="[sisa6]"]',this).val() > 0)
                                        {
                                            $('input[name$="[user]"]',this).val($('input[name$="[sisa6]"]',this).val());                                            
                                        }
                                        else
                                        {
                                            $('input[name$="[user]"]',this).val(0);
                                        }
                                        $('div[name$="[sisa5]"]',this).html(0);   
                                             

                                    });
                                       
                                     
                                     
                                     
                                },
                 //                 complete : function() {
                	// 				Metronic.unblockUI();
            					// }
                   
                       });
        	 }
        	
        });

       
    };

    var handleValidation = function() {
        var error1   = $('.alert-danger', $("#modal_1"));
        var success1 = $('.alert-success', $("#modal_1"));

        $("#modal_1").validate({
            // class has-error disisipkan di form element dengan class col-*
            errorPlacement: function(error, element) {
                error.appendTo(element.closest('[class^="col"]'));
            },
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            // rules: {
            // buat rulenya di input tag
            // },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                Metronic.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                $(element).closest('[class^="col"]').addClass('has-error');
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control group
            }

            
        });
        
       
    }

    var handleBtnIdentitas = function(gudang_id){
        $btnIdentitas = $('a.btn-modal-identitas', $('#table_edit_paket'));

        $.each($btnIdentitas, function(idx, btnIdentitas){
            var item_id = $(this).data('item_id'),
                satuan_id = $(this).data('satuan'),
                row = $(this).data('row'),
                sisa = $(this).data('sisa');

            $(this).attr('href', baseAppUrl+'modal_inventori_identitas/'+gudang_id+'/'+item_id+'/'+satuan_id+'/'+row);
            if(sisa > 0)
            {
                $(this).removeAttr('disabled');
            }
            else
            {
                $(this).attr('disabled','disabled');
            }
        });
    }
</script>
<?if($flag==1){?>
<?
	 $btn_del    = '<div class="text-center"><button class="btn btn-sm red-intense del-this5" title="Hapus"><i class="fa fa-times"></i></button></div>';
		    $item_cols = array(
			    'item_code'   => '<input type="hidden" id="paket_id5_{0}" name="paket5[{0}][idpaket5]"><input type="hidden" id="paket_harga5_{0}" name="paket5[{0}][harga5]"><input type="text" id="paket_nama5_{0}" name="paket5[{0}][namapaket5]" class="form-control" readonly style="background-color: transparent;border: 0px solid;">',
			    'add'	=>'<a  class="btn blue tambahpakett55" name="tambahpaket" id="tambahpakett55" title="Cari" ><i class="fa fa-search"></i></a>',
			    'action'      => $btn_del,
			);

			// gabungkan $item_cols jadi string table row
			$item_row_template5 =  '<tr id="item_row5_{0}" class="table_item5"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';


?>
 
  
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Paket", $this->session->userdata("language"))?></span>
					</h4>
				</div>
				<input type="hidden" id="fg2" value="<?=$flag?>">

				<div class="modal-body">
					<span id="tpl_item_row5" class="hidden"><?=htmlentities($item_row_template5)?></span>
							<table class="table table-striped table-bordered table-hover" id="table_paket5">
							<thead>
							<tr role="row" class="heading">
									<th class="text-center" colspan="2"><?=translate("Paket", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
									 
								 
								</tr>
								</thead>
								<tbody>
								 
								</tbody>
								
							</table>
				</div>
				<div class="modal-footer">
					<a class="btn default modal_batal" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
					<a class="btn btn-primary modal_ok" id="confirm_save_tagpaket55" data-dismiss="modal"><?=translate('OK', $this->session->userdata('language'))?></a>
				</div>
		 
 
  
<div id="popover_item_content_tagpaket5566" style="display:none" >
<div class="portlet light">
				 
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("SEARCH PAKET", $this->session->userdata("language"))?></span>
							</div>
						</div>
					 
	<div class="portlet-body form">

					 <div class="form-body">

						 <table class="table table-striped table-bordered table-hover" id="table_obat5">
							<thead>
							<tr role="row" class="heading">
									<th class="text-center"><?=translate("Tipe Paket", $this->session->userdata("language"))?> </th>
									 
									<th class="text-center"><?=translate("Nama Paket", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
									 
									<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
								 
								</tr>
								</thead>
								<tbody>
								
								</tbody>
							</table>
			
				</div>
			
				</div>
			</div>	
</div>

<?}else if($flag==2){?>
<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Paket", $this->session->userdata("language"))?></span>
					</h4>
				</div>
				<div class="modal-body">
						<input type="hidden" id="fg1" value="<?=$flag?>">
				 <input type="hidden" id="idtindakanpaket" name="idtindakanpaket" value="<?=$id?>">
				 <input type="hidden" id="idtindakanpaketid" name="idtindakanpaketid" value="<?=$paket_id?>">
				  <input type="hidden" id="tindakanhdpaketid" name="tindakanhdpaketid" value="<?=$tindakanhdpaket?>">
 <div class="portlet">
		 
		<div class="portlet-body form">
			 
			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
						
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Nomor Transaksi :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<p class="form-control-static" id="tagihantransaksinumber2">
									  
								</p>	
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Nama Paket :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<p class="form-control-static" id="tagihanpaketname2">
									 <?=$nama_paket?>
								</p>
							</div>
						</div>
	
				</div>
			</div>
		 
		</div>
			<!-- END FORM-->
	</div>

	<div class="portlet">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Paket", $this->session->userdata("language"))?></span>
				</div>
		
			</div>
		</div>
		<div class="portlet-body form">
			 
			<div class="form-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="table_view_paket5">
					<thead>
					<tr role="row" class="heading">
						 
						<th><center><?=translate("Nomor",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Nama",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Jatah",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Digunakan",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Sisa",$this->session->userdata("language"))?></center></th>
							 
							
					</tr>
					</thead>
					<tbody>
					 
					</tbody>
					</table>

					<div class="form-group">						
						<div class="col-md-4">
						<?php
			                $jumlah_item = array(
			                    "name"			=> "jumlah_item",
			                    "id"			=> "jumlah_item",
								"size"			=> "5",
			                    "class"			=> "text",
								"readonly"		=> "readonly",
								"hidden"		=> "hidden",
			                    // "value"			=> $result_count
			                );
							echo form_input($jumlah_item);
						?>
						</div>
					</div>

				</div>
					
			</div>
			 
		</div>
		 
	</div>
 
				</div>
				<div class="modal-footer">
					<a class="btn default modal_batal2" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
					<a class="btn btn-primary modal_ok" data-dismiss="modal"><?=translate('OK', $this->session->userdata('language'))?></a>
				</div>
 
<?}else{?>
<?
	  $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	  $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<form id="modal_1">
		<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Paket", $this->session->userdata("language"))?></span>
					</h4>
				</div>
				<div class="modal-body bs-modal-lg">
						<input type="hidden" id="fg1" value="<?=$flag?>">
				 <input type="hidden" id="idtindakanpaket" name="idtindakanpaket" value="<?=$id?>">
				 <input type="hidden" id="idtindakanpaketid" name="idtindakanpaketid" value="<?=$paket_id?>">
				 <input type="hidden" id="tindakanhdpaketid" name="tindakanhdpaketid" value="<?=$tindakanhdpaket?>">
 <div class="portlet">
		 
		<div class="portlet-body form">
			 
			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
						<div class="alert alert-danger display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_danger?>
					    </div>
					    <div class="alert alert-success display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_success?>
					    </div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Nomor Transaksi :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<p class="form-control-static" id="tagihantransaksinumber2">
									  
								</p>	
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Nama Paket :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<p class="form-control-static" id="tagihanpaketname2">
									 <?=$nama_paket?>
								</p>
							</div>
						</div>
	
				</div>
			</div>
		 
		</div>
			<!-- END FORM-->
	</div>

	<div class="portlet">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Paket", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
                 	 <select id="pilih_opsi" name="pilih_opsi" class="form-control">
			 		 	<option value="clear"><?=translate("Clear",$this->session->userdata("language"))?></option>
			 		 	<option value="all"><?=translate("Use All Remain",$this->session->userdata("language"))?></option>
			 		 	<?php
                         	$result=$this->paket_batch2_m->get_by(array('paket_id'=>$paket_id,'is_active'=>1));
			 		 		foreach ($result as $row) {
			 		 	?>
			 		 		<option value="<?=$row->id?>"><?=translate($row->nama,$this->session->userdata("language"))?></option>
			 		 	<?}?>
			 		 </select>
                     <select id="pilih_opsi_gudang" name="pilih_opsi_gudang" class="form-control">
                        
                        <?php
                            $result_gudang=$this->gudang_m->get_by(array('cabang_id'=>$this->session->userdata('cabang_id'),'is_active'=>1));
                            foreach ($result_gudang as $row_gudang) {
                        ?>
                            <option value="<?=$row_gudang->id?>"><?=translate($row_gudang->nama,$this->session->userdata("language"))?></option>
                        <?}?>
                     </select>
				</div>
		</div>
		<div class="portlet-body form">

			<div class="form-body">
					<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Waktu :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-3">
								 <input type="text" id="tggl_tindakan" name="tggl_tindakan" class="form-control" value="<?=date('H:i')?>" required="required" placeholder="Waktu">
									  
								 
							</div>
					</div>
					<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-3">
								<?php
											$keterangan = array(
												"id"			=> "keterangan_tindakan",
												"name"			=> "keterangan_tindakan",
												"rows"			=> 3,
												"autofocus"			=> true,
												"class"			=> "form-control", 
												"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
												 
											);
											echo form_textarea($keterangan);
										?>
									  
								</p>	
							</div>
					</div>					
				
			 	<div class="row">
			 		<div class="col-md-12"></div>
			 	</div>
			 	<div class="row">
			 		<div class="col-md-12"></div>
			 	</div>
				<div class="table-responsive">
					 <table class="table table-striped table-bordered table-hover" id="table_edit_paket">
					<thead>
					
					<tr role="row" class="heading">
						 
							<th><center><?=translate("Kode",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Nama",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Jatah",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Digunakan",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Sisa",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Digunakan",$this->session->userdata("language"))?></center></th>
							
							 
							
					</tr>
					</thead>
					<tbody>
					 
					</tbody>
					</table>

					<div class="form-group">						
						<div class="col-md-4">
						<?php
			                $jumlah_item = array(
			                    "name"			=> "jumlah_item",
			                    "id"			=> "jumlah_item",
								"size"			=> "5",
			                    "class"			=> "text",
								"readonly"		=> "readonly",
								"hidden"		=> "hidden",
			                    // "value"			=> $result_count
			                );
							echo form_input($jumlah_item);
						?>
						</div>
					</div>

				</div>
					
			</div>
			 
		</div>

		<!--  ====== -->
		<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
				</div>
				 
		</div>
		<div class="portlet-body form">

			<div class="form-body">
					 			
				
			 	<div class="row">
			 		<div class="col-md-12"></div>
			 	</div>
			 	<div class="row">
			 		<div class="col-md-12"></div>
			 	</div>
				<div class="table-responsive">
					 <table class="table table-striped table-bordered table-hover" id="table_edit_tindakan">
					<thead>
					
					<tr role="row" class="heading">
						 
							<th><center><?=translate("Kode",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Nama",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Jatah",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Digunakan",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Sisa",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Digunakan",$this->session->userdata("language"))?></center></th>
							
							 
							
					</tr>
					</thead>
					<tbody>
					 
					</tbody>
					</table>

				 

				</div>
					
			</div>
			 
		</div>
		 
	</div>
 <?php
  $msg = translate("Apakah anda akan menambah?",$this->session->userdata("language"));
 ?>
				</div>
				<div class="modal-footer">
					<a class="btn default modal_batal3" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
					<a class="btn btn-primary modal_ok2" id="modal_ok3" data-confirm="<?=$msg?>" ><?=translate('OK', $this->session->userdata('language'))?></a>
				</div>
			</form>
<?}?>
 