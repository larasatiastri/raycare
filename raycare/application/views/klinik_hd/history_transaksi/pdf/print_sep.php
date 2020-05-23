<html>
	<head>
		<style type="text/css">

			body
			{
				font-size: 12px;
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
				font-size: 16px;
				margin-left: 30px;
			}

			.title-child{
				font-size: 14px;
			}

			.head{
				display: block;
				width: 95%;
				margin: auto;
			}

			.logo{
				float: left;
				width : 206px;
				margin-left: -20px;
			}

			.title{
				font-size: 14px;
				text-align: center;
			}

			.title p{
				margin-left: -200px;
			}

			.term{
				font-size: 9px;
				font-style: italic;
			}

		</style>
	</head>
	<body>

		<?php 
        $sep_tindakan = $this->sep_tindakan_m->get_data_detail($tindakan_hd_id)->result_array();
        // die(dump($this->db->last_query()));
        foreach ($sep_tindakan as $detail) {
		?>
		<div id="header">
			<div class="head">
				<div class="logo">
					<?php 
						if (file_exists(FCPATH."assets/mb/global/image/logo/logo-bpjs.png") && is_file(FCPATH."assets/mb/global/image/logo/logo-bpjs.png")) 
				        {
				            $image_header = base_url()."assets/mb/global/image/logo/logo-bpjs.png";
				        }
				        else 
				        {
				            $image_header = base_url()."assets/mb/global/image/logo/logo-global.png";
				        }
					?>
					<img src="<?=$image_header?>" >
				</div>

				<div class="title text-center">
					<p>
						SURAT ELEGIBILITAS PESERTA<br/>
						KLINIK RAYCARE (HAEMODIALISA)
					</p>
				</div>
			</div>
			<table id="head-table-inform">
				<tr>
					<td width="15%"><?=translate('No. SEP', $this->session->userdata('language'))?></td>
					<td width="2%">:</td>
					<td width="33%"><?=strtoupper($detail['no_sep'])?></td>
				</tr>
				<tr>
					<td><?=translate('Tgl. SEP', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=date('d-m-Y', strtotime($detail['tanggal_sep']))?></td>
				</tr>

				<tr>
					<td><?=translate('No.Kartu', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=$detail['no_kartu']?></td>
					<!-- Right Coloumn < -->
					<td width="15%"><?=translate('Peserta', $this->session->userdata('language'))?></td>
					<td width="2%">:</td>
					<td><?=$detail['jenis_peserta']?></td>
					<!-- Right Coloumn > -->
				</tr>

				<tr>
					<td><?=translate('Nama Peserta', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=strtoupper($detail['nama_pasien'])?></td>
				</tr>

				<tr>
					<td><?=translate('Tgl. Lahir', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=date('d-m-Y', strtotime($detail['tanggal_lahir']))?></td>

					<!-- Right Coloumn < -->
					<td><?=translate('COB', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=$detail['cob']?></td>
					<!-- Right Coloumn > -->
				</tr>

				<tr>
					<td><?=translate('Jns.Kelamin', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=$detail['gender']?></td>

					<!-- Right Coloumn < -->
					<?php 
						$jenis_rawat = translate('Rawat Jalan', $this->session->userdata('language'));
						if ($detail['jenis_rawat'] == 2) {
							$jenis_rawat = translate('Rawat Inap', $this->session->userdata('language'));
						}
					?>
					<td><?=translate('Jns.Rawat', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=$jenis_rawat?></td>
					<!-- Right Coloumn > -->
				</tr>

				<tr>
					<td><?=translate('Poli Tujuan', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td>Hemodialisa</td>

					<!-- Right Coloumn < -->

					<?php 
						$kelas_rawat = translate('Kelas I', $this->session->userdata('language'));
						if ($detail['kelas_rawat'] == 2) {
							$kelas_rawat = translate('Kelas II', $this->session->userdata('language'));
						}elseif ($detail['kelas_rawat'] == 3) {
							$kelas_rawat = translate('Kelas III', $this->session->userdata('language'));
						}
					?>
					<td><?=translate('Kls.Rawat', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=$kelas_rawat?></td>
					<!-- Right Coloumn > -->
				</tr>

				<tr>
					<td><?=translate('Asal Faskes Tk. I', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=strtoupper($detail['ref_kode_rs_rujukan'])?></td>
				</tr>

				<tr>
					<td><?=translate('Diagnosa Awal', $this->session->userdata('language'))?></td>
					<td>:</td>
					<!-- <td><?=$detail['nama_diagnosa']?></td> -->
					<td>Chronic renal failure, unspecified</td>

					<!-- Right Coloumn < -->
					<td style="padding-left:15px;">Pasien/</td>
					<td></td>
					<td style="padding-left:15px;">Petugas</td>
					<!-- Right Coloumn > -->
				</tr>

				<tr>
					<td><?=translate('Catatan', $this->session->userdata('language'))?></td>
					<td>:</td>
					<td><?=$detail['catatan']?></td>

					<!-- Right Coloumn < -->
					<td style="padding-left:15px;"><?=translate('Keluarga Pasien', $this->session->userdata('language'))?></td>
					<td></td>
					<td style="padding-left:15px;"><?=translate('BPJS Kesehatan', $this->session->userdata('language'))?></td>
					<!-- Right Coloumn > -->
				</tr>

				<tr>
					<td rowspan="2" colspan="3" style="padding-top:5px;">
						<span class="term">*Saya Menyetujui BPJS Kesehatan menggunakan informasi Medis Pasien jika diperlukan.<br/>
						*SEP bukan sebagai bukti penjaminan peserta</span>
					</td>
				</tr>
				
				<tr>
					<!-- Right Coloumn < -->
					<td style="padding-top:20px; padding-left:15px;">_____________</td>
					<td></td>
					<td style="padding-top:20px; padding-left:15px;">_____________</td>
					<!-- Right Coloumn > -->
				</tr>

				<tr>
					<td colspan="3"><?=translate('Cetakan Ke', $this->session->userdata('language'))?> <?=$detail['cetakan_ke']?> - <?=$detail['tanggal_cetak']?></td>
				</tr>
			</table>
		</div>

	
		
		<div id="footer">
			
		</div>
		<?php

		}
		?>
	</body>
</html>