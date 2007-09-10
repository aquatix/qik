<?php
/**
 * mod_toolkit.php - Useful functions for doing operations on text, converting items etc
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


function toUTF8($string)
{
	if (isUTF8($string))
	{
		/* Already UTF8 */
		return $string;
	}
	return utf8_encode($string);
}


/**
 * Returns a formatted date from a string based on a given format
 *
 * Supported formats
 *
 * %Y - year as a decimal number including the century
 * %m - month as a decimal number (range 1 to 12)
 * %d - day of the month as a decimal number (range 1 to 31)
 *
 * %H - hour as decimal number using a 24-hour clock (range 0 to 23)
 * %M - minute as decimal number
 * %s - second as decimal number
 * %u - microsec as decimal number
 * @param string date  string to convert to date
 * @param string format expected format of the original date
 * @return string rfc3339 w/o timezone YYYY-MM-DD YYYY-MM-DDThh:mm:ss YYYY-MM-DDThh:mm:ss.s
 *
 * Taken from a comment on php.net/strtotime
 */
function parseDate( $date, $format ) {
	// Builds up date pattern from the given $format, keeping delimiters in place.
	if( !preg_match_all( "/%([YmdHMsu])([^%])*/", $format, $formatTokens, PREG_SET_ORDER ) )
	{
		return false;
	}
	$datePattern = '';
	foreach( $formatTokens as $formatToken )
	{
		$delimiter = '';
		if (isset($formatToken[2]))
		{
			$delimiter = preg_quote( $formatToken[2], "/" );
		}
		if($formatToken[1] == 'Y')
		{
			$datePattern .= '(.{1,4})'.$delimiter;
		} elseif($formatToken[1] == 'u')
		{
			$datePattern .= '(.{1,5})'.$delimiter;
		} else
		{
			$datePattern .= '(.{1,2})'.$delimiter;
		} 
	}

	// Splits up the given $date
	if( !preg_match( "/".$datePattern."/", $date, $dateTokens) )
	{
		return false;
	}
	$dateSegments = array();
	for($i = 0; $i < count($formatTokens); $i++)
	{
		$dateSegments[$formatTokens[$i][1]] = $dateTokens[$i+1];
	}

	// Reformats the given $date into rfc3339

	if( $dateSegments["Y"] && $dateSegments["m"] && $dateSegments["d"] )
	{
		if( ! checkdate ( $dateSegments["m"], $dateSegments["d"], $dateSegments["Y"] )) { return false; }
		$dateReformated =
			str_pad($dateSegments["Y"], 4, '0', STR_PAD_LEFT)
			."-".str_pad($dateSegments["m"], 2, '0', STR_PAD_LEFT)
			."-".str_pad($dateSegments["d"], 2, '0', STR_PAD_LEFT);
	} else
	{
		return false;
	}
	if( isset($dateSegments["H"]) && $dateSegments["H"] && $dateSegments["M"] )
	{
		$dateReformated .=
			"T".str_pad($dateSegments["H"], 2, '0', STR_PAD_LEFT)
			.':'.str_pad($dateSegments["M"], 2, '0', STR_PAD_LEFT);

		if( $dateSegments["s"] )
		{
			$dateReformated .=
				":".str_pad($dateSegments["s"], 2, '0', STR_PAD_LEFT);
			if( $dateSegments["u"] )
			{
				$dateReformated .=
					'.'.str_pad($dateSegments["u"], 5, '0', STR_PAD_RIGHT);
			}
		}
	}

	return $dateReformated;
}


function toDateTime($string)
{
	return date("Y-m-d H:i:s", $string);
}


function toDate($string)
{
	return date("Y-m-d", $string);
}


function toCleanDate($string)
{
	return date("Ymd", $string);
}


/*** Helper functions ***/
/*
 * Validate Unicode UTF-8 Version 4
 * This function takes as reference the table 3.6 found at http://www.unicode.org/versions/Unicode4.0.0/ch03.pdf
 * It also flags overlong bytes as error
 */
function isUTF8($str)
{
	// values of -1 represent disalloweded values for the first bytes in current UTF-8
	static $trailing_bytes = array (
			0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0, 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
			0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0, 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
			0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0, 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
			0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0, 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
			-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1, -1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,
			-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1, -1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,
			-1,-1,1,1,1,1,1,1,1,1,1,1,1,1,1,1, 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,
			2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2, 3,3,3,3,3,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1
			);

	$ups = unpack('C*', $str);
	if (!($aCnt = count($ups))) return true; // Empty string *is* valid UTF-8
	for ($i = 1; $i <= $aCnt;)
	{
		if (!($tbytes = $trailing_bytes[($b1 = $ups[$i++])])) continue;
		if ($tbytes == -1) return false;

		$first = true;
		while ($tbytes > 0 && $i <= $aCnt)
		{
			$cbyte = $ups[$i++];
			if (($cbyte & 0xC0) != 0x80) return false;

			if ($first)
			{
				switch ($b1)
				{
					case 0xE0:
						if ($cbyte < 0xA0) return false;
						break;
					case 0xED:
						if ($cbyte > 0x9F) return false;
						break;
					case 0xF0:
						if ($cbyte < 0x90) return false;
						break;
					case 0xF4:
						if ($cbyte > 0x8F) return false;
						break;
					default:
						break;
				}
				$first = false;
			}
			$tbytes--;
		}
		if ($tbytes) return false; // incomplete sequence at EOS
	}
	return true;
}


/*
 * Get the current time in seconds.microseconds
 * lastmodified 2003-09-28
 */
function getmicrotime()
{
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}


/*
 * Remove an element from an array while keeping indices intact [instead of doing unset]
 */
function removeArrayElement(&$arr, $index)
{
	if(isset($arr[$index]))
	{
		array_splice($arr, $index, 1);
	}
}

function getURIs($text)
{
	$urls = '(http|file|ftp|https)';
	$ltrs = '\w';
	$gunk = '/#~:.?+=&%@!\-';
	$punc = '.:?\-';
	$any = "$ltrs$gunk$punc";
	preg_match_all("{
			\b
			$urls   :
			[$any] +?


			(?=
			 [$punc] *
			 [^$any]
			 |
			 $
			)
			}x", $text, $matches);
//printf("Output of URLs %d URLs<P>\n", sizeof($matches[0]));
//foreach ($matches[0] as $u) {
//$link = $PHP_SELF . '?url=' . urlencode($u);
//echo "<A HREF='$link'>$u</A><BR>\n";
return $matches[0];
}


function getURIs_new($html, $basehref)
{
	$uris = array();
	$uricounter = 0;

	//$html_lines = file($url);
	/*$html = "";
	  for ($i = 0; $i < count($text); $i++)
	  {
	  $html .= $text[$i];
	  }
	 */
	preg_match_all("|href=([^>]+)|i",$html,$matches);
	for ($i = 0; $i < count($matches[0]); $i++)
	{
		$parts = explode("\"", $matches[1][$i]);
		if ($parts[0] == "")
		{
			$uris[$uricounter] = $parts[1] . "\n";
			$uricounter++;
		} else
		{
			if ($matches[1][$i][0] == "'")
			{
				$parts = explode("'", $matches[1][$i]);
				$uris[$uricounter] = $parts[1];
				$uricounter++;
			} else
			{
				$uris[$uricounter] = $matches[1][$i] . "\n";
				$uricounter++;
			}
		}
	}

	if ($basehref != null)
	{
		$uripieces = parse_url($basehref);
		$hostname = $uripieces["host"];
		for ($i = 0; $i < count($uris); $i++)
		{
			/* Translage '/uri' to 'http://hostname/uri' and '#uri' to '$text . $uri' */
			if ($uris[$i][0] == "/")
			{
				$uris[$i] = $hostname . $uris[$i];
			} else if (substr($uris[$i], 0, 4) == "http" || substr($uris[$i], 0, 4) == "mail" || substr($uris[$i], 0, 4) == "ftp:")
			{
				//nothing
			} else
			{
				$uris[$i] = $basehref . $uris[$i];
			}
		}
	}
	return $uris;
}


function getFilteredURIs($text)
{
	getFilteredURIsWithBase($text, null);
}


function getFilteredURIsWithBase($text, $basehref)
{
	$uris = array_unique(getURIs_new($text, $basehref));

	$filter = array(
			0 => "http://www.google.com/ads_by_google.html",
			1 => "imageads.",
			2 => "pagead",
			3 => "http://www.w3.org/1999/xhtml",
			4 => "http://www.securityfocus.com/sponsor/"
		       );
	//4 => "http://www.securityfocus.com/sponsor/SPIDynamics_secpapers_050404"

	//$uris = array_flip($uris);
	/*
	   for ($i = 0; $i < count($filter); $i++)
	   {

	   if (isset($uris[$filter[$i]]))
	   {
	   unset($uris[$filter[$i]]);
	   }
	   }
	 */

	$found = false;
	$nrOfURIs = count($uris);
	for ($i = 0; $i < $nrOfURIs; $i++)
	{
		$found = false;

		if ($uris[$i] == "" || $uris[$i] == "http://" || $uris[$i] == "http:" || $uris[$i] == "http" || $uris[$i] == "https" || $uris[$i] == "https:" || $uris[$i] == "https://")
		{
			unset($uris[$i]);
			$found = true;
		}

		$j = 0;
		//for ($j = 0; $j < count($filter); $j++)
		while ($j < count($filter) && $found == false)
		{
			//if (stristr($uris[$i], $filter[$j]) != "")
			if (strpos($uris[$i], $filter[$j]) !== false)
			{
				unset($uris[$i]);
				$found = true;
			}
			$j++;
		}
	}

	//$uris = array_flip($uris);
	//print_r($uris);
	return array_merge($uris);
}


/*
 * Better integer-checker
 */
function myIsInt($x)
{
	return (is_numeric($x) ? intval($x) == $x : false);
}


/*
 * Return a value passed to the page, providing a fallback value
 */
function getRequestParam($paramname, $default=null)
{
	if (isset($_REQUEST[$paramname]))
	{
		if (myIsInt($default))
		{
			return intval($_REQUEST[$paramname]);
		} else
		{
			return $_REQUEST[$paramname];
		}
	} else
	{
		return $default;
	}
}


function isValidURI($uri)
{
	if( preg_match( '/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}'
				.'((:[0-9]{1,5})?\/.*)?$/i' ,$uri))
	{
		return true;
	}
	else
	{
		return false;
	}

	/*
	 * Beware - it only validates schemes http and https, and it only takes into account host and port part of the uri. It does not accept username and password.
	 * For an email validator you could look at http://gaarsmand.com/index.php/IT_l%F8sninger/Kode_eksempler/PHP_kode.
	 */
}


function getHTTPErrorCode($code)
{
	$httpcode[400] = "HTTP/1.0 400 Bad Request";
	$httpcode[401] = "HTTP/1.0 401 Unauthorized";
	$httpcode[403] = "HTTP/1.0 402 Payment Required";
	$httpcode[403] = "HTTP/1.0 403 Forbidden";
	$httpcode[404] = "HTTP/1.0 404 Not Found";
	$httpcode[405] = "HTTP/1.0 405 Method Not Allowed";
	$httpcode[406] = "HTTP/1.0 406 Not Acceptable";
	$httpcode[407] = "HTTP/1.0 407 Proxy Authentication Required";
	$httpcode[408] = "HTTP/1.0 408 Request Timeout";
	$httpcode[409] = "HTTP/1.0 409 Conflict";
	$httpcode[410] = "HTTP/1.0 410 Gone";
	$httpcode[411] = "HTTP/1.0 411 Length Required";
	$httpcode[412] = "HTTP/1.0 412 Precondition Failed";
	$httpcode[413] = "HTTP/1.0 413 Request Entity Too Large";
	$httpcode[414] = "HTTP/1.0 414 Request-URI Too Long";
	$httpcode[415] = "HTTP/1.0 415 Unsupported Media Type";
	$httpcode[416] = "HTTP/1.0 416 Requested Range Not Satisfiable";
	$httpcode[417] = "HTTP/1.0 417 Expectation Failed";
	/*
	   $httpcode[422] = array("_ERROR_422";
	   $httpcode[423] = array("_ERROR_423";
	   $httpcode[424] = array("_ERROR_424";
	 */
	$httpcode[500] = "HTTP/1.0 500 Internal Server Error";
	$httpcode[501] = "HTTP/1.0 501 Not Implemented";
	$httpcode[502] = "HTTP/1.0 502 Bad Gateway";
	$httpcode[503] = "HTTP/1.0 503 Service Unavailable";
	$httpcode[504] = "HTTP/1.0 504 Gateway Timeout";
	$httpcode[505] = "HTTP/1.0 505 HTTP Version Not Supported";
	/*
	   $httpcode[506] = 

	   $httpcode[600] = array("_ERROR_600";
	   $httpcode[601] = array("_ERROR_601";
	   $httpcode[602] = array("_ERROR_602";
	   $httpcode[603] = array("_ERROR_603";
	 */
	return $httpcode[$code];
}


/*** File handling routines ***/
function loadFile($filename)
{
	$lines = file($filename);
	$content = "";
	for ($i = 0; $i < count($lines); $i++)
	{
		$content .= $lines[$i];
	}
	return $content;
}

function saveFile($filename, $content)
{
	$file = fopen($filename, "w");
	fputs($file, $content);
	fclose($file);
}

function getFilename($path)
{
	$parts = explode("/", $path);
	return $parts[count($parts)-1];
}


/*
 * Create thumbnail from $o_file, and store it in $thumbfilename when set
 * Output it to stdout too
 *
 * Adapted from:
 * Source: http://nl2.php.net/getimagesize
 * webmaster at WWW.ELLESSEWEB.NET
 * 26-Oct-2005 02:10
 * This is a useful function to display a thumbnail of a whatever image.
 * This piece of code has been lightly modified from an example found on <b>NYPHP.ORG</B>.
 * This function can build a thumbnail of any size you want and display it on your browser!
 * Hope it can be useful for you guys!
 */
/*function makeThumbnail($o_file, $t_ht = 100, $thumbfilename = null) {*/
function makeThumbnail($o_file, $t_max = 100, $thumbfilename = null) {
	$image_info = getImageSize($o_file) ; // see EXIF for faster way

	switch ($image_info['mime'])
	{
		case 'image/gif':
			if (imagetypes() & IMG_GIF)
			{ // not the same as IMAGETYPE
				$o_im = imageCreateFromGIF($o_file) ;
			} else
			{
				$ermsg = 'GIF images are not supported<br />';
			}
			break;
		case 'image/jpeg':
			if (imagetypes() & IMG_JPG)
			{
				$o_im = imageCreateFromJPEG($o_file) ;
			} else
			{
				$ermsg = 'JPEG images are not supported<br />';
			}
			break;
		case 'image/png':
			if (imagetypes() & IMG_PNG)
			{
				$o_im = imageCreateFromPNG($o_file) ;
			} else
			{
				$ermsg = 'PNG images are not supported<br />';
			}
			break;
		case 'image/wbmp':
			if (imagetypes() & IMG_WBMP)
			{
				$o_im = imageCreateFromWBMP($o_file) ;
			} else
			{
				$ermsg = 'WBMP images are not supported<br />';
			}
			break;
		default:
			$ermsg = $image_info['mime'].' images are not supported<br />';
			break;
	}

	if (!isset($ermsg)) {
		$o_wd = imagesx($o_im) ;
		$o_ht = imagesy($o_im) ;
		$t_wd = $t_max;
		$t_ht = $t_max;
		if ($o_wd > $o_ht)
		{
			// thumbnail height = original height * target / original width
			$t_ht = round($o_ht * $t_wd / $o_wd);
		} else
		{
			// thumbnail width = original width * target / original height
			$t_wd = round($o_wd * $t_ht / $o_ht) ;
		}

		$t_im = imageCreateTrueColor($t_wd,$t_ht);

		imageCopyResampled($t_im, $o_im, 0, 0, 0, 0, $t_wd, $t_ht, $o_wd, $o_ht);

		/* Safe to file if requested */
		if (null != $thumbfilename)
		{
			//imageJPEG($t_im, $thumbfilename, 100);
			imageJPEG($t_im, $thumbfilename, 80);
		}

		imageJPEG($t_im);

		imageDestroy($o_im);
		imageDestroy($t_im);
	}
	return isset($ermsg)?$ermsg:NULL;
}


/*
 * Delete file, or files if a wildcard is used
 */
function delfile($str)
{
	foreach(glob($str) as $fn)
	{
		unlink($fn);
	}
} 

?>
