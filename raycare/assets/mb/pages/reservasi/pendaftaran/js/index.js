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
        $('.btn').tooltip();
        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);

        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_jadwal_tindakan',
            data     : {shift:$('select#shift').val()},
            dataType : 'json',
            beforeSend : function() {
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success  : function( results ) {

                $tableJadwal = $('tbody#table_pasien_belum_datang');
                $tableJadwal.html('');

                var html = '';
                if(results.success == true){
                    var rows = results.rows,
                        i = 0;

                    $.each(rows, function(idx, row){
                        i++;
                        html += '<tr">';
                        html += '<td class="text-left">' + i + '</td>'
                        html += '<td class="text-left">' + row.no_member + '</td>'
                        html += '<td class="text-left">' + row.nama + '</td>'
                        html += '<td class="text-left">' + row.keterangan + '</td>'
                        html += '</tr>';

                    });
                }
                if(results.success == false){
                    i = 0;
                    html += '<tr">';
                    html += '<td class="text-center" colspan="4">No data available in table</td>'
                    html += '</tr>';
                }

                $tableJadwal.html(html);
                $('th#total_belum_datang').text(i + ' Pasien');
                $('th#total_jadwal_seharusnya').text(results.jumlah_all + ' Pasien');

              
            },
            complete : function() {
                 Metronic.unblockUI();
            }
        });
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_data_pasien_non_tindakan',
            data     : {shift:$('select#shift').val()},
            dataType : 'json',
            success  : function( results ) {
                $tableNonJadwal = $('tbody#table_pasien_tanpa_jadwal');
                $tableNonJadwal.html('');

                var html = '';
                if(results.success == true){
                    var rows = results.rows,
                        i = 0;

                    $.each(rows, function(idx, row){
                        i++;
                        html += '<tr">';
                        html += '<td class="text-left">' + i + '</td>'
                        html += '<td class="text-left">' + row.no_member + '</td>'
                        html += '<td class="text-left">' + row.nama + '</td>'
                        html += '</tr>';

                    });
                    $('th#total_tindakan').text(i + ' Pasien');
                }
                if(results.success == false){
                    html += '<tr">';
                    html += '<td class="text-center" colspan="3">No data available in table</td>'
                    html += '</tr>';
                    $('th#total_tindakan').text(0 + ' Pasien');
                }

                $tableNonJadwal.html(html);
            }
        });

        $('a.list_jenis_kartu').click(function(){
                var id = $(this).data('id'),
                    text = $(this).data('text');

            $('button#button_jenis_kartu').html(text + ' <i class="fa fa-angle-down"></i>');
            $('input#tipe_kartu').val(id);
            $('input#tipe_kartu').attr('value',id);
            $('input#no_member').attr('placeholder','Isi '+text);
        });
    }

    var handleConfirmSave = function()
    {
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;
            var i = 0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i == 1)
                    {
                      $('#save', $form).click();
                    }
                }
            });
        });
    };

  
     var handleSelectDokter = function(){
        $("#noantrian").html('');
        $('input#warehouse_id').val($('select#poliklinik_sub').val());

        var $dokter_select = $('select#dokter');
        var $label_status = $('label#status');
        var $tab_jadwal = $('a#jadwal');

        $label_status.removeClass("hidden");
       
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_status_poli',
            data     : {id_poliklinik: $('select#poliklinik_sub').val(),cabang_id:11},
            dataType : 'json',
            beforeSend : function() {
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success  : function( results ) {

                if(results[1]==1){
                    $("#ant").show();
                     $("#ant2").show();
                    $tab_jadwal.removeClass("hidden");
                }else{
                     $tab_jadwal.addClass('hidden');
                     $("#ant").show();
                     $("#ant2").show();
                }

                if (results[0]) {

                    $('#button_status').attr('title', 'Buka');
                    $('#button_status').removeClass('default');
                    $('#button_status').removeClass('btn-warning');
                    $('#button_status').addClass('btn-success');
                    $('#icon_status').removeClass('fa-times');
                    $('#icon_status').addClass('fa-check');
                   
                } 
                else {

                    $('#button_status').attr('title', 'Tutup');
                    $('#button_status').removeClass('default');
                    $('#button_status').removeClass('btn-success');
                    $('#button_status').addClass('btn-warning');
                    $('#icon_status').removeClass('fa-check');
                    $('#icon_status').addClass('fa-times');
                     

                }
                
            },
            complete : function() {
                 Metronic.unblockUI();
            }
        });

        

        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_dokter',
            data     : {id_poliklinik: 1},
            dataType : 'json',
            beforeSend : function() {
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
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
            },
            complete : function() {
                 Metronic.unblockUI();
            }
        });

        $('select#poliklinik_sub').on('change', function()
        {
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
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success  : function( results ) {

                    if(results[1]==1){
                        $("#ant").show();
                         $("#ant2").show();
                        $tab_jadwal.removeClass("hidden");
                    }else{
                         $tab_jadwal.addClass('hidden');
                         $("#ant").show();
                         $("#ant2").show();
                    }

                    if (results[0]) {

                        $('#button_status').attr('title', 'Buka');
                        $('#button_status').removeClass('default');
                        $('#button_status').removeClass('btn-warning');
                        $('#button_status').addClass('btn-success');
                        $('#icon_status').removeClass('fa-times');
                        $('#icon_status').addClass('fa-check');
                       
                    } 
                    else {

                        $('#button_status').attr('title', 'Tutup');
                        $('#button_status').removeClass('default');
                        $('#button_status').removeClass('btn-success');
                        $('#button_status').addClass('btn-warning');
                        $('#icon_status').removeClass('fa-check');
                        $('#icon_status').addClass('fa-times');
                         

                    }
                    
                },
                complete : function() {
                     Metronic.unblockUI();
                }
            });

            
 
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_dokter',
                data     : {id_poliklinik: $(this).val()},
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
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
                },
                complete : function() {
                     Metronic.unblockUI();
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
                { 'name' : "pasien.id id",'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : "pasien.no_member no_member",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.nama nama",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.tanggal_lahir tanggal_lahir",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien_alamat.alamat alamat",'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : "pasien.nama nama",'visible' : true, 'searchable': false, 'orderable': false }
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
            'info'                  : false,
            'filter'                : false,
            'paginate'              : false,
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
                                               $("#notifrujukan").removeClass('hidden');
                                               $("#notifrujukan").text(xx);
                                               // $("#notifrujukan").html("<img src="+ mb.baseUrl() + "assets/global/img/notifblue.jpg style='width:12px;height:12' placeholder='1'>");

                                           },
                                                 
              "footerCallback": function( tfoot, data, start, end, display ) {
                    if(data.length==0){
                         $('a#rujukan').addClass("hidden");
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
                 beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
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
                  
                },
                complete : function() {
                     Metronic.unblockUI();
                }
            });

            
 
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_dokter',
                data     : {id_poliklinik: id},
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
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
                },
                complete : function() {
                     Metronic.unblockUI();
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
                data     : {poliklinik_id: $("#poliklinik_sub").val(),dokter_id:$("#dokter").val(), shift:$('select#shift').val()},
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success  : function( results ) {

                    $("#noantrian").text(results);
                    $("#noantrianval").val(results);
                    $('.antrian-title').html("Antrian " + results);
                  
                },
                complete : function() {
                     Metronic.unblockUI();
                }
            });
            oTable2.api().ajax.url(baseAppUrl +  'listing_antrian/' + $("#poliklinik_sub").val() + '/' + $("#dokter").val()+ '/' + $("select#shift").val()).load();
        });
    }

    var handleSelectShift = function(){
        $('select#shift').on('change', function(){

           $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_antrian',
                data     : {poliklinik_id: $("#poliklinik_sub").val(),dokter_id:$("#dokter").val(), shift:$('select#shift').val()},
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success  : function( results ) {

                    $("#noantrian").text(results);
                    $("#noantrianval").val(results);
                    $('.antrian-title').html("Antrian " + results);
                  
                },
                complete : function() {
                     Metronic.unblockUI();
                }
            });

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_jadwal_tindakan',
                data     : {shift:$('select#shift').val()},
                dataType : 'json',
               
                success  : function( results ) {

                    $tableJadwal = $('tbody#table_pasien_belum_datang');
                    $tableJadwal.html('');

                    var html = '';
                    if(results.success == true){
                        var rows = results.rows,
                            i = 0;

                        $.each(rows, function(idx, row){
                            i++;
                            html += '<tr">';
                            html += '<td class="text-left">' + i + '</td>'
                            html += '<td class="text-left">' + row.no_member + '</td>'
                            html += '<td class="text-left">' + row.nama + '</td>'
                            html += '<td class="text-left">' + row.keterangan + '</td>'
                            html += '</tr>';

                        });
                    }
                    if(results.success == false){
                        i = 0;
                        html += '<tr">';
                        html += '<td class="text-center" colspan="4">No data available in table</td>'
                        html += '</tr>';
                    }

                    $tableJadwal.html(html);
                    $('th#total_belum_datang').text(i + ' Pasien');
                    $('th#total_jadwal_seharusnya').text(results.jumlah_all + ' Pasien');
                }
            });

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_data_pasien_non_tindakan',
                data     : {shift:$('select#shift').val()},
                dataType : 'json',
                success  : function( results ) {
                    $tableNonJadwal = $('tbody#table_pasien_tanpa_jadwal');
                    $tableNonJadwal.html('');

                    var html = '';
                    if(results.success == true){
                        var rows = results.rows,
                            i = 0;

                        $.each(rows, function(idx, row){
                            i++;
                            html += '<tr">';
                            html += '<td class="text-left">' + i + '</td>'
                            html += '<td class="text-left">' + row.no_member + '</td>'
                            html += '<td class="text-left">' + row.nama + '</td>'
                            html += '</tr>';

                        });
                        $('th#total_tindakan').text(i + ' Pasien');
                    }
                    if(results.success == false){
                        html += '<tr">';
                        html += '<td class="text-center" colspan="3">No data available in table</td>'
                        html += '</tr>';
                        $('th#total_tindakan').text(0 + ' Pasien');
                    }

                    $tableNonJadwal.html(html);

                    
                }
            });
            oTable2.api().ajax.url(baseAppUrl +  'listing_antrian/' + $("#poliklinik_sub").val() + '/' + $("#dokter").val()+ '/' + $("select#shift").val()).load();
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
                $UmurPasien     = $('input[name="umur"]'),
                $NomorPasien    = $('input[name="telepon_pasien"]'),
                $Upload         = $('a#upload');
                $itemCodeEl     = null,
                $itemNameEl     = null;     

            var $SideNamaPasien      = $('div#side_nama_pasien'),
                $SideUmurPasien      = $('div#side_umur_pasien'),
                $SideTransaksiPasien = $('div#side_transaksi_pasien'),
                $SideTagihanPasien   = $('div#side_tagihan_pasien'),
                $SideUploadPasien    = $('div#side_upload_pasien'),
                $SideTentangPasien   = $('h4#side_tentang_pasien'),
                $SideKeteraganPasien = $('span#side_keterangan_pasien'),
                $SidePhotoPasien     = $('img#side_img_pasien'),
                $SideAlamat          = $('label.side_alamat'),
                $SideGender          = $('label.side_gender'),
                $SideTglReg          = $('label.side_tgl_registrasi'),
                $SideTtl             = $('label.side_ttl'),
                $SideTlp             = $('label.side_tlp')
            ;

            $('.pilih-pasien', $form).popover('hide');     
            $('.tentang_pasien').show();

            $SideTransaksiPasien.html($(this).data('transaksi'));
            $SideTagihanPasien.html($(this).data('tagihan'));

            // menghitung perbedaan tahun, utk kebutuhan umur
            var today = new Date();
            var birthDate = new Date($(this).data('item').tanggal_lahir);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            // tempat tanggal lahir
            $tempat = $(this).data('item').tempat_lahir;
            $tgl    = $(this).data('item').tanggal_lahir;
            $ttl    = $tempat + ", " +  $tgl;
            
            // alert($(this).data('item').url_photo);
            $SideNamaPasien.html($(this).data('item').nama);
            ($(this).data('item').url_photo != null) ? $SidePhotoPasien.attr("src", $(this).data('item').url_photo) : $SidePhotoPasien.attr("src", "");
            (age > 1) ? $SideUmurPasien.html(age + " Tahun") : $SideUmurPasien.html("Dibawah 1 Tahun");
            $SideTentangPasien.html("Tentang " + $(this).data('item').nama);
            $SideAlamat.text($(this).data('item').alamat + ' ' + $(this).data('item').kelurahan + ' ' + $(this).data('item').kecamatan + " " + $(this).data('item').kota);
            ($(this).data('item').gender == 'P') ? $SideGender.text("Perempuan") : $SideGender.text("Laki-laki");
            $SideTglReg.text($(this).data('item').tanggal_registrasi);
            $SideTtl.text($ttl);
            $SideTlp.text($(this).data('item').nomor);

            
            if($(this).data('item').url_photo!=null)
            {
                 $("#imgpasien").html("<img class='img-thumbnail' src="+$(this).data('item').url_photo + " style='max-width:100px;'>");
            }else{
                $("#imgpasien").html('');
            }

            if($(this).data('item').url_photo!=null)
            {
                $("#imgpasien2").html("<img src="+$(this).data('item').url_photo + " style='border: 1px solid #000; max-width:100px;'>");
            }else{
                $("#imgpasien2").html('');
            }   
           
            $IdPasien.val($(this).data('item').id);
            $('a#adddoc').attr('href',baseAppUrl+'add_dokumen/'+ $(this).data('item').id);
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
            if(parseFloat($(this).data('item').umur) < 1)
            {
                $UmurPasien.val('Dibawah 1 Tahun');
                
            }
            else
            {
                $UmurPasien.val(Math.ceil($(this).data('item').umur)+' Tahun');                                            
            }
            $NomorPasien.val($(this).data('item').nomor);
            $('a#add_pj').removeAttr('disabled');
            $('a#add_pj').attr('href', mb.baseUrl()+'reservasi/pendaftaran_tindakan/add_pj/'+$(this).data('item').id);
            // $Upload.removeClass("hidden");
            // $('a#rujukan').removeClass("hidden");
            // $('a#bayar').removeClass("hidden");
            // $('a#klaim').removeClass("hidden");
            // $('a#pasien').removeClass("hidden");
            // $('a#upload').removeClass("hidden");
            // alert($itemIdEl.val($(this).data('item').id));
            $("#id_pasien").val();

            var hasil='';
            var hasil2;
            var hasil3;
            $.ajax
            ({ 
                type: 'POST',
                url: baseAppUrl +  "get_penanggungjawab",  
                data:  {pasienid:$("#id_pasien").val()},  
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success : function(results)
                {
                    var rowtipe = results.tipe_pj_option;
                    var $selectTipe = $('select#tipe_pj_daftar');

                    $selectTipe.empty();
                    $.each(rowtipe, function(key, value) {
                        $selectTipe.append($("<option></option>")
                            .attr("value", key).text(value));
                    });

                    handleSelectPJ();
                    $('div#nama_pj').addClass('hidden');

                },
                complete : function() {
                     Metronic.unblockUI();
                }
   
            });
            $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "get_data_pasien",  
                                data:  {pasienid:$("#id_pasien").val()},  
                                dataType : 'json',
                                beforeSend : function() {
                                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                                },
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
                                   
                                
                                },
                                complete : function() {
                                     Metronic.unblockUI();
                                }
                   
                       });
            xx=0;
            ll=0;
            zz=0;
            vv=0;
            $("#notifklaim").addClass('hidden');
            $("#notifrujukan").addClass('hidden');
            $("#notifbayar").addClass('hidden');
            $("#notifupload").addClass('hidden');

            oTableRujukan.api().ajax.url(baseAppUrl +  'listing_rujukan' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
            oTableklaim.api().ajax.url(baseAppUrl +  'listing_klaim' + '/' + $("#id_pasien").val()).load();
            oTable3.api().ajax.url(baseAppUrl +  'listing_upload' + '/' + $("#id_pasien").val()).load();
            oTablePembayaran.api().ajax.url(baseAppUrl +  'listing_pembayaran' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
            // alert(oTableRujukan.data.size()); 
           
            e.preventDefault();
        });     
    };

    var handleSelectPJ = function(){
        $('select#tipe_pj_daftar').on('change', function(){
            var id = $(this).val();

            if(id != 1)
            {
                $.ajax
                ({ 
                    type: 'POST',
                    url: baseAppUrl +  "get_data_pj_pasien",  
                    data:  {pasienid:$("#id_pasien").val(), tipe_pj:id},  
                    dataType : 'json',
                    beforeSend : function() {
                        Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                    },
                    success:function(results)          //on recieve of reply
                    {
                        var namapj = results.nama_pj_option;
                        var $selectNamaPJ = $('select#nama_pj');

                        $('div#nama_pj').removeClass('hidden');
                        $selectNamaPJ.empty();
                        $.each(namapj, function(key, value) {
                            $selectNamaPJ.append($("<option></option>")
                                .attr("value", value.id).text(value.nama+' ['+value.no_ktp+']'));
                        });

                        $('div#div_pj').removeClass('hidden');
                        $('input#no_ktp').val(namapj[0]['no_ktp']);

                        $.ajax
                        ({ 
                            type: 'POST',
                            url: baseAppUrl +  "get_alamat_telepon_pj",  
                            data:  {pasien_hub_id:namapj[0]['id']},  
                            dataType : 'json',
                            success:function(results)          //on recieve of reply
                            {
                                if(results.success == true)
                                {
                                    var alamat = results.hub_alamat;
                                    var telepon = results.hub_telepon;
                                    var rt_rw = alamat.rt_rw;
                                    var rtrw = rt_rw.split("_");

                                    $('textarea#alamat').val(alamat.alamat+' RT '+rtrw[0]+' / '+rtrw[1] +' '+results.form_kel_alamat+' '+results.form_kec_alamat+' '+results.form_kota_alamat+' '+alamat.kode_pos);
                                    $('input#no_telepon').val(telepon.nomor);
                                }
                            }
                        });

                        handleSelectNamaPJ();

                    },
                    complete : function() {
                        Metronic.unblockUI();
                    }

                });
            }
            else
            {
                $('div#nama_pj').addClass('hidden');
                $('div#div_pj').addClass('hidden');
            }
        });
    }

    var handleSelectNamaPJ = function() {
        $('select#nama_pj').on('change', function(){
            var pj_id = $(this).val();

            $.ajax
            ({ 
                type: 'POST',
                url: baseAppUrl +  "get_alamat_telepon_pj",  
                data:  {pasien_hub_id:pj_id},  
                dataType : 'json',
                success:function(results)          //on recieve of reply
                {
                    if(results.success == true)
                    {
                        var alamat = results.hub_alamat;
                        var telepon = results.hub_telepon;
                        var rt_rw = alamat.rt_rw;
                        var rtrw = rt_rw.split("_");

                        $('textarea#alamat').val(alamat.alamat+' RT '+rtrw[0]+' / '+rtrw[1] +' '+results.form_kel_alamat+' '+results.form_kec_alamat+' '+results.form_kota_alamat+' '+alamat.kode_pos);
                        $('input#no_telepon').val(telepon.nomor);
                    }
                }
            });
        });
    }
    var handleRefreshUpload = function()
    {
        $('a#refresh_upload').click(function(){
            var pasien_id = $("#id_pasien").val();
             oTable3.api().ajax.url(baseAppUrl +  'listing_upload' + '/' + pasien_id).load();
        });
    }
    

    var handleKlaim = function(){
        var x=0;
        oTableklaim=$("#table_klaim1").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'paging'                : false,
            'filter'                : false,
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
            "createdRow"            : function( row, data, dataIndex ) {
                if(data[5]=='Tidak Tersedia'){
                     zz++;
                     $("#notifklaim").removeClass('hidden');
                     $("#notifklaim").text('!');
                        
                }                                  
            },
            "footerCallback": function( tfoot, data, start, end, display ) 
            {
                      
            }          
        });
        $('#table_klaim1').on( 'order.dt', function () {
            // This will show: "Ordering on column 1 (asc)", for example
            zz=0;
        });
        $('#table_klaim1').on('draw.dt', function() {
            $radioSelect = $('input[type=radio].pilih_klaim', this);
            $radioSelect.on('click', function() {
                var id = $(this).data('id');
                if($(this).prop('checked') == true)
                {
                    $.ajax
                    ({ 
                        type: 'POST',
                        url: baseAppUrl +  "get_jumlah_tindakan",  
                        data:  {penjamin_id:id, pasien_id:$("#id_pasien").val()},  
                        dataType : 'json',
                        success:function(data)          //on recieve of reply
                        { 
                           if(data.success == false)
                           {
                                if(data.flag == 'nothing')
                                {
                                    mb.showMessage('error', data.msg, 'Informasi');
                                    $('a#adddoc').click();
                                    $('a#confirm_save').attr('disabled','disabled');
                                }
                                if(data.flag == 'expire')
                                {
                                    mb.showMessage('error', data.msg, 'Informasi');
                                    $('a#upload').click();
                                    $('div.tab_2_5').addClass('active');
                                    $('a#confirm_save').attr('disabled','disabled');
                                }
                           }
                           else
                           {
                                $('a#confirm_save').removeAttr('disabled');
                           }
                        }
                    });
                }
            });
        });
     
    }; 

    var handleUpload = function(){
        var vv=0,
            dok_id = '';
        var found = false;
        oTable3=$("#table_cabang4").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_upload',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'paging'                : false,
            'filter'                : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'pagingType' : 'full_numbers',
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },
            ],
            "createdRow" : function( row, data, dataIndex ) {
                 
                if(data[5]=='Kadaluarsa' || data[5]=='Peringatan'){
                    vv++;
                    dok_id = dok_id+data[6]+'_';
                    $("#notifupload").text(vv);
                    $("#notifupload").removeClass('hidden');
                    $('#side_upload_pasien').text(vv);
                }
                  
            },
            "footerCallback": function( tfoot, data, start, end, display ) {
                if(data.length==0){
                     $('a#upload').removeClass("hidden");

                }else{
                    console.log(dok_id);
                    if(dok_id != '')
                    {
                        $('a#button_warning').attr('href',baseAppUrl+'modal_warning/'+$("#id_pasien").val()+'/'+dok_id);
                        $('a#button_warning').click();
                    }
                    $('a#upload').removeClass("hidden");
                    $('a#confirm_save').removeAttr('disabled');
                }     
            }   
        });

        $('#table_cabang4').on( 'order.dt', function () {
            // This will show: "Ordering on column 1 (asc)", for example
            vv=0;
            dok_id = '';
            $("#notifupload").text(vv);
            $("#notifupload").addClass('hidden');
            $('#side_upload_pasien').text(vv);
        });

        $("#table_cabang4").on('draw.dt', function (){
              
            $('a[name="viewpic[]"]', this).click(function(){

                var $anchor = $(this),
                    id    = $anchor.data('id');
                    
                    $('#gambar', $("#modalpic")).html("<img src="+ mb.baseUrl() + id + " style='border: 1px solid #000; max-width:500px; max-height:500px;'>");       
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
                     
                                            if(data2[5]==0){

                                                  ll++;   
                                                    $("#notifbayar").text(ll);
                                                    $("#notifbayar").removeClass('hidden');
                                                  // $("#notifbayar").html(ll);
                                            }
                                              
                                               

                                           },
              "footerCallback": function( tfoot, data, start, end, display ) {
                    if(data.length==0){
                         $('a#bayar').addClass("hidden");
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
                $UmurPasien     = $('input[name="umur"]'),
                $NomorPasien    = $('input[name="telepon_pasien"]'),
                $Upload         = $('a#upload');
                $itemCodeEl     = null,
                $itemNameEl     = null;      

                var $SideNamaPasien      = $('div#side_nama_pasien'),
                    $SideUmurPasien      = $('div#side_umur_pasien'),
                    $SideTransaksiPasien = $('div#side_transaksi_pasien'),
                    $SideTagihanPasien   = $('div#side_tagihan_pasien'),
                    $SideUploadPasien    = $('div#side_upload_pasien'),
                    $SideTentangPasien   = $('h4#side_tentang_pasien'),
                    $SideKeteraganPasien = $('span#side_keterangan_pasien'),
                    $SidePhotoPasien     = $('img#side_img_pasien'),
                    $SideAlamat          = $('label.side_alamat'),
                    $SideGender          = $('label.side_gender'),
                    $SideTglReg          = $('label.side_tgl_registrasi'),
                    $SideTtl             = $('label.side_ttl'),
                    $SideTlp             = $('label.side_tlp')
                ;     

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


                                        $('.tentang_pasien').show();

                                        $SideTransaksiPasien.html(value.count_transaksi);
                                        $SideTagihanPasien.html(value.count_tagihan);

                                        // menghitung perbedaan tahun, utk kebutuhan umur
                                        var today = new Date();
                                        var birthDate = new Date(value.tanggal_lahir);
                                        var age = today.getFullYear() - birthDate.getFullYear();
                                        var m = today.getMonth() - birthDate.getMonth();
                                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                                            age--;
                                        }

                                        // tempat tanggal lahir
                                        $tempat = value.tempat_lahir;
                                        $tgl    = value.tanggal_lahir;
                                        $ttl    = $tempat + ", " +  $tgl;
                                        
                                        $SideNamaPasien.html(value.nama);
                                        (value.url_photo != null) ? $SidePhotoPasien.attr("src", value.url_photo) : $SidePhotoPasien.attr("src", "");
                                        (age > 1) ? $SideUmurPasien.html(age + " Tahun") : $SideUmurPasien.html("Dibawah 1 Tahun");
                                        $SideTentangPasien.html("Tentang " + value.nama);
                                        $SideAlamat.text(value.alamat + ' ' + value.kelurahan + ' ' + value.kecamatan + " " + value.kota);
                                        (value.gender == 'P') ? $SideGender.text("Perempuan") : $SideGender.text("Laki-laki");
                                        $SideTglReg.text(value.tanggal_registrasi);
                                        $SideTtl.text($ttl);
                                        $SideTlp.text(value.nomor);



                                        if(value.url_photo!=null)
                                        {
                                            $("#imgpasien").html("<img src="+ value.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                                        }else{
                                            $("#imgpasien").html('');
                                        }

                                         if(value.url_photo!=null)
                                        {
                                            $("#imgpasien2").html("<img src="+ value.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                                        }else{
                                            $("#imgpasien2").html('');
                                        }
                                        $IdPasien.val(value.id);
                                        $('a#adddoc').attr('href',baseAppUrl+'add_dokumen/'+value.id);
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

                                        if(parseFloat(value.umur) < 1)
                                        {
                                            $UmurPasien.val('Dibawah 1 Tahun');
                                            
                                        }
                                        else
                                        {
                                            $UmurPasien.val(Math.ceil(value.umur)+' Tahun');                                            
                                        }
                                        $NomorPasien.val(value.nomor);
                                        $('a#add_pj').removeAttr('disabled');
                                        $('a#add_pj').attr('href', mb.baseUrl()+'reservasi/pendaftaran_tindakan/add_pj/'+value.id);

                                        // $('a#rujukan').removeClass("hidden");
                                        // $('a#bayar').removeClass("hidden");
                                        // $('a#klaim').removeClass("hidden");
                                        // $('a#pasien').removeClass("hidden");
                                        // $('a#upload').removeClass("hidden");
                                        $('a#jadwal').parent().removeClass('active');
                                        $('a#daftar').click();
                                        $('a#daftar').parent().addClass('active');
                                        
                                        $.ajax
                                        ({ 
                                            type: 'POST',
                                            url: baseAppUrl +  "get_penanggungjawab",  
                                            data:  {pasienid:id},  
                                            dataType : 'json',
                                            beforeSend : function() {
                                                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                                            },
                                            success : function(results)
                                            {
                                                var rowtipe = results.tipe_pj_option;
                                                var $selectTipe = $('select#tipe_pj_daftar');

                                                $selectTipe.empty();
                                                $.each(rowtipe, function(key, value) {
                                                    $selectTipe.append($("<option></option>")
                                                        .attr("value", key).text(value));
                                                });

                                                handleSelectPJ();
                                                $('div#nama_pj').addClass('hidden');

                                            },
                                            complete : function() {
                                                 Metronic.unblockUI();
                                            }
                               
                                        });
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
                                        $("#notifklaim").addClass('hidden');
                                        $("#notifrujukan").addClass('hidden');
                                        $("#notifbayar").addClass('hidden');
                                        $("#notifupload").addClass('hidden');

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
                        $UmurPasien = $('input[name="umur"]'),
                        $NomorPasien    = $('input[name="telepon_pasien"]'),
                        $Upload         = $('a#upload');
                        $itemCodeEl     = null,
                        $itemNameEl     = null;   

                        var $SideNamaPasien      = $('div#side_nama_pasien'),
                            $SideUmurPasien      = $('div#side_umur_pasien'),
                            $SideTransaksiPasien = $('div#side_transaksi_pasien'),
                            $SideTagihanPasien   = $('div#side_tagihan_pasien'),
                            $SideUploadPasien    = $('div#side_upload_pasien'),
                            $SideTentangPasien   = $('h4#side_tentang_pasien'),
                            $SideKeteraganPasien = $('span#side_keterangan_pasien'),
                            $SidePhotoPasien     = $('img#side_img_pasien'),
                            $SideAlamat          = $('label.side_alamat'),
                            $SideGender          = $('label.side_gender'),
                            $SideTglReg          = $('label.side_tgl_registrasi'),
                            $SideTtl             = $('label.side_ttl'),
                            $SideTlp             = $('label.side_tlp')
                        ;     
         
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

                                        $('.tentang_pasien').show();

                                        $SideTransaksiPasien.html(value.count_transaksi);
                                        $SideTagihanPasien.html(value.count_tagihan);

                                        // menghitung perbedaan tahun, utk kebutuhan umur
                                        var today = new Date();
                                        var birthDate = new Date(value.tanggal_lahir);
                                        var age = today.getFullYear() - birthDate.getFullYear();
                                        var m = today.getMonth() - birthDate.getMonth();
                                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                                            age--;
                                        }

                                        // tempat tanggal lahir
                                        $tempat = value.tempat_lahir;
                                        $tgl    = value.tanggal_lahir;
                                        $ttl    = $tempat + ", " +  $tgl;
                                        
                                        $SideNamaPasien.html(value.nama);
                                        (value.url_photo != null) ? $SidePhotoPasien.attr("src", value.url_photo) : $SidePhotoPasien.attr("src", "");
                                        (age > 1) ? $SideUmurPasien.html(age + " Tahun") : $SideUmurPasien.html("Dibawah 1 Tahun");
                                        $SideTentangPasien.html("Tentang " + value.nama);
                                        $SideAlamat.text(value.alamat + ' ' + value.kelurahan + ' ' + value.kecamatan + " " + value.kota);
                                        (value.gender == 'P') ? $SideGender.text("Perempuan") : $SideGender.text("Laki-laki");
                                        $SideTglReg.text(value.tanggal_registrasi);
                                        $SideTtl.text($ttl);
                                        $SideTlp.text(value.nomor);

                                         if(value.url_photo!=null)
                                        {
                                            $("#imgpasien").html("<img src="+ value.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                                        }else{
                                            $("#imgpasien").html('');
                                        }

                                          if(value.url_photo!=null)
                                        {
                                            $("#imgpasien2").html("<img src="+ value.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                                        }else{
                                            $("#imgpasien2").html('');
                                        }
                                        $IdPasien.val(value.id);
                                        $('a#adddoc').attr('href',baseAppUrl+'add_dokumen/'+value.id);
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
                                        if(parseFloat(value.umur) < 1)
                                        {
                                            $UmurPasien.val('Dibawah 1 Tahun');
                                            
                                        }
                                        else
                                        {
                                            $UmurPasien.val(Math.ceil(value.umur)+' Tahun');                                            
                                        }
                                        $NomorPasien.val(value.nomor);

                                        // $('a#rujukan').removeClass("hidden");
                                        // $('a#bayar').removeClass("hidden");
                                        // $('a#klaim').removeClass("hidden");
                                        // $('a#pasien').removeClass("hidden");
                                        // $('a#upload').removeClass("hidden");
                                        $('a#jadwal').parent().removeClass('active');
                                        $('a#daftar').click();
                                        $('a#daftar').parent().addClass('active');
                                        
                                        $.ajax
                                        ({ 
                                            type: 'POST',
                                            url: baseAppUrl +  "get_penanggungjawab",  
                                            data:  {pasienid:id},  
                                            dataType : 'json',
                                            beforeSend : function() {
                                                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                                            },
                                            success : function(results)
                                            {
                                                var rowtipe = results.tipe_pj_option;
                                                var $selectTipe = $('select#tipe_pj_daftar');

                                                $selectTipe.empty();
                                                $.each(rowtipe, function(key, value) {
                                                    $selectTipe.append($("<option></option>")
                                                        .attr("value", key).text(value));
                                                });

                                                handleSelectPJ();
                                                $('div#nama_pj').addClass('hidden');

                                            },
                                            complete : function() {
                                                 Metronic.unblockUI();
                                            }
                               
                                        });
                                        $.ajax
                                        ({ 
         
                                            type: 'POST',
                                            url: baseAppUrl +  "get_data_pasien",  
                                            data:  {pasienid:id},  
                                            dataType : 'json',
                                            success:function(data2)          //on recieve of reply
                                            { 
                                                $.each(data2.form_data2, function(key, value){                                  
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
                                            
                                                    $("#data_penyakit_bawaan").html(hasil2);
                                                    $("#data_penyakit_penyebab").html(hasil3);                                                                                     
                                                });
                                   
                                
                                            }
                   
                                            });
     
                                        xx=0;
                                        ll=0;
                                        zz=0;
                                        vv=0;
                                        $("#notifklaim").addClass('hidden');
                                        $("#notifrujukan").addClass('hidden');
                                        $("#notifbayar").addClass('hidden');
                                        $("#notifupload").addClass('hidden');
           
           
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
                    createtable+= '<tr role="row">';
                    createtable+= '<th class="text-center" rowspan="2" style ="font-size: 12px;">No.Bed</th>';
                    createtable+= '<th class="text-center" colspan="4" style ="font-size: 12px;" ><table width="100%"><tr><td>'+hari+' ('+tggl2+')</td><td width="25px" align="right"><a class="btn" id="back" ><i class="fa fa-search-minus"></i></a></td></tr></table></th>';
                        
                    createtable+= '</tr>';
                    createtable+= '<tr role="row">';
                         
                    createtable+= '<th class="text-center" style ="font-size: 12px;">Pagi (07:00 - 12:00)</th>';
                    createtable+= '<th class="text-center" style ="font-size: 12px;">Siang  (13:00 - 18:00)</th>';
                    createtable+= '<th class="text-center" style ="font-size: 12px;">Sore  (18:00 - 23:00)</th>';
                    createtable+= '<th class="text-center" style ="font-size: 12px;">Malam  (23:00 - 03:00)</th>';
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
                                $('div[name$="baris_'+ key.no_urut_bed +'_'+ key.tipe +'"]').html('<div class="text-center"><table width="100%" border="0"><tr><td align="left">'+key.nama+'</td><td  width="20px" align="right"><a class="btn selectjadwal" data-item="'+key.pasien_id+'" ><i class="glyphicon glyphicon-ok-sign font-green-haze"></i></a></td></tr></table></div>');
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
        createtable+= '<tr role="row">';
        createtable+= '<th class="text-center" rowspan="2" style="font-size: 12px; vertical-align:middle">No.Bed</th>';
        createtable+= '<th class="text-center" colspan="4" style ="font-size: 12px;">Senin ('+ data[7] +')  <a class="btn pilihtggl" data-tggl="'+data[0]+'" data-tggl2="'+data[7]+'" data-hari="Senin"><i class="fa fa-search-plus"></i></a></th>';
        createtable+= '<th class="text-center" colspan="4" style ="font-size: 12px;">Selasa ('+ data[8] +') <a class="btn pilihtggl" data-tggl="'+data[1]+'" data-tggl2="'+data[8]+'" data-hari="Selasa"><i class="fa fa-search-plus"></i></a></th>';
        createtable+= '<th class="text-center" colspan="4" style ="font-size: 12px;">Rabu ('+ data[9] +') <a class="btn pilihtggl" data-tggl="'+data[2]+'" data-tggl2="'+data[9]+'" data-hari="Rabu"><i class="fa fa-search-plus"></i></a></th>';
        createtable+= '<th class="text-center" colspan="4" style ="font-size: 12px;">Kamis ('+ data[10] +') <a class="btn pilihtggl" data-tggl="'+data[3]+'" data-tggl2="'+data[10]+'" data-hari="Kamis"><i class="fa fa-search-plus"></i></a></th>';
        createtable+= '<th class="text-center" colspan="4" style ="font-size: 12px;">Jumat ('+ data[11] +') <a class="btn pilihtggl" data-tggl="'+data[4]+'" data-tggl2="'+data[11]+'" data-hari="Jumat"><i class="fa fa-search-plus"></i></a></th>';
        createtable+= '<th class="text-center" colspan="4" style ="font-size: 12px;">Sabtu ('+ data[12] +') <a class="btn pilihtggl" data-tggl="'+data[5]+'" data-tggl2="'+data[12]+'" data-hari="Sabtu"><i class="fa fa-search-plus"></i></a></th>';
        createtable+= '<th class="text-center" colspan="4" style ="font-size: 12px;">Minggu ('+ data[13] +') <a class="btn pilihtggl" data-tggl="'+data[6]+'" data-tggl2="'+data[13]+'" data-hari="Minggu"><i class="fa fa-search-plus"></i></a></th>';    
        createtable+= '</tr>';

        createtable+= '<tr role="row">';
        for(x=1;x<=7;x++)
        {
            createtable+= '<th class="text-center" style ="font-size: 12px;">Pagi</th>';
            createtable+= '<th class="text-center" style ="font-size: 12px;">Siang</th>';
            createtable+= '<th class="text-center" style ="font-size: 12px;">Sore</th>';
            createtable+= '<th class="text-center" style ="font-size: 12px;">Malam</th>';
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
                                $('#baris2_'+ key.no_urut_bed +'_'+ key.tipe +'_'+ key.tanggal.substring(0,11)).html('<div class="text-center"><a class="btn selectjadwal2" title="'+key.nama+'" data-item="'+key.pasien_id+'" id="btn_'+key.pasien_id+'_'+q+'" ><i class="glyphicon glyphicon-ok-sign font-green-haze"></i></a></div>');
                                  setpasien2("#btn_"+key.pasien_id+"_"+q);
                                // alert("#btn_"+key.pasien_id+"_"+q);
                            });

                            $('a.btn').tooltip();
                        }

                    });
                }    
            }
        });                                             
    }
    });                                    
                                        
}
    var handleJwertyEnterRent = function($nopasien){
        jwerty.key('enter', function() {
            
            var NomorPasien = $nopasien.val();

            searchPasienByNomorAndFill(NomorPasien);

            // cegah ENTER supaya tidak men-trigger form submit
            return false;

        }, this, $nopasien );
    }

    var searchPasienByNomorAndFill = function(NomorPasien)
    {
        var tipe = $('input#tipe_kartu').val();

        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'search_pasien_by_nomor',
            data     : {no_pasien:NomorPasien, tipe:tipe},   
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success : function(result){
                if(result.success === true)
                {
                    $noPasien       = $('input[name="no_member"]'),
                    $IdPasien       = $('input[name="id_pasien"]'),
                    $NamaPasien     = $('input[name="nama_pasien"]'),
                    $AlamatPasien   = $('input[name="alamat_pasien"]'),
                    $GenderPasien   = $('input[name="gender_pasien"]'),
                    $TglLahirPasien = $('input[name="tgl_lahir_pasien"]'),
                    $NomorPasien    = $('input[name="telepon_pasien"]'),
                    $UmurPasien     = $('input[name="umur"]'),
                    $Upload         = $('a#upload');

                    var 
                        $SideNamaPasien      = $('div#side_nama_pasien'),
                        $SideUmurPasien      = $('div#side_umur_pasien'),
                        $SideTransaksiPasien = $('div#side_transaksi_pasien'),
                        $SideTagihanPasien   = $('div#side_tagihan_pasien'),
                        $SideUploadPasien    = $('div#side_upload_pasien'),
                        $SideTentangPasien   = $('h4#side_tentang_pasien'),
                        $SideKeteraganPasien = $('span#side_keterangan_pasien'),
                        $SidePhotoPasien     = $('img#side_img_pasien'),
                        $SideAlamat          = $('label.side_alamat'),
                        $SideGender          = $('label.side_gender'),
                        $SideTglReg          = $('label.side_tgl_registrasi'),
                        $SideTtl             = $('label.side_ttl'),
                        $SideTlp             = $('label.side_tlp')
                    ;

                     var data = result.rows;

                    $('.tentang_pasien').show();

                    $SideTransaksiPasien.html(data.count_transaksi);
                    $SideTagihanPasien.html(data.count_tagihan);

                    // menghitung perbedaan tahun, utk kebutuhan umur
                    var today = new Date();
                    var birthDate = new Date(data.tanggal_lahir);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    var m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    // tempat tanggal lahir
                    $tempat = data.tempat_lahir;
                    $tgl    = data.tanggal_lahir;
                    $ttl    = $tempat + ", " +  $tgl;
                    
                    $SideNamaPasien.html(data.nama);
                    (data.url_photo != null) ? $SidePhotoPasien.attr("src", data.url_photo) : $SidePhotoPasien.attr("src", "");
                    (age > 1) ? $SideUmurPasien.html(age + " Tahun") : $SideUmurPasien.html("Dibawah 1 Tahun");
                    $SideTentangPasien.html("Tentang " + data.nama);
                    $SideAlamat.text(data.alamat + ' Kec. ' + data.kecamatan + ' Kel. ' + data.kelurahan + " Kota " + data.kota);
                    (data.gender == 'P') ? $SideGender.text("Perempuan") : $SideGender.text("Laki-laki");
                    $SideTglReg.text(data.tanggal_registrasi);
                    $SideTtl.text($ttl);
                    $SideTlp.text(data.nomor);




                    if(data.url_photo!=null)
                    {
                         $("#imgpasien").html("<img src="+ data.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                    }else{
                        $("#imgpasien").html('');
                    }
                    if(data.url_photo!=null)
                    {
                        $("#imgpasien2").html("<img src="+ data.url_photo + " style='border: 1px solid #000; max-width:100px;'>");
                    }else{
                        $("#imgpasien2").html('');
                    }
                   
                    $IdPasien.val(data.id);
                    $('a#adddoc').attr('href',baseAppUrl+'add_dokumen/'+data.id);
                    if(tipe == 1){
                        $noPasien.val(data.no_ktp);
                    }if(tipe == 2){
                        $noPasien.val(data.no_ktp_real);
                    }if(tipe == 3){
                        $noPasien.val(data.no_bpjs);
                    }
                    $NamaPasien.val(data.nama);
                    $AlamatPasien.val(data.alamat);
                    if(parseFloat(data.umur) < 1)
                    {
                        $UmurPasien.val('Dibawah 1 Tahun');
                        
                    }
                    else
                    {
                        $UmurPasien.val(Math.ceil(data.umur)+' Tahun');                                            
                    }
                    if(data.gender == 'P')
                    {
                        $gender = "Perempuan";
                    }
                    else
                    {
                        $gender = "Laki-laki";
                    }
                    
                    $tempat = data.tempat_lahir;
                    $tgl = data.tanggal_lahir;
                   // var tgllahir = new Date($tgl);
                    $ttl = $tempat + ", " +  $tgl;
                    $GenderPasien.val($gender);
                    $TglLahirPasien.val($ttl);
                    $NomorPasien.val(data.nomor);
                    $('a#add_pj').removeAttr('disabled');
                    $('a#add_pj').attr('href', mb.baseUrl()+'reservasi/pendaftaran_tindakan/add_pj/'+data.id);
                    // $Upload.removeClass("hidden");
                    // $('a#rujukan').removeClass("hidden");
                    // $('a#bayar').removeClass("hidden");
                    // $('a#klaim').removeClass("hidden");
                    // $('a#pasien').removeClass("hidden");
                    // $('a#upload').removeClass("hidden");
                    // alert($itemIdEl.val($(this).data('item').id));
                    $("#id_pasien").val();

                    var hasil='';
                    var hasil2;
                    var hasil3;
                    $.ajax
                    ({ 
                        type: 'POST',
                        url: baseAppUrl +  "get_penanggungjawab",  
                        data:  {pasienid:data.id},  
                        dataType : 'json',
                        beforeSend : function() {
                            Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                        },
                        success : function(results)
                        {
                            var rowtipe = results.tipe_pj_option;
                            var $selectTipe = $('select#tipe_pj_daftar');

                            $selectTipe.empty();
                            $.each(rowtipe, function(key, value) {
                                $selectTipe.append($("<option></option>")
                                    .attr("value", key).text(value));
                            });

                            handleSelectPJ();
                            $('div#nama_pj').addClass('hidden');

                        },
                        complete : function() {
                             Metronic.unblockUI();
                        }
           
                    });
                    $.ajax
                    ({ 
                        type     : 'POST',
                        url      : baseAppUrl +  "get_data_pasien",  
                        data     :  {pasienid:data.id},  
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
                                   
                                
                        },
                                         
                    });
                    xx=0;
                    ll=0;
                    zz=0;
                    vv=0;
                    $("#notifklaim").addClass('hidden');
                    $("#notifrujukan").addClass('hidden');
                    $("#notifbayar").addClass('hidden');
                    $("#notifupload").addClass('hidden');

                    oTableRujukan.api().ajax.url(baseAppUrl +  'listing_rujukan' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
                    oTableklaim.api().ajax.url(baseAppUrl +  'listing_klaim' + '/' + $("#id_pasien").val()).load();
                    oTable3.api().ajax.url(baseAppUrl +  'listing_upload' + '/' + $("#id_pasien").val()).load();
                    oTablePembayaran.api().ajax.url(baseAppUrl +  'listing_pembayaran' + '/' + $("#id_pasien").val() + '/' + $("#cabangid").val()).load();
                    // alert(oTableRujukan.data.size());                     
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

   
 
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'reservasi/pendaftaran_tindakan/';
        handleJwertyEnterRent($('input#no_member'));
        handleValidation();
        handleConfirmSave();
        handleConfirmSave3();
        handleConfirmSave4();
        initform();
        handleRefreshUpload();
        handleSelectDokter();
        handlePilihPasien();
        handleSelectDokter2();
        handleSelectShift();
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
        // uploadfile();
        // uploadfile2();
        // uploadfile3();
        handleDatePickers();
    };
 }(mb.app.cabang.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.add.init();
});