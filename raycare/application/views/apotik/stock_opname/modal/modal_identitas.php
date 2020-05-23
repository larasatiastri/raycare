
 
  
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
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Racik Obat", $this->session->userdata("language"))?></span>
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
                <input type="hidden" id="item_id" value="<?=$item_id?>">
                <input type="hidden" id="row_id" value="<?=$row_id?>">
                <input type="hidden" id="identitasCounter" value="1">

                <?php
                    $item_identitas='';
                    $identitas_row_template='';
                    $check;
                    $get_group_indetitas = $this->inventory_m->get_by(array('item_id' => $item_id, 'item_satuan_id' => $item_satuan_id));

                    // die_dump($this->db->last_query());
                    $group_indetitas = object_to_array($get_group_indetitas);
                    // die_dump($group_indetitas);
                    $total = 1;
                    foreach ($group_indetitas as $group) {

                        $cek_identitas = $this->inventory_m->cek_identitas($group['inventory_id'])->result_array();

                        // die_dump($cek_identitas);
                        if (!empty($cek_identitas)) {
                            // $get_item_identitas = $this->inventory_m->get_item_identitas($group['inventory_id']);

                            // $item_identitas = $get_item_identitas->result_array();

                            $get_item_identitas = $this->inventory_identitas_m->get_by(array('inventory_id' => $group['inventory_id']));

                            $item_identitas = object_to_array($get_item_identitas);


                            // die_dump($group_indetitas);


                            $i = $total;
                            foreach ($item_identitas as $data_item_identitas) {

                                $get_item_identitas_detail = $this->inventory_m->get_item_identitas($data_item_identitas['inventory_identitas_id']);
                                $item_identitas_detail = $get_item_identitas_detail->result_array();
                                // die_dump($item_identitas_detail);
                                $count = count($item_identitas);

                                if ($i >= $count) {
                                    $total = $i + 1;
                                }
                                $type = '';

                                $type .= '<td class="text-center no_urut" id="no">'.$i.'</td>';

                                $z = 1;
                                foreach ($item_identitas_detail as $data) {
                                    if ($data['inventory_identitas_id'] != NULL) {
                                        $type .= '<td>
                                                    <label class="control-label">'.$data['value'].'</label>
                                                    <input type="hidden" id="identitas_id_'.$data['identitas_id'].'_'.$i.'" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][id]" value="'.$data['identitas_id'].'">
                                                    <input type="hidden" id="identitas_judul_'.$data['identitas_id'].'_'.$i.'" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][judul]" value="'.$data['judul'].'">
                                                    <input type="hidden" id="identitas_value_'.$data['identitas_id'].'_'.$i.'" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][value]" value="'.$data['value'].'">
                                                  </td>';  
                                    }
                                $z++;
                                    
                                }
                                $type .= '<td class="text-center">
                                        <label class="control-label">'.$data['jumlah'].'</label>
                                        <input type="number" class="form-control text-right stock_item hidden" id="identitas_stock_'.$i.'" name="identitas_'.$item_id.'['.$i.'][stock]" min="1" value="'.$data['jumlah'].'">
                                        <input type="number" class="hidden" id="identitas_harga_beli_'.$i.'" name="identitas_'.$item_id.'['.$i.'][harga_beli]" value="'.$data['harga_beli'].'">
                                        <input type="hidden" id="identitas_gudang_id_'.$data['identitas_id'].'_'.$i.'" name="identitas_'.$item_id.'['.$i.'][gudang_id]" value="'.$group['gudang_id'].'">
                                        <input type="hidden" id="identitas_pmb_id_'.$data['identitas_id'].'_'.$i.'" name="identitas_'.$item_id.'['.$i.'][pmb_id]" min="1" value="'.$group['pmb_id'].'">
                                        <input type="hidden" id="identitas_inventory_id_'.$data['identitas_id'].'_'.$i.'" name="identitas_'.$item_id.'['.$i.'][inventory_id]" value="'.$group['inventory_id'].'">
                                        <input type="number" class="form-control text-right hidden" id="identitas_inventory_identitas_id_'.$i.'" name="identitas_'.$item_id.'['.$i.'][inventory_identitas_id]" min="1" value="'.$data['inventory_identitas_id'].'">

                                        
                                      </td>';
                                    $type .= '<td>
                                                <input type="number" class="form-control text-right jumlah_item" id="identitas_jumlah_'.$i.'" name="identitas_'.$item_id.'['.$i.'][jumlah]" min="0"  data-row="'.$i.'" value="0">
                                              </td>';


                                    
                                    $identitas_row_template .=  '<tr id="identitas_row_'.$i.'" class="table_item">'.$type.'</tr>';
                                    
                            $i++;                      
                            }

                            // die_dump($data);
                            
                        }
                    }

                ?>

                <span id="tpl_identitas" class="hidden"><?=htmlentities($identitas_row_template)?></span>

                <table class="table table-striped table-bordered table-hover" id="table_identitas">
                    <thead>
                        <tr class="heading">
                            <th class="text-center" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
                            <?php
                                $widthCell = '';

                                if (!empty($item_identitas)) {
                                    foreach ($item_identitas_detail as $data) {
                                        if ($data['identitas_id'] != NULL) {
                                            echo '<th class="text-center" style="width : 16% !important;">'.translate($data['judul'], $this->session->userdata("language")).'</th>';
                                            # code...
                                        }
                                    }
                                }
                                
                            ?>
                            <th class="text-center" style="width : 5% !important;"><?=translate("Stock", $this->session->userdata("language"))?></th>
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
        <button type="button" id="closeModal" class="btn default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnOK" onClick="javascript:modalOK();">OK</button>
    </div>


    </form>
 
    <script type="text/javascript">

        $(document).ready(function(){
            initForm();
           
            tambahIdentitasRow();
            // selectIndentitas();
            baseAppUrl = mb.baseUrl()+'apotik/stock_opname/'

            // modalOK();
        });

        
        var identitasCounter = 1;


        function initForm()
        {
            
            addIdentitasRow();

        };  

        function addIdentitasRow()
        {
            
            if (isNaN(identitasCounter)) {
                identitasCounter = 0;
            };   
            var tplIdentitas     = $.validator.format( $('#tpl_identitas').text()),
            $tableIdentitas  = $('#table_identitas');


            var numRow = $('tbody tr', $tableIdentitas).length;

            console.log('numrow' + numRow);

            // if (numRow > 0 && ! isValidLastRow()) return;

            var 
                $rowContainer  = $('tbody', $tableIdentitas),
                $newItemRow    = $(tplIdentitas(identitasCounter++)).appendTo( $rowContainer ),
                $btnSearchItem = $('.search-item', $newItemRow),
                $noUrut        = $('td.no_urut', $newItemRow);
                $inputJumlah   = $('input.jumlah_item', $newItemRow);

            // $('select.select-indentitas', $newItemRow).val('');

            // $noUrut.text(identitasCounter);
            console.log($newItemRow);

            selectIndentitas();
            setJumlah($inputJumlah);
            // handle delete btn
            // handleBtnDelete( $('.del-this', $newItemRow) );

            // // handle button search item
            // handleBtnSearchItem($btnSearchItem);

        };

        function tambahIdentitasRow()
        {
            $('a#tambah_identitas').on('click', function(){
                addIdentitasRow();
            });
        }

        function modalOK()
        {
            var total_jumlah = 0;
            $.each($('input.jumlah_item', $('#table_identitas')), function(idx, value){
                total_jumlah = parseInt(total_jumlah) + parseInt(this.value);
                $(this).attr('value', $(this).val());
            });

            // alert(total_jumlah);

            row_id = $('input#row_id').val();
            $row        = $('tr#'+row_id, $('#table_input_stock_opname'));

            // alert(row_id);
            $('input[name$="[item_jumlah]"]', $row).val(total_jumlah);
            $('input[name$="[item_jumlah]"]', $row).attr('value', total_jumlah);
            $('label[name$="[item_jumlah]"]', $row).text(total_jumlah);
             $('input[name$="[jumlah_input]"]', $row).val(total_jumlah);


            var jumlah = parseInt($('input[name$="[item_jumlah]"]', $row).val()),
                harga = parseInt($('input[name$="[item_harga]"]', $row).val());

            $('input[name$="[item_sub_harga]"]', $row).val(jumlah*harga);
            $('label[name$="[item_sub_harga]"]', $row).text(mb.formatRp(jumlah*harga));

            setHarga();

            $('div#simpan_identitas', $row).html($('table#table_identitas > tbody').html());
            // $('div#simpan_identitas', $row).html($('table#table_identitas').html());

            $('div.simpan_identitas input').removeClass('jumlah_item')
            $('#closeModal').click();
        }

        function selectIndentitas()
        {
            $('select.select-indentitas').on('change', function(){

                rowId = $(this).data('row');

                alert('input#jumlah_'+rowId);

                $('select.select-indentitas').val($(this).val());

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_jumlah_identitas',
                    data     : {inventory_id:$(this).val()},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                       $.each(results, function(key, value) {

                            $('input#jumlah_'+rowId).val(value.jumlah);

                        });
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });

            }); 
        }

        function setHarga(){
            $classrow   = $('.table_item', $('#table_input_stock_opname'));
            hargaAll    = $('input[name$="[item_sub_harga]"]', $classrow);

            harga = 0;
            $.each(hargaAll, function(idx, value){
                // alert(itemId);
                if ($(this).val() != "") {
                    harga = harga + parseInt(this.value);
                    // alert(harga);
                };
            });
            // alert(harga);
            $('label#sub_total').text(mb.formatRp(harga));
            $('input#sub_total').val(harga);

            var biaya_tambahan = parseInt($('input#biaya_tambahan').val());

            $('label#harga_jual').text(mb.formatRp(harga + biaya_tambahan));
            $('input#harga_jual').val(harga + biaya_tambahan);
        };

        function setJumlah($btn){
            
            $btn.on('change', function(){
                // rowId    = $btn.closest('tr').prop('id'),
                // $row     = $('#'+rowId, $('table_identitas'));

                var rowId = $(this).data('row');

                var jumlah = parseInt($(this).val()),
                    stock = parseInt($('input#identitas_stock_'+rowId, $('table#table_identitas')).val());
                if (jumlah > stock) {
                    // alert('stock kurang');s
                    $(this).val($('input#identitas_stock_'+rowId, $('table#table_identitas')).val());
                    $('input#identitas_jumlah_'+rowId, $('table#table_identitas')).val($(this).val());
                    $('input#identitas_jumlah_'+rowId, $('table#table_identitas')).attr('value', $(this).val());
                }else{
                    $('input#identitas_jumlah_'+rowId, $('table#table_identitas')).val($(this).val());
                    $('input#identitas_jumlah_'+rowId, $('table#table_identitas')).attr('value', $(this).val());
                }
            });
        }
    </script>
