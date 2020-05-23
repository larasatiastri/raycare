<?php
	$form_alert_danger  = translate('Silahkan pilih jenis surat terlebih dahulu.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title caption-subject font-blue-sharp bold uppercase"><?=translate('Pilih Surat', $this->session->userdata('language'))?></h4>
</div>
<form id="modal_buat_surat" name="modal_buat_surat" role="form" class="form-horizontal" autocomplete="off">
<div class="modal-body">
	<div class="portlet light">
		<div class="portlet-body form">
			<div class="form-body">
	            <div class="alert alert-danger display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_danger?>
        		</div>
        		<div class="alert alert-success display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_success?>
                </div>
                <input type="hidden" id="pasien_id" name="pasien_id" required="required" class="form-control" value="<?=$pasien_id?>">                       
                <div class="form-group">
                    <label class="control-label col-md-3"><?=translate("Jenis Surat", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                    <div class="col-md-6">
                        <?php
                            $jenis_surat = array(
                                config_item('url_core').'reservasi/rujukan/add'       => 'Surat Rujukan',
                                base_url().'klinik_hd/surat_keterangan_istirahat/add' => 'Surat Keterangan Istirahat',
                                base_url().'klinik_hd/surat_keterangan_sehat/add'     => 'Surat Keterangan Sehat',
                                base_url().'klinik_hd/surat_pengantar/add'            => 'Surat Pengantar',
                                base_url().'klinik_hd/surat_perpanjang_rujukan/add'   => 'Surat Perpanjang Rujukan',
                                base_url().'klinik_hd/surat_traveling/add'            => 'Surat Traveling',
                            );

                            echo form_dropdown('jenis_surat', $jenis_surat, '', 'id="jenis_surat" class="form-control" required');
                        ?>

                    </div>
                </div>       
		         
   			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a class="btn btn-default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
	<a id="confirm_save" class="btn btn-primary" href="" ><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $form = $('#modal_buat_surat');
    handleJenisSuratChange();
});

function handleJenisSuratChange() {
    var url = $('select#jenis_surat').val();
    var pasien_id = $('input#pasien_id').val();
    $('a#confirm_save').attr('href',url+'/'+pasien_id);

    $('select#jenis_surat').on('change', function() {
        var url = $(this).val();
        var pasien_id = $('input#pasien_id').val();
        $('a#confirm_save').attr('href',url+'/'+pasien_id);
    });
};


</script>