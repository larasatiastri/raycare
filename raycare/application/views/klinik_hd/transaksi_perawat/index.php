<div class="portlet light">
	<div class="portlet-title tabbable-line">
		<div class="caption">
			<i class="fa fa-wheelchair font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan HD", $this->session->userdata("language"))?></span>
			<?php
				$open_bed = $this->bed_m->get_by(array('user_edit_id' => $this->session->userdata('user_id'), 'status' => 5), true);

				if(count($open_bed)){
					$open_bed = object_to_array($open_bed);

					echo '<span id="status_open" class="caption-helper font-red bold"><i>'.translate("Anda sedang membuka bed", $this->session->userdata("language")).' '.$open_bed['kode'].'</i></span>';
				}else{
					echo '<span id="status_open" class="caption-helper"><label>'.date('d M Y').'</label></span>';
				}
			?>
		</div>
		<div class="actions" style="margin-left:6px;">
			<a title="Antrian" class="btn btn-info" href="<?=base_url()?>reservasi/antrian_tindakan"><i class="fa fa-list"></i> Antrian</a>
		</div>
		<ul class="nav nav-tabs">
			<li class="active tab">
				<a href="#lantai1" id="lantai_1"  data-toggle="tab">
				<?=translate('Lantai 1', $this->session->userdata('language'))?> <span class='badge badge-warning hidden' id="notifbed" style='width:15px;height:15px'>!</span></a>
			</li>
			<li class="tab">
				<a href="#lantai2" id="lantai_2" data-toggle="tab">
				<?=translate('Lantai 2', $this->session->userdata('language'))?> <span class='badge badge-warning hidden' id="notifbed" style='width:15px;height:15px'>!</span></a>

			</li>
			<li class="tab">
				<a href="#lantai3" id="lantai_3" data-toggle="tab">
				<?=translate('Lantai 3', $this->session->userdata('language'))?> <span class='badge badge-warning hidden' id="notifbed" style='width:15px;height:15px'>!</span></a>
			</li>

		</ul>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			
			<div class="tab-content">

				<div class="tab-pane active" id="lantai1">
					<div class="svg_file_lantai_1">

					
					</div>
				</div>

				<div class="tab-pane " id="lantai2">
					<div class="svg_file_lantai_2">

						
					</div>
				</div>

				<div class="tab-pane " id="lantai3">
					<div class="svg_file_lantai_3">

						
					</div>
				</div>
			</div>
			<div class="row">
					<br>
					<div class="col-md-2 form-group">
						<i class="fa fa-circle" style="color:#45b6af !important;font-size:20px;"></i>
						<label>Kosong</label>
					</div>

					<div class="col-md-2 form-group">
						<i class="fa fa-circle" style="color:#ff8100 !important;font-size:20px;"></i>
						<label>Booking</label>
					</div>
					<div class="col-md-2 form-group">
						<i class="fa fa-circle" style="color:#ff0000 !important;font-size:20px;"></i>
						<label>Terisi</label>
					</div>
					<div class="col-md-2 form-group">
						<i class="fa fa-circle" style="color:#2c3e50 !important;font-size:20px;"></i>
						<label>Sedang diakses</label>
					</div>	
					<div class="col-md-2 form-group">
						<i class="fa fa-circle" style="color:#95a5a6 !important;font-size:20px;"></i>
						<label>Rusak</label>
					</div>	
					<a id="btn_detail" title="<?=translate('Lihat', $this->session->userdata('language'))?>" href="<?=base_url()?>calendar/lihat_detail/" data-toggle="modal" data-target="#modal_detail" class="btn btn-primary hidden"><i class="fa fa-check"></i></a>	
				<!-- END CALENDAR PORTLET-->
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_tolak" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="modal fade" id="modal_pindah" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="modal fade" id="modal_detail" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="modal_notes" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="modal_notes_proses" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="modal_switch" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
