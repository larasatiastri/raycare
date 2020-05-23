mb.app.view = mb.app.view || {};





(function(o){

    

     var 

        baseAppUrl                  = '',

        $form                       = $('#form_add_permintaan_po'),

        $formModals                 = $('#form_modal_permintaan_po'),

        $tableAddAccount            = $('#table_add_account', $form),

        $tableAddAccountTitipan     = $('#table_add_account_titipan', $form),

        $tableAccountSearch         = $('#table_account_search'),

        $tableItemSearch            = $('#table_item_search'),

        $tableBox                   = $('#table_box'),

        $tableItemSearchTitipan     = $('#table_item_search_tindakan'),

        $tablePilihUser             = $('#table_pilih_user'),

        $tablePilihSupplier         = $('#table_supplier'),

        $tablePilihCalonSupplier    = $('#table_calon_supplier'),

        $tableInformation           = $('#table_information'),

        $popoverItemContent         = $('#popover_item_content'),

        $popoverItemContentTindakan = $('#popover_item_content_tindakan'),

        $popoverPasienContent       = $('#popover_pasien_content'), 

        $popoverSupplierContent     = $('#popover_supplier_content'), 

        $lastPopoverItem            = null,

        $lastPopoverSupplier        = null,

        tplItemRow                  = $.validator.format( $('#tpl_item_row').text() ),

        tplItemAccRow               = $.validator.format( $('#tpl_item_acc_row').text() ),

        itemCounter                 = 0,

        itemCounter1                = 0,

        tplFormGambar               = '<li class="fieldset">' + $('#tpl-form-gambar', $form).val() + '<hr></li>',

        regExpTplGambar             = new RegExp('gambar[0]', 'g'),   // 'g' perform global, case-insensitive

        gambarCounter               = 1,

        

        formsGambar = {

                        'gambar' : 

                        {            

                            section  : $('#section-gambar', $form),

                            template : $.validator.format( tplFormGambar.replace(regExpTplGambar, '_id_{0}') ), //ubah ke format template jquery validator

                            counter  : function(){ gambarCounter++; return gambarCounter-1; }

                        }   

                    },

        totalBayar              = 0





        ;



       var initForm = function(){

      

         



        var 

            $btnSearchAccount        = $('.search-account', $tableAddAccount),

            $btnSearchAccountTitipan = $('.search-account-titipan', $tableAddAccountTitipan),

            $btnSearchSupplier       = $('.search-supplier', $tableAddAccount),

            $btnDeletes              = $('.del-this', $tableAddAccount);

            $btnDeletestitipan       = $('.del-this-plus', $tableAddAccountTitipan);



        handleBtnSearchAccount($btnSearchAccount);  



        handleBtnSearchAccountTitipan($btnSearchAccountTitipan);  

        handleBtnSearchSupplier($btnSearchSupplier);  



        var $btnSearchUser  = $('.pilih-user', $form);

        handleBtnSearchUser($btnSearchUser);

        

        // handle delete btn

        $.each($btnDeletes, function(idx, btn){

            handleBtnDelete( $(btn) );

        });



        $.each($btnDeletestitipan, function(idx, btn){

            handleBtnDeleteTitipan( $(btn) );

        });







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

        

        $('input[name$="[biaya_tambahan]"]', $form).on('keyup', function(){

            calculateTotalKeseluruhan();

        });



        $('input[name$="[biaya_tambahan]"]', $form).on('change', function(){

            calculateTotalKeseluruhan();

        });



        calculateTotalKeseluruhan();





    };



    var calculateTotalKeseluruhan = function(){



        // var nominal = $('input[name$="[nominal]"]');



            // var totalBayar = 0;

            $('input#biaya_tambahan').on('change', function(){

            // alert('klik');

                

                var cash = parseInt($(this).val());

                var bayar = parseInt($('input#total_bayar_hidden').val());

                var tindakan = parseInt($('input#total_tindakan_hidden').val());



                    // alert(cash);



                totalBayar = bayar + tindakan + cash;

                

                $('input#total_keseluruhan').val(mb.formatRp(totalBayar));

                $('input#total_keseluruhan_hidden').val(totalBayar);



                if (!isNaN(totalBayar)){



                $('input#total_keseluruhan').val(mb.formatRp(totalBayar));

                $('input#total_keseluruhan_hidden').val(totalBayar);

                

                } else {



                $('input#total_keseluruhan').val(0);

                $('input#total_keseluruhan_hidden').val(0);



                }



                // var cash = parseInt($(this).val());

                // alert(cash);

                // alert('oii');



            });

    }



    var handleRadioButton = function()

    {



        $('a#item_terdaftar').on('click', function()

        {

            // alert('klik');   

            $('div#section_terdaftar').removeClass('hidden');

            $('div#section_tidak_terdaftar').addClass('hidden');



            $('a#item_tak_terdaftar').removeClass('btn-primary');

            $('a#item_tak_terdaftar').addClass('btn-default');

            $('input[name$="[satuan_tindakan]"]').removeClass('required');

            $('input[name$="[account_id]"]').attr('required','required');

            $('input[name$="[item_sub_kat_id]"]').attr('required','required');



            $(this).addClass('btn-primary');

            $(this).removeClass('btn-default');

            $('input#tipe_item').val(1);

            

            // $('div#select-dokter').removeClass('hidden');

            // $('div#identitas-pasien').removeClass('hidden');

            // $('div#portlet-informasi-dokter').removeClass('hidden');



        });



        $('a#item_tak_terdaftar').on('click', function()

        {

            // alert('klik');   



            $('div#section_terdaftar').addClass('hidden');

            $('div#section_tidak_terdaftar').removeClass('hidden');

            

            $('a#item_terdaftar').removeClass('btn-primary');

            $('a#item_terdaftar').addClass('btn-default');



            $('input[name$="[satuan_tindakan]"]').attr('required','required');

            $('input[name$="[account_id]"]').removeAttr('required');

            $('input[name$="[item_sub_kat_id]"]').removeAttr('required');



            $(this).addClass('btn-primary');

            $(this).removeClass('btn-default');

            $('input#tipe_item').val(2);

            

            // $('div#select-dokter').addClass('hidden');

            // $('div#identitas-pasien').addClass('hidden');

            // $('div#portlet-informasi-dokter').addClass('hidden');





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

                

                $('label[name$="[sub_total]"]', $row).text(mb.formatRp(totalCost));

                $('input[name$="[sub_total]"]', $row).val(totalCost);



                 total_alat_obat = total_alat_obat + totalCost;

                $('input#total_bayar').val(mb.formatRp(total_alat_obat));

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

                

                $('label[name$="[sub_total_titipan]"]', $row).text(mb.formatRp(totalCost));

                $('input[name$="[sub_total_tindakan]"]', $row).val(totalCost);

            

                total_tindakan = total_tindakan + totalCost;

                $('input#total_tindakan').val(mb.formatRp(total_tindakan));

                $('input#total_tindakan_hidden').val(total_tindakan);





            }

            // alert(totalCost);



        });



        // $('#total_before_discount_hidden').val(totalCost);

    };



    var addItemRow = function(){



        var numRow = $('tbody tr', $tableAddAccount).length;

        var 

            $rowContainer      = $('tbody', $tableAddAccount),

            $newItemRow        = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),

            $btnSearchAccount  = $('.search-account', $newItemRow),

            $btnSearchSupplier = $('.search-supplier', $newItemRow)

            ;



        // handle delete btn

        handleBtnDelete( $('.del-this', $newItemRow) );

      

        // handle button search item

        handleBtnSearchAccount($btnSearchAccount);

        handleBtnSearchSupplier($btnSearchSupplier);



    };



    var addItemAccRow = function(){



        var numRow = $('tbody tr', $tableAddAccountTitipan).length;

        

        var 

            $rowContainer         = $('tbody', $tableAddAccountTitipan),

            $newItemRow           = $(tplItemAccRow(itemCounter1++)).appendTo( $rowContainer ),

            $btnSearchAccount     = $('.search-account', $newItemRow)

            ;



        // handle delete btn

        handleBtnDeleteTitipan( $('.del-this-plus', $newItemRow) );

        handleDatePickers();



        $.each(formsGambar, function(idx, form){

            // handle button add

            $('a#tambah_gambar', form.section).on('click', function(){

                

                addFieldsetGambar(form);



            });

            // beri satu fieldset kosong

            addFieldsetGambar(form);



        });



        $('input[name$="[jumlah_tindakan]"]', $newItemRow).on('change', function(){

            if ($(this).val() < 0) {

                $(this).val(1);

            };

        });



        handleUnggahGambar ( $('.unggah-gambar', $newItemRow) );



        // $('.type', $newItemRow).bootstrapSwitch();

        // handleBootstrapSelectType($('.type', $newItemRow));



    };

     

     var handleTambahRowPelengkap = function(){

        $('a#add_biaya', $form).click(function() {

            addItemAccRow();

        });

    };

    
    var handleDataTableItems = function(){
       
        var tipe = $('input[name="jenis_permintaan"]:checked').val();

        oTableItem = $tableItemSearch.dataTable({

            'processing'            : true,

            'serverSide'            : true,

            'language'              : mb.DTLanguage(),

            'ajax'                  : {

                'url' : baseAppUrl + 'listing_alat_obat/' + tipe,

                'type' : 'POST',

            },          

            'pageLength'            : 10,

            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],

            'order'                 : [[1, 'asc']],

            'columns'               : [

                { 'name' : 'item.nama kode','visible' : true, 'searchable': true, 'orderable': false },

                { 'name' : 'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': false },

                { 'name' : 'item.nama nama', 'visible' : true, 'searchable': false, 'orderable': false },

                { 'name' : 'item.nama nama','visible' : true, 'searchable': false, 'orderable': false },

                ]

        });       

       

        $('input[name="jenis_permintaan"]').on( 'change', function () {

            var $row            = $('tbody>tr', $tableAddAccount);

            $row.remove();

            if($('tbody>tr', $tableAddAccount).length == 0){

                addItemRow();

            }
            oTableItem.api().ajax.url(baseAppUrl + 'listing_alat_obat/' + $(this).val()).load();
            
            $('.search-account', $tableAddAccount).popover('hide');      

        });

            // oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/' + id).load();



        $tableItemSearch.on('draw.dt', function (){

            var $btnSelect = $('a.select', this);

            handleAccountSelect( $btnSelect );     

        });

            

        $popoverItemContent.hide();        



    };

    var handleBtnGrab = function(){
        $('a#btn_grab').click(function(){
            var tipe = $('input[name="jenis_permintaan"]:checked').val();

            $.ajax({
                type     : 'POST',
                url      :  mb.baseUrl() + 'grab_data/get_data_item',
                dataType : 'json',
                success  : function( results ) {
                    oTableItem.api().ajax.url(baseAppUrl +  'listing_alat_obat/' + tipe).load();
                }
            });
        });
    }



    var handleDataTableSupplier = function() {

        oTableSupplier = $tablePilihSupplier.dataTable({

                'processing'            : true,

                'serverSide'            : true,

                'language'              : mb.DTLanguage(),

                'ajax'                  : {

                    'url' : baseAppUrl + 'listing_supplier',

                    'type' : 'POST',

                },          

                'pageLength'            : 10,

                'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],

                'order'                 : [[1, 'asc']],

                'columns'               : [

                                            { 'visible' : true, 'searchable': true, 'orderable': false },

                                            { 'visible' : true, 'searchable': true, 'orderable': false },

                                            { 'visible' : true, 'searchable': false, 'orderable': false },

                                          ]

        });       

        $('#table_supplier_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input

        $('#table_supplier_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown





        $tablePilihSupplier.on('draw.dt', function (){      

            var $btnSelect = $('a.select-supplier', this);       

            handleSupplierSelect( $btnSelect );       

        });

            

        $popoverSupplierContent.hide(); 

    };



    var handleDataTableCalonSupplier = function() {

        // body...

    };

    var handleDataTableBox = function(){

       oTable = $tableBox.dataTable({

            'processing'            : true,

            'serverSide'            : true,

            'language'              : mb.DTLanguage(),

            'ajax'                  : {

                'url' : baseAppUrl + 'listing_box_paket',

                'type' : 'POST',

            },          

            'pageLength'            : 10,

            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],

            'order'                 : [[1, 'asc']],

            'columns'               : [

                { 'visible' : false, 'searchable': true, 'orderable': false },

                { 'visible' : true, 'searchable': true, 'orderable': false },

                { 'visible' : true, 'searchable': false, 'orderable': false },

                ]

        });       

        $('#table_box_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input

        $('#table_box_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown





        $tableBox.on('draw.dt', function (){

            

            var $btnSelect = $('a.select-box', this);

            

            handleBoxSelect( $btnSelect );





            

        });

            

            $popoverItemContent.hide();        



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

                { 'visible' : true, 'searchable': true, 'orderable': false },

                { 'visible' : true, 'searchable': false, 'orderable': false },

                { 'visible' : true, 'searchable': false, 'orderable': false },

                { 'visible' : true, 'searchable': false, 'orderable': false },

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



        



                $('#total_tindakan').val(mb.formatRp(parseInt(total_tindakan)));

                // if(total_debit != grandtotal_debit && total_debit != grandtotal_credit)

                // {

                //     $('a#confirm_jurnal', $form).addClass('hidden');

                //     // alert('bebas');

                // }

                // else

                // {

                //     $('a#confirm_jurnal', $form).removeClass('hidden');

                // }

                // handleCompareTotal();    

                

            });

            

        } );



        $popoverItemContentTindakan.hide();        

    };



    var handleBoxSelect = function($btn){

        $btn.on('click', function(e){

            // alert('di klik');

            var 

                $parentPop  = $(this).parents('.popover').eq(0),

                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),

                $row        = $('#'+rowId, $tableAddAccount),

                $itemCodeEl = null,

                $itemNameEl = null, 

                $rowClass    = $('.row_item', $tableAddAccount),

                $itemHargaEl = null,

                $itemQtyEl  = $('input[name$="[name]"]', $row)

                ;                

            // console.log(itemTarget);

           

                itemIdElAll     = $('input[name$="[id_box]"]', $rowClass);

                $itemIdEl       = $('input[name$="[account_id]"]', $row);

                $itemCodeEl     = $('label[name$="[kode]"]', $row);

                $itemCodeIn     = $('input[name$="[kode]"]', $row);

                $itemNameEl     = $('label[name$="[nama]"]', $row);

                $itemNameIn     = $('input[name$="[nama]"]', $row);

                $itemHargaEl    = $('label[name$="[harga]"]', $row);

                $itemHargaIn    = $('input[name$="[harga]"]', $row);

                $itemSatuanEl    = $('select[name$="[satuan]"]', $row);



                $IdBox    = $('input[name$="[id_box]"]', $row);

                $itemNamaBox    = $('input[name$="[nama_box]"]', $row);

                $itemJumlahBox    = $('input[name$="[jumlah_box]"]', $row);

                $('.search-account', $tableAddAccount).popover('hide');





                itemId = $(this).data('box')['id'];

                jumlah = $(this).data('jumlah')[0]['jumlah_box'];

                // alert(jumlah);



                found = false;

                $.each(itemIdElAll,function(idx, value){

                    // alert(this.value);

                    if(itemId == this.value)

                    {

                        found = true;

                    }

                });



                // alert(found);

           

                if(found == false)

                {

                

                    var box_detail = $(this).data('box-detail');



                    $IdBox.val($(this).data('box')['id']);

                    $itemNamaBox.val($(this).data('box')['box_paket_nama']);

                    $itemNameEl.text($(this).data('box')['box_paket_nama']);

                    $itemNameIn.val($(this).data('box')['box_paket_nama']);

                    $itemJumlahBox.val($(this).data('box-detail')[0]['jumlah']);



                    $itemCodeIn.attr('disabled','disabled');

                    $itemSatuanEl.attr('disabled','disabled');



                    addItemRow();



                

                }



            calculateTotal();

            e.preventDefault();   



        });     

    };



    var handleSupplierSelect = function($btn){

        $btn.on('click', function(e){

            // alert('di klik');

            var 

                $parentPop  = $(this).parents('.popover').eq(0),

                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),

                $row        = $('#'+rowId, $tableAddAccount),

                $itemCodeEl = null,

                $itemNameEl = null, 

                $rowClass    = $('.row_item', $tableAddAccount);                

            // console.log(itemTarget);

           

                $suppIdIn     = $('input[name$="[supp_id]"]', $row);

                $suppNameIn     = $('input[name$="[supp_nama]"]', $row);

                $suppTipeEl     = $('label[name$="[supp_tipe]"]', $row);

               





                suppId = $(this).data('item')['supplier_id'];

                suppNama = $(this).data('item')['nama'];



                $suppIdIn.val(suppId);

                $suppNameIn.val(suppNama);

                

                $('.search-supplier', $tableAddAccount).popover('hide');

            e.preventDefault();   



        });     

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

                $rowClass    = $('.row_item', $tableAddAccount),

                $itemHargaEl = null,

                $itemQtyEl  = $('input[name$="[name]"]', $row)

                ;                

            // console.log(itemTarget);

           

                $itemIdEl       = $('input[name$="[account_id]"]', $row);

                $itemSubKatIn       = $('input[name$="[item_sub_kat_id]"]', $row);

                itemIdElAll     = $('input[name$="[account_id]"]', $rowClass);

                $itemCodeEl     = $('label[name$="[kode]"]', $row);

                $itemCodeIn     = $('input[name$="[kode]"]', $row);

                $itemNameEl     = $('label[name$="[nama]"]', $row);

                $itemNameIn     = $('input[name$="[nama]"]', $row);

                $itemHargaEl    = $('label[name$="[harga]"]', $row);

                $itemHargaIn    = $('input[name$="[harga]"]', $row);

                $itemSatuanEl    = $('select[name$="[satuan]"]', $row);

                $('.search-account', $tableAddAccount).popover('hide');

                



                itemId = $(this).data('item')['id'];



                found = false;

                $.each(itemIdElAll,function(idx, value){

                    // alert(this.value);

                    if(itemId == this.value)

                    {

                        found = true;

                    }

                });



                // alert(found);

                

                if (found == false)

                {

                    $itemIdEl.val($(this).data('item')['id']);

                    $itemSubKatIn.val($(this).data('item')['item_sub_kategori']);

                    $itemCodeEl.text($(this).data('item')['kode']);

                    $itemCodeIn.val($(this).data('item')['kode']);

                    $itemNameEl.text($(this).data('item')['nama']);

                    $itemNameIn.val($(this).data('item')['nama']);

                    $itemHargaEl .text(mb.formatRp(parseInt($(this).data('item')['harga'])));

                    $itemHargaIn .val($(this).data('item')['harga']);



                    var satuan = $(this).data('satuan'),

                        primary = $(this).data('satuan_primary');

                    

                    $itemSatuanEl.empty();

                    $.each(satuan, function(key, value) {

                        $itemSatuanEl.append($("<option></option>").attr("value", value.id).text(value.nama));

                        $itemSatuanEl.val(primary.id);

                    });

                    $itemSatuanEl.removeAttr('disabled');

                    $('.search-supplier', $tableAddAccount).removeAttr('disabled');



                    oTableSupplier.api().ajax.url(baseAppUrl + 'listing_supplier/' + itemId).load();

                    addItemRow();

                }



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

            $itemHargaEl.text(mb.formatRp(parseInt($(this).data('item')['harga'])));

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



     var handleUnggahGambar = function($btn){

        var 

            rowId           = $btn.closest('tr').prop('id'),

            $row            = $('#'+rowId, $tableAddAccountTitipan)



        $btn.on('click', function(e){            

                

            $.each(formsGambar, function(idx, form){

            // handle button add

            $('a#tambah_gambar', form.section).on('click', function(){

                

                addFieldsetGambar(form);



            });

            // beri satu fieldset kosong

            addFieldsetGambar(form);



        });

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



            $popContainer.css({minWidth: '640px', maxWidth: '420px'});



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

    



    var handleBtnSearchSupplier = function($btn){

        var rowId  = $btn.closest('tr').prop('id');

        // console.log(rowId);



        $btn.popover({ 

            html : true,

            container : '.page-content',

            placement : 'bottom',

            content: '<input type="hidden" name="rowItemId"/>'



        }).on("show.bs.popover", function(){



            var $popContainer = $(this).data('bs.popover').tip();



            $popContainer.css({minWidth: '720px', maxWidth: '900px'});



            if ($lastPopoverSupplier != null) $lastPopoverSupplier.popover('hide');



            $lastPopoverSupplier = $btn;



            $popoverSupplierContent.show();



        }).on('shown.bs.popover', function(){



            var 

                $popContainer = $(this).data('bs.popover').tip(),

                $popcontent   = $popContainer.find('.popover-content')

                ;



            // record rowId di popcontent

            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);

            

            // pindahkan $popoverSupplierContent ke .popover-conter

            $popContainer.find('.popover-content').append($popoverSupplierContent);



        }).on('hide.bs.popover', function(){

            //pindahkan kembali $popoverSupplierContent ke .page-content

            $popoverSupplierContent.hide();

            $popoverSupplierContent.appendTo($('.page-content'));



            $lastPopoverSupplier = null;



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

        if (jQuery().datepicker) {

            $('.date', $form).datepicker({

                rtl: Metronic.isRTL(),

                format : 'dd-M-yyyy',

                autoclose: true

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

                'url' : baseAppUrl + 'listing_pilih_user_permintaan_po',

                'type' : 'POST',

            },          

            'pageLength'            : 10,

            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],

            'order'                 : [[1, 'asc']],

            'columns'               : [

                { 'visible' : false, 'searchable': false, 'orderable': true },

                { 'visible' : true, 'searchable': true, 'orderable': false },

                { 'visible' : true, 'searchable': true, 'orderable': false },

                { 'visible' : true, 'searchable': false, 'orderable': false },

                ]

        });       

        var $btnSelects = $('a.select', $tablePilihUser);

        handlePilihUserSelect( $btnSelects );



        $tablePilihUser.on('draw.dt', function (){

            var $btnSelect = $('a.select', this);

            handlePilihUserSelect( $btnSelect );

            

        });



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



            $popContainer.css({minWidth: '560px', maxWidth: '560px'});



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

                $IdRefUser   = $('input[name="id_ref_user"]'),

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



    var handleUploadify = function()

    {



        $("#user_foto").uploadify({

            'swf'               : mb.baseUrl()+'assets/mb/global/uploadify/uploadify.swf',

            'uploader'          : mb.baseUrl()+'assets/mb/global/uploadify/uploadify6.php',

            'formData'          : {'type' : 'gambar_item'}, 

            'fileObjName'       : 'Filedata', 

            'fileSizeLimit'     : '512KB',  //TODO : mau pake parameter??

            'fileTypeDesc'      : 'All Files',

            'fileTypeExts'      : '*.*',

            'method'            : 'post', 

            'multi'             : false, 

            'queueSizeLimit'    : 1, 

            'removeCompleted'   : true, 

            'removeTimeout'     : 5, 

            'uploadLimit'       : 5, 

            'onUploadSuccess'   : function(file, data, response) {

                $("#choosen_file"  ).html('<a href="'+mb.baseUrl()+'assets/mb/var/temp/'+data+'" target="_blank"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+data+'" alt="Smiley face" style="border: 1px solid #000; max-width:200px; max-height:200px;"></a>');

                $("#choosen_file_container" ).show();

                $("#url" ).val(mb.baseUrl()+'mb/var/temp/'+data);

                $("#name_file" ).val(data);

            }

        }); 

    }



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



    var addFieldsetGambar = function(form)

    {



        var 

            $section           = form.section,

            $fieldsetContainer = $('ul', $section),

            counter            = form.counter(),

            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer);



        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){

        //     handleSelectSection(this.value, $newFieldset);

        // });

        $('a.del-gambar', $newFieldset).on('click', function(){

            handleDeleteFieldsetGambar($(this).parents('.fieldset').eq(0));

        });



        //jelasin warna hr pemisah antar fieldset

        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');



        handleUploadify(counter);



    };



    var uploadfile = function(table) {

  

        $('#uploaddokumen').uploadify({

            "swf"               : mb.baseUrl() + "assets/mb/global/uploadify/uploadify.swf",

            "uploader"          : mb.baseUrl() + "assets/mb/global/uploadify/uploadify4.php",

            "formData"          : {"type" : "dokumen", "dokumen_id" : "", "nama_dokumen" : "dokumen"}, 

            "fileObjName"       : "Filedata", 

            "fileSizeLimit"     : "2048KB",

            // "fileTypeDesc"      : "Image Files (.jpg, .jpeg, .png)",

            "fileTypeExts"      : "*.pdf",

            "method"            : "post", 

            "multi"             : false, 

            "queueSizeLimit"    : 1, 

            "removeCompleted"   : true, 

            "removeTimeout"     : 5, 

            "uploadLimit"       : 5, 

            "onUploadSuccess"   : function(file, data, response) {

             var paramsArray = data.split('%%__%%');

                param1 = paramsArray[0]; 

                param2 = paramsArray[1]; 

                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')

                {

                    $('#uploadchoosen_file_1').html("<img src="+ mb.baseUrl() + "assets/mb/pages/pembelian/permintaan_po/images/temp/"+param1+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");

                    $('#uploadchoosen_file_container_1').show();

                    $('#uploadfilename').val(param1);

                }else{

                    $('#uploadfilename').val(param1);

                     $('#uploadchoosen_file_container_1').show();

                    $('#uploadchoosen_file_1').html('<b>' + file.name + '</b>');

                }

              

            },

            "onUploadComplete"   : function(file) {

             

              

            }

        }); 

};





    o.init = function(){

        baseAppUrl = mb.baseUrl() + 'pembelian/permintaan_po/';

        handleDataTableItems();

        handleBtnGrab();

        handleDataTableBox();

        handleDataTableItemsTitipan();

        handleValidation();

        calculateTotalKeseluruhan();

        initForm();

        handleDatePickers();

        // handleBootstrapSelect();

        handleSelect2();    

        handleConfirmSave();

        handleDataTableSupplier();

        handleTambahRowPelengkap();

        handlePilihUser();

        handleUploadify();

        uploadfile();

        handleRadioButton();

        // handleDropdownTypeChange();

 

    };



}(mb.app.view));



$(function(){    

    mb.app.view.init();

});