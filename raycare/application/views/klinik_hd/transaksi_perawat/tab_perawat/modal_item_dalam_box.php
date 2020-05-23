<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">
        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Gunakan Item Dalam Paket", $this->session->userdata("language"))?></span>
    </h4>
</div>
<div class="modal-body">
    <div class="form-group">
        <label class="control-label col-md-4"><?=translate('Waktu', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$tindakan_hd_id?>">
            <input class="form-control hidden" id="gudang_id" name="gudang_id" value="1">
            <input class="form-control" id="waktu_dalam_box" name="waktu_dalam_box" value="<?=date('H:i')?>">
            <input class="form-control hidden" id="item_id" name="item_id" value="<?=$data_t_box_paket_detail['item_id']?>">
            <input type="hidden" class="form-control" id="satuan" name="satuan" value="<?=$data_t_box_paket_detail['item_satuan_id']?>">
            <input type="hidden" class="form-control" id="bn_sn_lot" name="bn_sn_lot" value="<?=$data_t_box_paket_detail['bn_sn_lot']?>">
            <input type="hidden" class="form-control" id="expire_date" name="expire_date" value="<?=$data_t_box_paket_detail['expire_date']?>">
            <input type="hidden" class="form-control" id="jumlah_inventory" name="jumlah_inventory" value="<?=$data_t_box_paket_detail['jumlah']?>">
            <input type="hidden" class="form-control" id="t_box_paket_id" name="t_box_paket_id" value="<?=$data_t_box_paket_detail['t_box_paket_id']?>">
            <input class="form-control hidden" id="pmb_id" name="pmb_id" value="<?=$data_t_box_paket_detail['pmb_id']?>">
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
            <textarea class="form-control" id="keterangan_dalam_box" name="keterangan_dalam_box" rows="5" ></textarea>
        </div>
    </div>

    <div class="form-group hidden">
        <label class="control-label col-md-4"><?=translate('ID', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control" id="t_box_paket_detail_id" name="t_box_paket_detail_id" value="<?=$t_box_paket_detail_id?>" readonly>
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
                url: baseAppUrl + "save_item_dalam_box_paket",  
                data:  $('#form_item_dalam_box').serialize(),
                dataType : "json",
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(result)         
                { 
                    // mb.showMessage(result[0],result[1],result[2]);
                    $('a.reload-table-box').click();
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