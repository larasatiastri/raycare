<form class="form-horizontal" id="form_add_notes">
	
	<div class="modal-body">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?=translate("TAMBAH NOTE PINDAH SHIFT", $this->session->userdata("language"))?> <?=$data_tindakan['no_transaksi'].' ( '.$data_pasien['nama'].' ['.$data_bed['kode'].'] )'?>
				</div>
				<div class="actions">
					<a class="btn btn-icon-only btn-default btn-circle add-notes">
						<i class="fa fa-plus"></i>
					</a>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					<input class="form-control" type="hidden" name="tindakan_hd_id" id="tindakan_hd_id" value="<?=$data_tindakan['id']?>"></input>
					<input class="form-control" type="hidden" name="bed_id" id="bed_id" value="<?=$data_bed['id']?>"></input>
					<input class="form-control" type="hidden" name="lantai_id" id="lantai_id" value="<?=$data_bed['lantai_id']?>"></input>
					<?php
	                    $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
	                    $attrs_notes = array(
	                        'id'          => 'item_notes_{0}',
	                        'name'        => 'item[{0}][notes]',
	                        'class'       => 'form-control'
	                    );

	                    $item_cols = array(
	                        'item_note'      => form_input($attrs_notes),
	                        'action'         => $btn_del
	                    );

	                    // gabungkan $item_cols jadi string table row
	                    $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
	                ?>
					<span id="tpl_notes_row" class="hidden"><?=htmlentities($item_row_template)?></span>
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="tabel_tambah_note">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="30%"><?=translate("Catatan", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <?//=$item_row?> -->
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<?php
		$msg = translate('Anda yakin untuk menambahkan note ini?', $this->session->userdata('language'));
	?>
	<div class="modal-footer">
		<a class="btn default" id="close_modal" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
		<a class="btn btn-primary" data-confirm="<?=$msg?>" id="buat_notes"><?=translate('OK', $this->session->userdata('language'))?></a>
	</div>
</form>

<script type="text/javascript">

	$(document).ready(function() 
	{
		$form = $('#form_add_notes');

		baseAppUrl       = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';
		$tableTambahNote = $('#tabel_tambah_note',$form);
		tplNoteRow       = $.validator.format($('#tpl_notes_row',$form).text());
		noteCounter      = 0;

		addItemRow();
		handleTambahRow();
		handleSaveNotes();
	});

	function addItemRow()
	{
		if(! isValidLastNoteRow() ) return;

        var numRow = $('tbody tr', $tableTambahNote).length;
        var 
            $rowContainer         = $('tbody', $tableTambahNote),
            $newItemRow           = $(tplNoteRow(noteCounter++)).appendTo( $rowContainer )
            ;
        $('input[name$="[notes]"]', $newItemRow).focus();
        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
    };

     
    function handleTambahRow()
    {
        $('a.add-notes').click(function() {
            addItemRow();
        });
    };

    function handleBtnDelete($btn)
    {
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableTambahNote)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableTambahNote).length == 0){
                addItemRow();
            }
            e.preventDefault();
        });
    };

    function isValidLastNoteRow()
    {      
        var 
            $itemNotes = $('input[name$="[notes]"]', $tableTambahNote ),
            itemNote    = $itemNotes.val()           
        
        return (itemNote != '');
    };
	
	function handleSaveNotes()
	{
        $('a#buat_notes').on('click', function(){

        	if(! isValidLastNoteRow() ) return;

        	var msg = $(this).data('confirm');
        	bootbox.confirm(msg, function(result){
        		if(result === true)
        		{
		            $.ajax ({ 
		                type: "POST",
		                url: baseAppUrl + "tambah_notes",  
		                data:  $form.serialize(),  
		                dataType : "json",
		                beforeSend : function(){
			                Metronic.blockUI({boxed: true });
			            },
		                success:function(data1)         
		                { 
		                // 	// data1[3] = lantai didapatkan dari tolak_bed
		                	$.ajax ({ 
			                    type: "POST",
			                    url: baseAppUrl + "show_denah_lantai_html",
		           	 			data:  {lantai: data1[3] },  
			                    dataType : "text",
			                    success: function(data2)         
			                    { 
			                    	$('a#close_modal').click();
			                        $("div.svg_file_lantai_"+data1[3]).html(data2);
		                    		mb.showMessage(data1[0],data1[1],data1[2]);
			                    },
			                });
		                },
		                complete : function() {
			                Metronic.unblockUI();
			            }
		            });                    			
        		}
        	});

        });
    }
</script>