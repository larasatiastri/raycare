mb.app.supplier = mb.app.supplier || {};
mb.app.supplier.edit = mb.app.supplier.edit || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_edit_supplier'),
        $tableItemDetail      = $('#table_item_detail', $form),
        $counter              = $('#counter'),
        $popoverPasienContent = $('#popover_pasien_content'), 
        $lastPopoverItem      = null,
        $tablePilihPasien     = $('#table_pilih_pasien'),
        tplFormPhone          = '<li class="fieldset">' + $('#tpl-form-phone', $form).val() + '<hr></li>',
        tplFormAlamat         = '<li class="fieldset">' + $('#tpl-form-alamat', $form).val() + '<hr></li>',
        tplFormEmail          = '<li class="fieldset">' + $('#tpl-form-email', $form).val() + '<hr></li>',
        tplFormPembayaran     = '<li class="fieldset">' + $('#tpl-form-pembayaran', $form).val() + '<hr></li>',
        tplFormBank           = '<li class="fieldset">' + $('#tpl-form-bank', $form).val() + '<hr></li>',
        regExpTplPhone        = new RegExp('phone[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplAlamat       = new RegExp('alamat[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplEmail        = new RegExp('email[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplpPembayaran  = new RegExp('pembayaran[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplpBank        = new RegExp('bank[0]', 'g'),   // 'g' perform global, case-insensitive
        phoneCounter          = $('input#phone_counter').val(),
        alamatCounter         = $('input#alamat_counter').val(),
        emailCounter          = $('input#email_counter').val(),
        pembayaranCounter     = $('input#jml_data_bayar').val(),
        bankCounter           = $('input#jml_data').val(),
        formsPhone = {
                    'phone' : 
                    {            
                        section  : $('#section-telepon', $form),
                        template : $.validator.format( tplFormPhone.replace(regExpTplPhone, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ phoneCounter++; return phoneCounter-1; }
                    }   
                },
        formsAlamat = {
                    'alamat' : 
                    {            
                        section  : $('#section-alamat', $form),
                        template : $.validator.format( tplFormAlamat.replace(regExpTplAlamat, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ alamatCounter++; return alamatCounter-1; }
                    }   
                },
        formsEmail = {
                    'email' : 
                    {            
                        section  : $('#section-email', $form),
                        template : $.validator.format( tplFormEmail.replace(regExpTplEmail, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ emailCounter++; return emailCounter-1; }
                    }   
                };
        formsPembayaran = {
                    'pembayaran' : 
                    {            
                        section  : $('#section-pembayaran', $form),
                        template : $.validator.format( tplFormPembayaran.replace(regExpTplpPembayaran, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ pembayaranCounter++; return pembayaranCounter-1; }
                    }   
                };
        formsBank = {
                    'bank' : 
                    {            
                        section  : $('#section-bank', $form),
                        template : $.validator.format( tplFormBank.replace(regExpTplpBank, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ bankCounter++; return bankCounter-1; }
                    }   
                };


    var initForm = function(){

        $counter.val(alamatCounter);
        // tambah 1 row kosong pertama

        $.each(formsPhone, function(idx, form){
            // handle button add
            $('a.add-telp', form.section).on('click', function(){
                addFieldsetPhone(form);
                $('input#primary_id_1').prop('checked', true);
            });

            $('a.del-db', form.section).on('click', function(){
                var id = $(this).data('id');
                var msg = $(this).data('confirm');

                handleDeleteDbPhone(form, id, msg);
            });
            
            $('a.edit-phone', form.section).on('click', function(){
                var id = $(this).data('id');
                //alert(counter);
                var counterHP = null;
                handleEditSubjekTelp(counterHP, id, "tab_phone");
            });
            // beri satu fieldset kosong
            // addFieldsetPhone(form);
        });

        $.each(formsEmail, function(idx, form){
            // handle button add
            $('a.add-email', form.section).on('click', function(){
                addFieldsetEmail(form);
                $('input#primary_id_1').prop('checked', true);
            });

            $('a.del-db', form.section).on('click', function(){
                var id = $(this).data('id');
                var msg = $(this).data('confirm');

                handleDeleteDbEmail(form, id, msg);
            });
            
            // beri satu fieldset kosong
            addFieldsetEmail(form);

            $btnDeleteEmailDB = $('a.del-this', form.section);

            $btnDeleteEmailDB.click(function() {
                var index = $(this).data('index'),
                    $row = $('li#email_'+index),
                    msg = $(this).data('confirm');

                bootbox.confirm(msg, function(result){
                    if(result == true)
                    {
                        $('input[name$="[is_delete]"]', $row).attr('value', 1);
                        $('input[name$="[is_primary_email]"]', $row).attr('value', 0);
                        $row.hide();
                    }
                });
            });
        });

        $.each(formsAlamat, function(idx, form){
            // handle button add
            $('a.add-alamat', form.section).on('click', function(){
                addFieldsetAlamat(form);
            });

            $('a.del-db', form.section).on('click', function(){
                var id = $(this).data('id');
                var msg = $(this).data('confirm');

                handleDeleteDbAlamat(form, id, msg);
            });

            $('a.edit-subjek', form.section).on('click', function(){
                var id = $(this).data('id');
                // alert(id);
                var counterHP = null;
                handleEditSubjekAlamat(counterHP, id, "tab_alamat");
            });

            $('a.edit-negara', form.section).on('click', function(){
                var id = $(this).data('id');
                //alert(counter);
                var counterHP = null;
                
                handleEditNegara(counterHP, id, "tab_alamat");
            });

            $('a.edit-provinsi', form.section).on('click', function(){
                var id = $(this).data('id');
                var counterHP = null;
                //alert(counter);
                handleEditProvinsi(counterHP, id, "tab_alamat");
            });

            $('a.edit-kota', form.section).on('click', function(){
                var id = $(this).data('id');
                var counterHP = null;
                //alert(counter);
                handleEditKota(counterHP, id, "tab_alamat");
            });

            $('a.edit-kecamatan', form.section).on('click', function(){
                var id = $(this).data('id');
                var counterHP = null;
                //alert(counter);
                handleEditKecamatan(counterHP, id, "tab_alamat");
            });

            $('a.edit-kelurahan', form.section).on('click', function(){
                var id = $(this).data('id');
                var counterHP = null;
                //alert(counter);
                handleEditKelurahan(counterHP, id, "tab_alamat");
            });

            $('select.negara', form.section).on('change', function(){
                var id = $(this).data('id');
                var counterHP = null;
                //alert(counter);
                handleSelectProvinsi(counterHP, id, "tab_alamat");
            });

            $('select.provinsi', form.section).on('change', function(){
                var id = $(this).data('id');
                var counterHP = null;
                //alert(counter);
                handleSelectKota(counterHP, id, "tab_alamat");
            });

            $('select.kota', form.section).on('change', function(){
                var id = $(this).data('id');
                var counterHP = null;
                //alert(counter);
                handleSelectKecamatan(counterHP, id, "tab_alamat");
            });

            $('select.kecamatan', form.section).on('change', function(){
                var id = $(this).data('id');
                var counterHP = null;
                //alert(counter);
                handleSelectKelurahan(counterHP, id, "tab_alamat");
            });

            
        });

        $.each(formsBank, function(idx, form){
        // handle button add
            $('a.add-bank', form.section).on('click', function(){
                addFieldsetBank(form);
            });           
            
            var $btnDeleteBankDB = $('a.del-this-bank-db', form.section);

            $btnDeleteBankDB.click(function() {
                var index = $(this).data('index'),
                    $row = $('li#bank_'+index),
                    msg = $(this).data('confirm');

                bootbox.confirm(msg, function(result){
                    if(result == true)
                    {
                        $('input[name$="[is_active]"]', $row).attr('value', 0);
                        $row.hide();
                    }
                });
            });
        });
         

        $.each(formsPembayaran, function(idx, form){
            // handle button add
            $('a.add-pembayaran', form.section).on('click', function(){
                addFieldsetPembayaran(form);
            });
             
            // beri satu fieldset kosong
            // addFieldsetPembayaran(form);
            //addFieldsetAlamat(form);
            var $selectTipeBayarDB = $('select[name$="[tipe_bayar]"]', form.section);
            var $btnDeleteBayarDB = $('a.del-this-bayar-db', form.section);

            $selectTipeBayarDB.on('change', function() {
                var index = $(this).data('index'),
                    $row = $('li#field_'+index);

                if (this.value == 3) {
                    $('.tempo', $row).removeClass('hidden');
                } else {
                    $('.tempo', $row).addClass('hidden');
                }
            });

            $btnDeleteBayarDB.click(function() {
                var index = $(this).data('index'),
                    $row = $('li#field_'+index),
                    msg = $(this).data('confirm');

                bootbox.confirm(msg, function(result){
                    if(result == true)
                    {
                        $('input[name$="[is_active]"]', $row).attr('value', 0);
                        $row.hide();
                    }
                });
            });
        });




    };

    var addFieldsetPhone = function(form)
    {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section), counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            $btnSubjekTelp = $('a#btn_edit_subjek_telp_' + counter)
            ;

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });

        // $('input#radio_primary_phone_id_1').prop('checked', true);
        // $('input[name$="[is_primary_phone]"]').val('');
        
        // $('input#primary_phone_id_1').val(1);
        $('input[name="phone_is_primary"]', $newFieldset).on('click', function()
        {
            $('div.radio span').removeClass('checked');

            $('input[name$="[is_primary_phone]"]').val('');
            $('input[name$="[is_primary_phone]"]', $newFieldset).val(1);
        });

        $('input[name="phone_is_primary"]', $newFieldset).uniform();

        $('input.address_radio', $newFieldset).on('change', function(){
            // alert($(this).prop('checked'));
            handleIsPrimary($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        var counterHP = null;
         $btnSubjekTelp.on('click', function(){
            handleEditSubjekTelp(counterHP, counter, "tab_phone");
        });

         // handleSelectSubjekTelp(counter);
    };

    var addFieldsetEmail = function(form)
    {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section), counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer);

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });

        // $('input#radio_primary_phone_id_1').prop('checked', true);
        // $('input[name$="[is_primary_phone]"]').val('');
        
        // $('input#primary_phone_id_1').val(1);
        $('input[name="email_is_primary"]', $newFieldset).on('click', function()
        {
            $('div.radio span').removeClass('checked');

            $('input[name$="[is_primary_email]"]').val('');
            $('input[name$="[is_primary_email]"]', $newFieldset).val(1);
        });

        $('input[name="email_is_primary"]', $newFieldset).uniform();

        $('input.address_radio', $newFieldset).on('change', function(){
            // alert($(this).prop('checked'));
            handleIsPrimary($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');


         // handleSelectSubjekTelp(counter);
    };

    var addFieldsetAlamat = function(form)
    {
        // if(! isValidLastAlamatRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            rowAlamat          = $('input#row_alamat').val(), // di ambil berdasarkan button sesuai row
            $btnSubjekAlamat   = $('a#btn_edit_subjek_alamat_' + counter), // di ambil berdasarkan button sesuai row
            $btnEditNegara     = $('a#btn_edit_negara_' + counter), // di ambil berdasarkan button sesuai row
            $btnEditProvinsi   = $('a#btn_edit_provinsi_' + counter),
            $btnEditKota       = $('a#btn_edit_kota_' + counter),
            $btnEditKecamatan  = $('a#btn_edit_kecamatan_' + counter),
            $btnEditKelurahan  = $('a#btn_edit_kelurahan_' + counter)
        ;

        $counter.val(counter);
        
        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });

        $('input[name="alamat_is_primary"]', $newFieldset).on('click', function()
        {
            $('.primary_alamat div.radio span').removeClass('checked');
            $('input[name$="[is_primary_alamat]"]').val('');
            $('input[name$="[is_primary_alamat]"]', $newFieldset).val(1);
        });

        $('input[name="alamat_is_primary"]', $newFieldset).uniform();

        $('input.address_radio', $newFieldset).on('change', function(){
            // alert($(this).prop('checked'));
            handleIsPrimary($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        var counterHP = null;
        $btnSubjekAlamat.on('click', function(){
            handleEditSubjekAlamat(counterHP,counter, "tab_alamat");
        });

        $btnEditNegara.on('click', function(){
            handleEditNegara(counterHP, counter, "tab_alamat");
        });

        $btnEditProvinsi.on('click', function(){
            handleEditProvinsi(counterHP, counter, "tab_alamat");
        });

        $btnEditKota.on('click', function(){
            handleEditKota(counterHP, counter, "tab_alamat");
        });

        $btnEditKecamatan.on('click', function(){
            handleEditKecamatan(counterHP, counter, "tab_alamat");
        });

        $btnEditKelurahan.on('click', function(){
            handleEditKelurahan(counterHP, counter, "tab_alamat");
        });

        // handleSelectNegara(counter, "tab_alamat");
        handleSelectProvinsi(counterHP, counter, "tab_alamat");
        handleSelectKota(counterHP, counter, "tab_alamat");
        handleSelectKecamatan(counterHP, counter, "tab_alamat");
        handleSelectKelurahan(counterHP, counter, "tab_alamat");
    };

    var addFieldsetPembayaran = function(form) {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section), 
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            $selectTipeBayar   = $('select.tipe_pembayaran', $newFieldset)
        ;

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });

        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        $selectTipeBayar.on('change', function() {
            if (this.value == 3) {
                $('.tempo', $newFieldset).show();
            } else {
                $('.tempo', $newFieldset).hide();
            }
        })

    };

    var addFieldsetBank = function(form) {

        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section), 
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer)
        ;
        $('a.del-this-bank', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });

        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

    };

    var handleEditSubjekTelp = function(counterHP, counter, tab){
        if (tab == "tab_phone") {
            var $inputSubjekTelp = $('input#input_subjek_telp_' + counter),
                $selectSubjekTelp = $('select#subjek_telp_' + counter),
                $btnSaveSubjekTelp = $('a#btn_save_subjek_telp_' + counter),
                $btnCancelSubjekTelp = $('a#btn_cancel_subjek_telp_' + counter),
                $btnEditSubjekTelp = $('a#btn_edit_subjek_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#btn_delete_subjek_telp_' + counter),
                $divSubjekTelp = $('div#subjek_' + counter),
                $divSubjekHiddenTelp = $('div#subjek_hidden_' + counter);


            $divSubjekTelp.addClass("hidden");
            $divSubjekHiddenTelp.removeClass("hidden");

            $btnEditSubjekTelp.addClass("hidden");
            $btnDeleteSubjekTelp.addClass("hidden");
            $selectSubjekTelp.addClass("hidden");

            $btnSaveSubjekTelp.removeClass("hidden");
            $btnCancelSubjekTelp.removeClass("hidden");
            $inputSubjekTelp.removeClass("hidden");

            $inputSubjekTelp.focus();

            $btnCancelSubjekTelp.on('click', function(){
                handleCancelSubjekTelp(counterHP, counter, "tab_phone");
            });

            // $('a[rel=ajax]').die('click').click(function (event) { -> will load once..
            $btnSaveSubjekTelp.die('click').click(function(e){
                // e.preventDefault();
                handleSaveSubjekTelp(counterHP, counter, "tab_phone");

                e.stopImmediatePropagation();  

            });
        }
        
    }

    var handleCancelSubjekTelp= function(counterHP, counter, tab){
        if (tab == "tab_phone") {
            var $inputSubjekTelp = $('input#input_subjek_telp_' + counter),
                $selectSubjekTelp = $('select#subjek_telp_' + counter),
                $btnSaveSubjekTelp = $('a#btn_save_subjek_telp_' + counter),
                $btnCancelSubjekTelp = $('a#btn_cancel_subjek_telp_' + counter),
                $btnEditSubjekTelp = $('a#btn_edit_subjek_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#btn_delete_subjek_telp_' + counter);
                $divSubjekTelp = $('div#subjek_' + counter),
                $divSubjekHiddenTelp = $('div#subjek_hidden_' + counter);


                $divSubjekTelp.removeClass("hidden");
                $divSubjekHiddenTelp.addClass("hidden");

                $btnEditSubjekTelp.removeClass("hidden");
                $btnDeleteSubjekTelp.removeClass("hidden");
                $selectSubjekTelp.removeClass("hidden");

                $btnSaveSubjekTelp.addClass("hidden");
                $btnCancelSubjekTelp.addClass("hidden");
                $inputSubjekTelp.addClass("hidden");

                $inputSubjekTelp.val("");
        }
        
    }

    var handleSaveSubjekTelp= function(counterHP, counter, tab){
        
        //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
        if (tab == "tab_phone") {
            var $inputSubjekTelp = $('input#input_subjek_telp_' + counter),
                $selectSubjekTelp = $('select#subjek_telp_' + counter),
                $btnSaveSubjekTelp = $('a#btn_save_subjek_telp_' + counter),
                $btnCancelSubjekTelp = $('a#btn_cancel_subjek_telp_' + counter),
                $btnEditSubjekTelp = $('a#btn_edit_subjek_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#btn_delete_subjek_telp_' + counter),
                $divSubjekTelp = $('div#subjek_' + counter),
                $divSubjekHiddenTelp = $('div#subjek_hidden_' + counter);

            if ($inputSubjekTelp.val() != "") {
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'save_subjek',
                    data     : {tipe: '2',
                                nama: $inputSubjekTelp.val()
                               },
                    dataType : 'json',
                    beforeSend  : function(){
                                        Metronic.blockUI({boxed: true });
                                      },
                    success  : function( results ) {
                        $divSubjekTelp.removeClass("hidden");
                        $divSubjekHiddenTelp.addClass("hidden");

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
                        var last_records = 0;
                        $.each(results, function(key, value) {
                            var last_records = value.id;
                            // alert(last_records);
                            $selectSubjekTelp.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectSubjekTelp.val(last_records);

                        });
                    },
                    complete    : function(){
                                        Metronic.unblockUI();
                                      }
                }); 

            } 
        }
    }

    var handleEditSubjekAlamat = function(counterHP, counter, tab){

            if (tab == "tab_alamat") {
                // alert(tab);
                var $inputSubjekAlamat = $('input#input_subjek_alamat_' + counter),
                    $selectSubjekAlamat = $('select#subjek_alamat_' + counter),
                    $btnSaveSubjekAlamat = $('a#btn_save_subjek_alamat_' + counter),
                    $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_alamat_' + counter),
                    $btnEditSubjekAlamat = $('a#btn_edit_subjek_alamat_' + counter),
                    $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_alamat_' + counter),
                    $divSubjekAlamat = $('div#subjek_alamat_' + counter),
                    $divSubjekHiddenAlamat = $('div#subjek_alamat_hidden_' + counter);

                $divSubjekAlamat.addClass("hidden");
                $divSubjekHiddenAlamat.removeClass("hidden");

                $btnEditSubjekAlamat.addClass("hidden");
                $btnDeleteSubjekAlamat.addClass("hidden");
                $selectSubjekAlamat.addClass("hidden");

                $btnSaveSubjekAlamat.removeClass("hidden");
                $btnCancelSubjekAlamat.removeClass("hidden");
                $inputSubjekAlamat.removeClass("hidden");

                $inputSubjekAlamat.focus();

                $btnCancelSubjekAlamat.on('click', function(){
                    handleCancelSubjekAlamat(counterHP, counter, "tab_alamat");
                });

                $btnSaveSubjekAlamat.on('click', function(e){
                    handleSaveSubjekAlamat(counterHP, counter, "tab_alamat");
                    e.stopImmediatePropagation();  

                });
            }
            
    }  

    var handleCancelSubjekAlamat= function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputSubjekAlamat = $('input#input_subjek_alamat_' + counter),
                $selectSubjekAlamat = $('select#subjek_alamat_' + counter),
                $btnSaveSubjekAlamat = $('a#btn_save_subjek_alamat_' + counter),
                $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_alamat_' + counter),
                $btnEditSubjekAlamat = $('a#btn_edit_subjek_alamat_' + counter),
                $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_alamat_' + counter),
                $divSubjekAlamat = $('div#subjek_alamat_' + counter),
                $divSubjekHiddenAlamat = $('div#subjek_alamat_hidden_' + counter);

                $divSubjekAlamat.removeClass("hidden");
                $divSubjekHiddenAlamat.addClass("hidden");
                
                $btnEditSubjekAlamat.removeClass("hidden");
                $btnDeleteSubjekAlamat.removeClass("hidden");
                $selectSubjekAlamat.removeClass("hidden");

                $btnSaveSubjekAlamat.addClass("hidden");
                $btnCancelSubjekAlamat.addClass("hidden");
                $inputSubjekAlamat.addClass("hidden");

                $inputSubjekAlamat.val("");
        }
        
    }

    var handleSaveSubjekAlamat= function(counterHP, counter, tab){
        
        if (tab == "tab_alamat") {
            var $inputSubjekAlamat = $('input#input_subjek_alamat_' + counter),
                $selectSubjekAlamat = $('select#subjek_alamat_' + counter),
                $btnSaveSubjekAlamat = $('a#btn_save_subjek_alamat_' + counter),
                $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_alamat_' + counter),
                $btnEditSubjekAlamat = $('a#btn_edit_subjek_alamat_' + counter),
                $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_alamat_' + counter),
                $divSubjekAlamat = $('div#subjek_alamat_' + counter),
                $divSubjekHiddenAlamat = $('div#subjek_alamat_hidden_' + counter);

                

            if ($inputSubjekAlamat.val() != "") {
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'save_subjek',
                    data     : {tipe: '1',
                                nama: $inputSubjekAlamat.val()
                               },
                    dataType : 'json',
                    beforeSend  : function(){
                                        Metronic.blockUI({boxed: true });
                                      },
                    success  : function( results ) {
                        $divSubjekAlamat.removeClass("hidden");
                        $divSubjekHiddenAlamat.addClass("hidden");

                        $btnEditSubjekAlamat.removeClass("hidden");
                        $btnDeleteSubjekAlamat.removeClass("hidden");
                        $selectSubjekAlamat.removeClass("hidden");

                        $btnSaveSubjekAlamat.addClass("hidden");
                        $btnCancelSubjekAlamat.addClass("hidden");
                        $inputSubjekAlamat.addClass("hidden");

                        $inputSubjekAlamat.val("");

                        $selectSubjekAlamat.empty();

                        //munculin index pertama Pilih..
                        $selectSubjekAlamat.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                            // $selectSubjekAlamat.val('');

                        //munculin semua data dari hasil post
                        var last_records = 0;
                        $.each(results, function(key, value) {
                            last_records = value.id;
                            $selectSubjekAlamat.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectSubjekAlamat.val(last_records);

                        });
                    },
                    complete    : function(){
                                        Metronic.unblockUI();
                                      }
                });   
            }
        }
    }

    var handleEditNegara = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputNegara = $('input#input_negara_' + counter),
                $selectNegara = $('select#negara_' + counter),
                $btnSaveNegara = $('a#btn_save_negara_' + counter),
                $btnCancelNegara = $('a#btn_cancel_negara_' + counter),
                $btnEditNegara = $('a#btn_edit_negara_' + counter);
                $divNegara = $('div#negara_' + counter),
                $divNegaraHidden = $('div#negara_hidden_' + counter);

            $divNegara.addClass("hidden");
            $divNegaraHidden.removeClass("hidden");

            $btnEditNegara.addClass("hidden");
            $selectNegara.addClass("hidden");

            $btnSaveNegara.removeClass("hidden");
            $btnCancelNegara.removeClass("hidden");
            $inputNegara.removeClass("hidden");

            $inputNegara.focus();

            $btnCancelNegara.on('click', function(){
                handleCancelNegara(counterHP, counter, "tab_alamat");
            });

            $btnSaveNegara.on('click', function(e){
                handleSaveNegara(counterHP, counter, "tab_alamat");
                e.stopImmediatePropagation();
            }); 
        }
            
    }   

    var handleCancelNegara= function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputNegara = $('input#input_negara_' + counter),
                $selectNegara = $('select#negara_' + counter),
                $btnSaveNegara = $('a#btn_save_negara_' + counter),
                $btnCancelNegara = $('a#btn_cancel_negara_' + counter),
                $btnEditNegara = $('a#btn_edit_negara_' + counter);
                $inputProvinsi = $('input#input_provinsi_' + counter),
                $divNegara = $('div#negara_' + counter),
                $divNegaraHidden = $('div#negara_hidden_' + counter);

            $divNegara.removeClass("hidden");
            $divNegaraHidden.addClass("hidden");

            $btnEditNegara.removeClass("hidden");
            $selectNegara.removeClass("hidden");

            $btnSaveNegara.addClass("hidden");
            $btnCancelNegara.addClass("hidden");
            $inputNegara.addClass("hidden");

            $inputProvinsi.val("");
        }  
    }

    var handleSaveNegara= function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
            var $inputNegara = $('input#input_negara_' + counter),
                $selectNegara = $('select#negara_' + counter),
                $selectProvinsi = $('select#provinsi_' + counter),
                $selectKota = $('select#kota_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter),
                $btnSaveNegara = $('a#btn_save_negara_' + counter),
                $btnCancelNegara = $('a#btn_cancel_negara_' + counter),
                $btnEditNegara = $('a#btn_edit_negara_' + counter);
                $divNegara = $('div#negara_' + counter),
                $divNegaraHidden = $('div#negara_hidden_' + counter);

            if ($inputNegara.val() != "") {
                // alert('a');
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'save_negara',
                    data     : {tipe: '1',
                                nama: $inputNegara.val()
                               },
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        $divNegara.removeClass("hidden");
                        $divNegaraHidden.addClass("hidden");
                        
                        $btnEditNegara.removeClass("hidden");
                        $selectNegara.removeClass("hidden");

                        $btnSaveNegara.addClass("hidden");
                        $btnCancelNegara.addClass("hidden");
                        $inputNegara.addClass("hidden");

                        $inputNegara.val("");

                        $selectNegara.empty();

                        //munculin index pertama Pilih..
                        $selectNegara.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                            $selectNegara.val('');

                        //munculin semua data dari hasil post
                        var last_records = 0;
                        $.each(results, function(key, value) {
                            last_records = value.id;
                            $selectNegara.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectNegara.val(last_records);

                        });


                        $selectProvinsi.empty();

                        //munculin index pertama Pilih..
                        $selectProvinsi.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectProvinsi.val('');

                        $selectKota.empty();

                        //munculin index pertama Pilih..
                        $selectKota.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKota.val('');

                        $selectKecamatan.empty();

                        $selectKecamatan.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKecamatan.val('');

                        $selectKelurahan.empty();

                        $selectKelurahan.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKelurahan.val('');

                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });   
            }
        }
            
    }

    var handleEditProvinsi = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputProvinsi = $('input#input_provinsi_' + counter),
                $selectProvinsi = $('select#provinsi_' + counter),
                $btnSaveProvinsi = $('a#btn_save_provinsi_' + counter),
                $btnCancelProvinsi = $('a#btn_cancel_provinsi_' + counter),
                $btnEditProvinsi = $('a#btn_edit_provinsi_' + counter);
                $divProvinsi = $('div#provinsi_' + counter),
                $divProvinsiHidden = $('div#provinsi_hidden_' + counter);

            $divProvinsi.addClass("hidden");
            $divProvinsiHidden.removeClass("hidden");
            
            $btnEditProvinsi.addClass("hidden");
            $selectProvinsi.addClass("hidden");

            $btnSaveProvinsi.removeClass("hidden");
            $btnCancelProvinsi.removeClass("hidden");
            $inputProvinsi.removeClass("hidden");

            $inputProvinsi.focus();

            $btnCancelProvinsi.on('click', function(){
                handleCancelProvinsi(counterHP, counter, "tab_alamat");
            });

            $btnSaveProvinsi.on('click', function(e){
                handleSaveProvinsi(counterHP, counter, "tab_alamat");
                e.stopImmediatePropagation();
            });
        }
            
    }

    var handleCancelProvinsi= function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputProvinsi = $('input#input_provinsi_' + counter),
                $selectProvinsi = $('select#provinsi_' + counter),
                $btnSaveProvinsi = $('a#btn_save_provinsi_' + counter),
                $btnCancelProvinsi = $('a#btn_cancel_provinsi_' + counter),
                $btnEditProvinsi = $('a#btn_edit_provinsi_' + counter);
                $divProvinsi = $('div#provinsi_' + counter),
                $divProvinsiHidden = $('div#provinsi_hidden_' + counter);

            $divProvinsi.removeClass("hidden");
            $divProvinsiHidden.addClass("hidden");
            $btnEditProvinsi.removeClass("hidden");
            $selectProvinsi.removeClass("hidden");

            $btnSaveProvinsi.addClass("hidden");
            $btnCancelProvinsi.addClass("hidden");
            $inputProvinsi.addClass("hidden");

            $inputProvinsi.val("");
        }
            
    }  

    var handleSaveProvinsi = function(counterHP, counter, tab){
        
        if (tab == "tab_alamat") {
            var $inputProvinsi = $('input#input_provinsi_' + counter),
                $selectProvinsi = $('select#provinsi_' + counter),
                $selectNegara = $('select#negara_' + counter),
                $selectKota = $('select#kota_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter),
                $btnSaveProvinsi = $('a#btn_save_provinsi_' + counter),
                $btnCancelProvinsi = $('a#btn_cancel_provinsi_' + counter),
                $btnEditProvinsi = $('a#btn_edit_provinsi_' + counter);
                $divProvinsi = $('div#provinsi_' + counter),
                $divProvinsiHidden = $('div#provinsi_hidden_' + counter);

            
                //di eksekusi apabila inputan dan select tidak kosong
            if ($inputProvinsi.val() != "" && $selectNegara.val() != "") { 
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'save_region',
                    data     : {parent: $selectNegara.val(), 
                                tipe: '2',
                                nama: $inputProvinsi.val()
                               },
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // $kelas_select.val('Pilih Kelas');

                        $divProvinsi.removeClass("hidden");
                        $divProvinsiHidden.addClass("hidden");

                        $btnEditProvinsi.removeClass("hidden");
                        $selectProvinsi.removeClass("hidden");

                        $btnSaveProvinsi.addClass("hidden");
                        $btnCancelProvinsi.addClass("hidden");
                        $inputProvinsi.addClass("hidden");

                        $inputProvinsi.val("");

                        $selectProvinsi.empty();

                        $selectProvinsi.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        var last_records = 0;
                        $.each(results, function(key, value) {
                            last_records = value.id;
                            $selectProvinsi.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectProvinsi.val(last_records);

                        });

                        $selectKota.empty();

                        //munculin index pertama Pilih..
                        $selectKota.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKota.val('');

                        $selectKecamatan.empty();

                        $selectKecamatan.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKecamatan.val('');

                        $selectKelurahan.empty();
                        
                        $selectKelurahan.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKelurahan.val('');
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });   
            }
        }
    }

    var handleEditKota = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputKota = $('input#input_kota_' + counter),
                $selectKota = $('select#kota_' + counter),
                $btnSaveKota = $('a#btn_save_kota_' + counter),
                $btnCancelKota = $('a#btn_cancel_kota_' + counter),
                $btnEditKota = $('a#btn_edit_kota_' + counter);
                $divKota = $('div#kota_' + counter),
                $divKotaHidden = $('div#kota_hidden_' + counter);

            $divKota.addClass("hidden");
            $divKotaHidden.removeClass("hidden");

            $btnEditKota.addClass("hidden");
            $selectKota.addClass("hidden");

            $btnSaveKota.removeClass("hidden");
            $btnCancelKota.removeClass("hidden");
            $inputKota.removeClass("hidden");

            $inputKota.focus();
            
            $btnCancelKota.on('click', function(){
                handleCancelKota(counterHP, counter, "tab_alamat");
            });

            $btnSaveKota.on('click', function(e){
                handleSaveKota(counterHP, counter, "tab_alamat");
                e.stopImmediatePropagation();
            });
        }
            
    }

    var handleCancelKota= function(counterHP, counter, tab){
            if (tab == "tab_alamat") {
                var $inputKota = $('input#input_kota_' + counter),
                    $selectKota = $('select#kota_' + counter),
                    $btnSaveKota = $('a#btn_save_kota_' + counter),
                    $btnCancelKota = $('a#btn_cancel_kota_' + counter),
                    $btnEditKota = $('a#btn_edit_kota_' + counter);
                    $divKota = $('div#kota_' + counter),
                    $divKotaHidden = $('div#kota_hidden_' + counter);

                $divKota.removeClass("hidden");
                $divKotaHidden.addClass("hidden");

                $btnEditKota.removeClass("hidden");
                $selectKota.removeClass("hidden");

                $btnSaveKota.addClass("hidden");
                $btnCancelKota.addClass("hidden");
                $inputKota.addClass("hidden");

                $inputKota.val("");
            }
            
    }

    var handleSaveKota = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var 
                $inputKota       = $('input#input_kota_' + counter),
                $selectKota      = $('select#kota_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter),
                $btnSaveKota     = $('a#btn_save_kota_' + counter),
                $btnCancelKota   = $('a#btn_cancel_kota_' + counter),
                $btnEditKota     = $('a#btn_edit_kota_' + counter),
                $selectProvinsi  = $('select#provinsi_' + counter);
                $divKota = $('div#kota_' + counter),
                $divKotaHidden = $('div#kota_hidden_' + counter);

                
                
                //di eksekusi apabila inputan dan select tidak kosong
            if ($inputKota.val() != "" && $selectProvinsi.val() != "") { 
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'save_region',
                    data     : {parent: $selectProvinsi.val(), 
                                tipe: '3',
                                nama: $inputKota.val()
                               },
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // $kelas_select.val('Pilih Kelas');
                        $divKota.removeClass("hidden");
                        $divKotaHidden.addClass("hidden");

                        $btnEditKota.removeClass("hidden");
                        $selectKota.removeClass("hidden");

                        $btnSaveKota.addClass("hidden");
                        $btnCancelKota.addClass("hidden");
                        $inputKota.addClass("hidden");

                        $inputKota.val("");

                        $selectKota.empty();

                        $selectKota.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $selectKota.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectKota.val(value.id);

                        });

                        
                        $selectKecamatan.empty();
                        $selectKelurahan.empty();

                        $selectKecamatan.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKecamatan.val('');

                        $selectKelurahan.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKelurahan.val('');
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });   
            }
        }
            
    }

    var handleEditKecamatan = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputKecamatan = $('input#input_kecamatan_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $btnSaveKecamatan = $('a#btn_save_kecamatan_' + counter),
                $btnCancelKecamatan = $('a#btn_cancel_kecamatan_' + counter),
                $btnEditKecamatan = $('a#btn_edit_kecamatan_' + counter);
                $divKecamatan = $('div#kecamatan_' + counter),
                $divKecamatanHidden = $('div#kecamatan_hidden_' + counter);

            $divKecamatan.addClass("hidden");
            $divKecamatanHidden.removeClass("hidden");

            $btnEditKecamatan.addClass("hidden");
            $selectKecamatan.addClass("hidden");

            $btnSaveKecamatan.removeClass("hidden");
            $btnCancelKecamatan.removeClass("hidden");
            $inputKecamatan.removeClass("hidden");

            $inputKecamatan.focus();
            
            $btnCancelKecamatan.on('click', function(){
                handleCancelKecamatan(counterHP, counter, "tab_alamat");
            });

            $btnSaveKecamatan.on('click', function(e){
                handleSaveKecamatan(counterHP, counter, "tab_alamat");
                e.stopImmediatePropagation();
            });
        }
            
    }

    var handleCancelKecamatan = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputKecamatan = $('input#input_kecamatan_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $btnSaveKecamatan = $('a#btn_save_kecamatan_' + counter),
                $btnCancelKecamatan = $('a#btn_cancel_kecamatan_' + counter),
                $btnEditKecamatan = $('a#btn_edit_kecamatan_' + counter);
                $divKecamatan = $('div#kecamatan_' + counter),
                $divKecamatanHidden = $('div#kecamatan_hidden_' + counter);

            $divKecamatan.removeClass("hidden");
            $divKecamatanHidden.addClass("hidden");

            $btnEditKecamatan.removeClass("hidden");
            $selectKecamatan.removeClass("hidden");

            $btnSaveKecamatan.addClass("hidden");
            $btnCancelKecamatan.addClass("hidden");
            $inputKecamatan.addClass("hidden");

            $inputKecamatan.val("");
        }
    }

    var handleSaveKecamatan = function(counterHP, counter, tab){
        
        if (tab == "tab_alamat") {
            var $inputKecamatan = $('input#input_kecamatan_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter),
                $btnSaveKecamatan = $('a#btn_save_kecamatan_' + counter),
                $btnCancelKecamatan = $('a#btn_cancel_kecamatan_' + counter),
                $btnEditKecamatan = $('a#btn_edit_kecamatan_' + counter),
                $selectKota = $('select#kota_' + counter);
                $divKecamatan = $('div#kecamatan_' + counter),
                $divKecamatanHidden = $('div#kecamatan_hidden_' + counter);

                //di eksekusi apabila inputan dan select tidak kosong
            if ($inputKecamatan.val() != "" && $selectKota.val() != "") { 
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'save_region',
                    data     : {parent: $selectKota.val(), 
                                tipe: '4',
                                nama: $inputKecamatan.val()
                               },
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // $kelas_select.val('Pilih Kelas');
                        $divKecamatan.removeClass("hidden");
                        $divKecamatanHidden.addClass("hidden");

                        $btnEditKecamatan.removeClass("hidden");
                        $selectKecamatan.removeClass("hidden");

                        $btnSaveKecamatan.addClass("hidden");
                        $btnCancelKecamatan.addClass("hidden");
                        $inputKecamatan.addClass("hidden");

                        $inputKecamatan.val("");

                        $selectKecamatan.empty();

                        $selectKecamatan.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $selectKecamatan.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectKecamatan.val(value.id);

                        });

                        $selectKelurahan.empty();
                        $selectKelurahan.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKelurahan.val('');
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });   
            } 
        }
           
    }

    var handleEditKelurahan = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputKelurahan = $('input#input_kelurahan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter),
                $btnSaveKelurahan = $('a#btn_save_kelurahan_' + counter),
                $btnCancelKelurahan = $('a#btn_cancel_kelurahan_' + counter),
                $btnEditKelurahan = $('a#btn_edit_kelurahan_' + counter);
                $divKelurahan = $('div#kelurahan_' + counter),
                $divKelurahanHidden = $('div#kelurahan_hidden_' + counter);

            $divKelurahan.addClass("hidden");
            $divKelurahanHidden.removeClass("hidden");

            $btnEditKelurahan.addClass("hidden");
            $selectKelurahan.addClass("hidden");

            $btnSaveKelurahan.removeClass("hidden");
            $btnCancelKelurahan.removeClass("hidden");
            $inputKelurahan.removeClass("hidden");

            $inputKelurahan.focus();
            
            $btnCancelKelurahan.on('click', function(){
                handleCancelKelurahan(counterHP, counter, "tab_alamat");
            });

            $btnSaveKelurahan.on('click', function(e){
                handleSaveKelurahan(counterHP, counter, "tab_alamat");
                e.stopImmediatePropagation();
            });
        }
    }

    var handleCancelKelurahan = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputKelurahan = $('input#input_kelurahan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter),
                $btnSaveKelurahan = $('a#btn_save_kelurahan_' + counter),
                $btnCancelKelurahan = $('a#btn_cancel_kelurahan_' + counter),
                $btnEditKelurahan = $('a#btn_edit_kelurahan_' + counter);
                $divKelurahan = $('div#kelurahan_' + counter),
                $divKelurahanHidden = $('div#kelurahan_hidden_' + counter);

            $divKelurahan.removeClass("hidden");
            $divKelurahanHidden.addClass("hidden");

            $btnEditKelurahan.removeClass("hidden");
            $selectKelurahan.removeClass("hidden");

            $btnSaveKelurahan.addClass("hidden");
            $btnCancelKelurahan.addClass("hidden");
            $inputKelurahan.addClass("hidden");

            $inputKelurahan.val("");
        }
        
    }

    var handleSaveKelurahan = function(counterHP, counter, tab){
        
        if (tab == "tab_alamat") {
            var $inputKelurahan = $('input#input_kelurahan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter),
                $btnSaveKelurahan = $('a#btn_save_kelurahan_' + counter),
                $btnCancelKelurahan = $('a#btn_cancel_kelurahan_' + counter),
                $btnEditKelurahan = $('a#btn_edit_kelurahan_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter);
                $divKelurahan = $('div#kelurahan_' + counter),
                $divKelurahanHidden = $('div#kelurahan_hidden_' + counter);

            

                
                //alert($selectKecamatan.val());
                //di eksekusi apabila inputan dan select tidak kosong
            if ($inputKelurahan.val() != "" && $selectKecamatan.val() != "") { 
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'save_region',
                    data     : {parent: $selectKecamatan.val(), 
                                tipe: '5',
                                nama: $inputKelurahan.val()
                               },
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // $kelas_select.val('Pilih Kelas');
                        $divKelurahan.removeClass("hidden");
                        $divKelurahanHidden.addClass("hidden");

                        $btnEditKelurahan.removeClass("hidden");
                        $selectKelurahan.removeClass("hidden");

                        $btnSaveKelurahan.addClass("hidden");
                        $btnCancelKelurahan.addClass("hidden");
                        $inputKelurahan.addClass("hidden");

                        $inputKelurahan.val("");

                        $selectKelurahan.empty();

                        $selectKelurahan.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $selectKelurahan.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectKelurahan.val(value.id);

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });   
            }
        } 
    }

    var isValidLastPhoneRow = function(){
        
        var 
                $numberEls = $('input[name$="[number]"]', $form),
                // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
                number    = $numberEls.eq($numberEls.length-1).val()
                // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;

       // var rowId    = $this('tr').prop('id');
        //alert(rowId);
            // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
            return (number != '');

    };  

    var isValidLastAlamatRow = function(){
        
        var 
                $alamatEls = $('select[name$="[kelurahan]"]', $form),
                // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
                alamat    = $alamatEls.eq($alamatEls.length-1).val()
                // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;

       // var rowId    = $this('tr').prop('id');
        //alert(rowId);
            // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
            return (alamat != '');

    };

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

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

    var handleTeleponIsPrimary = function(){

        $('input[name="phone_is_primary"]').on('click', function()
        {   
            var id = $(this).data('id');

            $('input[name$="[is_primary_phone]"]').val('');
            $('input#primary_phone_id_' + id).val(1);
        })
    }

    var handleEmailIsPrimary = function(){

        $('input[name="email_is_primary"]').on('click', function()
        {   
            var id = $(this).data('id');

            $('input[name$="[is_primary_email]"]').val('');
            $('input#primary_email_id_' + id).val(1);
        })
    }

    var handleAlamatIsPrimary = function(){

        $('input[name="alamat_is_primary"]').on('click', function()
        {   
            var id = $(this).data('id');

            $('input[name$="[is_primary_alamat]"]').val('');
            $('input#primary_alamat_id_' + id).val(1);
        })
    }

    var handleDeleteDbPhone = function(form, id, msg){
        //alert(id);

        bootbox.confirm(msg, function(result) {
        
            if (result==true) {
                $('li#phone_' + id).addClass('hidden');
                $('input#is_delete_phone_' + id).val(1);
            }
        });
    }

    var handleDeleteDbEmail = function(form, id, msg){
        //alert(id);

        bootbox.confirm(msg, function(result) {
        
            if (result==true) {
                $('li#email_' + id).addClass('hidden');
                $('input#is_delete_email_' + id).val(1);
            }
        });
    }

    var handleDeleteDbAlamat = function(form, id, msg){
        //alert(id);
        bootbox.confirm(msg, function(result) {
        
            if (result==true) {
                $('li#alamat_' + id).addClass('aaa');
                $('input#is_delete_alamat_' + id).val(1);
            }
        });
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

    var handleSetPenanggungJawab = function(row)
    {        
        // alert(row);
        // var 
        //     $parentUl     = $fieldset.parent(),
        //     fieldsetCount = $('.fieldset', $parentUl).length,
        //     hasId         = false,  //punya id tidak, jika tidak bearti data baru
        //     hasDefault    = 0,      //ada tidaknya fieldset yang di set sebagai default, diset ke 0 dulu
        //     $inputDefault = $('input:hidden[name$="[is_default]"]', $fieldset), 
        //     isDefault     = $inputDefault.val() == 1
        //     ; 

        // if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.

        // $fieldset.remove();

        // $('li#li_penanggung_jawab').html('<div class="form-group">'+ $('div#group_nama_'+row).html() + '</div>' + '<div class="form-group">' + $('div#group_ktp_'+row).html() + '</div>');
        $('div#ul_penanggung_jawab').removeClass('hidden');
        
        $('li#li_penanggung_jawab').html($('div#group_nama_'+row).html() + $('div#group_ktp_'+row).html());

        $('ul#ul_penanggung_jawab > li#li_penanggung_jawab input.send-data').attr('readonly', true);
        $('ul#ul_penanggung_jawab > li#li_penanggung_jawab a.penanggung_jawab').remove();
        $('ul#ul_penanggung_jawab > li#li_penanggung_jawab a.del_penanggung_jawab').removeClass('hidden');
        $('ul#ul_penanggung_jawab > li#li_penanggung_jawab a.set_penanggung_jawab').remove();
        $('ul#ul_penanggung_jawab > li#li_penanggung_jawab a.del-hub-pasien').remove();
        handleDeletePenanggungJawab();
    };

    var handleDeletePenanggungJawab = function(){
        // alert('a');
        $('ul#ul_penanggung_jawab > li#li_penanggung_jawab a.del_penanggung_jawab').on('click', function(){
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {

                if (result == true) {
                    $('a.penanggung_jawab').addClass('hidden');
                    $('a.set_penanggung_jawab').removeClass('hidden');
                    $('input[name$="[set_penanggung_jawab]"]').val('');

                    $('div#ul_penanggung_jawab').addClass('hidden');
                    $('li#li_penanggung_jawab').empty();
                
                    $.each(formsPenanggungJawab, function(idx, form){
                        // handle button add
                        $('a.add-penanggung-jawab', form.section).on('click', function(){
                            addFieldsetPenanggungJawab(form);
                        });
                         
                        // beri satu fieldset kosong
                        addFieldsetPenanggungJawab(form);
                        //addFieldsetAlamat(form);
                    });    
                };                
                
            });


            
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

    var handleTambahRowPelengkap = function(){
        $('a#tambah_row_pelengkap', $form).click(function() {
            addItemRow();
        });
    };

    var handleTambahRowRekamMedis = function(){
        $('a#tambah_row_rekam_medis', $form).click(function() {
            addItemRowRekamMedis();
        });
    };

    var handleSelectCabang = function(){
        $('select#cabang_id', $form).on('change', function() {
            var $kode_cabang_rujukan = $('input#kode_cabang_rujukan', $form);

            //$kode_cabang_rujukan.val($(this).val());
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kode_cabang',
                data     : {id_cabang: $(this).val()},
                dataType : 'json',
                success  : function( results ) {
                  
                    $.each(results, function(key, value) {

                        $kode_cabang_rujukan.val(value.kode);

                    });
                }
            });
        });
    };


    var handleMultiSelect = function () {
        $('#multi_select_penyakit_bawaan').multiSelect();   
        $('#multi_select_penyakit_penyebab').multiSelect();   
    };

    var handleSelectSubjekTelp = function(counter){
        //var numRow = $counter.val();
            //alert($counter.val());
            //$('input#warehouse_id').val($(this).val());

        var $telp_select = $('select#subjek_telp_' + counter);
        
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_subjek',
            data     : {tipe: '2'},
            dataType : 'json',
            success  : function( results ) {
                // $kelas_select.val('Pilih Kelas');
                $telp_select.empty();

                $telp_select.append($("<option></option>")
                    .attr("value", '').text('Pilih..'));

                $.each(results, function(key, value) {
                    $telp_select.append($("<option></option>")
                        .attr("value", value.id).text(value.nama));
                    $telp_select.val('');

                });
            }
        });
    }
    var handleSelectNegara = function(counter, tab){
        //var numRow = $counter.val();
            //alert($counter.val());
            //$('input#warehouse_id').val($(this).val());
        if (tab == "tab_alamat") {
            var $negara_select = $('select#negara_' + counter);
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_negara',
                data     : {id_negara: '1'},
                dataType : 'json',
                beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $negara_select.empty();

                    $negara_select.append($("<option></option>")
                        .attr("value", '').text('Pilih..'));

                    $.each(results, function(key, value) {
                        $negara_select.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $negara_select.val('');

                    });
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
        }else{

        }
        
    }

    var handleSelectProvinsi = function(counterHP, counter, tab){
        // alert('aa');
        if (tab == "tab_alamat") {
            $('select.negara').on('change', function(){

            //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $selectNegara = $('select#negara_' + counter),
                $selectProvinsi = $('select#provinsi_' + counter),
                $selectKota = $('select#kota_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter);
                
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_provinsi',
                    data     : {id_negara: $(this).val()},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // $kelas_select.val('Pilih Kelas');
                        $selectProvinsi.empty();

                        $selectProvinsi.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $selectProvinsi.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectProvinsi.val('');

                        });

                        $selectKota.empty();

                        $selectKota.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));

                        $selectKecamatan.empty();

                        $selectKecamatan.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));

                        $selectKelurahan.empty();

                        $selectKelurahan.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }
    }

    var handleSelectKota = function(counterHP, counter, tab){
        //alert('aa');
        if (tab == "tab_alamat") {
            $('select.provinsi').on('change', function(){

            //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $selectNegara = $('select#negara_' + counter),
                $selectProvinsi = $('select#provinsi_' + counter),
                $selectKota = $('select#kota_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter);
                
                //alert($kota_select);

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_kota',
                    data     : {id_provinsi: $(this).val()},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // $kelas_select.val('Pilih Kelas');
                        $selectKota.empty();

                        $selectKota.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $selectKota.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectKota.val('');

                        });

                        $selectKecamatan.empty();

                        $selectKecamatan.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));

                        $selectKelurahan.empty();

                        $selectKelurahan.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }
        
    }

    var handleSelectKecamatan = function(counterHP, counter, tab){
        //alert('aa');
        if (tab == "tab_alamat") {
            $('select.kota').on('change', function(){

                //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $selectNegara = $('select#negara_' + counter),
                $selectProvinsi = $('select#provinsi_' + counter),
                $selectKota = $('select#kota_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter);
                
                //alert($kota_select);

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_kecamatan',
                    data     : {id_kota: $(this).val()},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // $kelas_select.val('Pilih Kelas');
                        $selectKecamatan.empty();

                        $selectKecamatan.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $selectKecamatan.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectKecamatan.val('');

                        });

                        $selectKelurahan.empty();

                        $selectKelurahan.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }
        
    }

    var handleSelectKelurahan = function(counterHP, counter, tab){
        //alert('aa');
        if (tab == "tab_alamat") {
           $('select.kecamatan').on('change', function(){

            //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $selectNegara = $('select#negara_' + counter),
                $selectProvinsi = $('select#provinsi_' + counter),
                $selectKota = $('select#kota_' + counter),
                $selectKecamatan = $('select#kecamatan_' + counter),
                $selectKelurahan = $('select#kelurahan_' + counter);
                
                //alert($kota_select);

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_kelurahan',
                    data     : {id_kecamatan: $(this).val()},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // $kelas_select.val('Pilih Kelas');
                        $selectKelurahan.empty();

                        $selectKelurahan.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $selectKelurahan.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $selectKelurahan.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            }) 
        }
        
    }
    
    var handleMultiSelectItem = function () {
        $('#multi_select_item').multiSelect({
              selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Cari Item..'>",
              selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Cari Item..'>",
              afterInit: function(ms){
                
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                  if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                  }
                });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                  if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                  }
                });
              },
              afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
              },
              afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
              },
        
              selectableOptgroup: true
        });
        
        $('#select-all').click(function(){
              $('#multi_select_item').multiSelect('select_all');
              return false;
        });
        $('#deselect-all').click(function(){
              $('#multi_select_item').multiSelect('deselect_all');
              return false;
        });
    };

    var handleDataTable = function() 
    {
        $tableItemDetail.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_detail',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableItemDetail.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
            $('input[type=checkbox]',this).uniform();
            
            $('input.check-item').on('click', function(){
                if( $(this).is(':checked') ){
                    rowId = $(this).data('row_id');
                    $('input#is_selected_'+rowId).val(1);
                }else{
                    rowId = $(this).data('row_id');
                    $('input#is_selected_'+rowId).val('');
                }
            });
            
        });

        jQuery('#table_item_detail .group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                if (checked) {
                    rowId = $(this).data('row_id');

                    $(this).attr("checked", true);
                    $('input#is_selected_'+rowId).val(1);
                } else {
                    rowId = $(this).data('row_id');
                    $(this).attr("checked", false);

                    $('input#is_selected_'+rowId).val('');

                }                    
            });
            jQuery.uniform.update(set);
        });

        

        
    }

    var handleChangePKP = function(){
        $('input[name="is_pkp"]').on('change', function(){

            var pkp = $(this).val();

            if(pkp == 1){
                $('div#div_npwp').removeClass('hidden');
                $('input#npwp').attr('required','required');
            }if(pkp == 0){
                $('div#div_npwp').addClass('hidden');
                $('input#npwp').removeAttr('required');
            }
        });
    }

    var handleChangeJenis = function(){
        $('input[name="obat"]').on('change', function(){
            if($(this).prop('checked') == true){
                $('div#div_pbf').removeClass('hidden');
            }else{
                $('div#div_pbf').addClass('hidden'); 
            }
        }); 
        $('input[name="alkes"]').on('change', function(){
            if($(this).prop('checked') == true){
                $('div#div_pbf').removeClass('hidden');
            }else{
                $('div#div_pbf').addClass('hidden'); 
            }
        });
        $('input[name="lain"]').on('change', function(){
            if($(this).prop('checked') == true){
                if($('input[name="obat"]').prop('checked') == true || $('input[name="alkes"]').prop('checked') == true){
                    $('div#div_pbf').removeClass('hidden');  
                }else{
                    $('div#div_pbf').addClass('hidden'); 
                }
            }
        });
    }

    function handleUploadifySurat()
    {
        
        var ul = $('#upload_surat ul.ul-img');
   
        // Initialize the jQuery File Upload plugin
        $('#upl_surat').fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop'),
            dataType: 'json',
            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {

                tpl = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

                // Initialize the knob plugin
                tpl.find('input').knob();

                // Listen for clicks on the cancel icon
                tpl.find('span').click(function(){

                    if(tpl.hasClass('working')){
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function(){
                        tpl.remove();
                    });

                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },
            done: function(e, data){
                 console.log(data);
                var filename = data.result.filename;
                var filename = filename.replace(/ /g,"_");
                var filetype = data.files[0].type;

                if(filetype == 'image/jpeg' || filetype == 'image/png' || filetype == 'image/gif')
                {
                    tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                }
                else
                {
                    tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                }
                

                $('input#url').attr('value',filename);
                // Add the HTML to the UL element
                ul.html(tpl);
                handleFancybox();
                // data.context = tpl.appendTo(ul);

                Metronic.unblockUI();
                    // data.context = tpl.appendTo(ul);

            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                Metronic.blockUI({boxed: true});
            },


            fail:function(e, data){
                // Something has gone wrong!
                bootbox.alert('File Tidak Dapat Diupload');
                Metronic.unblockUI();
            }
        });


        // Prevent the default action when a file is dropped on the window
        $(document).on('drop dragover', function (e) {
            e.preventDefault();
        });

        // Helper function that formats the file sizes
        function formatFileSize(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }

            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }

            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }

            return (bytes / 1000).toFixed(2) + ' KB';
        }
        
    }

    function handleUploadifySuratKA()
    {
        
        var ul = $('#upload_sika ul.ul-img');
   
        // Initialize the jQuery File Upload plugin
        $('#upl_sika').fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop'),
            dataType: 'json',
            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {

                tpl = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

                // Initialize the knob plugin
                tpl.find('input').knob();

                // Listen for clicks on the cancel icon
                tpl.find('span').click(function(){

                    if(tpl.hasClass('working')){
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function(){
                        tpl.remove();
                    });

                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },
            done: function(e, data){
                 console.log(data);
                var filename = data.result.filename;
                var filename = filename.replace(/ /g,"_");
                var filetype = data.files[0].type;

                if(filetype == 'image/jpeg' || filetype == 'image/png' || filetype == 'image/gif')
                {
                    tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                }
                else
                {
                    tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                }
                

                $('input#url_sika').attr('value',filename);
                // Add the HTML to the UL element
                ul.html(tpl);
                handleFancybox();
                // data.context = tpl.appendTo(ul);

                Metronic.unblockUI();
                    // data.context = tpl.appendTo(ul);

            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                Metronic.blockUI({boxed: true});
            },


            fail:function(e, data){
                // Something has gone wrong!
                bootbox.alert('File Tidak Dapat Diupload');
                Metronic.unblockUI();
            }
        });


        // Prevent the default action when a file is dropped on the window
        $(document).on('drop dragover', function (e) {
            e.preventDefault();
        });

        // Helper function that formats the file sizes
        function formatFileSize(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }

            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }

            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }

            return (bytes / 1000).toFixed(2) + ' KB';
        }
        
    }
    
    function handleFancybox() {
        if (!jQuery.fancybox) {
            return;
        }

        if ($(".fancybox-button").size() > 0) {
            $(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });
        }
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/supplier/';
        handleValidation();
        handleConfirmSave();
        handleDataTable();
        handleMultiSelectItem();
        handleDatePickers();
        handleTeleponIsPrimary();
        handleAlamatIsPrimary();
        handleEmailIsPrimary();
        handleChangePKP();
        handleChangeJenis();
        handleUploadifySurat();
        handleUploadifySuratKA();
        initForm();
        //alert('1');
    };
 }(mb.app.supplier.edit));


// initialize  mb.app.supplier.edit
$(function(){
    mb.app.supplier.edit.init();
});