<form action="#" method="post" id="form_revisi" class="form-horizontal">        
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Revisi Permintaan Biaya", $this->session->userdata("language")).' '. $form_data['nomor_permintaan']?></h4>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label col-md-3"><?=translate("Isi Keterangan", $this->session->userdata("language"))?>:</label>
            <div class="col-md-8">
                <input type="hidden" id="permintaan_biaya_id" name="permintaan_biaya_id"  value="<?=$pk_value?>" />
                <input type="hidden" id="tipe_biaya" name="tipe_biaya"  value="<?=$form_data['tipe']?>" />
                <textarea id="keterangan_tolak" name="keterangan_tolak" class="form-control" rows="5" placeholder="Keterangan" value="<?=$form_data['keterangan_revisi']?>"></textarea>
                
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
        <a class="btn default" type="button" id="closeModal" data-dismiss="modal"><?=$back_text?></a>
        <a id="save" class="btn btn-primary" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=$submit_text?></a>
    </div>

</form>

<script type="text/javascript">

$(document).ready(function(){
    handleConfirmSave();
});

function initForm()
{
    handleConfirmSave() 
}

function handleConfirmSave()
{
    $form = $('#form_revisi');

    $('a#save',$form).click(function() {
        $.ajax
        ({
            type: 'POST',
            url: mb.baseUrl() + 'keuangan/proses_pembayaran_transaksi/revisi',  
            data:  $form.serialize(),  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                if(data.success == true){
                    mb.showMessage('success',data.msg,'Sukses');
                    location.href = mb.baseUrl() + 'keuangan/proses_pembayaran_transaksi';
                }if(data.success == false){
                    mb.showMessage('error',data.msg,'Error');
                    $('a#close').click();
                }
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
        });
    });
};


</script>