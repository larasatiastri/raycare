<html>
<head>
	<style type="text/css">
		body
		{
			font-size: 11px;
			/*font-family: "Times New Roman", Arial, Verdana;*/
		}

		.table{
			width: 100%;
			border-collapse:collapse;
			margin-bottom: 10px;
			border:1px solid #2462AC !important;
		}


		.table td, .table th{
			border:1px solid #2462AC !important;
			padding: 2px;
		}

		.item tr:nth-child(even){
			background:#F3F6FA;
		}

		.item td{
			border:none;
			border-left:1px solid #5889BA;
			border-right:1px solid #5889BA;
		}

		.item tfoot th {
			background: #fff;
		}

		.item tr{
			background:#fff;
		}

		.advice{
			width: 100%;
			/*float: left;*/
		}

		.tigawarna td{
			border:10px solid #fff;
			/*padding:5px 10px;*/
		}

		.strip-color 
		{
			width: 100%; 
			display: inline-block; 
		}

		.position-color
		{
			height: 15px; 
			width: 32.5%;
		}

		#biru
		{
			background: #649DD5;
			
			float: left; 
			border-right: 8px solid #fff;
		}

		#ungu{
			background: #B596C5;
			
			float: left; 
			border-right: 8px solid #fff;
		}

		#hijau{
			background: #8AB870;
		}
		.text-center{
			text-align: center;;
		}
		.text-left{
			text-align: left;;
		}
		.text-right{
			text-align: right;;
		}

		#advice_table{
			float: left; 
			width: 70%;
		}

		#signature{
			width: 100%;
			text-align: center;
			margin-top: 100px;
			font-weight: 700;
		}

		#column_2{
			overflow: hidden;

		}

		#head_info{
			margin-bottom: 10px;
			width: 100%;
			font-size: 11px;
			font-weight: 700;
		}

		#head_content td{
			font-weight: 700;
		}

		#text{
			font-weight: 700;
			padding-top: 20px;
		}

		#body_content{
			margin-top: 10px;
			width: 100%;
			border:1px solid #000;
		}

		#body_info{
			width: 100%;
			font-weight: 700;
		}

		table#body_content tr td.even{
			background-color: #f2f2f2;
		}

		table#body_content tr td.odd{
			background-color:#fff;
		}

		.stempel{
			width:200px !important;
			margin-right: -200px !important;
			padding:1px 1px 1px 1px;
			margin-top: -85px;
			margin-left: 260px;
			height:80px !important;
			position:fixed !important;
			bottom:0 !important;
			right:0 !important;
			opacity: 0.5;
			z-index: -1;
			/* Rotate div */
		    -ms-transform: rotate(7deg); /* IE 9 */
		    -webkit-transform: rotate(7deg); /* Chrome, Safari, Opera */
		    transform: rotate(7deg);
		}
	</style>
</head>
<body>
	<?php
		$supplier = $this->supplier_m->get($pembelian['supplier_id']);
		$supplier_alamat = $this->supplier_alamat_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'is_primary' => 1, 'is_active' => 1), true);
		$lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $supplier_alamat->kode_lokasi), true);

		$supplier_telepon = $this->supplier_telepon_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'subjek_telp_id' => 2, 'is_active' => 1), true);
		$supplier_fax = $this->supplier_telepon_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'subjek_telp_id' => 8, 'is_active' => 1), true);
		$supplier_mobile = $this->supplier_telepon_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'subjek_telp_id' => 5, 'is_active' => 1), true);
		$supplier_email = $this->supplier_email_m->get_by(array('supplier_id' => $pembelian['supplier_id'], 'is_primary' => 1, 'is_active' => 1), true);
		$kelurahan = '';
		if(count($lokasi))
		{
			$kelurahan = ucwords(strtolower($lokasi->nama_kelurahan)).', '.ucwords(strtolower($lokasi->nama_kecamatan)).', '.ucwords(strtolower($lokasi->nama_kabupatenkota)).', '.ucwords(strtolower($lokasi->nama_propinsi));
		}

		$tempo = $this->supplier_tipe_pembayaran_m->get($pembelian['tipe_pembayaran']);
		$nama_pembayaran = $this->master_tipe_bayar_m->get($tempo->tipe_bayar_id);

		if($pembelian['tipe_customer'] == 1)
		{
			$customer  = $this->penerima_cabang_m->get($pembelian['customer_id']);
			$customer_alamat = $this->cabang_alamat_m->get_alamat_lengkap($pembelian['customer_id']);		
		}
		else
		{
			$customer  = $this->penerima_customer_m->get($pembelian['customer_id']);
		}

		$delivery_time = $this->pembelian_detail_m->get_tanggal_kirim($pembelian['id']);

	    $tanda_tangan_issued = base_url()."assets/mb/global/image/logo/stempel.png";
		$user = $this->user_m->get($pembelian['created_by']);
		$membuat = '';
		$sipa_membuat = '';
		if(count($user) != 0){
			$membuat = $user->nama;
			$sipa_membuat = $user->sip;
			$tanda_tangan_issued = base_url()."assets/mb/pages/master/user/images/".$user->username."/".$user->url_sign;
		}else{
			$membuat = 'Nico';
		}
		//die_dump('testing');

		$data_setuju = $this->persetujuan_po_history_m->get_data_setuju($pembelian['id'])->row(0);
		$user_setuju = $this->user_m->get($data_setuju->disetujui_oleh);
		$menyetujui = '';
		$tanda_tangan_approval = base_url()."assets/mb/global/image/logo/tanda tangan lia.png";
		if(count($user_setuju) != 0){
			$menyetujui = $user_setuju->nama;
			$tanda_tangan_approval = base_url()."assets/mb/pages/master/user/images/".$user_setuju->username."/".$user_setuju->url_sign;
		}else{
			$menyetujui = 'Raymond';
		}


		
	?>
	<div id="body">
		<div style="background-color:red !important;height:30px;width:100%;margin-top:15px;">
			<div style="float:left;height:30px;width:500px;padding-top:15px;">		
				<table id="head_content" border="0">
					<tr>
						<td style="vertical-align:top;" width="22%">Company</td>
						<td style="vertical-align:top;" width="2%">: </td>
						<td style="vertical-align:top;"><?=$supplier->nama?></td>
					</tr>
					<tr>
						<td style="vertical-align:top;">Attn</td>
						<td style="vertical-align:top;">: </td>
						<td style="vertical-align:top;"><?=$supplier->orang_yang_bersangkutan?></td>
					</tr>
					<tr>
						<td style="vertical-align:top;">Address</td>
						<td style="vertical-align:top;">: </td>
						<td style="vertical-align:top;"><?=$supplier_alamat->alamat.'<br>'.$kelurahan?></td>
					</tr>
					<tr>
						<td style="vertical-align:top;">Phone</td>
						<td style="vertical-align:top;">: </td>
						<td style="vertical-align:top;"><?=(count($supplier_telepon))?$supplier_telepon->no_telp:'-' ?></td>
					</tr>
					<tr>
						<td style="vertical-align:top;">Fax</td>
						<td style="vertical-align:top;">: </td>
						<td style="vertical-align:top;"><?=(count($supplier_fax))?$supplier_fax->no_telp:'-' ?></td>
					</tr>
					<tr>
						<td style="vertical-align:top;">Mobile</td>
						<td style="vertical-align:top;">: </td>
						<td style="vertical-align:top;"><?=(count($supplier_mobile))?$supplier_mobile->no_telp:'-' ?></td>
					</tr>
					<tr>
						<td style="vertical-align:top;">Email</td>
						<td style="vertical-align:top;">: </td>
						<td style="vertical-align:top;color:#2462AC;"><?=(count($supplier_email))?$supplier_email->email:'-'?></td>
					</tr>
				</table>
			</div>
			<div style="float:left;height:30px;width:212px;">
				<table id="head_info">
					<tr>
						<td style="text-align:right;">Jakarta, <?=date('d F Y', strtotime($pembelian['tanggal_pesan']))?></td>
					</tr>

					<?php
						if(count($pembelian_penawaran) == 0)
						{
						?>
						<tr>
							<td style="text-align:right;">Ref : -</td>
						</tr>
						<?php	
						}
						else
						{
							$y = 1;
							foreach ($pembelian_penawaran as $penawaran) 
							{
								if($y == 1)
								{
									echo '<tr> <td style="text-align:right;">Ref : '.$penawaran['nomor_penawaran'].'</td></tr>';
								}
								else
								{
									echo '<tr> <td style="text-align:right;">'.$penawaran['nomor_penawaran'].'</td></tr>';
								}
								$y++;
							}
						}
					?>

				</table>
				
			</div>
		</div>

		
		<div id="text">Here with we would like to order the item as below :</div>

		<table id="body_content" class="table" style="border:1px solid #2462AC !important;">
			<tr>
				<th width="3%;" class="text-center" style="background:#2462AC;color:#fff;">No.</th>
				<th width="35%;" class="text-center" style="background:#2462AC;color:#fff;">Description</th>
				<th width="10%;" class="text-center" style="background:#2462AC;color:#fff;">Code</th>
				<th width="25%;" colspan="2" class="text-center" style="background:#2462AC;color:#fff;">Qty</th>
				<th width="20%;" colspan="2" class="text-center" style="background:#2462AC;color:#fff;">Price</th>
				<th width="5%;" class="text-center" style="background:#2462AC;color:#fff;">Disc</th>
				<th width="25%;" colspan="2" class="text-center" style="background:#2462AC;color:#fff;">Total</th>
			</tr>
			<?php
				$total = 0;
				$tgl_kirim = array();
				if($pembelian_detail)
				{	
					$item_po = array();
					$i = 1;
					foreach ($pembelian_detail as $detail) 
					{
						$row_color = 'odd';
						$border_color = '#fff';
						$item_po[$detail['id']] = $i;
						if($i%2 == 0){
							$row_color = 'even';
							$border_color = '#f2f2f2';
						}
			?>
				<tr>
					<td class="text-center <?=$row_color?>" ><?=$i.'.'?></td>
					<td class="<?=$row_color?>" style="padding-left:5px;" ><?=$detail['nama']?></td>
					<td class="text-left <?=$row_color?>" width="7%"><?=$detail['kode']?></td>
					<td class="text-right <?=$row_color?>" width="6%;" style="border-right:1px solid <?=$border_color?>;"><?=$detail['jumlah_disetujui']?></td>
					<td class="text-left <?=$row_color?>" width="11%;"><?=$detail['nama_satuan']?></td>
					<td class="<?=$row_color?>" style="padding-left:5px;border-right:1px solid <?=$border_color?>;" width="4%;">IDR</td>
					<td class="text-right <?=$row_color?>" width="16%;"><?=formattanparupiahnol($detail['harga_beli'])?></td>
					<td class="<?=$row_color?>" style="padding-left:5px;"><?=$detail['diskon']?> %</td>
					<td class="<?=$row_color?>" style="padding-left:5px;border-right:1px solid <?=$border_color?>;" width="4%;">IDR</td>
					<td class="text-right <?=$row_color?>" width="21%;"><?=formattanparupiahnol(($detail['jumlah_disetujui'] * $detail['harga_beli']) - ($detail['jumlah_disetujui'] * $detail['harga_beli'])*$detail['diskon']/100) ?></td>
				</tr>
			<?php
						$total = $total + (($detail['jumlah_disetujui'] * $detail['harga_beli']) - ($detail['jumlah_disetujui'] * $detail['harga_beli'])*$detail['diskon']/100);
						$i++;
					}
				}

				$diskon = ($pembelian['diskon']/100)*$total;
				$dp = ($pembelian['dp']/100)*$total;
				$tad    = $total - $diskon;
				$vat    = ($pembelian['pph']/100)*$tad;
				$tat   	= $tad + $vat;
				$cost = $pembelian['biaya_tambahan'];
				$grand_total = $cost + $tat;

			?>
			
			<tr>
				<td class="text-right" colspan="8">Subtotal</td>
				<td style="padding-left:5px;border-right:1px solid #FFFFFF;" width="3%;">IDR</td>
				<td class="text-right"><?=formattanparupiahnol($total)?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="8">Disc</td>
				<td style="padding-left:5px;border-right:1px solid #FFFFFF;" width="3%;">IDR</td>
				<td class="text-right"><?=formattanparupiahnol($diskon)?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="8"><b>TAD</b></td>
				<td style="padding-left:5px;border-right:1px solid #FFFFFF;" width="3%;"><b>IDR</b></td>
				<td class="text-right"><b><?=formattanparupiahnol($total - $diskon)?></b></td>
			</tr>

			<tr>
				<td class="text-right" colspan="8">VAT</td>
				<td style="padding-left:5px;border-right:1px solid #FFFFFF;" width="3%;">IDR</td>
				<td class="text-right"><?=formattanparupiahnol($vat)?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="8"><b>TAT</b></td>
				<td style="padding-left:5px;border-right:1px solid #FFFFFF;" width="3%;"><b>IDR</b></td>
				<td class="text-right"><b><?=formattanparupiahnol($tat)?></b></td>
			</tr>
			<tr>
				<td class="text-right" colspan="8"><b>DP</b></td>
				<td style="padding-left:5px;border-right:1px solid #FFFFFF;" width="3%;"><b>IDR</b></td>
				<td class="text-right"><b><?=formattanparupiahnol($dp)?></b></td>
			</tr>
			<tr>
				<td class="text-right" colspan="8"><b>COST</b></td>
				<td style="padding-left:5px;border-right:1px solid #FFFFFF;" width="3%;"><b>IDR</b></td>
				<td class="text-right"><b><?=formattanparupiahnol($cost)?></b></td>
			</tr>
			<tr>
				<td class="text-right" colspan="8"><b>TAC</b></td>
				<td style="padding-left:5px;border-right:1px solid #FFFFFF;" width="3%;"><b>IDR</b></td>
				<td class="text-right"><b><?=formattanparupiahnol($grand_total)?></b></td>
			</tr>

		</table>
		<div style="border-bottom:1.5px solid #000;width:116px;margin-bottom:5px;"><b>Term and Conditions</b></div>
		<table id="body_info" border="0">
			<tr>
				<td width="14%">POD</td>
				<td width="1%">:</td>
				<td colspan="3">Klinik Raycare - Kalideres Jakarta</td>
			</tr>
			<tr>
				
				<?php 
					if($pembelian['is_single_kirim'] == 1) {
				?>
					<td>Delivery Condition</td>
					<td>:</td>
					<td width="23%" colspan="3"><?=date('d F Y', strtotime($pembelian['tanggal_kirim']))?></td>
				<?php
					}else{
				?>
					<td valign="top">Delivery Condition</td>
					<td valign="top">:</td>
					<td width="23%" colspan="3">
						<table border="0">
							<tbody>
						<?php 
							$data_pengiriman = $this->pembelian_detail_tanggal_kirim_m->get_tanggal_kirim($pembelian['id']);

							$idx = 1;
							foreach ($data_pengiriman as $key => $data_kirim){
								$data_kirim_detail = $this->pembelian_detail_tanggal_kirim_m->get_tanggal_kirim_detail($pembelian['id'], $data_kirim['tanggal_kirim']);
						?>
							<tr>
								<td rowspan="<?=count($data_kirim_detail)+1?>" valign="top"><strong><?=date('d M Y', strtotime($data_kirim['tanggal_kirim']))?></strong></td>
							<?php
								$idy = 0;
								foreach ($data_kirim_detail as $key => $kirim_detail) {
							?>
								<tr>
								
									<td>&nbsp;-&nbsp;</td>
									<td>No. <?=$item_po[$kirim_detail['pembelian_detail_id']]?></td>
									<td>&nbsp;:&nbsp;</td>
									<td><?=$kirim_detail['jumlah_kirim']."&nbsp;".$kirim_detail['nama_satuan']?></td>
								</tr>
							<?php
								$idy++;

								}	
							?>		
								
							</tr>	
					<?php
								$idx++;
							}
						?>
							</tbody>
						</table>
					</td>
				<?php
					}
				?>
				
				
			</tr>
					
			<tr>
				<td>Payment</td>
				<td>:</td>
				<td width="430px" colspan="3"><?=($tempo->tipe_bayar_id != 3)?$nama_pembayaran->nama:$tempo->lama_tempo.' days after received the invoice'?></td>
				<td width="12%">Warranty</td>
				<td width="1%">:</td>
				<td><?=($pembelian['tanggal_garansi'] != '1970-01-01')?date('d M Y', strtotime($pembelian['tanggal_garansi'])):'-' ?></td>
			</tr>
			<tr>
				<td>Delivery To</td>
				<td>:</td>
				<td colspan="3"><?=$customer->nama?></td>
				<td>Validity</td>
				<td>:</td>
				<td><strong><?=($pembelian['tanggal_kadaluarsa'] != '1970-01-01')?date('d M Y', strtotime($pembelian['tanggal_kadaluarsa'])):'-' ?></strong></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="3"><?=$customer_alamat[0]['alamat']?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="3"><?='Kel. '.ucwords(strtolower($customer_alamat[0]['nama_kelurahan'])).' Kec.'.ucwords(strtolower($customer_alamat[0]['kecamatan']))?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="3"><?=ucwords(strtolower($customer_alamat[0]['kabkot'])).' '.$customer_alamat[0]['propinsi'].' '.$customer_alamat[0]['kode_pos']?> - Indonesia</td>
			</tr>
			<tr>
				<td>NPWP</td>
				<td>:</td>
				<td colspan="3"><?=$customer->npwp?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="3"><?=$customer->nama_wp?></td>
			</tr><tr>
				<td></td>
				<td></td>
				<td colspan="3"><?=$customer->email_wp?></td>
			</tr>
		</table>
		<?php 
			if (file_exists(FCPATH."assets/mb/global/image/logo/logo-big.png") && is_file(FCPATH."assets/mb/global/image/logo/logo-big.png")) 
	        {
	            $image_header = base_url()."assets/mb/global/image/logo/stempel.png";
	        }
	        else 
	        {
	            $image_header = base_url()."assets/mb/global/image/logo/stempel.png";
	        }
	        
		?>

		<div class="signature">
			<table id="signature">
				<tr>
					<td width="30%"><b>Created By,</b></td>
					<td width="30%"><b>Approval By,</b></td>
					<td width="30%"><b>Approval By,</b></td>
					<td width="30%"><b>Accepted By,</b></td>
				</tr>
				<tr >
					<td style="height: 75px;"></td>
					<td style="height: 75px;"></td>
					<td style="height: 75px;"></td>
					<td style="height: 75px;"></td>
				</tr>
				<tr>
					<td><?=$membuat?></td>
					<td>Ari Hartanto</td>
					<td><?=$menyetujui?></td>
					<td><?=$supplier->orang_yang_bersangkutan?></td>
				</tr>
				<tr>
					<td>SIPA: <?=$sipa_membuat?></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			<div class="stempel"></div>			
		</div>

		
	</div>

</body>
</html>

