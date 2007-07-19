<?php
/*** Initializing ***/

$skel['version'] = '0.1.18 2006-06-10';
$skel['starttime'] = microtime();

//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );  // set all on
error_reporting( E_ALL );
//error_reporting(0);     // set all off

//chdir('../');
chdir('/var/www');
/* Website configuration */
include_once('config.php');
/* Dictionary for the messages used by the framework */
include_once('modules/dictionary.php');

if ($skel['usedb'])
{
	include_once('modules/mod_qik_db.php');
} else
{
	include_once('modules/mod_qik_files.php');
}

/* Module with generic helpful functions */
include_once('modules/mod_toolkit.php');
/* Module for actually building parts of the pages */
include_once('modules/mod_site.php');
/* Module for expanding tags like @@NEWS@@ to the actual news */
include_once('modules/mod_tagsections.php');

/* Module for logging support */
include_once('modules/mod_logging.php');

include_once('site/pagetemplate.php');

$skel['base_uri'] = dirname($_SERVER['PHP_SELF']) . '/../';
if ('//' == $skel['base_uri'])
{
	/* Site is located in the root, compensate for the extra slash */
	$skel['base_uri'] = '/';
}
$skel['base_uri'] = '/';
$url_pieces = parse_url(getenv('SCRIPT_URI'));
$skel['base_server'] = '';
if (!isset($url_pieces['scheme']))
{
	//$url_pieces = parse_url($_SERVER['SCRIPT_URI']);
	$skel['base_server'] = 'http://' . $_SERVER['SERVER_NAME'];
} else
{
	$skel['base_server'] = $url_pieces['scheme'] . '://' . $url_pieces['host'];
}

$sections = getSections($skel);
if (null == $sections)
{
	echo 'Can\'t read site description file!';
	exit;
}
$sections[count($sections)] = 'sitemap=' . dict($skel, 'sitemap');

/*** Getting base stuff ***/


?>
