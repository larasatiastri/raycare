<?php
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Ganti Jadwal Pasien", $this->session->userdata("language"))?></span>
	</div>
</div>

<div class="modal-body">
<div class="portlet light">
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
                <label class="control-label col-md-4"><?=translate("Nomor Pasien", $this->session->userdata("language"))?> :</label>
                <div class="col-md-6">
                    <?php
                        $pasien = $this->pasien_m->get($pasien_id);
                        $nomor_pasien = array(
                            "id"          => "nomor_pasien",
                            "name"        => "nomor_pasien",
                            "autofocus"   => true,
                            "class"       => "form-control",
                            "placeholder" => translate("Pasien", $this->session->userdata("language")),
                            "style"       => "background-color: transparent;border: 0px solid;", 
                            "readonly"    => "readonly",
                            "value"       => $pasien->no_member
                        );
                        echo form_input($nomor_pasien);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
                <div class="col-md-6">
                    <?php
                        $nama_pasien_awal = array(
                            "id"          => "nama_pasien_awal",
                            "name"        => "nama_pasien_awal",
                            "autofocus"   => true,
                            "class"       => "form-control",
                            "placeholder" => translate("Pasien", $this->session->userdata("language")),
                            "style"       => "background-color: transparent;border: 0px solid;", 
                            "readonly"    => "readonly",
                            "value"       => $pasien->nama
                        );
                        echo form_input($nama_pasien_awal);
                    ?>
                </div>
            </div>
            <div class="form-group">
            	 <label class="control-label col-md-4"><?=translate("Jadwal Awal", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <input type="text" name="jadwal_awal" class="form-control jadwal_awal" style ="background-color: transparent;border: 0px solid;" readonly="readonly" value="<?=$hari.', '. date('d M Y', strtotime($tanggal))?>">
                    </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4"><?=translate("Waktu", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <input type="text" name="waktu" class="form-control waktu" style ="background-color: transparent;border: 0px solid;" readonly="readonly" value="<?=$tipe?>">
                    </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4"><?=translate("No.Urut", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <input type="text" name="no_bed" class="form-control no_urut" style ="background-color: transparent;border: 0px solid;" readonly="readonly" value="<?=$urut?>">
                    </div>
            </div>
            
            <div class="form-group" id="alasan">
                <label class="control-label col-md-4"><?=translate("Alasan", $this->session->userdata("language"))?> :</label>
                <div class="col-md-6">
                <?php
                    $option_alasan = array(
                        '' => translate('Pilih', $this->session->userdata('language')),
                        '1' => translate('Hapus Jadwal', $this->session->userdata('language')),
                        '2' => translate('Pasien Hadir', $this->session->userdata('language')),
                        '3' => translate('Pasien Tidak Hadir', $this->session->userdata('language')),
                        '4' => translate('Pasien Pindah Jadwal', $this->session->userdata('language')),
                        '5' => translate('Digantikan Pasien Lain', $this->session->userdata('language')),
                    );

                    echo form_dropdown('option_alasan', $option_alasan, '', 'id="option_alasan" class="form-control" ');
                ?>
                    
                </div>
            </div>

            <div class="form-group hidden" id="ganti_jadwal">          
                <label class="control-label col-md-4"><?=translate("Ubah Jadwal", $this->session->userdata("language"))?> :</label>
                <div class="col-md-6">
                    <?php
                        $tgl_jadwal = date('Y-m-d', strtotime($tanggal)).' '.$waktu.':00';
                        $dayofweek = date('w', strtotime($tgl_jadwal)) - 1;
                        $lastdayofweek = 6 - $dayofweek;


                        $tgl_awal = date('Y-m-d H:i', strtotime($tgl_jadwal."-$dayofweek days"));
                        $tgl_akhir = date('Y-m-d H:i', strtotime($tgl_jadwal."+$lastdayofweek days"));

                        $date_jadwal = date_range($tgl_awal, $tgl_akhir);
                                    

                        echo form_dropdown('tanggal_id', $date_jadwal, '', "id=\"tanggal_id\" class=\"form-control\"");
                    ?>
                    <span id="text_info"></span>
                </div>
            </div>
            <div id="ganti_pasien" class="hidden">
                <div class="form-group">
                    <label class="control-label col-md-4"><?=translate("Nomor Pasien Pengganti", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <?php
                                    $no_member = array(
                                        "id"          => "no_member",
                                        "name"        => "no_member",
                                        "autofocus"   => true,
                                        "class"       => "form-control",
                                        "placeholder" => translate("Nomor Pasien", $this->session->userdata("language"))
                                    );
                                    
                                    echo form_input($no_member);
                                ?>
                            <span class="input-group-btn">
                                <a class="btn btn-primary pilih-pasien" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
                                    <i class="fa fa-search"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4"><?=translate("Nama Pasien Pengganti", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-6">
                            <input type="text" name="nama_pasien" id="nama_pasien"  class="form-control nama_pasien"  readonly="readonly">
                        </div>
                </div>
            </div>
            <div class="form_group hidden">
                <label class="control-label col-md-4">cobaaaa:</label>
                <div class="col-md-6">
                <?php
                    $id_pasien = array(
                        "id"          => "id_pasien",                    
                        "name"        => "id_pasien",
                        "autofocus"   => true,
                        "class"       => "form-control",
                        "placeholder" => translate("Pasien", $this->session->userdata("language"))
                    );
                    echo form_input($id_pasien);
                ?>
                </div>
            </div>
            <div class="form-group" id="keterangan">
            	<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
        		<div class="col-md-6">
        			<?php
        				$ket = array(
        					"id"          => "keterangan",
        					"name"        => "keterangan",
        					"autofocus"   => true,
        					"class"       => "form-control keterangan",
        					"placeholder" => translate("Keterangan", $this->session->userdata("language")),
        					"rows"        => 4,
        					"value"       => $keterangan

        				);
        				echo form_textarea($ket);
        			?>
        		</div>
            </div>

            <div class="form-group">
            	<label class="control-label col-md-4"><?=translate("Created By", $this->session->userdata("language"))?> :</label>
        		<div class="col-md-6">
        			<?php $user_create = $this->user_m->get($created_by);?>
        			<label class="control-label"><?=$user_create->nama?></label>
        		</div>
            </div>
            <div class="form-group">
            	<label class="control-label col-md-4"><?=translate("Created Date", $this->session->userdata("language"))?> :</label>
        		<div class="col-md-6">
        			<label class="control-label"><?=date('d M Y H:i:s',strtotime($created_date))?></label>
        		</div>
            </div>
            <div class="form-group">
            	<label class="control-label col-md-4"><?=translate("Modified By", $this->session->userdata("language"))?> :</label>
        		<div class="col-md-6">
        			<?php $user_modified = '-'; if($modified_by != NULL) {$user_create = $this->user_m->get($modified_by); $user_modified = $user_create->nama;}?>
        			<label class="control-label"><?=$user_modified?></label>
        		</div>
            </div>
            <div class="form-group">
            	<label class="control-label col-md-4"><?=translate("Modified Date", $this->session->userdata("language"))?> :</label>
        		<div class="col-md-6">
        			<label class="control-label"><?=($modified_date!=NULL || $modified_date!='')?date('d M Y H:i:s',strtotime($modified_date)):'-'?></label>
        		</div>
            </div>
            <div class="form-group">
                    <div class="col-md-3">
                        <input type="text" class="form-control hidden" id="modified_date" name="modified_date" value="<?=$modified_date?>">
                        <input type="text" class="form-control hidden" id="cabang" name="cabang" value="<?=$this->session->userdata('cabang_id')?>">
                    </div>
            </div>
            <div class="form-group">
                    <div class="col-md-3">
                        <input type="text" class="form-control hidden" id="command" name="command" value="edit">
                    </div>
            </div>
            <div class="form-group">
                    <div class="col-md-3">
                        <input type="text" class="form-control id hidden" id="id" name="id" value="<?=$id?>">
                        <input type="text" class="form-control id hidden" id="id_pasien_awal" name="id_pasien_awal" value="<?=$pasien_id?>">
                    </div>
            </div>

            </div>
        </div>
    </div>
</div>
<?php
	$confirm = translate('Anda yakin akan mengubah jadwal untuk pasien ini?', $this->session->userdata('language'));
?>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary hidden" id="modal_ok_move" data-confirm="<?=$confirm?>" onClick="javascript:save();"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>

<script type="text/javascript">
	
$(document).ready(function(){

    $form_jadwal = $('#form_jadwal_move');
    handleValidation();
	baseAppUrl = mb.baseUrl() + 'klinik_hd/jadwal/';
	handleChangeSelect();
    handleButtonJadwal();
    var $btnSearchPasien  = $('a.pilih-pasien');
    handleBtnSearchPasien($btnSearchPasien);

    handleJwertyEnterRent($('input#no_member'));
    $('input[type=radio]').uniform();
});

function handleChangeSelect() 
{
	$select = $('select#tanggal_id');
	$select.on('change', function(){
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_jadwal',
            data     : { tgl : $select.val()},
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success  : function( results ) {
              if(results.success === true)
              {
              	$('span#text_info').text(results.msg);
              	$('span#text_info').attr('style','color:blue;');
                $('a#modal_ok_move').removeClass('hidden');
              }
              else
              {
              	$('span#text_info').text(results.msg);
              	$('span#text_info').attr('style','color:red;');
              	$('a#modal_ok_move').addClass('hidden');
              }
            },
            complete : function(){
                Metronic.unblockUI();
            }
        });
	});
}

function handleBtnSearchPasien($btn)
{
    var rowId  = $btn.closest('tr').prop('id');
    // console.log(rowId);

    $btn.popover({ 
        html : true,
        container : '.page-content',
        placement : 'bottom',
        content: '<input type="hidden" name="rowItemId"/>'

    }).on("show.bs.popover", function(){

        var $popContainer = $(this).data('bs.popover').tip();

        $popContainer.css({minWidth: '720px', maxWidth: '720px', zIndex: '99999'});

        if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

        $lastPopoverItem = $btn;

        $popoverPasienContent.show();

    }).on('shown.bs.popover', function(){

        var 
            $popContainer = $(this).data('bs.popover').tip(),
            $popcontent   = $popContainer.find('.popover-content')
            ;

        // record rowId di popcontent
        $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
        
        // pindahkan $popoverItemContent ke .popover-conter
        $popContainer.find('.popover-content').append($popoverPasienContent);

    }).on('hide.bs.popover', function(){
        //pindahkan kembali $popoverPasienContent ke .page-content
        $popoverPasienContent.hide();
        $popoverPasienContent.appendTo($('.page-content'));

        $lastPopoverItem = null;

    }).on('hidden.bs.popover', function(){
        // console.log('hidden.bs.popover')
    }).on('click', function(e){
        e.preventDefault();
    });
};

function handleValidation() {
    var error1   = $('.alert-danger', $form_jadwal);
    var success1 = $('.alert-success', $form_jadwal);

    $form_jadwal.validate({
        // class has-error disisipkan di form element dengan class col-*
        errorPlacement: function(error, element) {
            error.appendTo(element.closest('[class^="col"]'));
        },
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        // rules: {
        // buat rulenya di input tag
        // },
        invalidHandler: function (event, validator) { //display error alert on form submit              
            success1.hide();
            error1.show();
            Metronic.scrollTo(error1, -200);
        },

        highlight: function (element) { // hightlight error inputs
            $(element).closest('[class^="col"]').addClass('has-error');
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control group
        }

        
    });       
}


function save()
{    
    if(! $form_jadwal.valid()) return;

    var i = 0;
    var msg = $('a#modal_ok_move').data('confirm');
    bootbox.confirm(msg, function(result) {
        if (result==true) {
        	i = parseInt(i) + 1;
        	if(i == 1)
        	{
        		$.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'check_modified',
                    data     : {id:$('input[name="id"]').val(), modified_date : $('input[name="modified_date"]').val()},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                       if(results.success == true)
                       {
                          	$.ajax({
				                type     : 'POST',
				                url      : baseAppUrl + 'save_jadwal',
				                data     : $form_jadwal.serialize(),
				                dataType : 'json',
				                beforeSend : function(){
				                    Metronic.blockUI({boxed: true });
				                },
				                success  : function( results ) {
				                    
				                },
				                complete : function(){
				                    $('button#closeModal').click();     
				                    $('a#refresh').click(); 
                                    $('a#refresh_hari').click(); 
				                }
				            });        
                       }    
                       else
                       {
                            bootbox.confirm(results.msg, function(result) {
                                if(result == true)
                                {
                                   	$('button#closeModal').click();     
				                    $('a#refresh').click(); 
                                    $('a#refresh_hari').click(); 
                                }
                            });
                       }
                    }
                });
	                     
        		
        	}
        }
    });
}


function handleButtonJadwal()
{

    $('select[name="option_alasan"]').on('change', function() {
        var tipe = $(this).val();
        if(tipe == '')
        {
            $('div#ganti_pasien').addClass('hidden');
            $('div#ganti_jadwal').addClass('hidden');
            $('input#no_member').removeAttr('required');
            $('input#nama_pasien').removeAttr('required');
            $('input#id_pasien').removeAttr('required');
            $('div#keterangan').addClass('hidden');
            $('a#modal_ok_move').addClass('hidden');
        }
        if(tipe == 1)//hapus jadwal
        {
            $('div#ganti_pasien').addClass('hidden');
            $('div#ganti_jadwal').addClass('hidden');
            $('input#no_member').removeAttr('required');
            $('input#nama_pasien').removeAttr('required');
            $('input#id_pasien').removeAttr('required');
            $('div#keterangan').removeClass('hidden');
            $('a#modal_ok_move').removeClass('hidden');
        }
        if(tipe == 2)//pasien hadir
        {
            $('div#ganti_pasien').addClass('hidden');
            $('div#ganti_jadwal').addClass('hidden');
            $('input#no_member').removeAttr('required');
            $('input#nama_pasien').removeAttr('required');
            $('input#id_pasien').removeAttr('required');
            $('div#keterangan').addClass('hidden');
            $('a#modal_ok_move').removeClass('hidden');
        }
        if(tipe == 3)//pasien tidak hadir
        {
            $('div#ganti_pasien').addClass('hidden');
            $('div#ganti_jadwal').addClass('hidden');
            $('input#no_member').removeAttr('required');
            $('input#nama_pasien').removeAttr('required');
            $('input#id_pasien').removeAttr('required');
            $('div#keterangan').removeClass('hidden');
            $('a#modal_ok_move').removeClass('hidden');
        }
        if(tipe == 4)//pasien ubah jadwal
        {
            $('div#ganti_pasien').addClass('hidden');
            $('div#ganti_jadwal').removeClass('hidden');
            $('input#no_member').removeAttr('required');
            $('input#nama_pasien').removeAttr('required');
            $('input#id_pasien').removeAttr('required');
            $('div#keterangan').removeClass('hidden');
            $('a#modal_ok_move').removeClass('hidden');
        }
        if(tipe == 5)//jadwal digantikan
        {
            $('div#ganti_pasien').removeClass('hidden');
            $('div#ganti_jadwal').addClass('hidden');
            $('input#no_member').attr('required','required');
            $('input#nama_pasien').attr('required','required');
            $('input#id_pasien').attr('required','required');
            $('div#keterangan').removeClass('hidden');
            $('a#modal_ok_move').removeClass('hidden');
        }
    });
}

function handleJwertyEnterRent($nopasien){
    jwerty.key('enter', function() {
        
        var NomorPasien = $nopasien.val();

        searchPasienByNomorAndFill(NomorPasien);

        // cegah ENTER supaya tidak men-trigger form submit
        return false;

    }, this, $nopasien );
}

function searchPasienByNomorAndFill(NomorPasien)
{
    $.ajax({
        type     : 'POST',
        url      : mb.baseUrl() + 'klinik_hd/jadwal/search_pasien_by_nomor',
        data     : {no_pasien:NomorPasien},   
        dataType : 'json',
        beforeSend : function(){
            Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
        },
        success : function(result){
            if(result.success === true)
            {
                var 
                    $noPasien   = $('input[name="no_member"]'),
                    $IdPasien   = $('input[name="id_pasien"]'),
                    $NamaPasien = $('input[name="nama_pasien"]'),
                    data        = result.rows;

                    $IdPasien.val(data.id);
                    $noPasien.val(data.no_ktp);
                    $NamaPasien.val(data.nama);
            }
            else
            {
                mb.showMessage('error',result.msg,'Informasi');
                $('input#no_member').focus();
            }
        },
        complete : function()
        {
            Metronic.unblockUI();
        }
    });
}
</script>