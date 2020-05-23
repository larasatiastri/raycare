<div class="portlet light">
	 <div class="portlet-title tabbable-line">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Transaksi Sedang Diproses", $this->session->userdata("language"))?></span>
		</div>
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tabtabel" data-toggle="tab">
				Tabel </a>
			</li>
			<li>
				<a href="#tabdenah" data-toggle="tab">
				Denah </a>
			</li>
			<li>
				<a href="#tabvisit" data-toggle="tab">
				History Visit </a>
			</li>			 
		</ul>
		 
	</div>
	<div class="portlet-body">
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tabtabel">
				<div class="portlet-body">
					<table class="table table-condensed table-striped table-hover" id="table_cabang2">
					<thead>
						<tr role="row">
							<th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="12%"><?=translate("No.Transaksi", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="20%"><?=translate("Tempat, Tanggal Lahir", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="27%"><?=translate("Alamat", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="15%"><?=translate("Dokter", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
	 
						</tr>
					</thead>
					<tbody>
						
					</tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane fade" id="tabdenah">
				<?php include('denah_index.php') ?>
			</div>
			<div class="tab-pane fade in" id="tabvisit">
				<div class="portlet-body">
					<table class="table table-condensed table-striped table-hover" id="table_visit">
					<thead>
						<tr role="row">
							<th class="text-center" width="10%"><?=translate("No.Transaksi", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="15%"><?=translate("Dokter", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="5%"><?=translate("Mulai Visit", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="5%"><?=translate("Selesai Visit", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="49%"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
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

<div id="modal_end_visit" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal">
		<div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
		<div class="modal-dialog" id="modaldialogpaket">
			<div class="modal-content" id="modpaket">
				 
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="modal_ketarangan_hapus" role="basic" aria-hidden="true">
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