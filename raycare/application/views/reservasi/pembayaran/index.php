<input type="hidden" id="antrian_id" name="antrian_id" value="<?=$data_antrian['id']?>">
<input type="hidden" id="nama_pasien" name="nama_pasien" value="<?=$data_antrian['nama_pasien']?>">
<div class="row">
<style type="text/css">
	.dashboard-stat{
		    -webkit-border-radius: 26px;
		    -moz-border-radius: 26px;
		    -ms-border-radius: 26px;
		    -o-border-radius: 26px;
		    border-radius: 26px;
	}
	.dashboard-stat:hover{
		-webkit-border-radius: 46px;
		    -moz-border-radius: 46px;
		    -ms-border-radius: 46px;
		    -o-border-radius: 46px;
		    border-radius: 46px;
	}
	.budi > table{
		border-collapse: collapse;
	  	border-radius: 26px;
	  	overflow: hidden;
	  	color:#fff;
	}

	.budi th,
	.budi td {
	  padding: 0.5em;
	  background: #0052AD;
	  /*border-bottom: 2px solid #45a6e9;*/
	}
</style>
<div class="col-md-12">
	<div class="col-md-12 budi" style="margin-bottom:20px;">
	    <table class="budi" width="100%">
		    <tbody>
		    	<tr>
					<?php
						$html = '';

						if(count($list_antrian) == 0){
							$html .= '<td>-</td>
						          <td><i class="fa fa-angle-left" style="color:#2fff00;"></i> -</td>
						          <td><i class="fa fa-angle-left" style="color:#2fff00;"></i> -</td>
						          <td><i class="fa fa-angle-left" style="color:#2fff00;"></i> -</td>
						          <td><i class="fa fa-users" style="color:#2fff00;"></i> 0</td>';
						}elseif(count($list_antrian) != 0){

							$wl_tindakan = ((count($list_antrian) - 4) >= 0)?(count($list_antrian) - 4):0;

							for($x=0;$x<=3;$x++){
								$class = (isset($list_antrian[$x]['is_panggil']) && $list_antrian[$x]['is_panggil'] == 1)?'class="quadrat"':'';
								$nama_pasien = (isset($list_antrian[$x]['nama_pasien']))?strtoupper($list_antrian[$x]['nama_pasien']):'-';

								$html .= '<td '.$class.'><i class="fa fa-angle-double-left" style="color:#2fff00;"></i> '.$nama_pasien.'</td>';
							}
							$html .= '<td><i class="fa fa-users" style="color:#2fff00;"></i> '.$wl_tindakan.'</td>';
						}

						echo $html;
					?>
				</tr>
		    </tbody>
		</table>
	</div>
	<div class="col-md-12">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		        <a class="dashboard-stat dashboard-stat-v2 green" id="tombol_panggil">
		            <div class="visual">
		                <i class="fa fa-bullhorn"></i>
		            </div>
		            <div class="details">
		                <div class="number">
		                    <span data-counter="counterup" data-value="1349">Panggil</span>
		                </div>
		                <div class="desc" id="counter_panggil"><?=$data_antrian['nama_pasien']?></div>
		            </div>
		        </a>
		    </div>
		    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="div_tindak">
		        <a class="dashboard-stat dashboard-stat-v2 blue" id="tombol_tindak">
		            <div class="visual">
		                <i class="fa fa-user-md"></i>
		            </div>
		            <div class="details">
		                <div class="number">
		                    <span data-counter="counterup" data-value="1349">Tindak</span>
		                </div>
		                <div class="desc"> </div>
		            </div>
		        </a>
		    </div>
		    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden" id="div_lewat">
		        <a class="dashboard-stat dashboard-stat-v2 red"  id="tombol_lewat">
		            <div class="visual">
		                <i class="fa fa-refresh"></i>
		            </div>
		            <div class="details">
		                <div class="number">
		                    <span data-counter="counterup" data-value="1349">Lewati</span>
		                </div>
		                <div class="desc"> </div>
		            </div>
		        </a>
		    </div>
		</div>
		    
		</div>
	</div>
</div>

<?php
    $this->cabang_m->set_columns(array('id','nama'));
    $categories = $this->cabang_m->get();
    // die_dump($categories);
    $categories_options = array(
    
    '' => translate('Pilih Cabang', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_options[$categories->id] = $categories->nama;
    }

    //////////////////////////////////////////////////////////////////////////////////////

    $this->item_satuan_m->set_columns(array('id','nama'));
    $categories = $this->item_satuan_m->get();
    // die_dump($categories);
    $categories_satuan = array(
    
    '' => translate('Pilih Satuan', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_satuan[$categories->id] = $categories->nama;
    }

    //////////////////////////////////////////////////////////////////////////////////////

    $this->poliklinik_m->set_columns(array('id','nama', 'kode'));
    $where = array(

    	'is_active' => 1

    	);

    $categories = $this->poliklinik_m->get();
    // die_dump($categories);		
    $categories_poliklinik = array(
    
    '' => translate('Pilih Poliklinik', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_poliklinik[$categories->id] = $categories->nama;
    }


	$form_attr = array(
		"id"			=> "form_pembayaran", 
		"name"			=> "form_pembayaran", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."reservasi/pembayaran/save", $form_attr,$hidden);
	// echo form_open(base_url()."reservasi/pembayaran/cetak_invoice_pasien", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');


	$user_id = $this->session->userdata('user_id');
	$user_login = $this->user_m->get_by(array('id' => $user_id), true);
	
	
    $mesin_edc = $this->mesin_edc_m->get_data_full()->result_array();
    $mesin_edc_option = array(
        '' => translate('Pilih', $this->session->userdata('language')) . '..'
    );
    foreach ($mesin_edc as $msn) {
        $mesin_edc_option[$msn['id']] = $msn['nama'].' - '.$msn['nob'];
    }
    
    $jenis_kartu_options = array(
        '0' => translate('Pilih Jenis Kartu', $this->session->userdata('language')) . '..',
        '1' => translate('Kartu Debit', $this->session->userdata('language')),
        '2' => translate('Kartu Kredit', $this->session->userdata('language')),
    );


 //    /////////////////////////////////////////////////////////////////////

	$form_option_payment = '<div class="form-group">
					    	<div class="col-md-12">
					    			<select class="form-control payment_type" name="payment[_ID_0][payment_type]" id="payment[_ID_0][payment_type]">
									  <option value="1">Tunai</option>
									  <option value="2">Mesin EDC</option>
									</select>
					    		
					     	</div>
					    </div>
					    <div id="section_2" hidden>
					    	<div class="form-group">
								<div class="col-md-12">
            						'.form_dropdown('payment[_ID_0][mesin_edc_id]', $mesin_edc_option, ' ', "id=\"mesin_edc_id\" class=\"form-control\"").'
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
            						'.form_dropdown('payment[_ID_0][jenis_kartu]', $jenis_kartu_options, ' ', "id=\"jenis_kartu\" class=\"form-control\"").'
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<input class="form-control col-md-2" id="payment[_ID_0][no_kartu]" name="payment[_ID_0][no_kartu]" placeholder="No. Kartu">
								</div>
							</div>
					    </div>
					    <div id="section_1" hidden>
							<div class="form-group">
								<div class="col-md-12">
									<label class="control-label">Jumlah Bayar :</label>
								</div>
								<div class="col-md-12">
									<div class="input-group">
										<span class="input-group-addon">Rp.
										</span>
										<input type="number" min="0" class="form-control text-right col-md-2 payment_cash_ID_0 payment_cash" id="payment[_ID_0][jumlah_bayar]" name="payment[_ID_0][jumlah_bayar]" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class="control-label">Total Invoice :</label>
								</div>
								<div class="col-md-12">
									<div class="input-group">
										<span class="input-group-addon">Rp.
										</span>
										<input type="number" min="0" class="form-control text-right col-md-2 payment_cash_ID_0 payment_cash" id="payment[_ID_0][nominal]" name="payment[_ID_0][nominal]" value="0" readonly>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class="control-label">Kembali :</label>
								</div>
								<div class="col-md-12">
									<div class="input-group">
										<span class="input-group-addon">Rp.
										</span>
										<input type="number" min="0" class="form-control text-right col-md-2 payment_cash_ID_0 payment_cash" id="payment[_ID_0][kembali]" name="payment[_ID_0][kembali]" value="0" readonly>
									</div>
								</div>
							</div>
						</div>';

?>


<style type="text/css">
	
@charset "UTF-8";
.image {
  width: 250px;
  float: left;
  margin: 20px;
}

body {
  font-size: small;
  line-height: 1.4;
}

p {
  margin: 0;
}

.performance-facts {
  border: 1px solid black;
  margin: 20px;
  float: left;
  width: 300px;
  padding: 0.5rem;
}
.performance-facts table {
  border-collapse: collapse;
}

.performance-facts__title {
  font-weight: bold;
  font-size: 2rem;
  margin: 0 0 0.25rem 0;
}

.performance-facts__header {
  border-bottom: 10px solid black;
  padding: 0 0 0.25rem 0;
  margin: 0 0 0.5rem 0;
}
.performance-facts__header p {
  margin: 0;
}

.performance-facts__table, .performance-facts__table--small, .performance-facts__table--grid {
  width: 100%;
}
.performance-facts__table thead tr th, .performance-facts__table--small thead tr th, .performance-facts__table--grid thead tr th, .performance-facts__table thead tr td, .performance-facts__table--small thead tr td, .performance-facts__table--grid thead tr td {
  border: 0;
}
.performance-facts__table th, .performance-facts__table--small th, .performance-facts__table--grid th, .performance-facts__table td, .performance-facts__table--small td, .performance-facts__table--grid td {
  font-weight: normal;
  text-align: left;
  padding: 0.25rem 0;
  border-top: 1px solid black;
  white-space: nowrap;
}
.deskripsi th{
	white-space: normal !important;
}
.performance-facts__table td:last-child, .performance-facts__table--small td:last-child, .performance-facts__table--grid td:last-child {
  text-align: right;
}
.performance-facts__table .blank-cell, .performance-facts__table--small .blank-cell, .performance-facts__table--grid .blank-cell {
  width: 1rem;
  border-top: 0;
}
.performance-facts__table .thick-row th, .performance-facts__table--small .thick-row th, .performance-facts__table--grid .thick-row th, .performance-facts__table .thick-row td, .performance-facts__table--small .thick-row td, .performance-facts__table--grid .thick-row td {
  border-top-width: 5px;
}

.small-info {
  font-size: 0.7rem;
}

.performance-facts__table--small {
  border-bottom: 1px solid #999;
  margin: 0 0 0.5rem 0;
}
.performance-facts__table--small thead tr {
  border-bottom: 1px solid black;
}
.performance-facts__table--small td:last-child {
  text-align: left;
}
.performance-facts__table--small th, .performance-facts__table--small td {
  border: 0;
  padding: 0;
}

.performance-facts__table--grid {
  margin: 0 0 0.5rem 0;
}
.performance-facts__table--grid td:last-child {
  text-align: left;
}
.performance-facts__table--grid td:last-child::before {
  content: "•";
  font-weight: bold;
  margin: 0 0.25rem 0 0;
}

.text-center {
  text-align: center;
}

.thick-end {
  border-bottom: 10px solid black;
}

.thin-end {
  border-bottom: 1px solid black;
}

</style>

<!-- BEGIN PROFILE SIDEBAR -->
<div class="profile-sidebar" style="width: 250px;">
	<!-- PORTLET MAIN -->
	<div class="portlet light profile-sidebar-portlet">
		<div class="patient-padding-picture"></div>
		<!-- SIDEBAR USERPIC -->
		<div class="profile-userpic">
			<img id="side_img_pasien" src="<?=base_url().config_item('site_img_pasien')?>global/global_medium.png" class="img-responsive" alt="">
		</div>
		<!-- END SIDEBAR USERPIC -->
		<!-- SIDEBAR USER TITLE -->
		<div class="profile-usertitle">
			<div class="profile-usertitle-name" id="side_nama_pasien">
				 Pasien
			</div>
			<div class="profile-usertitle-job" id="side_umur_pasien">
				 Rp. ---
			</div>
		</div>
		<!-- END SIDEBAR USER TITLE -->

	</div>
	<!-- END PORTLET MAIN -->
	<!-- PORTLET MAIN -->
	<div class="portlet light">
		<!-- STAT -->
		<div class="row list-separated profile-stat">
			<div class="col-md-4 col-sm-4 col-xs-6">
				<div class="uppercase profile-stat-title" id="side_transaksi_pasien">
					 ---
				</div>
				<div class="uppercase profile-stat-text">
					 Transaksi
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-6">
				<div class="uppercase profile-stat-title" id="side_tagihan_pasien">
					 ---
				</div>
				<div class="uppercase profile-stat-text">
					 Tagihan
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-6">
				<div class="uppercase profile-stat-title" id="side_upload_pasien">
					 ---
				</div>
				<div class="uppercase profile-stat-text">
					 Uploads
				</div>
			</div>
		</div>
		<!-- END STAT -->
		<div class="tentang_pasien" style="display: none;">
			<h4 class="profile-desc-title" id="side_tentang_pasien">Tentang Pasien</h4>
			<span class="profile-desc-text" id="side_keterangan_pasien"> 
				<div class="form-group">
					<div class="col-md-12">
						<label class="col-md-12">Tanggal Registrasi : </label>
						<label class="side_tgl_registrasi col-md-12"></label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label class="col-md-12">Alamat :</label>
						<label class="side_alamat col-md-12"></label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label class="col-md-12">Gender : </label>
						<label class="side_gender col-md-12"></label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label class="col-md-12">Tempat, Tgl Lahir : </label>
						<label class="side_ttl col-md-12"></label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label class="col-md-12">Telepon : </label>
						<label class="side_tlp col-md-12"></label>
					</div>
				</div>
				
				
			</span>

		</div>
	</div>
	<!-- END PORTLET MAIN -->
</div>
<!-- END BEGIN PROFILE SIDEBAR -->

<!-- BEGIN PROFILE CONTENT -->
<div class="profile-content">
	<div class="row">
		<div class="col-md-9">
			<div class="portlet light">
				<div class="portlet-title">
				    <div class="caption"> 
				    <i class="fa fa-money font-blue-sharp"></i>
				    	<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pembayaran", $this->session->userdata("language"))?></span>
				      	<span class="caption-helper"><?php echo '<label class="control-label ">'.date('d M Y').'</label>'; ?></span> 
				    </div>
					
				  	
				    <div class="caption" style="float: left;">
				        <label class="control-label hidden" id="subtotal_label"></label>
				    </div>
				</div><!-- end of <div class="portlet-title"> -->
				<div class="portlet-body form">		
					<div class="form-body">
						<div class="alert alert-danger display-hide">
				        	<button class="close" data-close="alert"></button>
				        	<?=$form_alert_danger?>
					    </div>
					    <div class="alert alert-success display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_success?>
					    </div>
						<!-- <div class="row">
							<div class="col-md-12">
								<div class="portlet light bordered">
									<div class="portlet-title">
										<div class="caption">
											<?=translate("Detail Transaksi", $this->session->userdata("language"))?>
										</div>
									</div>
									<div class="portlet-body"> -->
								    <!-- <div class="table-responsive"> -->
								    <div class="form-group">
											<label class="col-md-3 bold" style="font-size:25px;"><?=translate("Cari Pasien", $this->session->userdata("language"))?> :</label>
											<div class="col-md-9">
												<div class="input-group">
                                                    <div class="input-group-btn">
                                                        <button id="button_jenis_kartu" type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="padding: 4px 12px;
font-size: 22px !important;">No. RM
                                                            <i class="fa fa-angle-down" style="font-size:22px !important;"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="list_jenis_kartu" data-id="1" data-text="No. RM" > No. RM </a>
                                                            </li>
                                                            <li>
                                                                <a class="list_jenis_kartu" data-id="2" data-text="No. KTP"> No. KTP </a>
                                                            </li>
                                                            <li>
                                                                <a class="list_jenis_kartu" data-id="3" data-text="No. BPJS"> No. BPJS </a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                    <!-- /btn-group --><input id="tipe_kartu" name="tipe_kartu" class="form-control" type="hidden" value="1">
                                                    <input id="no_member" name="no_member" class="form-control" placeholder="Isi No. Rekam Medis" type="text" style="height: 40px;font-size:22px !important;font-weight:900;">
                                                    <input id="id_ref_pasien" name="id_ref_pasien" class="form-control" type="hidden" >
                                                    
                                                </div>
											</div>
										</div>
										</br>
										<div class="row">
											<div class="col-md-6">
												<div class="col-md-12">
					              					<div class="btn-group btn-group-justified">
														<a id="btn_inv_umum" class="btn btn-primary">
															<?=translate("UMUM", $this->session->userdata("language"))?>
														</a>
														<a id="btn_inv_bpjs" class="btn btn-default">
															<?=translate("BPJS", $this->session->userdata("language"))?>
														</a>
													</div>
								              	</div>
								              	<div class="col-md-12">
								            	<div id="invoice-umum">
													<section class="performance-facts">
													  <header class="performance-facts__header">
													    <h1 class="performance-facts__title">Klinik Raycare <i>#999999</i></h1>
													    <p id="tanggal_invoice">Waktu : </p>
													    <p id="no_rm">No. RM : </p>
													    <p id="nama_pasien">Pasien : </p>
													    <p id="penjamin">Penjamin : </p>
													    <p id="no_penjamin">No. Penjamin : </p>
													  </header>
													  <table class="performance-facts__table">
													    <thead>
													      <tr>
													        <th colspan="3" class="small-info">
													          Detail Transaksi
													        </th>
													      </tr>
													    </thead>
													    <tbody id="detail_invoice_umum_new">
													      
													    </tbody>
													  </table>
													  
													  
													  </table>
													  
													  
													  <table class="performance-facts__table">
													    
													    <tbody>
													      <tr>
													        <th colspan="4">Total Transaksi</th>
													        
													        <td id="total_invoice_umum">#</td>
													      </tr>
													      <tr>
													        <th colspan="4">Diskon</th>
													        
													        <td id="diskon_umum">#</td>
													      </tr>
													      <tr>
													        <th colspan="4">Akomodasi</th>
													        
													        <td id="akomodasi_umum">#</td>
													      </tr>
													      <tr>
													        <th colspan="4">Grand Total</th>
													        
													        <td id="grand_total_umum">#</td>
													      </tr>
													      <tr>
													        <th colspan="5">Terbilang</th>
													      </tr>  
													      <th colspan="5" id="terbilang_umum">Terbilang</th>
													      </tr>
													      
													    </tbody>
													  </table>
													</section>

													

								              	</div>
								              	<div id="invoice-bpjs" class="hidden">
								              		<section class="performance-facts">
													  <header class="performance-facts__header">
													    <h1 class="performance-facts__title">Klinik Raycare <i>#999999</i></h1>
													    <p id="tanggal_invoice_bpjs">Waktu : </p>
													    <p id="no_rm_bpjs">No. RM : </p>
													    <p id="nama_pasien_bpjs">Pasien : </p>
													    <p id="penjamin_bpjs">Penjamin : </p>
													    <p id="no_penjamin_bpjs">No. Penjamin : </p>
													  </header>
													  <table class="performance-facts__table">
													    <thead>
													      <tr>
													        <th colspan="3" class="small-info">
													          Detail Transaksi
													        </th>
													      </tr>
													    </thead>
													    <tbody id="detail_invoice_bpjs_new">
													      
													    </tbody>
													  </table>
													  
													  
													  </table>
													  
													  
													  <table class="performance-facts__table">
													    
													    <tbody>
													      <tr>
													        <th colspan="4">Total Transaksi</th>
													        
													        <td id="total_invoice_bpjs">#</td>
													      </tr>
													      <tr>
													        <th colspan="5">Terbilang</th>
													      </tr>  
													      <th colspan="5" id="terbilang_bpjs">Terbilang</th>
													      </tr>
													      
													    </tbody>
													  </table>
													</section>
												
							              		</div>	
						              		</div>
											</div>
											<div class="col-md-6">
												
												<div class="portlet light bordered" id="section-payment">
									 				<div class="portlet-title">
									 					<div class="caption">
									 						<?=translate("Jenis Bayar", $this->session->userdata("language"))?>
									 					</div>
									 					<div class="actions">
												            
									 					</div>
									 				</div>
														<input type="hidden" id="tpl-form-payment" value="<?=htmlentities($form_option_payment)?>">

															<ul class="list-unstyled">
											                </ul>

											         
									 				

												</div><!-- end of section-payment -->
											</div>
										</div>
				                      	
						     
					</div><!-- end of <div class="form-body"> -->
					<?php
						$confirm_save       = translate('Anda yakin akan menyimpan transaksi pembayaran Ini ?',$this->session->userdata('language'));
						$submit_text        = translate('Setujui', $this->session->userdata('language'));
						$history_text       = translate('History', $this->session->userdata('language'));
					?>
					<div class="form-actions right"> 
				      	<button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
				    	<a class="btn btn-default" href="<?=base_url()?>reservasi/pembayaran/history"> <i class="fa fa-history"></i> <?=$history_text?></a> 
				      	<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><i class="fa fa-check"></i> <?=$submit_text?></a>
				  	</div>
				</div><!-- end of <div class="portlet-body form"> -->
				<?=form_close()?>
			</div><!-- end of <div class="portlet light"> -->
		</div><!-- end of col-md-10 -->
		<div class="col-md-3">
			<div class="portlet light">
				<div class="portlet-title">
				    <div class="caption"> 
				    <i class="fa fa-paper-plane font-blue-sharp"></i>
				    	<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Kirim Setoran", $this->session->userdata("language"))?></span>
				    </div>
					<?php
						$confirm_save       = translate('Anda yakin akan menyimpan transaksi pembayaran Ini ?',$this->session->userdata('language'));
						$submit_text        = translate('Setor', $this->session->userdata('language'));
						$history_text       = translate('History', $this->session->userdata('language'));
					?>
				  	<div class="actions"> 
				      	<button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
				      	<a class="btn btn-primary" href="<?=base_url()?>kasir/setoran_kasir" data-confirm="<?=$confirm_save?>" data-toggle="modal"><i class="fa fa-paper-plane"></i> <?=$submit_text?></a>
				  	</div>
				    <div class="caption" style="float: left;">
				        <label class="control-label hidden" id="subtotal_label"></label>
				    </div>
				</div><!-- end of <div class="portlet-title"> -->
				<div class="portlet-body form">		
					<div class="form-body">
						
					    <table class="table table-striped table-hover" id="table_invoice">
							<thead>
								<tr>
									<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("No. Invoice", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
								</tr>
							</thead>

							<tbody>
								
							</tbody>
						</table>
						
					</div><!-- end of <div class="form-body"> -->
				</div><!-- end of <div class="portlet-body form"> -->
				<?=form_close()?>
			</div><!-- end of <div class="portlet light"> -->
		</div><!-- end of col-md-2 -->
	</div><!-- end of row -->
</div><!-- end of div profile-content -->
<!-- END PROFILE CONTENT -->
<div class="modal fade bs-modal-lg" id="modal_detail" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" >
       <div class="modal-content">

       </div>
   </div>
</div>
<?=form_close();?>
<?php $this->load->view('reservasi/pembayaran/pilih_pasien.php'); ?>

