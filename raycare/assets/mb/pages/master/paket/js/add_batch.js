mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl          = '',
        $form               = $('#form_addtemplate'),
        $tablePaketItem     = $('#table_paket_item'),
        $tablePaketTindakan = $('#table_paket_tindakan'),
        id                  = $('input#paket_id').val()
    ;


    var handleDataTableItems = function(){
        
        oTable = $tablePaketItem.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            // 'sAjaxSource'              : baseAppUrl + 'listing_alat_obat',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_item_batch/' + id,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'paket.nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       

    };

    var handleDataTableItemsTitipan = function(){

        oTable2 = $tablePaketTindakan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_tindakan_batch/' + id,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                ]
        });       
        $('#table_paket_tindakan_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_paket_tindakan_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePaketTindakan.on('draw.dt', function (){
            
        });

    };

    jQuery('#table_paket_item .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }                    
        });
        jQuery.uniform.update(set);
    });

     jQuery('#table_paket_tindakan .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }                    
        });
        jQuery.uniform.update(set);
    });


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



    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/paket/';
        handleValidation();
        handleConfirmSave();
        handleDataTableItems();
        handleDataTableItemsTitipan();
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});