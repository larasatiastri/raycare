mb.app.compare_file_sep_ina = mb.app.compare_file_sep_ina || {};
(function(o){

    var 
        baseAppUrl             = '',
        $form                  = $('#form_compare_file_sep_ina'),
        $tableCompareFile        = $('#table_compare_file_sep_ina');


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

    var handleDateRangePicker = function() {
    	$('#reportrange').daterangepicker({
            opens: (Metronic.isRTL() ? 'left' : 'right'),
            startDate: moment().subtract('days', 29),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2020',
            dateLimit: {
                days: 60
            },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Last 30 Days': [moment().subtract('days', 29), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            buttonClasses: ['btn'],
            applyClass: 'green',
            cancelClass: 'default',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            locale: {
                applyLabel: 'Apply',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        },
        function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('input#tgl_awal').val(start.format('D-MM-YYYY'));
            $('input#tgl_akhir').val(end.format('D-MM-YYYY'));
        });
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().subtract('month', 1).startOf('month').format('MMMM D, YYYY') + ' - ' + moment().subtract('month', 1).endOf('month').format('MMMM D, YYYY'));
        $('input#tgl_awal').val(moment().subtract('month', 1).startOf('month').format('D-MM-YYYY'));
        $('input#tgl_akhir').val(moment().subtract('month', 1).endOf('month').format('D-MM-YYYY'));
    };

    var handleUploadify = function()
    {
        var ul = $('#upload_sep ul');

       
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

                console.log(data);
                var filename = data.result.filename;
                var filename = filename.replace(/ /g,"_");
                
                $('input#url_sep').attr('value',filename);
                $('div#text_filename_sep').text(filename);
                // // Add the HTML to the UL element
                Metronic.unblockUI();

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

    var handleUploadifyIna = function()
    {
        var ul = $('#upload_ina ul');

       
        // Initialize the jQuery File Upload plugin
        $('#upl_ina').fileupload({

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
                
            	$('input#url_ina').attr('value',filename);
            	$('div#text_filename_ina').text(filename);
                // // Add the HTML to the UL element
                Metronic.unblockUI();

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

    var handleButtonRefresh = function(){
        $('a#btn_compare').click(function(){
            if($('input#url_sep').val() != '' && $('input#url_ina').val() != '')
            {
                $.ajax({
                    type: 'POST',
                    url: baseAppUrl +  "import_file",  
                    data:  {filename_sep:$('input#url_sep').val(), filename_ina:$('input#url_ina').val(), tgl_awal:$('input#tgl_awal').val(), tgl_akhir : $('input#tgl_akhir').val()},  
                    dataType : 'json',
                    success:function(data)          //on recieve of reply
                    { 
                       if(data.success == false)
                       {
                            $('td#total_hd').text(0);
                            $('td#total_sep').text(0);
                            $('td#total_ina').text(0);

                            $('td#total_hd_double').text(0);
                            $('td#total_sep_double').text(0);
                            $('td#total_ina_double').text(0);

                            $('td#total_hd_blm_input').text(0);
                            $('td#total_sep_blm_input').text(0);
                            $('td#total_ina_blm_input').text(0);

                            mb.showMessage('error', data.msg, 'Informasi');
                       }
                       else
                       {
                            $('td#total_hd').text(data.count_hd);
                            $('td#total_sep').text(data.count_sep);
                            $('td#total_ina').text(data.count_ina);

                            $('td#total_hd_double').text(data.count_hd_double);
                            $('td#total_sep_double').text(data.count_sep_double);
                            $('td#total_ina_double').text(data.count_ina_double);

                            $('td#total_hd_blm_input').text(0);
                            $('td#total_sep_blm_input').text(data.count_sep_tanpa_ina);
                            $('td#total_ina_blm_input').text(data.count_ina_tanpa_sep);
                            
                            mb.showMessage('success', data.msg, 'Informasi');
                       }
                    }   
                });
            }
            else
            {
                mb.showMessage('error', 'File TXT belum diupload', 'Informasi');
            }
        });
        
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function(){

            if (! $form.valid()) return;

            var msg = $(this).data('confirm'),
                i = 0;

            bootbox.confirm(msg, function(result){
                if(result == true)
                {
                    Metronic.blockUI({boxed: true});
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                      $('#save', $form).click();
                    }
                }
            });

        });
    }

    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klaim/compare_file_sep_ina/';
        handleValidation();
        handleDateRangePicker();
        handleUploadify();
        handleUploadifyIna();
        handleButtonRefresh();
        handleConfirmSave();
    };
 }(mb.app.compare_file_sep_ina));


// initialize  mb.app.home.table
$(function(){
    mb.app.compare_file_sep_ina.init();
});