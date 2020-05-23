<?php

$form_attr = array(
        "id"            => "form_unggah_gambar_permintaaan_po", 
        "name"          => "form_unggah_gambar_permintaaan_po", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="#" method="post" id="form_gambar" class="form-horizontal">
                                         
    <div class="modal-body" style="padding:0px">
        <div class="portlet light" id="section-gambar">
            <div class="portlet-title">
                
                <div class="caption">
                        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Unggah Gambar", $this->session->userdata("language"))?></span>
                </div>
                <div class="actions">
                    <a id="tambah_gambar" class="btn btn-primary btn-circle">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
           <!--  <div class="portlet-body"> -->
                <input type="hidden" id="id_row_gambar" name="id_row_gambar"  value="<?=$id_row_gambar?>"  />
                <input type="hidden" id="index_row" name="index_row"  value="<?=$index_row?>"  />
                <?php

                    $i='';
                    // $i=0;
                    $form_gambar = '
                    <div id="gambar_{0}">
                        
                            <div class="form-group">
                                <label for="exampleInputFile" class="col-md-3 control-label hidden">'.translate("Url Gambar", $this->session->userdata("language")).'</label>
                            </div>

                            <div class="form-group ">
                                <label for="exampleInputFile" class="col-md-12">'.translate("Pilih Gambar", $this->session->userdata("language")).'<span class="required" style="color:red;">*</span>:</label>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" id="gambar_url_'.$index_row.'_{0}" name="gambar_'.$index_row.'[{0}][url]" value="" class="form-control" readonly="readonly"/>
                                        <input type="hidden" id="gambar_id_'.$index_row.'_{0}" name="gambar_'.$index_row.'[{0}][id]" value="" class="form-control" readonly="readonly"/>
                                        <input type="hidden" id="gambar_is_delete_'.$index_row.'_{0}" name="gambar_'.$index_row.'[{0}][is_delete]" value="" class="form-control" readonly="readonly"/>
                                        <span class="input-group-btn">
                                            <a class="btn red-intense del-gambar" id="gambar_hapus_'.$index_row.'_{0}" title="'.translate('Hapus', $this->session->userdata('language')).'" ><i class="fa fa-times"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile" class="col-md-12"></label>
                                <div id="upload_'.$index_row.'_{0}" class="col-md-12">
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>       
                                        <input type="file" name="upl" id="gambar_file_'.$index_row.'_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
                                    </span>
                                    <div class="form-group col-md-12">
                                        <ul class="ul-img">
                                            <!-- The file uploads will be shown here -->
                                        </ul>
                                    </div>
                               
                                </div>
                            </div>
                       
                        <input type="hidden" name="jml_gambar" id="jml_gambar">
                    </div>';

                ?>
                <div class="form-group hidden" id="penampung_value_url_gambar">
                </div>

                <div id="form_gambar_edit"></div>

                <input type="hidden" id="tpl-form-gambar" value="<?=htmlentities($form_gambar)?>">
                <div class="form-body">
                    <ul class="list-unstyled gambar">
                    </ul>
                </div>
                
           <!--  </div> -->
        </div>
    </div>
    <?php
        $confirm_save       = translate('Apa kamu yakin ingin menambahkan terima uang ini ?',$this->session->userdata('language'));
        $submit_text        = translate('Simpan', $this->session->userdata('language'));
        $reset_text         = translate('Reset', $this->session->userdata('language'));
        $back_text          = translate('Kembali', $this->session->userdata('language'));
    ?>
    <div class="modal-footer">
        <a id="closeModal" class="btn default" data-dismiss="modal">Close</a>
        <a class="btn btn-primary" id="btnOK" onclick="pilih_gambar();">OK</a>
    </div>

</form>
 
    <script type="text/javascript">

    $(document).ready(function(){
        $form               = $('#form_unggah_gambar_permintaaan_po');
        // handleUploadify();
        // alert('diklik');
        
        if($('div#url_gambar_' + $('input#index_row').val()).html() != 'pdf goes here')
        {
            $('ul.gambar').html($('div#url_gambar_' + $('input#index_row').val()).html());  
            $newFieldset = $('div#gambar_' + $('input#index_row').val());
            $('a.del-gambar', $newFieldset).on('click', function(){
                handleDeleteFieldsetGambar($(this).parents('.fieldset').eq(0));
            });
        }
        initForm();
    });

    function initForm()
    {
        // alert('buka init');
        var
            tplFormGambar       = '<li class="fieldset">' + $('#tpl-form-gambar').val() + '<hr></li>',
            regExpTplGambar     = new RegExp('gambar[0]', 'g');  // 'g' perform global, case-insensitive
            if($('div#url_gambar_' + $('input#index_row').val()).html() != 'pdf goes here')
            {
                gambarCounter       = parseInt($('input#jml_gambar').val())+1;
            }
            else
            {
                gambarCounter       = 1;
            }

            var formsGambar = {
                                'gambar' : 
                                {            
                                    section  : $('#section-gambar'),
                                    template : $.validator.format( tplFormGambar.replace(regExpTplGambar, '_id_{0}') ), //ubah ke format template jquery validator
                                    counter  : function(){ gambarCounter++; return gambarCounter-1; }
                                }   
                          }   
            ;



            $.each(formsGambar, function(idx, form){
                // handle button add
                $('a#tambah_gambar', form.section).on('click', function(){
                    
                    addFieldsetGambar(form);

                });
                // beri satu fieldset kosong
                addFieldsetGambar(form);

                $('a.del-gambar-db', form.section).on('click', function(){
                    var id = $(this).data('row');
                    var msg = $(this).data('confirm');

                    handleDeleteDbGambar(form, id, msg);
                });

            });

    }

    function pilih_gambar()
    {   
        $('div#url_gambar_' + $('input#index_row').val()).html($('ul.gambar').html());
        $('#closeModal').click();    
    }
    
    var handleDeleteDbGambar = function(form, id, msg){

        bootbox.confirm(msg, function(result) {
        
            if (result==true) {
                $('li#gambar_' + id).addClass('hidden');
                // alert('input#gambar_is_delete_' + $('input#index_row').val() + '_' + id);
                $('input#gambar_is_delete_' + $('input#index_row').val() + '_' + id).val(1);
                $('input#gambar_is_delete_' + $('input#index_row').val() + '_' + id).val(1);
            }
        });
    }

    function handleDeleteFieldsetGambar($fieldset)
    {        
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset', $parentUl).length,
            hasId         = false,  //punya id tidak, jika tidak bearti data baru
            hasDefault    = 0,      //ada tidaknya fieldset yang di set sebagai default, diset ke 0 dulu
            $inputDefault = $('input:hidden[name$="[is_default]"]', $fieldset), 
            isDefault     = $inputDefault.val() == 1
            ; 

        if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.

        $fieldset.remove();
    };

    function addFieldsetGambar(form)
    {

        var 
            $section           = form.section,
            $fieldsetContainer = $('ul.gambar', $section),
            counter            = form.counter(),
            row                = $('input#index_row').val(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer);

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-gambar', $newFieldset).on('click', function(){
            handleDeleteFieldsetGambar($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        $('input#jml_gambar').val(counter);    
        

        handleUploadify(row, counter);

    };

    function handleUploadify(row, counter)
    {

        var ul = $('#upload_'+row+'_'+counter+' ul');

       
        // Initialize the jQuery File Upload plugin
        $('input#gambar_file_'+row+'_'+counter).fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop_'+counter),
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
                
                tpl.find('div.thumbnail').html('<a class="fancybox-button" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a>');
                $('input#gambar_url_' + row + '_' + counter).attr('value',filename);
                // Add the HTML to the UL element
                ul.html(tpl);
                // data.context = tpl.prependTo(ul);

                handleFancybox();
                Metronic.unblockUI();
                    // data.context = tpl.prependTo(ul);

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

    </script>
