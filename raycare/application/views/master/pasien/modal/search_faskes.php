<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Cari Faskes', $this->session->userdata('language'))?></span>
	</div>
</div>
<div class="modal-body">
	<div class="portlet light">
		<div class="portlet-body form">
			<div class="form-body">
				<div class="form-group">
					<table class="table table-striped table-bordered table-hover" id="table_faskes">
						<thead>
							<tr>
								<th class="text-center"><?=translate("Jenis", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Alamat", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Regional", $this->session->userdata("language"))?></th>
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
	baseAppUrl = mb.baseUrl() + 'master/pasien/';
	$tableFaskes = $('table#table_faskes');
	handleDataTableFaskes();
	
});

function handleDataTableFaskes() {
	oTableFaskes = $tableFaskes.dataTable({
       	'processing'            : true,
		'serverSide'            : true,
		'pagingType'			: 'full_numbers',
		'language'              : mb.DTLanguage(),
		'stateSave'			: true,
		'ajax'              	: {
			'url' : baseAppUrl + 'listing_faskes',
			'type' : 'POST',
		},			
		'pageLength'			: 10,
		'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
		'order'                	: [[1, 'desc']],
		'columns'               : [
			{ 'name' : 'master_faskes.jenis jenis', 'visible' : true, 'searchable': true, 'orderable': true },
			{ 'name' : 'master_faskes.kode_faskes kode_faskes','visible' : true, 'searchable': true, 'orderable': true },
			{ 'name' : 'master_faskes.nama_faskes nama_faskes','visible' : true, 'searchable': true, 'orderable': true },
			{ 'name' : 'master_faskes.alamat alamat','visible' : true, 'searchable': true, 'orderable': true },
			{ 'name' : 'master_faskes.nama_reg nama_reg','visible' : true, 'searchable': true, 'orderable': true },
			{ 'name' : 'master_faskes.jenis jenis','visible' : true, 'searchable': false, 'orderable': false },
    		]
    });
    $tableFaskes.on('draw.dt', function (){
  
		$('.btn', this).tooltip();
		// action for delete locker
		$('a.select_faskes', this).click(function() 
		{
			$('input#id_faskes_1').val($(this).data('item').id);
			$('input#faskes_1').val($(this).data('item').nama_faskes);
			
			$('button#close_modal').click();

		});
	});
}

function handleSearchKelurahan()
{
	
}
</script>