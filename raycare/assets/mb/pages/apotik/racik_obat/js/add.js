mb.app.resep_obat = mb.app.resep_obat || {};
mb.app.resep_obat.add = mb.app.resep_obat.add || {};

// mb.app.resep_obat.add namespace
(function(o){

    var 
        baseAppUrl           = '',
        $form                = $('#form_add_racik_obat'),
        $errorTop            = $('.alert-danger', $form),
        $succesTop           = $('.alert-success', $form),
        $tableItemDigunakan  = $('#table_item_digunakan', $form),
        $tableItemSearch     = $('#table_item_search'),
        $tableResepSearch    = $('#table_resep_search'),
        $popoverItemContent  = $('#popover_item_content'), 
        $lastPopoverItem     = null,
        $popoverResepContent = $('#popover_resep_content'), 
        $lastPopoverResep    = null,
        tplItemRow           = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter          = 1,
        
        i                    = parseInt($('input#i').val()),
        
        tplFormIdentitas     = '<li class="fieldset">' + $('#tpl-form-identitas').val() + '<hr></li>',
        regExpTplIdentitas   = new RegExp('identitas[0]', 'g'),   // 'g' perform global, case-insensitive
        identitasCounter     = 1,

        formsIdentitas = {
                        'identitas' : 
                        {            
                            section  : $('#ajax_notes'),
                            template : $.validator.format( tplFormIdentitas.replace(regExpTplIdentitas, '_id_{0}') ), //ubah ke format template jquery validator
                            counter  : function(){ identitasCounter++; return identitasCounter-1; }
                        }
                           
                    }
        ;


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
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control locker
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control locker
            } 
        });   
    }

    var initForm = function(){
        var $btnSearchResep = $('.search-resep');
   
        handleBtnSearchResep($btnSearchResep);    


        $.each(formsIdentitas, function(idx, form){
            // handle button add
                

                // $('a#tambah_identitas',form.section).on('click', function(){

                //     $classrow   = $('div.show_identitas'),
                //     IdentitasIdAll = $('input.identitas_id', $classrow),
                //     found = false;
                //     $.each(IdentitasIdAll, function(idx, value){
                //         thisIdx = idx + 1;
                //         if($('input#identitas_tambah').val() == $('select#identitas').val())
                //         {
                            
                //                 alert('input#identitas_'+this.value+'_'+thisIdx);
                            

                //         }else{
                //             if ($('input#identitas_'+this.value+'_'+thisIdx).val() == $('input#identitas_tambah').val()) {
                //                 alert('sudah tampil');
                //             }else{
                //                 $.ajax({
                //                     type        : 'POST',
                //                     url         : baseAppUrl + 'show_identitas',
                //                     data        : {identitas_id: $('select#identitas').val(), item_id : $('input#item_id').val(), item_satuan_id : $('input#item_satuan_id').val(), i : $('input#i').val()},
                //                     dataType    : 'text',
                //                     success     : function( results ) {
                //                         // $kelas_select.val('Pilih Kelas');
                //                         $('div.show_identitas').append(results);
                //                         $('div#form_tambahan').append(results);
                //                         // alert(results);
                //                     }
                //                 });

                //                 $('input#identitas_tambah').val($('select#identitas').val());
                //             }
                            

                //         }
                        
                //     });
    
                    // alert(form.counter());

                    // if($('input#identitas_tambah').val() == $('select#identitas').val())
                    // {
                    //     thisIdx = idx + 1;
                    //         alert('input#identitas_'+this.value+'_'+thisIdx);
                        

                    // }else{
                    //     $.ajax({
                    //         type        : 'POST',
                    //         url         : baseAppUrl + 'show_identitas',
                    //         data        : {identitas_id: $('select#identitas').val(), item_id : $('input#item_id').val(), item_satuan_id : $('input#item_satuan_id').val(), i : $('input#i').val()},
                    //         dataType    : 'text',
                    //         success     : function( results ) {
                    //             // $kelas_select.val('Pilih Kelas');
                    //             $('div.show_identitas').append(results);
                    //             $('div#form_tambahan').append(results);
                    //             // alert(results);
                    //         }
                    //     });

                    //     $('input#identitas_tambah').val($('select#identitas').val());

                    // }
                    
                    
                    
                // });

                // $('a#tambah_fieldset',form.section).on('click', function(){
                    
                //     addFieldsetIdentitas(form, 'addFieldset');
                //     i = i + 1;
                //     $('input#i').val(i);
                // });

                $('a#tambah_identitas',form.section).on('click', function(){

                    
                    
                    $classrow   = $('div.show_identitas'),
                    IdentitasIdAll = $('input.identitas_id', $classrow),
                    found = false;
                    $.each(IdentitasIdAll, function(idx, value){
                        thisIdx = idx + 1;
                            alert(this.value);

                        if($('select#identitas').val() == this.value)
                        {

                            now = idx-1;
                            found = true;
                            alert(this.value+' '+idx+1);
                            $('input#identitas_'+this.value).removeClass('hidden');
                            $('label#identitas_'+this.value).removeClass('hidden');
                            $('input#identitas_'+this.value).addClass('tampil');
                            $('label#identitas_'+this.value).addClass('tampil');

                            $('input#identitas_tambah').val(thisIdx);

                            if ($('input#identitas_'+this.value).hasClass('tampil')) {
                                $('a#tambah_identitas',form.section).addClass('hidden');
                                $('a#tambah_fieldset',form.section).removeClass('hidden');
                            };
                        }

                        
                    });
                    
                });
                
                $('a#tambah_fieldset',form.section).on('click', function(){
                    
                    addFieldsetIdentitas(form, 'addFieldset');
                    $('a#tambah_identitas',form.section).removeClass('hidden');

                    i = i + 1;
                    $('input#i').val(i);
                });




                // beri satu fieldset kosong

                // addFieldsetIdentitas(form);
            
            });
    };

   var addFieldsetIdentitas = function(form, fieldset)
    {

        var 
                $section           = form.section,
                $fieldsetContainer = $('ul', $section),
                counter            = form.counter(),
                $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer);
        // alert('1');
        if (fieldset == "addFieldset") {
                

            

            $.ajax({
                    type        : 'POST',
                    url         : baseAppUrl + 'show_identitas',
                    data        : {identitas_id: $('select#identitas').val(), item_id : $('input#item_id').val(), item_satuan_id : $('input#item_satuan_id').val(), i : $('input#i').val()},
                    dataType    : 'text',
                    success     : function( results ) {
                        // $kelas_select.val('Pilih Kelas');
                        $('div.show_identitas', $newFieldset).html(results);
                        // $('div#form_tambahan').append(results);
                        // alert(results);
                    }
                });
            //jelasin warna hr pemisah antar fieldset
            $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

            $('input#identitas_tambah').val($('select#identitas').val());
            $('a#tambah_fieldset',form.section).addClass('hidden');

        }
        // else if (fieldset == "addIdentitas") {
                

            

        //     $.ajax({
        //             type        : 'POST',
        //             url         : baseAppUrl + 'show_identitas',
        //             data        : {identitas_id: $('select#identitas').val(), item_id : $('input#item_id').val(), item_satuan_id : $('input#item_satuan_id').val(), i : $('input#i').val()},
        //             dataType    : 'text',
        //             success     : function( results ) {
        //                 // $kelas_select.val('Pilih Kelas');
        //                 $('div.show_identitas').append(results);
        //                 $('div#form_tambahan').append(results);
        //                 // alert(results);
        //             }
        //         });

            
        //     //jelasin warna hr pemisah antar fieldset
        // };

        // $('a#tambah_fieldset',form.section).addClass('hidden');

        

    };
    var handleBtnDelete = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableItemDigunakan);

        $btn.on('click', function(e){
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
                // if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableItemDigunakan).length == 0){
                        addItemRow();
                    }
                    // focusLastItemCode();
                // }
            // });
            e.preventDefault();
        });
    };

    var handleBtnSearchResep = function($btn){
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

            if ($lastPopoverResep != null) $lastPopoverResep.popover('hide');

            $lastPopoverResep = $btn;

            $popoverResepContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            
            // pindahkan $popoverResepContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverResepContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverResepContent ke .page-content
            $popoverResepContent.hide();
            $popoverResepContent.appendTo($('.page-content'));

            $lastPopoverResep = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    

    var handleDataTableResep = function(){
        $tableResepSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_resep/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'name' : 'resep_racik_obat.nama','visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },               ]
        });

        var $btnSelects = $('a.select', $tableResepSearch);
        handleResepSearchSelect( $btnSelects );

        $tableResepSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handleResepSearchSelect( $btnSelect );
            
        } );

        $popoverResepContent.hide();        
    };

    var handleResepSearchSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $racikan             = null,
                $pembuat             = null,
                $keterangan          = null,
                $resep_racik_obat_id = null;        


           
                $racikan             = $('input#racikan');
                $pembuat             = $('label#pembuat');
                $keterangan          = $('label#keterangan');
                $resep_racik_obat_id = $('input#resep_racik_obat_id');


                $('.search-resep').popover('hide');
              // console.log($itemIdEl)
            
                $racikan.val($(this).data('resep').nama);
                $keterangan.text($(this).data('resep').keterangan);
                $resep_racik_obat_id.val($(this).data('resep').id);

                $.ajax
                ({ 

                        type: 'POST',
                        url: baseAppUrl +  "get_user",  
                        data:  {user_id : $(this).data('resep').user_id},  
                        dataType : 'json',
                        success:function(results)          //on recieve of reply
                        { 
                            $pembuat.text(results.nama);                        
                        }
           
                });

                $tableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_digunakan/' + $(this).data('resep').id + '/1').load();
                
                
        });     
    };

    var handleDataTableItemDigunakan = function(){
        $tableItemDigunakan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_digunakan/0/1',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'name' : 'item.kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'resep_racik_obat_detail.jumlah','visible' : true, 'searchable': false, 'orderable': true },               
                { 'name' : 'item.satuan','visible' : true, 'searchable': false, 'orderable': true },               
                { 'name' : 'item.harga','visible' : true, 'searchable': false, 'orderable': true },               
                ]
        });

        var $btnSelects = $('a.select', $tableItemDigunakan);
        handleResepSearchSelect( $btnSelects );

        $tableItemDigunakan.on('draw.dt', function (){
            var $btnSelect = $('a.select', this),
                $selectIdentitas = $('select#identitas');
            handleResepSearchSelect( $btnSelect );

            $('a[name="identitas[]"]', this).click(function(){
                    var $anchor = $(this),
                          item_id    = $anchor.data('item').item_id,
                          item_satuan_id    = $anchor.data('item').item_satuan_id;

                    $('input#item_id').val(item_id);
                    $('input#item_satuan_id').val(item_satuan_id);
                    // alert(item_id);
                    // $('div.show_identitas').html($('div#form_tambahan').html());

                    $.ajax({
                        type        : 'POST',
                        url         : baseAppUrl + 'get_identitas',
                        data        : {item_id: item_id},
                        dataType    : 'json',
                        success     : function( results ) {
                            // $kelas_select.val('Pilih Kelas');
                            $selectIdentitas.empty();

                            //     //munculin index pertama Pilih..
                            $selectIdentitas.append($("<option></option>")
                                    .attr("value", "").text("Pilih.."));
                                $selectIdentitas.val('');

                            // //munculin semua data dari hasil post
                            $.each(results, function(key, value) {
                                $selectIdentitas.append($("<option></option>")
                                    .attr("value", value.identitas_id).text(value.judul));
                                $selectIdentitas.val();

                            });
                            // alert(results);
                        }
                    });
            });

            var sub_total = parseInt($('input#sub_total').val());

            if (!isNaN(sub_total)) {
                $('label#sub_total').text(mb.formatRp(sub_total));
                $('label#harga_jual').text(mb.formatRp(sub_total));
                $('input#harga_jual').val(sub_total);
            };
            
           // $('input#a').val('a');

           
        });

        $popoverResepContent.hide();        
    };

    var handleConfirmSave = function(){
        var numRow = $('tbody tr', $tableItemDigunakan).length;
        $('a#confirm_save', $form).click(function(e) {
            
            if (! $form.valid() && numRow > 0) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                     $('#save',$form).click();  
                }
            });
            e.preventDefault();
        });
    };

    var handleConfirmModalOK = function(){
        
        $('a#modal_ok').click(function(e) {

            //alert("test");
            //$('input#identitas_1').value = $('input#identitas_1').val();
            $classrow   = $('div.show_identitas'),
            IdentitasIdAll = $('input.sendData', $classrow),
            found = false;
            $.each(IdentitasIdAll, function(idx, value){
            
            $(this).attr('value', $(this).val());

                
            });
            
            $.each($classrow, function(idx, value){
                divIdx = idx+1;
                $('div#form_tambahan').append($('div#show_identitas_'+divIdx).html());
            });

            $('hr').insertAfter('.jumlah');
            // $('div#form_tambahan').append($('div#show_identitas_2').html());
            $('div.show_identitas').empty();
            $('a#tambah_identitas').addClass('hidden');
            $('a#tambah_fieldset').removeClass('hidden');
            $('hr').remove();
            $('#modal_close').click();  
            
            //e.preventDefault();
        });
    };

    var handleConfirmModalCancel = function(){
        
        $('a#modal_cancel').click(function(e) {


            $('hr').insertAfter('.jumlah');
            // $('div#form_tambahan').append($('div#show_identitas_2').html());
            $('div.show_identitas').empty();
            $('a#tambah_identitas').addClass('hidden');
            $('a#tambah_fieldset').removeClass('hidden');
            $('hr').remove();
            $('#modal_close').click();  
            
            //e.preventDefault();
        });
    };

    var handleBiayaTambahan = function(){
        $('input#biaya_tambahan').on('keyup', function () {
           var sub_total = parseInt($('input#sub_total').val()),
               biaya_tambahan = parseInt($(this).val());

            if (!isNaN(sub_total) && !isNaN(biaya_tambahan)) {
                $('label#harga_jual').text(mb.formatRp(sub_total + biaya_tambahan));
                $('input#harga_jual').val(sub_total + biaya_tambahan);
            };
        });
    };

    var handleJumlahProduksi = function(){
        $('input#jumlah_produksi').on('keyup', function () {
           var resep_racik_obat_id = $('input#resep_racik_obat_id').val(),
               jumlah_produksi = parseInt($(this).val());

            if (!isNaN(jumlah_produksi)) {
                $tableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_digunakan/' + resep_racik_obat_id + '/' + jumlah_produksi).load();
            }else{
                $tableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_digunakan/' + resep_racik_obat_id + '/1').load();
            }
        });
    };

    var handleIdentitas = function(){
        $('select#identitas').on('change', function () {
           $.ajax({
                type        : 'POST',
                url         : baseAppUrl + 'show_identitas',
                data        : {identitas_id: $(this).val()},
                dataType    : 'text',
                success     : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#show_identitas").html(results);
                    // alert(results);
                }
            });
        });
    };


    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
                autoclose: true,
            })
        }
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/racik_obat/';
        handleValidation();
        handleDataTableResep();
        handleDataTableItemDigunakan();
        initForm(); 
        handleConfirmSave();
        handleBiayaTambahan();
        handleJumlahProduksi();
        handleDatePickers();
        handleConfirmModalOK();
        handleConfirmModalCancel();
        // handleIdentitas();
        // alert('a');
    };

}(mb.app.resep_obat.add));


// initialize  mb.app.resep_obat.add
$(function(){
    mb.app.resep_obat.add.init();
});