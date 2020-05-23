<form class="form-horizontal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Inventory Identitas", $this->session->userdata("language"))?></span>
		</h4>
	</div>
	<div class="modal-body">
		<?php 
			$hidden = '';
			$inventory_identitas_detail = array();

			// mencari inventory yg item_id dan item_satuan_id 
			$inventory = $this->inventory_klinik_m->get_by(array('gudang_id' => $gudang_id, 'item_id' => $item_id, 'item_satuan_id' => $item_satuan_id));

			foreach ($inventory as $data_inventory) 
			{
				// mencari inventory_identitas yg inventory_id nya sesuai dengan inventory_id yang didapatkan sebelumnya
				$inventory_identitas = $this->inventory_identitas_m->get_by(array('inventory_id' => $data_inventory->inventory_id));

				if($inventory_identitas)
				{
					foreach ($inventory_identitas as $data_inventory_identitas) 
					{
						$inventory_identitas_detail[] = $this->inventory_klinik_m->get_data_identitas($data_inventory_identitas->inventory_identitas_id)->result();	
						// die_dump($inventory_identitas_detail);
					}	
				}
				
			}
		
			if($inventory_identitas_detail)
			{

		?>

		<table class="table table-hover table-bordered table-striped" id="tabel_inventory_identitas">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate('No', $this->session->userdata('language'))?></th>
					<?php 
						foreach ($inventory_identitas_detail[0] as $head_inventory_identitas_detail) 
						{
							echo '<th class="text-center">'.$head_inventory_identitas_detail->judul.'</th>';
						}
					 ?>
					<th class="text-center"><?=translate('Stock', $this->session->userdata('language'))?></th>
					<th class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
				</tr>
			</thead>
			<tbody>
				<?php

					if(isset($row))
					{
						$i=1;
						foreach ($inventory_identitas_detail as $data_inventory_identitas_detail) 
						{
							
							echo '<tr>';
							echo '<td class="text-center">'.$i.'
										<input class="hidden form-control inventory_id_'.$i.'" id="inventory_id_'.$i.'" name="inventory_identitas_'.$item_id.'['.$i.'][inventory_id]" value="'.$data_inventory_identitas_detail[0]->inventory_id.'">
										<input class="hidden form-control inventory_gudang_id_'.$i.'" id="inventory_gudang_id_'.$i.'" name="inventory_identitas_'.$item_id.'['.$i.'][gudang_id]" value="'.$data_inventory_identitas_detail[0]->gudang_id.'">
										<input class="hidden form-control inventory_pmb_id_'.$i.'" id="inventory_pmb_id_'.$i.'" name="inventory_identitas_'.$item_id.'['.$i.'][pmb_id]" value="'.$data_inventory_identitas_detail[0]->pmb_id.'">
										<input class="hidden form-control inventory_tanggal_datang_'.$i.'" id="inventory_tanggal_datang_'.$i.'" name="inventory_identitas_'.$item_id.'['.$i.'][tanggal_datang]" value="'.$data_inventory_identitas_detail[0]->tanggal_datang.'">
										<input class="hidden form-control inventory_identitas_id_'.$i.'" id="inventory_identitas_id_'.$i.'" name="inventory_identitas_'.$item_id.'['.$i.'][inventory_identitas_id]" value="'.$data_inventory_identitas_detail[0]->inventory_identitas_id.'">
									</td>';
							
							$z = 1;
							foreach ($data_inventory_identitas_detail as $data_detail) 
							{
								echo '<td class="text-center">'.$data_detail->value.'
										<input class="hidden form-control inventory_identitas_detail_'.$i.'_'.$z.'" id="inventory_identitas_detail_'.$i.'_'.$z.'" name="inventory_identitas_detail_'.$item_id.'_'.$i.'['.$z.'][identitas_id]" value="'.$data_detail->identitas_id.'">
										<input class="hidden form-control inventory_identitas_detail_'.$i.'_'.$z.'" id="inventory_identitas_detail_'.$i.'_'.$z.'" name="inventory_identitas_detail_'.$item_id.'_'.$i.'['.$z.'][judul]" value="'.$data_detail->judul.'">
										<input class="hidden form-control inventory_identitas_detail_'.$i.'_'.$z.'" id="inventory_identitas_detail_'.$i.'_'.$z.'" name="inventory_identitas_detail_'.$item_id.'_'.$i.'['.$z.'][value]" value="'.$data_detail->value.'">
									</td>';
								$z++;
							}
							echo '<td class="text-center">'.$data_inventory_identitas_detail[0]->jumlah.'
									<input class="hidden form-control inventory_stock" id="inventory_stock_'.$i.'" name="inventory_identitas_'.$item_id.'['.$i.'][stock]" value="'.$data_inventory_identitas_detail[0]->jumlah.'">
									<input class="hidden form-control inventory_harga_beli" id="inventory_harga_beli_'.$i.'" name="inventory_identitas_'.$item_id.'['.$i.'][harga_beli]" value="'.$data_inventory_identitas_detail[0]->harga_beli.'">
								</td>';
							
							echo '<td class="text-center"><input type="number" class="form-control text-right identitas_jumlah" id="identitas_jumlah_'.$i.'" name="inventory_identitas_'.$item_id.'['.$i.'][jumlah]" min="0" max="'.$data_inventory_identitas_detail[0]->jumlah.'" data-row="'.$i.'" value="0"></td>';
							echo '</tr>';


							$i++;
						}
					}
					else
					{
						$i = 1;
						foreach ($inventory_identitas_detail as $data_inventory_identitas_detail) 
						{
							
							echo '<tr>';
							echo '<td class="text-center">'.$i.'
										<input class="hidden form-control inventory_id_'.$i.'" id="inventory_id_'.$i.'" name="inventory_identitas['.$i.'][inventory_id]" value="'.$data_inventory_identitas_detail[0]->inventory_id.'">
										<input class="hidden form-control inventory_gudang_id_'.$i.'" id="inventory_gudang_id_'.$i.'" name="inventory_identitas['.$i.'][gudang_id]" value="'.$data_inventory_identitas_detail[0]->gudang_id.'">
										<input class="hidden form-control inventory_pmb_id_'.$i.'" id="inventory_pmb_id_'.$i.'" name="inventory_identitas['.$i.'][pmb_id]" value="'.$data_inventory_identitas_detail[0]->pmb_id.'">
										<input class="hidden form-control inventory_tanggal_datang_'.$i.'" id="inventory_tanggal_datang_'.$i.'" name="inventory_identitas['.$i.'][tanggal_datang]" value="'.$data_inventory_identitas_detail[0]->tanggal_datang.'">
										<input class="hidden form-control inventory_identitas_id_'.$i.'" id="inventory_identitas_id_'.$i.'" name="inventory_identitas['.$i.'][inventory_identitas_id]" value="'.$data_inventory_identitas_detail[0]->inventory_identitas_id.'">
									</td>';
							
							$z = 1;
							foreach ($data_inventory_identitas_detail as $data_detail) 
							{
								echo '<td class="text-center">'.$data_detail->value.'
										<input class="hidden form-control inventory_identitas_detail_'.$i.'_'.$z.'" id="inventory_identitas_detail_'.$i.'_'.$z.'" name="inventory_identitas_detail_'.$i.'['.$z.'][identitas_id]" value="'.$data_detail->identitas_id.'">
										<input class="hidden form-control inventory_identitas_detail_'.$i.'_'.$z.'" id="inventory_identitas_detail_'.$i.'_'.$z.'" name="inventory_identitas_detail_'.$i.'['.$z.'][judul]" value="'.$data_detail->judul.'">
										<input class="hidden form-control inventory_identitas_detail_'.$i.'_'.$z.'" id="inventory_identitas_detail_'.$i.'_'.$z.'" name="inventory_identitas_detail_'.$i.'['.$z.'][value]" value="'.$data_detail->value.'">
									</td>';
								$z++;
							}
							echo '<td class="text-center">'.$data_inventory_identitas_detail[0]->jumlah.'
									<input class="hidden form-control inventory_stock" id="inventory_stock_'.$i.'" name="inventory_identitas['.$i.'][stock]" value="'.$data_inventory_identitas_detail[0]->jumlah.'">
									<input class="hidden form-control inventory_harga_beli" id="inventory_harga_beli_'.$i.'" name="inventory_identitas['.$i.'][harga_beli]" value="'.$data_inventory_identitas_detail[0]->harga_beli.'">
								</td>';
							
							echo '<td class="text-center"><input type="number" class="form-control text-right identitas_jumlah" id="identitas_jumlah_'.$i.'" name="inventory_identitas['.$i.'][jumlah]" min="0" max="'.$data_inventory_identitas_detail[0]->jumlah.'" data-row="'.$i.'" value="0"></td>';
							echo '</tr>';


							$i++;
						}
						
					}

					
				?>
			</tbody>
		</table>
		<?php 
		 }
		 else
		 {
		 	$hidden = 'hidden';
		 	echo translate('Tidak ada stok yang tersedia di gudang ini.', $this->session->userdata('language'));
		 }
		?>
		
	</div>
	<div class="modal-footer">
		<a class="btn default" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
		<?php if(isset($row)){
			$baris = $row;
			echo '<a class="btn btn-primary save_identitas_paket '.$hidden.'" data-dismiss="modal">'.translate('OK', $this->session->userdata('language')).'</a>';
		}
		else{
			$baris = '';
		echo '<a class="btn btn-primary save_identitas '.$hidden.'" data-dismiss="modal">'.translate('OK', $this->session->userdata('language')).'</a>';
			
		}
		?>
	</div>
</form>
<script type="text/javascript">

	$(document).ready(function() 
	{
	   	baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
        initForm();

	});

	function initForm()
	{
		$('a.save_identitas').click(function()
		{
			
			var jumlah = 0;
            $.each($('.identitas_jumlah', $('#tabel_inventory_identitas')), function(idx) 
            {
            	jumlah = jumlah + parseInt($(this).val());
            	$(this).attr("value", $(this).val());
            });

            $('input#jumlah_inventory').val(jumlah);
            $('div#inventory_identitas_detail').html($('table#tabel_inventory_identitas > tbody').html());


		});

		$('a.save_identitas_paket').click(function()
		{
			
			var jumlah = 0;
            $.each($('.identitas_jumlah', $('#tabel_inventory_identitas')), function(idx) 
            {
            	jumlah = jumlah + parseInt($(this).val());
            	$(this).attr("value", $(this).val());
            });

            $('input[name$="[user]"]', $('tr#'+"<?=$baris?>") ).val(jumlah);
            $('div#inventory_identitas_detail', $('tr#'+"<?=$baris?>")).html($('table#tabel_inventory_identitas > tbody').html());
            


		});
	}


</script>