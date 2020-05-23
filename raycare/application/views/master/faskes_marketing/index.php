	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-shield font-blue-sharp"></i>
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Faskes Marketing", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
	             <?php
	            	//tambahkan data ke tabel fitur_tombol. Field page="master_user_level", button="add"
	            	$user_level_id = $this->session->userdata('level_id');
	            	
	            	$data = '<a href="'.base_url().'master/faskes_marketing/add" class="btn btn-circle btn-default"> <i class="fa fa-plus"></i> <span class="hidden-480"> '.translate("Tambah", $this->session->userdata("language")).' </span> </a>';
	            	echo restriction_button($data, $user_level_id, 'master_faskes_marketing', 'add');
	            ?>
	        </div>
		</div>
		<div class="portlet-body">
			<div id="thead-filter-template" class="hidden"><?=htmlentities($td_filter)?></div>
			<table class="table table-striped table-bordered table-hover" id="table_faskes_marketing">
				<thead>
					<tr role="row">
						<th class="text-center"><?=translate("Kode Faskes", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Nama Faskes", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Regional", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Nama Marketing", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
 