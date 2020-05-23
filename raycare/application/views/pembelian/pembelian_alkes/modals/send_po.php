<form action="#" id="form_send_po" class="form-horizontal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <div class="caption">
                <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Kirim Email PO ".$data_po['no_pembelian'], $this->session->userdata("language"))?></span>
            </div>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="col-md-12"><?=translate('Pilih Alamat Email', $this->session->userdata('language'))?> :</label>
                <div class="col-md-12">
                    <?php
                        $email_option = array();

                        foreach ($supplier_email as $sup_email) {
                            $email_option[$sup_email['email']] = $sup_email['email'];
                        }

                        echo form_dropdown('supplier_email', $email_option, '','id="supplier_email" class="form-control"');
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
                            "autofocus" => true,
                            "class"     => "form-control id_po hidden" ,
                            "value"     => $po_id
                        );
                        echo form_input($id_po);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-6 hidden"><?=translate("Id", $this->session->userdata("language"))?> :</label>
                <div class="col-md-4">
                    <?php
                        $id = array(
                            "id"        => "id_supplier",
                            "name"      => "id_supplier",
                            "autofocus" => true,
                            "class"     => "form-control id_supplier hidden" ,
                            "value"     => $supplier['id']
                        );
                        echo form_input($id);
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
        handleKirimPO();
    }); 

    function handleKirimPO(){
        $('a#modal_ok').click(function() {

           
            $form_send_po = $('#form_send_po');

            var email = $('select#supplier_email', $form_send_po).val();
            // alert(email);
            if(email != 'null' && email != null){
                $(this).attr('disabled', true);
                $(this).text('Sedang Diproses');

                $.ajax({
                    type     : 'POST',
                    url      : mb.baseUrl() + 'pembelian/pembelian_alkes/kirim_email_po',
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
            }else{
                bootbox.alert("Silahkan pilih alamat email");
                $('select#supplier_email').focus();
            }

        });
    }
</script>