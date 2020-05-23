mb.app.proses = mb.app.proses || {};


(function(o){
    
     var 
        baseAppUrl              = '',
        $form                   = $('#form_proses_titip_setoran'),
        $tableKasirBiaya        = $('#table_add_detail_setoran_biaya')
        ;

    var initForm = function(){
        handleFancybox();
        handleConfirmSave();
        handleKasirBiaya();
        handleDatePickers();
        handleTerbilang();
    };

    var handleDatePickers = function(){

        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
            }).on('changeDate', function(){
                $('div.datepicker-dropdown').hide();
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
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

    var handleKasirBiaya = function(){

        $tableKasirBiaya.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'filter'                : false,
            'info'                  : false,
            'paginate'              : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_add_detail_setoran_biaya/' + $('input[name="id"]', $form).val(),
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
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });       
        $('#table_add_detail_setoran_biaya_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_add_detail_setoran_biaya_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown


        $tableKasirBiaya.on('draw.dt', function (){
            
            var total_setoran_biaya = $('input[name="total_biaya"]', this).val();
            var total = 0;
            
            if(!isNaN(total_setoran_biaya)){
                total = total_setoran_biaya;
                $('label#jumlah_bon').text(mb.formatRp(parseInt(total_setoran_biaya)));
                $('input#rupiah_bon').val(total_setoran_biaya);
                $('input#rupiah_bon').attr('value',total_setoran_biaya);
            }else{
                $('label#jumlah_bon').text(mb.formatRp(parseInt(0)));
                $('input#rupiah_bon').val(0);
                $('input#rupiah_bon').attr('value',0);
            }
            
            $('select#jenis_bayar', $form).on('change', function(){
                var val = $(this).val();

                if(val == '1'){
                    $('div#div_cek').removeClass('hidden');
                    $('div#div_transfer').addClass('hidden');
                }else if(val == '2'){
                    $('div#div_cek').addClass('hidden');
                    $('div#div_transfer').removeClass('hidden');
                }else{
                    $('div#div_cek').removeClass('hidden');
                    $('div#div_transfer').removeClass('hidden');
                }
            });
        });
    };


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

    var handleTerbilang = function(){
        $('input#nominal').on('change', function(){
            $.ajax
            ({
                type: 'POST',
                url: baseAppUrl +  "get_terbilang",  
                data:  {nominal:$(this).val()},  
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(data)          //on recieve of reply
                { 
                    
                  $('label#terbilang_nominal').text(data.terbilang);
                
                },
                complete : function(){
                  Metronic.unblockUI();
                }
            });
        });           
    }

    

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/proses_terima_setoran/';
        initForm();
    };

}(mb.app.proses));

$(function(){    
    mb.app.proses.init();
});