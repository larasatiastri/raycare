<div class="portlet light">
	<!-- TAGIHAN PAKET -->
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tagihan Paket", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-primary" id="modalpacket" data-toggle="modal" data-target="#modal_paket"  href="<?=base_url()?>klinik_hd/transaksi_dokter/modal_paket/0/0/0/1/0"><i class="fa fa-plus"></i> <?=translate("Tambah Paket", $this->session->userdata("language"))?></a>
			 
		</div>
	</div>
	<a class="btn blue-chambray hidden" id="reloadpakettagihan"><i class="fa fa-edit"></i> <?=translate('Edit', $this->session->userdata('language'))?></a>
	<a class="btn blue-chambray hidden" id="reloadpakettagihan2"><i class="fa fa-edit"></i> <?=translate('Edit', $this->session->userdata('language'))?></a>
	<div class="portlet-body">
		<div class="form-body">
			<table class="table table-striped table-hover table-bordered" id="table_tagihan_paket1">
				<thead>
					<tr class="heading">
						<th class="text-center"><?=translate('Tipe Paket', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Nama Paket', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-center">
						<td>Obat</td>
						<td>Paket Re-Use - Cimino</td>
						<td>
							<a title="Lihat" class="btn grey-cascade"><i class="fa fa-search"></i></a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
  
	</div>
	<?=form_close()?>
		<!-- END FORM-->
</div>