 <div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
			<?=translate("Jadwal Klinik HD", $this->session->userdata("language"))?>
		</div>
		<div class="actions">
		 
            <a href="<?=$this->session->userdata('url_login')?>klinik_hd/jadwal" class="btn btn-circle btn-icon-only btn-default" target="_blank">
                <i class="fa fa-plus"></i>
            </a>
        </div>
	</div>
		 
	<div class="portlet-body">
		 	<div id="tab_jadwal" class="table-scrollable">
		 	</div>
 	</div>
</div>