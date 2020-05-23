<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Pilih User Level', $this->session->userdata('language'))?></span>
	</div>
</div>
<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			<table class="table table-striped table-bordered table-hover" id="table_user_level">
				<thead>
					<tr>
						<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?></th>
						<th class="text-center"><?=translate("User_Level", $this->session->userdata("language"))?></th>
						<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
					</tr>
				</thead>

				<tbody>
					
				</tbody>
			</table>	
		</div>
	</div>
</div>
<div class="modal-footer">
	<a class="btn default" id="close" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
</div>


<script type="text/javascript">
$(document).ready(function(){
	baseAppUrl = mb.baseUrl() + 'master/user_level/';
	$tableUserLevel = $('table#table_user_level');
	handleDataTableUserLevel();
	
});

function handleDataTableUserLevel() {
	oTableUserLevel = $tableUserLevel.dataTable({
       	'processing'            : true,
		'serverSide'            : true,
		'paginate'				: false,
		'language'              : mb.DTLanguage(),
		'ajax'              	: {
			'url' : baseAppUrl + 'listing_p_customer/add',
			'type' : 'POST',
		},			
		'pageLength'			: 10,
		'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
		'order'                	: [[0, 'desc']],
		'columns'               : [
			{ 'visible' : false, 'searchable': true, 'orderable': true },
			{ 'visible' : true, 'searchable': true, 'orderable': true },
			{ 'visible' : true, 'searchable': false, 'orderable': false },
    		]
    });
    $tableUserLevel.on('draw.dt', function (){
  
		$('.btn', this).tooltip();
		// action for delete locker
		var row = $('input#numRowCustomer').val();
		// alert(row);
		$('a#select-p-customer', this).click(function() 
		{
			$('input#user_level_persetujuan_customer_name'+ row).val($(this).data('item').nama);
			$('input#user_level_persetujuan_customer_id'+ row).val($(this).data('item').id);
			$('label#user_level_customer_lblname'+ row).text($(this).data('item').nama);
			$('a#addRowCustomer').click();
			$('button#close_modal').click();

		});
	});
}
</script>