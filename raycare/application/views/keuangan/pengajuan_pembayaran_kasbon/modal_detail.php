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
				<?=translate("DAFTAR KASBON REMBES", $this->session->userdata("language"))?> / <?=$user_minta['nama'].' / '.$user_level['nama']?>
			</div>
			<div class="actions">
				<a class="btn btn-icon-only btn-default btn-circle add-notes" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="note note-success note-bordered">
			<p>
				 Keperluan: <?=$data_rembes['keperluan']?>
			</p>
		</div>
		<div class="portlet-body table-scrollable">
	    <table class="table table-striped table-bordered table-hover table-condensed" id="table_pembelian">
	    <?php
	    	$form_upload_bon = '';
			if(count($data_rembes_detail) != 0){
				foreach ($data_rembes_detail as $key => $bon) {
					$form_upload_bon .= '<tr>
					<td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
					<td style="vertical-align: top !important;">'.$bon['no_bon'].'</td>
					<td style="vertical-align: top !important;">'.date('d M Y', strtotime($bon['tgl_bon'])).'</td>
					<td style="vertical-align: top !important;">'.formatrupiah($bon['total_bon']).'</td>
					<td style="vertical-align: top !important;">'.$bon['keterangan'].'</td>
					</tr>';
				}	
			}
	    ?>
			<thead>
				<tr>
					<th class="text-center" width="8%">
				 		Image
					</th>
					<th class="text-center" width="10%">
						 No. Bon
					</th>
					<th class="text-center" width="8%">
						 Tgl. Bon
					</th>
					<th class="text-center" width="10%">
						 Total Bon
					</th>
					<th class="text-center" width="25%">
						 Keterangan
					</th>
				</tr>
			</thead>
			<tbody>
				<?=$form_upload_bon?>
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

$(document).ready(function(){
    baseAppUrl = mb.baseUrl()+'keuangan/pengajuan_pembayaran_kasbon/';
    handleFancybox();
});

function handleFancybox() {
    if (!jQuery.fancybox) {
        return;
    }

    if ($(".fancybox-button").size() > 0) {
        $(".fancybox-button").fancybox({
            groupAttr: 'data-rel',
            prevEffect: 'none',
            nextEffect: 'none',
            closeBtn: true,
            helpers: {
                title: {
                    type: 'inside'
                }
            }
        });
    }
};

</script>
