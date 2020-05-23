mb.app.transaksi_perawat = mb.app.transaksi_perawat || {};
mb.app.transaksi_perawat.add = mb.app.transaksi_perawat.add || {};
(function(o){

    var 
         baseAppUrl          = '',
         $popoverItemContent = $('div#popover_item_content'),
         $lastPopoverItem    = null,
        $popoverItemContentTransfusi = $('#popover_item_content_transfusi'), 
         $form               = $('#form_observasi_dialisis'),
         $tableOrderItem          = $('#table_order_item22', $form),
        $tableOrderItem2         = $('#table_order_item23', $form),
         $tableOrderItem3         = $('#table_paket', $form),
        $tableOrderItemTransfusi         = $('#table_order_item_transfusi'),
         $tableHistoryResep         = $('#table_history_resep'),
         $formAssesment      = $('#form_assesment'),
         $formSupervising    = $('#form_supervising'),
         $formExamination    = $('#form_examination'),
         $tableMonitoring    = $('#table_monitoring'),
         $tablePaket         = $('#table_paket'),
         itemCounter3        = 1,
         itemCounter              = 1,
        itemCounter2             = 1,
        itemCounter4             = 1,
        itemCounter5             = 1,
        itemCounterResep         = 1,
         tplItemRow               = $.validator.format( $('#tpl_item_row').text() ),
        tplItemRow2              = $.validator.format( $('#tpl_item_row2').text() ),
        tplItemRow4              = $.validator.format( $('#tpl_item_row776').text() ),
        tplItemRow5              = $.validator.format( $('#tpl_item_row211').text() ),
         tplItemRow3         = $.validator.format( $('#tpl_item_row3').text() ),
        tplItemRowResep          = $.validator.format( $('#tpl_item_row_resep').text() ),
         last_index          = $('input#last_index').val(),
         $tableItemTelahDigunakan = $('#table_item_telah_digunakan')

    ;

    var addItemRowTempResep = function(){
         var numRow = $('tbody tr', $tableOrderItemTransfusi).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableOrderItemTransfusi),
            $newItemRow    = $(tplItemRowResep(itemCounterResep++)).appendTo( $rowContainer ),
            $btnSearchItem     = $('.search-item', $newItemRow);
            $btnDelete     = $('.del-this', $newItemRow);
  
        handleBtnSearchItem($btnSearchItem);
        
        handleBtnDeleteItemTransfusi($btnDelete);
         
        
    };

    var handleObatTransfusi = function(){

      oTableobatTransfusi=$("#table_obat_transfusi").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_obat_transfusi',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'name' : 'item.kode kode', 'searchable': true, 'orderable': true },
                { 'visible' : true, 'name' : 'item.nama nama', 'searchable': true, 'orderable': true },
                { 'visible' : true, 'name' : 'item.keterangan keterangan', 'searchable': true, 'orderable': true },
                { 'visible' : true, 'name' : 'item.kode kode', 'searchable': false, 'orderable': false },
                ],
             
        });
        $("#table_obat_transfusi").on('draw.dt', function (){
              handleTableItemSearchSelectBtnResep($("#table_obat_transfusi"),'obat');
         } );
     
    }; 

    var handleTableItemSearchSelectBtnResep = function(table,tipe){
            // console.log($btn.length);
        var $btnsSelect = $('a.select', table);

        $.each($btnsSelect, function(idx, btn){
          $(btn).on('click', function(e){
            var 
              $rowClass    = $('.table_item_transfusi', $tableOrderItemTransfusi),
              ItemIdAll    = $('input[name$="[tindakan_id]"]', $rowClass),
              itemId       = $(this).data('item').id;     

              found = false;
              $.each(ItemIdAll,function(idx, value){
                  // alert(itemId);
                  if(itemId == this.value)
                  {
                      found = true;
                  }
              });
                
              if(found == false)
              {
                $('input[name$="[tindakan_id]"]', $tableOrderItemTransfusi).last().val($(this).data('item').id);
                $('input[name$="[tipe_obat]"]', $tableOrderItemTransfusi).last().val('obat');
                if(tipe=='obat'){
                    $('label[name$="[code]"]', $tableOrderItemTransfusi).last().text($(this).data('item').kode);
                }
                $('label[name$="[nama]"]', $tableOrderItemTransfusi).last().text($(this).data('item').nama);
                $('label[name$="[tipe]"]', $tableOrderItemTransfusi).last().text(tipe),
                $('input[name$="[satuan]"]', $tableOrderItemTransfusi).last().val($(this).data('item').id_satuan),
                $('span.satuan').last().text($(this).data('item').nama_satuan),
                
                    addItemRowTempResep();
                  
                $('input[name$="[tindakan_id2]"]', $tableOrderItemTransfusi).last().val('');

                }
                $('#tambahrowresep').popover('hide');
              
                 e.preventDefault();   
            });     
        });
        
    };



    var addItemRow3 = function(){
        var numRow = $('tbody tr', $tablePaket).length;
 
        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tablePaket),
            $newItemRow    = $(tplItemRow3(itemCounter3++)).appendTo( $rowContainer ),
            $btnDelete     = $('.del-this3', $newItemRow);
  
      //  handleBtnSearchItem($btnSearchItem);
       
       handleBtnDeleteItem3($btnDelete);
       
       // handleCheck($checkMultiply);
        
    };

    var handleBtnDeleteItem3 = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tablePaket);

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tablePaket).length == 0){
                        addItemRow3();
                    }
            //     }
            // });

            e.preventDefault();
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

     var initForm = function(){

        var $btnSearchItem        = $('.search-item');
        handleBtnSearchItem($btnSearchItem);  
        
        $popoverItemContent.hide();

        $('.modal_batal').on('click', function(){
            $('.search-item').popover('hide');          
        });

        $('.modal_ok').on('click', function(){
            $('.search-item').popover('hide');          
        });

        $('a#tambahrow2').on('click', function(){

            addItemRow2();
        });

        addItemRow3();
 
        $.ajax
        ({ 

                type: 'POST',
                url: baseAppUrl +  "getsejarah2",  
                data:  {id:$("#tindakan_hd_id").val()},  
                dataType : 'json',
                success:function(data)          //on recieve of reply
                { 

           
                    handleisidata2(data);
                  
                
                }
   
       });

        $('li#list_hasil_lab').click(function(){
            handleGetLab();
        });

        $('a#btn_lab_prev').click(function(){
            handleGetLabPrevNext('prev');
        });

        $('a#btn_lab_next').click(function(){
            handleGetLabPrevNext('next');
        });

        addItemRowTempResep();


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

            $popContainer.css({minWidth: '720px', maxWidth: '720px', zIndex: '99999'});

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
    }



    var handleConfirmSave = function()
    {
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


    var handleisidata = function(data){
    for(z=1;z<=6;z++)
    {
        $("#prob" + z).prop('checked',false);
        if(z==1){
            $("#probname" + z).html("Airway Clearance, ineffective");
        }else if(z==2){
            $("#probname" + z).html("Fluid balance");
        }else if(z==3){
            $("#probname" + z).html("High risk of infection");
        }else if(z==4){
            $("#probname" + z).html("Impaired sense of comfort pain");
        }else if(z==5){
            $("#probname" + z).html("Disequilibrium Syndrome");
        }else if(z==6){
            $("#probname" + z).html("Shock risk");
        }
    }

    for(b=1;b<=9;b++)
    {
        $("#comp" + b).prop('checked',false);
        if(b==1){
            $("#compname" + b).html("Bleeding");
        }else if(b==2){
            $("#compname" + b).html("Pruritus");
        }else if(b==3){
            $("#compname" + b).html("Dialyzer Alergic");
        }else if(b==4){
            $("#compname" + b).html("Headache");
        }else if(b==5){
            $("#compname" + b).html("Nausea");
        }else if(b==6){
            $("#compname" + b).html("Chest Pain");
        }else if(b==7){
            $("#compname" + b).html("Hypotension");
        }else if(b==8){
            $("#compname" + b).html("Shiver");
        }else if(b==9){
            $("#compname" + b).html("Etc");
        }
    }
    $("#medicine2").prop("checked", false);
    $('#food2').prop('checked', false);
    $("#regular").prop("checked", false);
    $("#minimal").prop("checked", false);
    $("#free").prop("checked", false);
    $("#new").prop("checked", false);
    $("#reuse").prop("checked", false);
    $("#av_shunt").prop("checked", false);
    $("#femoral").prop("checked", false);
    $("#double_lument").prop("checked", false);
    $("#bicarbonate").prop("checked", false);

    var x=0;
    $.each(data.problem, function(key, value) {
      x++;
      
      if((value.problem_id==$("#prob" + x).val()) && (value.nilai==1))
      {
        $("#prob" + x).prop('checked',true);  
      } 
    });

    var o=0;
    $.each(data.komplikasi, function(key, value) {
      o++;
    
      if((value.komplikasi_id==$("#comp" + o).val()) && (value.nilai==1))
      {
        $("#comp" + o).prop('checked',true); 
      } 
    });

    $("#datesejarah").val(data.assesment['date_value']);
    $("#time").val(data.assesment['time_value']);
    
    if(data.assesment['alergic_medicine']==1)
    {
       
      $("#medicine2").prop("checked", true);
    }
    if(data.assesment['alergic_food']==1)
    {
      $('#food2').prop('checked', true);
    }
     
    $("#assessment_cgs").val(data.assesment['assessment_cgs_value']);
    $("#medical_diagnose").val(data.assesment['medical_diagnose_value']);
    $("#time_of_dialysis").val(data.assesment['time_of_dialysis_value']);
    $("#quick_of_blood").val(data.assesment['quick_of_blood_value']);
    $("#quick_of_dialysate").val(data.assesment['quick_of_dialysis_value']);
    $("#uf_goal").val(data.assesment['uf_goal_value']);

    if(data.assesment['heparin_reguler_value'] == 1)
    {
         $("#regular").prop("checked", true);
    }
    if(data.assesment['heparin_minimal_value'] == 1)
    {
        $("#minimal").prop("checked", true);
    }
    if(data.assesment['heparin_free_value'] == 1)
    {
         $("#free").prop("checked", true);
    }

    if(data.assesment['dialyzer_new_value'] == 1)
    {
         $("#new").prop("checked", true);
    }
    if(data.assesment['dialyzer_reuse_value'] == 1)
    {
         $("#reuse").prop("checked", true);
    }

     if(data.assesment['ba_avshunt_value'] == 1)
    {
         $("#av_shunt").prop("checked", true);
    }
     if(data.assesment['ba_femoral_value'] == 1)
    {
         $("#femoral").prop("checked", true);
    }
     if(data.assesment['ba_catheter_value'] == 1)
    {
         $("#double_lument").prop("checked", true);
    }

    if(data.assesment['dialyzer_type_value'] == 1)
    {
         $("#bicarbonate").prop("checked", true);
    }
    $("#dose").val(data.assesment['dose_value']);
    $("#first").val(data.assesment['first_value']);
    $("#maintenance").val(data.assesment['maintenance_value']);
    $("#hour").val(data.assesment['hours_value']);
    $("#machine_no").val(data.assesment['machine_no_value']);
    $("#dialyzer").val(data.assesment['dialyzer_value']);
    $("#remaining_").val(data.assesment['remaining_of_priming_value']);
    $("#washout_").val(data.assesment['wash_out_value']);
    $("#drip_of_fluid_").val(data.assesment['drip_of_fluid_value']);
    $("#blood_").val(data.assesment['blood_value']);
    $("#drink_").val(data.assesment['drink_value']);
    $("#vomitting_").val(data.assesment['vomiting_value']);
    $("#urinate_").val(data.assesment['urinate_value']);
    $("#type_").val(data.assesment['transfusion_type_value']);
    $("#quantity_").val(data.assesment['transfusion_qty_value']);
    $("#blood_type_").val(data.assesment['transfusion_blood_type_value']);
    $("#serial_number_").val(data.assesment['serial_number_value']);
    $("#laboratory_").val(data.assesment['laboratory_value']);
    $("#ecg_").val(data.assesment['ecg_value']);
    $("#priming_").val(data.assesment['priming_value']);
    $("#initiation_").val(data.assesment['initiation_value']);
    $("#termination_").val(data.assesment['termination_value']);

                                    
}

var handleisidata2 = function(data)
    {
        for(z=1;z<=6;z++)
        {
            $("#prob1" + z).prop('checked',false);
            if(z==1){
                $("#probname1" + z).html("Airway Clearance, ineffective");
            }else if(z==2){
                $("#probname1" + z).html("Fluid balance");
            }else if(z==3){
                $("#probname1" + z).html("High risk of infection");
            }else if(z==4){
                $("#probname1" + z).html("Impaired sense of comfort pain");
            }else if(z==5){
                $("#probname1" + z).html("Disequilibrium Syndrome");
            }else if(z==6){
                $("#probname1" + z).html("Shock risk");
            }
        }

        for(b=1;b<=9;b++){
            $("#comp1" + b).prop('checked',false);
            if(b==1){
                $("#compname1" + b).html("Bleeding");
            }else if(b==2){
                $("#compname1" + b).html("Pruritus");
            }else if(b==3){
                $("#compname1" + b).html("Dialyzer Alergic");
            }else if(b==4){
                $("#compname1" + b).html("Headache");
            }else if(b==5){
                $("#compname1" + b).html("Nausea");
            }else if(b==6){
                $("#compname1" + b).html("Chest Pain");
            }else if(b==7){
                $("#compname1" + b).html("Hypotension");
            }else if(b==8){
                $("#compname1" + b).html("Shiver");
            }else if(b==9){
                $("#compname1" + b).html("Etc");
            }
        }

       

        var x=0;
        var o=0;
        $.ajax ({ 

            type: 'POST',
            url: baseAppUrl +  "getproblem",  
            data:  {tindakan_id:data.tindakan_hd_id,pasien_id:$("#pasienid").val()},  
            dataType : 'json',
            success:function(data)          //on recieve of reply
            { 
                $.each(data, function(key, value) {
                    x++;
                   
                    if((value.problem_id==$("#prob1" + x).val()) && (value.nilai==1))
                    {
                       $("#prob1" + x).prop('checked',true);
                    } 
                });
            }
        });

        $.ajax ({ 

            type: 'POST',
            url: baseAppUrl +  "getkomplikasi",  
            data:  {tindakan_id:data.tindakan_hd_id,pasien_id:$("#pasienid").val()},  
            dataType : 'json',
            success:function(data)          //on recieve of reply
            { 
                $.each(data, function(key, value) {
                     o++;
                    
                     if((value.komplikasi_id==$("#comp1" + o).val()) && (value.nilai==1))
                     {
                        $("#comp1" + o).prop('checked',true);
                     } 
                });
            }
        });
 
                                    
    }

    var handleSejarahTransaksi = function(){
        // alert($("#pasienid").val());
  
        oTableSejarahTransaksi=$("#table_sejarah_transaksi").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_sejarah_transaksi' + '/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 
            ],
        });

        $("#table_sejarah_transaksi").on('draw.dt', function (){
              
            $('a[name="viewsejarah[]"]', this).click(function(){
                $("#flag2").val(1);
                  var $anchor = $(this),
                  id    = $anchor.data('id')
                  tipe    = $anchor.data('tipe'),
                  cabang_id    = $anchor.data('cabang_id'),
                  index = $anchor.data('index');

                  $('#li2').attr('class', 'active');
                  $('#li1').removeAttr('class'); 
                  
                  // $('#page').removeAttr('class');
                   $('#page').attr('class','tab-pane active');
                   $('#tabel').attr('class','tab-pane');
                        
                    $.ajax
                    ({
                        type: 'POST',
                        url: baseAppUrl +  "getsejarah",  
                        data:  {tindakan_id:id,pasien_id:$("#pasienid").val(),tipe:tipe,cabang_id:cabang_id,flag:1 },  
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success:function(data)          //on recieve of reply
                        { 
                            
                          $("#tgglpage").html(data.assesment['date_value']+' ['+data.nama_cabang+']'); 
                          handleisidata(data);
                        
                        },
                        complete : function(){
                          Metronic.unblockUI();
                        }
                    });

                        $("#tipe_trans").val(tipe);   
                        $("#cabang_id").val(cabang_id);   
                        $("#tindakanhdid").val(id);
                        $("#curr_index").val(index);
                        // oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + id).load();
                        // oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + id).load();
                        // oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_digunakan/' + id).load();
                        // oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + id).load(); 
              });         
        });
    };

    var handleSejarahItem = function(){
  
        oTableSejarahItem=$("#table_sejarah_item").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_sejarah_item' + '/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : false, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': false, 'orderable': false },
                 { 'visible' : false, 'searchable': false, 'orderable': false },
            ],
        });

        $("#table_sejarah_item").on('draw.dt', function (){
              
            $('a[name="viewitem[]"]', this).click(function()
            {
                $("#flag2").val(1);
                var $anchor = $(this),
                    id      = $anchor.data('id');
                    tanggal = $anchor.data('tggl');

                $('#li2').attr('class', 'active');
                $('#li1').removeAttr('class'); 
                
                // $('#page').removeAttr('class');
                $('#page').attr('class','tab-pane active');
                $('#tabel').attr('class','tab-pane');
                

                $.ajax
                ({ 
                    type: 'POST',
                    url: baseAppUrl +  "getsejarah",  
                    data:  {tindakan_id:id,pasien_id:$("#pasienid").val(),tanggal:tanggal,flag:1 },  
                    dataType : 'json',
                    success:function(data)          //on recieve of reply
                    { 
                        $("#tgglpage").html(data.date_value); 
                        $("#tgglpage2").val(data.date_value);   
                        handleisidata(data);
                    }
           
                });

                $("#tindakanhdid").val(id);
                oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + id).load();
                oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + id).load();
                oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_digunakan/' + id).load();
                oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + id).load(); 

            });  
        });
    };

    var handleObservasi = function(){
  
        oTableObservasi = $("#table_dialysis").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_observasi',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': true, 'orderable': true },
            ],
             
        });

        $("#table_dialysis").on('draw.dt', function (){
              
              $('a[name="deleted[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        
                        $.ajax
                        ({ 
                            type: 'POST',
                            url: baseAppUrl +  "deleteajax2",  
                            data:  {id:id },  
                            dataType : 'json',
                            success:function(data)          //on recieve of reply
                            { 
                                oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' +  $("#tindakanhdid").val()).load();
                            
                            }
                       });
                         
              });  

              $('a[name="restore1[]"]', this).click(function(){

                 var $anchor = $(this),
                        id    = $anchor.data('id');
                        
                        $.ajax
                        ({ 
                            type: 'POST',
                            url: baseAppUrl +  "restoreajax",  
                            data:  {id:id },  
                            dataType : 'json',
                            success:function(data)          //on recieve of reply
                            { 
                                oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + $("#tindakanhdid").val()).load();
                            
                            }
                       });
                         
              });  

              $('a[name="viewobservasi[]"]', this).click(function(){

                    var $anchor = $(this),
                       id    = $anchor.data('id');
                         
                        $("#asses2").hide();
                        $("#asses1").show();
                        $("#observasiid").val(id);
                        $.ajax
                        ({ 
         
                            type: 'POST',
                            url: baseAppUrl +  "editdataobservasi",  
                            data:  {id:id},  
                            dataType : 'json',
                            success:function(data)          //on recieve of reply
                            { 
                                  //  alert(data.waktu_pencatatan_value);
                                $("#jam").val(data.waktu_pencatatan_value);
                                $("#tda3").val(data.tda_value);
                                $("#tdb3").val(data.tdb_value);
                                $("#ufg").val(data.ufg_value);
                                $("#ufr").val(data.ufr_value)
                                $("#ufv").val(data.ufv_value);
                                $("#qb").val(data.qb_value);
                                $("#nurse").val($("#username1").val());
                                $("#keterangan").val(data.keterangan_value);
                                 
                            }
                   
                       });

                    oTableObservasi2.api().ajax.url(baseAppUrl + 'listing_observasi2/'  + $("#tindakanhdid").val()).load();
                         
              });  
       
          });
         
     
    };

    var handleObservasi2 = function()
    {
        oTableObservasi2 = $("#table_dialysis2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_observasi2/' + $("#tindakanhdid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': false, 'orderable': false },

            ],
        });
    };

    var handleSaveEditObservasi = function()
    {
        $('a#confirmsave').click(function() {

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
 
                    $.ajax
                    ({ 

                        type: 'POST',
                        url: baseAppUrl +  "updateobservasi",  
                        data:  {jam:$("#jam").val(),tda:$("#tda3").val(),tdb:$("#tdb3").val(),ufg:$("#ufg").val(),ufr:$("#ufr").val(),ufv:$("#ufv").val(),qb:$("#qb").val(),userid:$("#userid1").val(),keterangan:$("#keterangan").val(),id_observasi:$("#observasiid").val()},  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                            mb.showMessage(data[0],data[1],data[2]);

                            $("#asses2").show();
                            $("#asses1").hide();

                            oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + $("#tindakanhdid").val()).load();
                        }
               
                   });
                }
            });
        });
    };

    var handleItemTersimpan = function(){
  
        oTableItemTersimpan = $("#table_item2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_tersimpan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                    { 'visible' : true, 'searchable': true, 'orderable': true },
                    { 'visible' : true, 'searchable': false, 'orderable': false },
            ],
             
        });

        $("#table_item2").on('draw.dt', function (){
              
            $('a[name="pakai[]"]', this).click(function(){

                var $anchor = $(this),
                    id      = $anchor.data('id');
                    msg     = $anchor.data('confirm');
                         
                bootbox.confirm(msg, function(result) 
                {
                    if (result==true) {
                           
                        $.ajax
                        ({ 
 
                            type: 'POST',
                            url: baseAppUrl +  "updatestatus/1",  
                            data:  {id:id },  
                            dataType : 'json',
                            success:function(data)          //on recieve of reply
                            { 
                           
                                oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + $("#tindakanhdid").val()).load();
                            }
                        });
                    }
                })
                         
            }); 

            $('a[name="batal[]"]', this).click(function(){

                var $anchor = $(this),
                    id      = $anchor.data('id');
                    msg     = $anchor.data('confirm');
                        
                bootbox.confirm(msg, function(result) 
                {
                    if (result==true) {

                        $.ajax
                        ({ 
 
                            type: 'POST',
                            url: baseAppUrl +  "updatestatus/2",  
                            data:  {id:id },  
                            dataType : 'json',
                            success:function(data)          //on recieve of reply
                            { 
                             
                                oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + $("#tindakanhdid").val()).load();
                        
                            }
           
                        });
                    }
                })
                         
            }); 

        });
     
    };


    var handleItemDigunakan = function(){
  
      oTableItemDigunakan = $("#table_provision").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_digunakan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },
            ],
        });
    };

    var handleTagihan = function(){
  
        oTableTagihan = $("#table_invoice3").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_tagihan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
            ],
             
        });

        $("#table_invoice3").on('draw.dt', function ()
        {
            $('a[name="viewtagihanpaket[]"]', this).click(function(){

                var $anchor     = $(this),
                    id          = $anchor.data('id');
                    namapaket   = $anchor.data('paketname');
                    transaksiid = $anchor.data('transaksiid');
                        
                $("#asses2").hide();
                $("#asses1").hide();
                $("#asses3").show();
                $("#observasiid").val(id);
                
                $("#tagihanpaketname").html(namapaket);
                oTableViewTagihann.api().ajax.url(baseAppUrl + 'listing_view_tagihan_paket/'  + transaksiid + '/' + id).load();

                $.ajax
                ({ 
 
                        type: 'POST',
                        url: baseAppUrl +  "getnomortransaksi",  
                        data:  {transaksiid:transaksiid},  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                            //  alert(data.waktu_pencatatan_value);
                            $("#tagihantransaksinumber").html(data.id);
                             
                        }
           
               });
                    
            });  
       });  
     
    };

    var handleViewTagihan= function(){
  
        oTableViewTagihann=$("#table_view_paket").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_view_tagihan_paket',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 
            ],
             
        });
     
    };


    var handlebackbtn = function()
    {
        $('#backbtn').click(function() 
        {
            $("#asses2").show();
            $("#asses1").hide();
            
        });
    };

     var handlebackbtn2 = function()
     {
        $('#backbtn2').click(function() 
        {
            $("#asses2").show();
            $("#asses1").hide();
            $("#asses3").hide();
            
        });
    };


    var handlebtnpage = function()
    {
         $('#page2').click(function() {
        if(!$("#flag2").val())
        {
          var curr_index = $('#curr_index').val();
          if(curr_index == 1)
          {
            $('#first1').attr('disabled','disabled');
            $('#prev').attr('disabled','disabled');
          }
          else
          {
            $('#first1').removeAttr('disabled');
            $('#prev').removeAttr('disabled');
          }
          $.ajax
          ({ 

            type: 'POST',
            url: baseAppUrl +  "getsejarah",  
            data:  {tindakan_id:$('#tindakanhdid').val(),pasien_id:$("#pasienid").val(),tipe:$('#tipe_trans').val(),cabang_id:$('#cabang_id').val(),flag:2},  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                
              $("#tgglpage").html(data.assesment['date_value']+' ['+data.nama_cabang+']'); 
              handleisidata(data);
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
     
         });


          
          $('#last1').removeAttr('disabled');
        }

        handlePrev();
        handleNext();
        handleFirst();
        handleLast();
      });
    };


    var handleFirst = function(){
     
        $('#first1').click(function() {
          var id        = $('input#transaksi_id_1').val(),
              tipe      = $('input#tipe_trans_1').val(),
              cabang_id = $('input#cabang_id_1').val();

          $('#li2').attr('class', 'active');
          $('#li1').removeAttr('class'); 
          
          // $('#page').removeAttr('class');
          $('#page').attr('class','tab-pane active');
          $('#tabel').attr('class','tab-pane');
                
          $.ajax
          ({
              type: 'POST',
              url: baseAppUrl +  "getsejarah",  
              data:  {tindakan_id:id,pasien_id:$("#pasienid").val(),tipe:tipe,cabang_id:cabang_id,flag:1 },  
              dataType : 'json',
              beforeSend : function(){
                  Metronic.blockUI({boxed: true });
              },
              success:function(data)          //on recieve of reply
              { 
                  
                $("#tgglpage").html(data.assesment['date_value']+' ['+data.nama_cabang+']'); 
                handleisidata(data);
              
              },
              complete : function(){
                Metronic.unblockUI();
              }
          });

          $("#tipe_trans").val(tipe);   
          $("#cabang_id").val(cabang_id);   
          $("#tindakanhdid").val(id);
          $("#curr_index").val(1);
          // oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + id).load();
          // oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + id).load();
          // oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_digunakan/' + id).load();
          // oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + id).load(); 
          $('#first1').attr('disabled','disabled');
          $('#last1').removeAttr('disabled');
          handlePrev();
          handleNext();
        });
    };
    var handlePrev = function(){
        var curr_index = $('#curr_index').val();
        if(curr_index == 1)
        {
          $('#prev').attr('disabled','disabled');
          $('#first1').attr('disabled','disabled');
        }
        else
        {
          $('#prev').removeAttr('disabled');
          $('#first1').removeAttr('disabled');

          $('#prev').click(function() {
            var curr_index = $('#curr_index').val();

            var id        = $('input#transaksi_id_'+curr_index).val(),
                tipe      = $('input#tipe_trans_'+curr_index).val(),
                cabang_id = $('input#cabang_id_'+curr_index).val();

            $('#li2').attr('class', 'active');
            $('#li1').removeAttr('class'); 
            
            // $('#page').removeAttr('class');
            $('#page').attr('class','tab-pane active');
            $('#tabel').attr('class','tab-pane');
                  
            $.ajax
            ({
                type: 'POST',
                url: baseAppUrl +  "getsejarah",  
                data:  {tindakan_id:id,pasien_id:$("#pasienid").val(),tipe:tipe,cabang_id:cabang_id,flag:1 },  
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(data)          //on recieve of reply
                { 
                    
                  $("#tgglpage").html(data.assesment['date_value']+' ['+data.nama_cabang+']'); 
                  handleisidata(data);
                
                },
                complete : function(){
                  Metronic.unblockUI();
                }
            });

            $("#tipe_trans").val(tipe);   
            $("#cabang_id").val(cabang_id);   
            $("#tindakanhdid").val(id);
            $("#curr_index").val(parseInt(curr_index)-1);
            handlePrev();
            handleNext();
            $('#last1').removeAttr('disabled');
            
          });          
        } 

    };
    var handleNext = function(){

        var curr_index = $('#curr_index').val();
        if(curr_index == last_index)
        {
          $('#next').attr('disabled','disabled');
          $('#last1').attr('disabled','disabled');
        }
        if(curr_index > last_index)
        {
          $('#next').attr('disabled','disabled');
          $('#last1').attr('disabled','disabled');
          $('#curr_index').val(last_index);
        }
        else
        {
          $('#next').removeAttr('disabled');
          $('#last1').removeAttr('disabled');

          $('#next').click(function() {
            var curr_index = $('#curr_index').val();

            var id        = $('input#transaksi_id_'+curr_index).val(),
                tipe      = $('input#tipe_trans_'+curr_index).val(),
                cabang_id = $('input#cabang_id_'+curr_index).val();

            $('#li2').attr('class', 'active');
            $('#li1').removeAttr('class'); 
            
            // $('#page').removeAttr('class');
            $('#page').attr('class','tab-pane active');
            $('#tabel').attr('class','tab-pane');
                  
            $.ajax
            ({
                type: 'POST',
                url: baseAppUrl +  "getsejarah",  
                data:  {tindakan_id:id,pasien_id:$("#pasienid").val(),tipe:tipe,cabang_id:cabang_id,flag:1 },  
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(data)          //on recieve of reply
                { 
                    
                  $("#tgglpage").html(data.assesment['date_value']+' ['+data.nama_cabang+']'); 
                  handleisidata(data);
                
                },
                complete : function(){
                  Metronic.unblockUI();
                }
            });

            $("#tipe_trans").val(tipe);   
            $("#cabang_id").val(cabang_id);   
            $("#tindakanhdid").val(id);
            $("#curr_index").val(parseInt(curr_index)+1);
            handleNext();
            handlePrev();
            $('#first1').removeAttr('disabled');
              
          });          
        } 
     
    };
    var handleLast = function(){
        $('#last1').click(function() {
          var id        = $('input#transaksi_id_'+last_index).val(),
              tipe      = $('input#tipe_trans_'+last_index).val(),
              cabang_id = $('input#cabang_id_'+last_index).val();

          $('#li2').attr('class', 'active');
          $('#li1').removeAttr('class'); 
          
          // $('#page').removeAttr('class');
          $('#page').attr('class','tab-pane active');
          $('#tabel').attr('class','tab-pane');
                
          $.ajax
          ({
              type: 'POST',
              url: baseAppUrl +  "getsejarah",  
              data:  {tindakan_id:id,pasien_id:$("#pasienid").val(),tipe:tipe,cabang_id:cabang_id,flag:1 },  
              dataType : 'json',
              beforeSend : function(){
                  Metronic.blockUI({boxed: true });
              },
              success:function(data)          //on recieve of reply
              { 
                  
                $("#tgglpage").html(data.assesment['date_value']+' ['+data.nama_cabang+']'); 
                handleisidata(data);
              
              },
              complete : function(){
                Metronic.unblockUI();
              }
          });

          $("#tipe_trans").val(tipe);   
          $("#cabang_id").val(cabang_id);   
          $("#tindakanhdid").val(id);
          $("#curr_index").val(last_index);
        
      
          $('#last1').attr('disabled','disabled');
          $('#first1').removeAttr('disabled');
          handlePrev();
          handleNext();
  
        });
    };
    var handleDokumenPelengkapPasien = function(){
 
        oTableDokumen = $("#table_dokumen_pelengkap1").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_dokumen_pasien/' + $('input#id_pasien').val() + '/1/1',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[2, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
               
                ]
        });

        $("#table_dokumen_pelengkap1").on('draw.dt', function (){
              
              $('a[name="viewpic[]"]', this).click(function(){

                var $anchor = $(this),
                    id    = $anchor.data('id');
                        
                    $('#gambar', $("#modalpic")).html("<img src="+ mb.baseUrl() + id + " style='border: 1px solid #000; max-width:500px; max-height:500px;'>");
                         
              });  
       
        });

        oTableDokumen2 = $("#table_dokumen_pelengkap2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_dokumen_pasien/' + $('input#id_pasien').val() + '/2/1',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[2, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
               
                ]
        });

        $("#table_dokumen_pelengkap2").on('draw.dt', function (){
              
              $('a[name="viewpic[]"]', this).click(function(){

                var $anchor = $(this),
                    id    = $anchor.data('id');
                        
                    $('#gambar', $("#modalpic")).html("<img src="+ mb.baseUrl() + id + " style='border: 1px solid #000; max-width:500px; max-height:500px;'>");
              });  
       
        });
     
    }; 

    var handleHistoriBtn = function(){
        $('#histori').click(function() {
         
            $("#act2").show();
            $("#act1").hide();
            oTableDokumen.api().ajax.url(baseAppUrl + 'listing_dokumen_pasien/' + $('input#id_pasien').val() + '/1/2').load();
            oTableDokumen2.api().ajax.url(baseAppUrl + 'listing_dokumen_pasien/' + $('input#id_pasien').val() + '/2/2').load();
            
        });
    };
    var handleHistoriBtn2 = function(){
        $('#kembali').click(function() {
         
            $("#act2").hide();
            $("#act1").show();
            oTableDokumen.api().ajax.url(baseAppUrl + 'listing_dokumen_pasien/' + $('input#id_pasien').val() + '/1/1').load();
            oTableDokumen2.api().ajax.url(baseAppUrl + 'listing_dokumen_pasien/' + $('input#id_pasien').val() + '/2/1').load();
            
        });
    };


    var handleBtnEditAssesment = function(){

        $('a.edit_assesment').on('click', function(){

            $('a.simpan_assesment').show();
            $(this).hide();

            $('input', $formAssesment).removeAttr("readonly");
            $('input', $formAssesment).removeAttr("disabled");
            $('div.checker', $formAssesment).removeClass("disabled");
            $('div.radio', $formAssesment).removeClass("disabled");
            $('textarea', $formAssesment).removeAttr("readonly");

        })
    }

    var handleBtnSimpanAssesment = function(){

        $('a.simpan_assesment').on('click', function(){

            var 
                id_tindakan_penaksiran = $('input#id_tindakan_penaksiran').val(),
                tanggal                = $('input#tanggal_').val(),
                waktu                  = $('input#waktu_').val(),

                assessment_cgs         = $('#assessment_cgs_').val(),
                medical_diagnose       = $('#medical_diagnose_').val(),
                time_of_dialysis       = $('input#time_of_dialysis_').val(),
                quick_of_blood         = $('input#quick_of_blood_').val(),
                quick_of_dialysate     = $('input#quick_of_dialysate_').val(),
                uf_goal                = $('input#uf_goal_').val(),

                dose                   = $('input#dose_').val(),
                first                  = $('input#first_').val(),
                maintenance            = $('input#maintenance_').val(),
                hour                   = $('input#hour_').val(),
                machine_no             = $('input#machine_no_').val(),

                dialyzer               = $('input#dialyzer_').val()

            ;

            
            ($('input#alergic_medicine').prop('checked')) ? alergic_medicine = 1 : alergic_medicine = 0;
            ($('input#alergic_food').prop('checked')) ? alergic_food = 1 : alergic_food = 0;
            ($('input#regular_').prop('checked')) ? regular = 1 : regular = 0;
            ($('input#minimal_').prop('checked')) ? minimal = 1 : minimal = 0;
            ($('input#free_').prop('checked')) ? free = 1 : free = 0;
            ($('input#new_').prop('checked')) ? new_ = 1 : new_ = 0;
            ($('input#reuse_').prop('checked')) ? reuse = 1 : reuse = 0;
            ($('input#av_shunt_').prop('checked')) ? av_shunt = 1 : av_shunt = 0;
            ($('input#femoral_').prop('checked')) ? femoral = 1 : femoral = 0;
            ($('input#double_lument_').prop('checked')) ? double_lument = 1 : double_lument = 0;
            ($('input#bicarbonate_').prop('checked')) ? bicarbonate = 1 : bicarbonate = 0;


            if (! $formAssesment.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    // $('#simpan', $formAssesment).click();
                    
                    // UTK SIMPAN ASSESMENT
                    $.ajax ({ 

                        type: 'POST',
                        url: baseAppUrl +  "simpan_assesment",  
                        data:  { 
                            id_tindakan_penaksiran: id_tindakan_penaksiran,
                            tanggal: tanggal,
                            waktu: waktu,
                            alergic_medicine: alergic_medicine,
                            alergic_food: alergic_food,
                            assessment_cgs: assessment_cgs,
                            medical_diagnose: medical_diagnose,
                            time_of_dialysis: time_of_dialysis,
                            quick_of_blood: quick_of_blood,
                            quick_of_dialysate: quick_of_dialysate,
                            uf_goal: uf_goal,
                            regular: regular,
                            minimal: minimal,
                            free: free,

                            dose: dose,
                            first: first,
                            maintenance: maintenance,
                            hour: hour,
                            machine_no: machine_no,
                            new_: new_,
                            reuse: reuse,
                            dialyzer: dialyzer,
                            av_shunt: av_shunt,
                            femoral: femoral,
                            double_lument: double_lument,
                            bicarbonate: bicarbonate
                        },  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                            if (data[0] == "success") 
                            {
                                mb.showMessage(data[0],data[1],data[2]);
                            }
                            else {

                                window.location.reload();
                            }

                        }
                    });
                        // UTK SIMPAN PROBLEM FOUND (PASIEN_PROBLEM)
                    $('.pasien_problem').each(function( index ) {

                        var i = index+1;

                        if ($('input#problem_'+i).prop('checked')) {

                            // alert($('input#problem_'+i).val());
                            $.ajax ({ 

                                type: 'POST',
                                url: baseAppUrl +  "simpan_pasien_problem",  
                                data:  { 

                                    tindakan_hd_id : $('input#id_tindakan').val(),
                                    problem_id : $('input#problem_'+i).val(),

                                },  
                                dataType : 'json',
                                beforeSend : function(){
                                    Metronic.blockUI({boxed: true });
                                },
                                success:function(data)          //on recieve of reply
                                { 

                                },
                                complete : function() {
                                    Metronic.unblockUI();
                                }
                            });
                        } else 
                        {
                            $.ajax ({ 

                                type: 'POST',
                                url: baseAppUrl +  "update_pasien_problem",  
                                data:  { 

                                    tindakan_hd_id : $('input#id_tindakan').val(),
                                    problem_id : $('input#problem_'+i).val(),

                                },  
                                dataType : 'json',
                                beforeSend : function(){
                                    Metronic.blockUI({boxed: true });
                                },
                                success:function(data)          //on recieve of reply
                                { 

                                },
                                complete : function() {
                                    Metronic.unblockUI();
                                }
                            });

                        }
                    });


                    // UTK SIMPAN KOMPLIKASI (PASIEN_KOMPLIKASI)
                    $('.pasien_komplikasi').each(function( index ) {

                        var i = index+1;
                        // alert(i);
                        if ($('input#komplikasi_'+i).prop('checked')) {

                            // alert($('input#komplikasi_'+i).val());
                            $.ajax ({ 

                                type: 'POST',
                                url: baseAppUrl +  "simpan_pasien_komplikasi",  
                                data:  { 

                                    tindakan_hd_id : $('input#id_tindakan').val(),
                                    komplikasi_id : $('input#komplikasi_'+i).val(),

                                },  
                                dataType : 'json',
                                beforeSend : function(){
                                    Metronic.blockUI({boxed: true });
                                },
                                success:function(data)          //on recieve of reply
                                { 

                                },
                                complete : function() {
                                    Metronic.unblockUI();
                                }
                            });
                        } else 
                        {
                            $.ajax ({ 

                                type: 'POST',
                                url: baseAppUrl +  "update_pasien_komplikasi",  
                                data:  { 

                                    tindakan_hd_id : $('input#id_tindakan').val(),
                                    komplikasi_id : $('input#komplikasi_'+i).val(),

                                },  
                                dataType : 'json',
                                beforeSend : function(){
                                    Metronic.blockUI({boxed: true });
                                },
                                success:function(data)          //on recieve of reply
                                { 

                                },
                                complete : function() {
                                    Metronic.unblockUI();
                                }
                            });

                        }
                    });

                    $('a.edit_assesment').show();
                    $('a.simpan_assesment').hide();

                    $('input', $formAssesment).attr("readonly", true);
                    $('input[type="checkbox"]', $formAssesment).attr("disabled", true);
                    $('div.checker', $formAssesment).addClass("disabled");
                    $('div.radio', $formAssesment).addClass("disabled");
                    $('textarea', $formAssesment).attr("readonly", true);


                }
            });

        })
    }

    var handleBtnSimpanEditSupervising = function(){

        $('a.edit_supervising').on('click', function(){

            $(this).hide();
            $('a.simpan_supervising').show();

            $('input', $formSupervising).removeAttr("readonly");
            $('textarea', $formSupervising).removeAttr("readonly");

        })

        $('a.simpan_supervising').on('click', function(){

            if (! $formSupervising.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    
                    $.ajax ({ 

                        type: 'POST',
                        url: baseAppUrl +  "simpan_supervising",  
                        data:  { 

                            id_tindakan_penaksiran: $('input#id_tindakan_penaksiran').val(),
                            
                            remaining_of_priming: $('input#remaining').val(),
                            wash_out: $('input#washout').val(),
                            drip_of_fluid: $('input#drip_of_fluid').val(),
                            blood: $('input#blood').val(),
                            drink: $('input#drink').val(),
                            vomiting: $('input#vomitting').val(),
                            urinate: $('input#urinate').val(),
                            transfusion_type: $('input#type').val(),
                            transfusion_qty: $('input#quantity').val(),
                            transfusion_blood_type: $('input#blood_type').val(),
                            serial_number: $('textarea#serial_number').val(),
                            
                            // laboratory: $('textarea#laboratory').val(),
                            // ecg: $('textarea#ecg').val(),
                            // priming: $('input#priming').val(),
                            // initiation: $('input#initiation').val(),
                            // termination: $('input#termination').val(),

                        },  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                            if (data[0] == "success") 
                            {
                                mb.showMessage(data[0],data[1],data[2]);
                            }
                            else {

                                window.location.reload();
                            }

                        }
                    });
                    

                    $('a.simpan_supervising').hide();
                    $('a.edit_supervising').show();

                    $('input', $formSupervising).attr("readonly", true);
                    $('textarea', $formSupervising).attr("readonly", true);

                }
            });

        })

    }


    var handleBtnSimpanEditExamination = function(){

        $('a.edit_examination').on('click', function(){

            $(this).hide();
            $('a.simpan_examination').show();

            // $('input', $formExamination).removeAttr("readonly");
            $('textarea', $formExamination).removeAttr("readonly");

        })

        $('a.simpan_examination').on('click', function(){

            if (! $formExamination.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    
                    $.ajax ({ 

                        type: 'POST',
                        url: baseAppUrl +  "simpan_examination",  
                        data:  { 

                            id_tindakan_penaksiran: $('input#id_tindakan_penaksiran').val(),
                            
                            laboratory: $('textarea#laboratory').val(),
                            ecg: $('textarea#ecg').val(),
                            priming: $('input#priming').val(),
                            initiation: $('input#initiation').val(),
                            termination: $('input#termination').val(),

                        },  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                            if (data[0] == "success") 
                            {
                                mb.showMessage(data[0],data[1],data[2]);
                            }
                            else {

                                window.location.reload();
                            }

                        }
                    });
                    

                    $('a.simpan_examination').hide();
                    $('a.edit_examination').show();

                    $('input', $formExamination).attr("readonly", true);
                    $('textarea', $formExamination).attr("readonly", true);

                }
            });

        })

    }


    var handleTableMonitoring = function(){

        oTableMonitoring = $tableMonitoring.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url'   : baseAppUrl + 'listing_monitoring/' + $("#tindakan_hd_id").val(),
                'type'  : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
            ]
        });
        
        $tableMonitoring.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
            $('a[name="deleted[]"]', this).click(function(){

                var $anchor = $(this),
                    id      = $anchor.data('id'),
                    msg = $anchor.data('msg');
                
                bootbox.confirm(msg, function(result){
                    if(result == true)
                    {
                        $.ajax
                        ({ 
                            type: 'POST',
                            url: baseAppUrl +  "deleteajax2",  
                            data:  {id:id },  
                            dataType : 'json',
                            beforeSend : function()
                            {
                                Metronic.blockUI({boxed: true, message: 'Sedang Diproses...'});
                            },
                            success:function(data)          //on recieve of reply
                            { 
                                oTableMonitoring.api().ajax.url(baseAppUrl + 'listing_monitoring/' + $("#tindakan_hd_id").val()).load();
                            
                            },
                            complete:function()
                            {
                                Metronic.unblockUI();
                            }
                        });                        
                    }
                }) 
                         
            });  

            
            $('a[name="restore1[]"]', this).click(function(){

                var $anchor = $(this),
                    id      = $anchor.data('id'),
                    msg = $anchor.data('msg');
                
                bootbox.confirm(msg, function(result){

                });
                $.ajax
                ({ 
                    type: 'POST',
                    url: baseAppUrl +  "restoreajax",  
                    data:  {id:id },  
                    dataType : 'json',
                    beforeSend : function()
                    {
                        Metronic.blockUI({boxed: true, message: 'Sedang Diproses...'});
                    },
                    success:function(data)          //on recieve of reply
                    { 
                        oTableMonitoring.api().ajax.url(baseAppUrl + 'listing_monitoring/' + $("#tindakan_hd_id").val()).load();
                    
                    },
                    complete:function()
                    {
                        Metronic.unblockUI();
                    }
                });
                         
            });  

            $('a[name="editobservasi[]"]', this).click(function(){

                var $anchor = $(this),
                    id      = $anchor.data('id');
                        
                    $("#monitoring_id").val(id);
                         
                $.ajax
                ({ 
 
                    type: 'POST',
                    url: baseAppUrl +  "editdataobservasi",  
                    data:  {id:id},  
                    dataType : 'json',
                    success:function(data)          //on recieve of reply
                    { 
                          //  alert(data.waktu_pencatatan_value);
                        $("#waktu").val(data.waktu_pencatatan_value);
                        $("#tekanan_darah_1").val(data.tda_value);
                        $("#tekanan_darah_2").val(data.tdb_value);
                        $("#ufg_").val(data.ufg_value);
                        $("#ufr_").val(data.ufr_value)
                        $("#ufv_").val(data.ufv_value);
                        $("#qb_").val(data.qb_value);
                        $("#nurse_").val($("#perawat_id").val());
                        $("#keterangan_").val(data.keterangan_value);
                         
                    }
           
               });
            });              
        } );

    }

    var handleSaveEditMonitoring = function()
    {
        $('a#save_monitoring').click(function() {

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
 
                    $.ajax
                    ({ 

                        type: 'POST',
                        url: baseAppUrl +  "updateobservasi",  
                        data:  {jam:$("#waktu").val(),tda:$("#tekanan_darah_1").val(),tdb:$("#tekanan_darah_2").val(),ufg:$("#ufg_").val(),ufr:$("#ufr_").val(),ufv:$("#ufv_").val(),qb:$("#qb_").val(),userid:$("#user_id").val(),keterangan:$("#keterangan_").val(),id_observasi:$("#monitoring_id").val()},  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                            mb.showMessage(data[0],data[1],data[2]);

                            oTableMonitoring.api().ajax.url(baseAppUrl + 'listing_monitoring/' + $("#tindakan_hd_id").val()).load();
                        }
               
                   });
                }
            });
        });
    };


    var handleSaveTambahMonitoring = function()
    {
        $('a#save_monitoring_add').click(function() {

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
 
                    $.ajax
                    ({ 

                        type: 'POST',
                        url: baseAppUrl +  "simpanobservasi",  
                        data:  {jam:$("#waktu_add").val(),tda:$("#tekanan_darah_1_add").val(),tdb:$("#tekanan_darah_2_add").val(),ufg:$("#ufg_add").val(),ufr:$("#ufr_add").val(),ufv:$("#ufv_add").val(),qb:$("#qb_add").val(),userid:$("#user_id").val(),keterangan:$("#keterangan_add").val(), transaksiid:$("#tindakan_hd_id").val()},  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                            mb.showMessage(data[0],data[1],data[2]);

                            oTableMonitoring.api().ajax.url(baseAppUrl + 'listing_monitoring/' + $("#tindakan_hd_id").val()).load();
                        }
               
                   });
                }
            });
        });
    };

    var handleBtnRefreshObsr = function()
    {
        $('a#refresh_table_observasi').click(function(){
            oTableMonitoring.api().ajax.url(baseAppUrl + 'listing_monitoring/' + $("#tindakan_hd_id").val()).load();
        });
    };

    var handleBtnmodalpacket = function()
    {
        $('a#modalpacket').click(function(){
           $("#modaldialogpaket").attr("class","modal-dialog");
        });
    };

    var handleTagihan2 = function(){
  
      oTableTagihan2=$("#table_tagihan_paket1").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_tagihan2/' + $("#tindakan_hd_id").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
              // 'paging'      :false,
              // 'searching'   :false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name' : 'c.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'c.tipe tipe','visible' : true, 'searchable': true, 'orderable': true },
                  
                  
                { 'name' : 'tindakan_hd_paket.paket_id paket_id','visible' : true, 'searchable': false, 'orderable': false },

                 
                ],
             
        });
    $("#table_tagihan_paket1").on('draw.dt', function (){
        $('.btn', this).tooltip();

        
    
         $('a[name="viewtagihanpaket5[]"]', this).click(function(){
               

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                       namapaket    = $anchor.data('paketname');
                       transaksiid    = $anchor.data('transaksiid');
                        tindakanhdpaket= $anchor.data('tindakanhdpaket');

                        $.ajax({
                                    type    : 'POST', 
                                    url     : baseAppUrl + 'modal_paket/' + transaksiid + '/' + id + '/' + namapaket + '/2/' + tindakanhdpaket,
                                    cache   : false,
                                    success : function(data){ 
                                    if(data){
                                        $("#modaldialogpaket").attr("class","modal-dialog");
                                        $('#modpaket').html(data);

                    
                                        $('#modal_paket').modal();
                                    }
                                    }
                        });
         });

         $('a[name="viewtagihanpaket6[]"]', this).click(function(){
               

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                       namapaket    = $anchor.data('paketname');
                       transaksiid    = $anchor.data('transaksiid');
                       tindakanhdpaket= $anchor.data('tindakanhdpaket');
                        $.ajax({
                                    type    : 'POST', 
                                    url     : baseAppUrl + 'modal_paket/' + transaksiid + '/' + id + '/' + namapaket + '/3/' + tindakanhdpaket,
                                    cache   : false,
                                    success : function(data){ 
                                    if(data){
                                        $("#modaldialogpaket").attr("class","modal-dialog modal-lg");
                                        $('#modpaket').html(data);

                    
                                        $('#modal_paket').modal();
                                    }
                                    }
                        });
         });
      
       });  
     
    };

    var handlereloadpakettagihan = function()
    {
        $('a#reloadpakettagihan').click(function(){
            // alert('hii');
            oTableTagihan2.api().ajax.url(baseAppUrl + 'listing_paket_tagihan2/' + $("#tindakan_hd_id").val()).load();
        });
    };
     var handlereloadpakettagihan2 = function()
    {
        $('a#reloadpakettagihan2').click(function(){

            $("#modal_paket").modal('toggle');
        });
    };

    var handleVisitKembali = function()
    {
        
        $('a.end_visit').click(function(){
            var id = $(this).data("bed");

            $.ajax ({ 
                type: "POST",
                url: baseAppUrl + "set_visit",  
                data:  {
                    id                  : id, 
                    type                : 1,
                    id_tindakan_visit   : $('input#tindakan_hd_visit_id').val()
                },  
                dataType : "json",
                success:function(data)         
                { 
                    
                }
            });

        })

    }

    var handleItemTelahDiguakan = function(){
  
        oTableItemTelahDigunakan = $tableItemTelahDigunakan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_telah_digunakan/'+ $('input#tindakan_hd_id').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'desc']],
            'columns'               : [
                { 'name': 'tindakan_hd_item.waktu waktu','visible' : true, 'searchable': true, 'orderable': true },
                { 'name': 'item.nama item_nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name': 'tindakan_hd_item.tipe_pemberian tipe_pemberian','visible' : true, 'searchable': false, 'orderable': true },
                { 'name': 'sum(tindakan_hd_item.jumlah) jumlah','visible' : true, 'searchable': false, 'orderable': true },
                { 'name': 'user.nama user_nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 
            ],
        });

        $tableItemTelahDigunakan.on('draw.dt', function (){
              
              $('a[name="delete[]"]', this).click(function(){
                
                var $anchor = $(this),
                    id      = $anchor.data('id')
                ;
                
                bootbox.confirm("Apakah anda ingin menghapus item ini?", function(result) 
                {
                    if (result==true) 
                    {
                        $.ajax
                        ({ 
                            type: 'POST',
                            url: baseAppUrl +  "delete_item_digunakan",  
                            data:  {id:id},  
                            dataType : 'json',
                            beforeSend : function(){
                                Metronic.blockUI({boxed: true });
                            },
                            success:function(data)          
                            { 
                                mb.showMessage(data[0],data[1],data[2]);
                                oTableItemTelahDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/'+ $('input#tindakan_hd_id').val()).load(); 
                            },
                            complete : function() {
                                Metronic.unblockUI();
                            }
                   
                        });
                    }
                });

            }); 

        });  
        
    };


    var handleObservasiItemTersimpan = function(){
  
        oTableObservasiItemTersimpan = $("#table_item_tersimpan").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_observasi_item_tersimpan/' + $('input#pasienid').val() + '/' + $('input#tindakan_hd_id').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'name': 'b.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
            ],
             
        });

        $("#table_item_tersimpan").on('draw.dt', function (){

            $('a[name="set_item[]"]', this).click(function(){
                
                var $anchor = $(this),
                    id      = $anchor.data('id');
                    tanggal = $anchor.data('tggl')
                ;

                $('#li2').attr('class', 'active');
                $('#li1').removeAttr('class'); 
                
                // $('#page').removeAttr('class');
                $('#page').attr('class','tab-pane active');
                $('#tabel').attr('class','tab-pane');
                

                $.ajax
                ({ 
 
                    type: 'POST',
                    url: baseAppUrl +  "getsejarah",  
                    data:  {tindakan_id:id, pasien_id:$("#pasienid").val(), tanggal:tanggal, flag:1 },  
                    dataType : 'json',
                    success:function(data)          //on recieve of reply
                    { 
                        
                        $("#tgglpage").html(data.date_value); 
                        $("#tgglpage2").val(data.date_value);   
                        handleisidata(data);
                    
                    }
           
                });

                oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + id).load(); 
            }); 
              

        });
     
    };

    var handleReloadTableItemTersimpan = function(){

        $('a.reload-table').click(function(){
            
            oTableObservasiItemTersimpan.api().ajax.url(baseAppUrl + 'listing_observasi_item_tersimpan/' + $('input#pasienid').val() + '/' + $('input#tindakan_hd_id').val()).load();
        })

        $('a.reload-table2').click(function(){
            
            oTableItemTelahDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/'+ $('input#tindakan_hd_id').val()).load();
        })


    }
   
    var handleTableItemSearchSelectBtn = function(table,tipe){
            // console.log($btn.length);
        var $btnsSelect = $('a.select', table);

        $.each($btnsSelect, function(idx, btn){

            $(btn).on('click', function(e){
                
                var 
                    $parentPop   = $(this).parents('.popover').eq(0),
                    rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                    $row         = $('#'+rowId, $tableOrderItem),
                    $rowClass    = $('.table_item', $tableOrderItem),
                    $itemIdEl    = $('input[name$="[tindakan_id]"]', $row),
                    ItemIdAll    = $('input[name$="[tindakan_id]"]', $rowClass),
                    $itemCodeEl  = $('input[name$="[code]"]', $row),
                    $itemNameEl  = $('input[name$="[nama]"]', $row), 
                    $itemStock   = $('input[name$="[tipe]"]', $row), 
                   

                 
                    itemId       = $(this).data('item').id
                    ;                
                
                               
                // console.log($itemIdEl);
                
                found = false;
                $.each(ItemIdAll,function(idx, value){
                    // alert(itemId);
                    if(itemId == this.value)
                    {
                        found = true;
                    }
                });
                
                if(found == false)
                {
                    
                    $itemIdEl.val($(this).data('item').id);
                     
                    if(tipe=='obat'){
                         $itemCodeEl.val($(this).data('item').kode);
                    }
                   
                    $itemNameEl.val($(this).data('item').nama);
                    $itemStock.val(tipe);
                     $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl+ 'getsatuanobat' ,  
                                data:  {id:$(this).data('item').id},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                   //   $('#accept', $form).click();
                                   // mb.showMessage(data[0],data[1],data[2])
                                  
                                   $.each(data, function(idx, key){
                                        $('select[name$="[satuan]"]', $row).append($("<option></option>").attr("value",key.id).text(key.nama));
                                    });
                                }
                   
                        });
                    // $itemStock.val($(this).data('item').stock);
                    // $itemQtyEl.val($(this).data('item').minimum_order);
                    // $itemUnitEl.text($(this).data('item').packaging);
      
                   // $divMultiple.text($(this).data('item').kelipatan_order+'/'+$(this).data('item').minimum_order);
                  // $("#popover_item_content").popover('hide');
                    //$('.search-item', $tableOrderItem).popover('hide');
                    // $('.search-item', $tableOrderItem).popover().on('click', function() {
                    //     alert('hi');
                    //      $(this).popover('hide');
 
                    // });
                $('input[name$="[tindakan_id2]"]', $row).val('');
                   // addItemRow();
                }
                $('.search-item').popover('hide');
                 $('button[name$="77resep[1][add77]"]').popover('hide');
                
                 if(found == false){
                    if($row.closest("tr").is(":last-child")) 
                    {
                         addItemRow();
                    }
                   // addItemRow();
                }
                 e.preventDefault();   
            });     
        });
        
    };

    var handleObat = function(){
 
        oTableobat=$("#table_obat").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_obat',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'name' : 'item.kode kode', 'searchable': true, 'orderable': true },
                { 'visible' : true, 'name' : 'item.nama nama', 'searchable': true, 'orderable': true },
                { 'visible' : true, 'name' : 'item.keterangan keterangan', 'searchable': true, 'orderable': true },
                { 'visible' : true, 'name' : 'item.kode kode', 'searchable': false, 'orderable': false },
                ],
             
        });
        $("#table_obat").on('draw.dt', function (){
             //handleTableItemSearchSelectBtn($("#table_obat"),'obat');
              handleTableItemSearchSelectBtn3($("#table_obat"),'obat');
         } );
     
    }; 


     var handleTableItemSearchSelectBtn3 = function(table,tipe){
            // console.log($btn.length);
        var $btnsSelect = $('a.select', table);

        $.each($btnsSelect, function(idx, btn){
          $(btn).on('click', function(e){
            var 
              $rowClass    = $('.table_item', $tableOrderItem),
              ItemIdAll    = $('input[name$="[tindakan_id]"]', $rowClass),
              itemId       = $(this).data('item').id;     

              found = false;
              $.each(ItemIdAll,function(idx, value){
                  // alert(itemId);
                  if(itemId == this.value)
                  {
                      found = true;
                  }
              });
                
              if(found == false)
              {
                $('input[name$="[tindakan_id]"]', $('table#table_order_item22')).last().val($(this).data('item').id);
                $('input[name$="[tipe_obat]"]', $('table#table_order_item22')).last().val('obat');
                if(tipe=='obat'){
                    $('label[name$="[code]"]', $('table#table_order_item22')).last().text($(this).data('item').kode);
                }
                $('label[name$="[nama]"]', $('table#table_order_item22')).last().text($(this).data('item').nama);
                $('label[name$="[tipe]"]', $('table#table_order_item22')).last().text(tipe),
                $('input[name$="[satuan]"]', $('table#table_order_item22')).last().val($(this).data('item').id_satuan),
                $('span.satuan', $('table#table_order_item22')).last().text($(this).data('item').nama_satuan),
                
                    addItemRow();
                  
                $('input[name$="[tindakan_id2]"]', $('table#table_order_item22')).last().val('');

                }
                $('#tambahrow').popover('hide');
              
                 e.preventDefault();   
            });     
        });
        
    };

     var handleTableItemSearchSelectBtn2 = function(table,tipe){
            // console.log($btn.length);
        var $btnsSelect = $('a.select2', table);

        $.each($btnsSelect, function(idx, btn){

            $(btn).on('click', function(e){
                 
                var 
                    $parentPop   = $(this).parents('.popover').eq(0),
                    rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                    $row         = $('#'+rowId, $tableOrderItem),
                    $rowClass    = $('.table_item', $tableOrderItem),
                    $itemIdEl    = $('input[name$="[tindakan_id2]"]', $row),
                    ItemIdAll    = $('input[name$="[tindakan_id2]"]', $rowClass),
                    ItemIdAll2    = $('input[name$="[tipe]"]', $rowClass),
                    $itemCodeEl  = $('input[name$="[code]"]', $row),
                    $itemNameEl  = $('input[name$="[nama]"]', $row), 
                    $itemStock   = $('input[name$="[tipe]"]', $row), 
                 
                    itemId       = $(this).data('item').id
                    ;                
                
                               
                // console.log($itemIdEl);
                
                found = false;
                found2 = false;
                $.each(ItemIdAll,function(idx, value){
                    // alert(itemId);
                    if(itemId == this.value )
                    {
                        found = true;
                    }
                });

             //    if(found == true){
             //     $.each(ItemIdAll2,function(idx, value){
             //        // alert(itemId);
             //        if(itemId == this.value)
             //        {
             //            found = true;
             //        }
             //    });
             // }
                
                if(found == false)
                {
                  //  alert('llll');
                    $itemIdEl.val($(this).data('item').id);
                    if(tipe=='obat'){
                         $itemCodeEl.val($(this).data('item').kode);
                    }
                   
                    $itemNameEl.val($(this).data('item').nama);
                    $itemStock.val(tipe);
                    $('div[name$="[div]"]', $row).html("-");
                    $('input[name$="[tindakan_id]"]', $row).val('');
                }
                $('.search-item', $tableOrderItem).popover('hide');
                 if(found == false){
                    if($row.closest("tr").is(":last-child")) 
                    {
                         addItemRow();
                    }
                   // addItemRow();
                }
                 e.preventDefault();   
            });     
        });
        
    };

     var handleBtnDeleteItem = function($btn){



        $btn.on('click', function(e){
            var 
                rowId = $btn.closest('tr').prop('id'),
                $row  = $('#'+rowId, $('table#table_order_item22'));
                        
                $row.remove();
                  
                if($('tbody>tr', $('table#table_order_item22')).length == 0){
                    addItemRow();
                }

            e.preventDefault();
        });

    };

    var handleBtnDeleteItemTransfusi = function($btn){



        $btn.on('click', function(e){
            var 
                rowId = $btn.closest('tr').prop('id'),
                $row  = $('#'+rowId, $('table#table_order_item_transfusi'));
                        
                $row.remove();
                  
                if($('tbody>tr', $('table#table_order_item_transfusi')).length == 0){
                    addItemRowTempResep();
                }

            e.preventDefault();
        });

    };

     var handleBtnDeleteItem2 = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $('table#table_order_item23'));

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $('table#table_order_item23')).length == 0){
                        addItemRow2();
                    }
            //     }
            // });

            e.preventDefault();
        });

    };

    var handleBtnDeleteItem3 = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem3);

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableOrderItem3).length == 0){
                        addItemRow3();
                    }
            //     }
            // });

            e.preventDefault();
        });

    };
    var handleBtnSearchItem2 = function(){
 
         // $('#tambahrow',$form).on('click', function(){
              
         //      alert('aaaaa');
              

         //   });
        $("#tambahrow").popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId123"/>'

        }).on("show.bs.popover", function(){
           
            var $popContainer = $(this).data('bs.popover').tip();
          // var maxWidth = parseInt(0.9*$('#form_add_cabang').width());
             var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            // $popContainer.css({minWidth: '720px', maxWidth: '720px'});
 
           // if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

           //  $lastPopoverItem = $btn;

           // $popoverItemContent.show();

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip();
            //    $popContent   = $popContainer.find('.popover-content');

            // record rowId di popcontent
          //  $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
            // $('input:hidden[name="itemTarget"]', $popcontent).val(target);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);
            $popoverItemContent.show();
        }).on('hide.bs.popover', function(){
        //   alert('tututp');
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

          //  $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
           
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
         //  alert('dddddsss');
            e.preventDefault();
        });

        $("#tambahrowresep").popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId123"/>'

        }).on("show.bs.popover", function(){
           
            var $popContainer = $(this).data('bs.popover').tip();
            var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            
        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip();
                        
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContentTransfusi);
            $popoverItemContentTransfusi.show();
        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverItemContentTransfusi ke .page-content
            $popoverItemContentTransfusi.hide();
            $popoverItemContentTransfusi.appendTo($('.page-content'));


        }).on('hidden.bs.popover', function(){
           
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
         //  alert('dddddsss');
            e.preventDefault();
        });
    }; 
    var reloadpopover =function(){
     $('#reloadpop',$form).on('click', function(){
       $("#tambahrow").remove();

      $("div#appendrow").append($("#btnadd").val());
       
         handleBtnSearchItem2();



         $.each($('.tambahpkt', $("#table_paket")), function(){
              this.remove();
              //alert('aaasssdd');
         });
         
         

         $.each($("#table_paket tbody tr"),function(){
         // alert('satu');
         //this.find("td").append("<tr />").children("tr:eq("+i+")");
         $(this).find("td:eq(1)").append('<a class="btn blue tambahpkt" name="tambahpaket" id="tambahpaket" data-original-title="" title=""><i class="fa fa-search"></i></a>');
            handleBtnSearchPaket($(this).find( "td a" ));
         })
          
         // for(t=1;t<=itemCounter-1;t++)
         // {
          
         //    $('#table_paket tbody tr:eq('+(t-1)+') td:eq(1)').append('<a class="btn blue tambahpkt" name="tambahpaket" id="tambahpaket" data-original-title="" title=""><i class="fa fa-search"></i></a>');
         //     handleBtnSearchPaket($('button[name$="77resep['+t+'][add77]"]'));
         // }
        
      
          });
};
var addItemRow = function(){
        var numRow = $('tbody tr', $tableOrderItem).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau        
        // if( numRow > 0 && ! isValidLastRow() ) return;
        var 
            $rowContainer  = $('tbody', $('table#table_order_item22')),
            $newItemRow    = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem     = $('.search-item', $newItemRow);
            $btnDelete     = $('button.del-this', $newItemRow);
  
        
        handleBtnDeleteItem($btnDelete);
         
       // handleCheck($checkMultiply);
        
    };

    var addItemRow2 = function(){
        var numRow = $('tbody tr', $('table#table_order_item23')).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $('table#table_order_item23')),
            $newItemRow    = $(tplItemRow2(itemCounter2++)).appendTo( $rowContainer ),
            $btnDelete     = $('button.del-this2', $newItemRow);
  
      //  handleBtnSearchItem($btnSearchItem);
       
        handleBtnDeleteItem2($btnDelete);
       
       // handleCheck($checkMultiply);
        
    };

    var handleSaveResep = function() {
        $form_resep = $('#form-resep');
        $('a#simpan_resep', $form_resep).click(function(){
            var msg = $(this).data('confirm');

            bootbox.confirm(msg, function(results){
                if(results == true){
                    $.ajax
                    ({ 

                        type: 'POST',
                        url: baseAppUrl+ 'save_resep' ,  
                        data: $form_resep.serialize(),  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                            if(data.success == true){
                                mb.showToast('success',data.msg,'Sukses');
                            }
                        }
               
                    });
                }
            });
            
        });

        oTableHistoryResep.api().ajax.url(baseAppUrl + 'listing_histori_detail_resep/'+$('input#tindakan_hd_id').val()+'/'+$('input#pasienid').val()).load();

    }

    function handleItemSearch(){
  
        oTableItemSearch = $("#table_item_search").dataTable(
        {
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_diluar_paket/'+$('input#tindakan_hd_id').val()+'/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name' : 'item.kode kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item_satuan.nama satuan','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'inventory.bn_sn_lot bn_sn_lot','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'inventory.expire_date expire_date','visible' : true, 'searchable': true, 'orderable': true },
            ],
        });

    }; 

    var handleDTHistoryResep = function(){
  
        oTableHistoryResep = $tableHistoryResep.dataTable(
        {
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_histori_detail_resep/'+$('input#tindakan_hd_id').val()+'/'+$('input#pasienid').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name' : 'item.nama nama_item','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'tindakan_resep_obat_detail.jumlah jumlah','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'tindakan_resep_obat_detail.dosis dosis','visible' : true, 'searchable': true, 'orderable': true },
            ],
        });

    };

    var handleGetLab = function(){
        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "get_hasil_lab",  
            data:  {command:'now',pasien_id:$("#pasienid").val(),tanggal:$('input#tanggal_sekarang').val() },  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                if(data.success == true){
                    $('label#label_tgl_lab').text(data.data_hasil_lab['tanggal']);
                    $('input#tanggal_lab').val(data.data_hasil_lab['tanggal']);
                    $('label#label_nomor_lab').text(data.data_hasil_lab['no_hasil_lab']);
                    $('label#label_klinik_lab').text(data.data_hasil_lab['nama']);
                    $('label#label_dokter_lab').text(data.data_hasil_lab['dokter']);

                    var detail = data.data_hasil_lab_detail,
                        thtml = '';

                    $.each(detail, function(idx, dtl){
                        thtml += '<tr>';
                        thtml += '<td>'+dtl.pemeriksaan+'</td>';
                        thtml += '<td>'+dtl.hasil+'</td>';
                        thtml += '<td>'+dtl.nilai_normal+'</td>';
                        thtml += '<td>'+dtl.satuan+'</td>';
                        thtml += '<td>'+dtl.keterangan+'</td>';
                        thtml += '</tr>';
                    });

                    $('table#table_detail_periksa tbody').html(thtml);

                    var detail_dokumen = data.data_hasil_lab_dokumen,
                        dokhtml = '';

                    $.each(detail_dokumen, function(idx, dtldok){
                        dokhtml += '<ul class="list-inline blog-images" style="display: inline;" id="upload_sign">';
                        dokhtml += '                <li>';
                        dokhtml += '                <a class="fancybox-button" target="_blank" title="'+dtldok.url+'" href="'+mb.baseDir()+'cloud/input_hasil_lab_manual/images/'+data.data_hasil_lab['id']+'/'+dtldok.url+'" data-rel="fancybox-button">';
                        dokhtml += '<img src="'+mb.baseDir()+'cloud/input_hasil_lab_manual/images/'+data.data_hasil_lab['id']+'/'+dtldok.url+'" alt="Tidak ditemukan">';
                        dokhtml += '</a> <span></span></li> </ul>'; 
                    });

                    $('div#gambar_lab').html(dokhtml);
                }
              
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
        });
    }

    var handleGetLabPrevNext = function(command){
        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "get_hasil_lab",  
            data:  {command:command,pasien_id:$("#pasienid").val(),tanggal:$('input#tanggal_lab').val() },  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                if(data.success == true){
                    $('label#label_tgl_lab').text(data.data_hasil_lab['tanggal']);
                    $('input#tanggal_lab').val(data.data_hasil_lab['tanggal']);
                    $('label#label_nomor_lab').text(data.data_hasil_lab['no_hasil_lab']);
                    $('label#label_klinik_lab').text(data.data_hasil_lab['nama']);
                    $('label#label_dokter_lab').text(data.data_hasil_lab['dokter']);

                    var detail = data.data_hasil_lab_detail,
                        thtml = '';

                    $.each(detail, function(idx, dtl){
                        thtml += '<tr>';
                        thtml += '<td>'+dtl.pemeriksaan+'</td>';
                        thtml += '<td>'+dtl.hasil+'</td>';
                        thtml += '<td>'+dtl.nilai_normal+'</td>';
                        thtml += '<td>'+dtl.satuan+'</td>';
                        thtml += '<td>'+dtl.keterangan+'</td>';
                        thtml += '</tr>';
                    });

                    $('table#table_detail_periksa tbody').html(thtml);

                    var detail_dokumen = data.data_hasil_lab_dokumen,
                        dokhtml = '';

                    $.each(detail_dokumen, function(idx, dtldok){
                        dokhtml += '<ul class="list-inline blog-images" style="display: inline;" id="upload_sign">';
                        dokhtml += '                <li>';
                        dokhtml += '                <a class="fancybox-button" target="_blank" title="'+dtldok.url+'" href="'+mb.baseDir()+'cloud/input_hasil_lab_manual/images/'+data.data_hasil_lab['id']+'/'+dtldok.url+'" data-rel="fancybox-button">';
                        dokhtml += '<img src="'+mb.baseDir()+'cloud/input_hasil_lab_manual/images/'+data.data_hasil_lab['id']+'/'+dtldok.url+'" alt="Tidak ditemukan">';
                        dokhtml += '</a> <span></span></li> </ul>';  
                    });

                    $('div#gambar_lab').html(dokhtml);
                }else{
                    
                    bootbox.alert('Hasil Lab tidak ditemukan');
                }
              
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
        });
    }

    var handleSaveTindakanLain = function() {
        $form_tindakan_lain = $('#form_tindakan_lain');
        $('a#simpan_tindakan_lain', $form_tindakan_lain).click(function(){
            var msg = $(this).data('confirm');

            bootbox.confirm(msg, function(results){
                if(results == true){
                    $.ajax
                    ({ 

                        type: 'POST',
                        url: baseAppUrl+ 'save_tindakan_lain' ,  
                        data: $form_tindakan_lain.serialize(),  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                            if(data.success == true){
                                mb.showToast('success',data.msg,'Sukses');
                            }
                        }
               
                    });
                }
            });
            
        });


    }


    


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
        // handleValidation();
        initForm();
        handleVisitKembali();
        handleConfirmSave();
        handleBtnRefreshObsr();
        // TAB REKAM MEDIS
        handleSejarahTransaksi();
        handleSejarahItem();
        handleTagihan2();
        handleReloadTableItemTersimpan();
        //TAB DATA PASIEN
        handleDokumenPelengkapPasien();
        handleHistoriBtn();
        handleHistoriBtn2();
        handlereloadpakettagihan();
        handlereloadpakettagihan2();
        handleBtnmodalpacket();

        handleObat();
        handleBtnSearchItem2();
        addItemRow();
        addItemRow2();
        addItemRow3();


        // TAB REKAM MEDIS
        handleObservasi();
        handleObservasi2();
        handleObservasiItemTersimpan();
        handleItemTelahDiguakan();
        handleTagihan();
        handleViewTagihan();
        handlePrev();
        handleNext();
        handleFirst();
        handleLast();
        handlebackbtn();
        handlebackbtn2();
        handlebtnpage();
        handleSaveEditObservasi();

        // TAB ASSESMENT
        handleBtnEditAssesment();
        handleBtnSimpanAssesment();
        handleBtnSimpanEditSupervising();
        handleBtnSimpanEditExamination();

        // TAB MONITORING DIALISIS
        handleTableMonitoring();
        handleSaveEditMonitoring();
        handleSaveTambahMonitoring();

        //TAB RESEP
        handleDTHistoryResep();
        handleSaveResep();
        handleItemSearch();

        handleObatTransfusi();
        handleSaveTindakanLain();


    };
    
 }(mb.app.transaksi_perawat.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.transaksi_perawat.add.init();
});