<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Detail Biaya', $this->session->userdata('language'))?></span>
    </div>
</div>
<div class="modal-body">
<div class="portlet light">
    <div class="portlet-body table-scrollable">
        <?php
            $form_upload_bon = '';
            if(count($form_data_bon) != 0){
                foreach ($form_data_bon as $key => $bon) {
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

        <table class="table table-bordered table-hover">
            <thead>
            <tr role="row" class="heading">
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
    <a class="btn default" id="close" data-dismiss="modal"><?=translate("OK", $this->session->userdata("language"))?></a>
    
</div>

<script type="text/javascript">
$(document).ready(function(){
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