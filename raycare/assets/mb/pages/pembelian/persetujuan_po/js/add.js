mb.app.daftar_permintaan_po = mb.app.daftar_permintaan_po || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableDetailPembelian   = $('#table_detail_pembelian'),
        $form                   = $('#form_add_persetujuan');
       

     var initform = function()
    {
        // alert("a");
        $('input[name$="[jumlah]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();

            if($('input[name$="[jumlah]"]').val() < $('input[name$="[jumlah_min]"]').val())
            {
                bootbox.confirm('Jumlah Item Tidak Boleh Kurang Dari Permintaan', function(result) {
                        if (result==true) {
                            
                        }
                    });
            }
        });

        calculateTotal();
    }

     var calculateTotal = function(){
        // alert('masuk function');
        var 
            $rows     = $('tbody>tr', $tableDetailPembelian), 
            $sub_total = $('.sub_total', $tableDetailPembelian),
            cost = 0,
            totalCost = 0,
            grandTotal = 0,
            grandTotalAll = 0,
            jumlah_tolak = 0

        ;

        $.each($rows, function(idx, row)
        {
            var 
                $row     = $(row), 
                itemCode = $('label[name$="[item_kode]"]', $row).text(),
                harga = parseFloat($('input[name$="[item_harga]"]', $row).val()),
                diskon     = parseFloat($('input[name$="[item_diskon]"]', $row).val()*1),
                jumlah     = parseFloat($('input[name$="[jumlah]"]', $row).val()*1),
                jumlah_order     = parseFloat($('input[name$="[jumlah_order]"]', $row).val()*1)
            ;
            // alert($('input[name$="[item_harga]"]', $row).val());

            if (itemCode != '' ){
                if(!isNaN(jumlah))
                {
                    cost = harga-(harga*diskon/100);
                    // alert(cost);
                    totalCost = cost*jumlah;
                    jumlah_tolak = jumlah_order - jumlah;

                    $('label[name$="[item_sub_total]"]', $row).text(mb.formatRp(totalCost));
                    $('input[name$="[item_sub_total]"]', $row).val(totalCost);
                    $('input[name$="[jumlah_tolak]"]', $row).val(jumlah_tolak);
                    $('input[name$="[jumlah_tolak]"]', $row).attr('value',jumlah_tolak);
                }
                else
                {
                    jumlah  = parseFloat($('label[name$="[jumlah]"]', $row).text())*1;
                    // alert(jumlah);
                    cost = harga-(harga*diskon/100);
                    // alert(cost);
                    totalCost = cost*jumlah;
                    jumlah_tolak = jumlah_order - jumlah;
                    
                    $('label[name$="[item_sub_total]"]', $row).text(mb.formatRp(totalCost));
                    $('input[name$="[item_sub_total]"]', $row).val(totalCost);
                    $('input[name$="[jumlah_tolak]"]', $row).attr('value',jumlah_tolak);
                    $('input[name$="[jumlah_tolak]"]', $row).val(jumlah_tolak);
                }

            }
        });

        $.each($sub_total, function(){
            grandTotal = grandTotal + parseFloat($(this).val());
        });

        $('input#total').val(mb.formatTanpaRp(grandTotal));
        $('input#total_hidden').val(grandTotal);

        grandTotalAD = grandTotal - parseFloat(grandTotal * parseFloat($('input#diskon').val())/100);

        $('input#total_after_disc').val(mb.formatTanpaRp(grandTotalAD));
        $('input#total_after_disc_hidden').val(grandTotalAD);

        grandTotalAT =  grandTotalAD + parseFloat(grandTotalAD * parseFloat($('input#pph').val())/100);       
        
        $('input#total_after_tax').val(mb.formatTanpaRp(grandTotalAT));
        $('input#total_after_tax_hidden').val(grandTotalAT);

        var pph23 = parseFloat($('input#pph23_nominal').val());
        var biaya_tambahan = parseFloat($('input#biaya_tambahan_hidden').val());
        
        grandTotalAll =  grandTotalAT  - pph23 + biaya_tambahan;       
        $('input#grand_total').val(mb.formatTanpaRp(grandTotalAll));
        $('input#grand_total_hidden').val(grandTotalAll);
    };

    jQuery('#table_detail_pembelian .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
                $('input#total').val(0);
                $('input#grand_total').val($('input#biaya_tambahan').val())
            } else {
                $(this).attr("checked", false);
                calculateTotal();
            }                    
        });
        jQuery.uniform.update(set);
    });

    $('#table_detail_pembelian').on('change', 'tbody tr .checkboxes', function(){
        var checked = $(this).is(":checked");
        if (checked) {
            
            var rp      = $(this).data('rp');
            var rpInt   = parseFloat(rp);
            var row = $(this).data('row');

            var harga = parseFloat($('input#total_hidden').val()) - parseFloat($('input#items_total_'+row).val());

            $('input#total').val( mb.formatTanpaRp(harga));
            $('input#total_hidden').val(harga);

        } else {

            var rp      = $(this).data('rp');
            var rpInt   = parseFloat(rp);
            var row = $(this).data('row');

            var harga = parseFloat($('input#total_hidden').val()) + parseFloat($('input#items_total_'+row).val());

            $('input#total').val( mb.formatTanpaRp(harga));
            $('input#total_hidden').val(harga);


        }
    });

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
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/persetujuan_po/';
        // handleDataTablePermintaan();
        // handleDataTable();
        // handleDataTableDraft();
        handleConfirmSave();
        initform();
    };
 }(mb.app.daftar_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_permintaan_po.init();
});