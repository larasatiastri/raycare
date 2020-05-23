<?
    $form_attr = array(
        "id"            => "form_pemisahan_item", 
        "name"          => "form_pemisahan_item", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="#" method="post" id="form_pemisahan_item" class="form-horizontal">
                                       
    <div class="modal-body">
        <div class="portlet light" id="section-gambar">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penggabungan Item", $this->session->userdata("language"))?></span>
                </div>
            </div>
            <div class="portlet-body">
                <input type="hidden" id="item_id_modal" value="<?=$item_id?>">
                <input type="hidden" id="item_satuan_id_modal" value="<?=$item_satuan_id?>">
                <input type="hidden" id="item_satuan_id_modal" value="<?=$item_satuan_id?>">
                <input type="hidden" id="initial_satuan" value="<?=$jumlah?>">
                <input type="hidden" id="row_id_modal" value="<?=$row_id?>">
            </div>
            
            <?php 
                $get_nama_satuan = $this->item_satuan_m->get($item_satuan_id);
                // die_dump($get_item_satuan);
                $nama_satuan = $get_nama_satuan->nama;
            ?>
            <div class="form-group">
                <label class="control-label col-md-3"><?=$jumlah?>&nbsp;<?=$nama_satuan?> :</label>
                <div class="col-md-3">
                    <input type="number" class="form-control text-right" min="0" id="change_convert" value="0">
                    <input type="hidden" id="item_satuan_nama_modal" value="<?=$nama_satuan?>">
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <?php
                            $get_item_satuan = $this->item_satuan_m->get_by(array('id <' => $item_satuan_id ,'item_id' => $item_id));
                            $item_satuan = object_to_array($get_item_satuan);
                            $item_satuan_option = array('' => "Pilih..");
                            // die_dump($this->db->last_query());
                            foreach ($item_satuan as $satuan) {
                                if ($satuan['id'] != $item_satuan_id) {
                                    $item_satuan_option[$satuan['id']] = $satuan['nama'];
                                }

                            }
                            
                            echo form_dropdown('satuan_convert', $item_satuan_option, '', 'id="satuan_convert" class="form-control"');
                    
                        ?>
                        <span class="input-group-btn">
                            <a id="proses_pemisahan" class="btn btn-primary" onClick="javascript:proses();"><?=translate('Proses', $this->session->userdata('language'))?></a>
                        </span>

                    </div>
                    
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3"><?=translate('Hasil', $this->session->userdata('language'))?> :</label>
                <div class="col-md-4">
                    <label class="control-label" id="result_convert"></label>
                    <input type="hidden" class="form-control" id="result_convert">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3"><?=translate('Sisa', $this->session->userdata('language'))?> :</label>
                <div class="col-md-4">
                    <label class="control-label" id="final_convert"><?=$jumlah?>&nbsp;<?=$nama_satuan?></label>
                    <input class="form-control hidden" id="final_convert">
                    <input class="form-control hidden" id="value_convert">
                    <input class="form-control hidden" id="sisa_convert">
                    <input class="form-control hidden" id="jumlah_convert">
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <a id="closeModalPemisahan" class="btn default" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
        <a type="button" class="btn btn-primary" id="btnOK" onClick="javascript:modalOK();"><?=translate("OK", $this->session->userdata("language"))?></a>
    </div>


    </form>
 
    <script type="text/javascript">

        $(document).ready(function()
        {
            $tablePembelianDetail      = $('#table_pembelian_detail'),
            tplPembelian               = $.validator.format( $('#tpl_pembelian_row').text()),
            pembelianCounter           = $('input#pembelianCounter').val();
            row_id = $('input#row_id_modal').val();
            $row        = $('tr#'+row_id, $('#table_pembelian_detail'));
            baseAppUrl = mb.baseUrl()+'gudang/barang_datang/'
            initForm();
        });

        function initForm()
        {
            // proses();
        };  

        function modalOK()
        {
            if ($('input#sisa_convert').val() >= 0) {
                addPembelianRow();
                $('label#final_convert').focus();
                $('#closeModalPemisahan').click();
            };
            

        }

        function addPembelianRow(){
        
            var numRow = $('tbody tr', $tablePembelianDetail).length;

            console.log('numrow' + numRow);

            // if (numRow > 0 && ! isValidLastRow()) return;


            var 
                $rowContainer      = $('tbody', $tablePembelianDetail),
                $newItemRow        = $(tplPembelian(pembelianCounter++)).insertAfter( $row ),
                $btnSearchItem     = $('.search-item', $newItemRow);

            $('input#pembelianCounter').val(pembelianCounter);

            thisCounter = parseInt(pembelianCounter)-1;
            
           
            handleBtnDeleteRow($('.del-detail-item', $newItemRow) );

            item_id               = $('input[name$="[item_id]"]', $row).val();
            po                    = $('input[name$="[po]"]', $row).val();
            harga_beli            = $('input[name$="[harga_beli]"]', $row).val();
            item_satuan_id        = $('#satuan_convert option:selected').val();
            item_satuan_nama      = $('#satuan_convert option:selected').text();
            item_kode             = $('label[name$="[item_kode]"]', $row).text();
            item_nama             = $('label[name$="[item_nama]"]', $row).text();
            result_convert        = $('input#result_convert').val();
            value_convert         = $('input#value_convert').val();
            result_convert_satuan = $('label#result_convert').text();
            identitas             = $('div.simpan_identitas', $row).html();
            satuan_convert        = $('#satuan_convert option:selected');
            jumlah_convert_per_satuan = $('input#jumlah_convert').val();

            $('label[name$="[item_kode]"]', $newItemRow).text(item_kode);
            $('label[name$="[item_nama]"]', $newItemRow).text(item_nama);
            $('label[name$="[jumlah_belum_diterima]"]', $newItemRow).text(result_convert_satuan);
            
            $('span[name$="[jumlah_pesan]"] > b', $newItemRow).text(result_convert);
            
            $('input[name$="[item_id]"]', $newItemRow).val(item_id);
            $('input[name$="[item_satuan_id]"]', $newItemRow).val(item_satuan_id);
            $('input[name$="[po]"]', $newItemRow).val(po);
            $('input[name$="[harga_beli]"]', $newItemRow).val(harga_beli);
            
            $('td.jumlah_pesan', $newItemRow).html('-');
            $('td.jumlah_terima', $newItemRow).html('-');
            $('td.jumlah_belum_diterima', $newItemRow).html('-');

            //------------Dikembalikan ke kondisi normal-------------------//
            $('div.simpan_identitas', $row).empty();

            $('div.identitas input[name$="[jumlah_masuk]"]', $row).val($('input#final_convert').val());
            $('div.identitas input[name$="[jumlah_masuk]"]', $row).attr('value',$('input#final_convert').val());


            $('div.identitas-kosong', $row).removeClass('hidden');
            $('div.identitas-isi', $row).addClass('hidden');
            //------------Akhir Dikembalikan ke kondisi normal-------------------//

            jumlah_masuk          = $('td.identitas', $row).html();
            $('td.identitas', $newItemRow).html(jumlah_masuk);

            $('div.identitas label[name$="[satuan_jumlah_masuk]"]', $newItemRow).text(satuan_convert.text());

            $('div.identitas input[name$="[jumlah_masuk]"]', $newItemRow).attr('type','text');
            $('div.identitas input[name$="[jumlah_masuk]"]', $newItemRow).attr('readonly',true);
            $('div.identitas input[name$="[jumlah_masuk]"]', $newItemRow).attr('style','background-color:#fff; cursor:default;');

            $('div.identitas input[name$="[jumlah_masuk]"]', $newItemRow).val(result_convert);
            $('div.identitas input[name$="[jumlah_masuk]"]', $newItemRow).attr('value',result_convert);
            $('div.identitas input[name$="[jumlah_masuk]"]', $newItemRow).attr('id', 'jumlah_masuk_'+thisCounter);
            $('div.identitas input[name$="[jumlah_masuk]"]', $newItemRow).attr('name', 'items['+thisCounter+'][jumlah_masuk]');

            $('div.identitas input[name$="[jumlah_masuk_awal]"]', $newItemRow).val(value_convert);
            $('div.identitas input[name$="[jumlah_masuk_awal]"]', $newItemRow).attr('value',value_convert);
            $('div.identitas input[name$="[jumlah_masuk_awal]"]', $newItemRow).attr('id', 'jumlah_masuk_awal_'+thisCounter);
            $('div.identitas input[name$="[jumlah_masuk_awal]"]', $newItemRow).attr('name', 'items['+thisCounter+'][jumlah_masuk_awal]');

            $('div.identitas input[name$="[total_identitas]"]', $newItemRow).attr('id', 'total_identitas'+thisCounter);
            $('div.identitas input[name$="[total_identitas]"]', $newItemRow).attr('name', 'items['+thisCounter+'][total_identitas]');
            $('div.identitas input[name$="[total_identitas]"]', $row).val(0);
            $('div.identitas input[name$="[total_identitas]"]', $row).attr('value', 0);

            $('div.identitas input[name$="[is_identitas]"]', $newItemRow).attr('id', 'is_identitas_'+thisCounter);
            $('div.identitas input[name$="[is_identitas]"]', $newItemRow).attr('name', 'items['+thisCounter+'][is_identitas]');

            $('div.identitas', $newItemRow).append('<input type="number" class="form-control" name="items['+thisCounter+'][jumlah_per_satuan_awal]" value="'+jumlah_convert_per_satuan+'">')
            $('a.identitas', $newItemRow).attr('href', baseAppUrl + 'modal_identitas/' + item_id + '/' + item_satuan_id + '/item_row_' + thisCounter);
            
        };

        function proses(){
            if ($('#satuan_convert option:selected').val() != '') {
                $item_id              = $('input#item_id_modal').val();
                $item_satuan_id       = $('input#item_satuan_id_modal').val();
                $initial_convert      = $('input#initial_satuan').val();
                $change_convert       = $('input#change_convert').val();
                $result_convert       = $('label#result_convert');
                $input_result_convert = $('input#result_convert');
                $final_convert        = $('label#final_convert');
                $input_final_convert  = $('input#final_convert');
                $input_value_convert  = $('input#value_convert');
                $sisa_convert  = $('input#sisa_convert');
                $jumlah_convert  = $('input#jumlah_convert');
                $satuan_convert       = $('#satuan_convert option:selected');
                $nama_satuan          = $('input#item_satuan_nama_modal').val();

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_konversi_item',
                    data     : {item_id: $item_id, item_satuan_id: $item_satuan_id, satuan_convert : $satuan_convert.val(), flag : 'up_convert' },
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        
                        $jumlah = 1;
                        $.each(results, function(key, value) {
                            $jumlah = $jumlah * parseInt(value.jumlah);
                            $jumlahChange = $jumlah * parseInt($change_convert);
                            $hasil = parseInt($initial_convert) - $jumlahChange;
                            $result_convert.text($change_convert +' ' + $satuan_convert.text());
                            $input_result_convert.val($change_convert);
                            $final_convert.text($hasil + ' ' + $nama_satuan);
                            $input_final_convert.val($hasil);
                            $input_value_convert.val($jumlahChange);

                            $sisa_convert.val($hasil);

                            $jumlah_convert.val($jumlah);
                        })

                        $('a.pemisahan_item', $row).attr('href', baseAppUrl + 'modal_pemisahan_item/' + $('input#item_id_modal').val() + '/' + $('input#item_satuan_id_modal').val() + '/' + $hasil + '/' + $('input#row_id_modal').val());

                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
            };
                
        }

        function handleBtnDeleteRow($btn){
            var numRow = $('tbody tr', $('table#table_pembelian_detail')).length;
            var 
                rowId    = $btn.closest('tr').prop('id'),
                $row     = $('#'+rowId, $('table#table_pembelian_detail'));

            $btn.on('click', function(e){
                
                $row.remove();
                if($('tbody>tr', $('table#table_pembelian_detail')).length == 0){
                    addIdentitasRow();
                }
                
                e.preventDefault();
            });
        };
    </script>
