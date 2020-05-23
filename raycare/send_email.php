<?php
	ini_set( 'display_errors', 1 );   
    error_reporting( E_ALL );    
    $from = "sys_rhs@yahoo.com";    
    $to = "prog_rhs@yahoo.com";    
    $subject = "Checking PHP mail";    
    $message = "PHP mail berjalan dengan baik";   
    $headers = "From:" . $from;    
    if(mail($to,$subject,$message, $headers)){
    	echo "Testing email terkirim.";
    }else{
    	echo "Testing email tidak terkirim.";
    }    
?>