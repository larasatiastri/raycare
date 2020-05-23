mb.app.laporan_invoice = mb.app.laporan_invoice || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form      = $('#form_laporan_invoice'),
        $lastPopoverItem                = null,
        $tableInvoice = $('#table_laporan_invoice');

    var handleDateRangePicker = function() {
        var resepsionis = $('select#resepsionis').val();
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
    	var resepsionis = $('select#resepsionis').val();
		var date = $('input#month_year').val();
		var penjamin = $('select#penjamin').val();
		var shift = $('select#shift').val();
    	oTable = $tableInvoice.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'pagingType'		    : 'full_numbers',
			'ajax'              	: {
				'url' : baseAppUrl + 'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/' + resepsionis + '/' + shift + '/' + penjamin,
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'filter'				: false,
			'paginate'				: false,
			'info'					: false,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'name':'pembayaran_detail.created_date tanggal','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name':'pembayaran_detail.waktu_tindakan waktu','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name':'pembayaran_detail.no_invoice no_invoice','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name':'pembayaran_detail.resepsionis resepsionis','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name':'pembayaran_detail.nama_penjamin nama_penjamin','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'pembayaran_detail.harga harga','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name':'pembayaran_detail.harga harga','visible' : true, 'searchable': true, 'orderable': false },
        		]
        });
        $tableInvoice.on('draw.dt', function (){
            var totalInvoice = parseInt($('input#input_total', this).val());
        	var totalInvoiceIna = parseInt($('input#input_total_ina', this).val());

        	// alert(totalInvoice);
        	if(!isNaN(totalInvoice))
            {
                $('b#total_invoice').text(mb.formatRp(totalInvoice));
            }
            else
            {
                $('b#total_invoice').text(mb.formatRp(0));
            }

            if(!isNaN(totalInvoiceIna))
        	{
        		$('b#total_invoice_ina').text(mb.formatRp(totalInvoiceIna));
        	}
        	else
        	{
        		$('b#total_invoice_ina').text(mb.formatRp(0));
        	}

            var $btnDetail = $('a.detail_item', this);
            $.each($btnDetail, function(idx, col){
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
                            html += '<td class="text-center">Nama</td>'
                            html += '<td class="text-center">Qty</td>'
                            html += '<td class="text-center">Harga</td>'
                            html += '<td class="text-center">Total</td>'
                            html += '</tr>';


                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.nama_tindakan + '</td>'
                            html += '<td class="text-center">' + item.qty + '</td>'
                            html += '<td class="text-right">' + mb.formatRp(parseInt(item.harga)) + '</td>'
                            html += '<td class="text-right">' + mb.formatRp(parseInt(item.harga)*item.qty) + '</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverItem !== null) $lastPopoverItem.popover('hide');
                    $lastPopoverItem = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverItem = null;
                }).on('click', function(e){

                });
            });
		
		} );
    }

    var handleBtnCari = function(){

		$('a#cari').click(function(){
			var resepsionis = $('select#resepsionis').val();
			var date = $('input#month_year').val();
			var penjamin = $('select#penjamin').val();
			var shift = $('select#shift').val();
			oTable.api().ajax.url(baseAppUrl +  'listing' + '/'  + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/' + resepsionis + '/' + shift + '/' + penjamin).load();
		})
	};



    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'kasir/laporan_invoice/';
        handleDateRangePicker();
        handleDataTable();
        handleBtnCari();
    };
 }(mb.app.laporan_invoice));


// initialize  mb.app.home.table
$(function(){
    mb.app.laporan_invoice.init();
});