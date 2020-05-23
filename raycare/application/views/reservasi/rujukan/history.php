<?php

	$td_filter = '<tr role="row" class="filter"> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"> 
                        <select name="order_status" id="status" class="form-control form-filter input-sm order_status"> 
                            <option value="0">'.translate("Semua", $this->session->userdata("language")).'</option> 
                            <option value="1">'.translate("Diproses", $this->session->userdata("language")).'</option> 
                            <option value="2">'.translate("Kadaluarsa", $this->session->userdata("language")).'</option> 
                            <option value="3">'.translate("Sedang Dalam Proses", $this->session->userdata("language")).'</option> 
                        </select> </div> 
                    </td> 
                    <td><div class="text-center"></div></td> 
                </tr>';

?>
<div class="row">

    <div class="col-md-12">

		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Rujukan", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
		            <a href="javascript:history.go(-1)" class="btn default">
			                <span class="hidden-480">
		                     <?=translate("Kembali", $this->session->userdata("language"))?>
		                </span>
		            </a>
		        </div>
			</div>
			<div class="form-group hidden">
				<div class="col-md-5">
					<div class="input-group date" id="date">
						<input type="text" class="form-control" id="date" name="date" value="<?=date('Y-m-d')?>" readonly >
						<span class="input-group-btn">
							<button class="btn default date-set" type="button" >
								<i class="fa fa-calendar"></i></button>
						</span>
					</div>
				</div>
			</div>
			<div class="portlet-body">
		        <div id="thead-filter-template" class="hidden"><?=htmlentities($td_filter)?></div>
				<table class="table table-striped table-bordered table-hover" id="table_history_rujukan">
					<thead>
						<tr class="heading">
							<th scope="col" ><div class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></div></th>
							<th scope="col" ><div class="text-center"><?=translate("Poliklinik Asal", $this->session->userdata("language"))?></div></th>
							<th scope="col" ><div class="text-center"><?=translate("Poliklinik Tujuan", $this->session->userdata("language"))?></div></th>
							<th scope="col" width="5%"><div class="text-center"><?=translate("Tanggal Di Rujuk", $this->session->userdata("language"))?></div></th>
							<th scope="col" width="5%"><div class="text-center"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?></div></th>
							<th scope="col" ><div class="text-center"><?=translate("Status", $this->session->userdata("language"))?></div></th>
							<th scope="col" ><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>
						</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
