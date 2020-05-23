mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl              = '',
        $form                   = $('#form_add_titip_setoran'),
        $tableKasirBiaya        = $('#table_add_detail_setoran_biaya')
        ;

    var initForm = function(){
        handleValidation();
        handleUploadify();
        handleDatePickers();
        handleConfirmSave();
        handleKasirBiaya();
        handleTerbilang();

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

 

    var handleDatePickers = function () {
        // var time = new Date($('#waktu_selesai').val());
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
                // update : time

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

    var handleKasirBiaya = function(){

        $tableKasirBiaya.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'filter'                : false,
            'info'                  : false,
            'paginate'              : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_add_detail_setoran_biaya',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });       
        $('#table_add_detail_setoran_biaya_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_add_detail_setoran_biaya_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown


        $tableKasirBiaya.on('draw.dt', function (){
            
            var total_setoran_biaya = $('input[name="total_biaya"]', this).val();
            var total = 0;
            
            if(!isNaN(total_setoran_biaya)){
                total = total_setoran_biaya;
                $('label#jumlah_bon').text(mb.formatRp(parseInt(total_setoran_biaya)));
                $('input#rupiah_bon').val(total_setoran_biaya);
                $('input#rupiah_bon').attr('value',total_setoran_biaya);
            }else{
                $('label#jumlah_bon').text(mb.formatRp(parseInt(0)));
                $('input#rupiah_bon').val(0);
                $('input#rupiah_bon').attr('value',0);
            }
            
            var sisa_biaya = 2000000 - parseInt(total);
                $('input#rupiah').val(sisa_biaya);

                $.ajax
                ({
                    type: 'POST',
                    url: baseAppUrl +  "get_terbilang",  
                    data:  {nominal:sisa_biaya},  
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success:function(data)          //on recieve of reply
                    { 
                        
                      $('label#terbilang').text(data.terbilang);
                    
                    },
                    complete : function(){
                      Metronic.unblockUI();
                    }
                });

        });
    };

    var handleTerbilang = function(){
        $('input#rupiah').on('change', function(){
            var nominal = $(this).val();
            $.ajax
            ({
                type: 'POST',
                url: baseAppUrl +  "get_terbilang",  
                data:  {nominal:nominal},  
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(data)          //on recieve of reply
                { 
                    
                  $('label#terbilang').text(data.terbilang);
                
                },
                complete : function(){
                  Metronic.unblockUI();
                }
            });

        });
    }

    var handleUploadify = function()
    {
        var ul = $('#upload ul');

       
        // Initialize the jQuery File Upload plugin
        $('#upl').fileupload({

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
                
                $('input#url_bukti_setor').attr('value',filename);
                // // Add the HTML to the UL element
                ul.html(tpl);
                // data.context = tpl.appendTo(ul);
                
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

    

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/titip_terima_setoran/';
        initForm();

       
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});