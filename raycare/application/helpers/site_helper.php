<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('object_to_array'))
{
	function object_to_array($d)
	{
		if (is_object($d))
		{
			$d = get_object_vars($d);
		}
		if (is_array($d))
		{
			return array_map(__FUNCTION__, $d);
		}
		else
		{
			return $d;
		}
	}
}


if(!function_exists('make_comparer'))
{
	function make_comparer() {
		// Normalize criteria up front so that the comparer finds everything tidy
		$criteria = func_get_args();

		foreach ($criteria as $index => $criterion) {
			$criteria[$index] = is_array($criterion)
				? array_pad($criterion, 3, null)
				: array($criterion, SORT_ASC, null);
		}
	 
		return function($first, $second) use ($criteria) {
			foreach ($criteria as $criterion) {
				// How will we compare this round?
				list($column, $sortOrder, $projection) = $criterion;
				$sortOrder = $sortOrder === SORT_DESC ? -1 : 1; 

				// If a projection was defined project the values now
				if ($projection) {
					$lhs = call_user_func($projection, $first[$column]);
					$rhs = call_user_func($projection, $second[$column]);
				}
				else {
					$lhs = $first[$column];
					$rhs = $second[$column];
				}	 

				// Do the actual comparison; do not return if equal
				if ($lhs < $rhs) {
					return -1 * $sortOrder;
				}
				else if ($lhs > $rhs) {
					return 1 * $sortOrder;
				}
			}
	 
			return 0; // tiebreakers exhausted, so $first == $second
		};
	}
}



if(!function_exists('array_sort_by_column'))
{
	function array_sort_by_column(&$array, $column, $direction = SORT_ASC) 
	{
		$reference_array = array();		

		foreach($array as $key => $row) 
		{
			$reference_array[$key] = $row[$column];
	    }

	    array_multisort($reference_array, $direction, $array);
	}
}

if(!function_exists("debug_var")) {
	function debug_var($var, $exit = FALSE) {
		echo "<pre>";
		print_r($var);
		echo "</pre>";

		if($exit)
			exit;
	}
}

if(!function_exists("generate_signature")) {
	function generate_signature($tStamp) {
		$apiKey = config_item('api_key');
 
		$secretKey = config_item('api_secret');

		// Generates a random string of ten digits
		$salt = md5("$apiKey&$tStamp");	// We hash the salt so it will hard to decypher

		// Computes the signature by hashing the salt with the secret key as the key
		$signature = hash_hmac('sha256', $salt, $secretKey, true);

		// base64 encode...
		$encodedSignature = base64_encode($signature);

		// urlencode...
		$encodedSignature = urlencode($encodedSignature);

		// echo "Voila! A signature: " . $encodedSignature;
		return $encodedSignature;
	}
}

if(!function_exists("generate_signature_server")) {
	function generate_signature_server($key, $secret, $tStamp) {
		$apiKey = $key;
 
		$secretKey = $secret;

		// Generates a random string of ten digits
		$salt = md5("$apiKey&$tStamp");	// We hash the salt so it will hard to decypher

		// Computes the signature by hashing the salt with the secret key as the key
		$signature = hash_hmac('sha256', $salt, $secretKey, true);

		// base64 encode...
		$encodedSignature = base64_encode($signature);

		// urlencode...
		$encodedSignature = urlencode($encodedSignature);

		// echo "Voila! A signature: " . $encodedSignature;
		return $encodedSignature;
	}
}


if(!function_exists("generate_api_signature")) {
	function generate_api_signature($param = array()) {
		$param['apikey'] = config_item('api_key');

	    ksort($param);
		
		// die(dump($param));	    
	    $string_param = "";
	    foreach($param as $key => $value) {
	        $string_param .= ($key.$value);
	    }
	    
	    return md5(config_item('api_secret').$string_param);
	}
}

if(!function_exists("generate_api_signature_server")) {
	function generate_api_signature_server($param = array()) {
	
		$secret = config_item('api_secret_server');
		// die(dump($secret));
		// Sort array parameter
	    ksort($param);
	    
	    $string_param = "";
	    foreach($param as $key => $value) {
	        $string_param .= ($key.$value);
	    }
	    
	    return md5($secret.$string_param);
	}
}

if(!function_exists("generate_key")) {
	function generate_key() {
		$factory = new QueryAuth\Factory();
		$keyGenerator = $factory->newKeyGenerator();

		$api = array();

		// 40 character random alphanumeric string
		$api['key'] = $keyGenerator->generateKey();

		// 60 character random string containing the characters
		// 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./
		$api['secret'] = $keyGenerator->generateSecret();

		return $api;
	}
}

if(!function_exists("generate_key_server")) {
	function generate_key_server() {
		$factory = new QueryAuth\Factory();
		$keyGenerator = $factory->newKeyGenerator();

		$api = array();

		// 40 character random alphanumeric string
		$api['key'] = $keyGenerator->generateKey();

		// 60 character random string containing the characters
		// 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./
		$api['secret'] = $keyGenerator->generateSecret();

		return $api;
	}
}

if(!function_exists("deliver_response")) {
	function deliver_response($status, $status_message, $data = NULL, $type = "json") {
		// header("HTTP/1.1: $status $status_message");

		if($type == "xml") {
			$response['http_code'] = $status;
			$response['status_message'] = $status_message;
			$response['data'] = $data;

			$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");
			array_to_xml($response, $xml);
			return $xml->asXML();
		}
		else {
			$response['http_code'] = $status;
			$response['status_message'] = $status_message;
			$response['data'] = $data;

			return json_encode($response);
		}
	}
}

if(!function_exists("time_out_of_bound")) {
	function time_out_of_bound($now, $timestamp) {
		$drift = 300;	// 10 second time from now
        if (abs($timestamp - $now) > $drift) {
            return true;
        }

        return false;
    }
}

if (!function_exists('getallheaders'))
{
    function getallheaders()
    { 
		$headers = ''; 
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
			   $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
    }
}

if (!function_exists('restriction_button'))
{
	function restriction_button($data, $user_level_id, $page, $button)
	{
		$CI =& get_instance();
		$CI->load->model('others/fitur_tombol_user_level_m');

		$fitur = $CI->fitur_tombol_user_level_m->get_by(array('page' => $page, 'button' => $button, 'user_level_id' => $user_level_id));

		if(count($fitur)) return '';
		else return $data;
	}
}

if(!function_exists('restriction_function'))
{
	function restriction_function($user_level_id, $page, $button)
	{
		$CI =& get_instance();
		$CI->load->model('others/fitur_tombol_user_level_m');

		$fitur = $CI->fitur_tombol_user_level_m->get_by(array('page' => $page, 'button' => $button, 'user_level_id' => $user_level_id));

		if(count($fitur)) return false;
		else return true;
		
	}
}

if(!function_exists('getSessionTimeLeft'))
{
	function getSessionTimeLeft()
	{
	    $CI =& get_instance();
	    $SessTimeLeft    = 0;
	    $SessExpTime     = $CI->config->config["sess_expiration"];
	    $CurrTime        = time();
	    $lastActivity = $CI->session->userdata['last_activity'];
	    $SessTimeLeft = ($SessExpTime - ($CurrTime - $lastActivity))*1000;

	    return $SessTimeLeft;
	}
}

if(!function_exists('date_range'))
{
	function date_range( $first, $last, $step = '+8 hours', $format = 'd M Y H:i' ) 
	{
		$CI =& get_instance();
		$dates = array();
		$current = strtotime( $first );
		$last = strtotime( $last );

		$current_time = date('H', $current);
		$current_date = date('D', $current);
		$x= 0;
		$tipe = '';
		$day = '';
		$dates[''] = translate('Pilih', $CI->session->userdata('language')).'...';

		while( $current <= $last ) {
			$x++;
			if($current_time == '23')
			{
				$tipe = 'Malam';		
			}
			if($current_time == '07')
			{
				$tipe = 'Pagi';	
			}
			if($current_time == '13')
			{
				$tipe = 'Siang';		
			}
			if($current_time == '18')
			{
				$tipe = 'Sore';		
			}

			if($current_date == 'Mon') $day = "Senin";
			if($current_date == 'Tue') $day = "Selasa";
			if($current_date == 'Wed') $day = "Rabu";
			if($current_date == 'Thu') $day = "Kamis";
			if($current_date == 'Fri') $day = "Jumat";
			if($current_date == 'Sat') $day = "Sabtu";
			if($current_date == 'Sun') $day = "Minggu";

			$dates[date('Y-m-d H:i', $current )] = $day.', '.date( $format, $current ).' ('.$tipe.')';
			if($x == 1 || $x == 9 || $x == 17)
			{ 
				if($current_time == '23')
				{
					$step = '+8 hours';	
				}
				if($current_time == '07')
				{
					$step = '+6 hours';			
				}
				if($current_time == '13')
				{
					$step = '+5 hours';			
				}
				if($current_time == '18')
				{
					$step = '+5 hours';		
				}
			}
			if($x == 2 || $x == 10 || $x == 18)
			{
				if($current_time == '23')
				{
					$step = '+8 hours';	
				}
				if($current_time == '07')
				{
					$step = '+6 hours';			
				}
				if($current_time == '13')
				{
					$step = '+5 hours';			
				}
				if($current_time == '18')
				{
					$step = '+5 hours';		
				}
			}
			if($x == 3 || $x == 11 || $x == 19)
			{
				if($current_time == '23')
				{
					$step = '+8 hours';	
				}
				if($current_time == '07')
				{
					$step = '+6 hours';			
				}
				if($current_time == '13')
				{
					$step = '+5 hours';			
				}
				if($current_time == '18')
				{
					$step = '+5 hours';		
				}
			}
			if($x == 4 || $x == 12 || $x == 20)
			{
				if($current_time == '23')
				{
					$step = '+8 hours';	
				}
				if($current_time == '07')
				{
					$step = '+6 hours';			
				}
				if($current_time == '13')
				{
					$step = '+5 hours';			
				}
				if($current_time == '18')
				{
					$step = '+5 hours';		
				}
			}
			if($x == 5 || $x == 13 || $x == 21)
			{
				if($current_time == '23')
				{
					$step = '+8 hours';	
				}
				if($current_time == '07')
				{
					$step = '+6 hours';			
				}
				if($current_time == '13')
				{
					$step = '+5 hours';			
				}
				if($current_time == '18')
				{
					$step = '+5 hours';		
				}
			}
			if($x == 6 || $x == 14 || $x == 22)
			{
				if($current_time == '23')
				{
					$step = '+8 hours';	
				}
				if($current_time == '07')
				{
					$step = '+6 hours';			
				}
				if($current_time == '13')
				{
					$step = '+5 hours';			
				}
				if($current_time == '18')
				{
					$step = '+5 hours';		
				}
			}
			if($x == 7 || $x == 15 || $x == 23)
			{
				if($current_time == '23')
				{
					$step = '+8 hours';	
				}
				if($current_time == '07')
				{
					$step = '+6 hours';			
				}
				if($current_time == '13')
				{
					$step = '+5 hours';			
				}
				if($current_time == '18')
				{
					$step = '+5 hours';		
				}
			}
			if($x == 8 || $x == 16 || $x == 24)
			{
				if($current_time == '23')
				{
					$step = '+8 hours';	
				}
				if($current_time == '07')
				{
					$step = '+6 hours';			
				}
				if($current_time == '13')
				{
					$step = '+5 hours';			
				}
				if($current_time == '18')
				{
					$step = '+5 hours';		
				}
			}
			$current = strtotime( $step, $current );
			$current_time = date('H', $current);
			$current_date = date('D', $current);
		}

		return $dates;
	}
}

if(!function_exists('check_url'))
{
	function check_url($url)
	{
		$result = get_headers($url);

		return $result[0];
		
	}
}

if(!function_exists('romanic_number'))
{
	function romanic_number($number, $upcase = true){ 
	    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
	    $return = ''; 
	    while($number > 0){ 
	        foreach($table as $rom=>$arb){ 
	            if($number >= $arb){ 
	                $number -= $arb; 
	                $return .= $rom; 
	                break; 
	            } 
	        } 
	    } 

	    return $return; 
	}
}

if(!function_exists('formatrupiah'))
{
	function formatrupiah($val) {
        $hasil ='IDR. ' . number_format($val, 2 , ',' , '.' ) . ',-';
        return $hasil;
    }
}

if(!function_exists('formattanparupiahstrip'))
{
	function formattanparupiahstrip($val) {
        $hasil = number_format($val, 2 , ',' , '.' ) . ',-';
        return $hasil;
    }
}

if(!function_exists('formattanparupiah'))
{
	function formattanparupiah($val) {
        $hasil = number_format($val, 2 , ',' , '.' );
        return $hasil;
    }
}

if(!function_exists('formatrupiahnol'))
{
	function formatrupiahnol($val) {
        $hasil ='IDR. ' . number_format($val, 2 , ',' , '.' );
        return $hasil;
    }
}

if(!function_exists('formattanparupiahnol'))
{
	function formattanparupiahnol($val) {
        $hasil = number_format($val, 2 , ',' , '.' );
        return $hasil;
    }
}


if(!function_exists('getdayname'))
{
	function getdayname($current_day){
		if($current_day == 'Mon') $day = "Senin";
		if($current_day == 'Tue') $day = "Selasa";
		if($current_day == 'Wed') $day = "Rabu";
		if($current_day == 'Thu') $day = "Kamis";
		if($current_day == 'Fri') $day = "Jumat";
		if($current_day == 'Sat') $day = "Sabtu";
		if($current_day == 'Sun') $day = "Minggu";

		return $day;
	}
}
/* End of file php_function.php */

/* Location: ./application/helpers/php_function.php */