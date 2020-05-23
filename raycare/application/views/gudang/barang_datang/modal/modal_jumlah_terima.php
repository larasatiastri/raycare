<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Jumlah Terima", $this->session->userdata("language"))?></h4>
</div>
<div class="modal-body">
    <input type="hidden" id="po_detail_id_modal" value="<?=$po_detail_id?>">
    <input type="hidden" id="item_id_modal" value="<?=$item_id?>">
    <input type="hidden" id="item_satuan_id_modal" value="<?=$item_satuan_id?>">

    <table class="table table-condensed table-striped table-hover" id="table_jumlah_terima">
        <thead>
            <tr role="row">
                <th><div class="text-center"><?=translate('No.PMB', $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate('Tanggal', $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate('Jumlah Diterima', $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate('BN', $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate('ED', $this->session->userdata('language'))?></div></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary hidden" id="btnOK">OK</button>
    <a class="btn btn-primary" data-dismiss="modal" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>


<script type="text/javascript">

    $(document).ready(function(){
        // arrayIdPembelian = [];
        baseAppUrl = mb.baseUrl()+'pembelian/pembelian_alkes/';
        // $('select#supplier').select2({});
        initForm();

    });



    function initForm(){
        handleDataTableModal();

    }

    function handleDataTableModal(){
        
        
        oTable = $('#table_jumlah_terima').dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'info'                  : false,
            'paging'                : false,
            'ordering'              : false,
            'filter'                : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_jumlah_terima/'+ $('input#po_detail_id_modal').val() + '/' + $('input#item_id_modal').val() + '/' + $('input#item_satuan_id_modal').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [

                { 'name':'pmb.no_pmb no_pmb','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pmb.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pmb_detail.jumlah_diterima jumlah_diterima','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pmb_detail.bn_sn_lot bn_sn_lot','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pmb_detail.expire_date expire_date','visible' : true, 'searchable': true, 'orderable': true },
                ]
        });

    }

</script>