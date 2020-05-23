<div id="popover_item_content" class="row">
    <div class="col-md-12">
    	<div class="portlet light bordered">
        	<div class="portlet-title tabbable-line">
		    	<ul class="nav nav-tabs">
					<li class="active">
						<a href="#terdaftar" data-toggle="tab">
						<?=translate('Item Yang Terdaftar', $this->session->userdata('language'))?> </a>
					</li>
					<li>
						<a href="#tidak_terdaftar" data-toggle="tab">
						<?=translate('Item Yang Tidak Terdaftar', $this->session->userdata('language'))?> </a>
					</li>
				</ul>
			</div>
			<div class="tab-content">
				<div class="tab-pane active" id="terdaftar">
					<div class="portlet">
						<div class="portlet-body">
					        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item">
					            <thead>
					                <tr role="row" class="heading">
					                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
					                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
					                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
					                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
					                </tr>
					            </thead>
					            <tbody>
					            </tbody>
					        </table>
					    </div>
					</div>
				</div>
				<div class="tab-pane" id="tidak_terdaftar">
					<div class="portlet">
						<div class="portlet-body">
					        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item_tidak_terdaftar">
					            <thead>
					                <tr role="row" class="heading">
					                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
					                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
					                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
					                </tr>
					            </thead>
					            <tbody>
					            </tbody>
					        </table>
					    </div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>