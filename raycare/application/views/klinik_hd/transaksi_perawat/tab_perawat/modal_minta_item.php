<?php
    $btn_del    = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

    $data_obat = $this->obat_m->get_data_obat()->result_array();

    $option_obat_alkes = array(
        ''  => 'Pilih...'
    );

    foreach ($data_obat as $key => $obat) {
        $option_obat_alkes[$obat['id'].'_'.$obat['kode'].'_'.$obat['nama'].'_'.$obat['item_satuan_id'].'_'.$obat['nama_satuan']] = $obat['kode'].' - '.$obat['nama'];
    }

    $item_cols = array(
        'item_code'     => form_dropdown('item_minta[{0}][item_id]',$option_obat_alkes,'','id="1tindakan_item_id_{0}" class="form-control item_id"').'<input type="hidden" id="1tindakan_item_id_{0}" name="item_minta[{0}][item_id_hidden]" class="form-control">',
        'item_jumlah'   => '<input type="number" id="1tindakan_jumlah_{0}" name="item_minta[{0}][jumlah]" class="form-control" value="1">',
        'item_satuan'   => '<div name="item_minta[{0}][div]" align="center"></div><input type="hidden" id="1tindakan_satuan_{0}" name="item_minta[{0}][satuan]" class="form-control">',
         
        'action'        => $btn_del,
    );

    // gabungkan $item_cols jadi string table row
    $item_row_template =  '<tr id="item_row556_{0}" class="table_item26"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';


?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">
        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Permintaan Obat/Alkes ke Ins.Farmasi", $this->session->userdata("language"))?></span>
    </h4>
</div>
<div class="modal-body">
    <div class="portlet light" id="section-gambar">
        <div class="portlet-body">
            <input type="hidden" id="identitasCounter" value="0">
            <input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$tindakan_hd_id?>">
            <input class="form-control hidden" id="pasien_id" name="pasien_id" value="<?=$pasien_id?>">
            <div class="form-group">
                <label class="control-label col-md-4"><?=translate("Item Untuk Tindakan :", $this->session->userdata("language"))?></label>
                
                <div class="col-md-5">
                    <?php
                        $options_jenis_tindakan = array(
                            ''  => 'Pilih...',
                            '1'  => 'Tindakan HD',
                            '2'  => 'Tindakan Transfusi',
                            '3'  => 'Tindakan Cek Lab',
                        );
                        echo form_dropdown('jenis_tindakan_id', $options_jenis_tindakan, '', 'id="jenis_tindakan_id" class="form-control" required');
                    ?>
                </div>
            </div>

            <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
            <table class="table table-striped table-bordered table-hover" id="table_item_row" name="table_item_row">
                <thead>
                    <tr role="row">
                        <th class="text-center" ><?=translate("Kode", $this->session->userdata("language"))?></th>
                        <th class="text-center" width="20%" colspan="2"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                        <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
                         
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div class="actions">
                <a id="tambah_identitas" class="btn btn-primary">
                    <i class="fa fa-plus"></i>
                    <span class="hidden-480">
                         <?=translate("Tambah", $this->session->userdata("language"))?>
                    </span>
                </a>
            </div>
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
        identitasCounter = $('input#identitasCounter').val();

        addItemRow();
        handleBtnSave();
        tambahIdentitasRow();

    });

    function handleBtnSave()
    {
        $('a.modal_ok').click(function()
        {

            $.ajax ({ 
                type: "POST",
                url: baseAppUrl + "save_resep",  
                data:  $('#form_minta_item').serialize(),
                dataType : "json",
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(result)         
                { 

                    if(result.success == true){
                       // mb.showMessage(result[0],result[1],result[2]);
                        $('a.reload-table-minta').click();
                        $('a.reload-table2').click();
                        $('a.reload-table').click();
 
                    }
                                    },
                complete : function() {
                    Metronic.unblockUI();
                }
            }); 
        });
    }

    function addItemRow()
    {
        if (isNaN(identitasCounter)) {
            identitasCounter = 0;
        };

        var tplItemRow = $.validator.format( $('#tpl_item_row').text()),
        $tableItemRow  = $('#table_item_row');

        var numRow = $('tbody tr', $tableItemRow).length;

        console.log('numrow' + numRow);


        var 
            $rowContainer  = $('tbody', $tableItemRow),
            $newItemRow    = $(tplItemRow(identitasCounter++)).appendTo( $rowContainer );

            $('input#identitasCounter').val(identitasCounter);

        handleBtnDeleteRow( $('.del-this', $newItemRow) );
        $('select.item_id', $newItemRow).select2();

        $select = $('select.item_id', $newItemRow);
        handleSelectItem($select, $newItemRow);

    };

    function handleBtnDeleteRow($btn){

        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $('table#table_item_row'))

        var numRow = $('tbody tr', $('table#table_item_row')).length;

        $btn.on('click', function(e){
           
            $row.remove();
            if($('tbody>tr', $('table#table_item_row')).length == 0){
                addItemRow();
            }
            
            e.preventDefault();
        });
    };

    function tambahIdentitasRow()
    {
        $('a#tambah_identitas').on('click', function(){
            addItemRow();
        });
    }

    function handleSelectItem($selectItem, $newItemRow){

        $selectItem.on('change', function(){
            var data_obat_alkes = $(this).val(),
                array_obat = data_obat_alkes.split("_"),
                id_item = parseInt(array_obat[0]);
                item_satuan_id = parseInt(array_obat[3]);
                nama_satuan = array_obat[4];

            $('input[name$="[item_id_hidden]"]', $newItemRow).val(id_item);
            $('input[name$="[satuan]"]', $newItemRow).val(item_satuan_id);
            $('div[name$="[div]"]', $newItemRow).text(nama_satuan);



            
        });

    }


</script>