mb.app.item = mb.app.item || {};
mb.app.item.add = mb.app.item.add || {};

// mb.app.item.add namespace
(function(o){

    var
        baseAppUrl                 = '', 
        $form                      = $('#form_add_item')
        $tableSatuan               = $('#table_satuan', $form),
        $tableHargaItemSatuan      = $('#table_satuan_harga'),
        $tableHargaItemSatuanModal = $('#table_harga_satuan_modal'),
        $tableIdentitasDetail      = $('#table_identitas_detail', $form),
        theadFilterTemplate        = $('#thead-filter-template').text(),
        jml                        = 1,
        x                          = 0,
        y                          = 0,
        months      = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
        date      = new Date(),
        day       = date.getDate(),
        month     = date.getMonth(),
        yy        = date.getYear(),
        year      = (yy < 1000) ? yy + 1900 : yy,
        curr_hour = date.getHours(),
        curr_min  = date.getMinutes(),
        curr_sec  = date.getSeconds();
        tanggal   = year +':' + months[month] + ':' + day,

        tplSatuanRow               = $.validator.format( $('#tpl_satuan_row').text()),
        satuanCounter              = 1,
        
        tplIdentitasRow            = $.validator.format( $('#tpl_identitas_row').text()),
        itemCounter                = 1,
        
        tplFormGambar              = '<li class="fieldset">' + $('#tpl-form-gambar', $form).val() + '<hr></li>',
        regExpTplGambar            = new RegExp('gambar[0]', 'g'),   // 'g' perform global, case-insensitive
        gambarCounter              = 1,
        
        formsGambar = {
                        'gambar' : 
                        {            
                            section  : $('#section-gambar', $form),
                            template : $.validator.format( tplFormGambar.replace(regExpTplGambar, '_id_{0}') ), //ubah ke format template jquery validator
                            counter  : function(){ gambarCounter++; return gambarCounter-1; }
                        }   
                    },

        tplFormIdentitas = '<li class="fieldset">' + $('#tpl-form-identitas', $form).val() + '<hr></li>',
        regExpTplIdentitas   = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        identitasCounter     = 1,
        
        formsIdentitas = {
                        'identitas' : 
                        {            
                            section  : $('#section-identitas', $form),
                            template : $.validator.format( tplFormIdentitas.replace(regExpTplIdentitas, '_id_{0}') ), //ubah ke format template jquery validator
                            counterIndetitas  : function(){ identitasCounter++; return identitasCounter-1; }
                        }   
                    }
        ;

    var initForm = function(){
        
        $.each(formsGambar, function(idx, form){
            // handle button add
            $('a#tambah_gambar', form.section).on('click', function(){
                
                addFieldsetGambar(form);

            });
            // beri satu fieldset kosong
            addFieldsetGambar(form);

        });

        $.each(formsIdentitas, function(idx, form){

            // handle button add
            $('a#tambah_identitas',form.section).on('click', function(){
               
                addFieldsetIdentitas(form);
                jml+=1;

            });

            // beri satu fieldset kosong

            addFieldsetIdentitas(form);
        
        });

        var $btnDeleteId = $('a.del-identitas-db');
        handleDeleteFieldsetIdentitasDb($btnDeleteId);

        addItemRow();
        // addIdentitasRow();

        $('#operator', $('tbody tr#item_row_1'), $tableSatuan).text("");
        $('tbody tr#item_row_1', $tableSatuan).hide();
        // handleRadioPrimary();
    };

    var addFieldsetGambar = function(form)
    {

        var 
            $section           = form.section,
            $fieldsetContainer = $('ul.gambar', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer);

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-gambar', $newFieldset).on('click', function(){
            handleDeleteFieldsetGambar($(this).parents('.fieldset').eq(0));
        });

        $('input#radio_primary_gambar_id_1').prop('checked', true);
        $('input[name$="[is_primary_gambar]"]').val('');
        $('input#primary_gambar_id_1').val(1);

        $('input[name="gambar_is_primary"]', $newFieldset).on('click', function()
        {
            $('input[name$="[is_primary_gambar]"]').val('');
            $('input[name$="[is_primary_gambar]"]', $newFieldset).val(1);
        });


        $('input[name="gambar_is_primary"]', $newFieldset).uniform();

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        handleUploadify(counter);

    };

    var addFieldsetIdentitas = function(form){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counterIndetitas(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            result=0,
            result2=0,
            result3=0;
            
            // alert(counter);
      //s  handleDatePicker();
        $('select[name$="[identitas_type]"]', $newFieldset).on('change', function(){
           
            handleSelectSection(this.value, $newFieldset);
        });
        
        $('a.del-identitas', $newFieldset).on('click', function(){
            
            handleDeleteFieldsetIdentitas($(this).parents('.fieldset').eq(0));
        });
        
        // var tplIdentitasRow            = $.validator.format( $('#tpl_identitas_row').text()),
        
        // console.log(tplIdentitasRow);
        // addIdentitasRow($('table#table_identitas_detail_' + counter));
        // addIdentitasRow($('table[name$="[table_identitas_detail]"]', $newFieldset),counter);
        addIdentitasRow($('table#table_identitas_detail_' + counter), counter);


        $('a[name$="[addrow]"]', $newFieldset).on('click', function(){
            
         // addIdentitasRow($newFieldset);
         // addIdentitasRow($('table#table_identitas_detail_' + counter));
         // addIdentitasRow($('table[name$="[table_identitas_detail]"]', $newFieldset),counter);
        addIdentitasRow($('table#table_identitas_detail_' + counter), counter);


           // handleSelectSection(this.value, $newFieldset);
        });
         
         // addIdentitasRow($newFieldset);
         // addIdentitasRow($('table[name$="[table_identitas_detail]"]', $newFieldset),counter);
         
        handleCheckIdentitas(); 
        //jelasin warna hr pemisah antar fieldset

        $('input[name$="[identitas_row]"]', $newFieldset).val(counter);
        $('hr', $newFieldset).css('border-color', 'silver');
    };

    var addItemRow = function(){
        // $('#operator', $('tbody tr#item_row_1'), $tableSatuan).text("");
        var numRow = $('tbody tr', $tableSatuan).length;
        
        console.log('Num Row Satuan ' + numRow);
        if( numRow > 0 && ! isValidLastRow() ) return;
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        //if( numRow > 0 && ! isValidLastRow() ) return;
        console.log('satuan row = ' + tplSatuanRow);
        var 
            $rowContainer       = $('tbody', $tableSatuan),
            $newItemRow         = $(tplSatuanRow(satuanCounter++)).appendTo( $rowContainer ),
            $btnSearchItem      = $('.search-item', $newItemRow),
            $operator           = $('#operator', $newItemRow).text("="),
            $inputJumlah        = $('input#satuan_jumlah_1');
            $inputJumlahSaatIni = $('input.jumlah', $newItemRow);
            $inputSatuan        = $('input#satuan_satuan_1');
            $inputSatuanSaatIni = $('input.satuan', $newItemRow);
            $inputActionSatuan        = $('input.action_satuan', $newItemRow);
            $inputActionJumlah        = $('input.action_jumlah', $newItemRow);
            $inputSatuanId        = $('input.satuan_id', $newItemRow);
        // handle delete btn
        console.log($newItemRow);

        handleBtnDelete( $('a.del-this', $newItemRow) );
        handleRadioPrimary( $('.is_primary', $newItemRow), $inputSatuanId );

        // $('.is_primary', $newItemRow).uniform();
        handleUpdateItemSatuan();
        handleJumlah($inputJumlah);
        // handleJumlahOnChange($inputJumlahOnchange);
        handleSatuan($inputSatuan);
        // handleSatuanOnChange($inputSatuanSaatIni, $inputJumlahSaatIni, $inputActionSatuan, $inputActionJumlah, $inputSatuanId);
        
    };

    var addIdentitasRow = function(tabel, counter){
        var numRow = $('tbody tr', $tableIdentitasDetail).length;
              // itemCounter             = 1;
        // alert(itemCounter);
        

        // var tplIdentitasRow            = $.validator.format( $('#tpl_identitas_row').text());
        console.log('identitas row = ' + tplIdentitasRow);
        var 
            $rowContainer   = $('tbody', tabel),
            $newItemRow     = $(tplIdentitasRow(itemCounter++)).appendTo( $rowContainer ),
            $btnDelete      = $('.del-detail', $newItemRow);
    
      //   console.log($newItemRow);
      // //  handleBtnSearchItem($btnSearchItem);
        // $('.table_detail', tabel).attr('id', 'item_row_' + itemCounter);
        $('.t1', $newItemRow).attr('id', 'identitas_text_detail_'+itemCounter);
        $('.t2', $newItemRow).attr('id', 'identitas_text_detail_'+itemCounter);
        $('.t1', $newItemRow).attr('name', 'identitas_'+counter+'['+itemCounter+'][text_detail]');
        $('.t2', $newItemRow).attr('name', 'identitas_'+counter+'['+itemCounter+'][isi_detail]');

        
        
        // itemCounter++;
        handleBtnDeleteDetail($btnDelete,tabel,counter);
      //   $("#counter").val(counter);
       // handleCheck($checkMultiply);
        // itemCounter++;
    };

    var handleDeleteFieldsetGambar = function($fieldset)
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

    var handleDeleteFieldsetIdentitas = function($fieldset)
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

    var handleDeleteFieldsetIdentitasDb = function($btn)
    {        
        $btn.click(function() {
            var index = $(this).data('index'),
                $rowId = $('li#list_'+index),
                msg = $(this).data('confirm');

            bootbox.confirm(msg, function(results){
                if(results == true){
                    $('input[name$="[is_active]"]', $rowId).attr('value',0);
                    $rowId.hide();
                }
            });


        });
       
    };

    var handleBtnDeleteDetail = function($btn, $tableItem)
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

    var handleSelectSection = function(value,$fieldset)
    {
        if(value == 1 || value==2)
        {
            $('div#section_1', $fieldset).show();
            $('div#section_2', $fieldset).hide();
            $('div#section_3', $fieldset).hide();
        }
        if(value==5 || value==6 || value==7)
        {
            $('div#section_1', $fieldset).hide();
            $('div#section_2', $fieldset).show();
            $('div#section_3', $fieldset).hide();
            
        }
        if(value == 3 || value == 4)
        {
            $('div#section_3', $fieldset).hide();
            $('div#section_2', $fieldset).hide();
            $('div#section_1', $fieldset).hide();
            
        }
    }

    var handleModalHarga = function(){
        $('a#modal_ok', $form).click(function() {
        
            $form_add_item = $('#form_add_item');

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_satuan_harga',
                data     : $form_add_item.serialize(),
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    var item_id = $('input#item_id').val();
                    $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + item_id).load();
                    // $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/51').load();
                    $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + $('input#item_id').val() + '/' + tanggal).load();
                    $('input#tanggal').val("");
                    $('#closeModal').click();              
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
        });
    }
    var handleDeleteRow = function(){
        $('a#delete_row_satuan', $form).click(function() {
            bootbox.confirm('Apakah anda yakin akan menghapus satuan item ini?', function(result){
                if (result==true) {
                    $('tbody tr', $tableSatuan).remove();
                    satuanCounter             = 1;

                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'delete_satuan',
                        data     : {item_id : $('input#item_id').val()},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            console.log('delete satuan item sukses');
                            
                            
                                $('input#parent_id').val("");
                                addItemRow();
                                // addIdentitasRow();

                                $('#operator', $('tbody tr#item_row_1'), $tableSatuan).text("");
                                $('tbody tr#item_row_1', $tableSatuan).hide();

                                $inputTambahRow = $('input#tambah_row');

                                $inputTambahRow.val('row_deleted');

                                $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + $('input#item_id').val()).load();
                                $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + $('input#item_id').val() + '/' + tanggal).load();
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });


                }
            });
            
        });
    }

    var handleTambahRow = function(){
        $('a#tambah_row_satuan', $form).click(function() {

            $inputTambahRow = $('input#tambah_row');

            $save = true;

            if ($('select#kategori option:selected').val() == "")
            {
                $('select#kategori').focus();
            }
            else if ($('select#sub_kategori option:selected').val() == "")
            {
                $('select#sub_kategori').focus();
            }
            else if ($('input#nama').val() == "")
            {
                $('input#nama').focus();
            }
            else
            {
                if ($inputTambahRow.val() == 'save_item') {

                    // Show First Row
                    $('tbody tr#item_row_1', $tableSatuan).show();
                    CounterJumlahSebelumnya = satuanCounter-2;
                    CounterJumlahSelanjutnya = satuanCounter-1;
                    $inputJumlahSebelumnya = $('input#satuan_jumlah_'+CounterJumlahSebelumnya);
                    $labelJumlahSelanjutnya = $('label#jumlah_'+CounterJumlahSelanjutnya);

                    $inputSatuanSebelumnya = $('input#satuan_satuan_'+CounterJumlahSebelumnya);
                    $labelSatuanSelanjutnya = $('label#satuan_'+CounterJumlahSelanjutnya);

                    $labelJumlahSelanjutnya.text();
                    $labelSatuanSelanjutnya.text($inputSatuanSebelumnya.val());
                    

                    //save Item
                    $kategori = $('#kategori').val();
                    $tipe_akun = $('input#tipe_akun').val();
                    $sub_kategori = $('#sub_kategori').val();
                    $nama = $('#nama').val();
                    $keterangan = $('#keterangan').val();

                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'save_item',
                        data     : {command : 'add', kategori : $kategori, tipe_akun : $tipe_akun, sub_kategori : $sub_kategori, nama : $nama, keterangan : $keterangan},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            console.log('input item sukses');
                            
                            
                                $('input#item_id').val(results);
                            
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });

                    $inputTambahRow.val('add_parent');
                }else if ($inputTambahRow.val() == 'add_parent'){
                    var item_id = $('input#item_id').val();

                    addItemRow();
                    CounterJumlahSebelumnya = satuanCounter-2;
                    CounterJumlahSelanjutnya = satuanCounter-1;
                    $inputJumlahSebelumnya = $('input#satuan_jumlah_'+CounterJumlahSebelumnya);
                    $labelJumlahSelanjutnya = $('label#jumlah_'+CounterJumlahSelanjutnya);

                    $inputSatuanIdSebelumnya = $('input#satuan_id_'+CounterJumlahSebelumnya);
                    $inputSatuanSebelumnya = $('input#satuan_satuan_'+CounterJumlahSebelumnya);
                    $labelSatuanSelanjutnya = $('label#satuan_'+CounterJumlahSelanjutnya);

                    $labelJumlahSelanjutnya.text(1);
                    $labelSatuanSelanjutnya.text($inputSatuanSebelumnya.val());

                    $inputTambahRow.val('add_child');
                }else if ($inputTambahRow.val() == 'add_child'){
                    var item_id = $('input#item_id').val();
                    
                    addItemRow();
                    CounterJumlahSebelumnya = satuanCounter-2;
                    CounterJumlahSelanjutnya = satuanCounter-1;
                    $inputJumlahSebelumnya = $('input#satuan_jumlah_'+CounterJumlahSebelumnya);
                    $labelJumlahSelanjutnya = $('label#jumlah_'+CounterJumlahSelanjutnya);

                    $inputSatuanIdSebelumnya = $('input#satuan_id_'+CounterJumlahSebelumnya);
                    $inputSatuanSebelumnya = $('input#satuan_satuan_'+CounterJumlahSebelumnya);
                    $labelSatuanSelanjutnya = $('label#satuan_'+CounterJumlahSelanjutnya);

                    $labelJumlahSelanjutnya.text(1);
                    $labelSatuanSelanjutnya.text($inputSatuanSebelumnya.val());

                    $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal/' + item_id).load();

                }else{
                    $('tbody tr#item_row_1', $tableSatuan).show();
                    CounterJumlahSebelumnya = satuanCounter-2;
                    CounterJumlahSelanjutnya = satuanCounter-1;
                    $inputJumlahSebelumnya = $('input#satuan_jumlah_'+CounterJumlahSebelumnya);
                    $labelJumlahSelanjutnya = $('label#jumlah_'+CounterJumlahSelanjutnya);

                    $inputSatuanSebelumnya = $('input#satuan_satuan_'+CounterJumlahSebelumnya);
                    $labelSatuanSelanjutnya = $('label#satuan_'+CounterJumlahSelanjutnya);

                    $labelJumlahSelanjutnya.text();
                    $labelSatuanSelanjutnya.text($inputSatuanSebelumnya.val());

                    $inputTambahRow.val('add_parent');
                }
            }

            
            
        });
    };

    var isValidLastRow = function(){
        
        var 
                $satuan = $('input[name$="[satuan]"]', $tableSatuan),
                // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
                satuanSet    = $satuan.eq($satuan.length-1).val()
                // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;

       
            return (satuanSet != '');
    };
    var handleBtnDelete = function($btn){
        var numRow = $('tbody tr', $tableSatuan).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableSatuan);

        $btn.on('click', function(e){
            
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        $row.remove();
                        if($('tbody>tr', $tableSatuan).length == 0){
                            addItemRow();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            
            e.preventDefault();
        });
    };

    var handleRadioPrimary = function($rdo, $input){
        var numRow = $('tbody tr', $tableSatuan).length;
        // var 
        //     rowId    = $rdo.closest('tr').prop('id'),
        //     $row     = $('#'+rowId, $tableSatuan);

        $rdo.on('click', function(e){
            // if ($rdo.is(':checked'))
            // {
            //   alert($rdo.data('id'));
            // }
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        // $row.remove();
                        // if($('tbody>tr', $tableSatuan).length == 0){
                        //     addItemRow();
                        // }
                        // focusLastItemCode();
                     // }
                // });
            // alert($input.val());

            $('input#satuan_primary').val($input.val());
            // e.preventDefault();
        });
    };

    // var handleRadioPrimary = function(){
    //     $('.is_primary').click(function() {
            
    //             // bootbox.confirm('Are you sure as to delete this item?', function(result){
    //                 // if (result==true) {
    //                     //if(! isValidLastRow() ) return;
    //                     // $row.remove();
    //                     // if($('tbody>tr', $tableSatuan).length == 0){
    //                     //     addItemRow();
    //                     // }
    //                     // focusLastItemCode();
    //                  // }
    //             // });
    //         alert($(this).data('id'));
            
    //         e.preventDefault();
    //     });
    // };

    var handleJumlah = function($input){
        var numRow = $('tbody tr', $tableSatuan).length;
        var 
            rowId    = $input.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableSatuan);

        $input.on('change', function(e){
            
            //alert($(this).val());
            // $('label.jumlah', $row).text($(this).val());    
            $('label#jumlah_1').text($(this).val());    
            
            e.preventDefault();
        });
    };

    var handleSatuanOnChange = function($inputSatuan, $inputJumlah, $inputActionSatuan, $inputActionJumlah){
        var numRow = $('tbody tr', $tableSatuan).length;
        var 
            rowId    = $inputSatuan.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableSatuan);

         CounterJumlahSebelumnya = satuanCounter-2;
        CounterJumlahSelanjutnya = satuanCounter-1;
        $inputJumlahSebelumnya = $('input#satuan_jumlah_'+CounterJumlahSebelumnya);
        $labelJumlahSelanjutnya = $('label#jumlah_'+CounterJumlahSelanjutnya);

        $inputSatuanIdSebelumnya = $('input#satuan_id_'+CounterJumlahSebelumnya);
        $inputSatuanSebelumnya = $('input#satuan_satuan_'+CounterJumlahSebelumnya);
        $labelSatuanSelanjutnya = $('label#satuan_'+CounterJumlahSelanjutnya);

        $labelJumlahSelanjutnya.text(1);

        $inputJumlah.on('change', function(e){
            if ($inputActionJumlah.val() == "") {
                // alert($(this).val()); 
                $inputActionJumlah.val('add_jumlah');
            }else if ($inputActionJumlah.val() == "add_jumlah") {
                // alert('add Jumlah ' + $(this).val()); 
                $inputActionJumlah.val('edit_jumlah');
            }else{
                // alert('edit Jumlah ' + $(this).val()); 
                $labelSatuanSelanjutnya.text($inputSatuanSebelumnya.val());
            }
            //alert($(this).val());
            // $('label.jumlah', $row).text($(this).val());    
            // $('label#jumlah_1').text($(this).val()); 
              
            
            e.preventDefault();
        });        

        $inputSatuan.on('change', function(e){
            if ($inputActionSatuan.val() == "") {
                // alert($(this).val()); 
                if ($('input#parent_id').val() == "") {
                    // $.ajax({
                    //     type     : 'POST',
                    //     url      : baseAppUrl + 'save_satuan',
                    //     data     : {command: 'add_parent' , item_id : $('input#item_id').val(), jumlah : $inputJumlah.val(), satuan: $inputSatuan.val()},
                    //     dataType : 'json',
                    //     success  : function( results ) {
                    //         console.log('input satuan sukses');
                    //          $('input#parent_id').val(results);
                    //          $inputSatuanId.val(results);

                    //          $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + $('input#item_id').val()).load();
                    //          $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal/' + $('input#item_id').val()).load();
                    //     }
                    // });
                }else{
                    // $.ajax({
                    //     type     : 'POST',
                    //     url      : baseAppUrl + 'save_satuan',
                    //     data     : {command: 'add_child' , item_id : $('input#item_id').val(), jumlah : $inputJumlah.val(), satuan: $inputSatuan.val(), parent : $('input#parent_id').val()},
                    //     dataType : 'json',
                    //     success  : function( results ) {
                    //         console.log('input satuan sukses');
                    //          $('input#parent_id').val(results);
                    //          $inputSatuanId.val(results);

                    //          $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + $('input#item_id').val()).load();
                    //          $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal/' + $('input#item_id').val()).load();
                    //     }
                    // });
                }
                
                $inputActionSatuan.val('add_satuan');

            }else if ($inputActionSatuan.val() == "add_satuan") {
                
                    // $.ajax({
                    //     type     : 'POST',
                    //     url      : baseAppUrl + 'save_satuan',
                    //     data     : {command: 'edit_satuan' , satuan: $inputSatuan.val(), satuan_id : $inputSatuanId.val()},
                    //     dataType : 'json',
                    //     success  : function( results ) {
                    //         console.log('update satuan sukses');
                    //          // $('input#parent_id').val(results);
                    //          // $inputSatuanId.val(results);
                    //          alert(results);
                    //     }
                    // });
                
                $labelSatuanSelanjutnya.text($inputSatuanSebelumnya.val());
                // $inputActionSatuan.val('edit_satuan');

            }else{
                // alert('edit satuan ' + $(this).val()); 
                $labelSatuanSelanjutnya.text($inputSatuanSebelumnya.val());
            }
            //alert($(this).val());
            // $('label.jumlah', $row).text($(this).val());    
            // $('label#jumlah_1').text($(this).val()); 
              
            
            e.preventDefault();
        });
    };

    var handleSatuan = function($input){
        var numRow = $('tbody tr', $tableSatuan).length;
        var 
            rowId    = $input.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableSatuan);

        $input.on('change', function(e){
            
            //alert($(this).val());
            // $('label.satuan', $row).text($(this).val());    
            $('label#satuan_1').text($(this).val());
            
            e.preventDefault();
        });
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

            // // ajax form submit
            // submitHandler: function (form) {
            //     success1.show();
            //     error1.hide();
            //     // ajax form submit
            // }
        });
        
        //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
        $('#bahasa', $form).change(function () {
            $form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
    }
    
    //untuk dropdown pemilihan bahasa agar muncul bendera setiap negara
    function formatLang(state) {
        if (!state.id) return state.text; // optgroup
        return '<img class="flag" src="' + mb.baseUrl() + 'assets/global/img/flags/' + state.id.toLowerCase() + '.png"/>&nbsp;&nbsp;' + state.text;
    }

    var handleDropdownLanguage = function()
    {
        $('#bahasa', $form).select2({
            placeholder: 'Select a Language',
            allowClear: true,
            formatResult: formatLang,
            formatSelection: formatLang,
            escapeMarkup: function (m) {
                return m;
            }
        });
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;
            var i = 0;
            var msg = $(this).data('confirm');
            var proses = $(this).data('proses');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    Metronic.blockUI({boxed: true, message: proses});
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

    var handleErrorAfterSubmit = function(){
        
        var hasError = false;
        $('.help-block', $form).each(function() {
            var str = $(this).text();
            if (str.length>0) {
                //jika tidak mangandung kata 'hint:', ini adalah error message.
                if (str.indexOf('hint:') == -1) {
                    $(this).parent().addClass( "has-error" );
                    hasError = true;
                }
            } 
        });
        
        if (hasError == true) $('.alert-danger', $form).show();

    };

    var handleUploadify = function(counter)
    {

        var ul = $('#upload_'+counter+' ul');

       
        // Initialize the jQuery File Upload plugin
        $('#gambar_file_'+counter).fileupload({

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
                
                tpl.find('div.thumbnail').html('<a class="fancybox-button" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a>');
                $('input#gambar_url_'+counter).attr('value',filename);
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

    var handleSelectKategori = function(){
        $('select#kategori', $form).on('change', function() {
            // alert($(this).val());
            var $tipe_akun = $('label#tipe_akun', $form),
                $id_tipe_akun = $('input#tipe_akun', $form),
                $sub_kategori = $('select#sub_kategori', $form);

            //$kode_cabang_rujukan.val($(this).val());
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_tipe_akun',
                data     : {id_kategori: $(this).val()},
                dataType : 'json',
                beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                success  : function(results) {
                    
                    // alert(results);
                    $.each(results, function(key, value) {
                        // alert(value.tipe_akun);s
                        var tipe_akun = '';
                        if(value.tipe_akun == 1)
                        {
                            tipe_akun = 'Persediaan Barang';
                        }
                        else if(value.tipe_akun == 2)
                        {
                            tipe_akun = 'Harta Lancar';
                        }
                        else if(value.tipe_akun == 3)
                        {
                            tipe_akun = 'Harta Tetap';
                        }
                        else if(value.tipe_akun == 4)
                        {
                            tipe_akun = 'Jasa';
                        }
                        else
                        {
                            tipe_akun = 'Tipe Akuna';
                        }
                        $tipe_akun.text(tipe_akun);
                        $id_tipe_akun.val(value.tipe_akun);

                    });
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_sub_kategori',
                data     : {id_kategori: $(this).val()},
                dataType : 'json',
                beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                success  : function( results ) {
                  
                    $.each(results, function(key, value) {

                        $sub_kategori.empty();

                        $sub_kategori.append($("<option></option>")
                            .attr("value", '').text('Pilih Sub Kategori..'));

                        $.each(results, function(key, value) {
                            $sub_kategori.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $sub_kategori.val('');

                        });

                    });
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
        });
    };

    var handleKategoriSet = function(){
        var $tipe_akun = $('#tipe_akun', $form),
            $id_tipe_akun = $('input#tipe_akun', $form),
            $sub_kategori = $('select#sub_kategori', $form);

            //$kode_cabang_rujukan.val($(this).val());
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_tipe_akun',
                data     : {id_kategori: $('select#kategori', $form).val()},
                dataType : 'json',
                success  : function( results ) {
                  
                    $.each(results, function(key, value) {

                        //alert(value.tipe_akun);
                        $tipe_akun.text(value.tipe_akun);
                        $id_tipe_akun.val(value.id_tipe_akun);

                    });
                }
            });

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_sub_kategori',
                data     : {id_kategori: $(this).val()},
                dataType : 'json',
                success  : function( results ) {
                  
                    $.each(results, function(key, value) {

                        $sub_kategori.empty();

                        $sub_kategori.append($("<option></option>")
                            .attr("value", '').text('Pilih Sub Kategori..'));

                        $.each(results, function(key, value) {
                            $sub_kategori.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $sub_kategori.val('');

                        });

                    });
                }
            });
    };

    var handleDataTable = function(){
        oTableHargaSatuan = $tableHargaItemSatuan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_harga_item_satuan/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'item_satuan.item_id item_id','visible' : false, 'searchable': true, 'orderable': true },
                { 'name':'item_satuan.id satuan_id','visible' : false, 'searchable': true, 'orderable': true },
                { 'name':'item_harga.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'item_satuan.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'item_harga.harga harga','visible' : true, 'searchable': true, 'orderable': true },
                ]
        });
    }

    var handleDataTableModal = function(){

        $tableHargaItemSatuanModal.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + 0 + '/' + tanggal ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [

                { 'name':'item_satuan.item_id item_id','visible' : false, 'searchable': true, 'orderable': true },
                { 'name':'item_satuan.id satuan_id','visible' : false, 'searchable': true, 'orderable': true },
                { 'name':'item_harga.tanggal tanggal','visible' : false, 'searchable': true, 'orderable': true },
                { 'name':'item_satuan.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'item_harga.harga harga','visible' : true, 'searchable': true, 'orderable': true },

                ]
        });
    }

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
                autoclose: true,
            }).on('changeDate', function(ev){

            $tanggal = $('input#tanggal').val();
            var months    = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
                    date_selected      = new Date($tanggal),
                    day       = date_selected.getDate(),
                    month     = date_selected.getMonth(),
                    yy        = date_selected.getYear(),
                    year      = (yy < 1000) ? yy + 1900 : yy,
                    tanggal_dipilih   = year +':' + months[month] + ':' + day;

            
            $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + $('input#item_id').val() + '/' + tanggal_dipilih).load();
            


            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleSelectSubKategori = function(){
        $('select#sub_kategori').on('change', function(){
            
            $.ajax({
                type        : 'POST',
                url         : baseAppUrl + 'show_spesifikasi',
                data        : {sub_kategori_id: $(this).val()},
                dataType    : 'text',
                beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },

                success     : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#show_spesifikasi").html(results);
                    // //alert(results);
                    
                    
                    // $('a.pilih-data-penjamin', $form).on('click', function(){
                    //     var id = $(this).data('id');

                    //     $currentRow.val(id);

                    // });

                    // var $btnSearchDataPenjamin  = $('.pilih-data-penjamin', $form);
                    // handleBtnSearchDataPenjamin($btnSearchDataPenjamin);

                    // $("#show_claim").find('script').each(function(){
                    // event.preventDefault();
                    // eval($(this).text());
                    // });
                },
                complete : function(){
                    Metronic.unblockUI();
                }

            });
        }) 
    }

    var handleMultiSelect = function () {
        $('#multi_select_penjamin').multiSelect(); 
        $('#multi_select_identitas').multiSelect(); 
    };

    var handleMultiSelectPabrik = function () {
        $('#multi_select_pabrik').multiSelect({
              selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Cari Pabrik..'>",
              selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Cari Pabrik..'>",
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
              $('#multi_select_pabrik').multiSelect('select_all');
              return false;
        });
        $('#deselect-all').click(function(){
              $('#multi_select_pabrik').multiSelect('deselect_all');
              return false;
        });
    };

    var handleTabClicked = function(){
        // $('a#penjualan').on('click', function(){
        //     location.href = baseAppUrl + 'add/#penjualan';
        // })

    }
    var handleUpdateItemSatuan = function(){
        $('.satuan').on('change', function(e){
            // alert($(this).data('row'));
            thisRow = $(this).data('row');
            nextRow = $(this).data('row')+1;

            $satuan_id = $('input#satuan_id_'+thisRow).val();
            $jumlah = $('input#satuan_jumlah_'+thisRow).val();
            $item_id = $('input#item_id').val();
            $satuan = $('input#satuan_satuan_'+thisRow).val();
            $parent_id = $('input#parent_id').val();

            // alert('row saat ini : ' + thisRow + ' row selanjutnya : ' + nextRow);

            if ($('input#satuan_action_satuan_' + thisRow).val() == "") {
                // alert($(this).val()); 
                if ($('input#parent_id').val() == "") {
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'save_satuan',
                        data     : {command: 'add_parent' , item_id : $item_id, jumlah : $jumlah, satuan: $satuan},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            console.log('input satuan sukses');
                             $('input#parent_id').val(results);
                             $inputSatuanId.val(results);

                             $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + $('input#item_id').val()).load();
                             $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + $('input#item_id').val() + '/' + tanggal).load();
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
                }else{
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'save_satuan',
                        data     : {command: 'add_child' , item_id : $item_id, jumlah : $jumlah, satuan: $satuan, parent : $parent_id},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            console.log('input satuan sukses');
                             $('input#parent_id').val(results);
                             $inputSatuanId.val(results);

                             $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + $('input#item_id').val()).load();
                             $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + $('input#item_id').val() + '/' + tanggal).load();
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
                }
                
                $('input#satuan_action_satuan_' + thisRow).val('added_satuan');

            }else if ($('input#satuan_action_satuan_' + thisRow).val() == "added_satuan") {
                
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'save_satuan',
                        data     : {command: 'edit_satuan' , satuan: $satuan, satuan_id : $satuan_id},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            console.log('update satuan sukses');
                             // $('input#parent_id').val(results);
                             // $inputSatuanId.val(results);
                             // alert(results);
                             $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + $('input#item_id').val()).load();
                             $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + $('input#item_id').val() + '/' + tanggal).load();
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
                
                $('label#satuan_'+nextRow).text($('input#satuan_satuan_'+thisRow).val());
                // alert($satuan_id);
                // $inputActionSatuan.val('edit_satuan');

            }
        });

        $('.jumlah').on('change', function(e){
            // alert($(this).data('row'));
            thisRow = $(this).data('row');
            nextRow = $(this).data('row')+1;

            $satuan_id = $('input#satuan_id_'+thisRow).val();
            $jumlah = $('input#satuan_jumlah_'+thisRow).val();
            $item_id = $('input#item_id').val();
            $satuan = $('input#satuan_satuan_'+thisRow).val();
            $parent_id = $('input#parent_id').val();

            // alert('row saat ini : ' + thisRow + ' row selanjutnya : ' + nextRow);

            if ($('input#satuan_action_jumlah_' + thisRow).val() == "") {
                
                $('input#satuan_action_jumlah_' + thisRow).val('added_jumlah');

            }else if ($('input#satuan_action_jumlah_' + thisRow).val() == "added_jumlah") {
                
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'save_satuan',
                        data     : {command: 'edit_jumlah' , jumlah: $jumlah, satuan_id : $satuan_id},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            console.log('update satuan sukses');
                             // $('input#parent_id').val(results);
                             // $inputSatuanId.val(results);
                             // alert(results);
                             $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + $('input#item_id').val()).load();
                             $tableHargaItemSatuanModal.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + $('input#item_id').val() + '/' + tanggal).load();
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
                
                // $('label#satuan_'+nextRow).text($('input#satuan_satuan_'+thisRow).val());
                // alert($satuan_id);
                // $inputActionSatuan.val('edit_satuan');

            }
        });

    }

    var handleRefreshHarga = function(){
        $('a#refresh_table_penjualan').on('click', function(){
            var item_id = $('input#item_id').val();
                $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + item_id).load();
        });
        
    }

    var handleCheckIdentitas = function(){
        $('input.input-identitas').on('change', function(){
            if ($(this).val() != "") {
                $('input#input_is_identitas').val(1);
                // alert('ada isi');
            }else{
                $('input#input_is_identitas').val(0);
                // alert('ga ada isi');
            }
        });
    }

    var handleIsSale = function(){
        $('input#is_sale').on('click', function(){
            if( $(this).is(':checked') ){
                $('li.penjualan').removeClass('hidden');
            }else{
                $('li.penjualan').addClass('hidden');
            }
        });
    }

    var handleIsIdentitas = function(){
        $('input#is_identitas').on('click', function(){
            if( $(this).is(':checked') ){
                $('li.identitas').removeClass('hidden');
            }else{
                $('li.identitas').addClass('hidden');
            }
        });
    }

    

    o.init = function(){
        // handleValidation();
        // handleDropdownLanguage();
        baseAppUrl = mb.baseUrl() + 'master/item/';
        // handleUploadify();
        handleConfirmSave();
        handleSelectKategori();
        handleSelectSubKategori();
        // handleKategoriSet();
        handleMultiSelect();
        handleMultiSelectPabrik();
        handleTambahRow();
        handleDeleteRow();
        handleDataTable();
        handleDataTableModal();
        handleModalHarga();
        handleRefreshHarga();
        // handleDatePickers();
        handleTabClicked();

        handleIsSale();
        handleIsIdentitas();
        initForm();
        // handleUpdateItemSatuan();
        // handleTes();
        // handleErrorAfterSubmit();
    };

}(mb.app.item.add));


// initialize  mb.app.users.add
$(function(){
    mb.app.item.add.init();
});