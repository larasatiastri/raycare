<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Paket", $this->session->userdata("language"))?></span>
					</h4>
				</div>
				<div class="modal-body">
						<input type="hidden" id="fg1" value="<?=$flag?>">
				 <input type="hidden" id="idtindakanpaket" name="idtindakanpaket" value="<?=$id?>">
				 <input type="hidden" id="idtindakanpaketid" name="idtindakanpaketid" value="<?=$paket_id?>">
 <div class="portlet">
		 
		<div class="portlet-body form">
			 
			<!-- BEGIN FORM-->
			<div class="form-wizard">
				<div class="form-body">
						
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Nomor Transaksi :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<p class="form-control-static" id="tagihantransaksinumber2">
									  
								</p>	
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Nama Paket :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-6">
								<p class="form-control-static" id="tagihanpaketname2">
									 <?=$nama_paket?>
								</p>
							</div>
						</div>
	
				</div>
			</div>
		 
		</div>
			<!-- END FORM-->
	</div>

	<div class="portlet">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Paket", $this->session->userdata("language"))?></span>
				</div>
		
			</div>
		</div>
		<div class="portlet-body form">
			 
			<div class="form-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="table_view_paket5">
					<thead>
					<tr role="row" class="heading">
						 
						<th><center><?=translate("Nomor",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Nama",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Jatah",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Digunakan",$this->session->userdata("language"))?></center></th>
							<th><center><?=translate("Sisa",$this->session->userdata("language"))?></center></th>
							 
							
					</tr>
					</thead>
					<tbody>
					 
					</tbody>
					</table>

					<div class="form-group">						
						<div class="col-md-4">
						<?php
			                $jumlah_item = array(
			                    "name"			=> "jumlah_item",
			                    "id"			=> "jumlah_item",
								"size"			=> "5",
			                    "class"			=> "text",
								"readonly"		=> "readonly",
								"hidden"		=> "hidden",
			                    // "value"			=> $result_count
			                );
							echo form_input($jumlah_item);
						?>
						</div>
					</div>

				</div>
					
			</div>
			 
		</div>
		 
	</div>
 
				</div>
				<div class="modal-footer">
					<a class="btn default modal_batal" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
					<a class="btn btn-primary modal_ok" data-dismiss="modal"><?=translate('OK', $this->session->userdata('language'))?></a>
				</div>
		 
 
 
 
<link href="<?=base_url()?>assets/metronic/admin/layout3/css/google-font-open-sans-400-300-600-700.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/mb/global/css/maestrobyte.css" rel="stylesheet" type="text/css">
<link href="<?=base_url()?>assets/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css">
 
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
 <div id="fk2">

 </div>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js" type="text/javascript"></script>


 <script language="javascript" type="text/javascript">
 // $("#fk1").html('');
	// 	$("#fk2").html('');
	// 	loadJS = function(src) {
 //     		var jsLink = $("<script type='text/javascript' src='<?=base_url()?>assets/metronic/global/plugins/bootstrap/js/"+src+"'>");
 //     		$("#fk2").append(jsLink); 
 // 		}; 
 //  		loadJS("bootstrap.min.js");
 var baseAppUrl              = '';
 var x=0;
 $(document).ready(function() {
  	var bootstrap3_enabled2 = (typeof $().emulateTransitionEnd == 'function');
	if(bootstrap3_enabled2==false)
	{
		loadJS = function(src) {
     		var jsLink = $("<script type='text/javascript' src='<?=base_url()?>assets/metronic/global/plugins/bootstrap/js/"+src+"'>");
     		$("#fk2").append(jsLink); 
 		}; 
  		loadJS("bootstrap.min.js");
	}
  
  	//$("#flagjs").val(1);
  
 

 alert(bootstrap3_enabled2);
    baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
    
     
    	handleViewTagihan5();
     
    
 
     $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getnomortransaksi",  
                                data:  {transaksiid:$("#idtindakanpaket").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    
                                      $("#tagihantransaksinumber2").html(data.id);
                                       
                                     
                                     
                                     
                                }
                   
                       });


});
 var handleViewTagihan5= function(){
  
      oTableViewTagihann5=$("#table_view_paket5").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_view_tagihan_paket/' + $("#idtindakanpaket").val() + '/' + $("#idtindakanpaketid").val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'visible' : true, 'searchable': true, 'orderable': true },
                {'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                {'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                 
                ],
             
        });

  		$("#table_view_paket5").on('draw.dt', function (){
			$('.btn', this).tooltip();
		 
          });
         
     
    };

 </script>