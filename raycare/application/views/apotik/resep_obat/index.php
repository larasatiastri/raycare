<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-list font-blue-sharp"> </i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Resep Obat & Box Paket HD", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-4">
				<table class="table table-striped table-bordered table-hover" id="table_box_paket">
					<thead>
						<tr class="heading">
							<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Bed", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?></th>
							<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
						</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
			<div class="col-md-8">
				<table class="table table-striped table-bordered table-hover" id="table_resep_obat">
					<thead>
						<tr class="heading">
							<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("No. Resep", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?></th>
							<th class="text-center" style="width : 100px !important;"><?=translate("Item", $this->session->userdata("language"))?></th>
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

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Item Dibatalkan", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>apotik/resep_obat/history" class="btn btn-default btn-circle">
                <i class="fa fa-history"></i>
                <span class="hidden-480">
                     <?=translate("History", $this->session->userdata("language"))?>
                </span>
            </a>  
    	</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover table-bordered" id="table_item_batal">
			<thead>
				<tr>
                    <th class="text-center"><?=translate('Pasien', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Kode Item', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Batch Number', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Expire Date', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Perawat', $this->session->userdata('language'))?></th>
                    <th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
                </tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>
<div class="modal fade bs-modal-lg" id="popup_modal_box_paket" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">
       </div>
   </div>
</div>

<div class="modal fade bs-modal-sm" id="popup_modal_verif" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">
       </div>
   </div>
</div>