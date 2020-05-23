<?php
	$btn_search = '<div class="text-center"><button title="Search Item" name="77resep[{0}][add77]" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></div>';
	$btn_del    = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

	$options = array(
		'' => translate('Pilih', $this->session->userdata('language')).'...',
		'1' => translate('PC', $this->session->userdata('language')),
		'2' => translate('AC', $this->session->userdata('language')),
		'3' => translate('SL', $this->session->userdata('language')),
		'4' => translate('SC', $this->session->userdata('language')),
		'5' => translate('IV', $this->session->userdata('language')),
		'6' => translate('IM', $this->session->userdata('language')),
		'7' => translate('UE', $this->session->userdata('language')),
	);
	$item_cols = array(
	    'item_code'   	=> '<input type="hidden" id="tindakan_tipe_obat_{0}" name="resep[{0}][tipe_obat]"><input type="hidden" id="tindakan_itemrow_{0}" name="resep[{0}][itemrow]"><input type="hidden" id="tindakan_id_{0}" name="resep[{0}][tindakan_id]"><input type="hidden" 							id="tindakan_id2_{0}" name="resep[{0}][tindakan_id2]"><label id="tindakan_code_{0}" name="resep[{0}][code]" />', 
	    'item_name'   	=> '<label id="tindakan_nama_{0}" name="resep[{0}][nama]" /><input type="hidden" 								id="tindakan_keterangan_{0}" name="resep[{0}][keteranganmodal]" class="form-control" readonly>', 
	    'item_jumlah'  	=> '<div class="input-group" style="width:120px;"><input type="text" id="tindakan_jumlah_{0}" name="resep[{0}][jumlah]" class="form-control" value="1" ><span class="input-group-addon satuan"></span></div><input type="hidden" id="tindakan_satuan_{0}" name="resep[{0}][satuan]" class="form-control" readonly>',
	    'item_dosis'  	=> '<input type="text" id="tindakan_item_dosis_{0}" name="resep[{0}][item_dosis]" class="form-control" style="width:100px;">',
	    'item_aturan'  	=> form_dropdown('resep[{0}][item_aturan]', $options, '','id="tindakan_item_aturan_{0}" class="form-control" style="width:100px;"'),
	    'item_bw_plg'  	=> '<div class="text-center"><input type="checkbox" id="tindakan_item_bawa_{0}" name="resep[{0}][item_bawa]" class="form-control" value="1"></div>',
	    'action'      	=> $btn_del,
	);

	// gabungkan $item_cols jadi string table row
	$item_row_template 	=  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
	$btn_del2    		= '<div class="text-center"><button class="btn red-intense del-this2" title="Delete Resep"><i class="fa fa-times"></i></button></div>';
	
	$item_cols2 		= array(
	    'item_keterangan'   => '<textarea cols="20" id="tindakan_keterangan_{0}" name="resepmanual[{0}][keterangan]" class="form-control"></textarea>',
	    'action'      		=> $btn_del2,
	);

	// gabungkan $item_cols jadi string table row
	$item_row_template2 	=  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols2) . '</td></tr>';

// =============temp
	$btn_search776 	= '<div class="text-center"><button title="Search Item" name="1resep[{0}][add1]" class="btn btn-primary search-item1"><i class="fa fa-search"></i></button></div>';
	$btn_del776    	= '<div class="text-center"><button class="btn red-intense del-this1" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';


	$item_cols776 = array(
	    'item_code'   	=> '<input type="hidden" id="tindakan_itemrow2_{0}" name="1resep[{0}][itemrow2]"><input type="hidden" id="1tindakan_id_{0}" name="1resep[{0}][tindakan_id1]"><input type="hidden" id="1tindakan_id2_{0}" name="1resep[{0}][tindakan_id2]"><input type="text" id="1tindakan_code_{0}" name="1resep[{0}][code1]" class="form-control" readonly>',
	    'item_search' 	=> $btn_search776,
	    'item_name'   	=> '<input type="text" id="1tindakan_nama_{0}" name="1resep[{0}][nama1]" class="form-control" readonly>',
	    
	    'item_jumlah'  	=> '<input type="text" id="1tindakan_jumlah_{0}" name="1resep[{0}][jumlah1]" value="1" class="form-control" style="width:300px;">',
	    'item_satuan'  	=> '<div name="1resep[{0}][div1]" align="center"><input type="text" id="1tindakan_satuan_{0}" name="1resep[{0}][satuan1]" class="form-control"></div>',
	     
	    'action'      	=> $btn_del776,
	);

	// gabungkan $item_cols jadi string table row
	$item_row_template776 =  '<tr id="item_row556_{0}" class="table_item26"><td>' . implode('</td><td>', $item_cols776) . '</td></tr>';
    

	$item_cols211 = array(
	    'item_keterangan'   => '<input type="hidden" id="tindakan_itemrow3_{0}" name="resepmanual3[{0}][itemrow3]"><input type="text" sid="tindakan_keterangan11_{0}" name="resepmanual3[{0}][keterangan11]" class="form-control">', 
	);

	// gabungkan $item_cols jadi string table row
	$item_row_template211 =  '<tr id="item_row556_{0}" class="table_item26"><td>' . implode('</td><td>', $item_cols211) . '</td></tr>';

	$btn_add='<a id="tambahrow"  class="btn btn-circle btn-primary" name="tambahrow">
            <i class="fa fa-plus"></i>
            <span class="hidden-480">
                 '.translate("Obat", $this->session->userdata("language")).'
            </span>
        	</a>';
?> 
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<?=translate("Pembuatan Resep", $this->session->userdata("language"))?>
			</div>
			<input type="hidden" id="btnadd" value="<?=htmlentities($btn_add)?>">
			<div class="actions" id="appendrow">
				    <a id="tambahracikan" data-target="#ajax_resep" href="<?=base_url()?>klinik_hd/transaksi_dokter/modalracikan" data-toggle="modal"  class="btn btn-circle btn-primary hidden" name="tambahracikan">
	                <i class="fa fa-plus"></i>
	                <span class="hidden-480">
	                     <?=translate("Obat Racikan", $this->session->userdata("language"))?>
	                </span>
	            </a>
	            
	            
	            <a id="simpan_resep" title="simpan" class="btn btn-icon-only btn-circle btn-primary hidden" name="simpanresep">
	                <i class="glyphicon glyphicon-floppy-disk"></i>
	            </a>
	        	 
	            <a id="reloadpop"  name="tambahrow"  style="display:none">
	                reload
	            </a> 
	            <a id="addrowracikan"  name="addrowracikan" style="display:none">
	                addrowracikan
	            </a> 

	             <a id="addrowracikan2"  name="addrowracikan2" style="display:none">
	                addrowracikan
	            </a> 
	        </div>
		</div>
		 
		<div class="portlet-body">	
			<div class="portlet light">
				<div class="portlet-title" >
					<div class="caption">
						<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
						<?=translate("Resep Obat", $this->session->userdata("language"))?>
					</div>
					<div class="actions">
						<a id="tambahrow" title="Search Item" class="btn btn-icon-only btn-circle btn-default" name="tambahrow">
			                <i class="fa fa-plus"></i>
			            </a>
		            </div>
				</div>
				<div class="portlet-body">

					<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
		        	<div class="table-responsive">
		           		<table class="table table-striped table-bordered table-hover" id="table_order_item22" name="table_order_item22">
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
		        	<span id="tpl_item_row776" class="hidden"><?=htmlentities($item_row_template776)?></span>
		        	 
		           		<table class="table table-striped table-bordered table-hover hidden" id="table_order_item2226" name="table_order_item2226">
		               		<thead>
		                   		<tr role="row">
		                       		<th  class="text-center" colspan="2"><?=translate("Kode", $this->session->userdata("language"))?></th>
		                        	<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
		                        	<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
		                        	<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></th>
		                        	<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
		                        	 
		                    	</tr>
		                	</thead>
		              		<tbody>
		           			</tbody>
		            	</table>
		            	<span id="tpl_item_row211" class="hidden"><?=htmlentities($item_row_template211)?></span>
		        	<div class="table-responsive">
		           		<table class="table table-striped table-bordered table-hover hidden" id="table_order_item211" name="table_order_item211">
		               		<thead>
		                   		<tr role="row">
		                       		<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
		                    	</tr>
		                	</thead>
		              		<tbody>
		           			</tbody>
		            	</table>
		        	</div>                    
				</div>
			</div>
			<div class="portlet light hidden">
				<div class="portlet-title" >
					<div class="caption">
						<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
						<?=translate("Resep Manual", $this->session->userdata("language"))?>
					</div>
					<div class="actions">
						 
			            <a id="tambahrow2"  class="btn btn-icon-only btn-circle btn-default" name="tambahrow2">
			                <i class="fa fa-plus"></i>
			            </a>
			        </div>
				</div>
				 
				<div class="portlet-body">			 
					<span id="tpl_item_row2" class="hidden"><?=htmlentities($item_row_template2)?></span>
		        	<div class="table-responsive">
		           		<table class="table table-striped table-bordered table-hover" id="table_order_item23" name="table_order_item23">
		               		<thead>
		                   		<tr role="row">
		                       		<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
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

<div id="popover_item_content" style="display:none">
	<div class="portlet-body form">
		<div class="form-body">
			<?php include('tab_data_pasien/obat.php') ?>
		</div>
	</div>	 
</div>	
 
 
 

 
 