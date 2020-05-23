mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl          = '',
        $form               = $('#form_addtemplate'),
        $tableAddAccount    = $('#table_add_account', $form),
        $tableAccountSearch = $('#table_account_search'),
        $tableItemSearch    = $('#table_item_search'),
        $tableInformation    = $('#table_information'),
        $popoverItemContent     = $('#popover_item_content'),
        $lastPopoverItem    = null,
        tplItemRow          = $.validator.format( $('#tpl_item_row').text() ),
        tplItemAccRow          = $.validator.format( $('#tpl_item_acc_row').text() ),
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
        addItemAccRow();
        addItemRow();
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
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_account',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
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
            // alert('di klik');
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
                $itemTypeEl = $('input[name$="[account_type]"]', $row);
                $itemCodeEl = $('input[name$="[code]"]', $row);
                $itemNameEl = $('input[name$="[name]"]', $row);
                $('.search-account', $tableAddAccount).popover('hide');
                // $('.search-item-result', $tableAddAccount).prop('disabled', false);
           
            $itemidEl.val($(this).data('item')['id']);
            $itemTypeEl.val($(this).data('item')['akun_tipe']);
            $itemCodeEl.val($(this).data('item')['no_akun']);
            $itemNameEl.val($(this).data('item')['nama']);
            
            addItemRow();
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
                addItemRow();
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
            alert('di klik');
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
            $itemCodeEl.val($(this).data('item')['account_no']);
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

    var handleBootstrapSelect = function($btn,name) {
        $btn.on('change', function(){

             var 
                rowId = parseInt(itemCounter-1),
                rowPlusId = parseInt(itemCounter-2) || parseInt(itemCounter-3) || parseInt(itemCounter-4) || parseInt(itemCounter-5),
                $row     = $('#item_row_'+rowId, $tableAddAccount),
                $rowPlus     = $('.row_plus', $tableAddAccount);
        
            if($(this).prop('checked'))
            {
                // var name = $(this).data('name');
                // alert(name);
                $rowPlus.show();
                $('input[name$="[name]"]', $rowPlus).val(name);
                $('input[name$="[account_type]"]',$rowPlus).val(1);
                $('input[name$="[name]"]',$rowPlus).attr('readonly','readonly');
            }
            
            else{
                $('input[name$="[name]"]', $rowPlus).val('');
                $('input[name$="[account_type]"]',$rowPlus).val('');
                $('input[name$="[name]"]',$rowPlus).attr('readonly','readonly');
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

var handleCheckBoxChange = function(){
    $('input#acc_payable').on('change', function()
    {
        if ($('input#acc_payable').prop('checked')) {
            // alert('CEKBOX DI KLIK checked');
             $('input[name$="[name]"]').val('Akun Hutang Supplier Bersangkutan');
             addItemRow();
        }else{
            alert('CEKBOX DI KLIK UNchecked');
        }
    }
)};
   
    

 var handleDropdownTypeChange = function()
    {
        $('#tipe_transaksi').on('change', function(){
            var tipeId = this.value;
            var $type_t = $('label#type_t');
            
            if(tipeId == 5 )
            {
                $('div.section-1').addClass('hidden');
            }
            else
            {
                var $check = $('input#acc_payable');

                $('div.section-1').removeClass('hidden');
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_account_type',
                    data     : {tipeId: tipeId},
                    dataType : 'json',
                    success  : function( results ) {
                        var name = results[0]["nama"];
                        $type_t.text(results[0]["subjek"]);
                        // $check.removeProp('checked');
                        $check.attr('data-name',results[0]["nama"]);

                    handleBootstrapSelect($check,name);

                    }
                });
                
            }


            //    oTable.fnSettings().sAjaxSource = baseAppUrl + 'listing_transaction_detail/' + tipeId;
            // oTable.fnClearTable(); 
        });
    }

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

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'akunting/jurnal_template/';
        handleValidation();
        initForm();
        handleDatePickers();
        // handleBootstrapSelect();
        handleConfirmSave();
        handleDTItems();
        handleDataTableItems();
        handleDropdownTypeChange();
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});