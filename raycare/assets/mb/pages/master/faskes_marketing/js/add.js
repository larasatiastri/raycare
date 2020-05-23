mb.app.faskes_marketing = mb.app.faskes_marketing || {};
mb.app.faskes_marketing.add = mb.app.faskes_marketing.add || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_faskes'),
        $popoverFaskesContent   = $('#popover_faskes_content'),
        $lastPopoverFaskes      = null,
        $tableTambahFaskes      = $('#tabel_tambah_faskes',$form),
        $tableFaskesSearch    = $('#table_pilih_faskes'),
        tplFaskesRow            = $.validator.format($('#tpl_faskes_row',$form).text()),
        tplFormParent            = $('#tpl_faskes_row',$form).text(),
        faskesCounter           = 0,
        regExpTplFaskes = new RegExp('faskes[0]', 'g'),   // 'g' perform global, case-insensitive
        formsFaskes = 
        {
            'faskes' : 
            {            
                section  : $('#section-faskes', $form),
                urlData  : function(){ return baseAppUrl + 'get_faskes'; },
                template : $.validator.format( tplFormParent.replace(regExpTplFaskes, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ faskesCounter++; return faskesCounter-1; },
                fields   : ['id','kode_faskes','nama_faskes', 'jenis', 'nama_reg', 'alamat', 'telp','is_active'],
                fieldPrefix : 'faskes'
            }   
        };

    var initForm = function(){

        $('select#marketing_id', $form).on('change', function(){
            marketing_id = $(this).val();

            $.each(formsFaskes, function(idx, form){
                $.ajax({
                    type     : 'POST',
                    url      : form.urlData(),
                    data     : {marketing_id: marketing_id},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        if (results.success === true) {
                            var rows = results.rows;

                            $body = $('tbody', $tableTambahFaskes);
                            $body.html('');
                            $.each(rows, function(idx, data){
                                addFaskesRow(form, data);
                            });
                        }
                        else
                        {
                            $body = $('tbody', $tableTambahFaskes);
                            $body.html('');
                            addFaskesRow(form,{});
                        }

                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            });  
        });
    
        $.each(formsFaskes, function(idx, form){
            $('a.add-item', form.section).on('click', function(){
                addFaskesRow(form,{});
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


    function addFaskesRow(form, data)
    {
        var $section           = form.section,
            $tableTambahFaskes = $('#tabel_tambah_faskes', $section)
            numRow             = $('tbody tr', $tableTambahFaskes).length,
            counter            = form.counter(),
            $rowContainer         = $('tbody', $tableTambahFaskes),
            $newItemRow           = $(form.template(counter)).appendTo( $rowContainer ),
            fields             = form.fields,
            prefix             = form.fieldPrefix;

        if(Object.keys(data).length>0){
            for (var i=0; i<fields.length; i++){
                // format: name="emails[_ID_1][subject]"
                $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newItemRow).val( data[fields[i]] );
                $('button.del-this', $newItemRow).attr('data-id',data[fields[1]]);
                $('button.search-item', $newItemRow).attr('disabled','disabled');
            }       
        }


        $btnSearchItem = $('button.search-item', $newItemRow);
        handleBtnSearchItem($btnSearchItem);
        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
    };
    
    var handleBtnDelete = function($btn)
    {
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableTambahFaskes)

        $btn.on('click', function(e){ 
            var id = $(this).data('id'),
                msg = $(this).data('confirm');

            if(id != undefined){
                bootbox.confirm(msg, function(result){
                    if(result == true){
                        $.ajax({
                            type     : 'POST',
                            url      : baseAppUrl + 'delete_faskes_marketing',
                            data     : {kode_faskes: id},
                            dataType : 'json',
                            beforeSend : function(){
                                Metronic.blockUI({boxed: true });
                            },
                            success  : function( results ) {
                                if (results.success === true) {
                                    $row.remove();
                                    if($('tbody>tr', $tableTambahFaskes).length == 0){
                                        $.each(formsFaskes, function(idx, form){
                                            addFaskesRow(form,{});
                                        });
                                    }
                                    oTable.api().ajax.url(baseAppUrl +  'listing_faskes').load();

                                }
                                else
                                {
                                    
                                }

                            },
                            complete : function(){
                                Metronic.unblockUI();
                            }
                        });
                        
                    }
                });
            }else{
                $row.remove();
                if($('tbody>tr', $tableTambahFaskes).length == 0){
                    $.each(formsFaskes, function(idx, form){
                        addFaskesRow(form,{});
                    });
                }
            }        
            e.preventDefault();
        });
    };

    var isValidLastItemRow = function()
    {      
        var 
            $itemNotes = $('input[name$="[name]"]', $tableTambahFaskes ),
            itemNote    = $itemNotes.val()           
        
        return (itemNote != '');
    };
    
    var handleBtnSearchItem = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '640px', maxWidth: '420px'});

            if ($lastPopoverFaskes != null) $lastPopoverFaskes.popover('hide');

            $lastPopoverFaskes = $btn;

            $popoverFaskesContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverFaskesContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverFaskesContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverFaskesContent ke .page-content
            $popoverFaskesContent.hide();
            $popoverFaskesContent.appendTo($('.page-content'));

            $lastPopoverFaskes = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableFaskes = function(){
        oTable = $tableFaskesSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_faskes',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'master_faskes.jenis jenis', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'master_faskes.kode_faskes kode_faskes','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'master_faskes.nama_faskes nama_faskes','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'master_faskes.alamat alamat','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'master_faskes.nama_reg nama_reg','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'master_faskes.jenis jenis','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_pilih_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableFaskesSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select_faskes', this);        
            handleItemSelect( $btnSelect );       
        });
            
        $popoverFaskesContent.hide();        
    };

    var handleItemSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');
            var 
                $parentPop   = $(this).parents('.popover').eq(0),
                rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row         = $('#'+rowId, $tableTambahFaskes),
                $rowClass    = $('.row_item', $tableTambahFaskes);                
           
                $itemIdEl     = $('input[name$="[id]"]', $row);
                $itemCodeIn   = $('input[name$="[kode_faskes]"]', $row);
                $itemNameIn   = $('input[name$="[nama_faskes]"]', $row);
                $itemTipeIn  = $('input[name$="[jenis]"]', $row);
                $itemRegIn    = $('input[name$="[nama_reg]"]', $row);
                $itemAlamatIn    = $('input[name$="[alamat]"]', $row);
                $itemTeleponIn = $('input[name$="[telp]"]', $row);

                itemId = $(this).data('item')['id'];

                $itemIdEl.val($(this).data('item')['id']);
                $itemCodeIn.val($(this).data('item')['kode_faskes']);
                $itemNameIn.val($(this).data('item')['nama_faskes']);
                $itemTipeIn.val($(this).data('item')['jenis']);
                $itemRegIn.val($(this).data('item')['nama_reg']);
                $itemAlamatIn.val($(this).data('item')['alamat']);
                $itemTeleponIn.val($(this).data('item')['telp']);

                var faskes_id = $(this).data('item')['id'],
                    kode_faskes = $(this).data('item')['kode_faskes'],
                    marketing_id = $('select#marketing_id').val();

                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'save_faskes_marketing',
                        data     : {faskes_id: faskes_id, kode_faskes:kode_faskes, marketing_id:marketing_id},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            if (results.success === true) {

                                $.each(formsFaskes, function(idx, form){
                                    addFaskesRow(form,{});
                                });
                                
                                oTable.api().ajax.url(baseAppUrl +  'listing_faskes').load();

                                mb.showMessage('success', results.msg, 'Sukses');
                            }
                            else
                            {
                                $itemIdEl.val('');
                                $itemCodeIn.val('');
                                $itemNameIn.val('');
                                $itemTipeIn.val('');
                                $itemRegIn.val('');
                                $itemAlamatIn.val('');
                                $itemTeleponIn.val('');
                            }

                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });


                $('button.search-item', $tableTambahFaskes).popover('hide');


            e.preventDefault();   
        });     
    };
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/faskes_marketing/';
        handleValidation();
        handleConfirmSave();
        handleDataTableFaskes();
        initForm();
    };
 }(mb.app.faskes_marketing.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.faskes_marketing.add.init();
});