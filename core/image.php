<?php

include_once('config.php');
include_once('modules/mod_toolkit.php');
include_once('modules/mod_site.php');
include_once('modules/mod_qik_files.php');
include_once('modules/mod_tagsections.php');
/*
include_once('setup_qik_framework.php');
*/

error_reporting( E_ALL );

$action = getRequestParam('action', null);
$kind = getRequestParam('kind', null);
//$filename = getRequestParam('filename', null);

if ('makethumb' == $action)
{
	/*
	if ('gallery' == $kind)
	{
		$destfile = str_replace('/', '_', $filename);
		$filename = realpath(dirname(__FILE__)) . '/images/gallery/' . $filename;
		$destfile = realpath(dirname(__FILE__)) . '/images/gallery/thumbs/'. $destfile;
	}
	*/
	if ('gallery' == $kind)
	{
		$gallery = getRequestParam('gallery', null);
		$file = getRequestParam('file', -1);
		$galleryItems = getGallery($skel, $gallery . ':' . $file . ':1');
		print_r($galleryItems);
	}
	header("Content-type: image/jpeg");
	makeThumbnail($filename, 80, $destfile);
	//makeThumbnail($filename, 80);
}

?>
