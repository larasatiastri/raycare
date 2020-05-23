<?php
	$form_attr = array(
	    "id"            => "form_pemisahan_item", 
	    "name"          => "form_pemisahan_item", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
	echo form_open("", $form_attr);
?>

<div class="modal-header">
 
	<h4 class="modal-title">
		<span class="caption-subject font-blue-sharp bold uppercase judul"><?=translate("Membuat Konversi", $this->session->userdata("language"))?></span>
	</h4>
</div>
<div class="modal-body">
	<div class="form-group">
	<input type="hidden" id="id_gudang" name="id_gudang" value="<?=$id_gudang?>">
	<input type="hidden" id="index" name="index" value="<?=$index?>">
		<label class="col-md-2"><?=translate('Konversi', $this->session->userdata('language'))?> :</label>
		
		<div class="col-md-2">
			<?php
				$sub_konvert_satuan = array('' => "Pilih..");
				// die_dump($data_konvert);
				foreach ($data_konvert as $konvert) {
					$sub_konvert_satuan[$konvert['id_satuan'].'-'.$konvert['jumlah_item']] = $konvert['satuan'];

				}
				
				echo form_dropdown('tipe_konversi_awal', $sub_konvert_satuan, '', "id=\"tipe_konversi_awal\" class=\"form-control warehouse\"");
			?>
		</div>

		<label class="col-md-2"><?=translate('Menjadi', $this->session->userdata('language'))?> :</label>
		<div class="col-md-2">
			<?php
				$sub_konvert = array('' => "Pilih..");
				
				echo form_dropdown('tipe_konversi_akhir', $sub_konvert, '', "id=\"tipe_konversi_akhir\" class=\"form-control warehouse\"");
			?>
		</div>
	</div>
	<div id="content_identitas">
		
		
	</div>

	<div class="form-group">
		<label class="col-md-3" id="lbl_jumlah_awal"></label>
		<label class="col-md-2" id="jumlah_awal"></label>
		<?php
				$konversi = array(
					"id"			=> "konversi",
					"name"			=> "konversi",
					"autofocus"		=> true,
					"class"			=> "form-control hidden"
				);

				$id_item = array(
					"id"			=> "id_item",
					"name"			=> "id_item",
					"autofocus"		=> true,
					"class"			=> "form-control hidden",
					"value"			=> $id
				);

				$satuan_item = array(
					"id"			=> "satuan_item",
					"name"			=> "satuan_item",
					"autofocus"		=> true,
					"class"			=> "form-control hidden"
				);

				$jumlah_stok = array(
					"id"			=> "jumlah_stok",
					"name"			=> "jumlah_stok",
					"autofocus"		=> true,
					"class"			=> "form-control hidden",
					"value"			=> $id
				);

				$id_satuan_stok = array(
					"id"			=> "id_satuan_stok",
					"name"			=> "id_satuan_stok",
					"autofocus"		=> true,
					"class"			=> "form-control hidden"
				);


				echo form_input($konversi);
				echo form_input($id_item);
				echo form_input($satuan_item);
				echo form_input($jumlah_stok);
				echo form_input($id_satuan_stok);

			?>
	</div>

	<div class="form-group">
		<label class="col-md-3" id="lbl_jumlah_konvert"></label>
		<label class="col-md-2" id="jumlah_konvert"></label>
		<?php
				$hasil_konversi = array(
					"id"			=> "hasil_konversi",
					"name"			=> "hasil_konversi",
					"autofocus"			=> true,
					"class"			=> "form-control hidden", 
					"readonly"		=> "readonly"
				);

				$satuan_konversi = array(
					"id"			=> "satuan_konversi",
					"name"			=> "satuan_konversi",
					"autofocus"			=> true,
					"class"			=> "form-control hidden", 
					"readonly"		=> "readonly"
				);

				$satuan_item_sudah = array(
					"id"			=> "satuan_item_sudah",
					"name"			=> "satuan_item_sudah",
					"autofocus"			=> true,
					"class"			=> "form-control hidden"
				);

				echo form_input($hasil_konversi);
				echo form_input($satuan_konversi);
				echo form_input($satuan_item_sudah);
			?>
	</div>

	
</div>
<div class="modal-footer">
	<a class="btn default" data-dismiss="modal" id="closeModal"><?=translate('Batal', $this->session->userdata('language'))?></a>
    <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
<?=form_close();?>
<script>
	$( document ).ready(function() {
	    sub_konvert_options();
	    hasil_konvert_options();
	    handlemodalOk();
	    // addKonversiRow();
	});

	var  $tableKonversi = $('#table_konversi'),
         tplKonversiRow  = $.validator.format( $('#tpl_item_row_konversi').text()),
         itemCounter        = $('input#itemRow').val(),
         $jumlah =1;

	function sub_konvert_options()
	{
		$('select#tipe_konversi_awal').on('change', function(){
            //$('input#warehouse_id').val($(this).val());

            var $konvert_akhir = $('select#tipe_konversi_akhir'),
            	$item_id = $('input#id_item').val(),
            	namaSatuanAwal = $('select#tipe_konversi_awal option:selected').text(),
            	namaSatuanAkhir = $('select#tipe_konversi_akhir option:selected').text();

            var satuan_id = $(this).val(),
            	split_satuan = satuan_id.split('-');
            // alert(split_satuan);
            $('input#jumlah_stok').val(split_satuan[1]);
            $('input#id_satuan_stok').val(split_satuan[0]);

            $('label#lbl_jumlah_awal').text('Jumlah '+namaSatuanAwal +' :');
            
            $.ajax({
                type     : 'POST',
                url      : mb.baseUrl() + 'apotik/pemisahan_item/get_konversi',
                data     : {id_konversi: split_satuan[0], item: $item_id },
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $konvert_akhir.empty();
                    
                    $.each(results, function(key, value) {

                        $konvert_akhir.append($("<option></option>")
                            .attr({"value" : value.id, "data-konversi" : value.jumlah}).text(value.nama));
                        $konvert_akhir.val('');
                    })
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });

            $.ajax({
                type     : 'POST',
                url      : mb.baseUrl() + 'apotik/pemisahan_item/get_identitas_item',
                data     : {id_konversi: split_satuan[0], item: $item_id, id_gudang : $('input#id_gudang').val(), index:$('input#index').val() },
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
	                $('div#content_identitas').html(results);  
	                handleInputChange();
	                
	               
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
        })
	}

	function handleInputChange()
    {
    		// alert('a');
		$.each($('input.stock_item', $('#table_identitas_item')), function(idx, value){
			// alert('a');
			var id = idx+1;
			var stok = parseInt(this.value);

			$('input#identitas_jumlah_'+id).on('change keyup', function()
			{
				var update = stok-parseInt($('input#identitas_jumlah_'+id).val()),
					jumlah = parseInt($('select#tipe_konversi_akhir option:selected').attr('data-konversi'));
				
				if (!isNaN(update))
        		{
        			$('input#identitas_update_'+id).val(update);
	            
	            } else {

	            $('input#identitas_update_'+id).val(0);

            	}

            	var total_jumlah = 0;
            		 total_konversi = 0;
	            $.each($('input.jumlah_item', $('#table_identitas_item')), function(idx, value){
	                total_jumlah = total_jumlah + parseInt(this.value);
	                total_konversi = total_konversi + (parseInt(this.value) * jumlah);
	                $(this).attr('value', $(this).val());
	            });

	            $('label#jumlah_awal').text(total_jumlah);
	            $('input#konversi').val(total_jumlah);
	            $('input#konversi').attr('value',total_jumlah);

	            $('label#jumlah_konvert').text(total_konversi);
	            $('input#hasil_konversi').val(total_konversi);
	            $('input#hasil_konversi').attr('value',total_konversi);

	            // alert(total_jumlah);
	            // var total_jumlah = 0;
	            $.each($('input.update_jumlah', $('#table_identitas_item')), function(idx, value){
	                // total_jumlah = total_jumlah + parseInt(this.value);
	                $(this).attr('value', $(this).val());
	            }); 
					
			});
		})
    }

	function hasil_konvert_options()
	{
		$('select#tipe_konversi_akhir').on('change', function(){

            //$('input#warehouse_id').val($(this).val());


            var $konvert 		 = $('input#hasil_konversi'),
				$satuan_konvert  = $('input#satuan_konversi'),
				$id_konvert      = $('input#id_satuan_stok').val(),
				$item_id         = $('input#id_item').val(),
				$jumlah_item     = $('input#konversi').val(),
				$satuanItemSudah = $('input#satuan_item_sudah'),
				$satuanItem      = $('input#satuan_item'),
				$jumlah          = $('option:selected', this).attr('data-konversi'),
				namaSatuanAwal = $('select#tipe_konversi_awal option:selected').text(),
            	namaSatuanAkhir = $('select#tipe_konversi_akhir option:selected').text();

            	$('label#lbl_jumlah_konvert').text('Jumlah '+namaSatuanAkhir+' :');

             	$satuanItem.val($('select#tipe_konversi_awal option:selected').text());
                $satuanItemSudah.val($('select#tipe_konversi_akhir option:selected').text());
              
               	$hasil = $jumlah * $jumlah_item;
                $satuan_konvert.val($jumlah);
                $konvert.val($hasil);

            	$('input#konversi').keyup(function(){
	            	$jumlah_item = $(this).val();
	            	
	                $hasil = $jumlah * $jumlah_item;
	                $konvert.val($hasil);
	       		});
        })
	}


	function handlemodalOk()
	{
		$('a#modal_ok').click(function(){

            //$('input#warehouse_id').val($(this).val());
            var numRow = itemCounter++,
            	$row        = $('#item_row_'+numRow, $tableKonversi);

            var $satuan = '.'+$('select#tipe_konversi_awal option:selected').text();
            var classJumlah = $($satuan);

            var stok_satuan = ($('input#id_satuan_stok').val());
            var stok_satuan_akhir = ($('select#tipe_konversi_akhir').val());
            var konversi =  parseInt($('input#konversi').val());
            var konversi_akhir =  parseInt($('input#hasil_konversi').val());

            total =0;
            $.each(classJumlah, function(idx, value)
            {
            	total = total + parseInt(this.value);
            	// alert(total);
            	
            })
            
            var $max_konversi = parseInt( $('input#jumlah_stok').val());
            var $konversi_item = parseInt($('input#konversi').val());
            var $jumlah_total = total+$konversi_item;
            if($jumlah_total > $max_konversi)
            {
            	// alert('kosong');
            	$('a#closeModal').click();
            	 	bootbox.confirm('Jumlah Stok Inventory Tidak Mencukupi', function(result) {
		                if (result==true) {
		                    
		                }
		            });
            	
            }
            else if($('input#hasil_konversi').val() == 0)
            {
            	$('a#closeModal').click();
            	 	bootbox.confirm('Jumlah Stok Inventory Tidak Mencukupi', function(result) {
		                if (result==true) {
		                    
		                }
		            });
            }
            else
            {

            	// alert($satuan);
            	$('a#addRow').click();
            	var newIndex = parseInt($('input#index').val()) + 1;
	            $('a#tambah_konversi').attr('href', mb.baseUrl() + 'apotik/pemisahan_item/tambah_pisah_item/'+ $('input#id_item').val() +'/' + $('input#id_gudang').val() +'/'+ newIndex);


            var $itemSebelum          = $('input[name$="[item_sebelum_jumlah]"]', $row);
				$itemSatuanSebelum    = $('input[name$="[item_sebelum_satuan]"]', $row);
				$itemSesudah          = $('input[name$="[item_sesudah_jumlah]"]', $row);
				$itemSatuanSesudah    = $('input[name$="[item_sesudah_satuan]"]', $row);
				$itemSebelumLbl       = $('label[name$="[lblnameJumlah]"]', $row);
				$itemSatuanSebelumLbl = $('label[name$="[lblnameSatuan]"]', $row);
				$itemSesudahLbl       = $('label[name$="[lblnameJumlahSudah]"]', $row);
				$itemSatuanSesudahLbl = $('label[name$="[lblnameSatuanSudah]"]', $row);
				$itemNomor            = $('label#nomer', $row);
				$jumlah 			  = $('input[name$="[item_jumlah_item]"]', $row);

                $itemNomor.text(numRow);
                $itemSebelum.val($('input#konversi').val());
                $itemSebelumLbl.text($('input#konversi').val());
                $itemSesudah.val($('input#hasil_konversi').val());
                $itemSesudahLbl.text($('input#hasil_konversi').val());
                $itemSatuanSebelum.val(stok_satuan);
                $itemSatuanSebelumLbl.text($('input#satuan_item').val());
                $itemSatuanSesudah.val($('select#tipe_konversi_akhir').val());
                $itemSatuanSesudahLbl.text($('input#satuan_item_sudah').val());
                $jumlah.val($('input#konversi').val());
                $jumlah.addClass($('input#satuan_item').val());

                $('a.info', $row).attr('href', mb.baseUrl()+'apotik/pemisahan_item/data_batch_number/'+$('input#id_gudang').val()+'/'+$('input#id_item').val()+'/'+stok_satuan+'/'+numRow);
            	$('a.info', $row).attr('data-target', '#ajax_notes2');
            	$('a.info', $row).attr('data-toggle', 'modal');

            	$('div#simpan_identitas', $row).html($('table#table_identitas_item > tbody').html());

            	$('a#closeModal').click();

            	$.each($('input.stok_akhir'), function(idx)
            	{
	            	var id = $(this).prop('id');
	            	// 	tes = $('input#stok_'+id).val(parseInt($('input#stok_'+id).val())-konversi);
	            	// alert(tes);

	            	if(stok_satuan == id)
	            	{
			            $('input[name$="[stok_awal_sebelum]"]', $row).val( $('input#stok_'+id).val());
			            $('input#stok_'+id).val(parseInt($('input#stok_'+id).val())-konversi);
			            var jumlah_stok_awal = $('input#stok_'+id).val();
			            $('input[name$="[stok_akhir_sebelum]"]', $row).val(jumlah_stok_awal);

	            	}

	            	// alert(stok_satuan_akhir);

	            	if(stok_satuan_akhir == id)
	            	{	
			            $('input[name$="[stok_awal_sesudah]"]', $row).val( $('input#stok_'+id).val());
	            		$('input#stok_'+id).val(parseInt($('input#stok_'+id).val())+konversi_akhir);
			            var jumlah_stok_akhir = $('input#stok_'+id).val();
			            $('input[name$="[stok_akhir_sesudah]"]', $row).val(jumlah_stok_akhir);

	            	}
	            	// alert($tes);
            	})
            }
        })
	}
</script>