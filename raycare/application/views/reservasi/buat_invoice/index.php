	<div class="note note-danger note-bordered">
		<p>
			 NOTE: Mulai tanggal 2 April 2018, Heparin tidak lagi di cantumkan kedalam invoice, Harga paket hemodialisa menjadi Rp. 1.050.000(Single) dan Rp. 850.000(Reuse) untuk pengguna BPJS.
		</p>
	</div>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-file font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Invoice", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>reservasi/buat_invoice/add" class="btn btn-circle btn-default">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">

		<table class="table table-striped table-hover" id="table_invoice">
			<thead>
				<tr>
					<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Waktu", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No. Invoice", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jenis Invoice", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Resepsionis", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
				
			</tbody>
		</table>

	</div>
</div>
