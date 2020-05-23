mb.app.hutang_pasien = mb.app.hutang_pasien || {};
(function(o){

    var 
        baseAppUrl             = '',
        $tableHutangPasien        = $('#table_hutang_pasien'),
        $lastPopoverItem = null,
        $lastPopoverItemHutang = null,
        $lastPopoverItemBayar = null;

    var handleDataTable = function() 
    {
        var tgl_awal = $("#tgl_awal").val(),
            tgl_akhir = $("#tgl_akhir").val(),
            shift = $("select#shift").val();

    	oTable = $tableHutangPasien.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing'+ '/' + tgl_awal + '/' + tgl_akhir + '/' +shift,
				'type' : 'POST',
			},	

            'paginate'  : false,
            'info'  : false,
            'filter'  : false,
			'pageLength'			: 25,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
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
        $tableHutangPasien.on('draw.dt', function (){
			$('.btn', this).tooltip();

            var totalInvoice = parseInt($('input#input_total', this).val());
            var totalInvoiceBayar = parseInt($('input#input_total_bayar', this).val());
            var totalInvoiceHutang = parseInt($('input#input_total_hutang', this).val());

            // alert(totalInvoice);
            if(!isNaN(totalInvoice))
            {
                $('b#total_invoice').text(mb.formatRp(totalInvoice));
            }
            else
            {
                $('b#total_invoice').text(mb.formatRp(0));
            }

            if(!isNaN(totalInvoiceBayar))
            {
                $('b#total_invoice_bayar').text(mb.formatRp(totalInvoiceBayar));
            }
            else
            {
                $('b#total_invoice_bayar').text(mb.formatRp(0));
            }

            if(!isNaN(totalInvoiceHutang))
            {
                $('b#total_invoice_hutang').text(mb.formatRp(totalInvoiceHutang));
            }
            else
            {
                $('b#total_invoice_hutang').text(mb.formatRp(0));
            }

            var $btnDetail = $('a.detail_item', this);
            $.each($btnDetail, function(idx, col){
                var
                    $col            = $(col),
                    dataPaket       = $col.data('paket'),
                    dataItem        = $col.data('item'),
                    dataTindakan    = $col.data('tindakan');

                // console.log(dataIdentitas);
                $col.popover({
                    html : true,
                    container : 'body',
                    placement : 'bottom',
                    content: function(){
                        
                        var html = '<table class="table table-striped table-hover">';
                            html += '<tr class="heading bold">';
                            html += '<td class="text-center">Nama</td>'
                            html += '<td class="text-center">Harga</td>'
                            html += '</tr>';

                        if(dataPaket != ''){
                            $.each(dataPaket, function(idx, paket){
                                html += '<tr">';
                                html += '<td class="text-left"> Paket Hemodialisa</td>'
                                html += '<td class="text-right">' + mb.formatRp(parseInt(paket.harga)) + '</td>'
                                html += '</tr>';

                            }); 
                        }

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.nama + '</td>'
                            html += '<td class="text-right">' + mb.formatRp(parseInt(item.harga)) + '</td>'
                            html += '</tr>';

                        });
                         if(dataTindakan != ''){
                            $.each(dataTindakan, function(idx, tindakan){
                                html += '<tr">';
                                html += '<td class="text-left">' + tindakan.nama + '</td>'
                                html += '<td class="text-right">' + mb.formatRp(parseInt(tindakan.harga)) + '</td>'
                                html += '</tr>';

                            }); 
                        }
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
        
            var $btnDetailHutang = $('a.detail_item_hutang', this);
            $.each($btnDetailHutang, function(idx, col){
                var
                    $col            = $(col),
                    dataPaket       = $col.data('paket'),
                    dataItem        = $col.data('item'),
                    dataTindakan    = $col.data('tindakan');

                // console.log(dataIdentitas);
                $col.popover({
                    html : true,
                    container : 'body',
                    placement : 'bottom',
                    content: function(){
                        
                        var html = '<table class="table table-striped table-hover">';
                            html += '<tr class="heading bold">';
                            html += '<td class="text-center">Nama</td>'
                            html += '<td class="text-center">Harga</td>'
                            html += '</tr>';

                        if(dataPaket != ''){
                            $.each(dataPaket, function(idx, paket){
                                html += '<tr">';
                                html += '<td class="text-left"> Paket Hemodialisa</td>'
                                html += '<td class="text-right">' + mb.formatRp(parseInt(paket.harga)) + '</td>'
                                html += '</tr>';

                            }); 
                        }

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.nama + '</td>'
                            html += '<td class="text-right">' + mb.formatRp(parseInt(item.harga)) + '</td>'
                            html += '</tr>';

                        });
                         if(dataTindakan != ''){
                            $.each(dataTindakan, function(idx, tindakan){
                                html += '<tr">';
                                html += '<td class="text-left">' + tindakan.nama + '</td>'
                                html += '<td class="text-right">' + mb.formatRp(parseInt(tindakan.harga)) + '</td>'
                                html += '</tr>';

                            }); 
                        }
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverItemHutang !== null) $lastPopoverItemHutang.popover('hide');
                    $lastPopoverItemHutang = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverItemHutang = null;
                }).on('click', function(e){

                });
            });
            
            var $btnDetailBayar = $('a.detail_item_bayar', this);
            $.each($btnDetailBayar, function(idx, col){
                var
                    $col            = $(col),
                    dataPaket       = $col.data('paket'),
                    dataItem        = $col.data('item'),
                    dataTindakan    = $col.data('tindakan');

                // console.log(dataIdentitas);
                $col.popover({
                    html : true,
                    container : 'body',
                    placement : 'bottom',
                    content: function(){
                        
                        var html = '<table class="table table-striped table-hover">';
                            html += '<tr class="heading bold">';
                            html += '<td class="text-center">Nama</td>'
                            html += '<td class="text-center">Harga</td>'
                            html += '</tr>';

                        if(dataPaket != ''){
                            $.each(dataPaket, function(idx, paket){
                                html += '<tr">';
                                html += '<td class="text-left"> Paket Hemodialisa</td>'
                                html += '<td class="text-right">' + mb.formatRp(parseInt(paket.harga)) + '</td>'
                                html += '</tr>';

                            }); 
                        }

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.nama + '</td>'
                            html += '<td class="text-right">' + mb.formatRp(parseInt(item.harga)) + '</td>'
                            html += '</tr>';

                        });
                         if(dataTindakan != ''){
                            $.each(dataTindakan, function(idx, tindakan){
                                html += '<tr">';
                                html += '<td class="text-left">' + tindakan.nama + '</td>'
                                html += '<td class="text-right">' + mb.formatRp(parseInt(tindakan.harga)) + '</td>'
                                html += '</tr>';

                            }); 
                        }
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverItemBayar !== null) $lastPopoverItemBayar.popover('hide');
                    $lastPopoverItemBayar = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverItemBayar = null;
                }).on('click', function(e){

                });
            });
			
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
            });
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
        $('input#tgl_awal').val(moment().format('D-MM-YYYY'));
        $('input#tgl_akhir').val(moment().format('D-MM-YYYY'));
    };

    var handleButtonCari = function(){
        $('a#cari').click(function(){
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val() + '/' + $("select#shift").val()).load();
        });
    }
    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'kasir/hutang_pasien/';
        handleDateRangePicker();
        handleDataTable();
        handleButtonCari();
    };
 }(mb.app.hutang_pasien));


// initialize  mb.app.home.table
$(function(){
    mb.app.hutang_pasien.init();
});