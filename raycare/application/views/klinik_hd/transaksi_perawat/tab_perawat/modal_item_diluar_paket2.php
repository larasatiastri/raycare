<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">
        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Item Diluar Paket", $this->session->userdata("language"))?></span>
    </h4>
</div>
<div class="modal-body">
    <div class="form-group">
        <label class="control-label col-md-4"><?=translate('Waktu', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$tindakan_hd_id?>">
            <input class="form-control hidden" id="gudang_id" name="gudang_id" value="1">
            <input class="form-control" id="waktu_diluar_paket" name="waktu_diluar_paket" value="<?=date('H:i')?>">
            <input class="form-control hidden" id="item_id" name="item_id" value="<?=$data_identitas['item_id']?>">
            <input type="hidden" class="form-control" id="satuan" name="satuan" value="<?=$data_identitas['item_satuan_id']?>">
            <input type="hidden" class="form-control" id="bn_sn_lot" name="bn_sn_lot" value="<?=$data_identitas['bn_sn_lot']?>">
            <input type="hidden" class="form-control" id="expire_date" name="expire_date" value="<?=$data_identitas['expire_date']?>">
            <input type="hidden" class="form-control" id="jumlah_inventory" name="jumlah_inventory" value="<?=$data_identitas['jumlah']?>">
            <input class="form-control hidden" id="pmb_id" name="pmb_id" value="<?=$data_identitas['pmb_id']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4"><?=translate('Perawat', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control" id="perawat_diluar_paket" name="perawat_diluar_paket" value="<?=$this->session->userdata('nama_lengkap')?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-4"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
        <div class="col-md-6">
            <!-- <input class="form-control input-small hidden" id="id_bed" name="id_bed"> -->
            <textarea class="form-control" id="keterangan_diluar_paket" name="keterangan_diluar_paket" rows="5" ></textarea>
        </div>
    </div>

    <div class="form-group hidden">
        <label class="control-label col-md-4"><?=translate('ID', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control" id="id_resep_detail" name="id_resep_detail" value="<?=$resep_identitas_id?>" readonly>
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
                url: baseAppUrl + "save_item_diluar_paket2",  
                data:  $('#form_item_diluar').serialize(),
                dataType : "json",
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(result)         
                { 
                    // mb.showMessage(result[0],result[1],result[2]);
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