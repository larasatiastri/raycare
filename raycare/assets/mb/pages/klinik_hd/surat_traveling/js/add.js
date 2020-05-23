mb.app.surat_traveling = mb.app.surat_traveling || {};
mb.app.surat_traveling.add = mb.app.surat_traveling.add || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_surat_traveling'),
        $popoverPasienContent = $('#popover_pasien_content'), 
        $lastPopoverItem      = null,
        $tablePilihPasien     = $('#table_pilih_pasien');

    var initForm = function(){

        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);
        handleAlasan();
   
    };

    var handleAlasan = function(){
        $('input[name="alasan"]', $form).on('change', function(){
            var nilai = $(this).val();

            if(nilai == 2){
                $('div#travel_for').removeClass('hidden');
                $('div#travel_for_reason').removeClass('hidden');
                $('div#move_for_reason').addClass('hidden');
                $('div#move_for_type').removeClass('hidden');
                $('#alasan_pindah').removeAttr('required');
                $('#tipe_pindah').removeClass('required');
            }if(nilai == 1){
                $('div#travel_for').addClass('hidden');
                $('div#travel_for_reason').addClass('hidden');
                $('div#move_for_reason').removeClass('hidden');
                $('div#move_for_type').removeClass('hidden');
                $('#alasan_pindah').attr('required','required');
                $('#tipe_pindah').attr('required','required');

            }if(nilai == 3){
                $('div#travel_for').addClass('hidden');
                $('div#travel_for_reason').addClass('hidden');
                $('div#move_for_reason').addClass('hidden');
                $('div#move_for_type').addClass('hidden');
                $('div#tujuan_internal').addClass('hidden');
                $('select#rs_tujuan_internal').removeAttr('required');

                $('#alasan_pindah').removeAttr('required');
                $('#tipe_pindah').removeAttr('required');
                
            }
        });

        $('select[name="tipe_pindah"]', $form).on('change', function(){
            var tipe_pindah = $(this).val();

            if(tipe_pindah == 1){
                $('#tujuan_internal').removeClass('hidden');
                $('#tujuan_external').addClass('hidden');
                $('select#rs_tujuan_internal').attr('required','required');
                $('input#rs_tujuan').removeAttr('required');
            }if(tipe_pindah == 2){
                $('#tujuan_internal').addClass('hidden');
                $('#tujuan_external').removeClass('hidden');
                $('select#rs_tujuan_internal').removeAttr('required');
                $('input#rs_tujuan').attr('required','required');
            }if(tipe_pindah == ''){
                $('#tujuan_internal').addClass('hidden');
                $('#tujuan_external').removeClass('hidden');
                $('select#rs_tujuan_internal').removeAttr('required');
                $('input#rs_tujuan').attr('required','required');
            }
        });
    };

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleValidation = function() {
        var error1   = $('.alert-danger', $form);
        var success1 = $('.alert-success', $form);

        $form.validate({
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

    var handleBtnSearchPasien = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

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

    var handlePilihPasien = function(){
        $tablePilihPasien.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_pasien',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'pasien.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.tanggal_lahir tanggal_lahir','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': false, 'orderable': false }
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
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefPasien   = $('input[name="nama_ref_pasien"]'),
                $IdRefPasien   = $('input[name="id_ref_pasien"]'),
                $noRekmedPasien   = $('input[name="no_rekmed"]'),
                $umurPasien   = $('input[name="umur_sex_pasien"]'),
                $itemCodeEl = null,
                $itemNameEl = null
                ;        


            $('.pilih-pasien', $form).popover('hide');          

            // console.log($itemIdEl)
            var gend = '';            
            if($(this).data('item').gender == 'P')
            {
                gend = 'Female';
            }
            if($(this).data('item').gender == 'L')
            {
                gend = 'Male';
            }
            var age = '';            
            if(parseFloat($(this).data('item').umur) < 1)
            {
                age = 'Under 1 y.o ';     
            }
            else
            {
                age = Math.ceil($(this).data('item').umur)+' y.o ';                                            
            }

            $noRekmedPasien.val($(this).data('item').no_ktp);
            $IdRefPasien.val($(this).data('item').id);
            $namaRefPasien.val($(this).data('item').nama);
            $umurPasien.val(age+gend);

            e.preventDefault();
        });     
    };
   
    var handleCheckHari = function(){
        var checkDay = $('input.check_hari');

        checkDay.on('change', function(){
            var checked = $('input.check_hari:checked').length;
            $('input#freq_hemo').val(checked);
        });
    };

    var handleJwertyEnter = function($nopasien){

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
            url      : baseAppUrl + 'search_pasien_by_nomor',
            data     : {no_pasien:NomorPasien},   
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success : function(result){
                if(result.success === true)
                {
                    var $namaRefPasien     = $('input[name="nama_ref_pasien"]'),
                        $IdRefPasien       = $('input[name="id_ref_pasien"]'),
                        $umurPasien        = $('input[name="umur_sex_pasien"]'),
                        $noRekmedPasien    = $('input[name="no_rekmed"]');

                    var data = result.rows;

                    var gend = '';            
                    if(data.gender == 'P')
                    {
                        gend = 'Female';
                    }
                    if(data.gender == 'L')
                    {
                        gend = 'Male';
                    }
                    var age = '';            
                    if(parseFloat(data.umur) < 1)
                    {
                        age = 'Under 1 y.o ';     
                    }
                    else
                    {
                        age = Math.ceil(data.umur)+' y.o ';                                            
                    }

                    $noRekmedPasien.val(data.no_ktp);
                    $IdRefPasien.val(data.id);
                    $namaRefPasien.val(data.nama);
                    $umurPasien.val(age+gend);
                    


                }
                else if(result.success === false)
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
    };
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/surat_traveling/';
        handleJwertyEnter($('input#no_rekmed'));
        handleValidation();
        handleConfirmSave();
        handleDatePickers();
        handlePilihPasien();
        initForm();
        handleCheckHari();
    };
 }(mb.app.surat_traveling.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.surat_traveling.add.init();
});