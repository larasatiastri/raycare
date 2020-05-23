<?php
	$form_attr = array(
	    "id"            => "form_laporan_hd", 
	    "name"          => "form_laporan_hd", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
</div>
<div class="modal-body">
    <div class="portlet light bordered">
        <div class="portlet-body">
		    <table class="table table-striped table-bordered table-hover table-condensed" id="table_pembelian">
				<thead>
					<tr>
						<th class="text-center" widht="1%"><?=translate("Pilih", $this->session->userdata("language"))?></th>
						<th class="text-center"><?=translate("No. PO", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
						<th class="text-center"><?=translate("Nominal", $this->session->userdata("language"))?></th>
					</tr>
				</thead>

				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
    <a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" ><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
<?=form_close()?>
<script type="text/javascript">

$(document).ready(function(){
    baseAppUrl = mb.baseUrl()+'keuangan/pengajuan_pembayaran_kasbon/';
    handleDataTablePembelian();

});

function handleDataTablePembelian() 
{
	oTable = $tablePembelian.dataTable({
       	'processing'            : true,
		'serverSide'            : true,
        'stateSave'             : true,
        'pagingType'            : 'full_numbers',
		'language'              : mb.DTLanguage(),
		'ajax'              	: {
			'url' : baseAppUrl + 'listing_pembelian',
			'type' : 'POST',
		},			
        
		'pageLength'			: 10,
		'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
		'order'                	: [[0, 'desc']],
		'columns'               : [
			{ 'visible' : true, 'searchable': false, 'orderable': false },
			{ 'visible' : true, 'searchable': true, 'orderable': false },
			{ 'visible' : true, 'searchable': true, 'orderable': false },
			{ 'visible' : true, 'searchable': true, 'orderable': false },
			{ 'visible' : true, 'searchable': true, 'orderable': false },
    		]
    });
}
</script>
