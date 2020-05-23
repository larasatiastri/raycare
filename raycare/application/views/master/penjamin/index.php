<?
$td_filter = '<tr role="row" class="filter hidden"> <td><div class="text-center"></div></td> <td><div class="text-center"></div></td>   <td><div class="text-center"> <select name="sales_status" id="sales_status" class="form-control form-filter input-sm"> <option value="3">'.translate("Semua", $this->session->userdata("language")).'</option> <option value="0">'.translate("Aktif", $this->session->userdata("language")).'</option><option value="1">'.translate("Tidak Aktif", $this->session->userdata("language")).'</option></select> </div> </td> <td><div class="text-center"></div></td> </tr>'
?>
<form id="satu" name="satu" class="horizontal" autocomplete="off">
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-shield font-blue-sharp"></i>
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penjamin", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
	             <?php
	            	//tambahkan data ke tabel fitur_tombol. Field page="master_user_level", button="add"
	            	$user_level_id = $this->session->userdata('level_id');
	            	
	            	$data = '<a href="'.base_url().'master/penjamin/add" class="btn btn-circle btn-default"> <i class="fa fa-plus"></i> <span class="hidden-480"> '.translate("Tambah", $this->session->userdata("language")).' </span> </a>';
	            	echo restriction_button($data, $user_level_id, 'master_penjamin', 'add');
	            ?>
	        </div>
		</div>
		<div class="portlet-body">
			<div id="thead-filter-template" class="hidden"><?=htmlentities($td_filter)?></div>
			<table class="table table-striped table-bordered table-hover" id="table_cabang">
				<thead>
					<tr role="row">
						<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</form>
 