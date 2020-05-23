<html>
	
	<body>
		<?php
			$cabang_id = 11;
			$cabang    = $this->cabang_m->get($cabang_id);

			$cabang_alamat  = $this->cabang_alamat_m->get_by(array('cabang_id' => $cabang_id, 'is_primary' => 1, 'is_active' => 1));
			$cabang_alamat  = object_to_array($cabang_alamat);
			$cabang_telepon = $this->cabang_telepon_m->get_by(array('cabang_id' => $cabang_id,'is_active' => 1, 'subjek_id' => 8));
			$cabang_telepon = object_to_array($cabang_telepon);
			$cabang_fax     = $this->cabang_telepon_m->get_by(array('cabang_id' => $cabang_id,'is_active' => 1, 'subjek_id' => 9));
			$cabang_fax     = object_to_array($cabang_fax);
			$cabang_email   = $this->cabang_sosmed_m->get_by(array('tipe' => 1,'cabang_id' => $cabang_id,'is_active' => 1));
			$cabang_email   = object_to_array($cabang_email);
			$cabang_fb      = $this->cabang_sosmed_m->get_by(array('tipe' => 3,'cabang_id' => $cabang_id,'is_active' => 1));
			$cabang_fb      = object_to_array($cabang_fb);
			$cabang_twitter = $this->cabang_sosmed_m->get_by(array('tipe' => 4,'cabang_id' => $cabang_id,'is_active' => 1));
			$cabang_twitter = object_to_array($cabang_twitter);
			$cabang_website = $this->cabang_sosmed_m->get_by(array('tipe' => 2,'cabang_id' => $cabang_id,'is_active' => 1));
			$cabang_website = object_to_array($cabang_website);

			$data_email = '';
			foreach ($cabang_email as $email) 
			{
				$data_email .= $email['url'].', ';
			}


			if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real')) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_logo_real'))) 
	        {
	            $image_header = config_item('base_dir').config_item('site_logo_real');
	        }
	        else 
	        {
	            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."logo/logo-real.png";
	        }
	        if($alamat_pasien != '')
	        {
	        	$rt = '';
				$rw = '';
				if ($alamat_pasien['rt_rw'] != NULL) 
				{
					$rt_rw = explode('_', $alamat_pasien['rt_rw']);
					$rt = " RT. ".$rt_rw[0];
					$rw = " RW. ".$rt_rw[1];
				}
				
				$form_alamat = $alamat_pasien['alamat'].$rt.$rw;
				$form_kel_alamat = ($form_kel_alamat != '')? " Kel. ".$form_kel_alamat:'';
				$form_kec_alamat = ($form_kec_alamat != '')?  " Kec. ".$form_kec_alamat:'';
				$form_kota_alamat = ($form_kota_alamat != '')?  " ".$form_kota_alamat:'';
	        }

	        $data_supplier = $this->supplier_m->get($data_faktur['supplier_id']);
	        $data_supplier_alamat = $this->supplier_alamat_m->get_data($data_faktur['supplier_id'])->row(0);

		?>
		<div style="width:158px;height:5px;background-color:#2462AC;float:left;margin-bottom:-1px;float:right;"></div>
		<div style="border-top:1.5px solid #2462AC; width:100%;">
			<div id="header">
				<div class="head" style="margin-top:5px;">
					
					<div class="address" style="height:50px;">
						<span><b>Address.</b></span>
						<br>
						<span><?=$cabang_alamat[0]['alamat']?></span>
						<br>
						<span><b>P.</b> <?=$cabang_telepon[0]['nomor']?></span>
						<br>
						<span><b>F.</b> <?=$cabang_fax[0]['nomor']?></span>
						
					</div>
					<div class="address" style="height:50px;">
						<span><b>E.</b> <?=rtrim($data_email,', ')?></span>
						<br>
						<br>
					</div>
					<div class="address" style="height:50px;">
						<span><b>Follow &amp; Visit</b></span><br>
						<span>fb : <?=$cabang_fb[0]['url']?></span><br>
						<span>twitter <?=$cabang_twitter[0]['url']?></span><br>
						<span><?=$cabang_website[0]['url']?></span><br>
					</div>
					
					<div class="logo-a5" style="padding-right:0px;float:right;z-index:-1;height:47px;">
						<img src="<?=$image_header?>">
					</div>
				</div>
			</div>
			
		</div>
		<div id="body_surat">
			<div id="title" style="margin-top:-15px;background-color:#F3F3F3; padding-top:3px;padding-bottom:3px;">
				<b>RECEIPT OF INVOICE</b>
			</div>
			<div style="font-size:9px; background-color:#FFF;width:50%;float:left;">
				<table id="" border="0" style="font-size:9px;white-space:nowrap;">
					<tbody>
						<tr height="3px">
							<td width="3%;" class="text-left" style="padding:5px;">Number</td>
							<td width="1%;" class="text-left" style="padding:5px;">:</td>
							<td width="50%;" class="text-left" style="padding:5px;"><?=$data_faktur['no_tanda_terima_faktur']?></td>
						</tr>
						<tr height="3px">
							<td width="3%;" class="text-left" style="padding:5px;">Date</td>
							<td width="1%;" class="text-left" style="padding:5px;">:</td>
							<td width="50%;" class="text-left" style="padding:5px;"><?=date('d M Y', strtotime($data_faktur['created_date']))?></td>
						</tr>
					</tbody>
				</table>				
			</div>
			<div style="font-size:9px; background-color:#FFF;width:50%;float:left;">
				<table id="" border="0" style="font-size:9px;white-space:nowrap;" >
					<tbody>
						<tr>
							<td width="3%;" class="text-left" style="padding:5px;white-space:nowrap;">Supplier</td>
							<td width="1%;" class="text-left" style="padding:5px;white-space:nowrap;">:</td>
							<td width="50%;" class="text-left" style="padding:5px;white-space:nowrap;"><?=$data_supplier->nama?></td>
						</tr>
						<tr>
							<td width="3%;" class="text-left" style="padding:5px;">Address</td>
							<td width="1%;" class="text-left" style="padding:5px;">:</td>
							<td width="50%;" class="text-left" style="padding:5px;"><?=$data_supplier_alamat->alamat?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="isi_surat" style="width:100%" style="margin-bottom:-10px;">
				<table id="body_content" border="0" class="table">
					<thead>
						<tr>
							<th width="5%;" class="text-center" style="background-color:#00AFEF;color:#fff;padding:6px;font-size:14px;">NO.</th>
							<th width="35%;" class="text-center" style="background-color:#00AFEF;color:#fff;padding:6px;font-size:14px;">DOCUMENT NUMBER</th>
							<th width="65%;"  class="text-center" style="background-color:#00AFEF;color:#fff;padding:6px;font-size:14px;">DESCRIPTION</th>
							<th width="15%;" class="text-center" style="background-color:#00AFEF;color:#fff;padding:6px;font-size:14px;">VALUE</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$user = $this->user_m->get($data_faktur['created_by']);
						$total = 0;
						if($data_faktur_detail)
						{
							$i = 1;
							foreach ($data_faktur_detail as $detail) 
							{

					?>
						<tr>
							<td width="5%;" class="text-center" style="padding:5px;"><?=$i?></td>
							<td width="35%;" style="padding:5px;"><?=$detail['no_berkas']?></td>
							<td width="55%;" style="padding:5px;" ><?=$detail['keterangan']?></td>

							<td width="25%;" class="text-right" style="padding:5px;">Rp. <?=formattanparupiah($detail['nominal'])?></td>
						</tr>
					<?php
								$total = $total + $detail['nominal'];
								$i++;
							}
						}

					?>
					</tbody>
				</table>
			</div>
			<div style="margin-top:-800px;">
				<table id="body_content" border="0" class="table" >
					<tfoot>
						<tr>
							<td width="12%;" class="text-right" style="background-color:#F3F3F3;padding:5px;"><b><i>Terbilang :</i></b></td>
							<td width="88%;" class="text-left" style="background-color:#F3F3F3;padding:5px;"><b><i>#<?=terbilang($total)?> Rupiah #</i></b></td>
						</tr>
					</tfoot>
				</table>
				
			</div>
			<br>
			<div style="float:left;width:73%; margin-top:-33px;">
			<table id="body_content" style="border:1px solid #00AFEF;" class="table">
				<thead >
					<tr>
						<th colspan="3" style="background-color:#00AFEF;color:#FFF;padding:6px;font-size:10px;" width="35%;" class="text-left">SUBMITTED BY</th>
					</tr>
				</thead>
				<tbody>
						<tr>

							<td height="6px" style="padding:2px;"></td>
							<td height="6px" style="padding:2px;"></td>
							<td height="6px" style="padding:2px;"></td>
						</tr>
					<tr>
						<td width="15%;" height="10px" style="padding:5px;font-size:12px;"><i>Name</i></td>
						<td width="55%;" height="10px" style="padding:5px;font-size:12px;"><i>:</i> <?=$data_faktur['diserahkan_oleh']?></td>
						<td width="30%;" height="10px" style="padding:5px;" ></td>
					</tr>
					<tr>
						<td width="15%;" height="10px" style="padding:5px;font-size:12px;"><i>Phone</i></td>
						<td width="55%;" height="10px" style="padding:5px;font-size:12px;"><i>:</i> <?=$data_faktur['no_telepon']?></td>
						<td width="30%;" height="10px" style="padding:5px;" ></td>
					</tr>

					<tr>
						<td width="15%;" height="10px" style="padding:5px;text-align:center;"></td>
						<td width="55%;" height="10px" style="padding:5px;text-align:center;"></td>
						<td width="30%;" height="10px" style="padding:5px;text-align:center;font-size:12px;" ><i>Signature & Stamp</i></td>
						
					</tr>
				</tbody>
			</table>
			</div>
			<div style="float:left;width:25%;margin-left:14px;">
				<table id="body_content" style="border:1px solid #00AFEF;" class="table">
					<thead >
						<tr>
							<th style="background-color:#00AFEF;color:#FFF;padding:6px;font-size:10px;" class="text-left">RECEIVED BY</th>
						</tr>
					</thead>
					<tbody>
						<tr>

							<td height="10px" style="padding:5px;" ></td>
						</tr>
						<tr>

							<td height="10px" style="padding:5px;" ></td>
						</tr>
						<tr>

							<td height="10px" style="padding:5px;" ></td>
						</tr>
						<tr>

							<td height="10px" style="padding:5px;" ></td>
						</tr>
						<tr>

							<td height="15px" style="padding:5px;" ></td>
						</tr>
						<tr>

							<td height="10px" style="padding:5px;text-align:center;font-size:12px;" ><?=$user->nama?></td>
							
						</tr>
					</tbody>
				</table>
			</div>

		</div>

			<div style="width:100%; padding:10px; padding-top:-0px; padding-bottom:0px; font-size:10px; border-left:3px solid #2462AC;background-color:#EEE;margin-top:-12px;display:none;">
				<p><b>Note :</b> Tukar faktur dilakukan pada setiap hari selasa, Pembayaran pada hari jum'at, Pada saat tukar faktur harus dilengkapi : - Invoice Asli   - Surat Jalan Asli   - Faktur Pajak Asli   - Copy PO</p>
			</div>

	</body>
</html>