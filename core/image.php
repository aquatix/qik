<?php
include_once('config.php');
include_once('modules/mod_toolkit.php');
include_once('modules/mod_site.php');

error_reporting( E_ALL );

$action = getRequestParam('action', null);
$kind = getRequestParam('kind', null);
$filename = getRequestParam('filename', null);

if ('makethumb' == $action)
{
	if ('gallery' == $kind)
	{
		$destfile = str_replace('/', '_', $filename);
		$filename = realpath(dirname(__FILE__)) . '/images/gallery/' . $filename;
		$destfile = realpath(dirname(__FILE__)) . '/images/gallery/thumbs/'. $destfile;
	}
	header("Content-type: image/jpeg");
	makeThumbnail($filename, 80, $destfile);
	//makeThumbnail($filename, 80);
}

?>
