mb.app.resep_obat = mb.app.resep_obat || {};
mb.app.resep_obat.add = mb.app.resep_obat.add || {};

// mb.app.resep_obat.add namespace
(function(o){

    var 
        baseAppUrl           = '',
        $form                = $('#form_add_resep_obat'),
        $errorTop            = $('.alert-danger', $form),
        $succesTop           = $('.alert-success', $form),
        $tableItemDigunakan  = $('#table_item_digunakan', $form),
        $tableItemSearch     = $('#table_item_search'),
        $tableResepSearch    = $('#table_resep_search'),
        $popoverItemContent  = $('#popover_item_content'), 
        $lastPopoverItem     = null,
        $popoverResepContent = $('#popover_resep_content'), 
        $lastPopoverResep    = null,
        tplItemRow           = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter          = 1
        ;


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
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control locker
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control locker
            } 
        });   
    }


 var handleValidation2 = function() {
        var error1   = $('.alert-danger', $("#form_add_keuangan"));
        var success1 = $('.alert-success', $("#form_add_keuangan"));

        $("#form_add_keuangan").validate({

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
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control locker
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control locker
            } 
        });   
    }
    var initForm = function(){
        var 
            $btnSearchItem = $('.search-item', $tableItemDigunakan),
            $btnSearchResep = $('.search-resep'),
            $btnDeletes    = $('.del-this', $tableItemDigunakan);

        handleBtnSearchItem($btnSearchItem);    
        handleBtnSearchResep($btnSearchResep);    

        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        // tambah 1 row kosong pertama
        addItemRow();
    };

// tambah row di table item details
    var addItemRow = function(){
        
        var numRow = $('tbody tr', $tableItemDigunakan).length;

        console.log('numrow' + numRow);

        // if (numRow > 0 && ! isValidLastRow()) return;

        var 
            $rowContainer     = $('tbody', $tableItemDigunakan),
            $newItemRow       = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            // $newGetItemRow = $(tplGetItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem    = $('.search-item', $newItemRow);

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );

        // handle button search item
        handleBtnSearchItem($btnSearchItem);

    };

     var isValidLastRow = function(){
        var 
            $itemCodeEls    = $('input[name$="[code]"]',$tableItemDigunakan),
            $qtyELs         = $('input[name$="[qty]"]',$tableItemDigunakan),
            itemCode        = $itemCodeEls.eq($qtyELs.length-1).val(),
            qty             = $qtyELs.eq($qtyELs.length-1).val() * 1
        ;

        return (itemCode != '' && qty > 0)
    }

   
    var handleBtnDelete = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableItemDigunakan);

        $btn.on('click', function(e){
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
                // if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableItemDigunakan).length == 0){
                        addItemRow();
                    }
                    // focusLastItemCode();
                // }
            // });
            e.preventDefault();
        });
    };

    var handleBtnSearchItem = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        var rowStatus  = $btn.closest('tr').prop('class');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverItemContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);

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

    var handleBtnSearchResep = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverResep != null) $lastPopoverResep.popover('hide');

            $lastPopoverResep = $btn;

            $popoverResepContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            
            // pindahkan $popoverResepContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverResepContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverResepContent ke .page-content
            $popoverResepContent.hide();
            $popoverResepContent.appendTo($('.page-content'));

            $lastPopoverResep = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableItems = function(){
      
       oTablebiaya= $("#table_laporan_keuangan").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            //'responsive': true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_laporan_biaya/' + $("#userid").val() + '/' + $("#date1").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : false, 'searchable': true, 'orderable': true },
                 { 'visible' : false, 'searchable': true, 'orderable': true },
                 { 'visible' : false, 'searchable': true, 'orderable': true },
                 
                 
              ],

              "footerCallback": function( tfoot, data, start, end, display ) {
                      //  alert(data[0][3]);
                         if(data.length>0)
                         {
                        //     //$('input[name="biaya[]"]').val()
                         $("#totalbiaya1").html(mb.formatRp(parseFloat(data[0][5])));
                         $("#totalbiaya2").html(mb.formatRp(parseFloat(data[0][6])));
                         $("#totalbiaya3").html(mb.formatRp(parseFloat(data[0][7])));
                        //    $("#totalbiaya").html(mb.formatRp(data[0][4]));
                        }else{
                            $("#totalbiaya1").html(0);
                         $("#totalbiaya2").html(0);
                         $("#totalbiaya3").html(0);
                        }
                         
                }
        });
 
//     column = oTablebiaya.column(3);
//     $( column.footer() ).html(
//     column.data().reduce( function (a,b) {
//         return a+b;
//     } )
// );
        // var $btnSelects = $('a.select', $tableItemSearch);
        // handleItemSearchSelect( $btnSelects );

        // $tableItemSearch.on('draw.dt', function (){
        //     var $btnSelect = $('a.select', this);
        //     handleItemSearchSelect( $btnSelect );
            
        // } );

        // $popoverItemContent.hide();        
    };

    var handleItemSearchSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input[name="rowItemId"]', $parentPop).val(),
                statusRow       = $('input[name="rowStatus"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableItemDigunakan),
                $row_status = '',
                $itemId   = null,
                $itemKode = null,
                $itemNama = null
                ;        


           
                $itemId        = $('input[name$="[item_id]"]', $row);
                $itemKode      = $('input[name$="[item_kode]"]', $row);
                $itemNama      = $('input[name$="[item_nama]"]', $row);
                $itemSatuan    = $('select[name$="[satuan]"]', $row);
                $itemLabelKode = $('label[name$="[item_kode]"]', $row);
                $itemLabelNama = $('label[name$="[item_nama]"]', $row);
                $row_status    = $('#'+rowId, $tableItemDigunakan).attr('class', 'item_row_edit');
                $btnSearchItem    = $('.search-item', $row).attr('data-status-row', 'item_row_edit');


                $('.search-item', $tableItemDigunakan).popover('hide');
            
                console.log($itemId);

            var primary = $(this).data('satuan_primary').id;
            
            $.ajax
            ({ 

                    type: 'POST',
                    url: baseAppUrl +  "get_item_satuan",  
                    data:  {item_id : $(this).data('item').id},  
                    dataType : 'json',
                    success:function(results)          //on recieve of reply
                    { 
                        $itemSatuan.empty();

                        //munculin index pertama Pilih..
                        $itemSatuan.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                            $itemSatuan.val('');

                        //munculin semua data dari hasil post
                        $.each(results, function(key, value) {
                            $itemSatuan.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $itemSatuan.val(primary);

                        });
                    
                    }
       
            });

            // console.log($itemIdEl)
            
            if ($itemKode.val() == "") {
                $itemId.val($(this).data('item').id);            
                $itemKode.val($(this).data('item').item_kode);
                $itemNama.val($(this).data('item').item_nama);

                $itemLabelKode.text($(this).data('item').item_kode);
                $itemLabelNama.text($(this).data('item').item_nama);

                addItemRow();
                e.preventDefault();
            }else{
                $itemId.val($(this).data('item').id);            
                $itemKode.val($(this).data('item').item_kode);
                $itemNama.val($(this).data('item').item_nama);

                $itemLabelKode.text($(this).data('item').item_kode);
                $itemLabelNama.text($(this).data('item').item_nama);
            }
            
        });     
    };

    var handleDataTableResep = function(){
        $tableResepSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_resep/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'name' : 'resep_racik_obat.nama','visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },               ]
        });

        var $btnSelects = $('a.select', $tableResepSearch);
        handleResepSearchSelect( $btnSelects );

        $tableResepSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handleResepSearchSelect( $btnSelect );
            
        } );

        $popoverResepContent.hide();        
    };

    var handleResepSearchSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $racikan   = null,
                $pembuat = null,
                $keterangan = null
                ;        


           
                $racikan        = $('input#racikan');
                $pembuat      = $('label#pembuat');
                $keterangan      = $('label#keterangan');


                $('.search-resep').popover('hide');
              // console.log($itemIdEl)
            
                $racikan.val($(this).data('resep').nama);
                $keterangan.text($(this).data('resep').keterangan);

                $.ajax
                ({ 

                        type: 'POST',
                        url: baseAppUrl +  "get_user",  
                        data:  {user_id : $(this).data('resep').user_id},  
                        dataType : 'json',
                        success:function(results)          //on recieve of reply
                        { 
                            $pembuat.text(results.nama);                        
                        }
           
                });

                $tableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_digunakan/' + $(this).data('resep').id).load();
            
        });     
    };

    var handleDataTableItemDigunakan = function(){
        $tableItemDigunakan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_digunakan/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'name' : 'item.kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'resep_racik_obat_detail.jumlah','visible' : true, 'searchable': false, 'orderable': true },               
                { 'name' : 'item.satuan','visible' : true, 'searchable': false, 'orderable': true },               
                { 'name' : 'item.harga','visible' : true, 'searchable': false, 'orderable': true },               
                ]
        });

        var $btnSelects = $('a.select', $tableItemDigunakan);
        handleResepSearchSelect( $btnSelects );

        $tableItemDigunakan.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handleResepSearchSelect( $btnSelect );

            $('a[name="identitas[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');

                    $tableInfoItem.api().ajax.url(baseAppUrl + 'listing_info_item/' + id).load();
                    
            });
            
        } );

        $popoverResepContent.hide();        
    };

    var handleConfirmSave = function(){
        var numRow = $('tbody tr', $tableItemDigunakan).length;
        $('a#confirm_save', $("#form_add_keuangan")).click(function(e) {
            
            if (! $("#form_add_keuangan").valid() ) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                     $('#savekeuangan',$("#form_add_keuangan")).click();  
                }
            });
            e.preventDefault();
        });
    };



   var handleDateRangePickers = function () {
        if (!jQuery().daterangepicker) {
            return;
        }

        $('#defaultrange', $form).daterangepicker({
                format: 'MM/DD/YYYY',
                separator: ' to ',
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                minDate: '01/01/2013',
                maxDate: '12/31/2100',
            },
            function (start, end) {
                console.log("Callback has been called!");
                $('#defaultrange input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );        
    }

    var handleDatePickers = function () {
         
            $('.date-picker', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'MM yyyy',
                autoclose: true,
                minViewMode:1,
            }).on('changeDate', function(e){
                oTablebiaya.api().ajax.url(baseAppUrl +  'listing_laporan_biaya/' + $("#userid").val() + '/' + $('#date1').val()).load();
               // alert($('#date1').val());
            });

             $('.date-picker2', $("#form_add_keuangan")).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
                autoclose: true,
                
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal

            
         
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'reservasi/keuangan/';
        handleValidation();
         handleValidation2();
      handleDataTableItems();
       
        initForm(); 
        handleConfirmSave();
        handleDatePickers();
       // handleDateRangePickers();
    };

}(mb.app.resep_obat.add));


// initialize  mb.app.resep_obat.add
$(function(){
    mb.app.resep_obat.add.init();
});