mb.app.pasien_meninggal = mb.app.pasien_meninggal || {};
mb.app.pasien_meninggal.add = mb.app.pasien_meninggal.add || {};
(function(o){

    var 
        baseAppUrl             = '',
        $form_pasien_meninggal = $('#form_pasien_meninggal');
        $btnSearchPasien       = $('a.pilih-pasien'),
        $popoverPasienContent  = $('div#popover_pasien_content'), 
        $lastPopoverItem       = null,
        $tablePilihPasien      = $('table#table_pilih_pasien');

    var handleValidation = function() {
        var error1   = $('.alert-danger', $form_pasien_meninggal);
        var success1 = $('.alert-success', $form_pasien_meninggal);

        $form_pasien_meninggal.validate({
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


    var handlePilihPasien = function(){
        $('#table_pilih_pasien').dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : mb.baseUrl() + 'master/pasien_meninggal/listing_pilih_pasien',
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
                { 'name' : "pasien.nama nama",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.nama nama",'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });        
        $('#table_pilih_pasien_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_pasien_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $('#table_pilih_pasien'));
        handlePilihPasienSelect( $btnSelects );

        $('#table_pilih_pasien').on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihPasienSelect( $btnSelect );
            
        } );

        $popoverPasienContent.hide();        
    }

    var handlePilihPasienSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $noPasien       = $('input[name="no_member"]'),
                $IdPasien       = $('input[name="id_pasien"]'),
                $NamaPasien     = $('input[name="nama_pasien"]'),
                $AlamatPasien   = $('input[name="alamat_pasien"]'),
                $GenderPasien   = $('input[name="gender_pasien"]'),
                $TglLahirPasien = $('input[name="tgl_lahir_pasien"]'),
                $NomorPasien    = $('input[name="telepon_pasien"]'),
                $Upload         = $('a#upload');
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-pasien').popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdPasien.val($(this).data('item').id);
            $noPasien.val($(this).data('item').no_ktp);
            $NamaPasien.val($(this).data('item').nama);
            $AlamatPasien.val($(this).data('item').alamat);
            if($(this).data('item').gender == 'P')
            {
                $gender = "Perempuan";
            }
            else
            {
                $gender = "Laki-laki";
            }

            $tempat = $(this).data('item').tempat_lahir;
            $tgl = $(this).data('item').tanggal_lahir;
            $ttl = $tempat + ", " + $tgl;
            $GenderPasien.val($gender);
            $TglLahirPasien.val($ttl);
            $NomorPasien.val($(this).data('item').nomor);
            $Upload.removeClass("hidden");
            // alert($itemIdEl.val($(this).data('item').id));


            e.preventDefault();
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


    var handleConfirmSave = function(){
        $('a#confirm_save', $form_pasien_meninggal).click(function() {
            if (! $form_pasien_meninggal.valid()) return;
            
            var msg = $(this).data('confirm');
            var i=0;
            bootbox.confirm(msg, function(result) {
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses..'});
                if (result==true) {
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form_pasien_meninggal).attr('disabled','disabled');
                    if(i === 1)
                    {
                      $('#save').click();
                    }
                }
            });
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

    var searchPasienByNomorAndFill = function(NomorPasien)
    {
        $.ajax({
            type     : 'POST',
            url      : mb.baseUrl() + 'master/pasien_meninggal/search_pasien_by_nomor',
            data     : {no_pasien:NomorPasien},   
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success : function(result){
                if(result.success === true)
                {
                    var 
                        $noPasien   = $('input[name="no_member"]'),
                        $IdPasien   = $('input[name="id_pasien"]'),
                        $NamaPasien = $('input[name="nama_pasien"]'),
                        data        = result.rows;

                        $IdPasien.val(data.id);
                        $noPasien.val(data.no_ktp);
                        $NamaPasien.val(data.nama);
                }
                else
                {
                    mb.showMessage('error',result.msg,'Informasi');
                    $('input#no_member').focus();
                }
            },
            complete : function()
            {
                Metronic.unblockUI();
            }
        });
    }

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form_pasien_meninggal).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleSelectTipeLokasi = function(){
        $('select#tipe_lokasi', $form_pasien_meninggal).on('change', function(){
            var tipe = $(this).val();

            if(tipe == 1)
            {
                $('div#cabang_klinik').removeClass('hidden');
                $('select#cabang_klinik').attr('required','required');
                $('div#lokasi_meninggal').addClass('hidden'); 
                $('input#lokasi_meninggal').removeAttr('required'); 
            }
            if(tipe == 2)
            {
                $('div#cabang_klinik').addClass('hidden');
                $('select#cabang_klinik').removeClass('required');
                $('div#lokasi_meninggal').removeClass('hidden'); 
                $('input#lokasi_meninggal').attr('required','required'); 
            }
        });

        $('select#tipe_lokasi_tujuan', $form_pasien_meninggal).on('change', function(){
            var tipe_tujuan = $(this).val();
            if(tipe_tujuan == 1)
            {
                $('div#lokasi_tujuan').addClass('hidden');
            }
            if(tipe_tujuan == 2)
            {
                $('div#lokasi_tujuan').removeClass('hidden');
                $('label#label_lokasi_tujuan').text('Nama Rumah Sakit');
            }
            if(tipe_tujuan == 3)
            {
                $('div#lokasi_tujuan').removeClass('hidden');
                $('label#label_lokasi_tujuan').text('Nama Rumah Duka');
            }
        });
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/pasien_meninggal/';
        handleValidation();
        handleDatePickers();
        handlePilihPasien();
        handleConfirmSave();
        handleSelectTipeLokasi();
        handleBtnSearchPasien($btnSearchPasien);

        handleJwertyEnterRent($('input#no_member'));
    };
 }(mb.app.pasien_meninggal.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.pasien_meninggal.add.init();
});