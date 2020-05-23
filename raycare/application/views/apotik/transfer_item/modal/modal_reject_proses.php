    <form action="<?=base_url()?>gudang/transfer_item/reject_request_item" method="post" id="form_reject_terima" class="form-horizontal">
        
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Reject Request Item", $this->session->userdata("language"))?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Isi Keterangan", $this->session->userdata("language"))?>:</label>
                         
                        <div class="col-md-8">
                            <!-- <input type="file" id="uploaddokumen" name="uploaddokumen" class="uploadbutton up-this" value="" required="required" /> -->
                            <input type="hidden" id="request_item_id" name="request_item_id"  value="<?=$request_item_id?>" required="required" />
                            <textarea id="nomer_{0}" name="keterangan" class="form-control" rows="3" placeholder="Keterangan"></textarea>
                            
                        </div>
                         <div class="col-md-12"></div>
                            <input type="hidden" id="url" name="url" value="" class="requiredfile form-control" />
                        <input type="hidden" id="uploadfilename" name="uploadfilename" required="required" />
                    </div> 
                
                    <div id="uploadchoosen_file_container_1" name="uploadchoosen_file_container_1" style="display:none" >
                        <div class="form-group">
                            <label class="control-label col-md-4"></label>
                            <div class="col-md-4">
                                <label id="uploadchoosen_file_1" name="uploadchoosen_file_1">
                                   
                                </label>
                            </div>
                            <div class="col-md-12"></div>
                        </div>
                    </div>

                </div>
                <?php
                    $confirm_save       = translate('Apa kamu yakin ingin menolak request item ini ?',$this->session->userdata('language'));
                    $submit_text        = translate('Ok', $this->session->userdata('language'));
                    $reset_text         = translate('Reset', $this->session->userdata('language'));
                    $back_text          = translate('Close', $this->session->userdata('language'));
                ?>
                <div class="modal-footer">
                    <!-- <button type="button" id="closeModal" class="btn default" data-dismiss="modal">Close</button> -->
                    <a class="btn default" type="button" id="closeModal" data-dismiss="modal"><?=$back_text?></a>
                    <!-- <button type="button" class="btn btn-primary btn-ok" data-confirm="<?=$confirm_save?>" id="btnOK" onClick="javascript:initForm();">OK</button> -->
                    <button type="submit" id="save_reject" class="btn btn-primary hidden" onClick="javascript:initForm();"><?=$submit_text?></button>
                    <a id="confirm_save_reject" class="btn btn-primary" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=$submit_text?></a>

                </div>

    </form>

    <script type="text/javascript">
    
        $(document).ready(function(){
            // upload();
            // initForm();
            // pilih_file();
            handleConfirmSave();
        });

    function initForm()
    {

            // upload_file = $('input#uploadfilename').val();
            // url         = $('input#url').val();
            // id_row      = $('input#id_row').val(); 
            // tr_rw       = $('tr#'+id_row);       
           
            // $('input[name$="[upload_file]"]', tr_rw).val(upload_file);
            // $('input[name$="[url_file]"]', tr_rw).val(url);
            handleConfirmSave()
            // $('#closeModal').click();
       
    }

    function handleConfirmSave()

    {
        $('a#confirm_save_reject').click(function() {
            // if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $('#save_reject').click();
                }
            });
        });
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