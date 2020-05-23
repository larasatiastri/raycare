<?php
	$td_filter = '<tr role="row" class="filter"><td><div class="text-center"></div></td> <td><div class="text-center"></div></td>  <td><div class="text-center"></div></td><td><div class="text-center"></div></td> <td><div class="text-center"></div></td> <td><div class="text-center"> <select name="pembelian_status" id="pembelian_status" class="form-control form-filter input-sx"> <option value="">'. translate("Semua", $this->session->userdata("language")).'</option> <option value="1">'. translate("Diproses", $this->session->userdata("language")).'</option> <option value="2">'. translate("Ditolak", $this->session->userdata("language")).'</option></select></div></td>';

	$td_filter2 = '<tr role="row" class="filter"><td><div class="text-center"></div></td> <td><div class="text-center"></div></td>  <td><div class="text-center"></div></td><td><div class="text-center"></div></td> <td><div class="text-center"></div></td> <td><div class="text-center"> <select name="pembelian_baru_status" id="pembelian_baru_status" class="form-control form-filter input-sx"> <option value="">'. translate("Semua", $this->session->userdata("language")).'</option> <option value="10">'. translate("Kadaluarsa", $this->session->userdata("language")).'</option> <option value="8">'. translate("Dihapus", $this->session->userdata("language")).'</option> <option value="7">'. translate("Diterima", $this->session->userdata("language")).'</option> <option value="6">'. translate("Ditolak", $this->session->userdata("language")).'</option></select></div></td><td><div class="text-center"></div></td>';

?>

	
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-file font-blue-sharp"> </i>
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Laporan Pembelian", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
					<a id="load_table"></a>
						<select class="form-control">
							<option value="">--Pilih Bulan--</option>
							<option value="01">Januari</option>
							<option value="02">Februari</option>
							<option value="03">Maret</option>
							<option value="04">April</option>
							<option value="05">Mei</option>
							<option value="06">Juli</option>
							<option value="07">July</option>
							<option value="08">Agustus</option>
							<option value="09">September</option>
							<option value="10">Oktober</option>
							<option value="11">November</option>
							<option value="12">Desember</option>
						</select>
						<select class="form-control">
							<option value="">--Pilih Tahun--</option>
							<option value="2014">2014</option>
							<option value="2015">2015</option>
							<option value="2016">2016</option>
							<option value="2017">2017</option>
							<option value="2018">2018</option>
							<option value="2019">2019</option>
							<option value="2020">2020</option>
							<option value="2021">2021</option>
							<option value="2022">2022</option>
							<option value="2023">2023</option>
							<option value="2024">2024</option>
							<option value="2025">2025</option>
						</select>


					<a href="<?=base_url()?>pembelian/history/export_pembelian/<?=date('m')?>/<?=date('Y')?>" class="btn default">
			                <i class="fa fa-download"></i>
			                <span class="hidden-480">
			                     <?=translate("Export CSV", $this->session->userdata("language"))?>
			                </span>
			            </a>
				</div>
			</div>
			<div id="thead-filter-template2" class="hidden"><?=htmlentities($td_filter2)?></div>

			<table class="table table-striped table-hover" id="table_laporan_pembelian">
				<thead>
					<tr>
						<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("No PO", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="10%"><?=translate("Total", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Dibuat oleh", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
		</div>
	


<div class="modal fade" id="popup_modal" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        </div>
    </div>
</div>
