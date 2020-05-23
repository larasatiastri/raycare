mb.app.pengurangan_modal = mb.app.pengurangan_modal || {};

// mb.app.pengurangan_modal namespace
(function(o){

    var 
        $tablePenguranganModal      = $('#table_pengurangan_modal');


    var handleDataTable = function(){
        oTable = $tablePenguranganModal.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [
                { 'name' : 'pengurangan_modal.no_permintaan no_permintaan', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pengurangan_modal.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pengurangan_modal.nominal nominal','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pengurangan_modal.status status','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pengurangan_modal.keperluan keperluan','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pengurangan_modal.id id','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $tablePenguranganModal.on('draw.dt', function (){
                
        });       
    };

    
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pengurangan_modal/';
        handleDataTable();
    };

}(mb.app.pengurangan_modal));


// initialize  mb.app.pengurangan_modal
$(function(){
    mb.app.pengurangan_modal.init();

});