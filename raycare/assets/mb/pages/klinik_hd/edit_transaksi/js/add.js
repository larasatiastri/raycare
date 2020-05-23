mb.app.edit_transaksi = mb.app.edit_transaksi || {};
mb.app.edit_transaksi.add = mb.app.edit_transaksi.add || {};
(function(o){

    var 
         baseAppUrl               = '',
         $popoverPasienContent      = $('#popover_pasien_content'),
         $lastPopoverItem         = null,
         $form_tindakan                  = $('#form_tindakan'),
         $formAssesment           = $('#form_assesment'),
         $formSupervising         = $('#form_supervising'),
         $formExamination         = $('#form_examination'),
         $tableMonitoring         = $('#table_monitoring'),
         $tablePaket              = $('#table_paket'),
         $tableItemTelahDigunakan = $('#table_item_telah_digunakan'),
         $tablePilihPasien        = $('#table_pilih_pasien'),
         itemCounter3             = 1,
         tplItemRow3              = $.validator.format( $('#tpl_item_row3').text() )

    ;

    var handleValidation = function() {
        var error1   = $('.alert-danger', $form_tindakan);
        var success1 = $('.alert-success', $form_tindakan);

        $form_tindakan.validate({
            // class has-error disisipkan di form element dengan class col-*
            errorPlacement: function(error, element) {
                error.appendTo(element.closest('[class^="col"]'));
            },
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            // rules: {
            // buat rulenya di input tag
            // },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                Metronic.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                $(element).closest('[class^="col"]').addClass('has-error');
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control group
            }

            
        });       
    }
        
    var handleBtnSearchPasien = function($btn)
    {
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px', zIndex: '99999'});

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverPasienContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPasienContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverPasienContent.hide();
            $popoverPasienContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDatePickers = function() {

        if (jQuery().datepicker) {
            $('div.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
            }).on('changeDate', function(ev){
                $('.datepicker-dropdown').hide();       // function jika menggunakan datepicker dalam modal 
            });
            $('body').removeClass("modal-open");
            $('.date').on('click', function(){
                if ($('#popup_modal').is(":visible") && $('body').hasClass("modal-open") == false) {
                    $('body').addClass("modal-open");
                }
            });
        }

        $('input.time').timepicker({
            autoclose: true,
            minuteStep: 5,
            showSeconds: false,
            showMeridian: false
        });
        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    };


    var handlePilihPasien = function(){
        $tablePilihPasien.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : mb.baseUrl() + 'klinik_hd/edit_transaksi/listing_pilih_pasien',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'stateSave' : true,
            'pagingType' : 'full_numbers',
            'columns'               : [
                { 'name' : "pasien.id id",'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : "pasien.no_member no_member",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.nama nama",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.tanggal_lahir tanggal_lahir",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien_alamat.alamat alamat",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.nama nama",'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#table_pilih_pasien_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_pasien_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $tablePilihPasien);
        handlePilihPasienSelect( $btnSelects );

        $tablePilihPasien.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihPasienSelect( $btnSelect );
            
        } );

        $popoverPasienContent.hide();        
    };

    var handlePilihPasienSelect = function($btn){
        $btn.on('click', function(e){

            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $noPasien       = $('input[name="no_member"]'),
                $IdPasien       = $('input[name="id_pasien"]'),
                $NamaPasien     = $('input[name="nama_pasien"]');  

            $IdPasien.val($(this).data('item').id);
            $noPasien.val($(this).data('item').no_ktp);
            $NamaPasien.val($(this).data('item').nama);

            $('.pilih-pasien', $form_tindakan).popover('hide');     
            
           
            e.preventDefault();
        });     
    };

    var handleSave = function()
    {
        $('a#confirm_save').click(function(){
            if(! $form_tindakan.valid()) return;

            var i = 0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    i = parseInt(i) + 1;
                    if(i === 1)
                    {      
                        $('a#confirm_save').attr('disabled','disabled');
                        $.ajax({
                            type     : 'POST',
                            url      : mb.baseUrl() + 'klinik_hd/edit_transaksi/save_add',
                            data     : $form_tindakan.serialize(),
                            dataType : 'json',
                            beforeSend : function(){
                                Metronic.blockUI({boxed: true });
                            },
                            success  : function( results ) {
                                location.href = mb.baseUrl() + 'klinik_hd/edit_transaksi';
                            },
                            complete : function(){
                            }
                        });  
                    }               
                }
            });
        }); 
    }

    var handleKlaim = function(){
        oTableklaim=$("#table_klaim1").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'paging'                : false,
            'filter'                : false,
            'ajax'                  : {
                'url' : mb.baseUrl() + 'klinik_hd/edit_transaksi/listing_klaim' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'stateSave' : true,
            'pagingType' : 'full_numbers',
            'columns'               : [ 
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },
            ],
                  
        });
    }; 

    var handleJwertyEnterRent = function($nopasien){
        jwerty.key('enter', function() {
            
            var NomorPasien = $nopasien.val();

            searchPasienByNomorAndFill(NomorPasien);

            // cegah ENTER supaya tidak men-trigger form submit
            return false;

        }, this, $nopasien );
    }


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/edit_transaksi/';
        // handleValidation();
        handlePilihPasien();
        handleDatePickers();
        handleBtnSearchPasien($('a.pilih-pasien'));
        handleSave();
        
    };
    
 }(mb.app.edit_transaksi.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.edit_transaksi.add.init();
});