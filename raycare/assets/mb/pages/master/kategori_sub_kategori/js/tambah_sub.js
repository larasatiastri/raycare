
mb.app.kategori_sub_kategori = mb.app.kategori_sub_kategori || {};
(function(o){

    var 
        baseAppUrl         = '',
        $form              = $('#form_tambah_sub'),
        tplFormSpesifikasi = '<li class="fieldset">' + $('#tpl-form-spesifikasi', $form).val() + '<hr></li>',
        $tablePersetujuan  = $('#table_persetujuan', $form),
        $tableUserlevel    = $('#table_user_level');
        $lastPopoverItem   = null,
        tplPersetujuanRow  = $.validator.format( $('#tpl_item_row_persetujuan').text()),
        itemCounter        = 1,
        itemCounter2       = 0,
        tplItemRow2        = $.validator.format($('#tpl_item_row').text()),
        itemCounter3       = 0,
        regExpTpl          = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        spesifikasiCounter = 1

    ;

    var forms = {
        'payment' : {            
            section  : $('#section-spesifikasi', $form),
            template : $.validator.format(tplFormSpesifikasi.replace(regExpTpl, '_ID_{0}')),        //ubah ke format template jquery validator
            counter  : function(){ spesifikasiCounter++; return spesifikasiCounter-1; }
        }      
    };

    var initForm = function(){

        $.each(forms, function(idx, form){

            // handle button add
            $('a.tambah-spesifikasi', form.section).on('click', function(){
                addFieldset(form);
            });

            // beri satu fieldset kosong
            addFieldset(form);
        });


        // TAB PEMBELIAN
        $btnDelete = $('a.del-this', $tablePersetujuan);

        $.each($btnDelete, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        addPersetujuanRow();

    };

    var addPersetujuanRow = function(){

        var numRow = $('tbody tr', $tablePersetujuan).length;
        console.log('numrow ' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer         = $('tbody', $tablePersetujuan),
            $newItemRow           = $(tplPersetujuanRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;
        // handle delete btn
        handleBtnDeletePersetujuan( $('a.del-this', $newItemRow) );
    };

    var handleBtnDeletePersetujuan = function($btn){
        var numRow = $('tbody tr', $tablePersetujuan).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuan);

        $btn.on('click', function(e){
            
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        $row.remove();
                        if($('tbody>tr', $tablePersetujuan).length == 0){
                            addPersetujuanRow();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            e.preventDefault();
        });
    };

    // var isValidLastRow = function(){
        
    //     var 
    //             $itemCodeEls = $('input[name$="[name]"]', $tablePersetujuan),
    //             // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
    //             itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
    //             // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
    //         ;

    //    // var rowId    = $this('tr').prop('id');
    //     //alert(rowId);
    //         // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
    //         return (itemCode != '');

    // };

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

    var addFieldset = function(form){
        
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer)
        ;

        console.log(counter);


        $('input[name$="[judul]"]', $newFieldset);
        
        $('select[name$="[spesifikasi_type]"]', $newFieldset).on('change', function(){
            handleSelectSection(this.value, $newFieldset);
        });

        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('input[name$="[count]"]', $newFieldset).val(counter);
        $('hr', $newFieldset).css('border-color', 'rgb(238, 238, 238)');
        
        // $('input#last_count').val(counter);
        addItemRow($('table[name$="[table_item]"]', $newFieldset), counter);

        $('a[name$="[add_item]"]', $newFieldset).on('click', function(e){
            // alert('a');
            // var 
            //     counter            = form.counter(),
            //     $newFieldset       = $(form.template(counter))
            // ;
            
            addItemRow($('table[name$="[table_item]"]', $newFieldset), counter);
        }); 
    };

    var addItemRow = function(table, counter)
    {
        // var tableItem = $('table[name$="[table_item]"]', $newFieldset)
        // var tplItemRow2    = $.validator.format($('#tpl_item_row').text());
        // var itemCounter3 = 10;
        // var numRow = $('tbody tr', $tableItem).length;
        // alert(numRow);
        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau

        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer         = $('tbody', table),
            $newItemRow           = $(tplItemRow2(itemCounter3++)).appendTo( $rowContainer )
        ;  
        console.log($('#tpl_item_row').text());
        
        $('.text', $newItemRow).attr('name', counter + $('.text', $newItemRow).attr('name'));
        $('.value', $newItemRow).attr('name', counter + $('.value', $newItemRow).attr('name'));
        
        // alert(itemCounter);
        // alert(tplItemRow(itemCounter++));
        // handleAddItem($('.add-item'));

        // handle delete btn
        handleBtnDelete( $('.del-item', $newItemRow), table);

    };


    var handleDataTable = function() 
    {
        $tableUserlevel.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'paginate'              : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_persetujuan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableUserlevel.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
            
            var $btnSelects = $('a#select', $tableUserlevel);
            handleItemSearchSelect($btnSelects);             

        });
    };

    var handleItemSearchSelect = function($btn){
        $btn.on('click', function(e){
            //alert('a');
            //var numRow = $('tbody tr', $tablePersetujuan).length;
            //alert(numRow);
            var numRow = itemCounter++ - 1;
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val()
                $row        = $('#item_row_'+numRow, $tablePersetujuan),

                $itemIdEl = null,
                $itemNameEl = null; 
                //$itemQtyEl  = $('input[name$="[stock][qty]"]', $row);                
            // console.log(itemTarget);
            
                $itemIdEl = $('input[name$="[user_level_id]"]', $row);
                $itemNameEl = $('input[name$="[name]"]', $row);
                $itemLabelNameEl = $('label[name$="[lblname]"]', $row);
                //$('.search-item-init', $tableProcessItem).popover('hide');
                // $('.search-item-result', $tableProcessItem).prop('disabled', false);
            
            $itemIdEl.val($(this).data('item').id);
            $itemNameEl.val($(this).data('item').nama);
            $itemLabelNameEl.text($(this).data('item').nama);
            //calculateTotal();
            $('#closeModal').click();
            e.preventDefault();
            addPersetujuanRow();
        });     
    };

    var handleDeleteFieldset = function($fieldset){
        
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset', $parentUl).length,
            hasId         = false,  //punya id tidak, jika tidak bearti data baru
            hasDefault    = 0,      //ada tidaknya fieldset yang di set sebagai default, diset ke 0 dulu
            $inputDefault = $('input:hidden[name$="[is_default]"]', $fieldset), 
            isDefault     = $inputDefault.val() == 1
        ; 

        if (fieldsetCount <= 1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.

        $fieldset.remove();
    };

    var handleSelectSection = function(value, $fieldset)
    {
        if(value == 1)
        {
            $('div#section_1', $fieldset).show();
            $('div#section_4', $fieldset).hide();
        }
        if (value == 2) 
        {
            $('div#section_1', $fieldset).hide();
            $('div#section_4', $fieldset).hide();
        }
        if (value == 3) 
        {
            $('div#section_1', $fieldset).hide();
            $('div#section_4', $fieldset).hide();
        }
        if(value == 4)
        {
            $('div#section_1', $fieldset).hide();
            $('div#section_4', $fieldset).show();
        }
        if(value == 5)
        {
            $('div#section_1', $fieldset).hide();
            $('div#section_4', $fieldset).show();
        }
        if(value == 6)
        {
            $('div#section_1', $fieldset).hide();
            $('div#section_4', $fieldset).show();
        }
        if(value == 7)
        {
            $('div#section_1', $fieldset).hide();
            $('div#section_4', $fieldset).show();
        }

    }
    

    var handleBtnDelete = function($btn, $tableItem)
    {
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableItem);

        $btn.on('click', function(e){
        // alert();
            $row.remove();
            if($('tbody>tr', $tableItem).length == 0){
                addItemRow();
            }
            e.preventDefault();
        });
    };

    var handleAddItem = function($btn)
    {
        $('.add-item').on('click', function(e){
            // alert('a');
            addItemRow();
            // focusLastItemCode();
            e.preventDefault();
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
    }

    

    // var isValidLastRow = function(){
        
    //     var 
    //         $itemCodeEls = $('select[name$="[subject]"]', $tablePoliklinikDokter),
    //         // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
    //         itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
    //         // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
    //         ;
    //         // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
    //         return (itemCode != '');
    // };


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/kategori_sub_kategori/';
        initForm();
        handleConfirmSave();
        handleValidation();
        handleDataTable();
    };

 }(mb.app.kategori_sub_kategori));


// initialize  mb.app.home.table
$(function(){
    mb.app.kategori_sub_kategori.init();
});