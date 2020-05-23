<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Data Pasien Tindakan Bulan ", $this->session->userdata("language")).date('M Y')?></span>
		</div>
		<div class="actions">
           <div class="actions">
				<a href="<?=base_url()?>laporan/grafik_tindakan_visitasi" class="btn btn-circle btn-default"> <i class="fa fa-chevron-left"></i> 
	           		<span class="hidden-480">
	                     <?=translate("Kembali", $this->session->userdata("language"))?>
	                </span>
	            </a>
	            <a href="<?=base_url()?>laporan/grafik_tindakan_visitasi/csv/<?=$start?>/<?=$penjamin_id?>/<?=$tipe?>" class="btn btn-circle btn-default"> <i class="fa fa-print"></i> 
	           		<span class="hidden-480">
	                     <?=translate("Cetak", $this->session->userdata("language"))?>
	                </span>
	            </a>
			</div>
        </div>
	</div>
	<div class="portlet-body">
	<input class="form-control" type="hidden" id="start" name="start" value="<?=$start?>"> </input>
	<input class="form-control" type="hidden" id="penjamin_id" name="penjamin_id" value="<?=$penjamin_id?>"> </input>
	<input class="form-control" type="hidden" id="tipe" name="tipe" value="<?=$tipe?>"> </input>
		<table class="table table-striped table-bordered table-hover" id="table_data_pasien">
			<thead>
				<tr>
					<th class="text-center"><?=translate("No", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Nama Lengkap", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Alamat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tempat, Tgl Lahir", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal Daftar", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
