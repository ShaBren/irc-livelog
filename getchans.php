<?php
/*
	Copyright (c) 2012 Stephen Bryant
	For conditions of distribution and use, see copyright notice in LICENSE
*/

$db = new SQLite3( 'log.sqlite' );

$results = $db->query( "SELECT DISTINCT channel FROM log" );

echo "<select id='channelselect'>\n";
echo "<option></option>\n";

while ( $row = $results->fetchArray() ) 
{
	$channel = $row[0];

	echo "<option value='$channel'>$channel</option>\n";
}

echo "</select>";

?>
