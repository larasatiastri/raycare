
<?php
    $btn_del    = '<button class="btn red-intense del-this3" title="Delete Purchase Item"><i class="fa fa-times"></i></button>';
    $item_cols = array(
	    'item_code'   => '<input type="hidden" id="paket_id_{0}" name="paket[{0}][idpaket]"><input type="hidden" id="paket_harga_{0}" name="paket[{0}][harga]"><input type="text" id="paket_nama_{0}" name="paket[{0}][namapaket]" class="form-control" readonly style="background-color: transparent;border: 0px solid;">',
	    'add'	=>'<div class="text-center inline-button-table"><a  class="btn blue tambahpkt" name="tambahpaket" id="tambahpaket" ><i class="fa fa-search"></i></a>'.$btn_del.'</div>',
	);

	// gabungkan $item_cols jadi string table row
	$item_row_template3 =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$td = explode('_', $form_data_daftar['tekanan_darah']);
?>
		<div class="portlet light bordered">
			 <div class="portlet-title">
			 	<div class="caption">
			 		<?=translate("Form Input", $this->session->userdata("language"))?>
			 	</div>
			 </div>
			<div class="portlet-body form">
				<div class="form-body">
					 <div class="row">
					 	<div class="col-md-3">
					 		<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Berat Badan Kering", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<div class="input-group">
										<input type="text" id="berat_kering" name="berat_kering" class="form-control" required="required" value="<?=$form_data3[0]['berat_badan_kering']?>">
										<span class="input-group-addon">
											Kg
										</span>
									</div>
								</div>
								 
							</div>
					 	</div>
					 	<div class="col-md-3">
					 		<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Berat Badan Awal", $this->session->userdata("language"))?> :</label>
								<div class="col-md-12">
									<div class="input-group">
										<input type="text" id="berat" name="berat" class="form-control" required="required" value="<?=$form_data_daftar['berat_badan']?>"> 
										<span class="input-group-addon">
											Kg
										</span>
									</div>
								</div>
							</div>
					 	</div>
					 	<div class="col-md-6">
					 		<div class="form-group">
		            			<label class="col-md-12 bold"><?=translate("Tekanan Darah Awal", $this->session->userdata("language"))?></label>
		            			<div class="col-md-12">
		                			<div class="input-group">
		                    		<?php
		                        			$tda = array(
		                            		"name"          => "tdatas",
		                            		"id"            => "tdatas",
		                            		"required"      => "required",
		                            		 "size"          => "5",
		                            	 	"class"         => "form-control", 
		                            		"maxlength"     => "255", 
		                            		"value"         => $td[0]
		                        			);
		                        		echo form_input($tda);
		                    		?>
		                			
		                			<span class="input-group-addon">/</span>
		                    		<?
		                        		$tdb = array(
		                            		"name"          => "tdbawah",
		                            		"required"      => "required",
		                            		"id"            => "tdbawah",
		                            		  "class"         => "form-control", 
		                            		 "size"          => "5",
		                            		"maxlength"     => "255", 
		                            		"value"         => $td[1]
		                        		);
		                        		echo form_input($tdb);
		                    		?>
		                    		</div>
		            			</div>
		             	
		        			</div>
					 	</div>
					 </div>

					<div class="row">
					 	<div class="col-md-3">
							 <div class="form-group">
								<label class="col-md-12 bold"><?=translate("Time of Dialysis", $this->session->userdata("language"))?> :</label>
							 
								<div class="col-md-12">
									<div class="input-group">
										<input type="number" id="time_dialisis" name="time_dialisis" class="form-control" step="0.25" required="required" size="16" maxlength="255" value="4"> 
										<span class="input-group-addon">
											<i>&nbsp;Hour(s)&nbsp;</i>
										</span>	 
									</div>
									<span class="help">
										Gunakan angka dengan satuan jam. Jika waktu tindakan 4 Jam 30 Menit, inputkan 4.5
									</span>	
								</div>
							</div>
					 	</div>
					 	<div class="col-md-3">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Quick of Blood", $this->session->userdata("language"))?> :</label>
							 
								<div class="col-md-12">
									<div class="input-group">
										<input type="text" id="qb" name="qb" class="form-control" required="required" size="16" maxlength="255" value="0"> 
										<span class="input-group-addon">
											<i>&nbsp;ml/Hour&nbsp;</i>
										</span>
									</div>
								</div>
									 
									 
								 
							</div>
					 	</div>
					 	<div class="col-md-3">
							 	<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Quick of Dialysate", $this->session->userdata("language"))?> :</label>
							 
								<div class="col-md-12">
									<div class="input-group">
										<input type="text" id="qd" name="qd" class="form-control" required="required" size="16" maxlength="255" value="500"> 
										<span class="input-group-addon">
											<i>&nbsp;ml/Hour&nbsp;</i>
										</span>
										</div>
								</div>
									 
									 
								 
							</div> 
					 	</div>
					 	<div class="col-md-3">
					 	<div class="form-group">
						<label class="col-md-12 bold"><?=translate("UF Goal", $this->session->userdata("language"))?> :</label>
					 
						<div class="col-md-12">
							<div class="input-group">
								<input type="text" id="ufg" name="ufg" class="form-control" required="required" size="16" maxlength="255" value="0"> 
								<span class="input-group-addon">
									<i>&nbsp;Liter(s)&nbsp;</i>
								</span>
								</div>
						</div>
							 
							 
						 
					</div> 
					 	</div>
					</div>
					
					

					
        			
					
					
					 
					 <div class="form-group">
						<label class="col-md-12 bold"><?=translate("Keluhan Pasien :", $this->session->userdata("language"))?> </label>
						<div class="col-md-12">
							<?php
								$assessment_cgs = array(
				                    "name"			=> "keluhan",
				                    "id"			=> "keluhan",
				                    "cols"			=> 32,
									"rows"			=> 5,
				                    "maxlength"		=> "255",
				                    "class"			=> "form-control",
				                    "placeholder"	=> translate("Keluhan Pasien", $this->session->userdata("language")), 
				                   
				                    // "value"			=> $assessment_cgs_value
				                );
			                echo form_textarea($assessment_cgs);
							?>
						</div>				  
					</div>
					<?php
					// 	if($form_data10 == '')
					// {
						?>
						<div class="form-group">
							<label class="col-md-2 bold"><?=translate("Penyakit Bawaan", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-4">
								<?php
									$penyakit_bawaan = $this->penyakit_m->get_by(array('tipe' => 1));
									$penyakit_bawaan_array = object_to_array($penyakit_bawaan);
									
									$penyakit_bawaan_option = array(
									);

									foreach ($penyakit_bawaan_array as $select) {
								        $penyakit_bawaan_option[$select['id']] = $select['nama'];
								    }

									echo form_dropdown('penyakit_bawaan[]', $penyakit_bawaan_option, '', "id=\"multi_select_penyakit_bawaan\" class=\"multi-select\" multiple=\"multiple\"");
										
								?>
							</div>
							
						</div>

						<div class="form-group">
							<label class="col-md-2 bold"><?=translate("Penyakit Penyebab", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-4">
								<?php
									$penyakit_penyebab = $this->penyakit_m->get_by(array('tipe' => 2));
									$penyakit_penyebab_array = object_to_array($penyakit_penyebab);
									
									$penyakit_penyebab_option = array(
									);

								    foreach ($penyakit_penyebab_array as $select) {
								        $penyakit_penyebab_option[$select['id']] = $select['nama'];
								    }
									echo form_dropdown('penyakit_penyebab[]', $penyakit_penyebab_option, '', "id=\"multi_select_penyakit_penyebab\" class=\"multi-select\" multiple=\"multiple\"");
										
								?>
							</div>
							
						</div>
					<?php
					// }
					?>
					
				</div>	
			</div>
		</div>
		<div class="portlet light bordered" id="section-diagnosis">
			<div class="portlet-title">
				<div class="caption">
					<?=translate("Diagnosis ICD", $this->session->userdata("language"))?>
				</div>
				<div class="actions">
					<a class="btn btn-icon-only btn-default btn-circle add-diagnose">
						<i class="fa fa-plus"></i>
					</a>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
				<?php				
						$form_diagnosis = '
						<div class="form-group">
							<label class="control-label col-md-3">'.translate("Diagnosis Awal", $this->session->userdata("language")).' :<span class="required">*</span></label>
							<div class="col-md-9">
								<input type="hidden" class="form-control" required id="diagnosa_awal_code_ast{0}" name="diagnosa_awal[{0}][code_ast]">
								<input type="hidden" class="form-control" id="diagnosa_awal_is_deleted{0}" name="diagnosa_awal[{0}][is_deleted]">
								<div class="input-group">
									<input class="form-control" required id="diagnosa_awal_name{0}" name="diagnosa_awal[{0}][name]" placeholder="Nama Penyakit" readonly>
									<span class="input-group-btn">
										<a class="btn btn-primary search" id="btn_search_diagnosa_awal_{0}" title="'.translate('Cari', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>
										<a class="btn red-intense del-this" id="btn_delete_diagnosa_awal_{0}" title="'.translate('Hapus', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="table-scrollable hidden" id="div_table_diagnosa">
						<table class="table table-hover table-light " id="table_diagnosa">
							<thead>
								<tr class="uppercase">
									<th class="text-center">'.translate("Kode", $this->session->userdata("language")).' </th>
									<th class="text-center">'.translate("Nama", $this->session->userdata("language")).' </th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						</div>
						
						';
					?>

					<input type="hidden" id="tpl-form-diagnosis" value="<?=htmlentities($form_diagnosis)?>">
					<div class="form-body">
						<ul class="list-unstyled">
						</ul>
					</div>
				</div>
			</div>
		</div><!-- end of <div class="portlet light bordered"> -->
		<div class="portlet light bordered hidden" id="section-alamat" style="margin-top:15px;">
			<div class="portlet-title">
				<div class="caption">
					<?=translate('Paket', $this->session->userdata('language'))?>
				</div>
			</div>
			<div class="portlet-body">
				<span id="tpl_item_row3" class="hidden"><?=htmlentities($item_row_template3)?></span>
				<table class="table table-striped table-bordered table-hover" id="table_paket">
					<thead>
						<tr role="row">
							<th class="text-center" ><?=translate("Paket", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
						</tr>
					</thead>
					<tbody>
					 
					</tbody>
				</table>
			</div>
		</div><!-- end of <div class="portlet light bordered" id="section-alamat"> -->

			 

<?php $msg = translate("Apakah anda yakin akan membuat data pasien ini?",$this->session->userdata("language"));?>
<div id="popover_item_content_paket" style="display:none" >
 	<div class="portlet light">
		<table class="table table-striped table-bordered table-hover" id="table_obat222">
			<thead>
				<tr role="row">
						<th class="text-center"><?=translate("Tipe Paket", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Nama Paket", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>		
</div>
	 

 




