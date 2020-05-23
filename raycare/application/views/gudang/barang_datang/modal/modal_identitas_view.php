<?
    $form_attr = array(
        "id"            => "form_identitas", 
        "name"          => "form_identitas", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="#" method="post" id="form_identitas" class="form-horizontal">
                                       
    <div class="modal-body">
        <div class="portlet light" id="section-gambar">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Identitas Item", $this->session->userdata("language"))?></span>
                </div>

                <div class="actions hidden">
                    <a id="tambah_identitas" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("Tambah", $this->session->userdata("language"))?>
                        </span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <input type="hidden" id="item_id_modal_identitas" value="<?=$item_id?>">
                <input type="hidden" id="item_satuan_id_modal_identitas" value="<?=$item_satuan_id?>">
                <input type="hidden" id="row_id_modal_identitas" value="<?=$row_id?>">
                <input type="hidden" id="identitasCounterModal" value="1">
        
                <div class="note note-info hidden">
                    <h4 class="block">Info</h4>
                    <p>
                        <?=translate("Anda harus memasukan jumlah identitas dengan total", $this->session->userdata("language"))?> : <label id="total_identitas_modal">100</label> <label id="satuan_item">Unit</label>
                    </p>
                </div>
                
                <?php

                    $get_pmb_detail = $this->pmb_detail_m->get_by(array('pmb_id' => $pmb_id, 'item_id' => $item_id, 'item_satuan_id' => $item_satuan_id));
                    $pmb_detail = object_to_array($get_pmb_detail);
                    $identitas_row_template='';

                    foreach ($pmb_detail as $data_pmb_detail) {
                        $get_pmb_identitas = $this->pmb_identitas_m->get_by(array('pmb_detail_id' => $data_pmb_detail['id']));
                        $pmb_identitas = object_to_array($get_pmb_identitas);

                        // die_dump($pmb_identitas);
                        foreach ($pmb_identitas as $data_pmb_identitas) {
                            $get_pmb_identitas_detail = $this->pmb_identitas_detail_m->get_by(array('pmb_identitas_id' => $data_pmb_identitas['id']));
                            $pmb_identitas_detail = object_to_array($get_pmb_identitas_detail);

                            $type = '';
                            $i = 1;
                            // $identitas_row_template = '';
                            $type .= '<td class="text-center no_urut hidden" id="no">1</td>';
                            foreach ($pmb_identitas_detail as $data) {
                                $type .= '<td>
                                            <label class="control-label">'.$data['value'].'</label>
                                        </td>';  
                            }

                            $type .= '<td class="text-center">
                                <label class="control-label">'.$data_pmb_identitas['jumlah'].'</label>
                              </td>';

                            $identitas_row_template .=  '<tr id="identitas_row_{0}" class="table_item">'.$type.'</tr>';
                        }
                    }
                ?>
                
                <span id="tpl_identitas" class="hidden"><?=htmlentities($identitas_row_template)?></span>

                <table class="table table-striped table-hover" id="table_identitas">
                    <thead>
                        <tr class="">
                            <th class="text-center hidden" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
                            <?php
                                if (!empty($pmb_identitas)) {
                                    // die_dump($identitas);
                                    foreach ($pmb_identitas_detail as $identitas) {
                                        if ($identitas['id'] != NULL) {
                                            echo '<th class="text-center" style="width : 16% !important;">'.translate($identitas['judul'], $this->session->userdata("language")).'</th>';
                                            # code...
                                        }
                                    }
                                }
                            ?>
                            <th class="text-center" style="width : 15% !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                        </tr>
                    </thead>
                        
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" id="closeModal" class="btn default" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></button>
        <button type="button" class="btn btn-primary" id="btnOK" onClick="javascript:modalOK();"><?=translate("OK", $this->session->userdata("language"))?></button>
    </div>


    </form>
 
    <script type="text/javascript">

        $(document).ready(function(){
            baseAppUrl = mb.baseUrl()+'gudang/barang_datang/'
            identitasCounter = $('input#identitasCounter').val();

            
            initForm();
            tambahIdentitasRow();
            // selectIndentitas();

            // modalOK();
        });

        function initForm()
        {
            total_identitas();
            getIdentitasBefore();
            addIdentitasRow();
            hitungSatuanHasilPenggabungan();
            handleDatePickers();
            handleBtnDelete();
            // modalOK();
        };  

        function total_identitas(){
            row_id = $('input#row_id_modal_identitas').val();
            $row        = $('tr#'+row_id, $('#table_pembelian_detail'));

            total_identitas = $('input[name$="[jumlah_masuk]"]', $row).val();
            satuan_nama = $('label[name$="[satuan_jumlah_masuk]"]', $row).text();

            $('label#total_identitas_modal').text(total_identitas);
            $('label#satuan_item').text(satuan_nama);

            
            // $('input[name$="[jumlah_persatuan]"]').val($('input[name$="[jumlah_per_satuan]"]', $row).val());
        }

        function getIdentitasBefore()
        {
            row_id = $('input#row_id_modal_identitas').val();
            $row        = $('tr#'+row_id, $('#table_pembelian_detail'));
            $countRow        = $('tr', $('#simpan_identitas', $row));

            $('table#table_identitas > tbody').html($('div#simpan_identitas', $row).html());
            $('table#table_identitas input[name$="[jumlah]"]').addClass('jumlah_item');
        };

        function addIdentitasRow()
        {
            row_id = $('input#row_id_modal_identitas').val();
            $row        = $('tr#'+row_id, $('#table_pembelian_detail'));
            
            if (isNaN(identitasCounter)) {
                identitasCounter = 0;
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
            handleDatePickers();

            $('input#identitasCounter').val(identitasCounter);
            handleBtnDeleteRow( $('.del-this', $newItemRow) );

        };

        function tambahIdentitasRow()
        {
            $('a#tambah_identitas').on('click', function(){
                addIdentitasRow();
            });
        }

        function hitungSatuanHasilPenggabungan(){
            row_id = $('input#row_id_modal_identitas').val();
            $row        = $('tr#'+row_id, $('#table_pembelian_detail'));

            if (typeof($('input[name$="[jumlah_per_satuan_awal]"]', $row).val()) != "undefined") {
                $('input[name$="[jumlah]"]').on('change keyup', function(){
                    thisRow = $(this).data('row');
                    jumlah_per_satuan = parseInt($('input[name$="[jumlah_per_satuan_awal]"]', $row).val());
                    jumlah = parseInt($(this).val());
                    total = jumlah_per_satuan * jumlah;

                    // alert(jumlah_per_satuan);
                    $('input#identitas_jumlah_per_satuan_'+ thisRow).val(total);
                    $('input#identitas_jumlah_per_satuan_'+ thisRow).attr('value', total);
                });
            }else{
                $('input[name$="[jumlah]"]').on('change keyup', function(){
                    thisRow = $(this).data('row');
                    jumlah = parseInt($(this).val());

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
                $('body').removeClass("modal-open");
                $('.date').on('click', function(){
                    if ($('#popup_modal_identitas').is(":visible") && $('body').hasClass("modal-open") == false) {
                        $('body').addClass("modal-open");
                    }
                });
            }
        }

        function modalOK()
        {
            row_id = $('input#row_id_modal_identitas').val();
            $row        = $('tr#'+row_id, $('#table_pembelian_detail'));

            var total_jumlah = 0;
            $.each($('input.jumlah_item', $('#table_identitas')), function(idx, value){
                total_jumlah = parseInt(total_jumlah) + parseInt(this.value);
                $(this).attr('value', $(this).val());
                $('input#total_identitas').val(total_jumlah);
                $('input#total_identitas').attr('value', total_jumlah);
            });

            $.each($('input.send-id', $('#table_identitas')), function(idx, value){
                $(this).attr('value', $(this).val());
            });

            $.each($('input.send-input', $('#table_identitas')), function(idx, value){
                $(this).attr('value', $(this).val());
            });

            $.each($('.send-textarea', $('#table_identitas')), function(idx){
                $(this).text($(this).val());
            });
            

            if (total_jumlah == $('input[name$="[jumlah_masuk]"]', $row).val() && total_jumlah != 0) {

                identitasKosong = $('div.identitas-kosong input[name$="[jumlah_masuk]"]', $row).val();

                $('div.identitas-kosong', $row).addClass('hidden');
                $('div.identitas-isi', $row).removeClass('hidden');
               
                $('div.identitas input[name$="[total_identitas]"]', $row).val(total_jumlah);
                $('div.identitas input[name$="[total_identitas]"]', $row).attr('value', total_jumlah);

                $('div#simpan_identitas', $row).html($('table#table_identitas > tbody').html());

                $('div.simpan_identitas input[name$="[jumlah]"]').removeClass('jumlah_item');
                $('#closeModal').click();
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
    </script>
