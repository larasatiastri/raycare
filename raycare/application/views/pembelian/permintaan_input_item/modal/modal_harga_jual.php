<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Setup Harga Jual", $this->session->userdata("language"))?></h4>
</div>
<div class="modal-body">
    <div class="form-group hidden" style="margin-top:20px; margin-bottom:20px;">
        <label class="control-label col-md-3"><?=translate("Flag", $this->session->userdata("language"))?> :</label>
        
        <div class="col-md-4">
            <input type="hidden" id="flag" value="<?=$flag?>">
        </div>
    </div>

    <div class="form-group" style="margin-top:20px; margin-bottom:20px;">
        <label class="control-label col-md-3"><?=translate("Harga Per Tanggal", $this->session->userdata("language"))?> :</label>
        
        <div class="col-md-4">
            <div class="input-group date" id="tanggal">
                <input type="text" class="form-control" id="tanggal" name="tanggal" readonly >
                <span class="input-group-btn">
                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                </span>
            </div>
        </div>
    </div>
    <table class="table table-striped table-bordered table-hover table-condensed" id="table_harga_satuan_modal">
        <thead>
            <tr class="heading">
                <th class="text-center" style="width:250px;"><?=translate("Item Id", $this->session->userdata("language"))?></th>
                <th class="text-center" style="width:250px;"><?=translate("Satuan Id", $this->session->userdata("language"))?></th>
                <th class="text-center" style="width:250px;"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
                <th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                <th class="text-center" style="width:250px;"><?=translate("Harga", $this->session->userdata("language"))?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary hidden" id="btnOK">OK</button>
    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>


<script type="text/javascript">

    $(document).ready(function(){
        baseAppUrl = mb.baseUrl()+'pembelian/permintaan_input_item/';
        
        var months      = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
            date            = new Date(),
            day             = date.getDate(),
            month           = date.getMonth(),
            yy              = date.getYear(),
            year            = (yy < 1000) ? yy + 1900 : yy,
            curr_hour       = date.getHours(),
            curr_min        = date.getMinutes(),
            curr_sec        = date.getSeconds(),
            tanggal         = year +':' + months[month] + ':' + day;
            

        initForm();

        // alert()
        // tambahIdentitasRow();
        // selectIndentitas();
        // var $tableHargaItemSatuanModal = $('#table_harga_satuan_modal');
        // var baseAppUrl = mb.baseUrl()+'master/item/';
        // modalOK();
    });



    function initForm(){
        handleDataTableModal();
        handleDatePickers();
        handleModalHarga();
    }

    function handleDatePickers(){
        // var $tanggal = $('input#tanggal').val();


        if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
                // autoclose: true
            }).on('changeDate', function(ev){
                var
                    date_selected   = new Date($('input#tanggal').val()),
                    day_selected    = date_selected.getDate(),
                    month_selected  = date_selected.getMonth(),
                    yy_selected     = date_selected.getYear(),
                    year_selected   = (yy < 1000) ? yy + 1900 : yy;
                        
                var tanggal_dipilih = year_selected +':' + months[month_selected] + ':' + day_selected;
                // alert(tanggal_dipilih);

                $('.datepicker-dropdown').hide();
                
                oTable.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + $('input#item_id').val() + '/' + tanggal_dipilih).load();
                
            });
            $('body').removeClass("modal-open");
            $('.date').on('click', function(){
                if ($('#popup_modal').is(":visible") && $('body').hasClass("modal-open") == false) {
                    $('body').addClass("modal-open");
                }
            });
        }
    }

    function handleDataTableModal(){
        
        
        oTable = $('#table_harga_satuan_modal').dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + 0 + '/' + tanggal ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [

                { 'name':'item_satuan.item_id item_id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name':'item_satuan.id satuan_id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name':'item_harga.tanggal tanggal','visible' : false, 'searchable': true, 'orderable': true },
                { 'name':'item_satuan.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'item_harga.harga harga','visible' : true, 'searchable': true, 'orderable': true },

                ]
        });
    }

    function handleModalHarga(){
        var $form = '';
        if ($('input#flag').val() == "add") {
            $form = $('#form_add_item');
        }else{

            $form = $('#form_edit_item');
        }
        
        $('a#modal_ok', $form).click(function() {
        

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_satuan_harga',
                data     : $form.serialize(),
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    var item_id = $('input#item_id').val();
                    // $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/' + item_id).load();
                    // $tableHargaItemSatuan.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan/51').load();
                    oTable.api().ajax.url(baseAppUrl + 'listing_harga_item_satuan_modal_by_tanggal/' + $('input#item_id').val() + '/' + tanggal).load();
                    $('input#tanggal').val("");
                    $('#closeModal').click();   
                    $('a#refresh_table_penjualan').click();           
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
        });
    }
</script>