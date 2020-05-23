<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-user-follow font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pasien", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			
				<div class="btn-group">
            <?php
            	//tambahkan data ke tabel fitur_tombol. Field page="user_level", button="add"
            	$user_level_id = $this->session->userdata('level_id');
            	
            	$data = '<a href="'.base_url().'master/pasien/add" class="btn green"> <i class="fa fa-plus"></i> <span class="hidden-480"> '.translate("Tambah", $this->session->userdata("language")).' </span> </a>';
            	echo restriction_button($data, $user_level_id, 'master_pasien','add');
            ?>
            </div>
        </div>
	</div>
	<div class="portlet-body">
		<div class="portlet light">
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Cabang", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<?php

									$cabang = $this->cabang_m->get_by(array('is_active' => '1', 'tipe' => 1));
									// die_dump($this->db->last_query());
									$cabang_option = array(
									    0 => translate('Semua', $this->session->userdata('language'))
									);

									foreach ($cabang as $data)
									{
									    $cabang_option[$data->id] = $data->nama;
									}
									echo form_dropdown('cabang_id', $cabang_option, '', 'id="cabang_id" class="form-control"');
								?>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Bulan Lahir", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="month_year" name="month_year" required  readonly >
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-12" style="color:white;">-</label>
							<div class="col-md-12">
								<a class="btn btn-info btn-block" id="refresh"><i class="fa fa-search"></i> <span class="hidden-480"><?=translate("Cari", $this->session->userdata("language"))?></span> </a>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-12" style="color:white;">-</label>
							<div class="col-md-12">
								<a class="btn btn-danger btn-block" id="reset"><i class="fa fa-refresh"></i> <span class="hidden-480"><?=translate("Reset", $this->session->userdata("language"))?></span> </a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<table class="table table-striped table-hover" id="table_pasien">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No.Kartu", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tempat, Tanggal Lahir", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Alamat", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tempat Daftar", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
