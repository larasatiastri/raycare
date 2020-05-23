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
				<input class="form-control" type="hidden" id="tipe" value="<?=$tipe?>"></input>
				<input class="form-control" type="hidden" id="index" value="<?=$index?>"></input>
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
	baseAppUrl = mb.baseUrl() + 'master/pasien/';
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
			var tipe = $('input#tipe').val(),
				index = $('input#index').val();

			if(tipe == 'pasien')
			{
				$('div#div_lokasi').removeClass('hidden');
				$('input#input_kelurahan_'+index).val($(this).data('item').kelurahan);
				$('input#input_kode_'+index).val($(this).data('item').kode);
				$('input#input_kecamatan_'+index).val($(this).data('item').kecamatan);
				$('input#input_kota_'+index).val($(this).data('item').kotkab);
				$('input#input_provinsi_'+index).val($(this).data('item').propinsi);
				$('input#input_negara_'+index).val('Indonesia');
			}
			if(tipe == 'hub_pasien')
			{
				$('div#div_hp_lokasi').removeClass('hidden');
				$('input#input_hp_kelurahan_'+index).val($(this).data('item').kelurahan);
				$('input#input_hp_kode_'+index).val($(this).data('item').kode);
				$('input#input_hp_kecamatan_'+index).val($(this).data('item').kecamatan);
				$('input#input_hp_kota_'+index).val($(this).data('item').kotkab);
				$('input#input_hp_provinsi_'+index).val($(this).data('item').propinsi);
				$('input#input_hp_negara_'+index).val('Indonesia');
			}
			if(tipe == 'pj_pasien')
			{
				$('div#div_pj_lokasi').removeClass('hidden');
				$('input#input_pj_kelurahan_'+index).val($(this).data('item').kelurahan);
				$('input#input_pj_kode_'+index).val($(this).data('item').kode);
				$('input#input_pj_kecamatan_'+index).val($(this).data('item').kecamatan);
				$('input#input_pj_kota_'+index).val($(this).data('item').kotkab);
				$('input#input_pj_provinsi_'+index).val($(this).data('item').propinsi);
				$('input#input_pj_negara_'+index).val('Indonesia');
			}

			$('button#close_modal').click();

		});
	});
}

function handleSearchKelurahan()
{
	
}
</script>