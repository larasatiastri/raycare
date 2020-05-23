<div class="portlet light">
	<div class="portlet-title tabbable-line">
		<div class="caption" id="bed_terpilih">
			<?=translate("Pilih Bed", $this->session->userdata("language"))?>
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


	<input type="hidden" class="form-control" id="bed_id" name="bed_id" value="" required>
	
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
