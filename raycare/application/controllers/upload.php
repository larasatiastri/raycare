<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

	public function upload_photo()
	{
		// A list of permitted file extensions

		$allowed = array('png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'gif','zip','pdf');

		if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
			$arr_filedata = pathinfo($_FILES['upl']['name']);
			$filename = $arr_filedata['filename'];
			$filename = str_replace(' ','_', $filename);
			$filename = date('ymdHis').'_'.$filename.'_'.$this->session->userdata('user_id');

			$tempFile = $_FILES['upl']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_temp_dir');
			$targetThumbnail = $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_temp_thumb_dir');
			$targetThumbnail2 = $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_temp_thumb_small_dir');
			$targetThumbnail3 = $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_temp_thumb_fix_dir');

			$fileParts = pathinfo($_FILES['upl']['name']);
			$extension = $fileParts ['extension'];
			//force rename $targetFile

			// die_dump($extension);
			$targetFile = rtrim($targetPath,'/') . '/' . $filename . '.' . $fileParts['extension'];
			$targetThumb = rtrim ($targetThumbnail, '/' ) . '/' . $filename. '.' . $fileParts['extension'];
			$targetThumb2 = rtrim ($targetThumbnail2, '/' ) . '/' . $filename. '.' . $fileParts['extension'];
			$targetThumb3 = rtrim ($targetThumbnail3, '/' ) . '/' . $filename. '.' . $fileParts['extension'];

			if ($extension == "jpg" || $extension == "jpeg" || $extension == "JPG" || $extension == "JPEG") 
			{
            	$src = imagecreatefromjpeg ( $tempFile );
	        } 
	        else if ($extension == "png" || $extension == "PNG") 
	        {
	            $src = imagecreatefrompng ( $tempFile );
	        } 
	        else if ($extension == "gif") {
	            $src = imagecreatefromgif ( $tempFile );
	        }

			list ( $width, $height ) = getimagesize ( $tempFile);

			$newwidth = 80;
	        $newheight = ($height / $width) * $newwidth;
	        $tmp = imagecreatetruecolor ( $newwidth, $newheight );

	        imagecopyresampled ( $tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );

	        $newwidth2 = 41;
	        $newheight2 = 41;
	        $tmp2 = imagecreatetruecolor ( $newwidth2, $newheight2 );

	        imagecopyresampled ( $tmp2, $src, 0, 0, 0, 0, $newwidth2, $newheight2, $width, $height );

	        $newwidth3 = 150;
	        $newheight3 = ($height / $width) * $newwidth3;
	        $tmp3 = imagecreatetruecolor ( $newwidth3, $newheight3 );

	        imagecopyresampled ( $tmp3, $src, 0, 0, 0, 0, $newwidth3, $newheight3, $width, $height );

	        $thumbname = $targetThumb;
	        $thumbname2 = $targetThumb2;
	        $thumbname3 = $targetThumb3;

	        if (file_exists ( $thumbname )) {
	            unlink ( $thumbname );
	        }


	         imagejpeg ( $tmp, $thumbname, 100 );

	        if (file_exists ( $thumbname2 )) {
	            unlink ( $thumbname2 );
	        }


	         imagejpeg ( $tmp2, $thumbname2, 100 );

	        if (file_exists ( $thumbname3 )) {
	            unlink ( $thumbname3 );
	        }

	        imagejpeg ( $tmp3, $thumbname3, 100 );


	        imagedestroy ( $src );
	        imagedestroy ( $tmp );


			if(!in_array(strtolower($extension), $allowed)){
				echo '{"status":"error"}';
				exit;
			}

			if(move_uploaded_file($_FILES['upl']['tmp_name'], $targetFile)){
				echo '{"status":"berhasil", "filename" : "'.$filename . '.' . $fileParts['extension'].'"}';
				exit;
			}
		}

		echo '{"status":"error"}';
		exit;
	}

	public function upload_file_txt()
	{
		// A list of permitted file extensions

		$allowed = array('txt');

		if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
			$arr_filedata = pathinfo($_FILES['upl']['name']);
			$filename = $arr_filedata['filename'];

			$tempFile = $_FILES['upl']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] .config_item('file_ina_ori');
			

			$fileParts = pathinfo($_FILES['upl']['name']);
			$extension = $fileParts ['extension'];
			//force rename $targetFile

			// die_dump($extension);
			$targetFile = rtrim($targetPath,'/') . '/' . $filename . '.' . $fileParts['extension'];
			

			if(!in_array(strtolower($extension), $allowed)){
				echo '{"status":"error"}';
				exit;
			}

			if(move_uploaded_file($_FILES['upl']['tmp_name'], $targetFile)){
				echo '{"status":"berhasil", "filename" : "'.$filename . '.' . $fileParts['extension'].'"}';
				exit;
			}
		}

		echo '{"status":"error"}';
		exit;
	}

	public function upload_file_csv()
	{
		// A list of permitted file extensions

		$allowed = array('csv');

		if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
			$arr_filedata = pathinfo($_FILES['upl']['name']);
			$filename = $arr_filedata['filename'];

			$tempFile = $_FILES['upl']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] .config_item('file_sep_ina_ori');
			

			$fileParts = pathinfo($_FILES['upl']['name']);
			$extension = $fileParts ['extension'];
			//force rename $targetFile

			// die_dump($extension);
			$targetFile = rtrim($targetPath,'/') . '/' . $filename . '.' . $fileParts['extension'];
			

			if(!in_array(strtolower($extension), $allowed)){
				echo '{"status":"error"}';
				exit;
			}

			if(move_uploaded_file($_FILES['upl']['tmp_name'], $targetFile)){
				echo '{"status":"berhasil", "filename" : "'.$filename . '.' . $fileParts['extension'].'"}';
				exit;
			}
		}

		echo '{"status":"error"}';
		exit;
	}

	public function session()
	{
		$this->load->view('session');
	}
}