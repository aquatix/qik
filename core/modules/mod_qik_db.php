<?php
/**
 * mod_qik_db.php - Module for getting the contents of the qik site -- database method
 *                  Used for abstrahizing the storage method, so you can transparantly switch between flat files and database
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


/*
 * Connect to the DB
 */
$skel["overload_db_link"] = mysql_connect($skel["overload_db_url"], $skel["overload_db_user"], $skel["overload_db_pass"])
or die("Could not connect to the database!\n");

if ( !$skel["overload_db_link"] )
{
        echo "ERROR: Didn't Connect to DB!";
} else
{
        mysql_select_db($skel["overload_db_name"], $skel["overload_db_link"]);
}


function doQuery($skel, $query)
{
        //echo $query . "\n";
        $result = mysql_query( $query, $skel["overload_db_link"] );
        //print_r($result);

        if (!$result)
        {
                echo "DB ERROR: " . mysql_error();
                //echo "\nquery was: '" . $query . "'\n\n";
                return null;
        }

        //if ( $result != false && mysql_num_rows( $result ) > 0 )
        if ( $result != false )
        {
                return $result;
        } else
        {
                return null;
        }
}

function getLastInsertID($skel)
{
        return mysql_insert_id($skel["overload_db_link"]);
}


function saveToLog($skel, $section, $page, $statuscode = 200)
{
}

?>
