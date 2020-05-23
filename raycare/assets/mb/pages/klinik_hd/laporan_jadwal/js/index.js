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
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec']
                day = tanggal.getDate(),
                bulan = tanggal.getMonth(),
                yy        = tanggal.getYear(),
                year      = (yy < 1000) ? yy + 1900 : yy,

                $senin = day+' '+months[bulan]+' '+year; 

                // $('input#test').val($senin);

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_tanggal',
                    data     : { senin : $senin},
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
                       handleBack();
                       
                       $('a#print').attr('href', baseAppUrl+'print_jadwal/'+$('input#text_senin').val()+'/'+$('input#text_minggu').val());
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
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
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
                       handleBack();
                       
                       $('a#print').attr('href', baseAppUrl+'print_jadwal/'+$('input#text_senin').val()+'/'+$('input#text_minggu').val());
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
                months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
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
                       handleBack();
                       
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
                    data     : { senin : $senin},
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
                       handleBack();
                       $('a#print').attr('href', baseAppUrl+'print_jadwal/'+$('input#text_senin').val()+'/'+$('input#text_minggu').val());
                       $('a.btn').tooltip();
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                })
                
        });         
    }

    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/laporan_jadwal/';
        $('a.btn').tooltip();
        initform();
        handleAmbilTipe();
        handleAmbilNext();
        handleMoveTipe();
        handleViewTipe();
       
        handleBack();
        handleCheck();
        handleCabang();
        handleRefresh();
        
        handleNext();
        handlePrev();

    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});