<!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg style="background: url('<?=base_url()?>assets/mb/global/image/Denah RayCare.png') no-repeat; background-size: 100%;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="100%" height="100%" viewBox="0 0 1907.08 715.16" style="enable-background:new 0 0 1907.08 715.16;" xml:space="preserve">

	<?php 
		// include('background.php');
 	?>

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

			$point_box = $row['point_box'];
			$split_str = explode(',', $point_box);

			$status = '';
			if ($row['status'] == 1) 
			{
				$status = '#44b6ae';
				$data_content = '<a style="margin-bottom:5px;" id="pilih_bed" data-id="'.$row['id'].'" class="btn btn-primary"><i class="fa fa-check"></i> Pilih Bed</a>';

			} elseif ($row['status'] == 2) 
			{
				$status = 'yellow';
				$data_content = '<a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/transaksi_dokter/lihat_detail_denah/'.$row['id'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';

			} elseif ($row['status'] == 3) 
			{
				$status = 'red';
				$data_content = '<a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/transaksi_dokter/lihat_detail_denah/'.$row['id'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';

			} elseif ($row['status'] == 4)
			{
				$status = 'grey';
				$data_content = 'Maintenance';
			}

			$data_content .= '<script> 
	                   			$(document).ready(function() {
	                   				
	                   				$("a#pilih_bed").click(function() {
	                   					var id = $(this).data("id");
	                   					$("input#bed_id").val(id);

	                   					$.ajax ({ 
						                    type: "POST",
						                    url: "'.base_url().'" + "klinik_hd/transaksi_dokter/pilih_bed",  
						                    data:  {id:id},  
						                    dataType : "text",
						                    success:function(data)         
						                    { 
						                    	// alert(data);
							                    mb.showMessage(data[0],data[1],data[2]);
            									// window.location.reload();
						                    	// alert(data);
						                    	// $(".svg_file").html(data);
						                    }

						                });

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
	
</svg>
