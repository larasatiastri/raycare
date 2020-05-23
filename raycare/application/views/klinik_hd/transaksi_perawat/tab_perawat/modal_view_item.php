<form class="form-horizontal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Inventory Identitas", $this->session->userdata("language"))?></span>
		</h4>
	</div>
	<div class="modal-body">
		<?php 

			if($tindakan_hd_item_id != '')
			{
				$tindakan_hd_item_id = base64_decode(urldecode($tindakan_hd_item_id));
				$tindakan_hd_item_id = rtrim($tindakan_hd_item_id, ',');
			}
			// mencari inventory_identitas yg inventory_id nya sesuai dengan inventory_id yang didapatkan sebelumnya
			$tindakan_hd_item_identitas = $this->tindakan_hd_item_identitas_m->get_by('tindakan_hd_item_id in ('.$tindakan_hd_item_id.')');

			foreach ($tindakan_hd_item_identitas as $data_tindakan_hd_item_identitas) 
			{
				$tindakan_hd_item_identitas_detail[] = $this->tindakan_hd_item_identitas_detail_m->get_data_identitas($data_tindakan_hd_item_identitas->id)->result();	
				// die_dump($tindakan_hd_item_identitas_detail);	
			}		
				
		?>

		<table class="table table-hover table-bordered table-striped" id="tabel_inventory_identitas">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate('No', $this->session->userdata('language'))?></th>
					<?php 
						foreach ($tindakan_hd_item_identitas_detail[0] as $head_tindakan_hd_item_identitas_detail) 
						{
							echo '<th class="text-center">'.$head_tindakan_hd_item_identitas_detail->judul.'</th>';
						}
					 ?>
					<!-- <th class="text-center"><?=translate('Stock', $this->session->userdata('language'))?></th> -->
					<th class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
				</tr>
			</thead>
			<tbody>
				<?php

					// die_dump($tindakan_hd_item_identitas_detail);

					$i = 1;
					foreach ($tindakan_hd_item_identitas_detail as $data_tindakan_hd_item_identitas_detail) {
						
						echo '<tr>';
						echo '<td class="text-center">'.$i.'</td>';
						
						$z = 1;
						foreach ($data_tindakan_hd_item_identitas_detail as $data_detail) 
						{
							echo '<td class="text-center">'.$data_detail->value.'
								</td>';
							$z++;
						}
						echo '<td class="text-center">'.$data_tindakan_hd_item_identitas_detail[0]->jumlah.'</td>';
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