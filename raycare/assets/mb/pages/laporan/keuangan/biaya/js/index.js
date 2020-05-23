mb.app.biaya = mb.app.biaya || {};
(function(o){

    var 
        baseAppUrl = '',
        $form      = $('#form_grafik_biaya');

    var handleMonthPeriode = function()
    {
        $(".date").datepicker( {
            format: "M yyyy",
            viewMode: "months", 
            minViewMode: "months",
            autoclose : true,
        }).on('changeDate', function(){
            var year = $('#month_year', $form).val();
            getTrans(year);
        });

        $('a#refresh', $form).click(function(){
            var year = $('#month_year', $form).val();
            getTrans(year);
        });
    }  

    var getTrans = function(start){
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_all_divisi/' + start +'/1',
            dataType : 'json',
            success  : function( results ) {
                $('span#label_bulan').text($('#month_year', $form).val());
                $('span#label_list_bulan').text($('#month_year', $form).val());

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
            url      : baseAppUrl + 'get_all_divisi/'+start  +'/2' ,
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
 
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'laporan/keuangan/biaya/';
        handleMonthPeriode();
        getTrans($('#month_year', $form).val());
    };
 }(mb.app.biaya));


// initialize  mb.app.home.table
$(function(){
    mb.app.biaya.init();
});