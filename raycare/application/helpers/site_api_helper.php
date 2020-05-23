<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if(!function_exists('get_http_header'))
{
	function get_http_header()
	{
		$tStamp = time();

	    $http_header = array(
	        "Accept: application/json", 
	        "X-const-id: " . config_item('api_key'), 
	        "X-timestamp: " . $tStamp, 
	        "X-signature: " . generate_signature($tStamp)
	    );

	    return $http_header;		
	}
}

if(!function_exists('get_http_header_bpjs'))
{
	function get_http_header_bpjs()
	{
		// date_default_timezone_set('UTC');
	 //    $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		//date_default_timezone_set('UTC');

	    //$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

	 	date_default_timezone_set("Asia/Jakarta");

		$tStamp = strtotime(date("Y-m-d H:i:s"));
		
		$consumerID = "20636";
		$variabel1 = "$consumerID&$tStamp";

		$data = "20636";
		$secretKey = "7qWF1B3083";

		$signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);

  		$encodedSignature = base64_encode($signature);

		$http_header_bpjs = array(

    		"Accept: application/json", 
			"X-cons-id: ".$data, 
			"X-timestamp: ".$tStamp, 
			"X-signature: ".$encodedSignature
		);	

		return $http_header_bpjs;
	}
}


if(!function_exists('get_http_header_bpjs_new_kld'))
{
	function get_http_header_bpjs_new_kld()
	{
		date_default_timezone_set('UTC');
	    $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		// date_default_timezone_set("Asia/Jakarta");
		// $tStamp = strtotime(date("Y-m-d H:i:s"));

	 
		$consumerID = "4329";
		$variabel1 = "$consumerID&$tStamp";
 
		$data = "4329";
		$secretKey = "8xSF1B7AFE";

      
		$signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);
  		$encodedSignature = base64_encode($signature);
	   

		$http_header_bpjs = array(
    		"Accept: application/json", 
			"X-cons-id: ".$data, 
			"X-timestamp: ".$tStamp, 
			"X-signature: ".$encodedSignature
		);	

		return $http_header_bpjs;
	}
}

if(!function_exists('get_http_header_bpjs_xml'))
{
	function get_http_header_bpjs_xml()
	{
		// date_default_timezone_set('UTC');
	 //    $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		// date_default_timezone_set("Asia/Jakarta");
		// $tStamp = strtotime(date("Y-m-d H:i:s"));

	 
		// $consumerID = "20636";
		// $variabel1 = "$consumerID&$tStamp";
 
		// $data = "20636";
		// $secretKey = "7qWF1B3083";

      
		// $signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);
  // 		$encodedSignature = base64_encode($signature);
	   

	    $data = "20636";
	    $secretKey = "7qWF1B3083";
	         // Computes the timestamp
        date_default_timezone_set('UTC');
        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
	           // Computes the signature by hashing the salt with the secret key as the key
	    $signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);
	 
	   // base64 encode…
	    $encodedSignature = base64_encode($signature);

		$http_header_bpjs = array(
    		"Accept: application/xml", 
			"X-cons-id: ".$data, 
			"X-timestamp: ".$tStamp, 
			"X-signature: ".$encodedSignature
		);	

		return $http_header_bpjs;
	}
}

if(!function_exists('get_menu_api'))
{
	function get_menu_api($user_level_id)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $menu_path = urlencode(base64_encode('application/menu/'. $CI->session->userdata('session')));
        $data = json_decode(file_get_contents(config_item('ip_real')."api_server/get_menu_file/".$menu_path.'/'.$user_level_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}



if(!function_exists('delete_menu_api'))
{
	function delete_menu_api()
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $menu_path = urlencode(base64_encode('application/menu/'. $CI->session->userdata('session')));
        $data = json_decode(file_get_contents(config_item('ip_real')."api_server/delete_menu_file/".$menu_path, false, $context));
        $data = object_to_array($data);
	}
}

if(!function_exists('get_menu_by_id'))
{
	function get_menu_by_id($menu_id)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));

        $data = json_decode(file_get_contents(config_item('ip_real')."api_server/get_menu_by_id/".$menu_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_menu_for_user'))
{
	function get_menu_for_user($menu_id,$user_level_id)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents(config_item('ip_real')."api_server/get_menu_for_userlevel/".$menu_id."/".$user_level_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}



if(!function_exists('insert_user_api'))
{
	function insert_user_api($param,$url, $user_id = null)
	{
		$apisign_param = array(
			'user_level_id' => $param['user_level_id'],
			'cabang_id'     => $param['cabang_id'],
			'nama'          => $param['nama'],
			'username'      => $param['username'],
			'password'      => $param['password'],
			'bahasa'        => $param['bahasa'],
			'url'           => $param['url'],
			'is_active'     => $param['is_active'],
        );
       
        $apisign = generate_api_signature($apisign_param);

        $param['apisign'] = $apisign;
        if($user_id != null)
        {
        	$param['user_id'] = $user_id;
        }

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/insert_user/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    if(!$server_output)
	    {
	    	$file_name = urlencode(base64_encode($url));
	    	$file_pending = FCPATH . 'application/pending/'.$file_name;

	    	if (file_exists($file_pending) && is_file($file_pending)) {
	    		$serialized = serialize($param)."addinsert";

				file_put_contents($file_pending, $serialized, FILE_APPEND);
	          	
			}
			else
			{
				$serialized = serialize($param)."addinsert";
				if (! write_file($file_pending, $serialized, 'w+') ) {
		            die('unable to write file');
		        }
			}
	    }
	    return $server_output;
	}
}

if(!function_exists('insert_user_api_pending'))
{
	function insert_user_api_pending($param,$url)
	{
		unset($param['apisign']);

		$apisign_param = array(
			'user_level_id' => $param['user_level_id'],
			'cabang_id'     => $param['cabang_id'],
			'nama'          => $param['nama'],
			'username'      => $param['username'],
			'password'      => $param['password'],
			'bahasa'        => $param['bahasa'],
			'url'           => $param['url'],
			'is_active'     => $param['is_active'],
			'user_id'       => $param['user_id'],
        );
       
        $apisign = generate_api_signature($apisign_param);

        $param['apisign'] = $apisign;
        

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/insert_user_pending/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    if($server_output)
	    {
	    	
	    	return $server_output;
	    }
	}
}



if(!function_exists('edit_user_api'))
{
	function edit_user_api($param,$url,$user_id)
	{
		$apisign_param = array(
			'cabang_id'     => $param['cabang_id'],
			'nama'          => $param['nama'],
			'username'      => $param['username'],
			'user_level_id' => $param['user_level_id'],
			'bahasa'        => $param['bahasa'],
			'url'           => $param['url'],
        );
     
        $apisign = generate_api_signature($apisign_param);

        $param['apisign'] = $apisign;
        $param['id'] = $user_id;

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/edit_user_api/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);

	    return $server_output;
	}
}

if(!function_exists('edit_status_api'))
{
	function edit_status_api($param,$url,$user_id)
	{
		$apisign_param = array(
			'is_active'     => $param['is_active'],
        );
     
        $apisign = generate_api_signature($apisign_param);

        $param['apisign'] = $apisign;
        $param['id'] = $user_id;

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/edit_status_api/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);

	    return $server_output;
	}
}
if(!function_exists('edit_password_api'))
{
	function edit_password_api($param,$url,$user_id)
	{
		$apisign_param = array(
			'password'     => $param['password'],
        );
     
        $apisign = generate_api_signature($apisign_param);

        $param['apisign'] = $apisign;
        $param['id'] = $user_id;

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/edit_password_api/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);

	    return $server_output;
	}
}

if(!function_exists('move_user_photo'))
{
	function move_user_photo($url,$data_path)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));

        $data_path = urlencode(base64_encode($data_path));
        $data = json_decode(file_get_contents($url."api_server/move_user_photo/".$data_path, false, $context));
        $data = object_to_array($data);
        
        return $data;
	}
}

if(!function_exists('move_penjamin_scan'))
{
	function move_penjamin_scan($url,$data_path)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));

        $data_path = urlencode(base64_encode($data_path));
        $data = json_decode(file_get_contents($url."api_server/move_penjamin_scan/".$data_path, false, $context));
        $data = object_to_array($data);
        
        return $data;
	}
}

if(!function_exists('move_pasien_penj_dok'))
{
	function move_pasien_penj_dok($url,$data_path)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));

        $data_path = urlencode(base64_encode($data_path));
        $data = json_decode(file_get_contents($url."api_server/move_pasien_penj_dok/".$data_path, false, $context));
        $data = object_to_array($data);
        
        return $data;
	}
}

if(!function_exists('insert_user_level_api'))
{
	function insert_user_level_api($param,$url,$user_level_id=null)
	{
		$apisign_param = array(
			'nama'          => $param['nama'],
			'dashboard_url' => $param['dashboard_url'],
			'is_active'     => $param['is_active'],
			'persetujuan'   => $param['persetujuan'],
        );
        $apisign = generate_api_signature($apisign_param);

        $param['apisign'] = $apisign;
        if($user_level_id != null)
        {
        	$param['user_level_id'] = $user_level_id;
        }

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/insert_user_level/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
        // die(dump($server_output));

	    return $server_output;
	}
}

if(!function_exists('insert_pendaftaran_tindakan'))
{
	function insert_pendaftaran_tindakan($param,$url, $pendaftaran_id=null)
	{
		$CI =& get_instance();
		$apisign_param = array(
			'cabang_id'     => $param['cabang_id'],
			'poliklinik_id' => $param['poliklinik_id'],
			'dokter_id'     => $param['dokter_id'],
			'pasien_id'     => $param['pasien_id'],
			'penjamin_id'   => $param['penjamin_id'],
			'penanggung_jawab_id' => $param['penanggung_jawab_id'],
			'antrian'       => $param['antrian'],
			'antrian_dokter'       => $param['antrian_dokter'],
			'shift'               => $param['shift'],
			'status'        => $param['status'],
			'is_active'     => $param['is_active'],
        );
        if(isset($param['user_verif_id']))
        {
			$apisign_param['user_verif_id']  = $param['user_verif_id'];       	
        }
        if(isset($param['status_verif']))
        {
			$apisign_param['status_verif']  = $param['status_verif'];       	
        }
        if(isset($param['tanggal_verif']))
        {
			$apisign_param['tanggal_verif']  = $param['tanggal_verif'];       	
        }
       
        $apisign = generate_api_signature($apisign_param);

        $param['apisign'] = $apisign;
        $param['user_login_id'] = $CI->session->userdata('user_id');
        if($pendaftaran_id != null)
        {
        	$param['pendaftaran_id'] = $pendaftaran_id;
        }

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/insert_pendaftaran_tindakan/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);

	    if(!$server_output)
	    {
	    	$file_name = urlencode(base64_encode($url)).'_insertdaftar';
	    	$file_pending = FCPATH . 'application/pending/'.$file_name;

	    	if (file_exists($file_pending) && is_file($file_pending)) {
	    		$serialized = serialize($param)."addinsert";

				file_put_contents($file_pending, $serialized, FILE_APPEND);
	          	
			}
			else
			{
				$serialized = serialize($param)."addinsert";
				if (! write_file($file_pending, $serialized, 'w+') ) {
		            die('unable to write file');
		        }
			}
	    }
	    return $server_output;
	}
}


if(!function_exists('sent_notification'))
{
	function sent_notification($action_id,$param_text, $param_id=null)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));

		// die(dump(config_item('ip_real')."api_server/sent_notification/".$action_id."/".$param_text."/".$param_id));

        $data = json_decode(file_get_contents(config_item('ip_real')."api_server/sent_notification/".$action_id."/".$param_text."/".$param_id, false, $context));
        $data = object_to_array($data);
        return $data;
    }
}

if(!function_exists('sent_notification_po'))
{
	function sent_notification_po($tipe_po,$param_text, $urutan)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));

        $data = json_decode(file_get_contents(config_item('ip_real')."api_server/sent_notification_po/".$tipe_po."/".$param_text."/".$urutan, false, $context));
       // $data = object_to_array($data);
        return $data;
    }
}

if(!function_exists('change_file_notif'))
{
	function change_file_notif($url,$filename)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/change_file_content/".$filename, false, $context));
        $data = object_to_array($data);
        // die(dump($data));
        return $data;
    }
}

if(!function_exists('get_notif'))
{
	function get_notif($user_id, $level_id)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents(config_item('ip_real')."api_server/get_notif/".$user_id."/".$level_id, false, $context));
        $data = object_to_array($data);
        return $data;
    }
}

if(!function_exists('delete_notif'))
{
	function delete_notif($notif_id, $user_id)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents(config_item('ip_real')."api_server/delete_notif/".$notif_id."/".$user_id, false, $context));
        $data = object_to_array($data);
        return $data;
    }
}

if(!function_exists('get_http_header_bpjs_new'))
{
	function get_http_header_bpjs_new()
	{
		date_default_timezone_set('UTC');
	    $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
 
		$consumerID = "8971";
		$variabel1 = "$consumerID&$tStamp";

		$data = "8971";
		$secretKey = "5tT757AAAF";

  
		$signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);
		$encodedSignature = base64_encode($signature);
   

		$http_header_bpjs = array(
 			"Accept: application/json", 
			"X-cons-id: ".$data, 
			"X-timestamp: ".$tStamp, 
			"X-signature: ".$encodedSignature
		);	

		return $http_header_bpjs;
	}
}

if(!function_exists('check_noka_bpjs'))
{
	function check_noka_bpjs($noka)
	{

		$url = "http://dvlp.bpjs-kesehatan.go.id:8081/devwslokalrest/Peserta/peserta/".$noka;
		
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header_bpjs_new()
            )
        ));
		      
        $result = get_headers($url);

        if ($result == false) {
	        return 'not_responding';
        }else{
        	if($result[0] == 'HTTP/1.1 200 OK')
	        {
				$data = file_get_contents($url, false, $context); 
				$data = json_decode($data);
				$data = object_to_array($data);

				if($data['response']['peserta'] == NULL){
					 return false;
				}if($data['response']['peserta'] != NULL){
					 return $data;
				}  
	        }
	        else
	        {
	        	return 'not_responding';
	        }
        }
        
	}
}

if(!function_exists('check_bpjs'))
{
	function check_bpjs($url)
	{
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header_bpjs_new()
            )
        ));
		      
        $result = get_headers($url);
        // die(dump($result));

        if ($result == false) {
	        return 'not_responding';
        }else{
        	if($result[0] == 'HTTP/1.1 200 OK')
	        {
				$data = file_get_contents($url, false, $context); 
				$data = json_decode($data);
				$data = object_to_array($data);

				if($data['response']['peserta'] == NULL){
					 return false;
				}if($data['response']['peserta'] != NULL){
					 return $data;
				}  
	        }
	        else
	        {
	        	return 'not_responding';
	        }
        }
        
	}
}


if(!function_exists('create_sep'))
{
	function create_sep($param)
	{

	    $url = "http://10.10.2.124:8082/SepLokalRest/sep/";

	    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "XML=".$param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $data = curl_exec($ch);
        curl_close($ch);

     //    $ch = curl_init();

	    // curl_setopt($ch, CURLOPT_URL, $url);
	    // curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header_bpjs_xml());
	    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    // curl_setopt($ch, CURLOPT_POST, true);
	    // curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlRequest=". $param);
	    // curl_setopt($ch, CURLOPT_HEADER, 0);
	    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    // $server_output = curl_exec($ch);
	    // curl_close($ch);

	    return($ch);

	    // $url = "http://10.10.2.124:8082/SepLokalRest/sep/";
	    // $session = curl_init();

	    // $myvars = json_encode($param);
	
	    // curl_setopt ( $session, CURLOPT_URL, $url );
	    // curl_setopt ( $session, CURLOPT_HTTPHEADER, get_http_header_bpjs_xml() );
	    // curl_setopt ( $session, CURLOPT_VERBOSE, true );
	    // curl_setopt ( $session, CURLOPT_SSL_VERIFYPEER, false);
	    // curl_setopt ( $session, CURLOPT_SSL_VERIFYHOST, false);
	    // curl_setopt ( $session, CURLOPT_POST, true );
	    // curl_setopt ( $session, CURLOPT_POSTFIELDS, "xmlRequest=". $param );
	    // curl_setopt ( $session, CURLOPT_RETURNTRANSFER, TRUE );
	    // $response = curl_exec ( $session );

	    // return $response;  
        
	}
}

if(!function_exists('update_pendaftaran'))
{
	function update_pendaftaran($param,$url, $pendaftaran_id=null)
	{
		$CI =& get_instance();
		
        $apisign = generate_api_signature($param);

        $param['apisign'] = $apisign;
        $param['user_login_id'] = $CI->session->userdata('user_id');
        if($pendaftaran_id != null)
        {
        	$param['pendaftaran_id'] = $pendaftaran_id;
        }

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/insert_pendaftaran_tindakan/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);

	    return $server_output;
	}
}

if(!function_exists('insert_data_api'))
{
	function insert_data_api($param,$url,$path_model,$id_index = null)
	{
		$CI =& get_instance();

        $apisign = generate_api_signature($param);
        // die(dump($param));

        $param['apisign'] = $apisign;
        $param['created_by'] = $CI->session->userdata('user_id');
        $param['path_model'] = $path_model;
        $model = explode('/', $param['path_model']);
        $count = count($model) - 1;
        $param['model'] = $model[$count];

        if($id_index != null)
        {
        	$param['id_index'] = $id_index;
        }

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/insert_data_api/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    if(!$server_output)
	    {
	    	$file_name = urlencode(base64_encode($url)).'_insertpasien_hubungan';
	    	$file_pending = FCPATH . 'application/pending/'.$file_name;

	    	if (file_exists($file_pending) && is_file($file_pending)) {
	    		$serialized = serialize($param)."addinsert";

				file_put_contents($file_pending, $serialized, FILE_APPEND);
	          	
			}
			else
			{
				$serialized = serialize($param)."addinsert";
				if (! write_file($file_pending, $serialized, 'w+') ) {
		            die('unable to write file');
		        }
			}
	    }
	    return $server_output;
	}
}

if(!function_exists('insert_data_api_id'))
{
	function insert_data_api_id($param,$url,$path_model,$id_index = null)
	{
		$CI =& get_instance();

        $apisign = generate_api_signature($param);
        // die(dump($param));

        $param['apisign'] = $apisign;
        $param['path_model'] = $path_model;
        $model = explode('/', $param['path_model']);
        $count = count($model) - 1;
        $param['model'] = $model[$count];

        if($id_index != null)
        {
        	$param['id_index'] = $id_index;
        }

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/insert_data_api_id/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    if(!$server_output)
	    {
	    	$file_name = urlencode(base64_encode($url)).'_insertpasien_hubungan';
	    	$file_pending = FCPATH . 'application/pending/'.$file_name;

	    	if (file_exists($file_pending) && is_file($file_pending)) {
	    		$serialized = serialize($param)."addinsert";

				file_put_contents($file_pending, $serialized, FILE_APPEND);
	          	
			}
			else
			{
				$serialized = serialize($param)."addinsert";
				if (! write_file($file_pending, $serialized, 'w+') ) {
		            die('unable to write file');
		        }
			}
	    }
	    return $server_output;
	}
}

if(!function_exists('get_datatable_api'))
{
	function get_datatable_api($param,$pasien_id,$url)
	{
		$params = array(
			"order_api"   => serialize($param['order']),
			"columns_api" => serialize($param['columns']),
			"search_api"  => serialize($param['search']),
			"start_api"   => serialize($param['start']),
			"length_api"  => serialize($param['length']),
		);
        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/get_datatable_api/".$pasien_id);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);

        return $server_output;
	}
}

if(!function_exists('get_data_sejarah_api'))
{
	function get_data_sejarah_api($pasien_id,$url)
	{
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_data_sejarah_api/".$pasien_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_data_sejarah_assesment'))
{
	function get_data_sejarah_assesment($transaksi_id,$pasien_id,$url)
	{
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_data_sejarah_assesment/".$transaksi_id."/".$pasien_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('insert_pasien'))
{
	function insert_pasien($param,$url, $pasien_id = null)
	{
		$CI =& get_instance();

        $apisign = generate_api_signature($param);

        $param['apisign'] = $apisign;
        $param['created_by'] = $CI->session->userdata('user_id');

        if($pasien_id != null)
        {
        	$param['id_pasien'] = $pasien_id;
        }

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/insert_pasien/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    if(!$server_output)
	    {
	    	$file_name = urlencode(base64_encode($url)).'_insertpasien';
	    	$file_pending = FCPATH . 'application/pending/'.$file_name;

	    	if (file_exists($file_pending) && is_file($file_pending)) {
	    		$serialized = serialize($param)."addinsert";

				file_put_contents($file_pending, $serialized, FILE_APPEND);
	          	
			}
			else
			{
				$serialized = serialize($param)."addinsert";
				if (! write_file($file_pending, $serialized, 'w+') ) {
		            die('unable to write file');
		        }
			}
	    }
	    return $server_output;
	}
}


if(!function_exists('insert_pasien_hub_tlp_alm'))
{
	function insert_pasien_hub_tlp_alm($param,$url,$model,$id_index = null)
	{
		$CI =& get_instance();

        $apisign = generate_api_signature($param);

        $param['apisign'] = $apisign;
        $param['created_by'] = $CI->session->userdata('user_id');
        $param['model'] = $model;

        if($id_index != null)
        {
        	$param['id_index'] = $id_index;
        }

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/insert_pasien_hub_tlp_alm/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    if(!$server_output)
	    {
	    	$file_name = urlencode(base64_encode($url)).'_insertpasien_hubungan';
	    	$file_pending = FCPATH . 'application/pending/'.$file_name;

	    	if (file_exists($file_pending) && is_file($file_pending)) {
	    		$serialized = serialize($param)."addinsert";

				file_put_contents($file_pending, $serialized, FILE_APPEND);
	          	
			}
			else
			{
				$serialized = serialize($param)."addinsert";
				if (! write_file($file_pending, $serialized, 'w+') ) {
		            die('unable to write file');
		        }
			}
	    }
	    return $server_output;
	}
}

if(!function_exists('get_max_id_pembayaran'))
{
	function get_max_id_pembayaran($url)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_max_id_pembayaran/", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}


if(!function_exists('get_max_id_os_pesan'))
{
	function get_max_id_os_pesan($url)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_max_id_os_pesan/", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('update_data_api'))
{
	function update_data_api($param,$url,$path_model,$wheres)
	{
		$CI =& get_instance();

        $apisign = generate_api_signature($param);

        $param['apisign'] = $apisign;
        $param['created_by'] = $CI->session->userdata('user_id');
        $param['path_model'] = $path_model;
        $model = explode('/', $param['path_model']);
        $count = count($model) - 1;
        $param['model'] = $model[$count];

        if($wheres != null)
        {
        	$param['id_index'] = serialize($wheres);
        }

        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/update_data_api/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    if(!$server_output)
	    {
	    	$file_name = urlencode(base64_encode($url)).'_update_api';
	    	$file_pending = FCPATH . 'application/pending/'.$file_name;

	    	if (file_exists($file_pending) && is_file($file_pending)) {
	    		$serialized = serialize($param)."update";

				file_put_contents($file_pending, $serialized, FILE_APPEND);
	          	
			}
			else
			{
				$serialized = serialize($param)."addinsert";
				if (! write_file($file_pending, $serialized, 'w+') ) {
		            die('unable to write file');
		        }
			}
	    }
	    return $server_output;
	}
}

if(!function_exists('get_data_invoice'))
{
	function get_data_invoice($url, $tindakan_id, $tipe, $penjamin_id)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_data_invoice/".$tindakan_id."/".$tipe."/".$penjamin_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_max_no_invoice'))
{
	function get_max_no_invoice($url)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_max_no_invoice/", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_data_penjualan'))
{
	function get_data_penjualan($url, $penjualan_id=null)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_data_penjualan/".$penjualan_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}


if(!function_exists('get_data_pemesanan'))
{
	function get_data_pemesanan($url, $permintaan_id, $tipe, $customer_id, $divisi_id)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_data_pemesanan/".$permintaan_id."/".$tipe."/".$customer_id."/".$divisi_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_max_id_pemesanan'))
{
	function get_max_id_pemesanan($url)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_max_id_pemesanan/", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_max_no_pemesanan'))
{
	function get_max_no_pemesanan($url)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_max_no_pemesanan/", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_max_id_pemesanan_detail'))
{
	function get_max_id_pemesanan_detail($url)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_max_id_pemesanan_detail/", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_max_id_pemesanan_kirim_detail'))
{
	function get_max_id_pemesanan_kirim_detail($url)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_max_id_pemesanan_kirim_detail/", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_harga_jual_item'))
{
	function get_harga_jual_item($url,$item_id, $item_satuan_id, $cabang_id)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_harga_jual_item/".$item_id."/".$item_satuan_id."/".$cabang_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_max_saldo_invoice'))
{
	function get_max_saldo_invoice($url)
	{
		$CI =& get_instance();
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));


        $data = json_decode(file_get_contents($url."api_server/get_max_saldo_invoice", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_saldo'))
{
	function get_saldo($url,$tanggal)
	{
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));
		
        $data = json_decode(file_get_contents($url."api_server/get_saldo/".$tanggal, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_saldo_bank'))
{
	function get_saldo_bank($url,$tanggal,$bank)
	{
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));
		
        $data = json_decode(file_get_contents($url."api_server/get_saldo_bank/".$tanggal."/".$bank, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_saldo_external'))
{
	function get_saldo_external($url,$tanggal)
	{
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));
		
        $data = json_decode(file_get_contents($url."api_server/get_saldo_external/".$tanggal, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_data_pasien_tindakan'))
{
	function get_data_pasien_tindakan($url,$shift,$cabang_id)
	{
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));
		
        $data = json_decode(file_get_contents($url."api_server/get_data_pasien_tindakan/".$shift.'/'.$cabang_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_data_pasien_non_tindakan'))
{
	function get_data_pasien_non_tindakan($url,$shift,$cabang_id)
	{
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));
		
        $data = json_decode(file_get_contents($url."api_server/get_data_pasien_non_tindakan/".$shift.'/'.$cabang_id, false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('delete_data_api'))
{
	function delete_data_api($param,$url,$path_model,$id_index = null)
	{
		$CI =& get_instance();

        $param['path_model'] = $path_model;
        $model = explode('/', $param['path_model']);
        $count = count($model) - 1;
        $param['model'] = $model[$count];
        
        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/delete_data_api/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	   
	    return $server_output;
	}
}

if(!function_exists('get_data_api'))
{
	function get_data_api($url,$path_model,$wheres)
	{
		$CI =& get_instance();

       
        $param['path_model'] = $path_model;
        $model = explode('/', $param['path_model']);
        $count = count($model) - 1;
        $param['model'] = $model[$count];
        $param['wheres'] = serialize($wheres);

       
        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/get_data_api/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    if(!$server_output)
	    {
	    	
	    }
	    return $server_output;
	}
}


if(!function_exists('proses_inventory'))
{
	function proses_inventory($param,$url)
	{
		$CI =& get_instance();

        $apisign = generate_api_signature($param);

        $param['apisign'] = $apisign;
        $param['created_by'] = $CI->session->userdata('user_id');


        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url."api_server/proses_inventory");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);

	    return $server_output;
	}
}

if(!function_exists('get_jadwal_cabang'))
{
	function get_jadwal_cabang($url,$cabang_id,$date)
	{
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));

        $data = json_decode(file_get_contents($url."api_server/get_jadwal_cabang/".$cabang_id."/".$date, false, $context));
        $data = object_to_array($data);
    	// die(dump($data));
	    return $data;
        
	}
}

if(!function_exists('get_frequensi_tindakan'))
{
	function get_frequensi_tindakan($url,$startdate,$enddate,$id)
	{
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));

        $data = json_decode(file_get_contents($url."api_server/get_frequensi_tindakan/".$startdate."/".$enddate."/".$id, false, $context));
        $data = object_to_array($data);
        return $data;
        
	}
}

if(!function_exists('get_data_website_supplier'))
{
	function get_data_website_supplier($url)
	{
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));
		
        $data = json_decode(file_get_contents($url."api_server/get_data_website_supplier", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}

if(!function_exists('get_data_item_new'))
{
	function get_data_item_new($url)
	{
		$context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header()
            )
        ));
		
        $data = json_decode(file_get_contents($url."api_server/get_data_item_new", false, $context));
        $data = object_to_array($data);
        return $data;
	}
}






