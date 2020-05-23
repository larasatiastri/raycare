<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title uppercase"><?=translate("Daftar Pembelian", $this->session->userdata("language"))?></h4>
</div>
<div class="modal-body">
    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-left col-md-12 bold"><?=translate("Tipe Supplier", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-12">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <div class="radio-inline">
                                        <span class="">
                                            <input type="radio" name="tipe_supplier_modal"  checked value="1" id="ts-dalam-negeri">
                                            <label>Dalam Negeri</label> 
                                        </span>
                                    </div> 

                                </label>
                                <label class="radio-inline">
                                    <div class="radio-inline"  >
                                        <span class="">
                                            <input type="radio" name="tipe_supplier_modal"  value="2" id="ts-luar-negeri">
                                            <label>Luar Negeri</label> 
                                        </span>
                                    </div> 
                                </label>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-left col-md-12 bold"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
                        
                        <div class="col-md-12">
                            <?php
                                $get_supplier = $this->supplier_m->get_by(array('is_active' => '1'));
                                // die_dump($get_gudang);
                                $supplier_option = array();

                                foreach ($get_supplier as $supplier) {
                                    $supplier_option[$supplier->id] = $supplier->nama.'&nbsp;"'.$supplier->kode.'"';
                                }

                                echo form_dropdown('supplier', $supplier_option, '', "id=\"supplier\" class=\"form-control select2me\"");
                            ?>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    <div class="form-body">
    </div>
    
    <table class="table table-striped table-hover table-condensed" id="table_pembelian">
        <thead>
            <tr>
                <th class="text-center" width="22%"><?=translate("No.PO", $this->session->userdata("language"))?></th>
                <th class="text-center" width="22%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?></th>
                <th class="text-center" width="22%"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?></th>
                <th class="text-center" width="28%"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
                <th class="table-checkbox" width="1%" >
                    <div class="text-center">
                        <input type="checkbox" class="group-checkable text-center" data-set="#table_pembelian .checkboxes"/>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary hidden" id="btnOK">OK</button>
    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>


<script type="text/javascript">

    $(document).ready(function(){
        arrayIdPembelian = [];
        baseAppUrl = mb.baseUrl()+'gudang/barang_datang/';
        $('select#supplier').select2({});
        initForm();

        // alert($('select#gudang').val());

    });



    function initForm(){
        handleDataTableModal();
        handlePilihSupplier();
        handleSelectSupplier();
        handleModalOK();

    }

    function handleDataTableModal(){
        
        
        oTable = $('#table_pembelian').dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_daftar_pembelian/1/1',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [

                { 'name':'pembelian.no_pembelian no_pembelian','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pembelian.tanggal_pesan tanggal_pesan','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pembelian.tanggal_kadaluarsa tanggal_kadaluarsa','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pembelian.keterangan keterangan','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },

                ]
        });

        $('#table_pembelian').on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
            $('input[type=checkbox]',this).uniform();
        });

        jQuery('#table_pembelian .group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                } else {
                    $(this).attr("checked", false);
                }                    
            });
            jQuery.uniform.update(set);
        });
    }

    function handlePilihSupplier(){
        $('input[name="tipe_supplier_modal"]').on('change',function(){
            // alert($('#gudang option:selected').val());
            oTable.api().ajax.url(baseAppUrl + 'listing_daftar_pembelian/' + $(this).val() + '/' + $('#supplier option:selected').val()).load();
        });
    }

    function handleSelectSupplier(){
        $('select#supplier').on('change',function(){
            // alert($('input[name="tipe_supplier"]:checked').val());
            oTable.api().ajax.url(baseAppUrl + 'listing_daftar_pembelian/' + $('input[name="tipe_supplier_modal"]:checked').val() + '/' + $(this).val()).load();
        });
    }

    function handleModalOK(){
        $('a#modal_ok').on('click', function(){
            $.each($('input[name="checkbox_pembelian"]:checked'), function(idx){
                arrayIdPembelian.push($(this).data('id'));
            });

            $(this).attr('disabled', true);
            $(this).text('Sedang Diproses');
            
            var gudang_id = $('select#gudang_id').val();
                supplier_id = $('select#supplier').val();

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'generate_pembelian_id',
                data     : {data_id : arrayIdPembelian, gudang_id : gudang_id, supplier_id : supplier_id},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    // alert(results);

                    location.href = baseAppUrl + 'proses/'+gudang_id+'/'+ $('#supplier option:selected').val() + '/' + results.draft_pmb_id_last;
                    $('#closeModal').click();   
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
                // alert(JSON.stringify(arrayIdPembelian));
        });
    }
</script>