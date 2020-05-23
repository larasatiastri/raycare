<form id="modaltindakan" name="modaltindakan"  role="form" autocomplete="off">
    <?
        $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
        $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
        $msg = translate("Apakah anda yakin akan menyimpan harga ini?",$this->session->userdata("language"));
    ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title caption-subject font-blue-sharp bold uppercase"><?=translate('Harga tindakan ', $this->session->userdata('language'))?></h4>
</div>
<div class="modal-body">  
    <div class="row">
        <div class="alert alert-danger display-hide">
	        <button class="close" data-close="alert"></button>
	        <?=$form_alert_danger?>
		</div>
		<div class="alert alert-success display-hide">
	        <button class="close" data-close="alert"></button>
	        <?=$form_alert_success?>
        </div>
        <div class="style" style="margin-top: 10px;">
	        <div class="form-group hidden">
                <label class="control-label col-md-3"><?=translate("ID", $this->session->userdata("language"))?>:</label>
                <div class="col-md-4">
                    
                    <input class="form-control" id="poli_tindakan_id" name="poli_tindakan_id" value="<?=$id?>">
                        
                </div>
                <div class="col-md-12"></div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3"><?=translate("Tanggal", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-4">
				    <div class="input-group input-medium-date date date-picker">
						<input class="form-control" id="date" readonly required="required" value="<?=date('d M Y')?>">
						<span class="input-group-btn">
						    <button type="button" class="btn default date-set">
							    <i class="fa fa-calendar"></i>
							</button>
                		</span>
				    </div>
				</div>
			    <div class="col-md-12"></div>
	        </div>
            <div class="form-group">
				<label class="control-label col-md-3"><?=translate("Harga", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-4">
                    <div class="input-group">
                        <input type="text" id="harga" name="harga" required="required" class="form-control">
                        <span class="input-group-btn">
                            <a title="Tambahkan" id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-plus"></i></a>
                        </span>
                    </div>

				</div>
			    <div class="col-md-12"></div>
	        </div>
        </div>
    </div>

    <div class="form-body" style="margin-top: 15px;">
        <table class="table table-striped table-bordered table-hover" id="table_harga_tindakan">
            <thead>
                <tr role="row" class="heading">
                    <th scope="col" ><div class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></div></th>
                    <th scope="col" ><div class="text-center"><?=translate("Harga", $this->session->userdata("language"))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <div class="form-actions fluid right">	
	    <div class="col-md-12">
            <button type="reset" class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></button>
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
      	</div>		
    </div>
    <!-- <button type="button" class="btn default" data-dismiss="modal">Simpan</button> -->
</div>
</form>
<script>

$(document).ready(function() {
    var $form = $('form#modaltindakan'),
        baseAppUrl = mb.baseUrl() + 'master/tindakan/';
        $tabletindakan = $('table#table_harga_tindakan');

        handleDataTable2();
        handleConfirmSave();
        handleDatePickers();


});
function handleDataTable2() 
{
    oTable2=$tabletindakan.dataTable({
        'processing'            : true,
        'serverSide'            : true,
        'language'              : mb.DTLanguage(),
        'ajax'                  : {
            'url' : mb.baseUrl() + 'master/tindakan/listing_tindakan_2/' + $("input#poli_tindakan_id").val(),
            'type' : 'POST',
        },          
        'pageLength'            : 10,
        'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
        'order'                 : [[0, 'desc']],
        'columns'               : [
            { 'visible' : true, 'searchable': true, 'orderable': true },
            { 'visible' : true, 'searchable': true, 'orderable': true },     
        ]
    });
    $tabletindakan.on('draw.dt', function (){
         
    });
}

function handleConfirmSave(){
var $form = $('form#modaltindakan');
$('a#confirm_save', $form).click(function() {

      if (! $form.valid()) return;

        var msg = $(this).data('confirm');
        bootbox.confirm(msg, function(result) {
            if (result==true) {
                $.ajax
                ({ 

                    type: 'POST',
                    url: mb.baseUrl() + 'master/tindakan/insertharga',  
                    data:  {tggl:$("#date").val(),harga:$("#harga").val(),id:$("input#poli_tindakan_id").val()},  
                    dataType : 'json',
                    success:function(data)          //on recieve of reply
                    { 
                          
                       mb.showMessage(data[0],data[1],data[2]);
                       oTable2.api().ajax.url(mb.baseUrl() + 'master/tindakan/listing_tindakan_2/' + $("input#poli_tindakan_id").val()).load();
                        //location.href = mb.baseUrl() + 'approval/finance2/';
                        $("#harga").val('');
                        $("#date").val('');
                    }
       
                });
            }
        });
    });
}

function handleDatePickers() {
    if (jQuery().datepicker) {
        $('.date-picker', $('#modaltindakan')).datepicker({
            rtl: Metronic.isRTL(),
            format : 'd M yyyy',
            autoclose: true
        }).on('changeDate', function(ev){
            $('.datepicker-dropdown').hide();       // function jika menggunakan datepicker dalam modal 
        });
        $('body').removeClass("modal-open");
        $('.date').on('click', function(){
            if ($('#popup_modal').is(":visible") && $('body').hasClass("modal-open") == false) {
                $('body').addClass("modal-open");
            }
        });;
        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }
}


</script>

