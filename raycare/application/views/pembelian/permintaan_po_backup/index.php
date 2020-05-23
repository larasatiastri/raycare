<?php
	$td_filter = '<tr role="row" class="filter">
					<td><div class="text-center"></div></td>
					<td><div class="text-center"></div></td>
					<td><div class="text-center"></div></td>
					<td><div class="text-center"></div></td>
					<td><div class="text-center"></div></td>
					<td><div class="text-center">
							<select name="pembelian_status" id="pembelian_status" class="form-control form-filter input-sx"> 
								<option value="">'. translate("Semua", $this->session->userdata("language")).'</option> <option value="1">'. translate("Ditolak", $this->session->userdata("language")).'</option> <option value="2">'. translate("Diproses", $this->session->userdata("language")).'</option>
							</select>
						</div>
					</td>';

?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cube font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Permintaan Barang & Jasa", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a href="<?=base_url()?>pembelian/permintaan_po/history" data-toggle="modal" class="btn btn-circle btn-default" hidden>
                <i class="fa fa-history"></i>
                <span class="hidden-480">
                     <?=translate("History", $this->session->userdata("language"))?>
                </span>
            </a>
            <a href="<?=base_url()?>pembelian/permintaan_po/add" data-toggle="modal" class="btn btn-circle btn-default">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover" id="table_permintaan_pembelian">
			<thead>
				<tr>
					<th class="text-center" width="5%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="8%"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="12%"><?=translate("No. Permintaan", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="1%"><?=translate("Item", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="10%"><?=translate("Posisi", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>
			<tbody>
			

			</tbody>
		</table>
	</div>
</div>





