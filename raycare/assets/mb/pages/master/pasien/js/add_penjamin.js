mb.app.pasien = mb.app.pasien || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form                   = $('#form_penjamin_pasien'),
        $popoverPenjaminContent = $('#popover_penjamin_content'), 
        $lastPopoverItem        = null,
        $tablePilihDataKlaim    = $('#table_pilih_data_penjamin'),
        $idPasien               = $('input#id_pasien'),
        $currentRow             = $('input#row'),
        $btnSearchDataPenjamin  = $('.pilih-data-penjamin', $form);

    var initForm = function(){



    };


    var handleSelectPenjamin = function(){
    	$('select#penjamin').on('change', function(){
    		
            $.ajax({
                type     	: 'POST',
                url      	: baseAppUrl + 'show_penjamin_kelompok',
                data     	: {id_penjamin: $(this).val()},
                dataType 	: 'text',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses...' });
                },
                success  	: function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#show_penjamin_kelompok").html(results);
                    //alert(results);
                },
                complete : function()
                {
                    Metronic.unblockUI();
                }
            });

            $.ajax({
                type     	: 'POST',
                url      	: baseAppUrl + 'show_penjamin',
                data     	: {id_penjamin: $(this).val(), id_pasien: $('input#id_pasien').val()},
                dataType 	: 'text',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses...' });
                },
                success  	: function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#show_penjamin").html(results);
                    //alert(results);
                    $('input[type=radio]').uniform();
                    $('input[type=checkbox]').uniform();
                    handleDatePickers();
                    $('.uploadbutton').each(function(index){
                        handleUploadifyDokumen(index);

                    });

                    handleFancybox();
                    
                    $('a.pilih-data-penjamin', $form).on('click', function(){
                        var id = $(this).data('id');

                        $currentRow.val(id);

                    });

                    var $btnSearchDataPenjamin  = $('.pilih-data-penjamin', $form);
                    handleBtnSearchDataPenjamin($btnSearchDataPenjamin);

                    $("#show_penjamin").find('script').each(function(){
                    event.preventDefault();
                    eval($(this).text());
                    });
                },
                complete : function()
                {
                    Metronic.unblockUI();
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
            var i = 0;
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses...'});
                    i = parseInt(i) + 1;
                    if(i==1)
                    {
                        $('#save', $form).click();
                    }
                    
                }
            });
        });
    };

    var handleMultiSelect = function () {
        $('.multi-select').multiSelect();   
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
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleUploadifyDokumen = function(index)
    {
        
            var ul = $('#upload_dokumen_'+index+' ul.ul-img');

         
            // Initialize the jQuery File Upload plugin
            $('#upload_'+index).fileupload({

                // This element will accept file drag/drop uploading
               
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
                        tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                    }
                    else
                    {
                        tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                    }
                    $('input[name$="[value]"]', $('#upload_dokumen_'+index)).attr('value',filename);
                    // Add the HTML to the UL element
                    ul.html(tpl);
                    handleFancybox();
                    Metronic.unblockUI();
                    // data.context = tpl.appendTo(ul);

                },

                progress: function(e, data){

                    // Calculate the completion percentage of the upload
                    Metronic.blockUI({boxed: true});
                },

                fail:function(e, data){
                    // Something has gone wrong!
                    bootbox.alert('File Tidak Dapat Diupload');
                    Metronic.unblockUI();
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

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/pasien/';
        handleValidation();
        handleSelectPenjamin();
        handleMultiSelect();
        handleConfirmSave();
        initForm();
        // handleUploadifyDokumen();
    };
 }(mb.app.pasien));


// initialize  mb.app.home.table
$(function(){
    mb.app.pasien.init();
});