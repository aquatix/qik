<?php
/**
 * index.php - Main file for the Qik framework
 * $Id$
 *
 * Copyright 2005-2009 mbscholt at aquariusoft.org
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
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


/*** Initializing ***/

$skel['version'] = '0.2.08 2009-03-09';

$skel['base_dir'] = dirname(__FILE__);
include_once('modules/mod_framework.php');

if (isset($skel['debugmode']) && true == $skel['debugmode'])
{
	//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );	// set all on
	error_reporting( E_ALL );	// set all on
} else
{
	error_reporting(0);		// set all off, default
}

/* N.B.: User should provide the site/pagetemplate.php */

$section = getRequestParam('section', null);
$page = getRequestParam('page', null);
$action = getRequestParam('action', null);


/*
 * Rewrite http://example.com/?section=w00t&page=blah to
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

/* Should we show an error page? */
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

/* Now find out what page we should show */
if ('sitemap' == $action)
{
	$body = '<h1 class="first">' . dict($skel, 'sitemap') . "</h1>\n";
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
	$page_name = dict($skel, 'visitslog');
	$skel['sectionname'] = $page_name;
	$subnav = buildSubnav($skel, 'viewlog', $subsections);

	$date = getRequestParam('date', null);
	$body .= buildVisitsLogOverview($skel, $logaction, $offset, $date);
	addToLog($skel, 'special', 'viewlog', 200);
} else if ('viewgallery' == $action)
{
	$gallery = getRequestParam('gallery', null);
	$file = getRequestParam('file', -1);
	$galleryItems = getGallery($skel, $gallery);
	$body = buildGalleryPage($skel, $gallery, $galleryItems, $file);
	echo processTags($skel, $body);
	exit;
} else if ('makethumb' == $action || 'makehover' == $action)
{
	/* index.php was called to generate a thumbnail. In this mode it will only generate a picture, output it to stdout and save it */
	$kind = getRequestParam('kind', null);
	$filename = null;
	$special = '';
	if ('makehover' == $action)
	{
		$special = 'hover/';
	}
	if ('gallery' == $kind)
	{
		$gallery = getRequestParam('gallery', null);
		$file = getRequestParam('file', -1);
		$galleryItems = getItems($skel, 'gallery', $gallery . ':' . $file . ':' . $file);
		$filename = getValue($galleryItems[0]);
		if ('http' != substr($filename, 0, 4))
		{
			/* It's a local image */
			$filename = realpath(dirname(__FILE__)) . '/images/gallery/' . $filename;
		}
		$destfile = realpath(dirname(__FILE__)) . '/images/gallery/thumbs/' . $special . $gallery . '_' . $file . '.jpg';
	}
	header('Content-type: image/jpeg');
	if ('makehover' == $action)
	{
		makeThumbnail($filename, $skel['hoversize'], $destfile);
	} else
	{
		makeThumbnail($filename, $skel['thumbsize'], $destfile);
	}
	exit;
} else
{
	/* Find which page is the homepage; needed for example to decide which page gets the 3-column layout with the news column */
	$skel['home_section'] = getItem($sections, 0);
	$homesections = getSubsections($skel, $skel['home_section']);
	$skel['home_page'] = getItem($homesections, 0);

	if ($section == null)
	{
		$section = getItem($sections, 0);
	}

	$section_name = getName($sections, $section);
	if ($section_name == null)
	{
		addToLog($skel, $section, $page, 404);
		echo build404($skel, null, dict($skel, 'section_not_found'));
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

	$content = getHTMLFileContents($skel, $section, $page);
	if (null == $content)
	{
		addToLog($skel, $section, $page, 404);
		echo build404($skel, null, dict($skel, 'page_x_not_found', $page));
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
		$body = '<h1 class="first">' . $page_name . "</h1>\n";
	}
	$body .= $content;

	addToLog($skel, $section, $page, 200);
}

$navbar = buildNav($skel, $sections);
echo processTags($skel, buildPage($skel, strip_tags($page_name), $navbar, $subnav, $body));
?>
