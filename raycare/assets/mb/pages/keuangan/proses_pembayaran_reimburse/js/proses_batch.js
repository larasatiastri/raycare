mb.app.proses_batch = mb.app.proses_batch || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form = $('form#form_proses_batch')
        $table1 = $('#table_proses_batch'),
        $tableBankPegawai = $('#table_pilih_bank'),
        $popoverBankContent  = $('#popover_bank_pegawai_content'), 
        $lastPopoverSuppBank = null,
        tplFormPayment       = '<li class="fieldset">' + $('#tpl-form-payment', $form).val() + '<hr></li>',
        regExpTpl            = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        paymentCounter       = 0;


    var forms = {
        'payment' : {            
            section  : $('#section-payment', $form),
            template : $.validator.format( tplFormPayment.replace(regExpTpl, '_ID_{0}') ), //ubah ke format template jquery validator
            counter  : function(){ paymentCounter++; return paymentCounter-1; }
        }      
    };
        


    var initForm = function(){

        $('select#user_id').select2();
        handleDataTableInvoice();

        $.each(forms, function(idx, form){

            // beri satu fieldset kosong
            addFieldset(form);

        });

        handleDataTableBankPegawai();
        handleConfirmSave();
    }

    var handleDataTableInvoice = function() 
    {
        $('select#user_id').on('change', function(){
            var user_id = $(this).val();

            $.ajax
            ({
                type: 'POST',
                url: baseAppUrl +  "get_data_invoice",  
                data:  {user_id:user_id},  
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(data)          //on recieve of reply
                {   
    
                    var xhtml = '';  

                    if(data.success == true){
                        $.each(data.data, function(idx, dt){
                           xhtml += '<tr>';
                           xhtml += '<td>'+dt.tgl_bon+'</td>';
                           xhtml += '<td><input type="hidden" class="form-control" name="input['+idx+'][permintaan_biaya_bon_id]" id="input_permintaan_biaya_bon_id_'+idx+'" value="'+dt.id+'">'+dt.nama_biaya+'</td>';
                           xhtml += '<td><a class="fancybox-button" title="'+dt.url+'" href="'+mb.baseDir()+'cloud/raycare/pages/keuangan/permintaan_biaya/images/'+dt.permintaan_biaya_id+'/'+dt.url+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/raycare/pages/keuangan/permintaan_biaya/images/'+dt.permintaan_biaya_id+'/'+dt.url+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a></td>';
                           xhtml += '<td>'+dt.no_bon+'</td>';
                           xhtml += '<td><input type="hidden" class="form-control" name="input['+idx+'][nominal_bon]" id="input_nominal_bon_'+idx+'" value="'+dt.total_bon+'">'+dt.total_bon+'</td>';
                           xhtml += '<td>'+dt.keterangan+'</td>';
                           xhtml += '<td><input type="checkbox" data-permintaan_biaya_id="'+dt.permintaan_biaya_id+'" data-nominal="'+dt.total_bon+'" class="form-control" name="input['+idx+'][pilih]" id="input_pilih_'+idx+'" value="'+dt.permintaan_biaya_id+'"></td>';
                           xhtml += '</tr>';
                             
                        }); 

                        $('table#table_proses_batch tbody').html(xhtml);
                        handleFancybox();
                        $('input[name$="[pilih]"]', $('table#table_proses_batch')).on('change', function(){
                            handleCheckbox();       
                        });

                    }else{
                        xhtml += '<tr>';
                        xhtml += '<td colspan="7" class="text-center">There are no data in table</td>';
                        xhtml += '</tr>';

                        $('table#table_proses_batch tbody').html(xhtml);
                    }
                
                },
                complete : function(){
                  Metronic.unblockUI();
                }
            });  

            oTableBankPegawai.api().ajax.url(baseAppUrl +  'listing_pegawai_bank/' + $('select#user_id').val()).load();

        });
        
    }

    function handleFancybox() {
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

    var handleCheckbox = function(){

        var arrayPermintaanId = [],
            arrayNominal = [],
            total_bon = 0;

        $.each($('input[name$="[pilih]"]:checked'), function(idx){
            arrayPermintaanId.push($(this).data('permintaan_biaya_id'));
            arrayNominal.push($(this).data('nominal'));

            total_bon = parseInt(total_bon + $(this).data('nominal'));
        });

        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "get_terbilang",  
            data:  {nominal:total_bon},  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            {   
                $('input#nominal').val(total_bon);
                $('input#nominal').attr("value" , total_bon);
                $('label#th_terbilang').html("<b>"+data.terbilang+"</b>");
            },
            complete : function(){
              Metronic.unblockUI();
            }
        }); 
    }

    var addFieldset = function(form){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer);

        handleDatePickers();
        $('select.payment_type').val(1);
        
        handleSelectSection(1, $newFieldset);

        $('select[name="payment_type"]', $newFieldset).on('change', function(){
            handleSelectSection(this.value, $newFieldset);

            var $btnBankPegawai = $('a.search-bank', $newFieldset);


            $.each($btnBankPegawai, function(idx, btnBankPegawai){
                var rowId  = $(this).closest('tr').prop('id');

                $(this).popover({ 
                    html : true,
                    container : '.page-content',
                    placement : 'bottom',
                    content: '<input type="hidden" name="rowItemId"/>'

                }).on("show.bs.popover", function(){

                    var $popContainer = $(this).data('bs.popover').tip();

                    $popContainer.css({minWidth: '1150px', maxWidth: '1150px'});

                    if ($lastPopoverSuppBank != null) $lastPopoverSuppBank.popover('hide');

                    $lastPopoverSuppBank = $(this);

                    $popoverBankContent.show();

                }).on('shown.bs.popover', function(){

                    var 
                        $popContainer = $(this).data('bs.popover').tip(),
                        $popcontent   = $popContainer.find('.popover-content')
                        ;

                    // record rowId di popcontent
                    $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
                    
                    // pindahkan $popoverBankContent ke .popover-conter
                    $popContainer.find('.popover-content').append($popoverBankContent);

                }).on('hide.bs.popover', function(){
                    //pindahkan kembali $popoverPasienContent ke .page-content
                    $popoverBankContent.hide();
                    $popoverBankContent.appendTo($('.page-content'));

                    $lastPopoverSuppBank = null;

                }).on('hidden.bs.popover', function(){
                    // console.log('hidden.bs.popover')
                }).on('click', function(e){
                    e.preventDefault();

                });
            });  
        });

        
        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'silver');
        $('input#last_count').val(counter);

    };

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "right",
                autoclose: true,
                format : 'dd M yyyy'
            });

            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "right",
                autoclose: true,
                format : 'dd M yyyy'
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleSelectSection = function(value,$fieldset)
    {
        if(value == 1)
        {
            $('div#section_1', $fieldset).removeClass('hidden');
            $('div#section_2', $fieldset).addClass('hidden');
            $('div#section_3', $fieldset).addClass('hidden');
        }
        if(value == 2)
        {
            $('div#section_1', $fieldset).addClass('hidden');
            $('div#section_2', $fieldset).removeClass('hidden');
            $('div#section_3', $fieldset).addClass('hidden');
        }
        if(value == 3)
        {
            $('div#section_3', $fieldset).removeClass('hidden');
            $('div#section_2', $fieldset).addClass('hidden');
            $('div#section_1', $fieldset).addClass('hidden');
        }
    }

    var handleDataTableBankPegawai = function()
    {
        oTableBankPegawai = $tableBankPegawai.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pegawai_bank/'+ $('select#user_id').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'name' : 'pegawai_bank.nob nob', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pegawai_bank.acc_name acc_name','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pegawai_bank.acc_number acc_number', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pegawai_bank.acc_number acc_name', 'visible' : true, 'searchable': false, 'orderable': false },               
                ]
        });


        $tableBankPegawai.on('draw.dt', function (){

            var $btnSelect = $('a.select-pegawai-bank', this);
            handlePegawaiBankSelect( $btnSelect );
            
        });

        $popoverBankContent.hide();        
    };

    var handlePegawaiBankSelect = function($btn){
        $btn.on('click', function(e){
            var 

                $bankPegawaiId    = $('input[name="bank_pegawai_id"]', $form);
                $bankPegawaiName  = $('input[name="bank_pegawai_name"]', $form);
                $bankPegawaiNomor = $('input[name="bank_pegawai_nomor"]', $form);


                $bankPegawaiId.val($(this).data('item').id);
                $bankPegawaiName.val($(this).data('item').nob);
                $bankPegawaiNomor.val($(this).data('item').acc_number);
                $('a.search-bank', $form).popover('hide');
        });  
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;

            var i = 0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses...'});
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                      $('#save', $form).click();
                    }
                }
            });
        });
    };
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/proses_pembayaran_reimburse/';
        initForm();
  

    };
 }(mb.app.proses_batch));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses_batch.init();
});