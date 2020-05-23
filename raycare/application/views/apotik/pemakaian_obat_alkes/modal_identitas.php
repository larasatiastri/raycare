<?php

    $msg = translate('Anda yakin akan menambahkan invoice di tindakan ini?', $this->session->userdata('language'));
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $inv_id = '';
    $inv_identitas_id = '';


    $checkbn = ($data_item_identitas[0] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('Batch Number', $this->session->userdata("language")).'</th>':'';
    $checkexpire = ($data_item_identitas[1] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('Expire Date', $this->session->userdata("language")).'</th>':'';
    $checkdll1 = ($data_item_identitas[2] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('ID1', $this->session->userdata("language")).'</th>':'';
    $checkdll2 = ($data_item_identitas[3] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('ID2', $this->session->userdata("language")).'</th>':'';
    $checkdll3 = ($data_item_identitas[4] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('ID3', $this->session->userdata("language")).'</th>':'';


?>
<form id="modal_identitas" name="modal_identitas" role="form" class="form-horizontal" autocomplete="off">
    <input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       
    <input type="hidden" id="row_id" name="row_id" required="required" class="form-control" value="<?=$row_id?>">                       

    <div class="modal-body" id="section-identitas">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <?=$data_item['nama'].' / '.$data_item_satuan['nama']?>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <?=$form_alert_danger?>
                    </div>
                    <div class="alert alert-success display-hide">
                        <button class="close" data-close="alert"></button>
                        <?=$form_alert_success?>
                    </div>

                    <div class="form-body" >

                        <table class="table table-striped table-bordered table-hover" id="table_identitas">
                            <thead>
                                <tr class="heading">
                                    <th class="text-center" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
                                    <?php
                                        echo $checkbn.$checkexpire.$checkdll1.$checkdll2.$checkdll3;
                                        
                                    ?>
                                    <th class="text-center" style="width : 10% !important;"><?=translate("Gudang", $this->session->userdata("language"))?></th>
                                    <th class="text-center" style="width : 10% !important;"><?=translate("Stock", $this->session->userdata("language"))?></th>
                                    <th class="text-center" style="width : 15% !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                                </tr>
                            </thead>
                                
                            <tbody id="content_identitas">
                            <tr>
                                <?php
                                    if($data_item['is_identitas'] == 1){
                                        $i = 1;
                                        $index = 0;
                                        foreach ($data_inventory as $inventory) {
                                            $rowbn = ($inventory['bn_sn_lot'] != null)?'<td class="text-left" style="width : 16% !important;"><input type="hidden" id="bn_sn_lot_'.$i.'" name="identitas_'.$item_id.'['.$index.'][bn_sn_lot]" value="'.$inventory['bn_sn_lot'].'">'.$inventory['bn_sn_lot'].'</td>':'';
                                            $rowexpire = ($inventory['expire_date'] != null)?'<td class="text-left" style="width : 16% !important;"><input type="hidden" id="bn_sn_lot_'.$i.'" name="identitas_'.$item_id.'['.$index.'][expire_date]" value="'.date('d M Y', strtotime($inventory['expire_date'])).'">'.date('d M Y', strtotime($inventory['expire_date'])).'</td>':'';


                                            echo '<td class="text-center" style="width : 5% !important;">'.$i.'</td>'.$rowbn.$rowexpire.' <td class="text-left" style="width : 16% !important;">'.$inventory['nama_gudang'].'</td><td class="text-left" style="width : 16% !important;">'.$inventory['jumlah'].'</td><td class="text-left" style="width : 16% !important;"><div class="input-group"><input type="number" class="form-control jumlah" id="jml_'.$i.'" name="identitas_'.$item_id.'['.$index.'][jumlah_identitas]" value="0" min="0" max="'.$inventory['jumlah'].'"><span class="input-group-addon">&nbsp;'.$data_item_satuan['nama'].'&nbsp;</span></div><input type="hidden" id="jml_'.$i.'" name="identitas_'.$item_id.'['.$index.'][inventory_id]" value="'.$inventory['inventory_id'].'"></td><input type="hidden" id="jml_'.$i.'" name="identitas_'.$item_id.'['.$index.'][gudang_id]" value="'.$inventory['gudang_id'].'"><input type="hidden" id="hpp_'.$i.'" name="identitas_'.$item_id.'['.$index.'][hpp]" value="'.$inventory['harga_beli'].'"></td></tr>';

                                            $i++;
                                            $index++;
                                        }
                                    }
                                ?>

                            </tbody>
                        </table>
                    </div>
                    
                    <input type="hidden" class="form-control" id="total_jml" name="total_jml"></input>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
        <a id="confirm_save_modal" class="btn btn-primary"><?=translate("Simpan", $this->session->userdata("language"))?></a>
        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function(){

    $table = $('#table_identitas');
    $inputJumlah = $('input.jumlah', $table);
    $inputJumlah.on('change keyup', function() {
        var jml = $(this).val();
        $(this).attr('value', jml);

        calculateTotal();
    });

    handleCheckHtml();
    handleConfirmSave();


}); 

function handleCheckHtml(){
    rowId = $('input#row_id').val();
    $row = $('#' + rowId);

    html_content = $('div#identitas_row', $row).html();
    if(html_content != ''){
        $('tbody#content_identitas').html($('div#identitas_row', $row).html());
        calculateTotal();
        $table = $('#table_identitas');
        $inputJumlah = $('input.jumlah', $table);
        $inputJumlah.on('change keyup', function() {
            var jml = $(this).val();
            $(this).attr('value', jml);

            calculateTotal();
        });
    }
}

function addFieldsetParent(form)
{
    var 
        $section           = form.section,
        $fieldsetContainer = $('ul.list-unstyled', $section),
        counter            = form.counter(),
        $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer)
    ;

    $('select.select2me', $newFieldset).select2({});
    
    $('input[name$="[jumlah]"]', $newFieldset).on('change keyup', function(){
        calculateTotal();
    });

    $('a.del-this', $newFieldset).on('click', function(){
    
        handleDeleteFieldset($(this).parents('.fieldset').eq(0));
    });

    $('input[name$="[value]"]', $newFieldset).on('change', function() {
        // body...
        $('input[name$="[value]"]', $newFieldset).attr('value', $(this).val());
    });
    $('select[name$="[value]"]', $newFieldset).on('change', function(){
        var id = $(this).val();

        $('option:selected', this).attr('selected','selected');
        $.ajax({
            type     : 'POST',
            url      : mb.baseUrl() + 'gudang/pemakaian_obat_alkes/get_stok_identitas',
            data     : {id:id},
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success  : function( results ) {
                
                $('input[name$="[jumlah]"]', $newFieldset).attr('max',results.stok);
               
            },
            complete : function(){
                Metronic.unblockUI();
            }
        });
    });
    //jelasin warna hr pemisah antar fieldset
    $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');
};

function handleDeleteFieldset($fieldset)
{
    var 
        $parentUl     = $fieldset.parent(),
        fieldsetCount = $('.fieldset', $parentUl).length,
        hasId         = false ; 

        if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
        $fieldset.remove();            
    
}

function calculateTotal(){
    $form = $('#modal_identitas');
    $table = $('#table_identitas');
    $inputJumlah = $('input.jumlah', $table);

    total = 0;
    $.each($inputJumlah, function(index, jml){
        total = parseInt(total) + parseInt($(this).val());
    });

    $('input#total_jml', $form).val(total);
}
function handleConfirmSave() {
    $('a#confirm_save_modal').click(function(){
        rowId = $('input#row_id').val();
        $row = $('#' + rowId);

        $('div#identitas_row', $row).html($('tbody#content_identitas').html());
        $('input[name$="[jumlah]"]', $row).attr('value', $('input#total_jml').val());

        $('a#close').click();
    });
}




</script>