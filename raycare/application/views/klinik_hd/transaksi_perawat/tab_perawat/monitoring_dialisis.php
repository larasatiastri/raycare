<div class="portlet light">

	<!-- MONITOR DIALISIS -->
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Monitoring Dialisis", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<?php
    			$user_level_id = $this->session->userdata('level_id');
        		
        		$data = '<a class="btn btn-circle btn-default" data-toggle="modal" href="'.base_url().'klinik_hd/transaksi_perawat/add_monitoring/'.$form_tindakan['id'].'/'.$form_assesment[0]['id'].'" data-target="#modal_dialisis_add"><i class="fa fa-plus"></i> '.translate('Tambah', $this->session->userdata('language')).'</a>';
        		echo restriction_button($data, $user_level_id, 'klinik_hd_transaksi_perawat', 'add_dialisis');
            ?>
			
		</div>
		<div class="actions">
			<a class="btn btn-primary hidden" id="refresh_table_observasi"><i class="fa fa-plus"></i> <?=translate('refresh', $this->session->userdata('language'))?></a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<table class="table table-striped table-hover table-bordered" id="table_monitoring">
				<thead>
					<tr>
						<th class="text-center"><?=translate('Waktu', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('BP', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('UFG', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('UFR', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('UFV', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('QB', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('TMP', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('VP', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('AP', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Cond', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Temperature', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Diinput Oleh', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Keterangan', $this->session->userdata('language'))?></th>
						<th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- EDIT MONITORING DIALISIS -->
<div id="modal_dialisis" class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal">
		<div class="modal-dialog">
			<div class="modal-content">
				
			</div>
		</div>
	</form>
</div>

<!-- TAMBAH  MONITORING DIALISIS -->
<div id="modal_dialisis_add" class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal">
		<div class="modal-dialog">
			<div class="modal-content">
				
			</div>
		</div>
	</form>
</div>

