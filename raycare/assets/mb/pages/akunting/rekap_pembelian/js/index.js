mb.app.rekap_pembelian = mb.app.rekap_pembelian || {};
(function(o){

    var 
        baseAppUrl             = '',
        $form                  = $('#form_rekap_pembelian'),
        $tableRekap                  = $('#table_rekap');

    var handleDataTable = function(){
        oTable = $tableRekap.dataTable({
          'processing'            : true,
          "scrollX"               : "100%",
          "scrollCollapse"        : true,
          'pagingType'            :'full_numbers',
          'info'                : false,
          'paginate'            : false,
          'sort'                : false,
          'filter'                : false,
          'language'              : mb.DTLanguage(),
            
        });
    }
    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format: "d-M-yyyy",
                orientation: "right",
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        $('select.select2').select2();
    }

    var handleBtnRefresh = function(){
        $('a#refresh').click(function(){
            var tanggal_awal = $('input#tgl_awal').val(),
                tanggal_akhir = $('input#tgl_akhir').val(),
                supplier_id = $('select#supplier_id').val(),
                string = $('select#item_id').val(),
                item = string;

                if(string != null){
                    string = string.toString();
                    item = string.replace(/,/g , "-");
                }
            

            location.href = baseAppUrl + 'search/' + tanggal_awal + '/' + tanggal_akhir + '/' +supplier_id+'/'+item;
        });

        $('a#cetak').click(function(){
            var tanggal_awal = $('input#tgl_awal').val(),
                tanggal_akhir = $('input#tgl_akhir').val(),
                supplier_id = $('select#supplier_id').val(),
                string = $('select#item_id').val(),
                item = string;

                if(string != null){
                    string = string.toString();
                    item = string.replace(/,/g , "-");
                }
            

            window.open(baseAppUrl + 'cetak/' + tanggal_awal + '/' + tanggal_akhir + '/' +supplier_id+'/'+item, '_blank');
        });
    }
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'akunting/rekap_pembelian/';
        handleDatePickers();
        handleDataTable();
        handleBtnRefresh();
      
    };
 }(mb.app.rekap_pembelian));


// initialize  mb.app.home.table
$(function(){
    mb.app.rekap_pembelian.init();
});