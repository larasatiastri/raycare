<html>
<head>
	<style type="text/css">
		
		body
		{
			font-family: Arial;
		}

		table {
			border: none;
			font-size: 10px;
			margin: 0;
			padding: 0;
		    border-collapse:collapse; 
		    line-height: 15px;
		}

		table#table_jadwal{
			font-size: 12px;
		}
		table#table_jadwal td{
			font-size: 12px;
			padding-right: 3px;
			border: 1px solid #343434;
		}

		table#table_jadwal th{
			border: 1px solid #343434;
		}

		table#table_jadwal2{
			font-size: 12px;
		}
		table#table_jadwal2 td{
			font-size: 12px;
			padding-right: 3px;
			border: 1px solid #343434;
		}

		table#table_jadwal2 th{
			border: 1px solid #343434;
		}

		tr.odd{
			background-color: #EFEFEF;
		}

		td.no{
			text-align: center;
		}
		#title {
			text-transform: uppercase;
			text-align: center;
			font-size: 11px;
		}
		
	</style>
</head>
<body>
	<div id="head_jadwal">
		<div id="title">
			<b><u>KLINIK RAYCARE</u></b>
			
		</div>
		<div id="title">
			<span style="font-size:10px;">JADWAL PELAYANAN PASIEN HEMODIALISA </span>
		</div>
		<div id="title">
			<span style="font-size:10px;">PERIODE&nbsp;<?=date('d M Y', strtotime($tanggal_awal))?> s/d <?=date('d M Y', strtotime($tanggal_akhir))?>
			</span>
		</div>
	</div>
	<br>
	<div id="body_jadwal">
		<table id="table_jadwal" width="100%" style="font-size:10px; border: 0.5px solid #343434;">
			<tr>
				<th width="3%" rowspan="2" class="no">No</th>
				<th width="40%" colspan="4">Senin</th>
				<th width="40%" colspan="4">Selasa</th>
				<th width="40%" colspan="4">Rabu</th>
				<th width="10%" colspan="4"></th>
			</tr>
			<tr>
				<th width="10%">Pagi</th>
				<th width="10%">Siang</th>
				<th width="10%">Sore</th>
				<th width="10%">Malam</th>
				<th width="10%">Pagi</th>
				<th width="10%">Siang</th>
				<th width="10%">Sore</th>
				<th width="10%">Malam</th>
				<th width="10%">Pagi</th>
				<th width="10%">Siang</th>
				<th width="10%">Sore</th>
				<th width="10%">Malam</th>
				<th width="10%"></th>
			</tr>
			<?php

				$date = date('d-M-Y', strtotime($tanggal_awal));
                // die_dump($date);
                $senin = date('d-m-Y', strtotime($date));
                $next_date = strtotime(date('d M Y', strtotime($date)). " +1 days");
                $selasa = date('d-m-Y', $next_date);
                $next_date_1 = strtotime(date('d M Y', strtotime($date)). " +2 days");
                $rabu = date('d-m-Y', $next_date_1);
                $next_date_2 = strtotime(date('d M Y', strtotime($date)). " +3 days");
                $kamis = date('d-m-Y', $next_date_2);
                $next_date_3 = strtotime(date('d M Y', strtotime($date)). " +4 days");
                $jumat = date('d-m-Y', $next_date_3);
                $next_date_4 = strtotime(date('d M Y', strtotime($date)). " +5 days");
                $sabtu = date('d-m-Y', $next_date_4);
                $next_date_5 = strtotime(date('d M Y', strtotime($date)). " +6 days");
                $minggu = date('d-m-Y', $next_date_5);
                $valid_date = strtotime(date('d M Y', strtotime($date)). " +7 days");
                $validasi = date('d-m-Y', $valid_date);
                // die_dump($minggu);

                $tgl_sekarang = date('d M Y');
                $selisih = $tgl_sekarang - $minggu;
               

				for ($i=1; $i <= 22 ; $i++) 
				{ 
					echo '<tr>
							<td class="no">'.$i.'</td>';

					for ($j=1; $j<=13; $j++) 
					{
						if($j < 5){
                            $tanggal = $date;
                        }
                        else if($j < 9 && $j > 4)
                        {     
                            $tanggal = $selasa;
                        }
                        else if($j < 13 && $j > 8)
                        {
                            $tanggal = $rabu;
                        }
                        
						$nama_pasien = '';

						if($j == 1 || $j == 5 || $j == 9 )
                        {
                            $data_tipe = "Pagi";
                        }
                        else if($j == 2 || $j == 6 || $j == 10)
                        {
                            $data_tipe = "Siang";
                        }
                        else if($j == 3 || $j == 7 || $j == 11)
                        {
                            $data_tipe = "Sore";
                        }
                        else
                        {
                            $data_tipe = "Malam";
                        }

						foreach ($jadwal as $data_row) 
						{
							$pasien_nama = $data_row['nama'];
							$tanggal_db = $data_row['tanggal'];
                            $tipe_db = $data_row['tipe'];
                            $no_bed_db = $data_row['no_urut_bed'];
                            $tgl =  date('d-m-Y',strtotime($tanggal_db));
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
                                $tipe_waktu = "Pagi";
                            }
                            else if ($tipe_db == 2) {
                                $tipe_waktu = "Siang";
                            }
                            else if ($tipe_db == 3) {
                                $tipe_waktu = "Sore";
                            }
                            else
                            {
                                $tipe_waktu = "Malam";
                            }

                            if ($tgl == $tgl_banding_1)
                            { 
	                            if($j < 5)
	                            {
	                                if($tipe_waktu == $data_tipe)
	                                {
	                                    if($no_bed_db == $i)
	                                    {
	                                        $nama_pasien = $pasien_nama;
	                                    }                                                       
	                                }
	                            }
	                      	}
	                      	if ($tgl == $tgl_banding_2)
                            { 
	                            if($j < 9 && $j > 4)
	                            {
	                                if($tipe_waktu == $data_tipe)
	                                {
	                                    if($no_bed_db == $i)
	                                    {
	                                        $nama_pasien = $pasien_nama;
	                                    }                                                       
	                                }
	                            }
	                      	}
	                      	if ($tgl == $tgl_banding_3)
                            { 
	                            if($j < 13 && $j > 8)
	                            {
	                                if($tipe_waktu == $data_tipe)
	                                {
	                                    if($no_bed_db == $i)
	                                    {
	                                        $nama_pasien = $pasien_nama;
	                                    }                                                       
	                                }
	                            }
	                      	}
	                      	if ($tgl == $tgl_banding_4)
                            { 
	                            if($j > 12)
	                            {
	                                if($tipe_waktu == $data_tipe)
	                                {
	                                    if($no_bed_db == $i)
	                                    {
	                                        $nama_pasien = $pasien_nama;
	                                    }                                                       
	                                }
	                            }
	                      	}
						}

						if($j == 1 || $j == 5 || $j == 9 || $j == 13)
                        {      
                            $btn_default = '<td class="text-center">'.$nama_pasien.'</td>';
                            echo $btn_default;
                        }
                        else if($j == 2 || $j == 6 || $j == 10)
                        {
                            
                            $btn_default = '<td class="text-center">'.$nama_pasien.'</td>';
                            echo $btn_default;
                        }
                        else if($j == 3 || $j == 7 || $j == 11)
                        {
                            
                           $btn_default = '<td class="text-center">'.$nama_pasien.'</td>';
                            echo $btn_default;
                        }
                        else
                        {
                            $btn_default = '<td class="text-center">'.$nama_pasien.'</td>';
                            echo $btn_default;
                        }
					}
					echo '</tr>';
				}
			?>
		</table>
		<br>
		<table id="table_jadwal2" width="100%" style="font-size:10px; border: 0.5px solid #343434;">
			<tr>
				<th width="3%" rowspan="2" class="no">No</th>
				<th width="40%" colspan="4">Kamis</th>
				<th width="40%" colspan="4">Jumat</th>
				<th width="40%" colspan="4">Sabtu</th>
				<th width="10%" >Minggu</th>
			</tr>
			<tr>
				<th width="10%">Pagi</th>
				<th width="10%">Siang</th>
				<th width="10%">Sore</th>
				<th width="10%">Malam</th>
				<th width="10%">Pagi</th>
				<th width="10%">Siang</th>
				<th width="10%">Sore</th>
				<th width="10%">Malam</th>
				<th width="10%">Pagi</th>
				<th width="10%">Siang</th>
				<th width="10%">Sore</th>
				<th width="10%">Malam</th>
				<th width="10%">Pagi</th>
			</tr>
			<?php

				for ($i=1; $i <= 22 ; $i++) 
				{ 
					echo '<tr>
							<td class="no">'.$i.'</td>';

					for ($j=1; $j<=13; $j++) 
					{
						if($j < 5){
                            $tanggal = $kamis;
                        }
                        else if($j < 9 && $j > 4)
                        {     
                            $tanggal = $jumat;
                        }
                        else if($j < 13 && $j > 8)
                        {
                            $tanggal = $sabtu;
                        }
                        else
                        {
                        	$tanggal = $minggu;
                        }
                        
						$nama_pasien = '';

						if($j == 1 || $j == 5 || $j == 9 || $j == 13 )
                        {
                            $data_tipe = "Pagi";
                        }
                        else if($j == 2 || $j == 6 || $j == 10)
                        {
                            $data_tipe = "Siang";
                        }
                        else if($j == 3 || $j == 7 || $j == 11)
                        {
                            $data_tipe = "Sore";
                        }
                        else
                        {
                            $data_tipe = "Malam";
                        }

                        // die(dump($jadwal))
						foreach ($jadwal as $data_row) 
						{
							$pasien_nama = $data_row['nama'];
							$tanggal_db = $data_row['tanggal'];
                            $tipe_db = $data_row['tipe'];
                            $no_bed_db = $data_row['no_urut_bed'];
                            $tgl =  date('d-m-Y',strtotime($tanggal_db));
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
                                $tipe_waktu = "Pagi";
                            }
                            else if ($tipe_db == 2) {
                                $tipe_waktu = "Siang";
                            }
                            else if ($tipe_db == 3) {
                                $tipe_waktu = "Sore";
                            }
                            else
                            {
                                $tipe_waktu = "Malam";
                            }

                            if ($tgl == $tgl_banding_4)
                            { 
	                            if($j < 5)
	                            {
	                                if($tipe_waktu == $data_tipe)
	                                {
	                                    if($no_bed_db == $i)
	                                    {
	                                        $nama_pasien = $pasien_nama;
	                                    }                                                       
	                                }
	                            }
	                      	}
	                      	if ($tgl == $tgl_banding_5)
                            { 
	                            if($j < 9 && $j > 4)
	                            {
	                                if($tipe_waktu == $data_tipe)
	                                {
	                                    if($no_bed_db == $i)
	                                    {
	                                        $nama_pasien = $pasien_nama;
	                                    }                                                       
	                                }
	                            }
	                      	}
	                      	if ($tgl == $tgl_banding_6)
                            { 
	                            if($j < 13 && $j > 8)
	                            {
	                                if($tipe_waktu == $data_tipe)
	                                {
	                                    if($no_bed_db == $i)
	                                    {
	                                        $nama_pasien = $pasien_nama;
	                                    }                                                       
	                                }
	                            }
	                      	}
	                      	if ($tgl == $tgl_banding_7)
                            { 
	                            if($j > 12)
	                            {
	                                if($tipe_waktu == $data_tipe)
	                                {
	                                    if($no_bed_db == $i)
	                                    {
	                                        $nama_pasien = $pasien_nama;
	                                    }                                                       
	                                }
	                            }
	                      	}
						}

						if($j == 1 || $j == 5 || $j == 9 || $j == 13)
                        {      
                            $btn_default = '<td class="text-center">'.$nama_pasien.'</td>';
                            echo $btn_default;
                        }
                        else if($j == 2 || $j == 6 || $j == 10)
                        {
                            
                            $btn_default = '<td class="text-center">'.$nama_pasien.'</td>';
                            echo $btn_default;
                        }
                        else if($j == 3 || $j == 7 || $j == 11)
                        {
                            
                           $btn_default = '<td class="text-center">'.$nama_pasien.'</td>';
                            echo $btn_default;
                        }
                        else if($j == 4 || $j == 8 || $j == 12)
                        {
                            $btn_default = '<td class="text-center">'.$nama_pasien.'</td>';
                            echo $btn_default;
                        }
					}
					echo '</tr>';
				}
			?>
		</table>
	</div>
</body>
</html>