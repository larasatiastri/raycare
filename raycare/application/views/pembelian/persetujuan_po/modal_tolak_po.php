    <form action="<?=base_url()?>pembelian/persetujuan_po/reject_po" method="post" id="form_reject" class="form-horizontal">
        
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Tolak PO", $this->session->userdata("language"))?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?>:</label>
                         
                        <div class="col-md-8">
                            <!-- <input type="file" id="uploaddokumen" name="uploaddokumen" class="uploadbutton up-this" value="" required="required" /> -->
                            <input type="hidden" id="pembelian_id" name="pembelian_id"  value="<?=$pembelian_id?>" required="required" />
                            <input type="hidden" id="tipe_transaksi" name="tipe_transaksi"  value="<?=$tipe_transaksi?>" required="required" />
                            <input type="hidden" id="jenis_pembelian" name="jenis_pembelian"  value="<?=$jenis_pembelian?>" required="required" />
                            <textarea id="nomer_{0}" name="keterangan_tolak" class="form-control" rows="3" placeholder="Keterangan"></textarea>
                            
                        </div>
                    </div> 

                </div>
                <?php
                    $confirm_save       = translate('Anda yakin akan menolak PO ini ?',$this->session->userdata('language'));
                    $submit_text        = translate('OK', $this->session->userdata('language'));
                    $reset_text         = translate('Reset', $this->session->userdata('language'));
                    $back_text          = translate('Close', $this->session->userdata('language'));
                ?>
                <div class="modal-footer">
                    <!-- <button type="button" id="closeModal" class="btn default" data-dismiss="modal">Close</button> -->
                    <a class="btn default" type="button" id="closeModal" data-dismiss="modal"><?=$back_text?></a>
                    <!-- <button type="button" class="btn btn-primary btn-ok" data-confirm="<?=$confirm_save?>" id="btnOK" onClick="javascript:initForm();">OK</button> -->
                    <button type="submit" id="save_reject" class="btn btn-primary hidden"><?=$submit_text?></button>
                    <a id="confirm_save_reject" class="btn btn-primary" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=$submit_text?></a>

                </div>

    </form>

    <script type="text/javascript">
    
        $(document).ready(function(){
            $('#keterangan_tolak').val($('input#keterangan').val());
            handleConfirmSave();
        });

    function initForm()
    {

        handleConfirmSave()
       
    }

    function handleConfirmSave()

    {
        $('a#confirm_save_reject').click(function() {
            // if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $('#save_reject').click();
                }
            });
        });
    };


    </script>