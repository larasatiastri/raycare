<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
//TODO : change targetFolder if folder name changes


//=====Raymond Code - don't change !!!=====
$targetFolder = '/maestrobyte/raycaretahap2/assets/mb/pages/klinik_hd/transaksi_dokter/images/temp/'; // Relative to the root
//$thumbsFolder = '/maestrobyte/raycaretahap2/assets/mb/pages/master/transaksi_dokter/images/thumbnail/';
if($_POST['type'] == "logos" || $_POST['type'] == "thumbnail")
{
	$fileTypes = array('jpg','jpeg','png'); // File extensions
	$filename = md5(time().uniqid());
}
elseif($_POST['type'] == "dokumen" || $_POST['type'] == "opportunity_item_file")
{
	$fileTypes = array();	// No Restriction
	$arr_filedata = pathinfo($_FILES['Filedata']['name']);
	//$filename = $arr_filedata['filename'];
	$filename = $_POST['nama_dokumen']."_".time().uniqid()."_".escapestring($arr_filedata['filename']);
}

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] .$targetFolder;
	//$targetThumbnail = $_SERVER['DOCUMENT_ROOT'] .$thumbsFolder;
	// $targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	//$fileTypes = array('jpg','jpeg','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$extension = $fileParts ['extension'];
	//force rename $targetFile
	$targetFile = rtrim($targetPath,'/') . '/' . $filename . '.' . $fileParts['extension'];
	//$targetThumb = rtrim ($targetThumbnail, '/' ) . '/' . $filename. '.' . $fileParts['extension'];
	
	if (empty($fileTypes) || in_array($fileParts['extension'],$fileTypes)) {
		if($_POST['type'] == "logos" || $_POST['type'] == "thumbnail")
{
		if ($extension == "jpg" || $extension == "jpeg") {
            $src = imagecreatefromjpeg ( $tempFile );
        } else if ($extension == "png") {
            $src = imagecreatefrompng ( $tempFile );
        } else {
            $src = imagecreatefromgif ( $tempFile );
        }

        list ( $width, $height ) = getimagesize ( $tempFile );

        $newwidth = 80;
        $newheight = ($height / $width) * $newwidth;
        $tmp = imagecreatetruecolor ( $newwidth, $newheight );

        imagecopyresampled ( $tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );

        $thumbname = $targetThumb;

        if (file_exists ( $thumbname )) {
            unlink ( $thumbname );
        }


         imagejpeg ( $tmp, $thumbname, 100 );

        imagedestroy ( $src );
        imagedestroy ( $tmp );

        move_uploaded_file($tempFile, $targetFile);
		//echo $filename.'.'.$fileParts['extension'];
		//$data=array($filename.'.'.$fileParts['extension'],$fileParts['extension']);
		echo $filename.'.'.$fileParts['extension'].'%%__%%'.$fileParts['extension'];
		//echo $data;
}else{
		move_uploaded_file($tempFile, $targetFile);
		//echo $filename.'.'.$fileParts['extension'];
		//$data=array($filename.'.'.$fileParts['extension'],$fileParts['extension']);
		echo $filename.'.'.$fileParts['extension'].'%%__%%'.$fileParts['extension'];

}
		
	} else {
		echo 'Invalid file type.';
	}
}

function escapestring($string){
	$karakter=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","1","2","3","4","5","6","7","8","9");

	$strlen = strlen($string);
	$id = "";
	for($y=0;$y <=count($karakter) - 1;$y++){
	for($i = 0; $i <= $strlen; $i++ ) {
    		$char = substr($string,$i,1);
    		 	
				if ($karakter[$y]==$char){
					$id .= $char;
				}else{
					$id .= "";
				}
		 
    			
		}
	}
	return $id;
}
?>