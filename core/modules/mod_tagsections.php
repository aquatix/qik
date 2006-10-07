<?php
/*
 * file: mod_tagsections.php
 *       v0.1.11 2006-09-18
 * Copyright 2005-2006 mbscholt at aquariusoft.org
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor Boston, MA 02110-1301,  USA

 */

function processTags($skel, $body)
{
	$body = expandTags($skel, $body);
	$body = str_replace("src=\"image", "src=\"" . $skel["base_uri"] . "image", $body);
	$body = str_replace("href=\"image", "href=\"" . $skel["base_uri"] . "image", $body);
	$body = str_replace("href=\"files", "href=\"" . $skel["base_uri"] . "files", $body);
	$body = str_replace("href=\"page", "href=\"" . $skel["base_uri"] . "page", $body);
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
			} else if ($keyvalue[0] == "gallery")
			{
				$result .= getGallery($skel, $keyvalue[1]);
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
					unset($datafile[$i]);
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
			$result .= "<div class=\"news\">\n";
			$result .= "\t<div class=\"date\">" . trim($title) . "</div>";
			$result .= "<div class=\"newscontent\">" . str_replace("\\n", "\n", str_replace("\\t", "\t", trim($content))) . "</div>\n</div>\n\n";
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


function getGallery_old($skel, $key)
{
	$items = getItems($skel, "gallery", $key);
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
			$file = getValue($items[$i]);
			//$result .= "<img src=\"images/gallery/" . $file . "\" alt=\"" . $title . "\" /><br />\n";
			$result .= "<img src=\"images/gallery/" . $file . "\" alt=\"" . $title . "\" />\n<p><em>" . $title . "</em></p>\n";
		}
	}
	return $result;
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

	$result = '';
	for ($i = 0; $i < count($items); $i++)
	{
		if ('' != trim($items[$i]))
		{
			$title = getKey($items[$i]);
			$file = getValue($items[$i]);
			if ('http' != substr($file, 0, 4))
			{
				$file .= 'images/gallery/';
			}
			//$result .= "<img src=\"images/gallery/" . $file . "\" alt=\"" . $title . "\" /><br />\n";
			//$filename = 'images/gallery/thumbs/' . str_replace('/', '_', $file);
			//$image_name = realpath(dirname(__FILE__) . '/../') . '/' . $filename;
			$filename = 'images/gallery/thumbs/' . $galleryname . '_' . ($i + 1) . '.jpg';
			$image_name = realpath(dirname(__FILE__)) . '/../images/gallery/thumbs/'. $galleryname . '_' . ($i + 1) . '.jpg';
			if(!file_exists($image_name)) 
			{
				//$filename = $skel['base_uri'] . 'viewimage/gallery/thumb/' . $file;
				$filename = $skel['base_uri'] . 'viewimage/gallery/thumb/' . $galleryname . '/' . ($i + 1) . '/';
			}
			$result .= "<div class=\"galleryimage\"><a href=\"" . $file . "\"><img src=\"" . $filename . "\" alt=\"" . $title . "\" /></a>\n<p><em>" . $title . "</em></p>\n</div>\n";
		}
	}
	//$result = str_replace('href="viewimage', 'href="' . $skel['base_uri'] . 'viewimage', $result);
	return $result;
}

?>
