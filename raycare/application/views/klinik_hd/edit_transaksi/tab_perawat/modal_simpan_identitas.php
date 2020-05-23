<form class="form-horizontal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Simpan Identitas", $this->session->userdata("language"))?></span>
		</h4>
	</div>
	<div class="modal-body">

		<?php 

			$simpan_item_identitas_detail = array();

			// mencari simpan_item yg item_id dan item_satuan_id 
			$simpan_item = $this->item_tersimpan_m->get_by(array('pasien_id' => $pasien_id, 'item_id' => $item_id, 'item_satuan_id' => $item_satuan_id));
			foreach ($simpan_item as $data_simpan_item) 
			{
				// mencari simpan_item_identitas yg simpan_item_id nya sesuai dengan simpan_item_id yang didapatkan sebelumnya
				$simpan_item_identitas = $this->simpan_item_identitas_m->get_by(array('simpan_item_id' => $data_simpan_item->simpan_item_id));
			// die(dump($simpan_item_identitas));

				foreach ($simpan_item_identitas as $data_simpan_item_identitas) 
				{
					$simpan_item_identitas_detail[] = $this->item_tersimpan_m->get_simpan_identitas($data_simpan_item_identitas->simpan_item_identitas_id)->result();	
				}		
				
			}
			if(count($simpan_item_identitas_detail))
			{
				$hidden = '';

		?>
		<table class="table table-hover table-bordered table-striped" id="tabel_simpan_identitas">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate('No', $this->session->userdata('language'))?></th>
					<?php 
						foreach ($simpan_item_identitas_detail[0] as $head_simpan_item_identitas_detail) 
						{
							echo '<th class="text-center">'.$head_simpan_item_identitas_detail->judul.'</th>';
						}
					 ?>
					<th class="text-center"><?=translate('Stock', $this->session->userdata('language'))?></th>
					<th class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
				</tr>
			</thead>
			<tbody>
				<?php

					// die_dump($simpan_item_identitas_detail);
					
					$i = 1;
					foreach ($simpan_item_identitas_detail as $data_simpan_item_identitas_detail) 
					{

						echo '<tr>';
						echo '<td class="text-center">'.$i.'
									<input class="hidden form-control simpan_item_id_'.$i.'" id="simpan_item_id_'.$i.'" name="simpan_identitas['.$i.'][simpan_item_id]" value="'.$data_simpan_item_identitas_detail[0]->simpan_item_id.'">
									<input class="hidden form-control simpan_item_identitas_id_'.$i.'" id="simpan_item_identitas_id_'.$i.'" name="simpan_identitas['.$i.'][simpan_item_identitas_id]" value="'.$data_simpan_item_identitas_detail[0]->simpan_item_identitas_id.'">
								</td>';
						
						$z = 1;
						foreach ($data_simpan_item_identitas_detail as $data_detail) 
						{
							echo '<td class="text-center">'.$data_detail->value.'
									<input class="hidden form-control simpan_identitas_detail_'.$i.'_'.$z.'" id="simpan_identitas_detail_'.$i.'_'.$z.'" name="simpan_identitas_detail_'.$i.'['.$z.'][identitas_id]" value="'.$data_detail->identitas_id.'">
									<input class="hidden form-control simpan_identitas_detail_'.$i.'_'.$z.'" id="simpan_identitas_detail_'.$i.'_'.$z.'" name="simpan_identitas_detail_'.$i.'['.$z.'][judul]" value="'.$data_detail->judul.'">
									<input class="hidden form-control simpan_identitas_detail_'.$i.'_'.$z.'" id="simpan_identitas_detail_'.$i.'_'.$z.'" name="simpan_identitas_detail_'.$i.'['.$z.'][value]" value="'.$data_detail->value.'">
								</td>';
							$z++;
						}
						echo '<td class="text-center">'.$data_simpan_item_identitas_detail[0]->jumlah.'
								<input class="hidden form-control simpan_item_stock" id="simpan_item_stock_'.$i.'" name="simpan_identitas['.$i.'][stock]" value="'.$data_simpan_item_identitas_detail[0]->jumlah.'">
								<input class="hidden form-control simpan_item_harga_beli" id="simpan_item_harga_beli_'.$i.'" name="simpan_identitas['.$i.'][harga_beli]" value="'.$data_simpan_item_identitas_detail[0]->harga_beli.'">
								<input class="hidden form-control simpan_item_harga_jual" id="simpan_item_harga_jual_'.$i.'" name="simpan_identitas['.$i.'][harga_jual]" value="'.$data_simpan_item_identitas_detail[0]->harga_jual.'">
							</td>';
						
						echo '<td class="text-center"><input type="number" class="form-control text-right identitas_jumlah" id="identitas_jumlah_'.$i.'" name="simpan_identitas['.$i.'][jumlah]" min="0" max="'.$data_simpan_item_identitas_detail[0]->jumlah.'" data-row="'.$i.'" value="0"></td>';
						echo '</tr>';


						$i++;
					}
					
				?>
			</tbody>
		</table>
		<?php
		}
		else
		{
			$hidden = 'hidden';

			echo translate('Tidak ada identitas item yang tersedia', $this->session->userdata('language'));
		}
		?>		
	</div>
	<div class="modal-footer">
		<a class="btn default" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
		<a class="btn btn-primary save_identitas <?=$hidden?>" data-dismiss="modal"><?=translate('OK', $this->session->userdata('language'))?></a>
	</div>
</form>
<script type="text/javascript">

	$(document).ready(function() 
	{
	   	baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';
        initForm();

	});

	function initForm()
	{

		$('a.save_identitas').click(function()
		{
			
			var jumlah = 0;
            $.each($('.identitas_jumlah', $('#tabel_simpan_identitas')), function(idx) 
            {
            	jumlah = jumlah + parseInt($(this).val());
            	$(this).attr("value", $(this).val());
            });

            $('input#jumlah_item_tersimpan').val(jumlah);
            $('div#simpan_identitas_detail').html($('table#tabel_simpan_identitas > tbody').html());


		})
	}

   	


</script>