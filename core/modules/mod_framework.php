<?php
/**
 * Qik site framework setup
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
 * GNU Library General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$skel['frameworkversion'] = '0.2.02 2008-10-19';
$skel['starttime'] = microtime();

chdir($skel['base_dir']);

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
/* Module for expanding tags like @@@tile=tilename@@@ to the actual tile file's contents */
include_once('modules/mod_tagsections.php');

/* Module for logging support */
include_once('modules/mod_logging.php');

$language = validateLanguage(getRequestParam('language', null));
/*
if ('' == $language)
{
	$language = $skel['language'];
} else
{
	$skel['language'] = $language;
}
*/
if ('' != $language)
{
	$skel['language'] = $language;
	$skel['baselanguage'] = $language;
}

include_once('site/' . getLanguageKey($skel) . 'pagetemplate.php');

$skel['base_uri'] = dirname($_SERVER['PHP_SELF']) . '/';
if ('//' == $skel['base_uri'])
{
	/* Site is located in the root, compensate for the extra slash */
	$skel['base_uri'] = '/';
}
if (isset($skel['base_uri_mask']))
{
	//$skel['base_uri'] = substr($skel['base_uri'], strlen($skel['base_uri_mask']));
	$skel['base_uri'] = str_replace($skel['base_uri_mask'], '', $skel['base_uri']);
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
	echo 'Qik error: Can\'t read site description file!';
	exit;
}
$sections[count($sections)] = 'sitemap=' . dict($skel, 'sitemap');


?>
