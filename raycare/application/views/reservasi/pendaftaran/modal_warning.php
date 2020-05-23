<form class="form-horizontal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title"><?=translate('Dokumen Kadaluarsa', $this->session->userdata('language'))?></h4>
	</div>
	<div class="modal-body">
		 <div class="form-group">
			<label class="col-md-12"><?=translate('Dokumen di bawah ini harus diperbaharui', $this->session->userdata('language'))?> :</label>
			<div class="col-md-12">
				<div class="radio-list">
			<?php
				$i = 1;
				foreach ($data_dokumen as $dokumen) 
				{
					$notif = $dokumen->notif_hari.' day';       

		            if($dokumen->is_kadaluarsa == 1)
		            {
		                $date1=date_create(date('Y-m-d',strtotime($dokumen->tanggal_kadaluarsa)));
		                date_sub($date1,date_interval_create_from_date_string($notif));
		                $startdate=date_format($date1,"Y-m-d");
		                
		                $tanggal_kadaluarsa = date('d M Y',strtotime($dokumen->tanggal_kadaluarsa));
		            

		                if(date('Y-m-d',strtotime($dokumen->tanggal_kadaluarsa)) < date('Y-m-d') )
		                {
		                    $status='<span class="label label-danger">Kadaluarsa</span>';
		                }
		                else if(date('Y-m-d',strtotime($dokumen->tanggal_kadaluarsa)) >= date('Y-m-d') )
		                {
		                    if($startdate <= date('Y-m-d') )
		                    {
		                        $status='<span class="label label-warning">Peringatan</span></div>';
		                    }
		                    else
		                    {
		                        $status='<span class="label label-success">Aktif</span></div>';
		                    }
		                }                 
		            }
			?>
					<label><?=$i?>.&nbsp;<?=$dokumen->nama.' / '.date('d M Y', strtotime($dokumen->tanggal_kadaluarsa)).' / '.$status?></label>
			<?php
					$i++;
				}
			?>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary" id="button_ok" data-dismiss="modal">OK</button>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	handleButtonClose();
});

function handleButtonClose() {
    $('button#button_ok').click(function(){
        $('a#upload').click();
        $('div.tab_2_5').addClass('active');
    });
}
</script>