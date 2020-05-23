<div class="row">
    <div class="portlet">
        <div class="portlet-body">
        	<div class="col-md-12">
		        <div class="portlet">
		            <div class="portlet-body form">
		                <form class="form-horizontal">
		                    <div class="form-body">
		                        <div class="form-group col-md-6">
		                            <label class="control-label col-md-4"><?=translate("Kategori :", $this->session->userdata("language"))?></label>
		                            <div class="col-md-6">
		                                <select class="bs-select form-control" id="select_so_history">
		                                    <option value="0" ><?=translate("All", $this->session->userdata("language"))?></option>
		                                    <option value="1" ><?=translate("Alat Kesehatan", $this->session->userdata("language"))?></option>
		                                    <option value="2" ><?=translate("Obat - obatan", $this->session->userdata("language"))?></option>
		                                </select>
		                            </div>
		                        </div>

		                        <div class="form-group col-md-6">
		                            <div class="col-md-6" style="float: right; padding-bottom: 10px;">
										<?php 

											$get_gudang = $this->gudang_m->get_by(array('is_active' => 1));
												// die_dump(object_to_array($get_gudang));

											$gudang = object_to_array($get_gudang);

											$gudang_option = array(
												'0' => translate('Semua Gudang', $this->session->userdata('language'))
											);
											foreach ($gudang as $data) {
												$gudang_option[$data['id']] = $data['nama'];
											}

											echo form_dropdown('gudang', $gudang_option, "", "id=\"gudang\" class=\"form-control\""); 
										?>
									</div>
		                        </div>
		                        
		                    </div>
		                </form>
		            </div>
		        </div>
		    </div>
			<div class="col-md-12">
		        <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_search">
		            <thead>
		                <tr role="row" class="heading">
		                    <th><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
		                    <th><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
		                   <!--  <th><div class="text-center"><?=translate("Stok", $this->session->userdata("language"))?></div></th> -->
		                   <!--  <th><div class="text-center"><?=translate("Unit", $this->session->userdata("language"))?></div></th> -->
		                    <!-- <th><div class="text-center"><?=translate("Harga", $this->session->userdata("language"))?></div></th> -->
		                    <th><div class="text-center"><?=translate("Tipe Item", $this->session->userdata("language"))?></div></th>
		                    <th><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
		                    <th><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>
		                </tr>
		            </thead>
		            <tbody>
		            </tbody>
		        </table>
		    </div>
		</div>
	</div>
</div>
