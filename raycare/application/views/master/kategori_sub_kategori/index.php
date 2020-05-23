<div class="portlet light">
	
	<!-- KATEGORI -->
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Kategori', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-primary" href="<?=base_url()?>master/kategori_sub_kategori/tambah">
				<i class="fa fa-plus"></i>
				<?=translate('Tambah', $this->session->userdata('language'))?>
			</a>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-hover table-bordered table-striped" id="table_kategori">
			<thead>
				<tr class="heading">
					<th class="text-center" width="15%"><?=translate('Kode', $this->session->userdata('language'))?></th>
					<th class="text-center" width="20%"><?=translate('Nama', $this->session->userdata('language'))?></th>
					<th class="text-center" width="50%"><?=translate('Keterangan', $this->session->userdata('language'))?></th>
					<th class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
	
	<!-- SUB KATEGORI -->
	<div class="portlet-title" style="margin-top:20px;">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Sub Kategori', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-primary" href="<?=base_url()?>master/kategori_sub_kategori/tambah_sub">
				<i class="fa fa-plus"></i>
				<?=translate('Tambah', $this->session->userdata('language'))?>
			</a>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-hover table-bordered table-striped" id="table_sub_kategori">
			<thead>
				<tr class="heading">
					<th class="text-center" width="10%"><?=translate('Kode', $this->session->userdata('language'))?></th>
					<th class="text-center" width="20%"><?=translate('Nama', $this->session->userdata('language'))?></th>
					<th class="text-center" width="15%"><?=translate('Kategori', $this->session->userdata('language'))?></th>
					<th class="text-center" width="10%"><?=translate('Tipe', $this->session->userdata('language'))?></th>
					<th class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>