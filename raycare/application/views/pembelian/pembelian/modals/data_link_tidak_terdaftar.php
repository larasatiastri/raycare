<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pilih Permintaan Detail", $this->session->userdata("language"))?></span>
	</div>
</div>
<div class="modal-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?=translate("User (user level)", $this->session->userdata("language"))?> :</label>
		<div class="col-md-5">
			<?php
				echo '<label name="user" id="user">'.$link_permintaan[$row-1]['user'].'('.$link_permintaan[$row-1]['user_level'].')</label>';

				$user = array(
					"id"			=> "data_user",
					"name"			=> "data_user",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $link_permintaan[$row-1]['user']
				);

				echo form_input($user);

				$id_detail = array(
					"id"			=> "id_detail",
					"name"			=> "id_detail",
					"autofocus"			=> true,
					"class"			=> "form-control",
					"value"			=> $id_detail
				);

				echo form_input($id_detail);

				$user_level = array(
					"id"			=> "data_user_level",
					"name"			=> "data_user_level",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $link_permintaan[$row-1]['user_level']
				);

				echo form_input($user_level);
			?>
		</div>
	</div>
    <div class="form-group">
		<label class="control-label col-md-3"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
		<div class="col-md-5">
			<?php
				echo '<label name="tanggal_popup" id="tanggal_popup">'.date('d M Y', strtotime($link_permintaan[$row-1]['tanggal'])).'</label>';	
				
				$tanggal = array(
					"id"			=> "data_tanggal",
					"name"			=> "data_tanggal",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> date('d M Y', strtotime($link_permintaan[$row-1]['tanggal']))
				);

				echo form_input($tanggal);
			?>
		</div>
	</div>
    <div class="form-group">
		<label class="control-label col-md-3"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
		<div class="col-md-5">
			<?php
				echo '<label name="subjek_popup" id="subjek_popup">'.$link_permintaan[$row-1]['subjek'].'</label>';
				
				$subjek = array(
					"id"			=> "data_subjek",
					"name"			=> "data_subjek",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $link_permintaan[$row-1]['subjek']
				);

				echo form_input($subjek);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
		<div class="col-md-7">
			<?php
				echo '<label name="subjek_popup" id="subjek_popup">'.$link_permintaan[$row-1]['keterangan'].'</label>';
				
				$keterangan = array(
					"id"			=> "data_keterangan",
					"name"			=> "data_keterangan",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $link_permintaan[$row-1]['keterangan']
				);

				echo form_input($keterangan);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
		<div class="col-md-5">
			<?php
				echo '<label name="nama_item" id="nama_item">'.$link_permintaan[$row-1]['nama'].'</label>';
			
				$nama_item = array(
					"id"			=> "data_nama_item",
					"name"			=> "data_nama_item",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $link_permintaan[$row-1]['nama']
				);

				echo form_input($nama_item);

			?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3"><?=translate("Jumlah Pesan", $this->session->userdata("language"))?> :</label>
		<div class="col-md-5">
			<?php
				echo '<label name="jml_pesan" id="jml_pesan">'.$link_permintaan[$row-1]['jumlah'].' '.$link_permintaan[$row-1]['satuan'].'</label>';	
			
				$jumlah_item = array(
					"id"			=> "data_jumlah_item",
					"name"			=> "data_jumlah_item",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $link_permintaan[$row-1]['jumlah']
				);

				echo form_input($nama_item);

				$nama_satuan = array(
					"id"			=> "data_nama_satuan",
					"name"			=> "data_nama_satuan",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $link_permintaan[$row-1]['satuan']
				);

				echo form_input($nama_satuan);

				$data_row = array(
					"id"			=> "data_row",
					"name"			=> "data_row",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $row
				);

				echo form_input($data_row);

				$data_rowId = array(
					"id"			=> "data_rowId",
					"name"			=> "data_rowId",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $rowId
				);

				echo form_input($data_rowId);
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3"><?=translate("Jumlah Alokasi", $this->session->userdata("language"))?> :</label>
		<div class="col-md-3">
			<?php
				$jumlah_alokasi = array(
					"id"			=> "jumlah_alokasi",
					"name"			=> "jumlah_alokasi",
					"autofocus"			=> true,
					"class"			=> "form-control",
					"placeholder"	=> translate("Jumlah Alokasi", $this->session->userdata("language"))
				);

				echo form_input($jumlah_alokasi);

				$satuan_id = array(
					"id"			=> "satuan_id",
					"name"			=> "satuan_id",
					"autofocus"			=> true,
					"class"			=> "form-control hidden",
					"value"			=> $satuan_id
				);

				echo form_input($satuan_id);
			?>
		</div>
		<label class="control-label col-md-0 text-left" name="satuan_alokasi" id="satuan_alokasi" ><?=$item_satuan['nama']?></label>
	</div>	
</div>
<div class="modal-footer">
    <button type="button" id="closeModalSave" class="btn default hidden" data-dismiss="modal">Close</button>
    <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" data-dismiss="modal" id="modal_ok"><?=translate("Simpan", $this->session->userdata("language"))?></a>
</div>
<script>
	$( document ).ready(function() {
	    // handleDataTablePermintaanTerdaftar();
	    // handleDataTablbePermintaanTidakTerdaftar();
	    // hasil_konvert_options();
	    // handlemodalOk();
	    // addKonversiRow();
	    handleSaveModalLink();
	});	
	
	function handleSaveModalLink()
    {
        // var $form               = $('#form_add_pembelian'),
        var	$tableCariPermintaan    = $('#table_search_permintaan'),
        	$rowId = $('input#data_rowId').val();
        	
        	// alert($rowId);
        // $jml = $('input#jumlah_alokasi').val();
         $('a#modal_ok').click(function() {
        	// alert('a');
        	// $tableCariPermintaan.append($("<tr></tr>")
        	// 	.attr("value", "a" ));
			// alert($row);
                    // $kelas_select.val('Pilih Kelas');
                    // $konvert_akhir.empty();

                        var tr = $('<tr />');
                        var td = $('<td />');
                        var tdh = $('<td />').attr({'class' : 'hidden'});
                        var inputTanggal = $('<input />').attr({'class' : 'form-control', 'readonly' : 'readonly', 'name' : 'link[0][tanggal]'})
                        var inputUser = $('<input />').attr({'class' : 'form-control', 'readonly' : 'readonly', 'name' : 'link[0][user]'})
                        var inputSubjek = $('<input />').attr({'class' : 'form-control', 'readonly' : 'readonly', 'name' : 'link[0][subjek]'})
                        var inputKeterangan = $('<input />').attr({'class' : 'form-control', 'readonly' : 'readonly', 'name' : 'link[0][keterangan]'})
                        var inputJumlah = $('<input />').attr({'class' : 'form-control', 'readonly' : 'readonly', 'name' : 'link[0][jumlah_item]'})
                        var inputId = $('<input />').attr({'class' : 'form-control', 'readonly' : 'readonly', 'name' : 'link[0][id_detail]'})
                        var inputTipe = $('<input />').attr({'class' : 'form-control', 'readonly' : 'readonly', 'name' : 'link[0][tipe_permintaan]'})

                        var button = $('<button />').attr({'class' : 'btn'});   
                        var tdTanggal = td.clone().append(inputTanggal.addClass('formInput').attr({'type' : 'text', 'value' : $('input#data_tanggal').val()}));
                        var tdUser = td.clone().append(inputUser.addClass('formInput').attr({'type': 'text', 'value' : $('input#data_user').val()+'('+$('input#data_user_level').val()+')'}));
                        var tdSubjek = td.clone().append(inputSubjek.addClass('formInput').attr({'type': 'text', 'value' : $('input#data_subjek').val()}));
                        var tdKeterangan = td.clone().append(inputKeterangan.addClass('formInput').attr({'type': 'text', 'value' : $('input#data_keterangan').val()}));
                        var tdJumlah = td.clone().append(inputJumlah.addClass('formInput').attr({'type': 'text', 'value' : $('input#jumlah_alokasi').val()+' '+$('label#satuan_alokasi').text()}));
                        var tdId = tdh.clone().append(inputId.addClass('formInput').attr({'type': 'hidden', 'value' : $('input#id_detail').val()}));
                        var tdTipe = tdh.clone().append(inputTipe.addClass('formInput').attr({'type': 'hidden', 'value' : '2'}));
                        var tdAction = td.clone().html(button.addClass('btn-danger').html('<i class="fa fa-times"></i>'));

                        tr.append(tdTanggal);
                        tr.append(tdUser);
                        tr.append(tdSubjek);
                        tr.append(tdKeterangan);
                        tr.append(tdJumlah);
                        tr.append(tdId);
                        tr.append(tdTipe);
                        tr.append(tdAction);
                        $tableCariPermintaan.append(tr);

                        $('div#simpan_data_'+$rowId, $('tr#item_row_'+$rowId)).html($('table#table_search_permintaan > tbody').html());
                        // $konvert_akhir.val('');
            // $('.closePopupModal').click();
            // $form_add_pembelian = $('#form_add_pembelian');
            		var jumlah = $('input[name$="[jumlah]"]').val();
            			
            			$('input#items_jumlah_'+$rowId).val(parseInt( parseInt(jumlah) + parseInt($('input#jumlah_alokasi').val())));
            			$('input#items_jumlah_min_'+$rowId).val(parseInt( parseInt(jumlah) + parseInt($('input#jumlah_alokasi').val())));
        });
    }
</script>