<form action="#" id="form_send_po" class="form-horizontal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <div class="caption">
                <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Finish PO ".$data_po['no_pembelian'], $this->session->userdata("language"))?></span>
            </div>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="col-md-12"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
                <div class="col-md-12">
                    <?php
                        $keterangan = array(
                            "id"        => "keterangan",
                            "name"      => "keterangan",
                            "autofocus" => true,
                            "rows"      => 5,
                            "cols"      => 6,
                            "class"     => "form-control" 
                        );
                        echo form_textarea($keterangan);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-6 hidden"><?=translate("PO ID", $this->session->userdata("language"))?> :</label>
                <div class="col-md-4">
                    <?php
                        $id_po = array(
                            "id"        => "id_po",
                            "name"      => "id_po",
                            "class"     => "form-control id_po hidden" ,
                            "value"     => $po_id
                        );
                        echo form_input($id_po);
                    ?>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
                <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
                <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
            </div>
        </div>
</form>

<script>
    $( document ).ready(function() {
        handleFinishPO();
    }); 

    function handleFinishPO(){
        $('a#modal_ok').click(function() {

           
            $form_send_po = $('#form_send_po');

            // alert(email);
            $(this).attr('disabled', true);
            $(this).text('Sedang Diproses');

            $.ajax({
                type     : 'POST',
                url      : mb.baseUrl() + 'pembelian/pembelian/finish_po',
                data     : $form_send_po.serialize(),
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( data ) {
                    
                    if(data.success == true){
                        mb.showMessage('success',data.msg,'Sukses');
                        $('#closeModal').click();              
                        $('#load_table').click(); 
                    }if(data.success == false){
                        mb.showMessage('error',data.msg,'Error');
                        $('#closeModal').click();              
                        $('#load_table').click(); 
                    }
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
            

        });
    }
</script>