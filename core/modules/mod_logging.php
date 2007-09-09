<?php
/**
 * mod_logging.php - Page hit logging support
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
