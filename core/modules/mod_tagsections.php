<?php
/**
 * mod_tagsections.php - Module for expanding @@@key=value@@@-style tags
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

require_once('mod_forms.php');

if (!isset($skel['thumbsize']))
{
	$skel['thumbsize'] = 80;
}

function processTags($skel, $body)
{
	$body = expandTags($skel, $body);
	$body = str_replace('src="image', 'src="' . $skel['base_uri'] . 'image', $body);
	$body = str_replace('href="image', 'href="' . $skel['base_uri'] . 'image', $body);
	$body = str_replace('href="files', 'href="' . $skel['base_uri'] . 'files', $body);
	$body = str_replace('href="page', 'href="' . $skel['base_uri'] . 'page', $body);
	$body = str_replace('href="forms', 'href="' . $skel['base_uri'] . 'forms', $body);
	//$body = str_replace('href="viewimage', 'href="' . $skel['base_uri'] . 'viewimage', $body);
	return $body;
}


/*
 * expands tags in the form of @@@key=value@@@
 * if you want to show the tags in your pages [because you want to talk about them], use &#64; instead of @
 */
function expandTags($skel, $body)
{
	//print_r(getTagNames($body));
	//$body = stripTags($body);
	$result = '';
	$pieces = explode("@@@", $body);
	for ($i = 0; $i < count($pieces); $i++)
	{
		if ($i % 2 == 1)
		{
			$keyvalue = explode("=", $pieces[$i]);
			if ($keyvalue[0] == "tile")
			{
				$tile = getTile($skel, $keyvalue[1]);
				if ($tile != null)
				{
					$result .= expandTags($skel, $tile);
				} else
				{
					$result .= "<p>" . dict($skel, "tile_x_not_found", $keyvalue[1]) . "</p>\n";
				}
			} else if ($keyvalue[0] == "news")
			{
				$result .= expandTags($skel, getNews($skel, $keyvalue[1]));
			} else if ($keyvalue[0] == "icon")
			{
				$result .= getIcon($skel, $keyvalue[1]);
			} else if ($keyvalue[0] == "flag")
			{
				$result .= getFlag($skel, $keyvalue[1]);
			} else if ($keyvalue[0] == 'dict')
			{
				$result .= dict($skel, $keyvalue[1]);
			} else if ($keyvalue[0] == "gallery")
			{
				/* Get gallery name */
				$galleryname = null;
				$parts = explode(':', $keyvalue[1]);
				//$parts = explode(':', $key);
				if ('' != $parts[0])
				{
					$galleryname = $parts[0];
				}
				//$galleryitems .= getGallery($skel, $keyvalue[1]);
				$galleryitems = getGallery($skel, $galleryname);
				$result .= buildGallery($skel, $galleryname, $galleryitems);
			} else
			{
				$result .= "<p>" . dict($skel, "key_x_not_understood", $pieces[$i]) . "</p>\n";
			}
		} else
		{
			$result .= $pieces[$i];
		}
	}
	return $result;
}


/*
 * Returns an array with names of tags; e.g.,
 * "Hello @@@tile=name@@@, this is fun @@@help=title:1:5@@@"
 * will return array(0 => "tile=name", 1 => "news=title:1:5")
 */
function getTagNames($body)
{
	$tags = array();
	$pieces = explode("@@@", $body);
	$counter = 0;
	for ($i = 1; $i < count($pieces); $i++)
	{
		if ($i % 2 == 1)
		{
			$tags[$counter] = $pieces[$i];
			$counter++;
		}
	}
	return $tags;
}


function stripTags($body)
{
	$result = "";
	$pieces = explode("@@@", $body);
	for ($i = 0; $i < count($pieces); $i++)
	{
		if ($i % 2 == 0)
		{
			$result .= $pieces[$i] . "\n";
		}
	}
	return $result;
}


/*
 * Get all items in the datafile
 */
function getItems($skel, $kind, $key)
{
	$parts = explode(':', $key);

	if ('' != $parts[0])
	{
		$datafile = getFile($skel, $kind, $parts[0]);
		if ($datafile != null)
		{
			$items = '';
			/* Clean up array: skip empty lines and lines starting with # */
			$i = 0;
			while ($i < count($datafile))
			{
				if ( isset($datafile[$i]) && ( ('' == trim($datafile[$i])) || ('#' == substr(ltrim($datafile[$i]), 0, 1)) ) )
				{
					//unset($datafile[$i]);
					//$datafile[$i] = null;
					$datafile = removeArrayElement($datafile, $i);
				} else
				{
					$i++;
				}
			}
			/* Look whether we only want a slice of it */
			if (isset($parts[2]) && isset($parts[1]))
			{
				/* Only return the slice of items that where asked for */
				$offset = $parts[1] - 1;
				$nr = $parts[2] - $offset;
				$datafile = array_slice($datafile, $offset, $nr);
			} else if (isset($parts[1]))
			{
				$offset = $parts[1] - 1;
				$datafile = array_slice($datafile, $offset);
			}
			return $datafile;
		}

	}
	return "<p>" . dict($skel, $kind . "key_x_not_found", $key) . "</p>\n";
}


/*
 * Extract the news items
 * for the 'news' tag, the following is possible:
 * "news=filename:1:5": items 1 to 5 from news file filename
 * "news=filename": get all news items from filename
 * "news=filename:5": get all items from filename, beginning at item 5
 *
 * $key contains the "filename:1:5" part
 */
function getNews($skel, $key)
{
	$items = getItems($skel, 'news', $key);
	if (!is_array($items))
	{
		/* Something went wrong, return the message */
		return $items;
	}

	$result = '';
	for ($i = 0; $i < count($items); $i++)
	{
		if ('' != trim($items[$i]))
		{
			$title = getKey($items[$i]);
			$content = getValue($items[$i]);
			$result .= buildNewsItem($skel, trim($title), str_replace("\\n", "\n", str_replace("\\t", "\t", trim($content))));
		}
	}
	return $result;
}


function getIcon($skel, $key)
{
	// @TODO: check for existance of the file
	return "<img src=\"images/icons/" . $key . ".png\" alt=\"" . $key . "\" height=\"24\" width=\"24\" />";
}


function getFlag($skel, $key)
{
	// @TODO: check for existance of the file
	return "<img src=\"images/icons/flags/" . $key . ".png\" alt=\"" . $key . "\" height=\"11\" width=\"16\" />";
}


function getGallery($skel, $key)
{
	$items = getItems($skel, 'gallery', $key);
	if (!is_array($items))
	{
		/* Something went wrong, return the message */
		return $items;
	}

	/* Get gallery name */
	$galleryname = null;
	$parts = explode(':', $key);
	if ('' != $parts[0])
	{
		$galleryname = $parts[0];
	}

	//$galleryitems = new array();
	$imagecounter = 0;
	for ($i = 0; $i < count($items); $i++)
	{
		if ('' != trim($items[$i]))
		{
			$title = getKey($items[$i]);
			$file = getValue($items[$i]);
			if ('http' != substr($file, 0, 4))
			{
				$file = 'images/gallery/' . $file;
			}
			$filename = 'images/gallery/thumbs/' . $galleryname . '_' . ($i + 1) . '.jpg';
			$image_name = realpath(dirname(__FILE__)) . '/../images/gallery/thumbs/'. $galleryname . '_' . ($i + 1) . '.jpg';
			if(!file_exists($image_name)) 
			{
				$filename = $skel['base_uri'] . 'viewimage/gallery/thumb/' . $galleryname . '/' . ($i + 1) . '/';
			}
			$hoverfilename = 'images/gallery/thumbs/hover/' . $galleryname . '_' . ($i + 1) . '.jpg';
			$image_name = realpath(dirname(__FILE__)) . '/../images/gallery/thumbs/hover/'. $galleryname . '_' . ($i + 1) . '.jpg';
			if(!file_exists($image_name)) 
			{
				$hoverfilename = $skel['base_uri'] . 'viewimage/gallery/thumb/hover/' . $galleryname . '/' . ($i + 1) . '/';
			}
			$galleryitems[$imagecounter]['filename'] = $filename;
			$galleryitems[$imagecounter]['hoverfilename'] = $hoverfilename;
			$galleryitems[$imagecounter]['title'] = $title;
			$galleryitems[$imagecounter]['targetfilename'] = $file;
			$galleryitems[$imagecounter]['galleryitem'] = $skel['base_uri'] . 'gallery/' . $galleryname . '/' . ($i + 1) . '/';
			$imagecounter++;
		}
	}
	return $galleryitems;
}
?>
