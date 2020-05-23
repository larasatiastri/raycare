<?
    $form_attr = array(
        "id"            => "form_harga", 
        "name"          => "form_harga", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="#" method="post" id="form_harga" class="form-horizontal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Harga Baru</h4>
    </div>
                                       
    <div class="modal-body">
        <div class="form-group hidden">
            <label class="col-md-12"><?=translate("Supplier Harga Item Id", $this->session->userdata("language"))?></label>
            <div class="col-md-12">
                <input type="text" id="supplier_harga_item_id" name="supplier_harga_item_id" class="form-control" value="<?=$supplier_harga_item_id?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-12"><?=translate("Harga", $this->session->userdata("language"))?></label>
            <div class="col-md-12">
                <input type="number" min="0" value="0" id="harga" name="harga" class="form-control  text-right" placeholder="<?=translate("Harga", $this->session->userdata("language"))?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-12"><?=translate("Tanggal Efektif", $this->session->userdata("language"))?></label>
            <div class="col-md-12">
                <div class="input-group date">
                    <input type="text" class="form-control" id="tanggal_efektif" name="tanggal_efektif" value="<?=date('d F Y')?>" readonly="readonly">
                    <span class="input-group-btn">
                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" id="closeModal" class="btn default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnOK" onClick="javascript:modalOK();">OK</button>
    </div>


    </form>
 
    <script type="text/javascript">

        $(document).ready(function(){
            initForm();
            baseAppUrl = mb.baseUrl()+'master/supplier/'
            handleDatePickers();
            // modalOK();
        });
        
        function initForm()
        {
            

        };  

        function modalOK()
        {
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_supplier_harga_item',
                data     : {supplier_harga_item_id : $('input#supplier_harga_item_id').val(), harga : $('input#harga').val(), tanggal_efektif : $('input#tanggal_efektif').val()},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                        oTable.api().ajax.url(baseAppUrl + 'listing_supplier_item/' + $('input#supplier_id').val()).load();
                        $('#closeModal').click();
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
            
        }

        function handleDatePickers(){


            if (jQuery().datepicker) {
                $('.date').datepicker({
                    rtl: Metronic.isRTL(),
                    format : 'dd MM yyyy',
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

        function selectIndentitas()
        {
            $('select.select-indentitas').on('change', function(){

                rowId = $(this).data('row');

                alert('input#jumlah_'+rowId);

                $('select.select-indentitas').val($(this).val());

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_jumlah_identitas',
                    data     : {inventory_id:$(this).val()},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                       $.each(results, function(key, value) {

                            $('input#jumlah_'+rowId).val(value.jumlah);

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });

            }); 
        }
    </script>
