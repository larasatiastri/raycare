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

        $btnEditNegara.on('click', function(){
            handleEditNegara();
        });

        $btnEditProvinsi.on('click', function(){
            handleEditProvinsi();
        });

        $btnEditKota.on('click', function(){
            handleEditKota();
        });

        $btnEditKecamatan.on('click', function(){
            handleEditKecamatan();
        });

        $btnEditKelurahan.on('click', function(){
            handleEditKelurahan();
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

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        $btnSubjekTelp.on('click', function(){
            handleEditSubjekTelp(counter);
        });

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

    var handleEditNegara = function(){
            var $inputNegara = $('input#input_negara'),
                $selectNegara = $('select#negara'),
                $btnSaveNegara = $('a#btn_save_negara'),
                $btnCancelNegara = $('a#btn_cancel_negara'),
                $btnEditNegara = $('a#btn_edit_negara');

            $btnEditNegara.addClass("hidden");
            $selectNegara.addClass("hidden");

            $btnSaveNegara.removeClass("hidden");
            $btnCancelNegara.removeClass("hidden");
            $inputNegara.removeClass("hidden");

            $inputNegara.focus();

            $btnCancelNegara.on('click', function(){
                handleCancelNegara();
            });

            $btnSaveNegara.on('click', function(){
                handleSaveNegara();
            });
    }   

    var handleCancelNegara= function(){
            var $inputNegara = $('input#input_negara'),
                $selectNegara = $('select#negara'),
                $btnSaveNegara = $('a#btn_save_negara'),
                $btnCancelNegara = $('a#btn_cancel_negara'),
                $btnEditNegara = $('a#btn_edit_negara');

            $btnEditNegara.removeClass("hidden");
            $selectNegara.removeClass("hidden");

            $btnSaveNegara.addClass("hidden");
            $btnCancelNegara.addClass("hidden");
            $inputNegara.addClass("hidden");

            $inputNegara.val("");
    }

    var handleSaveNegara= function(){
        
        //manggil semua yang berhubungan dengan negara sesuai dengan row yang di ambil dari counter
        var $inputNegara = $('input#input_negara'),
            $selectNegara = $('select#negara'),
            $btnSaveNegara = $('a#btn_save_negara'),
            $btnCancelNegara = $('a#btn_cancel_negara'),
            $btnEditNegara = $('a#btn_edit_negara');
            

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

    var handleEditProvinsi = function(){
            var $inputProvinsi = $('input#input_provinsi'),
                $selectProvinsi = $('select#provinsi'),
                $btnSaveProvinsi = $('a#btn_save_provinsi'),
                $btnCancelProvinsi = $('a#btn_cancel_provinsi'),
                $btnEditProvinsi = $('a#btn_edit_provinsi');

            $btnEditProvinsi.addClass("hidden");
            $selectProvinsi.addClass("hidden");

            $btnSaveProvinsi.removeClass("hidden");
            $btnCancelProvinsi.removeClass("hidden");
            $inputProvinsi.removeClass("hidden");

            $inputProvinsi.focus();

            $btnCancelProvinsi.on('click', function(){
                handleCancelProvinsi();
            });

            $btnSaveProvinsi.on('click', function(){
                handleSaveProvinsi();
            });
    }

    var handleCancelProvinsi= function(){
            var $inputProvinsi = $('input#input_provinsi'),
                $selectProvinsi = $('select#provinsi'),
                $btnSaveProvinsi = $('a#btn_save_provinsi'),
                $btnCancelProvinsi = $('a#btn_cancel_provinsi'),
                $btnEditProvinsi = $('a#btn_edit_provinsi');

            $btnEditProvinsi.removeClass("hidden");
            $selectProvinsi.removeClass("hidden");

            $btnSaveProvinsi.addClass("hidden");
            $btnCancelProvinsi.addClass("hidden");
            $inputProvinsi.addClass("hidden");

            $inputProvinsi.val("");
    }  

    var handleSaveProvinsi = function(){
            
        var $inputProvinsi = $('input#input_provinsi'),
            $selectProvinsi = $('select#provinsi'),
            $selectNegara = $('select#negara'),
            $btnSaveProvinsi = $('a#btn_save_provinsi'),
            $btnCancelProvinsi = $('a#btn_cancel_provinsi'),
            $btnEditProvinsi = $('a#btn_edit_provinsi');
            
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

    var handleEditKota = function(){
            var $inputKota = $('input#input_kota'),
                $selectKota = $('select#kota'),
                $btnSaveKota = $('a#btn_save_kota'),
                $btnCancelKota = $('a#btn_cancel_kota'),
                $btnEditKota = $('a#btn_edit_kota');

            $btnEditKota.addClass("hidden");
            $selectKota.addClass("hidden");

            $btnSaveKota.removeClass("hidden");
            $btnCancelKota.removeClass("hidden");
            $inputKota.removeClass("hidden");

            $inputKota.focus();
            
            $btnCancelKota.on('click', function(){
                handleCancelKota();
            });

            $btnSaveKota.on('click', function(){
                handleSaveKota();
            });
    }

    var handleCancelKota= function(){
            var $inputKota = $('input#input_kota'),
                $selectKota = $('select#kota'),
                $btnSaveKota = $('a#btn_save_kota'),
                $btnCancelKota = $('a#btn_cancel_kota'),
                $btnEditKota = $('a#btn_edit_kota');

            $btnEditKota.removeClass("hidden");
            $selectKota.removeClass("hidden");

            $btnSaveKota.addClass("hidden");
            $btnCancelKota.addClass("hidden");
            $inputKota.addClass("hidden");

            $inputKota.val("");
    }

    var handleSaveKota = function(){
            
        var $inputKota = $('input#input_kota'),
            $selectKota = $('select#kota'),
            $btnSaveKota = $('a#btn_save_kota'),
            $btnCancelKota = $('a#btn_cancel_kota'),
            $btnEditKota = $('a#btn_edit_kota'),
            $selectProvinsi = $('select#provinsi')
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

    var handleEditKecamatan = function(){
            var $inputKecamatan = $('input#input_kecamatan'),
                $selectKecamatan = $('select#kecamatan'),
                $btnSaveKecamatan = $('a#btn_save_kecamatan'),
                $btnCancelKecamatan = $('a#btn_cancel_kecamatan'),
                $btnEditKecamatan = $('a#btn_edit_kecamatan');

            $btnEditKecamatan.addClass("hidden");
            $selectKecamatan.addClass("hidden");

            $btnSaveKecamatan.removeClass("hidden");
            $btnCancelKecamatan.removeClass("hidden");
            $inputKecamatan.removeClass("hidden");

            $inputKecamatan.focus();
            
            $btnCancelKecamatan.on('click', function(){
                handleCancelKecamatan();
            });

            $btnSaveKecamatan.on('click', function(){
                handleSaveKecamatan();
            });
    }

    var handleCancelKecamatan = function(){
            var $inputKecamatan = $('input#input_kecamatan'),
                $selectKecamatan = $('select#kecamatan'),
                $btnSaveKecamatan = $('a#btn_save_kecamatan'),
                $btnCancelKecamatan = $('a#btn_cancel_kecamatan'),
                $btnEditKecamatan = $('a#btn_edit_kecamatan');

            $btnEditKecamatan.removeClass("hidden");
            $selectKecamatan.removeClass("hidden");

            $btnSaveKecamatan.addClass("hidden");
            $btnCancelKecamatan.addClass("hidden");
            $inputKecamatan.addClass("hidden");

            $inputKecamatan.val("");
    }

    var handleSaveKecamatan = function(){
            
        var $inputKecamatan = $('input#input_kecamatan'),
            $selectKecamatan = $('select#kecamatan'),
            $btnSaveKecamatan = $('a#btn_save_kecamatan'),
            $btnCancelKecamatan = $('a#btn_cancel_kecamatan'),
            $btnEditKecamatan = $('a#btn_edit_kecamatan'),
            $selectKota = $('select#kota')
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

    var handleEditKelurahan = function(){
        var $inputKelurahan = $('input#input_kelurahan'),
            $selectKelurahan = $('select#kelurahan'),
            $btnSaveKelurahan = $('a#btn_save_kelurahan'),
            $btnCancelKelurahan = $('a#btn_cancel_kelurahan'),
            $btnEditKelurahan = $('a#btn_edit_kelurahan');

        $btnEditKelurahan.addClass("hidden");
        $selectKelurahan.addClass("hidden");

        $btnSaveKelurahan.removeClass("hidden");
        $btnCancelKelurahan.removeClass("hidden");
        $inputKelurahan.removeClass("hidden");

        $inputKelurahan.focus();
        
        $btnCancelKelurahan.on('click', function(){
            handleCancelKelurahan();
        });

        $btnSaveKelurahan.on('click', function(){
            handleSaveKelurahan();
        });
    }

    var handleCancelKelurahan = function(){
        var $inputKelurahan = $('input#input_kelurahan'),
            $selectKelurahan = $('select#kelurahan'),
            $btnSaveKelurahan = $('a#btn_save_kelurahan'),
            $btnCancelKelurahan = $('a#btn_cancel_kelurahan'),
            $btnEditKelurahan = $('a#btn_edit_kelurahan');

        $btnEditKelurahan.removeClass("hidden");
        $selectKelurahan.removeClass("hidden");

        $btnSaveKelurahan.addClass("hidden");
        $btnCancelKelurahan.addClass("hidden");
        $inputKelurahan.addClass("hidden");

        $inputKelurahan.val("");
    }

    var handleSaveKelurahan = function(){
            
        var $inputKelurahan = $('input#input_kelurahan'),
            $selectKelurahan = $('select#kelurahan'),
            $btnSaveKelurahan = $('a#btn_save_kelurahan'),
            $btnCancelKelurahan = $('a#btn_cancel_kelurahan'),
            $btnEditKelurahan = $('a#btn_edit_kelurahan'),
            $selectKecamatan = $('select#kecamatan')
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


     var handleSelectProvinsi = function(){
        $('select#negara').on('change', function(){

            //$('input#warehouse_id').val($(this).val());

            var $provinsi_select = $('select#provinsi');
            
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

            var $kota_select = $('select#kota');
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kota',
                data     : {id_provinsi: $(this).val()},
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
        $('select#kota').on('change', function(){

            //$('input#warehouse_id').val($(this).val());

            var $kecamatan_select = $('select#kecamatan');
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kecamatan',
                data     : {id_kota: $(this).val()},
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
        //alert('aa');
        $('select#kecamatan').on('change', function(){

            //var numRow = itemCounter++;
            //alert($counter.val());
            //$('input#warehouse_id').val($(this).val());

            var $kelurahan_select = $('select#kelurahan');
            
            //alert($kota_select);

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_kelurahan',
                data     : {id_kecamatan: $(this).val()},
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
        // handleValidation();
        handleConfirmSave();
        addItemRow();
        initForm();
        handleDateRangePickers();
        handleSelectProvinsi();
        handleSelectKota();
        handleSelectKecamatan();
        handleSelectKelurahan();
    };
 }(mb.app.cabang.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.add.init();
});