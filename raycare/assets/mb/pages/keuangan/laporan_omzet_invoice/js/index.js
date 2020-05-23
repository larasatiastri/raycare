mb.app.laporan_omzet_invoice = mb.app.laporan_omzet_invoice || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form      = $('#form_laporan_omzet_invoice'),
        $lastPopoverItem                = null,
        $tableInvoice = $('#table_laporan_omzet_invoice');

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
                autoclose: true

            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }
   
    var handleBtnCari = function(){

		$('a#cari').click(function(){
			handleGetOmzet();

		});
	};

    var handleDataTable = function(){

        var tanggal = $('input#tanggal').val(),
            penjamin_id = $('select#penjamin').val(),
            shift = $('select#shift').val();

        oTable = $tableInvoice.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_bayar_lain',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'filter'                : false,
            'paginate'              : false,
            'info'                  : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'pembayaran_detail.no_invoice no_invoice','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'pembayaran_detail.nama_penjamin nama_penjamin','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'pembayaran_detail.harga harga','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });

        $tableInvoice.on('draw.dt', function (){
            var totalInvoice = parseInt($('input#input_total', this).val());

            // alert(totalInvoice);
            if(!isNaN(totalInvoice))
            {
                $('b#total_invoice').text(mb.formatRp(totalInvoice));
            }
            else
            {
                $('b#total_invoice').text(mb.formatRp(0));
            }
        });
    };

    var handleGetOmzet = function(){
        var tanggal = $('input#tanggal').val();
        var penjamin_id = $('select#penjamin').val();
        var shift = $('select#shift').val();

        $.ajax
        ({        
            type: 'POST',
            url: baseAppUrl +  "get_data_omzet",  
            data: {tanggal : tanggal, shift:shift, penjamin_id:penjamin_id},  
            dataType : 'json', 
            beforeSend : function(){
                Metronic.blockUI({boxed : true, target:".details"});
            },
            success:function(data)         
            { 
              if(data.success == true){

                $('div#omzet').text(mb.formatRp(parseInt(data.omzet)));
                $('div#edc').text(mb.formatRp(parseInt(data.edc)));
                $('div#tunai').text(mb.formatRp(parseInt(data.tunai)));
                $('div#hutang').text(mb.formatRp(parseInt(data.hutang)));

                tanggal = tanggal.replace(/ /g , "%20");

                $('a#link_omzet').attr('href', baseAppUrl + 'view_detail/1/' + penjamin_id + '/' + shift + '/' + tanggal);
                $('a#link_edc').attr('href', baseAppUrl + 'view_detail/2/' + penjamin_id + '/' + shift + '/' + tanggal);
                $('a#link_tunai').attr('href', baseAppUrl + 'view_detail/3/' + penjamin_id + '/' + shift + '/' + tanggal);
                $('a#link_hutang').attr('href', baseAppUrl + 'view_detail/4/' + penjamin_id + '/' + shift + '/' + tanggal);

                oTable.api().ajax.url(baseAppUrl + 'listing_bayar_lain/0/'+penjamin_id +'/'+shift+'/'+tanggal).load();
               
              }
            },
            complete : function() {
                Metronic.unblockUI(".details");
            } 
        });

    }

    



    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/laporan_omzet_invoice/';
        handleDatePickers();
        handleGetOmzet();
        handleBtnCari();
        handleDataTable();
    };
 }(mb.app.laporan_omzet_invoice));


// initialize  mb.app.home.table
$(function(){
    mb.app.laporan_omzet_invoice.init();
});