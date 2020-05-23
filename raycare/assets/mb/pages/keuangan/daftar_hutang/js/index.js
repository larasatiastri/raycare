mb.app.daftar_hutang = mb.app.daftar_hutang || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableDaftarHutang = $('#table_daftar_hutang_ttf'),
        $tableDaftarHutangPO = $('#table_daftar_hutang_po'),
        $tableDaftarHutangKyw = $('#table_daftar_hutang_karyawan'),
        $lastPopoverItem                     = null;


    var initForm = function(){

        $('select#pilihan_hutang').select2({
            placeholder: "Pilih Jenis Hutang",
            allowClear: true
        });

        $('select#pilihan_hutang').on('change', function(){
            var option = $(this).val();

            // alert(option);

            if(option == '1'){
                $('div#hutang_supplier_ttf').removeClass('hidden');
                $('div#hutang_supplier_po').addClass('hidden');
                $('div#hutang_karyawan').addClass('hidden');
            }if(option == '2'){
                $('div#hutang_supplier_ttf').addClass('hidden');
                $('div#hutang_supplier_po').removeClass('hidden');
                $('div#hutang_karyawan').addClass('hidden');
            }if(option == '3'){
                $('div#hutang_supplier_ttf').addClass('hidden');
                $('div#hutang_supplier_po').addClass('hidden');
                $('div#hutang_karyawan').removeClass('hidden');
            }if(option == '1,2'){
                $('div#hutang_supplier_ttf').removeClass('hidden');
                $('div#hutang_supplier_po').removeClass('hidden');
                $('div#hutang_karyawan').addClass('hidden');
            }if(option == '1,3'){
                $('div#hutang_supplier_ttf').removeClass('hidden');
                $('div#hutang_supplier_po').addClass('hidden');
                $('div#hutang_karyawan').removeClass('hidden');
            }if(option == '2,3'){
                $('div#hutang_supplier_ttf').addClass('hidden');
                $('div#hutang_supplier_po').removeClass('hidden');
                $('div#hutang_karyawan').removeClass('hidden');
            }
        }); 


    }
    var handleDataTableDaftarHutang = function() 
    {
    	$tableDaftarHutang.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'filter'                : false,
            'paginate'              : false,
            'info'                  : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableDaftarHutang.on('draw.dt', function (){
            var totalHutang = parseInt($('input#input_total', this).val());

            if(!isNaN(totalHutang))
            {
                $('b#total_hutang').text(mb.formatRp(totalHutang));
                $('span#text_total_hutang_ttf').text(mb.formatRp(totalHutang));
            }
            else
            {
                $('b#total_hutang').text(mb.formatRp(0));
                $('span#text_total_hutang_ttf').text(mb.formatRp(0));
            }
        }); 

        $tableDaftarHutangPO.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_po',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'filter'                : false,
            'paginate'              : false,
            'info'                  : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableDaftarHutangPO.on('draw.dt', function (){
            var totalHutang = parseInt($('input#input_total_po', this).val());

            if(!isNaN(totalHutang))
            {
                $('b#total_hutang_po').text(mb.formatRp(totalHutang));
                $('span#text_total_hutang_po').text(mb.formatRp(totalHutang));
            }
            else
            {
                $('b#total_hutang_po').text(mb.formatRp(0));
                $('span#text_total_hutang_po').text(mb.formatRp(0));
            }
        });  

        $tableDaftarHutangKyw.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_rb',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
            'filter'                : false,
            'paginate'              : false,
            'info'                  : false,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableDaftarHutangKyw.on('draw.dt', function (){
            var totalHutang = parseInt($('input#input_total_rb', this).val());

            if(!isNaN(totalHutang))
            {
                $('b#total_hutang_karyawan').text(mb.formatRp(totalHutang));
                $('span#text_total_hutang_rb').text(mb.formatRp(totalHutang));
            }
            else
            {
                $('b#total_hutang_karyawan').text(mb.formatRp(0));
                $('span#text_total_hutang_rb').text(mb.formatRp(totalHutang));
            }
        });      
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/daftar_hutang/';
        handleDataTableDaftarHutang();
        initForm();
       
    };
 }(mb.app.daftar_hutang));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_hutang.init();
});