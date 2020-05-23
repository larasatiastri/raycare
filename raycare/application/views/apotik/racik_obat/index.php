<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="icon-chemistry font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Racik Obat", $this->session->userdata("language"))?></span>
			<span class="caption-helper"><?php echo '<label class="control-label ">'.date('d M Y').'</label>'; ?></span>
		</div>
	</div>
	<div class="portlet-body">

		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#proses" data-toggle="tab">
				<?=translate('Belum di Proses', $this->session->userdata('language'))?> </a>
			</li>
			<li>
				<a href="#history" data-toggle="tab">
				<?=translate('History', $this->session->userdata('language'))?> </a>
			</li>

		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="proses">
				<?php include('tab_racik_obat/tab_proses.php') ?>
			</div>
			<div class="tab-pane" id="history">
				<?php include('tab_racik_obat/tab_history.php') ?>
			</div>
		</div>
	</div>
</div>