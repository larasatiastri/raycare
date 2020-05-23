mb.app.kartu_stok_item = mb.app.kartu_stok_item || {};
(function(o){

    var 
        baseAppUrl = '',
        $form      = $('#form_kartu_stok_item'),
        $tableKartuStokItem = $('#table_kartu_stok');

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
            
            $('a#cetak_pdf').attr('href',baseAppUrl+'cetak_pdf/'+$("#tgl_awal").val() + '/' + $("#tgl_akhir").val());
        });
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().startOf('month').format('MMMM D, YYYY') + ' - ' + moment().endOf('month').format('MMMM D, YYYY'));
        $('input#tgl_awal').val(moment().startOf('month').format('D-MM-YYYY'));
        $('input#tgl_akhir').val(moment().endOf('month').format('D-MM-YYYY'));
        $('a#cetak_pdf').attr('href',baseAppUrl+'cetak_pdf/'+$("#tgl_awal").val() + '/' + $("#tgl_akhir").val());
    };

    

    var handleButtonRefresh = function(){
        $('a#refresh').click(function(){
            var gudang_id = $('select#gudang_id').val(),
                kategori = $('select#tipe_kategori').val(),
                sub_kategori = $('select#tipe_sub_kategori').val(),
                string = $('select#item_id').val(),
                item = string;

                if(string != null){
                    string = string.toString();
                    item = string.replace(/,/g , "-");
                }

            getTrans($("#tgl_awal", $form).val(),$("#tgl_akhir", $form).val());

        });

        
        $('select#item_id').select2({
            placeholder: "Pilih Item",
            allowClear: true
        });
    }

    var getTrans = function(start,end){
        var gudang_id = $('select#gudang_id').val(),
            kategori = $('select#tipe_kategori').val(),
            sub_kategori = $('select#tipe_sub_kategori').val(),
            string = $('select#item_id').val(),
            item = string;

            if(string != null){
                string = string.toString();
                item = string.replace(/,/g , "-");
            }

        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_data_report/' + start + '/' + end + '/' +gudang_id+'/'+kategori+'/'+sub_kategori+'/'+item,
            dataType : 'json',
            success  : function( results ) {

                find = false;
                trxData = [];
                if (results.success == true) {
                    var data = results.rows,
                        xthml = '',
                        xthmlHarga = '',
                        grand_masuk = 0,
                        grand_keluar = 0,
                        grand_stok = 0,
                        no = 1;



                    $.each(data, function(key, value){

                        var harga_masuk = '',
                            total_masuk = '',
                            harga_keluar = '',
                            total_keluar = '',
                            idr_masuk = '',
                            idr_keluar = '',
                            total_stok = '';

                        if(value.harga_masuk != '' && value.harga_masuk != 'null'){
                            harga_masuk = mb.formatTanpaRp(parseInt(value.harga_masuk));
                            total_masuk = mb.formatTanpaRp(parseInt(value.masuk * value.harga_masuk));
                            grand_masuk = parseInt(grand_masuk) + parseInt(value.masuk * value.harga_masuk);
                            idr_masuk = 'IDR';
                            idr_keluar = '';
                        }
                        if(value.harga_keluar != '' && value.harga_keluar != 'null'){
                            harga_keluar = mb.formatTanpaRp(parseInt(value.harga_keluar));
                            total_keluar = mb.formatTanpaRp(parseInt(value.keluar * value.harga_keluar));
                            grand_keluar = parseInt(grand_keluar) + parseInt(value.keluar * value.harga_keluar);
                            idr_keluar = 'IDR';
                            idr_masuk = '';
                        }

                        total_stok = parseInt(value.final_stock * value.harga_beli);
                        grand_stok = parseInt(grand_stok) + total_stok;

                        xthml += '<tr>';
                        xthml += '<td>'+no+'</td>';
                        xthml += '<td><div class="inline-button-table">'+value.tanggal+'</div></td>';
                        xthml += '<td>'+value.kode+'</td>';
                        xthml += '<td><a href="'+baseAppUrl+'get_info/'+value.inventory_history_id+'/'+value.item_id+'/'+value.satuan_id+'" data-toggle="modal" data-target="#modal_detail">'+value.nama+'</td>';
                        xthml += '<td>'+value.satuan+'</td>';
                        xthml += '<td>'+value.bn_sn_lot+'</td>';
                        xthml += '<td>'+value.masuk+'</td>';
                        xthml += '<td> '+idr_masuk+' </td>';
                        xthml += '<td><div class="inline-button-table text-right">'+harga_masuk+'</div></td>';
                        xthml += '<td> '+idr_masuk+' </td>';
                        xthml += '<td><div class="inline-button-table text-right">'+total_masuk+'</div></td>';
                        xthml += '<td>'+value.keluar+'</td>';
                        xthml += '<td> '+idr_keluar+' </td>';
                        xthml += '<td><div class="inline-button-table text-right">'+harga_keluar+'</div></td>';
                        xthml += '<td> '+idr_keluar+' </td>';
                        xthml += '<td><div class="inline-button-table text-right">'+total_keluar+'</div></td>';
                        xthml += '<td>'+value.final_stock+'</td>';
                        xthml += '<td> IDR </td>';
                        xthml += '<td><div class="inline-button-table text-right">'+mb.formatTanpaRp(parseInt(value.harga_beli))+'</div></td>';
                        xthml += '<td> IDR </td>';
                        xthml += '<td><div class="inline-button-table text-right">'+mb.formatTanpaRp(total_stok)+'</div></td>';
                        xthml += '</tr>';

                        no = parseInt(no)+1;
                    });
                    
                    xthmlHarga += '<tr>';
                    xthmlHarga += '<td>'+mb.formatTanpaRp(grand_masuk)+'</td>';
                    xthmlHarga += '<td>'+mb.formatTanpaRp(grand_keluar)+'</td>';
                    xthmlHarga += '<td>'+mb.formatTanpaRp(grand_stok)+'</td>';
                    xthmlHarga += '</tr>';
                    
                    $('tbody#table_kartu_stok_item').html(xthml);
                    $('table#table_kartu_stok th#grand_total_masuk').text('IDR '+mb.formatTanpaRp(grand_masuk));
                    $('table#table_kartu_stok th#grand_total_keluar').text('IDR '+mb.formatTanpaRp(grand_keluar));
                    $('table#table_kartu_stok th#grand_total_stok').text('IDR '+mb.formatTanpaRp(grand_stok));

                }
            }
        });
    }

    var handleSelectSubKategori = function(){
        $('select#tipe_kategori').on('change', function(){

            //$('input#warehouse_id').val($(this).val());
            // $btnSet.attr('data-kategori', $(this).val());
            var $subKategori_select = $('select#tipe_sub_kategori');
            var $itemSelect = $('select#item_id');
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_sub_kategori',
                data     : {id_kategori: $(this).val()},
                dataType : 'json',
                beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    var sub_kategori = results.sub_kategori,
                        items = results.item;

                    $selected = $('li.select2-search-choice');
                    $selected.remove();
                    $itemSelect.removeAttr("selected");
                    $itemSelect.val("");
                    $itemSelect.empty();

                    $.each(items, function(key, value) {
                        $itemSelect.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $itemSelect.val('');
                    });

                    $subKategori_select.empty();

                    $subKategori_select.append($("<option></option>")
                            .attr("value", 0).text('Semua..'));
                    $.each(sub_kategori, function(key, value) {
                        $subKategori_select.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $subKategori_select.val(0);
                    });

                    handleSelectItem();
                },
                complete : function(){
                        Metronic.unblockUI();
                    }
            });
        })
    }
    var handleSelectItem = function(){
        $('select#tipe_sub_kategori').on('change', function(){

            var $itemSelect = $('select#item_id');
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_item',
                data     : {id_sub_kategori: $(this).val(), id_kategori : $('select#tipe_kategori').val()},
                dataType : 'json',
                beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $selected = $('li.select2-search-choice');
                    $selected.remove();
                    $itemSelect.removeAttr("selected");
                    $itemSelect.val("");
                    $itemSelect.empty();

                    $.each(results, function(key, value) {
                        $itemSelect.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $itemSelect.val('');
                    });
                },
                complete : function(){
                        Metronic.unblockUI();
                    }
            });
        })
    }
        

    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'laporan/kartu_stok_item/';
        handleDateRangePicker();
        handleButtonRefresh();
        handleSelectSubKategori();
        getTrans($("#tgl_awal", $form).val(),$("#tgl_akhir", $form).val());
    };
 }(mb.app.kartu_stok_item));


// initialize  mb.app.home.table
$(function(){
    mb.app.kartu_stok_item.init();
});