<form class="form-horizontal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Inventory Identitas", $this->session->userdata("language"))?></span>
		</h4>
	</div>
	<div class="modal-body">
		<?php 

			if($id != '')
			{
				$id = base64_decode(urldecode($id));
				$id = rtrim($id, ',');
			}
			// mencari inventory_identitas yg inventory_id nya sesuai dengan inventory_id yang didapatkan sebelumnya
			$inventory_history_detail = $this->inventory_history_detail_m->get_by('id in ('.$id.')');
			$inventory_history_detail = object_to_array($inventory_history_detail);

		?>
		
		<table class="table table-hover table-bordered table-striped" id="tabel_inventory_identitas">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate('No', $this->session->userdata('language'))?></th>
					<th class="text-center"><?=translate('Batch Number', $this->session->userdata('language'))?></th>
					<th class="text-center"><?=translate('Expire Date', $this->session->userdata('language'))?></th>
					
					<th class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
				</tr>
			</thead>
			<tbody>
				<?php

					$i = 1;
					foreach ($inventory_history_detail as $detail) {
						
						echo '<tr>';
						echo '<td class="text-center">'.$i.'</td>';
						echo '<td class="text-center">'.$detail['bn_sn_lot'].'</td>';
						echo '<td class="text-center">'.$detail['expire_date'].'</td>';
						echo '<td class="text-center">'.abs($detail['change_stock']).'</td>';
						echo '</tr>';


						$i++;
					}
					
				?>
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<a class="btn default" data-dismiss="modal"><?=translate('Kembali', $this->session->userdata('language'))?></a>
	</div>
</form>
<script type="text/javascript">

	$(document).ready(function() 
	{
	   	baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';

	});


</script>