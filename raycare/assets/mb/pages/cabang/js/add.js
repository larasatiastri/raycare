mb.app.cabang = mb.app.cabang || {};
mb.app.cabang.add = mb.app.cabang.add || {};
(function(o){

    var 
        baseAppUrl             = '',
        $form                  = $('#form_add_cabang'),
        $tablePoliklinikDokter = $('#table_poliklinik_dokter', $form),
        $btnAddItem            = $('.add-poliklinik-dokter', $form),
        $btnSubjekAlamat       = $('a#btn_edit_subjek_alamat'),
        $btnEditNegara         = $('a#btn_edit_negara'),
        $btnEditProvinsi       = $('a#btn_edit_provinsi'),
        $btnEditKota           = $('a#btn_edit_kota'),
        $btnEditKecamatan      = $('a#btn_edit_kecamatan'),
        $btnEditKelurahan      = $('a#btn_edit_kelurahan'),
        tplItemRow             = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter            = 1,


        tplFormPhone = '<li class="fieldset">' + $('#tpl-form-phone', $form).val() + '<hr></li>',
        regExpTplPhone   = new RegExp('phone[0]', 'g'),   // 'g' perform global, case-insensitive
        phoneCounter     = 1;

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

    var forms2 = 
    {
        'phone' : 
        {            
            section  : $('#section-telepon', $form),
            template : $.validator.format( tplFormPhone.replace(regExpTplPhone, '_id_{0}') ), //ubah ke format template jquery validator
            counter  : function(){ phoneCounter++; return phoneCounter-1; }
        }   
    };

    var initForm = function()
    {
        $.each(forms2, function(idx, form){
            // handle button add
            $('a.add-phone', form.section).on('click', function(){
                addFieldset(form);
            });
             
            // beri satu fieldset kosong
            addFieldset(form);
        });

        $btnSubjekAlamat.on('click', function(){
            handleEditSubjekAlamat();
        });

         // handle button add row item
        $btnAddItem.on('click', function(e){
            // console.log('dgfdgdgfdgfdgfdgf');
            addItemRow();
            // focusLastItemCode();
            e.preventDefault();
        }); 

        var $btnDeletes = $('.del-this', $tablePoliklinikDokter);

        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        handleTeleponIsPrimary();
    };

    var addFieldset = function(form)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            $btnSubjekTelp = $('a#btn_edit_subjek_telepon_' + counter)
            ;

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });

        $('input.address_radio', $newFieldset).on('change', function(){
            // alert($(this).prop('checked'));
            handleIsPrimary($(this).parents('.fieldset').eq(0));
        });

        $('input#primary_phone_1').prop('checked', true);
        $('input[name$="[is_primary]"]').val('');
        
        $('input#primary_id_1').val(1);
        $('input[name="phone_is_primary"]', $newFieldset).on('click', function()
        {
            $('input[name$="[is_primary]"]').val('');
            $('input[name$="[is_primary]"]', $newFieldset).val(1);
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        $btnSubjekTelp.on('click', function(){
            handleEditSubjekTelp(counter);
        });

        $('input[type=radio]', $newFieldset).uniform();

    };

    var handleIsPrimary = function($fieldset)
    {
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset', $parentUl).length,
            hasId         = false,  //punya id tidak, jika tidak bearti data baru
            hasDefault    = 0,      //ada tidaknya fieldset yang di set sebagai default, diset ke 0 dulu
            $inputDefault = $('input[name$="[is_primary]"]', $fieldset)
            ; 

            // if ($('input.address_radio').prop('checked')) {
            //     $inputDefault.val(1);
            // }else{
            //     $inputDefault.val(0);
            // }
    }

    var handleDeleteFieldset = function($fieldset)
    {        
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

    var handleEditSubjekTelp = function(counter){
        var $inputSubjekTelp = $('input#input_subjek_telepon_' + counter),
            $selectSubjekTelp = $('select#subjek_telepon_' + counter),
            $btnSaveSubjekTelp = $('a#btn_save_subjek_telepon_' + counter),
            $btnCancelSubjekTelp = $('a#btn_cancel_subjek_telepon_' + counter),
            $btnEditSubjekTelp = $('a#btn_edit_subjek_telepon_' + counter),
            $btnDeleteSubjekTelp = $('a#btn_delete_subjek_telepon_' + counter);

        $btnEditSubjekTelp.addClass("hidden");
        $btnDeleteSubjekTelp.addClass("hidden");
        $selectSubjekTelp.addClass("hidden");

        $btnSaveSubjekTelp.removeClass("hidden");
        $btnCancelSubjekTelp.removeClass("hidden");
        $inputSubjekTelp.removeClass("hidden");

        $inputSubjekTelp.focus();

        $btnCancelSubjekTelp.on('click', function(){
            handleCancelSubjekTelp(counter);
        });

        $btnSaveSubjekTelp.on('click', function(){
            handleSaveSubjekTelp(counter);
        });
    }

    var handleCancelSubjekTelp= function(counter){
        var $inputSubjekTelp = $('input#input_subjek_telepon_' + counter),
            $selectSubjekTelp = $('select#subjek_telepon_' + counter),
            $btnSaveSubjekTelp = $('a#btn_save_subjek_telepon_' + counter),
            $btnCancelSubjekTelp = $('a#btn_cancel_subjek_telepon_' + counter),
            $btnEditSubjekTelp = $('a#btn_edit_subjek_telepon_' + counter),
            $btnDeleteSubjekTelp = $('a#btn_delete_subjek_telepon_' + counter);

            $btnEditSubjekTelp.removeClass("hidden");
            $btnDeleteSubjekTelp.removeClass("hidden");
            $selectSubjekTelp.removeClass("hidden");

            $btnSaveSubjekTelp.addClass("hidden");
            $btnCancelSubjekTelp.addClass("hidden");
            $inputSubjekTelp.addClass("hidden");

            $inputSubjekTelp.val("");
    }

    var handleSaveSubjekTelp= function(counter){
        
        //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
        var $inputSubjekTelp = $('input#input_subjek_telepon_' + counter),
            $selectSubjekTelp = $('select#subjek_telepon_' + counter),
            $btnSaveSubjekTelp = $('a#btn_save_subjek_telepon_' + counter),
            $btnCancelSubjekTelp = $('a#btn_cancel_subjek_telepon_' + counter),
            $btnEditSubjekTelp = $('a#btn_edit_subjek_telepon_' + counter),
            $btnDeleteSubjekTelp = $('a#btn_delete_subjek_telepon_' + counter);
            

        if ($inputSubjekTelp.eq($inputSubjekTelp.length-1).val()) {
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_subjek',
                data     : {tipe: '2',
                            nama: $inputSubjekTelp.val()
                           },
                dataType : 'json',
                success  : function( results ) {
                    $btnEditSubjekTelp.removeClass("hidden");
                    $btnDeleteSubjekTelp.removeClass("hidden");
                    $selectSubjekTelp.removeClass("hidden");

                    $btnSaveSubjekTelp.addClass("hidden");
                    $btnCancelSubjekTelp.addClass("hidden");
                    $inputSubjekTelp.addClass("hidden");

                    $inputSubjekTelp.val("");

                    $selectSubjekTelp.empty();

                    //munculin index pertama Pilih..
                    $selectSubjekTelp.append($("<option></option>")
                            .attr("value", "").text("Pilih.."));
                        $selectSubjekTelp.val('');

                    //munculin semua data dari hasil post
                    $.each(results, function(key, value) {
                        $selectSubjekTelp.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $selectSubjekTelp.val('');

                    });
                }
            });   
        }    
    }

    var handleEditSubjekAlamat = function(){
            var $inputSubjekAlamat = $('input#input_subjek_alamat'),
                $selectSubjekAlamat = $('select#subjek_alamat'),
                $btnSaveSubjekAlamat = $('a#btn_save_subjek_alamat'),
                $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_alamat'),
                $btnEditSubjekAlamat = $('a#btn_edit_subjek_alamat');

            $btnEditSubjekAlamat.addClass("hidden");
            $selectSubjekAlamat.addClass("hidden");

            $btnSaveSubjekAlamat.removeClass("hidden");
            $btnCancelSubjekAlamat.removeClass("hidden");
            $inputSubjekAlamat.removeClass("hidden");

            $inputSubjekAlamat.focus();

            $btnCancelSubjekAlamat.on('click', function(){
                handleCancelSubjekAlamat();
            });

            $btnSaveSubjekAlamat.on('click', function(){
                handleSaveSubjekAlamat();
            });
    }  

    var handleCancelSubjekAlamat= function(){
        var $inputSubjekAlamat = $('input#input_subjek_alamat'),
            $selectSubjekAlamat = $('select#subjek_alamat'),
            $btnSaveSubjekAlamat = $('a#btn_save_subjek_alamat'),
            $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_alamat'),
            $btnEditSubjekAlamat = $('a#btn_edit_subjek_alamat');

            $btnEditSubjekAlamat.removeClass("hidden");
            $selectSubjekAlamat.removeClass("hidden");

            $btnSaveSubjekAlamat.addClass("hidden");
            $btnCancelSubjekAlamat.addClass("hidden");
            $inputSubjekAlamat.addClass("hidden");

            $inputSubjekAlamat.val("");
    }

    var handleSaveSubjekAlamat= function(){
        
        //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
        var $inputSubjekAlamat = $('input#input_subjek_alamat'),
            $selectSubjekAlamat = $('select#subjek_alamat'),
            $btnSaveSubjekAlamat = $('a#btn_save_subjek_alamat'),
            $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_alamat'),
            $btnEditSubjekAlamat = $('a#btn_edit_subjek_alamat');
            

        if ($inputSubjekAlamat.eq($inputSubjekAlamat.length-1).val()) {
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_subjek',
                data     : {tipe: '1',
                            nama: $inputSubjekAlamat.val()
                           },
                dataType : 'json',
                success  : function( results ) {
                    $btnEditSubjekAlamat.removeClass("hidden");
                    $selectSubjekAlamat.removeClass("hidden");

                    $btnSaveSubjekAlamat.addClass("hidden");
                    $btnCancelSubjekAlamat.addClass("hidden");
                    $inputSubjekAlamat.addClass("hidden");

                    $inputSubjekAlamat.val("");

                    $selectSubjekAlamat.empty();

                    //munculin index pertama Pilih..
                    $selectSubjekAlamat.append($("<option></option>")
                            .attr("value", "").text("Pilih.."));
                        $selectSubjekAlamat.val('');

                    //munculin semua data dari hasil post
                    $.each(results, function(key, value) {
                        $selectSubjekAlamat.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $selectSubjekAlamat.val('');

                    });
                }
            });   
        }    
    }

    var addItemRow = function()
    {
        var numRow = $('tbody tr', $tablePoliklinikDokter).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer         = $('tbody', $tablePoliklinikDokter),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;  

        $("select.phone_sub").select2({
            width : 250
        });

        $("select.phone").select2({
            width : 250
        });

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );

         handleTimePickers();
    };

    var handleBtnDelete = function($btn)
    {
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePoliklinikDokter);

        $btn.on('click', function(e){
                $row.remove();
                if($('tbody>tr', $tablePoliklinikDokter).length == 0){
                    addItemRow();
                }
            e.preventDefault();
        });
    };

   var isValidLastRow = function(){
        
        var 
            $itemCodeEls = $('select[name$="[subject]"]', $tablePoliklinikDokter),
            // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
            itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
            // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;
            // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
            return (itemCode != '');
    };

    var handleDeleteRow = function(id,msg)
    {
        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete/' +id;
            } 
        });
    
    };
	var handleConfirmSave = function()
    {
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

    var handleDateRangePickers = function () 
    {
        $('#defaultrange_modal').daterangepicker({
                opens: (Metronic.isRTL() ? 'left' : 'right'),
                format: 'MM/DD/YYYY',
                separator: ' to ',
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                minDate: '01/01/2012',
                maxDate: '12/31/2050',
            },
            function (start, end) {
                $('#defaultrange_modal input').val(start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
            }
        ); 
    }


    var handleTimePickers = function () {

        if (jQuery().timepicker) {
            $('.timepicker-default').timepicker({
                autoclose: true,
                showSeconds: true,
                minuteStep: 1
            });

            $('.timepicker-no-seconds').timepicker({
                autoclose: true,
                minuteStep: 5
            });

            $('.timepicker-24').timepicker({
                autoclose: true,
                minuteStep: 5,
                showSeconds: false,
                showMeridian: false
            });

            // handle input group button click
            $('.timepicker').parent('.input-group').on('click', '.input-group-btn', function(e){
                e.preventDefault();
                $(this).parent('.input-group').find('.timepicker').timepicker('showWidget');
            });
        }
    }

    var handleTeleponIsPrimary = function(){

        $('input[name="phone_is_primary"]').on('click', function()
        {   
            var id = $(this).data('id');

            $('input[name$="[is_primary_phone]"]').val('');
            $('input#primary_phone_id_' + id).val(1);
        })
    }

    var handleSelectTipe = function(){
        $('select#tipe').on('change', function(){

            var tipe = $(this).val(),
                input = $('input', $('#table_poliklinik_dokter')),
                select = $('select', $('#table_poliklinik_dokter'));


            if(tipe == 1) 
            {
                $('div#tipe_rumah_sakit').removeClass('hidden');
                // input.attr('required','required');
                // select.attr('required','required');
            }
            else
            {
                $('div#tipe_rumah_sakit').addClass('hidden');
                // input.removeAttr('required');
                // select.removeAttr('required');
            }
        })
    }


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/cabang/';
        handleValidation();
        handleConfirmSave();
        addItemRow();
        initForm();
        handleDateRangePickers();
        handleSelectTipe();
    };
 }(mb.app.cabang.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.add.init();
});