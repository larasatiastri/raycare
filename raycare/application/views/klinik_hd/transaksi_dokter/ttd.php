<html>
	<head>
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
	<link href="<?=base_url()?>assets/mb/global/css/jquery.signature.css" rel="stylesheet">
	<style>
.kbw-signature { width: 400px; height: 200px; }
</style>
	</head>
<body>
<div id="sig_setuju"></div>
<p style="clear: both;">
	<button id="disable">Disable</button> 
	<button id="clear_setuju">Clear</button> 
	<button id="json">To JSON</button>
	<button id="svg">To SVG</button>
</body>
</html>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="<?=base_url()?>assets/mb/global/js/signature/jquery.signature.min.js"></script>
<script>
$(document).ready(function(){
    var sig = $('#sig_setuju').signature();
    		alert(sig);

	
	$('#clear_setuju').click(function() {
		sig.signature('clear');
	});
	$('#json').click(function() {
		alert(sig.signature('toJSON'));
	});
	$('#svg').click(function() {
		alert(sig.signature('toSVG'));
	});
});

</script>
