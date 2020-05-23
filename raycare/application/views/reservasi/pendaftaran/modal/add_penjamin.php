<?php
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $alamat_sub = $this->pasien_m->get_data_subjek(1);
    $alamat_sub_array = $alamat_sub->result_array();
    
    $alamat_sub_option = array(
        '' => "Pilih..",

    );
    foreach ($alamat_sub_array as $select) {
        $alamat_sub_option[$select['id']] = $select['nama'];
    }

    $telp_sub = $this->pasien_m->get_data_subjek(2);
    $telp_sub_array = $telp_sub->result_array();
    
    $telp_sub_option = array(
        '' => "Pilih..",

    );
    foreach ($telp_sub_array as $select) {
        $telp_sub_option[$select['id']] = $select['nama'];
    }
?>
<form action="#" id="form_add_pj" class="form-horizontal" role="form">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Penanggungjawab Tindakan", $this->session->userdata("language"))?></span>
	</div>
</div>
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
                <input type="hidden" class="form-control" id="id_pasien" name="id_pasien" value="<?=$pasien->id?>"></input>
                <input type="hidden" class="form-control" id="no_member" name="no_member" value="<?=$pasien->no_member?>"></input>
                <div class="form-group">
                    <label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :<span style="color:red;" class="required">*</span></label>
                    <?php
                        $tipe_pj_option = array(
                            '2' => translate('Suami', $this->session->userdata('language')),
                            '3' => translate('Istri', $this->session->userdata('language')),
                            '4' => translate('Anak', $this->session->userdata('language')),
                            '5' => translate('Ayah', $this->session->userdata('language')),
                            '6' => translate('Ibu', $this->session->userdata('language')),
                            '7' => translate('Lain - lain', $this->session->userdata('language'))
                        );
                    ?>
                    <div class="col-md-12">
                        <?php
                            echo form_dropdown('tipe_pj', $tipe_pj_option, '', 'id="tipe_pj" class="form-control" required');
                        ?>
                    </div>
                </div>
                <div class="form-group hidden" id="div_alias">
                    <label class="col-md-12"><?=translate("Alias Penanggungjawab", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-12">
                        <input class="form-control" name="alias_pj" id="alias_pj"></input>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12"><?=translate("Nama", $this->session->userdata("language"))?> :<span style="color:red;" class="required">*</span></label>
                    <div class="col-md-12">
                        <input class="form-control" name="nama" id="nama" required></input>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12"><?=translate("No. KTP", $this->session->userdata("language"))?> :<span style="color:red;" class="required">*</span></label>
                    <div class="col-md-12">
                        <input class="form-control" name="no_ktp" id="no_ktp" required></input>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="hidden" name="url_ktp" id="url_ktp" required>
                        <div id="upload_ktp">
                            <span class="btn default btn-file">
                                <span class="fileinput-new"><?=translate('Upload KTP', $this->session->userdata('language'))?></span>       
                                <input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_photo" multiple />
                            </span>

                        <ul class="ul-img">
                            <!-- The file uploads will be shown here -->
                        </ul>

                        </div>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :<span style="color:red;" class="required">*</span></label>
                    <div class="col-md-12">
                        <div class="row">
                           <div class="col-md-2">
                                <?php 
                                    echo form_dropdown('subject', $alamat_sub_option, '', 'id="subject" class="form-control" required');
                                ?>
                           </div>
                           <div class="col-md-10">
                                <input class="form-control" name="alamat" id="alamat" placeholder="<?=translate("Alamat", $this->session->userdata("language"))?>" required></input>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12"></label>
                    <div class="col-md-12">
                        <div class="row">
                           <div class="col-md-1">
                                <input class="form-control" name="rt" id="rt" placeholder="<?=translate("RT", $this->session->userdata("language"))?>"></input>
                           </div>
                           <div class="col-md-1">
                                <input class="form-control" name="rw" id="rw" placeholder="<?=translate("RW", $this->session->userdata("language"))?>"></input>
                           </div>
                           <div class="col-md-2">
                                <input class="form-control" name="kodepos" id="kodepos" placeholder="<?=translate("Kodepos", $this->session->userdata("language"))?>"></input>
                           </div>
                           <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" id="input_kelurahan" name="kelurahan" class="form-control" placeholder="<?=translate("Kelurahan", $this->session->userdata("language"))?>" readonly>
                                    <input type="hidden" id="input_kode" name="kode" class="form-control">
                                    <span class="input-group-btn">
                                        <a class="btn btn-primary search_keluarahan" data-toggle="modal" data-target="#modal_alamat" id="btn_cari_kelurahan" title="<?=translate('Cari', $this->session->userdata('language'))?>" href="<?=base_url()?>reservasi/pendaftaran_tindakan/search_kelurahan"><i class="fa fa-search"></i></a>
                                    </span>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12"><?=translate("Telepon", $this->session->userdata("language"))?> :<span style="color:red;" class="required">*</span></label>
                    <div class="col-md-12">
                        <div class="row">
                           <div class="col-md-2">
                                <?php 
                                    echo form_dropdown('subject_telepon', $telp_sub_option, '', 'id="subject_telepon" class="form-control" required');
                                ?>
                           </div>
                           <div class="col-md-10">
                                <input class="form-control" name="no_telepon" id="no_telepon" placeholder="<?=translate("No. Telepon", $this->session->userdata("language"))?>" required></input>
                           </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<?php 
    $msg = translate('Apakah anda yakin menambahkan penanggungjawab ini?', $this->session->userdata("language"));
 ?>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" data-confirm="<?=$msg?>" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
</form>


<script type="text/javascript">
$(document).ready(function(){
    $form_add_pj = $('#form_add_pj');

    handleValidation();
    handleChangeTipe();
    handleConfirmSave();
    handleUploadify();
    
});

function handleValidation() {
    var error1   = $('.alert-danger', $form_add_pj);
    var success1 = $('.alert-success', $form_add_pj);

    $form_add_pj.validate({
        // class has-error disisipkan di form element dengan class col-*
        errorPlacement: function(error, element) {
            error.appendTo(element.closest('[class^="col"]'));
        },
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        // rules: {
        // buat rulenya di input tag
        // },
        invalidHandler: function (event, validator) { //display error alert on form submit              
            success1.hide();
            error1.show();
            Metronic.scrollTo(error1, -200);
        },

        highlight: function (element) { // hightlight error inputs
            $(element).closest('[class^="col"]').addClass('has-error');
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control group
        }

        
    });       
}
    


function handleConfirmSave()
{
    $('a#modal_ok', $form_add_pj).click(function(){
        if(! $form_add_pj.valid()) return;

        var i = 0;
        var msg = $('a#modal_ok').data('confirm');
        bootbox.confirm(msg, function(result) {
            if (result==true) {
                i = parseInt(i) + 1;
                if(i === 1)
                {      
                    $.ajax({
                        type     : 'POST',
                        url      : mb.baseUrl() + 'reservasi/pendaftaran_tindakan/save_pj',
                        data     : $form_add_pj.serialize(),
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            if(results.success == true)
                            {
                                var pj_id = results.pj_id;
                                var rowtipe = results.tipe_pj_option;
                                var tipe_pj_id = results.tipe_pj_id;
                                var $selectTipe = $('select#tipe_pj_daftar');

                                $selectTipe.empty();
                                $.each(rowtipe, function(key, value) {
                                    $selectTipe.append($("<option></option>")
                                        .attr("value", key).text(value));
                                });

                                $selectTipe.val(tipe_pj_id);

                                $('input#penanggungjawab_id').val(pj_id);
                            }
                        },
                        complete : function(){
                            $('button#closeModal').click();  
                            Metronic.unblockUI();
                        }
                    });  
                }               
            }
        });
    });
}


function handleChangeTipe()
{
    $('select#tipe_pj', $form_add_pj).on('change', function(){
        var tipe = $(this).val();

        if(tipe == 7)
        {
            $('#div_alias').removeClass('hidden');
        }
        else
        {
            $('#div_alias').addClass('hidden');
        }
    });
    
}

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

function handleUploadify()
{
    var ul = $('#upload_ktp ul.ul-img');

 
    // Initialize the jQuery File Upload plugin
    $('#upl').fileupload({

        // This element will accept file drag/drop uploading
       
        dataType: 'json',
        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            tpl = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },
        done: function(e, data){
            console.log(data);
            var filename = data.result.filename;
            var filename = filename.replace(/ /g,"_");
            var filetype = data.files[0].type;
            
            if(filetype == 'image/jpeg' || filetype == 'image/png' || filetype == 'image/gif')
            {
                tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
            }
            else
            {
                tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
            }
            $('input#url_ktp').attr('value',filename);
            // Add the HTML to the UL element
            ul.html(tpl);
            handleFancybox();
            Metronic.unblockUI();
            // data.context = tpl.appendTo(ul);

        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            Metronic.blockUI({boxed: true});
        },

        fail:function(e, data){
            // Something has gone wrong!
            bootbox.alert('File Tidak Dapat Diupload');
            Metronic.unblockUI();
        }
    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }    
}

</script>