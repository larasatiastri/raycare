<div class="portlet light"> <!-- begin of class="portlet light" supplier -->
	<div class="portlet-title"> <!-- begin of class="portlet-title" supplier -->
		<div class="caption">
			<i class="icon-user-follow font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Supplier", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions"> <!-- begin of class="actions" supplier -->
            <?php
            	//tambahkan data ke tabel fitur_tombol. Field page="user_level", button="add"
            	$user_level_id = $this->session->userdata('level_id');
            	
            	$data = '<a href="'.base_url().'master/supplier/add" class="btn btn-circle btn-default"> <i class="fa fa-plus"></i> <span class="hidden-480"> '.translate("Tambah", $this->session->userdata("language")).' </span> </a>';
            	echo restriction_button($data, $user_level_id, 'master_supplier','add');
            ?>

            <a class="btn btn-danger btn-circle" id="btn_grab">
                <i class="fa fa-recycle"></i> 
                <?=translate('Grab Item', $this->session->userdata('language'))?>
            </a>
        </div> <!-- end of class="actions" supplier -->
	</div> <!-- end of class="portlet-title" supplier -->
	<div class="portlet-body"> <!-- begin of class="portlet-body" supplier -->
		<table class="table table-striped table-bordered table-hover" id="table_supplier">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Rating", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Kontak Person", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div> <!-- end of class="portlet-body" supplier -->
</div> <!-- end of class="portlet light" supplier -->
