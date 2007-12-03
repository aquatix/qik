<?php
/**
 * index.php - Qik admin module for changing texts, and maybe later even edit sections and such
 * v0.2.01 2007-09-09
 * Copyright 2005-2007 mbscholt at aquariusoft.org
 *
 * Qik is the legal property of its developer, Michiel Scholten
 * [mbscholt at aquariusoft.org]
 * Please refer to the COPYRIGHT file distributed with this source distribution.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Library General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


/* Initialise framework */

//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );  // set all on
error_reporting( E_ALL );
//error_reporting(0);     // set all off

$skel['version'] = '0.1.04 2007-09-09';

$skel['base_dir'] = dirname(dirname(__FILE__));
$skel['base_uri_mask'] = 'admin/'; /* We are in a subdir, so the framework needs to know that */

require_once($skel['base_dir'] . '/modules/mod_framework.php');
require_once('config_admin.php');
require_once('modules/dictionary_admin.php');
require_once('modules/mod_admin.php');

$options = array(
	'overview=' . dict($skel, 'admin_home'),
	'logout=Log out'
);

/* Page initialisation */
$page_name = 'Admin';
//$subnav = null;
$skel['sectionname'] = 'admin';
$subnav = buildAdminSubnav($skel, 'edit', $options);

$action = getRequestParam('action', null);

/* session check */
if (isset($_REQUEST[$skel['admin_sessionname']]))
{
	/* already logged in, resume session */
	session_name($skel['admin_sessionname']);
	session_start();
}

$body = '';

if ('login' == $action)
{
	/* Try to log in */
		/* Try to log in */
		$user = getRequestParam('user', null);
		$pass = getRequestParam('pass', null);

		if ($user == $skel['admin_user'] && $pass == $skel['admin_pass'])
		{
			/* Login successfull! */
			/* Start new session */
			session_name($skel['admin_sessionname']);
			session_start();

			$_SESSION['username'] = $user;
			$action = 'editoverview';
		} else
		{
			//$body .= "<h1>Error!</h1>\n<p>Not a valid user/pass combo!</p>\n<p><a href=\"root.php\">Go back</a></p>\n<br /><br /><br /><br />\n";
			$body .= "<h1>Sorry</h1><em>Not a valid user/pass combo!</em>\n";
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
	$body .= "<h1>Admin - " . dict($skel, 'admin_loggedout') . "</h1>\n<p><a href=\"./\">" . dict($skel, 'admin_backtologin') . "</a></p>\n<br/><br/><br/><br/>";

}

if ('editoverview' == $action && isLoggedIn())
{
	$body  = '<h1>Admin - ' . dict($skel, 'admin_home') . '</h1>';

	$body .= '<h2>' . dict($skel, 'admin_pages') . '</h2>';
	$body .= '<p>' . dict($skel, 'admin_editpage_explanation') . "</p>\n";

	$body .= buildSitemap($skel, $sections, 'admin/edit', true);

	$body .= '<h2>' . dict($skel, 'admin_galleries') . '</h2>';
	$body .= buildGalleryOverview($skel);

} else if ('editpage' == $action && isLoggedIn())
{
	$body  = '<h1>Admin - ' . dict($skel, 'admin_editpage') . '</h1>';

	//$body .= '<p><a href="' . $skel['base_uri'] . $skel['base_uri_mask'] . 'edit/">' . dict($skel, 'admin_back2overview') . '</a></p>';

	$section = getRequestParam('section', null);
	$page = getRequestParam('page', null);

	if (isset($_POST['savebtn']))
	{
		$filename = getHTMLFilename($skel, $section, $page);
		$body .= '<p><em>' . dict($skel, 'admin_savedpage') . ': ' . $filename . '</em></p>';
		$pagecontent = str_replace("\r\n", "\n", getRequestParam('pagecontent', null));
		//echo '|||' . $pagecontent . '|||';
		$file = fopen($skel['base_dir'] . '/' . $filename, "w");
		fputs($file, $pagecontent);
		fclose($file);
	}

	$body .= '<p>' . dict($skel, 'admin_editingpage') . ': ' . $section . '/' . $page . "</p>\n";

	$content = getHTMLFileContents($skel, $section, $page);
	//$content = str_replace('@', '&#64;', $content);
	$body .= '<form action="" method="post">';
	$body .= '<textarea name="pagecontent" rows="40" cols="100">' . str_replace('@', '&#64;', htmlentities($content)) . '</textarea>';
	$body .= '<p><input type="submit" name="savebtn" value="' . dict($skel, 'save') . '" /></p>';
	$body .= '</form>';
} else if ('editgallery' == $action && isLoggedIn())
{

	$body .= '<h1>Admin - ' . dict($skel, 'admin_editgallery') . '</h1>';
	$gallery = getRequestParam('gallery', null);

	if (isset($_POST['savebtn']))
	{
		$filename = getFilesname($skel, 'gallery', $gallery);
		$body .= '<p><em>' . dict($skel, 'admin_savedgallery') . ': ' . $filename . '</em></p>';
		$pagecontent = str_replace("\r\n", "\n", getRequestParam('pagecontent', null));
		$file = fopen($skel['base_dir'] . '/' . $filename, "w");
		fputs($file, $pagecontent);
		fclose($file);
		/* rm all thumbnails; they are regenerated on next view of the gallery */
		cleanThumbs($skel, str_replace('.desc', '', $gallery));
	}

	$body .= '<p>' . dict($skel, 'admin_editinggallery') . ': ' . $gallery . '</p>';
	$body .= '<p>' . dict($skel, 'admin_editinggallery_explanation') . '</p>';
	$content = getFileContents($skel, 'gallery', $gallery);
	$body .= '<form action="" method="post">';
	$body .= '<textarea name="pagecontent" rows="40" cols="100" wrap="off">' . str_replace('@', '&#64;', htmlentities($content)) . '</textarea>';
	$body .= '<p><input type="submit" name="savebtn" value="' . dict($skel, 'save') . '" /></p>';
	$body .= '</form>';
} else
{
	$body .= '<h1>Admin - ' . dict($skel, 'admin_login') . '</h1>';

	$body .= '<p>' . dict($skel, 'admin_welcome') . "</p>\n";
	$body .= '<div id="loginform"><form action="' . $skel['base_uri'] . $skel['base_uri_mask'] . 'edit/login/" method="post">';
	$body .= '<p><input type="text" name="user" size="16" maxlength="16" />&nbsp;<span class="heading">' . dict($skel, 'admin_username') . '</span></p>';
	$body .= '<p><input type="password" name="pass" size="16" maxlength="16" />&nbsp;<span class="heading">' . dict($skel, 'admin_password') . '</span><p>';
	$body .= '<input name="loginbtn" value="Login" type="submit" />';
	$body .= '</form></div>';
}

$navbar = buildNav($skel, $sections);
echo processTags($skel, buildPage($skel, $page_name, $navbar, $subnav, $body));
#echo buildPage($skel, $page_name, $navbar, $subnav, $body);

/*
 * Check whether there is a valid session
 */
function isLoggedIn()
{
	return (isset($_SESSION['username']));
}
?>
