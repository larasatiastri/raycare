<form class="form-horizontal" id="form_edit_biaya">
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
                    <div class="form-group hidden">
                        <label class="control-label col-md-4">'.translate("ID", $this->session->userdata("language")).' :</label>
                        <div class="col-md-8">
                            <input class="form-control" id="id_biaya{0}" name="biaya[{0}][id]">
                        </div>
                    </div>
                    <div class="form-group hidden">
                        <label class="control-label col-md-4">'.translate("Active", $this->session->userdata("language")).' :</label>
                        <div class="col-md-8">
                            <input class="form-control" id="is_active_biaya{0}" name="biaya[{0}][is_active]">
                            <input class="form-control" id="pembelian_id_biaya{0}" name="biaya[{0}][pembelian_id]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">'.translate("Jenis Biaya", $this->session->userdata("language")).' :</label>
                        <div class="col-md-12">
                            <div class="input-group">';
                $form_biaya .= form_dropdown('biaya[{0}][biaya_id]', $biaya_option, '','id="jenis_biaya_{0}" class="form-control"');             
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
                                    <input class="form-control jumlah" required id="jumlah_biaya_{0}" name="biaya[{0}][nominal]" placeholder="Jumlah Biaya">
                                </div>
                                <span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
                        </div>
                    </div>';
            ?>
        <input type="hidden" id="tpl-form-biaya" value="<?=htmlentities($form_biaya)?>">
        <input type="hidden" id="biaya_tambah_modal" name="biaya_tambah_modal">
        <input type="hidden" id="id_po" name="id_po" value="<?=$id_po?>">
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

    $form          = $('#form_edit_biaya'),
    baseAppUrl      = mb.baseUrl() + 'pembelian/pembelian/',
    tplFormBiaya   = '<li class="fieldset-biaya">' + $('#tpl-form-biaya', $form).val() + '<hr></li>',
    regExpTplBiaya = new RegExp('biaya[0]', 'g'),   // 'g' perform global, case-insensitive
    biayaCounter   = parseInt($('input#jumlah_row').val()),
    id_po = $('input#id_po', $form).val(),
    formsBiaya = 
    {
        'biaya' : 
        {            
            section  : $('#section-biaya', $form),
            urlData  : function(){ return baseAppUrl + 'get_biaya_tambahan'; },
            template : $.validator.format( tplFormBiaya.replace(regExpTplBiaya, '{0}') ), //ubah ke format template jquery validator
            counter  : function(){ biayaCounter++; return biayaCounter-1; },
            fields   : ['id','pembelian_id','biaya_id','nominal','is_active'],
            fieldPrefix : 'biaya'
        }   
    };
    
    handleModalOK();
    handleCheckHtml();
}); 

function handleCheckHtml(){
    $tabelBeli = $('#table_detail_pembelian');
    html_content = $('div#biaya_tambahan', $tabelBeli).html();
    if(html_content != ''){
        $('div#body_content').html($('div#biaya_tambahan', $tabelBeli).html());
        handleCountTotalBiaya();
        
        $form          = $('#form_edit_biaya');
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
        $.each(formsBiaya, function(idx, formBiaya){
            var $section           = formBiaya.section,
                $fieldsetContainer = $('ul#biayaList', $section);

            // handle button add
            $('a.add-biaya', formBiaya.section).on('click', function(){
                addFieldsetBiaya(formBiaya,{});
            });
             
        }); 
    }else{
        $.each(formsBiaya, function(idx, formBiaya){
            var $section           = formBiaya.section,
                $fieldsetContainer = $('ul#biayaList', $section);

            $.ajax({
                type     : 'POST',
                url      : formBiaya.urlData(),
                data     : {id_po: id_po},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    if (results.success === true) {
                        var rows = results.rows;

                        $.each(rows, function(idx, data){
                            addFieldsetBiaya(formBiaya,data);
                        });
                    }
                    else
                    {
                        addFieldsetBiaya(formBiaya,{});
                    }

                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });

            

            // handle button add
            $('a.add-biaya', formBiaya.section).on('click', function(){
                addFieldsetBiaya(formBiaya,{});
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

    if(Object.keys(data).length>0){
        for (var i=0; i<fields.length; i++){
            // format: name="emails[_ID_1][subject]"
            $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).val( data[fields[i]] );
            $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).attr('value', data[fields[i]] );
            $('a.del-this-biaya', $newFieldset).attr('data-id',data[fields[0]]);
        }       
    }

    $('a.del-this-biaya', $newFieldset).on('click', function(){
        var id = $(this).data('id');
    
        handleDeleteFieldsetBiaya($(this).parents('.fieldset-biaya').eq(0), id);
    });

    $('input[name$="[nominal]"]', $newFieldset).on('change', function(){

        $('input[name$="[nominal]"]', $newFieldset).attr('value', $(this).val());
        handleCountTotalBiaya();
    });

    $('select[name$="[biaya_id]"]', $newFieldset).select2();

    $('select[name$="[biaya_id]"]', $newFieldset).on('change', function(){
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
    var $totalBon = $('input[name$="[nominal]"]', $form),
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
    $form      = $('#form_edit_biaya'),
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