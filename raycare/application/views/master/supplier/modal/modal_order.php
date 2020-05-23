<?
    $form_attr = array(
        "id"            => "form_order", 
        "name"          => "form_order", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="#" method="post" id="form_order" class="form-horizontal">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Order</h4>
    </div>                                      
    <div class="modal-body">
        <div class="form-group hidden">
            <label class="col-md-12"><?=translate("Supplier Item Id", $this->session->userdata("language"))?></label>
            <div class="col-md-12">
                <input type="text" id="supplier_item_id" name="supplier_item_id" class="form-control" value="<?=$supplier_item_id?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-12"><?=translate("Minimum Order", $this->session->userdata("language"))?></label>
            <div class="col-md-12">
                <input type="number" min="0" value="0" id="minimum_order" name="minimum_order" class="form-control  text-right" placeholder="<?=translate("Minimum Order", $this->session->userdata("language"))?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-12"><?=translate("Kelipatan Order", $this->session->userdata("language"))?></label>
            <div class="col-md-12">
                <input type="number" min="0" value="0" id="kelipatan_order" name="kelipatan_order" class="form-control  text-right" placeholder="<?=translate("Kelipatan Order", $this->session->userdata("language"))?>">
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
                url      : baseAppUrl + 'save_supplier_item',
                data     : {supplier_item_id : $('input#supplier_item_id').val(), minimum_order : $('input#minimum_order').val(), kelipatan_order : $('input#kelipatan_order').val()},
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
