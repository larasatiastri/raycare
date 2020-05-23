mb.app.pabrik = mb.app.pabrik || {};
mb.app.pabrik.add = mb.app.pabrik.add || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_view_pabrik'),
        $tablePelengkap       = $('#table_pelengkap', $form),
        $tableRekamMedis      = $('#table_rekam_medis', $form),
        $counter              = $('#counter'),
        $popoverPasienContent = $('#popover_pasien_content'), 
        $lastPopoverItem      = null,
        $tablePilihPasien     = $('#table_pilih_pasien'),
        tplFormPhone          = '<li class="fieldset">' + $('#tpl-form-phone', $form).val() + '<hr></li>',
        tplFormCP             = '<li class="fieldset">' + $('#tpl-form-cp', $form).val() + '<hr></li>',
        tplFormAlamat         = '<li class="fieldset">' + $('#tpl-form-alamat', $form).val() + '<hr></li>',
        regExpTplPhone        = new RegExp('phone[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplCP           = new RegExp('cp[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplAlamat       = new RegExp('alamat[0]', 'g'),   // 'g' perform global, case-insensitive
        cpCounter             = $('input#cp_counter').val(),
        phoneCounter          = $('input#phone_counter').val(),
        alamatCounter         = $('input#alamat_counter').val(),
        tplPelengkapRow       = $.validator.format( $('#tpl_pelengkap_row').text() ),
        tplRekamMedisRow      = $.validator.format( $('#tpl_rekam_medis_row').text() ),
        pelengkapCounter      = 1,
        rekamMedisCounter     = 1,
        formsPhone = {
                    'phone' : 
                    {            
                        section  : $('#section-telepon', $form),
                        template : $.validator.format( tplFormPhone.replace(regExpTplPhone, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ phoneCounter++; return phoneCounter-1; }
                    }   
                },
        formsCP = {
                    'cp' : 
                    {            
                        section  : $('#section-cp', $form),
                        template : $.validator.format( tplFormCP.replace(regExpTplCP, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ cpCounter++; return cpCounter-1; }
                    }   
                },
        formsAlamat = {
                    'alamat' : 
                    {            
                        section  : $('#section-alamat', $form),
                        template : $.validator.format( tplFormAlamat.replace(regExpTplAlamat, '_id_{0}') ), //ubah ke format template jquery validator
                        counter  : function(){ alamatCounter++; return alamatCounter-1; }
                    }   
                };

    var initForm = function(){

        $counter.val(alamatCounter);
        // tambah 1 row kosong pertama

        $.each(formsPhone, function(idx, form){
            // handle button add
            $('a.add-phone', form.section).on('click', function(){
                addFieldsetPhone(form);
            });
             
            // beri satu fieldset kosong
            addFieldsetPhone(form);
        });

        $.each(formsCP, function(idx, form){
            // handle button add
            $('a.add-cp', form.section).on('click', function(){
                addFieldsetCP(form);
            });
             
            // beri satu fieldset kosong
            addFieldsetCP(form);
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

    var addFieldsetCP = function(form)
    {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            $btnSubjekcp = $('a#btn_edit_subjek_cp_' + counter)
            ;

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-this', $newFieldset).on('click', function(){
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });


        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

         $btnSubjekcp.on('click', function(){
            handleEditSubjekTelp(counter);
        });

         handleSelectSubjekTelp(counter);
    };

    var addFieldsetPhone = function(form)
    {

        // if(! isValidLastPhoneRow() ) return;
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            $btnSubjekTelp = $('a#btn_edit_subjek_telp_' + counter)
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

         $btnSubjekTelp.on('click', function(){
            handleEditSubjekTelp(counter);
        });

         handleSelectSubjekTelp(counter);
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

        $('input.address_radio', $newFieldset).on('change', function(){
            // alert($(this).prop('checked'));
            handleIsPrimary($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');


        $btnSubjekAlamat.on('click', function(){
            handleEditSubjekAlamat(counter);
        });

        $btnEditNegara.on('click', function(){
            handleEditNegara(counter);
        });

        $btnEditProvinsi.on('click', function(){
            handleEditProvinsi(counter);
        });

        $btnEditKota.on('click', function(){
            handleEditKota(counter);
        });

        $btnEditKecamatan.on('click', function(){
            handleEditKecamatan(counter);
        });

        $btnEditKelurahan.on('click', function(){
            handleEditKelurahan(counter);
        });

        handleSelectNegara(counter);
        handleSelectProvinsi();
        handleSelectKota();
        handleSelectKecamatan();
        handleSelectKelurahan();
    };

    var handleEditSubjekcp = function(counter){
        var $inputSubjekcp = $('input#input_subjek_cp_' + counter),
            $selectSubjekcp = $('select#subjek_cp_' + counter),
            $btnSaveSubjekcp = $('a#btn_save_subjek_cp_' + counter),
            $btnCancelSubjekcp = $('a#btn_cancel_subjek_cp_' + counter),
            $btnEditSubjekcp = $('a#btn_edit_subjek_cp_' + counter),
            $btnDeleteSubjekcp = $('a#btn_delete_subjek_cp_' + counter);

        $btnEditSubjekcp.addClass("hidden");
        $btnDeleteSubjekcp.addClass("hidden");
        $selectSubjekcp.addClass("hidden");

        $btnSaveSubjekcp.removeClass("hidden");
        $btnCancelSubjekcp.removeClass("hidden");
        $inputSubjekcp.removeClass("hidden");

        $inputSubjekcp.focus();

        $btnCancelSubjekcp.on('click', function(){
            handleCancelSubjekcp(counter);
        });

        $btnSaveSubjekcp.on('click', function(){
            handleSaveSubjekcp(counter);
        });
    }

    var handleCancelSubjekcp= function(counter){
        var $inputSubjekcp = $('input#input_subjek_cp_' + counter),
            $selectSubjekcp = $('select#subjek_cp_' + counter),
            $btnSaveSubjekcp = $('a#btn_save_subjek_cp_' + counter),
            $btnCancelSubjekcp = $('a#btn_cancel_subjek_cp_' + counter),
            $btnEditSubjekcp = $('a#btn_edit_subjek_cp_' + counter),
            $btnDeleteSubjekcp = $('a#btn_delete_subjek_cp_' + counter);

            $btnEditSubjekcp.removeClass("hidden");
            $btnDeleteSubjekcp.removeClass("hidden");
            $selectSubjekcp.removeClass("hidden");

            $btnSaveSubjekcp.addClass("hidden");
            $btnCancelSubjekcp.addClass("hidden");
            $inputSubjekcp.addClass("hidden");

            $inputSubjekcp.val("");
    }

    var handleSaveSubjekcp= function(counter){
        
        //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
        var $inputSubjekcp = $('input#input_subjek_cp_' + counter),
            $selectSubjekcp = $('select#subjek_cp_' + counter),
            $btnSaveSubjekcp = $('a#btn_save_subjek_cp_' + counter),
            $btnCancelSubjekcp = $('a#btn_cancel_subjek_cp_' + counter),
            $btnEditSubjekcp = $('a#btn_edit_subjek_cp_' + counter),
            $btnDeleteSubjekcp = $('a#btn_delete_subjek_cp_' + counter);
            

        if ($inputSubjekcp.eq($inputSubjekcp.length-1).val()) {
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_subjek',
                data     : {tipe: '2',
                            nama: $inputSubjekcp.val()
                           },
                dataType : 'json',
                success  : function( results ) {
                    $btnEditSubjekcp.removeClass("hidden");
                    $btnDeleteSubjekcp.removeClass("hidden");
                    $selectSubjekcp.removeClass("hidden");

                    $btnSaveSubjekcp.addClass("hidden");
                    $btnCancelSubjekcp.addClass("hidden");
                    $inputSubjekcp.addClass("hidden");

                    $inputSubjekcp.val("");

                    $selectSubjekcp.empty();

                    //munculin index pertama Pilih..
                    $selectSubjekcp.append($("<option></option>")
                            .attr("value", "").text("Pilih.."));
                        $selectSubjekcp.val('');

                    //munculin semua data dari hasil post
                    $.each(results, function(key, value) {
                        $selectSubjekcp.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $selectSubjekcp.val('');

                    });
                }
            });   
        }    
    }

    var handleEditSubjekTelp = function(counter){
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
            handleCancelSubjekTelp(counter);
        });

        $btnSaveSubjekTelp.on('click', function(){
            handleSaveSubjekTelp(counter);
        });
    }

    var handleCancelSubjekTelp= function(counter){
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
    }

    var handleSaveSubjekTelp= function(counter){
        
        //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
        var $inputSubjekTelp = $('input#input_subjek_telp_' + counter),
            $selectSubjekTelp = $('select#subjek_telp_' + counter),
            $btnSaveSubjekTelp = $('a#btn_save_subjek_telp_' + counter),
            $btnCancelSubjekTelp = $('a#btn_cancel_subjek_telp_' + counter),
            $btnEditSubjekTelp = $('a#btn_edit_subjek_telp_' + counter),
            $btnDeleteSubjekTelp = $('a#btn_delete_subjek_telp_' + counter);
            

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

    var handleEditSubjekAlamat = function(counter){
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
                handleCancelSubjekAlamat(counter);
            });

            $btnSaveSubjekAlamat.on('click', function(){
                handleSaveSubjekAlamat(counter);
            });
    }  

    var handleCancelSubjekAlamat= function(counter){
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
    }

    var handleSaveSubjekAlamat= function(counter){
        
        //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
        var $inputSubjekAlamat = $('input#input_subjek_alamat_' + counter),
            $selectSubjekAlamat = $('select#subjek_alamat_' + counter),
            $btnSaveSubjekAlamat = $('a#btn_save_subjek_alamat_' + counter),
            $btnCancelSubjekAlamat = $('a#btn_cancel_subjek_alamat_' + counter),
            $btnEditSubjekAlamat = $('a#btn_edit_subjek_alamat_' + counter),
            $btnDeleteSubjekAlamat = $('a#btn_delete_subjek_alamat_' + counter);
            

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

    var handleEditNegara = function(counter){
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
                handleCancelNegara(counter);
            });

            $btnSaveNegara.on('click', function(){
                handleSaveNegara(counter);
            });
    }   

    var handleCancelNegara= function(counter){
            var $inputNegara = $('input#input_negara_' + counter),
                $selectNegara = $('select#negara_' + counter),
                $btnSaveNegara = $('a#btn_save_negara_' + counter),
                $btnCancelNegara = $('a#btn_cancel_negara_' + counter),
                $btnEditNegara = $('a#btn_edit_negara_' + counter);

            $btnEditNegara.removeClass("hidden");
            $selectNegara.removeClass("hidden");

            $btnSaveNegara.addClass("hidden");
            $btnCancelNegara.addClass("hidden");
            $inputNegara.addClass("hidden");

            $inputProvinsi.val("");
    }

    var handleSaveNegara= function(counter){
        
        //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
        var $inputNegara = $('input#input_negara_' + counter),
            $selectNegara = $('select#negara_' + counter),
            $btnSaveNegara = $('a#btn_save_negara_' + counter),
            $btnCancelNegara = $('a#btn_cancel_negara_' + counter),
            $btnEditNegara = $('a#btn_edit_negara_' + counter);
            

        if ($inputNegara.eq($inputNegara.length-1).val()) {
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_negara',
                data     : {tipe: '1',
                            nama: $inputNegara.val()
                           },
                dataType : 'json',
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
                    $.each(results, function(key, value) {
                        $selectNegara.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $selectNegara.val('');

                    });
                }
            });   
        }    
    }

    var handleEditProvinsi = function(counter){
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
                handleCancelProvinsi(counter);
            });

            $btnSaveProvinsi.on('click', function(){
                handleSaveProvinsi(counter);
            });
    }

    var handleCancelProvinsi= function(counter){
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
    }  

    var handleSaveProvinsi = function(counter){
            
        var $inputProvinsi = $('input#input_provinsi_' + counter),
            $selectProvinsi = $('select#provinsi_' + counter),
            $selectNegara = $('select#negara_' + counter),
            $btnSaveProvinsi = $('a#btn_save_provinsi_' + counter),
            $btnCancelProvinsi = $('a#btn_cancel_provinsi_' + counter),
            $btnEditProvinsi = $('a#btn_edit_provinsi_' + counter);
            
            //di eksekusi apabila inputan dan select tidak kosong
        if ($inputProvinsi.eq($inputProvinsi.length-1).val() && $selectNegara.val() != "") { 
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_region',
                data     : {parent: $selectNegara.val(), 
                            tipe: '2',
                            nama: $inputProvinsi.val()
                           },
                dataType : 'json',
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

                    $.each(results, function(key, value) {
                        $selectProvinsi.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $selectProvinsi.val('');

                    });
                }
            });   
        }    
    }

    var handleEditKota = function(counter){
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
                handleCancelKota(counter);
            });

            $btnSaveKota.on('click', function(){
                handleSaveKota(counter);
            });
    }

    var handleCancelKota= function(counter){
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
    }

    var handleSaveKota = function(counter){
            
        var $inputKota = $('input#input_kota_' + counter),
            $selectKota = $('select#kota_' + counter),
            $btnSaveKota = $('a#btn_save_kota_' + counter),
            $btnCancelKota = $('a#btn_cancel_kota_' + counter),
            $btnEditKota = $('a#btn_edit_kota_' + counter),
            $selectProvinsi = $('select#provinsi_' + counter)
            ;
            
            //di eksekusi apabila inputan dan select tidak kosong
        if ($inputKota.eq($inputKota.length-1).val() && !$selectProvinsi.val() != "") { 
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_region',
                data     : {parent: $selectProvinsi.val(), 
                            tipe: '3',
                            nama: $inputKota.val()
                           },
                dataType : 'json',
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
                        $selectKota.val('');

                    });
                }
            });   
        }    
    }

    var handleEditKecamatan = function(counter){
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
                handleCancelKecamatan(counter);
            });

            $btnSaveKecamatan.on('click', function(){
                handleSaveKecamatan(counter);
            });
    }

    var handleCancelKecamatan = function(counter){
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
    }

    var handleSaveKecamatan = function(counter){
            
        var $inputKecamatan = $('input#input_kecamatan_' + counter),
            $selectKecamatan = $('select#kecamatan_' + counter),
            $btnSaveKecamatan = $('a#btn_save_kecamatan_' + counter),
            $btnCancelKecamatan = $('a#btn_cancel_kecamatan_' + counter),
            $btnEditKecamatan = $('a#btn_edit_kecamatan_' + counter),
            $selectKota = $('select#kota_' + counter)
            ;
            
            //di eksekusi apabila inputan dan select tidak kosong
        if ($inputKecamatan.eq($inputKecamatan.length-1).val() && $selectKota.val() != "") { 
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_region',
                data     : {parent: $selectKota.val(), 
                            tipe: '4',
                            nama: $inputKecamatan.val()
                           },
                dataType : 'json',
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
                        $selectKecamatan.val('');

                    });
                }
            });   
        }    
    }

    var handleEditKelurahan = function(counter){
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
            handleCancelKelurahan(counter);
        });

        $btnSaveKelurahan.on('click', function(){
            handleSaveKelurahan(counter);
        });
    }

    var handleCancelKelurahan = function(counter){
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
    }

    var handleSaveKelurahan = function(counter){
            
        var $inputKelurahan = $('input#input_kelurahan_' + counter),
            $selectKelurahan = $('select#kelurahan_' + counter),
            $btnSaveKelurahan = $('a#btn_save_kelurahan_' + counter),
            $btnCancelKelurahan = $('a#btn_cancel_kelurahan_' + counter),
            $btnEditKelurahan = $('a#btn_edit_kelurahan_' + counter),
            $selectKecamatan = $('select#kecamatan_' + counter)
            ;
            
            //alert($selectKecamatan.val());
            //di eksekusi apabila inputan dan select tidak kosong
        if ($inputKelurahan.eq($inputKelurahan.length-1).val() && $selectKecamatan.val() != "") { 
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_region',
                data     : {parent: $selectKecamatan.val(), 
                            tipe: '5',
                            nama: $inputKelurahan.val()
                           },
                dataType : 'json',
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
                        $selectKelurahan.val('');

                    });
                }
            });   
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
    var handleSelectNegara = function(counter){
        //var numRow = $counter.val();
            //alert($counter.val());
            //$('input#warehouse_id').val($(this).val());

        var $negara_select = $('select#negara_' + counter);
        
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_negara',
            data     : {id_negara: '1'},
            dataType : 'json',
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
            }
        });
    }

    var handleSelectProvinsi = function(){
        // alert('aa');
        $('select.negara').on('change', function(){

            //var numRow = itemCounter++;
            var numRow = $counter.val();
            //alert($counter.val());
            //$('input#warehouse_id').val($(this).val());

            var $provinsi_select = $('select#provinsi_' + numRow);
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_provinsi',
                data     : {id_negara: $(this).val()},
                dataType : 'json',
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
                }
            });
        })
    }

    var handleSelectKota = function(){
        //alert('aa');
        $('select.provinsi').on('change', function(){

            //var numRow = itemCounter++;
            var numRow = $counter.val();
            //alert($counter.val());
            //$('input#warehouse_id').val($(this).val());

            var $kota_select = $('select#kota_' + numRow);
            
            //alert($kota_select);

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kota',
                data     : {id_provinsi: $(this).val()},
                dataType : 'json',
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
                }
            });
        })
    }

    var handleSelectKecamatan = function(){
        //alert('aa');
        $('select.kota').on('change', function(){

            //var numRow = itemCounter++;
            var numRow = $counter.val();
            //alert($counter.val());
            //$('input#warehouse_id').val($(this).val());

            var $kecamatan_select = $('select#kecamatan_' + numRow);
            
            //alert($kota_select);

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kecamatan',
                data     : {id_kota: $(this).val()},
                dataType : 'json',
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
                }
            });
        })
    }

    var handleSelectKelurahan = function(){
        //alert('aa');
        $('select.kecamatan').on('change', function(){

            //var numRow = itemCounter++;
            var numRow = $counter.val();
            //alert($counter.val());
            //$('input#warehouse_id').val($(this).val());

            var $kelurahan_select = $('select#kelurahan_' + numRow);
            
            //alert($kota_select);

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kelurahan',
                data     : {id_kecamatan: $(this).val()},
                dataType : 'json',
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
                }
            });
        })
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
        $("#pasien_foto").uploadify({
            'swf'               : mb.baseUrl()+'assets/mb/global/uploadify/uploadify.swf',
            'uploader'          : mb.baseUrl()+'assets/mb/global/uploadify/uploadify7.php',
            'formData'          : {'type' : 'foto_pasien'}, 
            'fileObjName'       : 'Filedata', 
            'fileSizeLimit'     : '1024KB',  //TODO : mau pake parameter??
            'fileTypeDesc'      : 'All Files',
            'fileTypeExts'      : '*.*',
            'method'            : 'post', 
            'multi'             : false, 
            'queueSizeLimit'    : 1, 
            'removeCompleted'   : true, 
            'removeTimeout'     : 5, 
            'uploadLimit'       : 5, 
            'onUploadSuccess'   : function(file, data, response) {
                console.log(data);
                $("#choosen_file_container").show();
                $("#choosen_file").html('<a href="'+mb.baseUrl()+'assets/mb/var/temp/'+data+'" target="_blank"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+data+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a>');
                $("#url").val(data);
            }
        }); 
    }
    
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/pabrik/';
        handleValidation();
        handleConfirmSave();
        handleMultiSelect();
        handleDatePickers();
        handlePilihPasien();
        handleTambahRowPelengkap();
        handleTambahRowRekamMedis();
        handleSelectCabang();
        // handleUploadify();
        //$('select#cabang_id').select2;
        //handleSelectProvinsi();
        initForm();
        //alert('1');
    };
 }(mb.app.pabrik.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.pabrik.add.init();
});