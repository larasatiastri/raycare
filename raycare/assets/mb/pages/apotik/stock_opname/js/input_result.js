mb.app.stockOpname = mb.app.stockOpname || {};
mb.app.stockOpname.inputResult = mb.app.stockOpname.inputResult || {};

// mb.app.stockOpname.inputResult namespace
(function(o){

    var $form = $('#form_input_result');
    var $tableInputResult = $('#table_input_stock_opname');
    var baseAppUrl = '';
    
     var   $popoverKomposisiContent        = $('#popover_komposisi_content');
    var    $popoverKomposisiHistoryContent = $('#popover_komposisi_history_content');
     var   $lastPopoverKomposisi           = null;
     var   $lastPopoverKomposisiHistory    = null;
     var   $lastPopoverKeterangan          = null;
    var $tableKomposisiItem             = $('#table_komposisi_item');
    var $tableKomposisiItemHistory      = $('#table_komposisi_item_history');
    var $tableKomposisiManual           = $('#table_komposisi_manual');
    // var initForm = function(){
    //   $('input#qty:hidden').val();
    // }

    var handleDataTable = function(){
    // alert($('input#warehouse_id').val());
    //setup datatable

    $tableInputResult.dataTable({
      'processing'              : true,
      'serverSide'              : true,
      'ajax'                  : {
                'url' : baseAppUrl + 'listingItem/' + $('input#id').val() + '/' + $('input#warehouse_id').val(),
                'type' : 'POST',
            }, 
     
      'pageLength'           : 10,
      'lengthMenu'              : [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
      'order'                : [[0, 'asc']],
      'columns'                : [
        { 'name' : 'item.kode', 'visible' : true, 'searchable': true, 'orderable': true },
        { 'name' : 'item.nama', 'visible' : true, 'searchable': true, 'orderable': true },
        { 'visible' : true, 'searchable': false, 'orderable': false },
        { 'visible' : true, 'searchable': false, 'orderable': false },
      ],
      'createdRow' : function( row, data, dataIndex ) {
                    $('.check-identitas', row).closest('tr').prop('id','item_row_'+dataIndex);
                     var $btnCheckIdentitas = $('.check-identitas',row);
                     var $btnCheckIdentitas3 = $('.identitas',row);
                     handleInfoIdentitas($btnCheckIdentitas);
                     $('select[name$="[item_satuan1]"]',row).on( 'change', function () {
                        
                            $('.identitas', row).attr('href', baseAppUrl + 'modal_identitas/'+ $btnCheckIdentitas3.data('itemid') + '/' + this.value + '/' + $btnCheckIdentitas3.data('rowid'));
                     });
                   // row.attr("id","item_row_1");
                    // var $btnKomposisiItem  = $('.komposisi-item',row);
                    // handleBtnKomposisiItem($btnKomposisiItem);
                }
    
    });//.fnSetFilteringDelay(500);
      $tableInputResult.on('draw.dt', function (){
          $('a[name="komposisi[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');

                    $tableKomposisiItem.api().ajax.url(baseAppUrl + 'listing_komposisi_item/' + id).load();
                    $tableKomposisiManual.api().ajax.url(baseAppUrl + 'listing_komposisi_manual/' + id).load();
                    
            });
            // var $btnKomposisiItem  = $('.komposisi-item');
            // handleBtnKomposisiItem($btnKomposisiItem);
      });
    $('#table_input_stock_opname_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
    $('#table_input_stock_opname_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline");
    
  };

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

    var handleDatePickers = function () {
      $('.date', $form).datetimepicker({
        rtl: Metronic.isRTL(),
        format : 'dd MM yyyy hh:ii',
        autoclose: true,

      });
      $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }

    var handleDataTableKomposisiItem = function(){
        $tableKomposisiItem.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_komposisi_item',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'item.id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'item.kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true }
                ]
        });

        $tableKomposisiItem.on('draw.dt', function (){
            $('.btn', this).tooltip();         
        } );    
    };

    var handleDataTableKomposisiManual = function(){
        $tableKomposisiManual.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_komposisi_manual',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'resep_obat_racikan_detail_manual.id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'resep_obat_racikan_detail_manual.keterangan','visible' : true, 'searchable': true, 'orderable': true },
                ]
        });

        $tableKomposisiManual.on('draw.dt', function (){
            $('.btn', this).tooltip();
            var $colItems = $('.show-notes', this);

            $.each($colItems, function(idx, colItem){
                var
                    $colItem = $(colItem),
                    itemsData = $colItem.data('content');
            
            $colItem.popover({
                html : true,
                container : 'body',
                placement : 'bottom',
                content: function(){

                    var html = '<table class="table table-striped table-hover">';
                        html += '<tr>';
                        html += '<td>'+itemsData+'</td>';
                        html += '</tr>';
                        html += '</table>';
                        return html;
                }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '360px'});

                    if ($lastPopoverKeterangan !== null) $lastPopoverKeterangan.popover('hide');
                    $lastPopoverKeterangan = $colItem;
                }).on('hide.bs.popover', function(){
                    $lastPopoverKeterangan = null;
                }).on('click', function(e){
                });
            });         
        });    
    };


var handleBtnKomposisiItem = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
    

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverKomposisi != null) $lastPopoverKomposisi.popover('hide');

            $lastPopoverKomposisi = $btn;

            $popoverKomposisiContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverKomposisiContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverKomposisiContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverKomposisiContent.hide();
            $popoverKomposisiContent.appendTo($('.page-content'));

            $lastPopoverKomposisi = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){

            e.preventDefault();
        });
    };

 var handleInfoIdentitas = function($btn){
      //  var $btnCheckIdentitas = $('.check-identitas');

        $btn.on('click', function(){
 
            var id = $(this).data('row-check');
            // alert(id);
            if ($('input#items_jumlah_'+id).val() == 0) {
                // alert('masih 0');
                $('a#info_identitas_'+id).click();
            }else{
                // alert('sudah ada');
                var msg = $(this).data('confirm');
                bootbox.confirm(msg, function(result) {
                    if (result==true) {
                        $('a#info_identitas_'+id).click();
                    }
                });
            }

        });
    }

    o.init = function(){
  
    //	$form.validate();
      baseAppUrl = mb.baseUrl() + 'apotik/stock_opname/';

    	handleConfirmSave();
     handleDatePickers();
     handleDataTable();
    // handleInfoIdentitas();
       // handleDataTableKomposisiItem();
        
       //  handleDataTableKomposisiManual();
     // $popoverKomposisiContent.hide();
        
      // initForm();
    };

}(mb.app.stockOpname.inputResult));


// initialize  mb.app.stockOpname.inputResult
$(function(){
	mb.app.stockOpname.inputResult.init();
});