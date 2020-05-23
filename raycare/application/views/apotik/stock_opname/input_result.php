<?php
	$form_attr = array(
		"id"			=> "form_input_result", 
		"name"			=> "form_input_result", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
	
	);
	$hidden = array(
		"command"	=> "input"
	);
	$id = array(
		'id'    => 'id',
    	'type'  => 'hidden',
    	'value' => $pk_value
	);
	echo form_open(base_url()."apotik/stock_opname/save", $form_attr,$hidden, $id);

	$date = date_create($form_data['created_date']);
	$tgl_cetak = date_format($date, 'd F Y h:i A');

	// $records_people = $form_data_people->result_array();
 //    $records_warehouse = $form_data_warehouse->result_array();

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
			</div><input type="hidden" id="pk" name="pk" value="<?=$wareid?>">
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
						<label class="control-label col-md-3"><?=translate("Stock Opname Oleh", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$form_data_people['nama']?></label>
							<input type="hidden" name="warehouse_people_id" value="<?=$form_data['gudang_orang_id']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Tanggal Cetak", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$tgl_cetak?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Tanggal Mulai", $this->session->userdata("language"))?> :</label>
						<div class="col-md-3">
							<div class="input-group input-medium date " id="tgl_mulai">
								<input type="text" class="form-control" id="start_date" name="start_date" readonly>
								<span class="input-group-btn">
									<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Tanggal Selesai", $this->session->userdata("language"))?> :</label>
						<div class="col-md-3">
							<div class="input-group input-medium date ">
								<input class="form-control " type="text" id="end_date" name="end_date" readonly>
								<span class="input-group-btn">
									<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Gudang", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$form_data_warehouse['nama']?></label>
							<input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$form_data['gudang_id']?>">
						</div>
					</div>

					<div class="form-group"></div>
					
					<div class="row">
					    <div class="col-md-12">
					        <div class="portlet">
					            <div class="portlet-title">
					                <div class="caption">
					                    <i class=""></i><?=translate("Stock Opname Item", $this->session->userdata("language"))?>
					                </div> 
					            </div>

					            <div class="portlet-body">
					            	<div class="form-body">
						                <table class="table table-striped table-bordered table-hover table-condensed" id="table_input_stock_opname">
						                    <thead>
						                        <tr role="row" class="heading">
						                            <th scope="col" ><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" width="60%"><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Jumlah Hitung", $this->session->userdata("language"))?></div></th>
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
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate('Catatan', $this->session->userdata('language'))?> :</label>
						<div class="col-md-7">
							<textarea class="form-control" rows="4" name="note"></textarea>
						</div>
					</div>
				</div>
				<?php 
	            	$confirm_save       = translate('Are you sure to submit and readjust quantity?',$this->session->userdata('language'));
	            ?>
	            <div class="form-actions fluid">	
					<div class="col-md-12 text-right">
				        <a class="btn default" href="javascript:history.go(-1)"><?=translate('Back', $this->session->userdata('language'))?></a>
						<a class="btn green-haze" id="confirm_save" data-tonggle="modal" data-confirm="<?=$confirm_save?>"><?=translate('Submit', $this->session->userdata('language'))?></a>
				        <button type="submit" id="save" class="btn default hidden" >Save</button>
					</div>			
				</div>
			</div>			
		</div>		
	</div>
	</div>
</div>
<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" style="width:1060px;" >
       <div class="modal-content">

       </div>
   </div>
</div>
<?=form_close();?>
 

