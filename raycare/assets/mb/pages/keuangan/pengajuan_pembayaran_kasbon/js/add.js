mb.app.pengajuan_pembayaran_kasbon = mb.app.pengajuan_pembayaran_kasbon || {};
mb.app.pengajuan_pembayaran_kasbon.add = mb.app.pengajuan_pembayaran_kasbon.add || {};


(function(o){
    
     var 
        baseAppUrl            = '',
        $form                 = $('#form_add_pengajuan_pembayaran_kasbon'),
        $tableKasbon          = $('#tabel_kasbon');

    var initForm = function(){
        handleValidation();
        handleDatePickers();
        handleConfirmSave();
        handleTerbilang();
        handleDataTableKasbon();
    };

   

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            // alert('klik');
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
        var time = new Date($('#tanggal').val());
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
                autoclose: true,
                update : time

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

    var handleTerbilang = function(){
        $('input#nominal').on('change', function(){
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

    var handleDataTableKasbon = function() 
    {
        $tableKasbon.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_kasbon',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'desc']],
            'filter'                : false,
            'paginate'              : false,
            'info'                  : false,
            'pagingType'            : 'full_numbers',
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableKasbon.on('draw.dt', function (){
            $('input.checkboxes', this).uniform();

            var $check = $('input.checkboxes', this);
                total_kasbon   = 0;

            $.each($check, function(idx, check){
                if($(this).prop('checked') == true){
                    var rp      = $(this).data('rp');
                    var rpInt   = parseInt(rp);

                    total_kasbon = total_kasbon + rpInt;                
                }
            });
            total_kasbon = total_kasbon;
            $('input[name="nominal"]').val(total_kasbon);
            $('input[name="nominal"]').attr('value',total_kasbon);

            var nominal = total_kasbon;
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

            checklist();

        });
    }

    var checklist = function() {

        var nominal      = parseInt($('input#nominal').val());

        $('input.checkboxes', $tableKasbon).on('click', function(){

            if($(this).prop('checked') == true)
            {
                $(this).parent().addClass('checked');
                $(this).attr('checked','checked');
                
                var rp      = $(this).data('rp');
                var rpInt   = parseInt(rp);

                nominal = nominal + rpInt;
                $('input#nominal').val(nominal);
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

            }else{
                var rp      = $(this).data('rp');
                var rpInt   = parseInt(rp);

                $(this).parent().removeClass('checked');
                $(this).removeAttr('checked');
            
                nominal = nominal - rpInt;
                $('input#nominal').val(nominal);
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

            }     
        });
    }


   
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pengajuan_pembayaran_kasbon/';
        initForm();

    };

}(mb.app.pengajuan_pembayaran_kasbon.add));

$(function(){    
    mb.app.pengajuan_pembayaran_kasbon.add.init();
});