<?php

	$form_attr = array(
	    "id"            => "form_os_po", 
	    "name"          => "form_os_po", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."pembelian/outstanding_po_alkes/save", $form_attr, $hidden);

?>
<div class="tabbable-custom nav-justified">
	<ul class="nav nav-tabs nav-justified">
		<li class="active" >
			<a href="#tab_os_po" data-toggle="tab">
			Outstanding Pembelian Alkes</a>
		</li>
		<li>
			<a href="#tab_pembelian_proses" data-toggle="tab">
			Outstanding Pembelian Diproses </a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_os_po">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Outstanding Pembelian Alkes", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<table class="table table-striped table-bordered table-hover" id="table_outstanding_po_alkes">
							<thead>
								<tr>
									<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
									<th class="text-center" ><?=translate("Item", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="15%"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="40%"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="1%"><?=translate("Pilih", $this->session->userdata("language"))?> </th>
								</tr>
							</thead>
							<tbody>
							
							</tbody>
						</table>
						<div class="form-actions right">
							<?php $msg = translate("Apakah anda yakin akan membuat PO untuk item yang terpilih ini?",$this->session->userdata("language"));?>
							<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
								<i class="fa fa-check"></i>
								<?=translate("Submit", $this->session->userdata("language"))?>
							</a>
				            <button type="submit" id="save" class="btn default hidden" >			            	
				            	<?=translate("Simpan", $this->session->userdata("language"))?>
				            </button>
						</div>
					</div>	
				</div>
			</div>
		</div>
		<div class="tab-pane" id="tab_pembelian_proses">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Outstanding Pembelian Alkes Diproses", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<table class="table table-striped table-bordered table-hover" id="table_outstanding_po_alkes_proses">
							<thead>
								<tr>
									<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="10%"><?=translate("Item", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="5%"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
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
