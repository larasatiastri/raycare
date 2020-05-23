<?php

	$form_attr = array(
		"id"			=> "form_edit_kirim_saldo", 
		"name"			=> "form_edit_kirim_saldo", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
        "command" => "edit",
        "id"      => $pk_value,
        "flag"    => $flag,
	);


	echo form_open("", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Apa kamu yakin ingin menambahkan titip setoran ini ?',$this->session->userdata('language'));
	$submit_text        = translate('Simpan', $this->session->userdata('language'));
	$reset_text         = translate('Reset', $this->session->userdata('language'));
	$back_text          = translate('Kembali', $this->session->userdata('language'));


?>	
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Kirim Petty Cash', $this->session->userdata('language'))?></span>
    </div>
</div>
<div class="modal-body">
	<div class="portlet light">	
		<div class="portlet-body">
			<div class="form-group">
				<label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
				
				<div class="col-md-12">
					<div class="input-group date" id="tanggal" >
						<input type="text" class="form-control" id="tanggal" name="tanggal" value="<?=date('d-M-Y', strtotime($setoran['tanggal']))?>" readonly >
						<span class="input-group-btn">
							<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
						</span>
					</div>
				</div>
			</div>
            <div class="form-group">
                <label class="col-md-12"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
                <div class="col-md-12">
                    <?php
                        $subjek = array(
                            "name"          => "subjek",
                            "id"            => "subjek",
                            "class"         => "form-control", 
                            "placeholder"   => translate("Subjek", $this->session->userdata("language")), 
                            "required"      => "required",
                            "value"         => $setoran['subjek']
                        );
                        echo form_input($subjek);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12"><?=translate("No. Cek", $this->session->userdata("language"))?> :</label>
                <div class="col-md-12">
                    <?php
                        $no_cek = array(
                            "name"          => "no_cek",
                            "id"            => "no_cek",
                            "class"         => "form-control", 
                            "placeholder"   => translate("No Cek", $this->session->userdata("language")), 
                            "required"      => "required",
                            "value"         => $setoran['no_cek']
                        );
                        echo form_input($no_cek);
                    ?>
                </div>
            </div>
			
			
			<div class="form-group">
				<label class="col-md-12"><?=translate("Nominal", $this->session->userdata("language"))?> :</label>		
				<div class="col-md-12">
                    <div class="input-group">
                            <span class="input-group-addon">
                                Rp
                            </span>
    					<?php
    						$rupiah = array(
    							"name"			=> "rupiah",
    							"id"			=> "rupiah",
    							"class"			=> "form-control", 
    							"placeholder"	=> translate("Nominal", $this->session->userdata("language")), 
    							"required"		=> "required",
                                "value"         => $setoran['total_setor']
    						);
    						echo form_input($rupiah);
    					?>
                    </div>
    					<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
				<label class="col-md-12" id="terbilang"></label>
			</div>
			<div class="form-group">
				<label class="col-md-12"><?=translate("Pengeluaran Bank", $this->session->userdata("language"))?> :</label>		
				<div class="col-md-12">
					<?php
						$banks = $this->bank_m->get_by(array('is_active' => 1));

						$bank_option = array(
							'' => translate('Pilih', $this->session->userdata('language')).'...'
						);

						foreach ($banks as $bank) {
							$bank_option[$bank->id] = $bank->nob.' a/n '.$bank->acc_name.' - '.$bank->acc_number;
						}

						echo form_dropdown('bank_id', $bank_option, $setoran['bank_id'], 'id="bank_id" class="form-control"');
					?>
				</div>
			</div>
            <?php 

            if($flag == 'send'){
            ?>
    			<div class="form-group">
    				<label class="col-md-12"><?=translate("Upload Cek", $this->session->userdata("language"))?> <span>:</span></label>
    				<div class="col-md-12">
    					<input type="hidden" name="url_bukti_setor" id="url_bukti_setor" value="<?=$setoran['url_bukti_setor']?>">
    					<div id="upload">
    						<span class="btn default btn-file">
    							<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>	
    							<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_photo" />
    						</span>

                            <ul class="ul-img">
                            <li class="working">
                                <div class="thumbnail">
                                    <a class="fancybox-button" title="<?=$setoran['url_bukti_setor']?>" href="<?=base_url().'assets/mb/pages/keuangan/kirim_petty_cash/images/'.$pk_value.'/'.$setoran['url_bukti_setor']?>" data-rel="fancybox-button"><img src="<?=base_url().'assets/mb/pages/keuangan/kirim_petty_cash/images/'.$pk_value.'/'.$setoran['url_bukti_setor']?>" alt="Smiley face" class="img-thumbnail" ></a>
                                </div>
                            </li>
                            </ul>
    					</div>
    				</div>
    			</div>
            
            <?php
            }
            ?>

			
		</div>
	</div>									
</div>
<?php
	$confirm = translate('Anda yakin akan mengirim petty cash ini?', $this->session->userdata('language'));
?>
<div class="modal-footer">
    <a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" id="simpan" data-confirm="<?=$confirm?>"><?=translate("Kirim", $this->session->userdata("language"))?></a>
</div>						
<?=form_close();?>

<script type="text/javascript">
$(document).ready(function(){
	handleUploadify();
    handleFancybox();
	handleDatePickers();
	handleTerbilang();
	save();

});

function handleTerbilang(){

    var nominal = $('input#rupiah').val();
    $.ajax
    ({
        type: 'POST',
        url: baseAppUrl +  "get_terbilang",  
        data:  {nominal:nominal},  
        dataType : 'json',
        beforeSend : function(){
            Metronic.blockUI({boxed: true });
        },
        success:function(data)          //on recieve of reply
        { 
            
          $('label#terbilang').text(data.terbilang);
        
        },
        complete : function(){
          Metronic.unblockUI();
        }
    });

    $('input#rupiah').on('change', function(){
        var nominal = $(this).val();
        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "get_terbilang",  
            data:  {nominal:nominal},  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                
              $('label#terbilang').text(data.terbilang);
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
        });

    });
}

function handleDatePickers() {

	$form = $('#form_edit_kirim_saldo');
    if (jQuery().datepicker) {
        $('.date', $form).datepicker({
            rtl: Metronic.isRTL(),
            format : 'dd-M-yyyy',
        }).on('changeDate', function(){
        	$('div.datepicker-dropdown').hide();
        });
        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }
}

function handleUploadify()
{
    var ul = $('#upload ul');

   
    // Initialize the jQuery File Upload plugin
    $('#upl').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),
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

            var filename = data.result.filename;
            var filename = filename.replace(/ /g,"_");
            var filetype = data.files[0].type;

            if(filetype == 'image/jpeg' || filetype == 'image/png' || filetype == 'image/gif')
            {
                tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
            }
            else
            {
                bootbox.alert('File Tidak Dapat Diupload');
            }
            
            $('input#url_bukti_setor').attr('value',filename);
            // // Add the HTML to the UL element
            ul.html(tpl);
            // data.context = tpl.appendTo(ul);
            
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

function save() {
    $form = $('#form_edit_kirim_saldo');

    $('a#simpan',$form).click(function() {

        if (! $form.valid()) return;
        var msg = $(this).data('confirm');
        bootbox.confirm(msg, function(result) {
            if (result==true) {
                $.ajax
                ({
                    type: 'POST',
                    url: mb.baseUrl() + 'keuangan/kirim_petty_cash/save',  
                    data:  $form.serialize(),  
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success:function(data)          //on recieve of reply
                    { 
                        if(data.success == true){
                            mb.showMessage('success',data.msg,'Sukses');
                            location.href = mb.baseUrl() + 'keuangan/kirim_petty_cash';
                        }if(data.success == false){
                            mb.showMessage('error',data.msg,'Error');
                            $('a#close').click();
                        }
                    
                    },
                    complete : function(){
                      Metronic.unblockUI();
                    }
                });
            }
        });
    });
}
</script>
