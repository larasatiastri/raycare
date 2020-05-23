<?php
	
	$form_attr = array(
	    "id"            => "form_index", 
	    "name"          => "form_index", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );

?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-money font-blue-sharp"></i> 
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("PENGAJUAN PEMBAYARAN KASBON", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="<?=base_url()?>keuangan/pengajuan_pembayaran_kasbon/history">
				<i class="fa fa-history"></i> 
				<?=translate('History', $this->session->userdata('language'))?>
			</a>
			<?php
            	//tambahkan data ke tabel fitur_tombol. Field page="user_level", button="add"
            	$user_level_id = $this->session->userdata('level_id');
            	
            	$data = '<a class="btn btn-default btn-circle" href="'.base_url().'keuangan/pengajuan_pembayaran_kasbon/add"><i class="fa fa-plus"></i> '.translate('Tambah', $this->session->userdata('language')).'</a>';
            	echo restriction_button($data,$user_level_id,'pengajuan_pembayaran_kasbon','add');
            ?>
			
		</div>
	</div>
	
	<div class="portlet-body">

		<table class="table table-striped table-bordered table-hover" id="table_pengajuan_pembayaran_kasbon">
			<thead>
			<tr>
				<th class="text-center" width="10%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Subjek", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No. Cek", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Bank", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
			</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
</div>