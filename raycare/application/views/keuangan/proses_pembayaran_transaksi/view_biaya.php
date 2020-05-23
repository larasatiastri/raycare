<?php
	$form_attr = array(
	    "id"            => "form_kasbon_detail", 
	    "name"          => "form_kasbon_detail", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>
<div class="modal-body">
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<?=translate("DAFTAR BIAYA TAMBAHAN", $this->session->userdata("language")).' '.$form_data[0]['no_pembelian']?>
			</div>
		</div>
		
		<div class="portlet-body">
	    <table class="table table-striped table-bordered table-hover table-condensed" id="table_pembelian">
			<thead>
				<tr>
					<th class="text-center" width="15%">
				 		Biaya
					</th>
					<th class="text-center" width="10%">
						Nominal
					</th>
				</tr>
			</thead>
			<tbody>
			<?php
				if(count($data_biaya) != 0){
					foreach ($data_biaya as $biaya) {
			?>
				<tr>
					<td><?=$biaya['nama']?></td>
					<td class="text-right"><?=formatrupiah($biaya['nominal'])?></td>
				</tr>
			<?php
					}
				}
			?>
			</tbody>
		</table>
		</div>
	</div>
</div>
<div class="modal-footer">
    <a class="btn default" id="close" data-dismiss="modal"><?=translate("Tutup", $this->session->userdata("language"))?></a>
</div>
<?=form_close()?>
<script type="text/javascript">



</script>
