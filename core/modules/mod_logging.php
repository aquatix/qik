<?php
/*
 * v0.1.06 2006-05-25
 */

function getLogTypes($skel)
{
	return array(
			'pages=' . dict($skel, 'logpages') . "\n",
			'hitsperdate=' . dict($skel, 'loghitsperdate') . "\n",
			'ips=' . dict($skel, 'logips') . "\n",
			'hits=' . dict($skel, 'loghits') . "\n",
			'404s=' . dict($skel, 'log404s') . "\n"
		    );
}

function addToLog($skel, $section, $page, $statuscode = 200)
{
	saveToLog($skel, $section, $page, $statuscode);
}


function getLoggedItems($skel, $offset, $number)
{
	return logGetLoggedItems($skel, $offset, $number);
}


function getNumberOfHits($skel)
{
	return logGetNumberOfHits($skel);
}


function getUniqueIPs($skel, $action = 'pages', $offset = 0)
{
	/* Sanitize $action */
	if (null == $action || ('ips' != $action && 'hits' != $action && 'pages' != $action))
	{
		$action = 'pages';
	}
	return logGetUniqueIPs($skel, $action, $offset);
}


function getHitsPerPage($skel, $offset, $number, $date = null)
{
	return logGetHitsPerPage($skel, $offset, $number, $date);
}


function get404s($skel)
{
	return logGet404s($skel);
}

?>
