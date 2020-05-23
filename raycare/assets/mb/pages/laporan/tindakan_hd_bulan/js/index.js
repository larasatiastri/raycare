mb.app.tindakan_hd_bulan = mb.app.tindakan_hd_bulan || {};
(function(o){

    var 
        baseAppUrl             = '',
        $tableTindakanBulanan        = $('#table_tindakan_bulanan');

    var handleDataTable = function() 
    {
        var tgl_awal = $("#tgl_awal").val(),
            tgl_akhir = $("#tgl_akhir").val(),
            penjamin = $('select#penjamin').val();

        oTable = $tableTindakanBulanan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing'+ '/' + tgl_awal + '/' + tgl_akhir +'/' +penjamin,
                'type' : 'POST',
            },          
            
            'pageLength'            : 25,
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
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
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
        $tableTindakanBulanan.on('draw.dt', function (){
            $('.btn', this).tooltip();
            $('input[type=checkbox]', this).uniform();
            
        } );
    }

    var handleDateRangePicker = function() {
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
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val() +'/' + $('select#penjamin').val()).load();
            $('a#cetak_pdf').attr('href',baseAppUrl+'cetak_pdf/'+$("#tgl_awal").val() + '/' + $("#tgl_akhir").val() +'/' + $('select#penjamin').val());
        });
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().subtract('month', 1).startOf('month').format('MMMM D, YYYY') + ' - ' + moment().subtract('month', 1).endOf('month').format('MMMM D, YYYY'));
        $('input#tgl_awal').val(moment().subtract('month', 1).startOf('month').format('D-MM-YYYY'));
        $('input#tgl_akhir').val(moment().subtract('month', 1).endOf('month').format('D-MM-YYYY'));
        $('a#cetak_pdf').attr('href',baseAppUrl+'cetak_pdf/'+$("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+'/' + $('select#penjamin').val());
    };

    var handleButtonRefresh = function(){
        $('a#refresh').click(function(){
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+'/' + $('select#penjamin').val()).load();
            $('a#cetak_pdf').attr('href',baseAppUrl+'cetak_pdf/'+$("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+'/' + $('select#penjamin').val());
        });
    };

    var handleSelectPenjamin = function(){
        $('select#penjamin').on('change', function(){
            var penjamin_id = $(this).val();
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+'/' + penjamin_id).load();
            $('a#cetak_pdf').attr('href',baseAppUrl+'cetak_pdf/'+$("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+'/' + penjamin_id);

        });
    }
    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'laporan/tindakan_hd_bulan/';
        handleDateRangePicker();
        handleDataTable();
        handleButtonRefresh();
        handleSelectPenjamin();
    };
 }(mb.app.tindakan_hd_bulan));


// initialize  mb.app.home.table
$(function(){
    mb.app.tindakan_hd_bulan.init();
});