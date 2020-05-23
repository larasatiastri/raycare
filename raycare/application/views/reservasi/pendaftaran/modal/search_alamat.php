<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Cari Kelurahan', $this->session->userdata('language'))?></span>
	</div>
</div>
<div class="modal-body">
	<div class="portlet light">
		<div class="portlet-body form">
			<div class="form-body">
				<div class="form-group">
					<table class="table table-striped table-bordered table-hover" id="table_kelurahan">
						<thead>
							<tr>
								<th class="text-center"><?=translate("Kelurahan", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Kecamatan", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Kota/Kab", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Provinsi", $this->session->userdata("language"))?></th>
								<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
							</tr>
						</thead>

						<tbody>
							
						</tbody>
					</table>	
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	baseAppUrl = mb.baseUrl() + 'reservasi/pendaftaran_tindakan/';
	$tableAlamat = $('table#table_kelurahan');
	handleDataTableAlamat();
	
});

function handleDataTableAlamat() {
	oTableAlamat = $tableAlamat.dataTable({
       	'processing'            : true,
		'serverSide'            : true,
		'pagingType'			: 'full_numbers',
		'language'              : mb.DTLanguage(),
		'stateSave'			: true,
		'ajax'              	: {
			'url' : baseAppUrl + 'listing_alamat',
			'type' : 'POST',
		},			
		'pageLength'			: 10,
		'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
		'order'                	: [[0, 'desc']],
		'columns'               : [
			{ 'visible' : true, 'searchable': true, 'orderable': true },
			{ 'visible' : true, 'searchable': true, 'orderable': true },
			{ 'visible' : true, 'searchable': true, 'orderable': true },
			{ 'visible' : true, 'searchable': true, 'orderable': true },
			{ 'visible' : true, 'searchable': false, 'orderable': false },
    		]
    });
    $tableAlamat.on('draw.dt', function (){
  
		$('.btn', this).tooltip();
		// action for delete locker
		$('a.select_alamat', this).click(function() 
		{
			$('input#input_kelurahan').val($(this).data('item').kelurahan + ', '+$(this).data('item').kecamatan+', '+$(this).data('item').kotkab+', '+$(this).data('item').propinsi +' Indonesia');
			$('input#input_kode').val($(this).data('item').kode);

			$('button#close_modal').click();
		});
	});
}

function handleSearchKelurahan()
{
	
}
</script>