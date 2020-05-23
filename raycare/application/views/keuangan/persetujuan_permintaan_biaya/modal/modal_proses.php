    <form action="<?=base_url()?>keuangan/persetujuan_permintaan_biaya/tolak_permintaan" method="post" id="form_reject" class="form-horizontal">
        
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Tolak Permintaan", $this->session->userdata("language"))?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Isi Keterangan", $this->session->userdata("language"))?>:</label>
                         
                        <div class="col-md-8">
                            <!-- <input type="file" id="uploaddokumen" name="uploaddokumen" class="uploadbutton up-this" value="" required="required" /> -->
                            <input type="hidden" id="persetujuan_permintaan_biaya_id" name="persetujuan_permintaan_biaya_id"  value="<?=$persetujuan_permintaan_biaya_id?>" required="required" />
                            <input type="hidden" id="permintaan_biaya_id" name="permintaan_biaya_id"  value="<?=$permintaan_biaya_id?>" required="required" />
                            <input type="hidden" id="order" name="order"  value="<?=$order?>" required="required" />
                            <textarea id="nomer_{0}" name="keterangan_tolak" class="form-control" rows="3" placeholder="Keterangan"></textarea>
                            
                        </div>
                    </div> 

                </div>
                <?php
                    $confirm_save       = translate('Apa kamu yakin ingin menolak permintaan pembayaran ini ?',$this->session->userdata('language'));
                    $submit_text        = translate('Ok', $this->session->userdata('language'));
                    $reset_text         = translate('Reset', $this->session->userdata('language'));
                    $back_text          = translate('Close', $this->session->userdata('language'));
                ?>
                <div class="modal-footer">
                    <!-- <button type="button" id="closeModal" class="btn default" data-dismiss="modal">Close</button> -->
                    <a class="btn default" type="button" id="closeModal" data-dismiss="modal"><?=$back_text?></a>
                    <!-- <button type="button" class="btn btn-primary btn-ok" data-confirm="<?=$confirm_save?>" id="btnOK" onClick="javascript:initForm();">OK</button> -->
                    <button type="submit" id="save_reject" class="btn btn-primary hidden" onClick="javascript:initForm();"><?=$submit_text?></button>
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