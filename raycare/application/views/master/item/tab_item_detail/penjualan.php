<?php
    $check_is_sale;
    if ($flag == "add") {
        $check_is_sale = '';
    }else{
        if($form_data['is_sale'] == '1'){
            $check_is_sale = 'checked';
        }else{
            $check_is_sale = '';
        }
    }
    
?>
<div class="form-group">
    <div class="col-md-12 hidden">
        <input type="checkbox" <?=$check_is_sale?> name="is_sales"><span><?=translate("Gunakan Item Ini Untuk di Jual", $this->session->userdata("language"))?></span>
    </div>
</div>
<div class="portlet light">
	<div class="portlet-title">
        <div class="caption">
            <?=translate("Setup Penjualan", $this->session->userdata("language"))?>
        </div>
		<div class="actions">
            <a data-toggle="modal" data-target="#popup_modal" href="<?=base_url()?>master/item/modal_harga_jual/<?=$flag?>" class="btn btn-circle btn-default">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Harga", $this->session->userdata("language"))?>
                </span>
            </a>
            <a id="refresh_table_penjualan" class="btn btn-circle btn-default hidden">
                <i class="fa fa-undo"></i>
                <span class="hidden-480">
                     <?=translate("Refresh", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_satuan_harga">
			<thead>
				<tr>
                    <th class="text-center"><?=translate("Item Id", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Satuan Id", $this->session->userdata("language"))?></th>
					<th class="text-center" width="30%"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
					<th class="text-center" width="30%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="40%"><?=translate("Harga", $this->session->userdata("language"))?></th>
				</tr>
			</thead>
            <tbody>
            </tbody>
		</table>
	</div>
</div>

<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog">
       <div class="modal-content">

       </div>
   </div>
</div>


