<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-calendar font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Jadwal Pasien", $this->session->userdata("language"))?></span>
			<span class="caption-helper"><?php echo '<label class="control-label ">'.date('d M Y').'</label>'; ?></span>
		</div>
		<div class="actions">
			<a title="<?=translate('Refresh', $this->session->userdata('language'))?>" id="refresh" name="refresh" class="btn btn-circle btn-default btn-icon-only refresh"><i class="fa fa-undo"></i></a>
			<a title="<?=translate('Refresh', $this->session->userdata('language'))?>" id="refresh_hari" name="refresh_hari" class="btn btn-circle btn-default btn-icon-only refresh_hari hidden"><i class="fa fa-undo"></i></a>
			<a title="<?=translate('Previous', $this->session->userdata('language'))?>" id="prev" name="prev" class="btn btn-circle btn-default btn-icon-only"><i class="fa fa-fast-backward"></i></a>
            <a title="<?=translate('Next', $this->session->userdata('language'))?>" id="next" name="next" class="btn btn-circle btn-default btn-icon-only" ><i class="fa fa-fast-forward"></i></a>
            <a title="<?=translate('Previous', $this->session->userdata('language'))?>" id="prev_hari" name="prev_hari" class="btn btn-circle btn-default btn-icon-only hidden"><i class="fa fa-fast-backward"></i></a>
            <a title="<?=translate('Next', $this->session->userdata('language'))?>" id="next_hari" name="next_hari" class="btn btn-circle btn-default btn-icon-only hidden" ><i class="fa fa-fast-forward"></i></a>
            <input type="hidden" id="text_hari" name="text_hari" value="">
		</div>
	</div>

        	<div id="table_jadwal" class="table-scrollable">
				<table class="table table-striped table-bordered table-hover" id="table_pasien">
				<thead>
				
				<tr>
					<th class="text-center" rowspan="2" style ="vertical-align: middle; font-size: 12px;"><?=translate("No.Bed", $this->session->userdata("language"))?> </th>
					<th class="text-center success" colspan="4" style ="font-size: 12px;">
						<?php
							
							$cabang_id = $this->session->userdata('cabang_id');

							$day = date("w");
							
						    $date = date('d M Y', strtotime('-'.($day-1).' days'));
						   
						    $t_date = strtotime(date('d M Y', strtotime($date)));
						    $senin = date('d-m-Y', $t_date);
						    $senin_ = date('d M Y', $t_date);
						    $mon = date('Y-m-d', $t_date);
						    $next_date = strtotime(date('d M Y', strtotime($senin)). " +1 days");
						    $selasa = date('d-m-Y', $next_date);
						    $tue = date('Y-m-d', $next_date);
						    $next_date_1 = strtotime(date('d M Y', strtotime($senin)). " +2 days");
						    $rabu = date('d-m-Y', $next_date_1);
						    $wed = date('Y-m-d', $next_date_1);
						    $next_date_2 = strtotime(date('d M Y', strtotime($senin)). " +3 days");
						    $kamis = date('d-m-Y', $next_date_2);
						    $thu = date('Y-m-d', $next_date_2);
						    $next_date_3 = strtotime(date('d M Y', strtotime($senin)). " +4 days");
						    $jumat = date('d-m-Y', $next_date_3);
						    $fri = date('Y-m-d', $next_date_3);
						    $next_date_4 = strtotime(date('d M Y', strtotime($senin)). " +5 days");
						    $sabtu = date('d-m-Y', $next_date_4);
						    $sat = date('Y-m-d', $next_date_4);
						    $next_date_5 = strtotime(date('d M Y', strtotime($senin)). " +6 days");
						    $minggu = date('d-m-Y', $next_date_5);
						    $sun = date('Y-m-d', $next_date_5);
						    $valid_date = strtotime(date('d M Y', strtotime($senin)). " +7 days");
						    $validasi = date('d-m-Y', $valid_date);


						    $dn = date('Y-m-d');
						    $pecah1 = explode("-", $dn);
						    $tanggal1 = $pecah1[2];
						    $bulan1 = $pecah1[1];
						    $tahun1 = $pecah1[0];

						    $tgl_awal = date('Y-m-d', strtotime($date));
						    $pecah2 = explode("-", $tgl_awal);
						    $tanggal2 = $pecah2[2];
						    $bulan2 = $pecah2[1];
						    $tahun2 = $pecah2[0];

						    $jd1 = GregorianToJD($bulan1, $tanggal1, $tahun1);
						    $jd2 = GregorianToJD($bulan2, $tanggal2, $tahun2);

						    $selisih = $jd1 - $jd2;
						    $minus = $selisih/7;
						    $min = ((round($minus)-1) < 0)?0:round($minus)-1;
						    

						    if($dn == $validasi || $dn > $validasi){
						    	$t_date = strtotime(date('d M Y', strtotime($date)). " +".$min." week");
							    $senin = date('d-m-Y', $t_date);
							    $senin_ = date('d M Y', $t_date);
							    $next_date = strtotime(date('d M Y', strtotime($senin)). " +1 days");
							    $selasa = date('d-m-Y', $next_date);
							    $next_date_1 = strtotime(date('d M Y', strtotime($senin)). " +2 days");
							    $rabu = date('d-m-Y', $next_date_1);
							    $next_date_2 = strtotime(date('d M Y', strtotime($senin)). " +3 days");
							    $kamis = date('d-m-Y', $next_date_2);
							    $next_date_3 = strtotime(date('d M Y', strtotime($senin)). " +4 days");
							    $jumat = date('d-m-Y', $next_date_3);
							    $next_date_4 = strtotime(date('d M Y', strtotime($senin)). " +5 days");
							    $sabtu = date('d-m-Y', $next_date_4);
							    $next_date_5 = strtotime(date('d M Y', strtotime($senin)). " +6 days");
							    $minggu = date('d-m-Y', $next_date_5);
							    $valid_date = strtotime(date('d M Y', strtotime($senin)). " +7 days");
							    $validasi = date('d-m-Y', $valid_date);
						    }
						    // die_dump($selasa);

						    $tampilan_date_1 = date("(d M Y)", strtotime($senin));
						    $tampilan_date_2 = date("(d M Y)", strtotime($selasa));
						    $tampilan_date_3 = date("(d M Y)", strtotime($rabu));
						    $tampilan_date_4 = date("(d M Y)", strtotime($kamis));
						    $tampilan_date_5 = date("(d M Y)", strtotime($jumat));
						    $tampilan_date_6 = date("(d M Y)", strtotime($sabtu));
						    $tampilan_date_7 = date("(d M Y)", strtotime($minggu));
						     // die(dump($senin_));

							$jml_senin_pagi   = $this->jadwal_m->get_data_jadwal_waktu($mon,'7',$cabang_id)->result_array();
							$jml_senin_siang  = $this->jadwal_m->get_data_jadwal_waktu($mon,'13',$cabang_id)->result_array();
							$jml_senin_sore   = $this->jadwal_m->get_data_jadwal_waktu($mon,'18',$cabang_id)->result_array();
							$jml_senin_malam  = $this->jadwal_m->get_data_jadwal_waktu($mon,'23',$cabang_id)->result_array();
							$jml_selasa_pagi  = $this->jadwal_m->get_data_jadwal_waktu($tue,'7',$cabang_id)->result_array();
							$jml_selasa_siang = $this->jadwal_m->get_data_jadwal_waktu($tue,'13',$cabang_id)->result_array();
							$jml_selasa_sore  = $this->jadwal_m->get_data_jadwal_waktu($tue,'18',$cabang_id)->result_array();
							$jml_selasa_malam = $this->jadwal_m->get_data_jadwal_waktu($tue,'23',$cabang_id)->result_array();
							$jml_rabu_pagi    = $this->jadwal_m->get_data_jadwal_waktu($wed,'7',$cabang_id)->result_array();
							$jml_rabu_siang   = $this->jadwal_m->get_data_jadwal_waktu($wed,'13',$cabang_id)->result_array();
							$jml_rabu_sore    = $this->jadwal_m->get_data_jadwal_waktu($wed,'18',$cabang_id)->result_array();
							$jml_rabu_malam   = $this->jadwal_m->get_data_jadwal_waktu($wed,'23',$cabang_id)->result_array();
							$jml_kamis_pagi   = $this->jadwal_m->get_data_jadwal_waktu($thu,'7',$cabang_id)->result_array();
							$jml_kamis_siang  = $this->jadwal_m->get_data_jadwal_waktu($thu,'13',$cabang_id)->result_array();
							$jml_kamis_sore   = $this->jadwal_m->get_data_jadwal_waktu($thu,'18',$cabang_id)->result_array();
							$jml_kamis_malam  = $this->jadwal_m->get_data_jadwal_waktu($thu,'23',$cabang_id)->result_array();
							$jml_jumat_pagi   = $this->jadwal_m->get_data_jadwal_waktu($fri,'7',$cabang_id)->result_array();
							$jml_jumat_siang  = $this->jadwal_m->get_data_jadwal_waktu($fri,'13',$cabang_id)->result_array();
							$jml_jumat_sore   = $this->jadwal_m->get_data_jadwal_waktu($fri,'18',$cabang_id)->result_array();
							$jml_jumat_malam  = $this->jadwal_m->get_data_jadwal_waktu($tue,'23',$cabang_id)->result_array();
							$jml_sabtu_pagi   = $this->jadwal_m->get_data_jadwal_waktu($sat,'7',$cabang_id)->result_array();
							$jml_sabtu_siang  = $this->jadwal_m->get_data_jadwal_waktu($sat,'13',$cabang_id)->result_array();
							$jml_sabtu_sore   = $this->jadwal_m->get_data_jadwal_waktu($sat,'18',$cabang_id)->result_array();
							$jml_sabtu_malam  = $this->jadwal_m->get_data_jadwal_waktu($sat,'23',$cabang_id)->result_array();
							$jml_minggu_pagi  = $this->jadwal_m->get_data_jadwal_waktu($sun,'7',$cabang_id)->result_array();
							$jml_minggu_siang = $this->jadwal_m->get_data_jadwal_waktu($sun,'13',$cabang_id)->result_array();
							$jml_minggu_sore  = $this->jadwal_m->get_data_jadwal_waktu($sun,'18',$cabang_id)->result_array();
							$jml_minggu_malam = $this->jadwal_m->get_data_jadwal_waktu($sun,'23',$cabang_id)->result_array();
							   // die(dump($senin_));
						?>
						<input type="hidden" id="test" name="test" value="<?=$senin_?>">
						<span class="input-group" style="width: 100%;">
							<input value="Senin <?=$tampilan_date_1?>" class="form-group text-center" id="hari_senin" readonly="readonly" style=" background-color: transparent;border: 0px solid; width: 80%;">
							<!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
							<a class="btn maximize" title="<?=translate('Maximize', $this->session->userdata('language'))?>" style="font-size: 14px;" id="view_senin"><i class="fa fa-search-plus"></i></a>
						</span>
					</th>
					<th class="text-center warning" colspan="4" style ="font-size: 12px;">
						<span class="input-group" style="width: 100%;">
							<input value="Selasa <?=$tampilan_date_2?>" class="form-group text-center" id="hari_selasa" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
							<!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
							<a class="btn maximize" title="<?=translate('Maximize', $this->session->userdata('language'))?>" style="font-size: 14px;" id="view_selasa"><i class="fa fa-search-plus"></i></a>
						</span>
					</th>
					<th class="text-center danger" colspan="4" style ="font-size: 12px;">
						<span class="input-group" style="width: 100%;">
							<input value="Rabu <?=$tampilan_date_3?>" class="form-group text-center" id="hari_rabu" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
							<!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
							<a class="btn maximize" title="<?=translate('Maximize', $this->session->userdata('language'))?>" style="font-size: 14px;" id="view_rabu"><i class="fa fa-search-plus"></i></a>
						</span>
					</th>
					<th class="text-center success" colspan="4" style ="font-size: 12px;">
						<span class="input-group" style="width: 100%;">
							<input value="Kamis <?=$tampilan_date_4?>" class="form-group text-center" id="hari_kamis" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
							<!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
							<a class="btn maximize" title="<?=translate('Maximize', $this->session->userdata('language'))?>" style="font-size: 14px;" id="view_kamis"><i class="fa fa-search-plus"></i></a>
						</span>
					</th>
					<th class="text-center warning" colspan="4" style ="font-size: 12px;">
						<span class="input-group" style="width: 100%;">
							<input value="Jumat <?=$tampilan_date_5?>" class="form-group text-center" id="hari_jumat" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
							<!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
							<a class="btn maximize" title="<?=translate('Maximize', $this->session->userdata('language'))?>" style="font-size: 14px;" id="view_jumat"><i class="fa fa-search-plus"></i></a>
						</span>
					</th>
					<th class="text-center danger" colspan="4" style ="font-size: 12px;">
						<span class="input-group" style="width: 100%;">
							<input value="Sabtu <?=$tampilan_date_6?>" class="form-group text-center" id="hari_sabtu" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
							<!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
							<a class="btn maximize" title="<?=translate('Maximize', $this->session->userdata('language'))?>" style="font-size: 14px;" id="view_sabtu"><i class="fa fa-search-plus"></i></a>
						</span>
					</th>
					<th class="text-center" colspan="4" style ="font-size: 12px;">
						<span class="input-group" style="width: 100%;">
							<input value="Minggu <?=$tampilan_date_7?>" class="form-group text-center" id="hari_minggu" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
							<!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
							<a class="btn maximize" title="<?=translate('Maximize', $this->session->userdata('language'))?>" style="font-size: 14px;" id="view_minggu"><i class="fa fa-search-plus"></i></a>
						</span>
					</th>
				</tr>
				<tr>
					<?php
						for ($i=1; $i <= 7 ; $i++) 
						{
							if($i == 1 || $i == 4)
							{
								echo  '<th class="text-center success" style ="font-size: 12px;">'.translate("Pagi", $this->session->userdata("language")).'</th>
								<th class="text-center success" style ="font-size: 12px;">'.translate("Siang", $this->session->userdata("language")).'</th>
								<th class="text-center success" style ="font-size: 12px;">'.translate("Sore", $this->session->userdata("language")).'</th>
								<th class="text-center success" style ="font-size: 12px;">'.translate("Malam", $this->session->userdata("language")).'</th>';
							}
							elseif($i == 2 || $i == 5)
							{
								echo  '<th class="text-center warning" style ="font-size: 12px;">'.translate("Pagi", $this->session->userdata("language")).'</th>
								<th class="text-center warning" style ="font-size: 12px;">'.translate("Siang", $this->session->userdata("language")).'</th>
								<th class="text-center warning" style ="font-size: 12px;">'.translate("Sore", $this->session->userdata("language")).'</th>
								<th class="text-center warning" style ="font-size: 12px;">'.translate("Malam", $this->session->userdata("language")).'</th>';
							}
							elseif($i == 3 || $i == 6)
							{
								echo  '<th class="text-center danger" style ="font-size: 12px;">'.translate("Pagi", $this->session->userdata("language")).'</th>
								<th class="text-center danger" style ="font-size: 12px;">'.translate("Siang", $this->session->userdata("language")).'</th>
								<th class="text-center danger" style ="font-size: 12px;">'.translate("Sore", $this->session->userdata("language")).'</th>
								<th class="text-center danger" style ="font-size: 12px;">'.translate("Malam", $this->session->userdata("language")).'</th>';
							}
							else
							{
								echo  '<th class="text-center" style ="font-size: 12px;">'.translate("Pagi", $this->session->userdata("language")).'</th>
								<th class="text-center" style ="font-size: 12px;">'.translate("Siang", $this->session->userdata("language")).'</th>
								<th class="text-center" style ="font-size: 12px;">'.translate("Sore", $this->session->userdata("language")).'</th>
								<th class="text-center" style ="font-size: 12px;">'.translate("Malam", $this->session->userdata("language")).'</th>';
							}
						
						}
						
					?>
				</tr>
				</thead>
				<tbody>
					<?php
						$cabang_id = $this->session->userdata('cabang_id');
						$data_pasien_klinik = $this->jadwal_m->get_data(date('Y-m-d', strtotime($date)), date('Y-m-d', strtotime($validasi)), $cabang_id)->result_array();
                    	$data_pasien = object_to_array($data_pasien_klinik);
						// die(dump($this->db->last_query()));

						// $data_row_pasien = "";
						//die_dump($data_pasien_klinik);

						for ($i=1; $i <= 22 ; $i++) 
						{ 
							echo '<tr>
									<td class="text-center">'.$i.'</td>';
									for ($j=1; $j<=28; $j++) 
									{
										if($j < 5){
											$hari = "Senin";
											$tanggal = $senin;
										}
										else if($j < 9 && $j > 4)
										{
											$hari = "Selasa";
											$tanggal = $selasa;
										}
										else if($j < 13 && $j > 8)
										{
											$hari = "Rabu";
											$tanggal = $rabu;
										}
										else if($j < 17 && $j > 12)
										{
											$hari = "Kamis";
											$tanggal = $kamis;
										}
										else if($j < 21 && $j > 16)
										{
											$hari = "Jumat";
											$tanggal = $jumat;
										}
										else if($j < 25 && $j > 20)
										{
											$hari = "Sabtu";
											$tanggal = $sabtu;
										}
										else
										{
											$hari = "Minggu";
											$tanggal = $minggu;
										}

										$time_now = date('H:i');
										$class_tipe = "glyphicon glyphicon-ok-sign font-grey";
										$class_button = "btn edit";
										$data_tipe = "";
										$data_id = "";
										$pop_up = "popup_modal";
										$url_function = "add_jadwal";
										$pasien_id = "";
										$nama_pasien = "...";
										$id = "";

										if($j == 1 || $j == 5 || $j == 9 || $j == 13 || $j == 17 || $j == 21 || $j == 25)
										{
											$data_tipe = "Pagi (07:00 - 12:00)";
										
										}
										else if($j == 2 || $j == 6 || $j == 10 || $j == 14 || $j == 18 || $j == 22 || $j == 26)
										{
											$data_tipe = "Siang (13:00 - 18:00)";
										}
										else if($j == 3 || $j == 7 || $j == 11 || $j == 15 || $j == 19 || $j == 23 || $j == 27)
										{
											$data_tipe = "Sore (18:00 - 23:00)";
										}
										else
										{
											$data_tipe = "Malam (23:00 - 03:00)";
										}

										$keterangan_db ="";
										$ket = "";
										$id_pasien_jadwal = "";
										$disable = "";
										$id = "";
										$data_url = array(
											'hari'      => $hari,
											'tanggal'   => $tanggal,
											'urut'      => $i,
											'tipe'      => $data_tipe,
											'pasien_id' => $pasien_id,
											'id'        => $id,
											'keterangan' => $ket
										);
										$data_url = serialize($data_url);

										$url = urlencode(base64_encode($data_url));
										foreach ($data_pasien as $data_row) 
										{
											// die_dump($data_row);

											$pasien_nama = $data_row['nama'];
											$pasien_id = $data_row['pasien_id'];
											// $pasien = $this->pasien_m->get($pasien_id);

											$keterangan_db = $data_row['keterangan'];
											$tanggal_db = $data_row['tanggal'];
											$tipe_db = $data_row['tipe'];
											$no_bed_db = $data_row['no_urut_bed'];
											$tgl =  date('d-m-Y',strtotime($tanggal_db));
											$id = $data_row['id'];
											$tgl_banding_1 = $senin;
											$tgl_banding_2 = $selasa;
											$tgl_banding_3 = $rabu;
											$tgl_banding_4 = $kamis;
											$tgl_banding_5 = $jumat;
											$tgl_banding_6 = $sabtu;
											$tgl_banding_7 = $minggu;
											
											$date_now = new DateTime();
											if($tipe_db == 1)
											{
												$tipe_waktu = "Pagi ";
											}
											else if ($tipe_db == 2) {
												$tipe_waktu = "Siang";
											}
											else if ($tipe_db == 3) {
												$tipe_waktu = "Sore ";
											}
											else
											{
												$tipe_waktu = "Malam";
											}

											$tipe_data = substr($data_tipe, 0, 5);
											
											if ($tgl == $tgl_banding_1)
											{	
												$date_now = new DateTime();
												$date_now = $date_now->format('Y-m-d');
												$tgl = new DateTime($tgl);
												$tgl = $tgl->format('Y-m-d');
					

												if($tgl < $date_now)
												{
													if($j < 5)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																$class_button = "btn view";
																$pop_up = "popup_modal_view";
																$url_function = "view_jadwal";
																$disable = "";
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket
																);
																if($data_row['status'] == 0){
																	$class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
																}
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }

																
															}													
														}
													}
												}
												else
												{
													if($j < 5)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
																$class_button = "btn move";
																$pop_up = "popup_modal_move";
																$url_function = "edit_jadwal";
																$ket = $keterangan_db;
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'       => $hari,
																	'tanggal'    => $tanggal,
																	'urut'       => $i,
																	'tipe'       => $data_tipe,
																	'pasien_id'  => $pasien_id,
																	'id'         => $id,
																	'keterangan' => $ket,
																	'tanggal_db' =>	$tanggal_db
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
																	$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																	$class_button = "btn view";
																	$pop_up = "popup_modal_view";
																	$url_function = "view_jadwal";
																	$disable = "";
																	$ket = $keterangan_db;
																	$pasien_id = $data_row['pasien_id'];
																	$nama_pasien = $pasien_nama;
																	$id = $data_row['id'];
																	$data_url = array(
																		'hari'      => $hari,
																		'tanggal'   => $tanggal,
																		'urut'      => $i,
																		'tipe'      => $data_tipe,
																		'pasien_id' => $pasien_id,
																		'id'        => $id,
																		'keterangan' => $ket
																	);
																	$data_url = serialize($data_url);

																	$url = urlencode(base64_encode($data_url));
																}
															}														
														}
													}
												}

											}
											else if ($tgl == $tgl_banding_2) 
											{
												$date_now = new DateTime();
												$date_now = $date_now->format('Y-m-d');
												$tgl = new DateTime($tgl);
												$tgl = $tgl->format('Y-m-d');

												if($tgl < $date_now)
												{
													if($j < 9 && $j > 4)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																$class_button = "btn view";
																$pop_up = "popup_modal_view";
																$url_function = "view_jadwal";
																$disable = "";
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
															}
														}
													}
												}
												else
												{
													if($j < 9 && $j > 4)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
																$class_button = "btn move";
																$pop_up = "popup_modal_move";
																$url_function = "edit_jadwal";
																$ket = $keterangan_db;
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket,
																	'tanggal_db'=>$tanggal_db
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
																	$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																	$class_button = "btn view";
																	$pop_up = "popup_modal_view";
																	$url_function = "view_jadwal";
																	$disable = "";
																	$ket = $keterangan_db;
																	$pasien_id = $data_row['pasien_id'];
																	$nama_pasien = $pasien_nama;
																	$id = $data_row['id'];
																	$data_url = array(
																		'hari'      => $hari,
																		'tanggal'   => $tanggal,
																		'urut'      => $i,
																		'tipe'      => $data_tipe,
																		'pasien_id' => $pasien_id,
																		'id'        => $id,
																		'keterangan' => $ket
																	);
																	$data_url = serialize($data_url);

																	$url = urlencode(base64_encode($data_url));
																}
															}
														}
													}
												}
											}
											else if ($tgl == $tgl_banding_3)
											{
												$date_now = new DateTime();
												$date_now = $date_now->format('Y-m-d');
												$tgl = new DateTime($tgl);
												$tgl = $tgl->format('Y-m-d');

												if($tgl < $date_now)
												{
													if($j < 13 && $j > 8)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																$class_button = "btn view";
																$pop_up = "popup_modal_view";
																$url_function = "view_jadwal";
																$disable = "";
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
															}
														}
													}
												}
												else
												{
													if($j < 13 && $j > 8)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
																$class_button = "btn move";
																$pop_up = "popup_modal_move";
																$url_function = "edit_jadwal";
																$ket = $keterangan_db;
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,

																	'id'        => $id,
																	'keterangan' => $ket,
																	'tanggal_db'=>$tanggal_db
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
																	$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																	$class_button = "btn view";
																	$pop_up = "popup_modal_view";
																	$url_function = "view_jadwal";
																	$disable = "";
																	$ket = $keterangan_db;
																	$pasien_id = $data_row['pasien_id'];
																	$nama_pasien = $pasien_nama;
																	$id = $data_row['id'];
																	$data_url = array(
																		'hari'      => $hari,
																		'tanggal'   => $tanggal,
																		'urut'      => $i,
																		'tipe'      => $data_tipe,
																		'pasien_id' => $pasien_id,
																		'id'        => $id,
																		'keterangan' => $ket
																	);
																	$data_url = serialize($data_url);

																	$url = urlencode(base64_encode($data_url));
																}
															}
														}
													}
												}
											}

											else if ($tgl == $tgl_banding_4)
											{
												$date_now = new DateTime();
												$date_now = $date_now->format('Y-m-d');
												$tgl = new DateTime($tgl);
												$tgl = $tgl->format('Y-m-d');

												if($tgl < $date_now)
												{
													if($j < 17 && $j > 12)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																$class_button = "btn view";
																$pop_up = "popup_modal_view";
																$url_function = "view_jadwal";
																$disable = "";
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
															}
														}
													}
												}
												else
												{
													if($j < 17 && $j > 12)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
																$class_button = "btn move";
																$pop_up = "popup_modal_move";
																$url_function = "edit_jadwal";
																$ket = $keterangan_db;
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket,
																	'tanggal_db'=>$tanggal_db
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
																	$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																	$class_button = "btn view";
																	$pop_up = "popup_modal_view";
																	$url_function = "view_jadwal";
																	$disable = "";
																	$ket = $keterangan_db;
																	$pasien_id = $data_row['pasien_id'];
																	$nama_pasien = $pasien_nama;
																	$id = $data_row['id'];
																	$data_url = array(
																		'hari'      => $hari,
																		'tanggal'   => $tanggal,
																		'urut'      => $i,
																		'tipe'      => $data_tipe,
																		'pasien_id' => $pasien_id,
																		'id'        => $id,
																		'keterangan' => $ket
																	);
																	$data_url = serialize($data_url);

																	$url = urlencode(base64_encode($data_url));
																}
															}
														}
													}
												}
											}

											else if ($tgl == $tgl_banding_5)
											{
												$date_now = new DateTime();
												$date_now = $date_now->format('Y-m-d');
												$tgl = new DateTime($tgl);
												$tgl = $tgl->format('Y-m-d');

												if($tgl < $date_now)
												{
													if($j < 21 && $j > 16)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																$class_button = "btn view";
																$pop_up = "popup_modal_view";
																$url_function = "view_jadwal";
																$disable = "";
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
															}
														}
													}
												}
												else
												{
													if($j < 21 && $j > 16)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
																$class_button = "btn move";
																$pop_up = "popup_modal_move";
																$url_function = "edit_jadwal";
																$ket = $keterangan_db;
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket,
																	'tanggal_db'=>$tanggal_db
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
																	$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																	$class_button = "btn view";
																	$pop_up = "popup_modal_view";
																	$url_function = "view_jadwal";
																	$disable = "";
																	$ket = $keterangan_db;
																	$pasien_id = $data_row['pasien_id'];
																	$nama_pasien = $pasien_nama;
																	$id = $data_row['id'];
																	$data_url = array(
																		'hari'      => $hari,
																		'tanggal'   => $tanggal,
																		'urut'      => $i,
																		'tipe'      => $data_tipe,
																		'pasien_id' => $pasien_id,
																		'id'        => $id,
																		'keterangan' => $ket
																	);
																	$data_url = serialize($data_url);

																	$url = urlencode(base64_encode($data_url));
																}
															}
														}
													}
												}
											}

											else if ($tgl == $tgl_banding_6)
											{
												$date_now = new DateTime();
												$date_now = $date_now->format('Y-m-d');
												$tgl = new DateTime($tgl);
												$tgl = $tgl->format('Y-m-d');

												if($tgl < $date_now)
												{
													if($j < 25 && $j > 20)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																$class_button = "btn view";
																$pop_up = "popup_modal_view";
																$url_function = "view_jadwal";
																$disable = "";
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
															}
														}
													}
												}
												else
												{
													if($j < 25 && $j > 20)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
																$class_button = "btn move";
																$pop_up = "popup_modal_move";
																$url_function = "edit_jadwal";
																$ket = $keterangan_db;
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket,
																	'tanggal_db'=>$tanggal_db
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
																	$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																	$class_button = "btn view";
																	$pop_up = "popup_modal_view";
																	$url_function = "view_jadwal";
																	$disable = "";
																	$ket = $keterangan_db;
																	$pasien_id = $data_row['pasien_id'];
																	$nama_pasien = $pasien_nama;
																	$id = $data_row['id'];
																	$data_url = array(
																		'hari'      => $hari,
																		'tanggal'   => $tanggal,
																		'urut'      => $i,
																		'tipe'      => $data_tipe,
																		'pasien_id' => $pasien_id,
																		'id'        => $id,
																		'keterangan' => $ket
																	);
																	$data_url = serialize($data_url);

																	$url = urlencode(base64_encode($data_url));
																}
															}
														}
													}
												}
											}

											else if($tgl == $tgl_banding_7)
											{
												$date_now = new DateTime();
												$date_now = $date_now->format('Y-m-d');
												$tgl = new DateTime($tgl);
												$tgl = $tgl->format('Y-m-d');
															
												if($tgl < $date_now)
												{
													if($j < 29 && $j > 24)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																$class_button = "btn view";
																$pop_up = "popup_modal_view";
																$url_function = "view_jadwal";
																$disable = "";
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
															}
														}
													}
												}
												else if($tgl > $date_now)
												{
													if($j < 29 && $j > 24)
													{
														if($tipe_waktu == $tipe_data)
														{
															if($no_bed_db == $i)
															{
																// die(dump($data_row['id']));
																$class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
																$class_button = "btn move";
																$pop_up = "popup_modal_move";
																$url_function = "edit_jadwal";
																$ket = $keterangan_db;
																$pasien_id = $data_row['pasien_id'];
																$nama_pasien = $pasien_nama;
																$id = $data_row['id'];													
																// die(dump($data_row['id']));
																$data_url = array(
																	'hari'      => $hari,
																	'tanggal'   => $tanggal,
																	'urut'      => $i,
																	'tipe'      => $data_tipe,
																	'pasien_id' => $pasien_id,
																	'id'        => $id,
																	'keterangan' => $ket,
																	'tanggal_db'=>$tanggal_db
																);
																$data_url = serialize($data_url);

																$url = urlencode(base64_encode($data_url));

																if($data_row['status'] == 0){
																	$class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
																	$class_button = "btn view";
																	$pop_up = "popup_modal_view";
																	$url_function = "view_jadwal";
																	$disable = "";
																	$ket = $keterangan_db;
																	$pasien_id = $data_row['pasien_id'];
																	$nama_pasien = $pasien_nama;
																	$id = $data_row['id'];
																	$data_url = array(
																		'hari'      => $hari,
																		'tanggal'   => $tanggal,
																		'urut'      => $i,
																		'tipe'      => $data_tipe,
																		'pasien_id' => $pasien_id,
																		'id'        => $id,
																		'keterangan' => $ket
																	);
																	$data_url = serialize($data_url);

																	$url = urlencode(base64_encode($data_url));
																}
															}
														}
													}
												}
											}
										}
										
										if($j == 1 || $j == 5 || $j == 9 || $j == 13 || $j == 17 || $j == 21 || $j == 25)
										{
											
											$btn_default = '<td class="text-center"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'" href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-pasien-id="'.$pasien_id.'" data-id="'.$id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.' ><i class="'.$class_tipe.'"></i></a></td>';

											echo $btn_default;
										}
										else if($j == 2 || $j == 6 || $j == 10 || $j == 14 || $j == 18 || $j == 22 || $j == 26)
										{

											
											$btn_default = '<td class="text-center"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'" href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.'><i class="'.$class_tipe.'"></i></a></td>';
											echo $btn_default;
										}
										else if($j == 3 || $j == 7 || $j == 11 || $j == 15 || $j == 19 || $j == 23 || $j == 27)
										{

											
											$btn_default = '<td class="text-center"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'" href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.'><i class="'.$class_tipe.'"></i></a></td>';
											echo $btn_default;
										}
										else
										{

											
											$btn_default = '<td class="text-center"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'" href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.'><i class="'.$class_tipe.'"></i></a></td>';
											echo $btn_default;
										}

													
									}
							echo '</tr>';
						}
					?>
				</tbody>
				<tfoot>
					<tr style="text-align:center;">
						<td>Kosong</td>
						<td><?=(22 - count($jml_senin_pagi))?></td>
						<td><?=(22 - count($jml_senin_siang))?></td>
						<td><?=(22 - count($jml_senin_sore))?></td>
						<td><?=(22 - count($jml_senin_malam))?></td>
						<td><?=(22 - count($jml_selasa_pagi))?></td>
						<td><?=(22 - count($jml_selasa_siang))?></td>
						<td><?=(22 - count($jml_selasa_sore))?></td>
						<td><?=(22 - count($jml_selasa_malam))?></td>
						<td><?=(22 - count($jml_rabu_pagi))?></td>
						<td><?=(22 - count($jml_rabu_siang))?></td>
						<td><?=(22 - count($jml_rabu_sore))?></td>
						<td><?=(22 - count($jml_rabu_malam))?></td>
						<td><?=(22 - count($jml_kamis_pagi))?></td>
						<td><?=(22 - count($jml_kamis_siang))?></td>
						<td><?=(22 - count($jml_kamis_sore))?></td>
						<td><?=(22 - count($jml_kamis_malam))?></td>
						<td><?=(22 - count($jml_jumat_pagi))?></td>
						<td><?=(22 - count($jml_jumat_siang))?></td>
						<td><?=(22 - count($jml_jumat_sore))?></td>
						<td><?=(22 - count($jml_jumat_malam))?></td>
						<td><?=(22 - count($jml_sabtu_pagi))?></td>
						<td><?=(22 - count($jml_sabtu_siang))?></td>
						<td><?=(22 - count($jml_sabtu_sore))?></td>
						<td><?=(22 - count($jml_sabtu_malam))?></td>
						<td><?=(22 - count($jml_minggu_pagi))?></td>
						<td><?=(22 - count($jml_minggu_siang))?></td>
						<td><?=(22 - count($jml_minggu_sore))?></td>
						<td><?=(22 - count($jml_minggu_malam))?></td>
						
					</tr>
				</tfoot>
				</table>
			</div>
			<div id="table_jadwal_hari" class="table-scrollable hidden">
				
			</div>
			
		
	</div>

	
</div>
<div id="popup_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
        </div>
    </div>
</div>

<div id="popup_modal_move" class="modal fade" role="dialog" aria-hidden="true">
    <form action="#" id="form_jadwal_move" class="form-horizontal" role="form">
        <div class="modal-dialog">
            <div class="modal-content">
                
            </div>
        </div>
    </form>
</div>

<div id="popup_modal_view" class="modal fade" role="dialog" aria-hidden="true">
    <form action="#" id="form_jadwal" class="form-horizontal" role="form">
        <div class="modal-dialog">
            <div class="modal-content">
                
            </div>
        </div>
    </form>
</div>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No Member', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tempat, Tanggal Lahir', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 


