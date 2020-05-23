mb.app.arus_kas_bank = mb.app.arus_kas_bank || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form   = $('#form_index'),
        $table1 = $('#table_arus_kas_bank')
        ;

    var handleDataTable = function() 
    {
         var tgl_awal = $("input#tgl_awal").val(),
            tgl_akhir = $("input#tgl_akhir").val(),
            bank_id = $('select#bank_id').val();


        oTable = $table1.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing/' + tgl_awal + '/' + tgl_akhir +'/'+bank_id,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'filter'                : false,
            'paginate'              : false,
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
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
        $table1.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker

            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          keuangan_arus_kas_id    = $anchor.data('keuangan_arus_kas_id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,keuangan_arus_kas_id,msg);
            });

            var grandtotal_debit =  parseInt($('input#grandtotal_debit', this).val());
            var grandtotal_kredit =  parseInt($('input#grandtotal_kredit', this).val());
            var grandtotal_saldo =  parseInt($('input#grandtotal_saldo', this).val());

            if (!isNaN(grandtotal_debit)) {
                $('.grandtot_debit').text(mb.formatRp(grandtotal_debit));
            }else{
                $('.grandtot_debit').text("");
            };

            if (!isNaN(grandtotal_kredit)) {
                $('.grandtot_kredit').text(mb.formatRp(grandtotal_kredit));
            }else{
                $('.grandtot_kredit').text("");
            };

            if (!isNaN(grandtotal_saldo)) {
                $('.grandtot_saldo').text(mb.formatRp(grandtotal_saldo));
            }else{
                $('.grandtot_saldo').text("");
            };

                    
        });
    }

    var handleDateRangePicker = function() {
        var bank_id = $('select#bank_id').val();

        $('#reportrange').daterangepicker({
            opens: (Metronic.isRTL() ? 'left' : 'right'),
            startDate: moment().subtract('days', 29),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2020',
            dateLimit: {
                days: 60
            },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Last 30 Days': [moment().subtract('days', 29), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            buttonClasses: ['btn'],
            applyClass: 'green',
            cancelClass: 'default',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            locale: {
                applyLabel: 'Apply',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        },
        function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('input#tgl_awal').val(start.format('D-MM-YYYY'));
            $('input#tgl_akhir').val(end.format('D-MM-YYYY'));
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val() +'/' + bank_id).load();
            });
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().startOf('month').format('MMMM D, YYYY') + ' - ' + moment().endOf('month').format('MMMM D, YYYY'));
        $('input#tgl_awal').val(moment().startOf('month').format('D-MM-YYYY'));
        $('input#tgl_akhir').val(moment().endOf('month').format('D-MM-YYYY'));
    };

    var handleChangeBank = function(){
        $('select#bank_id').on('change', function(){
            var bank_id = $(this).val();
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val() +'/' + bank_id).load();

        });
    }

    var handleDeleteRow = function(id,keuangan_arus_kas_id,msg) {
        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete/' +id+'/'+keuangan_arus_kas_id+'/'+$('select#bank_id').val();
            } 
        });
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/rekening_koran/';
        handleDateRangePicker();
        handleDataTable();
        handleChangeBank();

    };
 }(mb.app.arus_kas_bank));


// initialize  mb.app.home.table
$(function(){
    mb.app.arus_kas_bank.init();
});