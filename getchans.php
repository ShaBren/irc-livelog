<?php

$db = new SQLite3( 'log.sqlite' );

$results = $db->query( "SELECT DISTINCT channel FROM log" );

echo "<select id='channelselect'>";
echo "<option><option>";

while ( $row = $results->fetchArray() ) 
{
	$channel = $row[0];

	echo "<option value='$channel'>$channel</option>";
}

echo "</select>";

?>
