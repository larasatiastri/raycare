<div class="row">
	<div class="col-md-12">
		<!-- BEGIN PORTLET-->
	<div class="portlet light">
		<div class="portlet-title tabbable-line">
			<div class="caption caption-md">
				<i class="fa fa-list theme-font"></i>
				<span class="caption-subject theme-font bold uppercase">Antrian Tindakan</span>
			</div>
			<div class="actions">
				<a class="btn default" href="javascript:history.go(-1);"><i class="fa fa-chevron-left"></i> <?=translate('Kembali', $this->session->userdata('language'))?></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" style="height: 537px;" data-always-visible="1" data-rail-visible="0" data-handle-color="#D7DCE2">
				<ul class="feeds">
				<?php
					if(count($data_antrian) != 0){
						foreach ($data_antrian as $key => $row) {
							$status = '';

				            if($row['shift'] == 1)
				            {
				                $status = '<div class="label label-sm label-default"><i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i></div>';
				            }
				            if($row['shift'] == 2)
				            {
				                $status = '<div class="label label-sm label-success"><i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i></div>';

				            }
				            if($row['shift'] == 3)
				            {
				                $status = '<div class="label label-sm label-info"><i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i></div>';
				            }

				            $to_time =  strtotime(date('Y-m-d H:i:s'));
							$from_time = strtotime($row['created_date']);
							$time = round(abs($to_time - $from_time) / 60,2);
							// die(dump($time));

							if($time <= 2.00){
								$time = 'Just Now';
							}if($time < 60.00 && $time > 2.00){
								$time = round(abs($to_time - $from_time) / 60).' minutes';
							}if($time == 60.00){
								$time = intval((round(abs($to_time - $from_time) / 60) / 60)). " h 0 minutes";
							}elseif($time > 60.00){
								$time = intval((round(abs($to_time - $from_time) / 60) / 60)). " h ".intval((round(abs($to_time - $from_time) / 60) % 60)). " m";
							}

						echo '<li>
							<div class="col1">
								<div class="cont">
									<div class="cont-col1">
										'.$status.'
									</div>
									<div class="cont-col2">
										<div class="desc">
											'.$row['nama_pasien'].' ke '.$row['nama_dokter'].', Bed '.$row['kode'].'
										</div>
									</div>
								</div>
							</div>
							<div class="col2">
								<div class="date">
									'.$time.'
								</div>
							</div>
						</li>';
							
						}
					}
				?>
					
					
				</ul>
			</div>			
		</div>
	</div>

	</div>
</div>
	