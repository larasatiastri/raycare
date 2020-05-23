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

    echo form_open(base_url()."pembelian/outstanding_po/save", $form_attr, $hidden);

?>
<div class="tabbable-custom nav-justified">
	<ul class="nav nav-tabs nav-justified">
		<li class="active" >
			<a href="#tab_os_po" data-toggle="tab">
			Outstanding Pembelian Umum</a>
		</li>
		<li>
			<a href="#tab_pembelian_kasbon" data-toggle="tab">
			Outstanding PO Proses Kasbon </a>
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
						<i class="fa fa-list font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Outstanding Pembelian Umum", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<table class="table table-striped table-hover" id="table_outstanding_po">
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
							<?php $msg_kasbon = translate("Apakah anda yakin akan membuat kasbon untuk item yang terpilih ini?",$this->session->userdata("language"));?>
							<a id="button_kasbon" class="btn btn-info" data-confirm="<?=$msg_kasbon?>">
								<i class="fa fa-dollar"></i>
								<?=translate("Buat Kasbon", $this->session->userdata("language"))?>
							</a>
							<a id="button_tolak" class="btn btn-danger" href="<?=base_url()?>pembelian/outstanding_po/tolak_permintaan" data-target="#modal_tolak_permintaan" data-toggle="modal">
								<i class="fa fa-times"></i>
								<?=translate("Tolak Permintaan", $this->session->userdata("language"))?>
							</a>
							<a id="confirm_save" class="btn green" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
								<i class="fa fa-shopping-cart"></i>
								<?=translate("Lanjut ke PO", $this->session->userdata("language"))?>
							</a>
				            <button type="submit" id="save" class="btn default hidden" >			            	
				            	<?=translate("Simpan", $this->session->userdata("language"))?>
				            </button>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div class="tab-pane" id="tab_pembelian_kasbon">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("OS PO Umum Proses Kasbon", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body">
				<?php
					foreach ($data_kasbon as $key => $kasbon) {
				?>
					<div class="portlet box blue-sharp">
						<div class="portlet-title" style="margin-bottom: 0px !important;">
							<div class="caption">
								<?=$kasbon['nomor_permintaan']?>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="form-body">
							<input type="hidden" id="permintaan_biaya_id_<?=$key?>" name="permintaan_biaya_id_<?=$key?>" value="<?=$kasbon['permintaan_biaya_id']?>">
								<table class="table table-striped table-hover table_os_kasbon" id="table_outstanding_po_proses_kasbon_<?=$key?>">
									<thead>
										<tr>
											<th class="text-center" width="1%"><?=translate("ID", $this->session->userdata("language"))?> </th>
											<th class="text-center" ><?=translate("Item", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="5%"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="30%"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="1%"><?=translate("Pilih", $this->session->userdata("language"))?> </th>
										</tr>
									</thead>
									<tbody>
									
									</tbody>
								</table>
								<div class="form-actions right">
									<?php $msg_po_kasbon = translate("Apakah anda yakin akan membuat PO untuk item yang terpilih ini?",$this->session->userdata("language"));?>
									
									<a id="confirm_save_po_kasbon" class="btn green" href="#" data-confirm="<?=$msg_po_kasbon?>" data-toggle="modal">
										<i class="fa fa-shopping-cart"></i>
										<?=translate("Lanjut ke PO", $this->session->userdata("language"))?>
									</a>
						            <button type="submit" id="save" class="btn default hidden" >			            	
						            	<?=translate("Simpan", $this->session->userdata("language"))?>
						            </button>
								</div>
							</div>
						</div>
					</div>
				<?php
					}
				?>
					
					
				</div>
			</div>
		</div>
		<div class="tab-pane" id="tab_pembelian_proses">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Outstanding Pembelian Umum Diproses", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<table class="table table-striped table-hover" id="table_outstanding_po_proses">
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

<div class="modal fade" id="modal_tolak_permintaan" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        </div>
    </div>
</div>
