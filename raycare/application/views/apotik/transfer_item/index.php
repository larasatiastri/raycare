<div class="tabbable-custom nav-justified">
    <ul class="nav nav-tabs nav-justified">
        <li class="hidden">
            <a href="#permintaan" data-toggle="tab">
                <?=translate('Daftar Permintaan Kirim Item', $this->session->userdata('language'))?> </a>
        </li>
        <li class="active">
            <a href="#kirim" data-toggle="tab">
                <?=translate('Daftar Kirim Item', $this->session->userdata('language'))?> </a>
        </li>
        <li>
            <a href="#terima" data-toggle="tab">
                <?=translate('Daftar Terima Item', $this->session->userdata('language'))?> </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane hidden" id="permintaan" >
            <?php include('tab_transfer_item/permintaan.php') ?>
        </div>
        <div class="tab-pane active" id="kirim" >
        	 <?php include('tab_transfer_item/kirim.php')	 ?> 
        </div>
        <div class="tab-pane " id="terima" >
        	 <?php include('tab_transfer_item/terima.php') ?>
        </div>
    </div>
</div>

<div id="popover_item_content" class="row">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-body">
		        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item">
		            <thead>
		                <tr>
		                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>
		            </tbody>
		        </table>
		    </div>
		</div>
	</div>
</div>


<?=form_close();?>


<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-md" style="width: 480px !important;">
       <div class="modal-content">

       </div>
   </div>
</div>
