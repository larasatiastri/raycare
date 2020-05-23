mb.app.harga_jual = mb.app.harga_jual || {};
(function(o){

    var 
        baseAppUrl = '',
        $form      = $('#form_harga_jual'),
        $tableHargaJual = $('#table_harga_jual')


    var handleDataTableHargaJual = function() 
    {

//alert('test');
        oTableHargaJual = $tableHargaJual.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing/0/0',
                'type' : 'POST',
            },          

            'paginate'              : true,
            'filter'                : true,
            'info'                  : false,
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'columns'               : [
                { 'name':'item.id id', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'item.kode kode', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'item_satuan.nama satuan', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'item_sub_kategori.nama sub_kategori','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'item_sub_kategori.nama sub_kategori','visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableHargaJual.on('draw.dt', function (){

            handleDatePickers();
            $('a[name="edit[]"]', this).click(function(){

                    var $anchor = $(this),
                          id    = $anchor.data('index');

                    $('div#btn_edit_'+id).addClass('hidden');
                    $('div#btn_cancel_save_'+id).removeClass('hidden');
                    $('div#div_harga_'+id).addClass('hidden');
                    $('div#div_tanggal_'+id).addClass('hidden');
                    $('div#div_tanggal_edit_'+id).removeClass('hidden');
                    $('input#input_harga_'+id).removeClass('hidden');

            });

            $('a[name="batal[]"]', this).click(function(){

                    var $anchor = $(this),
                          id    = $anchor.data('index');


                    $('div#btn_edit_'+id).removeClass('hidden');
                    $('div#btn_cancel_save_'+id).addClass('hidden');
                    $('div#div_harga_'+id).removeClass('hidden');
                    $('div#div_tanggal_'+id).removeClass('hidden');
                    $('div#div_tanggal_edit_'+id).addClass('hidden');
                    $('input#input_harga_'+id).addClass('hidden');

            });



            $('a[name="simpan[]"]', this).click(function(){

                    var $anchor = $(this),
                          id    = $anchor.data('index');


                    var item_id = $('input#input_item_id_'+id).val(),
                        item_satuan_id = $('input#input_item_satuan_id_'+id).val(),
                        harga = $('input#input_harga_'+id).val(),
                        tanggal = $('input#input_tanggal_'+id).val();


                    $.ajax({

                        type     : 'POST',
                        url      : baseAppUrl + 'save',
                        data     : {item_id:item_id, item_satuan_id:item_satuan_id, tanggal:tanggal, harga:harga},
                        dataType : 'json',

                        success  : function( results ) {

                            
                            if(results.success == true){
                                
                                $('div#btn_edit_'+id).removeClass('hidden');
                    $('div#btn_cancel_save_'+id).addClass('hidden');
                    $('div#div_harga_'+id).removeClass('hidden');
                    $('div#div_tanggal_'+id).removeClass('hidden');
                    $('div#div_tanggal_edit_'+id).addClass('hidden');
                    $('input#input_harga_'+id).addClass('hidden');
                                oTableHargaJual.api().ajax.url(baseAppUrl + 'listing').load();
                                mb.showToast('success',results.msg,'Berhasil');

                            }

                            if(results.success == false){
                                $('div#btn_edit_'+id).removeClass('hidden');
                    $('div#btn_cancel_save_'+id).addClass('hidden');
                    $('div#div_harga_'+id).removeClass('hidden');
                    $('div#div_tanggal_'+id).removeClass('hidden');
                    $('div#div_tanggal_edit_'+id).addClass('hidden');
                    $('input#input_harga_'+id).addClass('hidden');
                                oTableHargaJual.api().ajax.url(baseAppUrl + 'listing').load();
                                mb.showToast('error',results.msg,'Gagal');

                            }

                        }

                    });

            });
        });
        
        $('select#kategori').on('change', function(){


            var kategori_id = $(this).val(),
                sub_kategori_id = $('select#sub_kategori').val(),
                $sub_kategori = $('select#sub_kategori');

            $.ajax
            ({ 
                type: 'POST',
                url: baseAppUrl +  "get_sub_kategori",  
                data:  {kategori_id:kategori_id},  
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success:function(data){ 
                    $sub_kategori.empty();

                    $sub_kategori.html($("<option></option>")
                                .attr("value", 0).text('---Semua Sub Kategori---'));

                    $.each(data, function(idx, data){
                        $sub_kategori.append($("<option></option>")
                                .attr("value", data.id).text(data.nama+' ['+data.kode+']'));
                    });
                },
                complete : function(){
                    Metronic.unblockUI();
                }

            });

        });

        $('a#btn_search').click(function(){
            oTableHargaJual.api().ajax.url(baseAppUrl +  'listing/' + $("select#kategori").val() + '/' + $('select#sub_kategori').val()).load();
        });

        
            
    } 

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $tableHargaJual).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/harga_jual/';
        // handleMonthPeriode();
        handleDataTableHargaJual();
        //handleButtonRefresh();
        //handleSelectSubKategori();
        // getTrans($("#tgl_awal", $form).val(),$("#tgl_akhir", $form).val());
    };
 }(mb.app.harga_jual));


// initialize  mb.app.home.table
$(function(){
    mb.app.harga_jual.init();
});