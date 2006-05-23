<?php
	/*
	 * Module for getting the contents of the qik site -- database method
	 * Used for abstrahizing the storage method, so you can transparantly switch between flat files and database
	 * version 0.1.01 2006-03-31
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