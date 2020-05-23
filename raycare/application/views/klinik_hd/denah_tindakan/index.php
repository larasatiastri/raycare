<?php
	$shift_aktif = 1;
	$shift_next = 2;
    if(date('H:i:s') > '06:30:00' &&  date('H:i:s') <= '11:30:00'){
        $shift_aktif = 1;
        $shift_next = 2;

    }if(date('H:i:s') > '11:30:01' &&  date('H:i:s') <= '18:30:00'){
        $shift_aktif = 2;
        $shift_next = 3;
    }if(date('H:i:s') > '18:30:01' &&  date('H:i:s') <= '23:30:00'){
        $shift_aktif = 3;
        $shift_next = 3;
    }
?>
<div class="portlet light">
	<div class="portlet-title tabbable-line">
		<div class="caption" >
			<?=translate("Denah Tindakan", $this->session->userdata("language"))?>
		</div>
		<div class="actions">
			<a title="Refresh" id="refresh" class="btn btn-circle  btn-icon-only btn-default"><i class="fa fa-undo"></i></a>
		</div>
		<ul class="nav nav-tabs">
			<li class="active tab">
				<a href="#lantai1" id="lantai_1"  data-toggle="tab">
				<?=translate('Lantai 1', $this->session->userdata('language'))?> </a>
			</li>
			<li class="tab">
				<a href="#lantai2" id="lantai_2" data-toggle="tab">
				<?=translate('Lantai 2', $this->session->userdata('language'))?> </a>

			</li>
			<li class="tab">
				<a href="#lantai3" id="lantai_3" data-toggle="tab">
				<?=translate('Lantai 3', $this->session->userdata('language'))?> </a>
			</li>

		</ul>
		
	</div>


	<input type="hidden" class="form-control" id="shift_aktif" name="shift_aktif" value="<?=$shift_aktif?>">
	<input type="hidden" class="form-control" id="shift" name="shift" value="<?=$shift_aktif?>">
	
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

</div>

<div class="modal fade" id="ajax_notes" role="basic" aria-hidden="true">
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
