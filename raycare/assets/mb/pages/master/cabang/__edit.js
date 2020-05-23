mb.app.cabang = mb.app.cabang || {};
mb.app.cabang.add = mb.app.cabang.add || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form = $('#form_add_cabang'),
        $tablePoliklinikDokter = $('#table_poliklinik_dokter', $form),
        $btnAddItem    = $('.add-poliklinik-dokter', $form),
        tplItemRow     = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter    = 1,


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
    };

    var addFieldset = function(form)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer)
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

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');
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
                $('#defaultrange_modal input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        ); 
    }


     var handleSelectProvinsi = function()
     {
        $('select#negara').on('change', function(){

            //$('input#warehouse_id').val($(this).val());
            
             var $provinsi_select = $('#provinsi');

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_provinsi',
                data     : {id_negara: $(this).val()},
                dataType : 'json',
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $provinsi_select.empty();

                    $.each(results, function(key, value) {
                        $provinsi_select.append($("<option></option>")
                            .attr("value", value.id).text(value.format_provinsi));
                        $provinsi_select.val('');

                    });
                }
            });
        })
    }

     var handleSelectKota = function(){
        $('select#provinsi').on('change', function(){

            //$('input#warehouse_id').val($(this).val());

            var $kota_select = $('#kota_kabupaten');
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kota',
                data     : {id: $(this).val()},
                dataType : 'json',
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $kota_select.empty();

                    $.each(results, function(key, value) {
                        $kota_select.append($("<option></option>")
                            .attr("value", value.id).text(value.format_kota));
                        $kota_select.val('');

                    });
                }
            });
        })
    }

    var handleSelectKecamatan = function(){
        $('select#kota_kabupaten').on('change', function(){

            //$('input#warehouse_id').val($(this).val());

            var $kecamatan_select = $('#kecamatan');
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kecamatan',
                data     : {id: $(this).val()},
                dataType : 'json',
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $kecamatan_select.empty();

                    $.each(results, function(key, value) {
                        $kecamatan_select.append($("<option></option>")
                            .attr("value", value.id).text(value.format_kecamatan));
                        $kecamatan_select.val('');

                    });
                }
            });
        })
    }

    var handleSelectKelurahan = function(){
        $('select#kecamatan').on('change', function(){

            //$('input#warehouse_id').val($(this).val());

            var $kelurahan_select = $('#kelurahan_desa');
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kelurahan',
                data     : {id: $(this).val()},
                dataType : 'json',
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $kelurahan_select.empty();

                    $.each(results, function(key, value) {
                        $kelurahan_select.append($("<option></option>")
                            .attr("value", value.id).text(value.format_kelurahan));
                        $kelurahan_select.val('');

                    });
                }
            });
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
        handleSelectProvinsi();
        handleSelectKota();
        handleSelectKecamatan();
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
    };
 }(mb.app.cabang.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.add.init();
});