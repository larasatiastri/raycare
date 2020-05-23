mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl                    = '',
        $form                         = $('#form_view_permintaan_po'),
        $formModals                   = $('#form_modal_permintaan_po'),
        $tableAddAccount              = $('#table_add_account', $form),
        $tableAddAccountTitipan       = $('#table_add_account_titipan', $form),
        $tableAccountSearch           = $('#table_account_search'),
        $tableItemTerdaftar           = $('#table_item_terdaftar'),
        $tableItemBoxPaket            = $('#table_item_box_paket'),
        $tableItemTidakTerdafar       = $('#table_item_tidak_terdaftar'),
        $tablePilihItem               = $('#table_pilih_item_user'),
        $tablePilihItemTidakTerdaftar = $('#table_pilih_item_tidak_terdaftar_user'),
        $tablePilihItemBox            = $('#table_pilih_item_user_box'),
        
        $tablePilihUser               = $('#table_pilih_user'),
        $tableInformation             = $('#table_information'),
        $popoverItemContent           = $('#popover_item_content'),
        $popoverItemContentTindakan   = $('#popover_item_content_tidak_terdaftar'),
        $popoverItemContentBox        =$('#popover_item_content_box'),
        
        $popoverPasienContent         = $('#popover_pasien_content'), 
        $lastPopoverItem              = null,
        $lastPopoverIdentitas         = null,
        
        tplItemRow                    = $.validator.format( $('#tpl_item_row').text() ),
        tplItemAccRow                 = $.validator.format( $('#tpl_item_acc_row').text() ),
        itemCounter                   = 0,
        itemCounter1                  = 0,
        tplFormGambar                 = '<li class="fieldset">' + $('#tpl-form-gambar', $form).val() + '<hr></li>',
        regExpTplGambar               = new RegExp('gambar[0]', 'g'),   // 'g' perform global, case-insensitive
        gambarCounter                 = 1,
        
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
            $btnDeletes              = $('.del-this', $tableAddAccount);
            $btnDeletestitipan       = $('.del-this-plus', $tableAddAccountTitipan);

        handleBtnSearchAccount($btnSearchAccount);  


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

        
        handlePortletItem();
        

    };

    var handlePortletItem = function()
    {

        var  $portlet_item = $('input#tipe').val();

        if($portlet_item == 1)
        {

            $('div#section_terdaftar').removeClass('hidden');
            $('div#section_tidak_terdaftar').addClass('hidden');

        } else {

            $('div#section_terdaftar').addClass('hidden');
            $('div#section_tidak_terdaftar').removeClass('hidden');


        }

    }

  

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

        handleUnggahGambar ( $('.unggah-gambar', $newItemRow) );

        // $('.type', $newItemRow).bootstrapSwitch();
        // handleBootstrapSelectType($('.type', $newItemRow));

    };
     
     var handleTambahRowPelengkap = function(){
        $('a#add_biaya', $form).click(function() {
            addItemAccRow();
        });
    };

    var handlePilihItemBox = function(){
       pk_value = $('input#pk_value').val();
       user_level_id = $('input#user_level_id').val();
        oTablePilihItemBox = $tablePilihItemBox.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_item_user_box/' + pk_value + '/' + user_level_id,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });       
        $('#table_pilih_item_user_box_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_user_box_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContentBox.hide();        
    };

    var handlePilihItem = function(){
        oTablePilihItem = $tablePilihItem.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_item_user_view_history',
                'type' : 'POST',
            }, 
            'filter'                : false,
            'info'                  : false,
            'paginate'              : false,         
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });       
        $('#table_pilih_item_user_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_user_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContent.hide();        
    };

    var handlePilihItemTidakTerdaftar = function(){
        oTablePilihItemTidakTerdaftar = $tablePilihItemTidakTerdaftar.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_item_user_tidak_terdaftar_view_history',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });       
        $('#table_pilih_item_tidak_terdaftar_user_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_tidak_terdaftar_user_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContentTindakan.hide();        
    };

    var handleDataTableItemsBoxPaket = function(){

       pk_value = $('input#pk_value').val();
       user_level_id = $('input#user_level_id').val();
       oTable = $tableItemBoxPaket.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            // 'sAjaxSource'              : baseAppUrl + 'listing_alat_obat',
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_box_paket/' + pk_value + '/' + user_level_id,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'name' : 'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });       
        $('#table_item_box_paket_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_box_paket_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

    

        $tableItemBoxPaket.on('draw.dt', function (){
            
            $('.btn', this).tooltip();

           

            $popoverItemContent.hide();

            var $identitasItem = $('.pilih-item-box', this);

            $.each($identitasItem, function(idx, col){
                var
                    $col            = $(col),
                    dataItem        = $col.data('item');

                // console.log(dataIdentitas);
                $col.popover({
                    html : true,
                    container : 'body',
                    placement : 'bottom',
                    content: function(){
                        
                        var html = '<table class="table table-striped table-hover">';
                            html += '<tr class="heading bold">';
                            html += '<td class="text-center">Kode</td>'
                            html += '<td class="text-center">Nama</td>'
                            html += '<td class="text-center">Jumlah</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-center">' + item.kode_item + '</td>'
                            html += '<td class="text-center">' + item.nama_item + '</td>'
                            html += '<td class="text-center">' + item.jumlah + ' ' + item.nama_satuan +'</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverIdentitas !== null) $lastPopoverIdentitas.popover('hide');
                    $lastPopoverIdentitas = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverIdentitas = null;
                }).on('click', function(e){

                });
            });
        });
    };



    var handleDataTableItems = function(){

       pk_value = $('input#pk_value').val();
       user_level_id = $('input#user_level_id').val();
       oTable = $tableItemTerdaftar.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_history/' + pk_value + '/' + user_level_id,
                'type' : 'POST',
            },         
            'filter'                : false,
            'info'                  : false,
            'paginate'              : false,
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });       
        $('#table_item_terdaftar_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_terdaftar_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown


            $tableItemTerdaftar.on('draw.dt', function (){
                
                $('.btn', this).tooltip();

                

                var $btnSearchItem  = $('.pilih-item-terdaftar-user', this);

                $btnSearchItem.click(function(){
                    
                    id                                = $(this).data('id');
                    persetujuan_permintaan_barang_id  = $(this).data('persetujuan');
                    order_permintaan_barang_detail_id = $(this).data('detail-id');
                    order                             = $(this).data('order');
                    level_id                          = $(this).data('level-id');

                    oTablePilihItem.api().ajax.url(baseAppUrl + 'listing_pilih_item_user_view_history/' + id + '/'+level_id+'/'+order_permintaan_barang_detail_id + '/' + order).load();

                });
                handleBtnSearchItem($btnSearchItem);

                $popoverItemContent.hide();
                // var $btnSelect = $('a.select', this);
                
                // handleAccountSelect( $btnSelect );
                
            });
            
            // $popoverItemContent.hide();        

    };

    var handleBtnSearchItem = function($btn){
        var rowId                             = $btn.closest('tr').prop('id');
            id                                = $btn.data('id');
            persetujuan_permintaan_barang_id  = $btn.data('persetujuan');
            order_permintaan_barang_detail_id = $btn.data('detail-id');
            order                             = $btn.data('order');
            level_id                          = $btn.data('level-id');

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

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
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    jQuery('#table_item_box_paket .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
        var status = $(this).data('status');

        // alert(status);

            jQuery(set).each(function () {
             if (checked) {

                $(this).attr("checked", true);
            
            } else {
            
                $(this).attr("checked", false);
            
            }                    
        });
        jQuery.uniform.update(set);
    });

     jQuery('#table_item_terdaftar .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
        var status = $(this).data('status');

        // alert(status);

            jQuery(set).each(function () {
             if (checked) {

                $(this).attr("checked", true);
            
            } else {
            
                $(this).attr("checked", false);
            
            }                    
        });
        jQuery.uniform.update(set);
    });

    var status_checkbox = function(){

        var checkbox = $('input#checkbox_').val();
        alert(checkbox);

    };

     jQuery('#table_item_tidak_terdaftar .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");

                jQuery(set).each(function () {
             if (checked) {

                $(this).attr("checked", true);
            
            } else {
            
                $(this).attr("checked", false);
            
            }                    
        });
        jQuery.uniform.update(set);
    });

    var handleDataTableItemsTitipan = function(){
       pk_value = $('input#pk_value').val();
       user_level_id = $('input#user_level_id').val();
        oTable2 = $tableItemTidakTerdafar.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_tidak_terdaftar_history/' + pk_value + '/' + user_level_id,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'filter'                : false,
            'paginate'              : false,
            'info'                  : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_item_tidak_terdaftar_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_tidak_terdaftar_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableItemTidakTerdafar.on('draw.dt', function (){

            $('.btn', this).tooltip();

            $('a.pilih-item-tidak-terdaftar-user', this).click(function(){
                // alert('klik');
            var $anchor = $(this),
                  id    = $anchor.data('id');
                  persetujuan_permintaan_barang_id = $anchor.data('persetujuan');
                  order_permintaan_barang_detail_id = $anchor.data('detail-id');
                  
                  level_id = $anchor.data('level-id');

            // oTablePilihItem.api().ajax.url(baseAppUrl + 'listing_pilih_item/' + id).load();
            oTablePilihItemTidakTerdaftar.api().ajax.url(baseAppUrl + 'listing_pilih_item_user_tidak_terdaftar_view_history/' + id+'/'+level_id+'/'+order_permintaan_barang_detail_id ).load();
            
            });

            var $btnSearchItem  = $('.pilih-item-tidak-terdaftar-user');
            handleBtnSearchAccountTitipan($btnSearchItem);

            $popoverItemContentTindakan.hide();
            
        });

        // $popoverItemContentTindakan.hide();        
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
                $itemSatuanEl    = $('select[name$="[satuan]"]', $row);
                $('.search-account', $tableAddAccount).popover('hide');
           
            $itemIdEl.val($(this).data('item')['id']);
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
                $itemSatuanEl.append($("<option></option>")
                    .attr("value", value.id).text(value.nama));
                $itemSatuanEl.val(primary.id);
            });


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

            $popContainer.css({  minWidth: '1024px', maxWidth: '1024px'});

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

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

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
        var time = new Date($('#tanggal').val());
        if (jQuery().datepicker) {
            $('.date-picker', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                orientation: "left",
                autoclose: true,
                update : time

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
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_user_permintaan_po',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#ttable_pilih_user_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#ttable_pilih_user_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $tablePilihUser);
        handlePilihUserSelect( $btnSelects );

        $tablePilihUser.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihUserSelect( $btnSelect );
            
        } );

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

     var handlePilihUserSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefUser   = $('input[name="nama_ref_user"]'),
                $IdRefUser   = $('input[name="id_ref_pasien"]'),
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

    var handleBtnInfoPersetujuanTidakTerdaftar = function()
    {

        $('.pilih-item-tidak-terdaftar-user').click(function(){
            // alert('klik');
        var $anchor = $(this),
              id    = $anchor.data('id');
              level_id = $anchor.data('level-id');
              order_permintaan_barang_detail_id = $anchor.data('op-detail-id');

        oTablePilihItemTidakTerdaftar.api().ajax.url(baseAppUrl + 'listing_pilih_item_user_tidak_terdaftar/' + id + '/' + level_id + '/' + order_permintaan_barang_detail_id).load();
        // oTablePilihItemTidakTerdaftar.api().ajax.url(baseAppUrl + 'listing_pilih_item_tidak_terdaftar/' + id).load();
        
        });

    }

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
        baseAppUrl = mb.baseUrl() + 'pembelian/persetujuan_permintaan_po/';
        handleValidation();
        calculateTotalKeseluruhan();
        initForm();
        handleDatePickers();
        handleSelect2();    
        handleConfirmSave();
        handlePilihItemBox();
        handlePilihItem();
        handlePilihItemTidakTerdaftar();
        handleDataTableItems();
        handleDataTableItemsTitipan();
        handleDataTableItemsBoxPaket();
        handleTambahRowPelengkap();
        handlePilihUser();
        handleUploadify();
        uploadfile();
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});