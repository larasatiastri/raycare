mb.app.antrian_cek_lab = mb.app.antrian_cek_lab || {};
mb.app.antrian_cek_lab.proses = mb.app.antrian_cek_lab.proses || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_cek_lab'),
        $popoverPasienContent = $('#popover_pasien_content'), 
        $lastPopoverPasien    = null,
        $tablePilihPasien     = $('#table_pilih_pasien');
       

    var initForm = function(){
        $('input[name$="[pilih]"]', $form).on('change', function(){
            handleCheckbox();       
         });
       
    };

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

            if ($lastPopoverPasien != null) $lastPopoverPasien.popover('hide');

            $lastPopoverPasien = $btn;

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

            $lastPopoverPasien = null;

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
                $itemCodeEl = null,
                $itemNameEl = null
                ;        


            $('.pilih-pasien', $form).popover('hide');          

            pekerjaan = '-';
            if($(this).data('item').nama_pekerjaan !== null)
            {
                pekerjaan = $(this).data('item').nama_pekerjaan;
            }

            $noRekmedPasien.val($(this).data('item').no_ktp);
            $IdRefPasien.val($(this).data('item').id);
            $namaRefPasien.val($(this).data('item').nama);


            $('#label_nama_pasien').text($(this).data('item').nama);
            $('#label_umur_pasien').text(parseInt($(this).data('item').umur)+' Tahun');
            $('#label_alamat_pasien').text($(this).data('item').alamat);
            $('#label_pekerjaan_pasien').text(pekerjaan);

            e.preventDefault();
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
                        $noRekmedPasien    = $('input[name="no_rekmed"]');

                    var data = result.rows;

                    $noRekmedPasien.val(data.no_ktp);
                    $IdRefPasien.val(data.id);
                    $namaRefPasien.val(data.nama);

                    pekerjaan = '-';
                    if(data.nama_pekerjaan !== null)
                    {
                        pekerjaan = data.nama_pekerjaan;
                    }

                    $('#label_nama_pasien').text(data.nama);
                    $('#label_umur_pasien').text(parseInt(data.umur)+' Tahun');
                    $('#label_alamat_pasien').text(data.alamat);
                    $('#label_pekerjaan_pasien').text(pekerjaan);


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
    }

   
    var handleTerdaftar = function()
    {
        $('a#btn_terdaftar').on('click', function(){
            $('a#btn_tidak_terdaftar').removeClass('btn-primary');
            $('a#btn_tidak_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('input#id_ref_pasien').attr('required','required');
            $('input#no_rekmed').attr('required','required');


            $('div.pasien_terdaftar').removeClass('hidden');
            $('input#nama_ref_pasien').attr('readonly','readonly');
            $('input#tipe_pasien').val(1);
        });

        $('a#btn_tidak_terdaftar').on('click', function(){
            $('a#btn_terdaftar').removeClass('btn-primary');
            $('a#btn_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('input#id_ref_pasien').removeAttr('required');
            $('input#no_rekmed').removeAttr('required');


            $('div.pasien_terdaftar').addClass('hidden');
            $('input#nama_ref_pasien').removeAttr('readonly');
            $('input#tipe_pasien').val(2);
        });
    }

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
                autoclose: true
            }).on('changeDate', function(){
                if($('input#tanggal_lahir').val() != ''){
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'get_umur_pasien',
                        data     : {tanggal_lahir:$('input#tanggal_lahir').val(), tanggal_periksa:$('input#tanggal_periksa').val()},   
                        dataType : 'json',
                        success : function(result){
                            $('input#umur_pasien').val(result.umur);
                        }
                    });
                } 
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_umur_pasien',
            data     : {tanggal_lahir:$('input#tanggal_lahir').val(), tanggal_periksa:$('input#tanggal_periksa').val()},   
            dataType : 'json',
            success : function(result){
                $('input#umur_pasien').val(result.umur);
            }
        });
    }

    var handleCheckbox = function(){

        var arrayNominal = [],
            total_bon = 0;

        $.each($('input[name$="[pilih]"]:checked'), function(idx){
            arrayNominal.push($(this).data('nominal'));

            total_bon = parseInt(total_bon + $(this).data('nominal'));

            //alert(total_bon);
        });

        // alert(total_bon);

        $('span#total_bayar').text(mb.formatRp(total_bon));
        $('input#total_bayar').val(total_bon);
        $('input#total_bayar').attr("value" , total_bon);

       
    }

    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'laboratorium/antrian_cek_lab/';
        handleJwertyEnter($('input#no_rekmed'));
        handleTerdaftar();
        handleValidation();
        handleConfirmSave();
        handleDatePickers();
        
        initForm();
    };
 }(mb.app.antrian_cek_lab.proses));


// initialize  mb.app.home.table
$(function(){
    mb.app.antrian_cek_lab.proses.init();
});