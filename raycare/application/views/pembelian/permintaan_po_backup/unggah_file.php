<?php

$form_attr = array(
        "id"            => "form_unggah_pdf_permintaaan_po", 
        "name"          => "form_unggah_pdf_permintaaan_po", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="#" method="post" id="form_pdf" class="form-horizontal">
                                     
    <div class="modal-body" style="padding:0px;">
        <div class="portlet light" id="section-pdf">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Unggah PDF", $this->session->userdata("language"))?></span>
                </div>
                
            </div>
            <!-- <div class="portlet-body"> -->
                <input type="hidden" id="id_row_pdf" name="id_row_pdf"  value="<?=$id_row_file?>"  />
                <input type="hidden" id="index_row" name="index_row"  value="<?=$index_row?>"  />
                <?php
                    $form_pdf = '
                    <div id="pdf_{0}">

                        <div class="form-group">
                            <label for="exampleInputFile" class="col-md-3 control-label hidden">'.translate("Url pdf", $this->session->userdata("language")).'</label>
                        </div>

                        <div class="form-group ">
                            <label for="exampleInputFile" class="col-md-12">'.translate("Pilih pdf", $this->session->userdata("language")).'<span class="required" style="color:red;">*</span>:</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" id="pdf_url_'.$index_row.'_{0}" name="pdf_'.$index_row.'[{0}][url]" value="" class="form-control" readonly="readonly"/>
                                    <input type="hidden" id="pdf_id_'.$index_row.'_{0}" name="pdf_'.$index_row.'[{0}][id]" value="" class="form-control" readonly="readonly"/>
                                    <input type="hidden" id="pdf_is_delete_'.$index_row.'_{0}" name="pdf_'.$index_row.'[{0}][is_delete]" value="" class="form-control" readonly="readonly"/>
                                    <span class="input-group-btn">
                                        <a class="btn red-intense del-pdf" title="'.translate('Hapus', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile" class="col-md-12"></label>
                            <div id="upload_'.$index_row.'_{0}" class="col-md-12">
                                <span class="btn default btn-file">
                                    <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
                                    <input type="file" name="upl" id="pdf_file_'.$index_row.'_{0}" data-url="'.base_url().'upload/upload_pdf" multiple />
                                </span>
                            </div>
                        </div>

                        <input type="hidden" name="jml_pdf" id="jml_pdf">
                    </div>';
                ?>
                <div class="form-group hidden" id="penampung_value_url_pdf">
                </div>

                <div id="form_pdf_edit"></div>

                <input type="hidden" id="tpl-form-pdf" value="<?=htmlentities($form_pdf)?>">
                <div class="form-body">
                    <ul class="list-unstyled pdf">
                    </ul>
                </div>
                
            <!-- </div> -->
        </div>
    </div>
    <?php
        $confirm_save       = translate('Apa kamu yakin ingin menambahkan terima uang ini ?',$this->session->userdata('language'));
        $submit_text        = translate('Simpan', $this->session->userdata('language'));
        $reset_text         = translate('Reset', $this->session->userdata('language'));
        $back_text          = translate('Kembali', $this->session->userdata('language'));
    ?>
    <div class="modal-footer">
        <a id="closeModalFile" class="btn default" data-dismiss="modal">Close</a>
        <a class="btn btn-primary" id="btnOKFile" onclick="pilih_pdf();">OK</a>
    </div>

</form>
 
    <script type="text/javascript">

    $(document).ready(function(){
        $form               = $('#form_unggah_pdf_permintaaan_po');
        // handleUploadify();
        // alert('diklik');
        if($('div#url_pdf_' + $('input#index_row').val()).html() != 'pdf goes here')
        {
            $('ul.pdf').html($('div#url_pdf_' + $('input#index_row').val()).html());  
            $newFieldset = $('div#pdf_' + $('input#index_row').val());
            $('a.del-pdf', $newFieldset).on('click', function(){
                handleDeleteFieldsetGambar($(this).parents('.fieldset').eq(0));
            });
        }
        initForm();
    });

    function initForm()
    {
        // alert('buka init');
        var
            tplFormpdf       = '<li class="fieldset">' + $('#tpl-form-pdf').val() + '<hr></li>',
            regExpTplpdf     = new RegExp('pdf[0]', 'g');  // 'g' perform global, case-insensitive
            if($('div#url_pdf_' + $('input#index_row').val()).html() != 'pdf goes here')
            {
                pdfCounter       = parseInt($('input#jml_pdf').val())+1;
            }
            else
            {
                pdfCounter       = 1;
            }

            formspdf = {
                            'pdf' : 
                            {            
                                section  : $('#section-pdf'),
                                template : $.validator.format( tplFormpdf.replace(regExpTplpdf, '_id_{0}') ), //ubah ke format template jquery validator
                                counter  : function(){ pdfCounter++; return pdfCounter-1; }
                            }   
                        }   
            ;

            $.each(formspdf, function(idx, form){
                // handle button add
                $('a#tambah_pdf', form.section).on('click', function(){
                    addFieldsetpdf(form);
                });
                // beri satu fieldset kosong
                addFieldsetpdf(form);

                $('a.del-pdf-db', form.section).on('click', function(){
                    var id = $(this).data('row');
                    var msg = $(this).data('confirm');

                    handleDeleteDbPdf(form, id, msg);
                });

            });

    }

    function pilih_pdf()
    {
        $('div#url_pdf_' + $('input#index_row').val()).html($('ul.pdf').html());

        $('#closeModalFile').click();     
    }

    var handleDeleteDbPdf = function(form, id, msg){

        bootbox.confirm(msg, function(result) {
        
            if (result==true) {
                $('li#pdf_' + id).addClass('hidden');
                // alert('input#pdf_is_delete_' + $('input#index_row').val() + '_' + id);
                $('input#pdf_is_delete_' + $('input#index_row').val() + '_' + id).val(1);
                $('input#pdf_is_delete_' + $('input#index_row').val() + '_' + id).attr('value', 1);
            }
        });
    }

    function handleDeleteFieldsetpdf($fieldset)
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

    function addFieldsetpdf(form)
    {

        var 
            $section           = form.section,
            $fieldsetContainer = $('ul.pdf', $section),
            counter            = form.counter(),
            row                = $('input#index_row').val(),
            $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer);

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-pdf', $newFieldset).on('click', function(){
            handleDeleteFieldsetpdf($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

        $('input#jml_pdf').val(counter);  

        handleUploadify(row, counter);

    };

    function handleUploadify(row, counter)
    {


        var ul = $('#upload_'+row+'_'+counter+' ul');

       
        // Initialize the jQuery File Upload plugin
        $('#pdf_file_'+row+'_'+counter).fileupload({

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
                // alert(filename);
                // tpl.find('div.thumbnail').html('<a class="fancybox-button" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a>');
                $('input#pdf_url_' + row + '_' + counter).attr('value',filename);
                // Add the HTML to the UL element
                // ul.html(tpl);
                // data.context = tpl.prependTo(ul);

                // handleFancybox();
                Metronic.unblockUI();
                    // data.context = tpl.prependTo(ul);

            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                Metronic.blockUI({boxed: true});
            },


            fail:function(e, data){
                // Something has gone wrong!
                alert(data.result.filename);
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
