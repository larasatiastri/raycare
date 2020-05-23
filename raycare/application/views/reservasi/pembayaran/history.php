<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"> <?=translate("History Pembayaran", $this->session->userdata("language"))?> </span>
		</div>
		<div class="actions">
				<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
					<?=translate("Kembali", $this->session->userdata("language"))?>
				</a>
			</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<table class="table table-hover table-bordered table-striped" id="table_history_pembayaran">
				<thead>
					<tr>
						<th class="text-center" width="20%"><?=translate('No. Pembayaran', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Pasien', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Kasir', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Tipe', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Total', $this->session->userdata('language'))?></th>
						<th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>

		</div>
	</div>
</div>