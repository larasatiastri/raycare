
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<?=translate("Formulir Tindakan Lain", $this->session->userdata("language"))?>
		</div>
	</div>
	 
	<div class="portlet-body">	
			
	<?php
		$option_tindakan = array(
			''	=> translate('Pilih', $this->session->userdata('language')).'...'
		);

		$tindakan = $this->tindakan_m->get_by(array('is_active' => 1));
		$tindakan = object_to_array($tindakan);

		foreach ($tindakan as $row) {
			$option_tindakan[$row['id']] = $row['nama'];
		}

			$btn_search = '<div class="text-center"><button title="Search Item" name="77resep_transfusi[{0}][add77]" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></div>';
			$btn_del    = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

			$options = array(
				'' => translate('Pilih', $this->session->userdata('language')).'...',
				'1' => translate('PC', $this->session->userdata('language')),
				'2' => translate('AC', $this->session->userdata('language')),
			);
			$item_cols = array(
			    'item_code'   	=> '<input type="hidden" id="tindakan_tipe_obat_{0}" name="resep_transfusi[{0}][tipe_obat]"><input type="hidden" id="tindakan_itemrow_{0}" name="resep_transfusi[{0}][itemrow]"><input type="hidden" id="tindakan_id_{0}" name="resep_transfusi[{0}][tindakan_id]"><input type="hidden" 							id="tindakan_id2_{0}" name="resep_transfusi[{0}][tindakan_id2]"><label id="tindakan_code_{0}" name="resep_transfusi[{0}][code]" />', 
			    'item_name'   	=> '<label id="tindakan_nama_{0}" name="resep_transfusi[{0}][nama]" /><input type="hidden" id="tindakan_keterangan_{0}" name="resep_transfusi[{0}][keteranganmodal]" class="form-control" readonly>', 
			    'item_jumlah'  	=> '<div class="input-group" style="width:120px;"><input type="text" id="tindakan_jumlah_{0}" name="resep_transfusi[{0}][jumlah]" class="form-control" value="1" ><span class="input-group-addon satuan"></span></div><input type="hidden" id="tindakan_satuan_{0}" name="resep_transfusi[{0}][satuan]" class="form-control" readonly>',
			    'item_dosis'  	=> '<input type="text" id="tindakan_item_dosis_{0}" name="resep_transfusi[{0}][item_dosis]" class="form-control" style="width:100px;">',
			    'item_aturan'  	=> form_dropdown('resep_transfusi[{0}][item_aturan]', $options, '','id="tindakan_item_aturan_{0}" class="form-control" style="width:100px;"'),
			    'item_bw_plg'  	=> '<div class="text-center"><input type="checkbox" id="tindakan_item_bawa_{0}" name="resep_transfusi[{0}][item_bawa]" class="form-control" value="1"></div>',
			    'action'      	=> $btn_del,
			);

			// gabungkan $item_cols jadi string table row
			$item_row_template 	=  '<tr id="item_row_{0}" class="table_item_transfusi"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
		?>

		<div class="form-body" >
			<div class="form-group">
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Pilih Tindakan Lain :", $this->session->userdata("language"))?></label>
					
					<div class="col-md-5">

						<div class="checkbox-list">
						 	<label class="checkbox-inline">
								<input  type="checkbox" id="tindakan_transfusi" value="1" class="" name="is_transfusi"> Transfusi  
						 	</label>
						 	<label class="checkbox-inline">
								<input type="checkbox" id="tindakan_ceklab" value="2" class="" name="is_cek_lab" disabled=""> Cek Lab  
						 	</label>
					 	</div> 
					</div>
				</div>
			</div>
			<div class="form-group" id="kantong_transfusi">
				<label class="control-label col-md-3"><?=translate("Jumlah Kantong Darah", $this->session->userdata("language"))?> :</label>
				<div class="col-md-6">
					<div class="input-group">
						<input type="number" id="jumlah_kantong_darah" name="jumlah_kantong_darah" class="form-control" value="0" min="0" max="6"> 
						<span class="input-group-addon">
							Labu
						</span>
					</div>
				</div>
			</div>
			
		</div>
		<div class="portlet light bordered" id="section-tindakan">
			<div class="portlet-title">
				<div class="caption">
					<?=translate("Detail Tindakan Transfusi", $this->session->userdata("language"))?>
				</div>
				<div class="actions">
					<a id="tambahrowresep" title="Search Item" class="btn btn-icon-only btn-circle btn-default" name="tambahrowresep">
		                <i class="fa fa-plus"></i>
		            </a>
		        </div>
			</div>
			 
			<div class="portlet-body">	
				
				<span id="tpl_item_row_resep" class="hidden"><?=htmlentities($item_row_template)?></span>
				
	        	<div class="table-responsive">
	           		<table class="table table-striped table-bordered table-hover" id="table_order_item_transfusi" name="table_order_item_transfusi">
	               		<thead>
	                   		<tr role="row">
	                       		<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></th>
	                        	<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
	                        	<th class="text-center" width="50px"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
	                        	<th class="text-center" colspan="2" width="10%"><?=translate("Aturan Pakai", $this->session->userdata("language"))?></th>
	                        	<th class="text-center" width="1%"><?=translate("Bawa Pulang", $this->session->userdata("language"))?></th>
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


 

 
 