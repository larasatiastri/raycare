<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-green-sharp bold uppercase"><?=translate("Transaksi Perawat", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#lantai2" data-toggle="tab">
					<?=translate('Lantai 2', $this->session->userdata('language'))?> </a>
				</li>
				<li>
					<a href="#lantai3" data-toggle="tab">
					<?=translate('Lantai 3', $this->session->userdata('language'))?> </a>
				</li>
				<li>
					<a href="#lantai4" data-toggle="tab">
					<?=translate('Lantai 4', $this->session->userdata('language'))?> </a>
				</li>

			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="akses_user">
					<img src="<?=base_url()?>assets/mb/global/image/Denah RayCare.png" style="border:2px solid #cecece; width:100%; ">
					<!-- <svg id="id_svg" xmlns="http://www.w3.org/2000/svg">
						<polygon id="id_poly" points="200,200 300,300 400,400" fill="red"/>

					</svg> -->

					<svg height="210" width="500" >
					  <polygon points="400,10 250,190 160,210 400,200" style="fill:lime;" />
					</svg>
					<div style="clear:both; width:120px; height:50px; background-color:red; z-index:2; position:absolute; top:230px; left:60px;">
						<img src="<?=base_url()?>assets/metronic/admin/layout3/img/avatar9.jpg" style="margin-left: 4px; margin-top:4px; height: 39px; float: left; border-radius: 50% !important;">
						<div style="float:right;width:72px; height:50px;padding:2px;background-color:red;"><p>Budi Handuk</p></div>
						<span style="position:absolute; margin:50px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: red transparent transparent  transparent ;">	
						</span>
					</div>
					<div style="clear:both; width:80px; height:40px; background-color:green; z-index:2; position:absolute; top:210px; left:230px;">
						<p><b>Tersedia</p>
						<span style="float: left; margin: 13px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: green transparent   transparent  transparent ;"></span>
					</div>
					<div style="clear:both; width:120px; height:50px; background-color:yellow; z-index:2; position:absolute; top:260px; left:310px;">
					<img src="<?=base_url()?>assets/metronic/admin/layout3/img/avatar9.jpg" style="margin-left: 4px; margin-top:4px; height: 39px; float: left; border-radius: 50% !important;">
						<div style="float:right;width:72px; height:50px;padding:2px;background-color:yellow;"><p>Budi Handuk</p></div>
						<span style="position:absolute; margin: 50px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: yellow transparent   transparent  transparent ;"></span>
					</div>
					<div style="clear:both; width:80px; height:40px; background-color:green; z-index:2; position:absolute; top:410px; left:140px;">
						<span style="float: left; margin: 40px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: green transparent   transparent  transparent ;"></span>
					</div>
					<div style=" clear:both; width:80px; height:40px; background-color:green; z-index:2; position:absolute; top:430px; left:320px;">
						<span style="float: left; margin: 40px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: green transparent transparent  transparent ;"></span>
					</div>
					<div style=" clear:both; width:80px; height:40px; background-color:yellow; z-index:2; position:absolute; top:430px; left:740px;">
						<span style="float: left; margin: 40px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: yellow transparent transparent  transparent ;"></span>
					</div>
					<div style=" clear:both; width:80px; height:40px; background-color:green; z-index:2; position:absolute; top:410px; left:970px;">
						<span style="float: left; margin: 40px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: green transparent transparent  transparent ;"></span>
					</div>
					<div style=" clear:both; width:80px; height:40px; background-color:red; z-index:2; position:absolute; top:210px; left:1020px;">
						<span style="float: left; margin: 40px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: red transparent transparent  transparent ;"></span>
					</div>
					<div style=" clear:both; width:80px; height:40px; background-color:red; z-index:2; position:absolute; top:240px; left:890px;">
						<span style="float: left; margin: 40px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: red transparent transparent  transparent ;"></span>
					</div>
					<div style=" clear:both; width:80px; height:40px; background-color:green; z-index:2; position:absolute; top:220px; left:710px;">
						<span style="float: left; margin: 40px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: green transparent transparent  transparent ;"></span>
					</div>
					<div style=" clear:both; width:80px; height:40px; background-color:green; z-index:2; position:absolute; top:240px; left:480px;">
						<span style="float: left; margin: 40px 20px; width: 0; height: 0; border-style: solid; border-width: 10px 10px 10px 10px; border-color: green transparent transparent  transparent ;"></span>
					</div>
				</div>
				
			</div>
			
		</div>
		
	</div>
</div>
