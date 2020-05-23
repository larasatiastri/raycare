<div class="portlet light">
	<div class="portlet-body form">
		<?php
			 
 
		    $btn_del    = '<div class="text-center"><button class="btn btn-sm red-intense del-this3" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';
		    $item_cols = array(
			    'item_code'   => '<input type="hidden" id="paket_id1_{0}" name="paket2[{0}][id]"><input type="hidden" id="paket_flag_{0}" name="paket2[{0}][flag]"><input type="hidden" id="paket_id_{0}" name="paket2[{0}][idpaket]"><input type="hidden" id="paket_harga_{0}" name="paket2[{0}][harga]"> <input type="text" id="paket_nama_{0}" name="paket2[{0}][namapaket]"  class="form-control" readonly style="background-color: transparent;border: 0px solid;">   ',
			    'add'	=>'<a  class="btn blue tambahpkt" name="tambahpaket" id="tambahpaket" ><i class="fa fa-search"></i></a>',
			    'action'      => $btn_del,
			);

			// gabungkan $item_cols jadi string table row
			$item_row_template3 =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
		    
			

		    
		 //    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			// $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			// $flash_form_data  = $this->session->flashdata('form_data');
			// $flash_form_error = $this->session->flashdata('form_error');
		?>

		 
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Umum", $this->session->userdata("language"))?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			 
	 		<input type="hidden" id="transid" name="transid" value="<?=$pk?>">
			<div class="row">
				<div class="col-md-6">
					<div class="portlet light">
						 
						<div class="portlet-body form">
							<div class="form-body">
								 
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Tanggal Daftar", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label bold"><?=date('d M Y',strtotime($form_data[0]['created_date']))?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Dokter Penanggung Jawab", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label bold"><?=$form_data[0]['nama']?></label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Frekuensi Perawatan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$form_data[0]['jangka_waktu']?></label> kali dalam minggu ini<input type="hidden" id="freq" name="freq" value="<?=$form_data[0]['jangka_waktu']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Berat Badan Kering", $this->session->userdata("language"))?> :</label>
									<div class="row">
											<div class="col-md-2">
													 <input type="text" id="berat_kering" name="berat_kering" class="form-control" required="required" value="<?=$form_data3[0]['berat_badan_kering']?>"> 
											</div>
											<div class="col-md-2">
												<label class="control-label">Kg</label>
											</div>
										</div>
									 
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Berat Badan Awal", $this->session->userdata("language"))?> :</label>
									<div class="row">
											<div class="col-md-2">
													 <input type="text" id="berat" name="berat" class="form-control" required="required" value="<?=$form_data[0]['berat_awal']?>"> 
											</div>
											<div class="col-md-2">
												<label class="control-label">Kg</label>
											</div>
										</div>
									 
								</div>
								

								<div class="form-group">
                        			<label class="control-label col-md-4"><?=translate("Tekanan Darah Awal", $this->session->userdata("language"))?></label>
                        
                        			<div class="col-md-2">
                            		<?php
                                			$tda = array(
                                    		"name"          => "tdatas",
                                    		"id"            => "tdatas",
                                    		"required"      => "required",
                                    		 "size"          => "5",
                                    	 	"class"         => "form-control", 
                                    		"maxlength"     => "255", 
                                    		"value"         => explode("_",$form_data10[0]['blood_preasure'])[0]
                                			);
                                		echo form_input($tda);
                            		?>
                        			</div>
                        			<label class="col-md-1">/</label>
                        			<div class="col-md-2">
                            		<?
                                		$tdb = array(
                                    		"name"          => "tdbawah",
                                    		"required"      => "required",
                                    		"id"            => "tdbawah",
                                    		"class"         => "form-control", 
                                    		"size"          => "5",
                                    		"maxlength"     => "255", 
                                    		"value"         =>  explode("_",$form_data10[0]['blood_preasure'])[1]
                                		);
                                		echo form_input($tdb);
                            		?>
                        		</div>
                         	
                    			</div>
                    			<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Time of Dialysis", $this->session->userdata("language"))?> :</label>
								 
									<div class="col-md-5">
										<div class="input-group">
											<input type="text" id="time_dialisis" name="time_dialisis" class="form-control" required="required" size="16" maxlength="255" value="<?=date('H:i')?>"> 
											<span class="input-group-addon">
												<i>&nbsp;Hour(s)&nbsp;</i>
											</span>	 
										</div>
									</div>
									
										 
									 
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Quick of Blood", $this->session->userdata("language"))?> :</label>
								 
									<div class="col-md-5">
										<div class="input-group">
											<input type="text" id="qb" name="qb" class="form-control" required="required" size="16" maxlength="255" value="0"> 
											<span class="input-group-addon">
												<i>&nbsp;ml/Hour&nbsp;</i>
											</span>
										</div>
									</div>
										 
										 
									 
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Quick of Dialysate", $this->session->userdata("language"))?> :</label>
								 
									<div class="col-md-5">
										<div class="input-group">
											<input type="text" id="qd" name="qd" class="form-control" required="required" size="16" maxlength="255" value="0"> 
											<span class="input-group-addon">
												<i>&nbsp;ml/Hour&nbsp;</i>
											</span>
											</div>
									</div>
										 
										 
									 
								</div> 
								 <div class="form-group">
									<label class="control-label col-md-4"><?=translate("UF Goal", $this->session->userdata("language"))?> :</label>
								 
									<div class="col-md-5">
										<div class="input-group">
											<input type="text" id="ufg" name="ufg" class="form-control" required="required" size="16" maxlength="255" value="0"> 
											<span class="input-group-addon">
												<i>&nbsp;Liter(s)&nbsp;</i>
											</span>
											</div>
									</div>
										 
										 
									 
								</div> 
								 <div class="form-group">
								<label class="control-label col-md-4"><?=translate("Keluhan Pasien :", $this->session->userdata("language"))?> </label>
								
								<div class="col-md-5">
									<?php
										$assessment_cgs = array(
						                    "name"			=> "keluhan",
						                    "id"			=> "keluhan",
						                    "cols"			=> 32,
											"rows"			=> 5,
						                    "maxlength"		=> "255",
						                    "class"			=> "form-control",
						                    "placeholder"	=> translate("Keluhan Pasien", $this->session->userdata("language")), 
						                   
						                     "value"			=> $form_data10[0]['assessment_cgs']
						                );
					                echo form_textarea($assessment_cgs);
									?>
								</div>
								  
								</div>
								 
								
						</div>	
					</div>
				</div>
				<div class="portlet light" id="section-alamat">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Item Tersimpan', $this->session->userdata('language'))?></span>
							</div>
							 
						</div>
						<input type="hidden" id="pk" name="pk" value="<?=$pk?>">
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="table_cabang">
							<thead>
							<tr role="row" class="heading">
									<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Nama Item", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
									 
								</tr>
								</thead>
								<tbody>
								<tr>
	                    			<td><div class="text-center">Budi Johan</div></td>
	                    			<td><div class="text-left">Jakarta, 13 Jan 1977</div></td>
	                    			<td><div class="text-center">Lorem ipsum dolor asmet</div></td>
	                    			 
	                			</tr>
	               				<tr>
	                    			<td><div class="text-center">Sasmi Dora</div></td>
	                    			<td><div class="text-left">Jakarta, 7 Jun 1934</div></td>
	                    			<td><div class="text-center">Lorem ipsum dolor asmet</div></td>
	                    			 
	                			</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-6">

					<div class="portlet light" id="section-telepon">
						<div class="row">
						<div class="col-md-9">
							<div class="portlet-body form">
							<div class="form-body">
								 
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-8">
										 <label class="control-label bold"><?=$form_data3[0]['nama']?></label>
									</div>
									
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-8">
										 <label class="control-label bold"><?=$form_data3[0]['alamat']?></label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Gender", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-8">
										 <label class="control-label bold"><?=$form_data3[0]['gender']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Umur", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-8"> 
										  <label class="control-label bold"><? if(floor($form_data3[0]['usia']/365)==0){?> < 1 Tahun <?}else{?><?=floor($form_data3[0]['usia']/365)?> Tahun<?}?> </label> 
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Telepon", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-8">
										  <label class="control-label bold"><?=$form_data4[0]['nomor']?></label>
									</div>
								</div>
								 <div class="form-group">
									<label class="control-label col-md-4"><?=translate("Tanggal Registrasi", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-8">
										  <label class="control-label bold"><?=date('d M Y',strtotime($form_data3[0]['tanggal_registrasi']))?></label>
									</div>
								</div>
								
						</div>	
						</div>
						</div>
						<div class="col-md-3">
							<div class="col-md-3">
								<table width="100px" border="1" style="border-color:black">
									<tr>
										<td style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px"><img src="<?=base_url()?>assets/mb/var/temp/thumbs/FHCDUF_M-2.jpg" style="max-width:100px"></td>
									</tr>
								</table>
										
									</div>
						</div>
					</div>

						
					</div>
					<div class="portlet light" id="section-alamat">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Paket', $this->session->userdata('language'))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<span id="tpl_item_row3" class="hidden"><?=htmlentities($item_row_template3)?></span>
							<table class="table table-striped table-bordered table-hover" id="table_paket">
							<thead>
							<tr role="row" class="heading">
									<th class="text-center" colspan="2"><?=translate("Paket", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
									 
								 
								</tr>
								</thead>
								<tbody>
								 
								</tbody>
								<!-- <tfoot>
										<th class="text-right">
											 <a  class="btn blue tambahpkt" name="tambahpaket" id="tambahpaket" >
	                						<i class="fa fa-search"></i>
	                						 
	            						</a>
										</th>
										<th class="text-center"></th>
								</tfoot> -->	
							</table>
						</div>
					</div>
				</div>
					
			</div>
 

		<?php $msg = translate("Apakah anda yakin akan membuat data pasien ini?",$this->session->userdata("language"));?>
	 
	 
	</div>
</div>

<div id="popover_item_content_paket" style="display:none" >
 <div class="portlet light">
				 
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("SEARCH PAKET", $this->session->userdata("language"))?></span>
							</div>
						</div>
					 
	<div class="portlet-body form">

					 <div class="form-body">

						 <table class="table table-striped table-bordered table-hover" id="table_obat222">
							<thead>
							<tr role="row" class="heading">
									<th class="text-center"><?=translate("Tipe Paket", $this->session->userdata("language"))?> </th>
									 
									<th class="text-center"><?=translate("Nama Paket", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
									 
									<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
								 
								</tr>
								</thead>
								<tbody>
								<tr>
	                    			<td><div class="text-left">Budi Johan</div></td>
	                    			<td><div class="text-center">Jakarta, 13 Jan 1977</div></td>
	                    			<td><div class="text-left">Budi Johan</div></td>
	                    			<td><div class="text-center">Jakarta, 13 Jan 1977</div></td>
	                    			 
	                			</tr>
	               				<tr>
	                    			<td><div class="text-left">Sasmi Dora</div></td>
	                    			<td><div class="text-center">Jakarta, 7 Jun 1934</div></td>
	                    			<td><div class="text-left">Budi Johan</div></td>
	                    			<td><div class="text-center">Jakarta, 13 Jan 1977</div></td>
	                    			 
	                			</tr>
								</tbody>
							</table>
			
				</div>
			
				</div>
			</div>	
		</div>
	 

 




