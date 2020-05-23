<?php
    $form_attr = array(
        "id"            => "form_add_barang_datang_farmasi", 
        "name"          => "form_add_barang_datang_farmasi", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );


    echo form_open(base_url()."gudang/barang_datang_farmasi/save", $form_attr);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
    
    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');

    $get_nama = $this->item_m->get_by(array('id' => $item_id), true);


?>
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-blue-sharp bold uppercase"><?=$get_nama->nama?></span>
        </div>

        
    </div>
    <div class="portlet-body">

        <input type="hidden" id="item_id_modal_identitas" name="item_id" value="<?=$item_id?>">
        <input type="hidden" id="item_satuan_id_modal_identitas" name="item_satuan_id" value="<?=$item_satuan_id?>">
        <input type="hidden" id="row_id_modal_identitas" value="<?=$row_id?>">
        <input type="hidden" id="identitasCounterModal" value="1">
        <input type="hidden" id="draft_detail_id" name="draft_detail_id" value="<?=$draft_detail_id?>">
        <input type="hidden" id="row_id" name="row_id" value="0">
        <input type="hidden" id="draft_id" name="draft_id" value="<?=$draft_pmb_id?>">
        <input type="hidden" id="supplier_id" name="supplier_id" value="<?=$supplier_id?>">
        <input type="hidden" id="gudang_id" name="gudang_id" value="<?=$gudang_id?>">
        <input type="hidden" id="po_detail_id" name="po_detail_id" value="<?=$po_detail_id?>">
        <input type="hidden" id="jumlah_pesan" name="jumlah_pesan" value="">
        
        <?php

            $get_group_indetitas = $this->draft_pmb_detail_actual_m->get_by(array('item_id' => $item_id, 'draft_pmb_detail_id' => $draft_detail_id));
            $group_indetitas = object_to_array($get_group_indetitas);

            if($group_indetitas)
            {
                $total = 1;
                $j = 0;
                $jml_data = 0;
                foreach ($group_indetitas as $group) 
                {

                    $option = '';
                    $satuan_terkecil    = $this->item_satuan_m->get_satuan_terkecil($item_id)->result_array();
                    // die(dump($satuan_terkecil));
                    $data_konversi = $this->item_satuan_m->get_data_konversi($item_id, $satuan_terkecil[0]['id']);
// die(dump($data_konversi));
                    foreach ($data_konversi as $data)
                    { 
                        $thisis = '';

                        if($data['id'] == $group['item_satuan_id'])
                        {
                            $thisis = 'selected="selected"';
                        }
                        // $option .= '<option value="'.$data['id'].'" data-konversi="'.$data['nilai_konversi'].'" '.$thisis.'>'.$data['nama'].'</option>'; 
                        $option .= '<option value="'.$data['id'].'" data-konversi="1" '.$thisis.'>'.$data['nama'].'</option>'; 
                    }

                    $type = '<td class="text-center no_urut hidden" id="no">1</td>';
                    $type .= '<td>
                                <input type="text" class="form-control send-input" id="identitas_detail_value_'.$item_id.'_'.$item_satuan_id.'_'.$j.'" name="identitas_'.$j.'['.$j.'][bn_sn_lot]" placeholder="Batch Number" value="'.$group['bn_sn_lot'].'">
                                <input type="hidden" class="form-control send-id" id="identitas_detail_id_'.$item_id.'_'.$item_satuan_id.'_'.$j.'" name="identitas_'.$j.'['.$j.'][draft_identitas_id]" value="">
                            </td>';  
                    $type .= '<td> <div class="input-group date"> <input type="text" class="form-control send-input" id="identitas_detail_value_'.$item_id.'_'.$item_satuan_id.'_'.$j.'" name="identitas_'.$j.'['.$j.'][expire_date]" value="'.date('d M Y', strtotime($group['expire_date'])).'" readonly="readonly"> <span class="input-group-btn"> <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button> </span> </div> </td>';

                    $type .= '<td>
                        <input type="number" class="form-control text-right jumlah_item" id="identitas_jumlah_'.$j.'" name="identitas_'.$j.'['.$j.'][jumlah]" min="0" data-row="'.$j.'" value="'.$group['jumlah_diterima'].'">
                        <input type="hidden" class="form-control" id="identitas_draft_'.$j.'" name="identitas_'.$j.'['.$j.'][draft_actual_id]" data-row="'.$j.'" value="'.$group['draft_pmb_actual_id'].'">
                        <input type="number" class="form-control hidden text-right jumlah_per_satuan" id="identitas_jumlah_per_satuan_'.$j.'" name="identitas_'.$j.'['.$j.'][jumlah_per_satuan]" min="0"  data-row="'.$j.'" value="0">
                        <input type="number" class="form-control hidden text-right send-input" id="identitas_numrow_'.$j.'" name="identitas_'.$j.'['.$j.'][numrow]" min="0"  data-row="'.$j.'" value="'.$j.'">
                      </td>';
                    $type .= '<td><select name="identitas_'.$j.'['.$j.'][satuan]" id="items_satuan_'.$j.'" data-row="'.$j.'" class="form-control satuan">';
                    $type .= $option;
                                // form_dropdown('identitas_'.$j.'['.$j.'][satuan]', $satuan_option, "", "id=\"items_satuan_'.$j.'\" data-row=\"'.$j.'\" class=\"form-control satuan\"")
                    $type .= '</select><input type="hidden" name="identitas_'.$j.'['.$j.'][nilai_konversi]" id="items_konversi_'.$j.'" data-row="'.$j.'" class="form-control nilai_konversi" value="1"></td>';
                    $type .= '<td class="text-center inline-button-table">
                                <a class="btn red-intense del-this-db" id="identitas_delete_'.$j.'" data-row="'.$j.'" data-row_id ="identitas_row_template'.$j.'" data-id="'.$group['draft_pmb_actual_id'].'" data-confirm="'.translate('Anda yakin akan menghapus item ini?', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                <input type="hidden" class="form-control" id="identitas_is_active_'.$j.'" name="identitas_'.$j.'['.$j.'][is_active]" value="1">
                              </td>';
                    $identitas_row[] =  '<tr id="identitas_row_template'.$j.'" class="table_item">'.$type.'</tr>';
                    $j++;
                    $jml_data++;
                }
            }
            else
            {
                $jml_data =0;
                $identitas_row =array();
            }

            $get_item_identitas = $this->item_identitas_m->data_item_identitas($item_id);
            $item_identitas     = $get_item_identitas->result_array();
            // die_dump($item_identitas);
            
            $satuan_terkecil    = $this->item_satuan_m->get_satuan_terkecil($item_id)->result_array();
            $data_konversi      = $this->item_satuan_m->get_data_konversi($item_id, $satuan_terkecil[0]['id']);
            
            // die_dump($data_konversi);
            $satuan_option      = array();
            $i = 0;
            
            $option = '';
            foreach ($data_konversi as $data) 
            { 
                $option .= '<option value="'.$data['id'].'" data-konversi="'.$data['nilai_konversi'].'">'.$data['nama'].'</option>'; 
            }

            $type = '';
            $i    = 1;
            // $identitas_row_template = '';
            $type .= '<td class="text-center no_urut hidden" id="no">1</td>';
            $type .= '<td>
                        <input type="text" class="form-control send-input" id="identitas_detail_value_'.$item_id.'_'.$item_satuan_id.'_{0}" name="identitas_{0}[{0}][bn_sn_lot]" placeholder="Batch Number">
                        <input type="hidden" class="form-control send-id" id="identitas_detail_id_'.$item_id.'_'.$item_satuan_id.'_{0}" name="identitas_{0}[{0}][draft_identitas_id]" value="">
                    </td>';  
            $type .= '<td> <div class="input-group date"> <input type="text" class="form-control send-input" id="identitas_detail_value_'.$item_id.'_'.$item_satuan_id.'_{0}" name="identitas_{0}[{0}][expire_date]" value="'.date('d M Y').'" readonly="readonly"> <span class="input-group-btn"> <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button> </span> </div> </td>';
            
            $type .= '<td>
                        <input type="number" class="form-control text-right jumlah_item" id="identitas_jumlah_{0}" name="identitas_{0}[{0}][jumlah]" min="0"  data-row="{0}" value="0">
                        <input type="hidden" class="form-control" id="identitas_draft_{0}" name="identitas_{0}[{0}][draft_actual_id]" data-row="{0}" value="">
                        <input type="number" class="form-control hidden text-right jumlah_per_satuan" id="identitas_jumlah_per_satuan_{0}" name="identitas_{0}[{0}][jumlah_per_satuan]" min="0"  data-row="{0}" value="0">
                        <input type="number" class="form-control hidden text-right send-input" id="identitas_numrow_{0}" name="identitas_{0}[{0}][numrow]" min="0"  data-row="{0}" value="{0}">
                      </td>';
            $type .= '<td><select name="identitas_{0}[{0}][satuan]" id="items_satuan_{0}" data-row="{0}" class="form-control satuan">';
            $type .= $option;
                        // form_dropdown('identitas_{0}[{0}][satuan]', $satuan_option, "", "id=\"items_satuan_{0}\" data-row=\"{0}\" class=\"form-control satuan\"")
            $type .= '</select><input type="hidden" name="identitas_{0}[{0}][nilai_konversi]" id="items_konversi_{0}" data-row="{0}" class="form-control nilai_konversi" value="1"></td>';

            $type .= '<td class="text-center inline-button-table">
                        <a class="btn red-intense del-this" id="identitas_delete_{0}" data-row="{0}"><i class="fa fa-times"></i></a>
                        <input type="hidden" class="form-control" id="identitas_is_active_{0}" name="identitas_{0}[{0}][is_active]" value="1">
                        
                      </td>';

            $identitas_row_template =  '<tr id="identitas_row_{0}" class="table_item">'.$type.'</tr>';

        ?>
        
        <span id="tpl_identitas" class="hidden"><?=htmlentities($identitas_row_template)?></span>
        <table class="table table-striped table-hover" id="table_identitas">
            <thead>
                <tr>
                    <th class="text-center hidden" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>

                    <th class="text-center" style="width : 10% !important;"><?=translate("Batch Number", $this->session->userdata("language"))?></th>
                    <th class="text-center" style="width : 10% !important;"><?=translate("Expire Date", $this->session->userdata("language"))?></th>
                    
                    <th class="text-center" style="width : 14% !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                    <th class="text-center" style="width : 14% !important;"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
                </tr>
            </thead>
            <tbody>

                <?php 
                if($identitas_row){
                    foreach ($identitas_row as $row){
                        echo $row;
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="actions">
            <a id="tambah_identitas" class="btn green" style="padding:5px 12px;">
                <i class="fa fa-plus"></i> <?=translate("Tambah", $this->session->userdata("language"))?>
            </a>
        </div>
    </div>
    <input type="hidden" id="jml_row" name="jml_row" value="<?=$jml_data?>">
</div>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary hidden" id="btnOK">OK</button>
    <a class="btn default" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary save_ajax"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
<script type="text/javascript">
    
    $(document).ready(function(){
        addIdentitasRow();
        hitungSatuanHasilPenggabungan();
        handleDatePickers();
        handleBtnDelete();
        tambahIdentitasRow();
        handleSaveDetailDraft();
        handleBtnSaveAjax($('a.save_ajax'));
        handleBtnDeleteDb($('a.del-this-db'));
        total_identitas();

        var identitasCounter = parseInt($('input#jml_row').val());

        // getIdentitasBefore();
    });

    function total_identitas(){
        row_id          = $('input#row_id_modal_identitas').val();
        $row            = $('tr#'+row_id, $('#table_pembelian_detail'));
        
        total_identitas = $('input[name$="[jumlah_masuk]"]', $row).val();
        satuan_nama     = $('label[name$="[satuan_jumlah_masuk]"]', $row).text();

        $('label#total_identitas_modal').text(total_identitas);
        $('label#satuan_item').text(satuan_nama);

        
        // $('input[name$="[jumlah_persatuan]"]').val($('input[name$="[jumlah_per_satuan]"]', $row).val());
    }

    function getIdentitasBefore()
    {
        row_id    = $('input#row_id_modal_identitas').val();
        $row      = $('tr#'+row_id, $('#table_pembelian_detail'));
        $countRow = $('tr', $('#simpan_identitas', $row));

        $('table#table_identitas > tbody').html($('div#simpan_identitas', $row).html());
        $('table#table_identitas input[name$="[jumlah]"]').addClass('jumlah_item');
    };

    function addIdentitasRow()
    {
        row_id = $('input#row_id_modal_identitas').val();
        $row   = $('tr#'+row_id, $('#table_pembelian_detail'));
        
        if (isNaN(identitasCounter)) {
            identitasCounter = parseInt($('input#jml_row').val());
        };   


        var tplIdentitas     = $.validator.format( $('#tpl_identitas').text()),
        $tableIdentitas  = $('#table_identitas');


        var numRow = $('tbody tr', $tableIdentitas).length;

        console.log('numrow' + numRow);


        var 
            $rowContainer  = $('tbody', $tableIdentitas),
            $newItemRow    = $(tplIdentitas(identitasCounter++)).appendTo( $rowContainer ),
            $btnSearchItem = $('.search-item', $newItemRow),
            $noUrut        = $('td.no_urut', $newItemRow);
            $inputJumlah   = $('input.jumlah_item', $newItemRow);

        // $('select.select-indentitas', $newItemRow).val('');
        hitungSatuanHasilPenggabungan();

        if (typeof($('input[name$="[jumlah_per_satuan_awal]"]', $row).val()) != "undefined") {
            $('input.jumlah_per_satuan', $newItemRow).val($('input[name$="[jumlah_per_satuan_awal]"]', $row).val());
        }else{
            $('input.jumlah_per_satuan', $newItemRow).val(1);
        }



        $noUrut.text(identitasCounter-1);
        $('input#jml_row').attr('value', identitasCounter);
        handleDatePickers();

        $('input#identitasCounter').val(identitasCounter);
        handleBtnDeleteRow( $('.del-this', $newItemRow) );
        
        handleInputChange( $('.send-input', $newItemRow),  $('.save_ajax', $newItemRow));
        handleSelectSatuan( $('.satuan', $newItemRow),  $('.nilai_konversi', $newItemRow));
    };

    function tambahIdentitasRow()
    {
        $('a#tambah_identitas').on('click', function(){
            addIdentitasRow();
        });
    }

    function hitungSatuanHasilPenggabungan(){
        row_id = $('input#row_id_modal_identitas').val();
        $row   = $('tr#'+row_id, $('#table_pembelian_detail'));

        if (typeof($('input[name$="[jumlah_per_satuan_awal]"]', $row).val()) != "undefined") {
            $('input[name$="[jumlah]"]').on('change keyup', function(){
                thisRow = $(this).data('row');
                jumlah_per_satuan = parseFloat($('input[name$="[jumlah_per_satuan_awal]"]', $row).val());
                jumlah = parseFloat($(this).val());
                total = jumlah_per_satuan * jumlah;

                // alert(jumlah_per_satuan);
                $('input#identitas_jumlah_per_satuan_'+ thisRow).val(total);
                $('input#identitas_jumlah_per_satuan_'+ thisRow).attr('value', total);
            });
        }else{
            $('input[name$="[jumlah]"]').on('change keyup', function(){
                thisRow = $(this).data('row');
                jumlah = parseFloat($(this).val());

                // alert(jumlah_per_satuan);
                $('input#identitas_jumlah_per_satuan_'+ thisRow).val(jumlah);
                $('input#identitas_jumlah_per_satuan_'+ thisRow).attr('value', jumlah);
            });
        }        
    }

    function handleDatePickers(){


        if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
                // autoclose: true
            }).on('changeDate', function(ev){
                $('.datepicker-dropdown').hide();
            });
            // $('body').removeClass("modal-open");
            // $('.date').on('click', function(){
            //     if ($('#popup_modal_identitas').is(":visible") && $('body').hasClass("modal-open") == false) {
            //         $('body').addClass("modal-open");
            //     }
            // });
        }
    }

    function handleBtnDeleteRow($btn){
        var numRow = $('tbody tr', $('table#table_identitas')).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $('table#table_identitas'));

        $btn.on('click', function(e){
            
            $row.remove();
            if($('tbody>tr', $('table#table_identitas')).length == 0){
                addIdentitasRow();
            }
            
            e.preventDefault();
        });
    };

    function handleBtnDeleteDb($btn){


        $btn.on('click', function(e){
            var 
                rowId    = $(this).data('row_id'),
                $row     = $('#'+rowId, $('table#table_identitas')),
                msg      = $(this).data('confirm');

            // alert(rowId);
            
            bootbox.confirm(msg, function(results){
                if(results == true){
                    $('input[name$="[is_active]"]', $row).attr('value',0);
                    $('input[name$="[jumlah]"]', $row).attr('value',0);
                    hitungSatuanHasilPenggabungan();
                    $row.hide();
                }
            })
            
            
            e.preventDefault();
        });
    };

    function handleBtnDelete(){
        $('a.del-this').on('click', function(e){
            var rowId = $(this).data('row');

            var $row     = $('#identitas_row_'+rowId, $('table#table_identitas'));    
            
                $row.remove();
                if($('tbody>tr', $('table#table_identitas')).length == 0){
                    addIdentitasRow();
                }

            e.preventDefault();
        });
    };

    function handleSaveDetailDraft(){
        $('a#save_draft').on('click', function(e){
           
           $('a.save_ajax').click();

           location.href = baseAppUrl+'proses/'+$('input#draft_id').val()+'/'+$('input#supplier_id').val()+'/'+$('input#gudang_id').val();
        });
    };

    function handleBtnSaveAjax($btn)
    {
 
        $btn.on('click', function(){

            row_id = $('input#row_id_modal_identitas').val();
            $row_tabel = $('tr#'+row_id);

            $('input#row_id').val($row);
            $form_identitas = $('#form_add_barang_datang_farmasi');

            var jml_pesan = parseFloat($('label[name$="[jumlah_belum_diterima]"]', $row_tabel).text());
            $('input#jumlah_pesan').val(jml_pesan);
            $('input#jumlah_pesan').attr('value', jml_pesan);

            // alert(jml_pesan);

            $.ajax({
                type     : 'POST',
                url      : mb.baseUrl() + 'gudang/barang_datang_farmasi/save_draft_detail',
                data     : $form_identitas.serialize(),
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    if(results.jml_item <= jml_pesan){
                       $('input[name$="[jumlah_masuk]"]', $row_tabel).val(results.jml_item);
                        $('button#closeModal').click(); 
                    }else{
                        bootbox.alert('Jumlah barang yang diinput melebihi jumlah pembelian');
                    }
                    
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });

        })
    }

    function handleInputChange($input, $btn)
    {
        $input.on('change', function(){
            $btn.attr('disabled', false);
        })
    }

    function handleSelectSatuan($select, $input)
    {
        $select.on('change', function(){
            $konversi    = $('option:selected', this).attr('data-konversi');

            $input.val($konversi);
        })
    }
</script>