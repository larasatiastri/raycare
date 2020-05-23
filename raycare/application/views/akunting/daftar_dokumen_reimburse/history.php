<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="fa fa-list font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Dokumen Reimburse", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>akunting/daftar_dokumen_reimburse/history" class="btn btn-circle btn-default">
                <i class="fa fa-history"></i>
                <span class="hidden-480">
                     <?=translate("History", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div> 
	<!--end of <div class="portlet-title">-->
	<div class="portlet-body form">
		<table class="table table-striped table-bordered table-hover" id="table_daftar_dokumen_reimburse_history">
		<thead>
		<tr class="heading">
			<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("User", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Tgl. Permintaan", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("No. Permintaan", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("No.Dokumen", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Tgl.Dokumen ", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Nominal ", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Keterangan ", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="5%"><?=translate("Dokumen", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div> <!--end of <div class="portlet-body form">-->
</div> <!--end of <div class="portlet light">-->