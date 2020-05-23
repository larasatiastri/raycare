mb.app.gudang = mb.app.gudang || {};
(function(o){

    var 
        baseAppUrl              = '',
        identitasCounter = $('input#identitasCounter').val()
        ;

    var initform = function()
    {
        total_identitas();
        getIdentitasBefore();
        addIdentitasRow();
        hitungSatuanHasilPenggabungan();
        handleDatePickers();
        handleBtnDelete();
        tambahIdentitasRow();
        handleSaveDetailDraft();
    }

    var total_identitas = function(){
        row_id = $('input#row_id_modal_identitas').val();
        $row        = $('tr#'+row_id, $('#table_pembelian_detail'));

        total_identitas = $('input[name$="[jumlah_masuk]"]', $row).val();
        satuan_nama = $('label[name$="[satuan_jumlah_masuk]"]', $row).text();

        $('label#total_identitas_modal').text(total_identitas);
        $('label#satuan_item').text(satuan_nama);

        
        // $('input[name$="[jumlah_persatuan]"]').val($('input[name$="[jumlah_per_satuan]"]', $row).val());
    }

    var getIdentitasBefore = function()
    {
        row_id = $('input#row_id_modal_identitas').val();
        $row        = $('tr#'+row_id, $('#table_pembelian_detail'));
        $countRow        = $('tr', $('#simpan_identitas', $row));

        $('table#table_identitas > tbody').html($('div#simpan_identitas', $row).html());
        $('table#table_identitas input[name$="[jumlah]"]').addClass('jumlah_item');
    };

    var addIdentitasRow = function()
    {
        row_id = $('input#row_id_modal_identitas').val();
        $row        = $('tr#'+row_id, $('#table_pembelian_detail'));
        
        if (isNaN(identitasCounter)) {
            identitasCounter = 0;
        };   

        var tplIdentitas     = $.validator.format( $('#tpl_identitas').text()),
        $tableIdentitas  = $('#table_identitas');


        var numRow = $('tbody tr', $tableIdentitas).length;

        console.log('numrow' + numRow);


        var 
            $rowContainer  = $('tbody', $tableIdentitas),
            $newItemRow    = $(tplIdentitas(identitasCounter++)).appendTo( $rowContainer ),
            $btnSearchItem = $('.search-item', $newItemRow),
            $noUrut        = $('td.no_urut', $newItemRow);
            $inputJumlah   = $('input.jumlah_item', $newItemRow);

        // $('select.select-indentitas', $newItemRow).val('');
        hitungSatuanHasilPenggabungan();

        if (typeof($('input[name$="[jumlah_per_satuan_awal]"]', $row).val()) != "undefined") {
            $('input.jumlah_per_satuan', $newItemRow).val($('input[name$="[jumlah_per_satuan_awal]"]', $row).val());
        }else{
            $('input.jumlah_per_satuan', $newItemRow).val(1);
        }


        $noUrut.text(identitasCounter-1);
        handleDatePickers();

        $('input#identitasCounter').val(identitasCounter);
        handleBtnDeleteRow( $('.del-this', $newItemRow) );
        handleBtnSaveAjax( $('.save_ajax', $newItemRow) );
        handleInputChange( $('.send-input', $newItemRow),  $('.save_ajax', $newItemRow));
        handleSelectSatuan( $('.satuan', $newItemRow),  $('.nilai_konversi', $newItemRow));
    };

    var tambahIdentitasRow = function()
    {
        $('a#tambah_identitas').on('click', function(){
            addIdentitasRow();
        });
    }

    var hitungSatuanHasilPenggabungan = function(){
        row_id = $('input#row_id_modal_identitas').val();
        $row        = $('tr#'+row_id, $('#table_pembelian_detail'));

        if (typeof($('input[name$="[jumlah_per_satuan_awal]"]', $row).val()) != "undefined") {
            $('input[name$="[jumlah]"]').on('change keyup', function(){
                thisRow = $(this).data('row');
                jumlah_per_satuan = parseInt($('input[name$="[jumlah_per_satuan_awal]"]', $row).val());
                jumlah = parseInt($(this).val());
                total = jumlah_per_satuan * jumlah;

                // alert(jumlah_per_satuan);
                $('input#identitas_jumlah_per_satuan_'+ thisRow).val(total);
                $('input#identitas_jumlah_per_satuan_'+ thisRow).attr('value', total);
            });
        }else{
            $('input[name$="[jumlah]"]').on('change keyup', function(){
                thisRow = $(this).data('row');
                jumlah = parseInt($(this).val());

                // alert(jumlah_per_satuan);
                $('input#identitas_jumlah_per_satuan_'+ thisRow).val(jumlah);
                $('input#identitas_jumlah_per_satuan_'+ thisRow).attr('value', jumlah);
            });
        }        
    }

    var handleDatePickers= function(){


        if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
                // autoclose: true
            }).on('changeDate', function(ev){
                $('.datepicker-dropdown').hide();
            });
        }
    }

    var handleBtnDeleteRow = function($btn){
        var numRow = $('tbody tr', $('table#table_identitas')).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $('table#table_identitas'));

        $btn.on('click', function(e){
            
            $row.remove();
            if($('tbody>tr', $('table#table_identitas')).length == 0){
                addIdentitasRow();
            }
            
            e.preventDefault();
        });
    };

    var handleBtnDelete = function(){
        $('a.del-this').on('click', function(e){
            var rowId = $(this).data('row');

            var $row     = $('#identitas_row_'+rowId, $('table#table_identitas'));    
            
                $row.remove();
                if($('tbody>tr', $('table#table_identitas')).length == 0){
                    addIdentitasRow();
                }

            e.preventDefault();
        });
    };

    var handleSaveDetailDraft = function(){
        $('a#save_draft').on('click', function(e){
           
           $('a.save_ajax').click();

           location.href = baseAppUrl+'proses/'+$('input#draft_id').val()+'/'+$('input#supplier_id').val()+'/'+$('input#gudang_id').val();
        });
    };

    var handleBtnSaveAjax = function($btn)
    {
 
        $btn.on('click', function(){

            $row = $(this).data('row');
            alert($row);

            $('input#row_id').val($row);
            $form_identitas = $('#form_add_barang_datang');

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_draft_detail',
                data     : $form_identitas.serialize(),
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    $btn.attr('disabled', true);
                    $('input#identitas_draft_'+$row).val(results.id);
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });

        })
    }

    var handleInputChange = function($input, $btn)
    {
        $input.on('change', function(){
            $btn.attr('disabled', false);
        })
    }

    var handleSelectSatuan = function($select, $input)
    {
        $select.on('change', function(){
            $konversi    = $('option:selected', this).attr('data-konversi');

            $input.val($konversi);
        })
    }
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl()+'gudang/barang_datang_farmasi/'
        initform();
        tambahIdentitasRow();
        handleSaveDetailDraft();
    };
 }(mb.app.gudang));


// initialize  mb.app.home.table
$(function(){
    mb.app.gudang.init();
});