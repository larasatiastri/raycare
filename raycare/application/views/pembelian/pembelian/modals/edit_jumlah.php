<form class="form-horizontal" id="form_add_jumlah">
<div class="modal-body">
    <div class="portlet light" id="section-jumlah">
        <div class="portlet-title">
            <div class="caption">
                Input jumlah item <?=$data_item->nama?> [ <?=$data_item_satuan->nama?> ]
            </div>
            <div class="actions hidden">
                <a class="btn btn-icon-only btn-default btn-circle add-biaya">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body form">
            <input type="hidden" id="pembelian_detail_id" name="pembelian_detail_id" value="<?=$data_detail->id?>">
            <input type="hidden" id="item_id" name="item_id" value="<?=$data_item->id?>">
            <input type="hidden" id="satuan_id" name="satuan_id" value="<?=$data_item_satuan->id?>">
            <input type="hidden" id="index_row" name="index_row" value="<?=$index_row?>">
            <input type="hidden" id="id_row" name="id_row" value="<?=$id_row?>">
            <?php
                $x = 0;
                $form_jumlah_db = '';
                foreach ($data_kirim as $key => $kirim) {
                    $form_jumlah_db .= '<li class="fieldset-jumlah">
                        <div class="form-group">
                            <label class="control-label col-md-3">'.translate("Jumlah Kirim", $this->session->userdata("language")).' :</label>
                            <div class="col-md-6">
                                <div class="input-group">';
                    $form_jumlah_db .= ' <input class="form-control jumlah" required id="jumlah_kirim_'.$x.'" name="input_jumlah_'.$index_row.'['.$x.'][jumlah_kirim]" placeholder="Jumlah Kirim" value="'.$kirim['jumlah_kirim'].'">';             
                    $form_jumlah_db .= '<span class="input-group-btn">
                                        <a class="btn red-intense del-this-jumlah" id="btn_delete_jumlah_'.$x.'" title="'.translate('Remove', $this->session->userdata('language')).'" data-id="'.$kirim['id'].'"><i class="fa fa-times"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">'.translate("Tanggal Kirim", $this->session->userdata("language")).' :</label>
                            <div class="col-md-6">
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="tanggal_kirim_'.$x.'" name="input_jumlah_'.$index_row.'['.$x.'][tanggal_kirim]" readonly value="'.date('d M Y',strtotime($kirim['tanggal_kirim'])).'">
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group hidden">
                            <label class="control-label col-md-3">'.translate("Is Active", $this->session->userdata("language")).' :</label>
                            <div class="col-md-6">
                                <input class="form-control jumlah" required id="is_active_'.$x.'" name="input_jumlah_'.$index_row.'['.$x.'][is_active]" placeholder="Aktif" value="1">
                                <input class="form-control jumlah" required id="id_kirim_'.$x.'" name="input_jumlah_'.$index_row.'['.$x.'][id_kirim]" placeholder="ID" value="'.$kirim['id'].'">
                            </div>
                        </div>
                    <hr style="border-color: rgb(228, 228, 228);">
                    </li>';
                    $x++;
                }
                
                $form_jumlah = '
                    <div class="form-group">
                        <label class="control-label col-md-3">'.translate("Jumlah Kirim", $this->session->userdata("language")).' :</label>
                        <div class="col-md-6">
                            <div class="input-group">';
                $form_jumlah .= ' <input class="form-control jumlah" required id="jumlah_kirim_{0}" name="input_jumlah_'.$index_row.'[{0}][jumlah_kirim]" placeholder="Jumlah Kirim"><input class="form-control hidden" required id="id_kirim_{0}" name="input_jumlah_'.$index_row.'[{0}][id_kirim]" placeholder="Jumlah Kirim"><input class="form-control hidden" required id="is_active_{0}" name="input_jumlah_'.$index_row.'[{0}][is_active]" value="1">';             
                $form_jumlah .= '<span class="input-group-btn">
                                    <a class="btn red-intense del-this-jumlah" id="btn_delete_jumlah_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">'.translate("Tanggal Kirim", $this->session->userdata("language")).' :</label>
                        <div class="col-md-6">
                            <div class="input-group date">
                                <input type="text" class="form-control" id="tanggal_kirim_{0}" name="input_jumlah_'.$index_row.'[{0}][tanggal_kirim]" readonly >
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>';
            ?>
        <input type="hidden" id="tpl-form-jumlah" value="<?=htmlentities($form_jumlah)?>">
        <input type="hidden" id="jumlah_" name="jumlah_">
        <input type="hidden" id="input_jumlah_modal" name="input_jumlah_modal">
        <div class="form-body" id="body_content">
            <ul class="list-unstyled" id="jumlahList">
                <?php
                    if(count($form_jumlah_db)){
                        echo $form_jumlah_db;
                    }
                ?>
            </ul>
            <input type="hidden" id="jumlah_row" name="jumlah_row" value="<?=$x?>">
            <a class="btn btn-primary add-biaya">
                <i class="fa fa-plus"></i>
                <?=translate("Tambahan", $this->session->userdata("language"))?>
            </a>
        </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
    <a class="btn default" id="btn_close" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
</form>
<script>
$(document).ready(function() {

    handleCheckHtml();

    $form          = $('#form_add_jumlah');
    tplFormBiaya   = '<li class="fieldset-jumlah">' + $('#tpl-form-jumlah', $form).val() + '<hr></li>',
    regExpTplBiaya = new RegExp('biaya[0]', 'g'),   // 'g' perform global, case-insensitive
    biayaCounter   = parseInt($('input#jumlah_row').val()),
    formsBiaya = 
    {
        'biaya' : 
        {            
            section  : $('#section-jumlah', $form),
            template : $.validator.format( tplFormBiaya.replace(regExpTplBiaya, '{0}') ), //ubah ke format template jquery validator
            counter  : function(){ biayaCounter++; return biayaCounter-1; },
            fieldPrefix : 'biaya'
        }   
    };

    $.each(formsBiaya, function(idx, formBiaya){
        var $section           = formBiaya.section,
            $fieldsetContainer = $('ul#jumlahList', $section);

        // addFieldsetBiaya(formBiaya,{});

        // handle button add
        $('a.add-biaya', formBiaya.section).on('click', function(){
            addFieldsetBiaya(formBiaya,{});
            biayaCounter + 1;
            $('input#jumlah_row').val(biayaCounter);
            $('input#jumlah_row').attr('value',biayaCounter);
        });
         
    }); 

    
    handleDatePickers();
    $tglKirim = $('input[name$="[tanggal_kirim]"]', $form);
    $.each($tglKirim, function(idx, tglKirim){
        $tglKirim.on('change',function(){
            $('input[name$="[tanggal_kirim]"]', $(this).parents('.fieldset-jumlah').eq(0)).attr('value', $(this).val());
        });
    });
    handleModalOK();
    handleCountTotalBiaya();

    
}); 

function handleCheckHtml(){
    $form      = $('#form_add_jumlah');
    $tabelBeli = $('#table_detail_pembelian');
    row_id = $('input#id_row', $form).val();
    $row = $('#'+row_id, $tabelBeli);

    html_content = $('div#detail_kirim', $row).html();
    if(html_content != ''){
        $('div#body_content').html($('div#detail_kirim', $row).html());
        handleCountTotalBiaya();
        
        $form          = $('#form_add_jumlah');
        $inputJumlah = $('input.jumlah', $form);
        $inputJumlah.on('change', function() {
            var jml = $(this).val();
            $(this).attr('value', jml);

            handleCountTotalBiaya();
        });

        $btnDelete = $('a.del-this-jumlah', $form);
        $.each($btnDelete, function(idx, btnDlt){
            $btnDelete.click(function(){
                var id = $(this).data('id');
    
                handleDeleteFieldsetJumlah($(this).parents('.fieldset-jumlah').eq(0), id);
            });
        });
        // biayaCounter   = parseInt($('input#jumlah_row').val());
    }
}

function addFieldsetBiaya(form,data)
{
    var 
        $section           = form.section,
        $fieldsetContainer = $('ul#jumlahList', $section),
        counter            = form.counter(),
        $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
        fields             = form.fields,
        prefix             = form.fieldPrefix
    ;

    $('input#jumlah_row').val(counter);
    $('input#jumlah_row').attr('value',counter);

    $('a.del-this-jumlah', $newFieldset).on('click', function(){
        var id = $(this).data('id');
    
        handleDeleteFieldsetJumlah($(this).parents('.fieldset-jumlah').eq(0), id);
    });

    $('input[name$="[jumlah_kirim]"]', $newFieldset).on('change', function(){

        $('input[name$="[jumlah_kirim]"]', $newFieldset).attr('value', $(this).val());
        handleCountTotalBiaya();
    }); 
    $('input[name$="[tanggal_kirim]"]', $newFieldset).on('change', function(){

        $('input[name$="[tanggal_kirim]"]', $newFieldset).attr('value', $(this).val());
    });

    

    handleDatePickers();

    //jelasin warna hr pemisah antar fieldset
    $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');


};

function handleDeleteFieldsetJumlah($fieldset, id)
{
    var 
        $parentUl     = $fieldset.parent(),
        fieldsetCount = $('.fieldset-jumlah', $parentUl).length,
        hasId         = false ; 

    if(id != undefined)
    {
        var i = 0;
        bootbox.confirm('Anda yakin akan menghapus jumlah ini?', function(result) {
            if (result==true) {
                i = parseInt(i) + 1;
                if(i == 1)
                {
                    $('input[name$="[is_active]"]', $fieldset).val(0);
                    $('input[name$="[is_active]"]', $fieldset).attr('value',0);
                    $fieldset.hide();        
                }
            }
        });
    }
    else
    {
        if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
        $fieldset.remove();            
    }
    handleCountTotalBiaya();
}

function handleCountTotalBiaya(){
    var $totalBon = $('input[name$="[jumlah_kirim]"]', $form),
        grandTotal = 0;
    
    $.each($totalBon, function(idx, totalbon){
        var total = $(this).val();

        if(total == ''){
            total = 0;
        }if(total != ''){
            total = parseInt(total);
        }

        grandTotal = grandTotal + total;
    });

    $('input#input_jumlah_modal').val(grandTotal);
}

function handleModalOK(){
    $form      = $('#form_add_jumlah'),
    $tabelBeli = $('#table_detail_pembelian');
    row_id = $('input#id_row', $form).val();
    $row = $('#'+row_id, $tabelBeli);

    $('a#modal_ok', $form).click(function(){
        var total_biaya = parseInt($('input#input_jumlah_modal').val());
        var harga = parseInt($('input[name$="[item_harga]"]', $row).val());


        $('input[name$="[jumlah]"]', $row).val(total_biaya);

        $('div#detail_kirim', $row).html($('div#body_content').html());
        $('label[name$="[item_sub_total]"]', $row).text(mb.formatRp(total_biaya * harga));
        $('input[name$="[item_sub_total]"]', $row).val(total_biaya * harga);
        
        $('a#btn_close').click();   
    });

}

function handleDatePickers() {

    if (jQuery().datepicker) {
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            orientation: "left",
            autoclose: true,
            format : 'dd M yyyy'
        }).on('changeDate', function(){
            $('div.datepicker-dropdown').hide();
        });
        $('body').removeClass("modal-open");

        $('.date').datepicker({
            rtl: Metronic.isRTL(),
            orientation: "left",
            autoclose: true,
            format : 'dd M yyyy'
        }).on('changeDate', function(){
            $('div.datepicker-dropdown').hide();
        });
        $('body').removeClass("modal-open");
    }

    /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
}

   
</script>