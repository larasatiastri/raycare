<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Jumlah Terima", $this->session->userdata("language"))?></h4>
</div>
<div class="modal-body">
    <input type="hidden" id="supplier_id_modal" value="<?=$supplier_id?>">
    <input type="hidden" id="item_id_modal" value="<?=$item_id?>">
    <input type="hidden" id="item_satuan_id_modal" value="<?=$item_satuan_id?>">

    <table class="table table-condensed table-striped table-hover" id="table_jumlah_terima_supplier_lain">
        <thead>
            <tr role="row" class="">
                <th><div class="text-center"><?=translate('No.PMB', $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate('Tanggal', $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate('Jumlah Diterima', $this->session->userdata('language'))?></div></th>
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
        // arrayIdPembelian = [];
        baseAppUrl = mb.baseUrl()+'gudang/barang_datang/';
        // $('select#supplier').select2({});
        initForm();

    });



    function initForm(){
        handleDataTableModal();

    }

    function handleDataTableModal(){
        
        
        oTable = $('#table_jumlah_terima_supplier_lain').dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'info'                  : false,
            'paging'                : false,
            'ordering'              : false,
            'filter'                : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_jumlah_terima_supplier_lain/'+ $('input#supplier_id_modal').val() + '/' + $('input#item_id_modal').val() + '/' + $('input#item_satuan_id_modal').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [

                { 'name':'pmb.no_pmb no_pmb','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pmb.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pmb_detail.jumlah_diterima jumlah_diterima','visible' : true, 'searchable': true, 'orderable': true },
                ]
        });

    }

</script>