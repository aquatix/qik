<?php
/**
 * Module for building most of the various content of the Qik site
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


function build404($skel, $navbar, $message = null)
{
	header("HTTP/1.0 404 Not Found");
	$body = "<h1>" . dict($skel, "404_header") . "</h1>\n<p>" . dict($skel, "404") . ". <a href=\"" . $skel["base_uri"] . "\">" . dict($skel, "gohome") . "</a> / <a href=\"" . $skel["base_uri"] . "page/sitemap/\">" . dict($skel, "gositemap") . "</a></p>\n";
	if (isset($skel["404_message"]) && $skel["404_message"] != "")
	{
		$body .= $skel["404_message"] . "\n";
	}
	if ($message != null)
	{
		$body .= "<p>" . dict($skel, "errormessage") . ": " . $message . "</p>\n";
	}
	return processTags($skel, buildPage($skel, dict($skel, "404"), $navbar, "", $body));
}


function buildHTTPErrorPage($skel, $navbar, $code, $message = null)
{
	$longcode = getHTTPErrorCode($code);
	if ($longcode != null)
	{
		header($longcode);
		$title = substr($longcode, strlen("HTTP/1.0 "));
		$body = "<h1>" . $title . "</h1>\n";
		if ($code == "403")
		{
			$body .= "<p>" . dict($skel, "403") . "</p>\n";
		} else if ($code == "401")
		{
			$body .= "<p>" . dict($skel, "401") . "</p>\n";
		} else
		{
			$body .= "<p>" . dict($skel, "errormessage") . ": " . dict($skel, "http_error") . "</p>\n";
		}

		return processTags($skel, buildPage($skel, $title, $navbar, "", $body));
	}
}


/*
 * Look up a given key in an array and return its value
 */
function getName($items, $id)
{
	for ($i = 0; $i < count($items); $i++)
	{
		if ('' != trim($items[$i]))
		{
			if (getKey($items[$i]) == $id)
			{
				return getValue($items[$i]);
			}
		}
	}
	return null;
}


/*
 * Get left part of a key=value pair
 */
function getKey($item)
{
	$parts = explode("=", $item);
	return trim($parts[0]);
}


/*
 * Get right part of a key=value pair
 */
function getValue($item)
{
	$parts = explode("=", $item);
	$value = array_slice($parts, 1);
	return trim(implode("=", $value));
}


function buildSitemap($skel, $sections, $base='page', $omitsitemap=false)
{
	$result = '';
	if (!$omitsitemap)
	{
		$result = '<div class="updatedat">generated @ ' . date('Y-m-d') . "</div>\n";
	}
	$result .= buildNavList($skel, $sections, $base, $omitsitemap, 'sitemap');

	return $result;
}


function buildNavList($skel, $sections, $base='page', $omitsitemap=false, $listname = '')
{
	if ('' != $listname)
	{
		$listname = ' id="' . $listname . '"';
	}
	$result = "<ul" . $listname . ">\n";

	for ($i = 0; $i < count($sections); $i++)
	{
		if ('' != trim($sections[$i]))
		{
			$key = getKey($sections[$i]);
			if ('#' != $key[0] && '' != getValue($sections[$i]))	// '#' denotes comment
			{
				if ('sitemap' != $key || ('sitemap' == $key && !$omitsitemap))
				{
					$result .= "\t<li><a href=\"" . $skel['base_uri'] . $base . '/' . getLanguageKey($skel) . $key . "/\">" . getValue($sections[$i]) . "</a>\n";
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
					$result .= "\t\t<ul>\n";
					for ($j = 0; $j < count($subsections); $j++)
					{
						if ('' != trim($subsections[$j]))
						{
							$subkey = getKey($subsections[$j]);
							if ('#' != $subkey[0] && '' != getValue($subsections[$j]))        // '#' denotes comment
							{
								$result .= "\t\t\t<li><a href=\"" . $skel['base_uri'] . $base . '/' . getLanguageKey($skel) . $key . '/' . $subkey . '/">' . getValue($subsections[$j]) . "</a></li>\n";
							}
						}
					}
					$result .= "\t\t</ul>\n\t</li>\n";
				}
			}
		}
	}
	$result .= "</ul>\n";

	return $result;
}


function buildVisitsLogOverview($skel, $action = 'pages', $offset = 0, $date = null)
{
	$result = '<div class="updatedat">generated @ ' . date('Y-m-d') . "</div>\n";
	$nrOfHits = getNumberOfHits($skel);
	$number = 50;

	$nrOfItems = $nrOfHits;
	if ('ips' == $action)
	{
		$uniqueIPs = getUniqueIPs($skel);
		$nrOfItems = count($uniqueIPs);
	} else if ('pages' == $action)
	{
		$pages = getHitsPerPage($skel, $offset, $number);
		$nrOfItems = count($pages);
	} else if ('hitsperdate' == $action)
	{
		if (null == $date)
		{
			$date = date("Ymd", time());
		}
		if (8 == strlen($date) && intval($date) == $date)
		{
			$date = parseDate($date, '%Y%m%d');
			$pages = getHitsPerPage($skel, $offset, $number, $date);
		} else
		{
			$pages = null;
		}
		$nrOfItems = count($pages);
	} else if ('404s' == $action)
	{
		$request404s = get404s($skel, $offset, $number);
		$nrOfItems = count($request404s);
	}

	$result .= '<p>' . dict($skel, 'logpageviews', $nrOfHits) . "</p>\n";

	$nav = '<p class="mininav">[ ';
	if ($offset > 0)
	{
		$nav .= '<a href="' . $skel['base_uri'] . 'page/viewlog/' . $action . '/">' . dict($skel, 'first') . '</a> | <a href="' . $skel['base_uri'] . 'page/viewlog/' . $action . '/' . (max($offset - $number, 0)) . '/">' . dict($skel, 'previous') . '</a> | ';
	} else
	{
		$nav .= dict($skel, 'first') . ' | ' . dict($skel, 'previous') . ' | ';
	}
	if ($offset + $number > $nrOfItems)
	{
		$nav .= dict($skel, 'next') . ' | ' . dict($skel, 'last');
	} else
	{
		$nav .= '<a href="' . $skel['base_uri'] . 'page/viewlog/' . $action . '/' . (min($offset + $number, $nrOfItems)) . '/">' . dict($skel, 'next') . '</a> | <a href="' . $skel['base_uri'] . 'page/viewlog/' . $action . '/' . ((intval($nrOfItems / $number) * $number)) . '/">' . dict($skel, 'last') . '</a>';
	}
	$nav .= " ]</p>\n";

	$result .= $nav;

	$odd = true;
	$rowclass = '';
	if ('hits' == $action)
	{
		$loggeditems = getLoggedItems($skel, $offset, $number);
		$result .= '<h2>' . dict($skel, 'loghits') . "</h2>\n";
		$result .= '<p>Showing items ' . ($offset + 1) . ' to ' . (min($offset + $number, $nrOfHits)) . ' out of ' . $nrOfHits . " total</p>\n";
		$result .= "<table>\n";
		if (0 == count($loggeditems))
		{
			$result .= "\t<tr><td>" . dict($skel, 'noitemsfound') . "</td></tr>\n";
		}
		foreach ($loggeditems as $logitem)
		{
			if ($odd)
			{
				$rowclass = ' class="odd"';
			} else
			{
				$rowclass = '';
			}
			$result .= "\t<tr" . $rowclass . '><td>' . $logitem['date'] . '</td><td>' . $logitem['ip'] . '</td><td>' . $logitem['section'] . '</td><td>' . $logitem['page'] . '</td><td>' . $logitem['request'] . "</td></tr>\n";
			$result .= "\t<tr" . $rowclass . '><td>' . $logitem['time'] . '</td><td>' . $logitem['statuscode'] . '</td><td colspan="3">' . $logitem['useragent'] . "</td></tr>\n";
			if ('' != trim($logitem['referer']))
			{
				$result .= "\t<tr" . $rowclass . '><td>&nbsp;</td><td colspan="4">' . $logitem['referer'] . "</td></tr>\n";
			}
			$odd = !$odd;
		}
		$result .= "</table>\n";
	} else if ('ips' == $action)
	{
		$result .= '<h2>' . dict($skel, 'logips') . "</h2>\n";
		$result .= '<p>' . dict($skel, 'loguniqueips', $nrOfItems) . "</p>\n";
		arsort($uniqueIPs);
		$uniqueIPs = array_slice($uniqueIPs, $offset, $number);
		$result .= '<p>Showing items ' . ($offset + 1) . ' to ' . (min($offset + $number, $nrOfItems)) . ' out of ' . $nrOfItems . " total</p>\n";
		$result .= "<table>\n";
		$result .= "\t<tr><th>IP</th><th>" . dict($skel, 'hostname') . '</th><th>' . dict($skel, 'loghits') . "</th></tr>\n";
		if (0 == count($uniqueIPs))
		{
			$result .= "\t<tr><td colspan=\"3\">" . dict($skel, 'noitemsfound') . "</td></tr>\n";
		}
		foreach ($uniqueIPs as $ip => $nr)
		{
			if ($odd)
			{
				$rowclass = ' class="odd"';
			} else
			{
				$rowclass = '';
			}
			$result .= "\t<tr" . $rowclass . '><td>' . $ip . '</td><td>' . gethostbyaddr($ip) . '</td><td>' . $nr . "</td></tr>\n";
			$odd = !$odd;
		}
		$result .= "</table>\n";
	} else if ('pages' == $action || 'hitsperdate' == $action)
	{
		$loggeditems = '';
		$result .= '<h2>' . dict($skel, 'logpages') . "</h2>\n";
		if ('hitsperdate' == $action)
		{
			$day_seconds = 24 * 60 * 60;
			$prev_day = toCleanDate(strtotime($date) - $day_seconds);
			$next_day = toCleanDate(strtotime($date) + $day_seconds);
			$result .= '<p class="mininav">[ <a href="' . $skel['base_uri'] . 'page/viewlog/' . $action . '/' . $prev_day . '/">' . parseDate($prev_day, '%Y%m%d') . '</a> | ' . $date . ' | <a href="' . $skel['base_uri'] . 'page/viewlog/' . $action . '/' . $next_day . '/">' . parseDate($next_day, '%Y%m%d') . '</a> ]</p>';
		}
		arsort($pages);
		$pages = array_slice($pages, $offset, $number);
		$result .= '<p>Showing items ' . ($offset + 1) . ' to ' . (min($offset + $number, $nrOfItems)) . ' out of ' . $nrOfItems . " total</p>\n";
		$result .= "<table>\n";
		$result .= "\t<tr><th>Page</th><th>" . dict($skel, 'loghits') . "</th></tr>\n";
		if (0 == count($pages))
		{
			$result .= "\t<tr><td colspan=\"2\">" . dict($skel, 'noitemsfound') . "</td></tr>\n";
		}
		foreach ($pages as $logpage => $nr)
		{
			if ($odd)
			{
				$rowclass = ' class="odd"';
			} else
			{
				$rowclass = '';
			}
			$result .= "\t<tr" . $rowclass . '><td>' . $logpage . '</td><td>' . $nr . "</td></tr>\n";
			$odd = !$odd;
		}
		$result .= "</table>\n";
	} else if ('404s' == $action)
	{
		$result .= '<h2>' . dict($skel, 'log404s') . "</h2>\n";
		arsort($request404s);
		$request404s = array_slice($request404s, $offset, $number);
		$result .= '<p>Showing items ' . ($offset + 1) . ' to ' . (min($offset + $number, $nrOfItems)) . ' out of ' . $nrOfItems . " total</p>\n";
		$result .= "<table>\n";
		$result .= "\t<tr><th>Request with 404</th><th>" . dict($skel, 'loghits') . "</th></tr>\n";
		if (0 == count($request404s))
		{
			$result .= "\t<tr><td colspan=\"2\">" . dict($skel, 'noitemsfound') . "</td></tr>\n";
		}
		foreach ($request404s as $logrequest => $nr)
		{
			if ($odd)
			{
				$rowclass = ' class="odd"';
			} else
			{
				$rowclass = '';
			}
			$result .= "\t<tr" . $rowclass . '><td>' . $logrequest . '</td><td>' . $nr . "</td></tr>\n";
			$odd = !$odd;
		}
		$result .= "</table>\n";
	}
	$result .= $nav;
	return $result;
}


function getItem($items, $which)
{
	$counter = 0;
	for ($i = 0; $i < count($items); $i++)
	{
		if ('' != trim($items[$i]))
		{
			$parts = explode('=', $items[$i]);
			if ($parts[0][0] != '#')	// '#' denotes comment
			{
				if ($counter == $which)
				{
					return trim($parts[0]);
				}
				$counter++;
			}
		}
	}
	return null;
}


/* Look up $key in dictionary */
function dict($skel, $key, $value = null)
{
	if (isset($skel["dict"][$key]))
	{
		if ($value != null)
		{
			return str_replace('%s', $value, $skel['dict'][$key]);
		} else
		{
			return $skel['dict'][$key];
		}
	} else
	{
		return 'DICTIONARY: key "' . $key . '" not found. Maybe not be translated?';
	}
}


/* Validate the language option from the url */
function validateLanguage($languagekey)
{
	/* @TODO: implement */
	return $languagekey;
}


function getLanguageKey($skel)
{
	if (true == $skel['multilanguage'])
	{
		return $skel['language'] . '/';
	} else
	{
		return '';
	}
}


/*
 * Check whether the current page is the site's homepage
 */
function isHomepage($skel)
{
	//if (isset($skel['home_page']) && isset($skel['home_section']) && $skel['home_page'] == $skel['page'] && $skel['home_section'] == $skel['section'])
	if ($skel['home_page'] == $skel['page'] && $skel['home_section'] == $skel['section'])
	{
		return true;
	} else
	{
		return false;
	}
}


/*
 * Remove all thumbnails for this gallery so they'll get regenerated next view
 * Used after updating a gallery
 */
function cleanThumbs($skel, $gallery)
{
	delfile($skel['base_dir'] . '/images/gallery/thumbs/'. $gallery . '_*.jpg');
}

?>
