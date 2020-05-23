<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">
        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan Lain", $this->session->userdata("language"))?></span>
    </h4>
</div>
<div class="modal-body">
    <div class="form-group">
        <label class="control-label col-md-4"><?=translate('Nama Tindakan', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control" id="nama_tindakan" name="nama_tindakan" value="<?=$data_tindakan_lain['nama_tindakan']?>" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4"><?=translate('Waktu', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$tindakan_hd_id?>">
            <input class="form-control hidden" id="pasien_id" name="pasien_id" value="<?=$data_tindakan_hd['pasien_id']?>">
            <input class="form-control" id="waktu_tindakan_lain" name="waktu_tindakan_lain" value="<?=date('H:i')?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4"><?=translate('Perawat', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control" id="perawat_dalam_box" name="perawat_dalam_box" value="<?=$this->session->userdata('nama_lengkap')?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-4"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
        <div class="col-md-6">
            <!-- <input class="form-control input-small hidden" id="id_bed" name="id_bed"> -->
            <textarea class="form-control" id="keterangan_tindakan_lain" name="keterangan_tindakan_lain" rows="5" ></textarea>
        </div>
    </div>

    <div class="form-group hidden">
        <label class="control-label col-md-4"><?=translate('ID', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control" id="tindakan_lain_id" name="tindakan_lain_id" value="<?=$tindakan_lain_id?>" readonly>
            <input class="form-control" id="tindakan_lain_item_id" name="tindakan_lain_item_id" value="<?=$data_tindakan_lain['tindakan_id']?>" readonly>
        </div>
    </div>

    <div class="form-group hidden">
        <label class="control-label col-md-4">Dummy Identitas :</label>
        <div class="col-md-8">
            <div id="inventory_identitas_detail"> </div>
        </div>
            
    </div>
    
</div>
<div class="modal-footer">
    <a class="btn default modal_batal" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
    <a class="btn btn-primary modal_ok" data-dismiss="modal"><?=translate('OK', $this->session->userdata('language'))?></a>
</div>



<script type="text/javascript">
    
    $(document).ready(function() 
    {
        baseAppUrl          = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';
        handleBtnSave();
    });

    function handleBtnSave()
    {
        $('a.modal_ok').click(function()
        {

            $.ajax ({ 
                type: "POST",
                url: baseAppUrl + "save_tindakan_lain",  
                data:  $('#form_tindakan_lain').serialize(),
                dataType : "json",
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(result)         
                { 
                    // mb.showMessage(result[0],result[1],result[2]);
                    $('a.reload-table-tindakan').click();
                    $('a.reload-table2').click();
                    $('a.reload-table').click();
                },
                complete : function() {
                    Metronic.unblockUI();
                }
            }); 
        })
    }

    

</script>