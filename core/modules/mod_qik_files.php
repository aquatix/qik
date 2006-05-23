<?php
/*
 * Module for getting the contents of the qik site -- flat files method
 * Used for abstrahizing the storage method, so you can transparantly switch between flat files and database
 * version 0.1.03 2006-04-03
 */

function getSections($skel)
{
	$sections = file('site/section.desc');
	if (false == $sections)
	{
		$sections = null;
	}
	return $sections;
}


function getSubsections($skel, $section)
{
	$subsections = file('site/sections/' . $section . '.desc');
	if (false == $subsections)
	{
		$subsections = null;
	}
	return $subsections;
}


function getFileContents($skel, $section, $page)
{
	$content = null;
	if (null != $page && '' != $page)
	{
		$content = file_get_contents('site/pages/' . $section . '_' . $page . '.html');
	} else
	{
		$content = file_get_contents('site/pages/' . $section . '.html');
	}
	return $content;
}


function getTile($skel, $key)
{
	return file_get_contents("site/tiles/" . $key . ".html");
}


function getFile($skel, $kind, $key)
{
	return file("site/" . $kind . "/" . $key . ".desc");
}


/*** Logging functions ***/


function saveToLog($skel, $section, $page, $statuscode = 200)
{
	$site_ip = getenv("REMOTE_ADDR");
	$useragent = getenv("HTTP_USER_AGENT");
	$time = date("d/m/Y - G:i:s", time());	//old style
	$time = date("Y-m-d G:i:s O", time());	//nice style
	$time = date("d/M/Y:G:i:s O", time());	//apache style
	$time = date("d/M/Y:G:i:sO", time());	//all-concatinated style
	$time = date("Y-m-d:G:i:sO", time());	//all-concatinated style 2

	$logstring = '';

	$section = str_replace(' ', '%20', $section);
	$page = str_replace(' ', '%20', $page);
	$referer = getenv("HTTP_REFERER");

	$logstring .= $site_ip . ' [' . $time . '] ' . $section . '/' . $page . ' ' . $statuscode . ' "' . getenv("REQUEST_URI") . '" "' . $useragent . '" "' . $referer . "\"\n";

	/* Als laatste pas in 1x schrijven naar file */
	$file = fopen($skel["logfile"], "a");

	fputs($file, $logstring);
	fclose($file);
}


function logGetNumberOfHits($skel)
{
	$logfile = fopen($skel['logfile'], 'r');

	$counter = 0;
	while (!feof($logfile))
	{
		$counter++;
		fgets($logfile);
	}
	fclose($logfile);
	return $counter--;
}


function logGetUniqueIPs($skel)
{
	$logfile = fopen($skel['logfile'], 'r');
	$result = array();

	$counter = 0;
	while (!feof($logfile))
	{
		$counter++;
		$line = fgets($logfile);
		$offset = strpos($line, ' ');
		$ip = substr($line, 0, $offset);
		if ('' != trim($ip) && !isset($result[$ip]))
		{
			$result[$ip] = 1;
		} else if ('' != trim($ip))
		{
			$result[$ip]++;
		}
	}
	fclose($logfile);
	return $result;
}


function logGetLoggedItems($skel, $offset, $number)
{
	$logfile = fopen($skel['logfile'], 'r');

	$result = array();

	$counter = 0;
	while (!feof($logfile) && $counter <= ($offset + $number))
	{
		$line = fgets($logfile);
		if ('' != trim($line) && $counter > $offset)
		{
			$parts = explode(' ', $line);
			$result[$counter]['ip'] = $parts[0];
			$result[$counter]['date'] = substr($parts[1], 1, 10);
			$result[$counter]['time'] = substr($parts[1], 12, 8);
			$section_page = explode('/', $parts[2]);
			if (isset($section_page[0]))
			{
				$result[$counter]['section'] = $section_page[0];
			} else
			{
				$result[$counter]['section'] = '';
			}
			if (isset($section_page[1]))
			{
				$result[$counter]['page'] = $section_page[1];
			} else
			{
				$result[$counter]['page'] = '';
			}
			$result[$counter]['statuscode'] = $parts[3];
			$result[$counter]['request'] = substr($parts[4], 1, strlen($parts[4]) - 2);

			$parts = array_slice($parts, 5, count($parts) - 5);
			$useragent_referer = implode(' ', $parts);
			preg_match("/\"(.[^\"]+)/", $useragent_referer, $match);
			$result[$counter]['useragent'] = $match[1];

			$useragent_referer = str_replace('"' . $match[1] . '"', '', $useragent_referer);
			preg_match("/\"(.[^\"]+)/", $useragent_referer, $match);
			$result[$counter]['referer'] = str_replace('"', '', $match[1]);
			$counter++;
		} else if ('' != trim($line))
		{
			$counter++;
		}
	}
	fclose($logfile);
	return $result;

}


function logGetHitsPerPage($skel, $offset, $number)
{
	$logfile = fopen($skel['logfile'], 'r');
	$result = array();

	$counter = 0;
	while (!feof($logfile))
	{
		$counter++;
		$line = fgets($logfile);
		$parts = explode(' ', $line);
		if (isset($parts[2]) && '' != trim($parts[2]) && !isset($result[$parts[2]]))
		{
			$result[$parts[2]] = 1;
		} else if (isset($parts[2]) && '' != trim($parts[2]))
		{
			$result[$parts[2]]++;
		}
	}
	fclose($logfile);
	return $result;
}


function logGet404s($skel)
{
	$logfile = fopen($skel['logfile'], 'r');
	$result = array();

	$counter = 0;
	while (!feof($logfile))
	{
		$counter++;
		$line = fgets($logfile);
		$parts = explode(' ', $line);
		if ('' != trim($line))
		{
		$request = substr($parts[4], 1, strlen($parts[4]) - 2);
		if ('404' == trim($parts[3]) && !isset($result[$request]))
		{
			$result[$request] = 1;
		} else if ('404' == trim($parts[3]))
		{
			$result[$request]++;
		}
		}
	}
	fclose($logfile);
	return $result;
}

?>
