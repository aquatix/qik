<?php
/**
 * file: mod_admin.php
 * v0.1.01 2007-09-09
 * Module with supporting functions for the admin function of the Qik framework
 * Copyright 2007-2007 mbscholt at aquariusoft.org
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


function getGalleries($skel)
{
	$dirpath = 'site/' . getLanguageKey($skel) . 'gallery/';

	$dh = opendir($dirpath);

	$result = array();
	$counter = 0;

	while (false !== ($file = readdir($dh)))
	{
		//Don't list subdirectories
		if (!is_dir("$dirpath/$file"))
		{
			//$result[$counter] = str_replace('.desc', '', $file) . '=' . $file . "\n";
			$result[$counter] = str_replace('.desc', '', $file);
			$counter++;
		}
	}
	closedir($dh);

	return $result;

}

function buildGalleryOverview($skel)
{
	$galleries = getGalleries($skel);
	//print_r($galleries);
	$result = "<ul>\n";
	
		for ($i = 0; $i < count($galleries); $i++)
	{
		$result .= "\t<li><a href=\"" . $skel['base_uri'] . 'admin/gallery/' . getLanguageKey($skel) . $galleries[$i] . "\">" . $galleries[$i] . "</a></li>\n";
}
	$result .= "</ul>\n";
	return $result;
}

?>
