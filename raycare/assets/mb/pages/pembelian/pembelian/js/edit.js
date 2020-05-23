mb.app.daftar_permintaan_po = mb.app.daftar_permintaan_po || {};
(function(o){

    var 
        baseAppUrl                      = '',
        $form                           = $('#form_add_pembelian');
        $popoverItemContent             = $('#popover_item_content'), 
        $popoverItemContentPembelian    = $('#popover_item_content_pembelian'), 
        $popoverPenerimaContent         = $('#popover_penerima_content_cabang'), 
        $popoverPenerimaContentCustomer = $('#popover_penerima_content_customer'), 
        $popoverJumlahPesan             = $('#popover_jumlah_pesan'), 
        $tableDetailPembelian           = $('#table_detail_pembelian'),
        $tableItemSearch                = $('#table_item_search'),
        $tablePenawaran                 = $('#table_penawaran'),
        $tablePilihSupplier             = $('#table_pilih_supplier'),
        $btnSearchPenerima              = $('.pilih-penerima-cabang'),
        $btnSearchPenerimaCustomer      = $('.pilih-penerima-customer'),
        $tablePilihCabang               = $('#table_pilih_cabang'),
        $tablePilihCustomer             = $('#table_pilih_customer'),
        $tableCariPermintaan            = $('#table_search_permintaan'),
        $lastPopoverItem                = null,
        $lastPopoverItemPembelian       = null,
        $lastPopoverJumlahPesan         = null,
        $lastPopoverPenerima            = null,
        $lastPopoverPenerimaCustomer    = null,
        $lastPopoverDetail              = null,
        tplItemRow                      = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter                     = $('input#jml_baris').val(),
        tplPenawaranRow                 = $.validator.format( $('#tpl_penawaran_row').text() ),
        penawaranCounter                = $('input#jml_penawaran').val()
        ;

    var 
        $btnSearchItemPembelian = $('.search-item', $tableDetailPembelian),
        $btnDeletes    = $('.del-this', $tableDetailPembelian),
        $btnSearchPermintaan = $('.search-jumlah', $tableDetailPembelian);
        $btnSearchPermintaanTabel = $('.search-tabel', $tableDetailPembelian);
        $btnDeleteItemsDB = $('.del-item-db', $tableDetailPembelian);
        $btnDeleteDetailDB = $('button.del-this-db', $tableDetailPembelian);
        $btnAddPenawaran          = $('a.add-upload');
        $btnDeletePenawaranDB = $('button.del-this-penawaran-db', $tablePenawaran);

        // $btnSearchPermintaan = $('.search-jumlah', $tableDetailPembelian)
    ;
        

    var initform = function()
    {    

        var $btnSearchSupplier  = $('.pilih-supplier');
        handleBtnSearchSupplier($btnSearchSupplier);

        handleBtnSearchPenerima($btnSearchPenerima); 
        handleBtnSearchPenerimaCustomer($btnSearchPenerimaCustomer);

        handleBtnDeleteDb( $btnDeleteDetailDB );
        
        handleBtnAddPenawaran($btnAddPenawaran);
        handleBtnDeletePenawaranDb( $btnDeletePenawaranDB );
        handlePopoverDetail();

        handleUploadify(penawaranCounter);
        



        $('input[name="tipe_supplier"]').on('click', function(){
            iStatTipe   = this.value;

            // alert(iStatTipe);
            $tablePilihSupplier.api().ajax.url(baseAppUrl + 'listing_supplier/' + iStatTipe).load();
            // $tablePermintaanPembelianProses.fnClearTable();
        });

        $('input[name="tipe_penerima"]').on('click', function(){
            iStatTipe   = this.value;

            // alert(iStatTipe);
            $('input[name="tipe"]').val(iStatTipe);
            if(iStatTipe == 1)
            {
                $('a.pilih-penerima-cabang').removeClass("hidden");
                $('a.pilih-penerima-customer').addClass("hidden");
            }
            else if(iStatTipe == 2)
            {
                $('a.pilih-penerima-cabang').addClass("hidden");
                $('a.pilih-penerima-customer').removeClass("hidden");
            }
            // $tablePermintaanPembelianProses.fnClearTable();
        });

        $('input[name$="[jumlah]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

            if($(this).val() < $('input[name$="[jumlah_min]"]').val())
            {
                bootbox.confirm('Jumlah Item Tidak Boleh Kurang Dari Permintaan', function(result) {
                        if (result==true) {
                            
                        }
                    });
            }
        });

        $('input[name$="[jumlah]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   

            if($(this).val() < $('input[name$="[jumlah_min]"]').val())
            {
                bootbox.confirm('Jumlah Item Tidak Boleh Kurang Dari Permintaan', function(result) {
                    if (result==true) {
                        
                    }
                });
            }         
        });

        $('input[name$="[item_diskon]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

        });

        $('input[name$="[item_diskon]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   
     
        });

        $('input[name$="[item_harga]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

        });

        $('input[name$="[item_harga]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   
     
        });

        $('input#diskon').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#diskon').on('change',function()
        {
            calculateTotal();   
     
        });
        $('input#disk_angka').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#disk_angka').on('change',function()
        {
            calculateTotal();   
     
        });

        $('input#diskon_nominal').on('keyup',function()
        {
            calculateTotalDiskon();

        });

        $('input#diskon_nominal').on('change',function()
        {
            calculateTotalDiskon();   
     
        });

        $('input#disk_hidden').on('keyup',function()
        {
            calculateTotalDiskon();

        });

        $('input#disk_hidden').on('change',function()
        {
            calculateTotalDiskon();   
     
        });

        $('input#pph').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#pph').on('change',function()
        {
            calculateTotal();   
     
        });

        $('input#pph_nominal').on('keyup',function()
        {
            calculateTotalPPh();

        });

        $('input#pph_nominal').on('change',function()
        {
            calculateTotalPPh();   
     
        });

        
        $('input#dp').on('keyup',function()
        {
            calculateTotalDP();

        });

        $('input#dp').on('change',function()
        {
            calculateTotalDP();   
     
        });

        $('input#dp_nominal').on('keyup',function()
        {
            calculateTotalDPNominal();

        });

        $('input#dp_nominal').on('change',function()
        {
            calculateTotalDPNominal();   
     
        });

        $('input#bunga_persen').on('keyup',function()
        {
            calculateTotalBungaPersen();

        });

        $('input#bunga_persen').on('change',function()
        {
            calculateTotalBungaPersen();   
     
        });

        $('input#bunga_nominal').on('keyup',function()
        {
            calculateTotalBungaNominal();

        });

        $('input#bunga_nominal').on('change',function()
        {
            calculateTotalBungaNominal();   
     
        });

         $('input#biaya_tambahan').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#biaya_tambahan').on('change',function()
        {
            calculateTotal();   
     
        });

        $('input#pembulatan').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#pembulatan').on('change',function()
        {
            calculateTotal();   
     
        });

        handleBtnSearchItemPembelian($btnSearchItemPembelian);

        handleBtnSearchPermintaan($btnSearchPermintaan);    
        handleBtnSearchPermintaan($btnSearchPermintaanTabel);    

        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDeletePembelian( $(btn) );
        });

        $.each($btnDeleteItemsDB, function(idx, btn){
            handleBtnDeleteItemsDB( $(btn) );
        });

        addItemRow();
        // calculateTotal();

        // $popoverJumlahPesan.hide();
    }

    var handlePopoverDetail = function() {
        var $identitasItem = $('.item-list', $tableDetailPembelian);

        $.each($identitasItem, function(idx, col){
            var
                $col            = $(col),
                dataItem        = $col.data('item'),
                dataSatuan      = $col.data('satuan');

            // console.log(dataIdentitas);
            $col.popover({
                html : true,
                container : 'body',
                placement : 'bottom',
                content: function(){
                    
                    var html = '<table class="table table-striped table-hover">';
                        html += '<tr>';
                        html += '<td class="text-left bold">Jumlah Pesan</td>';
                        html += '<td width="1px" class="text-left bold">:</td>';
                        html += '<td class="text-left">'+ dataItem.jumlah_pesan+' '+dataSatuan+'</td>';
                        html += '</tr>';
                        html += '<tr>';
                        html += '<td class="text-left bold">Jumlah Disetujui</td>';
                        html += '<td width="1px" class="text-left bold">:</td>';
                        html += '<td class="text-left">'+ dataItem.jumlah_disetujui+' '+dataSatuan+'</td>';
                        html += '</tr>';
                        html += '<tr>';
                        html += '<td class="text-left bold">Status</td>';
                        html += '<td width="1px" class="text-left bold">:</td>';
                        html += '<td class="text-left">'+ dataItem.status+'</td>';
                        html += '</tr>';
                        html += '<tr>';
                        html += '<td class="text-left bold">Disetujui Oleh</td>';
                        html += '<td width="1px" class="text-left bold">:</td>';
                        html += '<td class="text-left">'+ dataItem.nama+'</td>';
                        html += '</tr>';

                    
                    html += '</table>';
                    return html;
                }
            }).on("show.bs.popover", function(){
                $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                if ($lastPopoverDetail !== null) $lastPopoverDetail.popover('hide');
                $lastPopoverDetail = $col;
            }).on('hide.bs.popover', function(){
                $lastPopoverDetail = null;
            }).on('click', function(e){

            });
        });
    }

    var handleBtnDeleteDb = function($btn)
    {
        $btn.on('click', function(e){  
            var 
                index = $(this).data('index'),
                $row  = $('#item_row_'+index, $tableDetailPembelian),
                id_db = $(this).data('id');

            if(id_db != undefined)
            {
                var msg = $(this).data('confirm');

                bootbox.confirm(msg, function(result){
                    if(result == true)
                    {
                        $('input[name$="[is_active]"]', $row).attr('value',0);    
                        $('input[name$="[item_sub_total]"]', $row).attr('value',0);    
                        $('label[name$="[item_sub_total]"]', $row).text('value',0);    
                        $row.hide();
                        calculateTotal();

                    }
                });                
            }
            if($('tbody>tr', $tableDetailPembelian).length == 0){
                addItemRow();
            }
            calculateTotal();
            e.preventDefault();
        });
    };

    var handleBtnDeletePenawaranDb = function($btn)
    {
        $btn.on('click', function(e){  
            var 
                index = $(this).data('index'),
                $row  = $('#item_row_penawaran_'+index, $tablePenawaran),
                id_db = $(this).data('id');

            if(id_db != undefined)
            {
                var msg = $(this).data('confirm');

                bootbox.confirm(msg, function(result){
                    if(result == true)
                    {
                        $('input[name$="[is_active]"]', $row).attr('value',0);    
                        $row.hide();
                        if($('tbody>tr', $tablePenawaran).length == 0){
                            addPenawaranRow();
                        }

                    }
                });                
            }
            
            e.preventDefault();
        });
    };

    var addItemRow = function(){
        
        var numRow = $('tbody tr', $tableDetailPembelian).length;

        console.log('numrow' + numRow);

        // if (numRow > 0 && ! isValidLastRow()) return;

        var 
            $rowContainer     = $('tbody', $tableDetailPembelian),
            $newItemRow       = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            // $newGetItemRow = $(tplGetItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItemPembelian    = $('.search-item', $newItemRow);
            $btnSearchPermintaan = $('.search-jumlah', $newItemRow);
            $btnSearchPermintaanTabel = $('.search-tabel', $tableDetailPembelian);

            // $btnSearchPermintaan.attr('data-row', numRow);
            // $btnSearchPermintaanTabel.attr('data-row', numRow);


        $('input[name$="[jumlah]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

            if($('input[name$="[jumlah]"]').val() < $('input[name$="[jumlah_min]"]').val())
            {
                bootbox.confirm('Jumlah Item Tidak Boleh Kurang Dari Permintaan', function(result) {
                        if (result==true) {
                            
                        }
                    });
            }
        });

        $('input[name$="[jumlah]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   

            if($('input[name$="[jumlah]"]').val() < $('input[name$="[jumlah_min]"]').val())
            {
                bootbox.confirm('Jumlah Item Tidak Boleh Kurang Dari Permintaan', function(result) {
                        if (result==true) {
                            
                        }
                    });
            }         
        });

        if($('input[name$="[jumlah]"]', $tableDetailPembelian).val != 0)
        {
            calculateTotal();   
        };

        $('input[name$="[item_diskon]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

        });

        $('input[name$="[item_diskon]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   
     
        });

        $('input[name$="[item_harga]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

        });

        $('input[name$="[item_harga]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   
     
        });
        
        // handle delete btn
        handleBtnDeletePembelian( $('.del-this', $newItemRow) );

        // handle button search item
        handleBtnSearchItemPembelian($btnSearchItemPembelian);
        handleBtnSearchPermintaan($btnSearchPermintaan);
        
        calculateTotal();
    };

     var isValidLastRow = function(){
        var 
            $itemCodeEls    = $('input[name$="[code]"]',$tableDetailPembelian),
            $qtyELs         = $('input[name$="[qty]"]',$tableDetailPembelian),
            itemCode        = $itemCodeEls.eq($qtyELs.length-1).val(),
            qty             = $qtyELs.eq($qtyELs.length-1).val() * 1
        ;

        return (itemCode != '' && qty > 0)
    }

    var addPenawaranRow = function()
    { 
        var numRow = $('tbody tr', $tablePenawaran).length;

        var 
            $rowContainer     = $('tbody', $tablePenawaran),
            $newItemRow       = $(tplPenawaranRow(penawaranCounter++)).appendTo( $rowContainer );

            handleUploadify(penawaranCounter);
            handleBtnDeletePenawaran($('button.del-this-penawaran', $newItemRow));
           
    }

    function handleUploadify(counter)
    {
        counter = parseInt(counter) - 1;
        var ul = $('#upload_'+counter+' ul');

       
        // Initialize the jQuery File Upload plugin
        $('#pdf_file_'+counter).fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop_'+counter),
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
                // alert(filename);
                // tpl.find('div.thumbnail').html('<a class="fancybox-button" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a>');
                $('input#penawaran_url_' + counter ).attr('value',filename);
                // Add the HTML to the UL element
                // ul.html(tpl);
                // data.context = tpl.prependTo(ul);

                // handleFancybox();
                Metronic.unblockUI();
                    // data.context = tpl.prependTo(ul);

            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                Metronic.blockUI({boxed: true});
            },


            fail:function(e, data){
                // Something has gone wrong!
                alert(data.result.filename);
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

    var handleBtnAddPenawaran = function($btn) {
        $btn.click(function() {
            addPenawaranRow();
        });
    };

    var handleBtnDeletePenawaran = function($btn)
    {
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePenawaran);

        $btn.on('click', function(e){
            $row.remove();
            if($('tbody>tr', $tablePenawaran).length == 0){
                addPenawaranRow();
            }
            e.preventDefault();
        });
    };
   
    var handleBtnDeletePembelian = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableDetailPembelian);

        $btn.on('click', function(e){
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
                // if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableDetailPembelian).length == 0){
                        addItemRow();
                    }
                    // focusLastItemCode();
                // }
            // });
            calculateTotal();
            e.preventDefault();
        });
    };

    var calculateTotal = function(){
        // alert('masuk function');
        var 
            $rows     = $('tbody>tr', $tableDetailPembelian), 
            $sub_total = $('.sub_total', $tableDetailPembelian),
            cost = 0,
            totalCost = 0,
            grandTotal = 0,
            grandTotalAll = 0,
            grandTotalAD = 0,
            grandTotalAT = 0,
            pph23 = 0
        ;

        $.each($rows, function(idx, row)
        {
            var 
                $row     = $(row), 
                itemCode = $('label[name$="[item_kode]"]', $row).text(),
                isPPH = parseInt($('input[name$="[is_pph]"]', $row).val()),
                is_active = parseInt($('input[name$="[is_active]"]', $row).val()),
                harga = parseInt($('input[name$="[item_harga]"]', $row).val()),
                diskon     = parseFloat($('input[name$="[item_diskon]"]', $row).val()*1),
                jumlah     = parseFloat($('input[name$="[jumlah]"]', $row).val()*1)
            ;
                // alert($('input[name$="[item_harga]"]', $row).val());

                // alert(harga);
            if (itemCode != '' && is_active == 1 ){
                cost = harga-(harga*diskon/100);
                // alert(cost);
                totalCost = cost*jumlah;

               // alert(totalCost);
                
                $('label[name$="[item_sub_total]"]', $row).text(mb.formatRp(parseFloat(totalCost)));
                $('input[name$="[item_sub_total]"]', $row).val(parseFloat(totalCost));
                $('input[name$="[item_total]"]', $row).val(parseFloat(totalCost));

                if(isPPH == 1){
                    
                    console.log(parseFloat($('input#pph_23').val())/100);

                    pph23 = pph23 + (parseFloat($('input#pph_23').val())/100) * totalCost;
                    $('input#total_pph_23').val(pph23);
                    $('input#total_pph_23_hidden').val(pph23);
                    $('input#pph_23_nominal').val(pph23);
                }

            }

        });

        $.each($sub_total, function(){
            // alert(parseInt($(this).val()));
            grandTotal = grandTotal + parseInt($(this).val());
        });

        $('input#total').val(mb.formatTanpaRp(grandTotal));
        $('div#total').text(mb.formatTanpaRp(grandTotal));
        $('input#tot_hidden').val(grandTotal);
        $('input#tot_hidden').attr('value',grandTotal);

        grandTotalAD = grandTotal - parseFloat(grandTotal * parseFloat($('input#diskon').val())/100);

        $('input#total_after_disc').val(mb.formatTanpaRp(grandTotalAD));
        $('input#total_after_disc_hidden').val(grandTotalAD);

        grandTotalAT =  grandTotalAD + parseFloat(grandTotalAD * parseFloat($('input#pph').val())/100);       
        
        $('input#total_after_tax').val(mb.formatTanpaRp(grandTotalAT));
        $('input#total_after_tax_hidden').val(grandTotalAT);

        grandTotalAll =  grandTotalAT - parseFloat($('input#pembulatan').val()) - parseFloat($('input#pph_23_nominal').val());       
        $('input#grand_total').val(mb.formatTanpaRp(grandTotalAll));
        $('input#grand_total_hidden').val(grandTotalAll);

        grandTotalAllSetujuDiskon = grandTotal - parseFloat(grandTotal * parseFloat($('input#disk_angka').val())/100);
        grandTotalAllSetujuTax =  grandTotalAllSetujuDiskon + parseFloat(grandTotalAllSetujuDiskon * parseFloat($('input#ppn_hidden').val())/100);       
        grandTotalAllSetuju =  grandTotalAllSetujuTax - 0;
        
        $('input#grand_total').val(mb.formatTanpaRp(grandTotalAll));
        $('input#grand_total_hidden').val(grandTotalAll); 

        $('td#grand_tot').text(mb.formatTanpaRp(grandTotalAllSetuju));
        $('input#grand_tot_hidden').val(grandTotalAllSetuju);
        dp = parseFloat($('input#dp').val());

        dp_nominal = (dp/100)*grandTotalAll;
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotalAll-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotalAll-dp_nominal);
        $('input#diskon_nominal').val(parseInt(grandTotal * parseInt($('input#diskon').val())/100));
        $('input#disk_hidden').val(parseInt(grandTotal * parseInt($('input#disk_angka').val())/100));
        $('input#pph_nominal').val(parseInt(grandTotalAD * parseInt($('input#pph').val())/100));
        // $('#total_before_discount_hidden').val(totalCost);

        // $('#total_before_discount_hidden').val(totalCost);
    };

    var calculateTotalDiskon = function()
    {
        // alert('masuk function');
        var 
            grandTotal = parseInt($('input#total_hidden').val()),
            diskon_nominal = $('input#diskon_nominal').val();

            diskon_persen = parseInt(diskon_nominal * 100)/grandTotal;
            diskon_persen = diskon_persen;


        $('input#diskon').val(diskon_persen);

        grandTotalAll = grandTotal - parseInt(grandTotal * parseInt($('input#diskon').val())/100) + parseInt(grandTotal * parseInt($('input#pph').val())/100);
        grandTotalAll =  grandTotalAll - parseFloat($('input#pembulatan').val());
        $('input#grand_total').val(mb.formatTanpaRp(grandTotalAll));
        $('input#grand_total_hidden').val(grandTotalAll);
        dp = parseFloat($('input#dp').val());

        dp_nominal = (dp/100)*grandTotalAll;
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotalAll-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotalAll-dp_nominal);
        // $('#total_before_discount_hidden').val(totalCost);
    };

    var calculateTotalPPh = function()
    {
        // alert('masuk function');
        var 
            grandTotal = parseInt($('input#total_hidden').val()),
            pph_nominal = $('input#pph_nominal').val();

            pph_persen = parseInt(pph_nominal * 100)/grandTotal;
            pph_persen = pph_persen;


        $('input#pph').val(pph_persen);

        grandTotalAll = grandTotal - parseInt(grandTotal * parseInt($('input#diskon').val())/100) + parseInt(grandTotal * parseInt($('input#pph').val())/100);
        grandTotalAll =  grandTotalAll - parseFloat($('input#pembulatan').val());
        $('input#grand_total').val(mb.formatTanpaRp(grandTotalAll));
        $('input#grand_total_hidden').val(grandTotalAll);
        dp = parseFloat($('input#dp').val());

        dp_nominal = (dp/100)*grandTotalAll;
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotalAll-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotalAll-dp_nominal);
        // $('#total_before_discount_hidden').val(totalCost);
    };
    var calculateTotalDP = function()
    {
        var grandTotal = parseFloat($('input#grand_total_hidden').val());

        dp = parseFloat($('input#dp').val());

        dp_nominal = (dp/100)*grandTotal;

        $('input#dp_nominal').val(dp_nominal);
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotal-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotal-dp_nominal);
    };

    var calculateTotalDPNominal = function()
    {
        var grandTotal = parseFloat($('input#grand_total_hidden').val());

        dp_nominal = parseFloat($('input#dp_nominal').val());

        dp = (dp_nominal*100)/grandTotal;

        $('input#dp').val(dp);
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotal-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotal-dp_nominal);
    };

    var calculateTotalBungaPersen = function()
    {
        var sisaBayar = parseFloat($('input#sisa_bayar_hidden').val());

        bunga_persen = parseFloat($('input#bunga_persen').val());
        kelipatan = parseFloat($('input#kelipatan').val());

        bunga_nominal = (bunga_persen/100)*sisaBayar;

        $('input#bunga_nominal').val(bunga_nominal);
        $('input#setoran').val((sisaBayar + bunga_nominal) / kelipatan);
        $('input#total_bayar').val(parseFloat($('input#setoran').val()) * kelipatan);
    };

    var calculateTotalBungaNominal = function()
    {
        var sisaBayar = parseFloat($('input#sisa_bayar_hidden').val());

        bunga_nominal = parseFloat($('input#bunga_nominal').val());
        kelipatan = parseFloat($('input#kelipatan').val());

        bunga_persen = (bunga_nominal*100)/sisaBayar;

        $('input#bunga_persen').val(bunga_persen);
        $('input#setoran').val((sisaBayar + bunga_nominal) / kelipatan);
        $('input#total_bayar').val(parseFloat($('input#setoran').val()) * kelipatan);

    };

    var handleBtnSearchItemPembelian = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // var rowStatus  = $btn.closest('tr').prop('class');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

            if ($lastPopoverItemPembelian != null) $lastPopoverItemPembelian.popover('hide');

            $lastPopoverItemPembelian = $btn;

            $popoverItemContentPembelian.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContentPembelian);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContentPembelian.hide();
            $popoverItemContentPembelian.appendTo($('.page-content'));

            $lastPopoverItemPembelian = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableItems = function(){
        oTableItemSearch = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_search_item/' + $('input#id_supplier').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'item.kode kode', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },               ]
        });

        var $btnSelects = $('a.select', $tableItemSearch);
        handleItemSearchSelectPembelian( $btnSelects );

        $tableItemSearch.on('draw.dt', function (){
            $('.btn', this).tooltip();

            var $btnSelect = $('a.select', this);
            handleItemSearchSelectPembelian( $btnSelect );
            
        } );

        $popoverItemContentPembelian.hide();        
    };

    var handleItemSearchSelectPembelian = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input[name="rowItemId"]', $parentPop).val(),
                statusRow       = $('input[name="rowStatus"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableDetailPembelian),
                $row_status = '',
                $itemId   = null,
                $itemKode = null,
                $itemNama = null,
                $classrow   = $('.table_item', $tableDetailPembelian),
                ItemIdAll = $('input[name$="[item_id]"]', $classrow),
                itemId = $(this).data('item').id
                ;        


           
                $itemId              = $('input[name$="[item_id]"]', $row);
                $itemKode            = $('input[name$="[item_kode]"]', $row);
                $itemNama            = $('input[name$="[item_nama]"]', $row);
                $itemSatuan          = $('select[name$="[satuan_id]"]', $row);
                $SearchSatuan        = $('input[name$="[satuan_nama]"]', $row),
                $itemHarga           = $('input[name$="[item_harga]"]', $row),
                $itemLabelKode       = $('label[name$="[item_kode]"]', $row);
                $itemLabelNama       = $('label[name$="[item_nama]"]', $row);
                $itemLabelSyarat     = $('label[name$="[item_syarat]"]', $row);
                $itemLabelStok       = $('label[name$="[stok]"]', $row);
                $itemLabelHarga      = $('label[name$="[item_harga]"]', $row);
                $itemLabelTotal      = $('label[name$="[item_total]"]', $row);
                $btnSearchPermintaan = $('a.search-jumlah', $row);


                $('.search-item', $tableDetailPembelian).popover('hide');
                
                found = false;
                // $.each(ItemIdAll, function(idx, value){
                //     // alert(itemId);
                //     if(itemId == this.value)
                //     {
                //         found = true;
                //     }

                //     // alert(this.value);
                // });

                
                // console.log($itemId);

                var primary = $(this).data('satuan_primary').id;
                var name = $(this).data('satuan_primary').nama;

                $('a.add-jumlah', $row).attr('href', baseAppUrl + 'add_jumlah/'+rowId+'/'+$(this).data('item').id+'/'+primary);
                $('a.add-jumlah', $row).removeAttr('disabled');
                
                // alert(name);

                if(found == false)
                {
                    $.ajax
                    ({ 
                        type: 'POST',
                        url: baseAppUrl +  "get_item_satuan",  
                        data:  {item_id : $(this).data('item').id, supplier_id: $(this).data('item').id_sup},  
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success:function(results)          //on recieve of reply
                        { 
                            $itemSatuan.empty();

                            //munculin semua data dari hasil post
                            $.each(results, function(key, value) {
                                $itemSatuan.prepend($("<option></option>")
                                    .attr({ "value" : value.id, "data-harga" : value.harga, "data-min" : value.minimum_order, "data-max" : value.kelipatan_order }).text(value.nama));
                                $itemSatuan.val(primary);
                                if(value.is_primary)
                                {
                                    $itemLabelSyarat.text(value.minimum_order +'/'+ value.kelipatan_order);
                                    $itemLabelHarga.text(mb.formatRp(parseInt(value.harga), 10));
                                    $itemHarga.val(parseInt(value.harga));
                                }

                            });
                            
                        },

                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
                    if ($itemKode.val() == "") {
                        $itemId.val($(this).data('item').id);            
                        $itemKode.val($(this).data('item').item_kode);
                        $itemNama.val($(this).data('item').item_nama);
                        // $itemHarga.val(parseInt($(this).data('item').harga));
                        $SearchSatuan.val(name);
                        $itemLabelKode.text($(this).data('item').item_kode);
                        $itemLabelNama.text($(this).data('item').item_nama);
                        $itemLabelStok.text($(this).data('info').stok);
                        $itemLabelSyarat.text($(this).data('item').min_order +'/'+ $(this).data('item').max_order);
                        // $itemLabelHarga.text(mb.formatRp(parseInt($(this).data('item').harga), 10));
                        $btnSearchPermintaan.attr('data-id', $(this).data('item').id);
                        $btnSearchPermintaan.attr('data-satuan', primary);

                        addItemRow();
                        e.preventDefault();
                    }else{
                        $itemId.val($(this).data('item').id);            
                        $itemKode.val($(this).data('item').item_kode);
                        $itemNama.val($(this).data('item').item_nama);
                        // $itemHarga.val(parseInt($(this).data('item').harga));
                        $SearchSatuan.val(name);
                        $itemLabelKode.text($(this).data('item').item_kode);
                        $itemLabelNama.text($(this).data('item').item_nama);
                        $itemLabelStok.text($(this).data('info').stok);
                        $itemLabelSyarat.text($(this).data('item').min_order +'/'+ $(this).data('item').max_order);
                        // $itemLabelHarga.text($(this).data('item').harga);
                        $btnSearchPermintaan.attr('data-id', $(this).data('item').id);
                        $btnSearchPermintaan.attr('data-satuan', primary);
                    }
                    
                }

                //  $itemSatuan.on('change', function(){
                //         handeSelectSatuan($row, $(this).val());
                //         $btnSearchPermintaan.attr('data-satuan', $(this).val());
                // });

                // addItemRow();
                calculateTotal();
                handleSelectSatuan();
            
                // oTablePermintaanTerdaftar.api().ajax.url(baseAppUrl + 'listing_permintaan_terdaftar/0' + $(this).data('item').id).load();            
                // oTableCariPermintaan.api().ajax.url(baseAppUrl + 'listing_search_permintaan/0' + $(this).data('item').id).load();            
            
        });     
    };

    var handleSelectSatuan = function()
    {   

        $('select.satuan').on('change', function(){

            row = $(this).data('row');

            $('select#item_satuan_'+ row).attr('readonly');
            $('a#search_jumlah_permintaan_'+ row).attr('data-satuan', $(this).val());

            $itemSatuanId      = $('input#items_satuan_nama_' + row),
            $itemHarga       = $('input#items_harga_' + row),
            $itemLabelHarga  = $('label#items_hargaEl_' + row),
            $itemLabelSyarat = $('label#item_syarat_'+ row);
            // $SearchSatuan    = $('input#items_satuan_nama_',+ $row),

            // $itemKonversi      = $('input#items_konversi_' + row);
            // $totalStockPrimary = $('input#items_total_stock_' + row);
            // $itemJumlah        = $('input#items_jumlah_' + row);

            $harga    = $('option:selected', this).attr('data-harga');
            $min_order    = $('option:selected', this).attr('data-min');
            $max_order    = $('option:selected', this).attr('data-max');
            $text    = $('option:selected', this).text();
            // alert($('label#items_harga_' + row));
            // $konversi = $('option:selected', this).attr('data-konversi');

            $itemSatuanId.val($text);
            $itemHarga.val($harga);
            $itemLabelHarga.text(mb.formatRp(parseInt($harga)));
            $itemLabelSyarat.text($min_order+'/'+$max_order);


            $('label[name="satuan_alokasi"]').text($itemSatuanId.val());
            $('a#tambah-link').attr('href', baseAppUrl+'daftar_link/'+$('a#search_jumlah_permintaan_'+ row).data('id')+'/'+row+'/'+$(this).val());
        //     $total_stock = $totalStockPrimary.val();

        //     // utk set jumlah max inputnya sesuai total seluruh stock / konversi
        //     $max = parseInt($total_stock / $konversi);
        //     $itemJumlah.attr('max', $max);

            calculateTotal();

        })

    };


    var handleBtnSearchPermintaan = function($btn){
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

            if ($lastPopoverJumlahPesan != null) $lastPopoverJumlahPesan.popover('hide');

            $lastPopoverJumlahPesan = $btn;

            $popoverJumlahPesan.show();

        }).on('shown.bs.popover', function(e){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            var idRow = $('tr#'+ rowId);

            $('label[name="satuan_alokasi"]').text($('input[name$="[satuan_nama]"]', idRow).val());
            $('a#tambah-link').attr('href', baseAppUrl+'daftar_link/'+$(this).data('id')+'/'+parseInt($(this).data('row'))+'/'+$(this).data('satuan'));
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverJumlahPesan);

            $('#table_search_permintaan > tbody').remove();
            var tbody = $('<tbody></tbody>');
            $('table#table_search_permintaan').append(tbody);
            $('table#table_search_permintaan > tbody').append($('div#tabel_simpan_data_'+parseInt($(this).data('row'))).html());
            // $('#table_search_permintaan > tbody').html($('div#simpan_data_'+parseInt($(this).data('row')+1)).html());


        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverJumlahPesan.hide();
            $popoverJumlahPesan.appendTo($('.page-content'));

            $lastPopoverJumlahPesan = null;


        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
            // handleBtnSearchPermintaan();
        });
    };

    var handleBtnSearchPenerima = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

            if ($lastPopoverPenerima != null) $lastPopoverPenerima.popover('hide');

            $lastPopoverPenerima = $btn;

            $popoverPenerimaContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPenerimaContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverPenerimaContent.hide();
            $popoverPenerimaContent.appendTo($('.page-content'));

            $lastPopoverPenerima = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableSearchPermintaan = function(){
        oTableCariPermintaan = $tableCariPermintaan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_search_permintaan/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false }              ]
        });

        // var $btnSelects = $('a.select', $tableItemSearch);
        // handleItemSearchSelectPembelian( $btnSelects );

        // $tableItemSearch.on('draw.dt', function (){
        //     var $btnSelect = $('a.select', this);
        //     handleItemSearchSelectPembelian( $btnSelect );
            
        // } );

        $popoverJumlahPesan.hide();        
    };



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

            $popContainer.css({minWidth: '1150px', maxWidth: '1150px'});

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
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

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
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $('#table_pilih_supplier_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_supplier_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePilihSupplier.on('draw.dt', function (){
            var $btnSelect = $('a.select-supplier', this);
            handlePilihSupplierSelect( $btnSelect );
            
        } );

        $popoverItemContent.hide();
    }

    var handlePilihSupplierSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdSupplier     = $('input[name="id_supplier"]'),
                $NamaSupplier   = $('input[name="nama_supplier"]'),
                $KontakSupplier = $('input[name="kontak_supplier"]'),
                $AlamatSupplier = $('textarea[name="alamat_supplier"]'),
                $EmailSupplier  = $('input[name="email_supplier"]'),
                $tipePembayaran = $('select[name="tipe_pembayaran"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-supplier').popover('hide'); 

            var pembayaran = $(this).data('pembayaran');

            lama_tempo = '';
            $tipePembayaran.empty();
            $tipePembayaran.append($("<option></option>").attr("value", '').text('Pilih Pembayaran'));
            $.each(pembayaran, function(key, value) 
            {
                lama_tempo = '';
                if (value.lama_tempo != null && value.lama_tempo != '') {
                    lama_tempo = value.lama_tempo + ' hari';
                };
                $tipePembayaran.append($("<option></option>").attr({"value": value.id, "data-tempo": value.lama_tempo}).text(value.nama + ' ' + lama_tempo));
            });


            $IdSupplier.val($(this).data('item').id);
            $NamaSupplier.val($(this).data('item').nama);
            $KontakSupplier.val($(this).data('item').kontak_person +' ('+ $(this).data('item').no_telp +')');
            $AlamatSupplier.val($(this).data('item').alamat+','+$(this).data('item').kelurahan+','+$(this).data('item').kecamatan+','+$(this).data('item').kota+','+$(this).data('item').propinsi+','+$(this).data('negara'));
            $EmailSupplier.val($(this).data('item').email); 

            e.preventDefault();
        });     
    };

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true,
                format : 'dd M yyyy'
            });

            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true,
                format : 'dd M yyyy'
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
    }

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

     var handleConfirmSaveDraft = function(){
        $('a#confirm_save_draft', $form).click(function() {
            // if (! $form.valid()) return;
            $('input#save_draft').val(1);
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $('#save', $form).click();
                }
            });
        });
    };

    var handleDeleteDraft = function(){
        $('a#confirm_delete', $form).click(function() {

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    location.href = baseAppUrl + 'delete_draft/' + $('input#pk_value').val();
                }
            });
        });
    };

    var handleBtnDeleteItemsDB = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableDetailPembelian);

        $btn.on('click', function(e){
            bootbox.confirm('Are you sure want to delete this item?', function(result){
                if (result==true) {

                    $('input[name$="[is_deleted]"]', $row).attr('value', 1);   

                    $row.hide(); //hide
                    if($('tbody>tr', $tableDetailPembelian).length == 0){
                        addItemRow();

                    }
                }
            });
            calculateTotal();
            e.preventDefault();
        });
    };

    $('input#check').on('change', function(){
        var checked = $(this).is(":checked");
        if(checked){
            // alert('a');
            $('textarea#info_supplier').removeAttr('readonly', true);
        }
        else {

            $('textarea#info_supplier').attr('readonly', true);
        }
    });

    var handleDataTableCabang = function() 
    {
        $tablePilihCabang.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_penerima_cabang/' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $('#table_pilih_cabang_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_cabang_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePilihCabang.on('draw.dt', function (){
            var $btnSelect = $('a.select-cabang', this);
            handlePilihCabangSelect( $btnSelect );
            
        } );

        $popoverPenerimaContent.hide();
    }

    var handlePilihCabangSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdPenerima       = $('input[name="id_penerima"]'),
                $NamaPenerima     = $('input[name="nama_penerima"]'),
                $KontakPenerima     = $('input[name="kontak_penerima"]'),
                $AlamatPenerima     = $('textarea[name="alamat_penerima"]'),
                $EmailPenerima     = $('input[name="email_penerima"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-penerima-cabang').popover('hide');   

            if($(this).data('item').tipe == 2)
            {
                $btnSearchPermintaan.show();
                $btnSearchItemPembelian.attr('data-tipe', '2');

            }
            else
            {
                // $btnSearchPermintaan.addClass('hidden');
                $btnSearchItemPembelian.attr('data-tipe', '1');
            }       

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdPenerima.val($(this).data('item').id);
            $NamaPenerima.val($(this).data('item').nama_cabang);
            $KontakPenerima.val($(this).data('item').penanggung_jawab +' ('+ $(this).data('item').no_telp +')');
            $AlamatPenerima.val($(this).data('item').alamat+', '+$(this).data('item').kelurahan+', '+$(this).data('item').kecamatan+', '+$(this).data('item').kota+', '+$(this).data('item').propinsi+', '+$(this).data('negara'));
            $EmailPenerima.val($(this).data('item').email);
            // alert($itemIdEl.val($(this).data('item').id));

            e.preventDefault();
        });     
    };

    var handleBtnSearchPenerimaCustomer = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

            if ($lastPopoverPenerimaCustomer != null) $lastPopoverPenerimaCustomer.popover('hide');

            $lastPopoverPenerimaCustomer = $btn;

            $popoverPenerimaContentCustomer.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPenerimaContentCustomer);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverPenerimaContentCustomer.hide();
            $popoverPenerimaContentCustomer.appendTo($('.page-content'));

            $lastPopoverPenerimaCustomer = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableCustomer = function() 
    {
        $tablePilihCustomer.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_penerima_customer/' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $('#table_pilih_customer_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_customer_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

         $tablePilihCustomer.on('draw.dt', function (){
            var $btnSelect = $('a.select-customer', this);
            handlePilihCustomerSelect( $btnSelect );
            
        } );

        $popoverPenerimaContentCustomer.hide();
    }

    var handlePilihCustomerSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdPenerima       = $('input[name="id_penerima"]'),
                $NamaPenerima     = $('input[name="nama_penerima"]'),
                $KontakPenerima     = $('input[name="kontak_penerima"]'),
                $AlamatPenerima     = $('textarea[name="alamat_penerima"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-penerima-customer').popover('hide');  

            if($(this).data('tipe') == 1)
            {
                $btnSearchPermintaan.addClass('hidden');
            }        

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdPenerima.val($(this).data('item').id);
            $NamaPenerima.val($(this).data('item').nama_customer);
            $KontakPenerima.val($(this).data('item').orang_bersangkutan +' ('+ $(this).data('item').no_telp +')');
            $AlamatPenerima.val($(this).data('item').alamat+', '+$(this).data('item').kelurahan+', '+$(this).data('item').kecamatan+', '+$(this).data('item').kota+', '+$(this).data('item').propinsi+', '+$(this).data('item').negara);
          
            // alert($itemIdEl.val($(this).data('item').id));

            e.preventDefault();
        });     
    };

    var handleChangeJenisKirim = function(){
        $('input[name="is_single"]').on('change', function(){

            var single = $(this).val();

            if(single == 1){
                $('div#tgl_kirim').removeClass('hidden');
                $('input#tanggal_kirim').attr('required','required');
                $('input[name$="[jumlah]"]', $tableDetailPembelian).removeAttr('readonly');
                $('a.add-jumlah', $tableDetailPembelian).addClass('hidden');
            }if(single == 0){
                $('div#tgl_kirim').addClass('hidden');
                $('input#tanggal_kirim').removeAttr('required');
                $('input[name$="[jumlah]"]', $tableDetailPembelian).attr('readonly','readonly');
                $('a.add-jumlah', $tableDetailPembelian).removeClass('hidden');
            }
        });
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/pembelian/';
        initform();
        handleDatePickers();
        handleDataTableCabang();
        handleDataTableSupplier();
        handleDataTableItems();
        handleConfirmSave();
        handleDeleteDraft();
        handleConfirmSaveDraft();
        handleDataTableCustomer();
        handleChangeJenisKirim();
    };
 }(mb.app.daftar_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_permintaan_po.init();
});