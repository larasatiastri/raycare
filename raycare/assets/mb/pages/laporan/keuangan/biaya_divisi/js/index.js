mb.app.biaya = mb.app.biaya || {};
(function(o){

    var 
        baseAppUrl = '',
        $form      = $('#form_grafik_biaya'),
        $tablePerTanggal = $('#table_biaya_tanggal'),
        $tablePerKategori = $('#table_biaya_kategori'),
        $tablePerUser = $('#table_biaya_user');

    var handleMonthPeriode = function()
    {
        $(".date").datepicker( {
            format: "M yyyy",
            viewMode: "months", 
            minViewMode: "months",
            autoclose : true,
        }).on('changeDate', function(){
            var year = $('#month_year', $form).val();
            var divisi = $('select#divisi_id', $form).val();

            getTrans(year,divisi);
            oTablePerTanggal.api().ajax.url(baseAppUrl +  'listing_biaya_per_tanggal' + '/' + year +'/'+divisi).load();
            oTablePerKetegori.api().ajax.url(baseAppUrl +  'listing_biaya_per_kategori' + '/' + year +'/'+divisi).load();
            oTablePerUser.api().ajax.url(baseAppUrl +  'listing_biaya_per_user' + '/' + year +'/'+divisi).load();
        });

        $('a#refresh', $form).click(function(){
            var year = $('#month_year', $form).val();
            var divisi = $('select#divisi_id', $form).val();

            getTrans(year,divisi);
            oTablePerTanggal.api().ajax.url(baseAppUrl +  'listing_biaya_per_tanggal' + '/' + year +'/'+divisi).load();
            oTablePerKetegori.api().ajax.url(baseAppUrl +  'listing_biaya_per_kategori' + '/' + year +'/'+divisi).load();
            oTablePerUser.api().ajax.url(baseAppUrl +  'listing_biaya_per_user' + '/' + year +'/'+divisi).load();


        });
    }  

    var getTrans = function(start,divisi){

        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_all_divisi/' + start +'/'+ divisi +'/1',
            dataType : 'json',
            success  : function( results ) {
                $('span#label_bulan').text($('#month_year', $form).val());
                $('span#label_list_bulan').text($('#month_year', $form).val());
                $('span#label_list_kategori').text($('#month_year', $form).val());
                $('span#label_list_user').text($('#month_year', $form).val());

                find = false;
                trxData = [];
                if (results.success == true) {
                    var chart = AmCharts.makeChart("chart_2", {
                        "type": "serial",
                        "theme": "light",
                        "fontFamily": 'Open Sans',
                        "color":    '#555555',
                       
                        "legend": {
                            "equalWidths": false,
                            "useGraphSettings": true,
                            "valueAlign": "left",
                            "valueWidth": 120
                        },
                        "dataProvider": results.rows,
                        "valueAxes": [{
                            "id": "thisMonthAxis",
                            "axisAlpha": 0,
                            "gridAlpha": 0,
                            "position": "left",
                            "title": "Laporan Biaya Bulan Terpilih"
                        }],
                        "graphs": [{
                            "alphaField": "alpha",
                            "balloonText": "[[value]]",
                            "labelText" : "[[value]]",
                            "dashLengthField": "dashLength",
                            "fillAlphas": 0.7,
                            "legendPeriodValueText": "total: Rp. [[value.sum]]",
                            "legendValueText": "[[value]]",
                            "title": "Laporan Biaya Bulan Terpilih",
                            "type": "column",
                            "valueField": "total",
                        }],
                        "chartCursor": {
                            "cursorAlpha": 0.1,
                            "cursorColor": "#000000",
                            "fullWidth": true,
                            "valueBalloonsEnabled": false,
                            "zoomable": false
                        },
                        "categoryField": "kode",
                        "categoryAxis": {
                            "autoGridCount": false,
                            "axisColor": "#555555",
                            "gridAlpha": 0.1,
                            "gridColor": "#FFFFFF",
                            "gridCount": 50,
                            "gridPosition": "start"
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
            url      : baseAppUrl + 'get_all_divisi/'+ start +'/'+ divisi +'/2' ,
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

                        "legend": {
                            "equalWidths": false,
                            "useGraphSettings": true,
                            "valueAlign": "left",
                            "valueWidth": 120
                        },
                        "dataProvider": results.rows,
                        "valueAxes": [{
                            "id": "lastMonthAxis",
                            "axisAlpha": 0,
                            "gridAlpha": 0,
                            "inside": true,
                            "position": "left",
                            "title": "Laporan Biaya Bulan Sebelumnya"
                        }],
                        "graphs": [{
                            "bullet": "square",
                            "bulletBorderAlpha": 1,
                            "balloonText": "[[value]]",
                            "labelText" : "[[value]]",
                            "bulletBorderThickness": 1,
                            "dashLengthField": "dashLength",
                            "legendPeriodValueText": "total: Rp. [[value.sum]]",
                            "legendValueText": "[[value]]",
                            "title": "Laporan Biaya Bulan Sebelumnya",
                            "fillAlphas": 0,
                            "valueField": "total",
                        }],
                        "chartCursor": {
                            "cursorAlpha": 0.1,
                            "cursorColor": "#000000",
                            "fullWidth": true,
                            "valueBalloonsEnabled": false,
                            "zoomable": false
                        },
                        "categoryField": "kode",
                        "categoryAxis": {
                            "autoGridCount": false,
                            "axisColor": "#555555",
                            "gridAlpha": 0.1,
                            "gridColor": "#FFFFFF",
                            "gridCount": 50
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
    };

    var handleDivisiChange = function(){
        $('select#divisi_id', $form).on('change', function() {
            var year = $('#month_year', $form).val();
            var divisi = $(this).val();

            getTrans(year,divisi);
            oTablePerTanggal.api().ajax.url(baseAppUrl +  'listing_biaya_per_tanggal' + '/' + year +'/'+divisi).load();
            oTablePerKetegori.api().ajax.url(baseAppUrl +  'listing_biaya_per_kategori' + '/' + year +'/'+divisi).load();
            oTablePerUser.api().ajax.url(baseAppUrl +  'listing_biaya_per_user' + '/' + year +'/'+divisi).load();
            


        });
    }

    var handleDataTablePerTanggal = function() 
    {
        oTablePerTanggal = $tablePerTanggal.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_biaya_per_tanggal'+ '/' + $('#month_year', $form).val() + '/' + $('select#divisi_id', $form).val(),
                'type' : 'POST',
            },          

            'paginate'              : false,
            'filter'                : false,
            'info'                  : false,
            'pageLength'            : 25,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });
        $tablePerTanggal.on('draw.dt', function (){
            
        });
    }  

    var handleDataTablePerKategori = function() 
    {
        oTablePerKetegori = $tablePerKategori.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_biaya_per_kategori'+ '/' + $('#month_year', $form).val() + '/' + $('select#divisi_id', $form).val(),
                'type' : 'POST',
            },          

            'paginate'              : false,
            'filter'                : false,
            'info'                  : false,
            'pageLength'            : 25,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });
        $tablePerKategori.on('draw.dt', function (){
            
        });
    } 

    var handleDataTablePerUser = function() 
    {
        oTablePerUser = $tablePerUser.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_biaya_per_user'+ '/' + $('#month_year', $form).val() + '/' + $('select#divisi_id', $form).val(),
                'type' : 'POST',
            },          

            'paginate'              : false,
            'filter'                : false,
            'info'                  : false,
            'pageLength'            : 25,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });
        $tablePerUser.on('draw.dt', function (){
            
        });
    }  


 
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'laporan/keuangan/biaya_divisi/';
        handleMonthPeriode();
        handleDivisiChange();
        handleDataTablePerTanggal();
        handleDataTablePerKategori();
        handleDataTablePerUser();
        getTrans($('#month_year', $form).val(), $('select#divisi_id', $form).val());
    };
 }(mb.app.biaya));


// initialize  mb.app.home.table
$(function(){
    mb.app.biaya.init();
});