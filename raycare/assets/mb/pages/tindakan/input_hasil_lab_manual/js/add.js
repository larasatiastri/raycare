mb.app.input_hasil_lab = mb.app.input_hasil_lab || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_hasil_lab'),
        $popoverPasienContent = $('#popover_pasien_content'), 
        $popoverItemContent   = $('#popover_item_content'),
        $lastPopoverPasien    = null,
        $lastPopoverItem      = null,
        $tablePilihPasien     = $('#table_pilih_pasien'),
        $tableTambahItem      = $('#tabel_tambah_item',$form),
        $tableItemSearch      = $('#table_pilih_item'),
        tplItemRow            = $.validator.format($('#tpl_item_row',$form).text()),
        itemCounter           = 0,
        tplFormParent         = '<li class="fieldset">' + $('#tpl-form-upload', $form).val() + '<hr></li>',
        regExpTplUpload         = new RegExp('hasil_lab[0]', 'g'), // 'g' perform global, case-insensitive
        uploadCounter         = 0,
        formsUpload = 
        {
            'bon' : 
            {            
                section  : $('#section-file', $form),
                template : $.validator.format( tplFormParent.replace(regExpTplUpload, '{0}') ), //ubah ke format template jquery validator
                counter  : function(){ uploadCounter++; return uploadCounter-1; },
                fieldPrefix : 'hasil_lab'
            }   
        };


    var initForm = function(){

        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);
        
        addItemRowPaket();
        //addItemRowObat();
        //handleTambahRow();
        handleBtnAdd();

        $.each(formsUpload, function(idx, form){
            var $section           = form.section,
                $fieldsetContainer = $('ul#hasilLabList', $section);

            addFieldsetParent(form,{});

            // handle button add
            $('a.add-upload', form.section).on('click', function(){
                addFieldsetParent(form,{});
            });
             
        }); 

        $('select.select2').select2();
        
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

    var handleBtnAdd = function(){
        $('a#tambah_identitas').click(function() {

            //alert('test');
            addItemRowPaket();
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

            if ($lastPopoverPasien != null) $lastPopoverPasien.popover('hide');

            $lastPopoverPasien = $btn;

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

            $lastPopoverPasien = null;

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
                { 'name':'pasien.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.tanggal_lahir tanggal_lahir','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#table_pilih_pasien_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_pasien_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select-pasien', $tablePilihPasien);
        handlePilihPasienSelect( $btnSelects );

        $tablePilihPasien.on('draw.dt', function (){
            var $btnSelect = $('a.select-pasien', this);
            handlePilihPasienSelect( $btnSelect );
            
        });

        $popoverPasienContent.hide();        
    };

    var handlePilihPasienSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefPasien  = $('input[name="nama_ref_pasien"]'),
                $IdRefPasien    = $('input[name="id_ref_pasien"]'),
                $noRekmedPasien = $('input[name="no_rekmed"]'),
                $usiaPasien     = $('input[name="usia"]'),
                $umurPasien     = $('input[name="umur"]'),
                $kategoriUsia    = $('input[name="kategori_usia_id"]'),
                $alamatPasien   = $('textarea[name="alamat_pasien"]'),
                $tanggalLahir    = $('input[name="tanggal_lahir"]');
                $itemCodeEl     = null,
                $itemNameEl     = null
                ;        


            $('.pilih-pasien', $form).popover('hide');          

            pekerjaan = '-';
            if($(this).data('item').nama_pekerjaan !== null)
            {
                pekerjaan = $(this).data('item').nama_pekerjaan;
            }

            $noRekmedPasien.val($(this).data('item').no_member);
            $IdRefPasien.val($(this).data('item').id);
            $namaRefPasien.val($(this).data('item').nama);
            $alamatPasien.val($(this).data('item').alamat);
            // $usiaPasien.val($(this).data('item').usia);
            $umurPasien.val(parseInt($(this).data('item').umur));
            $kategoriUsia.val(parseInt($(this).data('item').kategori_umur));
            $tanggalLahir.val($(this).data('item').tanggal_lahir);


            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_umur_pasien',
                data     : {tanggal_lahir:$(this).data('item').tanggal_lahir, tanggal_periksa:$('input#tanggal').val()},   
                dataType : 'json',
                success : function(result){
                    $('input#usia').val(result.umur);
                }
            });


            oTable.api().ajax.url(baseAppUrl + 'listing_item/'+parseInt($(this).data('item').kategori_umur)).load();


            e.preventDefault();
        });     
    };

    var handleJwertyEnter = function($nopasien){

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
                    var $namaRefPasien     = $('input[name="nama_ref_pasien"]'),
                        $IdRefPasien       = $('input[name="id_ref_pasien"]'),
                        $noRekmedPasien    = $('input[name="no_rekmed"]'),
                        $tanggalLahir    = $('input[name="tanggal_lahir"]');

                    var data = result.rows;

                    $noRekmedPasien.val(data.no_ktp);
                    $IdRefPasien.val(data.id);
                    $namaRefPasien.val(data.nama);
                    $tanggalLahir.val(data.tanggal_lahir);

                    pekerjaan = '-';
                    if(data.nama_pekerjaan !== null)
                    {
                        pekerjaan = data.nama_pekerjaan;
                    }

                    $('#label_nama_pasien').text(data.nama);
                    $('#label_umur_pasien').text(parseInt(data.umur)+' Tahun');
                    $('#label_alamat_pasien').text(data.alamat);
                    $('#label_pekerjaan_pasien').text(pekerjaan);


                    
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

    function addItemRowPaket()
    {
        var numRow = $('tbody tr', $tableTambahItem).length;


        //if (numRow > 0) return;
        var 
            $rowContainer         = $('tbody', $tableTambahItem),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer )
            ;

                    //alert(numRow);

        // $('input[name$="[kode]"]', $newItemRow).focus();

        // $btnSearchItem = $('button.search-item', $newItemRow);
        // handleBtnSearchItem($btnSearchItem);
        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
    };
     
    var handleTambahRow = function()
    {
        $('a.add-item').click(function() {
            addItemRowPaket();
        });
    };

    var handleBtnDelete = function($btn)
    {
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableTambahItem)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableTambahItem).length == 0){
                addItemRowPaket();
            }
            e.preventDefault();
        });
    };

    var isValidLastItemRow = function()
    {      
        var 
            $itemNotes = $('input[name$="[name]"]', $tableTambahItem ),
            itemNote    = $itemNotes.val()           
        
        return (itemNote != '');
    };

    function addFieldsetParent(form,data)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul#hasilLabList', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            fields             = form.fields,
            prefix             = form.fieldPrefix
        ;

        // alert(is_pkp);

       
        $('a.del-this', $newFieldset).on('click', function(){
            var id = $(this).data('id');
        
            handleDeleteFieldset($(this).parents('.fieldset').eq(0), id);
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
            bootbox.confirm('Anda yakin akan menghapus file ini?', function(result) {
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

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverItemContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableItems = function(){
        oTable = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'pemeriksaan_lab.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'kategori_pemeriksaan_lab.tipe tipe', 'visible' : true, 'searchable': false, 'orderable': true },
                { 'name' : 'kategori_pemeriksaan_lab.nama nama_kategori', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pemeriksaan_lab.nama nama','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pemeriksaan_lab.satuan satuan','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'pemeriksaan_lab.id id','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });  

        $tableItemSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);        
            handleItemSelect( $btnSelect );       
        });
            
        $popoverItemContent.hide();        
    };

    
    var handleItemSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');
            var 
                $parentPop   = $(this).parents('.popover').eq(0),
                rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row         = $('#'+rowId, $tableTambahItem),
                $rowClass    = $('.row_item', $tableTambahItem);                
           
                $itemIdEl       = $('input[name$="[item_id]"]', $row);
                $itemCodeIn     = $('input[name$="[kode]"]', $row);
                $itemIdDetail     = $('input[name$="[id_detail]"]', $row);
                $itemNilaiNormalBawah     = $('input[name$="[nilai_normal_bawah]"]', $row);
                $itemNilaiNormalAtas     = $('input[name$="[nilai_normal_atas]"]', $row);
                $divNilaiNormal     = $('div#item_nilai_normal', $row);
                $itemSatuan    = $('input[name$="[satuan]"]', $row);
                $divSatuan    = $('div#item_satuan', $row);
                $btnAddIdentitas    = $('button.add-identitas', $row);

                itemId = $(this).data('item')['id'];
                    
                $itemIdEl.val($(this).data('item').id);
                $itemCodeIn.val($(this).data('item').nama);
                $itemSatuan.val($(this).data('item').satuan);
                $itemIdDetail.val($(this).data('item_detail').id);
                $itemNilaiNormalBawah.val($(this).data('item_detail').nilai_normal_bawah);
                $itemNilaiNormalAtas.val($(this).data('item_detail').nilai_normal_atas);
                $itemSatuan.val($(this).data('item').satuan);
                $divSatuan.text($(this).data('item').satuan);
                $divNilaiNormal.text($(this).data('item_detail').nilai_normal_bawah +' - '+ $(this).data('item_detail').nilai_normal_atas);


                $('button.search-item', $tableTambahItem).popover('hide');


                addItemRowPaket();

            e.preventDefault();   
        });     
    };

    var isValidLastRow = function()
    {
        var 
            $itemCodeEls    = $('input[name$="[kode]"]',$tableTambahItem),
            $qtyELs         = $('input[name$="[qty]"]',$tableTambahItem),
            itemCode        = $itemCodeEls.eq($qtyELs.length-1).val(),
            qty             = $qtyELs.eq($qtyELs.length-1).val() * 1
        ;

        return (itemCode != '')
    }

    
    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
                autoclose: true
            }).on('changeDate', function(){
                if($('input#tanggal_lahir').val() != ''){
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'get_umur_pasien',
                        data     : {tanggal_lahir:$('input#tanggal_lahir').val(), tanggal_periksa:$('input#tanggal').val()},   
                        dataType : 'json',
                        success : function(result){
                            $('input#usia').val(result.umur);
                        }
                    });
                } 
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

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
                    
                    $('input#url_hasil_lab'+index).attr('value',filename);
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

    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'tindakan/input_hasil_lab_manual/';
        handleJwertyEnter($('input#no_rekmed'));
        handleValidation();
        handleConfirmSave();
        handlePilihPasien();
        handleDataTableItems();
        initForm();
        handleDatePickers();
    };
 }(mb.app.input_hasil_lab));


// initialize  mb.app.home.table
$(function(){
    mb.app.input_hasil_lab.init();
});