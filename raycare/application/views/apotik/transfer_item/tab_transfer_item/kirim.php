<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Kirim Item", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">
            <!-- <a href="<?=base_url()?>master/user_level/add" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a> -->
            <div class="btn-group">
                <?php 

                    $user_id = $this->session->userdata('user_id');
                    // die_dump($user_id);

                    $get_gudang = $this->gudang_m->get_by(array('is_active' => 1 ));
                        // die_dump($get_gudang);

                    $get_gudang = object_to_array($get_gudang);

                    $gudang_option = array();

                    foreach ($get_gudang as $data) {
                        $gudang_option[$data['id']] = $data['nama'];
                    }

                    echo form_dropdown('gudang', $gudang_option, "", "id=\"gudang\" class=\"form-control\""); 
                ?>
                
            </div>
    
            <?php
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="add"
                $user_level_id = $this->session->userdata('level_id');
                
                $data = '<a href="'.base_url().'apotik/transfer_item/history_transfer_item" class="btn btn-circle btn-default"> <i class="fa fa-history"></i> <span class="hidden-480"> '.translate("History", $this->session->userdata("language")).'</span> </a>';
                echo restriction_button($data,$user_level_id,'transfer_item','history');
            ?>
            <?php
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="add"
                $user_level_id = $this->session->userdata('level_id');
                
                $data = '<a href="'.base_url().'apotik/transfer_item/add_kirim_item/WH-05-2016-001" class="btn btn-circle btn-default add-kirim" > <i class="fa fa-plus"></i> <span class="hidden-480"> '.translate("Tambah", $this->session->userdata("language")).'</span> </a>';
                echo restriction_button($data,$user_level_id,'transfer_item','add_kirim_item');
            ?>
        </div>
    </div>
    <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover" id="table_kirim_item">
            <thead>
                <tr>
                    <th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("No. Transfer", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("No. Surat Jalan", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Dari", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Ke", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Dikirim Oleh", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
</div>