<?php

    // die_dump($id_draft);
    $get_supplier = $this->supplier_m->get($supplier_id);
    // die_dump($get_supplier);
    $tipe = '';
    if($get_supplier->tipe == 1)
    {
        $tipe = 'Dalam Negeri';
    }
    else if($get_supplier->tipe == 2)
    {
        $tipe = 'Luar Negeri';
    }
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Draft Daftar Pembelian", $this->session->userdata("language"))?> :</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        <input type="hidden" id="id_draft" value="<?=$id_draft?>">
        <input type="hidden" id="supplier_id" value="<?=$supplier_id?>">
        <input type="hidden" id="gudang_id" value="<?=$gudang_id?>">
        <div class="row" style="margin-top:10px;margin-bottom:10px;">
            <label class="control-label text-right col-md-3"><?=translate("Tipe Supplier", $this->session->userdata("language"))?> :</label>
            <label class="control-label text-left col-md-3"><?=$tipe?></label>
        </div>
        <div class="row" style="margin-top:10px;margin-bottom:30px;">
            <label class="control-label text-right col-md-3"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
            <label class="control-label text-left col-md-3"><?=$get_supplier->nama.'&nbsp;"'.$get_supplier->kode.'"'?></label>
        </div>
    </div>
    
    <table class="table table-striped table-hover table-condensed" id="table_pembelian">
        <thead>
            <tr class="">
                <th class="text-center" width="22%"><?=translate("No.PO", $this->session->userdata("language"))?></th>
                <th class="text-center" width="22%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?></th>
                <th class="text-center" width="22%"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?></th>
                <th class="text-center" width="28%"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
                <th class="table-checkbox" width="1%" >
                    <div class="text-center">
                        <input type="checkbox" disabled class="group-checkable text-center" data-set="#table_pembelian .checkboxes"/>
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
        baseAppUrl = mb.baseUrl()+'gudang/barang_datang_farmasi/';
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
                'url' : baseAppUrl + 'listing_daftar_pembelian_draft/'+$('input#id_draft').val()+'/'+$('input#supplier_id').val()+'/'+$('input#gudang_id').val(),
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

            var gudang_id = $('input#gudang_id').val();
                supplier_id = $('input#supplier_id').val();
                draft_id = $('input#id_draft').val();

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'generate_pembelian_id_draft',
                data     : {data_id : arrayIdPembelian, gudang_id : gudang_id, supplier_id : supplier_id, draft_id : draft_id},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    // alert(results);

                    location.href = baseAppUrl + 'proses_tambah_po/' + results  + '/' + gudang_id + '/' + supplier_id;
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