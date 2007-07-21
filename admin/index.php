<?php
/*
 * Admin module for changing texts, and maybe later even edit sections and such
 */

/* Initialise */

//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );  // set all on
error_reporting( E_ALL );
//error_reporting(0);     // set all off

$skel['version'] = '0.1.01 2007-07-19';

$skel['base_dir'] = dirname(dirname(__FILE__));
$skel['base_uri_mask'] = 'admin/'; /* We are in a subdir, so the framework needs to know that */

require_once($skel['base_dir'] . '/modules/mod_framework.php');
require_once('modules/dictionary_admin.php');


/* Page initialisation */
$page_name = 'Admin';
$subnav = null;
//$skel['section'] = 'search';

$body  = '<h1>' . dict($skel, 'admin_home') . '</h1>';

$action = getRequestParam('action', null);

if ('login' == $action)
{
	/* Try to log in */
	if ((isLoggedIn() == false) && (isset($_POST['user']) && $_POST['user'] != '') && (isset($_POST['pass']) && $_POST['pass'] != ''))
	{
		/* Try to log in */
		$user = getRequestParam('user', null);
		$pass = getRequestParam('pass', null);
		$userid = login($skel, $user, $pass);
		if ( $userid > 0)
		{
			/* Login successfull! */
			/* Start new session */
			session_name($skel['session_name']);
			session_start();

			$_SESSION['username'] = $user;
			$_SESSION['userid'] = $userid;
		} else
		{
			$page_body .= "<h1>Error!</h1>\n<p>Not a valid user/pass combo!</p>\n<p><a href=\"root.php\">Go back</a></p>\n<br /><br /><br /><br />\n";
			include "inc/inc_pagetemplate.php";
			exit;
		}
	}

} else if ( 'logout' == $action )
{   
	/* user wants to log out */
	unset($_SESSION['username']);
	/* Destroy session vars */
	$_SESSION = array();
	session_destroy();
	$user_name = null;
	$user_pass = null;
	$body .= "<h1>" . dict($skel, 'admin_loggedout') . "</h1>\n<p><a href=\"./\">" . dict($skel, 'admin_backtologin') . "</a></p>\n<br/><br/><br/><br/>";

} else
{


	$body .= '<p>' . dict($skel, 'admin_welcome') . "</p>\n";
}
/*
   $body  = '<h1>' . dict($skel, 'search_homes') . '</h1>';
   $body .= '<form action="" method="post">';
   $body .= '<p><input type="text" name="query" value="' . $searchquery . '" /></p>';
   $body .= '<p>Price between <input type="text" name="pricelow" value="' . $pricerange_low . '" /> and <input type="text" name="pricehigh" value="' . $pricerange_high . '" /></p>';$body .= '<p><input type="submit" name="search" value="' . dict($skel, 'find') . '" /></p>';
   $body .= '</form>';

   $body .= "<h2>" . dict($skel, 'in_budget') . "</h2>\n";
//$body .= '<p>Koophuizen onder de &euro;100.000</p>';
 */
$skel['section'] = 'admin';

$navbar = buildNav($skel, $sections);
echo processTags($skel, buildPage($skel, $page_name, $navbar, $subnav, $body));

?>
