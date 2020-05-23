<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<?=translate('Informasi Dokumen', $this->session->userdata('language'))?>
		</div>
	</div>
	<div class="portlet-body">

		<?php 

			$penjamin_dokumen  = $this->penjamin_dokumen_m->get_data_penjamin_dokumen(1)->result_array();
			
			$y = 1;
			$z = 0;
			$radio = '';
			foreach ($penjamin_dokumen as $data) 
			{

		?>

		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<?=translate($data['nama'], $this->session->userdata('language'))?>
				</div>
			</div>
			<div class="portlet-body">
			<?php
				$dokumen_detail = $this->dokumen_detail_m->get_by(array('dokumen_id' => $data['dokumen_id']));
				if($data['is_kadaluarsa'] == 1)
				{
					$expire = '<div class="form-group">
							<label class="control-label col-md-4">Tanggal Kadaluarsa :';

					if($data['is_required'] == 1)
					{
						$expire .= '<span class="required" aria-required="true">*</span>';	
					}
					$expire .= '</label>
								<div class="col-md-8">
								<div class="input-group date" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]">
									<input type="text" class="form-control" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" name="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]"'; 
					if($data['is_required'] == 1)
					{
						$expire .= ' required="required" ';	
					}
					$expire .='readonly="" aria-required="true">
									<span class="input-group-btn">
										<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>';

				}
				else
				{
					$expire = '<input type="hidden" class="form-control" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" name="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]">';
				}
				$expire .= '<input type="hidden" id="penjamin_dokumen['.$y.'][dokumen_id]" name="penjamin_dokumen['.$y.'][dokumen_id]" value="'.$data['dokumen_id'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_kadaluarsa]" name="penjamin_dokumen['.$y.'][is_kadaluarsa]" value="'.$data['is_kadaluarsa'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_required]" name="penjamin_dokumen['.$y.'][is_required]" value="'.$data['is_required'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][tipe]" name="penjamin_dokumen['.$y.'][tipe]" value="'.$data['tipe'].'">';

				echo $expire;
				if(count($dokumen_detail))
				{
					$detail = '';
					$dokumen_detail = object_to_array($dokumen_detail);
					$i = 0;
					$ii = 0;
					foreach ($dokumen_detail as $data_detail) 
					{
						

						$detail .= '<input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][id]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][id]" value="'.$data_detail['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][dokumen_id]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][dokumen_id]" value="'.$data['dokumen_id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][nama_dok]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][judul]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe_dokumen]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe_dokumen]" value="'.$data['tipe'].'">';

						if ($data_detail['tipe'] == 1)
			            {
			            	$required = '';
			                $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
			                if($data['is_required'] == 1)
							{
								$input .= '<span class="required" aria-required="true">*</span>';
								$required = 'required="required"';	
							}
			                $input .= '</label>
			                            <div class="col-md-8">
			                            	<input type="text" class="form-control" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" '.$required.' placeholder="'.$data_detail['judul'].'" maxlength="'.$data_detail['maksimal_karakter'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">			
			                            </div>';
			            }
			            
			            elseif ($data_detail['tipe'] == 2)
			            {
			            	$required = '';
			                $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';

			                if($data['is_required'] == 1)
							{
								$input .= '<span class="required" aria-required="true">*</span>';
								$required = 'required="required"';	
							}

			                $input .= '</label>
			                            <div class="col-md-8">
			                            	<textarea class="form-control" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' maxlength="'.$data_detail['maksimal_karakter'].'"></textarea>

			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">			
			                            </div>';
			            }

			            elseif ($data_detail['tipe'] == 3) 
			            {
			                $input = ' <label class="control-label col-md-4">'.$data_detail['judul'].' :';
			                $required = '';
			                if($data['is_required'] == 1)
							{
								$input .= '<span class="required" aria-required="true">*</span>';
								$required = 'required="required"';	
							}

			                $input .='</label>
			                            <div class="col-md-8">
			                            	<input type="number" class="form-control" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' min="0" max="'.$data_detail['maksimal_karakter'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">			
			                            </div>';
			            }

			            elseif ($data_detail['tipe'] == 4) 
			            {
			                $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
			                $detail_tipe_option = array(
			                    '' => translate('Pilih..', $this->session->userdata('language'))
			                );

			                foreach ($detail_tipe as $detail_tipe)
			                {	
			                    $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
			                }

			                $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
			                $required = '';
			                if($data['is_required'] == 1)
							{
								$input .= '<span class="required" aria-required="true">*</span>';
								$required = 'required="required"';	
							}
			                $input .= '</label>
			                            <div class="col-md-8">'.
			                            	form_dropdown('penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]', $detail_tipe_option, '', 'class="form-control" '.$required.' id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]"')
			                            	.'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">			
			                            </div>';
			            }
			            
			            elseif ($data_detail['tipe'] == 5)
			            {
			                $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
			                $detail_tipe_option = array(
			                    '' => translate('Pilih..', $this->session->userdata('language'))
			                );


			                $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
			                $required = '';
			                if($data['is_required'] == 1)
							{
								$input .= '<span class="required" aria-required="true">*</span>';
								$required = 'required="required"';	
							}

			                $input .= '</label>
			                            <div class="col-md-8"><div class="radio-list">';
			                foreach ($detail_tipe as $detail_tipe)
			                {	
			                	$input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" '.$required.' id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" value="'.$detail_tipe->value.'">'.$detail_tipe->text.'</label>';
			                }
			                 $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">			
			                            </div>';
			            }
			            
			            elseif ($data_detail['tipe'] == 6)
			            {
			                $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
			                $detail_tipe_option = array(
			                    '' => translate('Pilih..', $this->session->userdata('language'))
			                );


			                $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
			                $required = '';
			                if($data['is_required'] == 1)
							{
								$input .= '<span class="required" aria-required="true">*</span>';
								$required = 'required="required"';	
							}
			                $input .= '</label>
			                            <div class="col-md-8"><div class="checkbox-list">';
			                foreach ($detail_tipe as $detail_tipe)
			                {	
			                	$input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" '.$required.' id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" value="'.$detail_tipe->value.'">'.$detail_tipe->text.'</label>';
			                }
			                 $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">			
			                            </div>';
			            }

			            elseif ($data_detail['tipe'] == 7) 
			            {
			                $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
			                $detail_tipe_option = array(
			                    '' => translate('Pilih..', $this->session->userdata('language'))
			                );

			                foreach ($detail_tipe as $detail_tipe)
			                {	
			                    $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
			                }

			                $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
			                $required = '';
			                if($data['is_required'] == 1)
							{
								$input .= '<span class="required" aria-required="true">*</span>';
								$required = 'required="required"';	
							}
			                $input .= '</label>
			                            <div class="col-md-8">'.
			                            	form_dropdown('penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" multiple="multiple" '.$required.' ')
			                            	.'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">			
			                            </div>';
			            }

			            elseif ($data_detail['tipe'] == 8) 
			            {
			            	$input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
			            	$required = '';
			                if($data['is_required'] == 1)
							{
								$input .= '<span class="required" aria-required="true">*</span>';
								$required = 'required="required"';	
							}
			            	$input .= '</label>
			                            <div class="col-md-8">
			                            <div class="input-group date" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]">
											<input type="text" class="form-control" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" readonly="" aria-required="true" '.$required.'>
											<span class="input-group-btn">
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
			                            	
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">			
			                            </div>';
			            }
			            elseif ($data_detail['tipe'] == 9) 
			            {
			                $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
			                $required = '';
			                if($data['is_required'] == 1)
							{
								$input .= '<span class="required" aria-required="true">*</span>';
								$required = 'required="required"';	
							}
			                $input .= '</label>
			                          <div class="col-md-8">
			                          	<div id="upload_dokumen_'.$z.'">
			                                <input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][value]" value="" '.$required.' />
			                                <span class="btn default btn-file">
											<span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>	
		        								<input type="file" name="upl" id="upload_'.$z.'" data-url="'.base_url().'upload/upload_photo" class="uploadbutton" multiple />
		        							</span>
			                                <ul class="ul-img">
												
											</ul>
			                            </div>
				                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">
			                          </div>';
			            	$z++;
			            }
						
						echo '<div class="form-group">'.$detail.'</div><div class="form-group">'.$input.'</div>';
						$i++;
						$ii++;
					}
				}
			?>

			</div>
		</div>

		<?php

			$y++;
		
			}

			echo '<div class="form-group hidden">
                    <label class="control-label col-md-4 ">Total Dokumen</label>
                    <div class="col-md-8 ">
                    	<input type="text" id="data_upload" name="data_upload" value="'.$z.'" />
                        <input type="text" name="total_dokumen" value="'.$i.'">
                    </div>
                </div>';

	 	?>

	</div>
</div>