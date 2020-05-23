<?php
	$penjamin_pasien = $this->pasien_penjamin_m->get_by(array('pasien_id' => $pasien['id'], 'penjamin_id' => $penjamin_id, 'is_active' => 1), true);
	$no_penjamin = (count($penjamin_pasien) != 0)? $penjamin_pasien->no_kartu : '';

	$jenis_peserta = ($tindakan_hd['jenis_peserta'] != '' && $tindakan_hd['jenis_peserta'] != NULL)?' ('.$tindakan_hd['jenis_peserta'].')':'';
?>

<html>
<head>
	<style type="text/css">
		body
		{
			font-family: Arial;
			text-align: justify;
		}
	</style>
	
</head>
	<body>
		<div id="body_invoice">
			<div style="width:375px; height:20px; margin-left:5px; text-align:center;">
				<div style="float:left;width:182px; height:10px;margin:0 15px 0 100px;border-bottom:1.5px solid #000; text-align:center; font-size:12px;vertical-align:bottom;"><b>TREATMENT RECEIPT NOTE<b></div>
				<div style="float:left;width:182px; height:10px;margin:0 15px 0 100px;font-size:10px;"><b>TANDA TERIMA JASA HEMODIALISA<b></div>
			</div>
			
		</div>
		<div id="head_invoice" style="position:fixed;left:5px;top:75px;">
			<table border=0 width="373.5px" style="font-size:11px;">
				<tr>
					<td width="10px" valign="top">No. Transaksi</td>
					<td width="2px" valign="top">:</td>
					<td width="5px" valign="top"><?=$invoice['no_invoice']?></td> 

					<td width="10px" valign="top">Penanggung</td>
					<td width="2px" valign="top">:</td>
					<td width="5px" valign="top"><?=$invoice['nama_penjamin'].$jenis_peserta?></td>
				</tr>
				<tr>
					<td width="10px" valign="top">Nama Pasien</td>
					<td width="1%" valign="top">:</td>
					<td width="5px" valign="top"><?=$pasien['nama']?></td>

					<td width="10px" valign="top">No.Penanggung</td>
					<td width="2px" valign="top">:</td>
					<td width="5px" valign="top"><?=($invoice['penjamin_id'] == 1)?'-':$no_penjamin?></td>
				</tr>
				<tr>
					<td width="10px" valign="top">No. Pasien</td>
					<td width="2px" valign="top">:</td>
					<td width="5px" valign="top"><?=$pasien['no_member']?></td>
 
					<td width="10px" valign="top">Waktu</td>
					<td width="2px" valign="top">:</td>
					<td width="5px" valign="top"><?=$invoice['waktu_tindakan']?></td>
				</tr>
					
			</table>
		</div>

		<div id="isi_invoice" style="position:fixed; left:5px; top:190px;">
			<table border=0 width="373.5px;" style="font-size:11px;">
				<tr>
					<td width="1%" style="text-align:center;"><b>NO.</b></td>
					<td width="51%" style="text-align:center;" colspan="2"><b>NAMA TRANSAKSI</b></td>
					<td width="3%" style="text-align:center;"><b>QTY</b></td>
					<td width="20%" style="text-align:center;"><b>BIAYA</b></td>
				</tr>
				<tr>
					<td width="99%" colspan="5" height="6px"></td>
				</tr>
				<?php
					if($invoice['user_level_id'] == NULL || $invoice['user_level_id'] == 0){
			            if($this->session->userdata('level_id') == 19){
			                $invoice_data['created_by'] = $this->session->userdata('user_id');
			                $invoice_data['user_level_id'] = $this->session->userdata('level_id');
			                $edit_invoice = $this->invoice_m->save($invoice_data,$invoice['id']);
			            }
			        }
					$user = $this->user_m->get($invoice['created_by']);

					$i = 1;
					if($invoice_paket != '')
					{
						$total_paket = 0;
						foreach ($invoice_paket as $paket) 
						{
				?>
							<tr>
								<td width="1%" style="text-align:left;"><b><?=$i?></b></td>
								<td width="51%" colspan="2"><b>Paket Hemodialisis</b></td>
								<td width="3%" style="text-align:center;"><?=$paket['qty']?></td>
								<td width="20%" style="text-align:right;">IDR <?=formattanparupiah($paket['qty'] * $paket['harga'])?> #</td>
							</tr>


				<?php
							$total_paket = $total_paket + ($paket['qty'] * $paket['harga']);
							$i++;
						}
					}
				?>	
							<tr>
								<td width="99%" colspan="5" height="6px"></td>
							</tr>
							<tr>
								<td width="1%" style="text-align:left;"><b><?=$i?></b></td>
								<td width="98%" colspan="4"><b>Obat</b></td>	
							</tr>
				<?php
					if($invoice_item != '')
					{
				?>
							
				<?php
						$data_heparin = $this->invoice_detail_m->get_by(array('invoice_id' => $invoice['id'], 'item_id' => 8, 'tipe_item' => 2,'is_active' => 1), true);

						$total_item = 0;
						if(count($data_heparin))
						{
							$data_item = $this->item_m->get(8);
				?>
							<tr>
								<td width="1%"></td>
								<td width="1%" style="text-align:right;padding:0; margin:0;"><?='A.1'?></td>
								<td width="50%"><?=$data_item->nama?></td>
								<td width="3%" style="text-align:center;"><?=$data_heparin->qty?></td>
								<td width="17%" style="text-align:right;">IDR <?=formattanparupiah($data_heparin->qty*$data_heparin->harga)?> #</td>
							</tr>
							<tr>
								<td width="1%"></td>
								<td width="1%" style="text-align:right;"><?='   2'?></td>
								<td width="50%"></td>
								<td width="3%"></td>
								<td width="17%" style="text-align:right;"></td>
							</tr>
							<tr>
								<td width="80%" colspan="5" height="7px"></td>
							</tr>
				<?php	
							$total_item = ($data_heparin->qty*$data_heparin->harga);
						} 
						else
						{
							if($invoice['penjamin_id'] == 2)
							{

				?>
							<tr>
								<td width="1%"></td>
								<td width="1%" style="text-align:right;">A.1</td>
								<td width="50%"></td>
								<td width="3%"></td>
								<td width="17%" style="text-align:right;"></td>
							</tr>
							<tr>
								<td width="1%"></td>
								<td width="1%" style="text-align:right;">   2</td>
								<td width="50%"></td>
								<td width="3%"></td>
								<td width="17%" style="text-align:right;"></td>
							</tr>
							<tr>
								<td width="80%" colspan="5" height="7px"></td>
							</tr>
				<?php
							}
						}

						$x = 1;
						$y = 1;
						$total_items = 0;
						foreach ($invoice_items as $item) 
						{
							if($item['item_id'] != 8 && $item['tipe_item'] == 2)
							{
								
								$data_item = $this->item_m->get($item['item_id']);

								?>
									<tr>
										<td width="1%"></td>
										<td width="1%"  style="text-align:right;"><?=($y == 1)?'B.'.$y:'  '.$y?></td>
										<td width="50%"><?=$data_item->nama?></td>
										<td width="3%" style="text-align:center;"><?=$item['qty']?></td>
										<td width="17%" style="text-align:right;">IDR <?=formattanparupiah($item['qty']*$item['harga'])?> #</td>
									</tr>
								<?php
							$total_items = $total_items + ($item['qty']*$item['harga']);
							}
							if($item['item_id'] != 8 && $item['tipe_item'] == 3)
							{
								
								$data_item = $this->tindakan_m->get($item['item_id']);

								?>
									<tr>
										<td width="1%"></td>
										<td width="1%"  style="text-align:right;"><?=($y == 1)?'B.'.$y:'  '.$y?></td>
										<td width="50%"><?=$data_item->nama?></td>
										<td width="3%" style="text-align:center;"><?=$item['qty']?></td>
										<td width="17%" style="text-align:right;">IDR <?=formattanparupiah($item['qty']*$item['harga'])?> #</td>
									</tr>
								<?php
							$total_items = $total_items + ($item['qty']*$item['harga']);
							}
							$x++;
							$y++;
						}
					}
				?>
				<tr>
					<td width="100%" colspan="5" height="10px"></td>
				</tr>
				<tr>
					<td width="1%" style="text-align:left;"><b><?=$i+1?></b></td>
					<td width="98%" colspan="4"><b>Penunjang Medik</b></td>
							
					
				</tr>
				<?php
					if($invoice_alat != '')
					{
				?>
				<?php

						$z = 1;
						$total_alat = 0;
						foreach ($invoice_alat as $alat) 
						{
							if($alat['tipe_item'] == 2)
							{
								$data_item = $this->item_m->get($alat['item_id']);
							?>
							<tr>
								<td width="1%"></td>
								<td width="1%" style="text-align:right;"><?=$z?></td>
								<td width="50%"><?=$data_item->nama?></td>
								<td width="3%" style="text-align:center;"><?=$alat['qty']?></td>
								<td width="17%" style="text-align:right;">IDR <?=formattanparupiah($alat['qty']*$alat['harga'])?> #</td>
							</tr>
							<?php
							}
							elseif ($alat['tipe_item'] == 3) 
							{
								$data_item = $this->tindakan_m->get($alat['item_id']);
							?>
							<tr>
								<td width="1%"></td>
								<td width="1%" style="text-align:right;"><?=$z?></td>
								<td width="50%"><?=$data_item->nama?></td>
								<td width="3%" style="text-align:center;"><?=$alat['qty']?></td>
								<td width="17%" style="text-align:right;">IDR <?=formattanparupiah($alat['qty']*$alat['harga'])?> #</td>
							</tr>
							<?php
							}
							$z++;
							$total_alat = $total_alat + ($alat['qty']*$alat['harga']);
						}
					}
					else
					{
				?>
					<tr>
						<td width="1%"></td>
						<td width="1%" style="text-align:right;">1</td>
						<td width="50%"></td>
						<td width="3%"></td>
						<td width="17%" style="text-align:right;"></td>
					</tr>
					<tr>
						<td width="1%"></td>
						<td width="1%" style="text-align:right;">2</td>
						<td width="50%"></td>
						<td width="3%"></td>
						<td width="17%" style="text-align:right;"></td>
					</tr>
					<tr>
						<td width="1%"></td>
						<td width="1%" style="text-align:right;">3</td>
						<td width="50%"></td>
						<td width="2%"></td>
						<td width="17%" style="text-align:right;"></td>
					</tr>
				<?php
					}
				?>
				
				<tr>
					<td width="80%" colspan="5" height="10px"></td>
				</tr>
			</table>

		</div>
		<div style="position:fixed; left:5px; top:460px; width:373.5px;">
			<table border=0 width="100%" style="font-size:11px;">
				<tr>
					<td width="65%" colspan="4" style="text-align:right;">TOTAL TRANSAKSI</td>
					<td width="26%" style="text-align:right;">IDR <?=formattanparupiah($total_paket + $total_item + $total_items + $total_alat)?> #</td>
				</tr>
				<?php
					$akomodasi = 0;
					if($invoice['akomodasi'] != NULL && $invoice['akomodasi'] != 0){
						$akomodasi = $invoice['akomodasi'];
				?>
				<tr>
					<td width="65%" colspan="4" style="text-align:right;">AKOMODASI / BIAYA ADMIN</td>
					<td width="26%" style="text-align:right;">IDR <?=formattanparupiah($akomodasi)?> #</td>
				</tr>
				<?php
					}
				?>
				<tr>
					<td width="65%" colspan="4" style="text-align:right;">PEMBULATAN</td>
					<td width="26%" style="text-align:center;">-</td>
				</tr>
				<tr>
					<td width="65%" colspan="4" style="text-align:right;"><b>TOTAL BIAYA</b></td>
					<td width="26%" style="text-align:right;"><b>IDR <?=formattanparupiah($total_paket + $total_item + $total_items + $total_alat + $akomodasi)?> #</b></td>
				</tr>
				<tr>
					<td width="99%" colspan="5" height="20px"></td>
				</tr>
				<tr>
					<td width="100%" colspan="5"><b>Terbilang :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# <?=terbilang($total_paket + $total_item + $total_items + $total_alat + $akomodasi)?> Rupiah #</b></td>
				</tr>
			</table>
			<table border=0 width="100%" style="font-size:11px;margin-top:20px;">
				<tr>
					<td width="50%" style="text-align:center;">Jakarta, <?=date('d F Y', strtotime($invoice['created_date']))?></td>
					<td width="50%"></td>
				</tr>
				<tr>
					<td width="100%" colspan="2" height="5px"></td>
				</tr>
				<tr>
					<td width="50%" style="text-align:center;">Kasir</td>
					<td width="50%" style="text-align:center;">Pasien</td>
				</tr>
				<tr>
					<td width="100%" colspan="2" height="55px"></td>
				</tr>
				<tr>
					<td width="50%" style="text-align:center;"><?=$user->nama?></td>
					<td width="50%" style="text-align:center;"><?=$pasien['nama']?></td>
				</tr>

			</table>
		</div>
	</body>
</html>
