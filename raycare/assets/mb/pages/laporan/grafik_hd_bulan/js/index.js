mb.app.grafik_hd_bulan = mb.app.grafik_hd_bulan || {};
(function(o){

    var 
        baseAppUrl = '',
        $form      = $('#form_grafik_hd'),
        $tableTindakanBulanan = $('#table_tindakan_bulanan'),
        $tablePasienBaru = $('#table_pasien_baru'),
        $tablePasienPindah = $('#table_pasien_pindah'),
        $tablePasienTraveling = $('#table_pasien_traveling'),
        $tablePasienMeninggal = $('#table_pasien_meninggal'),
        $tableDataPasien = $('#table_data_pasien');

    var handleMonthPeriode = function()
    {
        $(".date").datepicker( {
            format: "M yyyy",
            viewMode: "months", 
            minViewMode: "months",
            autoclose : true,
        }).on('changeDate', function(){
            var year = $('#month_year', $form).val();
            var penjamin_id = $('select#penjamin', $form).val();
            getTrans(year,penjamin_id);
            handleGetPasien(year,penjamin_id);
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + year +'/'+penjamin_id).load();
            oTablePasienBaru.api().ajax.url(baseAppUrl +  'listing_pasien_baru' + '/' + year).load();
            oTablePasienMeninggal.api().ajax.url(baseAppUrl +  'listing_pasien_meninggal' + '/' + year).load();
            oTablePasienPindah.api().ajax.url(baseAppUrl +  'listing_pasien_pindah' + '/' + year).load();
            oTablePasienTraveling.api().ajax.url(baseAppUrl +  'listing_pasien_traveling' + '/' + year).load();
        });

        $('a#refresh', $form).click(function(){
            var year = $('#month_year', $form).val();
            var penjamin_id = $('select#penjamin', $form).val();
            getTrans(year,penjamin_id);
            handleGetPasien(year,penjamin_id);
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + year +'/' + penjamin_id).load();
            oTablePasienBaru.api().ajax.url(baseAppUrl +  'listing_pasien_baru' + '/' + year).load();
            oTablePasienMeninggal.api().ajax.url(baseAppUrl +  'listing_pasien_meninggal' + '/' + year).load();
            oTablePasienPindah.api().ajax.url(baseAppUrl +  'listing_pasien_pindah' + '/' + year).load();
            oTablePasienTraveling.api().ajax.url(baseAppUrl +  'listing_pasien_traveling' + '/' + year).load();


        });
    }  

    var handleDataTable = function() 
    {
        var penjamin_id = $('select#penjamin', $form).val();
        oTable = $tableTindakanBulanan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing'+ '/' + $('#month_year', $form).val() +'/' + penjamin_id,
                'type' : 'POST',
            },          

            'paginate'              : false,
            'filter'                : false,
            'info'                  : false,
            'pageLength'            : 7,
            'lengthMenu'            : [[7, 14, 21, 28, 35], [7, 14, 21, 28, 35]],
            'columns'               : [
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
            var jml_tindakan = $('input#input_tindakan', this).val(),
                jml_pagi = $('input#input_pagi', this).val(),
                jml_siang = $('input#input_siang', this).val(),
                jml_sore = $('input#input_sore', this).val(),
                jml_malam = $('input#input_malam', this).val();

            if(!isNaN(jml_tindakan))
            {
                $('th#total_tindakan', this).text(jml_tindakan);  
            }
            else
            {
                $('th#total_tindakan', this).text(0);  
            }
            if(!isNaN(jml_pagi))
            {
                $('th#tindakan_pagi', this).text(jml_pagi);
            }
            else
            {
                $('th#tindakan_pagi', this).text(0);
            }
            if(!isNaN(jml_siang))
            {
                $('th#tindakan_siang', this).text(jml_siang);
            }
            else
            {
                $('th#tindakan_siang', this).text(0);
            }
            if(!isNaN(jml_sore))
            {
                $('th#tindakan_sore', this).text(jml_sore);
            }
            else
            {
                $('th#tindakan_sore', this).text(0);
            }
            if(!isNaN(jml_malam))
            {
                $('th#tindakan_mlm', this).text(jml_malam);    
            }
            else
            {
                $('th#tindakan_mlm', this).text(0);    
            }

            
        } );
    } 

    var handleDataTablePasienBaru = function() 
    {
        oTablePasienBaru = $tablePasienBaru.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pasien_baru'+ '/' + $('#month_year', $form).val(),
                'type' : 'POST',
            },          

            'paginate'              : false,
            'filter'                : false,
            'info'                  : false,
            'pageLength'            : 7,
            'lengthMenu'            : [[7, 14, 21, 28], [7, 14, 21, 28]],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });
        $tablePasienBaru.on('draw.dt', function (){
            
        } );
    }

    var handleDataTablePasienMeninggal = function() 
    {
        oTablePasienMeninggal = $tablePasienMeninggal.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pasien_meninggal'+ '/' + $('#month_year', $form).val(),
                'type' : 'POST',
            },          

            'paginate'              : false,
            'filter'                : false,
            'info'                  : false,
            'pageLength'            : 7,
            'lengthMenu'            : [[7, 14, 21, 28], [7, 14, 21, 28]],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });
        $tablePasienMeninggal.on('draw.dt', function (){
            
        } );
    }  

    var handleDataTablePasienPindah = function() 
    {
        oTablePasienPindah = $tablePasienPindah.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pasien_pindah'+ '/' + $('#month_year', $form).val(),
                'type' : 'POST',
            },          

            'paginate'              : false,
            'filter'                : false,
            'info'                  : false,
            'pageLength'            : 7,
            'lengthMenu'            : [[7, 14, 21, 28], [7, 14, 21, 28]],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });
        $tablePasienPindah.on('draw.dt', function (){
            
        } );

        oTablePasienTraveling = $tablePasienTraveling.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pasien_traveling'+ '/' + $('#month_year', $form).val(),
                'type' : 'POST',
            },          

            'paginate'              : false,
            'filter'                : false,
            'info'                  : false,
            'pageLength'            : 7,
            'lengthMenu'            : [[7, 14, 21, 28], [7, 14, 21, 28]],
            'columns'               : [
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
        $tablePasienTraveling.on('draw.dt', function (){
            
        } );
    }      

    var getTrans = function(start, penjamin_id){
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_day_trans/' + start + '/' +penjamin_id ,
            dataType : 'json',
            success  : function( results ) {
                $('span#label_bulan').text($('#month_year', $form).val());
                $('span#label_list_bulan').text($('#month_year', $form).val());
                $('span#label_list_baru').text($('#month_year', $form).val());
                $('span#label_list_meninggal').text($('#month_year', $form).val());
                $('span#label_list_pindah').text($('#month_year', $form).val());
                $('span#label_list_traveling').text($('#month_year', $form).val());

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
                            "title": "Tindakan Bulan Terpilih"
                        }],
                        "graphs": [{
                            "alphaField": "alpha",
                            "balloonText": "[[value]] transaction",
                            "labelText" : "[[value]]",
                            "dashLengthField": "dashLength",
                            "fillAlphas": 0.7,
                            "legendPeriodValueText": "total: [[value.sum]] trans",
                            "legendValueText": "[[value]] trans",
                            "title": "Tindakan Bulan Terpilih",
                            "type": "column",
                            "valueField": "this_trans_count",
                            "valueAxis": "thisMonthAxis"
                        }],
                        "chartCursor": {
                            "categoryBalloonDateFormat": "DD",
                            "cursorAlpha": 0.1,
                            "cursorColor": "#000000",
                            "fullWidth": true,
                            "valueBalloonsEnabled": false,
                            "zoomable": false
                        },
                        "dataDateFormat": "YYYY-MM-DD",
                        "categoryField": "date",
                        "categoryAxis": {
                            "dateFormats": [{
                                "period": "DD",
                                "format": "DD"
                            }, {
                                "period": "WW",
                                "format": "MMM DD"
                            }, {
                                "period": "MM",
                                "format": "MMM"
                            }, {
                                "period": "YYYY",
                                "format": "YYYY"
                            }],
                            "parseDates": true,
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

                    $('#chart_2').closest('.portlet').find('.fullscreen').click(function() {
                        chart.invalidateSize();
                    });

                }
            }                              
        });

        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_last_trans/'+start  +'/'+penjamin_id ,
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
                            "title": "Tindakan Bulan Sebelumnya"
                        }],
                        "graphs": [{
                            "bullet": "square",
                            "bulletBorderAlpha": 1,
                            "balloonText": "[[value]] transaction",
                            "labelText" : "[[value]]",
                            "bulletBorderThickness": 1,
                            "dashLengthField": "dashLength",
                            "legendPeriodValueText": "total: [[value.sum]] trans",
                            "legendValueText": "[[value]] trans",
                            "title": "Tindakan Bulan Sebelumnya",
                            "fillAlphas": 0,
                            "valueField": "last_trans_count",
                            "valueAxis": "lastMonthAxis"
                        }],
                        "chartCursor": {
                            "categoryBalloonDateFormat": "DD",
                            "cursorAlpha": 0.1,
                            "cursorColor": "#000000",
                            "fullWidth": true,
                            "valueBalloonsEnabled": false,
                            "zoomable": false
                        },
                        "dataDateFormat": "YYYY-MM-DD",
                        "categoryField": "date",
                        "categoryAxis": {
                            "dateFormats": [{
                                "period": "DD",
                                "format": "DD"
                            }, {
                                "period": "WW",
                                "format": "MMM DD"
                            }, {
                                "period": "MM",
                                "format": "MMM"
                            }, {
                                "period": "YYYY",
                                "format": "YYYY"
                            }],
                            "parseDates": true,
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
        

    var handleSelectPenjamin = function(){
        $('select#penjamin', $form).on('change', function(){
            var year = $('#month_year', $form).val();
            var penjamin_id = $(this).val();
            getTrans(year,penjamin_id);
            handleGetPasien(year,penjamin_id);
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + year +'/' + penjamin_id).load();
            oTablePasienBaru.api().ajax.url(baseAppUrl +  'listing_pasien_baru' + '/' + year).load();
            oTablePasienMeninggal.api().ajax.url(baseAppUrl +  'listing_pasien_meninggal' + '/' + year).load();
            oTablePasienPindah.api().ajax.url(baseAppUrl +  'listing_pasien_pindah' + '/' + year).load();
            oTablePasienTraveling.api().ajax.url(baseAppUrl +  'listing_pasien_traveling' + '/' + year).load();


        });
    }

   var handleGetPasien = function(start, penjamin_id) {
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_day_patient/'+start  +'/'+penjamin_id ,
            dataType : 'json',
            success  : function( results ) {
                if (results.success == true) {
                    $('a#label_pasien_now').text("Total Pasien : " + results.rows.jml_pasien+" pasien");
                    $('a#label_pasien_now').attr("target", "_blank");
                    $('a#label_pasien_now').attr("href", baseAppUrl + 'cetak_data_pasien/'+start  +'/'+penjamin_id+'/1');
                }
            }
        });
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_last_patient/'+start  +'/'+penjamin_id ,
            dataType : 'json',
            success  : function( results ) {
                if (results.success == true) {
                    $('a#label_pasien_last').text("Total Pasien : " + results.rows.jml_pasien+" pasien");
                    $('a#label_pasien_last').attr("target", "_blank");
                    $('a#label_pasien_last').attr("href", baseAppUrl + 'cetak_data_pasien/'+start  +'/'+penjamin_id+'/2');
                }
            }
        });
    }

    var handleDataTableDataPasien = function() 
    {
        var start_data = $('input#start').val(),
            penjamin_data = $('input#penjamin_id').val(),
            tipe          = $('input#tipe').val();

        oTableDataPasien = $tableDataPasien.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_data_pasien'+ '/' + start_data  +'/'+penjamin_data+'/'+tipe,
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
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });
        $tableDataPasien.on('draw.dt', function (){
            
        } );
    }  

    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'laporan/grafik_hd_bulan/';
        handleMonthPeriode();
        handleDataTable();
        handleDataTablePasienBaru();
        handleDataTablePasienMeninggal();
        handleDataTablePasienPindah();
        handleDataTableDataPasien();
        handleSelectPenjamin();
        getTrans($('#month_year', $form).val(),$('select#penjamin', $form).val());
        handleGetPasien($('#month_year', $form).val(),$('select#penjamin', $form).val());
    };
 }(mb.app.grafik_hd_bulan));


// initialize  mb.app.home.table
$(function(){
    mb.app.grafik_hd_bulan.init();
});