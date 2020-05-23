<!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg style="background: url('<?=base_url()?>assets/mb/global/image/Denah RayCare.png') no-repeat; background-size: 100%;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="100%" height="100%" viewBox="0 0 1907.08 715.16" style="enable-background:new 0 0 1907.08 715.16;" xml:space="preserve">

	<?php 

		$i=0;
		foreach ($bed as $row) {

			// GET ID TINDAKAN 
			$tindakan_id = $this->bed_m->get_bed_pasien($row['id']);
			$tindakan = object_to_array($tindakan_id);
			$tindakan_row_id = '';
			foreach ($tindakan as $tindakan_row) {
				$tindakan_row_id = $tindakan_row['tindakan_id'];
			}
			// die_dump($tindakan[0]['tindakan_id']);

			$point_box = $row['point_box'];
			$split_str = explode(',', $point_box);

			$status = '';
			if ($row['status'] == 1) 
			{
				$status = '#44b6ae';
				$data_content = '<div class="kosong">
									Belum Digunakan
								</div>
								<a id="pilih" data-id="'.$row['id'].'" class="btn btn-primary" style="display:none;"><i class="fa fa-check"></i> Pilih Bed</a>';

			} elseif ($row['status'] == 2) 
			{
				$status = 'yellow';
				$data_content = '<div class="dipesan">
									<a style="margin-bottom:5px;" id="proses" data-id="'.$row['id'].'" class="btn btn-primary"><i class="fa fa-gears"></i> Proses</a>
			                   		<br><a id="pindah" data-toggle="modal" data-target="#modal_pindah" href="'.base_url().'klinik_hd/transaksi_perawat/modal_pindah/'.$row['id'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary"><i class="fa fa-arrows"></i> Pindah Bed</a>
			                   		<br><a id="tolak" data-toggle="modal" data-target="#modal_tolak" href="'.base_url().'klinik_hd/transaksi_perawat/modal_tolak/'.$row['id'].'" data-id="'.$row['id'].'" class="btn btn-primary"><i class="fa fa-times"></i> Tolak</a>
			                   	</div>
			                   	<div class="pilih_bed_tujuan" style="display:none;">
									Silahkan Pilih Bed Tujuan
			                   	</div>';

			} elseif ($row['status'] == 3) 
			{
				$status = 'red';
				$data_content = '<div class="digunakan">
									<a style="margin-bottom:5px;" href="'.base_url().'klinik_hd/transaksi_perawat/observasi_dialisis/'.$row['id'].'/0/'.$tindakan_row_id.'" id="observasi" data-id="'.$row['id'].'" class="btn btn-primary"><i class="fa fa-plus-square"></i> Observasi Dialisis</a>
			                   		<br><a id="detail" data-toggle="modal" data-target="#modal_detail" href="'.base_url().'klinik_hd/transaksi_perawat/modal_detail/'.$row['id'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>
			                   		<br><a id="selesai" href="'.base_url().'klinik_hd/transaksi_perawat/observasi_dialisis/'.$row['id'].'/1/'.$tindakan_row_id.'" data-id="'.$row['id'].'" class="btn btn-primary"><i class="fa fa-power-off"></i> Selesaikan Tindakan</a>
			                   	</div>
			                   	<div class="pilih_bed_tujuan" style="display:none;">	
									Silahkan Pilih Bed Tujuan
			                   	</div>';

			} elseif ($row['status'] == 4)
			{
				$status = 'grey';
				$data_content = 'Maintenance';
			}

			$data_content .= '<script> 
	                   			$(document).ready(function() {

   									baseAppUrl = mb.baseUrl() + "klinik_hd/transaksi_perawat/";

	                   				
	                   				$("a#proses").click(function() {
	                   					var id = $(this).data("id");

	                   					$.ajax ({ 
						                    type: "POST",
						                    url: baseAppUrl + "proses_bed",  
						                    data:  {id:id},  
						                    dataType : "json",
						                    beforeSend : function(){
								                Metronic.blockUI({boxed: true });
								            },
						                    success:function(data)         
						                    { 
            									$.ajax ({ 
								                    type: "POST",
								                    url: baseAppUrl + "show_denah_lantai_2_html",  
								                     
								                    dataType : "text",
								                    success:function(data)         
								                    { 
								                    	$("div.svg_file_lantai_2").html(data);
								                    },
								                });
						                    },
						                    complete : function() {
								                Metronic.unblockUI();
								            }
						                });

										$(".popover_menu").popover("hide");
	                   				}); 

									$("a#tolak").click(function() 
									{
										$(".popover_menu").popover("hide");
	                   				
	                   				}); 

									$("a#pindah").click(function() 
									{
										$(".popover_menu").popover("hide");

	                   				}); 

									$("a#detail").click(function() 
									{
										$(".popover_menu").popover("hide");

	                   				});
									

								});
							</script>';
	?>
		<polygon id="bed_<?=$i?>" class="popover_bed" points="<?=$row['point']?>" data-id="<?=$i+1?>" data-content='<?=$data_content?>' style="fill:white; opacity:0.1; cursor: hand;"/>
		<rect id="box_bed_<?=$i?>" class="rectangle" x="<?=$split_str[0]?>" y="<?=$split_str[1]?>" rx="10" ry="10" width="150" height="20" style="fill:<?=$status?>; opacity:1;" />
		<text x="<?=$split_str[0]+10?>" y="<?=$split_str[1]+16?>" font-family="arial" font-size="16" font-weight="bold" fill="black" > <?=$row['nama'].' ['.$row['kode'].']'?> </text>

	<?php
		$i++;
		}
	 ?>
	

	<!-- <polygon id="bed_2" points="310,200 395,200 375,350 285,350" style="fill:yellow; opacity:0.5;"/>
	<rect id="box_bed_2" x="300" y="180" width="100" height="20" style="fill:blue; opacity:0.5;" />
	<text x="300" y="195" font-family="Verdana" font-size="19" fill="black" > 02 </text>

	<polygon id="bed_3" points="420,200 505,200 485,350 395,350" style="fill:green; opacity:0.5;"/>
	<rect id="box_bed_3" x="410" y="180" width="100" height="20" style="fill:blue; opacity:0.5;" />
	<text x="410" y="195" font-family="Verdana" font-size="19" fill="black" > 03 </text>

	<polygon id="bed_4" points="680,200 755,200 750,350 660,350" style="fill:yellow; opacity:0.5;"/>
	<rect id="box_bed_4" x="665" y="180" width="100" height="20" style="fill:blue; opacity:0.5;" />
	<text x="665" y="195" font-family="Verdana" font-size="19" fill="black" > 04 </text>

	<polygon id="bed_5" points="1010,200 1095,200 1098,350 1010,350" style="fill:yellow; opacity:0.5;"/>
	<rect id="box_bed_5" x="1002" y="180" width="100" height="20" style="fill:blue; opacity:0.5;" />
	<text x="1002" y="195" font-family="Verdana" font-size="19" fill="black" > 05 </text>

	<polygon id="bed_6" points="1285,200 1365,200 1385,350 1295,350" style="fill:green; opacity:0.5;"/>
	<rect id="box_bed_6" x="1275" y="180" width="100" height="20" style="fill:blue; opacity:0.5;" />
	<text x="1275" y="195" font-family="Verdana" font-size="19" fill="black" > 06 </text>

	<polygon id="bed_7" points="1489,200 1575,200 1595,350 1510,350" style="fill:yellow; opacity:0.5;"/>
	<rect id="box_bed_7" x="1480" y="180" width="100" height="20" style="fill:blue; opacity:0.5;" />
	<text x="1480" y="195" font-family="Verdana" font-size="19" fill="black" > 07 </text>

	<polygon id="bed_8" points="1395,480 1489,480 1510,630 1410,630" style="fill:yellow; opacity:0.5;"/>
	<rect id="box_bed_8" x="1410" y="630" width="100" height="20" style="fill:blue; opacity:0.5;" />
	<text x="1410" y="645" font-family="Verdana" font-size="19" fill="black" > 08 </text>

	<polygon id="bed_9" points="1062,480 1145,480 1155,630 1064,630" style="fill:green; opacity:0.5;"/>
	<rect id="box_bed_9" x="1064" y="630" width="90" height="20" style="fill:blue; opacity:0.5;" />
	<text x="1064" y="645" font-family="Verdana" font-size="19" fill="black" > 09 </text>

	<polygon id="bed_10" points="470,480 550,480 535,630 445,630" style="fill:green; opacity:0.5;"/>
	<rect id="box_bed_10" x="445" y="630" width="90" height="20" style="fill:blue; opacity:0.5;" />
	<text x="445" y="645" font-family="Verdana" font-size="19" fill="black" > 10 </text>

	<polygon id="bed_11" points="180,480 275,480 250,630 160,630" style="fill:green; opacity:0.5;"/>
	<rect id="box_bed_11" x="160" y="630" width="90" height="20" style="fill:blue; opacity:0.5;" />
	<text x="160" y="645" font-family="Verdana" font-size="19" fill="black" > 11 </text> -->
				
</svg>
