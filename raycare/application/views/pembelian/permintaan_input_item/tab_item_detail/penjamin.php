<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<?=translate("Pilih Penjamin", $this->session->userdata("language"))?>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-group">
			<div class="col-md-12">
				<?php
				$cabang_id = $this->session->userdata('cabang_id');
				$penjamin = $this->item_klaim_m->data_penjamin($cabang_id)->result_array();
				// die_dump($penjamin);
				$penjamin_option = array(
				);
				foreach ($penjamin as $data)
				{
				    $penjamin_option[$data['id']] = $data['nama'];
				}

				if ($flag == "add") {
					echo form_dropdown("penjamin[]", $penjamin_option, '', "id=\"multi_select_penjamin\" class=\"multi-select\" multiple=\"multiple\"");
				}
				else
				{

					$item_penjamin = array();
					$data_item_penjamin = $this->item_klaim_m->get_by(array('item_id' => $pk_value, 'is_active' => 1));
					
					foreach ($data_item_penjamin as $item_penjamin_selected)
					{
				    	$item_penjamin[$item_penjamin_selected->penjamin_id] = $item_penjamin_selected->penjamin_id;
					}

					// die_dump($user_level_menu_option);
					
					echo form_dropdown("penjamin[]", $penjamin_option, $item_penjamin, "id=\"multi_select_penjamin\" class=\"multi-select\" multiple=\"multiple\"");
						
				}
				?>
			</div>	
		</div>
	</div>
</div>


