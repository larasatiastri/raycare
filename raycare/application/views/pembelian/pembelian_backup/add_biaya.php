<form class="form-horizontal" id="form_add_biaya">
<div class="modal-body">
    <div class="portlet light" id="section-biaya">
        <div class="portlet-title">
            <div class="caption">
                <?=translate("BIAYA TAMBAHAN", $this->session->userdata("language"))?> 
            </div>
            <div class="actions">
                <a class="btn btn-icon-only btn-default btn-circle add-biaya">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body form">
            <?php
                $biaya_option = array(
                    ''  => translate('Pilih', $this->session->userdata('language')).'...'
                );

                $biaya = $this->biaya_m->get_by(array('is_active' => 1));

                foreach ($biaya as $row) {
                    $biaya_option[$row->id] = $row->nama;
                }

                $form_biaya = '
                    <div class="form-group">
                        <label class="col-md-12">'.translate("Jenis Biaya", $this->session->userdata("language")).' :</label>
                        <div class="col-md-12">
                            <div class="input-group">';
                $form_biaya .= form_dropdown('biaya[{0}][jenis_biaya]', $biaya_option, '','id="jenis_biaya_{0}" class="form-control"');             
                $form_biaya .= '<span class="input-group-btn">
                                    <a class="btn red-intense del-this-biaya" id="btn_delete_biaya_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">'.translate("Jumlah Biaya", $this->session->userdata("language")).' :</label>
                        <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        Rp.
                                    </span>
                                    <input class="form-control jumlah" required id="jumlah_biaya_{0}" name="biaya[{0}][jumlah_biaya]" placeholder="Jumlah Biaya">
                                </div>
                                <span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
                        </div>
                    </div>';
            ?>
        <input type="hidden" id="tpl-form-biaya" value="<?=htmlentities($form_biaya)?>">
        <input type="hidden" id="biaya_tambah_modal" name="biaya_tambah_modal">
        <div class="form-body" id="body_content">
            <ul class="list-unstyled" id="biayaList">
            </ul>
            <input type="hidden" id="jumlah_row" name="jumlah_row" value="0">
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

    $form          = $('#form_add_biaya');
    tplFormBiaya   = '<li class="fieldset-biaya">' + $('#tpl-form-biaya', $form).val() + '<hr></li>',
    regExpTplBiaya = new RegExp('biaya[0]', 'g'),   // 'g' perform global, case-insensitive
    biayaCounter   = parseInt($('input#jumlah_row').val()),
    formsBiaya = 
    {
        'biaya' : 
        {            
            section  : $('#section-biaya', $form),
            template : $.validator.format( tplFormBiaya.replace(regExpTplBiaya, '{0}') ), //ubah ke format template jquery validator
            counter  : function(){ biayaCounter++; return biayaCounter-1; },
            fieldPrefix : 'biaya'
        }   
    };

    $.each(formsBiaya, function(idx, formBiaya){
        var $section           = formBiaya.section,
            $fieldsetContainer = $('ul#biayaList', $section);

        addFieldsetBiaya(formBiaya,{});

        // handle button add
        $('a.add-biaya', formBiaya.section).on('click', function(){
            addFieldsetBiaya(formBiaya,{});
        });
         
    }); 


    handleModalOK();
    handleCheckHtml();
}); 

function handleCheckHtml(){
    $tabelBeli = $('#table_detail_pembelian');
    html_content = $('div#biaya_tambahan', $tabelBeli).html();
    if(html_content != ''){
        $('div#body_content').html($('div#biaya_tambahan', $tabelBeli).html());
        handleCountTotalBiaya();
        
        $form          = $('#form_add_biaya');
        $inputJumlah = $('input.jumlah', $form);
        $inputJumlah.on('change', function() {
            var jml = $(this).val();
            $(this).attr('value', jml);

            handleCountTotalBiaya();
        });

        $btnDelete = $('a.del-this-biaya', $form);
        $.each($btnDelete, function(idx, btnDlt){
            $btnDelete.click(function(){
                var id = $(this).data('id');
    
                handleDeleteFieldsetBiaya($(this).parents('.fieldset-biaya').eq(0), id);
            });
        });
    }
}

function addFieldsetBiaya(form,data)
{
    var 
        $section           = form.section,
        $fieldsetContainer = $('ul#biayaList', $section),
        counter            = form.counter(),
        $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
        fields             = form.fields,
        prefix             = form.fieldPrefix
    ;

    $('input#jumlah_row').val(counter);
    $('input#jumlah_row').attr('value',counter);

    $('a.del-this-biaya', $newFieldset).on('click', function(){
        var id = $(this).data('id');
    
        handleDeleteFieldsetBiaya($(this).parents('.fieldset-biaya').eq(0), id);
    });

    $('input[name$="[jumlah_biaya]"]', $newFieldset).on('change', function(){

        $('input[name$="[jumlah_biaya]"]', $newFieldset).attr('value', $(this).val());
        handleCountTotalBiaya();
    });

    $('select[name$="[jenis_biaya]"]', $newFieldset).select2();

    $('select[name$="[jenis_biaya]"]', $newFieldset).on('change', function(){
        $('option:selected', this).attr('selected','selected');
    });

    //jelasin warna hr pemisah antar fieldset
    $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');
};

function handleDeleteFieldsetBiaya($fieldset, id)
{
    var 
        $parentUl     = $fieldset.parent(),
        fieldsetCount = $('.fieldset-biaya', $parentUl).length,
        hasId         = false ; 

    if(id != undefined)
    {
        var i = 0;
        bootbox.confirm('Anda yakin akan menghapus biaya ini?', function(result) {
            if (result==true) {
                i = parseInt(i) + 1;
                if(i == 1)
                {
                    $('input[name$="[is_active]"]', $fieldset).val(0);
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
    var $totalBon = $('input[name$="[jumlah_biaya]"]', $form),
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

    $('input#biaya_tambah_modal').val(grandTotal);
}

function handleModalOK(){
    $form      = $('#form_add_biaya'),
    $tabelBeli = $('#table_detail_pembelian');

    $('a#modal_ok', $form).click(function(){
        var total_biaya = parseInt($('input#biaya_tambah_modal').val());
        var grand_total_po = parseInt($('input#grand_total_hidden', $tabelBeli).val());



        $('input#biaya_tambahan_show', $tabelBeli).val(mb.formatTanpaRp(total_biaya));
        $('input#biaya_tambahan', $tabelBeli).val(total_biaya);

        $('input#grand_total_biaya', $tabelBeli).val(mb.formatTanpaRp(total_biaya+grand_total_po));
        $('input#grand_total_biaya_hidden', $tabelBeli).val(total_biaya+grand_total_po);

        $('div#biaya_tambahan', $tabelBeli).html($('div#body_content').html());
        $('a#btn_close').click();   
    });

}

   
</script>