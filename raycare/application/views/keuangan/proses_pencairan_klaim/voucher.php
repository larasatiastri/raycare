<html>
	<head>
		<style type="text/css">

			body
			{
				font-size: 13px;
				font-family: Arial, Verdana, "Times New Roman";
			}

			#header 
			{
				width: 95%;
				margin: auto;
			}

			#head-table, #head-table-inform{
				width: 100%;
				margin-bottom: 10px;
			}

			#body
			{
				width: 95%;
				margin: auto;
			}

			#body-table td{
				height: 75px;
			}

			#body-table{
				border-collapse:collapse;
				color:#333;
				width: 100%;
			}

			#body-table #signature{
				width: 20%;
				text-align: center;
				height: 0px;
			}

			#body-table th, #body-table td{
				vertical-align:top;
				padding:5px 10px;
				border:1px solid #000;
			}

			#title {
				text-transform: uppercase;
				text-align: center;
			}
			#logo {
				width: 20%;
			}

			.title-child{
				font-size: 14px;
			}

			
		</style>
	</head>
	<body>
		<div id="header">
			<table id="head-table">
				<tr>
					<td width="10%">
						<?php 
							if (file_exists(FCPATH."assets/mb/global/image/logo/logo-big.png") && is_file(FCPATH."assets/mb/global/image/logo/logo-big.png")) 
					        {
					            $image_header = base_url()."assets/mb/global/image/logo/logo-big.png";
					        }
					        else 
					        {
					            $image_header = base_url()."assets/mb/global/image/logo/logo-global.png";
					        }
						?>
						<img id="logo" src="<?=$image_header?>" >
					</td>
					<td id="title">
						<h2>PT. RAVENA INDONESIA</h2>
						<p class="title-child">Bukti Pengeluaran Kas/Bank</p>
						<p class="title-child">(Cash/Bank Receipt Voucher)</p>
						<p class="title-child">(USD/IDR)</p>
					</td>
				</tr>
			</table>

			<table id="head-table-inform">
				<tr>
					<td width="20%">Nomor (Number)</td>
					<td>:</td>
				</tr>
				<tr>
					<td>Tanggal (Date)</td>
					<td>: <?=date('d M Y', strtotime($form_data['tanggal_proses']))?></td>
				</tr>

			</table>
		</div>
		<div id="body">

			<table id="body-table">
				<tr>
					<td colspan="2" rowspan="2">
						<?php

							$get_nama = $this->user_m->get_by(array('id' => $form_data['diproses_oleh']), true);
							$get_created_by = $this->user_m->get_by(array('id' => $form_data['created_by']), true);
							$get_setuju = $this->user_m->get_by(array('id' => $form_data['disetujui_oleh']), true);
							// die_dump($get_nama);

						?>
						Dibayarkan Kepada : <?=$get_created_by->nama?>
						<br>(Paid To)
					</td>
					<td id="signature">
						Dibuat Oleh
						<br>(Made by)
					</td>
				</tr>
				<tr>
					<td rowspan="2">
						<?=$get_created_by->nama?>
						<br> <br> <br> <br>
					</td>
				</tr>
				<tr>
					<td rowspan="2">
						Jumlah Rupiah/USD : 
						<br>(Amount)
						<br>
						<br><?=formatrupiah($form_data['nominal_setujui']+$form_data['sisa'])?>
					</td>
					<td rowspan="2">
						<?php
							$rupiah = $form_data['nominal_setujui']+$form_data['sisa'];
							// die_dump($rupiah);
		        			$terbilang = terbilang($rupiah);
		        			// die_dump($terbilang);
						?>
						Terbilang : 
						<br>(Say)
						<br>
						<br><?=$terbilang?> Rupiah
					</td>
				</tr>
				<tr>
					<td id="signature">
						Disetujui Oleh
						<br>(Approved by)
					</td>
				</tr>
				<tr>
					<td rowspan="3">
						Dibayarkan dengan :
						<br>(Paying with)
						<br>
						<?php

							$permintaan_biaya_id = $form_data['id'];
        					
        					// die_dump($get_data_tipe_bayar);

        					$tunai = '<input id="tunai" type="checkbox" checked="checked">';
        					$cek = '<input id="cek" type="checkbox">';
        					$giro = '<input id="giro" type="checkbox">';

        					// die_dump($giro);


						?>
						<br><?=$tunai?> Tunai
						<br><?=$cek?> Cheque No:
						<br><?=$giro?> Bilyetgiro No:
					</td>
					<td rowspan="3">
						Uraian Pembayaran : 
						<br>(Being)
						<br>
						<br> <?=$form_data['keperluan']?>
					</td>
					<td><?=$get_setuju->nama?></td>
				</tr>
				<tr>
					<td id="signature">
						Diterima Oleh
						<br>(Received by)
					</td>
				</tr>
				<tr>
					<td><?=$get_created_by->nama?></td>
				</tr>

			</table>
			
		</div>
		<div id="footer">
			
		</div>
	</body>
</html>