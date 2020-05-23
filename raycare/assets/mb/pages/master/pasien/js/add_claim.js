mb.app.pasien = mb.app.pasien || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form = $('#form_claim_pasien'),
        $popoverPenjaminContent     = $('#popover_penjamin_content'), 
        $lastPopoverItem        = null,
        $tablePilihDataKlaim        = $('#table_pilih_data_klaim'),
        $idPasien        = $('input#id_pasien'),
        $currentRow       = $('input#row'),
        $btnSearchDataPenjamin  = $('.pilih-data-penjamin', $form);

    var initForm = function(){

        // var $btnSearchDataPenjamin  = $('.pilih-data-penjamin', $form);
        // handleBtnSearchDataPenjamin($btnSearchDataPenjamin);
        $popoverPenjaminContent.hide();

    };

    var handleDataTable = function() 
    {
        $tablePilihDataKlaim.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_data_klaim/' + $idPasien.val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': true, 'orderable': true },
                { 'visible' : false , 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        var $btnSelects = $('a.select', $tablePilihDataKlaim);
        handlePilihDataKlaim( $btnSelects );

        $tablePilihDataKlaim.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihDataKlaim( $btnSelect );
            
        });
    }

    var handlePilihDataKlaim = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop   = $(this).parents('.popover').eq(0),
                rowId        = $('input#rowItemId', $parentPop).val(),
                //itemTarget = $('input:hidden[name="itemTarget"]', $parentPop).val(),
                $getRow      = $currentRow.val(),
                $row         = $('#dokumen_'+$getRow),
                $penjamin_syarat         = $('#pasien_syarat_penjamin_id_'+$getRow)
                ;        

            console.log($getRow);
            

            // console.log($itemIdEl)
            
            $row.val($(this).data('item').value); 
            $penjamin_syarat.val($(this).data('item').pasien_syarat_penjamin_id); 
            $('.pilih-data-penjamin', $form).popover('hide');
            // alert($itemIdEl.val($(this).data('item').id));


            e.preventDefault();
        });     
    };
    var handleSelectClaim = function(){
    	$('select#penjamin').on('change', function(){
    		
            $.ajax({
                type     	: 'POST',
                url      	: baseAppUrl + 'show_claim_kelompok',
                data     	: {id_penjamin: $(this).val()},
                dataType 	: 'text',
                success  	: function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#show_claim_kelompok").html(results);
                    //alert(results);
                }
            });

            $.ajax({
                type     	: 'POST',
                url      	: baseAppUrl + 'show_claim',
                data     	: {id_penjamin: $(this).val()},
                dataType 	: 'text',
                success  	: function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#show_claim").html(results);
                    //alert(results);
                    
                    
                    $('a.pilih-data-penjamin', $form).on('click', function(){
                        var id = $(this).data('id');

                        $currentRow.val(id);

                    });

                    var $btnSearchDataPenjamin  = $('.pilih-data-penjamin', $form);
                    handleBtnSearchDataPenjamin($btnSearchDataPenjamin);

                    $("#show_claim").find('script').each(function(){
                    event.preventDefault();
                    eval($(this).text());
                    });
                }
            });
        }) 
    }

    var handleBtnSearchDataPenjamin = function($btn){
        var rowId  = $currentRow.val();
        var a  = 'a';
        console.log(a);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" id="rowItemId" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverPenjaminContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input#rowItemId', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPenjaminContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverPenjaminContent.hide();
            $popoverPenjaminContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $('#save', $form).click();
                }
            });
        });
    };

    var handleMultiSelect = function () {
        $('.multi-select').multiSelect();   
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/pasien/';
        handleSelectClaim();
        handleMultiSelect();
        handleConfirmSave();
        handleDataTable();
        initForm();
    };
 }(mb.app.pasien));


// initialize  mb.app.home.table
$(function(){
    mb.app.pasien.init();
});