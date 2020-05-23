mb.app.cabang = mb.app.cabang || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form 					= $('#form_add_dokter_sppd'),
        $popoverPasienContent     = $('#popover_pasien_content'), 
        $lastPopoverItem        = null,
        $tablePilihPasien        = $('#table_pilih_pasien'),
        tplFormParent   = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
        regExpTplUpload = new RegExp('gambar[0]', 'g'),
        uploadCounter   = 0,
        baseAppUrl      = '',
        formsUpload     = 
        {
            'gambar' : 
            {            
                section  : $('#section-gambar', $form),
                template : $.validator.format( tplFormParent.replace(regExpTplUpload, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ uploadCounter++; return uploadCounter-1; },
                fieldPrefix : 'gambar'
            }   
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


	 var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                format : 'dd M yyyy',
                autoclose: true
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
    }

    var initform = function()
    {
        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);

        $('input#diagnosa2').on('change', function(){
            $('label#label_diagnosa2').text($(this).val());
        });

        $('label#label_diagnosa1').text($('input#diagnosa1').val());

        $('textarea#alasan').on('keyup change', function(){
            $('label#label_alasan_hd').text($(this).val());
        });

        $.each(formsUpload, function(idx, form){
            var $section           = form.section,
                $fieldsetContainer = $('ul#list_gambar', $section);

            addFieldsetParent(form,{});

            // handle button add
            $('a.add-upload', form.section).on('click', function(){
                addFieldsetParent(form,{});
            });
             
        }); 
    }

    function addFieldsetParent(form,data)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul#list_gambar', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            fields             = form.fields,
            prefix             = form.fieldPrefix,
            $parentUl           = $newFieldset.parent(),
            fieldsetCount       = $('.fieldset', $parentUl).length
        ;

    
        $('a.del-this', $newFieldset).on('click', function(){
            var id = $(this).data('id');
        
            handleDeleteFieldset($(this).parents('.fieldset').eq(0), id);
        });

        $('input#radio_primary_gambar_id_0').prop('checked', true);
        $('input[name$="[is_primary_gambar]"]').removeAttr('value');
        $('input#primary_gambar_id_0').attr('value',1);

        $('input[name="gambar_is_primary"]', $newFieldset).on('click', function()
        {
            $('input[name$="[is_primary_gambar]"]').removeAttr('value');
            $('input[name$="[is_primary_gambar]"]', $newFieldset).attr('value',1);
        });

        handleUploadify();

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');


    };

    function handleDeleteFieldset($fieldset, id)
    {
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset', $parentUl).length,
            hasId         = false ; 

        if(id != undefined)
        {
            var i = 0;
            bootbox.confirm('Anda yakin akan menghapus gambar ini?', function(result) {
                if (result==true) {
                    i = parseInt(i) + 1;
                    if(i == 1)
                    {
                        $('input[name$="[is_active]"]', $fieldset).val(0);
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

    }

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
                { 'name' : 'pasien.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien.tanggal_lahir tanggal_lahir','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien_alamat.alamat alamat','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien.id id','visible' : true, 'searchable': false, 'orderable': false }
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
                $NamaPasien     = $('input[name="nama_pasien"]'),
                $lblNamaPasien     = $('label#label_nama_pasien'),
                $lblNamaPasienPrev     = $('label#label_nama_pasien_prev'),
                $lblNoRMPasien     = $('label#label_norekmed'),
                $AlamatPasien   = $('textarea[name="alamat_pasien"]'),
                $lblAlamatPasien   = $('label#label_alamat_pasien'),
                $lblAlamatPasienPrev   = $('label#label_alamat_pasien_prev'),
                $lblUmurPasien   = $('label#label_umur_pasien'),
                $lblUmurPasienPrev   = $('label#label_umur_pasien_prev'),
                $GenderPasien   = $('input[name="gender_pasien"]'),
                $TglLahirPasien = $('input[name="tgl_lahir_pasien"]'),
                $NomorPasien    = $('input[name="telepon_pasien"]'),
                $Upload         = $('a#upload');
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-pasien', $form).popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdPasien.val($(this).data('item').id);
            $noPasien.val($(this).data('item').no_ktp);
            $lblNoRMPasien.text($(this).data('item').no_ktp);
            $NamaPasien.val($(this).data('item').nama);
            $lblNamaPasien.text($(this).data('item').nama);
            $lblNamaPasienPrev.text($(this).data('item').nama);
            $lblUmurPasien.text(parseInt($(this).data('item').umur)+' Tahun');
            $lblUmurPasienPrev.text(parseInt($(this).data('item').umur)+' Tahun');
            $AlamatPasien.val($(this).data('item').alamat);
            $lblAlamatPasien.text($(this).data('item').alamat);
            $lblAlamatPasienPrev.text($(this).data('item').alamat);
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

            handleDataTindakan($(this).data('item').id);


            e.preventDefault();
        });     
    };

    var handleUploadify = function()
    {
        $('.upl_invoice').each(function(index)
        {
            var ul = $('#upload_'+index+' ul.ul-img');
       
            // Initialize the jQuery File Upload plugin
            $('#upl_'+index).fileupload({

                // This element will accept file drag/drop uploading
                dropZone: $('#drop'),
                dataType: 'json',
                // This function is called when a file is added to the queue;
                // either via the browse button, or via drag/drop:
                add: function (e, data) {

                    tpl = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

                    // Initialize the knob plugin
                    tpl.find('input').knob();

                    // Listen for clicks on the cancel icon
                    tpl.find('span').click(function(){

                        if(tpl.hasClass('working')){
                            jqXHR.abort();
                        }

                        tpl.fadeOut(function(){
                            tpl.remove();
                        });

                    });

                    // Automatically upload the file once it is added to the queue
                    var jqXHR = data.submit();
                },
                done: function(e, data){
                     console.log(data);
                    var filename = data.result.filename;
                    var filename = filename.replace(/ /g,"_");
                    var filetype = data.files[0].type;

                    if(filetype == 'image/jpeg' || filetype == 'image/png' || filetype == 'image/gif')
                    {
                        tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                    }
                    else
                    {
                        tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                    }
                    

                    $('input#nama_'+index).attr('value',filename);
                    // Add the HTML to the UL element
                    ul.html(tpl);
                    handleFancybox();
                    // data.context = tpl.appendTo(ul);

                    Metronic.unblockUI('#upl_'+index);
                        // data.context = tpl.appendTo(ul);

                },

                progress: function(e, data){

                    // Calculate the completion percentage of the upload
                    Metronic.blockUI({boxed: true, target:'#upl_'+index});
                },


                fail:function(e, data){
                    // Something has gone wrong!
                    bootbox.alert('File Tidak Dapat Diupload');
                    Metronic.unblockUI('#upl_'+index);
                }
            });


            // Prevent the default action when a file is dropped on the window
            $(document).on('drop dragover', function (e) {
                e.preventDefault();
            });

            // Helper function that formats the file sizes
            function formatFileSize(bytes) {
                if (typeof bytes !== 'number') {
                    return '';
                }

                if (bytes >= 1000000000) {
                    return (bytes / 1000000000).toFixed(2) + ' GB';
                }

                if (bytes >= 1000000) {
                    return (bytes / 1000000).toFixed(2) + ' MB';
                }

                return (bytes / 1000).toFixed(2) + ' KB';
            }
        });
    }

    var handleFancybox = function() {
        if (!jQuery.fancybox) {
            return;
        }

        if ($(".fancybox-button").size() > 0) {
            $(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });
        }
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

        $('a#check_required', $form).click(function() {
            if (! $form.valid()) return;
            $('a#show_modal').click();
        });

        $('a#modal_ok', $form).click(function() {
            if (! $form.valid()) return;

		      $('#save', $form).click();
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
            url      : baseAppUrl + 'search_pasien_by_nomor',
            data     : {no_pasien:NomorPasien},   
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success : function(result){
                if(result.success === true)
                {
                    $noPasien       = $('input[name="no_member"]'),
                    $IdPasien       = $('input[name="id_pasien"]'),
                    $NamaPasien     = $('input[name="nama_pasien"]'),
                    $lblNamaPasien     = $('label#label_nama_pasien'),
                    $lblAlamatPasien   = $('label#label_alamat_pasien'),
                    $lblUmurPasien   = $('label#label_umur_pasien');

                    var data = result.rows;
                                console.log(data);

                    $IdPasien.val(data.id);
                    $noPasien.val(data.no_ktp);
                    $NamaPasien.val(data.nama);
                    $lblNamaPasien.text(data.nama);
                    $lblUmurPasien.text(parseInt(data.umur)+' Tahun');
                    $lblAlamatPasien.text(data.alamat);
                   
                    
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


    var handleDataTindakan = function($pasien_id) {
        $.ajax
        ({ 
            type: 'POST',
            url: baseAppUrl +  "get_past_tindakan/" + $pasien_id,  
            dataType : 'html',
            beforeSend : function(){
            },
            success:function(results)          //on recieve of reply
            { 
                $('tbody#tabel_tindakan').html('');
                $('tbody#tabel_tindakan').html(results);
                
            },

            complete : function(){
            }
        });
    }
	

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/surat_dokter_sppd/';
        handleJwertyEnterRent($('input#no_member'));
        handlePilihPasien();
        handleValidation();
        initform();
        handleDatePickers();
        handleConfirmSave();

    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});