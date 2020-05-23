mb.app.verifikasi_klaim_bpjs = mb.app.verifikasi_klaim_bpjs || {};
mb.app.verifikasi_klaim_bpjs.add = mb.app.verifikasi_klaim_bpjs.add || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_proses_verif_klaim'),
        $tableTidakLayak      = $('#tabel_tindakan_tidak_layak', $form),
        $tabelPasienSearch    = $('#table_pasien'),
        $popoverPasienContent = $('#popover_pasien_content'),
        tplPasienRow          = $.validator.format( $('#tpl_item_row').text() ),
        pasienCounter         = 9,
        $lastPopoverPasien    = null;

    var initForm = function(){
        
        addItemRow();
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
            
            // pindahkan $popoverPasienContent ke .popover-conter
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

    var addItemRow = function(){

        var numRow = $('tbody tr', $tableTidakLayak).length;
        var 
            $rowContainer    = $('tbody', $tableTidakLayak),
            $newItemRow      = $(tplPasienRow(pasienCounter++)).appendTo( $rowContainer ),
            $btnSearchPasien = $('.search-pasien', $newItemRow);

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
        // handle button search item
        handleBtnSearchPasien($btnSearchPasien);

    };

    var handleDataTablePasien = function(){
        
        $tabelPasienSearch.dataTable({
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
                { 'name' : 'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien_alamat.alamat alamat','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien.id id','visible' : true, 'searchable': false, 'orderable': true },
                ]
        });       
      
        $tabelPasienSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
        
            handlePasienSelect( $btnSelect );
        });
        $popoverPasienContent.hide();        
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

    var handleBtnDelete = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableTidakLayak)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableTidakLayak).length == 0){
                addItemRow();
                // addItemAccRow();
            }
            e.preventDefault();
        });
    };

    var handlePasienSelect = function($btn){
        $btn.on('click', function(e){

            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableTidakLayak);                
           
            $pasienIdEl = $('input[name$="[id_pasien]"]', $row);
            $pasienNoRMEL = $('input[name$="[no_rm]"]', $row);
            $pasienNamaEL = $('input[name$="[nama_pasien]"]', $row);
            $('.search-pasien', $tableTidakLayak).popover('hide');
            
            pasien_id = $(this).data('item')['id'];
            periode_tindakan = $('input#periode_tindakan', $form).val();
            $selectTanggal = $('select[name$="[tanggal_tindakan]"]', $row);

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_tanggal_tindakan',
                data     : {pasien_id: pasien_id, periode_tindakan:periode_tindakan},
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success  : function( results ) {
                    if(results.success == true)
                    {
                        rows = results.rows;
                        $selectTanggal.val();

                        $.each(rows, function(key, value) {
                            $selectTanggal.append($("<option></option>")
                                .attr("value", value.val).text(value.tanggal));
                        });
                    }
                    else
                    {
                        mb.showMessage('error','Tidak ada tindakan untuk pasien terpilih','Informasi');
                        $selectTanggal.empty();
                    }
                },
                complete : function() {
                     Metronic.unblockUI();
                }
            });
            $pasienIdEl.val($(this).data('item')['id']);
            $pasienNoRMEL.val($(this).data('item')['no_ktp']);
            $pasienNamaEL.val($(this).data('item')['nama']);
            
            addItemRow();
            e.preventDefault();   
        });   
    };

    var handleInputTidakLayak = function(){
        $('input#tindakan_tidak_layak', $form).on('change', function(){
            var jml_tindakan = $('input#jumlah_tindakan', $form).val(),
                jml_verif = parseInt(jml_tindakan - $(this).val());

            $('label#label_jumlah_tindakan_verif', $form).text(jml_verif);
            $('input#jumlah_tindakan_verif', $form).val(jml_verif);
        });

        $('input#tarif_tidak_layak', $form).on('change', function(){
            var jumlah_tarif_ina = $('input#jumlah_tarif_ina', $form).val(),
                jml_tarif_verif = parseInt(jumlah_tarif_ina - $(this).val());

            $('label#label_jumlah_tarif_ina_verif', $form).text(mb.formatRp(jml_tarif_verif));
            $('input#jumlah_tarif_ina_verif', $form).val(jml_tarif_verif);
        });
    };

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }


    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klaim/verifikasi_klaim_bpjs/';
        handleValidation();
        handleConfirmSave();
        handleDataTablePasien();
        handleInputTidakLayak();
      handleDatePickers();
        initForm();
    };
 }(mb.app.verifikasi_klaim_bpjs.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.verifikasi_klaim_bpjs.add.init();
});