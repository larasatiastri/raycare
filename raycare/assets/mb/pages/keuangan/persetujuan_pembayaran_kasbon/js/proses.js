mb.app.proses = mb.app.proses || {};


(function(o){
    
     var 
        baseAppUrl            = '',
        $form                 = $('#form_proses'),
        $tableKasirBiaya        = $('#table_add_detail_setoran_biaya');

    var initForm = function(){
        handleValidation();
        handleConfirmSave();
        handleTerbilang();
        handleKasirBiaya();
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

    var handleTerbilang = function(){
        $('input#nominal_setujui').on('change', function(){
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
                    
                  $('label#terbilang_setujui').text(data.terbilang);
                
                },
                complete : function(){
                  Metronic.unblockUI();
                }
            });

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
                'url' : baseAppUrl + 'listing_add_detail_setoran_biaya/' + $('input[name="titip_setoran_id"]', $form).val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
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
            
                $.ajax
                ({
                    type: 'POST',
                    url: baseAppUrl +  "get_terbilang",  
                    data:  {nominal:total_setoran_biaya},  
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success:function(data)          //on recieve of reply
                    { 
                        
                      $('label#terbilang_biaya').text(data.terbilang);
                    
                    },
                    complete : function(){
                      Metronic.unblockUI();
                    }
                });

        });
    };


    


    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/persetujuan_pembayaran_kasbon/';
        handleValidation();
        initForm();
       
 
    };

}(mb.app.proses));

$(function(){    
    mb.app.proses.init();
});