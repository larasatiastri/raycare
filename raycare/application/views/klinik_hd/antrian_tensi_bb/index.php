<input type="hidden" id="antrian_id" name="antrian_id" value="<?=$data_antrian['id']?>">
<input type="hidden" id="nama_pasien" name="nama_pasien" value="<?=$data_antrian['nama_pasien']?>">
<div class="row">
<style type="text/css">
	.dashboard-stat{
		    -webkit-border-radius: 26px;
		    -moz-border-radius: 26px;
		    -ms-border-radius: 26px;
		    -o-border-radius: 26px;
		    border-radius: 26px;
	}
	.dashboard-stat:hover{
		-webkit-border-radius: 46px;
		    -moz-border-radius: 46px;
		    -ms-border-radius: 46px;
		    -o-border-radius: 46px;
		    border-radius: 46px;
	}
	.budi > table{
		border-collapse: collapse;
	  	border-radius: 26px;
	  	overflow: hidden;
	  	color:#fff;
	}

	.budi th,
	.budi td {
	  padding: 0.5em;
	  background: #0052AD;
	  /*border-bottom: 2px solid #45a6e9;*/
	}
</style>
<div class="col-md-12">
	<div class="col-md-12 budi" style="margin-bottom:20px;">
	    <table class="budi" width="100%">
		    <tbody>
		    	<tr>
					<?php
						$html = '';

						if(count($list_antrian) == 0){
							$html .= '<td>-</td>
						          <td><i class="fa fa-angle-left" style="color:#2fff00;"></i> -</td>
						          <td><i class="fa fa-angle-left" style="color:#2fff00;"></i> -</td>
						          <td><i class="fa fa-angle-left" style="color:#2fff00;"></i> -</td>
						          <td><i class="fa fa-users" style="color:#2fff00;"></i> 0</td>';
						}elseif(count($list_antrian) != 0){

							$wl_tindakan = ((count($list_antrian) - 4) >= 0)?(count($list_antrian) - 4):0;

							for($x=0;$x<=3;$x++){
								$class = (isset($list_antrian[$x]['is_panggil']) && $list_antrian[$x]['is_panggil'] == 1)?'class="quadrat"':'';
								$nama_pasien = (isset($list_antrian[$x]['nama_pasien']))?strtoupper($list_antrian[$x]['nama_pasien']):'-';

								$html .= '<td '.$class.'><i class="fa fa-angle-double-left" style="color:#2fff00;"></i> '.$nama_pasien.'</td>';
							}
							$html .= '<td><i class="fa fa-users" style="color:#2fff00;"></i> '.$wl_tindakan.'</td>';
						}

						echo $html;
					?>
				</tr>
		    </tbody>
		</table>
	</div>
	<div class="col-md-12">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		        <a class="dashboard-stat dashboard-stat-v2 green" id="tombol_panggil">
		            <div class="visual">
		                <i class="fa fa-bullhorn"></i>
		            </div>
		            <div class="details">
		                <div class="number">
		                    <span data-counter="counterup" data-value="1349">Panggil</span>
		                </div>
		                <div class="desc" id="counter_panggil"><?=$data_antrian['nama_pasien']?></div>
		            </div>
		        </a>
		    </div>
		    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="div_tindak">
		        <a class="dashboard-stat dashboard-stat-v2 blue" id="tombol_tindak">
		            <div class="visual">
		                <i class="fa fa-user-md"></i>
		            </div>
		            <div class="details">
		                <div class="number">
		                    <span data-counter="counterup" data-value="1349">Tindak</span>
		                </div>
		                <div class="desc"> </div>
		            </div>
		        </a>
		    </div>
		    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden" id="div_lewat">
		        <a class="dashboard-stat dashboard-stat-v2 red"  id="tombol_lewat">
		            <div class="visual">
		                <i class="fa fa-refresh"></i>
		            </div>
		            <div class="details">
		                <div class="number">
		                    <span data-counter="counterup" data-value="1349">Lewati</span>
		                </div>
		                <div class="desc"> </div>
		            </div>
		        </a>
		    </div>
		</div>
		    
	</div>
</div>



    
    
</div>
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Antrian Pasien", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
				<div class="btn-group">
					<a class="btn green-haze" href="javascript:;" data-toggle="dropdown">
					<!--<i class="fa fa-check-circle"></i>-->
					 Columns <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu pull-right" id="sample_4_column_toggler">
						<li>
							<label><input type="checkbox" data-column="1">No. RM</label>
						</li>
						<li>
							<label><input type="checkbox" data-column="3">Alamat</label>
						</li>
					</ul>
				</div>
				<a href="<?=base_url()?>klinik_hd/antrian_tensi_bb/history" class="btn default"> <i class="fa fa-history"></i> <span class="hidden-480"><?=translate("Histori", $this->session->userdata("language"))?></span> </a>
			
	        </div>
		</div>
		<div class="portlet-body">
			<table class="table table-condensed table-striped table-hover" id="table_antrian_tensi">
				<thead>
					<tr role="row">
						<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("No. RM", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="30%"><?=translate("Alamat", $this->session->userdata("language"))?> </th>
						<th class="text-center"width="10%"><?=translate("BB", $this->session->userdata("language"))?> </th>
						<th class="text-center"width="20%"><?=translate("TD", $this->session->userdata("language"))?> </th>
				 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div><!-- akhir dari portlet -->