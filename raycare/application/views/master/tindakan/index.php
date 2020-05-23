<form id="satu" name="satu" class="horizontal" autocomplete="off">
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Data Tindakan", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
	            <a href="<?=base_url()?>master/tindakan/add" class="btn btn-circle btn-primary">
	                <i class="fa fa-plus"></i>
	                <span class="hidden-480">
	                     <?=translate("Tambah", $this->session->userdata("language"))?>
	                </span>
	            </a>
	        </div>
		</div>
		<div class="portlet-body">
			<table class="table table-striped table-bordered table-hover" id="table_cabang">
				<thead>
					<tr>
						<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
				 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</form>
<div id="popover_item_content2">
    <div class="portlet light">
		<div class="portlet-title" style="display:none">
			<div class="caption">
				<span class="caption-subject font-green-sharp bold uppercase"><?=translate("Poliklinik Tindakan", $this->session->userdata("language"))?></span>
			</div>
		</div>
		<div class="portlet-body form">
			<?php
				$form_attr = array(
				    "id"            => "form_tindakan", 
				    "name"          => "form_tindakan", 
				    "autocomplete"  => "off", 
				    "class"         => "form-horizontal",
				    "role"			=> "form"
			    );
			    $hidden = array(
			        "command"   => "add"
			    );
			    echo form_open("", $form_attr, $hidden);
			?>
			<div class="form-body">
				<div class="form-group" style="display:none">
					<label class="control-label col-md-4"><?=translate("Kode", $this->session->userdata("language"))?> <span class="required"></span>:</label>
					<div class="col-md-2">
						<label class="control-label"><div id="kode2"></div></label>
					</div>
				</div>
				<div class="form-group" style="display:none">
					<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required"></span>:</label>
					<div class="col-md-3">
						<label class="control-label"><div id="nama2"></div></label>
					</div>
				</div>
				<div class="form-group" style="display:none">
					<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> <span class="required"></span>:</label>
					<div class="col-md-4">
						<label class="control-label" style="text-align: left;"><div id="keterangan2"></div></label>
					</div>
				</div>
				<div class="row">
	                <div class="col-md-12">
	                    <div class="portlet">
	                        <div class="portlet-title" style="display:none">
	                            <div class="caption">
	                                <i class=""></i><span class="caption-subject font-green-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
	                            </div>
	                        </div>
	                        <div class="portlet-body">
								<div class="table-responsive">
	                            	<table class="table table-striped table-bordered table-hover" id="table_order_item">
										<thead>
	                                        <tr role="row" class="heading">
	                                        	 
	                                            <th class="text-center"><?=translate('Nama', $this->session->userdata("language"))?></th>
	                                            <th class="text-center"><?=translate('Harga', $this->session->userdata("language"))?></th>
	                                              <th class="text-center"><?=translate('Harga', $this->session->userdata("language"))?></th>
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
			<?=form_close()?>
		</div>
	</div>
</div>

<form id="modalindex" name="modalindex"  role="form" autocomplete="off">
	<div class="modal fade" id="ajax_notes" role="basic" aria-hidden="true" style="display:none">
	    <div class="page-loading page-loading-boxed">
	        <span>
	            &nbsp;&nbsp;Loading...
	        </span>
	    </div>
	    <div class="modal-dialog">
	        <div class="modal-content">
	        	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title caption-subject font-green-sharp bold uppercase"><?=translate('Harga tindakan per poliklinik', $this->session->userdata('language'))?></h4>
	        	</div>
	           	<div class="modal-body">
	    			<div class="row">
						<input type="hidden" id="pk2" name="pk2">
						<input type="hidden" id="flag" name="<?=$flag?>">
	    			</div>
	    			<div class="form-body">
                        <table class="table table-striped table-bordered table-hover" id="table_addperson">
                            <thead>
	                            <tr role="row" class="heading">
	                                <th scope="col" ><div class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></div></th>
	                                <th scope="col" ><div class="text-center"><?=translate("Harga", $this->session->userdata("language"))?></div></th>
	                            </tr>
	                       	</thead>
	                        <tbody>
	                        </tbody>
						</table>    	 
	                </div>
				</div>
				<div class="modal-footer">
				    <?php $msg = translate("Apakah anda yakin akan menyimpan harga ini?",$this->session->userdata("language"));?>
					<div class="form-actions fluid">	
						<div class="col-md-12">
				    		<button type="reset" class="btn default" data-dismiss="modal"><?=translate("OK", $this->session->userdata("language"))?></button>
				    	</div>		
					</div>
				</div>
	        </div>
	    </div>
	</div>
</form>