<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-users font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("User Level", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <!-- <a href="<?=base_url()?>master/user_level/add" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a> -->
            <?php
            	//tambahkan data ke tabel fitur_tombol. Field page="user_level", button="add"
            	$user_level_id = $this->session->userdata('level_id');
            	
            	$data = '<a href="'.base_url().'master/user_level/add" class="btn green"> <i class="fa fa-plus"></i> <span class="hidden-480"> '.translate("Tambah", $this->session->userdata("language")).'</span> </a>';
            	echo restriction_button($data,$user_level_id,'master_user_level','add');
            ?>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover" id="table_user_level">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
