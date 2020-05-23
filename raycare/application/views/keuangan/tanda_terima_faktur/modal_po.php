<?php
	$form_attr = array(
	    "id"            => "form_laporan_hd", 
	    "name"          => "form_laporan_hd", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title uppercase"><?=translate("Daftar Pembelian Ke ", $this->session->userdata("language"))?><strong><?=$data_supplier['nama']?></strong></h4>
</div>
<div class="modal-body">
    <div class="portlet light bordered hidden">
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-md-12 hidden"><?=translate("Tipe Pembayaran", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-12">
	                        <div class="row">
		                        <div class="col-md-8">
									<div class="checkbox-list">
									
									<label class="checkbox-inline">
									<input type="checkbox" class="tipe_bayar" name="tempo" id="tempo" value="3" checked=""> Tempo </label>
								</div><a id="cari" class="btn btn-primary col-md-12" href="#"><i class="fa fa-search"></i> <?=translate("Cari", $this->session->userdata("language"))?></a>
								</div>
							</div>
						</div>
						<input type="hidden" name="tipe_bayar_id" id="tipe_bayar_id" value="3" >
						<input type="hidden" name="supplier_id_modal" id="supplier_id_modal" value="<?=$supplier_id?>" >
                        
                    </div>
                   
                </div>
                    
            </div>
            
        </div>
    </div>    
    <table class="table table-striped table-bordered table-hover table-condensed" id="table_pembelian">
		<thead>
			<tr>
				<th class="text-center"><?=translate("No. PO", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
				<th class="text-center"><?=translate("Total PO", $this->session->userdata("language"))?></th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
			</tr>
		</thead>

		<tbody>
		</tbody>
	</table>
</div>
<div class="modal-footer">
    <a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
</div>
<?=form_close()?>
<script type="text/javascript">

$(document).ready(function(){
    baseAppUrl = mb.baseUrl()+'keuangan/tanda_terima_faktur/';
    $('input[type=checkbox]').uniform();
    handleDataTablePembelian();
    handleButtonCari();

});

function handleDataTablePembelian() 
{
    var supplier_id = $('input#supplier_id_modal').val(),
    	tipe = $('input#tipe_bayar_id').val(),
    	$tablePembelian   = $('#table_pembelian');

	oTable = $tablePembelian.dataTable({
       	'processing'            : true,
		'serverSide'            : true,
        'stateSave'             : true,
        'pagingType'            : 'full_numbers',
		'language'              : mb.DTLanguage(),
		'ajax'              	: {
			'url' : baseAppUrl + 'listing/'+tipe+'/'+supplier_id ,
			'type' : 'POST',
		},			
        
		'pageLength'			: 10,
		'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
		'order'                	: [[0, 'desc']],
		'columns'               : [
			{ 'visible' : true, 'searchable': true, 'orderable': false },
			{ 'visible' : true, 'searchable': true, 'orderable': false },
			{ 'visible' : true, 'searchable': true, 'orderable': false },
			{ 'visible' : true, 'searchable': false, 'orderable': false },
    		]
    });

    $tablePembelian.on('draw.dt', function (){
		$('.btn', this).tooltip();
		var $btnSelect = $('a.select', this);
		$btnSelect.on('click', function(e){
	        var 
	            $IdPO     = $('input[name="id_po"]'),
	            $NoPo   = $('div#no_po'),
	            $HargaPo   = $('div#harga_po');
	        
			id_po           = $(this).data('item').id;
			no_po           = $(this).data('item').no_po;
			harga           = parseInt($(this).data('item').grand_total);
			tanggal_pesan   = $(this).data('item').tanggal;
			tanggal_garansi = $(this).data('item').tanggal_garansi;
			tipe_bayar      = $(this).data('item').nama_bayar;
			keterangan      = $(this).data('item').keterangan;
			grand_total_po  = parseInt($(this).data('item').grand_total_po);
			grand_total     = parseInt($(this).data('item').grand_total);
			diskon          = parseFloat($(this).data('item').diskon);
			pph             = parseInt($(this).data('item').pph);
			dp              = parseFloat($(this).data('item').dp);
			biaya_tambahan  = parseInt($(this).data('item').biaya_tambahan);

	        lama_tempo = '';
	        if($(this).data('item').master_tipe_pembayaran_id == 3){
	        	lama_tempo = $(this).data('item').lama_tempo + ' Hari';
	        }

	        $('div#info_po').removeClass('hidden');
	        $('input#id_po').val(id_po);
	        $('input#id_po').attr('value',id_po);
	        $('label#tanggal_pesan').text(tanggal_pesan);
	        $('label#tanggal_garansi').text(tanggal_garansi);
	        $('label#tipe_bayar').text(tipe_bayar+' '+lama_tempo);
	        $('label#keterangan').text(keterangan);

	        $rows = $(this).data('detail');
	        $data_satuan = $(this).data('satuan_item');

	        xhtml = '';
	        total = 0;
	        x = 0;

	        $.each($rows, function(idx, row){
   				var harga_beli = parseFloat(row.harga_beli);
   				var jumlah_disetujui = parseFloat(row.jumlah_disetujui);

   				var terima = 0;
   				if(row.jumlah_terima != null){
   					terima = row.jumlah_terima;
   				}

   				var disc = parseFloat(row.diskon/100 * harga_beli);
   				var item_id = row.item_id;

   				xhtml += '<tr>';
	        	xhtml += '<td>'+row.kode+'</td>';
	        	xhtml += '<td>'+row.nama+'</td>';
	        	xhtml += '<td class="text-right">'+mb.formatRp(harga_beli)+'</td>';
	        	xhtml += '<td>'+row.diskon+' %</td>';
	        	xhtml += '<td class="text-left">'+row.jumlah_disetujui+' '+row.nama_satuan+'</td>';
	        	xhtml += '<td class="text-left hidden"><a href="'+baseAppUrl+'modal_pmb/'+row.id+'" data-target="#modal_pmb" data-toggle="modal">'+terima+'</a></td>';
	        	xhtml += '<td class="text-left"><input type="text" class="form-control" name="pmb['+x+'][jumlah_datang]" id="pmb_'+x+'_jumlah_datang" required></td>';
	        	xhtml += '<td class="text-center">';
	        	xhtml += '<select class="form-control" name="pmb['+x+'][satuan_datang]" id="pmb_'+x+'_satuan_datang">';
	        	

	        	$.each($data_satuan[x], function(idx, satuan_item){
	        		// alert(satuan_item);
	        		xhtml += '<option value="'+satuan_item.id+'">'+satuan_item.nama+'</option>';
               	});
	        	xhtml += '</select>';

                xhtml += '</td>';
	        	xhtml += '<td class="text-right">'+mb.formatRp((row.jumlah_disetujui * harga_beli) - (disc * row.jumlah_disetujui))+'</td>';
	        	xhtml += '</tr>';

   				total = total + ((row.jumlah_disetujui * harga_beli) - (disc * row.jumlah_disetujui));

   				x++;
	        });

	        $('tbody#detail_po').html(xhtml);

	        $()




	        $('div#total').text(mb.formatRp(total));


	        var tad = total - ((diskon/100) * total);
	        var tat = ((pph/100) * tad);

	        $('td#diskon_persen').text(diskon+' %');
	        $('td#diskon_nominal').text(mb.formatRp((diskon/100) * total));
	        $('td#ppn_persen').text(pph +' %');
	        $('td#ppn_nominal').text(mb.formatRp((pph/100) * tad));
	        $('td#dp_persen').text(dp + ' %');
	        $('td#dp_nominal').text(mb.formatRp((dp/100) * total));
	        $('td#grand_tot').text(mb.formatRp(tad ));
	        $('td#grand_tot_tax').text(mb.formatRp(tad +tat ));
	        $('input#grand_tot_hidden').val(tad + tat);
	        $('input#grand_tot_hidden').attr('value',tad + tat);
	        $('td#biaya_tambahan_po').text(mb.formatRp(biaya_tambahan));
	        $('td#grand_tot_biaya').text(mb.formatRp(tad + tat + biaya_tambahan));
	        

	        $form = $('#form_tanda_terima_faktur');
	        tplFormBiaya   = '<li class="fieldset-biaya">' + $('#tpl-form-biaya', $form).val() + '<hr></li>',
	        regExpTplBiaya = new RegExp('biaya[0]', 'g'),   // 'g' perform global, case-insensitive
	        biayaCounter   = 0,
	        formsBiaya = 
		    {
		        'biaya' : 
		        {            
		            section  : $('#section-biaya', $form),
		            template : $.validator.format( tplFormBiaya.replace(regExpTplBiaya, '{0}') ), //ubah ke format template jquery validator
		            urlData  : function(){ return baseAppUrl + 'get_biaya_tambahan'; },
		            counter  : function(){ biayaCounter++; return biayaCounter-1; },
		            fields   : ['id','pembelian_id','biaya_id','nominal','is_active'],
		            fieldPrefix : 'biaya'
		        }   
		    };

		    $.each(formsBiaya, function(idx, formBiaya){
	            var $section           = formBiaya.section,
	                $fieldsetContainer = $('ul#biayaList', $section);

	            $.ajax({
	                type     : 'POST',
	                url      : formBiaya.urlData(),
	                data     : {id_po: id_po},
	                dataType : 'json',
	                beforeSend : function(){
	                    Metronic.blockUI({boxed: true });
	                },
	                success  : function( results ) {
	                    if (results.success === true) {
	                        var rows = results.rows;

	                        $.each(rows, function(idx, data){
	                            addFieldsetBiaya(formBiaya,data);
	                        });

	                        handleCountTotalBiaya();
	                    }
	                    

	                },
	                complete : function(){
	                    Metronic.unblockUI();
	                }
	            });

	            // handle button add
	            $('a.add-biaya', formBiaya.section).on('click', function(){
	                addFieldsetBiaya(formBiaya,{});
	            });
	             
	        }); 

	        $('a#close').click();
	       
	        e.preventDefault();
	    });   				
	});
}

function addFieldsetBiaya(form,data)
{
    var 
        $section           = form.section,
        $fieldsetContainer = $('ul#biayaList', $section),
        counter            = form.counter(),
        $newFieldset       = $(form.template(counter)).prependTo($fieldsetContainer),
        fields             = form.fields,
        prefix             = form.fieldPrefix
    ;

    if(Object.keys(data).length>0){
        for (var i=0; i<fields.length; i++){
            // format: name="emails[_ID_1][subject]"
            $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).val( data[fields[i]] );
            $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).attr( 'value', data[fields[i]] );
            $('a.del-this-biaya', $newFieldset).attr('data-id',data[fields[0]]);
        }       
    }

    $('a.del-this-biaya', $newFieldset).on('click', function(){
        var id = $(this).data('id');
    
        handleDeleteFieldsetBiaya($(this).parents('.fieldset-biaya').eq(0), id);
    });

    $('input[name$="[nominal]"]', $newFieldset).on('change', function(){
        handleCountTotalBiaya();
    });

    $('select[name$="[biaya_id]"]', $newFieldset).select2();

    //jelasin warna hr pemisah antar fieldset
    $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');
};

function handleDeleteFieldsetBiaya($fieldset, id)
{
    var 
        $parentUl     = $fieldset.parent(),
        fieldsetCount = $('.fieldset-biaya', $parentUl).length,
        hasId         = false ; 

    if(id != undefined)
    {
        var i = 0;
        bootbox.confirm('Anda yakin akan menghapus biaya ini?', function(result) {
            if (result==true) {
                i = parseInt(i) + 1;
                if(i == 1)
                {
                    $('input[name$="[is_active]"]', $fieldset).val(0);
                    $fieldset.hide();        
                }
            }
        });
    }
    else
    {
        if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
        $fieldset.remove();            
    }

    handleCountTotalBiaya();
}

function handleCountTotalBiaya(){
    var $totalBiaya = $('input[name$="[nominal]"]', $form),
        grandTotal = 0,
        grandTotalPO = parseInt($('input#grand_tot_hidden').val());

    
    $.each($totalBiaya, function(idx, totalbiaya){
        var total = $(this).val();

        if(total == ''){
            total = 0;
        }if(total != ''){
            total = parseInt(total);
        }

        grandTotal = grandTotal + total;
    });

    $('td#biaya_tambahan_po').text(mb.formatRp(grandTotal));
    $('input#biaya_tambah_hidden').val(grandTotal);
    $('input#biaya_tambah_hidden').attr('value',grandTotal);
    $('input#grand_tot_biaya_hidden').val(grandTotal + grandTotalPO);
    $('input#grand_tot_biaya_hidden').attr('value', (grandTotal + grandTotalPO));
    $('td#grand_tot_biaya').text(mb.formatRp(grandTotal + grandTotalPO));

}
function handleSelectTipeBayar() {
    var $tipeBayar = $('input.tipe_bayar');
    var tipe = '';

    $.each($tipeBayar, function(idx,val){
        if($(this).prop('checked') == true){
            tipe = tipe + $(this).val() +'-';
            $('input#tipe_bayar_id').val(tipe);
        }
    });
}

function handleButtonCari(){
    $('a#cari').click(function(){
        handleSelectTipeBayar();
        var tipe_bayar = $('input#tipe_bayar_id').val(),
        	supplier_id = $('input#supplier_id_modal').val();
        oTable.api().ajax.url(baseAppUrl +  'listing/'+tipe_bayar+'/'+supplier_id ).load();
    });
}
</script>
