<?php
    $judul = "Detail Invoice";
    $namashift = '';
    $tipe_bayar = '';
    if($shift != 0) $namashift = ' Shift '.$shift;

    if($tipe == 1 && $penjamin_id == 0) $judul .= " Keseluruhan";
    if($tipe == 1 && $penjamin_id == 1) $judul .= " Swasta Keseluruhan";
    if($tipe == 1 && $penjamin_id == 2) $judul .= " BPJS Keseluruhan";
    if($tipe == 2 && $penjamin_id == 0) {$judul .= " Keseluruhan Dibayar Mesin EDC"; $tipe_bayar = 2;}
    if($tipe == 2 && $penjamin_id == 1) {$judul .= " Swasta Dibayar Mesin EDC"; $tipe_bayar = 2;}
    if($tipe == 2 && $penjamin_id == 2) {$judul .= " BPJS Dibayar Mesin EDC"; $tipe_bayar = 2;}
    if($tipe == 3 && $penjamin_id == 0) {$judul .= " Keseluruhan Dibayar Tunai"; $tipe_bayar = 1;}
    if($tipe == 3 && $penjamin_id == 1) {$judul .= " Swasta Dibayar Tunai"; $tipe_bayar = 1;}
    if($tipe == 3 && $penjamin_id == 2) {$judul .= " BPJS Dibayar Tunai"; $tipe_bayar = 1;}
    if($tipe == 4) $judul .= " Belum Dibayar";
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title uppercase"><?=translate($judul, $this->session->userdata("language")).$namashift?></h4>
</div>
<div class="modal-body">
<input type="hidden" id="tipe" name="tipe" value="<?=$tipe?>">
<input type="hidden" id="tipe_bayar" name="tipe_bayar" value="<?=$tipe_bayar?>">
<input type="hidden" id="tanggal" name="tanggal" value="<?=$tanggal?>">
<input type="hidden" id="penjamin_id" name="penjamin_id" value="<?=$penjamin_id?>">
<input type="hidden" id="shift" name="shift" value="<?=$shift?>">
    <table class="table table-striped table-bordered table-hover table-condensed" id="table_invoice">
        <thead>
            <tr>
                <th class="text-center" width="22%"><?=translate("No.Invoice", $this->session->userdata("language"))?></th>
                <th class="text-center" width="22%"><?=translate("Jenis", $this->session->userdata("language"))?></th>
                <th class="text-center" width="22%"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
                <th class="text-center" width="22%"><?=translate("Pasien", $this->session->userdata("language"))?></th>
                <th class="text-center" width="28%"><?=translate("Total", $this->session->userdata("language"))?></th>
                
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Grand Total</th>
                <th id="total" class="text-right"></th> 
            </tr>
        </tfoot>
    </table>
</div>
<div class="modal-footer">
    <a id="closeModal" class="btn default" data-dismiss="modal">Tutup</a>
</div>


<script type="text/javascript">

    $(document).ready(function(){
        baseAppUrl = mb.baseUrl() +'keuangan/laporan_omzet_invoice/';
        handleDataTable();

        var tipe = $('input#tipe').val();
        var tipe_bayar = $('input#tipe_bayar').val();
        var date = $('input#tanggal').val();
        var penjamin = $('input#penjamin_id').val();
        var shift = $('input#shift').val();

        if(tipe == 1){
            oTable.api().ajax.url(baseAppUrl + 'listing_invoice' + '/' + date + '/' + shift + '/' + penjamin).load();
        }if(tipe == 4){
            oTable.api().ajax.url(baseAppUrl + 'listing_hutang' + '/' + date + '/' + shift).load();
        }if(tipe == 2 || tipe == 3){
            oTable.api().ajax.url(baseAppUrl + 'listing_bayar' + '/' +tipe_bayar +'/'+ penjamin + '/' + shift +'/' +date).load();
        }
    });

    function handleDataTable() 
    {
        $tableInvoice = $('table#table_invoice');


        oTable = $tableInvoice.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_invoice',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'filter'                : false,
            'paginate'              : false,
            'info'                  : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'pembayaran_detail.no_invoice no_invoice','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'pembayaran_detail.nama_penjamin nama_penjamin','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name':'pembayaran_detail.harga harga','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableInvoice.on('draw.dt', function (){
            var totalInvoice = parseInt($('input#input_total', this).val());

            // alert(totalInvoice);
            if(!isNaN(totalInvoice))
            {
                $('th#total').text(mb.formatRp(totalInvoice));
            }
            else
            {
                $('th#total').text(mb.formatRp(0));
            }

            
        });
    }


</script>