mb.app.pasien = mb.app.pasien || {};
mb.app.pasien.add = mb.app.pasien.add || {};
(function(o){

    var 
        baseAppUrl               = '',
        $form                    = $('#form_add_pasien'),
        $tablePelengkap          = $('#table_pelengkap', $form),
        $tableRekamMedis         = $('#table_rekam_medis', $form),
        $counter                 = $('#counter'),
        $popoverPasienContent    = $('#popover_pasien_content'), 
        $lastPopoverItem         = null,
        $tablePilihPasien        = $('#table_pilih_pasien'),
        tplFormPhone             = '<li class="fieldset">' + $('#tpl-form-phone', $form).val() + '<hr></li>',
        tplFormAlamat            = '<li class="fieldset">' + $('#tpl-form-alamat', $form).val() + '<hr></li>',
        tplFormHubunganPasien    = '<li class="fieldset">' + $('#tpl-form-hubungan-pasien', $form).val() + '<hr></li>',
        tplFormPenanggungJawab   = '<li class="fieldset">' + $('#tpl-form-penanggung-jawab', $form).val() + '</li>',
        tplFormHPAlamat          = '<li class="fieldset">' + $('#tpl-form-hp-alamat', $form).val() + '<hr></li>',
        tplFormHPPhone           = '<li class="fieldset">' + $('#tpl-form-hp-phone', $form).val() + '<hr></li>',
        tplFormPJAlamat          = '<li class="fieldset">' + $('#tpl-form-pj-alamat', $form).val() + '<hr></li>',
        tplFormPJPhone           = '<li class="fieldset">' + $('#tpl-form-pj-phone', $form).val() + '<hr></li>',
        regExpTplPhone           = new RegExp('phone[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplAlamat          = new RegExp('alamat[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplHubunganPasien  = new RegExp('hubungan_pasien[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplPenanggungJawab = new RegExp('penanggung_jawab[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplHPAlamat        = new RegExp('hp_alamat[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplHPPhone         = new RegExp('hp_phone[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplPJAlamat        = new RegExp('pj_alamat[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplPJPhone         = new RegExp('pj_phone[0]', 'g'),   // 'g' perform global, case-insensitive
        phoneCounter             = 1,
        alamatCounter            = 1,
        hubunganPasienCounter    = 1,
        penanggungJawabCounter   = 1,
        
        pjAlamatCounter          = 1,
        pjPhoneCounter           = 1,
        tplPelengkapRow          = $.validator.format( $('#tpl_pelengkap_row').text() ),
        tplRekamMedisRow         = $.validator.format( $('#tpl_rekam_medis_row').text() ),
        pelengkapCounter         = 1,
        rekamMedisCounter        = 1,
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
        formsHubunganPasien = {
                    'hubungan_pasien' : 
                    {            
                        section  : $('#section-hubungan-pasien', $form),
                        template : $.validator.format( tplFormHubunganPasien.replace(regExpTplHubunganPasien, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ hubunganPasienCounter++; return hubunganPasienCounter-1; }
                    }   
                },
        formsPenanggungJawab = {
                    'hubungan_pasien' : 
                    {            
                        section  : $('#section-penanggung-jawab', $form),
                        template : $.validator.format( tplFormPenanggungJawab.replace(regExpTplPenanggungJawab, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ penanggungJawabCounter++; return penanggungJawabCounter-1; }
                    }   
                };

    var initForm = function(){

        $counter.val(alamatCounter);
        // tambah 1 row kosong pertama

        $.each(formsPhone, function(idx, form){
            // handle button add
            $('a.add-phone', form.section).on('click', function(){
                addFieldsetPhone(form);
                $('input#primary_id_1').prop('checked', true);
            });
             
            // beri satu fieldset kosong
            addFieldsetPhone(form);
        });

        $.each(formsAlamat, function(idx, form){
            // handle button add
            $('a.add-alamat', form.section).on('click', function(){
                addFieldsetAlamat(form);
            });
             
            // beri satu fieldset kosong
            addFieldsetAlamat(form);
            //addFieldsetAlamat(form);
        });

        $.each(formsHubunganPasien, function(idx, form){
            // handle button add
            $('a.add-hubungan-pasien', form.section).on('click', function(){
                addFieldsetHubunganPasien(form);
            });
             
            // beri satu fieldset kosong
            addFieldsetHubunganPasien(form);
            //addFieldsetAlamat(form);
        });

        $.each(formsPenanggungJawab, function(idx, form){
                    // handle button add
            $('a.add-penanggung-jawab', form.section).on('click', function(){
                addFieldsetPenanggungJawab(form);
            });
             
            // beri satu fieldset kosong
            addFieldsetPenanggungJawab(form);
            //addFieldsetAlamat(form);
        });
        
        $('select#id_marketing', $form).on('change', function(){
            var marketing = $('select#id_marketing option:selected').text();

                $('input#nama_marketing').val(marketing);
        });
        


        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);

        $btnDelete = $('a.del-this', $tablePelengkap);

        $.each($btnDelete, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        $btnDeleteRekamMedis = $('a.del-this', $tableRekamMedis);

        $.each($btnDeleteRekamMedis, function(idx, btn){
            handleBtnDeleteRekamMedis( $(btn) );
        });

        addItemRow();
        addItemRowRekamMedis();

        $('input[type=radio]', $form).uniform();
        $('input[type=checkbox]', $form).uniform();
    };

    var addItemRow = function(){

        var numRow = $('tbody tr', $tablePelengkap).length;
        console.log('Num Row Pelengkap ' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        //if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer         = $('tbody', $tablePelengkap),
            $newItemRow           = $(tplPelengkapRow(pelengkapCounter++)).appendTo( $rowContainer ),
            $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;
        // handle delete btn
        handleBtnDelete( $('a.del-this', $newItemRow) );
        handleDatePickers();
    };

    var isValidLastRow = function(){
        
        var 
                $itemCodeEls = $('input[name$="[name]"]', $tablePersetujuan),
                // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
                itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
                // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;

       // var rowId    = $this('tr').prop('id');
        //alert(rowId);
            // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
            return (itemCode != '');
    };

    var handleBtnDelete = function($btn){
        var numRow = $('tbody tr', $tablePelengkap).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePelengkap);

        $btn.on('click', function(e){
            
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        $row.remove();
                        if($('tbody>tr', $tablePelengkap).length == 0){
                            addItemRow();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            
            e.preventDefault();
        });
    };

    var addItemRowRekamMedis = function(){

        var numRow = $('tbody tr', $tableRekamMedis).length;
        console.log('Num Row Rekam Medis ' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        //if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer         = $('tbody', $tableRekamMedis),
            $newItemRow           = $(tplRekamMedisRow(rekamMedisCounter++)).appendTo( $rowContainer ),
            $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;
        // handle delete btn
        handleBtnDeleteRekamMedis( $('a.del-this', $newItemRow) );
        handleDatePickers();
    };

    var handleBtnDeleteRekamMedis = function($btn){
        var numRow = $('tbody tr', $tableRekamMedis).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableRekamMedis);

        $btn.on('click', function(e){
            
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        $row.remove();
                        if($('tbody>tr', $tableRekamMedis).length == 0){
                            addItemRowRekamMedis();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            
            e.preventDefault();
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

        $('input#radio_primary_phone_id_1').prop('checked', true);
        $('input[name$="[is_primary_phone]"]').val('');
        
        $('input#primary_phone_id_1').val(1);
        $('input[name="phone_is_primary"]', $newFieldset).on('click', function()
        {
            $('input[name$="[is_primary_phone]"]').val('');
            $('input[name$="[is_primary_phone]"]', $newFieldset).val(1);
        });


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
        $('a.search_keluarahan', $newFieldset).attr('href',baseAppUrl+'search_kelurahan/pasien/'+counter);
        

        $('input#radio_primary_alamat_id_1').prop('checked', true);
        $('input[name$="[is_primary_alamat]"]').val('');

        $('input#primary_alamat_id_1').val(1);
        $('input[name="alamat_is_primary"]', $newFieldset).on('click', function()
        {
            $('input[name$="[is_primary_alamat]"]').val('');
            $('input[name$="[is_primary_alamat]"]', $newFieldset).val(1);
        });

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

        $('input[type=radio]', $newFieldset).uniform();
        // handleSelectNegara(counter, "tab_alamat");
        handleSelectProvinsi(counterHP, counter, "tab_alamat");
        handleSelectKota(counterHP, counter, "tab_alamat");
        handleSelectKecamatan(counterHP, counter, "tab_alamat");
        handleSelectKelurahan(counterHP, counter, "tab_alamat");
    };

    var addFieldsetHubunganPasien = function(form)
    {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul.hubungan-pasien', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            $btnSubjekTelp = $('a#btn_edit_subjek_telp_' + counter)
            ;

        $('a.del-hub-pasien', $newFieldset).on('click', function(){
            // console.log($(this).parents('.fieldset').eq(0));
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });
         $('input[type=radio]', $newFieldset).uniform();

        $('a.set_penanggung_jawab', $newFieldset).on('click', function(){
            $.each($('.send-data'), function(idx, value){
                // alert(this.value);
                // alert($(this).prop('id'));
                $(this).attr('value', this.value);
            });
            handleSetPenanggungJawab($(this).data('row'));
            $('div#penanggung_jawab_1').remove();
            $('a.penanggung_jawab').addClass('hidden');
            $('a.set_penanggung_jawab').removeClass('hidden');

            $('a#set_penanggung_jawab_'+$(this).data('row')).addClass('hidden');
            $('a#penanggung_jawab_'+$(this).data('row')).removeClass('hidden');
            
            $('input[name$="[set_penanggung_jawab]"]').val('');
            $('input#set_penanggung_jawab_' +$(this).data('row')).val(1);

            $('#check_penanggung_jawab').val(0)
            // $('.fieldset', $newFieldset).prop('id', 'a');
            // $('ul#ul_penanggung_jawab').html($('li.fieldset').html());
        });


        var hpAlamatCounter          = 1,
            hpPhoneCounter           = 1;

        formsHPAlamat = {
            'hp_alamat' : 
            {            
                section  : $('#section-hp-alamat', $newFieldset),
                template : $.validator.format( tplFormHPAlamat.replace(regExpTplHPAlamat, '_id_{0}') ), //ubah ke format template jquery validator
                counter  : function(){ hpAlamatCounter++; return hpAlamatCounter-1; }
            }   
        };

        $.each(formsHPAlamat, function(idx, form){
            // handle button add
            $('a.add-hp-alamat', form.section).on('click', function(){
                addFieldsetHPAlamat(form, counter);
            });
             
            // beri satu fieldset kosong
            addFieldsetHPAlamat(form, counter);
            //addFieldsetAlamat(form);
        });

        formsHPPhone = {
            'hp_phone' : 
            {            
                section  : $('#section-hp-phone', $newFieldset),
                template : $.validator.format( tplFormHPPhone.replace(regExpTplHPPhone, '_id_{0}') ), //ubah ke format template jquery validator
                counter  : function(){ hpPhoneCounter++; return hpPhoneCounter-1; }
            }   
        };

        $.each(formsHPPhone, function(idx, form){
            // handle button add
            $('a.add-hp-phone', form.section).on('click', function(){
                addFieldsetHPPhone(form, counter);
            });
             
            // beri satu fieldset kosong
            addFieldsetHPPhone(form, counter);
            //addFieldsetAlamat(form);
        });

        handleUploadifyHP(counter);

        
    };

    var addFieldsetHPAlamat = function(form, counterHP)
    {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul.hp-alamat', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            rowAlamat          = $('input#row_alamat').val(), // di ambil berdasarkan button sesuai row
            $btnSubjekAlamat   = $('a#btn_edit_subjek_hp_alamat_' + counter), // di ambil berdasarkan button sesuai row
            $btnEditNegara     = $('a#btn_edit_hp_negara_' + counter), // di ambil berdasarkan button sesuai row
            $btnEditProvinsi   = $('a#btn_edit_hp_provinsi_' + counter),
            $btnEditKota       = $('a#btn_edit_hp_kota_' + counter),
            $btnEditKecamatan  = $('a#btn_edit_hp_kecamatan_' + counter),
            $btnEditKelurahan  = $('a#btn_edit_hp_kelurahan_' + counter)
        ;

        $counter.val(counter);
        
        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });
         $('input[type=radio]', $newFieldset).uniform();
        
        $('a.search_keluarahan', $newFieldset).attr('href',baseAppUrl+'search_kelurahan/hub_pasien/'+counter);


        $('input.address_radio', $newFieldset).on('change', function(){
            // alert($(this).prop('checked'));
            handleIsPrimary($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        $('.hp_subjek', $newFieldset).attr('name', counterHP +'_'+ $('.hp_subjek', $newFieldset).attr('name'));
        $('.hp_subjek', $newFieldset).attr('id', counterHP +'_'+ $('.hp_subjek', $newFieldset).attr('id'));

        $('.input-hp-subjek', $newFieldset).attr('id', counterHP +'_'+ $('.input-hp-subjek', $newFieldset).attr('id'));
        $('.edit-subjek', $newFieldset).attr('id', counterHP +'_'+ $('.edit-subjek', $newFieldset).attr('id'));
        $('.delete-subjek', $newFieldset).attr('id', counterHP +'_'+ $('.delete-subjek', $newFieldset).attr('id'));
        $('.save-subjek', $newFieldset).attr('id', counterHP +'_'+ $('.save-subjek', $newFieldset).attr('id'));
        $('.cancel-subjek', $newFieldset).attr('id', counterHP +'_'+ $('.cancel-subjek', $newFieldset).attr('id'));

        $('.hp_negara', $newFieldset).attr('id', counterHP +'_'+ $('.hp_negara', $newFieldset).attr('id'));
        $('.input-negara', $newFieldset).attr('id', counterHP +'_'+ $('.input-negara', $newFieldset).attr('id'));
        $('.edit-negara', $newFieldset).attr('id', counterHP +'_'+ $('.edit-negara', $newFieldset).attr('id'));
        $('.delete-negara', $newFieldset).attr('id', counterHP +'_'+ $('.delete-negara', $newFieldset).attr('id'));
        $('.save-negara', $newFieldset).attr('id', counterHP +'_'+ $('.save-negara', $newFieldset).attr('id'));
        $('.cancel-negara', $newFieldset).attr('id', counterHP +'_'+ $('.cancel-negara', $newFieldset).attr('id'));

        $('.hp_provinsi', $newFieldset).attr('id', counterHP +'_'+ $('.hp_provinsi', $newFieldset).attr('id'));
        $('.input-provinsi', $newFieldset).attr('id', counterHP +'_'+ $('.input-provinsi', $newFieldset).attr('id'));
        $('.edit-provinsi', $newFieldset).attr('id', counterHP +'_'+ $('.edit-provinsi', $newFieldset).attr('id'));
        $('.delete-provinsi', $newFieldset).attr('id', counterHP +'_'+ $('.delete-provinsi', $newFieldset).attr('id'));
        $('.save-provinsi', $newFieldset).attr('id', counterHP +'_'+ $('.save-provinsi', $newFieldset).attr('id'));
        $('.cancel-provinsi', $newFieldset).attr('id', counterHP +'_'+ $('.cancel-provinsi', $newFieldset).attr('id'));

        $('.hp_kota', $newFieldset).attr('id', counterHP +'_'+ $('.hp_kota', $newFieldset).attr('id'));
        $('.input-kota', $newFieldset).attr('id', counterHP +'_'+ $('.input-kota', $newFieldset).attr('id'));
        $('.edit-kota', $newFieldset).attr('id', counterHP +'_'+ $('.edit-kota', $newFieldset).attr('id'));
        $('.delete-kota', $newFieldset).attr('id', counterHP +'_'+ $('.delete-kota', $newFieldset).attr('id'));
        $('.save-kota', $newFieldset).attr('id', counterHP +'_'+ $('.save-kota', $newFieldset).attr('id'));
        $('.cancel-kota', $newFieldset).attr('id', counterHP +'_'+ $('.cancel-kota', $newFieldset).attr('id'));

        $('.hp_kecamatan', $newFieldset).attr('id', counterHP +'_'+ $('.hp_kecamatan', $newFieldset).attr('id'));
        $('.input-kecamatan', $newFieldset).attr('id', counterHP +'_'+ $('.input-kecamatan', $newFieldset).attr('id'));
        $('.edit-kecamatan', $newFieldset).attr('id', counterHP +'_'+ $('.edit-kecamatan', $newFieldset).attr('id'));
        $('.delete-kecamatan', $newFieldset).attr('id', counterHP +'_'+ $('.delete-kecamatan', $newFieldset).attr('id'));
        $('.save-kecamatan', $newFieldset).attr('id', counterHP +'_'+ $('.save-kecamatan', $newFieldset).attr('id'));
        $('.cancel-kecamatan', $newFieldset).attr('id', counterHP +'_'+ $('.cancel-kecamatan', $newFieldset).attr('id'));

        $('.hp_kelurahan', $newFieldset).attr('id', counterHP +'_'+ $('.hp_kelurahan', $newFieldset).attr('id'));
        $('.input-kelurahan', $newFieldset).attr('id', counterHP +'_'+ $('.input-kelurahan', $newFieldset).attr('id'));
        $('.edit-kelurahan', $newFieldset).attr('id', counterHP +'_'+ $('.edit-kelurahan', $newFieldset).attr('id'));
        $('.delete-kelurahan', $newFieldset).attr('id', counterHP +'_'+ $('.delete-kelurahan', $newFieldset).attr('id'));
        $('.save-kelurahan', $newFieldset).attr('id', counterHP +'_'+ $('.save-kelurahan', $newFieldset).attr('id'));
        $('.cancel-kelurahan', $newFieldset).attr('id', counterHP +'_'+ $('.cancel-kelurahan', $newFieldset).attr('id'));



        $('.hp_alamat', $newFieldset).attr('name', counterHP +'_'+ $('.hp_alamat', $newFieldset).attr('name'));
        $('.hp_rt', $newFieldset).attr('name', counterHP +'_'+ $('.hp_rt', $newFieldset).attr('name'));
        $('.hp_rw', $newFieldset).attr('name', counterHP +'_'+ $('.hp_rw', $newFieldset).attr('name'));
        $('.hp_negara', $newFieldset).attr('name', counterHP +'_'+ $('.hp_negara', $newFieldset).attr('name'));
        $('.hp_provinsi', $newFieldset).attr('name', counterHP +'_'+ $('.hp_provinsi', $newFieldset).attr('name'));
        $('.hp_kota', $newFieldset).attr('name', counterHP +'_'+ $('.hp_kota', $newFieldset).attr('name'));
        $('.hp_kecamatan', $newFieldset).attr('name', counterHP +'_'+ $('.hp_kecamatan', $newFieldset).attr('name'));
        $('.hp_kelurahan', $newFieldset).attr('name', counterHP +'_'+ $('.hp_kelurahan', $newFieldset).attr('name'));
        $('.hp_kode', $newFieldset).attr('name', counterHP +'_'+ $('.hp_kode', $newFieldset).attr('name'));
        $('.hp_kode_pos', $newFieldset).attr('name', counterHP +'_'+ $('.hp_kode_pos', $newFieldset).attr('name'));
        // $('.hp_primary_alamat', $newFieldset).attr('name', counterHP +'_'+ $('.hp_primary_alamat', $newFieldset).attr('name'));

        $('.hp_primary_alamat', $newFieldset).attr('name', counterHP +'_'+ $('.hp_primary_alamat', $newFieldset).attr('name'));
        $('.hp_primary_alamat', $newFieldset).attr('id', counterHP +'_'+ $('.hp_primary_alamat', $newFieldset).attr('id'));
        $('.hp_primary_alamat', $newFieldset).addClass(counterHP+'_hp_primary_alamat');
        $('.is_primary_hp_alamat', $newFieldset).attr('id', counterHP +'_'+ $('.is_primary_hp_alamat', $newFieldset).attr('id'));
        $('.is_primary_hp_alamat', $newFieldset).attr('name', counterHP +'_'+ $('.is_primary_hp_alamat', $newFieldset).attr('name'));
        // $('.hp_phone_is_primary', $newFieldset).addClass('hp_phone_is_primary_'+counterHP);

        $('input#'+counterHP+'_radio_primary_hp_alamat_id_1').prop('checked', true);
        $('input.'+counterHP+'_hp_primary_alamat').val('');
        
        $('input#'+counterHP+'_primary_hp_alamat_id_1').val(1);

        $('input[name="'+counterHP+'_hp_alamat_is_primary"]', $newFieldset).on('click', function()
        {
            // alert(counterHP);
            $('input.'+counterHP+'_hp_primary_alamat').val('');
            $('input.'+counterHP+'_hp_primary_alamat', $newFieldset).val(1);
        });

        $btnSubjekAlamat.on('click', function(){
            handleEditSubjekAlamat(counterHP, counter, "tab_hubungan_pasien");
        });

        $btnEditNegara.on('click', function(){
            handleEditNegara(counterHP, counter, "tab_hubungan_pasien");
            // alert(counter);
        });

        $btnEditProvinsi.on('click', function(){
            handleEditProvinsi(counterHP, counter, "tab_hubungan_pasien");
        });

        $btnEditKota.on('click', function(){
            handleEditKota(counterHP, counter, "tab_hubungan_pasien");
        });

        $btnEditKecamatan.on('click', function(){
            handleEditKecamatan(counterHP, counter, "tab_hubungan_pasien");
        });

        $btnEditKelurahan.on('click', function(){
            handleEditKelurahan(counterHP, counter, "tab_hubungan_pasien");
        });

        // handleSelectNegara(counter, "tab_hubungan_pasien");
        handleSelectProvinsi(counterHP, counter, "tab_hubungan_pasien");
        handleSelectKota(counterHP, counter, "tab_hubungan_pasien");
        handleSelectKecamatan(counterHP, counter, "tab_hubungan_pasien");
        handleSelectKelurahan(counterHP, counter, "tab_hubungan_pasien");

    };

    var addFieldsetHPPhone = function(form, counterHP)
    {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul.hp-phone', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            $btnSubjekTelp = $('a#btn_edit_subjek_hp_telp_' + counter)
            ;

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });
         $('input[type=radio]', $newFieldset).uniform();

        $('.hp_subjek_telp', $newFieldset).attr('name', counterHP +'_'+ $('.hp_subjek_telp', $newFieldset).attr('name'));
        $('.hp_subjek_telp', $newFieldset).attr('id', counterHP +'_'+ $('.hp_subjek_telp', $newFieldset).attr('id'));
        $('.input-subjek-telp', $newFieldset).attr('id', counterHP +'_'+ $('.input-subjek-telp', $newFieldset).attr('id'));
        $('.edit-subjek-telp', $newFieldset).attr('id', counterHP +'_'+ $('.edit-subjek-telp', $newFieldset).attr('id'));
        $('.delete-subjek-telp', $newFieldset).attr('id', counterHP +'_'+ $('.delete-subjek-telp', $newFieldset).attr('id'));
        $('.save-subjek-telp', $newFieldset).attr('id', counterHP +'_'+ $('.save-subjek-telp', $newFieldset).attr('id'));
        $('.cancel-subjek-telp', $newFieldset).attr('id', counterHP +'_'+ $('.cancel-subjek-telp', $newFieldset).attr('id'));

        $('.hp_no_telp', $newFieldset).attr('name', counterHP +'_'+ $('.hp_no_telp', $newFieldset).attr('name'));
        $('.hp_primary_telp', $newFieldset).attr('name', counterHP +'_'+ $('.hp_primary_telp', $newFieldset).attr('name'));
        $('.hp_primary_telp', $newFieldset).attr('id', counterHP +'_'+ $('.hp_primary_telp', $newFieldset).attr('id'));
        $('.hp_primary_telp', $newFieldset).addClass(counterHP+'_hp_primary_telp');
        $('.is_primary_hp_phone', $newFieldset).attr('id', counterHP +'_'+ $('.is_primary_hp_phone', $newFieldset).attr('id'));
        $('.is_primary_hp_phone', $newFieldset).attr('name', counterHP +'_'+ $('.is_primary_hp_phone', $newFieldset).attr('name'));
        // $('.hp_phone_is_primary', $newFieldset).addClass('hp_phone_is_primary_'+counterHP);

        $('input#'+counterHP+'_radio_primary_hp_phone_id_1').prop('checked', true);
        $('input.'+counterHP+'_hp_primary_telp').val('');
        
        $('input#'+counterHP+'_primary_hp_phone_id_1').val(1);

        $('input[name="'+counterHP+'_hp_phone_is_primary"]', $newFieldset).on('click', function()
        {
            // alert(counterHP);
            $('input.'+counterHP+'_hp_primary_telp').val('');
            $('input.'+counterHP+'_hp_primary_telp', $newFieldset).val(1);
        });


        $('input.address_radio', $newFieldset).on('change', function(){
            // alert($(this).prop('checked'));
            handleIsPrimary($(this).parents('.fieldset').eq(0));
        });

        
        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');


         $btnSubjekTelp.on('click', function(){
            handleEditSubjekTelp(counterHP, counter, "tab_hubungan_pasien");
        });

        // handleSelectSubjekTelp(counter);
        // console.log($fieldsetContainer);

    };

    var addFieldsetPenanggungJawab = function(form)
    {

        if($('#check_penanggung_jawab').val() == '0'){
            var 
                $section           = form.section,
                $fieldsetContainer = $('ul.penanggung-jawab', $section),
                counter            = form.counter(),
                $newFieldset       = $(form.template(1)).appendTo($fieldsetContainer),
                $btnSubjekTelp = $('a#btn_edit_subjek_telp_' + counter)
                ;

            $('a.del-hub-pasien', $newFieldset).on('click', function(){
                console.log($(this).parents('.fieldset').eq(0));
                // handleDeleteFieldset($(this).parents('.fieldset').eq(0));
            });
             $('input[type=radio]', $newFieldset).uniform();

            $('a.set_penanggung_jawab', $newFieldset).on('click', function(){
                $.each($('.send-data'), function(idx, value){
                    // alert(this.value);
                    // alert($(this).prop('id'));
                    $(this).attr('value', this.value);
                });
                handleSetPenanggungJawab($(this).data('row'));

                $('a#set_penanggung_jawab_'+$(this).data('row')).addClass('hidden');
                $('a#penanggung_jawab_'+$(this).data('row')).removeClass('hidden');
                
                $('input[name$="[set_penanggung_jawab]"]').val('');
                $('input#set_penanggung_jawab_' +$(this).data('row')).val(1);
                // $('.fieldset', $newFieldset).prop('id', 'a');
                // $('ul#ul_penanggung_jawab').html($('li.fieldset').html());
            });


            formsPJAlamat = {
                'pj_alamat' : 
                {            
                    section  : $('#section-pj-alamat', $newFieldset),
                    template : $.validator.format( tplFormPJAlamat.replace(regExpTplPJAlamat, '_id_{0}') ), //ubah ke format template jquery validator
                    counter  : function(){ pjAlamatCounter++; return pjAlamatCounter-1; }
                }   
            };

            $.each(formsPJAlamat, function(idx, form){
                // handle button add
                $('a.add-pj-alamat', form.section).on('click', function(){
                    addFieldsetPJAlamat(form);
                });
                 
                // beri satu fieldset kosong
                addFieldsetPJAlamat(form);
                //addFieldsetAlamat(form);
            });

            formsPJPhone = {
                'pj_phone' : 
                {            
                    section  : $('#section-pj-phone', $newFieldset),
                    template : $.validator.format( tplFormPJPhone.replace(regExpTplPJPhone, '_id_{0}') ), //ubah ke format template jquery validator
                    counter  : function(){ pjPhoneCounter++; return pjPhoneCounter-1; }
                }   
            };

            $.each(formsPJPhone, function(idx, form){
                // handle button add
                $('a.add-pj-phone', form.section).on('click', function(){
                    addFieldsetPJPhone(form);
                });
                 
                // beri satu fieldset kosong
                addFieldsetPJPhone(form);
                //addFieldsetAlamat(form);
            });

            $('#check_penanggung_jawab').val(1);

            handleUploadifyPJ(counter);
        }
        
    };

    var addFieldsetPJAlamat = function(form)
    {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul.pj-alamat', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            rowAlamat          = $('input#row_alamat').val(), // di ambil berdasarkan button sesuai row
            $btnSubjekAlamat   = $('a#btn_edit_subjek_pj_alamat_' + counter), // di ambil berdasarkan button sesuai row
            $btnEditNegara     = $('a#btn_edit_pj_negara_' + counter), // di ambil berdasarkan button sesuai row
            $btnEditProvinsi   = $('a#btn_edit_pj_provinsi_' + counter),
            $btnEditKota       = $('a#btn_edit_pj_kota_' + counter),
            $btnEditKecamatan  = $('a#btn_edit_pj_kecamatan_' + counter),
            $btnEditKelurahan  = $('a#btn_edit_pj_kelurahan_' + counter)
        ;

        $counter.val(counter);
         $('input[type=radio]', $newFieldset).uniform();
        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });

        $('a.search_keluarahan', $newFieldset).attr('href',baseAppUrl+'search_kelurahan/pj_pasien/'+counter);

        $('input#radio_primary_pj_alamat_id_1').prop('checked', true);
        $('input[name$="[is_primary_pj_alamat]"]').val('');

        $('input#primary_pj_alamat_id_1').val(1);
        $('input[name="pj_alamat_is_primary"]', $newFieldset).on('click', function()
        {
            $('input[name$="[is_primary_pj_alamat]"]').val('');
            $('input[name$="[is_primary_pj_alamat]"]', $newFieldset).val(1);
        });

        $('input.address_radio', $newFieldset).on('change', function(){
            // alert($(this).prop('checked'));
            handleIsPrimary($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');


        var counterHP = null;
        $btnSubjekAlamat.on('click', function(){
            handleEditSubjekAlamat(counterHP, counter, "tab_penanggung_jawab");
        });

        $btnEditNegara.on('click', function(){
            handleEditNegara(counterHP, counter, "tab_penanggung_jawab");
            // alert(counter);
        });

        $btnEditProvinsi.on('click', function(){
            handleEditProvinsi(counterHP, counter, "tab_penanggung_jawab");
        });

        $btnEditKota.on('click', function(){
            handleEditKota(counterHP, counter, "tab_penanggung_jawab");
        });

        $btnEditKecamatan.on('click', function(){
            handleEditKecamatan(counterHP, counter, "tab_penanggung_jawab");
        });

        $btnEditKelurahan.on('click', function(){
            handleEditKelurahan(counterHP, counter, "tab_penanggung_jawab");
        });

        // handleSelectNegara(counter, "tab_penanggung_jawab");
        handleSelectProvinsi(counterHP, counter, "tab_penanggung_jawab");
        handleSelectKota(counterHP, counter, "tab_penanggung_jawab");
        handleSelectKecamatan(counterHP, counter, "tab_penanggung_jawab");
        handleSelectKelurahan(counterHP, counter, "tab_penanggung_jawab");

    };

    var addFieldsetPJPhone = function(form)
    {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul.pj-phone', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            $btnSubjekTelp = $('a#btn_edit_subjek_pj_telp_' + counter)
            ;

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });
         $('input[type=radio]', $newFieldset).uniform();

        $('input#radio_primary_pj_phone_id_1').prop('checked', true);
        $('input[name$="[is_primary_pj_phone]"]').val('');
        
        $('input#primary_pj_phone_id_1').val(1);
        $('input[name="pj_phone_is_primary"]', $newFieldset).on('click', function()
        {
            $('input[name$="[is_primary_pj_phone]"]').val('');
            $('input[name$="[is_primary_pj_phone]"]', $newFieldset).val(1);
        });


        $('input.address_radio', $newFieldset).on('change', function(){
            // alert($(this).prop('checked'));
            handleIsPrimary($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        var counterHP = null;
         $btnSubjekTelp.on('click', function(){
            handleEditSubjekTelp(counterHP, counter, "tab_penanggung_jawab");
        });

        // handleSelectSubjekTelp(counter);
        // console.log($fieldsetContainer);

    };
    var handleEditSubjekTelp = function(counterHP, counter, tab){
        if (tab == "tab_phone") {
            var $inputSubjekTelp = $('input#input_subjek_telp_' + counter),
                $selectSubjekTelp = $('select#subjek_telp_' + counter),
                $btnSaveSubjekTelp = $('a#btn_save_subjek_telp_' + counter),
                $btnCancelSubjekTelp = $('a#btn_cancel_subjek_telp_' + counter),
                $btnEditSubjekTelp = $('a#btn_edit_subjek_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#btn_delete_subjek_telp_' + counter);

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
        }else if (tab == "tab_hubungan_pasien"){
            var $inputSubjekTelp = $('input#'+counterHP+'_input_subjek_hp_telp_' + counter),
                $selectSubjekTelp = $('select#'+counterHP+'_subjek_hp_telp_' + counter),
                $btnSaveSubjekTelp = $('a#'+counterHP+'_btn_save_subjek_hp_telp_' + counter),
                $btnCancelSubjekTelp = $('a#'+counterHP+'_btn_cancel_subjek_hp_telp_' + counter),
                $btnEditSubjekTelp = $('a#'+counterHP+'_btn_edit_subjek_hp_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#'+counterHP+'_btn_delete_subjek_hp_telp_' + counter);

            $btnEditSubjekTelp.addClass("hidden");
            $btnDeleteSubjekTelp.addClass("hidden");
            $selectSubjekTelp.addClass("hidden");

            $btnSaveSubjekTelp.removeClass("hidden");
            $btnCancelSubjekTelp.removeClass("hidden");
            $inputSubjekTelp.removeClass("hidden");

            $inputSubjekTelp.focus();

            $btnCancelSubjekTelp.on('click', function(){
                handleCancelSubjekTelp(counterHP, counter, "tab_hubungan_pasien");
            });

            // $('a[rel=ajax]').die('click').click(function (event) { -> will load once..
            $btnSaveSubjekTelp.die('click').click(function(e){
                // e.preventDefault();
                handleSaveSubjekTelp(counterHP, counter, "tab_hubungan_pasien");

                e.stopImmediatePropagation();  

            });
        }else{
            var $inputSubjekTelp = $('input#input_subjek_pj_telp_' + counter),
                $selectSubjekTelp = $('select#subjek_pj_telp_' + counter),
                $btnSaveSubjekTelp = $('a#btn_save_subjek_pj_telp_' + counter),
                $btnCancelSubjekTelp = $('a#btn_cancel_subjek_pj_telp_' + counter),
                $btnEditSubjekTelp = $('a#btn_edit_subjek_pj_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#btn_delete_subjek_pj_telp_' + counter);

            $btnEditSubjekTelp.addClass("hidden");
            $btnDeleteSubjekTelp.addClass("hidden");
            $selectSubjekTelp.addClass("hidden");

            $btnSaveSubjekTelp.removeClass("hidden");
            $btnCancelSubjekTelp.removeClass("hidden");
            $inputSubjekTelp.removeClass("hidden");

            $inputSubjekTelp.focus();

            $btnCancelSubjekTelp.on('click', function(){
                handleCancelSubjekTelp(counterHP, counter, "tab_penanggung_jawab");
            });

            // $('a[rel=ajax]').die('click').click(function (event) { -> will load once..
            $btnSaveSubjekTelp.die('click').click(function(e){
                // e.preventDefault();
                handleSaveSubjekTelp(counterHP, counter, "tab_penanggung_jawab");

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

                $btnEditSubjekTelp.removeClass("hidden");
                $btnDeleteSubjekTelp.removeClass("hidden");
                $selectSubjekTelp.removeClass("hidden");

                $btnSaveSubjekTelp.addClass("hidden");
                $btnCancelSubjekTelp.addClass("hidden");
                $inputSubjekTelp.addClass("hidden");

                $inputSubjekTelp.val("");
        }else if(tab == "tab_hubungan_pasien"){
            var $inputSubjekTelp = $('input#'+counterHP+'_input_subjek_hp_telp_' + counter),
                $selectSubjekTelp = $('select#'+counterHP+'_subjek_hp_telp_' + counter),
                $btnSaveSubjekTelp = $('a#'+counterHP+'_btn_save_subjek_hp_telp_' + counter),
                $btnCancelSubjekTelp = $('a#'+counterHP+'_btn_cancel_subjek_hp_telp_' + counter),
                $btnEditSubjekTelp = $('a#'+counterHP+'_btn_edit_subjek_hp_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#'+counterHP+'_btn_delete_subjek_hp_telp_' + counter);

                $btnEditSubjekTelp.removeClass("hidden");
                $btnDeleteSubjekTelp.removeClass("hidden");
                $selectSubjekTelp.removeClass("hidden");

                $btnSaveSubjekTelp.addClass("hidden");
                $btnCancelSubjekTelp.addClass("hidden");
                $inputSubjekTelp.addClass("hidden");

                $inputSubjekTelp.val(""); 
        }else{
            var $inputSubjekTelp = $('input#input_subjek_pj_telp_' + counter),
                $selectSubjekTelp = $('select#subjek_pj_telp_' + counter),
                $btnSaveSubjekTelp = $('a#btn_save_subjek_pj_telp_' + counter),
                $btnCancelSubjekTelp = $('a#btn_cancel_subjek_pj_telp_' + counter),
                $btnEditSubjekTelp = $('a#btn_edit_subjek_pj_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#btn_delete_subjek_pj_telp_' + counter);

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
                $btnDeleteSubjekTelp = $('a#btn_delete_subjek_telp_' + counter);
            

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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputSubjekTelp = $('input#'+counterHP+'_input_subjek_hp_telp_' + counter),
                $selectSubjekTelp = $('select#'+counterHP+'_subjek_hp_telp_' + counter),
                $btnSaveSubjekTelp = $('a#'+counterHP+'_btn_save_subjek_hp_telp_' + counter),
                $btnCancelSubjekTelp = $('a#'+counterHP+'_btn_cancel_subjek_hp_telp_' + counter),
                $btnEditSubjekTelp = $('a#'+counterHP+'_btn_edit_subjek_hp_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#'+counterHP+'_btn_delete_subjek_hp_telp_' + counter);
            

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

                        // alert(results);
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
        }else{
            var $inputSubjekTelp = $('input#input_subjek_pj_telp_' + counter),
                $selectSubjekTelp = $('select#subjek_pj_telp_' + counter),
                $btnSaveSubjekTelp = $('a#btn_save_subjek_pj_telp_' + counter),
                $btnCancelSubjekTelp = $('a#btn_cancel_subjek_pj_telp_' + counter),
                $btnEditSubjekTelp = $('a#btn_edit_subjek_pj_telp_' + counter),
                $btnDeleteSubjekTelp = $('a#btn_delete_subjek_pj_telp_' + counter);
            

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

                        // alert(results);
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
                    $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_alamat_' + counter);

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
            }else if(tab == "tab_hubungan_pasien"){
                // alert('input#'+counterHP+'_input_subjek_hp_alamat_' + counter);
                var $inputSubjekAlamat = $('input#'+counterHP+'_input_subjek_hp_alamat_' + counter),
                    $selectSubjekAlamat = $('select#'+counterHP+'_subjek_hp_alamat_' + counter),
                    $btnSaveSubjekAlamat = $('a#'+counterHP+'_btn_save_subjek_hp_alamat_' + counter),
                    $btnCancelSubjekAlamat = $('a#'+counterHP+'_btn_cancel_subjek_hp_alamat_' + counter),
                    $btnEditSubjekAlamat = $('a#'+counterHP+'_btn_edit_subjek_hp_alamat_' + counter),
                    $btnDeleteSubjekAlamat = $('a#'+counterHP+'_btn_delete_subjek_hp_alamat_' + counter);

                $btnEditSubjekAlamat.addClass("hidden");
                $btnDeleteSubjekAlamat.addClass("hidden");
                $selectSubjekAlamat.addClass("hidden");

                $btnSaveSubjekAlamat.removeClass("hidden");
                $btnCancelSubjekAlamat.removeClass("hidden");
                $inputSubjekAlamat.removeClass("hidden");

                $inputSubjekAlamat.focus();

                $btnCancelSubjekAlamat.on('click', function(){
                    handleCancelSubjekAlamat(counterHP, counter, "tab_hubungan_pasien");
                });

                $btnSaveSubjekAlamat.on('click', function(e){
                    handleSaveSubjekAlamat(counterHP, counter, "tab_hubungan_pasien");
                    e.stopImmediatePropagation();  

                });
            }else{
                var $inputSubjekAlamat = $('input#input_subjek_pj_alamat_' + counter),
                    $selectSubjekAlamat = $('select#subjek_pj_alamat_' + counter),
                    $btnSaveSubjekAlamat = $('a#btn_save_subjek_pj_alamat_' + counter),
                    $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_pj_alamat_' + counter),
                    $btnEditSubjekAlamat = $('a#btn_edit_subjek_pj_alamat_' + counter),
                    $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_pj_alamat_' + counter);

                $btnEditSubjekAlamat.addClass("hidden");
                $btnDeleteSubjekAlamat.addClass("hidden");
                $selectSubjekAlamat.addClass("hidden");

                $btnSaveSubjekAlamat.removeClass("hidden");
                $btnCancelSubjekAlamat.removeClass("hidden");
                $inputSubjekAlamat.removeClass("hidden");

                $inputSubjekAlamat.focus();

                $btnCancelSubjekAlamat.on('click', function(){
                    handleCancelSubjekAlamat(counterHP, counter, "tab_penanggung_jawab");
                });

                $btnSaveSubjekAlamat.on('click', function(e){
                    handleSaveSubjekAlamat(counterHP, counter, "tab_penanggung_jawab");
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
                $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_alamat_' + counter);

                $btnEditSubjekAlamat.removeClass("hidden");
                $btnDeleteSubjekAlamat.removeClass("hidden");
                $selectSubjekAlamat.removeClass("hidden");

                $btnSaveSubjekAlamat.addClass("hidden");
                $btnCancelSubjekAlamat.addClass("hidden");
                $inputSubjekAlamat.addClass("hidden");

                $inputSubjekAlamat.val("");
        }else if(tab == "tab_hubungan_pasien"){
            var $inputSubjekAlamat = $('input#'+counterHP+'_input_subjek_hp_alamat_' + counter),
                $selectSubjekAlamat = $('select#'+counterHP+'_subjek_hp_alamat_' + counter),
                $btnSaveSubjekAlamat = $('a#'+counterHP+'_btn_save_subjek_hp_alamat_' + counter),
                $btnCancelSubjekAlamat = $('a#'+counterHP+'_btn_cancel_subjek_hp_alamat_' + counter),
                $btnEditSubjekAlamat = $('a#'+counterHP+'_btn_edit_subjek_hp_alamat_' + counter),
                $btnDeleteSubjekAlamat = $('a#'+counterHP+'_btn_delete_subjek_hp_alamat_' + counter);

                $btnEditSubjekAlamat.removeClass("hidden");
                $btnDeleteSubjekAlamat.removeClass("hidden");
                $selectSubjekAlamat.removeClass("hidden");

                $btnSaveSubjekAlamat.addClass("hidden");
                $btnCancelSubjekAlamat.addClass("hidden");
                $inputSubjekAlamat.addClass("hidden");

                $inputSubjekAlamat.val("");
        }else{
            var $inputSubjekAlamat = $('input#input_subjek_pj_alamat_' + counter),
                $selectSubjekAlamat = $('select#subjek_pj_alamat_' + counter),
                $btnSaveSubjekAlamat = $('a#btn_save_subjek_pj_alamat_' + counter),
                $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_pj_alamat_' + counter),
                $btnEditSubjekAlamat = $('a#btn_edit_subjek_pj_alamat_' + counter),
                $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_pj_alamat_' + counter);

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
                $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_alamat_' + counter);
                
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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputSubjekAlamat = $('input#'+counterHP+'_input_subjek_hp_alamat_' + counter),
                $selectSubjekAlamat = $('select#'+counterHP+'_subjek_hp_alamat_' + counter),
                $btnSaveSubjekAlamat = $('a#'+counterHP+'_btn_save_subjek_hp_alamat_' + counter),
                $btnCancelSubjekAlamat = $('a#'+counterHP+'_btn_cancel_subjek_hp_alamat_' + counter),
                $btnEditSubjekAlamat = $('a#'+counterHP+'_btn_edit_subjek_hp_alamat_' + counter),
                $btnDeleteSubjekAlamat = $('a#'+counterHP+'_btn_delete_subjek_hp_alamat_' + counter);
                
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
        }else{
            var $inputSubjekAlamat = $('input#input_subjek_pj_alamat_' + counter),
                $selectSubjekAlamat = $('select#subjek_pj_alamat_' + counter),
                $btnSaveSubjekAlamat = $('a#btn_save_subjek_pj_alamat_' + counter),
                $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_pj_alamat_' + counter),
                $btnEditSubjekAlamat = $('a#btn_edit_subjek_pj_alamat_' + counter),
                $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_pj_alamat_' + counter);
                
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
        //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
            
    }

    var handleEditNegara = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputNegara = $('input#input_negara_' + counter),
                $selectNegara = $('select#negara_' + counter),
                $btnSaveNegara = $('a#btn_save_negara_' + counter),
                $btnCancelNegara = $('a#btn_cancel_negara_' + counter),
                $btnEditNegara = $('a#btn_edit_negara_' + counter);

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
        }else if(tab == "tab_hubungan_pasien"){
            // alert('a#'+counterHP+'_btn_save_hp_negara_'+ counter);
            var $inputNegara = $('input#'+counterHP+'_input_hp_negara_' + counter),
                $selectNegara = $('select#'+counterHP+'_hp_negara_' + counter),
                $btnSaveNegara = $('a#'+counterHP+'_btn_save_hp_negara_' + counter),
                $btnCancelNegara = $('a#'+counterHP+'_btn_cancel_hp_negara_' + counter),
                $btnEditNegara = $('a#'+counterHP+'_btn_edit_hp_negara_' + counter);

            $btnEditNegara.addClass("hidden");
            $selectNegara.addClass("hidden");

            $btnSaveNegara.removeClass("hidden");
            $btnCancelNegara.removeClass("hidden");
            $inputNegara.removeClass("hidden");

            $inputNegara.focus();

            $btnCancelNegara.on('click', function(){
                handleCancelNegara(counterHP, counter, "tab_hubungan_pasien");
            });

            $btnSaveNegara.on('click', function(e){
            // alert('a#'+counterHP+'_btn_save_hp_negara_'+ counter);

                handleSaveNegara(counterHP, counter, "tab_hubungan_pasien");
                e.stopImmediatePropagation();
            }); 
        }else{
            var $inputNegara = $('input#input_pj_negara_' + counter),
                $selectNegara = $('select#pj_negara_' + counter),
                $btnSaveNegara = $('a#btn_save_pj_negara_' + counter),
                $btnCancelNegara = $('a#btn_cancel_pj_negara_' + counter),
                $btnEditNegara = $('a#btn_edit_pj_negara_' + counter);

            $btnEditNegara.addClass("hidden");
            $selectNegara.addClass("hidden");

            $btnSaveNegara.removeClass("hidden");
            $btnCancelNegara.removeClass("hidden");
            $inputNegara.removeClass("hidden");

            $inputNegara.focus();

            $btnCancelNegara.on('click', function(){
                handleCancelNegara(counterHP, counter, "tab_penanggung_jawab");
            });

            $btnSaveNegara.on('click', function(e){
                handleSaveNegara(counterHP, counter, "tab_penanggung_jawab");
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

            $btnEditNegara.removeClass("hidden");
            $selectNegara.removeClass("hidden");

            $btnSaveNegara.addClass("hidden");
            $btnCancelNegara.addClass("hidden");
            $inputNegara.addClass("hidden");

            $inputProvinsi.val("");
        }else if(tab == "tab_hubungan_pasien"){
            var $inputNegara = $('input#'+counterHP+'_input_hp_negara_' + counter),
                $selectNegara = $('select#'+counterHP+'_hp_negara_' + counter),
                $btnSaveNegara = $('a#'+counterHP+'_btn_save_hp_negara_' + counter),
                $btnCancelNegara = $('a#'+counterHP+'_btn_cancel_hp_negara_' + counter),
                $btnEditNegara = $('a#'+counterHP+'_btn_edit_hp_negara_' + counter);
                $inputProvinsi = $('input#'+counterHP+'_input_hp_provinsi_' + counter),

            $btnEditNegara.removeClass("hidden");
            $selectNegara.removeClass("hidden");

            $btnSaveNegara.addClass("hidden");
            $btnCancelNegara.addClass("hidden");
            $inputNegara.addClass("hidden");

            $inputProvinsi.val("");
        }else{
            var $inputNegara = $('input#input_pj_negara_' + counter),
                $selectNegara = $('select#pj_negara_' + counter),
                $btnSaveNegara = $('a#btn_save_pj_negara_' + counter),
                $btnCancelNegara = $('a#btn_cancel_pj_negara_' + counter),
                $btnEditNegara = $('a#btn_edit_pj_negara_' + counter);
                $inputProvinsi = $('input#input_pj_provinsi_' + counter),

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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputNegara = $('input#'+counterHP+'_input_hp_negara_' + counter),
                $selectNegara    = $('select#'+counterHP+'_hp_negara_' + counter),
                $selectProvinsi  = $('select#'+counterHP+'_hp_provinsi_' + counter),
                $selectKota      = $('select#'+counterHP+'_hp_kota_' + counter),
                $selectKecamatan = $('select#'+counterHP+'_hp_kecamatan_' + counter),
                $selectKelurahan = $('select#'+counterHP+'_hp_kelurahan_' + counter),
                $btnSaveNegara   = $('a#'+counterHP+'_btn_save_hp_negara_' + counter),
                $btnCancelNegara = $('a#'+counterHP+'_btn_cancel_hp_negara_' + counter),
                $btnEditNegara   = $('a#'+counterHP+'_btn_edit_hp_negara_' + counter);
                

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
        }else{
           var $inputNegara = $('input#input_pj_negara_' + counter),
                $selectNegara    = $('select#pj_negara_' + counter),
                $selectProvinsi  = $('select#pj_provinsi_' + counter),
                $selectKota      = $('select#pj_kota_' + counter),
                $selectKecamatan = $('select#pj_kecamatan_' + counter),
                $selectKelurahan = $('select#pj_kelurahan_' + counter),
                $btnSaveNegara   = $('a#btn_save_pj_negara_' + counter),
                $btnCancelNegara = $('a#btn_cancel_pj_negara_' + counter),
                $btnEditNegara   = $('a#btn_edit_pj_negara_' + counter);
                

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

    var handleEditProvinsi = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputProvinsi = $('input#input_provinsi_' + counter),
                $selectProvinsi = $('select#provinsi_' + counter),
                $btnSaveProvinsi = $('a#btn_save_provinsi_' + counter),
                $btnCancelProvinsi = $('a#btn_cancel_provinsi_' + counter),
                $btnEditProvinsi = $('a#btn_edit_provinsi_' + counter);

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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputProvinsi = $('input#'+counterHP+'_input_hp_provinsi_' + counter),
                $selectProvinsi = $('select#'+counterHP+'_hp_provinsi_' + counter),
                $btnSaveProvinsi = $('a#'+counterHP+'_btn_save_hp_provinsi_' + counter),
                $btnCancelProvinsi = $('a#'+counterHP+'_btn_cancel_hp_provinsi_' + counter),
                $btnEditProvinsi = $('a#'+counterHP+'_btn_edit_hp_provinsi_' + counter);

            $btnEditProvinsi.addClass("hidden");
            $selectProvinsi.addClass("hidden");

            $btnSaveProvinsi.removeClass("hidden");
            $btnCancelProvinsi.removeClass("hidden");
            $inputProvinsi.removeClass("hidden");

            $inputProvinsi.focus();

            $btnCancelProvinsi.on('click', function(){
                handleCancelProvinsi(counterHP, counter, "tab_hubungan_pasien");
            });

            $btnSaveProvinsi.on('click', function(e){
                handleSaveProvinsi(counterHP, counter, "tab_hubungan_pasien");
                e.stopImmediatePropagation();
            });
        }else{
            var $inputProvinsi = $('input#input_pj_provinsi_' + counter),
                $selectProvinsi = $('select#pj_provinsi_' + counter),
                $btnSaveProvinsi = $('a#btn_save_pj_provinsi_' + counter),
                $btnCancelProvinsi = $('a#btn_cancel_pj_provinsi_' + counter),
                $btnEditProvinsi = $('a#btn_edit_pj_provinsi_' + counter);

            $btnEditProvinsi.addClass("hidden");
            $selectProvinsi.addClass("hidden");

            $btnSaveProvinsi.removeClass("hidden");
            $btnCancelProvinsi.removeClass("hidden");
            $inputProvinsi.removeClass("hidden");

            $inputProvinsi.focus();

            $btnCancelProvinsi.on('click', function(){
                handleCancelProvinsi(counterHP, counter, "tab_penanggung_jawab");
            });

            $btnSaveProvinsi.on('click', function(e){
                handleSaveProvinsi(counterHP, counter, "tab_penanggung_jawab");
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

            $btnEditProvinsi.removeClass("hidden");
            $selectProvinsi.removeClass("hidden");

            $btnSaveProvinsi.addClass("hidden");
            $btnCancelProvinsi.addClass("hidden");
            $inputProvinsi.addClass("hidden");

            $inputProvinsi.val("");
        }else if(tab == "tab_hubungan_pasien"){
            var $inputProvinsi = $('input#'+counterHP+'_input_hp_provinsi_' + counter),
                $selectProvinsi = $('select#'+counterHP+'_hp_provinsi_' + counter),
                $btnSaveProvinsi = $('a#'+counterHP+'_btn_save_hp_provinsi_' + counter),
                $btnCancelProvinsi = $('a#'+counterHP+'_btn_cancel_hp_provinsi_' + counter),
                $btnEditProvinsi = $('a#'+counterHP+'_btn_edit_hp_provinsi_' + counter);

            $btnEditProvinsi.removeClass("hidden");
            $selectProvinsi.removeClass("hidden");

            $btnSaveProvinsi.addClass("hidden");
            $btnCancelProvinsi.addClass("hidden");
            $inputProvinsi.addClass("hidden");

            $inputProvinsi.val("");
        }else{
            var $inputProvinsi = $('input#input_pj_provinsi_' + counter),
                $selectProvinsi = $('select#pj_provinsi_' + counter),
                $btnSaveProvinsi = $('a#btn_save_pj_provinsi_' + counter),
                $btnCancelProvinsi = $('a#btn_cancel_pj_provinsi_' + counter),
                $btnEditProvinsi = $('a#btn_edit_pj_provinsi_' + counter);

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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputProvinsi = $('input#'+counterHP+'_input_hp_provinsi_' + counter),
                $selectProvinsi = $('select#'+counterHP+'_hp_provinsi_' + counter),
                $selectNegara = $('select#'+counterHP+'_hp_negara_' + counter),
                $selectKota = $('select#'+counterHP+'_hp_kota_' + counter),
                $selectKecamatan = $('select#'+counterHP+'_hp_kecamatan_' + counter),
                $selectKelurahan = $('select#'+counterHP+'_hp_kelurahan_' + counter),
                $btnSaveProvinsi = $('a#'+counterHP+'_btn_save_hp_provinsi_' + counter),
                $btnCancelProvinsi = $('a#'+counterHP+'_btn_cancel_hp_provinsi_' + counter),
                $btnEditProvinsi = $('a#'+counterHP+'_btn_edit_hp_provinsi_' + counter);
                
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
                        $selectKecamatan.empty();
                        $selectKelurahan.empty();

                        //munculin index pertama Pilih..
                        $selectKota.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKota.val('');

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
        }else{
            var $inputProvinsi = $('input#input_pj_provinsi_' + counter),
                $selectProvinsi = $('select#pj_provinsi_' + counter),
                $selectNegara = $('select#pj_negara_' + counter),
                $selectKota = $('select#pj_kota_' + counter),
                $selectKecamatan = $('select#pj_kecamatan_' + counter),
                $selectKelurahan = $('select#pj_kelurahan_' + counter),
                $btnSaveProvinsi = $('a#btn_save_pj_provinsi_' + counter),
                $btnCancelProvinsi = $('a#btn_cancel_pj_provinsi_' + counter),
                $btnEditProvinsi = $('a#btn_edit_pj_provinsi_' + counter);
                
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
                        $selectKecamatan.empty();
                        $selectKelurahan.empty();

                        //munculin index pertama Pilih..
                        $selectKota.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                        $selectKota.val('');

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

    var handleEditKota = function(counterHP, counter, tab){
        if (tab == "tab_alamat") {
            var $inputKota = $('input#input_kota_' + counter),
                $selectKota = $('select#kota_' + counter),
                $btnSaveKota = $('a#btn_save_kota_' + counter),
                $btnCancelKota = $('a#btn_cancel_kota_' + counter),
                $btnEditKota = $('a#btn_edit_kota_' + counter);

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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputKota = $('input#'+counterHP+'_input_hp_kota_' + counter),
                $selectKota = $('select#'+counterHP+'_hp_kota_' + counter),
                $btnSaveKota = $('a#'+counterHP+'_btn_save_hp_kota_' + counter),
                $btnCancelKota = $('a#'+counterHP+'_btn_cancel_hp_kota_' + counter),
                $btnEditKota = $('a#'+counterHP+'_btn_edit_hp_kota_' + counter);

            $btnEditKota.addClass("hidden");
            $selectKota.addClass("hidden");

            $btnSaveKota.removeClass("hidden");
            $btnCancelKota.removeClass("hidden");
            $inputKota.removeClass("hidden");

            $inputKota.focus();
            
            $btnCancelKota.on('click', function(){
                handleCancelKota(counterHP, counter, "tab_hubungan_pasien");
            });

            $btnSaveKota.on('click', function(e){
                handleSaveKota(counterHP, counter, "tab_hubungan_pasien");
                e.stopImmediatePropagation();
            });

        }else{
            var $inputKota = $('input#input_pj_kota_' + counter),
                $selectKota = $('select#pj_kota_' + counter),
                $btnSaveKota = $('a#btn_save_pj_kota_' + counter),
                $btnCancelKota = $('a#btn_cancel_pj_kota_' + counter),
                $btnEditKota = $('a#btn_edit_pj_kota_' + counter);

            $btnEditKota.addClass("hidden");
            $selectKota.addClass("hidden");

            $btnSaveKota.removeClass("hidden");
            $btnCancelKota.removeClass("hidden");
            $inputKota.removeClass("hidden");

            $inputKota.focus();
            
            $btnCancelKota.on('click', function(){
                handleCancelKota(counterHP, counter, "tab_penanggung_jawab");
            });

            $btnSaveKota.on('click', function(e){
                handleSaveKota(counterHP, counter, "tab_penanggung_jawab");
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

                $btnEditKota.removeClass("hidden");
                $selectKota.removeClass("hidden");

                $btnSaveKota.addClass("hidden");
                $btnCancelKota.addClass("hidden");
                $inputKota.addClass("hidden");

                $inputKota.val("");
            }else if(tab == "tab_hubungan_pasien"){
                var $inputKota = $('input#'+counterHP+'_input_hp_kota_' + counter),
                    $selectKota = $('select#'+counterHP+'_hp_kota_' + counter),
                    $btnSaveKota = $('a#'+counterHP+'_btn_save_hp_kota_' + counter),
                    $btnCancelKota = $('a#'+counterHP+'_btn_cancel_hp_kota_' + counter),
                    $btnEditKota = $('a#'+counterHP+'_btn_edit_hp_kota_' + counter);

                $btnEditKota.removeClass("hidden");
                $selectKota.removeClass("hidden");

                $btnSaveKota.addClass("hidden");
                $btnCancelKota.addClass("hidden");
                $inputKota.addClass("hidden");

                $inputKota.val("");
            }else{
                var $inputKota = $('input#input_pj_kota_' + counter),
                    $selectKota = $('select#pj_kota_' + counter),
                    $btnSaveKota = $('a#btn_save_pj_kota_' + counter),
                    $btnCancelKota = $('a#btn_cancel_pj_kota_' + counter),
                    $btnEditKota = $('a#btn_edit_pj_kota_' + counter);

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
                $selectProvinsi  = $('select#provinsi_' + counter)
            ;
                
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
        }else if(tab == "tab_hubungan_pasien"){
            // alert('a');
            var 
                $inputKota      = $('input#'+counterHP+'_input_hp_kota_' + counter),
                $selectKota     = $('select#'+counterHP+'_hp_kota_' + counter),
                $selectKecamatan = $('select#'+counterHP+'_hp_kecamatan_' + counter),
                $selectKelurahan = $('select#'+counterHP+'_hp_kelurahan_' + counter),
                $btnSaveKota    = $('a#'+counterHP+'_btn_save_hp_kota_' + counter),
                $btnCancelKota  = $('a#'+counterHP+'_btn_cancel_hp_kota_' + counter),
                $btnEditKota    = $('a#'+counterHP+'_btn_edit_hp_kota_' + counter),
                $selectProvinsi = $('select#'+counterHP+'_hp_provinsi_' + counter)
            ;
                
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
        }else{
            var 
                $inputKota      = $('input#input_pj_kota_' + counter),
                $selectKota     = $('select#pj_kota_' + counter),
                $selectKecamatan = $('select#pj_kecamatan_' + counter),
                $selectKelurahan = $('select#pj_kelurahan_' + counter),
                $btnSaveKota    = $('a#btn_save_pj_kota_' + counter),
                $btnCancelKota  = $('a#btn_cancel_pj_kota_' + counter),
                $btnEditKota    = $('a#btn_edit_pj_kota_' + counter),
                $selectProvinsi = $('select#pj_provinsi_' + counter)
            ;
                
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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputKecamatan = $('input#'+counterHP+'_input_hp_kecamatan_' + counter),
                $selectKecamatan = $('select#'+counterHP+'_hp_kecamatan_' + counter),
                $btnSaveKecamatan = $('a#'+counterHP+'_btn_save_hp_kecamatan_' + counter),
                $btnCancelKecamatan = $('a#'+counterHP+'_btn_cancel_hp_kecamatan_' + counter),
                $btnEditKecamatan = $('a#'+counterHP+'_btn_edit_hp_kecamatan_' + counter);

            $btnEditKecamatan.addClass("hidden");
            $selectKecamatan.addClass("hidden");

            $btnSaveKecamatan.removeClass("hidden");
            $btnCancelKecamatan.removeClass("hidden");
            $inputKecamatan.removeClass("hidden");

            $inputKecamatan.focus();
            
            $btnCancelKecamatan.on('click', function(){
                handleCancelKecamatan(counterHP, counter, "tab_hubungan_pasien");
            });

            $btnSaveKecamatan.on('click', function(e){
                handleSaveKecamatan(counterHP, counter, "tab_hubungan_pasien");
                e.stopImmediatePropagation();
            });
        }else{
            var $inputKecamatan = $('input#input_pj_kecamatan_' + counter),
                $selectKecamatan = $('select#pj_kecamatan_' + counter),
                $btnSaveKecamatan = $('a#btn_save_pj_kecamatan_' + counter),
                $btnCancelKecamatan = $('a#btn_cancel_pj_kecamatan_' + counter),
                $btnEditKecamatan = $('a#btn_edit_pj_kecamatan_' + counter);

            $btnEditKecamatan.addClass("hidden");
            $selectKecamatan.addClass("hidden");

            $btnSaveKecamatan.removeClass("hidden");
            $btnCancelKecamatan.removeClass("hidden");
            $inputKecamatan.removeClass("hidden");

            $inputKecamatan.focus();
            
            $btnCancelKecamatan.on('click', function(){
                handleCancelKecamatan(counterHP, counter, "tab_penanggung_jawab");
            });

            $btnSaveKecamatan.on('click', function(e){
                handleSaveKecamatan(counterHP, counter, "tab_penanggung_jawab");
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

            $btnEditKecamatan.removeClass("hidden");
            $selectKecamatan.removeClass("hidden");

            $btnSaveKecamatan.addClass("hidden");
            $btnCancelKecamatan.addClass("hidden");
            $inputKecamatan.addClass("hidden");

            $inputKecamatan.val("");
        }else if(tab == "tab_hubungan_pasien"){
            var $inputKecamatan = $('input#'+counterHP+'_input_hp_kecamatan_' + counter),
                $selectKecamatan = $('select#'+counterHP+'_hp_kecamatan_' + counter),
                $btnSaveKecamatan = $('a#'+counterHP+'_btn_save_hp_kecamatan_' + counter),
                $btnCancelKecamatan = $('a#'+counterHP+'_btn_cancel_hp_kecamatan_' + counter),
                $btnEditKecamatan = $('a#'+counterHP+'_btn_edit_hp_kecamatan_' + counter);

            $btnEditKecamatan.removeClass("hidden");
            $selectKecamatan.removeClass("hidden");

            $btnSaveKecamatan.addClass("hidden");
            $btnCancelKecamatan.addClass("hidden");
            $inputKecamatan.addClass("hidden");

            $inputKecamatan.val("");
        }else{
            var $inputKecamatan = $('input#input_pj_kecamatan_' + counter),
                $selectKecamatan = $('select#pj_kecamatan_' + counter),
                $btnSaveKecamatan = $('a#btn_save_pj_kecamatan_' + counter),
                $btnCancelKecamatan = $('a#btn_cancel_pj_kecamatan_' + counter),
                $btnEditKecamatan = $('a#btn_edit_pj_kecamatan_' + counter);

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
                $selectKota = $('select#kota_' + counter)
                ;
                
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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputKecamatan = $('input#'+counterHP+'_input_hp_kecamatan_' + counter),
                $selectKecamatan = $('select#'+counterHP+'_hp_kecamatan_' + counter),
                $selectKelurahan = $('select#'+counterHP+'_hp_kelurahan_' + counter),
                $btnSaveKecamatan = $('a#'+counterHP+'_btn_save_hp_kecamatan_' + counter),
                $btnCancelKecamatan = $('a#'+counterHP+'_btn_cancel_hp_kecamatan_' + counter),
                $btnEditKecamatan = $('a#'+counterHP+'_btn_edit_hp_kecamatan_' + counter),
                $selectKota = $('select#'+counterHP+'_hp_kota_' + counter)
                ;
                
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
        }else{
            var $inputKecamatan = $('input#input_pj_kecamatan_' + counter),
                $selectKecamatan = $('select#pj_kecamatan_' + counter),
                $selectKelurahan = $('select#pj_kelurahan_' + counter),
                $btnSaveKecamatan = $('a#btn_save_pj_kecamatan_' + counter),
                $btnCancelKecamatan = $('a#btn_cancel_pj_kecamatan_' + counter),
                $btnEditKecamatan = $('a#btn_edit_pj_kecamatan_' + counter),
                $selectKota = $('select#pj_kota_' + counter)
                ;
                
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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputKelurahan = $('input#'+counterHP+'_input_hp_kelurahan_' + counter),
                $selectKelurahan = $('select#'+counterHP+'_hp_kelurahan_' + counter),
                $btnSaveKelurahan = $('a#'+counterHP+'_btn_save_hp_kelurahan_' + counter),
                $btnCancelKelurahan = $('a#'+counterHP+'_btn_cancel_hp_kelurahan_' + counter),
                $btnEditKelurahan = $('a#'+counterHP+'_btn_edit_hp_kelurahan_' + counter);

            $btnEditKelurahan.addClass("hidden");
            $selectKelurahan.addClass("hidden");

            $btnSaveKelurahan.removeClass("hidden");
            $btnCancelKelurahan.removeClass("hidden");
            $inputKelurahan.removeClass("hidden");

            $inputKelurahan.focus();
            
            $btnCancelKelurahan.on('click', function(){
                handleCancelKelurahan(counterHP, counter, "tab_hubungan_pasien");
            });

            $btnSaveKelurahan.on('click', function(e){
                handleSaveKelurahan(counterHP, counter, "tab_hubungan_pasien");
                e.stopImmediatePropagation();
            });
        }
        else{
           var $inputKelurahan = $('input#input_pj_kelurahan_' + counter),
                $selectKelurahan = $('select#pj_kelurahan_' + counter),
                $btnSaveKelurahan = $('a#btn_save_pj_kelurahan_' + counter),
                $btnCancelKelurahan = $('a#btn_cancel_pj_kelurahan_' + counter),
                $btnEditKelurahan = $('a#btn_edit_pj_kelurahan_' + counter);

            $btnEditKelurahan.addClass("hidden");
            $selectKelurahan.addClass("hidden");

            $btnSaveKelurahan.removeClass("hidden");
            $btnCancelKelurahan.removeClass("hidden");
            $inputKelurahan.removeClass("hidden");

            $inputKelurahan.focus();
            
            $btnCancelKelurahan.on('click', function(){
                handleCancelKelurahan(counterHP, counter, "tab_penanggung_jawab");
            });

            $btnSaveKelurahan.on('click', function(e){
                handleSaveKelurahan(counterHP, counter, "tab_penanggung_jawab");
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

            $btnEditKelurahan.removeClass("hidden");
            $selectKelurahan.removeClass("hidden");

            $btnSaveKelurahan.addClass("hidden");
            $btnCancelKelurahan.addClass("hidden");
            $inputKelurahan.addClass("hidden");

            $inputKelurahan.val("");
        }else if(tab == "tab_hubungan_pasien"){
            var $inputKelurahan = $('input#'+counterHP+'_input_hp_kelurahan_' + counter),
                $selectKelurahan = $('select#'+counterHP+'_hp_kelurahan_' + counter),
                $btnSaveKelurahan = $('a#'+counterHP+'_btn_save_hp_kelurahan_' + counter),
                $btnCancelKelurahan = $('a#'+counterHP+'_btn_cancel_hp_kelurahan_' + counter),
                $btnEditKelurahan = $('a#'+counterHP+'_btn_edit_hp_kelurahan_' + counter);

            $btnEditKelurahan.removeClass("hidden");
            $selectKelurahan.removeClass("hidden");

            $btnSaveKelurahan.addClass("hidden");
            $btnCancelKelurahan.addClass("hidden");
            $inputKelurahan.addClass("hidden");

            $inputKelurahan.val("");
        }else{
            var $inputKelurahan = $('input#input_pj_kelurahan_' + counter),
                $selectKelurahan = $('select#pj_kelurahan_' + counter),
                $btnSaveKelurahan = $('a#btn_save_pj_kelurahan_' + counter),
                $btnCancelKelurahan = $('a#btn_cancel_pj_kelurahan_' + counter),
                $btnEditKelurahan = $('a#btn_edit_pj_kelurahan_' + counter);

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
                $selectKecamatan = $('select#kecamatan_' + counter)
                ;
                
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
        }else if(tab == "tab_hubungan_pasien"){
            var $inputKelurahan = $('input#'+counterHP+'_input_hp_kelurahan_' + counter),
                $selectKelurahan = $('select#'+counterHP+'_hp_kelurahan_' + counter),
                $btnSaveKelurahan = $('a#'+counterHP+'_btn_save_hp_kelurahan_' + counter),
                $btnCancelKelurahan = $('a#'+counterHP+'_btn_cancel_hp_kelurahan_' + counter),
                $btnEditKelurahan = $('a#'+counterHP+'_btn_edit_hp_kelurahan_' + counter),
                $selectKecamatan = $('select#'+counterHP+'_hp_kecamatan_' + counter)
                ;
                
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
        }else{
            var $inputKelurahan = $('input#input_pj_kelurahan_' + counter),
                $selectKelurahan = $('select#pj_kelurahan_' + counter),
                $btnSaveKelurahan = $('a#btn_save_pj_kelurahan_' + counter),
                $btnCancelKelurahan = $('a#btn_cancel_pj_kelurahan_' + counter),
                $btnEditKelurahan = $('a#btn_edit_pj_kelurahan_' + counter),
                $selectKecamatan = $('select#pj_kecamatan_' + counter)
                ;
                
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
                format : 'dd-M-yyyy',
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
            var i=0;
            bootbox.confirm(msg, function(result) {
                
                if (result==true) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses..'});
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                      $('#save', $form).click();
                    }
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

                var $provinsi_select = $('select#provinsi_' + counter);
                
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
                        $provinsi_select.empty();

                        $provinsi_select.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $provinsi_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $provinsi_select.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }else if(tab == "tab_hubungan_pasien"){
            $('select.hp_negara').on('change', function(){

            //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $provinsi_select = $('select#'+counterHP+'_hp_provinsi_' + counter);
                
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
                        $provinsi_select.empty();

                        $provinsi_select.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $provinsi_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $provinsi_select.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }else{
           $('select.pj_negara').on('change', function(){

            //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $provinsi_select = $('select#pj_provinsi_' + counter);
                
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
                        $provinsi_select.empty();

                        $provinsi_select.append($("<option></option>")
                            .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $provinsi_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $provinsi_select.val('');

                        });
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

                var $kota_select = $('select#kota_' + counter);
                
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
                        $kota_select.empty();

                        $kota_select.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $kota_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $kota_select.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }else if(tab == "tab_hubungan_pasien"){
            $('select.hp_provinsi').on('change', function(){

            //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $kota_select = $('select#'+counterHP+'_hp_kota_' + counter);
                
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
                        $kota_select.empty();

                        $kota_select.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $kota_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $kota_select.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }else{
            $('select.pj_provinsi').on('change', function(){

            //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $kota_select = $('select#pj_kota_' + counter);
                
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
                        $kota_select.empty();

                        $kota_select.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $kota_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $kota_select.val('');

                        });
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

                var $kecamatan_select = $('select#kecamatan_' + counter);
                
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
                        $kecamatan_select.empty();

                        $kecamatan_select.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $kecamatan_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $kecamatan_select.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }else if(tab == "tab_hubungan_pasien"){
            $('select.hp_kota').on('change', function(){

                //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $kecamatan_select = $('select#'+counterHP+'_hp_kecamatan_' + counter);
                
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
                        $kecamatan_select.empty();

                        $kecamatan_select.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $kecamatan_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $kecamatan_select.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }else{
            $('select.pj_kota').on('change', function(){

                //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $kecamatan_select = $('select#pj_kecamatan_' + counter);
                
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
                        $kecamatan_select.empty();

                        $kecamatan_select.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $kecamatan_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $kecamatan_select.val('');

                        });
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

                var $kelurahan_select = $('select#kelurahan_' + counter);
                
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
                        $kelurahan_select.empty();

                        $kelurahan_select.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $kelurahan_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $kelurahan_select.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            }) 
        }else if(tab == "tab_hubungan_pasien"){
            $('select.hp_kecamatan').on('change', function(){

            //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $kelurahan_select = $('select#'+counterHP+'_hp_kelurahan_' + counter);
                
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
                        $kelurahan_select.empty();

                        $kelurahan_select.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $kelurahan_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $kelurahan_select.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            }) 
        }else{
            $('select.pj_kecamatan').on('change', function(){

            //var numRow = itemCounter++;
                var numRow = $counter.val();
                //alert($counter.val());
                //$('input#warehouse_id').val($(this).val());

                var $kelurahan_select = $('select#pj_kelurahan_' + counter);
                
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
                        $kelurahan_select.empty();

                        $kelurahan_select.append($("<option></option>")
                                .attr("value", '').text('Pilih..'));

                        $.each(results, function(key, value) {
                            $kelurahan_select.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $kelurahan_select.val('');

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            })
        }
        
    }

    var handleBtnSearchPasien = function($btn){
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

    var handlePilihPasien = function(){
        $tablePilihPasien.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_pasien',
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
                { 'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#table_pilih_pasien_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_pasien_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $tablePilihPasien);
        handlePilihPasienSelect( $btnSelects );

        $tablePilihPasien.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihPasienSelect( $btnSelect );
            
        } );

        $popoverPasienContent.hide();        
    };

    var handlePilihPasienSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefPasien   = $('input[name="nama_ref_pasien"]'),
                $IdRefPasien   = $('input[name="id_ref_pasien"]'),
                $itemCodeEl = null,
                $itemNameEl = null
                ;        


            $('.pilih-pasien', $form).popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdRefPasien.val($(this).data('item').id);
            $namaRefPasien.val($(this).data('item').nama);

            // alert($itemIdEl.val($(this).data('item').id));


            e.preventDefault();
        });     
    };

    var handleUploadify = function()
    {
        var ul = $('#upload ul.img-ktp');

       
        // Initialize the jQuery File Upload plugin
        $('#upl_ktp').fileupload({

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
                    tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                }
                else
                {
                    bootbox.alert('File tidak boleh berupa pdf.');
                }
                

                $('input#url_ktp').attr('value',filename);
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

    var handleUploadifyBpjs = function()
    {
        var ul = $('#upload ul.img-bpjs');

       
        // Initialize the jQuery File Upload plugin
        $('#upl_bpjs').fileupload({

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
                    tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                }
                else
                {
                    bootbox.alert('File tidak boleh berupa pdf.');
                }
                

                $('input#url_bpjs').attr('value',filename);
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

    var handleUploadifyHP = function(counter)
    {
        var ul = $('#upload_'+counter+' ul');

       
        // Initialize the jQuery File Upload plugin
        $('#hubungan_pasien_scan_ktp_'+counter).fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop_'+counter),
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

                var filename = data.result.filename;
                var filename = filename.replace(/ /g,"_");
                var filetype = data.files[0].type;
                
                if(filetype == 'image/jpeg' || filetype == 'image/png' || filetype == 'image/gif')
                {
                    tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                }
                else
                {
                    tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                }
                $('input#hubungan_pasien_url_ktp_'+counter).attr('value',filename);
                // Add the HTML to the UL element
                ul.html(tpl);
                // data.context = tpl.appendTo(ul);

                handleFancybox();
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

    var handleUploadifyPJ = function(counter)
    {
        var ul = $('#upload_pj_'+counter+' ul');

       
        // Initialize the jQuery File Upload plugin
        $('#penanggung_jawab_scan_ktp_'+counter).fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop_pj_'+counter),
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

                var filename = data.result.filename;
                var filename = filename.replace(/ /g,"_");
                var filetype = data.files[0].type;
                
                if(filetype == 'image/jpeg' || filetype == 'image/png' || filetype == 'image/gif')
                {
                    tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                }
                else
                {
                    tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                }
                $('input#penanggung_jawab_url_ktp_'+counter).attr('value',filename);
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

    var handleUploadifyDokumen = function()
    {
        $('.uploadbutton').each(function(index)
        {
            var ul = $('#upload_dokumen_'+index+' ul.ul-img');

         
            // Initialize the jQuery File Upload plugin
            $('#upload_'+index).fileupload({

                // This element will accept file drag/drop uploading
               
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
                        tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                    }
                    else
                    {
                        tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                    }
                    $('input[name$="[value]"]', $('#upload_dokumen_'+index)).attr('value',filename);
                    // Add the HTML to the UL element
                    ul.html(tpl);
                    handleFancybox();
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
        });
        
    }

    var handleFancybox = function() {
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


    
    var handleSelectFaskes = function(){
        $('select#faskes').on('change', function(){

            if ($(this).val() == 'lain-lain') {
                $('input#nama_marketing').val('');
                $('input#nama_marketing').attr('readonly', false);
                $('input#nama_marketing').attr('placeholder', 'Nama Marketing');

                $('label.faskes').removeClass('hidden');
                $('input.faskes').removeClass('hidden');
                $('input.faskes').val('hidden');
            }else{
                $('input#nama_marketing').attr('readonly', true);
                $('input#nama_marketing').attr('placeholder', 'Otomatis');

                $('label.faskes').addClass('hidden');
                $('input.faskes').addClass('hidden');

                $.ajax({
                    type        : 'POST',
                    url         : baseAppUrl + 'get_data_faskes',
                    data        : {id: $(this).val()},
                    dataType    : 'json',
                    beforeSend  : function(){
                                    Metronic.blockUI({boxed: true });
                                  },
                    success     : function( results ) {
                                    $.each(results, function(key, value) {

                                        $('input#nama_marketing').val(value.nama_dokter);

                                    });
                                  },
                    complete    : function(){
                                    Metronic.unblockUI();
                                  }
                });
            }
            
        });
    }
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/pasien/';
        handleValidation();
        handleConfirmSave();
        handleMultiSelect();
        handleDatePickers();
        handlePilihPasien();
        handleTambahRowPelengkap();
        handleTambahRowRekamMedis();
        handleSelectCabang();
        handleUploadify();
        handleUploadifyBpjs();
        handleUploadifyDokumen();
        handleSelectFaskes();
        handleDeletePenanggungJawab();
        //$('select#cabang_id').select2;
        //handleSelectProvinsi();
        initForm();

        //alert('1');
    };
 }(mb.app.pasien.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.pasien.add.init();
});