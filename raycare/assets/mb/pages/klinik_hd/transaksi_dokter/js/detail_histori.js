mb.app.cabang = mb.app.cabang || {};
mb.app.cabang.add = mb.app.cabang.add || {};

(function(o){

    var 
        baseAppUrl              = '',
        $form = $('#form_add_item');

        $errorTop               = $('.alert-danger', $form),
        $successTop             = $('.alert-success', $form),
        $tableOrderItem         = $('#table_order_item22', $form),
        $tableOrderItem2         = $('#table_order_item23', $form),
        $tableOrderItem3        = $('#table_paket', $form),
        $tableSupplierSearch    = $('#table_supplier_search'),
        $tableItemSearch        = $('#table_item_search'),
        $popoverSupplierContent = $('#popover_supplier_content'), 
        $popoverItemContent     = $('#popover_item_content'), 
         $popoverItemContent5     = $('#popover_item_content5'), 
        $popoverItemContentpaket     = $('#popover_item_content_paket'), 
        $btnSearchSupplier      = $('.search-supplier', $form),
        $btnAddItem             = $('.add-item'),
        $lastPopoverBed = null,
        $lastPopoverItem        = null,
        theadFilterTemplate     = $('#thead-filter-template').text(),
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        tplItemRow2              = $.validator.format( $('#tpl_item_row2').text() ),
        tplItemRow3              = $.validator.format( $('#tpl_item_row3').text() ),
        itemCounter             = 1,
        itemCounter2             = 1,
         itemCounter3            = 1,
        tplFormPayment = '<li class="fieldset">' + $('#tpl-form-payment', $form).val() + '<hr></li>',
        tplFormPayment2 = '<li class="fieldset">' + $('#tpl-form-payment2', $form).val() + '<hr></li>',
        regExpTpl        = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        paymentCounter     = 1,
        paymentCounter2     = 1,
        paymentCounter3     = 1,
        jml=1,
        x=0,
        y=0
        ;

    var forms = {
        'payment' : {            
            section  : $('#section-payment', $form),
            template : $.validator.format( tplFormPayment.replace(regExpTpl, '_ID_{0}') ), //ubah ke format template jquery validator
            counter  : function(){ paymentCounter++; return paymentCounter-1; }
        }      
    };

    var forms2 = {
        'upload' : {            
            section  : $('#section-payment2', $form),
            template : $.validator.format( tplFormPayment2.replace(regExpTpl, '_ID_{0}') ), //ubah ke format template jquery validator
            counter  : function(){ paymentCounter2++; return paymentCounter2-1; }
        }      
    };

    var handleValidation = function() {
        var error1   = $('.alert-danger', $form);
        var success1 = $('.alert-success', $form);

        $form.validate({
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
    var handleConfirmSave = function(){
        $('a#confirm_save', $("#modaltindakan")).click(function() {
            if (! $("#modaltindakan").valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                  //  alert($('input[name$="kel"]:checked', $("#modaltindakan")).val());
                  // alert($("#nama", $("#modaltindakan")).val() + '/' + $("#jenisdokumen", $("#modaltindakan")).val() + '/' + $("#namadokumen", $("#modaltindakan")).val() + '/' + $("#nodokumen", $("#modaltindakan")).val() + '/' + $('input:radio[name="kel"]', $("#modaltindakan")).val() + '/' + $("#date", $("#modaltindakan")).val() + '/' + $("#uploadfilename", $("#modaltindakan")).val());
                    $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "adddokumen",  
                                data:  {nama:$("#nama", $("#modaltindakan")).val(),jenisdokumen:$("#jenisdokumen", $("#modaltindakan")).val(),namadokumen:$("#namadokumen", $("#modaltindakan")).val(),nodokumen:$("#nodokumen", $("#modaltindakan")).val(),tipedokumen:$('input[name$="kel"]:checked', $("#modaltindakan")).val(),tggl:$("#date", $("#modaltindakan")).val(),url:$("#uploadfilename", $("#modaltindakan")).val(),pasienid:$("#pasienid").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                   $('#ajax_notes').modal('toggle');
                                   oTable3.api().ajax.reload();
                                
                                }
                   
                       });
                }
            });
        });
    };
    var handleConfirmcreatetindakan = function(){
        $('a#confirm_save_tindakan', $("#form_add_cabang")).click(function() {
            if (! $("#form_add_cabang").valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $("#savetindakan").click();
                  //  alert($('input[name$="kel"]:checked', $("#modaltindakan")).val());
                  // alert($("#nama", $("#modaltindakan")).val() + '/' + $("#jenisdokumen", $("#modaltindakan")).val() + '/' + $("#namadokumen", $("#modaltindakan")).val() + '/' + $("#nodokumen", $("#modaltindakan")).val() + '/' + $('input:radio[name="kel"]', $("#modaltindakan")).val() + '/' + $("#date", $("#modaltindakan")).val() + '/' + $("#uploadfilename", $("#modaltindakan")).val());
                  
                }
            });
        });
    };

var handleConfirmSave3 = function(){
        $('a#confirm_save3', $("#modaltindakan")).click(function() {
            if (! $("#modaltindakan").valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                  
                    $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "updatedokumen",  
                                data:  {tggl:$("#date2", $("#modaltindakan")).val(),url:$("#uploadfilename2", $("#modaltindakan")).val(),pasienid:$("#pasienid").val(),id:$("#id", $("#modaltindakan")).val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                   $('#ajax_notes3').modal('toggle');
                                   oTable3.api().ajax.reload();
                                
                                }
                   
                       });
                }
            });
        });
    };

    var handleConfirmSave4 = function(){
        $('a#confirmsave',$("#modalpic")).click(function() {
           if (! $("#modalpic").valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                 
                  // $("#saveobservasi").click();
 
                    $.ajax
                        ({ 
         

                                type: 'POST',
                                url: baseAppUrl +  "updateobservasi",  
                                data:  {jam:$("#jam").val(),tda:$("#tda3").val(),tdb:$("#tdb3").val(),ufg:$("#ufg").val(),ufr:$("#ufr").val(),ufv:$("#ufv").val(),qb:$("#qb").val(),userid:$("#userid1").val(),keterangan:$("#keterangan").val(),id_observasi:$("#observasiid").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                     
                                 //  mb.showMessage(data[0],data[1],data[2]);
                                   // $("#asses2").show();
                                   // $("#asses1").hide();

                                   oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + $("#tindakanhdid").val()).load();
                                   $('#ajax_notes4').modal('toggle');  
                                }
                   
                       });
                   
                }
            });
        });
    };
    var handleAddDokumen = function(){
        $('a#adddoc').click(function() {
           // if (! $("#modaltindakan").valid()) return;
           $("#nama", $("#modaltindakan")).val('');
           $("#namadokumen", $("#modaltindakan")).val('');
           $("#nodokumen", $("#modaltindakan")).val('');
           $('#uploadchoosen_file_1').html('');
            $("#uploadfilename", $("#modaltindakan")).val('');
            
        });
    };
 
    var handleRadiobutn = function(){
        $('input[name="kel"]', $form).click(function() {
   
        if(this.value==1){
           // $('li#grup', $form).show();
           // $('li#grup', $form).fadeIn();
        $( "li#grup" ).fadeIn( "slow", function() {
    // Animation complete
        });
        $( "#url1" ).fadeOut( "slow", function() {
    // Animation complete
        });
        }else{
           // $('li#grup', $form).hide();
            $( "li#grup" ).fadeOut( "slow", function() {
     
            });
              $( "#url1" ).fadeIn( "slow", function() {
    // Animation complete
            });
        }
            
        });
    };

     var handlecheckbutn = function(){
        $('input[name="susp"]', $form).click(function() {
        if(x==0){
            $("#suspval").val(this.value);
            x=1;
        }else{
            $("#suspval").val(0);
            
            x=0;
        }
     
        });
    };

     var handlecheckall = function(){
        $('input[name="checkall"]', $form).click(function() {
         if(y==0){
           $.each($("#table_kelompok_penjamin"), function(){
            // handleBtnDeleteItem($(btn));
                 $('input[name="check[]"]', this).prop('checked', true);
         });
            y=1;
        }else{
        $.each($("#table_kelompok_penjamin"), function(){
            // handleBtnDeleteItem($(btn));
                 $('input[name="check[]"]', this).prop('checked', false);
         });
            
            y=0;
        }
        
        });
    };

    var handletabbutn1 = function(){
        $('#tab1').click(function() {
           // $("table_dokumen_pelengkap2").hide();
           // oTable.ajax.url(baseAppUrl + 'listing4').load()
        });
    };
      var handletabbutn2 = function(){
        $('#tab2').click(function() {
         // $("table_dokumen_pelengkap1").hide();
           
           // oTable.ajax.url(baseAppUrl + 'listing4').load()
            
        });
    };

     var handlehistoribtn = function(){
        $('#histori').click(function() {
         
            $("#act2").show();
        $("#act1").hide();
          oTable4.api().ajax.url(baseAppUrl + 'listing4/1/2/' + $("#pasienid").val()).load();
          oTable5.api().ajax.url(baseAppUrl + 'listing4/2/2/' + $("#pasienid").val()).load();
            
        });
    };
    var handlehistoribtn2 = function(){
        $('#kembali').click(function() {
         
        $("#act2").hide();
        $("#act1").show();
          oTable4.api().ajax.url(baseAppUrl + 'listing4/1/1/' + $("#pasienid").val()).load();
          oTable5.api().ajax.url(baseAppUrl + 'listing4/2/1/' + $("#pasienid").val()).load();
            
        });
    };

    var handleRefresh = function(){
        $('#refrsh').click(function() {
         
        
          oTableRujukan.api().ajax.url(baseAppUrl + 'listing_rujukan/' + $("#pasienid").val()).load();
          
            
        });
    };

 var handlebackbtn = function(){
        $('#backbtn').click(function() {
         
        
          // $("#asses2").show();
          //  $("#asses1").hide();
          $("#ajax_notes4").modal('toggle');
            
        });
    };

     var handlebackbtn2 = function(){
        $('#backbtn2').click(function() {
         
        
          $("#asses2").show();
         // $("#asses1").hide();
        $("#asses3").hide();
            
        });
    };

    var handlebtnpage = function(){
     
        $('#page2').click(function() {

            if(!$("#flag2").val())
            {
                $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getsejarah",  
                                data:  {pasien_id:$("#pasienid").val(),flag:2 },  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 

                                oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + + data.tindakan_hd_id).load();
                                oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + data.tindakan_hd_id).load();
                                oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + data.tindakan_hd_id).load();
                                oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + data.tindakan_hd_id).load();  
                                  $("#tgglpage").html(data.date_value); 
                                  $("#tgglpage2").val(data.date_value); 
                                  $("#tindakanhdid").val(data.tindakan_hd_id);    
                                  handleisidata(data);
                                  
                                
                                }
                   
                       });
            }
           
          
            
        });
    };

    var handleFirst = function(){
     
        $('#first1').click(function() {
         
        
          $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getpage/first",  
                                data:  {pasien_id:$("#pasienid").val(),tanggal:$("#tgglpage2").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                     oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + + data.tindakan_hd_id).load();
                                      oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + data.tindakan_hd_id).load();
                                       oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + data.tindakan_hd_id).load();
                                      oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + data.tindakan_hd_id).load();
                                     $("#tgglpage").html(data.date_value); 
                                     $("#tgglpage2").val(data.date_value); 
                                     $("#tindakanhdid").val(data.tindakan_hd_id);  
                                     handleisidata(data);
                                    
                                 // handleisidata(data);
                                
                                }
                   
                       });
           $('#first1').attr('disabled','disabled');
             $('#last1').removeAttr('disabled');
        });
    };
    var handlePrev = function(){
        $('#prev').click(function() {
         
        
          $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getpage/prev",  
                                data:  {pasien_id:$("#pasienid").val(),tanggal:$("#tgglpage2").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                     oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + + data.tindakan_hd_id).load();
                                      oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + data.tindakan_hd_id).load();
                                       oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + data.tindakan_hd_id).load();
                                     oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + data.tindakan_hd_id).load();
                                    $("#tgglpage").html(data.date_value); 
                                    $("#tgglpage2").val(data.date_value);   
                                    $("#tindakanhdid").val(data.tindakan_hd_id);
                                     handleisidata(data);
                                    
                                
                                }
                   
                       });
          $('#last1').removeAttr('disabled');
            
        });
    };
    var handleNext = function(){
        $('#next').click(function() {
         
         $found=false;
          $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getpage/next",  
                                data:  {pasien_id:$("#pasienid").val(),tanggal:$("#tgglpage2").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 

                                     if(!data){
                                         $found=true;
                                          alert('kjjjj44');
                                    }
                                     oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + + data.tindakan_hd_id).load();
                                      oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + data.tindakan_hd_id).load();
                                       oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + data.tindakan_hd_id).load();
                                      oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + data.tindakan_hd_id).load();
                                     $("#tgglpage").html(data.date_value); 
                                     $("#tgglpage2").val(data.date_value);   
                                     $("#tindakanhdid").val(data.tindakan_hd_id);
                                     handleisidata(data);
                                    
                                   
                                
                                }
                   
                       });
          $('#first1').removeAttr('disabled');
          if(found==true){
           // alert('kjjjj');
            $('#last1').attr('disabled','disabled');
          }
            
        });
    };
    var handleLast = function(){
        $('#last1').click(function() {
         
        
         $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getpage/last",  
                                data:  {pasien_id:$("#pasienid").val(),tanggal:$("#tgglpage2").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                     oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + + data.tindakan_hd_id).load();
                                      oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + data.tindakan_hd_id).load();
                                       oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + data.tindakan_hd_id).load();
                                      oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + data.tindakan_hd_id).load();
                                     $("#tgglpage").html(data.date_value); 
                                     $("#tgglpage2").val(data.date_value);   
                                     $("#tindakanhdid").val(data.tindakan_hd_id);
                                     handleisidata(data);
                                 // handleisidata(data);
                                 


                                
                                }
                   
                       });
            $('#last1').attr('disabled','disabled');
            $('#first1').removeAttr('disabled');
          
            
        });
    };
    var initForm2 = function(){
     
        // handle button add row item
        //  $('input[name="kel"]', $form).on('click', function(e){
        //     addItemRow();
        //     alert('asdada');
        //     e.preventDefault();
        // });        

        // var 
            
        //     $btnsDelete     = $('.del-this', $tableOrderItem);

       
        // $.each($btnsDelete, function(idx, btn){
        //     handleBtnDeleteItem($(btn));

        // });

        // tambah 1 row order item kosong
        addItemRow();
    };

     var addItemRow = function(){
        var numRow = $('tbody tr', $tableOrderItem).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableOrderItem),
            $newItemRow    = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem     = $('.search-item', $newItemRow);
            $btnDelete     = $('.del-this', $newItemRow);
  
        handleBtnSearchItem($btnSearchItem);
        
        handleBtnDeleteItem($btnDelete);
         
       // handleCheck($checkMultiply);
        
    };

    var addItemRow2 = function(){
        var numRow = $('tbody tr', $tableOrderItem2).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableOrderItem2),
            $newItemRow    = $(tplItemRow2(itemCounter2++)).appendTo( $rowContainer ),
            $btnDelete     = $('.del-this2', $newItemRow);
  
      //  handleBtnSearchItem($btnSearchItem);
       
        handleBtnDeleteItem2($btnDelete);
       
       // handleCheck($checkMultiply);
        
    };

     var addItemRow3 = function(){
        var numRow = $('tbody tr', $tableOrderItem3).length;
 
        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableOrderItem3),
            $newItemRow    = $(tplItemRow3(itemCounter3++)).appendTo( $rowContainer ),
            $btnDelete     = $('.del-this3', $newItemRow);
             $btnSearchItem     = $('.tambahpkt', $newItemRow);

   handleBtnSearchPaket($btnSearchItem);
      //  handleBtnSearchItem($btnSearchItem);
       
       handleBtnDeleteItem3($btnDelete);
       
       // handleCheck($checkMultiply);
        
    };

    var handleBtnSearchItem = function($btn){
 
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){
          
            var $popContainer = $(this).data('bs.popover').tip();
          // var maxWidth = parseInt(0.9*$('#form_add_cabang').width());
             var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            // $popContainer.css({minWidth: '720px', maxWidth: '720px'});

           if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

           // $popoverItemContent.show();

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popContent   = $popContainer.find('.popover-content');

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
            // $('input:hidden[name="itemTarget"]', $popcontent).val(target);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);
            $popoverItemContent.show();
        }).on('hide.bs.popover', function(){
          
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
           
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            
            e.preventDefault();
        });
    };   

     var handleBtnSearchSyarat = function(){
 
       // var rowId  = $("#addsyarat").closest('tr').prop('id');
        // console.log(rowId);
      
        $("#addsyarat").popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){
           oTable.api().ajax.reload();
            var $popContainer = $(this).data('bs.popover').tip();
          // var maxWidth = parseInt(0.9*$('#form_add_cabang').width());
             var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            // $popContainer.css({minWidth: '720px', maxWidth: '720px'});

           // if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

           //  $lastPopoverItem = $btn;

           // $popoverItemContent.show();

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip();
              //  $popContent   = $popContainer.find('.popover-content');

          
         //   $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
          
            
            
            $popContainer.find('.popover-content').append($popoverItemContent5);
            $popoverItemContent5.show();
        }).on('hide.bs.popover', function(){
          
            //pindahkan kembali $popoverItemContent ke .page-content
          //  $popoverItemContent5.hide();
         //   $popoverItemContent5.appendTo($('.page-content'));

          //  $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
           
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            
            e.preventDefault();
        });
    };   


    var handleBtnSearchPaket = function($btn){
  
        //var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId2"/>'

        }).on("show.bs.popover", function(){
            oTablePaket.api().ajax.reload();
            var $popContainer = $(this).data('bs.popover').tip();
          // var maxWidth = parseInt(0.9*$('#form_add_cabang').width());
             var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            

            // if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            //  $lastPopoverItem = $("#tambahpaket");

        

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip();
                // $popContent   = $popContainer.find('.popover-content');

           
            // $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
            
            
            $popContainer.find('.popover-content').append($popoverItemContentpaket);
            $popoverItemContentpaket.show();
        }).on('hide.bs.popover', function(){
          
            
            // $popoverItemContentpaket.hide();
            // $popoverItemContentpaket.appendTo($('.page-content'));

           // $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
           
          e.preventDefault();
        });
    };   
     var handleDTItems = function(){
 
       $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
              'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
      //   oTabelItem = $tableItemSearch.dataTable({
      //       'bProcessing'              : true,
      //       'bServerSide'              : true,
      //       'sServerMethod'            : 'POST',
      //       'oLanguage'                : mb.DTLanguage(),
      //       'sAjaxSource'              : baseAppUrl + 'listing_item',
      //       'iDisplayLength'           : 10,
      //       'aLengthMenu'              : [[2, 5, 10, 25, 50, 100], [2, 5, 10, 25, 50, 100]],
      //       'aaSorting'                : [[0, 'asc']],
      //       'aoColumns'                : [
      //           { 'bVisible' : true, 'bSearchable': false, 'bSortable': false },
      //           { 'bVisible' : true, 'bSearchable': true, 'bSortable': true },
      //           { 'bVisible' : true, 'bSearchable': true, 'bSortable': true },
                
      //           { 'bVisible' : true, 'bSearchable': false, 'bSortable': false },
      //           ]
      //   });
      //   $('#table_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
      //   $('#table_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

      // //  handleTableItemSearchSelectBtn();

        $tableItemSearch.on('draw.dt', function (){
             handleTableItemSearchSelectBtn();
         } );

      //   //sembunyikan #popover_item_content
        $popoverItemContent.hide();
    }; 

    var handleTableItemSearchSelectBtn = function(table,tipe){
            // console.log($btn.length);
        var $btnsSelect = $('a.select', table);

        $.each($btnsSelect, function(idx, btn){

            $(btn).on('click', function(e){
                
                var 
                    $parentPop   = $(this).parents('.popover').eq(0),
                    rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                    $row         = $('#'+rowId, $tableOrderItem),
                    $rowClass    = $('.table_item', $tableOrderItem),
                    $itemIdEl    = $('input[name$="[tindakan_id]"]', $row),
                    ItemIdAll    = $('input[name$="[tindakan_id]"]', $rowClass),
                    $itemCodeEl  = $('input[name$="[code]"]', $row),
                    $itemNameEl  = $('input[name$="[nama]"]', $row), 
                    $itemStock   = $('input[name$="[tipe]"]', $row), 
                 
                    itemId       = $(this).data('item').id
                    ;                
                
                               
                // console.log($itemIdEl);
                
                found = false;
                $.each(ItemIdAll,function(idx, value){
                    // alert(itemId);
                    if(itemId == this.value)
                    {
                        found = true;
                    }
                });
                
                if(found == false)
                {
                  //  alert('llll');
                    $itemIdEl.val($(this).data('item').id);
                    if(tipe=='obat'){
                         $itemCodeEl.val($(this).data('item').kode);
                    }
                   
                    $itemNameEl.val($(this).data('item').nama);
                    $itemStock.val(tipe);
                     $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl+ 'getsatuanobat' ,  
                                data:  {id:$(this).data('item').id},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                   //   $('#accept', $form).click();
                                   // mb.showMessage(data[0],data[1],data[2])
                                  
                                   $.each(data, function(idx, key){
                                        $('select[name$="[satuan]"]', $row).append($("<option></option>").attr("value",key.id).text(key.nama));
                                    });
                                }
                   
                        });
                    // $itemStock.val($(this).data('item').stock);
                    // $itemQtyEl.val($(this).data('item').minimum_order);
                    // $itemUnitEl.text($(this).data('item').packaging);
      
                   // $divMultiple.text($(this).data('item').kelipatan_order+'/'+$(this).data('item').minimum_order);
                  // $("#popover_item_content").popover('hide');
                    //$('.search-item', $tableOrderItem).popover('hide');
                    // $('.search-item', $tableOrderItem).popover().on('click', function() {
                    //     alert('hi');
                    //      $(this).popover('hide');
 
                    // });
                $('input[name$="[tindakan_id2]"]', $row).val('');
                   // addItemRow();
                }
                $('.search-item', $tableOrderItem).popover('hide');
                 if(found == false){
                    if($row.closest("tr").is(":last-child")) 
                    {
                         addItemRow();
                    }
                   // addItemRow();
                }
                 e.preventDefault();   
            });     
        });
        
    };

     var handleTableItemSearchSelectBtn2 = function(table,tipe){
            // console.log($btn.length);
        var $btnsSelect = $('a.select2', table);

        $.each($btnsSelect, function(idx, btn){

            $(btn).on('click', function(e){
                 
                var 
                    $parentPop   = $(this).parents('.popover').eq(0),
                    rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                    $row         = $('#'+rowId, $tableOrderItem),
                    $rowClass    = $('.table_item', $tableOrderItem),
                    $itemIdEl    = $('input[name$="[tindakan_id2]"]', $row),
                    ItemIdAll    = $('input[name$="[tindakan_id2]"]', $rowClass),
                    ItemIdAll2    = $('input[name$="[tipe]"]', $rowClass),
                    $itemCodeEl  = $('input[name$="[code]"]', $row),
                    $itemNameEl  = $('input[name$="[nama]"]', $row), 
                    $itemStock   = $('input[name$="[tipe]"]', $row), 
                 
                    itemId       = $(this).data('item').id
                    ;                
                
                               
                // console.log($itemIdEl);
                
                found = false;
                found2 = false;
                $.each(ItemIdAll,function(idx, value){
                    // alert(itemId);
                    if(itemId == this.value )
                    {
                        found = true;
                    }
                });

             //    if(found == true){
             //     $.each(ItemIdAll2,function(idx, value){
             //        // alert(itemId);
             //        if(itemId == this.value)
             //        {
             //            found = true;
             //        }
             //    });
             // }
                
                if(found == false)
                {
                  //  alert('llll');
                    $itemIdEl.val($(this).data('item').id);
                    if(tipe=='obat'){
                         $itemCodeEl.val($(this).data('item').kode);
                    }
                   
                    $itemNameEl.val($(this).data('item').nama);
                    $itemStock.val(tipe);
                    $('div[name$="[div]"]', $row).html("-");
                    
                    // $itemStock.val($(this).data('item').stock);
                    // $itemQtyEl.val($(this).data('item').minimum_order);
                    // $itemUnitEl.text($(this).data('item').packaging);
                     $('input[name$="[tindakan_id]"]', $row).val('');
                   // $divMultiple.text($(this).data('item').kelipatan_order+'/'+$(this).data('item').minimum_order);
                  // $("#popover_item_content").popover('hide');
                    //$('.search-item', $tableOrderItem).popover('hide');
                    // $('.search-item', $tableOrderItem).popover().on('click', function() {
                    //     alert('hi');
                    //      $(this).popover('hide');
 
                    // });
 
                   // addItemRow();
                }
                $('.search-item', $tableOrderItem).popover('hide');
                 if(found == false){
                    if($row.closest("tr").is(":last-child")) 
                    {
                         addItemRow();
                    }
                   // addItemRow();
                }
                 e.preventDefault();   
            });     
        });
        
    };

     var handleBtnDeleteItem = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem);

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableOrderItem).length == 0){
                        addItemRow();
                    }
            //     }
            // });

            e.preventDefault();
        });

    };

     var handleBtnDeleteItem2 = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem2);

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableOrderItem2).length == 0){
                        addItemRow2();
                    }
            //     }
            // });

            e.preventDefault();
        });

    };

    var handleBtnDeleteItem3 = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem3);

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableOrderItem3).length == 0){
                        addItemRow3();
                    }
            //     }
            // });

            e.preventDefault();
        });

    };

    var initForm = function(){
//alert($("#id").val()+'/'+$("#tggl").val());
        $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getsejarah2",  
                                data:  {id:$("#id").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
 
                                  oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + + data.tindakan_hd_id).load();
                                  oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + data.tindakan_hd_id).load();
                                  oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + data.tindakan_hd_id).load();
                                  oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + data.tindakan_hd_id).load();  
                                  
                                   
                                    
                                      handleisidata(data);
                                  
                                  
                                  
                                
                                }
                   
                       });
    };

     var addFieldset = function(form){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            result=0,
            result2=0,
            result3=0;
           // alert(counter);

      //s  handleDatePicker();
        $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
           
            handleSelectSection(this.value, $newFieldset);
        });
        $('a.del-this', $newFieldset).on('click', function(){
            
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });
 
         $('a[name$="[addrow]"]', $newFieldset).on('click', function(){
       
         addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
           // handleSelectSection(this.value, $newFieldset);
        });
         addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
        //jelasin warna hr pemisah antar fieldset

        $('input[name$="[idcount]"]', $newFieldset).val(counter);
        $('hr', $newFieldset).css('border-color', 'silver');
    };

     var addFieldset3 = function(form,tipe,judul,max,id){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            result=0,
            result2=0,
            result3=0;
           // alert(counter);

      //s  handleDatePicker();
      //  $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
           
        handleSelectSection(tipe, $newFieldset);
        $('select[name$="[payment_type]"]', $newFieldset).val(tipe);
        $('input[name$="[judul]"]', $newFieldset).val(judul);
        $('input[name$="[text]"]', $newFieldset).val(max);
        $('input[name$="[idsyarat]"]', $newFieldset).val(id);
       // });
        $('a.del-this', $newFieldset).on('click', function(){
            
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });
 
         $('a[name$="[addrow]"]', $newFieldset).on('click', function(){
       
         addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
           // handleSelectSection(this.value, $newFieldset);
        });

         $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "syaratdetail",  
                                data:  {id:id},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                  $.each(data, function(key, value) {
                                         addItemRow2($('table[name$="[table_order_item22]"]', $newFieldset),counter,value.text,value.value);
                                    });
                                
                                }
                   
                       });
         //addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
        //jelasin warna hr pemisah antar fieldset

        $('input[name$="[idcount]"]', $newFieldset).val(counter);

         $('select[name$="[payment_type]"]', $newFieldset).attr('disabled','disabled');
         $('input[name$="[judul]"]', $newFieldset).attr('disabled','disabled');
         $('input[name$="[text]"]', $newFieldset).attr('disabled','disabled');
         $('a[name$="[addrow]"]', $newFieldset).attr('disabled','disabled');
        $('hr', $newFieldset).css('border-color', 'silver');
    };

     var addFieldset2 = function(form){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer)
            ;
           // alert(counter);

      //s  handleDatePicker();
        uploadfile($newFieldset);
        $('a.del-this2', $newFieldset).on('click', function(){
            
            handleDeleteFieldset2($(this).parents('.fieldset').eq(0));
        });
 
        //  $('a[name$="[addrow]"]', $newFieldset).on('click', function(){
        //  //  alert('hiiii222');
        //  addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
        //    // handleSelectSection(this.value, $newFieldset);
        // });
        //  addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
        // //jelasin warna hr pemisah antar fieldset

        // $('input[name$="[idcount]"]', $newFieldset).val(counter);
        $('hr', $newFieldset).css('border-color', 'silver');
    };

     var handleDeleteFieldset = function($fieldset){
      
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset', $parentUl).length,
            hasId         = false,  //punya id tidak, jika tidak bearti data baru
            hasDefault    = 0,      //ada tidaknya fieldset yang di set sebagai default, diset ke 0 dulu
            $inputDefault = $('input:hidden[name$="[is_default]"]', $fieldset), 
            isDefault     = $inputDefault.val() == 1
            

        if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.

        $fieldset.remove();


  
    };

    var handleDeleteFieldset2 = function($fieldset){
      
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset', $parentUl).length,
            hasId         = false,  //punya id tidak, jika tidak bearti data baru
            hasDefault    = 0,      //ada tidaknya fieldset yang di set sebagai default, diset ke 0 dulu
            $inputDefault = $('input:hidden[name$="[is_default]"]', $fieldset), 
            isDefault     = $inputDefault.val() == 1
            

        if (fieldsetCount<=1) 
        {
             $('label[name$="[choosen_file_1]"]', $fieldset).html("");
             $('div[name$="[choosen_file_container_1]"]', $fieldset).hide();
             $('input[name$="[filename]"]', $fieldset).val('');
             return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
        }
        $fieldset.remove();
  
    };

     var handleSelectSection = function(value,$fieldset)
    {
        if(value == 1 || value==2)
        {
            $('div#section_1', $fieldset).show();
            $('div#section_2', $fieldset).hide();
            $('div#section_3', $fieldset).hide();
        }
        if(value == 4 || value==5 || value==6 || value==7)
        {
            $('div#section_1', $fieldset).hide();
            $('div#section_2', $fieldset).show();
            $('div#section_3', $fieldset).hide();
            
        }
        if(value == 3)
        {
            $('div#section_3', $fieldset).hide();
            $('div#section_2', $fieldset).hide();
            $('div#section_1', $fieldset).hide();
            
        }
    }

    var handleDTItems2 = function(){
 
       $("#table_cabang").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing2' + '/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
               
                ]
        });
     
    }; 

     var handleDTItems3 = function(){
  
       oTable3=$("#table_cabang4").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing3/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
              'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
               
                ]
        });

        $("#table_cabang4").on('draw.dt', function (){
              
              $('a[name="viewpic[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        
                        $('#gambar', $("#modalpic")).html("<img src="+ mb.baseUrl() + id + " style='border: 1px solid #000; max-width:500px; max-height:500px;'>");
                         
              });  

               $('a[name="update[]"]', this).click(function(){
                $("#id").val($(this).data('item').id);
                  $("#namadokumen2").html($(this).data('item').subjek);
                  if($(this).data('item').jenis==1){
                     $("#jenisdokumen2").html("Dokumen Pelengkap");
                  }else{
                    $("#jenisdokumen2").html("Dokumen Rekam Medis");
                  }
                 // $("#jenisdokumen2").html($(this).data('item').jenis);
                  if($(this).data('item').tipe==2){
                     $("#kel22").html("Gambar");
                  }else{
                    $("#kel22").html("File");
                  }
                 
                  $("#nodokumen2").html($(this).data('item').no_dokumen);
                  $("#uploadchoosen_file_container_12").show();

                   var paramsArray = $(this).data('item').url_file.split('.');
                                param1 = paramsArray[0]; 
                                param2 = paramsArray[1]; 
                   var paramsArray2 = $(this).data('item').url_file.split('/');
                                param3 = paramsArray2[paramsArray2.length-1]; 
                                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                                {
                                    $('#uploadchoosen_file_12').html("<img src="+ mb.baseUrl() + $(this).data('item').url_file + " style='border: 1px solid #000; max-width:200px'>");
                                }else{
                                    $('#uploadchoosen_file_12').html(param3);
                                }
                  
              });  
       
          });

        
        
     
    }; 

    var handleDTItems4 = function(){
 
       oTable4=$("#table_dokumen_pelengkap1").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing4/1/1/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
              'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
               
                ]
        });

        $("#table_dokumen_pelengkap1").on('draw.dt', function (){
              
              $('a[name="viewpic[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        
                        $('#gambar', $("#modalpic")).html("<img src="+ mb.baseUrl() + id + " style='border: 1px solid #000; max-width:500px; max-height:500px;'>");
                         
              });  
       
          });

        oTable5=$("#table_dokumen_pelengkap2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing4/2/1',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
              'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
               
                ]
        });

        $("#table_dokumen_pelengkap2").on('draw.dt', function (){
              
              $('a[name="viewpic[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        
                        $('#gambar', $("#modalpic")).html("<img src="+ mb.baseUrl() + id + " style='border: 1px solid #000; max-width:500px; max-height:500px;'>");
                         
              });  
       
          });
     
    }; 

  //    var handleDeleteRow = function(id){

  //   bootbox.confirm(msg, function(result) {
  //     if(result==true) {
        
  //       $.ajax
  //                       ({ 
         
  //                               type: 'POST',
  //                               url: baseAppUrl +  "deleteajax",  
  //                               data:  {id:id},  
  //                               dataType : 'json',
  //                               success:function(data)          //on recieve of reply
  //                               { 
                                     
  //                                   mb.showMessage(data[0],data[1],data[2]);
  //                                   oTable3.api().ajax.reload();
                      
  //                               }
                   
  //                      });
  //                       // oTable.ajax.url(baseAppUrl + 'listing').load();
                        
  //     } 
  //   });
  
  // };

    var handleDTsyarat = function(){
 
      oTable = $("#table_syarat2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_syarat',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
              'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': false, 'orderable': true },
                 { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ],
             'createdRow'             : function( row, data, dataIndex ) {
                $('a[name="pilih[]"]', row).click(function(){
                    var $anchor = $(this),
                      id    = $anchor.data('id');
                      tipe    = $anchor.data('tipe');
                      judul    = $anchor.data('judul');
                      max    = $anchor.data('max');
                    // alert(max);
                     $.each(forms, function(idx, form){
 
                             addFieldset3(form,tipe,judul,max,id);
                               
                        });
                });
                                            //     handleBtnSearchItem($('.search-item', row));
                                            // handleDelete($('a[name="delete[]"]', row));
                 }
        });
     
     
    }; 

 var handleObat = function(){
 
      oTableobat=$("#table_obat").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_obat' + '/' + $("#kategori").val(),
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
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ],
             
        });
        $("#table_obat").on('draw.dt', function (){
             handleTableItemSearchSelectBtn($("#table_obat"),'obat');
         } );
     
    }; 

    var handleRacikan = function(){
 
     $("#table_racikan2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_racikan',
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
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ],
             
        });
     
         $("#table_racikan2").on('draw.dt', function (){
             handleTableItemSearchSelectBtn2($("#table_racikan2"),'racikan');
         } );
    }; 

 var handleKlaim = function(){
  
      oTableklaim=$("#table_klaim1").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_klaim' + '/' + $("#pasienid").val(),
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
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 { 'visible' : true, 'searchable': false, 'orderable': false },
                ],
             
        });
         
     
    }; 

    var handleRujukan = function(){
  
      oTableRujukan=$("#table_rujukan").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_rujukan' + '/' + $("#pasienid").val(),
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
                { 'visible' : true, 'searchable': true, 'orderable': false },
                 
                ],
             
        });
         
     
    };

     var handlePaket = function(){
  
      oTablePaket=$("#table_obat222").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_popover',
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
                            $('a[name="viewpaket3[]"]', row).click(function(){
                                found=false;
                                 
                               $.each($('input[name$="[idpaket]"]', $("#table_paket")),function(idx, value){
                               
                                if($('a[name="viewpaket3[]"]', row).data('item').id == this.value)
                                    {
                                        found=true;
                                       
                                    } 
                                });

                              if(found==false)
                               {
                                            $('input[name$="[namapaket]"]').last().val($(this).data('item').nama);
                                             $('input[name$="[idpaket]"]').last().val($(this).data('item').id);
                                             $('input[name$="[harga]"]').last().val($(this).data('item').harga);
                                           
                                            addItemRow3();
                                }
                                          
                                       $('.tambahpkt', $("#table_paket")).popover('hide');          


                                                });
                                            }
             
        });

        
     
    };

     var handleSejarahTransaksi = function(){
  
      oTableSejarahTransaksi=$("#table_sejarah_transaksi").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_sejarah_transaksi' + '/' + $("#pasienid").val(),
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
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 
                ],
             
        });

        $("#table_sejarah_transaksi").on('draw.dt', function (){
              
              $('a[name="viewsejarah[]"]', this).click(function(){
                $("#flag2").val(1);
                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        tanggal    = $anchor.data('tggl');

                        $('#li2').attr('class', 'active');
                        $('#li1').removeAttr('class'); 
                        
                        // $('#page').removeAttr('class');
                         $('#page').attr('class','tab-pane active');
                         $('#tabel').attr('class','tab-pane');
                        
  
                         $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getsejarah",  
                                data:  {tindakan_id:id,pasien_id:$("#pasienid").val(),tanggal:tanggal,flag:1 },  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    
                                  $("#tgglpage").html(data.date_value); 
                                  $("#tgglpage2").val(data.date_value);   
                                  handleisidata(data);
                                
                                }
                   
                       });

                        $("#tindakanhdid").val(id);
                        oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + id).load();
                        oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + id).load();
                        oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + id).load();
                        oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + id).load(); 
              });  
       
          });
         
     
    };

var handleisidata = function(data){
                                    for(z=1;z<=6;z++){
                                        $("#prob" + z).prop('checked',false);
                                        if(z==1){
                                                        $("#probname" + z).html("Airway Clearance, ineffective");
                                                    }else if(z==2){
                                                        $("#probname" + z).html("Fluid balance");
                                                    }else if(z==3){
                                                        $("#probname" + z).html("High risk of infection");
                                                    }else if(z==4){
                                                        $("#probname" + z).html("Impaired sense of comfort pain");
                                                    }else if(z==5){
                                                        $("#probname" + z).html("Disequilibrium Syndrome");
                                                    }else if(z==6){
                                                        $("#probname" + z).html("Shock risk");
                                                    }
                                    }

                                    for(b=1;b<=9;b++){
                                         $("#comp" + b).prop('checked',false);
                                        if(b==1){
                                                        $("#compname" + b).html("Bleeding");
                                                    }else if(b==2){
                                                        $("#compname" + b).html("Pruritus");
                                                    }else if(b==3){
                                                        $("#compname" + b).html("Dialyzer Alergic");
                                                    }else if(b==4){
                                                        $("#compname" + b).html("Headache");
                                                    }else if(b==5){
                                                        $("#compname" + b).html("Nausea");
                                                    }else if(b==6){
                                                        $("#compname" + b).html("Chest Pain");
                                                    }else if(b==7){
                                                        $("#compname" + b).html("Hypotension");
                                                    }else if(b==8){
                                                        $("#compname" + b).html("Shiver");
                                                    }else if(b==9){
                                                        $("#compname" + b).html("Etc");
                                                    }
                                    }
                                 $("#medicine2", $("#form_add_cabang")).prop("checked", false);
                                 $('#food2', $("#form_add_cabang")).prop('checked', false);
                                 $("#regular", $("#form_add_cabang")).prop("checked", false);
                                 $("#minimal", $("#form_add_cabang")).prop("checked", false);
                                  $("#free", $("#form_add_cabang")).prop("checked", false);
                                  $("#new", $("#form_add_cabang")).prop("checked", false);
                                  $("#reuse", $("#form_add_cabang")).prop("checked", false);
                                   $("#av_shunt", $("#form_add_cabang")).prop("checked", false);
                                   $("#femoral", $("#form_add_cabang")).prop("checked", false);
                                   $("#double_lument", $("#form_add_cabang")).prop("checked", false);
                                   $("#bicarbonate", $("#form_add_cabang")).prop("checked", false);

                                var x=0;
                                var o=0;
                                $.ajax
                                    ({ 
         
                                        type: 'POST',
                                        url: baseAppUrl +  "getproblem",  
                                        data:  {tindakan_id:data.tindakan_hd_id,pasien_id:$("#pasienid").val()},  
                                        dataType : 'json',
                                        success:function(data)          //on recieve of reply
                                        { 
                                    
                                             $.each(data, function(key, value) {
                                                 x++;
                                                
                                                 if((value.problem_id==$("#prob" + x).val()) && (value.nilai==1))
                                                 {
                                                    $("#prob" + x).prop('checked',true);
                                                    
                                                    
                                                 } 
                                            });
                                            
                                        }
                   
                                });

                                $.ajax
                                    ({ 
         
                                        type: 'POST',
                                        url: baseAppUrl +  "getkomplikasi",  
                                        data:  {tindakan_id:data.tindakan_hd_id,pasien_id:$("#pasienid").val()},  
                                        dataType : 'json',
                                        success:function(data)          //on recieve of reply
                                        { 
                                    
                                             $.each(data, function(key, value) {
                                                 o++;
                                                
                                                 if((value.komplikasi_id==$("#comp" + o).val()) && (value.nilai==1))
                                                 {

                                                    $("#comp" + o).prop('checked',true);
                                                    
                                                    
                                                 } 
                                            });
                                            
                                        }
                   
                                });

                                   
                                                

                                    $("#datesejarah").val(data.date_value);
                                     $("#time").val(data.time_value);
                                    
                                     if(data.alergic_medicine==1)
                                     {
                                       
                                        $("#medicine2", $("#form_add_item")).prop("checked", true);
                                     }
                                     if(data.alergic_food==1)
                                     {
                                        $('#food2', $("#form_add_item")).prop('checked', true);
                                     }
                                     
                                    $("#assessment_cgs").val(data.assessment_cgs_value);
                                    $("#medical_diagnose").val(data.medical_diagnose_value);
                                    $("#time_of_dialysis").val(data.time_of_dialysis_value);
                                    $("#quick_of_blood").val(data.quick_of_blood_value);
                                    $("#quick_of_dialysate").val(data.quick_of_dialysis_value);
                                    $("#uf_goal").val(data.uf_goal_value);

                                    if(data.heparin_reguler_value == 1)
                                    {
                                         $("#regular", $("#form_add_item")).prop("checked", true);
                                    }
                                    if(data.heparin_minimal_value == 1)
                                    {
                                        $("#minimal", $("#form_add_item")).prop("checked", true);
                                    }
                                    if(data.heparin_free_value == 1)
                                    {
                                         $("#free", $("#form_add_item")).prop("checked", true);
                                    }

                                    if(data.dialyzer_new_value == 1)
                                    {
                                         $("#new", $("#form_add_item")).prop("checked", true);
                                    }
                                    if(data.dialyzer_reuse_value == 1)
                                    {
                                         $("#reuse", $("#form_add_item")).prop("checked", true);
                                    }

                                     if(data.ba_avshunt_value == 1)
                                    {
                                         $("#av_shunt", $("#form_add_item")).prop("checked", true);
                                    }
                                     if(data.ba_femoral_value == 1)
                                    {
                                         $("#femoral", $("#form_add_item")).prop("checked", true);
                                    }
                                     if(data.ba_catheter_value == 1)
                                    {
                                         $("#double_lument", $("#form_add_item")).prop("checked", true);
                                    }

                                    if(data.dialyzer_type_value == 1)
                                    {
                                         $("#bicarbonate", $("#form_add_item")).prop("checked", true);
                                    }
                                    $("#dose").val(data.dose_value);
                                    $("#first").val(data.first_value);
                                    $("#maintenance").val(data.maintenance_value);
                                    $("#hour").val(data.hours_value);
                                    $("#machine_no").val(data.machine_no_value);
                                    $("#dialyzer").val(data.dialyzer_value);
                                    $("#remaining").val(data.remaining_of_priming_value);
                                    $("#washout").val(data.wash_out_value);
                                    $("#drip_of_fluid").val(data.drip_of_fluid_value);
                                    $("#blood").val(data.blood_value);
                                    $("#drink").val(data.drink_value);
                                    $("#vomitting").val(data.vomiting_value);
                                    $("#urinate").val(data.urinate_value);
                                    $("#type").val(data.transfusion_type_value);
                                    $("#quantity").val(data.transfusion_qty_value);
                                    $("#blood_type").val(data.transfusion_blood_type_value);
                                    $("#serial_number").val(data.serial_number_value);
                                    $("#laboratory").val(data.laboratory_value);
                                    $("#ecg").val(data.ecg_value);
                                    $("#priming").val(data.priming_value);
                                    $("#initiation").val(data.initiation_value);
                                    $("#termination").val(data.termination_value);

                                    
}
    var handleSejarahItem = function(){
  
      oTableSejarahItem=$("#table_sejarah_item").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_sejarah_item' + '/' + $("#pasienid").val(),
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
                 { 'visible' : false, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 { 'visible' : false, 'searchable': false, 'orderable': false },
                 
                ],
             
        });

        $("#table_sejarah_item").on('draw.dt', function (){
              
              $('a[name="viewitem[]"]', this).click(function(){
                $("#flag2").val(1);
                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        tanggal    = $anchor.data('tggl');

                        $('#li2').attr('class', 'active');
                        $('#li1').removeAttr('class'); 
                        
                        // $('#page').removeAttr('class');
                         $('#page').attr('class','tab-pane active');
                         $('#tabel').attr('class','tab-pane');
                        
  
                         $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getsejarah",  
                                data:  {tindakan_id:id,pasien_id:$("#pasienid").val(),tanggal:tanggal,flag:1 },  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    
                                  $("#tgglpage").html(data.date_value); 
                                  $("#tgglpage2").val(data.date_value);   
                                  handleisidata(data);
                                
                                }
                   
                       });

                        $("#tindakanhdid").val(id);
                        oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + id).load();
                        oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + id).load();
                        oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + id).load();
                        oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + id).load(); 
              });  
       
          });
         
     
    };

    var handleObservasi = function(){
  
      oTableObservasi=$("#table_dialysis").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_observasi',
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
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true }

                 
                ],
             
        });     
    };

    var handleObservasi2 = function(){
  
      oTableObservasi2=$("#table_dialysis2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_observasi2/' + $("#tindakanhdid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name':'observasi_dialisis_hd.waktu_pencatatan ob_waktu_pencatatan','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'observasi_dialisis_hd.tekanan_darah_1 ob_tekanan_darah','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'observasi_dialisis_hd.ufg ob_ufg','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'observasi_dialisis_hd.ufr ob_ufr','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'observasi_dialisis_hd.ufv ob_ufv','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'observasi_dialisis_hd.qb ob_qb','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'b.nama nama_perawat','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'observasi_dialisis_hd.keterangan keterangan', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'observasi_dialisis_hd.keterangan keterangan', 'visible' : false, 'searchable': false, 'orderable': false },

                 
                ],
             
        });
         
     
    };

    var handleItemTersimpan = function(){
  
      oTableItemTersimpan=$("#table_item2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_tersimpan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
             'stateSave'             :true,
              'pagingType'            :'full_numbers',
              'paging'  : false,
              'searching' : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                
                { 'visible' : true, 'searchable': false, 'orderable': false },
               
                 
                ],
             
        });

      $("#table_item2").on('draw.dt', function (){
              
              $('a[name="pakai[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        msg    = $anchor.data('confirm');
                         bootbox.confirm(msg, function(result) {
                            if (result==true) {
                                   
                                $.ajax
                                ({ 
         
                                    type: 'POST',
                                    url: baseAppUrl +  "updatestatus/1",  
                                    data:  {id:id },  
                                    dataType : 'json',
                                    success:function(data)          //on recieve of reply
                                    { 
                                   
                                         oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + $("#tindakanhdid").val()).load();
                                
                                    }
                   
                                });
                            }
                        })
                         
              }); 

              $('a[name="batal[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        msg    = $anchor.data('confirm');
                         bootbox.confirm(msg, function(result) {
                            if (result==true) {

                                     $.ajax
                                ({ 
         
                                    type: 'POST',
                                    url: baseAppUrl +  "updatestatus/2",  
                                    data:  {id:id },  
                                    dataType : 'json',
                                    success:function(data)          //on recieve of reply
                                    { 
                                     
                                        oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + $("#tindakanhdid").val()).load();
                                
                                    }
                   
                                });
                            }
                        })
                         
              }); 

          });
         
     
    };

     var handleItemDigunakan = function(){
  
      oTableItemDigunakan=$("#table_provision").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_telah_digunakan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
             'stateSave'             :true,
              'pagingType'            :'full_numbers',
              'paging'  : false,
              'searching' : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                  { 'visible' : true, 'searchable': true, 'orderable': true },
                   { 'visible' : true, 'searchable': true, 'orderable': true },
                   { 'visible' : true, 'searchable': true, 'orderable': true },
                   { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },

                 
                ],
             
        });
         
     
    };

    var handleTagihan = function(){
  
      oTableTagihan=$("#table_invoice3").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_tagihan',
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
                  
                  
                { 'visible' : true, 'searchable': false, 'orderable': false },

                 
                ],
             
        });
    $("#table_invoice3").on('draw.dt', function (){
       $('a[name="viewtagihanpaket[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                       namapaket    = $anchor.data('paketname');
                       transaksiid    = $anchor.data('transaksiid');
                        
                        $("#asses2").hide();
                       // $("#asses1").hide();
                        $("#asses3").show();
                        $("#observasiid").val(id);
                        
                        $("#tagihanpaketname").html(namapaket);
                         oTableViewTagihann.api().ajax.url(baseAppUrl + 'listing_view_tagihan_paket/'  + transaksiid + '/' + id).load();
                        $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getnomortransaksi",  
                                data:  {transaksiid:transaksiid},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                      //  alert(data.waktu_pencatatan_value);
                                      $("#tagihantransaksinumber").html(data.id);
                                       
                                     
                                     
                                     
                                }
                   
                       });
                    
                         
              });  
       });  
     
    };

     var handleViewTagihan= function(){
  
      oTableViewTagihann=$("#table_view_paket").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_view_tagihan_paket',
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
         
     
    };

var handleResepObat= function(){
  
      oTableViewResepObat=$("#table_resep_obat").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_histori_detail_resep/' + $("#id").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
             'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name':'b.nama nama_item','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_resep_obat.tipe_item tipe_time','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_resep_obat.jumlah jumlah_obat','visible' : true, 'searchable': true, 'orderable': true },

                {'name':'tindakan_resep_obat.dosis dosis_obat','visible' : true, 'searchable': true, 'orderable': true },
                 
                 
                ],
             
        });
         
     
    };

    var handleResepObatRacikan= function(){
   
      oTableViewResepObatRacikan=$("#table_resep_racikan").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_histori_detail_resep_racikan/' + $("#id").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
             'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },

                ],
             
        });
$("#table_resep_racikan").on('draw.dt', function (){
        var $colItems = $('.show-notes', this);

            $.each($colItems, function(idx, colItem){
                var
                    $colItem = $(colItem),
                    itemsData = $colItem.data('content');
            
            $colItem.popover({
                    html : true,
                    container : 'body',
                    placement : 'right',
                    content: function(){

                        var html = '<table class="table table-striped table-hover">';
                            html += '<tr>';
                            html += '<td>'+itemsData+'</td>';
                            html += '</tr>';
                            html += '</table>';
                            return html;
                    }
                    }).on("show.bs.popover", function(){
                        $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '720px'});

                        if ($lastPopoverItem !== null) $lastPopoverItem.popover('hide');
                        $lastPopoverItem = $colItem;
                    }).on('hide.bs.popover', function(){
                        $lastPopoverItem = null;
                    }).on('click', function(e){
                    });
                });  
});
         
     
    };
   
    var uploadfile = function(table) {
 //    alert(mb.baseUrl());
  //var table2=$("#dokumen_1");
    $('input[name$="[dokumen]"]', table).uploadify({
                            "swf"               :  mb.baseUrl() + "assets/mb/global/uploadify/uploadify.swf",
                            "uploader"          : mb.baseUrl() + "assets/mb/global/uploadify/uploadify3.php",
                            "formData"          : {"type" : "dokumen", "dokumen_id" : "", "nama_dokumen" : "approval"}, 
                            "fileObjName"       : "Filedata", 
                            "fileSizeLimit"     : "2048KB",
                            // "fileTypeDesc"      : "Image Files (.jpg, .jpeg, .png)",
                            // "fileTypeExts"      : "*.jpg; *.jpeg; *.png",
                            "method"            : "post", 
                            "multi"             : false, 
                            "queueSizeLimit"    : 1, 
                            "removeCompleted"   : true, 
                            "removeTimeout"     : 5, 
                            "uploadLimit"       : 5, 
                            "onUploadSuccess"   : function(file, data, response) {
                             var paramsArray = data.split('%%__%%');
                                param1 = paramsArray[0]; 
                                param2 = paramsArray[1]; 
                                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                                {
                                    $('label[name$="[choosen_file_1]"]', table).html("<img src="+ mb.baseUrl() + "assets/mb/pages/klinik_hd/klaim_asuransi/images/temp/"+param1+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");
                                    $('div[name$="[choosen_file_container_1]"]', table).show();
                                    $('input[name$="[filename]"]', table).val(param1);
                                }else{
                                    $('input[name$="[filename]"]', table).val(param1);
                                     $('div[name$="[choosen_file_container_1]"]', table).show();
                                    $('label[name$="[choosen_file_1]"]', table).html('<b>' + file.name + '</b>');
                                }
                              
                            },
                            "onUploadComplete"   : function(file) {
                             
                              
                            }
                        }); 
};

var uploadfile2 = function(table) {
  
    $('#uploaddokumen').uploadify({
                            "swf"               :  mb.baseUrl() + "assets/mb/global/uploadify/uploadify.swf",
                            "uploader"          : mb.baseUrl() + "assets/mb/global/uploadify/uploadify4.php",
                            "formData"          : {"type" : "dokumen", "dokumen_id" : "", "nama_dokumen" : "dokumen"}, 
                            "fileObjName"       : "Filedata", 
                            "fileSizeLimit"     : "2048KB",
                            // "fileTypeDesc"      : "Image Files (.jpg, .jpeg, .png)",
                            // "fileTypeExts"      : "*.jpg; *.jpeg; *.png",
                            "method"            : "post", 
                            "multi"             : false, 
                            "queueSizeLimit"    : 1, 
                            "removeCompleted"   : true, 
                            "removeTimeout"     : 5, 
                            "uploadLimit"       : 5, 
                            "onUploadSuccess"   : function(file, data, response) {
                             var paramsArray = data.split('%%__%%');
                                param1 = paramsArray[0]; 
                                param2 = paramsArray[1]; 
                                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                                {
                                    $('#uploadchoosen_file_1').html("<img src="+ mb.baseUrl() + "assets/mb/pages/klinik_hd/transaksi_dokter/images/temp/"+param1+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");
                                    $('#uploadchoosen_file_container_1').show();
                                    $('#uploadfilename').val(param1);
                                }else{
                                    $('#uploadfilename').val(param1);
                                     $('#uploadchoosen_file_container_1').show();
                                    $('#uploadchoosen_file_1').html('<b>' + file.name + '</b>');
                                }
                              
                            },
                            "onUploadComplete"   : function(file) {
                             
                              
                            }
                        }); 
};

var uploadfile3 = function(table) {
  
    $('#uploaddokumen2').uploadify({
                            "swf"               :  mb.baseUrl() + "assets/mb/global/uploadify/uploadify.swf",
                            "uploader"          : mb.baseUrl() + "assets/mb/global/uploadify/uploadify4.php",
                            "formData"          : {"type" : "dokumen", "dokumen_id" : "", "nama_dokumen" : "dokumen"}, 
                            "fileObjName"       : "Filedata", 
                            "fileSizeLimit"     : "2048KB",
                            // "fileTypeDesc"      : "Image Files (.jpg, .jpeg, .png)",
                            // "fileTypeExts"      : "*.jpg; *.jpeg; *.png",
                            "method"            : "post", 
                            "multi"             : false, 
                            "queueSizeLimit"    : 1, 
                            "removeCompleted"   : true, 
                            "removeTimeout"     : 5, 
                            "uploadLimit"       : 5, 
                            "onUploadSuccess"   : function(file, data, response) {
                             var paramsArray = data.split('%%__%%');
                                param1 = paramsArray[0]; 
                                param2 = paramsArray[1]; 
                                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                                {
                                    $('#uploadchoosen_file_12').html("<img src="+ mb.baseUrl() + "assets/mb/pages/klinik_hd/transaksi_dokter/images/temp/"+param1+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");
                                    $('#uploadchoosen_file_container_12').show();
                                    $('#uploadfilename2').val(param1);
                                }else{
                                    $('#uploadfilename2').val(param1);
                                     $('#uploadchoosen_file_container_12').show();
                                    $('#uploadchoosen_file_12').html('<b>' + file.name + '</b>');
                                }
                              
                            },
                            "onUploadComplete"   : function(file) {
                             
                              
                            }
                        }); 
};
 var handleDatePickers = function () {
        
            $('.date-picker', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'd MM yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal

            
              $('.date-picker', $("#modaltindakan")).datepicker({
                rtl: Metronic.isRTL(),
                format : 'd M yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }

var handlekategori = function(){
     $('#kategori').on('change', function(){
          
            oTableobat.api().ajax.url(baseAppUrl + 'listing_obat/' + this.value).load();
        
        });
}

    
    // DENAH 
    var handleKlikBed = function(){

        var $bed = $('polygon');

        $.each($bed, function(idx, colBed){
            var
                $colBed = $(colBed);

            // console.log($colBed);
            $colBed.popover({
                html : true,
                container : 'body',
                placement : 'top',
                content: function(){
                 
                }
            }).on("show.bs.popover", function(){
                $(this).data("bs.popover").tip().addClass('popover_menu');
                $(this).data("bs.popover").tip().css({minWidth: '100px', maxWidth: '300px', margin: '26px 0 0 40px', left : '85.808815px'});
                if ($lastPopoverBed !== null) $lastPopoverBed.popover('hide');
                $lastPopoverBed = $colBed;
            }).on('hide.bs.popover', function(){
                $lastPopoverBed = null;
            }).on('click', function(e){

            });
        });
    }

    var handleRefresh2 = function(){

        $('a#refresh').on('click', function(){
            window.location.reload();
        })
    }
 
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
    initForm();
     //   alert('hi');
        handleValidation();
      
         handleDTItems2();
         handleDTItems3();
         handleDTItems4();
         handleObat();
         handleRacikan();
         handleKlaim();
         handleRujukan();
         handlePaket();
         handleSejarahTransaksi();
         handleSejarahItem();
         handleObservasi();
         handleObservasi2();
         handleItemTersimpan();
         handleItemDigunakan();
         handleTagihan();
         handleViewTagihan();
         handleResepObat();
         handleResepObatRacikan();

        handlebtnpage();
        handlePrev();
        handleNext();
        handleFirst();
        handleLast();
        handlebackbtn();
        handlebackbtn2();
         handletabbutn1();
        handletabbutn2();
        handlehistoribtn();
        handlehistoribtn2();
        handlekategori();
        handleRefresh();
        uploadfile2();
        uploadfile3();

        handleConfirmSave();
       handleConfirmSave3();
       handleConfirmSave4();
       handleConfirmcreatetindakan();
       handleDatePickers();
       handleAddDokumen();
       
       
       
     

        handleKlikBed();
        // handleRefresh2();

        
      //  uploadfile();
    };
 }(mb.app.cabang.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.add.init();
});