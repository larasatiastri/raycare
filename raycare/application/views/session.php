<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Session Habis", $this->session->userdata("language"))?></span>
	</div>
</div>

<div class="modal-body">
    <div class="portlet light">
        <div class="portlet-body form">
            <div class="form-body">
                "Session anda telah habis, silahkan login ulang untuk melanjutkan aktivitas anda."
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a type="button" id="closeModal" class="btn btn-primary">OK</a>
</div>
<script type="text/javascript">
    
$(document).ready(function(){
    $('a#closeModal').click(function() {
        location.href = mb.loginUrl()+'home/logout';
 
    });
});

</script>