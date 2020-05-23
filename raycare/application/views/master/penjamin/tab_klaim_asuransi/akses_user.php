<?php	
 
		

$form_option_payment = '<div class="form-group">
								<input type="hidden" name="payment[_ID_0][idsyarat]">
								<input type="hidden" name="payment[_ID_0][idsyarat2]">
								<input type="hidden" name="payment[_ID_0][isdeleted]" value="1">
								<label class="control-label col-md-2">Judul<span class="required">*</span></label>
								<div class="col-md-6">
								<div class="input-group">
								 <input class="form-control col-md-2" id="payment[_ID_0][judul]" name="payment[_ID_0][judul]" required="required">
								<span class="input-group-btn">
							     		<a class="btn red-intense del-this" title="Remove"><i class="fa fa-times"></i></a>
							     	</span>
					     		</div>	 
								</div>
							</div>
						<div class="form-group">
							
				     	<label for="payment[_ID_0][payment_type]" class="col-md-2 control-label">Tipe<span class="required">*</span></label>
					    	<div class="col-md-4">
					    		 
					    			<select class="form-control" name="payment[_ID_0][payment_type]" id="payment[_ID_0][payment_type]">
									  <option value="0">Pilih..</option>
									  <option value="1">Text</option>
									  <option value="2">Text Area</option>
									  <option value="3">Number</option>
									  <option value="4">Dropdown</option>
									  <option value="5">Radio Button</option>
									  <option value="6">Checbox</option>
									  <option value="7">Multi Select</option>
									  <option value="8">File</option>
									</select>
					     			 
					     		</div>
					     	</div>
					    </div>
					    
					    <div id="section_2" hidden>
					    	<div class="form-group">
								<label class="control-label col-md-2"></label>
								<div class="col-md-6">
								<div class="portlet">
								 <div class="portlet-title">
                                	<div class="caption">
                                    	<i class=""></i><span>List</span>
                                	</div>
                                	<div class="actions">
                                    	<a  class="btn btn-primary" id="payment[_ID_0][addrow]" name="payment[_ID_0][addrow]">
                                    	<i class="fa fa-plus"></i>
                                        <span class="hidden-480">
                                          
                                        </span>
                                    </a>
                                </div>
                            	</div>
								 <div class="portlet-body">
								 	<input type="hidden" name="payment[_ID_0][idcount]">
	                    			<span id="tpl_item_row" class="hidden">'.htmlentities($item_row_template).'</span>
	                            	<div class="table-responsive">
	                               		<table class="table table-striped table-bordered table-hover" id="payment[_ID_0][table_order_item22]" name="payment[_ID_0][table_order_item22]">
	                                   		<thead>
	                                       		<tr role="row" class="heading">
	                                           		<th  class="text-center">Text</th>
	                                            	<th class="text-center">Value</th>
	                                            	<th class="text-center">Aksi</th>
	                                            	 
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
							 
							 
					    </div>
					    <div id="section_1" hidden>
							<div class="form-group">
								<label class="control-label col-md-2"></label>
								<div class="col-md-1">
									<input class="form-control col-md-1" id="payment[_ID_0][text]" name="payment[_ID_0][text]" > 
								</div>
								<label class="control-label">Maksimal karakter</label>
							</div>
							 
					    </div>';

			$btn_del    = '<button class="btn btn-sm red-intense del-this" title="Hapus Tindakan"><i class="fa fa-times"></i></button></div>';
		 	$item_cols = array(
    			 
                'item_text' =>'	<input type="text" id="tindakan_text1_{0}" name="tindakan[{0}][text1]" class="form-control t1">',
    			'item_value'  => '<input type="text" id="tindakan_isi_{0}" name="tindakan[{0}][isi]" class="form-control t2">',
    			'action'      => '<div align=center>'.$btn_del.'</div>',
			);

 			$item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Syarat Pasien", $this->session->userdata("language"))?></span>
		</div>
			<div class="actions">
				 <a title="Cari" class="btn btn-circle btn-icon-only btn-default add-syarat" id="addsyarat">
	                <i class="fa fa-search"></i>
	            </a>
	            <a title="Tambah" class="btn btn-circle btn-icon-only btn-default add-payment">
	                <i class="fa fa-plus"></i>
	            </a>
	        </div>
	</div>
	<div class="portlet-body form">
		<div class="">
			<div class="form-group">
				<div class="col-md-12">
					<input type="hidden" id="counter" name="counter" >
					 <div id="section-payment">
						<input type="hidden" id="tpl-form-payment" value="<?=htmlentities($form_option_payment)?>">
						<div class="form-body">
							<ul class="list-unstyled">
                		</ul>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>

<div id="popover_item_content5" style="display:none">
    <table id="table_syarat2" class="table table-striped table-bordered table-hover">
    	<thead>
        	<tr>
            	<th class="text-center"><?=translate("Judul", $this->session->userdata("language"))?></th>
                <th class="text-center"><?=translate("Judul", $this->session->userdata("language"))?></th>
                <th class="text-center"><?=translate("Judul", $this->session->userdata("language"))?></th>
                <th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
		    </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


