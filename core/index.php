<?php

/**
 * index.php Main file for the Qik framework
 *
 * Qik is the legal property of its developer, Michiel Scholten
 * [mbscholtNOSPAM@aquariusoft.org]
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
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


/*** Initializing ***/

$skel['version'] = '0.1.17 2006-05-28';
$skel['starttime'] = microtime();

//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );	// set all on
error_reporting( E_ALL );
//error_reporting(0);	// set all off

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


$skel['base_uri'] = dirname($_SERVER['PHP_SELF']) . '/';
if ('//' == $skel['base_uri'])
{
	/* Site is located in the root, compensate for the extra slash */
	$skel['base_uri'] = '/';
}
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

/* User should provide the site/pagetemplate.php */

$section = getRequestParam('section', null);
$page = getRequestParam('page', null);
$action = getRequestParam('action', null);


/*
 * rewrite http://example.com/?section=w00t&page=blah to
 * http://example.com/page/w00t/blah/
 */
if (isset($url_pieces['query']) && '' != $url_pieces['query'])
{
	/* Redirect. Check for url injections */
	header('HTTP/1.1 301 Moved Permanently');
	$redir = $skel['base_server'] . $skel['base_uri'] . 'page/' . $section . '/';
	if ($section == null || $section == '')
	{
		$redir = $skel['base_server'] . '/';
	} else if ($page != NULL)
	{
		$redir .= $page . '/';
	}
	header('Location: ' . $redir);
	addToLog($skel, $section, $page, 301);
	exit;
} else if ($url_pieces['path'] == '/page/')
{
	header('HTTP/1.1 301 Moved Permanently');
	$redir = $skel['base_server'] . '/';
	header('Location: ' . $redir);
	addToLog($skel, '', '', 301);
	exit;
}

if ($section == 'error')
{
	$navbar = buildNav($skel, $sections);
	if ($page == '404')
	{
		addToLog($skel, $section, $page, 404);
		echo build404($skel, $navbar);
		/*} else if ($page == '401')
		  {
		 */
	} else {
		addToLog($skel, $section, $page, 500);
		echo buildHTTPErrorPage($skel, $navbar, $page);
	}
	exit;
}

if ('sitemap' == $action)
{
	$body = '<h1>' . dict($skel, 'sitemap') . "</h1>\n";
	$body .= buildSitemap($skel, $sections);
	addToLog($skel, 'special', 'sitemap', 200);
	$page_name = dict($skel, 'sitemap');
	$subnav = null;
	$skel['section'] = 'sitemap';
	//$skel['sectionname'] = $page_name;
} else if ('viewlog' == $action)
{
	$logaction = getRequestParam('logaction', 'pages');
	$offset = getRequestParam('offset', 0);
	$body = '<h1>' . dict($skel, 'visitslog') . "</h1>\n";

	$skel['section'] = 'viewlog';
	$subsections = getLogTypes($skel);
	$subnav = buildSubnav($skel, 'viewlog', $subsections);
	$page_name = dict($skel, 'visitslog');
	$skel['sectionname'] = $page_name;

	$date = getRequestParam('date', null);
	$body .= buildVisitsLogOverview($skel, $logaction, $offset, $date);
	addToLog($skel, 'special', 'viewlog', 200);
} else
{
	if ($section == null)
	{
		$section = getItem($sections, 0);
	}

	$section_name = getName($sections, $section);
	if ($section_name == null)
	{
		addToLog($skel, $section, $page, 404);
		echo build404($skel, $navbar, dict($skel, 'section_not_found'));
		exit;
	}

	$skel['section'] = $section;
	$skel['sectionname'] = $section_name;

	$subsections = getSubsections($skel, $section);
	if (null == $page)
	{
		/* If no page is specified, get first page in the sub category list */
		$page = getItem($subsections, 0);
	}

	$skel['page'] = $page;

	$subnav = null;
	if (null != $subsections)
	{
		$subnav = buildSubnav($skel, $section, $subsections);
	}

	$content = getFileContents($skel, $section, $page);
	if (null == $content)
	{
		addToLog($skel, $section, $page, 404);
		echo build404($skel, $navbar, dict($skel, 'page_x_not_found', $page));
		exit;
	}

	$page_name = '';
	if ($page != null)
	{
		$page_name = getName($subsections, $page);
	} else
	{
		$page_name = $section_name;
	}

	$body = '';
	if (trim($page_name) != '')
	{
		$body = '<h1>' . $page_name . "</h1>\n";
	}
	$body .= $content;

	/* Rebuild navbar, now with highlighted section */
	//$navbar = buildNav($skel, $sections);

	addToLog($skel, $section, $page, 200);
}

$navbar = buildNav($skel, $sections);
echo processTags($skel, buildPage($skel, $page_name, $navbar, $subnav, $body));
?>
