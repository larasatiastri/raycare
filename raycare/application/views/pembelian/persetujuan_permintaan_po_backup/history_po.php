<?php
	$td_filter = '<tr role="row" class="filter"><td><div class="text-center"></div></td> <td><div class="text-center"></div></td>  <td><div class="text-center"></div></td><td><div class="text-center"></div></td> <td><div class="text-center"></div></td> <td><div class="text-center"> <select name="pembelian_status" id="pembelian_status" class="form-control form-filter input-sx"> <option value="">'. translate("Semua", $this->session->userdata("language")).'</option> <option value="1">'. translate("Ditolak", $this->session->userdata("language")).'</option> <option value="2">'. translate("Diproses", $this->session->userdata("language")).'</option></select></div></td>';

?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-history font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Persetujuan Permintaan Barang", $this->session->userdata("language"))?></span>
		</div>
		<?php
			$back_text = translate('Kembali', $this->session->userdata('language'));
		?>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
	        </a>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_history_persetujuan_permintaan_po">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("User (User Level)", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Item", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>
			<tbody>
			

			</tbody>
		</table>
	</div>
</div>
