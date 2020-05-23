<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_racik_obat", 
			    "name"          => "form_add_racik_obat", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."apotik/racik_obat/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');

		?>

		<div class="form-body">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-cursor font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Resep Obat", $this->session->userdata("language"))?></span>
						<span class="caption-helper"><?php echo '<label class="control-label ">'.date('d M Y').'</label>'; ?></span>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="alert alert-danger display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_danger?>
					    </div>
					    <div class="alert alert-success display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_success?>
					    </div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Racikan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$racikan = array(
										"id"			=> "racikan",
										"name"			=> "racikan",
										"autofocus"			=> true,
										"class"			=> "form-control required", 
										"placeholder"	=> translate("Racikan", $this->session->userdata("language")), 
										"value"			=> $flash_form_data['racikan'],
										"help"			=> $flash_form_data['racikan'],
									);
									echo form_input($racikan);
								?>

								<?php
									$resep_racik_obat_id = array(
										"id"          => "resep_racik_obat_id",
										"name"        => "resep_racik_obat_id",
										"type"        => "hidden",
										"autofocus"   => true,
										"class"       => "form-control required", 
										"placeholder" => translate("Resep Racik Id", $this->session->userdata("language")), 
										"value"       => $flash_form_data['resep_racik_obat_id'],
										"help"        => $flash_form_data['resep_racik_obat_id'],
									);
									echo form_input($resep_racik_obat_id);
								?>
							</div>
							<div class="col-md-1" style="margin-left:-30px !important;">
								<a class="btn btn-primary search-resep" id="search-resep"><i class="fa fa-search"></i></a>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<label id="pembuat" class="control-label"></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<label id="keterangan" class="control-label" style="text-align : left !important"></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Jumlah Produksi", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$jumlah_produksi = array(
										"id"          => "jumlah_produksi",
										"name"        => "jumlah_produksi",
										"type"        => "number",
										"min"         => 1,
										"autofocus"   => true,
										"class"       => "form-control text-right required", 
										"placeholder" => translate("Jumlah Produksi", $this->session->userdata("language")), 
										"value"       => "1",
										"help"        => $flash_form_data['jumlah_produksi'],
									);
									echo form_input($jumlah_produksi);
								?>	
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> :</label>
                        
	                        <div class="col-md-2">
	                            <div class="input-group date" id="tanggal">
	                                <input type="text" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" >
	                                <span class="input-group-btn">
	                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
	                                </span>
	                            </div>
	                        </div>
						</div>
						
						
					</div>
				</div>	
			</div>
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Daftar Item Yang Digunakan', $this->session->userdata('language'))?></span>
					</div>
				</div>
				<div class="portlet-body">
				<div id="form_tambahan">
					
				</div>

					<table class="table table-striped table-bordered table-hover" id="table_item_digunakan">
						<thead>

							<tr class="heading">
								<th class="text-center" style="width : 250px !important;"><?=translate("Kode", $this->session->userdata("language"))?></th>
								<th class="text-center" style="width : 550px !important;"><?=translate("Nama", $this->session->userdata("language"))?></th>
			                    <th class="text-center" style="width : 100px !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
			                    <th class="text-center" style="width : 250px !important;"><?=translate("Satuan", $this->session->userdata("language"))?></th>
								<th class="text-center" style="width : 350px !important;"><?=translate("Harga", $this->session->userdata("language"))?></th>
							</tr>
						</thead>

						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="4"><b><?=translate("Sub Total", $this->session->userdata("language"))?> : </b></td>
								<td class="text-right">
									<label id="sub_total"></label>
								</td>
							</tr>
							<tr>
								<td class="text-right" colspan="4"><b><?=translate("Biaya Tambahan", $this->session->userdata("language"))?> : </b></td>
								<td><input type="number" min="0" class="form-control text-right" value="0" id="biaya_tambahan" name="biaya_tambahan"></td>
							</tr>
							<tr>
								<td class="text-right" colspan="4"><b><?=translate("Harga Jual", $this->session->userdata("language"))?> : </b></td>
								<td class="text-right">
									<label id="harga_jual"></label>
									<input type="hidden" id="harga_jual" name="harga_jual">
								</td>
							</tr>
						</tfoot>
					</table>
				</div>


			</div>

			<?php $msg = translate("Apakah anda yakin akan membuat racik obat ini?",$this->session->userdata("language"));?>
			<div class="form-actions fluid">	
				<div class="col-md-offset-1 col-md-9">
					<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
					<a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
			        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
				</div>		
			</div>
			
		</div>
	</div>
</div>

<div id="popover_resep_content" class="row">
    <div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Daftar Resep', $this->session->userdata('language'))?></span>
			</div>
		</div>
		<div class="portlet-body">
			<table class="table table-striped table-bordered table-hover" id="table_resep_search">
				<thead>

					<tr class="heading">
						<th class="text-center" style="width : 250px !important;"><?=translate("Nama", $this->session->userdata("language"))?></th>
						<th class="text-center" style="width : 550px !important;"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?></th>
						<th class="text-center" style="width : 350px !important;"><?=translate("Aksi", $this->session->userdata("language"))?></th>
					</tr>
				</thead>

				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div> 

<div class="modal fade form-horizontal" id="ajax_notes" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        	<!-- <form action="" class="form-horizontal"> -->
	        	<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Identitas Item", $this->session->userdata("language"))?></h4>
				</div>
				<div class="modal-body">
					<div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate('I', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<input type="text" name="i" value="1" id="i" class="form-control">	
						</div>
					</div>
					<div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate('Item Id', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<input type="text" name="item_id" value="" id="item_id" class="form-control" autofocus="1">	
						</div>
					</div>
					<div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate('Item Satuan Id', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<input type="text" name="item_satuan_id" value="" id="item_satuan_id" class="form-control" autofocus="1">	
						</div>
					</div>
					<div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate('Identitas Ditambahkan', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<input type="text" name="identitas_tambah" value="" id="identitas_tambah" class="form-control" autofocus="1">	
						</div>
					</div>
					<div class="form-group" style="margin-bottom : 20px;">
						<label class="control-label col-md-3"><?=translate('Identitas', $this->session->userdata('language'))?></label>
						<div class="col-md-4">
							<?php 
								$identitas_option = array(
									'' => 'Pilih..'
								);
								
								echo form_dropdown('identitas', $identitas_option, "", "id=\"identitas\" class=\"form-control\""); 
							?>
						</div>
						<div class="col-md-2" style="margin-left : -30px !important;">
							<a id="tambah_identitas" class="btn btn-primary hidden"><?=translate('Tambah', $this->session->userdata('language'))?></a>
							<a id="tambah_fieldset" class="btn btn-primary"><?=translate('Tambah', $this->session->userdata('language'))?></a>
						</div>
					</div>

					<?php			                
					$form_identitas = '
		                <div id="show_identitas_{0}" class="show_identitas">
		   					
		                </div>';
			        ?>

			        <div id="form_identitas"></div>
			        <input type="hidden" id="tpl-form-identitas" value="<?=htmlentities($form_identitas)?>">
			        <div class="form-body">
			            <ul class="list-unstyled">
			            </ul>
			        </div>
				</div>
			<!-- </form> -->
			<div class="modal-footer">
				<a type="button" id="modal_close" class="btn default hidden" data-dismiss="modal"><?=translate('Close', $this->session->userdata('language'))?></a>
				<a id="modal_cancel" class="btn default" data-dismiss="modal"><?=translate('Cancel', $this->session->userdata('language'))?></a>
				<a id="modal_ok" class="btn btn-primary"><?=translate('OK', $this->session->userdata('language'))?></a>
			</div>
        </div>
    </div>
</div>


<?=form_close()?>