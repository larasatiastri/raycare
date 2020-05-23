<?php
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<form action="#" id="form_jadwal" class="form-horizontal" role="form">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Jadwal Pasien", $this->session->userdata("language"))?></span>
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
            	 <label class="control-label col-md-4"><?=translate("Hari", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <input type="text" name="hari" value="<?=$hari?>" class="form-control hari"  readonly="readonly">
                    </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <input type="text" name="tanggal" value="<?=$tanggal?>" class="form-control tanggal"  readonly="readonly">
                    </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4"><?=translate("Waktu", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <input type="text" name="waktu" value="<?=$tipe?>" class="form-control waktu"  readonly="readonly">
                    </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4"><?=translate("No.Urut", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <input type="text" name="no_bed" value="<?=$urut?>" class="form-control no_urut"  readonly="readonly">
                    </div>
            </div>
    
            <div class="form-group">
            	<label class="control-label col-md-4"><?=translate("Nomor Pasien", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <div class="input-group">
                			<?php
                				$no_member = array(
                                    "id"          => "no_member",
                                    "name"        => "no_member",
                                    "autofocus"   => true,
                                    "class"       => "form-control",
                                    "required"    => "required",
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
                <label class="control-label col-md-4"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <input type="text" name="nama_pasien" id="nama_pasien"  class="form-control nama_pasien"  readonly="readonly">
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
                        "required"    => "required",
                        "placeholder" => translate("Pasien", $this->session->userdata("language"))
                    );
            		echo form_input($id_pasien);
                ?>
                </div>
            </div>
             <div class="form-group">
            	<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
        		<div class="col-md-6">
        			<?php
        				$keterangan = array(
                            "id"          => "keterangan",
                            "name"        => "keterangan",
                            "autofocus"   => true,
                            "class"       => "form-control",
                            "placeholder" => translate("Keterangan", $this->session->userdata("language")),
                            "rows"        => 4
        				);
        				echo form_textarea($keterangan);
        			?>
        		</div>
            </div> 	
            <div class="form-group">
        		<label class="control-label col-md-7">
        			<input type="checkbox" name="berulang" id="berulang" class="ulang_minggu" class="form-control" onclick="javascript:ulang_minggu()"><?=translate('Berulang Mingguan', $this->session->userdata('language'))?>
        		</label>
        	</div>
        	<div class="form-group">
                <label class="control-label col-md-4 hidden" id="label_berulang"><?=translate("Berulang Untuk", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                    <div class="input-group">
                        <input type="number" value="0" class="form-control hidden" id="minggu" name="minggu">
                        <span class="input-group-btn">
                            <a class="btn btn-default hidden" id="label_minggu" disabled >
                                <?=translate('Minggu', $this->session->userdata('language'))?>
                            </a>
                        </span>
                    </div>
                    </div>
            </div>
            <div class="form-group">
                    <div class="col-md-3">
                        <input type="text" class="form-control hidden" id="cabang" name="cabang" value="<?=$this->session->userdata('cabang_id')?>">
                    </div>
            </div>
            <div class="form-group">
                    <div class="col-md-3">
                        <input type="text" class="form-control hidden" id="command" name="command" value="add">
                    </div>
            </div>
            <div class="form-group">
                    <div class="col-md-3">
                        <input type="text" class="form-control id hidden" id="id" name="id">
                    </div>
            </div>
        </div>
        </div>
    </div>
</div>
<?php 
    $msg = translate('Apakah anda yakin menambahkan jadwal ini?', $this->session->userdata("language"));
 ?>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" data-confirm="<?=$msg?>" id="modal_ok" onclick="javascript:save();"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $form_jadwal = $('#form_jadwal');
    handleValidation();
    var $btnSearchPasien  = $('a.pilih-pasien');
    handleBtnSearchPasien($btnSearchPasien);

    handleJwertyEnterRent($('input#no_member'));
    $('input[type=checkbox]').uniform();
});

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

function ulang_minggu()
{
    
    var checked = $('input.ulang_minggu').prop('checked');
    if(checked){

    var $labelBerulang = $('label#label_berulang'),
        $labelMinggu = $('a#label_minggu'),
        $inputMinggu = $('input#minggu');

        $labelBerulang.removeClass("hidden");
        $labelMinggu.removeClass("hidden");
        $inputMinggu.removeClass("hidden");
    }
    else{
    
    var $labelBerulang = $('label#label_berulang'),
        $labelMinggu = $('a#label_minggu'),
        $inputMinggu = $('input#minggu');

        $labelBerulang.addClass("hidden");
        $labelMinggu.addClass("hidden");
        $inputMinggu.addClass("hidden");
    }   
        
}

function save()
{


    if(! $form_jadwal.valid()) return;

    var i = 0;
    var msg = $('a#modal_ok').data('confirm');
    bootbox.confirm(msg, function(result) {
        if (result==true) {
            i = parseInt(i) + 1;
            if(i === 1)
            {      
                $.ajax({
                    type     : 'POST',
                    url      : mb.baseUrl() + 'klinik_hd/jadwal/save_jadwal',
                    data     : $form_jadwal.serialize(),
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        
                        // $('button#closeModal').click();   
                        // Metronic.blockUI({boxed: true });         
                        // // location.href = mb.baseUrl() + 'klinik_hd/jadwal';
                    },
                    complete : function(){
                        $('button#closeModal').click();  
                        $('a#refresh').click(); 
                        $('a#refresh_hari').click(); 
                    }
                });  
            }               
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