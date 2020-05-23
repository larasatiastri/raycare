mb.app.cabang = mb.app.cabang || {};
mb.app.cabang.add = mb.app.cabang.add || {};
(function(o){

    var 
        baseAppUrl             = '',
        $form                  = $('#form_pendaftaran_tindakan'),
        $popoverPasienContent     = $('#popover_pasien_content'), 
        $lastPopoverItem        = null,
        $tablePilihPasien        = $('#table_pilih_pasien'),
        xx=0,
        ll=0,
        zz=0,
        vv=0;

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

    var initform = function()
    {
        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);
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

  
     var handleSelectDokter = function(){
        $('select#poliklinik_sub').on('change', function(){
  $("#noantrian").html('');
            $('input#warehouse_id').val($(this).val());

            var $dokter_select = $('select#dokter');
            var $label_status = $('label#status');
            var $tab_jadwal = $('a#jadwal');

            $label_status.removeClass("hidden");
            
           
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_status_poli',
                data     : {id_poliklinik: $(this).val(),cabang_id:$("#cabangid").val()},
                dataType : 'json',
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');

  
                        if(results[1]==1){
                            $("#ant").hide();
                             $("#ant2").hide();
                            $tab_jadwal.removeClass("hidden");
                        }else{
                             $tab_jadwal.attr('class','hidden');
                             $("#ant").show();
                             $("#ant2").show();
                         }

                        
                        $("div#status2").html(results[0]);
                   
                  
                }
            });

            
 
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_dokter',
                data     : {id_poliklinik: $(this).val()},
                dataType : 'json',
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');

                    $dokter_select.empty();

                   
                    $dokter_select.append($("<option></option>")
                        .attr("value", '').text('Pilih..'));

                    $.each(results, function(key, value) {
                       
                     
                        $dokter_select.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                       // $dokter_select.val('');

                    });
                }
            });
        })
    }

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

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            //$popoverPasienContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPasienContent);

            $popoverPasienContent.show();

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverPasienContent.hide();
            $popoverPasienContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

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
            'stateSave' : true,
            'pagingType' : 'full_numbers',
            'columns'               : [
                { 'name' : "pasien.id",'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : "pasien.nama",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.tempat_lahir,pasien.tanggal_lahir",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.nama",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.nama",'visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#table_pilih_pasien_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_pasien_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $tablePilihPasien);
        handlePilihPasienSelect( $btnSelects );

        $tablePilihPasien.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihPasienSelect( $btnSelect );
            
        } );

        $popoverPasienContent.hide();        
    };

var handleAntrian = function(){
        oTable2=$("#table_antrian").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_antrian/' + $("#poliklinik_sub").val() + '/' + $("#dokter").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
           'stateSave' : true,
            'pagingType' : 'full_numbers',
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
               
                ]
        });       
        $('#table_pilih_pasien_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_pasien_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
      
    };

    var handleRujukan = function(){
  
      oTableRujukan=$("#table_rujukan").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_rujukan' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'stateSave' : true,
            'pagingType' : 'full_numbers',
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 { 'visible' : false, 'searchable': false, 'orderable': false },
                 
                ],
                "createdRow"             : function( row, data, dataIndex ) {
                                               xx++;
                                               $("#notifrujukan").html("<span class='badge badge-primary' style='width:15px;height:15px'>"+xx+"</span>");
                                               // $("#notifrujukan").html("<img src="+ mb.baseUrl() + "assets/global/img/notifblue.jpg style='width:12px;height:12' placeholder='1'>");

                                           },
                                                 
              "footerCallback": function( tfoot, data, start, end, display ) {
                    if(data.length==0){
                         $('a#rujukan').attr("class","hidden");
                    }else{
                         $('a#rujukan').removeClass("hidden");
                    }
                         
                }   

        });
$('#table_rujukan').on( 'order.dt', function () {
    // This will show: "Ordering on column 1 (asc)", for example
    xx=0;
} );

         $("#table_rujukan").on('draw.dt', function (){
             
              $('a[name="pilihid[]"]', this).click(function(){
 
                 var $anchor = $(this),
                       id    = $anchor.data('id');
                         $('input#warehouse_id').val(id);
                          $('select#poliklinik_sub').val(id);

            var $dokter_select = $('select#dokter');
            var $label_status = $('label#status');
            var $tab_jadwal = $('a#jadwal');

            $label_status.removeClass("hidden");
            
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_status_poli',
                data     : {id_poliklinik: id,cabang_id:$("#cabangid").val()},
                dataType : 'json',
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');

 
                        if(results[1]==1){
                             $("#ant").hide();
                             $("#ant2").hide();
                            $tab_jadwal.removeClass("hidden");
                        }else{
                              $("#ant").show();
                             $("#ant2").show();
                             $tab_jadwal.attr('class','hidden');
                         }

                        
                        $("div#status2").html(results[0]);
                   
                  
                }
            });

            
 
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_dokter',
                data     : {id_poliklinik: id},
                dataType : 'json',
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');

                    $dokter_select.empty();

                   
                    $dokter_select.append($("<option></option>")
                        .attr("value", '').text('Pilih..'));

                    $.each(results, function(key, value) {
                       
                     
                        $dokter_select.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                       // $dokter_select.val('');

                    });
                }
            });
                       
                         
              });  
          });
     
    };
     var handleSelectDokter2 = function(){
        $('select#dokter').on('change', function(){

           $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_antrian',
                data     : {poliklinik_id: $("#poliklinik_sub").val(),dokter_id:$("#dokter").val()},
                dataType : 'json',
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#noantrian").html(results);
                    $("#noantrianval").val(results);
  
                  
                }
            });
            oTable2.api().ajax.url(baseAppUrl +  'listing_antrian/' + $("#poliklinik_sub").val() + '/' + $("#dokter").val()).load();
        })
    }
    var handlePilihPasienSelect = function($btn){
        $btn.on('click', function(e){

            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $noPasien       = $('input[name="no_member"]'),
                $IdPasien       = $('input[name="id_pasien"]'),
                $NamaPasien     = $('input[name="nama_pasien"]'),
                $AlamatPasien   = $('input[name="alamat_pasien"]'),
                $GenderPasien   = $('input[name="gender_pasien"]'),
                $TglLahirPasien = $('input[name="tgl_lahir_pasien"]'),
                $NomorPasien    = $('input[name="telepon_pasien"]'),
                $Upload         = $('a#upload');
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-pasien', $form).popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            //alert($(this).data('item').url_photo);
            if($(this).data('item').url_photo!=null)
            {
                 $("#imgpasien").html("<img src="+ mb.baseUrl() + $(this).data('item').url_photo + " style='border: 1px solid #000; max-width:100px;'>");
            }else{
                $("#imgpasien").html('');
            }
                 if($(this).data('item').url_photo!=null)
                                        {
                                            $("#imgpasien2").html("<img src="+ mb.baseUrl() + $(this).data('item').url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                                        }else{
                                            $("#imgpasien2").html('');
                                        }
           
            $IdPasien.val($(this).data('item').id);
            $noPasien.val($(this).data('item').no_ktp);
            $NamaPasien.val($(this).data('item').nama);
            $AlamatPasien.val($(this).data('item').alamat);
            if($(this).data('item').gender == 'P')
            {
                $gender = "Perempuan";
            }
            else
            {
                $gender = "Laki-laki";
            }
            
            $tempat = $(this).data('item').tempat_lahir;
            $tgl = $(this).data('item').tanggal_lahir;
           // var tgllahir = new Date($tgl);
            $ttl = $tempat + ", " +  $tgl;
            $GenderPasien.val($gender);
            $TglLahirPasien.val($ttl);
            $NomorPasien.val($(this).data('item').nomor);
            $Upload.removeClass("hidden");
            $('a#rujukan').removeClass("hidden");
            $('a#bayar').removeClass("hidden");
            $('a#klaim').removeClass("hidden");
            $('a#pasien').removeClass("hidden");
            $('a#upload').removeClass("hidden");
            // alert($itemIdEl.val($(this).data('item').id));
            $("#id_pasien").val();

            var hasil='';
            var hasil2;
            var hasil3;
            $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_data_pasien",  
                                data:  {pasienid:$("#id_pasien").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    $.each(data.form_data2, function(key, value) {
                                       
                                        $("#data_frekuensi_perawatan").html(value.counts);
                                       
                                        
                                       
                                    });

                                    $.each(data.form_data5, function(key, value) {
                                        
                                        $("#data_no_member").html(value.no_member);
                                        $("#data_keterangan").html(value.keterangan);
                                        $("#data_tempat_lahir").html(value.tempat_lahir + ',' + value.tanggal_lahir);
                                        $("#data_nama").html(value.nama);
                                        $("#data_agama").html(value.nama1);
                                        $("#data_nama2").html(value.nama2);
                                        if(value.is_meninggal==0)
                                        {
                                             $("#data_status").html('Hidup');
                                         }else{
                                             $("#data_status").html('Meninggal');
                                         }
                                        

                                         $("#data_kodecabang").html(value.kodecabang);
                                         $("#data_rujukan").html(value.rujukan);
                                         $("#data_tgglrujukan").html(value.tgglrujukan);
                                         $("#data_nomorrujukan").html(value.nomorrujukan);
                                         $("#data_dokter_pengirim").html(value.dokter_pengirim);
                                         
                                    });

                                     $.each(data.form_data6, function(key, value) {
                                       
                                        hasil +='<div class="form-group"><label class="control-label col-md-3">'+ value.nama +' :</label><div class="col-md-4"><label class="control-label">' + value.nomor +'</label></div></div>';
                                       
                                        
                                       
                                    });
                                     $("div#data_telepon").html(hasil);
                                       $.each(data.form_data7, function(key, value) {
                                        
                                        $("#data_nama_subjek").html(value.nama);
                                        $("#data_alamat").html(value.alamat);
                                        $("#data_rt_rw").html(value.rt_rw);
                                       
                                         
                                    });


                                       $.each(data.form_data8, function(key, value) {
                                            $("#data_kelurahan").html(value.kelurahan);
                                            $("#data_kecamatan").html(value.kecamatan);
                                            $("#data_kota").html(value.kota);
                                       
                                         
                                        });

                                        $.each(data.form_data9,function(key, value) {
                                            if(value.tipe==1){
                                                 hasil2='<label class="control-label">'+  value.nama  + '</label>';
                                            }
                                            if(value.tipe==2){
                                                 hasil3='<label class="control-label">'+  value.nama  + '</label>';
                                            }
                                            // hasil2+='<label class="control-label">'+ if(value.tipe==1) { value.nama }; + '</label>';
                                            // hasil3+='<label class="control-label">'+ if(value.tipe==2) { value.nama }; + '</label>';
                                            $("#data_penyakit_bawaan").html(hasil2);
                                            $("#data_penyakit_penyebab").html(hasil3);
                                            
                                       
                                         
                                        });
                                   
                                
                                }
                   
                       });
            xx=0;
            ll=0;
            zz=0;
            vv=0;
            $("#notifklaim").html('');
            $("#notifrujukan").html('');
            $("#notifbayar").html('');
            $("#notifupload").html('');

            oTableRujukan.api().ajax.url(baseAppUrl +  'listing_rujukan' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
            oTableklaim.api().ajax.url(baseAppUrl +  'listing_klaim' + '/' + $("#id_pasien").val()).load();
            oTable3.api().ajax.url(baseAppUrl +  'listing_upload' + '/' + $("#id_pasien").val()).load();
            oTablePembayaran.api().ajax.url(baseAppUrl +  'listing_pembayaran' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
            // alert(oTableRujukan.data.size()); 
           
            e.preventDefault();
        });     
    };
    

    var handleKlaim = function(){
   var x=0;
      oTableklaim=$("#table_klaim1").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_klaim' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'stateSave' : true,
            'pagingType' : 'full_numbers',
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 { 'visible' : true, 'searchable': false, 'orderable': false },
                 { 'visible' : false, 'searchable': false, 'orderable': false },
                ],
              "createdRow"             : function( row, data, dataIndex ) {
                
                                        if(data[5]=='Tersedia'){
                                             zz++;
                                             $("#notifklaim").html("<span class='badge badge-warning' style='width:15px;height:15px'>"+zz+"</span>");
                                                
                                        }
                                              
                                           },
             "footerCallback": function( tfoot, data, start, end, display ) {
                    if(data.length==0){
                         $('a#klaim').attr("class","hidden");
                    }else{
                         $('a#klaim').removeClass("hidden");
                    }
                         
                }   
             
        });
$('#table_klaim1').on( 'order.dt', function () {
    // This will show: "Ordering on column 1 (asc)", for example
    zz=0;
} );
         // if(oTableklaim.row().data().length==0)
         // {
         //     $('a#klaim').attr("class","hidden");
         // }else{
         //     $('a#klaim').removeClass("hidden");
         // }
     
    }; 

     var handleUpload = function(){
    var x=0;
       oTable3=$("#table_cabang4").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_upload',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'stateSave' : true,
            'pagingType' : 'full_numbers',
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
               { 'visible' : false, 'searchable': false, 'orderable': true },
                ],
                "createdRow"             : function( row, data, dataIndex ) {
                  
                                        if(data[7]=='Kadaluarsa'){
                                             vv++;
                                             $("#notifupload").html("<span class='badge badge-danger' style='width:15px;height:15px'>"+ll+"</span>");
                                             
                                        }
                                              
                                           },
                 "footerCallback": function( tfoot, data, start, end, display ) {
                    if(data.length==0){
                         $('a#upload').attr("class","hidden");
                    }else{
                         $('a#upload').removeClass("hidden");
                    }
                         
                }   

        });
$('#table_cabang4').on( 'order.dt', function () {
    // This will show: "Ordering on column 1 (asc)", for example
    vv=0;
} );
        $("#table_cabang4").on('draw.dt', function (){
              
              $('a[name="viewpic[]"]', this).click(function(){

                 var $anchor = $(this),
                       id    = $anchor.data('id');
                        
                        $('#gambar', $("#modalpic")).html("<img src="+ mb.baseUrl() + id + " style='border: 1px solid #000; max-width:500px; max-height:500px;'>");
                         
              });  

               $('a[name="update[]"]', this).click(function(){
                $("#id").val($(this).data('item').id);
                 $("#nama2").html($("#nama_pasien").val());
                  $("#namadokumen2").html($(this).data('item').subjek);
                  if($(this).data('item').jenis==1){
                     $("#jenisdokumen2").html("Dokumen Pelengkap");
                  }else{
                    $("#jenisdokumen2").html("Dokumen Rekam Medis");
                  }
                 // $("#jenisdokumen2").html($(this).data('item').jenis);
                  if($(this).data('item').tipe==2){
                     $("#kel22").html("Gambar");
                  }else{
                    $("#kel22").html("File");
                  }
                 
                  $("#nodokumen2").html($(this).data('item').no_dokumen);
                  $("#uploadchoosen_file_container_12").show();

                   var paramsArray = $(this).data('item').url_file.split('.');
                                param1 = paramsArray[0]; 
                                param2 = paramsArray[1]; 
                   var paramsArray2 = $(this).data('item').url_file.split('/');
                                param3 = paramsArray2[paramsArray2.length-1]; 
                                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                                {
                                    $('#uploadchoosen_file_12').html("<img src="+ mb.baseUrl() + $(this).data('item').url_file + " style='border: 1px solid #000; max-width:200px'>");
                                }else{
                                    $('#uploadchoosen_file_12').html(param3);
                                }
                  
              });  
       
          });

        
        
     
    }; 

    var handlePembayaran = function(){
   
      oTablePembayaran=$("#table_pembayaran").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pembayaran' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'stateSave' : true,
            'pagingType' : 'full_numbers',
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 { 'visible' : true, 'searchable': false, 'orderable': false },
                 { 'visible' : false, 'searchable': false, 'orderable': false },
                ],
                "createdRow"             : function( row, data2, dataIndex ) {
                     
                                            if(data2[5]==1){

                                                  ll++;   
                                                    $("#notifbayar").html("<span class='badge badge-danger' style='width:15px;height:15px'>"+ll+"</span>");
                                                  // $("#notifbayar").html(ll);
                                            }
                                              
                                               

                                           },
              "footerCallback": function( tfoot, data, start, end, display ) {
                    if(data.length==0){
                         $('a#bayar').attr("class","hidden");
                    }else{
                         $('a#bayar').removeClass("hidden");
                    }
                         
                }                             
             
        });
         $('#table_pembayaran').on( 'order.dt', function () {
    // This will show: "Ordering on column 1 (asc)", for example
    ll=0;
} )
     
    }; 

 var handleConfirmSave4 = function(){
        $('a#confirm_save', $("#modaltindakan")).click(function() {
            if (! $("#modaltindakan").valid()) return;
//alert($("#id_pasien").val());
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                  //  alert($('input[name$="kel"]:checked', $("#modaltindakan")).val());
                  // alert($("#nama", $("#modaltindakan")).val() + '/' + $("#jenisdokumen", $("#modaltindakan")).val() + '/' + $("#namadokumen", $("#modaltindakan")).val() + '/' + $("#nodokumen", $("#modaltindakan")).val() + '/' + $('input:radio[name="kel"]', $("#modaltindakan")).val() + '/' + $("#date", $("#modaltindakan")).val() + '/' + $("#uploadfilename", $("#modaltindakan")).val());
                    $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "adddokumen",  
                                data:  {nama:$("#nama", $("#modaltindakan")).val(),jenisdokumen:$("#jenisdokumen", $("#modaltindakan")).val(),namadokumen:$("#namadokumen", $("#modaltindakan")).val(),nodokumen:$("#nodokumen", $("#modaltindakan")).val(),tipedokumen:$('input[name$="kel"]:checked', $("#modaltindakan")).val(),tggl:$("#date", $("#modaltindakan")).val(),url:$("#uploadfilename", $("#modaltindakan")).val(),pasienid:$("#id_pasien").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                   $('#ajax_notes').modal('toggle');
                                    oTable3.api().ajax.url(baseAppUrl +  'listing_upload' + '/' + $("#id_pasien").val()).load();
                                
                                }
                   
                       });
                }
            });
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
                                data:  {tggl:$("#date2", $("#modaltindakan")).val(),url:$("#uploadfilename2", $("#modaltindakan")).val(),pasienid:$("#id_pasien").val(),id:$("#id", $("#modaltindakan")).val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                   $('#ajax_notes3').modal('toggle');
                                   oTable3.api().ajax.url(baseAppUrl +  'listing_upload' + '/' + $("#id_pasien").val()).load();
                                
                                }
                   
                       });
                }
            });
        });
    };
 
    var handleAddDokumen = function(){
        $('a#adddoc').click(function() {
           // if (! $("#modaltindakan").valid()) return;
           $("#nama3").html($("#nama_pasien").val());
           $("#nama", $("#modaltindakan")).val('');
           $("#namadokumen", $("#modaltindakan")).val('');
           $("#nodokumen", $("#modaltindakan")).val('');
           $('#uploadchoosen_file_1').html('');
            $("#uploadfilename", $("#modaltindakan")).val('');
            
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
    };

    var handleDTItems4 = function(){
 
       oTable4=$("#table_dokumen_pelengkap1").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing4/1/1/' + $("#id_pasien").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'stateSave' : true,
            'pagingType' : 'full_numbers',
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': true, 'orderable': true },
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
                'url' : baseAppUrl + 'listing4/2/1/' + + $("#id_pasien").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'stateSave' : true,
            'pagingType' : 'full_numbers',
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

    var handlehistoribtn = function(){
        $('#histori').click(function() {
         
            $("#act2").show();
        $("#act1").hide();
          oTable4.api().ajax.url(baseAppUrl + 'listing4/1/2/' + $("#id_pasien").val()).load();
          oTable5.api().ajax.url(baseAppUrl + 'listing4/2/2/' + $("#id_pasien").val()).load();
            
        });
    };
    var handlehistoribtn2 = function(){
        $('#kembali').click(function() {
         
        $("#act2").hide();
        $("#act1").show();
          oTable4.api().ajax.url(baseAppUrl + 'listing4/1/1/' + $("#id_pasien").val()).load();
          oTable5.api().ajax.url(baseAppUrl + 'listing4/2/1/' + $("#id_pasien").val()).load();
            
        });
    };
 //function setpasien(x)
    var setpasien = function()
    {
     $.each($("#table_pilihjadwal2"), function(){
            // handleBtnDeleteItem($(btn));
         
                 $('.selectjadwal', this).click(function() {
                      var $anchor = $(this),
                       id    = $anchor.data('item');
   
             
                var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $noPasien       = $('input[name="no_member"]'),
                $IdPasien       = $('input[name="id_pasien"]'),
                $NamaPasien     = $('input[name="nama_pasien"]'),
                $AlamatPasien   = $('input[name="alamat_pasien"]'),
                $GenderPasien   = $('input[name="gender_pasien"]'),
                $TglLahirPasien = $('input[name="tgl_lahir_pasien"]'),
                $NomorPasien    = $('input[name="telepon_pasien"]'),
                $Upload         = $('a#upload');
                $itemCodeEl     = null,
                $itemNameEl     = null;        
 
                var hasil='';
                var hasil2;
                var hasil3;

                $.ajax
                        ({ 
         
                                type: 'POST',
                                url: mb.baseUrl() + 'reservasi/pendaftaran_tindakan/' +  "get_pasien_data",  
                                data:  {pasien_id:id},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    $.each(data, function(key, value) {
                                         if(value.url_photo!=null)
                                        {
                                            $("#imgpasien").html("<img src="+ mb.baseUrl() + value.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                                        }else{
                                            $("#imgpasien").html('');
                                        }

                                         if(value.url_photo!=null)
                                        {
                                            $("#imgpasien2").html("<img src="+ mb.baseUrl() + value.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                                        }else{
                                            $("#imgpasien2").html('');
                                        }
                                        $IdPasien.val(value.id);
                                        $noPasien.val(value.no_ktp);
                                        $NamaPasien.val(value.nama);
                                        $AlamatPasien.val(value.alamat);
                                        if(value.gender == 'P')
                                        {
                                            $gender = "Perempuan";
                                        }
                                        else
                                        {
                                            $gender = "Laki-laki";
                                        }

                                        $tempat = value.tempat_lahir;
                                        $tgl = value.tanggal_lahir;
                                        $ttl = $tempat + ", " + $tgl;
                                        $GenderPasien.val($gender);
                                        $TglLahirPasien.val($ttl);
                                        $NomorPasien.val(value.nomor);

                                        $('a#rujukan').removeClass("hidden");
                                        $('a#bayar').removeClass("hidden");
                                        $('a#klaim').removeClass("hidden");
                                        $('a#pasien').removeClass("hidden");
                                        $('a#upload').removeClass("hidden");
            
                                        $.ajax
                                        ({ 
         
                                            type: 'POST',
                                            url: baseAppUrl +  "get_data_pasien",  
                                            data:  {pasienid:id},  
                                            dataType : 'json',
                                            success:function(data2)          //on recieve of reply
                                            { 
                                                $.each(data2.form_data2, function(key, value) {
                                       
                                                    $("#data_frekuensi_perawatan").html(value.counts);
                                       
                                        
                                       
                                                });

                                                $.each(data2.form_data5, function(key, value) {
                                        
                                                $("#data_no_member").html(value.no_member);
                                                $("#data_keterangan").html(value.keterangan);
                                                $("#data_tempat_lahir").html(value.tempat_lahir + ',' + value.tanggal_lahir);
                                                $("#data_nama").html(value.nama);
                                                $("#data_agama").html(value.nama1);
                                                $("#data_nama2").html(value.nama2);
                                                if(value.is_meninggal==0)
                                                {
                                                    $("#data_status").html('Hidup');
                                                }else{
                                                    $("#data_status").html('Meninggal');
                                                }
                                        

                                                $("#data_kodecabang").html(value.kodecabang);
                                                $("#data_rujukan").html(value.rujukan);
                                                $("#data_tgglrujukan").html(value.tgglrujukan);
                                                $("#data_nomorrujukan").html(value.nomorrujukan);
                                                $("#data_dokter_pengirim").html(value.dokter_pengirim);
                                         
                                                });

                                                $.each(data2.form_data6, function(key, value) {
                                       
                                                hasil +='<div class="form-group"><label class="control-label col-md-3">'+ value.nama +' :</label><div class="col-md-4"><label class="control-label">' + value.nomor +'</label></div></div>';
                                       
                                        
                                       
                                                });
                                                $("div#data_telepon").html(hasil);
                                                $.each(data2.form_data7, function(key, value) {
                                        
                                                $("#data_nama_subjek").html(value.nama);
                                                $("#data_alamat").html(value.alamat);
                                                $("#data_rt_rw").html(value.rt_rw);
                                       
                                         
                                                });


                                                $.each(data2.form_data8, function(key, value) {
                                                    $("#data_kelurahan").html(value.kelurahan);
                                                    $("#data_kecamatan").html(value.kecamatan);
                                                    $("#data_kota").html(value.kota);
                                       
                                         
                                                });

                                                $.each(data2.form_data9,function(key, value) {
                                                if(value.tipe==1){
                                                    hasil2='<label class="control-label">'+  value.nama  + '</label>';
                                                }
                                                if(value.tipe==2){
                                                    hasil3='<label class="control-label">'+  value.nama  + '</label>';
                                                }
                                            // hasil2+='<label class="control-label">'+ if(value.tipe==1) { value.nama }; + '</label>';
                                            // hasil3+='<label class="control-label">'+ if(value.tipe==2) { value.nama }; + '</label>';
                                                $("#data_penyakit_bawaan").html(hasil2);
                                                $("#data_penyakit_penyebab").html(hasil3);
                                            
                                       
                                         
                                                });
                                   
                                
                                            }
                   
                                        });
            xx=0;
            ll=0;
            zz=0;
            vv=0;
            $("#notifklaim").html('');
            $("#notifrujukan").html('');
            $("#notifbayar").html('');
            $("#notifupload").html('');

                                        oTableRujukan.api().ajax.url(baseAppUrl +  'listing_rujukan' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
                                        oTableklaim.api().ajax.url(baseAppUrl +  'listing_klaim' + '/' + $("#id_pasien").val()).load();
                                        oTable3.api().ajax.url(baseAppUrl +  'listing_upload' + '/' + $("#id_pasien").val()).load();
                                        oTablePembayaran.api().ajax.url(baseAppUrl +  'listing_pembayaran' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
                                    });
                                }
                   
                       });

    });
});

 
}

var setpasien2=function(btn)
{
    var f=0;
   // $.each($("#table_pilihjadwal"), function(){
       
        $(btn).click(function() {
         
                      var $anchor = $(this),
                       id    = $anchor.data('item');
 // alert(id);
               var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $noPasien       = $('input[name="no_member"]'),
                $IdPasien       = $('input[name="id_pasien"]'),
                $NamaPasien     = $('input[name="nama_pasien"]'),
                $AlamatPasien   = $('input[name="alamat_pasien"]'),
                $GenderPasien   = $('input[name="gender_pasien"]'),
                $TglLahirPasien = $('input[name="tgl_lahir_pasien"]'),
                $NomorPasien    = $('input[name="telepon_pasien"]'),
                $Upload         = $('a#upload');
                $itemCodeEl     = null,
                $itemNameEl     = null;        
 
                var hasil='';
                var hasil2;
                var hasil3;

                $.ajax
                        ({ 
         
                                type: 'POST',
                                url: mb.baseUrl() + 'reservasi/pendaftaran_tindakan/' +  "get_pasien_data",  
                                data:  {pasien_id:id},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    $.each(data, function(key, value) {
                                         if(value.url_photo!=null)
                                        {
                                            $("#imgpasien").html("<img src="+ mb.baseUrl() + value.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                                        }else{
                                            $("#imgpasien").html('');
                                        }

                                          if(value.url_photo!=null)
                                        {
                                            $("#imgpasien2").html("<img src="+ mb.baseUrl() + value.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                                        }else{
                                            $("#imgpasien2").html('');
                                        }
                                        $IdPasien.val(value.id);
                                        $noPasien.val(value.no_ktp);
                                        $NamaPasien.val(value.nama);
                                        $AlamatPasien.val(value.alamat);
                                        if(value.gender == 'P')
                                        {
                                            $gender = "Perempuan";
                                        }
                                        else
                                        {
                                            $gender = "Laki-laki";
                                        }

                                        $tempat = value.tempat_lahir;
                                        $tgl = value.tanggal_lahir;
                                        $ttl = $tempat + ", " + $tgl;
                                        $GenderPasien.val($gender);
                                        $TglLahirPasien.val($ttl);
                                        $NomorPasien.val(value.nomor);

                                        $('a#rujukan').removeClass("hidden");
                                        $('a#bayar').removeClass("hidden");
                                        $('a#klaim').removeClass("hidden");
                                        $('a#pasien').removeClass("hidden");
                                        $('a#upload').removeClass("hidden");
            
                                        $.ajax
                                        ({ 
         
                                            type: 'POST',
                                            url: baseAppUrl +  "get_data_pasien",  
                                            data:  {pasienid:id},  
                                            dataType : 'json',
                                            success:function(data2)          //on recieve of reply
                                            { 
                                                $.each(data2.form_data2, function(key, value) {
                                       
                                                    $("#data_frekuensi_perawatan").html(value.counts);
                                       
                                        
                                       
                                                });

                                                $.each(data2.form_data5, function(key, value) {
                                        
                                                $("#data_no_member").html(value.no_member);
                                                $("#data_keterangan").html(value.keterangan);
                                                $("#data_tempat_lahir").html(value.tempat_lahir + ',' + value.tanggal_lahir);
                                                $("#data_nama").html(value.nama);
                                                $("#data_agama").html(value.nama1);
                                                $("#data_nama2").html(value.nama2);
                                                if(value.is_meninggal==0)
                                                {
                                                    $("#data_status").html('Hidup');
                                                }else{
                                                    $("#data_status").html('Meninggal');
                                                }
                                        

                                                $("#data_kodecabang").html(value.kodecabang);
                                                $("#data_rujukan").html(value.rujukan);
                                                $("#data_tgglrujukan").html(value.tgglrujukan);
                                                $("#data_nomorrujukan").html(value.nomorrujukan);
                                                $("#data_dokter_pengirim").html(value.dokter_pengirim);
                                         
                                                });

                                                $.each(data2.form_data6, function(key, value) {
                                       
                                                hasil +='<div class="form-group"><label class="control-label col-md-3">'+ value.nama +' :</label><div class="col-md-4"><label class="control-label">' + value.nomor +'</label></div></div>';
                                       
                                        
                                       
                                                });
                                                $("div#data_telepon").html(hasil);
                                                $.each(data2.form_data7, function(key, value) {
                                        
                                                $("#data_nama_subjek").html(value.nama);
                                                $("#data_alamat").html(value.alamat);
                                                $("#data_rt_rw").html(value.rt_rw);
                                       
                                         
                                                });


                                                $.each(data2.form_data8, function(key, value) {
                                                    $("#data_kelurahan").html(value.kelurahan);
                                                    $("#data_kecamatan").html(value.kecamatan);
                                                    $("#data_kota").html(value.kota);
                                       
                                         
                                                });

                                                $.each(data2.form_data9,function(key, value) {
                                                if(value.tipe==1){
                                                    hasil2='<label class="control-label">'+  value.nama  + '</label>';
                                                }
                                                if(value.tipe==2){
                                                    hasil3='<label class="control-label">'+  value.nama  + '</label>';
                                                }
                                            // hasil2+='<label class="control-label">'+ if(value.tipe==1) { value.nama }; + '</label>';
                                            // hasil3+='<label class="control-label">'+ if(value.tipe==2) { value.nama }; + '</label>';
                                                $("#data_penyakit_bawaan").html(hasil2);
                                                $("#data_penyakit_penyebab").html(hasil3);
                                            
                                       
                                         
                                                });
                                   
                                
                                            }
                   
                                        });
     
 //alert('hii');
                                          xx=0;
                                            ll=0;
            zz=0;
            vv=0;
            $("#notifklaim").html('');
            $("#notifrujukan").html('');
            $("#notifbayar").html('');
            $("#notifupload").html('');
           
           
             oTablePembayaran.api().ajax.url(baseAppUrl +  'listing_pembayaran' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
                        oTableRujukan.api().ajax.url(baseAppUrl +  'listing_rujukan' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
                                        oTableklaim.api().ajax.url(baseAppUrl +  'listing_klaim' + '/' + $("#id_pasien").val()).load();
                                        oTable3.api().ajax.url(baseAppUrl +  'listing_upload' + '/' + $("#id_pasien").val()).load();
                                       
            
                                
                                    });
                                }
                   
                       });

          });
 //});
}

 

var setpasien3=function()
{
  
       $('#back').click(function() {
            set_table_jadwal_week();
       });
}
var detailbtn=function()
{

 $.each($("#table_pilihjadwal"), function(){
    var createtable='';
    var x=0;
    var y=0;
 
    
    $('.pilihtggl', this).click(function() {
                    var $anchor = $(this),
                    tggl    = $anchor.data('tggl');
                     tggl2    = $anchor.data('tggl2');
                      hari    = $anchor.data('hari');
                     
                    createtable+='<table class="table table-striped table-bordered table-hover" id="table_pilihjadwal2">';
                    createtable+= '<thead>';
                    createtable+= '<tr role="row" class="heading">';
                    createtable+= '<th class="text-center" rowspan="2">No.Bed</th>';
                    createtable+= '<th class="text-center" colspan="4"><table width="100%"><tr><td>'+hari+' ('+tggl2+')</td><td width="25px" align="right"><a class="btn btn-xs grey-cascade" id="back" ><i class="fa fa-search-plus"></i></a></td></tr></table></th>';
                        
                    createtable+= '</tr>';
                    createtable+= '<tr role="row" class="heading">';
                         
                    createtable+= '<th class="text-center">Pagi (07:00 - 12:00)</th>';
                    createtable+= '<th class="text-center">Siang  (13:00 - 18:00)</th>';
                    createtable+= '<th class="text-center">Sore  (18:00 - 23:00)</th>';
                    createtable+= '<th class="text-center">Malam  (23:00 - 03:00)</th>';
                    createtable+= '</tr>';
                    createtable+= '</thead>';
                    createtable+= '<tbody>';

                                    for(y=1;y<=33;y++){   
                                        createtable+='<tr>';
                                        createtable+='<td class="text-center">'+ y +'</td>';

                                        for(x=1;x<5;x++){
                                               
                                           createtable+='<td class="text-center"><div name="baris_'+y+'_'+ x +'"></div></td>';
                                                  
                                        }
                                         createtable+='</tr>';
                                    }

                    createtable+='</tbody></table>';
                     
                    $("#tab_jadwal").html(createtable);

                    
                    $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "check_jadwal",  
                                data:  {cabang_id:$("#cabangid").val(),date:tggl},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                  
                                    $.each(data, function(idx, key){

                                  var from = key.tanggal.substring(0,11).split("-");
                                 //  var f = new Date(from[2], from[1] - 1, from[0]);
                                     
                                    //alert(d);
                                         $('div[name$="baris_'+ key.no_urut_bed +'_'+ key.tipe +'"]').html('<div class="text-center"><table width="100%" border="0"><tr><td align="left">'+key.nama+'</td><td  width="20px" align="right"><a class="btn btn-xs green-haze selectjadwal" data-item="'+key.pasien_id+'" ><i class="fa fa-check"></i></a></td></tr></table></div>');
                                    });
                                     setpasien3();
                                    setpasien();
                                }
                    
                       });
                        
 


    });
});

}
var set_table_jadwal_week=function()
{
     var createtable='';
     //$("#tab_jadwal").html('');
     var z=0;
     

                                        $.ajax
                                        ({ 
         
                                            type: 'POST',
                                            url: baseAppUrl +  "tanggal_jadwal",  
                                            dataType : 'json',
                                            success:function(data)          //on recieve of reply
                                            { 

                                                createtable+='<table class="table table-striped table-bordered table-hover" id="table_pilihjadwal">';
                                                createtable+= '<thead>';
                                                createtable+= '<tr role="row" class="heading">';
                                                createtable+= '<th class="text-center" rowspan="2" style="vertical-align:middle">No.Bed</th>';
                                                createtable+= '<th class="text-center" colspan="4">Senin ('+ data[7] +')  <a class="btn btn-xs grey-cascade pilihtggl" data-tggl="'+data[0]+'" data-tggl2="'+data[7]+'" data-hari="Senin"><i class="fa fa-search-plus"></i></a></th>';
                                                createtable+= '<th class="text-center" colspan="4">Selasa ('+ data[8] +') <a class="btn btn-xs grey-cascade pilihtggl" data-tggl="'+data[1]+'" data-tggl2="'+data[8]+'" data-hari="Selasa"><i class="fa fa-search-plus"></i></a></th>';
                                                createtable+= '<th class="text-center" colspan="4">Rabu ('+ data[9] +') <a class="btn btn-xs grey-cascade pilihtggl" data-tggl="'+data[2]+'" data-tggl2="'+data[9]+'" data-hari="Rabu"><i class="fa fa-search-plus"></i></a></th>';
                                                createtable+= '<th class="text-center" colspan="4">Kamis ('+ data[10] +') <a class="btn btn-xs grey-cascade pilihtggl" data-tggl="'+data[3]+'" data-tggl2="'+data[10]+'" data-hari="Kamis"><i class="fa fa-search-plus"></i></a></th>';
                                                createtable+= '<th class="text-center" colspan="4">Jumat ('+ data[11] +') <a class="btn btn-xs grey-cascade pilihtggl" data-tggl="'+data[4]+'" data-tggl2="'+data[11]+'" data-hari="Jumat"><i class="fa fa-search-plus"></i></a></th>';
                                                createtable+= '<th class="text-center" colspan="4">Sabtu ('+ data[12] +') <a class="btn btn-xs grey-cascade pilihtggl" data-tggl="'+data[5]+'" data-tggl2="'+data[12]+'" data-hari="Sabtu"><i class="fa fa-search-plus"></i></a></th>';
                                                createtable+= '<th class="text-center" colspan="4">Minggu ('+ data[13] +') <a class="btn btn-xs grey-cascade pilihtggl" data-tggl="'+data[6]+'" data-tggl2="'+data[13]+'" data-hari="Minggu"><i class="fa fa-search-plus"></i></a></th>';    
                                                createtable+= '</tr>';

                                                createtable+= '<tr role="row" class="heading">';
                                                for(x=1;x<=7;x++)
                                                {
                                                    createtable+= '<th class="text-center">Pagi</th>';
                                                    createtable+= '<th class="text-center">Siang</th>';
                                                    createtable+= '<th class="text-center">Sore</th>';
                                                    createtable+= '<th class="text-center">Malam</th>';
                                                }
                   
                                                createtable+= '</tr>';
                                                createtable+= '</thead>';
                                                createtable+= '<tbody>';

                                                for(y=1;y<=33;y++)
                                                {   
                                                    createtable+='<tr>';
                                                    createtable+='<td class="text-center">'+ y +'</td>';
                                                    for(x=0;x<7;x++)
                                                    {
                                                        for(z=1;z<=4;z++)
                                                        {
                                                            createtable+='<td class="text-center"><div name="baris2_'+ y +'_'+ z +'_'+ data[x] + '" id="baris2_'+ y +'_'+ z +'_'+ data[x] + '"></div></td>';
                                                        } 
                                          
                                                    }
                                                }
                                                createtable+='</tr>';
                                                createtable+='</tbody></table>';
                                                $("#tab_jadwal").html(createtable);
                                                detailbtn();
 var q=0;
                                                $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "tanggal_jadwal",  
                                //data:  {cabang_id:$("#cabangid").val(),date:tggl},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                   
                                   for(j=0;j<7;j++)
                                   {
                                  
                                         $.ajax
                                            ({ 
         
                                                type: 'POST',
                                                url: baseAppUrl +  "check_jadwal",  
                                                data:  {cabang_id:$("#cabangid").val(),date:data[j]},  
                                                dataType : 'json',
                                              
                                                success:function(data2)          //on recieve of reply
                                                { 
                                       
                                                      $.each(data2, function(idx, key){
                                                            q++;
                                                            $('#baris2_'+ key.no_urut_bed +'_'+ key.tipe +'_'+ key.tanggal.substring(0,11)).html('<div class="text-center"><a class="btn btn-xs green-haze selectjadwal2" data-item="'+key.pasien_id+'" id="btn_'+key.pasien_id+'_'+q+'" ><i class="fa fa-check"></i></a></div>');
                                                              setpasien2("#btn_"+key.pasien_id+"_"+q);
                                                           //  alert("#btn_"+key.pasien_id+"_"+q);
                                                        });
                                    
                                                         //  setpasien2();
                                                    }
                    
                                                });
                                   }
                                   
                                    
                                }
                    
                       });
                                             

                                            }
                                        });

                                       
                                        

                      

                    
}
 
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'reservasi/pendaftaran_tindakan/';
          handleValidation();
        handleConfirmSave();
        handleConfirmSave3();
        handleConfirmSave4();
        handleAddDokumen();
        initform();
        handleSelectDokter();
        handlePilihPasien();
        handleSelectDokter2();
        handleAntrian();
        handleRujukan();
        handleKlaim();
        handleUpload();
        handlePembayaran();
        handleDTItems4();
        handlehistoribtn();
        handlehistoribtn2();
setpasien();
setpasien2();
set_table_jadwal_week();
detailbtn();
        uploadfile();
        uploadfile2();
        uploadfile3();
        handleDatePickers();
    };
 }(mb.app.cabang.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.add.init();
});