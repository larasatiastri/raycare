<?php
	$form_attr = array(
		"id"			=> "form_history", 
		"name"			=> "form_history", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);
	$hidden = array(
		"command"	=> "add"
	);
	$id = array(
		'id'    => 'id',
    	'type'  => 'hidden',
    	'value' => $pk_value
	);
	echo form_open("", $form_attr,$hidden, $id);

	$date = date_create($form_data['tanggal_mulai']);
	if ($form_data['tanggal_mulai'] != NULL) {
		$start_date = date_format($date, 'd F Y h:i');
	} else{
		$start_date = '';
	}
?>	
<style type="text/css">
	.form-group{
		margin-bottom: 10px;
	}
</style>
<div class="portlet light">
    <div class="portlet-body form">
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Information", $this->session->userdata("language"))?></span>
			</div>
			<div class="tools">
				<a href="javascript:;" class="collapse">
				</a>	
			</div>
		</div>
		<div class="portlet-body form">	
			<div class="form-wizard">
				<div class="form-body">
					<div class="form-group hidden">
						<label class="control-label col-md-3">ID</label>
						<div class="col-md-1">
							<input class="form-control" id="id" name="id" value="<?=$pk_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Stock Opname Number", $this->session->userdata("language"))?> :</label>
						<div class="col-md-4">
							<label class="control-label"><?=$form_data['no_stok_opname']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Stock Opname By", $this->session->userdata("language"))?> :</label>
						<div class="col-md-4">
							<label class="control-label"><?=$form_data_people['nama']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Start Date", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$start_date?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Warehouse", $this->session->userdata("language"))?> :</label>
						<div class="col-md-4">
							<label class="control-label"><?=$form_data_warehouse['nama']?></label>
						</div>
					</div>
					
					<div class="row">
					    <div class="col-md-12">
					        <div class="portlet">
								<div class="form-group"></div>
					            <div class="portlet-title">
					                <div class="caption">
					                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Stock Opname", $this->session->userdata("language"))?></span>
					                </div>
					                
					            </div>

					            <div class="portlet-body">
					            	<div class="form-body">
						                <table class="table table-striped table-bordered table-hover table-condensed" id="table_stock_opname">
						                    <thead>
						                        <tr role="row" class="heading">
						                            <th scope="col" width="25%"><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></div></th>
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
				<div class="form-actions fluid">	
					<div class="col-md-12 text-right">
						<a class="btn default" href="javascript:history.go(-1)"><?=translate("Back", $this->session->userdata("language"))?></a>
						<a class="btn green-haze" href="<?=base_url()?>apotik/stock_opname/input_result/<?=$pk_value.'/'.$warehouse_id.'/'.$warehouse_people_id?>"><?=translate("Input Result", $this->session->userdata("language"))?></a>
					</div>			
				</div>
			</div>			
		</div>		
	</div>
		</div>		
	</div>
<?=form_close();?>

