mb.app.cabang = mb.app.cabang || {};
(function(o){

    var 
        baseAppUrl              = '',
		$form                 = $('#form_index_jadwal'),
		$tablePasien          = $('#table_pasien'),
		$tablePasienMove      = $('#table_pasien_move');
		$popoverPasienContent = $('#popover_pasien_content'), 
		$lastPopoverItem      = null,
		$tablePilihPasien     = $('#table_pilih_pasien'),
        i = 0,
        j = 0;



    var initform = function()
    {
    	// alert("a");
        var $btnSearchPasien  = $('.pilih-pasien');
        handleBtnSearchPasien($btnSearchPasien);
        $('a.btn').tooltip();
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

            $popContainer.css({minWidth: '720px', maxWidth: '720px', zIndex: '99999'});

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

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
                { 'name' : "pasien.nama nama",'visible' : true, 'searchable': true, 'orderable': true },
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


            $('.pilih-pasien').popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
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
            $ttl = $tempat + ", " + $tgl;
            $GenderPasien.val($gender);
            $TglLahirPasien.val($ttl);
            $NomorPasien.val($(this).data('item').nomor);
            $Upload.removeClass("hidden");
            // alert($itemIdEl.val($(this).data('item').id));


            e.preventDefault();
        });     
    };

    var handleAmbilTipe = function(){
    	$('a.edit').click(function()
    	{
    		$('input.waktu').val($(this).data('tipe'));
    		$('input.no_urut').val($(this).data('urut'));   		
    		$('input.hari').val($(this).data('hari'));
    		$('input.tanggal').val($(this).data('tanggal'));
    		$('input[name="nama_pasien"]').val("");
            $('input.id').val($(this).data('id'));
            // alert($(this).data('tipe')); 
    	});
    }

    var handleAmbilNext = function(){
        $('a.tes').click(function()
        {
            $('input.waktu').val($(this).data('tipe'));
            $('input.no_urut').val($(this).data('urut'));           
            $('input.hari').val($(this).data('hari'));
            $('input.tanggal').val($(this).data('tanggal'));
            $('input[name="nama_pasien"]').val("");
            $('input.id').val($(this).data('id'));
            // alert($(this).data('tipe'));
        });
    }

    var handleMoveTipe = function(){
        $('a.move').click(function()
        {
            $('input.waktu').val($(this).data('tipe'));
            $('input.no_urut').val($(this).data('urut'));           
            $('input.jadwal_awal').val($(this).data('hari')+','+$(this).data('tanggal'));
            $('.keterangan').val($(this).data('keterangan'));            
            $('input.id').val($(this).data('id'));
            $('input#id_pasien').val($(this).data('pasien-id'));
            $('input#nama_pasien').val($(this).data('nama'));

            $('select#tanggal_id').on('change', function(){
                $('input.tanggal_ubah').val("");
                $('input.tanggal_ubah').val($('select#tanggal_id option:selected').text());
            })
            
        });
    }

    var handleViewTipe = function(){
        $('a.view').click(function()
        {
            $('input.waktu').val($(this).data('tipe'));
            $('input.no_urut').val($(this).data('urut'));           
            $('input.jadwal_awal').val($(this).data('hari')+','+$(this).data('tanggal'));
            $('input#nama_pasien').val($(this).data('nama'));
        });
    }

    
	
    var handleNext = function()
    {        
        var $btnNext = $('a#next');
        $btnNext.on('click', function(){

            var $cabang = $('select#cabang_id').val();

            i = 7;
            j = 0;
            var today = new Date($('input#test').val());
                nextWeek = new Date(today),
                tanggal = new Date(nextWeek.setDate(nextWeek.getDate() + i)),
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                day = tanggal.getDate(),
                bulan = tanggal.getMonth(),
                yy        = tanggal.getYear(),
                year      = (yy < 1000) ? yy + 1900 : yy,

                $senin = day+' '+months[bulan]+' '+year; 

                // $('input#test').val($senin);

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal',
                    data     : { senin : $senin, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal').html('');
                       $('#table_jadwal').html(results);
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleDataSenin();
                       handleDataSelasa();
                       handleDataRabu();
                       handleDataKamis();
                       handleDataJumat();
                       handleDataSabtu();
                       handleDataMinggu();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                })
                
        })
    }

    var handlePrev = function()
    {

        var $btnPrev = $('a#prev');
        $btnPrev.on('click', function(){

            var $cabang = $('select#cabang_id').val();

            j = 7;
            i = 0;

            var today = new Date($('input#test').val()),
                lastWeek = new Date(today);
                tanggal = new Date(lastWeek.setDate(lastWeek.getDate() - j)),
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                day = tanggal.getDate(),
                bulan = tanggal.getMonth(),
                yy        = tanggal.getYear(),
                year      = (yy < 1000) ? yy + 1900 : yy,

                $senin = day+' '+months[bulan]+' '+year; 

                // $('input#test').val($senin);

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal',
                    data     : { senin : $senin },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal').html('');
                       $('#table_jadwal').html(results);
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleDataSenin();
                       handleDataSelasa();
                       handleDataRabu();
                       handleDataKamis();
                       handleDataJumat();
                       handleDataSabtu();
                       handleDataMinggu();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                })
        })
    }

     var handleNextHari = function()
    {
        
        
        var $btnNext = $('a#next_hari');
        $btnNext.on('click', function(){

            var $cabang = $('select#cabang_id').val();

            i = 1;
            j = 0;
            var str = $('input#hari_tanggal').val(),
                str_hari = $('input#hari_tanggal').val(),
                $hari = str_hari.substr(0, 6);

                if($hari == 'Senin ')
                {
                    $hari = 'Selasa';
                    today = new Date(str.substr(7, 11));

                    
                }
                else if($hari == 'Selasa')
                {
                    $hari = 'Rabu';
                    today = new Date(str.substr(8, 11));
                    
                }
                else if($hari == 'Rabu (')
                {
                    $hari = 'Kamis';
                    today = new Date(str.substr(6, 11));
                    
                }
                else if($hari == 'Kamis ')
                {
                    $hari = 'Jumat';
                    today = new Date(str.substr(7, 11));
                    
                }
                else if($hari == 'Jumat ')
                {
                    $hari = 'Sabtu';
                    today = new Date(str.substr(7, 11));
                    
                }
                else if($hari == 'Sabtu ')
                {
                    $hari = 'Minggu';
                    today = new Date(str.substr(7, 11));
                    
                }
                else
                {
                    $hari = 'Senin';
                    today = new Date(str.substr(8, 11));
                    
                }

            var nextWeek = new Date(today),
                tanggal = new Date(nextWeek.setDate(nextWeek.getDate() + i)),
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                day = tanggal.getDate();

                if(day < 10)
                {
                    day ='0'+day;
                }else
                {
                    day = day;
                }
            var bulan = tanggal.getMonth(),
                yy        = tanggal.getYear(),
                year      = (yy < 1000) ? yy + 1900 : yy,

                $tgl = day+' '+months[bulan]+' '+year; 

                // $('input#test').val($senin);
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal_hari',
                    data     : { tanggal : $tgl, hari : $hari, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal_hari').html('');
                       $('#table_jadwal_hari').html(results);
                       $('#table_jadwal_hari').removeClass('hidden');
                       $('#table_jadwal').addClass('hidden');
                       $('#table_pasien_move').removeClass("hidden");
                       $('input#hari_tanggal').val($hari+' ('+$tgl+')');
                       $('input#text_hari').val($hari.toLowerCase());
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                })
                
        })
    }
 
    var handlePrevHari = function()
    {

        var $btnNext = $('a#prev_hari');
        $btnNext.on('click', function(){

            var $cabang = $('select#cabang_id').val();

            i = 0;
            j = 1;
            var str = $('input#hari_tanggal').val(),
                str_hari = $('input#hari_tanggal').val(),
                $hari = str_hari.substr(0, 6);

                if($hari == 'Senin ')
                {
                    $hari = 'Minggu';
                    today = new Date(str.substr(7, 11));
                    
                }
                else if($hari == 'Selasa')
                {
                    $hari = 'Senin';
                    today = new Date(str.substr(8, 11));
                    
                }
                else if($hari == 'Rabu (')
                {
                    $hari = 'Selasa';
                    today = new Date(str.substr(6, 11));
                    
                }
                else if($hari == 'Kamis ')
                {
                    $hari = 'Rabu';
                    today = new Date(str.substr(7, 11));
                    
                }
                else if($hari == 'Jumat ')
                {
                    $hari = 'Kamis';
                    today = new Date(str.substr(7, 11));
                    
                }
                else if($hari == 'Sabtu ')
                {
                    $hari = 'Jumat';
                    today = new Date(str.substr(7, 11));
                    
                }
                else
                {
                    $hari = 'Sabtu';
                    today = new Date(str.substr(8, 11));
                    
                }

            var nextWeek = new Date(today),
                tanggal = new Date(nextWeek.setDate(nextWeek.getDate() - j)),
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                day = tanggal.getDate();

                if(day < 10)
                {
                    day ='0'+day;
                }else
                {
                    day = day;
                }
            var bulan = tanggal.getMonth(),
                yy        = tanggal.getYear(),
                year      = (yy < 1000) ? yy + 1900 : yy,

                $tgl = day+' '+months[bulan]+' '+year; 

                // $('input#test').val($senin);
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal_hari',
                    data     : { tanggal : $tgl, hari : $hari, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert($tgl);
                       $('#table_jadwal_hari').html('');
                       $('#table_jadwal_hari').html(results);
                       $('#table_jadwal_hari').removeClass('hidden');
                       $('#table_jadwal').addClass('hidden');
                       $('#table_pasien_move').removeClass("hidden");
                       $('input#hari_tanggal').val($hari+' ('+$tgl+')');
                       $('input#text_hari').val($hari.toLowerCase());
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                })
                
        })
    }

	var handleCheck = function(){
		$('input.ulang_minggu').on('click', function(){
			var checked = $(this).prop('checked');
   		 	if(checked){

			var $labelBerulang = $('label#label_berulang'),
			    $labelMinggu = $('a#label_minggu'),
            	$inputMinggu = $('input#minggu');

            	$labelBerulang.removeClass("hidden");
            	$labelMinggu.removeClass("hidden");
            	$inputMinggu.removeClass("hidden");
            }
            else{
            
            var $labelBerulang = $('label#label_berulang'),
			    $labelMinggu = $('a#label_minggu'),
            	$inputMinggu = $('input#minggu');

            	$labelBerulang.addClass("hidden");
            	$labelMinggu.addClass("hidden");
            	$inputMinggu.addClass("hidden");
            }	
		})
	}

    var handleModalOk = function(){
        $('a#modal_ok').click(function() {
           
            $form_jadwal = $('#form_jadwal');

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_jadwal',
                data     : $form_jadwal.serialize(),
                dataType : 'json',
                beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                success  : function( results ) {
                    
                    $('#closeModal').click();              
                    location.href = baseAppUrl;
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
        });
    }

    var handleModalMove = function(){
        $('a#modal_ok_move').click(function() {
           
            $form_jadwal_move = $('#form_jadwal_move');

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_jadwal',
                data     : $form_jadwal_move.serialize(),
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    
                    $('#closeModal').click();              
                    location.href = baseAppUrl;
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
        });
    }

    var handleCabang = function(){
        $('select#cabang_id').on('change', function()
        {
            $('input#cabang').val($(this).val());
            var $cabang = $(this).val();

            var today = new Date($('input#test').val()),
                lastWeek = new Date(today);
                tanggal = new Date(lastWeek.setDate(lastWeek.getDate() - j)),
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                day = tanggal.getDate(),
                bulan = tanggal.getMonth(),
                yy        = tanggal.getYear(),
                year      = (yy < 1000) ? yy + 1900 : yy,

                $senin = day+' '+months[bulan]+' '+year; 

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal',
                    data     : { cabang : $cabang, senin : $senin},
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal').html('');
                       $('#table_jadwal').html(results);
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleDataSenin();
                       handleDataSelasa();
                       handleDataRabu();
                       handleDataKamis();
                       handleDataJumat();
                       handleDataSabtu();
                       handleDataMinggu();
                       handleBack();
                       handleRefreshHari();
                    },               
                    complete : function(){
                        Metronic.unblockUI();
                    }
                })
        })
    }

    var handleDataSenin = function()
    {
    	$('a#view_senin').on('click', function()
    	{
            var $cabang = $('select#cabang_id').val();

            var str = $('input#hari_senin').val(),
                str_hari = $('input#hari_senin').val(),
                $hari = str_hari.substr(0, 5),
                today = new Date(str.substr(7, 11)),
                lastWeek = new Date(today),
                now = new Date(lastWeek.setDate(lastWeek.getDate()));
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                days = now.getDate(),
                month = now.getMonth(),
                yy        = now.getYear(),
                years      = (yy < 1000) ? yy + 1900 : yy,
                $tgl = days+' '+months[month]+' '+years,

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal_hari',
                    data     : { tanggal : $tgl, hari : $hari, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal_hari').html('');
                       $('#table_jadwal_hari').html(results);
                       $('#table_jadwal_hari').removeClass('hidden');
                       $('#table_jadwal').addClass('hidden');
                       $('#table_pasien').addClass("hidden");
                       $('#table_pasien_move').removeClass("hidden");
                       $('input#hari_tanggal').val(str);
                       $('a#next').addClass("hidden");
                       $('a#next_hari').removeClass("hidden");
                       $('a#prev').addClass("hidden");
                       $('a#prev_hari').removeClass("hidden");
                       $('a#refresh').addClass("hidden");
                       $('a#refresh_hari').removeClass("hidden");
                       $('input#text_hari').val("senin");
                       // $('select#cabang_id').addClass('hidden');
                       // $('select#cabang_id_harian').removeClass('hidden');
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                       // handleCabangHarian();
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                }) 

                // $('input#hari_tanggal').val($(''input#hari_senin'')));
                
    	})
    }

    var handleDataSelasa = function()
    {
    	$('a#view_selasa').on('click', function()
    	{
    		var $cabang = $('select#cabang_id').val();

            var str = $('input#hari_selasa').val(),
                str_hari = $('input#hari_selasa').val(),
                $hari = str_hari.substr(0, 6),
                today = new Date(str.substr(8, 11)),
                lastWeek = new Date(today),
                now = new Date(lastWeek.setDate(lastWeek.getDate()));
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                days = now.getDate(),
                month = now.getMonth(),
                yy        = now.getYear(),
                years      = (yy < 1000) ? yy + 1900 : yy,
                $tgl = days+' '+months[month]+' '+years,

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal_hari',
                    data     : { tanggal : $tgl, hari : $hari, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal_hari').html('');
                       $('#table_jadwal_hari').html(results);
                       $('#table_jadwal_hari').removeClass('hidden');
                       $('#table_jadwal').addClass('hidden');
                       $('#table_pasien').addClass("hidden");
                       $('#table_pasien_move').removeClass("hidden");
                       $('input#hari_tanggal').val(str);
                        $('a#next').addClass("hidden");
                       $('a#next_hari').removeClass("hidden");
                       $('a#prev').addClass("hidden");
                       $('a#prev_hari').removeClass("hidden");
                       $('a#refresh').addClass("hidden");
                       $('a#refresh_hari').removeClass("hidden");
                       $('input#text_hari').val("selasa");
                       // $('select#cabang_id').addClass('hidden');
                       // $('select#cabang_id_harian').removeClass('hidden');
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                       // handleCabangHarian();
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                   
                })
    	})
    }

    var handleDataRabu = function()
    {
    	$('a#view_rabu').on('click', function()
    	{
    		var $cabang = $('select#cabang_id').val();

            var str = $('input#hari_rabu').val(),
                str_hari = $('input#hari_rabu').val(),
                $hari = str_hari.substr(0, 4),
                today = new Date(str.substr(6, 11)),
                lastWeek = new Date(today),
                now = new Date(lastWeek.setDate(lastWeek.getDate()));
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                days = now.getDate(),
                month = now.getMonth(),
                yy        = now.getYear(),
                years      = (yy < 1000) ? yy + 1900 : yy,
                $tgl = days+' '+months[month]+' '+years,

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal_hari',
                    data     : { tanggal : $tgl, hari : $hari, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal_hari').html('');
                       $('#table_jadwal_hari').html(results);
                       $('#table_jadwal_hari').removeClass('hidden');
                       $('#table_jadwal').addClass('hidden');
                       $('#table_pasien').addClass("hidden");
                       $('#table_pasien_move').removeClass("hidden");
                       $('input#hari_tanggal').val(str);
                       $('a#next').addClass("hidden");
                       $('a#next_hari').removeClass("hidden");
                       $('a#prev').addClass("hidden");
                       $('a#prev_hari').removeClass("hidden");
                       $('a#refresh').addClass("hidden");
                       $('a#refresh_hari').removeClass("hidden");
                       $('input#text_hari').val("rabu");
                       // $('select#cabang_id').addClass('hidden');
                       // $('select#cabang_id_harian').removeClass('hidden');
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                       // handleCabangHarian();
                    },                    
                    complete : function(){
                        Metronic.unblockUI();
                    }

                })
        })
    }

    var handleDataKamis = function()
    {
    	$('a#view_kamis').on('click', function()
    	{
    		var $cabang = $('select#cabang_id').val();

            var str = $('input#hari_kamis').val(),
                str_hari = $('input#hari_kamis').val(),
                $hari = str_hari.substr(0, 5),
                today = new Date(str.substr(7, 11)),
                lastWeek = new Date(today),
                now = new Date(lastWeek.setDate(lastWeek.getDate()));
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                days = now.getDate(),
                month = now.getMonth(),
                yy        = now.getYear(),
                years      = (yy < 1000) ? yy + 1900 : yy,
                $tgl = days+' '+months[month]+' '+years,

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal_hari',
                    data     : { tanggal : $tgl, hari : $hari, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal_hari').html('');
                       $('#table_jadwal_hari').html(results);
                       $('#table_jadwal_hari').removeClass('hidden');
                       $('#table_jadwal').addClass('hidden');
                       $('#table_pasien').addClass("hidden");
                       $('#table_pasien_move').removeClass("hidden");
                       $('input#hari_tanggal').val(str);
                        $('a#next').addClass("hidden");
                       $('a#next_hari').removeClass("hidden");
                       $('a#prev').addClass("hidden");
                       $('a#prev_hari').removeClass("hidden");
                       $('a#refresh').addClass("hidden");
                       $('a#refresh_hari').removeClass("hidden");
                       $('input#text_hari').val("kamis");
                       // $('select#cabang_id').addClass('hidden');
                       // $('select#cabang_id_harian').removeClass('hidden');
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                       // handleCabangHarian();
                    },                    
                    complete : function(){
                        Metronic.unblockUI();
                    }
                }) 
    	})
    }

    var handleDataJumat = function()
    {
    	$('a#view_jumat').on('click', function()
    	{
    		var $cabang = $('select#cabang_id').val();

            var str = $('input#hari_jumat').val(),
                str_hari = $('input#hari_jumat').val(),
                $hari = str_hari.substr(0, 5),
                today = new Date(str.substr(7, 11)),
                lastWeek = new Date(today),
                now = new Date(lastWeek.setDate(lastWeek.getDate()));
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                days = now.getDate(),
                month = now.getMonth(),
                yy        = now.getYear(),
                years      = (yy < 1000) ? yy + 1900 : yy,
                $tgl = days+' '+months[month]+' '+years,

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal_hari',
                    data     : { tanggal : $tgl, hari : $hari, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal_hari').html('');
                       $('#table_jadwal_hari').html(results);
                       $('#table_jadwal_hari').removeClass('hidden');
                       $('#table_jadwal').addClass('hidden');
                       $('#table_pasien').addClass("hidden");
                       $('#table_pasien_move').removeClass("hidden");
                       $('input#hari_tanggal').val(str);
                       $('input#hari_tanggal').attr('value',str);
                        $('a#next').addClass("hidden");
                       $('a#next_hari').removeClass("hidden");
                       $('a#prev').addClass("hidden");
                       $('a#prev_hari').removeClass("hidden");
                       $('a#refresh').addClass("hidden");
                       $('a#refresh_hari').removeClass("hidden");
                       $('input#text_hari').val("jumat");
                       // $('select#cabang_id').addClass('hidden');
                       // $('select#cabang_id_harian').removeClass('hidden');
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                       // handleCabangHarian();
                    },
                   
                    complete : function(){
                        Metronic.unblockUI();
                    }
                }) 
    	})
    }

    var handleDataSabtu = function()
    {
    	$('a#view_sabtu').on('click', function()
    	{
    		var $cabang = $('select#cabang_id').val();

            var str = $('input#hari_sabtu').val(),
                str_hari = $('input#hari_sabtu').val(),
                $hari = str_hari.substr(0, 5),
                today = new Date(str.substr(7, 11)),
                lastWeek = new Date(today),
                now = new Date(lastWeek.setDate(lastWeek.getDate()));
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                days = now.getDate(),
                month = now.getMonth(),
                yy        = now.getYear(),
                years      = (yy < 1000) ? yy + 1900 : yy,
                $tgl = days+' '+months[month]+' '+years,

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal_hari',
                    data     : { tanggal : $tgl, hari : $hari, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal_hari').html('');
                       $('#table_jadwal_hari').html(results);
                       $('#table_jadwal_hari').removeClass('hidden');
                       $('#table_jadwal').addClass('hidden');
                       $('#table_pasien').addClass("hidden");
                       $('#table_pasien_move').removeClass("hidden");
                       $('input#hari_tanggal').val(str);
                        $('a#next').addClass("hidden");
                       $('a#next_hari').removeClass("hidden");
                       $('a#prev').addClass("hidden");
                       $('a#prev_hari').removeClass("hidden");
                       $('a#refresh').addClass("hidden");
                       $('a#refresh_hari').removeClass("hidden");
                       $('input#text_hari').val("sabtu");
                       // $('select#cabang_id').addClass('hidden');
                       // $('select#cabang_id_harian').removeClass('hidden');
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                       // handleCabangHarian();
                    },                   
                    complete : function(){
                        Metronic.unblockUI();
                    }
                }) 

    	})
    }

    var handleDataMinggu = function()
    {
    	$('a#view_minggu').on('click', function()
    	{
    		var $cabang = $('select#cabang_id').val();

            var str = $('input#hari_minggu').val(),
                str_hari = $('input#hari_minggu').val(),
                $hari = str_hari.substr(0, 6),
                today = new Date(str.substr(8, 11)),
                lastWeek = new Date(today),
                now = new Date(lastWeek.setDate(lastWeek.getDate()));
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                days = now.getDate(),
                month = now.getMonth(),
                yy        = now.getYear(),
                years      = (yy < 1000) ? yy + 1900 : yy,
                $tgl = days+' '+months[month]+' '+years,

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal_hari',
                    data     : { tanggal : $tgl, hari : $hari, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal_hari').html('');
                       $('#table_jadwal_hari').html(results);
                       $('#table_jadwal_hari').removeClass('hidden');
                       $('#table_jadwal').addClass('hidden');
                       $('#table_pasien').addClass("hidden");
                       $('#table_pasien_move').removeClass("hidden");
                       $('input#hari_tanggal').val(str);
                        $('a#next').addClass("hidden");
                       $('a#next_hari').removeClass("hidden");
                       $('a#prev').addClass("hidden");
                       $('a#prev_hari').removeClass("hidden");
                       $('a#refresh').addClass("hidden");
                       $('a#refresh_hari').removeClass("hidden");
                       $('input#text_hari').val("minggu");
                       // $('select#cabang_id').addClass('hidden');
                       // $('select#cabang_id_harian').removeClass('hidden');
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleBack();
                       handleRefreshHari();
                       $('a.btn').tooltip();
                       // handleCabangHarian();
                    },                    
                    complete : function(){
                        Metronic.unblockUI();
                    }
                }) 

    	})
    }

    var handleBack = function()
    {
    	$('a#back').on('click', function()
    	{
            Metronic.blockUI({boxed: true });
            $('#table_jadwal_hari').addClass('hidden');
            $('#table_jadwal').removeClass('hidden');
    		$('#table_pasien').removeClass("hidden");
            $('#table_pasien_move').addClass("hidden");
            $('a#next').removeClass("hidden");
            $('a#next_hari').addClass("hidden");
            $('a#prev').removeClass("hidden");
            $('a#prev_hari').addClass("hidden");
            $('a#refresh').removeClass("hidden");
            $('a#refresh_hari').addClass("hidden");
            $('a#refresh_hari').removeAttr("data-hari");
            Metronic.unblockUI();
            // $('select#cabang_id').removeClass('hidden');
            // $('select#cabang_id_harian').addClass('hidden');
    	})
    }

    var handleRefresh = function()
    {        
        // var $btnRefresh = $('a.refresh');
        $('a.refresh').on('click', function(){

            var $cabang = $('select#cabang_id').val();

            i = 7;
            j = 0;
            var $senin = $('input#test').val(); 

                // $('input#test').val($senin);

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal',
                    data     : { senin : $senin, cabang : $cabang },
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // alert(results);
                       $('#table_jadwal').html('');
                       $('#table_jadwal').html(results);
                       handleAmbilNext();
                       handleViewTipe();
                       handleMoveTipe();
                       handleDataSenin();
                       handleDataSelasa();
                       handleDataRabu();
                       handleDataKamis();
                       handleDataJumat();
                       handleDataSabtu();
                       handleDataMinggu();
                       handleBack();
                       $('a.btn').tooltip();
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                })
                
        });         
    }

    var handleRefreshHari = function()
    {
        $('a.refresh_hari').on('click', function(){

            var hari = $('input#text_hari').val();

            if(hari == 'senin')
            {
                $('a#view_senin').click();
            }
            if(hari == 'selasa')
            {
                 $('a#view_selasa').click();
            }
            if(hari == 'rabu')
            {
                 $('a#view_rabu').click();
            }
            if(hari == 'kamis')
            {
                 $('a#view_kamis').click();
            }
            if(hari == 'jumat')
            {
                 $('a#view_jumat').click();
            }
            if(hari == 'sabtu')
            {
                 $('a#view_sabtu').click();
            }
            if(hari == 'minggu')
            {
                $('a#view_minggu').click();
            }                
        });
    }
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/jadwal/';
        $('a.btn').tooltip();
        initform();
        handlePilihPasien();
        handleAmbilTipe();
        handleAmbilNext();
        handleMoveTipe();
        handleViewTipe();
        handleDataSenin();
        handleDataSelasa();
        handleDataRabu();
        handleDataKamis();
        handleDataJumat();
        handleDataSabtu();
        handleDataMinggu();
        handleBack();
        handleCheck();
        handleCabang();
        handleRefresh();
        // handleCabangHarian();
        handleModalOk();
        handleModalMove();
        handleNext();
        handlePrev();
        handleNextHari();
        handlePrevHari();

    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});