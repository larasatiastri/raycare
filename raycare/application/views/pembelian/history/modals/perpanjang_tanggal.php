<form action="#" id="form_tanggal_kadaluarsa" class="form-horizontal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <div class="caption">
                <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Konfirmasi Perpanjang Kadaluarsa", $this->session->userdata("language"))?></span>
            </div>
        </div>
        <div class="modal-body form">
            <div class="form-group"></div>
            <div class="form-group">
                <label class="control-label col-md-5"><?=translate('Perpanjang Tanggal Kadaluarsa', $this->session->userdata('language'))?> :</label>
                <div class="col-md-4">
                    <div class="input-group date" id="tanggal">
                        <input class="form-control" readonly id="tanggal_perpanjang" name="tanggal_perpanjang">
                        <span class="input-group-btn">
                            <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-6 hidden"><?=translate("Id", $this->session->userdata("language"))?> :</label>
                <div class="col-md-4">
                    <?php
                        $id = array(
                            "id"        => "id_perpanjang",
                            "name"      => "id_perpanjang",
                            "autofocus" => true,
                            "class"     => "form-control id_perpanjang hidden" ,
                            "value"     => $id
                        );
                        echo form_input($id);
                    ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
            <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
            <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
            <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
        </div>
</form>

<script>
    $( document ).ready(function() {
        handleUpdatePerpanjang();
        handleDatePickers();
    }); 

    function handleUpdatePerpanjang(){
        $('a#modal_ok').click(function() {
           
            $form_tanggal = $('#form_tanggal_kadaluarsa');

            $(this).attr('disabled', true);
            $(this).text('Sedang Diproses');

            $.ajax({
                type     : 'POST',
                url      : mb.baseUrl() + 'pembelian/history/save_tanggal',
                data     : $form_tanggal.serialize(),
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    
                    $('#closeModal').click();              
                    $('#load_table').click();              
                    // location.href = mb.baseUrl() + 'pembelian/pembelian';
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
        });
    }

    function handleDatePickers() {

       if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
            }).on('changeDate', function(ev){
                $('.datepicker-dropdown').hide();       // function jika menggunakan datepicker dalam modal 
            });
            $('body').removeClass("modal-open");
            $('.date').on('click', function(){
                if ($('#popup_modal').is(":visible") && $('body').hasClass("modal-open") == false) {
                    $('body').addClass("modal-open");
                }
            });
        }
    }
</script>