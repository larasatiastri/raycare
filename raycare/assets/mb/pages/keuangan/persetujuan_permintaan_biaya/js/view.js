mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl            = '',
        $form                 = $('#form_view_persetujuan_permintaan_biaya'),
        $tablePilihUser       = $('#table_pilih_user'),
        $popoverPasienContent = $('#popover_pasien_content'), 
        $lastPopoverItem      = null,
        
        tplFormPayment        = '<li class="fieldset">' + $('#tpl-form-payment', $form).val() + '<hr></li>',
        regExpTpl             = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        paymentCounter        = 0,
        
        forms                 = {
            'payment' : {            
                section  : $('#section-payment', $form),
                template : $.validator.format( tplFormPayment.replace(regExpTpl, '_ID_{0}') ), //ubah ke format template jquery validator
                counter  : function(){ paymentCounter++; return paymentCounter-1; }
            }      
        };

        totalBayar                  = 0


        ;

       var initForm = function(){
      
        $.each(forms, function(idx, form){

            // handle button add
            $('a.add-payment', form.section).on('click', function(){
                // addFieldset(form);
            });

            // beri satu fieldset kosong
            // addFieldset(form);

        });

        var $btnSearchUser  = $('.pilih-user', $form);
        handleBtnSearchUser($btnSearchUser);
        

    };

    var handleSelectSection = function(value,$fieldset)
    {
        if(value == 1)
        {
            $('div#section_1', $fieldset).show();
            $('div#section_2', $fieldset).hide();
            $('div#section_3', $fieldset).hide();
        }
        if(value == 2)
        {
            $('div#section_1', $fieldset).show();
            $('div#section_2', $fieldset).show();
            $('div#section_3', $fieldset).hide();
        }
        if(value == 3)
        {
            $('div#section_3', $fieldset).show();
            $('div#section_2', $fieldset).hide();
            $('div#section_1', $fieldset).show();
        }
    }

    var addFieldset = function(form){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer)
            ;

        handleDatePickersBayar();
        $('select.payment_type').val(1);
        
        handleSelectSection(1, $newFieldset);

        $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
            handleSelectSection(this.value, $newFieldset);
        });
        

        $('a.del-this-payment', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });

        $('input[name$="[nominal]"]', $newFieldset).on('change keypress keyup', function(){
                total_payment = 0;
            $.each($('input.payment_cash'), function(){
                payment = parseInt($(this).val());
                total_payment = total_payment + payment;
            });
            $('input#bayar').val(mb.formatTanpaRp(total_payment));
            $('input#bayar_hidden').val(total_payment);

            
            var cash = parseInt(total_payment);
            var grand_total_klaim = parseInt($('input#grand_total_klaim_hidden').val());
            var grand_total = parseInt($('input#grand_total_hidden').val());
            
            // alert(grand_total);

            //     // alert(cash);

            totalBayar = cash - grand_total ;
            
            // alert(totalBayar);

            $('input#kembali').val(mb.formatTanpaRp(totalBayar));
            $('input#kembali_hidden').val(totalBayar);

            if (!isNaN(totalBayar)){

            $('input#kembali').val(mb.formatTanpaRp(totalBayar));
            $('input#kembali_hidden').val(totalBayar);
            
            } else {

            $('input#kembali').val(0);
            $('input#kembali_hidden').val(0);

            }
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'silver');
        $('input#last_count').val(counter);

        // calculateTotal();
        // checkedTotal();

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

        if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.

        $fieldset.remove();
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
            // alert('klik');
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
        var time = new Date($('#tanggal').val());
        if (jQuery().datepicker) {
            $('.date-picker', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                orientation: "left",
                autoclose: true,
                update : time

            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleDatePickersBayar = function () {
        var time = new Date($('#waktu_selesai').val());
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true,
                // update : time

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

    var handleSelectPoli = function(id){

        $('#poli').select2({

            placholder : 'Pilih Poli',
            allowClear : true

        });
    }

    var handleSelect2 = function () {

        $("#poli").select2({
            // tags: ["developer@oriensjaya.com", "admin@oriensjaya.com", "amir@oriensjaya.com", "udin@oriensjaya.com", "usro@oriensjaya.com", "ujang@oriensjaya.com", "ipul@oriensjaya.com"]
        });

    };

    var handlePilihUser = function(){
        $tablePilihUser.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_user',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#ttable_pilih_user_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#ttable_pilih_user_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $tablePilihUser);
        handlePilihUserSelect( $btnSelects );

        $tablePilihUser.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihUserSelect( $btnSelect );
            
        } );

        $popoverPasienContent.hide();        
    };

    var handleBtnSearchUser = function($btn){
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

            $popoverPasienContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPasienContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverPasienContent.hide();
            $popoverPasienContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

     var handlePilihUserSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefUser   = $('input[name="nama_ref_user"]'),
                $IdRefUser   = $('input[name="id_ref_pasien"]'),
                $cabang_id   = $('input[name="cabang_id"]'),
                $user_level_id   = $('input[name="user_level_id"]'),
                $itemCodeEl = null,
                $itemNameEl = null
                ;        


            $('.pilih-user', $form).popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdRefUser.val($(this).data('item').id_user);
            $cabang_id.val($(this).data('item').cabang_id);
            $namaRefUser.val($(this).data('item').nama_user);
            $user_level_id.val($(this).data('item').user_level_id);

            // alert($itemIdEl.val($(this).data('item').id));


            e.preventDefault();
        });     
    };


    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/persetujuan_permintaan_pembayaran/';
        handleValidation();
        // calculateTotalKeseluruhan();
        initForm();
        handleDatePickers();
        handleDatePickersBayar();
        // handleBootstrapSelect();
        handleSelect2();    
        handleConfirmSave();
        handlePilihUser();
        // handleDropdownTypeChange();
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});