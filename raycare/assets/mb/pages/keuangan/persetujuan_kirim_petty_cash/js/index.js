mb.app.persetujuan_kirim_petty_cash = mb.app.persetujuan_kirim_petty_cash || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form   = $('#form_index'),
        $table1 = $('#table_persetujuan_kirim_petty_cash'),
        $lastPopoverItem = null
        ;

    var handleDataTable = function() 
    {
    	$table1.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
            'filter'                : true,
            'paginate'              : true,
            'info'                  : false,
            'pagingType'            : 'full_numbers',
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
        		]
        });
        $table1.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
      
            /////////////////tooltip///////////////////////////

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
                    $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '720px'});

                    if ($lastPopoverItem !== null) $lastPopoverItem.popover('hide');
                    $lastPopoverItem = $colItem;
                    }).on('hide.bs.popover', function(){
                        $lastPopoverItem = null;
                    }).on('click', function(e){
                });
            }); 

			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg);
			});

			var grandtotal_debit =  $('input#grandtotal_debit', this).val();
            var grandtotal_kredit =  $('input#grandtotal_kredit', this).val();
            var grandtotal_saldo =  $('input#grandtotal_saldo', this).val();

            if (!isNaN(grandtotal_debit)) {
                $('.grandtot_debit').text(grandtotal_debit);
            }else{
                $('.grandtot_debit').text("");
            };

            if (!isNaN(grandtotal_kredit)) {
                $('.grandtot_kredit').text(grandtotal_kredit);
            }else{
                $('.grandtot_kredit').text("");
            };

            if (!isNaN(grandtotal_saldo)) {
                $('.grandtot_saldo').text(grandtotal_saldo);
            }else{
                $('.grandtot_saldo').text("");
            };

        	// $('.grandtot_debit').text(mb.formatTanpaRp(parseInt(grandtotal_debit)));
            // $('.grandtot_kredit').text(mb.formatTanpaRp(parseInt(grandtotal_kredit)));
            // $('.grandtot_saldo').text(mb.formatTanpaRp(parseInt(grandtotal_saldo)));

						
		});
    }

    var handleDeleteRow = function(id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete/' +id;
			} 
		});
	
	};

	var handleSelectTemplate = function(){
        $('select#kasir').on('change', function(){
            // total = parseInt($('input#total_harga_detail').val());
            // alert(total);
            filter_date   = $('input#date').val();

            $table1.api().ajax.url(baseAppUrl + 'listing/' + $(this).val() + '/' + filter_date).load();
            
            
        });

    }

    var handleDatePickers = function () 
    {
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            format : 'MM yyyy',
            orientation: "left",
            minViewMode: 'months',  // only view month
            autoclose: true,

        }).on('changeDate', function(ev){

            var filter_date   = $('input#date').val();
            var kasir   	  = $('select#kasir').val();
            // alert(kasir);
            // 
            $table1.api().ajax.url(baseAppUrl + 'listing/' + kasir + '/' + filter_date).load();

            // oTable.fnSettings().sAjaxSource = baseAppUrl + 'listing/2/' + filter_date + '/';
            // oTable.fnClearTable();                

            // oTable2.fnSettings().sAjaxSource = baseAppUrl + 'listing/1/' + filter_date + '/';
            // oTable2.fnClearTable();

            // $('input#bulan').val($('input#date').val());
        });
        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }

	

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/persetujuan_kirim_petty_cash/';
        handleDataTable();
        handleSelectTemplate();
        handleDatePickers();

    };
 }(mb.app.persetujuan_kirim_petty_cash));


// initialize  mb.app.home.table
$(function(){
    mb.app.persetujuan_kirim_petty_cash.init();
});