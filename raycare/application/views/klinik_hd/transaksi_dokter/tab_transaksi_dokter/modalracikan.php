 
 <link href="<?=base_url()?>assets/metronic/admin/layout3/css/google-font-open-sans-400-300-600-700.css" rel="stylesheet" type="text/css"/>
 <link href="<?=base_url()?>assets/mb/global/css/maestrobyte.css" rel="stylesheet" type="text/css">
 <link href="<?=base_url()?>assets/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css">
 
<style>
.popover{
    z-index:100000000;
}
</style>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/mb/pages/klinik_hd/transaksi_dokter/js/create_tindakan2.js" type="text/javascript"></script>

<form id="modalracikan1" name="modalracikan1">
 
<?
			$btn_search77 = '<div class="text-center"><button title="Search Item" name="777resep[{0}][add777]" class="btn btn-sm btn-success search-item775"><i class="fa fa-search"></i></button></div>';
			$btn_del77    = '<div class="text-center"><button class="btn btn-sm red-intense del-this77" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';
 
		 

			$item_cols77 = array(
			    'item_code'   => '<input type="hidden" id="77tindakan_id_{0}" name="77resep[{0}][tindakan_id77]"><input type="hidden" id="77tindakan_id2_{0}" name="77resep[{0}][tindakan_id277]"><input type="text" id="77tindakan_code_{0}" name="77resep[{0}][code77]" class="form-control"  readonly >',
			    'item_search' => $btn_search77,
			    'item_name'   => '<input type="text" id="77tindakan_nama_{0}" name="77resep[{0}][nama77]" class="form-control" readonly>',
			    
			    'item_jumlah'  => '<input type="text" id="77tindakan_jumlah_{0}" name="77resep[{0}][jumlah77]" class="form-control" >',
			    'item_satuan'  => '<div name="77resep[{0}][div77]" align="center"><select id="77tindakan_satuan_{0}" name="77resep[{0}][satuan77]" class="form-control"></select></div>',
			     
			    'action'      => $btn_del77,
			);

			// gabungkan $item_cols jadi string table row
			$item_row_template77 =  '<tr id="item_row55_{0}" class="table_item2"><td>' . implode('</td><td>', $item_cols77) . '</td></tr>';
		    
			

			$btn_del26    = '<div class="text-center"><button class="btn btn-sm red-intense del-this22222" title="Delete Resep"><i class="fa fa-times"></i></button></div>';
 			$item_cols26 = array(
			    'item_keterangan'   => '<input type="text" id="tindakan_keterangan77_{0}" name="77resepmanual[{0}][keterangan77]" class="form-control">',
			    'action'      => $btn_del26,
			);

			// gabungkan $item_cols jadi string table row
			$item_row_template277 =  '<tr id="item_row55_{0}" class="table_item2"><td>' . implode('</td><td>', $item_cols26) . '</td></tr>';
 ?>
 <?
        $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
        $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
        $msg = translate("Apakah anda yakin akan menambah dokumen ini?",$this->session->userdata("language"));
    ?>
	<div class="portlet light">
			<div class="alert alert-danger display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_danger?>
			    </div>
			    <div class="alert alert-success display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_success?>
			    </div>
		<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Buat Resep Obat Racikan", $this->session->userdata("language"))?></span>
					</div>
				</div>
		  <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                     <div class="input-group">
                                        <input type="text" id="nama_racikan" name="nama_racikan" required="required" class="form-control" placeholder="Nama"  >
                                        
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Jumlah", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" id="jumlah_racikan" name="jumlah_racikan" required="required" class="form-control" placeholder="Jumlah">
                                        
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>

                            <div class="form-group">
								<label class="control-label col-md-4"><?=translate("Keterangan :", $this->session->userdata("language"))?> </label>
								
								<div class="col-md-4">
									<?php
										$assessment_cgs = array(
						                    "name"			=> "keterangan_racikan",
						                    "id"			=> "keterangan_racikan",
						                    "cols"			=> 32,
											"rows"			=> 5,
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control",
						                    "placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
						                   
						                    // "value"			=> $assessment_cgs_value
						                );
					                echo form_textarea($assessment_cgs);
									?>
								</div>
								 <div class="col-md-12"></div>
							</div>
							 <div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Komposisi Racikan", $this->session->userdata("language"))?></span>
					</div>
				</div>
		<div class="portlet-body">
						
	                    			<span id="tpl_item_row77" class="hidden"><?=htmlentities($item_row_template77)?></span>
	                            	 
	                               		<table class="table table-striped table-bordered table-hover" id="table_order_item222" name="table_order_item222">
	                                   		<thead>
	                                       		<tr role="row" class="heading">
	                                           		<th  class="text-center" colspan="2">Kode</th>
	                                            	<th class="text-center">Nama</th>
	                                             
	                                            	<th class="text-center">Jumlah</th>
	                                            	<th class="text-center">Satuan</th>
	                                            	 
	                                            	<th class="text-center">Aksi</th>
	                                            	 
	                                        	</tr>
	                                    	</thead>
	                                  		<tbody>
	                               			</tbody>
	                                	</table>
	                            	 
	                          
	                            
		</div>

		<div class="portlet-title" >
			 <div class="caption">
				<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Keterangan Manual", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
				 
	            <a id="tambahrow222"  class="btn btn-primary" name="tambahrow222">
	                <i class="fa fa-plus"></i>
	                <span class="hidden-480">
	                     <?=translate("Tambah", $this->session->userdata("language"))?>
	                </span>
	            </a>
	        </div>
		</div>
		 
		<div class="portlet-body">
						 
	                    			<span id="tpl_item_row88" class="hidden"><?=htmlentities($item_row_template277)?></span>
	                            	<div class="table-responsive">
	                               		<table class="table table-striped table-bordered table-hover" id="table_order_item233" name="table_order_item233">
	                                   		<thead>
	                                       		<tr role="row" class="heading">
	                                           		<th  class="text-center" >Keterangan</th>
	                                            	<th class="text-center">Aksi</th>
	                                             
	                                            	 
	                                        	</tr>
	                                    	</thead>
	                                  		<tbody>
	                               			</tbody>
	                                	</table>
	                            	</div>
	                          
	                            
		</div>
	 <div class="modal-footer">
                    <div class="form-actions fluid">	
    				    <div class="col-md-12">
    				    	
                            <button type="reset" class="btn default" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></button>
                              <a class="btn btn-primary" id="simpan_racikan" ><?=translate("Simpan", $this->session->userdata("language"))?></a>
        		      	</div>		
                    </div>
                    <!-- <button type="button" class="btn default" data-dismiss="modal">Simpan</button> -->
                </div>
 
	</div>
 
</form>
 <form id="modalracikan2" name="modalracikan2">
 	<div id="popover_item_content244" style="display:none" >
	<div class="portlet-body form">
					<div class="form-body">
						<div class="portlet light">
	 <div class="form-body">
				 <div class="form-group">
        						<label class="control-label col-md-3"><?=translate("Kategori", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
        						<div class="col-md-4">
                                    <div class="input-group">
                                    	<?php
                                		$wareid="";
                                
                                		$warehouse_option = array(
                                    			'all' => translate('Semua', $this->session->userdata('language')) . '..'
                                			);
                                		$result = $this->item_kategori_m->get_by(array('is_active' => 1));
                                		foreach($result as $row)
                                		{
                                    		$warehouse_option[$row->id] = $row->nama;
                                		}
                                		echo form_dropdown("kategori11", $warehouse_option, $wareid, "id=\"kategori11\" class=\"form-control filter\" ");
                            
                               // $this->session->unset_userdata('wareid1');
                            ?>
                                      <!--   <select id="jenisdokumen" name="jenisdokumen" required="required" class="form-control">
                                        	<option value="1">Dokumen Pelengkap</option>
                                        	<option value="2">Dokumen Rekam Medis</option>
                                        </select> -->
                                        
                                    </div>

        						</div>
        					    <div class="col-md-12"></div>
        			        </div>
				</div>
	<div class="portlet-body form">
	 
		  

			<div class="row">
				
				<div class="col-md-12">
					<div class="portlet light" id="section-alamat">
						
						<div class="portlet-body">

							<table class="table table-striped table-bordered table-hover" id="table_obat11">
							<thead>
							<tr role="row" class="heading">
									<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
									 
									<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
								 
								</tr>
								</thead>
								<tbody>
								<tr>
	                    			<td><div class="text-left">Budi Johan</div></td>
	                    			<td><div class="text-center">Jakarta, 13 Jan 1977</div></td>
	                    			<td><div class="text-left">Budi Johan</div></td>
	                    			<td><div class="text-center">Jakarta, 13 Jan 1977</div></td>
	                    			 
	                			</tr>
	               				<tr>
	                    			<td><div class="text-left">Sasmi Dora</div></td>
	                    			<td><div class="text-center">Jakarta, 7 Jun 1934</div></td>
	                    			<td><div class="text-left">Budi Johan</div></td>
	                    			<td><div class="text-center">Jakarta, 13 Jan 1977</div></td>
	                    			 
	                			</tr>
								</tbody>
							</table>
							 
						</div>
					</div>
 
			</div>
		</div>

		 
	</div>

</div>
							 
			
				</div>
			</div>	
     
</div>

 
 