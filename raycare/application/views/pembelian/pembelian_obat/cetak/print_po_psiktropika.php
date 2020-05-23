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
		$pegawai_user = $this->pegawai_user_m->get_by(array('user_id' => $pembelian['created_by']), true);
		$pegawai = $this->pegawai_m->get_by(array('id' => $pegawai_user->pegawai_id), true);
		$membuat = '';
		if(count($user) != 0){
			$membuat = $pegawai->nama;
			$tanda_tangan_issued = base_url()."assets/mb/pages/master/user/images/".$user->username."/".$user->url_sign;
		}else{
			$membuat = 'Nico';
		}

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
		<div class="head-title" style="padding:40px 113px 0 113px;">
			<div class="title" style="border-bottom:1px solid #000;font-size:16px;" align="center"><b>SURAT PESANAN OBAT MENGANDUNG PSIKTROPIKA</b></div>
			<div class="no_surat" style="" align="center"><b>Nomor SP :</b> <?=$pembelian['no_pembelian']?></div>
		</div>
		
		<div style="background-color:red !important;height:30px;width:100%;margin-top:15px;">
			<div style="float:left;height:30px;width:700px;padding-top:15px;">		
				<table id="head_content" border="0">
					<tr>
						<td style="vertical-align:top;" width="700px" colspan="3">Yang bertandatangan di bawah ini :</td>
						<br>
					</tr>
					<tr>
						<td style="height:6px;"></td>
					</tr>
					<tr>
						<td style="vertical-align:top;" width="80px;">Nama</td>
						<td style="vertical-align:top;" width="5px;">: </td>
						<td style="vertical-align:top;" width="350px;"><?=$membuat?></td>
					</tr>
					<tr>
						<td style="vertical-align:top;" width="80px">Jabatan</td>
						<td style="vertical-align:top;" width="4px">: </td>
						<td style="vertical-align:top;" width="350px">Apoteker Penanggung Jawab</td>
					</tr>
					<tr>
						<td style="vertical-align:top;" width="80px">No. SIPA</td>
						<td style="vertical-align:top;" width="4px">: </td>
						<td style="vertical-align:top;" width="350px"><?=$user->sip?></td>
					</tr>
				</table>
			</div>
			<div style="float:left;height:30px;width:700px;padding-top:15px;">	
			<table id="head_content" border="0">	
					<tr>
						<td style="vertical-align:top;" width="700px;" colspan="3">Mengajukan pesanan obat mengandung Psiktropika kepada :</td>
					</tr>
					<tr>
						<td style="height:6px;"></td>
					</tr>
					<tr>
						<td style="vertical-align:top;" width="80px;">Nama PBF</td>
						<td style="vertical-align:top;" width="5px;">: </td>
						<td style="vertical-align:top;" width="350px;"><?=$supplier->nama?></td>
					</tr>
					
					<tr>
						<td style="vertical-align:top;">Alamat</td>
						<td style="vertical-align:top;">: </td>
						<td style="vertical-align:top;"><?=$supplier_alamat->alamat.'<br>'.$kelurahan?></td>
					</tr>
					<tr>
						<td style="vertical-align:top;">Telp</td>
						<td style="vertical-align:top;">: </td>
						<td style="vertical-align:top;"><?=(count($supplier_telepon))?$supplier_telepon->no_telp:'-' ?></td>
					</tr>
					
				</table>
			</div>
			
		</div>

		
		<div id="text">Jenis obat mengandung Psiktropika yang dipesan adalah :</div>

		<table id="body_content" class="table" style="border:1px solid #2462AC !important;">
			<tr>
				<th width="3%;" class="text-center" style="background:#2462AC;color:#fff;">No.</th>
				<th width="27%;" class="text-center" style="background:#2462AC;color:#fff;">Nama Obat Mengandung Psiktropika</th>
				<th width="30%;" class="text-center" style="background:#2462AC;color:#fff;">Zat Aktif Psiktropika</th>
				<th width="20%;" class="text-center" style="background:#2462AC;color:#fff;">Bentuk & Kekuatan Sediaan</th>
				<th width="10%;" class="text-center" style="background:#2462AC;color:#fff;">Satuan</th>
				<th width="10%;" class="text-center" style="background:#2462AC;color:#fff;">Jumlah</th>
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

					$jumlah = intval($detail['jumlah_disetujui']);
					$jumlah = terbilang($jumlah);
			?>
				<tr>
					<td class="text-center <?=$row_color?>" ><?=$i.'.'?></td>
					<td class="<?=$row_color?>" style="padding-left:5px;" ><?=$detail['nama']?></td>
					<td class="text-right <?=$row_color?>" width="6%;" style="<?=$border_color?>;"><?=$detail['zat_aktif']?></td>
					<td class="text-left <?=$row_color?>" width="11%;"><?=$detail['bentuk_kekuatan']?></td>
					<td class="text-right <?=$row_color?>" width="16%;"><?=$detail['nama_satuan']?></td>
					<td class="text-right <?=$row_color?>" width="21%;"><?=intval($detail['jumlah_disetujui']).' ('.$jumlah.')'?></td>
				</tr>
			<?php
						$total = $total + (($detail['jumlah_disetujui'] * $detail['harga_beli']) - ($detail['jumlah_disetujui'] * $detail['harga_beli'])*$detail['diskon']/100);
						$i++;
					}
				}

			?>


		</table>
		<div style="margin-bottom:5px;">Obat mengandung Psiktropika tersebut akan digunakan untuk memenuhi kebutuhan :</div>

		<div style="float:left;height:30px;width:700px;padding-right:250px;">	
			<table id="body_info" border="0">
				<tr>
					<td width="140px">Nama Apotek</td>
					<td width="8px">:</td>
					<td width="350px" colspan="3">Apotik Raycare</td>
				</tr>
				<tr>
					<td width="80px">Alamat Lengkap</td>
					<td width="6px">:</td>
					<td width="350px" colspan="3">Jl. Peta Selatan No. 6, Kalideres - Jakarta Barat 11840</td>
				</tr>
				<tr>
					<td width="80px" height="20px"></td>
				</tr>
				<tr>
					<td width="80px">Surat Ijin Apotek</td>
					<td width="6px">:</td>
					<td width="350px" colspan="3">003/2.30.1/31.73.06/1.779.3/V/2016</td>
				</tr>
				
			</table>
		</div>
		
		<br>
		<div style="float:right;height:30px;width:207px;">
				<table id="head_info">
					<tr>
						<td style="text-align:left;">Jakarta, <?=date('d F Y', strtotime($pembelian['tanggal_pesan']))?></td>
					</tr>
					<tr>
						<td width="30%"><b>Pemesan</b></td>
					</tr>
					<tr >
						<td width="30%" height="70px;"></td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid #000;"><b><?=$membuat?></b></td>
					</tr>
					
					<tr>
						<td style="font-size:10.2px;">No. SIPA<?=$user->sip?></td>
					</tr>

				</table>
			</div>
		</div>
	</body>
</html>

