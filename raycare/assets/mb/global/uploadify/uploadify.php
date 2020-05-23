<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
//TODO : change targetFolder if folder name changes
$targetFolder = '/maestrobyte/raycare_all/raycare/assets/mb/var/temp/'; // Relative to the root
$targetFolderDoc = '/maestrobyte/raycare_all/raycare/assets/mb/var/doc/'; // Relative to the root
$thumbsFolder = '/maestrobyte/raycare_all/raycare/assets/mb/var/temp/thumbs/';
$thumbsFolder2 = '/maestrobyte/raycare_all/raycare/assets/mb/var/temp/thumbs2/';
if($_POST['type'] == "logos" || $_POST['type'] == "thumbnail")
{
	$fileTypes = array('jpg','jpeg','png'); // File extensions
	$filename = md5(time().uniqid());
}
elseif($_POST['type'] == "dokumen_user" || $_POST['type'] == "dokumen_claim")
{
	$fileTypes = array();	// No Restriction
	$arr_filedata = pathinfo($_FILES['Filedata']['name']);
	//$filename = $arr_filedata['filename'];
	$filename = $arr_filedata['filename'];
}

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] .$targetFolder;
	$targetPathDoc = $_SERVER['DOCUMENT_ROOT'] .$targetFolderDoc;
	$targetThumbnail = $_SERVER['DOCUMENT_ROOT'] .$thumbsFolder;
	$targetThumbnail2 = $_SERVER['DOCUMENT_ROOT'] .$thumbsFolder2;
	// $targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	//$fileTypes = array('jpg','jpeg','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$extension = $fileParts ['extension'];
	//force rename $targetFile
	$targetFile = rtrim($targetPath,'/') . '/' . $filename . '.' . $fileParts['extension'];
	$targetFileDoc = rtrim($targetPathDoc,'/') . '/' . $filename . '.' . $fileParts['extension'];
	$targetThumb = rtrim ($targetThumbnail, '/' ) . '/' . $filename. '.' . $fileParts['extension'];
	$targetThumb2 = rtrim ($targetThumbnail2, '/' ) . '/' . $filename. '.' . $fileParts['extension'];
	
	if (empty($fileTypes) || in_array($fileParts['extension'],$fileTypes)) {

		if ($extension == "jpg" || $extension == "jpeg") {
            $src = imagecreatefromjpeg ( $tempFile );
        } else if ($extension == "png") {
            $src = imagecreatefrompng ( $tempFile );
        } else if ($extension == "gif") {
            $src = imagecreatefromgif ( $tempFile );
        } else {
        	move_uploaded_file($tempFile, $targetFileDoc);
			echo $filename.'.'.$fileParts['extension'];
        }

        list ( $width, $height ) = getimagesize ( $tempFile );

        $newwidth = 80;
        $newheight = ($height / $width) * $newwidth;
        $tmp = imagecreatetruecolor ( $newwidth, $newheight );

        imagecopyresampled ( $tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );

        $newwidth2 = 41;
        $newheight2 = 41;
        $tmp2 = imagecreatetruecolor ( $newwidth2, $newheight2 );

        imagecopyresampled ( $tmp2, $src, 0, 0, 0, 0, $newwidth2, $newheight2, $width, $height );

        $thumbname = $targetThumb;
        $thumbname2 = $targetThumb2;

        if (file_exists ( $thumbname )) {
            unlink ( $thumbname );
        }


         imagejpeg ( $tmp, $thumbname, 100 );

        if (file_exists ( $thumbname2 )) {
            unlink ( $thumbname2 );
        }


         imagejpeg ( $tmp2, $thumbname2, 100 );


        imagedestroy ( $src );
        imagedestroy ( $tmp );

		move_uploaded_file($tempFile, $targetFile);
		echo $filename.'.'.$fileParts['extension'];
	} else {
		
		echo 'Invalid file type.';
	}
}
?>