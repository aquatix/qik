<?php
/**
 * $Id$
 * googlesitemap.php - Generates sitemap XML for Qik site framework
 * https://www.google.com/webmasters/tools/docs/en/protocol.html
 *
 * Copyright 2008-2008 mbscholt at aquariusoft.org
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

$skel['version'] = '0.2.01 2008-08-05';

$skel['base_dir'] = dirname(__FILE__);
include_once('modules/mod_framework.php');

//print_r($skel);

if (isset($skel['debugmode']) && true == $skel['debugmode'])
{
	//error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );	// set all on
	error_reporting( E_ALL );	// set all on
} else
{
	error_reporting(0);		// set all off, default
}
	error_reporting( E_ALL );	// set all on

$body  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$body .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
//$body .= "\t<url>\n";
//$body .= "\t\t<loc>http://www.example.com/</loc>\n";
//$body .= "\t\t<lastmod>2005-01-01</lastmod>\n";
//$body .= "\t\t<changefreq>monthly</changefreq>\n";
//$body .= "\t\t<priority>0.8</priority>\n";
//$body .= "\t</url>\n";

//echo $body;

//print_r ($sections);
$base='page';
$omitsitemap = true;
$result = '';
for ($i = 0; $i < count($sections); $i++)
{
	if ('' != trim($sections[$i]))
	{
		$key = getKey($sections[$i]);
		if ('#' != $key[0] && '' != getValue($sections[$i]))	// '#' denotes comment
		{
			if ('sitemap' != $key || ('sitemap' == $key && !$omitsitemap))
			{
				$result .= "\t<url>\n\t\t<loc>" . $skel['base_server'] . $skel['base_uri'] . $base . '/' . getLanguageKey($skel) . $key . "/</loc>\n\t\t<priority>1.0</priority>\n\t</url>\n";
			}

			if ('sitemap' == $key)
			{
				$subsections = false;
			} else
			{
				$subsections = file('site/' . getLanguageKey($skel) . 'sections/' . $key . '.desc');
			}
			if ($subsections != false)
			{
				for ($j = 0; $j < count($subsections); $j++)
				{
					if ('' != trim($subsections[$j]))
					{
						$subkey = getKey($subsections[$j]);
						if ('#' != $subkey[0] && '' != getValue($subsections[$j]))        // '#' denotes comment
						{
							$result .= "\t<url>\n\t\t<loc>" . $skel['base_server'] . $skel['base_uri'] . $base . '/' . getLanguageKey($skel) . $key . '/' . $subkey . "/</loc>\n\t\t<priority>0.5</priority>\n\t</url>\n";
						}
					}
				}
			}
		}
	}
}
$body .= $result;
$body .= "</urlset>\n";
echo $body;
?>
