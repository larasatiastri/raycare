<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-blue-sharp"></i> -->
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Data paket", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            
            <?php
    			$user_level_id = $this->session->userdata('level_id');
        		
        		$data = '<a href="'.base_url().'master/paket/add" class="btn btn-primary"> <i class="fa fa-plus"></i> <span class="hidden-480"> '.translate("Tambah", $this->session->userdata("language")).' </span> </a>';
        		echo restriction_button($data, $user_level_id, 'master_paket', 'add');
            ?>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_paket">
		<thead>
		<tr class="heading">
			<th class="text-center"><?=translate("Kode paket", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Nama paket", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div>
</div>