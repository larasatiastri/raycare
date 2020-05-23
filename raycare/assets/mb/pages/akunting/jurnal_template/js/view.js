mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl          = '',
        $form               = $('#form_view_template'),
        $tableAddAccount    = $('#table_add_account', $form),
        $tableAccountSearch = $('#table_account_search'),
        $tableItemSearch    = $('#table_item_search'),
        $tableInformation    = $('#table_information'),
        $popoverItemContent     = $('#popover_item_content'),
        $lastPopoverItem    = null,
        // tplItemRow          = $.validator.format($('#tpl_item_row').text()),
        // tplItemAccRow          = $.validator.format( $('#tpl_item_acc_row').text() ),
        type_trans =  $('input[name="tipe_transaksi"]').val(),
        itemCounter         = 9
        ;

       var initForm = function(){
      

        var 
            $btnSearchAccount = $('.search-account', $tableAddAccount),
            $btnDeletes = $('.del-this', $tableAddAccount);

        handleBtnSearchAccount($btnSearchAccount);  


        
        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        // tambah 1 row kosong pertama
        // addItemAccRow();
        // addItemRow();
        $('.row_plus', $tableAddAccount).hide();

    };
    

    var addItemRow = function(){

        var numRow = $('tbody tr', $tableAddAccount).length;
        var 
            $rowContainer         = $('tbody', $tableAddAccount),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchAccount  = $('.search-account', $newItemRow)
            ;

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
        // handle button search item
        handleBtnSearchAccount($btnSearchAccount);

        $('.type', $newItemRow).bootstrapSwitch();
        handleBootstrapSelectType($('.type', $newItemRow));
    };

    var addItemAccRow = function(){

        var numRow = $('tbody tr', $tableAddAccount).length;
        var 
            $rowContainer         = $('tbody', $tableAddAccount),
            $newItemRow           = $(tplItemAccRow(itemCounter++)).appendTo( $rowContainer ),
            $btnAddAccount  = $('.add_row', $newItemRow)
            ;

        // handle delete btn
        $('.del-this-plus', $newItemRow).on('click', function(e){
            $('input[name$="[var]"]', $newItemRow).val('');
            e.preventDefault();
        });
        // handle button search item
        // handleAddAcc($btnAddAccount);

        $('.type', $newItemRow).bootstrapSwitch();
        handleBootstrapSelectType($('.type', $newItemRow));

    };
     
     var handleBtnSearchSales = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'right',
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
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
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

    var handleDataTableItems = function(){
        $tableItemSearch.dataTable({
           'bProcessing'              : true,
            'bServerSide'              : true,
            'sServerMethod'            : 'POST',
            'oLanguage'                : mb.DTLanguage(),
            'sAjaxSource'              : baseAppUrl + 'listing_account',
            'iDisplayLength'           : 5,
            'aLengthMenu'              : [[2, 5, 10, 25, 50, 100], [2, 5, 10, 25, 50, 100]],
            'aaSorting'                : [[0, 'asc']],
            'aoColumns'                : [
                { 'bVisible' : true, 'bSearchable': true, 'bSortable': true },
                { 'bVisible' : true, 'bSearchable': false, 'bSortable': true },
                { 'bVisible' : true, 'bSearchable': false, 'bSortable': false }
                ],
        });       
        $('#table_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableItemSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handleAccountSelect( $btnSelect );
            
        } );

        $popoverItemContent.hide();        
    };

    
  

    var handleAccountSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableAddAccount),
                $itemCodeEl = null,
                $itemNameEl = null, 
                $itemQtyEl  = $('input[name$="[name]"]', $row)
                ;                
            // console.log(itemTarget);
           
                $itemidEl = $('input[name$="[account_id]"]', $row);
                $itemCodeEl = $('input[name$="[code]"]', $row);
                $itemNameEl = $('input[name$="[name]"]', $row);
                $('.search-account', $tableAddAccount).popover('hide');
                // $('.search-item-result', $tableAddAccount).prop('disabled', false);
           
            $itemidEl.val($(this).data('item')['id']);
            $itemCodeEl.val($(this).data('item')['no_account']);
            $itemNameEl.val($(this).data('item')['name']);
            
            // addItemRow();
            e.preventDefault();   
        });     
    };


    var handleBtnDelete = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableAddAccount);

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableAddAccount).length == 0){
                // addItemRow();
            }
            e.preventDefault();
        });
    };

   

    var handleBtnSearchAccount = function($btn){
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

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverItemContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
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

    var handleItemSearchSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableAddAccount),
                $itemCodeEl = null,
                $itemNameEl = null
               ;                
            // console.log(itemTarget);
                $itemidEl = $('input[name$="[account_id]"]', $row);
                $itemCodeEl = $('input[name$="[code]"]', $row);
                $itemNameEl = $('input[name$="[name]"]', $row);
                $('.search-account', $tableAddAccount).popover('hide');
               
           
            
              $itemidEl.val($(this).data('item')['id']);
            $itemCodeEl.val($(this).data('item')['no_account']);
            $itemNameEl.val($(this).data('item')['name']);
              addItemRow();

            e.preventDefault();   
        });     
    };

    var handleDTItems = function(){
        $tableAccountSearch.dataTable({
            "aoColumnDefs": [
                { "aTargets": [ 0 ] }
            ],
            "aaSorting": [[1, 'asc']],
             "aLengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "iDisplayLength": 5,
        });       
        $('#table_account_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_account_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $tableAccountSearch);
        handleItemSearchSelect( $btnSelects );

        $tableAccountSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handleItemSearchSelect( $btnSelect );
            
        } );

        $popoverItemContent.hide();   
             
    };

    var handleBootstrapSelect = function() {
        $('#acc-payable', $form).on('switchChange.bootstrapSwitch', function (event, state) {
        
        
            var 
                rowId = parseInt(itemCounter-1),
                rowPlusId = parseInt(itemCounter-2) || parseInt(itemCounter-3) || parseInt(itemCounter-4) || parseInt(itemCounter-5),
                $row     = $('#item_row_'+rowId, $tableAddAccount),
                $rowPlus     = $('.row_plus', $tableAddAccount);

            if($(this).parent().parent().prop('class')=='bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-normal bootstrap-switch-id-acc-payable bootstrap-switch-focused bootstrap-switch-animate bootstrap-switch-on')
            {
                $rowPlus.show();
                $('input[name$="[names]"]').val('Account hutang supplier bersangkutan');
                $('input[name$="[names]"]').attr('disabled','disabled');
            }
            else{
                $rowPlus.hide();
            }
           
        });

        $('#acc-receivable', $form).on('switchChange.bootstrapSwitch', function (event, state) {
            console.log($(this).parent().parent().prop('class'));
            var 
                rowId = parseInt(itemCounter-1),
                rowPlusId = parseInt(itemCounter-2) || parseInt(itemCounter-3) || parseInt(itemCounter-4) || parseInt(itemCounter-5),
                $row     = $('#item_row_'+rowId, $tableAddAccount),
                $rowPlus     = $('.row_plus', $tableAddAccount);

            if($(this).parent().parent().prop('class')=='bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-normal bootstrap-switch-id-acc-receivable bootstrap-switch-focused bootstrap-switch-animate bootstrap-switch-on')
            {
                $rowPlus.show();
                $('input[name$="[names]"]').val('Account piutang customer bersangkutan');
                $('input[name$="[names]"]').attr('disabled','disabled');

            }
            else{
                $rowPlus.hide();
            }
        });

        $('#deduct-payable', $form).on('switchChange.bootstrapSwitch', function (event, state) {
            console.log($(this).parent().parent().prop('class'));
            var 
                rowId = parseInt(itemCounter-1),
                rowPlusId = parseInt(itemCounter-2) || parseInt(itemCounter-3) || parseInt(itemCounter-4) || parseInt(itemCounter-5),
                $row     = $('#item_row_'+rowId, $tableAddAccount),
                $rowPlus     = $('.row_plus', $tableAddAccount);

            if($(this).parent().parent().prop('class')=='bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-normal bootstrap-switch-id-deduct-payable bootstrap-switch-focused bootstrap-switch-animate bootstrap-switch-on')
            {
                $rowPlus.show();
                $('input[name$="[names]"]').val('Account hutang supplier bersangkutan');
                $('input[name$="[names]"]').attr('disabled','disabled');
            }
            else{
                $rowPlus.hide();
            }
        });

        $('#deduct-receivable', $form).on('switchChange.bootstrapSwitch', function (event, state) {
            console.log($(this).parent().parent().prop('class'));
            var 
                rowId = parseInt(itemCounter-1),
                rowPlusId = parseInt(itemCounter-2) || parseInt(itemCounter-3) || parseInt(itemCounter-4) || parseInt(itemCounter-5),
                $row     = $('#item_row_'+rowId, $tableAddAccount),
                $rowPlus     = $('.row_plus', $tableAddAccount);

            if($(this).parent().parent().prop('class')=='bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-normal bootstrap-switch-id-deduct-receivable bootstrap-switch-focused bootstrap-switch-animate bootstrap-switch-on')
            {
                
                $rowPlus.show();
                $('input[name$="[names]"]').val('Account piutang customer bersangkutan');
                $('input[name$="[names]"]').attr('disabled','disabled');
            }
            else{
                $rowPlus.hide();
            }
        });
    };

    var handleBootstrapSelectType = function($selector)
    {
        $selector.on('switchChange.bootstrapSwitch', function (event, state) {
            console.log($(this).parent().parent().prop('class'));
        });
    };

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

    var handleType = function(){
        

        $('#selector1').on('change', function(){
            var option = $('#selector1').val();
            var $rowPlus     = $('.row_plus', $tableAddAccount);
            // alert(option);
            if((option == 1)){
                $('.section-1').show();
                $('.section-2').hide();
                $('.section-3').hide();
                $('.section-4').hide();
                $('.section-5').hide();
                // alert('asd');
            }
            else if((option == 2)){
                $('.section-1').hide();
                $('.section-2').show();
                $('.section-3').hide();
                $('.section-4').hide();
                $('.section-5').hide();
                // alert('asd');
            }
            else if((option == 3)){
                $('.section-1').hide();
                $('.section-2').hide();
                $('.section-3').show();
                $('.section-4').hide();
                $('.section-5').hide();
            }
            else if((option == 4)){
                $('.section-1').hide();
                $('.section-2').hide();
                $('.section-3').hide();
                $('.section-4').show();
                $('.section-5').hide();
            }
            else if((option == 5)){
                $('.section-1').hide();
                $('.section-2').hide();
                $('.section-3').hide();
                $('.section-4').hide();
                $('.section-5').show();
            }

            $('input[name$="[names]"]').val('');
            $rowPlus.hide();
        });

    
            
    }

    var handleDatePickers = function () {
        var time = new Date($('#waktu_selesai').val());
        if (jQuery().datetimepicker) {
            $('.date', $form).datetimepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy hh:ii:ss',
                autoclose: true,
                update : time

            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleDataTableInfo = function(){
         oTable = $tableInformation.dataTable({
            'bProcessing'              : true,
            'bServerSide'              : true,
            'sServerMethod'            : 'POST',
            'oLanguage'                : mb.DTLanguage(),
            'sAjaxSource'              : baseAppUrl + 'listing_transaction_detail/' + type_trans,
            'iDisplayLength'           : 25,
            'aLengthMenu'              : [[25, 50, 100], [25, 50, 100]],
            'aaSorting'                : [[0, 'asc']],
            'aoColumns'                : [
                { 'bVisible' : true, 'bSearchable': true, 'bSortable': true },
                { 'bVisible' : true, 'bSearchable': true, 'bSortable': true }
                ],
        }); 
        oTable.fnFilter(2, 4);      
    };
    
    

 var handleDropdownTypeChange = function()
    {
        $('#tipe_transaksi').on('change', function(){
            var tipeId = this.value;
            var $type_t = $('label#type_t');

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_account_type',
                data     : {tipeId: tipeId},
                dataType : 'json',
                success  : function( results ) {
                // $type_t.text(results.subject);
                // alert(results["subject"]);

                    $.each(results, function() {
                            alert(tipeId);
                    });

                }
            });

               oTable.fnSettings().sAjaxSource = baseAppUrl + 'listing_transaction_detail/' + tipeId;
            oTable.fnClearTable(); 
        });
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'akunting/jurnal_template/';
        initForm();
        handleDatePickers();
        handleBootstrapSelect();
        handleType();
        handleConfirmSave();
        handleDTItems();
        handleDataTableItems();
        handleDataTableInfo();
        handleDropdownTypeChange();
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});