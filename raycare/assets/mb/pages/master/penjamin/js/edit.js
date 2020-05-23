mb.app.cabang = mb.app.cabang || {};
mb.app.cabang.add = mb.app.cabang.add || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form = $('#form_add_cabang');

        $errorTop               = $('.alert-danger', $form),
        $successTop             = $('.alert-success', $form),
        $tableOrderItem         = $('#table_order_item22', $form),
        $tableSupplierSearch    = $('#table_supplier_search'),
        $tableItemSearch        = $('#table_item_search'),
        $popoverSupplierContent = $('#popover_supplier_content'), 
        $popoverItemContent     = $('#popover_item_content'), 
         $popoverItemContent5     = $('#popover_item_content5'), 
        $btnSearchSupplier      = $('.search-supplier', $form),
        $btnAddItem             = $('.add-item'),
        $lastPopoverItem        = null,
        theadFilterTemplate     = $('#thead-filter-template').text(),
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter             = 1,
        tplFormPayment = '<li class="fieldset">' + $('#tpl-form-payment', $form).val() + '<hr></li>',
        tplFormPayment2 = '<li class="fieldset">' + $('#tpl-form-payment2', $form).val() + '<hr></li>',
        regExpTpl        = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        paymentCounter     = 1,
        paymentCounter2     = 1,
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
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $('#save', $form).click();
                }
            });
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
        if($('#susp').is(':checked')){
            $("#suspval").val(1);
        }else{
            $("#suspval").val(0);
        }
        // if(x==0){
        //     $("#suspval").val(this.value);
        //     x=1;
        // }else{
        //     $("#suspval").val(0);
            
        //     x=0;
        // }
     
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

     var addItemRow = function(tabel,counter){
        var numRow = $('tbody tr', $tableOrderItem).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', tabel),
            $newItemRow    = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnDelete     = $('.del-this', $newItemRow);
  
      //  handleBtnSearchItem($btnSearchItem);
        $('.t1', $newItemRow).attr('name', counter + $('.t1', $newItemRow).attr('name'));
        $('.t2', $newItemRow).attr('name', counter + $('.t2', $newItemRow).attr('name'));
        handleBtnDeleteItem($btnDelete,tabel,counter);
        $("#counter").val(counter);
       // handleCheck($checkMultiply);
        
    };

    var addItemRow2 = function(tabel,counter,text,value,flag){
        var numRow = $('tbody tr', $tableOrderItem).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', tabel),
            $newItemRow    = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnDelete     = $('.del-this', $newItemRow);
  
      //  handleBtnSearchItem($btnSearchItem);
        $('.t1', $newItemRow).attr('name', counter + $('.t1', $newItemRow).attr('name'));
        $('.t2', $newItemRow).attr('name', counter + $('.t2', $newItemRow).attr('name'));
        handleBtnDeleteItem($btnDelete,tabel,counter);
        $("#counter").val(counter);
         $('.t1', $newItemRow).val(text);
        $('.t2', $newItemRow).val(value);

        if(flag=='add'){
              $('.t2', $newItemRow).attr('disabled','disabled');
                $('.t1', $newItemRow).attr('disabled','disabled');
                $btnDelete.attr('disabled','disabled');
        }
       
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

    var handleTableItemSearchSelectBtn = function(){
            // console.log($btn.length);
        var $btnsSelect = $('a.select', $tableItemSearch);

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
                    $itemStock   = $('input[name$="[harga]"]', $row),                   
                                    
                    $itemUnitEl  = $('label.unit', $row),                  
                    $divMultiple = $('label.multiply',$row),
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
                    $itemCodeEl.val($(this).data('item').kode);
                    $itemNameEl.val($(this).data('item').nama);
                    $itemStock.val($(this).data('item').harga);
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

     var handleBtnDeleteItem = function($btn,tabel,counter){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, tabel);

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', tabel).length == 0){
                        addItemRow(tabel,counter);
                    }
            //     }
            // });

            e.preventDefault();
        });

    };

    var initForm = function(){
         
    
        if($('#kel1').is(':checked')){
          
            $( "li#grup" ).fadeIn( "slow", function() {
    
            });
            $( "#url1" ).fadeOut( "slow", function() {
    
            });
        }else{
          
            $( "li#grup" ).fadeOut( "slow", function() {
     
            });
              $( "#url1" ).fadeIn( "slow", function() {
    
            });
        }
            
          if($('#susp').is(':checked')){
            $("#suspval").val(1);
          }

         $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "syarat",  
                                data:  {id:$("#pk").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                  $.each(data, function(key, value) {
                                        $.each(forms, function(idx, form){
 
                                            addFieldset3(form,value.tipe,value.judul,value.maksimal_karakter,value.id,'edit');
                               
                                        });
                                    });
                                
                                }
                   
                       });

        handleDTsyarat();

        $.each(forms, function(idx, form){

            // handle button add
            $('a.add-payment',$form).on('click', function(){
               
                addFieldset(form);
                jml+=1;

            });

            // beri satu fieldset kosong
           // addFieldset(form);
            

            // var 
             
            // $btnsDelete     = $('.del-this', $form);
 
            // $.each($('table[name$="[table_order_item22]"]',$form), function(idx, btn){

            //     addItemRow(btn);

            // });
             
           

        });

         $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "scan",  
                                data:  {id:$("#pk").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 

                                  $.each(data, function(key, value) {
                                        $.each(forms2, function(idx, form){
 
                                             addFieldset4(form,value.url);
                               
                                        });
                                    });
                                
                                }
                   
                       });
        $.each(forms2, function(idx, form){

            // handle button add
            $('a.add-payment2',$form).on('click', function(){
               
                addFieldset2(form);
                jml+=1;

            });

            // beri satu fieldset kosong
           // addFieldset2(form);
            

            // var 
             
            // $btnsDelete     = $('.del-this', $form);
 
            // $.each($('table[name$="[table_order_item22]"]',$form), function(idx, btn){

            //     addItemRow(btn);

            // });
             
           

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

     var addFieldset3 = function(form,tipe,judul,max,id,flag,flag2){
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
       handleSelectSection(tipe, $newFieldset);
      // addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
         $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
          
            handleSelectSection(this.value, $newFieldset);
             
        });
        $('select[name$="[payment_type]"]', $newFieldset).val(tipe);
        $('input[name$="[judul]"]', $newFieldset).val(judul);
        $('input[name$="[text]"]', $newFieldset).val(max);
        if(flag=='add'){
            $('input[name$="[idsyarat]"]', $newFieldset).val(id);
        }
         if(flag=='edit'){
            $('input[name$="[idsyarat2]"]', $newFieldset).val(id);
        }
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
                                    if(data.length > 0){
                                         $.each(data, function(key, value) {
                                            addItemRow2($('table[name$="[table_order_item22]"]', $newFieldset),counter,value.text,value.value,flag);
                                        });
                                    }else{
                                         addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
                                       // addItemRow2($('table[name$="[table_order_item22]"]', $newFieldset),counter,value.text,value.value,flag);
                                    }
                                 
                                
                                }
                   
                       });
         //addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
        //jelasin warna hr pemisah antar fieldset

        $('input[name$="[idcount]"]', $newFieldset).val(counter);

        if(flag=='add'){
                $('select[name$="[payment_type]"]', $newFieldset).attr('disabled','disabled');
                $('input[name$="[judul]"]', $newFieldset).attr('disabled','disabled');
                $('input[name$="[text]"]', $newFieldset).attr('disabled','disabled');
                $('a[name$="[addrow]"]', $newFieldset).attr('disabled','disabled');
        } 

         if(flag2=='del'){
                $('input[name$="[isdeleted]"]', $newFieldset).val(0);
                  $newFieldset.hide();
         }
       
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
    var addFieldset4 = function(form,url){
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
 
                                var paramsArray = url.split('.');
                                param1 = paramsArray[0]; 
                                param2 = paramsArray[1]; 
                                var paramsArray2 = url.split('/');
                                param3 = paramsArray2[paramsArray2.length-1]; 
                                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                                {
                                    $('label[name$="[choosen_file_1]"]', $newFieldset).html("<img src="+ mb.baseUrl() + "assets/mb/pages/master/penjamin/images/temp/" +param3+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");
                                    $('div[name$="[choosen_file_container_1]"]', $newFieldset).show();
                                    $('input[name$="[filename]"]', $newFieldset).val(param3);
                                }else{
                                    $('input[name$="[filename]"]', $newFieldset).val(param3);
                                     $('div[name$="[choosen_file_container_1]"]', $newFieldset).show();
                                     $('label[name$="[choosen_file_1]"]', $newFieldset).html('<b>' + param3 + '</b>');
                                }
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

        if(!$('input[name$="[idsyarat]"]', $fieldset).val() && !$('input[name$="[idsyarat2]"]', $fieldset).val())
        {
             $fieldset.remove();
        }else{
           //  $fieldset.hide();
             $fieldset.remove();
             $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "syarat2",  
                                data:  {id1:$("#pk").val(),id2:$('input[name$="[idsyarat2]"]', $fieldset).val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                  $.each(data, function(key, value) {
                                        $.each(forms, function(idx, form){
 
                                            addFieldset3(form,value.tipe,value.judul,value.maksimal_karakter,value.id,'edit','del');
                               
                                        });
                                    });
                                
                                }
                   
                       });
            // $('input[name$="[isdeleted]"]', $fieldset).val(0);
        }
        

  
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
        if(value == 3  || value==8)
        {
            $('div#section_3', $fieldset).hide();
            $('div#section_2', $fieldset).hide();
            $('div#section_1', $fieldset).hide();
            
        }
    }

    var handleDTItems2 = function(){
 
       $("#table_kelompok_penjamin").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_kelompok/' + $("#flag").val() + '/' + $("#pk").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
     
    }; 

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
 
                             addFieldset3(form,tipe,judul,max,id,'add','nodel');
                               
                        });
                });
                                            //     handleBtnSearchItem($('.search-item', row));
                                            // handleDelete($('a[name="delete[]"]', row));
                 }
        });
       // $("#table_syarat2").on('draw.dt', function (){
              
       //        $('a[name="pilih[]"]', this).click(function(){

       //           var $anchor = $(this),
       //                 id    = $anchor.data('id');
       //                alert(id);
       //                 // $.each(forms, function(idx, form){
 
       //                 //       addFieldset(form);
                               
       //                 //  });
                    
       //        });  
       
       //    });
     
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
                                    $('label[name$="[choosen_file_1]"]', table).html("<img src="+ mb.baseUrl() + "assets/mb/pages/master/penjamin/images/temp/"+param1+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");
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

 var handleDatePickers = function () {
         
            $('.date-picker', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'd MM yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal

            
         
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/penjamin/';
       initForm();
       // initForm2();
     //   alert('hi');
        handleValidation();
        //  initForm(); 
        handleBtnSearchSyarat();
         handleDTItems2();
         
        handleConfirmSave();
        handleRadiobutn();
        handlecheckbutn();
        handlecheckall();
        handleDatePickers();

      //  uploadfile();
    };
 }(mb.app.cabang.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.add.init();
});