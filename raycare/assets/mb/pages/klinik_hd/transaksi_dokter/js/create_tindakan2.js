mb.app.cabang = mb.app.cabang || {};
mb.app.cabang.add = mb.app.cabang.add || {};

(function(o){

    var 
        baseAppUrl               = '',
        $form                    = $('#form_add_cabang');
        
        $errorTop                = $('.alert-danger', $form),
        $successTop              = $('.alert-success', $form),
        $tableTindakan          = $('#table_tindakan', $form),
        $tableOrderItem          = $('#table_order_item22', $form),
        $tableDiagnose          = $('#table_diagnosa', $form),
        $tableOrderItem2         = $('#table_order_item23', $form),
        $tableOrderItem3         = $('#table_paket', $form),
        $tableSupplierSearch     = $('#table_supplier_search'),
        $tableItemSearch         = $('#table_item_search'),
        $popoverTindakanContent  = $('#popover_tindakan_content'), 
        $popoverSupplierContent  = $('#popover_supplier_content'), 
        $popoverItemContent      = $('#popover_item_content'), 
        $popoverItemContent5     = $('#popover_item_content5'), 
        $popoverItemContentpaket = $('#popover_item_content_paket'), 
        $btnSearchSupplier       = $('.search-supplier', $form),
        $btnAddItem              = $('.add-item'),
        tplFormDiagnosis            = '<li class="fieldset">' + $('#tpl-form-diagnosis', $form).val() + '<hr></li>',
        regExpTplDiagnosis          = new RegExp('diagnosa_awal[0]', 'g'),   // 'g' perform global, case-insensitive
        $lastPopoverBed          = null,
        $lastPopoverItem         = null,
        $lastPopoverTindakan        = null,
        theadFilterTemplate      = $('#thead-filter-template').text(),
        tplItemRowTindakan       = $.validator.format( $('#tpl_item_row_tindakan').text() ),
        tplItemRow               = $.validator.format( $('#tpl_item_row').text() ),
        tplItemRowDiagnosa       = $.validator.format( $('#tpl_item_row_diagnosa').text() ),
        tplItemRow2              = $.validator.format( $('#tpl_item_row2').text() ),
        tplItemRow3              = $.validator.format( $('#tpl_item_row3').text() ),
        tplItemRow4              = $.validator.format( $('#tpl_item_row776').text() ),
        tplItemRow5              = $.validator.format( $('#tpl_item_row211').text() ),
        itemTindakanCounter              = 1,
        itemCounter              = 1,
        itemCounter2             = 1,
        itemCounter3             = 1,
        itemCounter4             = 1,
        itemCounter5             = 1,
        itemCounterDiagnose             = 1,
        tplFormPayment           = '<li class="fieldset">' + $('#tpl-form-payment', $form).val() + '<hr></li>',
        tplFormPayment2          = '<li class="fieldset">' + $('#tpl-form-payment2', $form).val() + '<hr></li>',
        regExpTpl                = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        paymentCounter           = 1,
        paymentCounter2          = 1,
        paymentCounter3          = 1,
        diagnosisCounter         = 1,
        jml                      = 1,
        x                        = 0,
        y                        = 0,
        timestamp                = 0,
        noerror                  = true,
        last_index              = $('input#last_index').val();

    var forms = {
        'payment' : {            
            section  : $('#section-payment', $form),
            template : $.validator.format( tplFormPayment.replace(regExpTpl, '_ID_{0}') ), //ubah ke format template jquery validator
            counter  : function(){ paymentCounter++; return paymentCounter-1; }
        }      
    }; 

    var formsDiagnosis = {
        'diagnosis' : {            
            section  : $('#section-diagnosis', $form),
            urlData  : function(){ return baseAppUrl + 'get_last_diagnose'; },
            template : $.validator.format( tplFormDiagnosis.replace(regExpTplDiagnosis, '{0}') ), //ubah ke format template jquery validator
            counter  : function(){ diagnosisCounter++; return diagnosisCounter-1; },
            fields   : ['code_ast','name'],
            fieldPrefix : 'diagnosa_awal'
        }      
    };

    var forms2 = {
        'upload' : {            
            section  : $('#section-payment2', $form),
            template : $.validator.format( tplFormPayment2.replace(regExpTpl, '_ID_{0}') ), //ubah ke format template jquery validator
            counter  : function(){ paymentCounter2++; return paymentCounter2-1; }
        }      
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
        $('a#confirm_save', $("#modaltindakan")).click(function() {
            if (! $("#modaltindakan").valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                  //  alert($('input[name$="kel"]:checked', $("#modaltindakan")).val());
                  // alert($("#nama", $("#modaltindakan")).val() + '/' + $("#jenisdokumen", $("#modaltindakan")).val() + '/' + $("#namadokumen", $("#modaltindakan")).val() + '/' + $("#nodokumen", $("#modaltindakan")).val() + '/' + $('input:radio[name="kel"]', $("#modaltindakan")).val() + '/' + $("#date", $("#modaltindakan")).val() + '/' + $("#uploadfilename", $("#modaltindakan")).val());
                    $.ajax
                    ({ 
     
                            type: 'POST',
                            url: baseAppUrl +  "adddokumen",  
                            data:  {nama:$("#nama", $("#modaltindakan")).val(),jenisdokumen:$("#jenisdokumen", $("#modaltindakan")).val(),namadokumen:$("#namadokumen", $("#modaltindakan")).val(),nodokumen:$("#nodokumen", $("#modaltindakan")).val(),tipedokumen:$('input[name$="kel"]:checked', $("#modaltindakan")).val(),tggl:$("#date", $("#modaltindakan")).val(),url:$("#uploadfilename", $("#modaltindakan")).val(),pasienid:$("#pasienid").val()},  
                            dataType : 'json',
                            success:function(data)          //on recieve of reply
                            { 
                               $('#ajax_notes').modal('toggle');
                               oTable3.api().ajax.reload();
                            
                            }
               
                   });
                }
            });
        });
    };
    var handleConfirmcreatetindakan = function(){
        $('a#confirm_save_tindakan').click(function() {
         if (! $("#form_add_cabang").valid()) return;
            var  clickcounter1=0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    clickcounter1++;
                    if(clickcounter1==1)
                    {
                       $.ajax
                        ({        
                          type: 'POST',
                          url: baseAppUrl +  "save_umum",  
                          data:  $("#form_add_cabang").serialize(),  
                          dataType : 'json', 
                          beforeSend : function(){
                              Metronic.blockUI({boxed : true});
                          },
                          success:function(data)         
                          { 
                            if(data.success == true){
                               location.href = baseAppUrl;
                            }
                          },
                          complete : function() {
                              Metronic.unblockUI();
                          } 

                        }); 
                    }
                 
                }
            });
        });

        $('a#btn-message').click(function() {
            $('a#btn-batal').click();
        });
    };

var handleConfirmSave3 = function(){
        $('a#confirm_save3', $("#modaltindakan")).click(function() {
            if (! $("#modaltindakan").valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                  
                    $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "updatedokumen",  
                                data:  {tggl:$("#date2", $("#modaltindakan")).val(),url:$("#uploadfilename2", $("#modaltindakan")).val(),pasienid:$("#pasienid").val(),id:$("#id", $("#modaltindakan")).val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                   $('#ajax_notes3').modal('toggle');
                                   oTable3.api().ajax.reload();
                                
                                }
                   
                       });
                }
            });
        });
    };

    var handleConfirmSave4 = function(){
        $('a#confirmsave',$("#modalpic22")).click(function() {
          
           if (! $("#modalpic22").valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                 
                  // $("#saveobservasi").click();
  
                    $.ajax
                        ({ 
         

                                type: 'POST',
                                url: baseAppUrl +  "updateobservasi",  
                                data:  {jam:$("#jam").val(),tda:$("#tda3").val(),tdb:$("#tdb3").val(),ufg:$("#ufg").val(),ufr:$("#ufr").val(),ufv:$("#ufv").val(),qb:$("#qb").val(),userid:$("#userid1").val(),keterangan:$("#keterangan").val(),id_observasi:$("#observasiid").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                     
                                 //  mb.showMessage(data[0],data[1],data[2]);
                                //   $("#asses2").show();
                                 //  $("#asses1").hide();

                                   oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + $("#tindakanhdid").val()).load();
                                     $('#ajax_notes4').modal('toggle');  
                                }
                   
                       });
                   
                }
            });
        });
    };
    var handleAddDokumen = function(){
        $('a#adddoc').click(function() {
           // if (! $("#modaltindakan").valid()) return;
           $("#nama", $("#modaltindakan")).val('');
           $("#namadokumen", $("#modaltindakan")).val('');
           $("#nodokumen", $("#modaltindakan")).val('');
           $('#uploadchoosen_file_1').html('');
            $("#uploadfilename", $("#modaltindakan")).val('');
            
        });
    };
 
    var handleRadiobutn = function(){
        $('input[name="kel"]', $form).click(function() {
   
        if(this.value==1){
           // $('li#grup', $form).show();
           // $('li#grup', $form).fadeIn();
        $( "li#grup" ).fadeIn( "slow", function() {
    // Animation complete
        });
        $( "#url1" ).fadeOut( "slow", function() {
    // Animation complete
        });
        }else{
           // $('li#grup', $form).hide();
            $( "li#grup" ).fadeOut( "slow", function() {
     
            });
              $( "#url1" ).fadeIn( "slow", function() {
    // Animation complete
            });
        }
            
        });
    };

     var handlecheckbutn = function(){
        $('input[name="susp"]', $form).click(function() {
        if(x==0){
            $("#suspval").val(this.value);
            x=1;
        }else{
            $("#suspval").val(0);
            
            x=0;
        }
     
        });
    };

     var handlecheckall = function(){
        $('input[name="checkall"]', $form).click(function() {
         if(y==0){
           $.each($("#table_kelompok_penjamin"), function(){
            // handleBtnDeleteItem($(btn));
                 $('input[name="check[]"]', this).prop('checked', true);
         });
            y=1;
        }else{
        $.each($("#table_kelompok_penjamin"), function(){
            // handleBtnDeleteItem($(btn));
                 $('input[name="check[]"]', this).prop('checked', false);
         });
            
            y=0;
        }
        
        });
    };
var handlebackbtn5 = function(){
        $('#backbtn5').click(function() {
        
        
          // $("#asses2").show();
          //  $("#asses1").hide();
          $("#ajax_notes4").modal('toggle');
            
        });
    };
    var handletabbutn1 = function(){
        $('#tab1').click(function() {
           // $("table_dokumen_pelengkap2").hide();
           // oTable.ajax.url(baseAppUrl + 'listing4').load()
        });
    };
      var handletabbutn2 = function(){
        $('#tab2').click(function() {
         // $("table_dokumen_pelengkap1").hide();
           
           // oTable.ajax.url(baseAppUrl + 'listing4').load()
            
        });
    };

     var handlehistoribtn = function(){
        $('#histori').click(function() {
         
            $("#act2").show();
        $("#act1").hide();
          oTable4.api().ajax.url(baseAppUrl + 'listing4/1/2/' + $("#pasienid").val()).load();
          oTable5.api().ajax.url(baseAppUrl + 'listing4/2/2/' + $("#pasienid").val()).load();
            
        });
    };
    var handlehistoribtn2 = function(){
        $('#kembali').click(function() {
         
        $("#act2").hide();
        $("#act1").show();
          oTable4.api().ajax.url(baseAppUrl + 'listing4/1/1/' + $("#pasienid").val()).load();
          oTable5.api().ajax.url(baseAppUrl + 'listing4/2/1/' + $("#pasienid").val()).load();
            
        });
    };

    var handleRefresh = function(){
        $('#refrsh').click(function() {
         
        
          oTableRujukan.api().ajax.url(baseAppUrl + 'listing_rujukan/' + $("#pasienid").val()).load();
          
            
        });
    };

 var handlebackbtn = function(){
        $('#backbtn').click(function() {
         
        
          $("#asses2").show();
           $("#asses1").hide();
            
        });
    };

     var handlebackbtn2 = function(){
        $('#backbtn2').click(function() {
         
        
          $("#asses2").show();
          $("#asses1").hide();
        $("#asses3").hide();
            
        });
    };

    var handlebtnpage = function(){
     
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
          oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + id).load();
          oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + id).load();
          oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + id).load();
          oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + id).load(); 
          $('#first1').attr('disabled','disabled');
          $('#last1').removeAttr('disabled');
          
          
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
          
          
  
        });
    };
    var initForm2 = function(){
     
        $.each(formsDiagnosis, function(idx, form){
            var $section           = form.section,
                $fieldsetContainer = $('ul', $section);

            $.ajax({
                type     : 'POST',
                url      : form.urlData(),
                data     : {pasien_id: $("#pasienid").val()},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    if (results.success === true) {
                        var rows = results.rows;
                        
                        $.each(rows, function(idx, data){
                            addFieldsetDiagnose(form, data);
                        });
                    }
                    else
                    {
                        addFieldsetDiagnose(form,{});
                    }

                    //oTableklaim.api().ajax.url(baseAppUrl + 'listing_klaim' + '/' + $("#pasienid").val()+ '/' + $("#pk").val()).load();

                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
            // handle button add
            $('a.add-diagnose', form.section).on('click', function(){
                addFieldsetDiagnose(form,{});
            });
             
            // // beri satu fieldset kosong
        });  

        // tambah 1 row order item kosong
        // addItemRow();
    };

    var addFieldsetDiagnose = function(form,data)
    {
      var numRow = $('tbody tr', $tableDiagnose).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableDiagnose),
            $newItemRow    = $(tplItemRowDiagnosa(itemCounterDiagnose++)).appendTo( $rowContainer ),
            $btnDelete     = $('.del-this', $newItemRow);
          
        handleDeleteFieldsetDiagnose($btnDelete);
    };

  

     var addItemRowTindakan = function(){
        var numRow = $('tbody tr', $tableTindakan).length;

        var 
            $rowContainer  = $('tbody', $tableTindakan),
            $newItemRow    = $(tplItemRowTindakan(itemTindakanCounter++)).appendTo( $rowContainer ),
            $btnSearchItem     = $('.search-item', $newItemRow);
            $btnDelete     = $('.del-this-tindakan', $newItemRow);

            $('select.select2', $newItemRow).select2();
  
        handleBtnSearchItemTindakan($btnSearchItem);
        
        handleBtnDeleteItemTindakan($btnDelete);

        handleSelectTindakanChange($newItemRow);
        
    };

    var handleSelectTindakanChange = function($itemRow){
      $('select[name$="[id]"]', $itemRow).on('change', function(){
          var harga = $(this).find(':selected').data('harga');

          $('label[name$="[harga]"]', $itemRow).text(mb.formatRp(harga));
          $('input[name$="[harga]"]', $itemRow).val(harga);
      });
    }

    var addItemRow = function(){
        var numRow = $('tbody tr', $tableOrderItem).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableOrderItem),
            $newItemRow    = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem     = $('.search-item', $newItemRow);
            $btnDelete     = $('.del-this', $newItemRow);
  
        handleBtnSearchItem($btnSearchItem);
        
        handleBtnDeleteItem($btnDelete);
         
       // handleCheck($checkMultiply);
        
    };

    var addItemRow2 = function(){
        var numRow = $('tbody tr', $tableOrderItem2).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableOrderItem2),
            $newItemRow    = $(tplItemRow2(itemCounter2++)).appendTo( $rowContainer ),
            $btnDelete     = $('.del-this2', $newItemRow);
  
      //  handleBtnSearchItem($btnSearchItem);
       
        handleBtnDeleteItem2($btnDelete);
       
       // handleCheck($checkMultiply);
        
    };

     var addItemRow3 = function(){
        var numRow = $('tbody tr', $tableOrderItem3).length;
 
        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableOrderItem3),
            $newItemRow    = $(tplItemRow3(itemCounter3++)).appendTo( $rowContainer ),
            $btnDelete     = $('.del-this3', $newItemRow);
            $btnSearchItem     = $('.tambahpkt', $newItemRow);

   handleBtnSearchPaket($btnSearchItem);
      //  handleBtnSearchItem($btnSearchItem);
       
       handleBtnDeleteItem3($btnDelete);
       
       // handleCheck($checkMultiply);
        
    };

    var addItemRowTemp = function(){
        var numRow = $('tbody tr', $("#table_order_item2226")).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $("#table_order_item2226")),
            $newItemRow    = $(tplItemRow4(itemCounter4++)).appendTo( $rowContainer ),
            $btnSearchItem     = $('.search-item1', $newItemRow);
            $btnDelete     = $('.del-this1', $newItemRow);
  
     //   handleBtnSearchItem233($btnSearchItem);
        
      //  handleBtnDeleteItem($btnDelete);
         
     
        
    };

     var addItemRowTemp2 = function(){
        var numRow = $('tbody tr', $("#table_order_item211")).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $("#table_order_item211")),
            $newItemRow    = $(tplItemRow5(itemCounter5++)).appendTo( $rowContainer );
           
  
      //  handleBtnSearchItem($btnSearchItem);
       
       // handleBtnDeleteItem2($btnDelete);
       
       // handleCheck($checkMultiply);
        
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
          // var maxWidth = parseInt(0.9*$('#form_add_cabang').width());
             var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            // $popContainer.css({minWidth: '720px', maxWidth: '720px'});
 
           if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

           // $popoverItemContent.show();

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popContent   = $popContainer.find('.popover-content');

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
            // $('input:hidden[name="itemTarget"]', $popcontent).val(target);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);
            $popoverItemContent.show();
        }).on('hide.bs.popover', function(){
        //   alert('tututp');
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
           
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
         //  alert('dddddsss');
            e.preventDefault();
        });
    }; 

    var handleBtnSearchItemTindakan = function($btn){
 
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){
          
            var $popContainer = $(this).data('bs.popover').tip();
          // var maxWidth = parseInt(0.9*$('#form_add_cabang').width());
             var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            // $popContainer.css({minWidth: '720px', maxWidth: '720px'});
 
           if ($lastPopoverTindakan != null) $lastPopoverTindakan.popover('hide');

            $lastPopoverTindakan = $btn;

           // $popoverTindakanContent.show();

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popContent   = $popContainer.find('.popover-content');

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
            // $('input:hidden[name="itemTarget"]', $popcontent).val(target);
            
            // pindahkan $popoverTindakanContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverTindakanContent);
            $popoverTindakanContent.show();
        }).on('hide.bs.popover', function(){
        //   alert('tututp');
            //pindahkan kembali $popoverTindakanContent ke .page-content
            $popoverTindakanContent.hide();
            $popoverTindakanContent.appendTo($('.page-content'));

            $lastPopoverTindakan = null;

        }).on('hidden.bs.popover', function(){
           
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
         //  alert('dddddsss');
            e.preventDefault();
        });
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
          // var maxWidth = parseInt(0.9*$('#form_add_cabang').width());
             var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            // $popContainer.css({minWidth: '720px', maxWidth: '720px'});
 
           if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

           // $popoverItemContent.show();

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popContent   = $popContainer.find('.popover-content');

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
            // $('input:hidden[name="itemTarget"]', $popcontent).val(target);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);
            $popoverItemContent.show();
        }).on('hide.bs.popover', function(){
        //   alert('tututp');
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
           
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
         //  alert('dddddsss');
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
    };   

     var handleBtnSearchSyarat = function(){
 
       // var rowId  = $("#addsyarat").closest('tr').prop('id');
        // console.log(rowId);
      
        $("#addsyarat").popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){
           oTable.api().ajax.reload();
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
              //  $popContent   = $popContainer.find('.popover-content');

          
         //   $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
          
            
            
            $popContainer.find('.popover-content').append($popoverItemContent5);
            $popoverItemContent5.show();
        }).on('hide.bs.popover', function(){
          
            //pindahkan kembali $popoverItemContent ke .page-content
          //  $popoverItemContent5.hide();
         //   $popoverItemContent5.appendTo($('.page-content'));

          //  $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
           
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            
            e.preventDefault();
        });
    };   


    var handleBtnSearchPaket = function($btn){
  
        //var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId2"/>'

        }).on("show.bs.popover", function(){
            oTablePaket.api().ajax.reload();
            var $popContainer = $(this).data('bs.popover').tip();
          // var maxWidth = parseInt(0.9*$('#form_add_cabang').width());
             var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            

            // if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            //  $lastPopoverItem = $("#tambahpaket");

        

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip();
                // $popContent   = $popContainer.find('.popover-content');

           
            // $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
            
            
            $popContainer.find('.popover-content').append($popoverItemContentpaket);
            $popoverItemContentpaket.show();
        }).on('hide.bs.popover', function(){
          
            
            // $popoverItemContentpaket.hide();
            // $popoverItemContentpaket.appendTo($('.page-content'));

           // $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
           
          e.preventDefault();
        });
    };   
     var handleDTItems = function(){
 
       $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
      //   oTabelItem = $tableItemSearch.dataTable({
      //       'bProcessing'              : true,
      //       'bServerSide'              : true,
      //       'sServerMethod'            : 'POST',
      //       'oLanguage'                : mb.DTLanguage(),
      //       'sAjaxSource'              : baseAppUrl + 'listing_item',
      //       'iDisplayLength'           : 10,
      //       'aLengthMenu'              : [[2, 5, 10, 25, 50, 100], [2, 5, 10, 25, 50, 100]],
      //       'aaSorting'                : [[0, 'asc']],
      //       'aoColumns'                : [
      //           { 'bVisible' : true, 'bSearchable': false, 'bSortable': false },
      //           { 'bVisible' : true, 'bSearchable': true, 'bSortable': true },
      //           { 'bVisible' : true, 'bSearchable': true, 'bSortable': true },
                
      //           { 'bVisible' : true, 'bSearchable': false, 'bSortable': false },
      //           ]
      //   });
      //   $('#table_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
      //   $('#table_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

      // //  handleTableItemSearchSelectBtn();

        $tableItemSearch.on('draw.dt', function (){
             handleTableItemSearchSelectBtn();
         } );

      //   //sembunyikan #popover_item_content
        $popoverItemContent.hide();
    }; 

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
                $('input[name$="[tindakan_id]"]').last().val($(this).data('item').id);
                $('input[name$="[tipe_obat]"]').last().val('obat');
                if(tipe=='obat'){
                    $('label[name$="[code]"]').last().text($(this).data('item').kode);
                }
                $('label[name$="[nama]"]').last().text($(this).data('item').nama);
                $('label[name$="[tipe]"]').last().text(tipe),
                $('input[name$="[satuan]"]').last().val($(this).data('item').id_satuan),
                $('span.satuan').last().text($(this).data('item').nama_satuan),
                
                    addItemRow();
                  
                $('input[name$="[tindakan_id2]"]').last().val('');

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
                    
                    // $itemStock.val($(this).data('item').stock);
                    // $itemQtyEl.val($(this).data('item').minimum_order);
                    // $itemUnitEl.text($(this).data('item').packaging);
                     $('input[name$="[tindakan_id]"]', $row).val('');
                   // $divMultiple.text($(this).data('item').kelipatan_order+'/'+$(this).data('item').minimum_order);
                  // $("#popover_item_content").popover('hide');
                    //$('.search-item', $tableOrderItem).popover('hide');
                    // $('.search-item', $tableOrderItem).popover().on('click', function() {
                    //     alert('hi');
                    //      $(this).popover('hide');
 
                    // });
 
                   // addItemRow();
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

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem);

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {

                  // alert($('input[name$="[itemrow]"]', $row).val());

                    $.each($("#table_order_item2226 tbody tr"),function(){
                        if($('input[name$="[itemrow2]"]', this).val()==$('input[name$="[itemrow]"]', $row).val())
                        {
                           //alert('hii');
                           $(this).remove();
                        }
                    })

                    $.each($("#table_order_item211 tbody tr"),function(){
                        if($('input[name$="[itemrow3]"]', this).val()==$('input[name$="[itemrow]"]', $row).val())
                        {
                           //alert('hii');
                           $(this).remove();
                        }
                    })
                  $row.remove();
                      
                    if($('tbody>tr', $tableOrderItem).length == 0){
                        addItemRow();
                    }
            //     }
            // });

            e.preventDefault();
        });

    };

     var handleBtnDeleteItem2 = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem2);

        $btn.on('click', function(e){
             
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableOrderItem2).length == 0){
                        addItemRow2();
                    }
            //     }
            // });

            e.preventDefault();
        });

    };

     var handleBtnDeleteItemTindakan = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableTindakan);

        $btn.on('click', function(e){   
            $row.remove();
            if($('tbody>tr', $tableTindakan).length == 0){
                addItemRowTindakan();
            }

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

var addrowracikan =function(){
     $('#addrowracikan',$form).on('click', function(){
      
          var itemrow3 =0;
          $('input[name$="[nama]"]',$tableOrderItem).last().val($("#nama_racikan").val());
          $('input[name$="[jumlah]"]',$tableOrderItem).last().val($("#jumlah_racikan").val());
          $('input[name$="[itemrow]"]',$tableOrderItem).last().val(itemCounter);
          $('input[name$="[keteranganmodal]"]',$tableOrderItem).last().val($("#keterangan_racikan").val());
           $('input[name$="[tipe_obat]"]',$tableOrderItem).last().val('racikan');
            $('input[name$="[tindakan_id]"]',$tableOrderItem).last().val(1);
          
          $('input[name$="[code]"]',$tableOrderItem).last().hide();
          $('input[name$="[tipe]"]',$tableOrderItem).last().hide();
          $('input[name$="[item_dosis]"]',$tableOrderItem).last().hide();
          $('select[name$="[satuan]"]',$tableOrderItem).last().hide();
          $('button[name$="[add77]"]',$tableOrderItem).last().hide();

          itemrow3=itemCounter;
          addItemRow();
        
        
      $.each($("#table_order_item222 tbody tr"), function(){
       if(!$('input[name$="[code77]"]',this).val()){

       }else{
           addItemRowTemp();
           $('input[name$="[itemrow2]"]').last().val(itemrow3);
           $('input[name$="[tindakan_id1]"]').last().val($('input[name$="[tindakan_id77]"]',this).val());
           $('input[name$="[code1]"]').last().val($('input[name$="[code77]"]',this).val());
           $('input[name$="[nama1]"]').last().val($('input[name$="[nama77]"]',this).val());
           $('input[name$="[jumlah1]"]').last().val($('input[name$="[jumlah77]"]',this).val());
           $('input[name$="[satuan1]"]').last().val($('select[name$="[satuan77]"]',this).val());
            
       }
         
              
         });

    $.each($("#table_order_item233 tbody tr"), function(){
       if(!$('input[name$="[keterangan77]"]',this).val()){

       }else{
           addItemRowTemp2();
           $('input[name$="[itemrow3]"]').last().val(itemrow3);
           $('input[name$="[keterangan11]"]').last().val($('input[name$="[keterangan77]"]',this).val());
           
            
       }
         
              
         });
       //  $.each($('table[name$="[table_order_item222]"]',$("#modalracikan1")),function(idx, btn){
       // //   addItemRowTemp();
       //    alert($('input[name$="[code77]',idx).val());
       //   // $('input[name$="[code1]').last().val($('input[name$="[code77]',this).val());
       //  });

        $('#simpan_racikan').attr("data-dismiss","modal");
      });
};
// var addrowracikan2 =function(){
//      $('#addrowracikan2',$form).on('click', function(){
//         addItemRowTemp();
//       });
// };
    var initForm = function(){
       $.ajax
        ({ 
          type: 'POST',
          url: baseAppUrl +  "update_waktu_proses",  
          data:  {id:$("#pk").val()},  
          dataType : 'json',
          success:function(data)          //on recieve of reply
          { 
            // handleKlaim();
          
          }
       });

        $('#tambahrow2',$form).on('click', function(){

        addItemRow2();


        });

        $('a.add-tindakan',$form).on('click', function(){

          addItemRowTindakan();

        });

        addItemRowTindakan();
        addItemRow();
        addItemRow2();
        addItemRow3();
         handleLoadDenah();
 

    };

     var addFieldset = function(form){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            result=0,
            result2=0,
            result3=0;
           // alert(counter);

      //s  handleDatePicker();
        $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
           
            handleSelectSection(this.value, $newFieldset);
        });
        $('a.del-this', $newFieldset).on('click', function(){
            
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });
 
         $('a[name$="[addrow]"]', $newFieldset).on('click', function(){
       
         addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
           // handleSelectSection(this.value, $newFieldset);
        });
         addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
        //jelasin warna hr pemisah antar fieldset

        $('input[name$="[idcount]"]', $newFieldset).val(counter);
        $('hr', $newFieldset).css('border-color', 'silver');
    };

     var addFieldset3 = function(form,tipe,judul,max,id){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
            result=0,
            result2=0,
            result3=0;
           // alert(counter);

      //s  handleDatePicker();
      //  $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
           
        handleSelectSection(tipe, $newFieldset);
        $('select[name$="[payment_type]"]', $newFieldset).val(tipe);
        $('input[name$="[judul]"]', $newFieldset).val(judul);
        $('input[name$="[text]"]', $newFieldset).val(max);
        $('input[name$="[idsyarat]"]', $newFieldset).val(id);
       // });
        $('a.del-this', $newFieldset).on('click', function(){
            
            handleDeleteFieldset($(this).parents('.fieldset').eq(0));
        });
 
         $('a[name$="[addrow]"]', $newFieldset).on('click', function(){
       
         addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
           // handleSelectSection(this.value, $newFieldset);
        });

         $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "syaratdetail",  
                                data:  {id:id},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                  $.each(data, function(key, value) {
                                         addItemRow2($('table[name$="[table_order_item22]"]', $newFieldset),counter,value.text,value.value);
                                    });
                                
                                }
                   
                       });
         //addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
        //jelasin warna hr pemisah antar fieldset

        $('input[name$="[idcount]"]', $newFieldset).val(counter);

         $('select[name$="[payment_type]"]', $newFieldset).attr('disabled','disabled');
         $('input[name$="[judul]"]', $newFieldset).attr('disabled','disabled');
         $('input[name$="[text]"]', $newFieldset).attr('disabled','disabled');
         $('a[name$="[addrow]"]', $newFieldset).attr('disabled','disabled');
        $('hr', $newFieldset).css('border-color', 'silver');
    };

     var addFieldset2 = function(form){
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer)
            ;
           // alert(counter);

      //s  handleDatePicker();
        uploadfile($newFieldset);
        $('a.del-this2', $newFieldset).on('click', function(){
            
            handleDeleteFieldset2($(this).parents('.fieldset').eq(0));
        });
 
        //  $('a[name$="[addrow]"]', $newFieldset).on('click', function(){
        //  //  alert('hiiii222');
        //  addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
        //    // handleSelectSection(this.value, $newFieldset);
        // });
        //  addItemRow($('table[name$="[table_order_item22]"]', $newFieldset),counter);
        // //jelasin warna hr pemisah antar fieldset

        // $('input[name$="[idcount]"]', $newFieldset).val(counter);
        $('hr', $newFieldset).css('border-color', 'silver');
    };

    var handleDeleteFieldset = function($fieldset){
      var 
          $parentUl     = $fieldset.parent(),
          fieldsetCount = $('.fieldset', $parentUl).length,
          hasId         = false,  //punya id tidak, jika tidak bearti data baru
          hasDefault    = 0,      //ada tidaknya fieldset yang di set sebagai default, diset ke 0 dulu
          $inputDefault = $('input:hidden[name$="[is_default]"]', $fieldset), 
          isDefault     = $inputDefault.val() == 1

      if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.

      $fieldset.remove();  
    };

    var handleDeleteFieldsetDiagnose = function($btn){
      var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableDiagnose);

          $btn.on('click', function(e){
              $row.remove();
                  
              if($('tbody>tr', $tableDiagnose).length == 0){
                  addFieldsetDiagnose();
              }

            e.preventDefault();
        });
    };

    var handleDeleteFieldset2 = function($fieldset){
      
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset', $parentUl).length,
            hasId         = false,  //punya id tidak, jika tidak bearti data baru
            hasDefault    = 0,      //ada tidaknya fieldset yang di set sebagai default, diset ke 0 dulu
            $inputDefault = $('input:hidden[name$="[is_default]"]', $fieldset), 
            isDefault     = $inputDefault.val() == 1
            

        if (fieldsetCount<=1) 
        {
             $('label[name$="[choosen_file_1]"]', $fieldset).html("");
             $('div[name$="[choosen_file_container_1]"]', $fieldset).hide();
             $('input[name$="[filename]"]', $fieldset).val('');
             return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
        }
        $fieldset.remove();
  
    };

     var handleSelectSection = function(value,$fieldset)
    {
        if(value == 1 || value==2)
        {
            $('div#section_1', $fieldset).show();
            $('div#section_2', $fieldset).hide();
            $('div#section_3', $fieldset).hide();
        }
        if(value == 4 || value==5 || value==6 || value==7)
        {
            $('div#section_1', $fieldset).hide();
            $('div#section_2', $fieldset).show();
            $('div#section_3', $fieldset).hide();
            
        }
        if(value == 3)
        {
            $('div#section_3', $fieldset).hide();
            $('div#section_2', $fieldset).hide();
            $('div#section_1', $fieldset).hide();
            
        }
    }

    var handleDTItems2 = function(){
 
       $("#table_cabang").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing2' + '/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
              'paging'                :false,
            'searching'                :false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
               
                ]
        });
     
    }; 

     var handleDTItems3 = function(){
  
       oTable3=$("#table_cabang4").dataTable({
            'processing'            : true,
            'filter'                : false,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing3/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': true, 'orderable': true },
               
                ]
        });

        $("#table_cabang4").on('draw.dt', function (){
             
              
          });
    }; 

    var handleDTItems4 = function(){
 
       oTable4=$("#table_dokumen_pelengkap1").dataTable({
            'processing'            : true,
            'filter'                : false,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing4/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
               
                ]
        });

        $("#table_dokumen_pelengkap1").on('draw.dt', function (){
              
              $('a[name="viewpic[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        
                        $('#gambar', $("#modalpic")).html("<img src="+ mb.baseUrl() + id + " style='border: 1px solid #000; max-width:500px; max-height:500px;'>");
                         
              });  
       
        });

        oTable5=$("#table_dokumen_pelengkap2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing4/2/1',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
               
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

  //    var handleDeleteRow = function(id){

  //   bootbox.confirm(msg, function(result) {
  //     if(result==true) {
        
  //       $.ajax
  //                       ({ 
         
  //                               type: 'POST',
  //                               url: baseAppUrl +  "deleteajax",  
  //                               data:  {id:id},  
  //                               dataType : 'json',
  //                               success:function(data)          //on recieve of reply
  //                               { 
                                     
  //                                   mb.showMessage(data[0],data[1],data[2]);
  //                                   oTable3.api().ajax.reload();
                      
  //                               }
                   
  //                      });
  //                       // oTable.ajax.url(baseAppUrl + 'listing').load();
                        
  //     } 
  //   });
  
  // };

    var handleDTsyarat = function(){
 
      oTable = $("#table_syarat2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_syarat',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': false, 'orderable': true },
                 { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ],
             'createdRow'             : function( row, data, dataIndex ) {
                $('a[name="pilih[]"]', row).click(function(){
                    var $anchor = $(this),
                      id    = $anchor.data('id');
                      tipe    = $anchor.data('tipe');
                      judul    = $anchor.data('judul');
                      max    = $anchor.data('max');
                    // alert(max);
                     $.each(forms, function(idx, form){
 
                             addFieldset3(form,tipe,judul,max,id);
                               
                        });
                });
                                            //     handleBtnSearchItem($('.search-item', row));
                                            // handleDelete($('a[name="delete[]"]', row));
                 }
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

    var handleRacikan = function(){
 
     $("#table_racikan2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_racikan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ],
             
        });
     
         $("#table_racikan2").on('draw.dt', function (){
             handleTableItemSearchSelectBtn2($("#table_racikan2"),'racikan');
         } );
    }; 

 var handleKlaim = function(){
  
      oTableklaim = $("#table_klaim1").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'filter'                : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_klaim' + '/' + $("#pasienid").val()+ '/' + $("#pk").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'paging'                : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
            ],             
        });
        
        $("#table_klaim1").on('draw dt', function(){
          // alert('test');
          $('input[type=radio]', this).uniform();
          
        });
         
     
    }; 

    var handleRujukan = function(){
  
      oTableRujukan=$("#table_rujukan").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_rujukan' + '/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name':'a.nama asal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'b.nama tujuan','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'rujukan.tanggal_dirujuk tggldirujuk','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'rujukan.tanggal_rujukan tgglrujukan','visible' : true, 'searchable': true, 'orderable': false },
                 
                ],
                'createdRow'             : function( row, data, dataIndex ) {

                  if($('input[name="poliid[]"]', row).val()==1)
                  {
                     $(row).addClass("gradeX active");
                   //  $(this, row).parents('tr').addClass("gradeX active");
                          
                  }
                }
             
        });
         
     
    };

     var handlePaket = function(){
  
      oTablePaket=$("#table_obat222").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_popover',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 
                ],
            "createdRow"             : function( row, data, dataIndex ) {
                            $('a[name="viewpaket3[]"]', row).click(function(){
                                found=false;
                                 
                               $.each($('input[name$="[idpaket]"]', $("#table_paket")),function(idx, value){
                               
                                if($('a[name="viewpaket3[]"]', row).data('item').id == this.value)
                                    {
                                        found=true;
                                       
                                    } 
                                });

                              if(found==false)
                               {
                                            $('input[name$="[namapaket]"]').last().val($(this).data('item').nama);
                                             $('input[name$="[idpaket]"]').last().val($(this).data('item').id);
                                             $('input[name$="[harga]"]').last().val($(this).data('item').harga);
                                           
                                            // addItemRow3();
                                }
                                          
                                       $('.tambahpkt', $("#table_paket")).popover('hide');          


                                                });
                                            }
             
        });

        
     
    };

     var handleSejarahTransaksi = function(){
  
      oTableSejarahTransaksi=$("#table_sejarah_transaksi").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_sejarah_transaksi' + '/' + $("#pasienid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'rm_transaksi_pasien.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'rm_transaksi_pasien.nama_cabang nama_cabang','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'rm_transaksi_pasien.nama_poliklinik nama_poliklinik','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'rm_transaksi_pasien.no_transaksi no_transaksi','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'rm_transaksi_pasien.nama_dokter nama_dokter','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'rm_transaksi_pasien.keterangan keterangan','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'rm_transaksi_pasien.transaksi_id transaksi_id','visible' : true, 'searchable': false, 'orderable': false },
                 
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
                        oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + id).load();
                        oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + id).load();
                        oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + id).load();
                        oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + id).load(); 
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
    $("#medicine2", $("#form_add_cabang")).prop("checked", false);
    $('#food2', $("#form_add_cabang")).prop('checked', false);
    $("#regular", $("#form_add_cabang")).prop("checked", false);
    $("#minimal", $("#form_add_cabang")).prop("checked", false);
    $("#free", $("#form_add_cabang")).prop("checked", false);
    $("#new", $("#form_add_cabang")).prop("checked", false);
    $("#reuse", $("#form_add_cabang")).prop("checked", false);
    $("#av_shunt", $("#form_add_cabang")).prop("checked", false);
    $("#femoral", $("#form_add_cabang")).prop("checked", false);
    $("#double_lument", $("#form_add_cabang")).prop("checked", false);
    $("#bicarbonate", $("#form_add_cabang")).prop("checked", false);

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
       
      $("#medicine2", $("#form_add_cabang")).prop("checked", true);
    }
    if(data.assesment['alergic_food']==1)
    {
      $('#food2', $("#form_add_cabang")).prop('checked', true);
    }
    
    $("#bb_awal").val(data.tindakan['berat_awal']);
    $("#bb_akhir").val(data.tindakan['berat_akhir']);
    $("#assessment_cgs").val(data.assesment['assessment_cgs_value']);
    $("#medical_diagnose").val(data.assesment['medical_diagnose_value']);
    $("#time_of_dialysis").val(data.assesment['time_of_dialysis_value']);
    $("#quick_of_blood").val(data.assesment['quick_of_blood_value']);
    $("#quick_of_dialysate").val(data.assesment['quick_of_dialysis_value']);
    $("#uf_goal").val(data.assesment['uf_goal_value']);

    if(data.assesment['heparin_reguler_value'] == 1)
    {
         $("#regular", $("#form_add_cabang")).prop("checked", true);
    }
    if(data.assesment['heparin_minimal_value'] == 1)
    {
        $("#minimal", $("#form_add_cabang")).prop("checked", true);
    }
    if(data.assesment['heparin_free_value'] == 1)
    {
         $("#free", $("#form_add_cabang")).prop("checked", true);
    }

    if(data.assesment['dialyzer_new_value'] == 1)
    {
         $("#new", $("#form_add_cabang")).prop("checked", true);
    }
    if(data.assesment['dialyzer_reuse_value'] == 1)
    {
         $("#reuse", $("#form_add_cabang")).prop("checked", true);
    }

     if(data.assesment['ba_avshunt_value'] == 1)
    {
         $("#av_shunt", $("#form_add_cabang")).prop("checked", true);
    }
     if(data.assesment['ba_femoral_value'] == 1)
    {
         $("#femoral", $("#form_add_cabang")).prop("checked", true);
    }
     if(data.assesment['ba_catheter_value'] == 1)
    {
         $("#double_lument", $("#form_add_cabang")).prop("checked", true);
    }

    if(data.assesment['dialyzer_type_value'] == 1)
    {
         $("#bicarbonate", $("#form_add_cabang")).prop("checked", true);
    }
    $("#dose").val(data.assesment['dose_value']);
    $("#first").val(data.assesment['first_value']);
    $("#maintenance").val(data.assesment['maintenance_value']);
    $("#hour").val(data.assesment['hours_value']);
    $("#machine_no").val(data.assesment['machine_no_value']);
    $("#dialyzer").val(data.assesment['dialyzer_value']);
    $("#remaining").val(data.assesment['remaining_of_priming_value']);
    $("#washout").val(data.assesment['wash_out_value']);
    $("#drip_of_fluid").val(data.assesment['drip_of_fluid_value']);
    $("#blood").val(data.assesment['blood_value']);
    $("#drink").val(data.assesment['drink_value']);
    $("#vomitting").val(data.assesment['vomiting_value']);
    $("#urinate").val(data.assesment['urinate_value']);
    $("#type").val(data.assesment['transfusion_type_value']);
    $("#quantity").val(data.assesment['transfusion_qty_value']);
    $("#blood_type").val(data.assesment['transfusion_blood_type_value']);
    $("#serial_number").val(data.assesment['serial_number_value']);
    $("#laboratory").val(data.assesment['laboratory_value']);
    $("#ecg").val(data.assesment['ecg_value']);
    $("#priming").val(data.assesment['priming_value']);
    $("#initiation").val(data.assesment['initiation_value']);
    $("#termination").val(data.assesment['termination_value']);

                                    
}

    var handleChangeBB = function() {

      var bbKering = $('input#berat_kering').val();
      var bbAkhir = $('input#berat_akhir_post').val();
      // alert(bbKering);
      ufg = 0;
      if(bbKering !== 'Obs' || bbKering !== 'OBS' || bbKering !== 'obs')
      {
        ufg = parseFloat($('input#berat').val() - bbKering);
        if(ufg > 0)
        {
          ufg = ufg;
        }

        ufg = ufg.toFixed(2);
      }
      if(bbKering === 'Obs' )
      {
        ufg = parseFloat($('input#berat').val() - bbAkhir);
        if(ufg > 0)
        {
          ufg = ufg;
        }

        ufg = ufg.toFixed(2);
      }
      if(bbKering === 'OBS' )
      {
        ufg = parseFloat($('input#berat').val() - bbAkhir);
        if(ufg > 0)
        {
          ufg = ufg;
        }

        ufg = ufg.toFixed(2);
      }

      if(bbKering === 'obs' )
      {
        ufg = parseFloat($('input#berat').val() - bbAkhir);
        if(ufg > 0)
        {
          ufg = ufg;
        }

        ufg = ufg.toFixed(2);
      }


      $('input#ufg').val(ufg);

      $('input#berat').on('change', function() {
        var bbKering = $('input#berat_kering').val();
        var bbAkhir = $('input#berat_akhir_post').val();
        // alert(bbKering);
        ufg = 0;
        if(bbKering !== 'Obs' || bbKering !== 'OBS' || bbKering !== 'obs')
        {
          ufg = parseFloat($(this).val() - bbKering);
          if(ufg > 0)
          {
            ufg = ufg;
          }

          ufg = ufg.toFixed(2);
        }
        if(bbKering === 'Obs' )
        {
          ufg = parseFloat($(this).val() - bbAkhir);
          if(ufg > 0)
          {
            ufg = ufg;
          }

          ufg = ufg.toFixed(2);
        }
        if(bbKering === 'OBS' )
        {
          ufg = parseFloat($(this).val() - bbAkhir);
          if(ufg > 0)
          {
            ufg = ufg;
          }

          ufg = ufg.toFixed(2);
        }

        if(bbKering === 'obs' )
        {
          ufg = parseFloat($(this).val() - bbAkhir);
          if(ufg > 0)
          {
            ufg = ufg;
          }

          ufg = ufg.toFixed(2);
        }


        $('input#ufg').val(ufg);

      });

      $('input#berat_kering').on('change', function() {
        var bbKering = $('input#berat_kering').val();
        var bbAkhir = $('input#berat_akhir_post').val();

        ufg = 0;
        if(bbKering !== 'Obs' || bbKering !== 'OBS' || bbKering !== 'obs')
        {
          ufg = parseFloat($('input#berat').val() - bbKering);
          if(ufg > 0)
          {
            ufg = ufg;
          }

          ufg = ufg.toFixed(2);
        }
        if(bbKering == 'Obs' )
        {
          ufg = parseFloat($('input#berat').val() - bbAkhir);
          if(ufg > 0)
          {
            ufg = ufg;
          }
          ufg = ufg.toFixed(2);
        }
        if(bbKering == 'OBS' )
        {
          ufg = parseFloat($('input#berat').val() - bbAkhir);
          if(ufg > 0)
          {
            ufg = ufg;
          }
          ufg = ufg.toFixed(2);
        }
        if(bbKering == 'obs' )
        {
          ufg = parseFloat($('input#berat').val() - bbAkhir);
          if(ufg > 0)
          {
            ufg = ufg;
          }
          ufg = ufg.toFixed(2);
        }

        $('input#ufg').val(ufg);

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
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
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
              
              $('a[name="viewitem[]"]', this).click(function(){
                $("#flag2").val(1);
                var $anchor   = $(this),
                    id        = $anchor.data('id')
                    tipe      = $anchor.data('tipe'),
                    cabang_id = $anchor.data('cabang_id')
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
                  oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + id).load();
                  oTableItemTersimpan.api().ajax.url(baseAppUrl + 'listing_item_tersimpan/' + id).load();
                  oTableItemDigunakan.api().ajax.url(baseAppUrl + 'listing_item_telah_digunakan/' + id).load();
                  oTableTagihan.api().ajax.url(baseAppUrl + 'listing_paket_tagihan/' + id).load(); 
              });  
       
          });
         
     
    };

    var handleObservasi = function(){
  
      oTableObservasi=$("#table_dialysis").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_observasi',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
              'pagingType'            :'full_numbers',
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
                                    
                                     oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + + $("#tindakanhdid").val()).load();
                                
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
                                    
                                     oTableObservasi.api().ajax.url(baseAppUrl + 'listing_observasi/' + + $("#tindakanhdid").val()).load();
                                
                                }
                   
                       });
                         
              });  

              $('a[name="viewobservasi[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                      
                       // $("#asses2").hide();
                       // $("#asses1").show();
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
                                      $("#nurse").val(data.nama);
                                      $("#userid1").val(data.user_id_value);
                                      $("#keterangan").val(data.keterangan_value);
                                     
                                     
                                     
                                     
                                     
                                     
                                     
                                     
                                
                                }
                   
                       });

                    oTableObservasi2.api().ajax.url(baseAppUrl + 'listing_observasi2/'  + $("#tindakanhdid").val()).load();
                         
              });  
       
          });
         
     
    };

    var handleObservasi2 = function(){
  
      oTableObservasi2=$("#table_dialysis2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_observasi2/' + $("#tindakanhdid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
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

    var handleItemTersimpan = function(){
  
      oTableItemTersimpan=$("#table_item2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_tersimpan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
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
                       id    = $anchor.data('id');
                        msg    = $anchor.data('confirm');
                         bootbox.confirm(msg, function(result) {
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
                       id    = $anchor.data('id');
                        msg    = $anchor.data('confirm');
                         bootbox.confirm(msg, function(result) {
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
  
      oTableItemDigunakan=$("#table_provision").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_telah_digunakan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
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
  
      oTableTagihan=$("#table_invoice3").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_tagihan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                  
                  
                { 'visible' : false, 'searchable': false, 'orderable': false },

                 
                ],
             
        });
    $("#table_invoice3").on('draw.dt', function (){
       $('a[name="viewtagihanpaket[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                       namapaket    = $anchor.data('paketname');
                       transaksiid    = $anchor.data('transaksiid');
                        
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
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                {'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                {'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 
                ],
             
        });
         
     
    };

   
    var uploadfile = function(table) {
 //    alert(mb.baseUrl());
  //var table2=$("#dokumen_1");
    $('input[name$="[dokumen]"]', table).uploadify({
                            "swf"               :  mb.baseUrl() + "assets/mb/global/uploadify/uploadify.swf",
                            "uploader"          : mb.baseUrl() + "assets/mb/global/uploadify/uploadify3.php",
                            "formData"          : {"type" : "dokumen", "dokumen_id" : "", "nama_dokumen" : "approval"}, 
                            "fileObjName"       : "Filedata", 
                            "fileSizeLimit"     : "2048KB",
                            // "fileTypeDesc"      : "Image Files (.jpg, .jpeg, .png)",
                            // "fileTypeExts"      : "*.jpg; *.jpeg; *.png",
                            "method"            : "post", 
                            "multi"             : false, 
                            "queueSizeLimit"    : 1, 
                            "removeCompleted"   : true, 
                            "removeTimeout"     : 5, 
                            "uploadLimit"       : 5, 
                            "onUploadSuccess"   : function(file, data, response) {
                             var paramsArray = data.split('%%__%%');
                                param1 = paramsArray[0]; 
                                param2 = paramsArray[1]; 
                                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                                {
                                    $('label[name$="[choosen_file_1]"]', table).html("<img src="+ mb.baseUrl() + "assets/mb/pages/klinik_hd/klaim_asuransi/images/temp/"+param1+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");
                                    $('div[name$="[choosen_file_container_1]"]', table).show();
                                    $('input[name$="[filename]"]', table).val(param1);
                                }else{
                                    $('input[name$="[filename]"]', table).val(param1);
                                     $('div[name$="[choosen_file_container_1]"]', table).show();
                                    $('label[name$="[choosen_file_1]"]', table).html('<b>' + file.name + '</b>');
                                }
                              
                            },
                            "onUploadComplete"   : function(file) {
                             
                              
                            }
                        }); 
};

var uploadfile2 = function(table) {
  
    $('#uploaddokumen').uploadify({
                            "swf"               :  mb.baseUrl() + "assets/mb/global/uploadify/uploadify.swf",
                            "uploader"          : mb.baseUrl() + "assets/mb/global/uploadify/uploadify4.php",
                            "formData"          : {"type" : "dokumen", "dokumen_id" : "", "nama_dokumen" : "dokumen"}, 
                            "fileObjName"       : "Filedata", 
                            "fileSizeLimit"     : "2048KB",
                            // "fileTypeDesc"      : "Image Files (.jpg, .jpeg, .png)",
                            // "fileTypeExts"      : "*.jpg; *.jpeg; *.png",
                            "method"            : "post", 
                            "multi"             : false, 
                            "queueSizeLimit"    : 1, 
                            "removeCompleted"   : true, 
                            "removeTimeout"     : 5, 
                            "uploadLimit"       : 5, 
                            "onUploadSuccess"   : function(file, data, response) {
                             var paramsArray = data.split('%%__%%');
                                param1 = paramsArray[0]; 
                                param2 = paramsArray[1]; 
                                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                                {
                                    $('#uploadchoosen_file_1').html("<img src="+ mb.baseUrl() + "assets/mb/pages/klinik_hd/transaksi_dokter/images/temp/"+param1+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");
                                    $('#uploadchoosen_file_container_1').show();
                                    $('#uploadfilename').val(param1);
                                }else{
                                    $('#uploadfilename').val(param1);
                                     $('#uploadchoosen_file_container_1').show();
                                    $('#uploadchoosen_file_1').html('<b>' + file.name + '</b>');
                                }
                              
                            },
                            "onUploadComplete"   : function(file) {
                             
                              
                            }
                        }); 
};

var uploadfile3 = function(table) {
  
    $('#uploaddokumen2').uploadify({
                            "swf"               :  mb.baseUrl() + "assets/mb/global/uploadify/uploadify.swf",
                            "uploader"          : mb.baseUrl() + "assets/mb/global/uploadify/uploadify4.php",
                            "formData"          : {"type" : "dokumen", "dokumen_id" : "", "nama_dokumen" : "dokumen"}, 
                            "fileObjName"       : "Filedata", 
                            "fileSizeLimit"     : "2048KB",
                            // "fileTypeDesc"      : "Image Files (.jpg, .jpeg, .png)",
                            // "fileTypeExts"      : "*.jpg; *.jpeg; *.png",
                            "method"            : "post", 
                            "multi"             : false, 
                            "queueSizeLimit"    : 1, 
                            "removeCompleted"   : true, 
                            "removeTimeout"     : 5, 
                            "uploadLimit"       : 5, 
                            "onUploadSuccess"   : function(file, data, response) {
                             var paramsArray = data.split('%%__%%');
                                param1 = paramsArray[0]; 
                                param2 = paramsArray[1]; 
                                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                                {
                                    $('#uploadchoosen_file_12').html("<img src="+ mb.baseUrl() + "assets/mb/pages/klinik_hd/transaksi_dokter/images/temp/"+param1+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");
                                    $('#uploadchoosen_file_container_12').show();
                                    $('#uploadfilename2').val(param1);
                                }else{
                                    $('#uploadfilename2').val(param1);
                                     $('#uploadchoosen_file_container_12').show();
                                    $('#uploadchoosen_file_12').html('<b>' + file.name + '</b>');
                                }
                              
                            },
                            "onUploadComplete"   : function(file) {
                             
                              
                            }
                        }); 
};
 var handleDatePickers = function () {
        
            $('.date-picker', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'd MM yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal

            
              $('.date-picker', $("#modaltindakan")).datepicker({
                rtl: Metronic.isRTL(),
                format : 'd M yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }

var handlekategori = function(){
     $('#kategori').on('change', function(){
          
            oTableobat.api().ajax.url(baseAppUrl + 'listing_obat/' + this.value).load();
        
        });
}

    
    // DENAH 
    var handleKlikBed = function(){

        var $bed = $('polygon');

        $.each($bed, function(idx, colBed){
            var
                $colBed = $(colBed);

            // console.log($colBed);
            $colBed.popover({
                html : true,
                container : 'body',
                placement : 'top',
                content: function(){
                 
                }
            }).on("show.bs.popover", function(){
                $(this).data("bs.popover").tip().addClass('popover_menu');
                $(this).data("bs.popover").tip().css({minWidth: '100px', maxWidth: '300px', margin: '26px 0 0 40px', left : '85.808815px'});
                if ($lastPopoverBed !== null) $lastPopoverBed.popover('hide');
                $lastPopoverBed = $colBed;
            }).on('hide.bs.popover', function(){
                $lastPopoverBed = null;
            }).on('click', function(e){

            });
        });
    }

    var handleClickReload = function(){
        $('.btn').tooltip();

        $('a#refresh').click(function(){
            handleLoadDenah();
        })
    }

    var handleLoadDenah = function() 
    {
        handleLantai2();
        handleLantai3();
        handleLantai4();
    }
    
    var handleLantai2 = function()
    {
        // Load pertamakali tampilan pertama Lantai 2
        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "show_denah_lantai_html_create",  
            data:  {lantai: 1, shift:$('input#shift').val()},  
            dataType : "text",
            beforeSend : function(){
              
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_1").html(data);
            },
            complete : function() {
                
            }
        });
    }

    var handleLantai3 = function()
    {
        // Load pertamakali tampilan pertama Lantai 3
        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "show_denah_lantai_html_create",  
            data:  {lantai: 2, shift:$('input#shift').val()},  
            dataType : "text",
            beforeSend : function(){
              
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_2").html(data);
            },
            complete : function() {
                
            }
        });
    }

    var handleLantai4 = function()
    {
        // Load pertamakali tampilan pertama Lantai 3
        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "show_denah_lantai_html_create",  
            data:  {lantai: 3, shift:$('input#shift').val()},  
            dataType : "text",
            beforeSend : function(){
              
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_3").html(data);
            },
            complete : function() {
                
            }
        });
    }

    var handleCommet = function() {
        
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl +'commet_bed',
            data     : { timestamp : timestamp },
            // dataType : 'json',
            success  : function( transport ) {
                var response = $.parseJSON(transport);
                timestamp = response.timestamp;
                handleLoadDenah();
                noerror = true;
                       
            },
            complete : function (transport) {
                if (!noerror)
                {
                  // if a connection problem occurs, try to reconnect each 5 seconds
                  setTimeout(function(){ handleCommet() }, 5000); 
                }
                else
                  handleCommet();
                
                noerror = false;
            }
        });

    }

    var handleRefreshUpload = function()
    {
        $('a#refresh_upload').click(function(){
            var pasien_id = $("#pasienid").val();
             oTable3.api().ajax.url(baseAppUrl +  'listing3' + '/' + pasien_id).load();
        });
    }

    var handleMultiSelect = function () {
        $('#multi_select_penyakit_bawaan').multiSelect();   
        $('#multi_select_penyakit_penyebab').multiSelect();   
    };
 
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
      //  alert(baseAppUrl);
        initForm();
        handleChangeBB();
        handleRefreshUpload();
        handleMultiSelect();

        initForm2();
        handleCommet();
     //   alert('hi');
        handleValidation();
         //handleKlaim();
      
         handleDTItems2();
         handleDTItems3();
         handleDTItems4();
         handleObat();
         handleRacikan();
         handleRujukan();
         handlePaket();
         handleSejarahTransaksi();
         handleSejarahItem();
         handleObservasi();
         handleObservasi2();
         handleItemTersimpan();
         handleItemDigunakan();
         handleTagihan();
         handleViewTagihan();

         // Kebutuhan utk denah | Create by Abu
       
        handleClickReload();

 handleConfirmSave();
       handleConfirmSave3();
       handleConfirmSave4();
         handleBtnSearchItem2();
         handleConfirmcreatetindakan();
        handlebtnpage();
        
        handlebackbtn();
        handlebackbtn2();
        handlebackbtn5();
         handletabbutn1();
        handletabbutn2();
        handlehistoribtn();
        handlehistoribtn2();
        handlekategori();
        handleRefresh();
        uploadfile2();
        uploadfile3();
        reloadpopover();
        // addrowracikan();
        // addrowracikan2();           // ini error
        
             
       
       handleDatePickers();
       handleAddDokumen();
       // handleBtnSearchPaket();      // ini error
       

       
       
     


        
      //  uploadfile();
    };
 }(mb.app.cabang.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.add.init();
});