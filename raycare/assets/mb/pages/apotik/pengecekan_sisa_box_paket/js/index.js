mb.app.pengecekan_sisa_box_paket = mb.app.pengecekan_sisa_box_paket || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_pengecekan_sisa_box_paket'),
        $popoverItemContent   = $('#popover_item_content'),
        $lastPopoverItem      = null,
        $tableTambahItem      = $('#tabel_tambah_item',$form),
        $tableItemSearch      = $('#table_pilih_item'),
        tplItemRow            = $.validator.format($('#tpl_item_row',$form).text()),
        itemCounter           = 0;

    var initForm = function(){

        
        addItemRowPaket();
        handleTambahRow();

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

    
    function addItemRowPaket()
    {
        var numRow = $('tbody tr', $tableTambahItem).length;

        if (numRow > 0 && ! isValidLastRow()) return;
        var 
            $rowContainer         = $('tbody', $tableTambahItem),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer )
            ;
        $('input[name$="[kode]"]', $newItemRow).focus();

        $btnSearchItem = $('button.search-item', $newItemRow);
        handleBtnSearchItem($btnSearchItem);
        handleCountSubTotalItem();
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

        var tgl_awal = $("input#tgl_awal").val(),
            tgl_akhir = $("input#tgl_akhir").val();

        oTable = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item/'+tgl_awal+'/'+tgl_akhir,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'item.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'item.kode kode', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 't_box_paket_detail.bn_sn_lot bn_sn_lot', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 't_box_paket_detail.expire_date expire_date', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_pilih_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

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
           
                $itemIdDetailEl       = $('input[name$="[item_id_detail]"]', $row);
                $itemIdEl       = $('input[name$="[item_id]"]', $row);
                $itemCodeIn     = $('input[name$="[kode]"]', $row);
                $itemNameIn     = $('input[name$="[name]"]', $row);
                $itemBNIn     = $('input[name$="[bn]"]', $row);
                $itemEDIn     = $('input[name$="[ed]"]', $row);
                $itemEDdiv     = $('div[name$="[ed]"]', $row);
                $itemBNdiv     = $('div[name$="[bn]"]', $row);
                

                itemId = $(this).data('item')['item_id'];
                    
                $itemIdDetailEl.val($(this).data('item')['id']);
                $itemIdEl.val($(this).data('item')['item_id']);
                $itemCodeIn.val($(this).data('item')['kode']);
                $itemNameIn.val($(this).data('item')['nama']);
                $itemBNIn.val($(this).data('item')['bn_sn_lot']);
                $itemEDIn.val($(this).data('item')['expire_date']);
                $itemBNdiv.text($(this).data('item')['bn_sn_lot']);
                $itemEDdiv.text($(this).data('item')['expire_date']);


                addItemRowPaket();

            e.preventDefault();   
        });     
    };

    var handleCountSubTotalItem = function() {
        var $itemSubTotIn = $('input[name$="[sub_total]"]', $tableTambahItem);

        var grand_total = 0;
        $.each($itemSubTotIn, function(idx, subTot){
            var sub_total = parseInt($(this).val());

            grand_total = grand_total + sub_total;

        });

        $('th#total', $tableTambahItem).text(mb.formatRp(grand_total));
        $('input#total_hidden', $tableTambahItem).val(grand_total);


        var $diskon = $('input#diskon', $tableTambahItem);

        if($diskon.val() == ''){
            total_all = grand_total - parseInt(0);
        }else{
            total_all = grand_total - parseInt($diskon.val());
        }
        

        $('th#grand_total', $tableTambahItem).text(mb.formatRp(total_all));
        $('input#grand_total_hidden', $tableTambahItem).val(total_all);  

    };

    var handleDiskon = function(){
        var $diskon = $('input#diskon', $tableTambahItem);

        total_all = 0;
        $('input#diskon', $tableTambahItem).on('change', function() {

            grand_total = $('input#total_hidden', $tableTambahItem).val(); 
            total_all = grand_total - parseInt($(this).val());

            $('th#grand_total', $tableTambahItem).text(mb.formatRp(total_all));
            $('input#grand_total_hidden', $tableTambahItem).val(total_all);
            
        });


    }

    var handleTerdaftar = function()
    {
        $('a#btn_terdaftar').on('click', function(){
            $('a#btn_tidak_terdaftar').removeClass('btn-primary');
            $('a#btn_tidak_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('input#id_ref_pasien').attr('required','required');
            $('input#no_rekmed').attr('required','required');


            $('div.pasien_terdaftar').removeClass('hidden');
            $('input#nama_ref_pasien').attr('readonly','readonly');
            $('textarea#alamat_pasien').attr('readonly','readonly');
            $('input#tipe_pasien').val(1);
        });

        $('a#btn_tidak_terdaftar').on('click', function(){
            $('a#btn_terdaftar').removeClass('btn-primary');
            $('a#btn_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('input#id_ref_pasien').removeAttr('required');
            $('input#no_rekmed').removeAttr('required');

            $('div.pasien_terdaftar').addClass('hidden');
            $('input#nama_ref_pasien').removeAttr('readonly');
            $('textarea#alamat_pasien').removeAttr('readonly');
            $('input#tipe_pasien').val(2);
        });
    }

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

        var handleDateRangePicker = function() {
      $('#reportrange').daterangepicker({
            opens: (Metronic.isRTL() ? 'left' : 'right'),
            startDate: moment().subtract('days', 180),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2020',
            dateLimit: {
                days: 180
            },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Last 30 Days': [moment().subtract('days', 29), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            buttonClasses: ['btn'],
            applyClass: 'green',
            cancelClass: 'default',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            locale: {
                applyLabel: 'Apply',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        },
        function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('input#tgl_awal').val(start.format('D-MM-YYYY'));
            $('input#tgl_akhir').val(end.format('D-MM-YYYY'));
            
            oTable.api().ajax.url(baseAppUrl +  'listing_item' + '/'+$("#tgl_awal").val() + '/' + $("#tgl_akhir").val()).load();

            $('a#cetak_pdf').attr('href',baseAppUrl+'cetak_pdf/'+$("#tgl_awal").val() + '/' + $("#tgl_akhir").val());
        });
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().startOf('month').format('MMMM D, YYYY') + ' - ' + moment().endOf('month').format('MMMM D, YYYY'));
        $('input#tgl_awal').val(moment().startOf('month').format('D-MM-YYYY'));
        $('input#tgl_akhir').val(moment().endOf('month').format('D-MM-YYYY'));
    };
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/pengecekan_sisa_box_paket/';
        handleValidation();
        handleConfirmSave();
        handleDataTableItems();
        initForm();
        handleDateRangePicker();

    };
 }(mb.app.pengecekan_sisa_box_paket));


// initialize  mb.app.home.table
$(function(){
    mb.app.pengecekan_sisa_box_paket.init();
});