<div class="portlet light">
	<!-- ITEM TERSIMPAN -->
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Obat & Alkes Resep Dokter", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-primary reload-table" id="reload-table-resep">Reload</a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<table class="table table-striped table-hover table-bordered" id="table_item_search">
				<thead>
					<tr>
                        <th class="text-center"><?=translate('Kode Item', $this->session->userdata('language'))?></th>
                        <th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
                        <th class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
                        <th class="text-center"><?=translate('Batch Number', $this->session->userdata('language'))?></th>
                        <th class="text-center"><?=translate('Expire Date', $this->session->userdata('language'))?></th>
                        <th class="text-center"><?=translate('Dibuat Oleh', $this->session->userdata('language'))?></th>
                        <th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
                    </tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>
</div>
	<div class="portlet light">
<!-- ITEM TERSIMPAN -->
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Alkes Dalam Box Paket", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
				<a class="btn btn-primary reload-table-box" id="reload-table-box">Reload</a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="form-body">
				<table class="table table-striped table-hover table-bordered" id="table_item_box_paket">
					<thead>
						<tr>
	                        <th class="text-center" width="1%"><?=translate('Kode Box', $this->session->userdata('language'))?></th>
	                        <th class="text-center" width="1%"><?=translate('Kode Item', $this->session->userdata('language'))?></th>
	                        <th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
	                        <th class="text-center" width="1%"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
	                        <th class="text-center"><?=translate('Batch Number', $this->session->userdata('language'))?></th>
	                        <th class="text-center"><?=translate('Expire Date', $this->session->userdata('language'))?></th>
	                        <th class="text-center" width="1%"><?=translate('Status', $this->session->userdata('language'))?></th>
	                        <th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
	                    </tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>



<div class="portlet light">
	<!-- ITEM TELAH DIGUNAKAN -->
	<div class="portlet-title">
		<div class="caption"> 
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Obat & Alkes Telah Digunakan", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions hidden">
			
			<?php
    			$user_level_id = $this->session->userdata('level_id');
        		
        		$data = '<a class="btn btn-circle btn-default" data-backdrop="static" data-keyboard="false" data-target="#modal_item_diluar" data-toggle="modal" href="'.base_url().'klinik_hd/transaksi_perawat/modal_item_diluar_paket/'.$form_tindakan['id'].'/'.$pk_value.'"><i class="fa fa-plus"></i> '.translate("Gunakan Item Diluar Paket", $this->session->userdata("language")).'</a>';
        		echo restriction_button($data, $user_level_id, 'klinik_hd_transaksi_perawat', 'gunakan_item_diluar');
            ?>
			<a class="btn btn-primary reload-table2" id="reload-table-digunakan"><i class="fa fa-undo"></i></a>

		</div>
		
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<table class="table table-striped table-hover table-bordered" id="table_item_telah_digunakan">
				<thead>
					<tr>
						<th class="text-center" width="5%"><?=translate('Waktu Pemberian', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
						<th class="text-center" width="15%"><?=translate('Tipe', $this->session->userdata('language'))?></th>
						<th class="text-center" width="25%"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
						<th class="text-center" width="25%"><?=translate('Batch Number', $this->session->userdata('language'))?></th>
						<th class="text-center" width="25%"><?=translate('Expire Date', $this->session->userdata('language'))?></th>
						<th class="text-center" width="25%" ><?=translate('Diberikan Oleh', $this->session->userdata('language'))?></th>
						<th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>
	<?=form_close()?>
		<!-- END FORM-->
</div>

<!-- GUNAKAN ITEM DALAM BOX PAKET  -->
<div id="modal_item_box" class="modal fade">
	<form class="form-horizontal" id="form_item_dalam_box">
		<div class="modal-dialog">
			<div class="modal-content">
				
			</div>
		</div>
	</form>
</div>	

<!-- GUNAKAN ITEM DILUAR PAKET  -->
<div id="modal_item_diluar" class="modal fade">
	<form class="form-horizontal" id="form_item_diluar">
		<div class="modal-dialog">
			<div class="modal-content">
				
			</div>
		</div>
	</form>
</div>	


<!-- GUNAKAN ITEM TERSIMPAN  -->
<div id="modal_item_tersimpan" class="modal fade">
    <form class="form-horizontal" id="form_item_tersimpan">
        <div class="modal-dialog">
            <div class="modal-content">
                
            </div>
        </div>
    </form>
</div>  


<!-- JUMLAH INVENTORY IDENTITAS  -->
<div id="modal_identitas" class="modal fade">
    <form class="form-horizontal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
            </div>
        </div>
    </form>
</div>  

<!-- VIEW ITEMS  -->
<div id="modal_view_item" class="modal fade">
    <form class="form-horizontal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
            </div>
        </div>
    </form>
</div>  
