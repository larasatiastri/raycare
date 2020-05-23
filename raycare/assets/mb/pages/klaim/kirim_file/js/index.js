mb.app.kirim_file = mb.app.kirim_file || {};
(function(o){

    var 
        baseAppUrl             = '',
        $form                  = $('#form_kirim_file'),
        $tableKirimFile        = $('#table_kirim_file'),
        $tableKirimFileHistory = $('#table_kirim_file_history');

    var handleDataTable = function() 
    {
    	oTable = $tableKirimFile.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
            'paginate'              : false,
            'info'                  : false,
            'filter'                : false,
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableKirimFile.on('draw.dt', function (){
			$('.btn', this).tooltip();
			$('input[type=checkbox]', this).uniform();
			
		} );
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

                console.log(data);
                var filename = data.result.filename;
                var filename = filename.replace(/ /g,"_");
                
            	$('input#url').attr('value',filename);
            	$('div#text_filename').text(filename);
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
        $('a#generate').click(function(){
            if($('input#url').val() != '')
            {
                $.ajax({
                    type: 'POST',
                    url: baseAppUrl +  "import_file",  
                    data:  {filename:$('input#url').val()},  
                    dataType : 'json',
                    success:function(data)          //on recieve of reply
                    { 
                       if(data.success == false)
                       {
                            mb.showMessage('error', data.msg, 'Informasi');
                            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()).load();
                       }
                       else
                       {
                            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()).load();
                       }
                    }   
                });
            }
            else
            {
                mb.showMessage('error', 'File TXT belum diupload', 'Informasi');
            }
        });

        $('a#refresh').click(function(){
            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()).load();
        });

        $('a#update_inacbg',$form ).click(function(){
            $.ajax({
                type: 'POST',
                url: baseAppUrl +  "update_inacbg",  
                data:  $form.serialize(),  
                dataType : 'json',
                success:function(data)          //on recieve of reply
                { 
                   if(data.success == false)
                   {
                        mb.showMessage('error', data.msg, 'Informasi');
                        oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()).load();
                   }
                   else
                   {
                        mb.showMessage('success', data.msg, 'Informasi');
                        oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + $("#tgl_awal").val() + '/' + $("#tgl_akhir").val()).load();
                   }
                }   
            });
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
        baseAppUrl = mb.baseUrl() + 'klaim/kirim_file_txt/';
        handleValidation();
        handleDataTable();
        handleDateRangePicker();
        handleUploadify();
        handleButtonRefresh();
        handleConfirmSave();
    };
 }(mb.app.kirim_file));


// initialize  mb.app.home.table
$(function(){
    mb.app.kirim_file.init();
});