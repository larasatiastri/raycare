<?php

$form_attr = array(
        "id"            => "form_unggah_pdf_persetujuan_permintaan_barang", 
        "name"          => "form_unggah_pdf_persetujuan_permintaan_barang", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>
    <form action="#" method="post" id="form_file" class="form-horizontal">
        
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button> -->
                    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Unggah Pdf", $this->session->userdata("language"))?></h4>
                </div>
                <div class="modal-body">
                    <div class="portlet light" id="section-pdf">
                        <div class="portlet-title">
                            <input type="" id="id_row_pdf" name="id_row_pdf" value="<?=$id_row_file?>" />
                            <input type="" id="index_row" name="index_row" value="<?=$index_row?>" />
                            <div class="actions">
                                <a id="tambah_pdf" class="btn btn-primary">
                                    <i class="fa fa-plus"></i>
                                    <span class="hidden-480">
                                         <?=translate("Tambah", $this->session->    userdata("language"))?>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php

                                $i='';
                                // $i=0;
                                $form_pdf = '
                                <div id="pdf_{0}">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="exampleInputFile" class="col-md-3 control-label hidden">'.translate("Url pdf", $this->session->userdata("language")).'</label>
                                        </div>

                                        <div class="form-group ">
                                            <label for="exampleInputFile" class="col-md-3 control-label">'.translate("Pilih pdf", $this->session->userdata("language")).'<span class="required">*</span>:</label>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input type="text" id="pdf_url_'.$index_row.'_{0}" name="pdf_'.$index_row.'[{0}][url]" value="" class="form-control" readonly="readonly"/>
                                                    <span class="input-group-btn">
                                                        <a class="btn red-intense del-pdf" title="'.translate('Hapus', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputFile" class="col-md-3 control-label"></label>
                                            <div id="upload_'.$index_row.'_{0}" class="col-md-6">
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
                                                    <input type="file" name="upl" id="pdf_file_'.$index_row.'_{0}" data-url="'.base_url().'upload/upload_pdf" multiple />
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            ?>
                            <div class="form-group">
                                <label class="control-label col-md-4 hidden"><?=translate("Phone Counter", $this->session->userdata("language"))?> :</label>
                                <div class="col-md-5">
                                    <input type="hidden" id="pdf_counter" value="<?=$i?>" >
                                </div>
                            </div>

                            <div class="form-group hidden" id="penampung_value_url_pdf">
                            </div>

                            <div id="form_pdf_edit"></div>

                            <input type="hidden" id="tpl-form-pdf" value="<?=htmlentities($form_pdf)?>">
                            <div class="form-body">
                                <ul class="list-unstyled pdf">
                                </ul>
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
                    <button type="button" id="closeModal" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-ok" id="btnOK" onClick="javascript:pilih_pdf();">OK</button>

                </div>

    </form>

    <script type="text/javascript">
    
        $(document).ready(function(){
            $form = $('#form_unggah_pdf_persetujuan_permintaan_barang');
            // upload();
            initForm();
            // pilih_file();

        });

    function initForm()
    {

            // alert('buka init');
        var
            tplFormpdf       = '<li class="fieldset">' + $('#tpl-form-pdf').val() + '<hr></li>',
            regExpTplpdf     = new RegExp('pdf[0]', 'g'),   // 'g' perform global, case-insensitive
            pdfCounter       = 1;
            // pdfCounter       = $('input#counter_pdf').val();
            
            
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

            });

            $('input#counter_pdf').val(pdfCounter);
       
    }

    function pilih_pdf()
    {
        // $.each($('.url'), function(idx, value){

        //     // handle button add
        //    // alert(this.value);
        // url             = $('input#url_1').val();
        // id_row_pdf      = $('input#id_row_pdf').val(); 
        // index_row       = $('input#index_row').val(); 
        // tr_rw           = $('tr#'+id_row_pdf);       
        // // alert(id_row_pdf);
        // // $('input[name$="[upload_file]"]', tr_rw).val(upload_file);
        // // $('input[name$="[url_pdf]"]', tr_rw).append(this.value);
        // $('div#penampung_value_url_pdf').append(this.value + ' ');
        // $('div#url_pdf').append('<input type="hidden" id="a" name="pdf_url_'+index_row+'['+idx+'][url]" value="'+this.value+'" >');
        // $('input[name$="[url_pdf]"]', tr_rw).val($('div#penampung_value_url_pdf').html());
        

        // });
            // alert('div#url_pdf_' +$('input#index_row').val());
        
            $('div#url_pdf_' + $('input#index_row').val()).html($('ul.pdf').html());

            $('#closeModalFile').click();
        
       
        
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
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer);

        // $('select[name$="[payment_type]"]', $newFieldset).on('change', function(){
        //     handleSelectSection(this.value, $newFieldset);
        // });
        $('a.del-pdf', $newFieldset).on('click', function(){
            handleDeleteFieldsetpdf($(this).parents('.fieldset').eq(0));
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');

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
                // data.context = tpl.appendTo(ul);

                // handleFancybox();
                Metronic.unblockUI();
                    // data.context = tpl.appendTo(ul);

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

    function upload()
    {
        $('#uploaddokumen').uploadify({
            "swf"               : mb.baseUrl() + "assets/mb/global/uploadify/uploadify.swf",
            "uploader"          : mb.baseUrl() + "assets/mb/global/uploadify/uploadify4.php",
            "formData"          : {"type" : "dokumen", "dokumen_id" : "", "nama_dokumen" : "dokumen"}, 
            "fileObjName"       : "Filedata", 
            "fileSizeLimit"     : "2048KB",
            // "fileTypeDesc"      : "Image Files (.jpg, .jpeg, .png)",
            "fileTypeExts"      : "*.pdf",
            "method"            : "post", 
            "multi"             : false, 
            "queueSizeLimit"    : 1, 
            "removeCompleted"   : true, 
            "removeTimeout"     : 5, 
            "uploadLimit"       : 5, 
            "onUploadSuccess"   : function(file, data, response) {
             var paramsArray = data.split('%%__%%');
                param1 = paramsArray[0]; 
                param2 = paramsArray[1]; 

                if(param2=='jpg' || param2=='jpeg' || param2=='png' || param2=='gif')
                {
                    alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
                    $('#uploadchoosen_file_1').html("<img src="+ mb.baseUrl() + "assets/mb/pages/pembelian/permintaan_po/files/temp/"+param1+" style='border: 1px solid #000; max-width:200px; max-height:200px;'>");
                    $('#uploadchoosen_file_container_1').show();
                    $('#uploadfilename').val(param1);
                }else{
                    $('#uploadfilename').val(param1);
                    $('#uploadchoosen_file_container_1').show();
                    $('#uploadchoosen_file_1').html('<b>' + file.name + '</b>');
                }
                $("#url").val(mb.baseUrl()+'assets/mb/pages/pembelian/permintaan_po/files/temp/'+param1);

              
            },
            "onUploadComplete"   : function(file) {
                
                alert('File ' + file.name + ' selesai di Unggah');
              
            }
        }); 
        
    }

    </script>