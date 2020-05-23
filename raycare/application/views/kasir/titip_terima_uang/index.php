<?php
	$form_attr = array(
		"id"			=> "form_titip_terima_uang", 
		"name"			=> "form_titip_terima_uang", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
	);
	$hidden = array(
		"command"			=> "add_deposit", 
	);
	echo form_open(base_url()."kasir/titip_terima_uang", $form_attr, $hidden);
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Titip Uang", $this->session->userdata("language"))?></span>
                </div>
                <div class="actions">
                	&nbsp;
                </div>
                <div class="actions">
                    <a href="<?=base_url()?>kasir/titip_terima_uang/add" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("Tambah", $this->session->userdata("language"))?>
                        </span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
            <div class="form-group"></div>
            <div class="form-group">
            	<label class="control-label col-md-1">
            		Bulan
            	</label>
            	<div class="col-md-2">
    				<div class="input-group input-medium-date date date-picker" >
    					<input  class="form-control" id="date" name="date" readonly value="<?=date('M Y')?>">
						<span class="input-group-btn">
							<button type="button" class="btn default date-set">
								<i class="fa fa-calendar"></i>
							</button>
						</span>
					</div>
				</div>
            </div>
            <div class="caption">
            	<label class="control-label"><h4></h4></label>
            </div>
            	<table class="table table-striped table-bordered table-hover" id="table_titip_uang">
					<thead>
						<tr role="row" class="heading">
                            <th scope="col" width="8%"><div class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Diberikan Oleh", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Diterima Oleh", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Rupiah", $this->session->userdata("language"))?></div></th>		                            
                        </tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>		            		
            </div>
		</div>

		<div class="portlet light">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Terima Uang", $this->session->userdata("language"))?></span>
                </div>
                <div class="actions">
                	&nbsp;
                </div>
                <div class="actions">
                    <a href="<?=base_url()?>kasir/titip_terima_uang/add_terima_uang" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("Tambah", $this->session->userdata("language"))?>
                        </span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
            <div class="form-group"></div>
            <div class="form-group">
            	<label class="control-label col-md-1">
            		Bulan
            	</label>
            	<div class="col-md-2">
					<div class="input-group input-medium-date date date-picker" >
        					<input  class="form-control" id="date_terima" name="date_terima" readonly value="<?=date('M Y')?>">
							<span class="input-group-btn">
								<button type="button" class="btn default date-set">
									<i class="fa fa-calendar"></i>
								</button>
							</span>
						</div>
				</div>
            </div>
            <div class="caption">
            	<label class="control-label"><h4></h4></label>
            </div>
            	<table class="table table-striped table-bordered table-hover" id="table_terima_uang">
					<thead>
						<tr role="row" class="heading">
                            <th scope="col" width="8%"><div class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Diberikan Oleh", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Diterima Oleh", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Rupiah", $this->session->userdata("language"))?></div></th>		                            
                        </tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>		            		
            </div>
		</div>
	
		<div class="portlet light">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History", $this->session->userdata("language"))?></span>
                </div>
                <div class="actions">
                	&nbsp;
                </div>
            </div>
            <div class="portlet-body">
		        <div class="form-group"></div>
		        <div class="caption">
		        	<label class="control-label"><h4></h4></label>
		        </div>
		        	<table class="table table-striped table-bordered table-hover" id="table_history">
						<thead>
							<tr role="row" class="heading">
		                        <th scope="col" width="8%"><div class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></div></th>
		                        <th scope="col" ><div class="text-center"><?=translate("Diberikan Oleh", $this->session->userdata("language"))?></div></th>
	                            <th scope="col" ><div class="text-center"><?=translate("Diterima Oleh", $this->session->userdata("language"))?></div></th>
	                            <th scope="col" ><div class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></div></th>
	                            <th scope="col" ><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
	                            <th scope="col" ><div class="text-center"><?=translate("Rupiah", $this->session->userdata("language"))?></div></th>			                            
		                    </tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>		            		
            </div>
		</div>
	</div>
</div>