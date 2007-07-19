<?php
/*
 * Admin module for changing texts, and maybe later even edit sections and such
 */

/* Initialise */

//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );  // set all on
error_reporting( E_ALL );
//error_reporting(0);     // set all off

$skel['basedir'] = dirname(dirname(__FILE__));

require_once($skel['basedir'] . 'modules/mod_framework.php');
require_once('modules/dictionary_admin.php');


/* Page initialisation */
$page_name = 'Admin';
$subnav = null;
//$skel['section'] = 'search';

$action = getRequestParam('action', null);

if (null != $action)
{
	/* Do a search */
	//$homes = filterHomes($skel, $searchquery);
	$homes = filterHomes($skel, $pricerange_low, $pricerange_high);
	print_r($homes);
	//  
	//$result = doQuery($skel, $query);
} else
{


	echo "<h1>Admin</h1>\n<p>" . dict($skel, 'welcome_admin') . "</p>\n";
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
