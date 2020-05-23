<?php
	$form_attr = array(
	    "id"            => "form_harga_jual", 
	    "name"          => "form_harga_jual", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);

    $option_kategori = array(
        '0'  => '---'.translate('Semua Kategori', $this->session->userdata('language')).'---'
    );
    $result_kategori = $this->item_kategori_m->get_by(array('is_active' => 1));
    foreach($result_kategori as $row)
    {
        $option_kategori[$row->id] = $row->nama;
    }

    $option_sub_kategori = array(
        '0'  => '---'.translate('Semua Sub Kategori', $this->session->userdata('language')).'---'
    );

?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-list font-blue-sharp bold"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Harga Jual", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a class="btn btn-circle blue-chambray hidden" id="btn-edit"> <i class="fa fa-edit"></i> <span class="hidden-480"> <?=translate("Edit", $this->session->userdata("language"))?> </span> </a>
            <a class="btn btn-circle btn-primary hidden" id="btn-save" data-confirm="<?=$confirm_save?>"> <i class="fa fa-save"></i> <span class="hidden-480"> <?=translate("Save", $this->session->userdata("language"))?> </span> </a>
			<button type="submit" id="save" class="btn btn-primary hidden" >Save</button>
        </div>
	</div>
	<div class="portlet-body form">
		<div class="row"> 
			<div class="col-md-12">
                <div class="portlet box blue-madison">
                    <div class="portlet-title" style="margin-bottom: 0px !important;">
                        <div class="caption">
                            <!-- <i class="fa fa-cogs font-green-sharp"></i> -->
                            <span class="caption-subject"><?=translate("Filter", $this->session->userdata("language"))?></span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <?php
                                            echo form_dropdown('kategori', $option_kategori, '','id="kategori" class="form-control" required="required"');
                                        ?>  
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                       <?php
                                            echo form_dropdown('sub_kategori', $option_sub_kategori, '','id="sub_kategori" class="form-control" required="required"');
                                        ?>   
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <a id="btn_search" class="btn btn-primary col-md-12">
                                            <i class="fa fa-search"></i>
                                            <?=translate("Cari", $this->session->userdata("language"))?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div> 
                </div>
            </div>

	        <div class="col-md-12"> 
	        	<table class="table table-striped table-bordered table-hover" id="table_harga_jual">
					<thead>
						<tr>
							<th class="text-center" width="1%"><?=translate("No.", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("SK/K", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("HBB", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?></th>
							<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
						</tr>
					</thead>

					<tbody>
					</tbody>

				</table>
	        </div>
		</div>
	</div>	

</div>
<div class="modal fade bs-modal-lg" id="modal_detail" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>
<div class="modal fade bs-modal-lg" id="modal_detail_stok" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>
<?=form_close()?>
