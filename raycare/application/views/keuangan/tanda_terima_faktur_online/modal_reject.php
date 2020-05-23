    <form action="<?=base_url()?>keuangan/tanda_terima_faktur_online/save" method="post" id="form_reject" class="form-horizontal">
        
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Tolak TTF", $this->session->userdata("language")).' No.'.$data_ttf['no_tanda_terima_faktur']?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?>:</label>
                         
                        <div class="col-md-8">
                            <input type="hidden" id="command" name="command"  value="tolak_ttf" />
                            <input type="hidden" id="id" name="id"  value="<?=$data_ttf['id']?>" required="required" />
                            <textarea id="nomer_{0}" name="keterangan_tolak" class="form-control" rows="3" placeholder="Keterangan" required></textarea>
                            
                        </div>
                    </div> 

                </div>
                <?php
                    $confirm_save       = translate('Anda yakin menolak tukar faktur ini ?',$this->session->userdata('language'));
                    $submit_text        = translate('Tolak', $this->session->userdata('language'));
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