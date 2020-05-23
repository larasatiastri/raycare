<?php
	$form_attr = array(
	    "id"            => "form_grafik_biaya", 
	    "name"          => "form_grafik_biaya", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Grafik Laporan Biaya", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions hidden">
			<a id="cetak_csv" class="btn btn-circle btn-primary" target="_blank" href="<?=base_url()?>laporan/keuangan/biaya/cetak_csv">
				<i class="fa fa-file-excel-o"></i>
				<?=translate("Download CSV", $this->session->userdata("language"))?>
			</a>
			<a href="#" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""></a>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="portlet solid blue-madison">
			<div class="portlet-title">
				<div class="caption">
					<?=translate("Filter", $this->session->userdata("language"))?>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-12"><?=translate("Periode", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="month_year" name="month_year" required  value="<?=date('M Y')?>" readonly >
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject uppercase">
				      <?=translate("Grafik", $this->session->userdata("language"))?>
				    </span>
					<span class="caption-helper" id="label_bulan"></span>
				</div>
				
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-6">
						<div id="chart_3" class="chart">
                		</div>
					</div>
					<div class="col-md-6">
						<div id="chart_2" class="chart">
               			</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<?=form_close()?>
