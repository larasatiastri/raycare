<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("View Persetujuan", $this->session->userdata("language"))?></h4>
</div>
<div class="modal-body">
    <div class="table-scrollable">
    <table class="table table-condensed table-striped table-bordered table-hover" id="tabel_persetujuan">
        <thead>
            <tr role="row">
                <th><div class="text-center"><?=translate("No", $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate("User Level", $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate("Status", $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate("Dibaca Oleh", $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate("Tgl Baca", $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate("Disetujui Oleh", $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate("Tgl Setuju", $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate("Nominal", $this->session->userdata('language'))?></div></th>
                <th><div class="text-center"><?=translate("Keterangan", $this->session->userdata('language'))?></div></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $i = 1;
                foreach ($data_setuju as $key => $setuju) {
                    $status = '';
                    $tgl_baca = '';
                    $tgl_setuju = '';

                    if($setuju['tanggal_baca'] != NULL){
                        $tgl_baca = date('d-M-Y', strtotime($setuju['tanggal_baca']));
                    }if($setuju['tanggal_persetujuan'] != NULL){
                        $tgl_setuju = date('d-M-Y', strtotime($setuju['tanggal_persetujuan']));
                    }
                    if($setuju['status'] == 2)
                    {
                        $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';
                    
                    } elseif($setuju['status'] == 3){

                        $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

                    } elseif($setuju['status'] == 4){

                        $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
                    }
                    echo '<tr>
                            <td>'.$i.'</td>
                            <td>'.$setuju['nama_level'].'</td>
                            <td>'.$status.'</td>
                            <td>'.$setuju['nama_baca'].'</td>
                            <td>'.$tgl_baca.'</td>
                            <td>'.$setuju['nama_setuju'].'</td>
                            <td>'.$tgl_setuju.'</td>
                            <td>'.formatrupiah($setuju['nominal']).'</td>
                            <td>'.$setuju['keterangan'].'</td>
                        </tr>';

                    $i++;
                }
            ?>
            
        </tbody>
    </table>
    </div>
</div>
<?php
    $back_text  = translate('Close', $this->session->userdata('language'));
?>
<div class="modal-footer">
    <a class="btn default" type="button" id="closeModal" data-dismiss="modal"><?=$back_text?></a>

</div>
