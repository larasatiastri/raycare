mb.app.view = mb.app.view || {};


(function(o){
    
    var 
        baseAppUrl              = '',
        $form                   = $('#form_tanda_terima_faktur'),
        $tablePilihSupplier     = $('#table_pilih_supplier'),
        $tableBerkas            = $('#table_berkas'),
        $tableInformation       = $('#table_information'),
        $popoverSupplierContent = $('#popover_supplier_content'), 
        $lastPopoverSupplier    = null,
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        tplBerkasRow            = $.validator.format( $('#tpl_berkas_row').text() ),
        uploadCounter           = 0,
        berkasCounter           = 0,
        itemCounter             = 0,
        itemCounter1            = 0,
        tplFormParent           = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
        tplFormBiaya           = '<li class="fieldset-biaya">' + $('#tpl-form-biaya', $form).val() + '<hr></li>',
        regExpTplBiaya         = new RegExp('biaya[0]', 'g'),   // 'g' perform global, case-insensitive
        regExpTplUpload         = new RegExp('bon[0]', 'g'),   // 'g' perform global, case-insensitive
        biayaCounter           = 1,
        
        formsBiaya = 
        {
            'biaya' : 
            {            
                section  : $('#section-biaya', $form),
                template : $.validator.format( tplFormBiaya.replace(regExpTplBiaya, '{0}') ), //ubah ke format template jquery validator
                urlData  : function(){ return baseAppUrl + 'get_biaya_tambahan'; },
                counter  : function(){ biayaCounter++; return biayaCounter-1; },
                fields   : ['id','pembelian_id','biaya_id','nominal','is_active'],
                fieldPrefix : 'biaya'
            }   
        };
        formsUpload = 
        {
            'bon' : 
            {            
                section  : $('#section-bon', $form),
                template : $.validator.format( tplFormParent.replace(regExpTplUpload, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ uploadCounter++; return uploadCounter-1; },
                fieldPrefix : 'bon'
            }   
        }
        totalBayar              = 0;

        var $btnAddBerkas          = $('a.add-berkas');

    var initForm = function(){
      
        var 
            $btnSearchSupplier = $('.pilih-supplier');

        handleBtnSearchSupplier($btnSearchSupplier);
        handleBtnAddBerkas($btnAddBerkas);

        // tambah 1 row kosong pertama
        addBerkasRow();

        $('input[name="tipe_supplier"]').on('click', function(){
            iStatTipe = this.value;
            // alert(iStatTipe);
            $tablePilihSupplier.api().ajax.url(baseAppUrl + 'listing_supplier/' + iStatTipe).load();
        });
        $.each(formsUpload, function(idx, form){
            var $section           = form.section,
                $fieldsetContainer = $('ul#invoiceList', $section);

            addFieldsetParent(form,{});

            // handle button add
            $('a.add-upload', form.section).on('click', function(){
                addFieldsetParent(form,{});
            });
             
        }); 

        $.each(formsBiaya, function(idx, formBiaya){
            var $section           = formBiaya.section,
                $fieldsetContainer = $('ul#biayaList', $section);

            addFieldsetBiaya(formBiaya,{});

            // handle button add
            $('a.add-biaya', formBiaya.section).on('click', function(){
                addFieldsetBiaya(formBiaya,{});
            });
             
        }); 

    };

    var addBerkasRow = function()
    { 
        var numRow = $('tbody tr', $tableBerkas).length;
        var 
            $rowContainer     = $('tbody', $tableBerkas),
            $newItemRow       = $(tplBerkasRow(berkasCounter++)).appendTo( $rowContainer );

            handleBtnDeleteBerkas($('button.del-this-berkas', $newItemRow));

            $('input[name$="[nilai]"]', $newItemRow).on('keyup', function(){
                calculateTotalKeseluruhan();
            });
           
    };

    function addFieldsetBiaya(form,data)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul#biayaList', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            fields             = form.fields,
            prefix             = form.fieldPrefix
        ;

        if(Object.keys(data).length>0){
            for (var i=0; i<fields.length; i++){
                // format: name="emails[_ID_1][subject]"
                $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).val( data[fields[i]] );
                $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).attr( 'value', data[fields[i]] );
                $('a.del-this-biaya', $newFieldset).attr('data-id',data[fields[0]]);
            }       
        }
    
        $('a.del-this-biaya', $newFieldset).on('click', function(){
            var id = $(this).data('id');
        
            handleDeleteFieldsetBiaya($(this).parents('.fieldset-biaya').eq(0), id);
        });

        $('input[name$="[nominal]"]', $newFieldset).on('change', function(){
            handleCountTotalBiaya();
        });

        $('select[name$="[biaya_id]"]', $newFieldset).select2();

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');
        
        handleUploadifyBiaya();
    };



    var handleBtnAddBerkas = function($btn) {
        $btn.click(function() {
            addBerkasRow();
        });
    };

    var handleBtnDeleteBerkas = function($btn)
    {
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableBerkas);

        $btn.on('click', function(e){
            $row.remove();
            if($('tbody>tr', $tableBerkas).length == 0){
                addBerkasRow();
            }
            e.preventDefault();
        });
    };

    function handleDeleteFieldsetBiaya($fieldset, id)
    {
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset-biaya', $parentUl).length,
            hasId         = false ; 

        if(id != undefined)
        {
            var i = 0;
            bootbox.confirm('Anda yakin akan menghapus biaya ini?', function(result) {
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

        handleCountTotalBiaya();
    }

    function addFieldsetParent(form,data)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul#invoiceList', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            fields             = form.fields,
            prefix             = form.fieldPrefix,
            is_pkp             = $('input#is_pkp').val()
        ;

        // alert(is_pkp);

        if(is_pkp == 1){
            $('.upload_pkp', $newFieldset).removeClass('hidden');
        }else{
            $('.upload_pkp', $newFieldset).addClass('hidden');
        }
    
        $('a.del-this', $newFieldset).on('click', function(){
            var id = $(this).data('id');
        
            handleDeleteFieldset($(this).parents('.fieldset').eq(0), id);
        });

        $('input[name$="[total_bon]"]', $newFieldset).on('change', function(){
            handleCountTotal();
        });

        handleUploadify();
        handleUploadifyPajak();
        handleDatePickers();

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
            bootbox.confirm('Anda yakin akan menghapus invoice ini?', function(result) {
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

    var handleCountTotal = function(){
        var $totalBon = $('input[name$="[total_bon]"]', $form),
            grandTotal = 0;
        
        $.each($totalBon, function(idx, totalbon){
            var total = $(this).val();

            if(total == ''){
                total = 0;
            }if(total != ''){
                total = parseInt(total);
            }

            grandTotal = grandTotal + total;
        });

        $('td#label_total_invoice').text(mb.formatRp(grandTotal));
        $('input#total_invoice').val(grandTotal);

    } 

    var handleCountTotalBiaya = function(){
        var $totalBiaya = $('input[name$="[nominal]"]', $form),
            grandTotal = 0,
            grandTotalPO = parseInt($('input#grand_tot_hidden').val());

        
        $.each($totalBiaya, function(idx, totalbiaya){
            var total = $(this).val();

            if(total == ''){
                total = 0;
            }if(total != ''){
                total = parseInt(total);
            }

            grandTotal = grandTotal + total;
        });

        $('td#biaya_tambahan_po').text(mb.formatRp(grandTotal));
        $('input#biaya_tambah_hidden').val(grandTotal);
        $('input#biaya_tambah_hidden').attr('value',grandTotal);
        $('input#grand_tot_biaya_hidden').val(grandTotal + grandTotalPO);
        $('input#grand_tot_biaya_hidden').attr('value', (grandTotal + grandTotalPO));
        $('td#grand_tot_biaya').text(mb.formatRp(grandTotal + grandTotalPO));

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
    

    var calculateTotalKeseluruhan = function(){

        // var nominal = $('input[name$="[nominal]"]');

        var 
            $rows     = $('tbody>tr', $tableBerkas), 
            $nilai = $('input[name$="[nilai]"]', $tableBerkas),
            totalFaktur = 0;
        ;

        $.each($nilai, function(idx, row)
        {
            
                // alert($('input[name$="[item_harga]"]', $row).val());

                totalFaktur = totalFaktur + parseInt($(this).val());;
                // alert(cost);
                
                $('input#total_keseluruhan').val(mb.formatRp(totalFaktur));
                $('input#total_keseluruhan_hidden').val(totalFaktur);

                if (!isNaN(totalFaktur)){

                $('input#total').val(mb.formatRp(totalFaktur));
                $('input#total_hidden').val(totalFaktur);
                
                } else {

                $('input#total').val(0);
                $('input#total_hidden').val(0);

                }

 

        });

            
    }

    var handleBtnSearchSupplier = function($btn){
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

            if ($lastPopoverSupplier != null) $lastPopoverSupplier.popover('hide');

            $lastPopoverSupplier = $btn;

            $popoverSupplierContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverSupplierContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverSupplierContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverSupplierContent.hide();
            $popoverSupplierContent.appendTo($('.page-content'));

            $lastPopoverSupplier = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableSupplier = function() 
    {
        $tablePilihSupplier.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_supplier/1' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'supplier.id id','visible' : false, 'searchable': false, 'orderable': false },
                { 'name' : 'supplier.kode kode','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier.nama nama','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier.orang_yang_bersangkutan kontak_person','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier_alamat.alamat alamat','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier.rating rating','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'supplier.id id','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $('#table_pilih_supplier_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_supplier_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePilihSupplier.on('draw.dt', function (){
            var $btnSelect = $('a.select-supplier', this);
            handlePilihSupplierSelect( $btnSelect );
            
        });

        $popoverSupplierContent.hide();
    }

    var handlePilihSupplierSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdSupplier     = $('input[name="id_supplier"]'),
                $IsPKP          = $('input#is_pkp'),
                $KodeSupplier   = $('input[name="nama_supplier"]'),
                $NamaSupplier   = $('label#nama_supplier'),
                $KontakSupplier = $('label#kontak_supplier'),
                $AlamatSupplier = $('label#alamat_supplier'),
                $EmailSupplier  = $('label#email_supplier'),
                $tipePembayaran = $('label#tipe_bayar'),              
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-supplier').popover('hide');          

            
            
            $IdSupplier.val($(this).data('item').id);
            $KodeSupplier.val($(this).data('item').kode);
            $NamaSupplier.text($(this).data('item').nama);
            $KontakSupplier.text($(this).data('item').kontak_person +' ('+ $(this).data('item').no_telp +')');
            $AlamatSupplier.text($(this).data('item').alamat+','+$(this).data('item').kelurahan+','+$(this).data('item').kecamatan+','+$(this).data('item').kota+','+$(this).data('item').propinsi+','+$(this).data('negara'));
            $EmailSupplier.text($(this).data('email').email); 

            var is_pkp = $(this).data('item').is_pkp;
            
            $IsPKP.attr('value', is_pkp); 
            $IsPKP.val($(this).data('item').is_pkp); 


            if(is_pkp == 1){
                $('.upload_pkp').removeClass('hidden');
            }else{
                $('.upload_pkp').addClass('hidden');
            }

            $('a#show_modal').attr('href', baseAppUrl+'modal_po/'+$(this).data('item').id);
            $('a#show_modal').click();
            e.preventDefault();

            $.each(formsUpload, function(idx, form){
                // handle button add
                $('a.add-upload', form.section).on('click', function(){
                    addFieldsetParent(form,{});
                });
                 
            }); 
        });     
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
        var time = new Date($('#tanggal_faktur').val());
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "right",
                autoclose: true,
                update : time

            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }    

    function handleUploadify()
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
                    

                    $('input#bon_url_'+index).attr('value',filename);
                    // Add the HTML to the UL element
                    ul.html(tpl);
                    handleFancybox();
                    // data.context = tpl.appendTo(ul);

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
        });
        
    }

    function handleUploadifyPajak()
    {
        $('.upl_invoice').each(function(index)
        {
            var ul_pajak = $('#upload_pajak_'+index+' ul.ul-img-pajak');
       
            // Initialize the jQuery File Upload plugin
            $('#upl_pajak_'+index).fileupload({

                // This element will accept file drag/drop uploading
                dropZone: $('#drop'),
                dataType: 'json',
                // This function is called when a file is added to the queue;
                // either via the browse button, or via drag/drop:
                add: function (e, data) {

                    tplpajak = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

                    // Initialize the knob plugin
                    tplpajak.find('input').knob();

                    // Listen for clicks on the cancel icon
                    tplpajak.find('span').click(function(){

                        if(tplpajak.hasClass('working')){
                            jqXHR.abort();
                        }

                        tplpajak.fadeOut(function(){
                            tplpajak.remove();
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
                        tplpajak.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                    }
                    else
                    {
                        tplpajak.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                    }
                    

                    $('input#bon_url_pajak_'+index).attr('value',filename);
                    // Add the HTML to the UL element
                    ul_pajak.html(tplpajak);
                    handleFancybox();
                    // data.context = tplpajak.appendTo(ul);

                    Metronic.unblockUI();
                        // data.context = tplpajak.appendTo(ul);

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

    function handleUploadifyBiaya()
    {
        $('.upl_biaya').each(function(index)
        {
            index = index + 1;
            var ul = $('#upload_biaya_'+index+' ul.ul-img');
       
            // Initialize the jQuery File Upload plugin
            $('#upl_biaya_'+index).fileupload({

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
                    

                    $('input#biaya_url_'+index).attr('value',filename);
                    // Add the HTML to the UL element
                    ul.html(tpl);
                    handleFancybox();
                    // data.context = tpl.appendTo(ul);

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
        });
        
    }


    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/tanda_terima_faktur/';
        handleValidation();
        calculateTotalKeseluruhan();
        initForm();
        handleDatePickers(); 
        handleConfirmSave();
        handleDataTableSupplier();

 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});