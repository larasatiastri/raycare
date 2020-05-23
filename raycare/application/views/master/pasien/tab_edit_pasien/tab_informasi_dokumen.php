<div class="portlet light" id="section-alamat">
	<div class="portlet-title">
		<div class="caption">
			<?=translate('Informasi Dokumen', $this->session->userdata('language'))?>
		</div>
	</div>
	<div class="portlet-body">
	<?php 
	    $pasien_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $form_data['id'], 'is_active' => 1));
	    $penj_id = '';
	    foreach ($pasien_penjamin as $pas_penj) 
	    {
	    	$penj_id .= $pas_penj->penjamin_id.',';
	    }
	    $pasien_dokumen = $this->penjamin_dokumen_m->get_data_dokumen($penj_id)->result_array();

	    $y = 1;
		$z = 0;
		$radio = '';
		foreach ($pasien_dokumen as $data) 
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
				$dokumen_value = $this->pasien_dokumen_m->get_by(array('pasien_id' => $form_data['id'], 'dokumen_id' => $data['id']), true);
				$dokumen_detail = $this->dokumen_detail_m->get_by(array('dokumen_id' => $data['id']));
				$tanggal_kadaluarsa = '';
				$pas_dok_id = '';
				if(count($dokumen_value) != 0)
				{
					$pas_dok_id = $dokumen_value->id;
					$tanggal_kadaluarsa = date('d-M-Y', strtotime($dokumen_value->tanggal_kadaluarsa));
				}
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
					$expire .='readonly="" aria-required="true" value="'.$tanggal_kadaluarsa.'">
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
				$expire .= '<input type="hidden" id="penjamin_dokumen['.$y.'][pasien_dokumen_id]" name="penjamin_dokumen['.$y.'][pasien_dokumen_id]" value="'.$pas_dok_id.'"><input type="hidden" id="penjamin_dokumen['.$y.'][dokumen_id]" name="penjamin_dokumen['.$y.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_kadaluarsa]" name="penjamin_dokumen['.$y.'][is_kadaluarsa]" value="'.$data['is_kadaluarsa'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_required]" name="penjamin_dokumen['.$y.'][is_required]" value="'.$data['is_required'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][nama_dok]" name="penjamin_dokumen['.$y.'][nama_dok]" value="'.$data['nama'].'">';

				echo $expire;
				if(count($dokumen_detail))
				{
					$dokumen = $this->dokumen_m->get($data['id']);
					$detail = '';
					$dokumen_detail = object_to_array($dokumen_detail);
					$i = 0;
					$ii = 0;
					foreach ($dokumen_detail as $data_detail) 
					{
						$detail_value = $this->pasien_dokumen_m->get_by_data(array('dokumen_id' => $data['id'],'pasien_id' => $form_data['id'], 'dokumen_detail_id' => $data_detail['id']));

						$tipe_value = $data_detail['value'];
						$tipe_id = '';
						/*if(count($detail_value))
						{
							$tipe_value = $detail_value->value;
							$tipe_id = $detail_value->id;
						}*/
						$detail .= '<input type="hidden" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][id]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][id]" value="'.$data_detail['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][dokumen_id]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][nama_dok]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][judul]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][tipe]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][tipe]" value="'.$data_detail['tipe'].'">';

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
			                            	<input type="text" class="form-control" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" '.$required.' placeholder="'.$data_detail['judul'].'" maxlength="100" value="'.$tipe_value.'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" value="'.$tipe_id.'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" value="'.$data['tipe'].'">			
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
			                            	<textarea class="form-control" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' maxlength="100" value="'.$tipe_value.'">'.$tipe_value.'</textarea>

			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" value="'.$tipe_id.'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" value="'.$data['tipe'].'">			
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
			                            	<input type="number" class="form-control" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' min="0" max="100" value="'.$tipe_value.'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" value="'.$tipe_id.'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" value="'.$data['tipe'].'">		
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
			                            	form_dropdown('penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]', $detail_tipe_option, $tipe_value, 'class="form-control" '.$required.' id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]"')
			                            	.'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" value="'.$tipe_id.'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" value="'.$data['tipe'].'">			
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
			                $checked = '';
			                foreach ($detail_tipe as $detail_tipe)
			                {	
			                	if($detail_tipe->value == $tipe_value)
			                	{
			                		$checked = 'checked="checked"';
			                	}
			                	$input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" '.$required.' id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
			                }
			                 $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" value="'.$tipe_id.'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" value="'.$data['tipe'].'">			
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
			                $checked = '';
			                foreach ($detail_tipe as $detail_tipe)
			                {	
			                	if($detail_tipe->value == $tipe_value)
			                	{
			                		$checked = 'checked="checked"';
			                	}	
			                	$input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" '.$required.' id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
			                }
			                 $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" value="'.$tipe_id.'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" value="'.$data['tipe'].'">				
			                            </div>';
			            }

			            elseif ($data_detail['tipe'] == 7) 
			            {
			                $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
			                $detail_tipe_option = array(
			                    '' => translate('Pilih..', $this->session->userdata('language'))
			                );

			                $selected = '';
			                foreach ($detail_tipe as $detail_tipe)
			                {	
			                	if($detail_tipe->value == $tipe_value)
			                	{
			                		$selected = 'selected="selected"';
			                	}	
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
			                            	form_dropdown('penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" multiple="multiple" '.$required.' '.$selected.' ')
			                            	.'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" value="'.$tipe_id.'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" value="'.$data['tipe'].'">				
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
							if($tipe_value != '')
							{
								$tipe_value = date('d-M-Y', strtotime($tipe_value));
							}
			            	$input .= '</label>
			                            <div class="col-md-8">
			                            <div class="input-group date" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]">
											<input type="text" class="form-control" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" readonly="" aria-required="true" '.$required.' value="'.$tipe_value.'">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
			                            	
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" value="'.$tipe_id.'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" value="'.$data['tipe'].'">				
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
							$image = '';
                            if($tipe_value != '')
                            {
                                if($tipe_value != 'doc_global/document.png')
                                {
                                    $value_url = $tipe_value;
                                	
                                    $tipe = ($dokumen->tipe == 1)?'pelengkap':'rekam_medis';
                                    $tipe_value = $form_data['no_member'].'/dokumen/'.$tipe.'/'.$tipe_value;
                                }


                                $image = '<li class="working">
                                            <div class="thumbnail">
                                                <a class="fancybox-button" title="'.$tipe_value.'" href="'.base_url().config_item('site_img_pasien').$tipe_value.'" data-rel="fancybox-button">
                                                    <img src="'.base_url().config_item('site_img_pasien').$tipe_value.'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                                </a>
                                            </div>
                                        </li>';
                            }
			                $input .= '</label>
			                          <div class="col-md-8">
			                          	<div id="upload_dokumen_'.$z.'">
			                                <input type="hidden" id="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data['id'].'['.$ii.'][value]" value="'.$value_url.'" '.$required.' />
			                                <span class="btn default btn-file">
											<span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>	
		        								<input type="file" name="upl" id="upload_dok_'.$z.'" data-url="'.base_url().'upload/upload_photo" class="uploadbutton" multiple />
		        							</span>
			                                <ul class="ul-img">
												'.$image.'
											</ul>
			                            </div>
				                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
			                            	<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][id]" value="'.$tipe_id.'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][dokumen_id]" value="'.$data['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe]" value="'.$data_detail['tipe'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" name="penjamin_dokumen_detail_tipe'.$data['id'].'['.$i.'][tipe_dokumen]" value="'.$data['tipe'].'">	
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
            <div class="col-md-3 ">
            	<input type="text" id="data_upload" name="data_upload" value="" />
                <input type="text" name="total_dokumen" value="">
            </div>
        </div>';

 	?>

		
	</div>
</div>