<?php

$db = new SQLite3( 'log.sqlite' );

if ( array_key_exists( 'time', $_GET ) and $_GET['time'] > 0 )
{
	$timeOffset = $_GET['time'];
}
else
{
	$timeOffset = 3600; // Default to an hour
}

if ( array_key_exists( 'channel', $_GET ) )
{
	$channel = $db->escapeString( $_GET['channel'] );
}
else
{
	$channel = "#ncsulug";
}

$time = time() - $timeOffset;

$results = $db->query( "SELECT * FROM log WHERE channel='$channel' AND time > $time" );

$i = 0;

while ( $row = $results->fetchArray() ) 
{
	$i++;
}

$results->reset();

echo "<table>\n";

if ( $i % 2 )
{
	echo "<tr></tr>\n";
}

while ( $row = $results->fetchArray() ) 
{
	$nick = $row[1];

	$nickColor = "nick" . ( ( ( crc32( $nick ) ) % 8 ) + 1 );

	$msg = htmlspecialchars( $row[2] );
	$time = strftime( "%T", $row[3] );
	$type = $row[4];

	if ( $type == 'PRIVMSG' )
	{
		echo "<tr><td class='time'>$time</td><td class='nick $nickColor'>&lt;$nick&gt;</td> <td class='message'>$msg</td></tr>\n";
	}
	else if ( $type == 'ACTION' )
	{
		echo "<tr><td class='time'>$time</td><td class='nick $nickColor action'>*$nick</td> <td class='message'>$msg</td></tr>\n";
	}
}
echo "</table>";
?>
