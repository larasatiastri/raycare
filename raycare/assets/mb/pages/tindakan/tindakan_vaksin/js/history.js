mb.app.tindakan_vaksin = mb.app.tindakan_vaksin || {};
(function(o){

    var 
        baseAppUrl            = '',
        $tableHistoryVaksin     = $('#tabel_history_vaksin');

  
    var handleDataTableHistory = function(){
        oTableVaksin = $tableHistoryVaksin.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_history_vaksin_all',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'tindakan_vaksin.id id','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'tindakan_vaksin.pasien_nama nama_pasien','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'master_vaksin.nama nama_vaksin','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_vaksin.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'a.nama nama_dokter','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'b.nama nama_perawat','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'cabang.nama nama_cabang','visible' : true, 'searchable': true, 'orderable': true },
                ]
        });       

        $tableHistoryVaksin.on('draw.dt', function (){
            
        });
    };
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'tindakan/tindakan_vaksin/';
        handleDataTableHistory();
    };
 }(mb.app.tindakan_vaksin));


// initialize  mb.app.home.table
$(function(){
    mb.app.tindakan_vaksin.init();
});