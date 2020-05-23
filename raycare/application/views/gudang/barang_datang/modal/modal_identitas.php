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

                <div class="actions">
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
        
                <div class="note note-info">
                    <h4 class="block">Info</h4>
                    <p>
                        <?=translate("Anda harus memasukan jumlah identitas dengan total", $this->session->userdata("language"))?> : <label id="total_identitas_modal">100</label> <label id="satuan_item">Unit</label>
                    </p>
                </div>
                
                <?php
                    $get_item_identitas = $this->item_identitas_m->data_item_identitas($item_id);
                    $item_identitas = $get_item_identitas->result_array();
                    // die_dump($item_identitas);

                    $type = '';
                    $i = 1;
                    // $identitas_row_template = '';
                    $type .= '<td class="text-center no_urut hidden" id="no">1</td>';
                    
                    foreach ($item_identitas as $data) {

                        if ($data['tipe'] == '1')
                        {
                            $type .= '<td>
                                        <input type="hidden" class="form-control send-id" id="identitas_detail_id_'.$item_id.'_'.$item_satuan_id.'_{0}" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][id]" value="'.$data['identitas_id'].'">
                                        <input type="hidden" class="form-control send-input" id="identitas_detail_judul_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][judul]" placeholder="'.$data['judul'].'" value="'.$data['judul'].'">
                                        <input type="text" class="form-control send-input" id="identitas_detail_value_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][value]" placeholder="'.$data['judul'].'">
                                      </td>';  
                        }elseif ($data['tipe'] == '2')
                        {
                            $type .= '<td>
                                        <input type="hidden" class="form-control send-id" id="identitas_detail_id_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][id]" value="'.$data['identitas_id'].'">
                                        <input type="hidden" class="form-control send-input" id="identitas_detail_judul_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][judul]" placeholder="'.$data['judul'].'" value="'.$data['judul'].'">
                                        <textarea class="form-control send-textarea" id="identitas_detail_value_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][value]" placeholder="'.$data['judul'].'" rows="1"></textarea>
                                      </td>';
                        }elseif ($data['tipe'] == '3')
                        {
                            $type .= '<td>
                                        <input type="hidden" class="form-control send-id" id="identitas_detail_id_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][id]" value="'.$data['identitas_id'].'">
                                        <input type="hidden" class="form-control send-input" id="identitas_detail_judul_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][judul]" placeholder="'.$data['judul'].'" value="'.$data['judul'].'">
                                        <input type="number" class="form-control send-input text-right" id="identitas_detail_value_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][value]" placeholder="'.$data['judul'].'" value="0">
                                      </td>';
                        }elseif ($data['tipe'] == '4')
                        {
                            $type .= '<td>
                                        <input type="hidden" class="form-control send-id" id="identitas_detail_id_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][id]" value="'.$data['identitas_id'].'">
                                        <input type="hidden" class="form-control send-input" id="identitas_detail_judul_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][judul]" placeholder="'.$data['judul'].'" value="'.$data['judul'].'">
                                        <div class="input-group date">
                                            <input type="text" class="form-control send-input" id="identitas_detail_value_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][value]" placeholder="'.$data['judul'].'" readonly="readonly">
                                            <span class="input-group-btn">
                                                <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                      </td>';
                        }
                        else
                        {
                            $type .= '<td>
                                        <input type="hidden" class="form-control send-id" id="identitas_detail_id_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'['.$i.'][id]" value="'.$data['identitas_id'].'">
                                        <input type="hidden" class="form-control send-input" id="identitas_detail_judul_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'_{0}['.$i.'][judul]" placeholder="'.$data['judul'].'" value="'.$data['judul'].'">
                                        <input type="text" class="form-control send-input" id="identitas_detail_value_'.$item_id.'_'.$item_satuan_id.'" name="identitas_detail_'.$item_id.'_'.$item_satuan_id.'['.$i.'][value]" placeholder="'.$data['judul'].'">
                                      </td>';
                        }
                        
                            
                        
                        


                            
                    $i++;        
                    }
                    $type .= '<td>
                                <input type="number" class="form-control text-right jumlah_item" id="identitas_jumlah_{0}" name="identitas_'.$item_id.'_'.$item_satuan_id.'[{0}][jumlah]" min="0"  data-row="{0}" value="0">
                                <input type="number" class="form-control hidden text-right jumlah_per_satuan" id="identitas_jumlah_per_satuan_{0}" name="identitas_'.$item_id.'_'.$item_satuan_id.'[{0}][jumlah_per_satuan]" min="0"  data-row="{0}" value="0">
                                <input type="number" class="form-control hidden text-right send-input" id="identitas_numrow_{0}" name="identitas_'.$item_id.'_'.$item_satuan_id.'[{0}][numrow]" min="0"  data-row="{0}" value="{0}">
                              </td>';

                    $type .= '<td class="text-center">
                                <a class="btn red-intense del-this" id="identitas_delete_{0}" data-row="{0}"><i class="fa fa-times"></i></a>
                              </td>';

                    $identitas_row_template =  '<tr id="identitas_row_{0}" class="table_item">'.$type.'</tr>';
                ?>
                
                <span id="tpl_identitas" class="hidden"><?=htmlentities($identitas_row_template)?></span>

                <table class="table table-striped table-bordered table-hover" id="table_identitas">
                    <thead>
                        <tr class="heading">
                            <th class="text-center hidden" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
                            <?php
                                if (!empty($item_identitas)) {
                                    // die_dump($identitas);
                                    foreach ($item_identitas as $identitas) {
                                        if ($identitas['id'] != NULL) {
                                            echo '<th class="text-center" style="width : 16% !important;">'.translate($identitas['judul'], $this->session->userdata("language")).'</th>';
                                            # code...
                                        }
                                    }
                                }
                            ?>
                            <th class="text-center" style="width : 14% !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                            <th class="text-center" style="width : 1% !important;"><?=translate("Aksi", $this->session->userdata("language"))?></th>
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
