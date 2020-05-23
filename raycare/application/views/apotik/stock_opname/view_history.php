<?php
	$form_attr = array(
		"id"			=> "form_history", 
		"name"			=> "form_history", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);
	$hidden = array(
		"command"	=> "view"
	);
	$id = array(
		'id'    => 'id',
    	'type'  => 'hidden',
    	'value' => $pk_value
	);
	echo form_open("", $form_attr,$hidden,$id);
	
	$start_date = '';
	$date = date_create($form_data['tanggal_mulai']);
	if ($form_data['tanggal_mulai'] != NULL) {
		$start_date = date_format($date, 'd F Y h:i A');
	}

	$end_date = '';
	$date2 = date_create($form_data['tanggal_selesai']);
	if ($form_data['tanggal_selesai'] != NULL) {
		$end_date = date_format($date2, 'd F Y h:i A');
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
						<label class="control-label col-md-3"><?=translate("Nomor Stok Opnamer", $this->session->userdata("language"))?> :</label>
						<div class="col-md-4">
							<label class="control-label"><?=$form_data['no_stok_opname']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Stok Opname oleh", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$form_data_people['nama']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Tanggal Mulai", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$start_date?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Tanggal Selesai", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$end_date?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Data Input By", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$form_data_user['nama']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Gudang", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$form_data_warehouse['nama']?></label>
							<input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$form_data_warehouse['id']?>">

						</div>
					</div>
					
					<div class="row">
					    <div class="col-md-12">
					        <div class="portlet">
					            <div class="portlet-title">
					                <div class="caption">
					                    <i class=""></i><?=translate("Stock Opname", $this->session->userdata("language"))?>
					                </div>
					                
					            </div>

					            <div class="portlet-body">
					            	<div class="form-body">
						                <table class="table table-striped table-bordered table-hover table-condensed" id="table_stock_opname_history">
						                    <thead>
						                        <tr role="row" class="heading">
						                            <th scope="col" width="17%"><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" width="50%"><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" width="50%"><div class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Jumlah Sistem", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Jumlah Hitung", $this->session->userdata("language"))?></div></th>
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
					</div>			
				</div>
			</div>			
		</div>		
	</div>
</div>
</div>
<?=form_close();?>

