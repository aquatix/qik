<?php
/*
 * Dictionary additions for Admin module
 */

if ($skel['language'] == 'nl')
{
	$skel['dict']['admin_welcome'] = 'Welkom op de Admin pagina. Hier kunt u teksten etc aanpassen.';
	$skel['dict']['admin_home'] = 'Admin - overzicht';
	$skel['dict']['admin_loggedout'] = 'Uitgelogged';
	$skel['dict']['admin_backtologin'] = 'Go back to the login page';

	//$skel['dict'][''] = '';
} else
{
	/* default to en */
	$skel['dict']['admin_welcome'] = 'Welcome to the Admin page. Here you can edit texts etc.';
	$skel['dict']['admin_home'] = 'Admin - overview';
	$skel['dict']['admin_loggedout'] = 'Logged out';
	$skel['dict']['admin_backtologin'] = 'Go back to the login page';

	//$skel['dict'][''] = '';
	//$skel['dict']['gallerykey_x_not_found'] = 'ERROR: gallery "%s" not found';
}
?>

