mb.app.resep_obat = mb.app.resep_obat || {};
mb.app.resep_obat.proses = mb.app.resep_obat.proses || {};

// mb.app.resep_obat.add namespace
(function(o){

    var 
        baseAppUrl             = '',
        $form                  = $('#form_proses_racik_obat'),
        $errorTop              = $('.alert-danger', $form),
        $succesTop             = $('.alert-success', $form),
        $tableItemDigunakan    = $('#table_item_digunakan', $form),
        $tableItemSearch       = $('#table_item_search'),
        $tableResepSearch      = $('#table_resep_search'),
        $tableKomposisiManual  = $('#table_komposisi_manual'),
        $tableKomposisiRacikan  = $('#table_komposisi_racikan'),
        $popoverItemContent    = $('#popover_item_content'), 
        $lastPopoverKeterangan = null,
        $lastPopoverItem       = null,
        $popoverResepContent   = $('#popover_resep_content'), 
        $lastPopoverResep      = null,
        tplKomposisiRacikan    = $.validator.format( $('#tpl_komposisi_racikan').text() ),
        itemCounter            = $('input#counter').val(),
        
        i                      = parseInt($('input#i').val()),
        
        tplFormIdentitas       = '<li class="fieldset">' + $('#tpl-form-identitas').val() + '<hr></li>',
        regExpTplIdentitas     = new RegExp('identitas[0]', 'g'),   // 'g' perform global, case-insensitive
        identitasCounter       = 1,

        formsIdentitas = {
                        'identitas' : 
                        {            
                            section  : $('#ajax_notes'),
                            template : $.validator.format( tplFormIdentitas.replace(regExpTplIdentitas, '_id_{0}') ), //ubah ke format template jquery validator
                            counter  : function(){ identitasCounter++; return identitasCounter-1; }
                        }
                           
                    }
        ;


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
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control locker
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control locker
            } 
        });   
    }

    var initForm = function(){
        // var $btnSearchResep = $('.search-resep');
   
        // handleBtnSearchResep($btnSearchResep);    


        $.each(formsIdentitas, function(idx, form){
            // handle button add
                
                $('a#tambah_identitas',form.section).on('click', function(){

                    
                    
                    $classrow   = $('div.show_identitas'),
                    IdentitasIdAll = $('input.identitas_id', $classrow),
                    found = false;
                    $.each(IdentitasIdAll, function(idx, value){
                        thisIdx = idx + 1;
                            alert(this.value);

                        if($('select#identitas').val() == this.value)
                        {

                            now = idx-1;
                            found = true;
                            alert(this.value+' '+idx+1);
                            $('input#identitas_'+this.value).removeClass('hidden');
                            $('label#identitas_'+this.value).removeClass('hidden');
                            $('input#identitas_'+this.value).addClass('tampil');
                            $('label#identitas_'+this.value).addClass('tampil');

                            $('input#identitas_tambah').val(thisIdx);

                            if ($('input#identitas_'+this.value).hasClass('tampil')) {
                                $('a#tambah_identitas',form.section).addClass('hidden');
                                $('a#tambah_fieldset',form.section).removeClass('hidden');
                            };
                        }

                        
                    });
                    
                });
                
                $('a#tambah_fieldset',form.section).on('click', function(){
                    
                    addFieldsetIdentitas(form, 'addFieldset');
                    $('a#tambah_identitas',form.section).removeClass('hidden');

                    i = i + 1;
                    $('input#i').val(i);
                });

            
            });
        
        var 
            $btnSearchItem   = $('.search-item', $tableKomposisiRacikan),
            $btnSearchItemDb = $('.search-item-db', $tableKomposisiRacikan),
            $btnDeletes      = $('.del-this', $tableKomposisiRacikan);
            $btnDeletesDb    = $('.del-this-db', $tableKomposisiRacikan),

        handleBtnSearchItem($btnSearchItem);

        addItemRow();
        $popoverItemContent.hide();

        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        $.each($btnDeletesDb, function(idx, btn){
            handleBtnDeleteDB( $(btn) );
        });

        $.each($btnSearchItemDb, function(idx, btn){
            handleBtnSearchItem( $(btn) );
        });
        
    };

    var addItemRow = function(){
        
        var numRow = $('tbody tr', $tableKomposisiRacikan).length;

        console.log('numrow' + numRow);

        // if (numRow > 0 && ! isValidLastRow()) return;

        var 
            $rowContainer      = $('tbody', $tableKomposisiRacikan),
            $newItemRow        = $(tplKomposisiRacikan(itemCounter++)).appendTo( $rowContainer ),
            // $newGetItemRow  = $(tplGetItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem     = $('.search-item', $newItemRow);

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
        
        // handle button search item
        handleBtnSearchItem($btnSearchItem);
    };

    var addFieldsetIdentitas = function(form, fieldset)
    {

        var 
                $section           = form.section,
                $fieldsetContainer = $('ul', $section),
                counter            = form.counter(),
                $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer);
        // alert('1');
        if (fieldset == "addFieldset") {
                

            

            $.ajax({
                    type        : 'POST',
                    url         : baseAppUrl + 'show_identitas',
                    data        : {identitas_id: $('select#identitas').val(), item_id : $('input#item_id').val(), item_satuan_id : $('input#item_satuan_id').val(), i : $('input#i').val()},
                    dataType    : 'text',
                    success     : function( results ) {
                        // $kelas_select.val('Pilih Kelas');
                        $('div.show_identitas', $newFieldset).html(results);
                        // $('div#form_tambahan').append(results);
                        // alert(results);
                    }
                });
            //jelasin warna hr pemisah antar fieldset
            $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

            $('input#identitas_tambah').val($('select#identitas').val());
            $('a#tambah_fieldset',form.section).addClass('hidden');

        }
    };

    var handleBtnDelete = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableKomposisiRacikan);

        $btn.on('click', function(e){
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
                // if (result==true) {
                    $row.remove();
                    setHarga();
                    if($('tbody>tr', $tableKomposisiRacikan).length == 0){
                        addItemRow();
                    }
                    // focusLastItemCode();
                // }
            // });
            e.preventDefault();
        });
    };

    var handleBtnDeleteDB = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableKomposisiRacikan);

        $btn.on('click', function(e){
            bootbox.confirm('Are you sure want to delete this item?', function(result){
                if (result==true) {

                    // $('input[name$="[is_delete]"]', $row).attr('value',1);
                    // $row.hide(); //hide
                    $row.remove();
                    setHarga();
                    if($('tbody>tr', $tableKomposisiRacikan).length == 0){
                        addItemRow();
                    }
                }
            });
            e.preventDefault();
        });
    };


    
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

    var handleConfirmModalOK = function(){
        
        $('a#modal_ok').click(function(e) {

            //alert("test");
            //$('input#identitas_1').value = $('input#identitas_1').val();
            $classrow   = $('div.show_identitas'),
            IdentitasIdAll = $('input.sendData', $classrow),
            found = false;
            $.each(IdentitasIdAll, function(idx, value){
            
            $(this).attr('value', $(this).val());

                
            });
            
            $.each($classrow, function(idx, value){
                divIdx = idx+1;
                $('div#form_tambahan').append($('div#show_identitas_'+divIdx).html());
            });

            $('hr').insertAfter('.jumlah');
            // $('div#form_tambahan').append($('div#show_identitas_2').html());
            $('div.show_identitas').empty();
            $('a#tambah_identitas').addClass('hidden');
            $('a#tambah_fieldset').removeClass('hidden');
            $('hr').remove();
            $('#modal_close').click();  
            
            //e.preventDefault();
        });
    };

    var handleConfirmModalCancel = function(){
        
        $('a#modal_cancel').click(function(e) {


            $('hr').insertAfter('.jumlah');
            // $('div#form_tambahan').append($('div#show_identitas_2').html());
            $('div.show_identitas').empty();
            $('a#tambah_identitas').addClass('hidden');
            $('a#tambah_fieldset').removeClass('hidden');
            $('hr').remove();
            $('#modal_close').click();  
            
            //e.preventDefault();
        });
    };

    var handleBiayaTambahan = function(){
        $('input#biaya_tambahan').on('keyup change', function () {
           var sub_total = parseInt($('input#sub_total').val()),
               biaya_tambahan = parseInt($(this).val());

            if (!isNaN(sub_total) && !isNaN(biaya_tambahan)) {
                $('label#harga_jual').text(mb.formatRp(sub_total + biaya_tambahan));
                $('input#harga_jual').val(sub_total + biaya_tambahan);
            };
        });
    };

    var handleJumlahProduksi = function(){
        $('input#jumlah_produksi').on('change', function () {
            jumlah_produksi = parseInt($(this).val());

            $.each($('input.jumlah_dokter_awal'), function(idx, value){
                var jumlah_dokter = parseInt(this.value),
                    dataId        = $(this).data('id');

                    $('input#items_jumlah_dokter_'+dataId).val(jumlah_produksi*jumlah_dokter);
                    $('label#items_jumlah_dokter_'+dataId).text(jumlah_produksi*jumlah_dokter);
                    
                    var jumlah = parseInt($('input#items_jumlah_dokter_'+dataId).val()),
                        harga = parseInt($('input#items_harga_'+dataId).val());

                    if (!isNaN(harga)) {
                        // alert(jumlah*harga);
                        $('input#items_sub_harga_'+dataId).val(jumlah*harga);   
                        $('label#items_sub_harga_'+dataId).text(jumlah*harga);  
                        // $('label#items_sub_harga_'+dataId).text(jumlah*harga);  

                        setHarga();
                    };
                    
                
                // $(this).attr('value', jumlah_produksi*jumlah_dokter);
            });
        });
    };

    var handleIdentitas = function(){
        $('select#identitas').on('change', function () {
           $.ajax({
                type        : 'POST',
                url         : baseAppUrl + 'show_identitas',
                data        : {identitas_id: $(this).val()},
                dataType    : 'text',
                success     : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#show_identitas").html(results);
                    // alert(results);
                }
            });
        });
    };


    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
                autoclose: true,
            })
        }
    }

    var handleDataTableKomposisiManual = function(){
        $tableKomposisiManual.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'paging'                : false,
            'info'                  : false,
            'ordering'              : false,
            'filter'                : false,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_komposisi_manual/'+ $('input#resep_obat_racikan_id').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'resep_obat_racikan_detail_manual.id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'resep_obat_racikan_detail_manual.keterangan','visible' : true, 'searchable': true, 'orderable': true },
                ]
        });

        $tableKomposisiManual.on('draw.dt', function (){
            $('.btn', this).tooltip();       
            var $colItems = $('.show-notes', this);

            $.each($colItems, function(idx, colItem){
                var
                    $colItem = $(colItem),
                    itemsData = $colItem.data('content');
            
            $colItem.popover({
                html : true,
                container : 'body',
                placement : 'bottom',
                content: function(){

                    var html = '<table class="table table-striped table-hover">';
                        html += '<tr>';
                        html += '<td>'+itemsData+'</td>';
                        html += '</tr>';
                        html += '</table>';
                        return html;
                }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '360px'});

                    if ($lastPopoverKeterangan !== null) $lastPopoverKeterangan.popover('hide');
                    $lastPopoverKeterangan = $colItem;
                }).on('hide.bs.popover', function(){
                    $lastPopoverKeterangan = null;
                }).on('click', function(e){
                });
            });  
        });    
    };

    var handleDataTableItems = function(){
        $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_search_item/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'item.kode', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },               ]
        });

        var $btnSelects = $('a.select', $tableItemSearch);
        handleItemSearchSelect( $btnSelects );

        $tableItemSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handleItemSearchSelect( $btnSelect );
            
        } );

        $popoverItemContent.hide();        
    };

    var handleItemSearchSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input[name="rowItemId"]', $parentPop).val(),
                statusRow       = $('input[name="rowStatus"]', $parentPop).val(),
                $row            = $('#'+rowId, $tableKomposisiRacikan),
                $row_status     = '',
                $itemId         = null,
                $itemInvetoryId = null,
                $itemGudangId   = null,
                $itemPmbId      = null,
                $itemKode       = null,
                $itemSatuanId   = null,
                $itemNama       = null,
                $classrow       = $('.table_item', $tableKomposisiRacikan),
                ItemIdAll       = $('input[name$="[item_id]"]', $classrow),
                hargaAll        = $('input[name$="[item_harga]"]', $classrow),
                itemId          = $(this).data('item').item_id
                ;        


           
                $itemId                = $('input[name$="[item_id]"]', $row);
                $itemInvetoryId        = $('input[name$="[inventory_id]"]', $row);
                $itemGudangId          = $('input[name$="[gudang_id]"]', $row);
                $itemPmbId             = $('input[name$="[pmb_id]"]', $row);
                $itemKode              = $('input[name$="[item_kode]"]', $row);
                $itemSatuanId          = $('input[name$="[item_satuan_id]"]', $row);
                $itemNama              = $('input[name$="[item_nama]"]', $row);
                $itemInputHarga        = $('input[name$="[item_harga]"]', $row);
                $itemInputJumlahDokter = $('input[name$="[item_jumlah_dokter]"]', $row);
                $itemInputSubHarga     = $('input[name$="[item_sub_harga]"]', $row);
                
                $itemLabelJumlahDokter = $('label[name$="[item_jumlah_dokter]"]', $row);
                $itemLabelJumlah       = $('label[name$="[item_jumlah]"]', $row);
                $itemLabelSatuan       = $('label[name$="[item_satuan]"]', $row);
                $itemLabelHarga        = $('label[name$="[item_harga]"]', $row);
                $itemLabelSubHarga     = $('label[name$="[item_sub_harga]"]', $row);
                $itemLabelKode         = $('label[name$="[item_kode]"]', $row);
                $itemLabelNama         = $('label[name$="[item_nama]"]', $row);
                
                $btnIdentitas          = $('a.identitas', $row);


                $('.search-item', $tableKomposisiRacikan).popover('hide');
                $('.search-item-db', $tableKomposisiRacikan).popover('hide');
                
                found = false;
                $.each(ItemIdAll, function(idx, value){
                    // alert(itemId);
                    if(itemId == this.value)
                    {
                        found = true;
                    }
                });
                
                console.log($itemId);

                var primary = $(this).data('satuan_primary').id;
                
                

                if(found == false)
                {
                    if ($itemKode.val() == "") {
                        var harga = parseInt($(this).data('item').harga),
                            sub_harga = 0 * harga;

                        $itemId.val($(this).data('item').item_id); 
                        $itemInvetoryId.val($(this).data('item').id);            
                        $itemGudangId.val($(this).data('item').gudang_id);            
                        $itemPmbId.val($(this).data('item').pmb_id);           
                        $itemKode.val($(this).data('item').item_kode);
                        $itemSatuanId.val($(this).data('item').satuan_id);
                        $itemNama.val($(this).data('item').item_nama);
                        $itemInputHarga.val(harga);
                        $itemInputSubHarga.val(sub_harga);

                        $itemLabelKode.text($(this).data('item').item_kode);
                        $itemLabelNama.text($(this).data('item').item_nama);

                        // $itemInputJumlahDokter.attr('value', $('input#jumlah_produksi').val());
                        // $itemLabelJumlahDokter.text($('input#jumlah_produksi').val());

                        $itemInputJumlahDokter.attr('value', 0);
                        $itemLabelJumlahDokter.text(0);
                        
                        $itemLabelJumlah.text(0);
                        $itemLabelSatuan.text($(this).data('item').satuan);

                        $itemLabelHarga.text(mb.formatTanpaRp(harga));
                        $itemLabelSubHarga.text(mb.formatTanpaRp(sub_harga));
                        
                        var itemId = $(this).data('item').item_id;
                        var satuanId = $(this).data('item').satuan_id;
                        $btnIdentitas.attr('href', baseAppUrl + 'modal_identitas/' + itemId + '/' + satuanId + '/' + rowId);
                        addItemRow();

                        setHarga();
                        e.preventDefault();
                    }else{
                        var harga = parseInt($(this).data('item').harga),
                            sub_harga = 0 * harga;

                        $itemId.val($(this).data('item').item_id);            
                        $itemInvetoryId.val($(this).data('item').id);            
                        $itemGudangId.val($(this).data('item').gudang_id);            
                        $itemPmbId.val($(this).data('item').pmb_id);            
                        $itemKode.val($(this).data('item').item_kode);
                        $itemSatuanId.val($(this).data('item').satuan_id);
                        $itemNama.val($(this).data('item').item_nama);
                        $itemInputHarga.val(harga);
                        $itemInputSubHarga.val(sub_harga);


                        $itemLabelKode.text($(this).data('item').item_kode);
                        $itemLabelNama.text($(this).data('item').item_nama);

                        // $itemInputJumlahDokter.attr('value', $('input#jumlah_produksi').val());
                        // $itemLabelJumlahDokter.text($('input#jumlah_produksi').val());
                        
                        $itemInputJumlahDokter.attr('value', 0);
                        $itemLabelJumlahDokter.text(0);

                        $itemLabelJumlah.text(0);
                        $itemLabelSatuan.text($(this).data('item').satuan);
                        
                        $itemLabelHarga.text(mb.formatTanpaRp(harga));
                        $itemLabelSubHarga.text(mb.formatTanpaRp(sub_harga));

                        // $btnIdentitas.attr('href', baseAppUrl + 'modal_identitas/' + $(this).data('item').id + '/' + $(this).data('satuan_id').id + '/' + rowId);
                        var itemId = $(this).data('item').item_id;
                        var satuanId = $(this).data('item').satuan_id;
                        $btnIdentitas.attr('href', baseAppUrl + 'modal_identitas/' + itemId + '/' + satuanId + '/' + rowId);

                        setHarga();

                    }
                    
                }
        });     
    };
    
    var handleBtnSearchItem = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        var rowStatus  = $btn.closest('tr').prop('class');
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
            $('input[name="rowItemId"]', $popcontent).val(rowId);
            
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

    var setHarga = function(){
        $classrow   = $('.table_item', $tableKomposisiRacikan);
        hargaAll    = $('input[name$="[item_sub_harga]"]', $classrow);

        harga = 0;
        $.each(hargaAll, function(idx, value){
            // alert(itemId);
            if ($(this).val() != "") {
                harga = harga + parseInt(this.value);
                // alert(harga);
            };
        });
        // alert(harga);
        $('label#sub_total').text(mb.formatTanpaRp(harga));
        $('input#sub_total').val(harga);

        var biaya_tambahan = parseInt($('input#biaya_tambahan').val());

        $('label#harga_jual').text(mb.formatTanpaRp(harga + biaya_tambahan));
        $('input#harga_jual').val(harga + biaya_tambahan);
    };

    var selectGudang = function(){
        $('select#gudang').on('change', function(){
            // alert($(this).val());
            $tableItemSearch.api().ajax.url(baseAppUrl + 'listing_search_item/' + $(this).val()).load();
            
        });
    }

    var handleInfoIdentitas = function(){
        var $btnCheckIdentitas = $('.check-identitas');

        $btnCheckIdentitas.on('click', function(){

            var id = $(this).data('row-check');
            // alert(id);
            if ($('input#items_jumlah_'+id).val() == 0) {
                // alert('masih 0');
                $('a#info_identitas_'+id).click();
            }else{
                // alert('sudah ada');
                var msg = $(this).data('confirm');
                bootbox.confirm(msg, function(result) {
                    if (result==true) {
                        $('a#info_identitas_'+id).click();
                    }
                });
            }

        });
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/racik_obat/';
        // handleValidation();
        handleDataTableItems();
        handleDataTableKomposisiManual();
        initForm(); 
        handleConfirmSave();
        handleBiayaTambahan();
        handleJumlahProduksi();
        handleDatePickers();
        handleConfirmModalOK();
        handleConfirmModalCancel();
        selectGudang();
        handleInfoIdentitas()

        // setHarga();
        // handleIdentitas();
        // alert('a');
    };

}(mb.app.resep_obat.proses));


// initialize  mb.app.resep_obat.proses
$(function(){
    mb.app.resep_obat.proses.init();
});