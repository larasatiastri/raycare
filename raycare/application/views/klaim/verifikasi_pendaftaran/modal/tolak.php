<?
    $form_attr = array(
        "id"            => "form_proses", 
        "name"          => "form_proses", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="<?=base_url()?>klaim/verifikasi_pendaftaran/tolak" method="post" id="form_proses" class="form-horizontal">
                                       
    <div class="modal-body">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses", $this->session->userdata("language"))?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-group hidden">
                    <label class="control-label col-md-3"><?=translate("Pendaftaran Tindakan ID", $this->session->userdata("language"))?></label>
                    <div class="col-md-6">
                        <?php
                            $pendaftaran_tindakan = $this->pendaftaran_tindakan_m->get($pendaftaran_tindakan_id);
                            $pendaftaran_tindakan = object_to_array($pendaftaran_tindakan);
                        ?>
                        <input type="text" id="pendaftaran_tindakan_id" name="pendaftaran_tindakan_id" class="form-control" value="<?=$pendaftaran_tindakan_id?>">
                    </div>
                </div>

                <div class="form-group hidden">
                    <label class="control-label col-md-6"><?=translate("Cabang ID", $this->session->userdata("language"))?></label>
                    <div class="col-md-6">
                        <input type="text" id="cabang_id" name="cabang_id" class="form-control" value="<?=$pendaftaran_tindakan['cabang_id']?>">
                    </div>
                </div>

                <div class="form-group hidden">
                    <label class="control-label col-md-6"><?=translate("Pasien ID", $this->session->userdata("language"))?></label>
                    <div class="col-md-6">
                        <input type="text" id="pasien_id" name="pasien_id" class="form-control" value="<?=$pendaftaran_tindakan['pasien_id']?>">
                    </div>
                </div>
                
                <div class="form-group hidden">
                    <label class="control-label col-md-3"><?=translate("Keterangan DB", $this->session->userdata("language"))?></label>
                    <div class="col-md-8">
                        <textarea rows="5" id="keterangan_db" name="keterangan_db" class="form-control" placeholder="<?=translate("Keterangan", $this->session->userdata("language"))?>"><?=$pendaftaran_tindakan['keterangan']?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?></label>
                    <div class="col-md-8">
                        <textarea rows="5" id="keterangan" name="keterangan" class="form-control" placeholder="<?=translate("Keterangan", $this->session->userdata("language"))?>"></textarea>
                    </div>
                </div>
               
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <?php 
            $msg = translate("Tolak pendaftaran pasien ini ?", $this->session->userdata("language"));
        ?>
        <a id="closeModal" class="btn btn-circle btn-default" data-dismiss="modal">Close</a>
        <button type="submit" class="btn btn-primary hidden" id="btnTolakOK">OK</button>
        <a class="btn btn-circle btn-primary" id="confirm_tolak" data-confirm="<?=$msg?>">OK</a>
    </div>


    </form>
 
    <script type="text/javascript">

        $(document).ready(function(){
            initForm();
            baseAppUrl = mb.baseUrl()+'klaim/verifikasi_pendaftaran/'
            // modalOK();
        });
        
        function initForm()
        {
            confirmTolak();
        };  

        function confirmTolak(){
            $('a#confirm_tolak').click(function() {
                var msg = $(this).data('confirm');
                var i=0;
                bootbox.confirm(msg, function(result) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses..'});
                    if (result==true) {
                        i = parseInt(i) + 1;
                        $('a#confirm_tolak').attr('disabled','disabled');
                        if(i === 1)
                        {
                          $('#btnTolakOK').click();
                        }
                    }
                });
            });
        };
    </script>
