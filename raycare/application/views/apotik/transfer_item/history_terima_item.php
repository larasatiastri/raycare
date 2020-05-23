<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
        	<i class="fa fa-history font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Terima Item", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">

        	<div class="btn-group">
        		<?php 

					$get_gudang = $this->gudang_m->get_by(array('is_active' => 1));
						// die_dump(object_to_array($get_gudang));

					$gudang = object_to_array($get_gudang);

					$gudang_option = array(
						
					);
					foreach ($gudang as $data) {
						$gudang_option[$data['id']] = $data['nama'];
					}

					echo form_dropdown('gudang_terima', $gudang_option, "", "id=\"gudang_terima\" class=\"form-control\""); 
				?>
        	</div>
            <?php
                $back_text      = translate('Kembali', $this->session->userdata('language'));
            ?>
                
            <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
                <i class="fa fa-chevron-left"></i>
                <?=$back_text?>
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover" id="table_history_terima_item">
            <thead>
                <tr>
                    <th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="1%"><?=translate("No. Surat Jalan", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Dari", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Ke", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Tanggal Terima", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Dikirim Oleh", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
</div>


