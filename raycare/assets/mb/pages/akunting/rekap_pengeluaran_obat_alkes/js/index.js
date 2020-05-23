mb.app.rekap_pengeluaran_obat_alkes = mb.app.rekap_pengeluaran_obat_alkes || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form      = $('#form_rekap_pengeluaran_obat_alkes'),
        $lastPopoverItem                = null,
        $tablePengeluaranObatAlkes = $('#table_rekap_pengeluaran_obat_alkes'),
        $tablePasien = $('#table_pasien'),
        $tableObatAlkes = $('#table_obat_alkes');

    var handleDateRangePicker = function() {
        var penjamin = $('select#penjamin').val();
        var shift = $('select#shift').val();

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
            // oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/' + resepsionis + '/' + shift + '/' + penjamin).load();
            });
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
        $('input#tgl_awal').val(moment().format('D-MM-YYYY'));
        $('input#tgl_akhir').val(moment().format('D-MM-YYYY'));
    };

    var handleDataTable = function() 
    {
		var penjamin = $('select#penjamin').val();
		var shift = $('select#shift').val();

    	oTable = $tablePengeluaranObatAlkes.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/' + '/' + shift + '/' + penjamin,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'filter'                : false,
            'paginate'              : false,
            'info'                  : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'invoice.id id','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'invoice.tanggal tanggal','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'invoice.no_invoice no_invoice','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'tindakan_hd.penjamin_id penjamin_id','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'invoice.harga harga','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'invoice.id id','visible' : true, 'searchable': true, 'orderable': false },
                ]
        });

        oTablePasien = $tablePasien.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pasien' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/' + '/' + shift + '/' + penjamin,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'filter'                : false,
            'paginate'              : false,
            'info'                  : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'pasien.id id','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'tindakan_hd_history.tanggal tanggal','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'tindakan_hd_history.penjamin_id penjamin_id','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                ]
        });

        oTableAlkesObat = $tableObatAlkes.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'pagingType'		    : 'full_numbers',
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_obat_alkes' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/' + '/' + shift + '/' + penjamin,
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'filter'				: false,
			'paginate'				: false,
			'info'					: false,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
                { 'name':'pasien.id id','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name':'invoice.tanggal tanggal','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name':'invoice_detail.nama_tindakan nama_tindakan','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'SUM(invoice_detail.qty) qty','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'item_satuan.nama nama_satuan','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'SUM(invoice_detail.qty * invoice_detail.harga) total_harga','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'SUM(invoice_detail.diskon_nominal) total_diskon','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name':'SUM((invoice_detail.qty * invoice_detail.harga) - invoice_detail.diskon_nominal) grand_total','visible' : true, 'searchable': true, 'orderable': false },
        		]
        });
    }

    var handleBtnCari = function(){

		$('a#cari').click(function(){
			var date = $('input#month_year').val();
			var penjamin = $('select#penjamin').val();
			var shift = $('select#shift').val();
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/'  + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/' + '/' + shift + '/' + penjamin).load();
            oTablePasien.api().ajax.url(baseAppUrl +  'listing_pasien' + '/'  + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/' + '/' + shift + '/' + penjamin).load();
			oTableAlkesObat.api().ajax.url(baseAppUrl +  'listing_obat_alkes' + '/'  + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/' + '/' + shift + '/' + penjamin).load();
		})
	};



    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'akunting/rekap_pengeluaran_obat_alkes/';
        handleDateRangePicker();
        handleDataTable();
        handleBtnCari();
    };
 }(mb.app.rekap_pengeluaran_obat_alkes));


// initialize  mb.app.home.table
$(function(){
    mb.app.rekap_pengeluaran_obat_alkes.init();
});