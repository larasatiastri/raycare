mb.app.outstanding_po_alkes = mb.app.outstanding_po_alkes || {};
(function(o){

    var 
         baseAppUrl                      = '',
         $tableOutstandingPO       = $('#table_outstanding_po_alkes'),
         $tableOutstandingPOHistory       = $('#table_outstanding_po_alkes_proses'),
         $lastPopoverIdentitas                = null,
         arrayItemId = [];
         arrayItemSatuanId = [];
         arraySupplierId = [];
         arrayJumlah = [];


    var handleDataTablePermintaan = function() 
    {
    	$tableOutstandingPO.dataTable({
           	'processing'            : true,
            'serverSide'            : true,
            'info'            : true,
			'paginate'            : false,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/2',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : false, 'name' : 'o_s_pmsn.id id', 'searchable': false, 'orderable': false },
				{ 'visible' : true, 'name' : 'item.nama nama_item', 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'name' : 'o_s_pmsn.jumlah jumlah', 'searchable': true, 'orderable': false },
                { 'visible' : true, 'name' : 'o_s_pmsn.subjek subjek', 'searchable': false, 'orderable': false },
				{ 'visible' : true, 'name' : 'o_s_pmsn.subjek subjek', 'searchable': false, 'orderable': false },
        		]
        });
        $tableOutstandingPO.on('draw.dt', function (){

            $('select[name$="[supplier_id]"]', this).on('change', function(){
                
                var index = $(this).data('index'),
                    supplier_id = $(this).val();


                $('input#input_pilih_supplier_'+index).val(index);
                $('input#input_pilih_supplier_'+index).attr('value',supplier_id);

                $('input#input_pilih_'+index).attr('checked','checked');
                $('input#input_pilih_'+index).attr('data-supplier_id',supplier_id);

            });

            var $identitasItem = $('.item-unlist', this);

            $.each($identitasItem, function(idx, col){
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
                            html += '<td class="text-center">Tanggal</td>'
                            html += '<td class="text-center">Jumlah</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.nama + '</td>'
                            html += '<td class="text-left">' + item.tanggal + '</td>'
                            html += '<td class="text-left">' + item.jumlah +'</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverIdentitas !== null) $lastPopoverIdentitas.popover('hide');
                    $lastPopoverIdentitas = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverIdentitas = null;
                }).on('click', function(e){

                });
            });
        });

        $tableOutstandingPOHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'info'            : true,
            'paginate'            : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_history/2',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'name' : 'o_s_pmsn.id id', 'searchable': false, 'orderable': false },
                { 'visible' : true, 'name' : 'item.nama nama_item', 'searchable': true, 'orderable': false },
                { 'visible' : true, 'name' : 'o_s_pmsn.jumlah jumlah', 'searchable': true, 'orderable': false },
                ]
        });
        $tableOutstandingPOHistory.on('draw.dt', function (){
            var $identitasItem = $('.item-unlist', this);

            $.each($identitasItem, function(idx, col){
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
                            html += '<td class="text-center">Tanggal</td>'
                            html += '<td class="text-center">Jumlah</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.nama + '</td>'
                            html += '<td class="text-left">' + item.tanggal + '</td>'
                            html += '<td class="text-left">' + item.jumlah +'</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverIdentitas !== null) $lastPopoverIdentitas.popover('hide');
                    $lastPopoverIdentitas = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverIdentitas = null;
                }).on('click', function(e){

                });
            });
            
        });
			
    }

    var handleButtonSubmit = function(){
        $('a#confirm_save').click(function(){

            $.each($('input[name$="[pilih]"]:checked'), function(idx){
                arrayItemId.push($(this).data('item_id'));
                arrayItemSatuanId.push($(this).data('item_satuan_id'));
                arraySupplierId.push($(this).data('supplier_id'));
                arrayJumlah.push($(this).data('jumlah'));
            });

            var hasil = '';

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'generate_po',
                data     : {data_item_id : arrayItemId, data_item_satuan_id : arrayItemSatuanId, data_supplier_id : arraySupplierId, data_jumlah : arrayJumlah},
                async    : false,
                dataType : 'json',
                beforeSend : function(){
                    // Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    $.each(results, function(idx, result){
                // alert(idx);
                        var x = window.open( mb.baseUrl() + 'pembelian/pembelian_alkes/add/'+idx+'/'+result['item_id_array']+'/'+result['item_satuan_id_array']+'/'+result['jumlah_array'], '_blank');                
                    });
                    
                },
                complete : function(){

                }
            });

            

            // x = window.open('http://simrhs.com/ravena/pembelian/pembelian/add/','_blank');
            // y = window.open('http://simrhs.com/ravena/pembelian/pembelian/add','_blank');
            // z = window.open('http://simrhs.com/ravena/pembelian/pembelian/add','_blank');
        })
    }
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/outstanding_po_alkes/';
        handleDataTablePermintaan();   
        handleButtonSubmit(); 

    };
 }(mb.app.outstanding_po_alkes));


// initialize  mb.app.home.table
$(function(){
    mb.app.outstanding_po_alkes.init();
});