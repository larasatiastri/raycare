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
		$supplier = $this->supplier_m->get($form_data['supplier_id']);
		$supplier_alamat = $this->supplier_alamat_m->get_by(array('supplier_id' => $form_data['supplier_id'], 'is_primary' => 1, 'is_active' => 1), true);
		$lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $supplier_alamat->kode_lokasi), true);


		$supplier_telepon = $this->supplier_telepon_m->get_by(array('supplier_id' => $form_data['supplier_id'], 'subjek_telp_id' => 2, 'is_active' => 1), true);
		$supplier_fax = $this->supplier_telepon_m->get_by(array('supplier_id' => $form_data['supplier_id'], 'subjek_telp_id' => 8, 'is_active' => 1), true);
		$supplier_mobile = $this->supplier_telepon_m->get_by(array('supplier_id' => $form_data['supplier_id'], 'subjek_telp_id' => 5, 'is_active' => 1), true);
		$supplier_email = $this->supplier_email_m->get_by(array('supplier_id' => $form_data['supplier_id'], 'is_primary' => 1, 'is_active' => 1), true);
		$kelurahan = '';
		if(count($lokasi))
		{
			$kelurahan = ucwords(strtolower($lokasi->nama_kelurahan)).', '.ucwords(strtolower($lokasi->nama_kecamatan)).', '.ucwords(strtolower($lokasi->nama_kabupatenkota)).', '.ucwords(strtolower($lokasi->nama_propinsi));
		}

		$user = $this->user_m->get($pembelian['created_by']);
		$membuat = '';
		$sipa_membuat = '';
		if(count($user) != 0){
			$membuat = $user->nama;
			$sipa_membuat = $user->sip;
			$tanda_tangan_issued = base_url()."assets/mb/pages/master/user/images/".$user->username."/".$user->url_sign;
		}else{
			$membuat = '';
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
						<td style="text-align:right;">Tangerang, <?=date('d F Y', strtotime($form_data['tanggal']))?></td>
					</tr>

					

				</table>
				
			</div>
		</div>

		
		<div id="text">Here with we would like to return the item as below :</div>

		<table id="body_content" class="table" style="border:1px solid #2462AC !important;">
			<tr>
				<th width="3%;" class="text-center" style="background:#2462AC;color:#fff;">No.</th>
				<th width="35%;" class="text-center" style="background:#2462AC;color:#fff;">Description</th>
				<th width="10%;" class="text-center" style="background:#2462AC;color:#fff;">Code</th>
				<th width="25%;" colspan="2" class="text-center" style="background:#2462AC;color:#fff;">Qty</th>
				<th width="25%;" class="text-center" style="background:#2462AC;color:#fff;">BN</th>
				<th width="25%;" class="text-center" style="background:#2462AC;color:#fff;">ED</th>
				<th width="20%;" class="text-center" style="background:#2462AC;color:#fff;">Price</th>
				<th width="25%;" class="text-center" style="background:#2462AC;color:#fff;">Total</th>
			</tr>
			<?php
				$total = 0;
				$tgl_kirim = array();
				if($form_data_detail)
				{	
					$item_po = array();
					$i = 1;
					foreach ($form_data_detail as $detail) 
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
					<td class="text-right <?=$row_color?>" width="6%;" style="border-right:1px solid <?=$border_color?>;"><?=$detail['jumlah']?></td>
					<td class="text-left <?=$row_color?>" width="11%;"><?=$detail['nama_satuan']?></td>
					<td class="text-left <?=$row_color?>" width="11%;"><?=$detail['bn_sn_lot']?></td>
                    <td class="text-left <?=$row_color?>" width="11%;"><?=date('d M Y', strtotime($detail['expire_date']))?></td>
                    <td class="text-right<?=$row_color?>"><?=formatrupiah($detail['hpp'])?></td>
                    <td class="text-right <?=$row_color?>"><?=formatrupiah($detail['hpp']*$detail['jumlah'])?></td>
				</tr>
			<?php
						$total = $total + ($detail['hpp']*$detail['jumlah']);
						$i++;
					}
				}


			?>
			
			<tr>
				<td class="text-right" colspan="8">Grand Total</td>
				<td class="text-right"><?=formattanparupiahnol($total)?></td>
			</tr>
			

		</table>
	
		<div class="signature">
			<table id="signature">
				<tr>
					<td width="30%"><b>Created By,</b></td>
					
				</tr>
				<tr >
					<td style="height: 75px;"></td>
				</tr>
				<tr>
					<td><?=$membuat?></td>
				</tr>
				<tr>
					<td>SIPA: <?=$sipa_membuat?></td>
					
				</tr>
			</table>
			<div class="stempel"></div>			
		</div>

		
	</div>

</body>
</html>

