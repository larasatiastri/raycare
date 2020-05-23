<form action="#" id="form_tolak_os" class="form-horizontal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <div class="caption">
                <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tolak Permintaan Obat & Alkes ".$data_po['no_pembelian'], $this->session->userdata("language"))?></span>
            </div>
        </div>
        <div class="modal-body">

            <table class="table table-striped table-hover" id="table_tolak_outstanding_po_obat">
                <thead>
                    <tr>
                        <th class="text-left"><?=translate("ID", $this->session->userdata("language"))?> </th>
                        <th class="text-left" ><?=translate("Item", $this->session->userdata("language"))?> </th>
                        <th class="text-left" width="15%"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> </th>
                        <th class="text-left" width="20%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
                        <th class="text-left" width="10%"><?=translate("Jml Minta", $this->session->userdata("language"))?> </th>
                        <th class="text-left" width="30%"><?=translate("Jml Tolak", $this->session->userdata("language"))?> </th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>

            <div class="form-group">
                <label class="col-md-12 bold"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
                <div class="col-md-12">
                    <?php
                        $keterangan = array(
                            "id"        => "keterangan",
                            "name"      => "keterangan",
                            "autofocus" => true,
                            "rows"      => 5,
                            "cols"      => 6,
                            "class"     => "form-control" 
                        );
                        echo form_textarea($keterangan);
                    ?>
                </div>
            </div>
           
            
            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
                <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
                <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
            </div>
        </div>
</form>

<script>
    $( document ).ready(function() {
        handleDataTablePermintaan();
        handleSaveTolak();
    }); 

    function handleDataTablePermintaan() 
    {
        var baseAppUrl = mb.baseUrl() + 'pembelian/outstanding_po_obat/';
        $('#table_tolak_outstanding_po_obat').dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'info'            : true,
            'paginate'            : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_tolak_permintaan/3',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'name' : 'o_s_pmsn.id id', 'searchable': false, 'orderable': false },
                { 'visible' : true, 'name' : 'item.nama nama_item', 'searchable': true, 'orderable': false },
                { 'visible' : true, 'name' : 'user.nama nama_user', 'searchable': false, 'orderable': false },
                { 'visible' : true, 'name' : 'o_s_pmsn.subjek subjek', 'searchable': false, 'orderable': false },
                { 'visible' : true, 'name' : 'o_s_pmsn.jumlah jumlah', 'searchable': true, 'orderable': false },
                { 'visible' : true, 'name' : 'o_s_pmsn.subjek subjek', 'searchable': false, 'orderable': false },
                ]
        });
        

    };


    function handleSaveTolak(){
        $('a#modal_ok').click(function() {

            $form_tolak_os = $('#form_tolak_os');

            $(this).attr('disabled', true);
            $(this).text('Sedang Diproses');

            $.ajax({
                type     : 'POST',
                url      : mb.baseUrl() + 'pembelian/outstanding_po_obat/tolak_os',
                data     : $form_tolak_os.serialize(),
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( data ) {
                    
                    if(data.success == true){
                        mb.showToast('success',data.msg,'Sukses');
                        $('#closeModal').click();              
                        location.href = mb.baseUrl() + 'pembelian/outstanding_po_obat';
                    }if(data.success == false){
                        mb.showToast('error',data.msg,'Error');
                        $('#closeModal').click();              
                        location.href = mb.baseUrl() + 'pembelian/outstanding_po_obat';
                    }
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
        
        });
    }
</script>