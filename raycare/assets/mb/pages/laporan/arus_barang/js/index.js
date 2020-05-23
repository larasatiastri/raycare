mb.app.arus_barang = mb.app.arus_barang || {};
(function(o){

    var 
        baseAppUrl = '',
        $form      = $('#form_grafik_arus_barang'),
        $tableBarangMasuk = $('#table_barang_masuk'),
        $tableBarangKeluar = $('#table_barang_keluar');

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

    var handleDataTableBarangMasuk = function() 
    {
        var tgl_awal = $("#tgl_awal").val(),
            tgl_akhir = $("#tgl_akhir").val(),
            gudang_id = $('select#gudang_id').val(),
            kategori = $('select#tipe_kategori').val(),
            sub_kategori = $('select#tipe_sub_kategori').val(),
            item = $('select#item_id').val();

        oTableBarangMasuk = $tableBarangMasuk.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_barang_masuk'+ '/' + tgl_awal + '/' + tgl_akhir +'/2/'+gudang_id+'/'+kategori+'/'+sub_kategori+'/'+item,
                'type' : 'POST',
            },          

            'paginate'              : true,
            'filter'                : true,
            'info'                  : true,
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableBarangMasuk.on('draw.dt', function (){
        });
            
    } 

    var handleDataTableBarangKeluar = function() 
    {
        var tgl_awal = $("#tgl_awal").val(),
            tgl_akhir = $("#tgl_akhir").val(),
            gudang_id = $('select#gudang_id').val(),
            kategori = $('select#tipe_kategori').val(),
            sub_kategori = $('select#tipe_sub_kategori').val(),
            item = $('select#item_id').val();

        oTableBarangKeluar = $tableBarangKeluar.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_barang_keluar'+ '/' + tgl_awal + '/' + tgl_akhir+'/1/'+gudang_id+'/'+kategori+'/'+sub_kategori+'/'+item,
                'type' : 'POST',
            },          

            'paginate'              : true,
            'filter'                : true,
            'info'                  : true,
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableBarangKeluar.on('draw.dt', function (){
            
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
            url      : baseAppUrl + 'get_barang_keluar/' + start + '/' + end + '/' +gudang_id+'/'+kategori+'/'+sub_kategori+'/'+item,
            dataType : 'json',
            success  : function( results ) {

                find = false;
                trxData = [];
                if (results.success == true) {
                    var chart = AmCharts.makeChart("chart_2", {
                        "theme": "light",
                        "type": "serial",
                        "startDuration": 2,

                        "fontFamily": 'Open Sans',
                        
                        "color":    '#888',

                        "legend": {
                            "equalWidths": false,
                            "useGraphSettings": true,
                            "valueAlign": "left",
                            "valueWidth": 120
                        },
                        "dataProvider": results.rows,
                        "valueAxes": [{
                            "position": "left",
                            "axisAlpha": 0,
                            "gridAlpha": 0
                        }],
                        "graphs": [{
                            "balloonText": "[[kode_item]] | [[nama_item]] : <b>[[value]]</b> [[nama_satuan]]",
                            "colorField": "color",
                            "legendValueText": "[[kode_item]] | [[nama_item]] : [[value]] [[nama_satuan]]",
                            "fillAlphas": 0.85,
                            "lineAlpha": 0.1,
                            "type": "column",
                            "topRadius": 1,
                            "valueField": "jumlah"
                        }],
                        "depth3D": 40,
                        "angle": 30,
                        "chartCursor": {
                            "categoryBalloonEnabled": false,
                            "cursorAlpha": 0,
                            "zoomable": false
                        },
                        "categoryField": "kode_item",
                        "categoryAxis": {
                            "gridPosition": "start",
                            "axisAlpha": 0,
                            "gridAlpha": 0

                        },

                        "exportConfig": {
                            "menuBottom": "20px",
                            "menuRight": "22px",
                            "menuItems": [{
                                "icon": Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/export.png",
                                "format": 'png'
                            }]
                        }
                    });

                    $('#chart_2').closest('.portlet').find('.fullscreen').click(function() {
                        chart.invalidateSize();
                        
                    });

                }
            }                              
        });

        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_barang_masuk/' + start + '/' + end + '/' +gudang_id+'/'+kategori+'/'+sub_kategori+'/'+item,
            dataType : 'json',
            success  : function( results ) {
                find = false;
                trxData = [];
                if (results.success == true) {
                    var chart2 = AmCharts.makeChart("chart_3", {
                        "type": "serial",
                        "theme": "light",
                        "fontFamily": 'Open Sans',
                        "color":    '#888888',
                        "addClassNames": true,
                        "startDuration": 1,

                        "legend": {
                            "bulletType": "round",
                            "equalWidths": false,
                            "valueWidth": 120,
                            "useGraphSettings": true,
                            "color": "#6c7b88"
                        },
                        "dataProvider": results.rows,
                        "valueAxes": [{
                            "id": "lastMonthAxis",
                            "axisAlpha": 0,
                            "gridAlpha": 0,
                            "inside": true,
                            "position": "left",
                            "title": "Grafik Barang Masuk"
                        }],
                        "graphs": [{
                            "id": "g3",
                            "title": "Jumlah",
                            "valueField": "jumlah",
                            "type": "line",
                            "valueAxis": "lastMonthAxis",
                            "lineAlpha": 0.8,
                            "lineColor": "#e26a6a",
                            "balloonText": "[[kode_item]] | [[nama_item]] : <b>[[value]]</b> [[nama_satuan]]",
                            "lineThickness": 1,
                            "legendValueText": "[[kode_item]] | [[nama_item]] : [[value]] [[nama_satuan]]",
                            "bullet": "square",
                            "bulletBorderColor": "#e26a6a",
                            "bulletBorderThickness": 1,
                            "bulletBorderAlpha": 0.8,
                            "dashLengthField": "dashLength",
                            "animationPlayed": true
                        }],
                        "categoryField": "kode_item",
                        "categoryAxis" :{
                            "labelRotation" : 90,
                        },
                        "chartCursor": {
                            "categoryBalloonDateFormat": "DD",
                            "cursorAlpha": 0.1,
                            "cursorColor": "#000000",
                            "fullWidth": true,
                            "valueBalloonsEnabled": false,
                            "zoomable": false
                        },
                        "exportConfig": {
                            "menuBottom": "20px",
                            "menuRight": "22px",
                            "menuItems": [{
                                "icon": Metronic.getGlobalPluginsPath() + "amcharts/amcharts/images/export.png",
                                "format": 'png'
                            }]
                        }
                    });

                    $('#chart_3').closest('.portlet').find('.fullscreen').click(function() {
                        chart2.invalidateSize();
                    });

                }
            }                              
        });

        function handleClick(event)
        {
            alert(event.item.category + ": " + event.item.values.value);
        }
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


            oTableBarangMasuk.api().ajax.url(baseAppUrl +  'listing_barang_masuk' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val() + '/2/' +gudang_id+'/'+kategori+'/'+sub_kategori+'/'+item).load();
            oTableBarangKeluar.api().ajax.url(baseAppUrl +  'listing_barang_keluar' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()+ '/1/' +gudang_id+'/'+kategori+'/'+sub_kategori+'/'+item).load();
            getTrans($("#tgl_awal", $form).val(),$("#tgl_akhir", $form).val());
        });

        
        $('select#item_id').select2({
            placeholder: "Pilih Item",
            allowClear: true
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
        baseAppUrl = mb.baseUrl() + 'laporan/arus_barang/';
        handleDateRangePicker();
        handleDataTableBarangMasuk();
        handleDataTableBarangKeluar();
        handleButtonRefresh();
        handleSelectSubKategori();
        getTrans($("#tgl_awal", $form).val(),$("#tgl_akhir", $form).val());
    };
 }(mb.app.arus_barang));


// initialize  mb.app.home.table
$(function(){
    mb.app.arus_barang.init();
});