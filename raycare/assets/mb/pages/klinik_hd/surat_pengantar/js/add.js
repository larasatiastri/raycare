mb.app.surat_pengantar = mb.app.surat_pengantar || {};
mb.app.surat_pengantar.add = mb.app.surat_pengantar.add || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_surat_pengantar'),
        $popoverPasienContent = $('#popover_pasien_content'), 
        $lastPopoverItem      = null,
        $tablePilihPasien     = $('#table_pilih_pasien'),
        tplFormDiagnosis      = '<li class="fieldset">' + $('#tpl-form-diagnosis', $form).val() + '</li>',
        regExpTplDiagnosis    = new RegExp('diagnosa_awal[0]', 'g'),   // 'g' perform global, case-insensitive
        diagnosisCounter      = 1,
        formsDiagnosis = {
            'diagnosis' : 
            {            
                section  : $('#section-diagnosis', $form),
                template : $.validator.format( tplFormDiagnosis.replace(regExpTplDiagnosis, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ diagnosisCounter++; return diagnosisCounter-1; },
                fields   : ['code_ast','name'],
                fieldPrefix : 'diagnosa_awal'
            }      
        };

    var initForm = function(){

        $.each(formsDiagnosis, function(idx, form){
            
            $('a.add-diagnose', form.section).on('click', function(){
                addFieldsetDiagnose(form,{});
            });  
            // // beri satu fieldset kosong
            addFieldsetDiagnose(form,{});
        });  


        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);
   
    };

    var addFieldsetDiagnose = function(form,data)
    {
      if(! isValidLastDiagnoseRow() ) return;

        var 
            $section           = form.section,
            $fieldsetContainer = $('ul#ul-diagnosis', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            fields             = form.fields,
            prefix             = form.fieldPrefix;

        if(Object.keys(data).length>0){
            for (var i=0; i<fields.length; i++){
                // format: name="emails[_ID_1][subject]"
                $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).val( data[fields[i]] );
                $('a.del-this', $newFieldset).attr('data-id',data[fields[0]]); 
                $('a.search', $newFieldset).attr('disabled','disabled');  
            }       
        }
        else
        {
          $('a.search', $newFieldset).removeAttr('disabled'); 

          oTableDiagnose = $('table#table_diagnosa', $newFieldset).dataTable({
              'processing'            : true,
              'serverSide'            : true,
              'language'              : mb.DTLanguage(),
              'ajax'                  : {
                  'url' : baseAppUrl + 'listing_icd_code',
                  'type' : 'POST',
              },          
              'pageLength'            : 5,
              'stateSave'             : true,
              'info'                : false,     
              'lengthMenu'            : [[5,10, 25, 50, 100], [5,10, 25, 50, 100]],
              'order'                 : [[0, 'asc']],
              'columns'               : [
                  { 'visible' : true, 'searchable': true, 'orderable': false },
                  { 'visible' : true, 'searchable': true, 'orderable': false },
              ]

            });
            $('table#table_diagnosa', $newFieldset).on('draw.dt', function(){
          
                $('a.icd_name', this).on('click', function(e){
                    var code = $(this).data('code');
                    var name = $(this).data('name');

                    $('input[name$="[code_ast]"]', $newFieldset).val(code);
                    $('input[name$="[name]"]', $newFieldset).val(name);

                    oTableDiagnose.api().ajax.url(baseAppUrl + 'listing_icd_code').load();

                    addFieldsetDiagnose(form,{});

                    $('div#div_table_diagnosa', $newFieldset).addClass('hidden');
                    e.preventDefault();
                });
              

              $('div.table-scrollable').addClass('table-scrollable-borderless');
            });
            
            $('a.search', $newFieldset).toggle(
                function(){
                  $('div#div_table_diagnosa', $newFieldset).removeClass('hidden');

                  $('a.chapter_name', $('table#table_diagnosa', $newFieldset)).on('click', function(e){
                    var code = $(this).data('code');

                    oTableDiagnose.api().ajax.url(baseAppUrl + 'listing_icd_code').load();

                    e.preventDefault();
                });

                },
                function(){
                   $('div#div_table_diagnosa', $newFieldset).addClass('hidden');
                 }
            );
          }


        $('a.del-this', $newFieldset).on('click', function(){
            var id = $(this).data('id');
        
            handleDeleteFieldsetDiagnose($(this).parents('.fieldset').eq(0), id);
        });

    };

    var isValidLastDiagnoseRow = function(){
        
        var 
            $itemCodeEls = $('input[name$="[code_ast]"]', $('div#section-diagnosis') ),
            $itemNameEls = $('input[name$="[name]"]', $('div#section-diagnosis') ),
            itemCode    = $itemCodeEls.val(),
            itemName    = $itemNameEls.val()           
        ;
        return (itemCode != '' && itemName != '');

    };

    var handleDeleteFieldsetDiagnose = function($fieldset, id){
      var 
          $parentUl     = $fieldset.parent(),
          fieldsetCount = $('.fieldset', $parentUl).length,
          hasId         = false,  //punya id tidak, jika tidak bearti data baru
          hasDefault    = 0;

        if(id != undefined)
        {
            var i = 0;
            bootbox.confirm('Anda yakin akan menghapus diagnosa ini?', function(result) {
                if (result==true) {
                    i = parseInt(i) + 1;
                    if(i == 1)
                    {
                      $('input[name$="[is_deleted]"]', $fieldset).val(1);
                      $fieldset.hide();                                               
                    }
                }
            });
        }
        else
        {
            if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
            $fieldset.remove();            
        }
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
                $itemCodeEl = null,
                $itemNameEl = null
                ;        


            $('.pilih-pasien', $form).popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $noRekmedPasien.val($(this).data('item').no_ktp);
            $IdRefPasien.val($(this).data('item').id);
            $namaRefPasien.val($(this).data('item').nama);

            // alert($itemIdEl.val($(this).data('item').id));


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
    
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/surat_pengantar/';
        handleJwertyEnter($('input#no_rekmed'));
        handleValidation();
        handleConfirmSave();
        handleDatePickers();
        handlePilihPasien();
        initForm();
    };
 }(mb.app.surat_pengantar.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.surat_pengantar.add.init();
});