<?php
	$form_attr = array(
	    "id"            => "form_laporan_omzet_invoice", 
	    "name"          => "form_laporan_omzet_invoice", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-file font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Laporan Omzet Tindakan HD", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Form Filter", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-12"><?=translate("Tanggal Tindakan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?=date('d M Y')?>"readonly >
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12"><?=translate("Penjamin", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
						<div class="col-md-12">
							<?php
								$penjamin_option = array(
									'0'			=> translate('Semua', $this->session->userdata('language')).'..',
									'2'			=> translate('BPJS', $this->session->userdata('language')),
									'1'			=> translate('Swasta', $this->session->userdata('language')),
								);
								echo form_dropdown('penjamin', $penjamin_option, '','id="penjamin" class="form-control" ');
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12"><?=translate("Shift", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
						<div class="col-md-12">
							<?php
								$shift_option = array(
									'0'			=> translate('Semua', $this->session->userdata('language')).'..',
									'1'			=> translate('Shift 1', $this->session->userdata('language')),
									'2'			=> translate('Shift 2', $this->session->userdata('language')),
									'3'			=> translate('Shift 3', $this->session->userdata('language')),
									'4'			=> translate('Shift 4', $this->session->userdata('language')),
								);
								echo form_dropdown('shift', $shift_option, '','id="shift" class="form-control" ');
							?>
						</div>
					</div>
					</div>
					<br>
					<div class="form-group">
						<div class="col-md-12">
							<a id="cari" class="btn btn-primary col-md-12" href="#"><i class="fa fa-search"></i> <?=translate("Cari", $this->session->userdata("language"))?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="portlet light bordered">
					<div class="portlet-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-10">
							<div class="dashboard-stat green-haze">
								<div class="visual">
									<i class="fa fa-briefcase fa-icon-medium"></i>
								</div>
								<div class="details">
									<div class="number" id="omzet">
										 Rp. 0,-
									</div>
									<div class="desc">
										 Omzet
									</div>
								</div>
								<a class="more" id="link_omzet" data-target="#modal_omzet" data-toggle="modal" href="#">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-10">
							<div class="dashboard-stat yellow-casablanca">
								<div class="visual">
									<i class="fa fa-credit-card fa-icon-medium"></i>
								</div>
								<div class="details">
									<div class="number" id="edc">
										 Rp. 0,-
									</div>
									<div class="desc">
										 Mesin EDC
									</div>
								</div>
								<a class="more" id="link_edc" data-toggle="modal" data-target="#modal_edc" href="#">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="dashboard-stat red-intense">
								<div class="visual">
									<i class="fa fa-shopping-cart"></i>
								</div>
								<div class="details">
									<div class="number" id="hutang">
										 Rp. 0,-
									</div>
									<div class="desc">
										 Hutang
									</div>
								</div>
								<a class="more" id="link_hutang" data-toggle="modal" data-target="#modal_hutang" href="#">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="dashboard-stat blue-madison">
								<div class="visual">
									<i class="fa fa-money fa-icon-medium"></i>
								</div>
								<div class="details">
									<div class="number" id="tunai">
										 Rp. 0,-
									</div>
									<div class="desc">
										 Bayar Tunai
									</div>
								</div>
								<a class="more" id="link_tunai" data-toggle="modal" data-target="#modal_tunai" href="#">
								View more <i class="m-icon-swapright m-icon-white"></i>
								</a>
							</div>
						</div>
					</div>
					</div>
				</div>

				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<?=translate("Pembayaran Invoice Tanggal Lain", $this->session->userdata("language"))?>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="table_laporan_omzet_invoice">
							<thead>
								<tr>
									<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("No. Invoice", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Jenis", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Jenis Bayar", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
								</tr>
							</thead>

							<tbody>
								
							</tbody>
								<tfoot>
					                <tr>
					                	<td class="text-right" colspan="6"><b><?=translate('Total', $this->session->userdata('language'))?> :</b></td>
						                
						                <td class="text-right" >
						            	<b id="total_invoice"></b>
						                </td>
					              	</tr>
		                        </tfoot>
						</table>

					</div>
				</div>
			</div>
		</div>	
	</div>
</div>
<div  class="modal fade bs-modal-lg"  id="modal_omzet" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>        
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
               

        </div>
    </div>
</div>
<div  class="modal fade bs-modal-lg"  id="modal_edc" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>        
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
               

        </div>
    </div>
</div>
<div  class="modal fade bs-modal-lg"  id="modal_tunai" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>        
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
               

        </div>
    </div>
</div>
<div  class="modal fade bs-modal-lg"  id="modal_hutang" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>        
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
               

        </div>
    </div>
</div>
<?=form_close()?>

