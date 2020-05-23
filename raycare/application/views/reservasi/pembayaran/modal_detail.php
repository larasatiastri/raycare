<?php
    $checked = '';

    if($data_invoice['jenis_invoice'] == 2){
        $checked = 'checked="checked"';
    }
    $i=1;
    $html = '';
    $total = 0;
    if(!empty($data_paket)){
        foreach ($data_paket as $key => $paket) {
            $total = $total + ($paket['harga']*$paket['qty']);
            $input_hidden = '<input type="hidden" class="form-control" name="items_'.$index.'['.$i.'][id]" value="'.$paket['id_detail'].'"><input type="hidden" class="form-control" name="items_'.$index.'['.$i.'][item_id]" value="'.$paket['id'].'"><input type="hidden" class="form-control" name="items_'.$index.'['.$i.'][tipe_item]" value="1"><input type="hidden" class="form-control" name="items_'.$index.'['.$i.'][harga]" value="'.($paket['harga']*$paket['qty']).'"><input type="hidden" class="form-control" name="items_'.$index.'['.$i.'][nama_item]" value="Paket Hemodialisa">';
            $html .= '<tr>
                    <td><div class="text-center">'.$i.'</div></td>
                    <td><div class="text-left">Paket Hemodialisa</div></td>
                    <td><div class="text-right">'.formatrupiah($paket['harga']*$paket['qty']).'</div></td>
                    <td class="text-center"><div class="text-center"><input class="form-control checkboxes" name="items_'.$index.'['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.($paket['harga']*$paket['qty']).'" '.$checked.'>'.$input_hidden.'</div></td>
                        
            </tr>';
            $i++;
        }
    }
    $j=$i;
    if(!empty($data_items)){
        foreach ($data_items as $key => $item) {
            $harga = $item['harga']*$item['qty'];

            if($data_invoice['jenis_invoice'] == 2){
                $checked = 'checked="checked"';
                $harga = ($item['harga']*$item['qty']) + $data_invoice['akomodasi'];
                $total = $total + $harga;

            }

            $input_hidden = '<input type="hidden" class="form-control" name="items_'.$index.'['.$j.'][id]" value="'.$item['id_detail'].'"><input type="hidden" class="form-control" name="items_'.$index.'['.$j.'][item_id]" value="'.$item['id'].'"><input type="hidden" class="form-control" name="items_'.$index.'['.$j.'][tipe_item]" value="2"><input type="hidden" class="form-control" name="items_'.$index.'['.$j.'][harga]" value="'.$harga.'"><input type="hidden" class="form-control" name="items_'.$index.'['.$j.'][nama_item]" value="'.$item['nama'].'">';

            $html .= '<tr>
                    <td><div class="text-center">'.$j.'</div></td>
                    <td><div class="text-left">'.$item['nama'].'</div></td>
                    <td><div class="text-right">'.formatrupiah($item['harga']*$item['qty']).'</div></td>
                    <td class="text-center"><div class="text-center"><input class="form-control checkboxes" name="items_'.$index.'['.$j.'][checkbox]" id="checkbox_'.$j.'" type="checkbox" data-rp="'.$harga.'" '.$checked.'>'.$input_hidden.'</div></td>
                        
            </tr>';
            $j++;
        }
    }
    $k=$j;
    if(!empty($data_tindakan)){
        foreach ($data_tindakan as $key => $tindakan) {
            $harga = $tindakan['harga']*$tindakan['qty'];

            if($data_invoice['jenis_invoice'] == 2){
                $checked = 'checked="checked"';
                $harga = ($tindakan['harga']*$tindakan['qty']) + $data_invoice['akomodasi'];
                $total = $total + ($tindakan['harga']*$tindakan['qty']);

            }

            $input_hidden = '<input type="hidden" class="form-control" name="items_'.$index.'['.$k.'][id]" value="'.$tindakan['id_detail'].'"><input type="hidden" class="form-control" name="items_'.$index.'['.$k.'][item_id]" value="'.$tindakan['id'].'"><input type="hidden" class="form-control" name="items_'.$index.'['.$k.'][tipe_item]" value="3"><input type="hidden" class="form-control" name="items_'.$index.'['.$k.'][harga]" value="'.$harga.'"><input type="hidden" class="form-control" name="items_'.$index.'['.$k.'][nama_item]" value="'.$tindakan['nama'].'">';

            $html .= '<tr>
                    <td><div class="text-center">'.$k.'</div></td>
                    <td><div class="text-left">'.$tindakan['nama'].'</div></td>
                    <td><div class="text-right">'.formatrupiah($tindakan['harga']*$tindakan['qty']).'</div></td>
                    <td class="text-center"><div class="text-center"><input class="form-control checkboxes" name="items_'.$index.'['.$k.'][checkbox]" id="checkbox_'.$k.'" type="checkbox" data-rp="'.$harga.'" '.$checked.'>'.$input_hidden.'</div></td>
                        
            </tr>';
            $k++;
        }
    }
    
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Invoice', $this->session->userdata('language')).' No.'.$data_invoice['no_invoice']?></span>
    </div>
</div>
<div class="modal-body">
<div class="portlet light">
    <div class="portlet-body">
        <input type="hidden" class="form-control" id="row_id" name="row_id" value="<?=$row_id?>">
        <input type="hidden" class="form-control" id="jenis_invoice" name="jenis_invoice" value="<?=$data_invoice['jenis_invoice']?>">
        <table class="table table-striped table-bordered table-hover" id="table_data_item" >
            <thead>
              <tr>
                <th class="text-center" width="3%"><?=translate("No", $this->session->userdata('language'))?></th>
                <th class="text-center" width="15%"><?=translate("Nama", $this->session->userdata('language'))?></th>
                <th class="text-center" width="15%"><?=translate("Harga", $this->session->userdata('language'))?></th>
                <th class="text-center" width="1%"><?=translate("Pilih", $this->session->userdata('language'))?></th>
                
              </tr>
            </thead>
            <tbody id="table_content_item">
                <?php

                    echo $html;
                ?>
            </tbody>
            <tfoot>
            <?php
                if($data_invoice['akomodasi'] != NULL && $data_invoice['akomodasi'] != 0)
                {
            ?>
            <tr>
                <th colspan="2">Akomodasi</th>
                <th class="text-right" colspan="2"><b id="total_akomodasi"><?=formatrupiah($data_invoice['akomodasi'])?></b></th>
            </tr> 
            <?php
                }
            ?>

            <tr>
                <th colspan="2">Total Bayar</th>
                <th class="text-right" colspan="2"><b id="total_bayar"></b><input type="hidden" class="form-control" name="items[total]" value="<?=$total?>"><input type="hidden" class="form-control" id="akomodasi" name="items[akomodasi]" value="<?=$data_invoice['akomodasi']?>"></th>
            </tr>

            </tfoot>
        </table>
    </div>
</div>
</div>
<div class="modal-footer">
    <a class="btn default hidden" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
    <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
    <a class="btn btn-primary" id="simpan" ><?=translate("OK", $this->session->userdata("language"))?></a>
</div>

<script type="text/javascript">
$(document).ready(function(){
    baseAppUrl = mb.baseUrl() + 'reservasi/pembayaran/';
    $('input.checkboxes', $('#table_data_item')).uniform();

    checklist();
    btnOk();
    if($('input#jenis_invoice').val() == 2){
        hitungAkomodasi();
    }
    var rowId = $('input#row_id').val(),
            $row = $('#'+rowId),
            data_temporary = $('div#temporary_data', $row).html();

    if(data_temporary != ''){
        $('tbody#table_content_item').html(data_temporary);
        hitung();
        checklist();
    }
});

function hitungAkomodasi(){

    var subtotal      = parseInt($('input#subtotal_hidden').val()),
        total         = 0,
        total_klaim   = parseInt($('input[name$="[total]"]').val()),
        sisa_hutang   = parseInt($('input#sisa_hutang_hidden').val()),
        totalBayar    = 0;
    
    $('input.checkboxes').prop('checked');

    var $check = $('input.checkboxes', $('#table_data_item'));
        total_klaim   = 0;
        akomodasi   = parseInt($('#akomodasi').val());

    $.each($check, function(idx, check){
        if($(this).prop('checked') == true){
            var rp      = $(this).data('rp');
            var rpInt   = parseInt(rp);

            total_klaim = total_klaim + rpInt;                
        }
    });
    total_klaim = total_klaim;
    $('b#total_bayar').text(mb.formatRp(total_klaim));
    $('input[name$="[total]"]').val(total_klaim);
    $('input[name$="[total]"]').attr('value',total_klaim);

    if(subtotal != 0){
        subtotal = subtotal - total_klaim;
    }
    subtotal = subtotal + total_klaim;
    if(sisa_hutang != 0){
        sisa_hutang = sisa_hutang - total_klaim;
    }
    $('input#subtotal').val(mb.formatTanpaRp(subtotal));
    $('input#subtotal_hidden').val(subtotal);

    $('input#grand_total').val(mb.formatTanpaRp(subtotal));
    $('input#grand_total_hidden').val(subtotal);

    $('input#sisa_hutang').val(mb.formatTanpaRp(sisa_hutang));
    $('input#sisa_hutang_hidden').val(sisa_hutang);
}

function hitung(){
    var $check = $('input.checkboxes', $('#table_data_item'));
        total_klaim   = 0;

    $.each($check, function(idx, check){
        if($(this).prop('checked') == true){
            var rp      = $(this).data('rp');
            var rpInt   = parseInt(rp);

            total_klaim = total_klaim + rpInt;                
            $('b#total_bayar').text(mb.formatRp(total_klaim));
            $('input[name$="[total]"]').val(total_klaim);
            $('input[name$="[total]"]').attr('value',total_klaim);

        }
    });
}

function btnOk(){
    $('a#simpan').click(function(){
        var rowId = $('input#row_id').val(),
            $row = $('#'+rowId);

        $('div#temporary_data', $row).html($('tbody#table_content_item').html());
        $('input[name$="[harga_invoice]"]', $row).val($('input[name$="[total]"]').val());
        $('input[name$="[harga_invoice]"]', $row).attr('value',$('input[name$="[total]"]').val());
        $('a#close').click();
    });
}



function checklist(){

    var subtotal      = parseInt($('input#subtotal_hidden').val()),
        total         = 0,
        total_klaim   = parseInt($('input[name$="[total]"]').val()),
        sisa_hutang   = parseInt($('input#sisa_hutang_hidden').val()),
        totalBayar    = 0;

    $('input.checkboxes', $('#table_data_item')).on('click', function(){

        // checked = $(this).prop('checked');
        if($(this).prop('checked') == true)
        {
            // alert('checked');
            $(this).parent().addClass('checked');
            $(this).attr('checked','checked');
            
            var rp      = $(this).data('rp');
            var rpInt   = parseInt(rp);

            total_klaim = total_klaim + rpInt;                
            $('b#total_bayar').text(mb.formatRp(total_klaim));
            $('input[name$="[total]"]').val(total_klaim);

            subtotal = subtotal + rpInt;
            sisa_hutang = sisa_hutang - rpInt;
            $('input#subtotal').val(mb.formatTanpaRp(subtotal));
            $('input#subtotal_hidden').val(subtotal);

            $('input#grand_total').val(mb.formatTanpaRp(subtotal));
            $('input#grand_total_hidden').val(subtotal);

            $('input#sisa_hutang').val(mb.formatTanpaRp(sisa_hutang));
            $('input#sisa_hutang_hidden').val(sisa_hutang);
            // var cash = parseInt($(this).val());
            var grand_total_klaim = parseInt($('input[name$="[total]"]').val());
            var grand_total = parseInt($('input#grand_total_hidden').val());

        }else{
            var rp      = $(this).data('rp');
            var rpInt   = parseInt(rp);
            $(this).parent().removeClass('checked');
            $(this).removeAttr('checked');

            // alert(rpInt);

            total_klaim = total_klaim - rpInt;                
            $('b#total_bayar').text(mb.formatRp(total_klaim));
            $('input[name$="[total]"]').val(total_klaim);

            subtotal = subtotal - rpInt;
            sisa_hutang = sisa_hutang + rpInt;
            $('input#subtotal').val(mb.formatTanpaRp(subtotal));
            $('input#subtotal_hidden').val(subtotal);

            $('input#grand_total').val(mb.formatTanpaRp(subtotal));
            $('input#grand_total_hidden').val(subtotal);

            $('input#sisa_hutang').val(mb.formatTanpaRp(sisa_hutang));
            $('input#sisa_hutang_hidden').val(sisa_hutang);

            // $(this).parents('tr').toggleClass("active");
            // $(this).attr("checked", false);
            

            // var cash = parseInt($(this).val());
            var grand_total_klaim = parseInt($('input[name$="[total]"]').val());
            var grand_total = parseInt($('input#grand_total_hidden').val());

        }     
    });

    $('#table_data_item .group-checkable').change(function () {
        var subtotal                = parseInt($('input#subtotal_hidden').val()),
        total                   = 0,
        total_klaim             = parseInt($('input[name$="[total]"]').val()),
        sisa_hutang   = parseInt($('input#sisa_hutang_hidden').val()),
        totalBayar              = 0;
        
        var set = $(this).attr("data-set");
        var checked = $(this).is(":checked");
            $.uniform.update(set);
            // alert(klaim);
            $(set).each(function () {
            if (checked) 
            {
                $(this).parent().addClass('checked');
                $(this).attr('checked','checked');
            
                var rp      = $(this).data('rp');
                var rpInt   = parseInt(rp);

                total_klaim = total_klaim + rpInt;                
                $('b#total_bayar').text(mb.formatRp(total_klaim));
                $('input[name$="[total]"]').val(total_klaim);

                subtotal = subtotal + rpInt;
                sisa_hutang = sisa_hutang - subtotal;
                $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                $('input#subtotal_hidden').val(subtotal);

                $('input#grand_total').val(mb.formatTanpaRp(subtotal));
                $('input#grand_total_hidden').val(subtotal);

                $('input#sisa_hutang').val(mb.formatTanpaRp(sisa_hutang));
                $('input#sisa_hutang_hidden').val(sisa_hutang);

                // var cash = parseInt($(this).val());
                var grand_total_klaim = parseInt($('input[name$="[total]"]').val());
                var grand_total = parseInt($('input#grand_total_hidden').val());

            } else {
                var rp      = $(this).data('rp');
                var rpInt   = parseInt(rp);
                $(this).parent().removeClass('checked');
                $(this).removeAttr('checked');

                // alert(rpInt);

                total_klaim = total_klaim - rpInt;                
                $('b#total_bayar').text(mb.formatRp(total_klaim));
                $('input[name$="[total]"]').val(total_klaim);

                subtotal = subtotal - rpInt;
                sisa_hutang = sisa_hutang + subtotal;
                $('input#subtotal').val(mb.formatTanpaRp(subtotal));
                $('input#subtotal_hidden').val(subtotal);

                $('input#grand_total').val(mb.formatTanpaRp(subtotal));
                $('input#grand_total_hidden').val(subtotal);

                $('input#sisa_hutang').val(mb.formatTanpaRp(sisa_hutang));
                $('input#sisa_hutang_hidden').val(sisa_hutang);
            }                    
        });
    });


}

</script>