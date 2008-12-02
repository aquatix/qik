<?php
/**
 * mod_admin.php - Module with supporting functions for the admin function of the Qik framework
 * $Id$
 * v0.2.02 2008-12-02
 * Copyright 2007-2008 mbscholt at aquariusoft.org
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


function getFileItems($dirpath)
{
	$dh = opendir($dirpath);

	$result = array();
	$counter = 0;

	while (false !== ($file = readdir($dh)))
	{
		//Don't list subdirectories
		if (!is_dir("$dirpath/$file"))
		{
			$result[$counter] = str_replace('.desc', '', $file);
			$counter++;
		}
	}
	closedir($dh);

	return $result;
}


function buildItemsOverview($skel, $items, $kind)
{
	$result = "<ul>\n";
	
	for ($i = 0; $i < count($items); $i++)
	{
		$result .= "\t<li><a href=\"" . $skel['base_uri'] . 'admin/' . $kind . '/' . getLanguageKey($skel) . $items[$i] . "\">" . $items[$i] . "</a></li>\n";
	}
	$result .= "</ul>\n";
	return $result;
}


function getGalleries($skel)
{
	$dirpath = 'site/' . getLanguageKey($skel) . 'gallery/';
	return getFileItems($dirpath);
}


function buildGalleryOverview($skel)
{
	return buildItemsOverview($skel, getGalleries($skel), 'gallery');
}

function getNewsfiles($skel)
{
	$dirpath = 'site/' . getLanguageKey($skel) . 'news/';
	return getFileItems($dirpath);
}

function buildNewsOverview($skel)
{
	return buildItemsOverview($skel, getNewsFiles($skel), 'news');
}

?>
