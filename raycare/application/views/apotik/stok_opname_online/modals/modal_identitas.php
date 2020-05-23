<?
    $form_attr = array(
        "id"            => "form_identitas", 
        "name"          => "form_identitas", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="#" method="post" id="form_identitas" class="form-horizontal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Item Identitas</h4>
    </div>                             
    <div class="modal-body">
        <div class="portlet light" id="section-gambar">

            <div class="portlet-body form">
                <input type="hidden" id="id_so" name="so_id" value="">
                <input type="hidden" id="item_id" name="item_id" value="<?=$item_id?>">
                <input type="hidden" id="gudang_id" name="gudang_id" value="<?=$gudang_id?>">
                <input type="hidden" id="item_satuan_id" name="item_satuan_id" value="<?=$item_satuan_id?>">
                <input type="hidden" id="row_id" name="row_id" value="<?=$row_id?>">
                <input type="hidden" id="nama_satuan" name="nama_satuan" value="<?=$item_satuan_nama?>">
                <input type="hidden" id="total_jumlah" name="total_jumlah" value="0">
                <input type="hidden" id="total_stock" name="total_stock" value="0">
                <div class="form-body">
                    <input class="form-control search_identitas " id="search_identitas" name="search_identitas" placeholder="Cari Item">
                    <!--<label class="control-label col-md-10"><?=translate('Search', $this->session->userdata('language'))?> :</label>-->
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        
                    </div>

                    <div class="row">

                    </div>
                    <div id="show_modal_identitas">
                        
                    </div>
                    <a id="tambah_identitas" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("Tambah", $this->session->userdata("language"))?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary hidden" id="btnOK">OK</button>
        <a class="btn default" id="minCounter" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
        <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
    </div>


    </form>
 
    <script type="text/javascript">

        $(document).ready(function(){
            baseAppUrl = mb.baseUrl()+'apotik/stok_opname_online/'
            initForm();
            search_identitas();
            // tambahIdentitasRow();

            // modalOK();
            var so_id = $('input#so_id').val();
            $('input#id_so').attr('value',so_id);
        });

        function initForm()
        {
            // total_identitas();
            // addIdentitasRow();
            // getIdentitasBefore();
            handleModalOK();    
            handleDatePickers();

            $('button#minCounter').on('click', function(){
                $('input#identitasCounter').val(parseInt(identitasCounter)+1);
                // alert($('input#identitasCounter').val());
            })

            var gudang_id = $('input#gudang_id').val(),
                item_id = $('input#item_id').val(),
                item_satuan_id = $('input#item_satuan_id').val(),
                like = $('input#search_identitas').val();

            $.ajax({
                type        : 'POST',
                url         : baseAppUrl + 'show_modal_identitas',
                data        : {gudang_id: gudang_id, item_id: item_id, item_satuan_id: item_satuan_id, like: like},
                dataType    : 'text',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses...' });
                },
                success     : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#show_modal_identitas").html(results);  
                    var identitasCounter = $('input#identitasCounter').val();
                    tambahIdentitasRow();
                    //alert(results);
                },
                complete : function()
                {
                    Metronic.unblockUI();
                }
            });
        };  

        function search_identitas()
        {   
            var gudang_id = $('input#gudang_id').val(),
                item_id = $('input#item_id').val(),
                item_satuan_id = $('input#item_satuan_id').val(),
                like = $('input#search_identitas');

            like.on('change', function(){
                $.ajax({
                type        : 'POST',
                url         : baseAppUrl + 'show_modal_identitas',
                data        : {gudang_id: gudang_id, item_id: item_id, item_satuan_id: item_satuan_id, like: $(this).val()},
                dataType    : 'text',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses...' });
                },
                success     : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $("#show_modal_identitas").html(results);  
                    var identitasCounter = $('input#identitasCounter').val();
                    tambahIdentitasRow();
                    //alert(results);
                },
                complete : function()
                {
                    Metronic.unblockUI();
                }
            });
            });
        }

        function total_identitas(){
            row_id = $('input#row_id').val();
            $row        = $('tr#'+row_id, $('#retur_pembelian_detail'));

            total_identitas = $('input[name$="[jumlah_pesan]"]', $row).val();
            satuan_nama = $('select.item_satuan option:selected', $row).text();

            // alert('tr#'+row_id);
            $('label#total_identitas_modal').text(total_identitas);
            $('label#satuan_item_modal').text(satuan_nama);

            
            // $('input[name$="[jumlah_persatuan]"]').val($('input[name$="[jumlah_per_satuan]"]', $row).val());
        }

        function addIdentitasRow(identitasCounter)
        {
            // alert(identitasCounter);
            if (isNaN(identitasCounter)) {
                identitasCounter = 0;
            };   
            var tplIdentitas     = $.validator.format( $('#tpl_identitas').text()),
            $tableIdentitas  = $('#table_identitas');


            var numRow = $('tbody tr', $tableIdentitas).length;

            console.log('numrow' + numRow);

            var 
                $rowContainer  = $('tbody', $tableIdentitas),
                $newItemRow    = $(tplIdentitas(identitasCounter++)).appendTo( $rowContainer ),
                $btnSearchItem = $('.search-item', $newItemRow),
                $noUrut        = $('td.no_urut_row', $newItemRow),
                $noUrutRow        = $('label.no_urut_row', $newItemRow),
                $inputJumlah   = $('input.jumlah_item', $newItemRow);

                $noUrutRow.text(identitasCounter-1);
                console.log($newItemRow);

            setJumlah($inputJumlah);
            handleDatePickers();
        };

        function tambahIdentitasRow()
        {
            $('a#tambah_identitas').on('click', function(){
                var identitasCounter = $('input#identitasCounter').val();
                $('input#identitasCounter').val(parseInt(identitasCounter)+1);
                // alert(identitasCounter);
                addIdentitasRow(identitasCounter);
            });
        }

        function handleModalOK(){
            $('a#modal_ok').click(function() {
               
                var total_jumlah = 0,
                    $row = $('input#row_id').val();

                $.each($('input.jumlah_item', $('#table_identitas')), function(idx, value){
                    total_jumlah = parseInt(total_jumlah) + parseInt(this.value);
                    $(this).attr('value', $(this).val());
                });

                var total_awal = 0;
                $.each($('input.stock_item', $('#table_identitas')), function(idx, value){
                    total_awal = parseInt(total_awal) + parseInt(this.value);
                    $(this).attr('value', $(this).val());
                });

                $.each($('input.send-id', $('#table_identitas')), function(idx, value){
                    $(this).attr('value', $(this).val());
                });

                $.each($('input.send-input', $('#table_identitas')), function(idx, value){
                    $(this).attr('value', $(this).val());
                });

                $('input#total_jumlah').val(total_jumlah);
                $('input#total_stock').val(total_awal);

                $form_identitas = $('#form_identitas');

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'save',
                    data     : $form_identitas.serialize(),
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        // location.href = baseAppUrl;
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            var satuan = $('input#nama_satuan').val();

            $('label#jumlahEl_'+$row).text(total_jumlah+' '+satuan);
            $('input#jumlahIn_'+$row).val(total_awal);
            $('input#jumlahFin_'+$row).val(total_jumlah);
            $('input#jumlahCh_'+$row).val(total_jumlah-total_awal);

            $('div#simpan_identitas_'+$row).html($('table#table_identitas > tbody').html());
            // $('div#simpan_identitas', $row).html($('table#table_identitas').html());

            $('div.simpan_identitas input').removeClass('jumlah_item')
        
            $('#closeModal').click();              

            });
        }

        function setJumlah($btn){
            
            $btn.on('change', function(){
                var rowId = $(this).data('row');

                var jumlah = parseInt($(this).val()),
                    stock = parseInt($('input#identitas_stock_'+rowId, $('table#table_identitas')).val());
                if (jumlah > stock) {
                    // alert('stock kurang');s
                    $(this).val($('input#identitas_stock_'+rowId, $('table#table_identitas')).val());
                    $('input#identitas_jumlah_'+rowId, $('table#table_identitas')).val($(this).val());
                    $('input#identitas_jumlah_'+rowId, $('table#table_identitas')).attr('value', $(this).val());
                }else{
                    $('input#identitas_jumlah_'+rowId, $('table#table_identitas')).val($(this).val());
                    $('input#identitas_jumlah_'+rowId, $('table#table_identitas')).attr('value', $(this).val());
                }
            });
        }

        function handleDatePickers(){


            if (jQuery().datepicker) {
                $('.date').datepicker({
                    rtl: Metronic.isRTL(),
                    format : 'yyyy-mm-dd ',
                    // autoclose: true
                }).on('changeDate', function(ev){
                    $('.datepicker-dropdown').hide();
                });
                $('body').removeClass("modal-open");
                $('.date').on('click', function(){
                    if ($('#popup_modal_identitas').is(":visible") && $('body').hasClass("modal-open") == false) {
                        $('body').addClass("modal-open");
                    }
                });
            }
        }

    </script>
