mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl              = '',
        $form                   = $('#form_pembayaran'),
        $tableAddAccount        = $('#table_daftar_tindakan', $form),
        $tableAddAccountTitipan = $('#table_daftar_obat', $form),
        $tableAccountSearch     = $('#table_account_search'),
        $tableItemSearch        = $('#table_item_search'),
        $tableItemSearchTitipan = $('#table_item_search_tindakan'),
        $tablePilihPasien       = $('#table_pilih_pasien'),
        $tableInformation       = $('#table_information'),
        $popoverItemContent     = $('#popover_item_content'),
        $popoverItemContentTindakan     = $('#popover_item_content_tindakan'),
        $lastPopoverClient = null,
        $popoverPasienContent   = $('#popover_pasien_content'), 
        $lastPopoverItem        = null,
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        tplItemAccRow           = $.validator.format( $('#tpl_item_acc_row').text() ),
        tplFormPayment          = '<li class="fieldset">' + $('#tpl-form-payment', $form).val() + '<hr></li>',
        regExpTpl               = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        paymentCounter          = 0,

        itemCounter             = 9
        
        ;

    var forms = {
        'payment' : {            
            section  : $('#section-payment', $form),
            template : $.validator.format( tplFormPayment.replace(regExpTpl, '_ID_{0}') ), //ubah ke format template jquery validator
            counter  : function(){ paymentCounter++; return paymentCounter-1; }
        }      
    };

    var initForm = function(){


        var 
            $btnSearchAccount        = $('.search-account', $tableAddAccount),
            $btnSearchAccountTitipan  = $('.search-account-titipan', $tableAddAccountTitipan),
            $btnDeletes              = $('.del-this', $tableAddAccount);
            $btnDeletestitipan       = $('.del-this-plus', $tableAddAccountTitipan);

        handleBtnSearchAccount($btnSearchAccount);  

        handleBtnSearchAccountTitipan($btnSearchAccountTitipan);  


        $.each(forms, function(idx, form){

            // handle button add
            $('a.add-payment', form.section).on('click', function(){
                addFieldset(form);
            });

            // beri satu fieldset kosong
            addFieldset(form);

        });

        
        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        $.each($btnDeletestitipan, function(idx, btn){
            handleBtnDeleteTitipan( $(btn) );
        });

        ////////////////////////////
        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);

        // tambah 1 row kosong pertama
        addItemAccRow();
        addItemRow();

        // $('.row_plus', $tableAddAccount).hide();
        // $('.row_plus', $tableAddAccountTitipan).hide();

        // $popoverItemContent.hide();

        ////////////////////////////////////

        $('input[name$="[jumlah]"]', $tableAddAccount).on('keyup', function(){
            calculateTotal();
        });

        $('input[name$="[jumlah]"]', $tableAddAccount).on('change', function(){
            calculateTotal();
        });

        calculateTotal();

        //////////////////////////////////////////////////////////////////////////////////

        $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTitipan).on('keyup', function(){
            calculateTotalTitipan();
        });

        $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTitipan).on('change', function(){
            calculateTotalTitipan();
        });

        calculateTotalTitipan();

        //////////////////////////////////////////////////////////////////////////////
        
        $('input[name$="[bayar]"]', $form).on('keyup', function(){
            calculateTotalKeseluruhan();
        });

        $('input[name$="[bayar]"]', $form).on('change', function(){
            calculateTotalKeseluruhan();
        });

        calculateTotalKeseluruhan();

        ///////////////////////////////////////////////////////////////////////////////////

        $('input[name$="[grand_total_klaim]"]', $form).on('keyup', function(){
            calculateTotalSubTotal();
        });

        $('input[name$="[grand_total_klaim]"]', $form).on('change', function(){
            calculateTotalSubTotal();
        });

        calculateTotalSubTotal();

        ////////////////////////////////////////////////////////////////////////////////

        $('inputpayment[_ID_0][nominal]', forms).on('change', function(){
            calculatenominal();
        });

        calculatenominal();

        handleSelectPasienTdkTerdaftar();

        $('a.list_jenis_kartu').click(function(){
                var id = $(this).data('id'),
                    text = $(this).data('text');

            $('button#button_jenis_kartu').html(text + ' <i class="fa fa-angle-down"></i>');
            $('input#tipe_kartu').val(id);
            $('input#tipe_kartu').attr('value',id);
            $('input#no_member').attr('placeholder','Isi '+text);
        });

        $('a#btn_inv_umum').on('click', function(){
            $('a#btn_inv_bpjs').removeClass('btn-primary');
            $('a#btn_inv_bpjs').addClass('btn-default');

            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('div#invoice-umum').removeClass('hidden');
            $('div#invoice-bpjs').addClass('hidden');
        });

        $('a#btn_inv_bpjs').on('click', function(){
            $('a#btn_inv_umum').removeClass('btn-primary');
            $('a#btn_inv_umum').addClass('btn-default');

            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('div#invoice-umum').addClass('hidden');
            $('div#invoice-bpjs').removeClass('hidden');
        });

        handleTombolAntrian();


    };

    var handleTombolAntrian = function(){

        var i = 0;

        $('a#tombol_panggil').click(function(){

          i = parseInt(i)+1;

          $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_antrian',
            data     : {antrian_id : $('input#antrian_id').val(), counter: i},
            dataType : 'json',
            success  : function( results ) {

              if(results.success == true){
                $('div#counter_panggil').text($('input#nama_pasien').val() +'| ' +i+' Kali');

                if(i == 3){
                  $('div#div_lewat').removeClass('hidden');
                  $('div#div_tindak').removeClass('hidden');
                  i = 0;
                }
              }
              
                       
            },
            complete : function (transport) {
                
            }
          });

            
        });

        $('a#tombol_tindak').click(function(){
            i = 0;

            $.ajax({
              type     : 'POST',
              url      : baseAppUrl + 'tindak_antrian',
              data     : {antrian_id : $('input#antrian_id').val(), counter: i},
              dataType : 'json',
              success  : function( results ) {

                if(results.success == true){
                  $('div#counter_panggil').text('');

                  $('div#div_lewat').addClass('hidden');

                  location.reload();
                }else{
                    mb.showToast('error','Pasien Belum Dipanggil','Gagal');
                    oTable.api().ajax.url(baseAppUrl + 'listing').load();
                }
                
                         
              },
              complete : function (transport) {
                  
              }
            });

            
            
        });

        $('a#tombol_lewat').click(function(){
            i = 0;

            $.ajax({
              type     : 'POST',
              url      : baseAppUrl + 'lewati_antrian',
              data     : {antrian_id : $('input#antrian_id').val(), counter: i},
              dataType : 'json',
              success  : function( results ) {

                if(results.success == true){
                  $('div#counter_panggil').text('');

                  $('div#div_lewat').addClass('hidden');
                  $('div#div_tindak').removeClass('hidden');
                  location.reload();
                }else{
                    mb.showToast('error','Pasien Belum Dipanggil','Gagal');
                    oTable.api().ajax.url(baseAppUrl + 'listing').load();
                }
                
                         
              },
              complete : function (transport) {
                  
              }
            }); 
        });
    }


     var addFieldset = function(form){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer)
            ;

        handleDatePickers();
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
            totalSemua = grand_total + grand_total_klaim;
            totalBayar = cash - totalSemua ;
            
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

        calculateTotal();
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
                { 'name':'pasien.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.tanggal_lahir tanggal_lahir','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#table_pilih_pasien_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_pasien_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown


        $tablePilihPasien.on('draw.dt', function (){
            var $btnSelect = $('a.select-pasien', this);
            handlePilihPasienSelect( $btnSelect );
            // handleSelectPasien();
        

            
        } );

        $popoverPasienContent.hide();        
    };

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

    var handleSelectPasien = function(){
        $('a.select-pasien', this).on('click', function(){
            alert('klik');
            var id   = $(this).val(),
                pasien_id = $('input#id_ref_pasien').val();

            oTable_transaksi.api().ajax.url(baseAppUrl + 'listing_daftar_tindakan/' + pasien_id).load();
            
        });
    }

    var handlePilihPasienSelect = function($btn){
        $btn.on('click', function(e){
            // alert('klik');
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefPasien  = $('input[name="nama_ref_pasien"]'),
                $cabang_id      = $('input[name="cabang_id"]'),
                $IdRefPasien    = $('input[name="id_ref_pasien"]'),
                $pasien_id      = $('input[name="pasien_id"]'),
                $nama           = $('label[name="nama"]'),
                $alamat         = $('label[name="alamat"]'),
                $gender         = $('label[name="gender"]'),
                $umur           = $('label[name="umur"]'),
                $no_tlp         = $('label[name="no_tlp"]'),
                $IdRefPasien    = $('input[name="id_ref_pasien"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null
                ;        

            $('.pilih-pasien', $form).popover('hide');          
            $('div#grup_pasien', $form).removeClass('hidden');
            // console.log($itemIdEl)
            $IdRefPasien.val($(this).data('item').id);
            $namaRefPasien.val($(this).data('item').no_ktp);
            $cabang_id.val($(this).data('item').cabang_id);
            $pasien_id.val($(this).data('item').id);
            $nama.text($(this).data('item').nama);
            $alamat.text($(this).data('item').alamat);
            if($(this).data('item').gender == 'L')
            {
                gender = 'Laki - Laki';
            }
            else
            {
                gender = "Perempuan";
            }
            $gender.text(gender);
            $umur.text(parseInt($(this).data('item').usia/365) + ' Tahun');
            $no_tlp.text($(this).data('item').nomor);

            var pasien_id = $IdRefPasien.val();
            // alert(pasien_id);
            
            // oTable_transaksi.api().ajax.url(baseAppUrl + 'listing_daftar_tindakan/' + pasien_id).load();
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_data_invoice',
                data     : {pasien_id: pasien_id},
                beforeSend  : function( results ) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },success  : function( results ) {
                    $('tbody#table-content').html(results);
                    var $table = $('tbody#table-content'),
                        $total = $('input[name$="[total_invoice]"]', $table);

                    sisa_hutang = 0;
                    $.each($total, function(idx, tot){
                        var hutang = parseInt($(this).val());

                        sisa_hutang = sisa_hutang + hutang;
                    });

                    $('input#subtotal').val(mb.formatTanpaRp(0));
                    $('input#subtotal_hidden').val(0);
                    $('input#grand_total').val(mb.formatTanpaRp(0));
                    $('input#grand_total_hidden').val(0);
                    $('input#sisa_hutang').val(mb.formatTanpaRp(sisa_hutang));
                    $('input#sisa_hutang_hidden').val(sisa_hutang);

                    $('input[name="select_invoice"]', $table).on('change', function(){
                        var harga = $(this).data('rp'),
                            index = $(this).data('index');

                        if($(this).prop('checked') == true){
                            $('input#subtotal').val(mb.formatTanpaRp(harga));
                            $('input#subtotal_hidden').val(harga);
                            $('input#grand_total').val(mb.formatTanpaRp(harga));
                            $('input#grand_total_hidden').val(harga);
                            $('input#sisa_hutang').val(mb.formatTanpaRp(0));
                            $('input#sisa_hutang_hidden').val(sisa_hutang-harga);
                            $('input.selected_radio').val(0);
                            $('input.selected_radio').attr('value',0);
                            $('input#invoice_select_'+index).val(1);
                            $('input#invoice_select_'+index).attr('value',1);
                        }
                    });



                    
                },complete : function(results){
                    Metronic.unblockUI();
                }
            });


            var $SideNamaPasien      = $('div#side_nama_pasien'),
                $SideUmurPasien      = $('div#side_umur_pasien'),
                $SideTransaksiPasien = $('div#side_transaksi_pasien'),
                $SideTagihanPasien   = $('div#side_tagihan_pasien'),
                $SideUploadPasien    = $('div#side_upload_pasien'),
                $SideTentangPasien   = $('h4#side_tentang_pasien'),
                $SideKeteraganPasien = $('span#side_keterangan_pasien'),
                $SidePhotoPasien     = $('img#side_img_pasien'),
                $SideAlamat          = $('label.side_alamat'),
                $SideGender          = $('label.side_gender'),
                $SideTglReg          = $('label.side_tgl_registrasi'),
                $SideTtl             = $('label.side_ttl'),
                $SideTlp             = $('label.side_tlp');

            $('.pilih-pasien', $form).popover('hide');     
            $('.tentang_pasien').show();

            $SideTransaksiPasien.html($(this).data('transaksi'));
            $SideTagihanPasien.html($(this).data('tagihan'));

            // menghitung perbedaan tahun, utk kebutuhan umur
            var today = new Date();
            var birthDate = new Date($(this).data('item').tanggal_lahir);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            // tempat tanggal lahir
            $tempat = $(this).data('item').tempat_lahir;
            $tgl    = $(this).data('item').tanggal_lahir;
            $ttl    = $tempat + ", " +  $tgl;
            
            // alert($(this).data('item').url_photo);
            $SideNamaPasien.html($(this).data('item').nama);
            ($(this).data('item').url_photo != null) ? $SidePhotoPasien.attr("src", $(this).data('item').url_photo) : $SidePhotoPasien.attr("src", "");
          
            $SideTentangPasien.html("Tentang " + $(this).data('item').nama);
            $SideAlamat.text($(this).data('item').alamat + ' Kec. ' + $(this).data('item').kecamatan + ' Kel. ' + $(this).data('item').kelurahan + " Kota " + $(this).data('item').kota);
            ($(this).data('item').gender == 'P') ? $SideGender.text("Perempuan") : $SideGender.text("Laki-laki");
            $SideTglReg.text($(this).data('item').tanggal_registrasi);
            $SideTtl.text($ttl);
            $SideTlp.text($(this).data('item').nomor);

            e.preventDefault();
        });     
    };

    var handleSelectPasienTdkTerdaftar = function(){
        $('select#pasien_option').on('change', function(){

            var nama_pasien = $(this).val(),
                tanggal = $('input#tanggal').val(),
               $SideNamaPasien      = $('div#side_nama_pasien'),
               $SideTentangPasien = $('h4#side_tentang_pasien');

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_data_invoice_nama',
                data     : {nama_pasien: nama_pasien, tanggal:tanggal},
                beforeSend  : function( results ) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },success  : function( results ) {
                    $('tbody#table-content').html(results);
                    var $table = $('tbody#table-content'),
                        $total = $('input[name$="[total_invoice]"]', $table);

                    sisa_hutang = 0;
                    $.each($total, function(idx, tot){
                        var hutang = parseInt($(this).val());

                        sisa_hutang = sisa_hutang + hutang;
                    });

                    $('input#subtotal').val(mb.formatTanpaRp(0));
                    $('input#subtotal_hidden').val(0);
                    $('input#grand_total').val(mb.formatTanpaRp(0));
                    $('input#grand_total_hidden').val(0);
                    $('input#sisa_hutang').val(mb.formatTanpaRp(sisa_hutang));
                    $('input#sisa_hutang_hidden').val(sisa_hutang);

                    $('input[name="select_invoice"]', $table).on('change', function(){
                        var harga = $(this).data('rp'),
                            index = $(this).data('index');

                        if($(this).prop('checked') == true){
                            $('input#subtotal').val(mb.formatTanpaRp(harga));
                            $('input#subtotal_hidden').val(harga);
                            $('input#grand_total').val(mb.formatTanpaRp(harga));
                            $('input#grand_total_hidden').val(harga);
                            $('input#sisa_hutang').val(mb.formatTanpaRp(0));
                            $('input#sisa_hutang_hidden').val(sisa_hutang-harga);
                            $('input.selected_radio').val(0);
                            $('input.selected_radio').attr('value',0);
                            $('input#invoice_select_'+index).val(1);
                            $('input#invoice_select_'+index).attr('value',1);
                        }
                    });

                    $SideNamaPasien.html(nama_pasien);
                    $SideTentangPasien.html("Tentang " + nama_pasien);
                },complete : function(results){
                    Metronic.unblockUI();
                }
            });

        });
    }
    var calculatenominal = function()
    {
        $('input#payment[_ID_0][nominal]').on('change', function(){
            // alert('klik');
                
            // var grand_total_klaim = parseInt($(this).val());
            var nominal = parseInt($('input[name="nominal"]').val());
            // var grand_total = parseInt($('input#grand_total_hidden').val());
                // alert(cash);

            bayar = nominal;
            
            $('input#bayar').val(mb.formatTanpaRp(bayar));
            $('input#bayar_hidden').val(bayar);

            if (!isNaN(bayar)){

            $('input#bayar').val(mb.formatTanpaRp(bayar));
            $('input#bayar_hidden').val(bayar);
            
            } else {

            $('input#bayar').val(0);
            $('input#bayar_hidden').val(0);

            }

            // var cash = parseInt($(this).val());
            // alert(cash);
            // alert('oii');

        });
    }

    var calculateTotalSubTotal = function(){

        // var nominal = $('input[name$="[nominal]"]');

            // var totalBayar = 0;
            $('input#bayar').on('change', function(){
            // alert('klik');
                
                // var grand_total_klaim = parseInt($(this).val());
                var grand_total_klaim = parseInt($('input#grand_total_klaim_hidden').val());
                var grand_total = parseInt($('input#grand_total_hidden').val());

                    // alert(cash);

                subtotal = grand_total_klaim + grand_total;
                
                $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                $('input#subtotal_hidden').val(subtotal);

                if (!isNaN(subtotal)){

                $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                $('input#subtotal_hidden').val(subtotal);
                
                } else {

                $('input#subtotal').val(0);
                $('input#subtotal_hidden').val(0);

                }

                // var cash = parseInt($(this).val());
                // alert(cash);
                // alert('oii');

            });
    }

    var calculateTotalKeseluruhan = function(){

        // var nominal = $('input[name$="[nominal]"]');

            // var totalBayar = 0;
            $('input#bayar').on('change', function(){
            // alert('klik');
                // alert('kembali');
                var cash = parseInt($(this).val());
                var grand_total_klaim = parseInt($('input#grand_total_klaim_hidden').val());
                var grand_total = parseInt($('input#grand_total_hidden').val());

                    // alert(cash);
                totalAll = grand_total + grand_total_klaim;

                alert(totalAll);
                totalBayar = cash - totalAll ;
                
                $('input#kembali').val(mb.formatTanpaRp(totalBayar));
                $('input#kembali_hidden').val(totalBayar);

                if (!isNaN(totalBayar)){

                $('input#kembali').val(mb.formatTanpaRp(totalBayar));
                $('input#kembali_hidden').val(totalBayar);
                
                } else {

                $('input#kembali').val(0);
                $('input#kembali_hidden').val(0);

                }

                // var cash = parseInt($(this).val());
                // alert(cash);
                // alert('oii');

            });
    }


    var calculateTotal = function(){
        // alert('masuk function');
        var 
            $rows     = $('tbody>tr', $tableAddAccount), 
            totalCost = 0,
            total_alat_obat = 0;

        $.each($rows, function(idx, row){
            var 
                $row     = $(row), 
                itemCode = $('label[name$="[kode]"]', $row).text(),
                harga = $('input[name$="[harga]"]', $row).val(),
                jumlah     = $('input[name$="[jumlah]"]', $row).val()*1
                ;
                // alert(itemCode);

            if (itemCode != '' ){
                totalCost = harga*jumlah;
                
                $('label[name$="[sub_total]"]', $row).text(mb.formatTanpaRp(totalCost));
                $('input[name$="[sub_total]"]', $row).val(totalCost);

                 total_alat_obat = total_alat_obat + totalCost;
                $('input#total_bayar').val(mb.formatTanpaRp(total_alat_obat));
                $('input#total_bayar_hidden').val(total_alat_obat);

            }
            // alert(totalCost);

        });

        // $('#total_before_discount_hidden').val(totalCost);
    };
    
    var calculateTotalTitipan = function(){
        // alert('masuk function');
        var 
            $rows     = $('tbody>tr', $tableAddAccountTitipan), 
            totalCost = 0,
            total_tindakan = 0;

        $.each($rows, function(idx, row){
            var 
                $row     = $(row), 
                itemCode = $('label[name$="[kode_titipan]"]', $row).text(),
                harga    = $('input[name$="[harga_tindakan]"]', $row).val(),
                jumlah   = $('input[name$="[jumlah_tindakan]"]', $row).val()*1
                ;
                // alert(itemCode);

            if (itemCode != 0 ){
                // totalCost += harga*jumlah;
                totalCost = harga*jumlah;

                // alert(totalCost);
                
                $('label[name$="[sub_total_titipan]"]', $row).text(mb.formatTanpaRp(totalCost));
                $('input[name$="[sub_total_tindakan]"]', $row).val(totalCost);
            
                total_tindakan = total_tindakan + totalCost;
                $('input#total_tindakan').val(mb.formatTanpaRp(total_tindakan));
                $('input#total_tindakan_hidden').val(total_tindakan);


            }
            // alert(totalCost);

        });

        // $('#total_before_discount_hidden').val(totalCost);
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

        //

        $('input[name$="[jumlah]"]', $tableAddAccount).on('keyup', function(){
            calculateTotal();
            // calculateTotalAdisc();
        });

        $('input[name$="[jumlah]"]', $tableAddAccount).on('change', function(){
            calculateTotal();
            // calculateTotalAdisc();
        });

         $('input[name$="[biaya_tambahan]"]', $form).on('keyup', function(){
            calculateTotalKeseluruhan();
        });

        $('input[name$="[biaya_tambahan]"]', $form).on('change', function(){
            calculateTotalKeseluruhan();
        });

        // $('.type', $newItemRow).bootstrapSwitch();
        // handleBootstrapSelectType($('.type', $newItemRow));
    };

    var addItemAccRow = function(){

        var numRow = $('tbody tr', $tableAddAccountTitipan).length;
        var 
            $rowContainer         = $('tbody', $tableAddAccountTitipan),
            $newItemRow           = $(tplItemAccRow(itemCounter++)).appendTo( $rowContainer ),
            // $btnAddAccount  = $('.add_row', $newItemRow),
            $btnSearchAccountTitipan  = $('.search-account-titipan', $newItemRow)

            ;

        // handle delete btn
        handleBtnDeleteTitipan( $('.del-this-plus', $newItemRow) );
      

        // handle button search item
        // handleAddAcc($btnAddAccount);

        handleBtnSearchAccountTitipan($btnSearchAccountTitipan);

        ///////////////////////

         $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTitipan).on('keyup', function(){
            calculateTotalTitipan();
        });

        $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTitipan).on('change', function(){
            calculateTotalTitipan();
        });

         $('input[name$="[biaya_tambahan]"]', $form).on('keyup', function(){
            calculateTotalKeseluruhan();
        });

        $('input[name$="[biaya_tambahan]"]', $form).on('change', function(){
            calculateTotalKeseluruhan();
        });


        // $('.type', $newItemRow).bootstrapSwitch();
        // handleBootstrapSelectType($('.type', $newItemRow));

    };
    

    var handleDataTableItems = function(){
       oTable = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            // 'sAjaxSource'              : baseAppUrl + 'listing_alat_obat',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_alat_obat/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

       

       $( '#select_so_history').on( 'change', function () {
            // alert($(this).val());
            //$tableCabangHistory.fnFilter( this.value, 4);
            var cabang_id = $('select#tipe_transaksi').val();
            oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/'+ cabang_id +'/'+$(this).val()).load();
          // alert($(this).val());
        });
            // oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/' + id).load();

        $tableItemSearch.on('draw.dt', function (){
            
            var $btnSelect = $('a.select', this);
            
            handleAccountSelect( $btnSelect );


            
        });
            
            $popoverItemContent.hide();        

    };

    var handleDataTableInvoice = function(){
       oTableInvoice = $('table#table_invoice').dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            // 'sAjaxSource'              : baseAppUrl + 'listing_alat_obat',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_invoice/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'name' : 'invoice.created_date tanggal', 'searchable': true, 'orderable': true },
                { 'visible' : true, 'name' : 'invoice.no_invoice no_invoice','searchable': false, 'orderable': true },
                { 'visible' : true, 'name' : 'invoice.nama_penjamin nama_penjamin','searchable': false, 'orderable': true },
                { 'visible' : true, 'name' : 'invoice.nama_pasien nama_pasien','searchable': false, 'orderable': true },
                { 'visible' : true, 'name' : 'invoice.harga harga','searchable': false, 'orderable': true },
                { 'visible' : true, 'name' : 'invoice.id id','searchable': false, 'orderable': false },
                ]
        });       

    };

    var handleDataTableItemsTitipan = function(){
        oTable2 = $tableItemSearchTitipan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_tindakan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_item_search_titipan_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_search_titipan_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableItemSearchTitipan.on('draw.dt', function (){
            var $btnSelect = $('a.select-tindakan', this);
            handleAccountTitipanSelect( $btnSelect );

            var grandtotal_tindakan =  $('input[name="total_tindakan"]', this).val();
            // var grandtotal_credit =  $('input[name="grandtotal_credit"]', this).val();

            $('input[name$="[jumlah_tindakan]"]', this).on('change keyup', function(){
                var total_tindakan = 0;
                var $subtotal_tindakan = $('label[name$="[sub_total_titipan]"]', $tableAddAccountTitipan);

                $.each($subtotal_tindakan, function(){
                    total_tindakan = subtotal_tindakan + parseInt($(this).val());
                });

        

                $('#total_tindakan').val(mb.formatTanpaRp(parseInt(total_tindakan)));
                
                
            });
            
        } );

        $popoverItemContentTindakan.hide();        
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
                $itemHargaEl = null,
                $itemQtyEl  = $('input[name$="[name]"]', $row)
                ;                
            // console.log(itemTarget);
           
                $itemIdEl       = $('input[name$="[account_id]"]', $row);
                $itemCodeEl     = $('label[name$="[kode]"]', $row);
                $itemCodeIn     = $('input[name$="[kode]"]', $row);
                $itemNameEl     = $('label[name$="[nama]"]', $row);
                $itemNameIn     = $('input[name$="[nama]"]', $row);
                $itemHargaEl    = $('label[name$="[harga]"]', $row);
                $itemHargaIn    = $('input[name$="[harga]"]', $row);
                $('.search-account', $tableAddAccount).popover('hide');
           
            $itemIdEl.val($(this).data('item')['id']);
            $itemCodeEl.text($(this).data('item')['kode']);
            $itemCodeIn.val($(this).data('item')['kode']);
            $itemNameEl.text($(this).data('item')['nama']);
            $itemNameIn.val($(this).data('item')['nama']);
            $itemHargaEl .text(mb.formatTanpaRp(parseInt($(this).data('item')['harga'])));
            $itemHargaIn .val($(this).data('item')['harga']);
            
            addItemRow();
            calculateTotal();
            e.preventDefault();   
        });     
    };

    var handleAccountTitipanSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');SS
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableAddAccountTitipan),
                $itemKodeEl = null,
                $itemNamaEl = null, 
                $itemHargaEl = null, 
                $itemSubTotalEl = null 
                // $itemQtyEl  = $('input[name$="[name]"]', $row)
                ;                
            // console.log(itemTarget);
           
                $itemidEl = $('input[name$="[tindakan_id]"]', $row);
                $itemKodeEl = $('label[name$="[kode_titipan]"]', $row);
                $itemNamaEl = $('label[name$="[nama_titipan]"]', $row);
                $itemHargaEl = $('label[name$="[harga_titipan]"]', $row);
                $itemHargaIn = $('input[name$="[harga_tindakan]"]', $row);
                $('.search-account-titipan', $tableAddAccountTitipan).popover('hide');
           
            $itemidEl.val($(this).data('item')['id']);
            $itemKodeEl.text($(this).data('item')['kode']);
            $itemNamaEl.text($(this).data('item')['nama']);
            $itemHargaEl.text(mb.formatTanpaRp(parseInt($(this).data('item')['harga'])));
            $itemHargaIn.val($(this).data('item')['harga']);
            
            addItemAccRow();
            calculateTotalTitipan();
            e.preventDefault();   
        });     
    };


    var handleBtnDelete = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableAddAccount)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableAddAccount).length == 0){
                addItemRow();
                // addItemAccRow();
            }
            e.preventDefault();
        });

        
    };

    var handleBtnDeleteTitipan = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableAddAccountTitipan)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableAddAccountTitipan).length == 0){
                // addItemRow();
                addItemAccRow();
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

    var handleBtnSearchAccountTitipan = function($btn){
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

            $popoverItemContentTindakan.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContentTindakan ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContentTindakan);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverItemContentTindakan ke .page-content
            $popoverItemContentTindakan.hide();
            $popoverItemContentTindakan.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
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


    var handleSelectCabang = function(){
        $('select#tipe_transaksi').on('change', function(){
            var id   = $(this).val(),
                poli = $('#poli').val('');

            var $poli = $('#poli');
            

            oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/' + id).load();
         
           ///////////////multi-select poliklinik////////////////////
         $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_cabang_id',
                data     : {id: id},
                dataType : 'json',
                success  : function( results ) {
                    $poli.empty();
                   
                    $.each(results, function(key, value) {
                        $poli.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $poli.val('');

                    });
                }
            });
            
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

    var handleDataTableDaftarTindakan = function(){
        // id = $('input#id_ref_pasien').val();
       oTable_transaksi = $tableAddAccount.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_daftar_tindakan/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'filter'                : false,
            'paging'                : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });       
        $('#table_daftar_tindakan_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); 
        $('#table_daftar_tindakan_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); 

        oTable_transaksi.on('draw.dt', function (){

            var $colClients = $('.pop-detail', this);

            $.each($colClients, function(idx, colClient){
                var
                    $colClient = $(colClient),
                    clientData = $colClient.data('detail');

                // console.log(clientData);
                $colClient.popover({
                    html : true,
                    container : 'body',
                    placement : 'bottom',
                    content: function(){
                        return clientData;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '280px', maxWidth: '720px'});
                    if ($lastPopoverClient !== null) $lastPopoverClient.popover('hide');
                    $lastPopoverClient = $colClient;
                }).on('hide.bs.popover', function(){
                    $lastPopoverClient = null;
                }).on('click', function(e){

                });
            });

            // alert('A');
            $('input.checkboxes', this).uniform();
            var subtotal            = 0,
                total                   = 0,
                total_klaim             = 0,
                totalBayar              = 0;
            $('input.checkboxes', this).on('click', function(){

                var klaim = $(this).data('klaim');

                // checked = $(this).prop('checked');
                if($(this).prop('checked') == true)
                {
                    

                    // alert('checked');
                    // console.log($(this).parent());
                    $(this).parent().addClass('checked');
                    if(klaim == 'Swasta') 
                    {
                        var rp      = $(this).data('rp');
                        var rpInt   = parseInt(rp);

                        // alert(rpInt);

                        total = total + rpInt;                
                        $('input#grand_total').val(mb.formatTanpaRp(total));
                        $('input#grand_total_hidden').val(total);

                        // $(this).parents('tr').toggleClass("active");
                        // $(this).attr("checked", false);
                    
                    } else {

                        var rp      = $(this).data('rp');
                        var rpInt   = parseInt(rp);

                        // alert(rpInt);

                        total_klaim = total_klaim + rpInt;                
                        $('input#grand_total_klaim').val(mb.formatTanpaRp(total_klaim));
                        $('input#grand_total_klaim_hidden').val(total_klaim);

                        // $(this).parents('tr').toggleClass("active");
                        // $(this).attr("checked", false);
                    }

                    // var cash = parseInt($(this).val());
                    var grand_total_klaim = parseInt($('input#grand_total_klaim_hidden').val());
                    var grand_total = parseInt($('input#grand_total_hidden').val());

                    // alert(grand_total_klaim);


                    subtotal = grand_total + grand_total_klaim;
                    
                    $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                    $('input#subtotal_hidden').val(subtotal);
                    $('label#subtotal_label').text(mb.formatRp(subtotal));

                    if (!isNaN(subtotal)){

                    $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                    $('input#subtotal_hidden').val(subtotal);
                    $('label#subtotal_label').text(mb.formatTanpaRp(subtotal));
                    $('div#side_umur_pasien').text(mb.formatRp(subtotal));
                    
                    } 
                    else 
                    {

                        $('input#subtotal').val(0);
                        $('input#subtotal_hidden').val(0);
                        $('label#subtotal_label').text(mb.formatTanpaRp(0));
                        $('div#side_umur_pasien').text(mb.formatTanpaRp(0));

                    }
                }else{
                    // alert('Unchecked');

                    if(klaim == 'Swasta') 
                    {
                        var rp      = $(this).data('rp');
                        var rpInt   = parseInt(rp);

                        // alert(rpInt);

                        total = total - rpInt;                
                        $('input#grand_total').val(mb.formatTanpaRp(total));
                        $('input#grand_total_hidden').val(total);

                        // $(this).parents('tr').toggleClass("active");
                        // $(this).attr("checked", false);
                    
                    } else {

                        var rp      = $(this).data('rp');
                        var rpInt   = parseInt(rp);

                        // alert(rpInt);

                        total_klaim = total_klaim - rpInt;                
                        $('input#grand_total_klaim').val(mb.formatTanpaRp(total_klaim));
                        $('input#grand_total_klaim_hidden').val(total_klaim);

                        // $(this).parents('tr').toggleClass("active");
                        // $(this).attr("checked", false);
                    }

                    // var cash = parseInt($(this).val());
                    var grand_total_klaim = parseInt($('input#grand_total_klaim_hidden').val());
                    var grand_total = parseInt($('input#grand_total_hidden').val());

                    // alert(grand_total_klaim);


                    subtotal = grand_total + grand_total_klaim;
                    
                    $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                    $('input#subtotal_hidden').val(subtotal);
                    $('label#subtotal_label').text(mb.formatRp(subtotal));

                    if (!isNaN(subtotal)){

                    $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                    $('input#subtotal_hidden').val(subtotal);
                    $('label#subtotal_label').text(mb.formatTanpaRp(subtotal));
                    $('div#side_umur_pasien').text(mb.formatRp(subtotal));
                    
                    } 
                    else 
                    {

                        $('input#subtotal').val(0);
                        $('input#subtotal_hidden').val(0);
                        $('label#subtotal_label').text(mb.formatTanpaRp(0));
                        $('div#side_umur_pasien').text(mb.formatTanpaRp(0));

                    }
                }
               
            });
            
        });
    };

    var handleDataTableDaftarObat = function(){
        // id = $('input#paket_id').val();
       oTable2 = $tableAddAccountTitipan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            // 'sAjaxSource'              : baseAppUrl + 'listing_alat_obat',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_daftar_obat',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });       
        $('#table_daftar_obat_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_daftar_obat_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

                $tableAddAccountTitipan.on('draw.dt', function (){
                });
            
    };

    jQuery('#table_daftar_tindakan .group-checkable').change(function () {
        var subtotal                = 0,
        total                   = 0,
        total_klaim             = 0,
        totalBayar              = 0;
        // if (this.checked) {
        //     alert(total);
        //     // $("#chkBox").attr('checked', false); 
        //     $('input#grand_total_klaim_hidden').val(0);
        //     $('input#grand_total_hidden').val(0)
        // };
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
            jQuery.uniform.update(set);
            // alert(klaim);
            jQuery(set).each(function () {
            var klaim = $(this).data('klaim');
            if (checked) 
            {
                $(this).parent().addClass('checked');
                // alert(klaim);
                if(klaim == 'Swasta') {
                    var rp      = $(this).data('rp');
                    var rpInt   = parseInt(rp);

                    
                    total = total + rpInt;                
                    // alert(total);
                    $('input#grand_total').val(mb.formatTanpaRp(total));
                    $('input#grand_total_hidden').val(total);

                    // $(this).parents('tr').toggleClass("active");
                    // $(this).attr("checked", false);
                
                } else {

                    var rp      = $(this).data('rp');
                    var rpInt   = parseInt(rp);

                    // alert(rpInt);

                    total_klaim = total_klaim + rpInt;                
                    $('input#grand_total_klaim').val(mb.formatTanpaRp(total_klaim));
                    $('input#grand_total_klaim_hidden').val(total_klaim);

                    // $(this).parents('tr').toggleClass("active");
                    // $(this).attr("checked", false);
                }

                // var cash = parseInt($(this).val());
                var grand_total_klaim = parseInt($('input#grand_total_klaim_hidden').val());
                var grand_total = parseInt($('input#grand_total_hidden').val());

                // alert(grand_total_klaim);


                subtotal = grand_total + grand_total_klaim;
                
                $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                $('input#subtotal_hidden').val(subtotal);
                $('label#subtotal_label').text(mb.formatRp(subtotal));

                if (!isNaN(subtotal)){

                $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                $('input#subtotal_hidden').val(subtotal);
                $('label#subtotal_label').text(mb.formatTanpaRp(subtotal));
                $('div#side_umur_pasien').text(mb.formatRp(subtotal));
                
                } else {

                $('input#subtotal').val(0);
                $('input#subtotal_hidden').val(0);
                $('label#subtotal_label').text(mb.formatTanpaRp(0));
                $('div#side_umur_pasien').text(mb.formatTanpaRp(0));

                }

            } else {
                // $(this).prop('checked', false);
                $(this).parent().removeClass('checked');
                if(klaim == 'Swasta') {

                    var rp      = $(this).data('rp');
                    var rpInt   = parseInt(rp);

                    // alert(rpInt);

                    // total = total - rpInt;                
                    $('input#grand_total').val(mb.formatTanpaRp(total));
                    $('input#grand_total_hidden').val(total);

                    // $(this).attr("checked", true);
                    // $(this).parents('tr').toggleClass("active");
                
                } else {

                    var rp      = $(this).data('rp');
                    var rpInt   = parseInt(rp);

                    // total_klaim = total_klaim - rpInt;                
                    $('input#grand_total_klaim').val(mb.formatTanpaRp(total_klaim));
                    $('input#grand_total_klaim_hidden').val(total_klaim);

                    // $(this).attr("checked", true);
                    // $(this).parents('tr').toggleClass("active");
                }

                var grand_total_klaim = parseInt($('input#grand_total_klaim_hidden').val());
                var grand_total = parseInt($('input#grand_total_hidden').val());

                    // alert(cash);

                subtotal = grand_total_klaim + grand_total;
                
                $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                $('input#subtotal_hidden').val(subtotal);
                $('label#subtotal_label').text(mb.formatTanpaRp(subtotal));

                if (!isNaN(subtotal)){

                $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                $('input#subtotal_hidden').val(subtotal);
                $('label#subtotal_label').text(mb.formatTanpaRp(subtotal));
                
                } else {

                $('input#subtotal').val(0);
                $('input#subtotal_hidden').val(0);
                $('label#subtotal_label').text(mb.formatTanpaRp(0));

                }
            }                    
        });
    });

    var handleTerdaftar = function()
    {
        $('a#btn_terdaftar').on('click', function(){
            $('a#btn_tidak_terdaftar').removeClass('btn-primary');
            $('a#btn_tidak_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('div.pop-pasien').show();
            $('div.select-pasien').hide();

            $('div#tidak_terdaftar').addClass('hidden');
        });
    }

    var handleTidakTerdaftar = function()
    {
        $('a#btn_tidak_terdaftar').on('click', function(){
            $('a#btn_terdaftar').removeClass('btn-primary');
            $('a#btn_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('div.pop-pasien').hide();
            $('div.select-pasien').show();

            $('div#tidak_terdaftar').removeClass('hidden');
        });
    }

    var handleJwertyEnterRent = function($nopasien){
        jwerty.key('enter', function() {
            
            var NomorPasien = $nopasien.val();

            searchPasienByNomorAndFill(NomorPasien);

            // cegah ENTER supaya tidak men-trigger form submit
            return false;

        }, this, $nopasien );

    }

    var searchPasienByNomorAndFill = function(NomorPasien)
    {
        var tipe = $('input#tipe_kartu').val();

        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'search_pasien_by_nomor',
            data     : {no_pasien:NomorPasien, tipe:tipe},  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success : function(result){
                if(result.success === true)
                {
                    $noPasien       = $('input[name="no_member"]'),
                    $IdPasien       = $('input[name="id_ref_pasien"]'),
                    $NamaPasien     = $('input[name="nama_pasien"]'),
                    $AlamatPasien   = $('input[name="alamat_pasien"]'),
                    $GenderPasien   = $('input[name="gender_pasien"]'),
                    $TglLahirPasien = $('input[name="tgl_lahir_pasien"]'),
                    $NomorPasien    = $('input[name="telepon_pasien"]'),
                    $UmurPasien     = $('input[name="umur"]'),
                    $Upload         = $('a#upload');

                    var 
                        $SideNamaPasien      = $('div#side_nama_pasien'),
                        $SideUmurPasien      = $('div#side_umur_pasien'),
                        $SideTransaksiPasien = $('div#side_transaksi_pasien'),
                        $SideTagihanPasien   = $('div#side_tagihan_pasien'),
                        $SideUploadPasien    = $('div#side_upload_pasien'),
                        $SideTentangPasien   = $('h4#side_tentang_pasien'),
                        $SideKeteraganPasien = $('span#side_keterangan_pasien'),
                        $SidePhotoPasien     = $('img#side_img_pasien'),
                        $SideAlamat          = $('label.side_alamat'),
                        $SideGender          = $('label.side_gender'),
                        $SideTglReg          = $('label.side_tgl_registrasi'),
                        $SideTtl             = $('label.side_ttl'),
                        $SideTlp             = $('label.side_tlp')
                    ;

                     var data = result.rows;
                                console.log(data);

                    $('.tentang_pasien').show();

                    $IdPasien.val(data.id);
                    $SideTransaksiPasien.html(data.count_transaksi);
                    $SideTagihanPasien.html(data.count_tagihan);

                    // menghitung perbedaan tahun, utk kebutuhan umur
                    var today = new Date();
                    var birthDate = new Date(data.tanggal_lahir);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    var m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    // tempat tanggal lahir
                    $tempat = data.tempat_lahir;
                    $tgl    = data.tanggal_lahir;
                    $ttl    = $tempat + ", " +  $tgl;
                    
                    $SideNamaPasien.html(data.nama);
                    (data.url_photo != null) ? $SidePhotoPasien.attr("src", data.url_photo) : $SidePhotoPasien.attr("src", "");
                   
                    $SideTentangPasien.html("Tentang " + data.nama);
                    $SideAlamat.text(data.alamat + ' Kec. ' + data.kecamatan + ' Kel. ' + data.kelurahan + " Kota " + data.kota);
                    (data.gender == 'P') ? $SideGender.text("Perempuan") : $SideGender.text("Laki-laki");
                    $SideTglReg.text(data.tanggal_registrasi);
                    $SideTtl.text($ttl);
                    $SideTlp.text(data.nomor);
                    
                    //oTable_transaksi.api().ajax.url(baseAppUrl + 'listing_daftar_tindakan/' + data.id).load();

                    var invoice_umum        = result.invoice_umum,
                        invoice_umum_detail = result.invoice_umum_detail,
                        invoice_umum_detail_item = result.invoice_umum_detail_item,
                        invoice_bpjs        = result.invoice_bpjs,
                        invoice_bpjs_detail = result.invoice_bpjs_detail,
                        invoice_bpjs_detail_item = result.invoice_bpjs_detail_item;


                    // $('td#tgl_tindakan_umum').text(invoice_umum.created_date);
                    // $('td#nama_penanggung_umum').text(invoice_umum.nama_pasien);
                    // $('td#nama_pasien_umum').text(invoice_umum.nama_pasien);
                    // $('td#no_penganggung_umum').text('-');
                    // $('td#no_member_umum').text(NomorPasien);
                    // $('td#no_member_umum').text(NomorPasien);

                    $('p#tanggal_invoice').text('Tgl : '+invoice_umum.created_date);
                    $('p#nama_pasien').text('Pasien : '+invoice_umum.nama_pasien);
                    $('p#no_rm').text('No. RM : '+NomorPasien);
                    $('p#penjamin').text('Penjamin : '+invoice_umum.nama_pasien);
                    $('p#no_penjamin').text('No. Penjamin : -');

                    tbodyDetail = '<tr> <th colspan="2"> <b>Deskripsi</b> (Jml)</th> <td> Sub Total </td> </tr> <tr class="thick-row"> <td colspan="3" class="small-info"> <b>(Jumlah * Harga)</b> </td> </tr> <tr> <th colspan="3"> <b>Tindakan</b> </th></tr>';

                    var grandTotal = 0;

                    $.each(invoice_umum_detail, function(idx, inv_umum_det){

                        var diskon_detail = 0;
                        if(!isNaN(inv_umum_det.diskon_nominal) && (inv_umum_det.diskon_nominal != null)){
                            diskon_detail = inv_umum_det.diskon_nominal;
                        }
                        tbodyDetail += '<tr>';
                        tbodyDetail += '<td class="blank-cell"> </td>';
                        tbodyDetail += '<th class="deskripsi" style="white-space: normal !important;">'+inv_umum_det.nama_tindakan+' ('+inv_umum_det.jumlah+')</th>';
                        tbodyDetail += '<td ><b>'+mb.formatRp(parseFloat(inv_umum_det.harga_jual * inv_umum_det.jumlah))+'</b></td>';
                        tbodyDetail += '</tr>';
                        tbodyDetail += '<tr>';
                        tbodyDetail += '<td class="blank-cell"> </td>';
                        tbodyDetail += '<th class="deskripsi" style="white-space: normal !important;">Diskon</th>';
                        tbodyDetail += '<td ><b>'+mb.formatRp(parseFloat(diskon_detail))+'</b></td>';
                        tbodyDetail += '</tr>';

                        grandTotal += parseFloat((inv_umum_det.harga_jual * inv_umum_det.jumlah) - diskon_detail);
                    });

                    tbodyDetail += '<tr> <th colspan="3"> <b>Obat & Alkes</b> </th>  </tr>';

                    $.each(invoice_umum_detail_item, function(idx, inv_umum_det_item){

                        var diskon_detail = 0;
                        if(!isNaN(inv_umum_det_item.diskon_nominal) && (inv_umum_det_item.diskon_nominal != null)){
                            diskon_detail = inv_umum_det_item.diskon_nominal;
                        }

                        tbodyDetail += '<tr>';
                        tbodyDetail += '<td class="blank-cell"> </td>';
                        tbodyDetail += '<th class="deskripsi" style="white-space: normal !important;">'+inv_umum_det_item.nama_tindakan+' ('+inv_umum_det_item.jumlah+')</th>';
                        tbodyDetail += '<td ><b>'+mb.formatRp(parseFloat(inv_umum_det_item.harga_jual * inv_umum_det_item.jumlah))+'</b></td>';
                        tbodyDetail += '</tr>';
                        tbodyDetail += '<tr>';
                        tbodyDetail += '<td class="blank-cell"> </td>';
                        tbodyDetail += '<th class="deskripsi" style="white-space: normal !important;">Diskon</th>';
                        tbodyDetail += '<td ><b>'+mb.formatRp(parseFloat(diskon_detail))+'</b></td>';
                        tbodyDetail += '</tr>';

                        grandTotal += parseFloat((inv_umum_det_item.harga_jual * inv_umum_det_item.jumlah) - diskon_detail);
                    });

                    tbodyDetail += '<tr class="thick-end"> <th colspan="2"> <b></b> </th> <td> </td> </tr>';

                    var akomodasi = 0;

                    if(!isNaN(invoice_umum.akomodasi)){
                        akomodasi = invoice_umum.akomodasi;
                    }

                    var diskon = 0 ;
                    if(!isNaN(invoice_umum.diskon_nominal) && (invoice_umum.diskon_nominal != null)){
                        diskon = invoice_umum.diskon_nominal;
                    }



                    $('tbody#detail_invoice_umum_new').append(tbodyDetail);
                    $('td#total_invoice_umum').addClass('text-right bold');
                    $('td#total_invoice_umum').text(mb.formatRp(grandTotal));
                    $('td#total_transaksi_umum').addClass('text-right');
                    $('td#total_transaksi_umum').text(mb.formatRp(grandTotal));
                    $('td#diskon_umum').text(mb.formatRp(parseInt(diskon)));
                    $('td#akomodasi_umum').text(mb.formatRp(parseInt(akomodasi)));
                    $('td#grand_total_umum').text(mb.formatRp(grandTotal+ parseInt(akomodasi)));
                    $('th#terbilang_umum').text('# '+result.terbilang_umum+' Rupiah #');


                    $('input[name$="[nominal]"').val(grandTotal - parseInt(diskon) + parseInt(akomodasi));
                    $('input[name$="[nominal]"').attr('value', (grandTotal - parseInt(diskon) + parseInt(akomodasi)));
                    $('input#sisa_hutang').val(grandTotal - parseInt(diskon) + parseInt(akomodasi));
                    $('input#sisa_hutang_hidden').val(grandTotal - parseInt(diskon) + parseInt(akomodasi));

                    //draft_invoice_bpjs
                    $('p#tanggal_invoice_bpjs').text('Tgl : '+invoice_bpjs.created_date);
                    $('p#nama_pasien_bpjs').text('Pasien : '+invoice_bpjs.nama_pasien);
                    $('p#no_rm_bpjs').text('No. RM : '+NomorPasien);
                    $('p#penjamin_bpjs').text('Penjamin : BPJS - JKN');
                    $('p#no_penjamin_bpjs').text('No. Penjamin : -');

                    tbodyDetailBpjs = '<tr> <th colspan="2"> <b>Deskripsi</b> (Jml)</th> <td> Sub Total </td> </tr> <tr class="thick-row"> <td colspan="3" class="small-info"> <b>(Jumlah * Harga)</b> </td> </tr> <tr> <th colspan="3"> <b>Tindakan</b> </th></tr>';

                    var grandTotalBpjs = 0;

                    $.each(invoice_bpjs_detail, function(idx, inv_bpjs_det){
                        tbodyDetailBpjs += '<tr>';
                        tbodyDetailBpjs += '<td class="blank-cell"> </td>';
                        tbodyDetailBpjs += '<th class="deskripsi" style="white-space: normal !important;">'+inv_bpjs_det.nama_tindakan+' ('+inv_bpjs_det.jumlah+')</th>';
                        tbodyDetailBpjs += '<td ><b>'+mb.formatRp(parseFloat(inv_bpjs_det.harga_jual * inv_bpjs_det.jumlah))+'</b></td>';
                        tbodyDetailBpjs += '</tr>';

                        grandTotalBpjs += parseFloat(inv_bpjs_det.harga_jual * inv_bpjs_det.jumlah);
                    });

                    tbodyDetailBpjs += '<tr> <th colspan="3"> <b>Obat & Alkes</b> </th>  </tr>';

                    $.each(invoice_bpjs_detail_item, function(idx, inv_bpjs_det_item){
                        tbodyDetailBpjs += '<tr>';
                        tbodyDetailBpjs += '<td class="blank-cell"> </td>';
                        tbodyDetailBpjs += '<th class="deskripsi" style="white-space: normal !important;">'+inv_bpjs_det_item.nama_tindakan+' ('+inv_bpjs_det_item.jumlah+')</th>';
                        tbodyDetailBpjs += '<td ><b>'+mb.formatRp(parseFloat(inv_bpjs_det_item.harga_jual * inv_bpjs_det_item.jumlah))+'</b></td>';
                        tbodyDetailBpjs += '</tr>';

                        grandTotalBpjs += parseFloat(inv_bpjs_det_item.harga_jual * inv_bpjs_det_item.jumlah);
                    });

                    tbodyDetailBpjs += '<tr class="thick-end"> <th colspan="2"> <b></b> </th> <td> </td> </tr>';

                    $('tbody#detail_invoice_bpjs_new').append(tbodyDetailBpjs);
                    $('td#total_invoice_bpjs').addClass('text-right bold');
                    $('td#total_invoice_bpjs').text(mb.formatRp(grandTotalBpjs));
                    $('th#terbilang_bpjs').text('# '+result.terbilang_bpjs+' Rupiah #');

                    $('input[name$="[jumlah_bayar]"]').on('change keyup', function(){

                        var jumlah_bayar = parseInt($(this).val()),
                            total_invoice = parseInt($('input[name$="[nominal]"]').val()),
                            kembali = jumlah_bayar - total_invoice;


                        $('input[name$="[kembali]"]').val(kembali);
                        $('input[name$="[kembali]"]').attr('value',kembali);



                    });






                    
                }
                else if(result.success === false)
                {
                    mb.showMessage('error',result.msg,'Informasi');
                    $('input#no_member').focus();
                }
            },
            complete : function()
            {
                Metronic.unblockUI();
            }
        });
    }

    var handleDiskon = function(){
        $('input#diskon').on('change', function(){
            var grand_total_klaim = parseInt($('input#grand_total_klaim_hidden').val());
            var grand_total = parseInt($('input#grand_total_hidden').val());
            
            diskon = parseInt($(this).val());
            
            total_diskon = grand_total*diskon/100;
            total_diskon_klaim = grand_total_klaim*diskon/100;

            hasil_diskon = grand_total-total_diskon;
            hasil_diskon_klaim = grand_total_klaim-total_diskon_klaim;
            // alert(hasil_diskon_klaim);
            $('input#grand_total_hidden').val(hasil_diskon);
            $('input#grand_total_klaim_hidden').val(hasil_diskon_klaim);

            $('input#grand_total').val(mb.formatTanpaRp(hasil_diskon));
            $('input#grand_total_klaim').val(mb.formatTanpaRp(hasil_diskon_klaim));
        });
    }

    var handlePPH = function(){
        $('input#pph').on('change', function(){
            var grand_total_klaim = parseInt($('input#grand_total_klaim_hidden').val());
            var grand_total = parseInt($('input#grand_total_hidden').val());
            
            pph = parseInt($(this).val());
            
            total_pph = grand_total*pph/100;
            total_pph_klaim = grand_total_klaim*pph/100;

            hasil_pph = grand_total-total_pph;
            hasil_pph_klaim = grand_total_klaim-total_pph_klaim;
            // alert(hasil_pph_klaim);
            $('input#grand_total_hidden').val(hasil_pph);
            $('input#grand_total_klaim_hidden').val(hasil_pph_klaim);

            $('input#grand_total').val(mb.formatTanpaRp(hasil_pph));
            $('input#grand_total_klaim').val(mb.formatTanpaRp(hasil_pph_klaim));
        });
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'reservasi/pembayaran/';
        handleJwertyEnterRent($('input#no_member'));
        handleValidation();
        calculateTotalKeseluruhan();
        initForm();
        handleSelectCabang();
        handleDatePickers();
        handlePilihPasien();
        // handleDataTableDaftarTindakan();
        // handleDataTableDaftarObat();
        // handleBootstrapSelect();
        handleSelect2();    
        handleConfirmSave();
        handleDataTableItems();
        handleDataTableItemsTitipan();

        handleTerdaftar();
        handleTidakTerdaftar();

        handleDiskon();
        handlePPH();
        handleDataTableInvoice();
        // handleDropdownTypeChange();
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});