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
		date_default_timezone_set('UTC');
 
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

	 
		$consumerID = "1000";
		$variabel1 = "$consumerID&$tStamp";
 
		$data = $variabel1;
		$secretKey = "1112";

      
		$signature = hash_hmac('sha256', $data, $secretKey, true);
  		$encodedSignature = base64_encode($signature);

		$http_header_bpjs = array(
    		"Accept: application/json", 
			"X-cons-id: ".$consumerID, 
			"X-Timestamp: ".$tStamp, 
			"X-Signature: ".$encodedSignature
		);	

		return $http_header_bpjs;
	}
}

if(!function_exists('get_http_header_bpjs_xml'))
{
	function get_http_header_bpjs_xml()
	{
		date_default_timezone_set('UTC');
 
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

	 
		$consumerID = "1000";
		$variabel1 = "$consumerID&$tStamp";
 
		$data = $variabel1;
		$secretKey = "1112";

      
		$signature = hash_hmac('sha256', $data, $secretKey, true);
  		$encodedSignature = base64_encode($signature);

		$http_header_bpjs = array(
    		"Accept: application/xml", 
			"X-cons-id: ".$consumerID, 
			"X-Timestamp: ".$tStamp, 
			"X-Signature: ".$encodedSignature
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
        $data = json_decode(file_get_contents($CI->session->userdata('url_login')."api_server/get_menu_file/".$menu_path.'/'.$user_level_id, false, $context));
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
        $data = json_decode(file_get_contents($CI->session->userdata('url_login')."api_server/delete_menu_file/".$menu_path, false, $context));
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

        $data = json_decode(file_get_contents($CI->session->userdata('url_login')."api_server/get_menu_by_id/".$menu_id, false, $context));
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


        $data = json_decode(file_get_contents($CI->session->userdata('url_login')."api_server/get_menu_for_userlevel/".$menu_id."/".$user_level_id, false, $context));
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
			'antrian'       => $param['antrian'],
			'status'        => $param['status'],
			'status_verif'  => $param['status_verif'],
			'is_active'     => $param['is_active'],
        );
       
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

		// die(dump($CI->session->userdata('url_login')."api_server/sent_notification/".$action_id."/".$param_text."/".$param_id));

        $data = json_decode(file_get_contents($CI->session->userdata('url_login')."api_server/sent_notification/".$action_id."/".$param_text."/".$param_id, false, $context));
        $data = object_to_array($data);
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


        $data = json_decode(file_get_contents($CI->session->userdata('url_login')."api_server/get_notif/".$user_id."/".$level_id, false, $context));
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


        $data = json_decode(file_get_contents($CI->session->userdata('url_login')."api_server/delete_notif/".$notif_id."/".$user_id, false, $context));
        $data = object_to_array($data);
        return $data;
    }
}

if(!function_exists('check_bpjs'))
{
	function check_bpjs($url)
	{
		
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => get_http_header_bpjs()
            )
        ));

        $result = get_headers($url);

   //      if($result[0] == 'HTTP/1.1 200 OK')
   //      {
			// $data = json_decode(file_get_contents($url, false, $context));        	
	  //       return $data;
   //      }
   //      elseif($result[0] != 'HTTP/1.1 200 OK')
   //      {
 		// 	return false;        	
   //      }
   //      else
   //      {
        	return 'not_responding';
        // }
        
	}
}


if(!function_exists('create_sep'))
{
	function create_sep()
	{
		$param = array(
			'noKartu'      => '0001434606265', 
			'tglSep'       => '2015-03-26 13:05:03',
			'ppkPelayanan' => '0115R207', 
			'jnsPelayanan' => '2', 
			'catatan'      => 'testse', 
			'diagAwal'     => 'B010', 
			'poliTujuan'   => 'HD', 
			'klsRawat'     => '3', 
			'user'         => 'UJ', 
			'noMr'         => '80890JK9890', 
		);
        
      
        $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, "http://api.asterix.co.id/SepWebRest/sep/create/");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, get_http_header_bpjs_xml());
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $server_output = curl_exec($ch);
	    curl_close($ch);

	    return($ch);
        
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




