<?php

$form_attr = array(
        "id"            => "form_unggah_gambar_persetujuan_permintaan_barang", 
        "name"          => "form_unggah_gambar_persetujuan_permintaan_barang", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="#" method="post" id="form_gambar" class="form-horizontal">  
    <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button> -->
        <h4 class="modal-title uppercase"><?=translate("Gallery Gambar", $this->session->userdata("language"))?></h4>
    </div>                                  
    <div class="modal-body">
        <div class="portlet light" id="section-gambar">
            <div class="portlet-body">               
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <!-- BEGIN FILTER -->
                        <div class="margin-top-10">
                            <div class="row mix-grid">                                       
                                <?php
                                    if($data_img != '')
                                    {
                                        foreach ($data_img as $img) 
                                        {
                                            ?>
                                                <div class="col-md-12 mix category_1">
                                                    <div class="mix-inner">
                                                        <img class="img-responsive" src="<?=base_url()?>assets/mb/pages/pembelian/permintaan_po/images/<?=strtolower(str_replace(' ', '_', $data_order_detail['nama']))?>/<?=$img['url']?>" alt="">
                                                        <div class="mix-details">
                                                            <h5><?=$data_order_detail['nama']?></h5>
                                                            <a class="mix-preview fancybox-button" style="top:-3%;left:1%;" href="<?=base_url()?>assets/mb/pages/pembelian/permintaan_po/images/<?=str_replace(' ', '_', $data_order_detail['nama'])?>/<?=$img['url']?>" title="<?=$data_order_detail['nama']?>" data-rel="fancybox-button">
                                                            <i class="fa fa-search"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                
                                            <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <!-- END FILTER -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        $confirm_save       = translate('Apa kamu yakin ingin menambahkan terima uang ini ?',$this->session->userdata('language'));
        $submit_text        = translate('Simpan', $this->session->userdata('language'));
        $reset_text         = translate('Reset', $this->session->userdata('language'));
        $back_text          = translate('Kembali', $this->session->userdata('language'));
    ?>
    <div class="modal-footer">

        <a type="button" id="closeModal" class="btn default" data-dismiss="modal">Close</a>
        <a type="button" class="btn btn-primary" id="btnOK" onClick="javascript:pilih_gambar();">OK</a>

    </div>
</form>
 
    <script type="text/javascript">

     $(document).ready(function(){
        $form               = $('#form_unggah_gambar_permintaaan_item');
        // handleUploadify();
        // alert('diklik');
        initForm();
        handleFancybox();
        $('.mix-grid').mixitup();
        // pilih_gambar();
    });

    function initForm()
    {


        // alert('buka init');
        var
            tplFormGambar       = '<li class="fieldset">' + $('#tpl-form-gambar').val() + '<hr></li>',
            regExpTplGambar     = new RegExp('gambar[0]', 'g'),   // 'g' perform global, case-insensitive
            gambarCounter       = 1;
            // gambarCounter       = $('input#counter_gambar').val();
            
            
            formsGambar = {
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

            });

            $('input#counter_gambar').val(gambarCounter);
    }

    function pilih_gambar()
    {
        // $.each($('.url'), function(idx, value){

        //     // handle button add
        //    // alert(this.value);
        // url             = $('input#url_1').val();
        // id_row_gambar      = $('input#id_row_gambar').val(); 
        // index_row       = $('input#index_row').val(); 
        // tr_rw           = $('tr#'+id_row_gambar);       
        // // alert(id_row_gambar);
        // // $('input[name$="[upload_file]"]', tr_rw).val(upload_file);
        // // $('input[name$="[url_gambar]"]', tr_rw).append(this.value);
        // $('div#penampung_value_url_gambar').append(this.value + ' ');
        // $('div#url_gambar').append('<input type="hidden" id="a" name="gambar_url_'+index_row+'['+idx+'][url]" value="'+this.value+'" >');
        // $('input[name$="[url_gambar]"]', tr_rw).val($('div#penampung_value_url_gambar').html());
        

        // });
            // alert('div#url_gambar_' +$('input#index_row').val());
        
            $('div#url_gambar_' + $('input#index_row').val()).html($('ul.gambar').html());

            $('#closeModal').click();
        
       
        
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
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer);

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-gambar', $newFieldset).on('click', function(){
            handleDeleteFieldsetGambar($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        handleUploadify(row, counter);

    };

    function handleUploadify(row, counter)
    {


        var ul = $('#upload_'+row+'_'+counter+' ul');

       
        // Initialize the jQuery File Upload plugin
        $('#gambar_file_'+row+'_'+counter).fileupload({

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
    

    </script>
