<?php
/*
 * Admin module for changing texts, and maybe later even edit sections and such
 */

/* Initialise */
/*
$skel['qis_db_url'] = 'localhost';
$skel['qis_db_name'] = 'qis';
$skel['qis_db_user'] = 'qis';
$skel['qis_db_pass'] = 'qisuser';
*/
require_once('modules/setup_qik_framework.php');
require_once('modules/mod_db.php');
//require_once('modules/mod_qis_homes.php');
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
