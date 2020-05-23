<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sent_email extends CI_Controller {    


	public function kirim_email()
	{

		ini_set( 'display_errors', 1 );   
	    error_reporting( E_ALL );    
	    $from = "sim.raycarehealthsolution@gmail.com";    
	    $to = "prog_rhs@yahoo.com";    
	    $subject = "Checking PHP mail";    
	    $message = "PHP mail berjalan dengan baik";   
	    $headers = "From:" . $from;    
	    mail($to,$subject,$message, $headers);    
	    echo "Pesan email sudah terkirim.";
	}
}